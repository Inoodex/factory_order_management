<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Budget::with(['creator', 'chartOfAccount']);

        if ($search = $request->get('search')) {
            $query->whereHas('chartOfAccount', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($period = $request->get('period')) {
            $query->where('period', $period);
        }

        $budgets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $accounts = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        return view('admin.budgets.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateBudget($request);

        Budget::create($validated);

        return redirect()
            ->route('admin.budgets.index')
            ->with('success', 'Budget allocated successfully.');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('*accountant');
        $accounts = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        return view('admin.budgets.edit', compact('budget', 'accounts'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('*accountant');

        $validated = $this->validateBudget($request);

        $budget->update($validated);

        return redirect()
            ->route('admin.budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('*accountant');

        return $this->safeDelete($budget, 'admin.budgets.index', [], 'Budget deleted successfully.');
    }

    private function validateBudget(Request $request): array
    {
        return $request->validate([
            'chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', Rule::in(['monthly', 'yearly'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
