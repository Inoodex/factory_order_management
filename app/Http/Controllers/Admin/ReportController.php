<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\JournalEntry;
use App\Models\Budget;
use App\Models\Setting;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;

class ReportController extends Controller
{

    public function summary(Request $request)
    {
        $startDateParam = $request->get('start_date');
        $endDateParam = $request->get('end_date');

        if ($startDateParam && $endDateParam) {
            $startDate = Carbon::parse($startDateParam)->startOfDay();
            $endDate = Carbon::parse($endDateParam)->endOfDay();
            $month = $startDate->format('m');
            $year = $startDate->format('Y');
        } else {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        }

        $accountId = $request->get('account_id');
        $transactionType = $request->get('transaction_type'); // 'income', 'expense', 'transfer'

        $accounts = \App\Models\OfficeAccount::orderBy('account_name')->get();

        $expensesQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        $paymentsQuery = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('payment_status', ['pending', 'completed']);
        // Transfers are now tracked via journal_entries
        $transfersQuery = JournalEntry::whereBetween('date', [$startDate, $endDate])
            ->where('note', 'like', '%transfer%');

        // Filter by Account
        if ($accountId) {
            $expensesQuery->where('office_account_id', $accountId);
            $paymentsQuery->where('office_account_id', $accountId);
            // Simplified: transfer filtering by account requires complex joins
        }

        // Filter by Transaction Type
        if ($transactionType) {
            if ($transactionType !== 'expense') {
                $expensesQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'income') {
                $paymentsQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'transfer') {
                $transfersQuery->whereRaw('1 = 0');
            }
        }

        $expenses = $expensesQuery->get();
        $payments = $paymentsQuery->get();
        $transfers = $transfersQuery->get();

        $budgets = Budget::where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->get();

        $summary = [
            'total_income' => $payments->sum('amount'),
            'total_expense' => $expenses->sum('amount'),
            'total_transfer' => 0, // Would need to calculate from journal entries
            'income_count' => $payments->count(),
            'expense_count' => $expenses->count(),
            'transfer_count' => $transfers->count(),
            'by_category' => $expenses->groupBy('chart_of_account_id')->map->sum('amount'),
            'by_method' => $expenses->groupBy('payment_method')->map->sum('amount'),
        ];

        return view('admin.reports.summary', compact('summary', 'month', 'year', 'expenses', 'transfers', 'budgets', 'payments', 'accounts', 'accountId', 'transactionType', 'startDate', 'endDate'));
    }

    public function balanceSheet(Request $request)
    {

        $asOfDate = $request->get('as_of_date', date('Y-m-d'));
        $fromDate = $request->get('from_date');
        $endDate = Carbon::parse($asOfDate)->endOfDay();

        // Fetch all accounts with their debit/credit sums within the date range
        $accounts = \App\Models\ChartOfAccount::withSum([
            'journalEntryItems as total_debit' => function ($query) use ($fromDate, $endDate) {
                $query->whereHas('journalEntry', function ($q) use ($fromDate, $endDate) {
                    $q->where('date', '<=', $endDate);
                    if ($fromDate) {
                        $q->where('date', '>=', $fromDate);
                    }
                });
            }
        ], 'debit')
            ->withSum([
                'journalEntryItems as total_credit' => function ($query) use ($fromDate, $endDate) {
                    $query->whereHas('journalEntry', function ($q) use ($fromDate, $endDate) {
                        $q->where('date', '<=', $endDate);
                        if ($fromDate) {
                            $q->where('date', '>=', $fromDate);
                        }
                    });
                }
            ], 'credit')
            ->get();

        // Group by type and calculate balances
        $grouped = $accounts->groupBy('type');

        $data = [
            'asset' => collect(),
            'liability' => collect(),
            'equity' => collect(),
            'revenue' => collect(),
            'expense' => collect(),
        ];

        foreach ($grouped as $type => $typeAccounts) {
            foreach ($typeAccounts as $account) {
                $balance = 0;
                $debit = $account->total_debit ?? 0;
                $credit = $account->total_credit ?? 0;

                if ($type === 'asset') {
                    $balance = $debit - $credit;
                } else {
                    // liability, equity, revenue
                    $balance = $credit - $debit;
                }

                // If expense, balance is usually Dr - Cr, but we treat it as a reduction of profit
                if ($type === 'expense') {
                    $balance = $debit - $credit;
                }

                if ($balance != 0 || $account->is_default) {
                    $account->balance = $balance;
                    $data[$type]->push($account);
                }
            }
        }

        // Calculate Net Profit/Loss for Retained Earnings
        $totalRevenue = $data['revenue']->sum('balance');
        $totalExpenses = $data['expense']->sum('balance');
        $netProfit = $totalRevenue - $totalExpenses;

        $totalAssets = $data['asset']->sum('balance');
        $totalLiabilities = $data['liability']->sum('balance');
        $totalEquity = $data['equity']->sum('balance');

        return view('admin.reports.balance_sheet', compact(
            'data',
            'asOfDate',
            'fromDate',
            'netProfit',
            'totalAssets',
            'totalLiabilities',
            'totalEquity'
        ));
    }

