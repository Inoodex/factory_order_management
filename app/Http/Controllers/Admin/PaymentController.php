<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{CustomerOrder, Invoice, OfficeAccount, Payment, Setting, User, JournalEntry, JournalEntryItem, AccountingPeriod, ChartOfAccount};
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Payment::with(['customerOrder.customer', 'collector', 'account', 'invoice']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('customerOrder', function ($oq) use ($search) {
                        $oq->where('order_no', 'like', "%{$search}%")
                            ->orWhere('style_no', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($cq) use ($search) {
                                $cq->where('name', 'like', "%{$search}%")
                                    ->orWhere('brand', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($type = $request->get('payment_type')) {
            $query->where('payment_type', $type);
        }

        if ($status = $request->get('payment_status')) {
            $query->where('payment_status', $status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $this->authorize('*accountant');

        $customerOrders = CustomerOrder::with(['customer', 'supplier'])->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $selected_order_id = $request->get('customer_order_id');
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.create', compact('customerOrders', 'users', 'selected_order_id', 'accounts'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        try {
            DB::beginTransaction();

            $payment = Payment::create($validated);

            // Double-Entry Ledger Posting
            if ($validated['payment_status'] === 'completed') {
                $officeAccount = OfficeAccount::find($payment->office_account_id);
                $period = AccountingPeriod::where('is_closed', false)
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->first();

                if ($officeAccount && $officeAccount->chart_of_account_id && $period) {
                    $orderNo = $payment->customerOrder ? $payment->customerOrder->order_no : 'N/A';
                    $entry = JournalEntry::create([
                        'period_id' => $period->id,
                        'date' => $payment->payment_date ?? now(),
                        'reference_number' => 'JV-PAY-' . $payment->receipt_number,
                        'note' => 'Automated post for Order Payment: ' . $payment->receipt_number . ($orderNo !== 'N/A' ? " (Order #{$orderNo})" : ''),
                        'status' => 'posted',
                        'created_by' => Auth::id(),
                    ]);

                    // Debit: Cash/Bank Account (Asset increases)
                    JournalEntryItem::create([
                        'journal_entry_id' => $entry->id,
                        'chart_of_account_id' => $officeAccount->chart_of_account_id,
                        'debit' => $payment->amount,
                        'credit' => 0,
                        'description' => 'Receipt via ' . $officeAccount->account_name,
                    ]);

                    // Credit: Revenue Account or Accounts Receivable
                    $revenueCoa = ChartOfAccount::where('type', 'revenue')->first() 
                        ?? ChartOfAccount::first();

                    if ($revenueCoa) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $entry->id,
                            'chart_of_account_id' => $revenueCoa->id,
                            'debit' => 0,
                            'credit' => $payment->amount,
                            'description' => 'Payment received for Order: ' . $orderNo,
                        ]);
                    }

                    $payment->journal_entry_id = $entry->id;
                    $payment->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Creation failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Payment $payment)
    {
        $this->authorize('*accountant');
        $payment->load(['customerOrder.customer', 'customerOrder.supplier', 'collector', 'account', 'invoice', 'journalEntry.items.chartOfAccount']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('*accountant');

        $customerOrders = CustomerOrder::with(['customer', 'supplier'])->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.edit', compact('payment', 'customerOrders', 'users', 'accounts'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        try {
            DB::beginTransaction();

            $payment->update($validated);

            // Double-Entry Ledger Sync
            if ($payment->journal_entry_id) {
                $entry = JournalEntry::find($payment->journal_entry_id);
                if ($entry) {
                    if ($payment->payment_status === 'completed') {
                        $officeAccount = OfficeAccount::find($payment->office_account_id);
                        $orderNo = $payment->customerOrder ? $payment->customerOrder->order_no : 'N/A';

                        $entry->items()->delete();

                        if ($officeAccount && $officeAccount->chart_of_account_id) {
                            JournalEntryItem::create([
                                'journal_entry_id' => $entry->id,
                                'chart_of_account_id' => $officeAccount->chart_of_account_id,
                                'debit' => $payment->amount,
                                'credit' => 0,
                                'description' => 'Receipt via ' . $officeAccount->account_name,
                            ]);
                        }

                        $revenueCoa = ChartOfAccount::where('type', 'revenue')->first() ?? ChartOfAccount::first();
                        if ($revenueCoa) {
                            JournalEntryItem::create([
                                'journal_entry_id' => $entry->id,
                                'chart_of_account_id' => $revenueCoa->id,
                                'debit' => 0,
                                'credit' => $payment->amount,
                                'description' => 'Payment received for Order: ' . $orderNo,
                            ]);
                        }
                    } else {
                        // If changed back to pending, void the journal entry
                        $entry->update(['status' => 'void']);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('*accountant');

        try {
            DB::beginTransaction();

            if ($payment->journal_entry_id) {
                $entry = JournalEntry::find($payment->journal_entry_id);
                if ($entry) {
                    $entry->items()->delete();
                    $entry->delete();
                }
            }

            $payment->delete();

            DB::commit();
            return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    public function downloadInvoice(Payment $payment)
    {
        $this->authorize('*accountant');

        $payment->load(['customerOrder.customer', 'customerOrder.supplier', 'collector', 'account', 'invoice.payments', 'invoice.items']);
        $settings = Setting::pluck('value', 'key')->all();

        $totalPaid = $payment->invoice ? $payment->invoice->payments->sum('amount') : $payment->amount;
        $remainingBalance = $payment->invoice ? max(0, $payment->invoice->total_amount - $totalPaid) : 0;

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);
        $mpdf->WriteHTML(view('admin.payments.invoice', compact('payment', 'settings', 'remainingBalance', 'totalPaid'))->render());

        $filename = 'Invoice_' . ($payment->receipt_number ?: $payment->id) . '.pdf';

        return $mpdf->Output($filename, 'D');
    }

    private function validatePayment(Request $request): array
    {
        return $request->validate([
            'customer_order_id' => ['nullable', 'exists:customer_orders,id'],
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', Rule::in(['advance', 'partial', 'final'])],
            'payment_date' => ['nullable', 'date'],
            'receipt_number' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', Rule::in(['pending', 'completed'])],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    public function getOrderBalance(Request $request)
    {
        $orderId = $request->get('customer_order_id') ?? $request->get('application_id');
        $order = CustomerOrder::with('payments')->find($orderId);

        if (!$order) {
            return response()->json(['total_fee' => 0, 'total_paid' => 0, 'balance' => 0]);
        }

        $totalPaid = $order->payments()
            ->whereIn('payment_status', ['pending', 'completed'])
            ->sum('amount');

        $totalPrice = (float) ($order->total_price ?: ($order->price * $order->color_qty));

        return response()->json([
            'total_fee' => $totalPrice,
            'total_paid' => $totalPaid,
            'balance' => max(0, $totalPrice - $totalPaid)
        ]);
    }

    public function getOrderInvoices(Request $request)
    {
        $orderId = $request->get('customer_order_id') ?? $request->get('application_id');
        $invoices = Invoice::where('customer_order_id', $orderId)
            ->with('payments')
            ->get()
            ->map(function ($invoice) {
                $totalPaid = $invoice->payments->where('payment_status', 'completed')->sum('amount');
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => (float) $invoice->total_amount,
                    'total_paid' => (float) $totalPaid,
                ];
            });

        return response()->json(['invoices' => $invoices]);
    }

    public function getApplicationBalance(Request $request)
    {
        return $this->getOrderBalance($request);
    }

    public function getApplicationInvoices(Request $request)
    {
        return $this->getOrderInvoices($request);
    }
}
