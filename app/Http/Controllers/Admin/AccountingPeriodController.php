<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = AccountingPeriod::orderBy('start_date', 'desc')->get();
        return view('admin.accounts.periods.index', compact('periods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('*accountant');
        return view('admin.accounts.periods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fiscal_year,monthly,quarterly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable|string',
        ]);

        // Check if period with same name already exists
        $exists = AccountingPeriod::where('name', $validated['name'])->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'An accounting period with this name already exists.'])
                ->withInput();
        }

        AccountingPeriod::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'open',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Accounting period created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountingPeriod $period)
    {
        // Check if this is a status-only update (from inline form)
        if ($request->has('status') && !$request->has('name')) {
            $validated = $request->validate([
                'status' => ['required', Rule::in(['open', 'closed'])],
            ]);

            if ($validated['status'] === 'closed') {
                $period->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'closed_by' => auth()->id(),
                ]);
            } else {
                $period->update([
                    'status' => 'open',
                    'closed_at' => null,
                    'closed_by' => null,
                ]);
            }

            return redirect()->back()->with('success', 'Accounting period status updated.');
        }

        // Full update from edit form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('accounting_periods')->ignore($period->id)],
            'type' => 'required|in:fiscal_year,monthly,quarterly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable|string',
        ]);

        $period->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('admin.accounting-periods.index')->with('success', 'Accounting period updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountingPeriod $period)
    {
        $this->authorize('*accountant');
        return view('admin.accounts.periods.edit', compact('period'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountingPeriod $period)
    {
        // Future: Add check for linked journal entries to prevent deletion of active history
        $period->delete();

        return redirect()->back()->with('success', 'Accounting period removed.');
    }
}
