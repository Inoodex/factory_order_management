<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankReconciliation;
use App\Models\BankReconciliationItem;
use App\Models\OfficeAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $reconciliations = BankReconciliation::with(['account', 'closedBy'])
            ->orderBy('statement_date', 'desc')
            ->paginate(15);
        return view('admin.accounts.bank-reconciliations.index', compact('reconciliations'));
    }

    public function create()
    {
        $accounts = OfficeAccount::where('status', 'active')->get();
        return view('admin.accounts.bank-reconciliations.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:office_accounts,id',
            'statement_date' => 'required|date',
            'statement_balance' => 'required|numeric',
        ]);

        $account = OfficeAccount::find($request->account_id);

        // System balance is opening balance + sum of all matched credits - sum of all matched debits
        // Initially we set it to current balance as a reference
        $systemBalance = $account->remaining_balance;

        $reconciliation = BankReconciliation::create([
            'account_id' => $request->account_id,
            'statement_date' => $request->statement_date,
            'statement_balance' => $request->statement_balance,
            'system_balance' => $systemBalance,
            'difference' => $request->statement_balance - $systemBalance,
            'status' => 'draft',
        ]);

        return redirect()->route('admin.bank-reconciliations.show', $reconciliation);
    }

    public function show(BankReconciliation $reconciliation)
    {
        $reconciliation->load(['account.chartOfAccount', 'items.journalEntryItem.journalEntry']);

        // Find all unreconciled transactions for this account's ledger
        $unreconciledItems = JournalEntryItem::where('chart_of_account_id', $reconciliation->account->chart_of_account_id)
            ->whereDoesntHave('reconciliationItems', function ($query) {
                $query->whereHas('reconciliation', function ($q) {
                    $q->where('status', 'closed');
                });
            })
            ->whereDoesntHave('reconciliationItems', function ($query) use ($reconciliation) {
                $query->where('reconciliation_id', $reconciliation->id);
            })
            ->with('journalEntry')
            ->orderBy('journal_entry_id', 'desc')
            ->get();

        return view('admin.accounts.bank-reconciliations.show', compact('reconciliation', 'unreconciledItems'));
    }

    public function matchItem(Request $request, BankReconciliation $reconciliation)
    {
        if ($reconciliation->status === 'closed') {
            return response()->json(['success' => false, 'message' => 'Reconciliation is closed.'], 403);
        }

        $item = JournalEntryItem::findOrFail($request->journal_entry_item_id);

        DB::transaction(function () use ($reconciliation, $item) {
            BankReconciliationItem::create([
                'reconciliation_id' => $reconciliation->id,
                'journal_entry_item_id' => $item->id,
                'amount' => $item->credit - $item->debit, // Net impact on bank
                'type' => 'matched',
                'matched_at' => now(),
                'matched_by' => Auth::id(),
            ]);

            $this->updateBalances($reconciliation);
        });

        return response()->json(['success' => true]);
    }

    public function unmatchItem(Request $request, BankReconciliation $reconciliation)
    {
        if ($reconciliation->status === 'closed') {
            return response()->json(['success' => false, 'message' => 'Reconciliation is closed.'], 403);
        }

        $recItem = BankReconciliationItem::where('reconciliation_id', $reconciliation->id)
            ->where('journal_entry_item_id', $request->journal_entry_item_id)
            ->firstOrFail();

        DB::transaction(function () use ($reconciliation, $recItem) {
            $recItem->delete();
            $this->updateBalances($reconciliation);
        });

        return response()->json(['success' => true]);
    }

    public function close(BankReconciliation $reconciliation)
    {
        if ($reconciliation->status === 'closed') {
            return redirect()->back()->with('error', 'Already closed.');
        }

        // Logic to finalize
        $reconciliation->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.bank-reconciliations.index')->with('success', 'Bank reconciliation closed successfully.');
    }

    private function updateBalances(BankReconciliation $reconciliation)
    {
        // Re-calculate system balance based on matched items
        // In a real accounting system, this would be: 
        // Opening Balance (at start of period) + Sum of matched items
        // For simplicity here, we assume opening balance of account is the base.

        $matchedSum = $reconciliation->items()->sum('amount');
        $accountOpeningBalance = $reconciliation->account->opening_balance ?? 0;

        // We also need to add all PREVIOUSLY CLOSED reconciliation items for this account
        $previouslyClosedSum = BankReconciliationItem::whereHas('reconciliation', function ($q) use ($reconciliation) {
            $q->where('account_id', $reconciliation->account_id)
                ->where('status', 'closed')
                ->where('id', '!=', $reconciliation->id);
        })->sum('amount');

        $systemBalance = $accountOpeningBalance + $previouslyClosedSum + $matchedSum;

        $reconciliation->update([
            'system_balance' => $systemBalance,
            'difference' => $reconciliation->statement_balance - $systemBalance,
        ]);
    }

    public function destroy(BankReconciliation $reconciliation)
    {
        if ($reconciliation->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot delete a closed reconciliation.');
        }

        $reconciliation->delete();
        return redirect()->route('admin.bank-reconciliations.index')->with('success', 'Draft reconciliation deleted.');
    }
}