    public function balanceSheetPdf(Request $request)
    {
        $asOfDate = $request->get('as_of_date', date('Y-m-d'));
        $fromDate = $request->get('from_date');
        $date = Carbon::parse($asOfDate)->endOfDay();

        // Fetch all accounts with their debit/credit sums within the range
        $accounts = \App\Models\ChartOfAccount::withSum([
            'journalEntryItems as total_debit' => function ($query) use ($fromDate, $date) {
                $query->whereHas('journalEntry', function ($q) use ($fromDate, $date) {
                    $q->where('date', '<=', $date);
                    if ($fromDate) {
                        $q->where('date', '>=', $fromDate);
                    }
                });
            }
        ], 'debit')
            ->withSum([
                'journalEntryItems as total_credit' => function ($query) use ($fromDate, $date) {
                    $query->whereHas('journalEntry', function ($q) use ($fromDate, $date) {
                        $q->where('date', '<=', $date);
                        if ($fromDate) {
                            $q->where('date', '>=', $fromDate);
                        }
                    });
                }
            ], 'credit')
            ->get();

        // Group by type and calculate balances
        $grouped = $accounts->groupBy('type');

        $data = [
            'asset' => collect(),
            'liability' => collect(),
            'equity' => collect(),
            'revenue' => collect(),
            'expense' => collect(),
        ];

        foreach ($grouped as $type => $typeAccounts) {
            foreach ($typeAccounts as $account) {
                $balance = 0;
                $debit = $account->total_debit ?? 0;
                $credit = $account->total_credit ?? 0;

                if ($type === 'asset') {
                    $balance = $debit - $credit;
                } else {
                    $balance = $credit - $debit;
                }

                if ($type === 'expense') {
                    $balance = $debit - $credit;
                }

                if ($balance != 0 || $account->is_default) {
                    $account->balance = $balance;
                    $data[$type]->push($account);
                }
            }
        }

        $totalRevenue = $data['revenue']->sum('balance');
        $totalExpenses = $data['expense']->sum('balance');
        $netProfit = $totalRevenue - $totalExpenses;

        $totalAssets = $data['asset']->sum('balance');
        $totalLiabilities = $data['liability']->sum('balance');
        $totalEquity = $data['equity']->sum('balance');

        // Calculate difference for balance check
        $diff = abs($totalAssets - ($totalLiabilities + $totalEquity + $netProfit));
        $isBalanced = $diff < 0.01;

        // Get company info from settings
        $settings = Setting::pluck('value', 'key')->all();
        $companyInfo = (object)[
            'name' => $settings['company_name'] ?? config('app.name', 'Your Company'),
            'address' => $settings['company_address'] ?? '',
            'phone' => $settings['company_phone'] ?? '',
            'email' => $settings['company_email'] ?? '',
        ];

        // Generate PDF using mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        $html = view('admin.reports.balance_sheet_pdf', compact(
            'data',
            'asOfDate',
            'netProfit',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'isBalanced',
            'diff',
            'companyInfo'
        ))->render();

        $mpdf->WriteHTML($html);

        $filename = 'Balance_Sheet_' . str_replace('-', '_', $asOfDate) . '.pdf';

        // Match journal-entry behavior: output=preview|download (preview by default).
        $isDownload = $request->get('output') === 'download';
        $disposition = $isDownload ? 'attachment' : 'inline';

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', $disposition . '; filename="' . $filename . '"');
    }

    public function downloadPdf(Request $request)
    {
        $startDateParam = $request->get('start_date');
        $endDateParam = $request->get('end_date');

        if ($startDateParam && $endDateParam) {
            $startDate = Carbon::parse($startDateParam)->startOfDay();
            $endDate = Carbon::parse($endDateParam)->endOfDay();
            $reportDate = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        } else {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $reportDate = $startDate->format('F Y');
        }

        $accountId = $request->get('account_id');
        $transactionType = $request->get('transaction_type');

        $expensesQuery = Expense::with(['creator', 'chartOfAccount'])->whereBetween('expense_date', [$startDate, $endDate]);
        $paymentsQuery = Payment::with('student')->whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('payment_status', ['pending', 'completed']);
        // Transfers are now tracked via journal_entries
        $transfersQuery = JournalEntry::with(['period', 'creator', 'items.chartOfAccount'])->whereBetween('date', [$startDate, $endDate])
            ->where('note', 'like', '%transfer%');

        // Filter by Account
        if ($accountId) {
            $expensesQuery->where('office_account_id', $accountId);
            $paymentsQuery->where('office_account_id', $accountId);
            // Simplified: transfer filtering by account requires complex joins
        }

        // Filter by Transaction Type
        if ($transactionType) {
            if ($transactionType !== 'expense') {
                $expensesQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'income') {
                $paymentsQuery->whereRaw('1 = 0');
            }
            if ($transactionType !== 'transfer') {
                $transfersQuery->whereRaw('1 = 0');
            }
        }

        $expenses = $expensesQuery->get();
        $payments = $paymentsQuery->get();
        $transfers = $transfersQuery->get();

        $settings = Setting::pluck('value', 'key')->all();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        $html = view('admin.reports.pdf', compact('expenses', 'payments', 'transfers', 'settings', 'reportDate'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Financial_Report_' . str_replace(' ', '_', $reportDate) . '.pdf"');
    }
}
