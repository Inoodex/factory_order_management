<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Expense, ChartOfAccount, OfficeAccount, Salary, User};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{

    public function index(Request $request)
    {
        $query = Salary::with(['user', 'creator']);
        $selectedMonth = $request->get('month');
        if (blank($selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('month', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('payment_status', $status);
        }

        $query->where('month', $selectedMonth);

        $salaries = $query->latest()->paginate(15)->withQueryString();

        // Get summary stats
        $stats = [
            'total_salaries' => Salary::sum('net_salary'),
            'total_paid' => Salary::where('payment_status', 'paid')->sum('paid_amount'),
            'total_pending' => Salary::where('payment_status', 'pending')->sum('net_salary'),
            'total_partial' => Salary::where('payment_status', 'partial')->sum(DB::raw('net_salary - paid_amount')),
        ];

        return view('admin.salaries.index', compact('salaries', 'stats', 'selectedMonth'));
    }

    public function create()
    {

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('name')->get(['id', 'name', 'email']);

        // Generate default month (current month)
        $defaultMonth = now()->format('Y-m');

        return view('admin.salaries.create', compact('users', 'defaultMonth'));
    }

    public function store(Request $request)
    {

        $validated = $this->validateSalary($request);

        Salary::create($validated);

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', 'Salary record created successfully.');
    }

    public function show(Salary $salary)
    {

        $salary->load(['user', 'creator']);

        return view('admin.salaries.show', compact('salary'));
    }

    public function edit(Salary $salary)
    {

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.salaries.edit', compact('salary', 'users'));
    }

    public function update(Request $request, Salary $salary)
    {

        $validated = $this->validateSalary($request);

        $salary->update($validated);

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', 'Salary record updated successfully.');
    }

    public function destroy(Salary $salary)
    {
        return $this->safeDelete($salary, 'admin.salaries.index', [], 'Salary record deleted successfully.');
    }

    public function markAsPaid(Request $request, Salary $salary)
    {

        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $salary->update([
            'payment_status' => 'paid',
            'paid_amount' => $salary->net_salary,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'notes' => $validated['notes'] ?? $salary->notes,
        ]);

        return redirect()
            ->route('admin.salaries.show', $salary->id)
            ->with('success', 'Salary marked as paid.');
    }

    public function generate(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        // 1. Check if salaries already exist for this specific month
        $existingSalaries = Salary::where('month', $month)->orderBy('id', 'asc')->get();

        // 2. If NO salaries exist for this month, pull from the most recent month instead
        if ($existingSalaries->isEmpty()) {
            $latestMonth = Salary::latest('month')->value('month');
            if ($latestMonth) {
                $existingSalaries = Salary::where('month', $latestMonth)->get();
                // Clear the IDs so they are treated as NEW records for the current month
                foreach ($existingSalaries as $s) {
                    $s->id = null;
                }
            }
        }

        $salaries = [];
        foreach ($existingSalaries as $salary) {
            $salaries[] = [
                'id' => $salary->id, // Will be null if pulled from previous month
                'user_id' => $salary->user_id,
                'name' => $salary->employee_name,
                'designation' => $salary->designation ?? 'Employee',
                'basic_salary' => (float) $salary->basic_salary,
                'bonus' => (float) $salary->bonus,
                'deduction' => (float) ($salary->tax_deduction + $salary->insurance_deduction + $salary->other_deductions),
                'net_salary' => (float) $salary->net_salary,
                'status' => $salary->payment_status,
                'account_number' => $salary->account_number,
                'bank_name' => $salary->bank_name,
                'bank_branch' => $salary->bank_branch,
                'routing_number' => $salary->routing_number,
            ];
        }

        return view('admin.salaries.generate', compact('salaries', 'month'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'salaries' => ['required', 'array'],
            'salaries.*.id' => ['nullable', 'exists:salaries,id'],
            'salaries.*.user_id' => ['nullable', 'exists:users,id'],
            'salaries.*.employee_name' => ['required', 'string', 'max:255'],
            'salaries.*.basic_salary' => ['required', 'numeric', 'min:0'],
            'salaries.*.bonus' => ['nullable', 'numeric', 'min:0'],
            'salaries.*.deduction' => ['nullable', 'numeric', 'min:0'],
            'salaries.*.account_number' => ['nullable', 'string', 'max:255'],
            'salaries.*.bank_name' => ['nullable', 'string', 'max:255'],
            'salaries.*.bank_branch' => ['nullable', 'string', 'max:255'],
            'salaries.*.routing_number' => ['nullable', 'string', 'max:255'],
        ]);

        $created = 0;
        $updated = 0;
        foreach ($validated['salaries'] as $salaryData) {
            if (!empty($salaryData['id'])) {
                $salary = Salary::find($salaryData['id']);
                if ($salary) {
                    $salary->update([
                        'employee_name' => $salaryData['employee_name'],
                        'basic_salary' => $salaryData['basic_salary'],
                        'bonus' => $salaryData['bonus'] ?? 0,
                        'tax_deduction' => $salaryData['deduction'] ?? 0,
                        'account_number' => $salaryData['account_number'] ?? null,
                        'bank_name' => $salaryData['bank_name'] ?? null,
                        'bank_branch' => $salaryData['bank_branch'] ?? null,
                        'routing_number' => $salaryData['routing_number'] ?? null,
                    ]);
                    $updated++;
                }
                continue;
            }

            if (!empty($salaryData['user_id'])) {
                $existing = Salary::where('user_id', $salaryData['user_id'])
                    ->where('month', $validated['month'])
                    ->first();

                if ($existing) {
                    continue;
                }
            }

            Salary::create([
                'user_id' => $salaryData['user_id'] ?? null,
                'employee_name' => $salaryData['employee_name'],
                'month' => $validated['month'],
                'basic_salary' => $salaryData['basic_salary'],
                'bonus' => $salaryData['bonus'] ?? 0,
                'overtime_amount' => 0,
                'allowances' => 0,
                'tax_deduction' => $salaryData['deduction'] ?? 0,
                'insurance_deduction' => 0,
                'other_deductions' => 0,
                'payment_status' => 'pending',
                'account_number' => $salaryData['account_number'] ?? null,
                'bank_name' => $salaryData['bank_name'] ?? null,
                'bank_branch' => $salaryData['bank_branch'] ?? null,
                'routing_number' => $salaryData['routing_number'] ?? null,
            ]);
            $created++;
        }

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', "{$created} salary records created and {$updated} updated successfully.");
    }

    public function bulkUpdateBasicSalary(Request $request)
    {

        $validated = $request->validate([
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'employees' => ['required', 'array'],
            'employees.*.user_id' => ['required', 'exists:users,id'],
            'employees.*.basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        $updated = 0;
        foreach ($validated['employees'] as $employeeData) {
            $employee = User::find($employeeData['user_id']);

            Salary::updateOrCreate(
                [
                    'user_id' => $employeeData['user_id'],
                    'month' => $validated['month'],
                ],
                [
                    'employee_name' => $employee?->name ?? 'Employee',
                    'basic_salary' => $employeeData['basic_salary'],
                ]
            );
            $updated++;
        }

        return redirect()
            ->back()
            ->with('success', "{$updated} employee basic salaries updated successfully.");
    }

    public function bulkUpdateAccountDetails(Request $request)
    {

        $validated = $request->validate([
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'employees' => ['required', 'array'],
            'employees.*.user_id' => ['required', 'exists:users,id'],
            'employees.*.account_number' => ['nullable', 'string', 'max:255'],
            'employees.*.bank_name' => ['nullable', 'string', 'max:255'],
            'employees.*.bank_branch' => ['nullable', 'string', 'max:255'],
            'employees.*.routing_number' => ['nullable', 'string', 'max:255'],
        ]);

        $updated = 0;
        foreach ($validated['employees'] as $employeeData) {
            $employee = User::find($employeeData['user_id']);

            Salary::updateOrCreate(
                [
                    'user_id' => $employeeData['user_id'],
                    'month' => $validated['month'],
                ],
                [
                    'employee_name' => $employee?->name ?? 'Employee',
                    'basic_salary' => Salary::where('user_id', $employeeData['user_id'])
                        ->latest('month')
                        ->value('basic_salary') ?? 0,
                    'account_number' => $employeeData['account_number'],
                    'bank_name' => $employeeData['bank_name'],
                    'bank_branch' => $employeeData['bank_branch'],
                    'routing_number' => $employeeData['routing_number'],
                ]
            );
            $updated++;
        }

        return redirect()
            ->back()
            ->with('success', "{$updated} employee account details updated successfully.");
    }

    public function getEmployeeDetails(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $salary = Salary::where('user_id', $request->user_id)
            ->latest('month')
            ->latest('id')
            ->first();
        $user = User::find($request->user_id);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'account_number' => $salary?->account_number,
            'bank_name' => $salary?->bank_name,
            'bank_branch' => $salary?->bank_branch,
            'routing_number' => $salary?->routing_number,
        ]);
    }

    public function checkExistingSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string',
        ]);

        $existing = Salary::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->first();

        if ($existing) {
            return response()->json([
                'exists' => true,
                'salary' => $existing,
                'message' => 'Salary record already exists for this employee and month.',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function bulkPayForm(Request $request)
    {

        $salaryIds = $request->get('salary_ids', []);

        // Convert comma-separated string to array if needed
        if (is_string($salaryIds) && !empty($salaryIds)) {
            $salaryIds = array_filter(array_map('intval', explode(',', trim($salaryIds))));
        } elseif (is_array($salaryIds)) {
            $salaryIds = array_filter(array_map('intval', $salaryIds));
        } else {
            $salaryIds = [];
        }

        if (empty($salaryIds)) {
            return redirect()->route('admin.salaries.index')->with('error', 'Please select at least one salary.');
        }

        $salaries = Salary::whereIn('id', $salaryIds)
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get();

        if ($salaries->isEmpty()) {
            return redirect()->route('admin.salaries.index')->with('error', 'No pending or partial salaries selected.');
        }

        $totalAmount = $salaries->sum(function ($salary) {
            return $salary->net_salary - $salary->paid_amount;
        });
        $accounts = OfficeAccount::where('status', 'active')->get();
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();

        return view('admin.salaries.bulk-pay', compact('salaries', 'totalAmount', 'accounts', 'categories'));
    }

    public function bulkPay(Request $request)
    {

        $validated = $request->validate([
            'salary_ids' => ['required', 'array'],
            'salary_ids.*' => ['exists:salaries,id'],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $salaries = Salary::whereIn('id', $validated['salary_ids'])
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get();

        if ($salaries->isEmpty()) {
            return back()->with('error', 'No valid salaries found.');
        }

        DB::beginTransaction();
        try {
            foreach ($salaries as $salary) {
                $remainingAmount = $salary->net_salary - $salary->paid_amount;

                // Create expense for salary payment
                $expense = Expense::create([
                    'description' => 'Salary Payment - ' . $salary->employee_name . ' (' . $salary->month . ')',
                    'amount' => $remainingAmount,
                    'expense_date' => $validated['payment_date'],
                    'chart_of_account_id' => $validated['chart_of_account_id'],
                    'payment_method' => $validated['payment_method'],
                    'office_account_id' => $validated['office_account_id'],
                    'salary_id' => $salary->id,
                    'created_by' => auth()->id(),
                    'notes' => $validated['notes'],
                ]);

                // Update salary status
                $salary->paid_amount = $salary->net_salary;
                $salary->payment_status = 'paid';
                $salary->payment_date = $validated['payment_date'];
                $salary->payment_method = $validated['payment_method'];
                $salary->save();
            }

            DB::commit();
            return redirect()->route('admin.salaries.index')->with('success', 'Bulk salary payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing bulk payment: ' . $e->getMessage());
        }
    }

    private function validateSalary(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'employee_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'overtime_amount' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'tax_deduction' => ['nullable', 'numeric', 'min:0'],
            'insurance_deduction' => ['nullable', 'numeric', 'min:0'],
            'other_deductions' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:pending,partial,paid'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'routing_number' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    public function exportExcel(Request $request)
    {

        $month = $request->get('month', now()->format('Y-m'));

        return Excel::download(
            new \App\Exports\SalaryExport($month),
            "salaries-{$month}.xlsx"
        );
    }

    public function exportPdf(Request $request)
    {

        $month = $request->get('month', now()->format('Y-m'));

        $salaries = Salary::with('user')
            ->where('month', $month)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.salaries.pdf', compact('salaries', 'month'));

        return $pdf->download("salaries-{$month}.pdf");
    }
}
