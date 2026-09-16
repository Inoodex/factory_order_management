<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_type',
        'chart_of_account_id',
        'provider_name',
        'account_number',
        'branch_name',
        'opening_balance',
        'status',
        'created_by',
        'notes',
    ];

    /**
     * Computed attribute: remaining_balance calculated from journal entries
     */
    public function getRemainingBalanceAttribute(): float
    {
        $totalCredit = $this->journalEntryItems()->sum('credit');
        $totalDebit = $this->journalEntryItems()->sum('debit');
        return ($this->opening_balance ?? 0) + $totalDebit - $totalCredit;
    }

    /**
     * Journal entry items linked to this account
     */
    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class, 'chart_of_account_id', 'chart_of_account_id');
    }

    protected static function booted()
    {
        static::creating(function ($account) {
            if (auth()->check() && !$account->created_by) {
                $account->created_by = auth()->id();
            }

            if (empty($account->chart_of_account_id)) {
                $coa = ChartOfAccount::create([
                    'code' => '1' . str_pad(OfficeAccount::max('id') + 1, 4, '0', STR_PAD_LEFT),
                    'name' => $account->account_name,
                    'type' => 'asset',
                    'is_active' => $account->status === 'active',
                ]);
                $account->chart_of_account_id = $coa->id;
            }
        });

        static::updating(function ($account) {
            if ($account->isDirty('account_name') && $account->chartOfAccount) {
                $account->chartOfAccount->update(['name' => $account->account_name]);
            }
            if ($account->isDirty('status') && $account->chartOfAccount) {
                $account->chartOfAccount->update(['is_active' => $account->status === 'active']);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    /**
     * Relationship for incoming transactions (Credits)
     */
    public function incomingTransactions()
    {
        return $this->journalEntryItems()->where('journal_entry_items.credit', '>', 0);
    }

    /**
     * Relationship for outgoing transactions (Debits)
     */
    public function outgoingTransactions()
    {
        return $this->journalEntryItems()->where('journal_entry_items.debit', '>', 0);
    }

    public function incomingJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'period_id')
            ->whereHas('items', function ($query) {
                $query->where('credit', '>', 0);
            });
    }

    public function outgoingJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'period_id')
            ->whereHas('items', function ($query) {
                $query->where('debit', '>', 0);
            });
    }
}
