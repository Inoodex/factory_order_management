<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reconciliation_id',
        'journal_entry_item_id',
        'bank_statement_ref',
        'amount',
        'type',
        'matched_at',
        'matched_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'matched_at' => 'datetime',
    ];

    public function reconciliation(): BelongsTo
    {
        return $this->belongsTo(BankReconciliation::class, 'reconciliation_id');
    }

    public function journalEntryItem(): BelongsTo
    {
        return $this->belongsTo(JournalEntryItem::class, 'journal_entry_item_id');
    }

    public function matchedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_by');
    }
}
