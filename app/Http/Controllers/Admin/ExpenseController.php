<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Expense, ChartOfAccount, OfficeAccount, Salary};
use Mpdf\Mpdf;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Expense::with(['creator', 'chartOfAccount']);

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        // Category filter (using chart_of_account)
        if ($category = $request->get('category')) {
            $query->whereHas('chartOfAccount', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Date range filter
        if ($startDate = $request->get('start_date')) {
            $query->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('expense_date', '<=', $endDate);
        }

        $expenses = $query->latest()->paginate(15)->withQueryString();

        // Fetch expense accounts
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();
        $pendings_salaries = Salary::whereIn('payment_status', ['pending', 'partial'])->get();

        return view('admin.expenses.create', compact('categories', 'accounts', 'pendings_salaries'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense = Expense::create($validated);

        // Update salary payment status if linked
        if ($expense->salary_id) {
            $salary = Salary::find($expense->salary_id);
            if ($salary) {
                $totalPaid = Expense::where('salary_id', $expense->salary_id)->sum('amount');
                $salary->paid_amount = $totalPaid;

                if ($totalPaid >= $salary->net_salary) {
                    $salary->payment_status = 'paid';
                } elseif ($totalPaid > 0) {
                    $salary->payment_status = 'partial';
                }
                $salary->save();
            }
        }

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('*accountant');
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.expenses.edit', compact('expense', 'categories', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense->update($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('*accountant');

        return $this->safeDelete($expense, 'admin.expenses.index', [], 'Expense deleted successfully.');
    }

    public function downloadPdf(Expense $expense)
    {
        $this->authorize('*accountant');

        $expense->load(['creator', 'chartOfAccount', 'account']);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        $html = view('admin.expenses.pdf', compact('expense'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="expense-' . $expense->id . '.pdf"');
    }

    // public function report(Request $request)
    // {
    //     $this->authorize('*accountant');

    //     $query = Expense::with(['creator', 'chartOfAccount']);

    //     // Apply same filters as index
    //     if ($search = $request->get('search')) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('description', 'like', "%{$search}%")
    //                 ->orWhere('payment_method', 'like', "%{$search}%");
    //         });
    //     }

    //     if ($category = $request->get('category')) {
    //         $query->whereHas('chartOfAccount', function ($q) use ($category) {
    //             $q->where('name', 'like', "%{$category}%");
    //         });
    //     }

    //     if ($startDate = $request->get('start_date')) {
    //         $query->whereDate('expense_date', '>=', $startDate);
    //     }
    //     if ($endDate = $request->get('end_date')) {
    //         $query->whereDate('expense_date', '<=', $endDate);
    //     }

    //     $expenses = $query->orderBy('expense_date', 'desc')->get();
    //     $totalAmount = $expenses->sum('amount');

    //     $pdf = Pdf::loadView('admin.expenses.report', compact('expenses', 'totalAmount', 'request'));
    //     return $pdf->download('expenses-report-' . now()->format('Y-m-d') . '.pdf');
    // }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'salary_id' => ['nullable', 'exists:salaries,id'],
            'notes' => ['nullable', 'string'],
        ]);
    }

       public function preview(Request $request)
    {
        $this->authorize('*accountant');

        $query = Expense::with(['creator', 'chartOfAccount']);

        // Apply same filters as index
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->whereHas('chartOfAccount', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        if ($startDate = $request->get('start_date')) {
            $query->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('expense_date', '<=', $endDate);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        $totalAmount = $expenses->sum('amount');

        // Generate PDF using mPDF for preview
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        $html = view('admin.expenses.report', compact('expenses', 'totalAmount', 'request'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="expenses-report-preview.pdf"');
    }

    public function download(Request $request)
    {
        $this->authorize('*accountant');

        $query = Expense::with(['creator', 'chartOfAccount']);

        // Apply same filters as index
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->whereHas('chartOfAccount', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        if ($startDate = $request->get('start_date')) {
            $query->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('expense_date', '<=', $endDate);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        $totalAmount = $expenses->sum('amount');

        // Generate PDF using mPDF for download
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        $html = view('admin.expenses.report', compact('expenses', 'totalAmount', 'request'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="expenses-report-' . now()->format('Y-m-d') . '.pdf"');
    }
}
