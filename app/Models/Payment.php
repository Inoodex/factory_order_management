<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id',
        'invoice_id',
        'amount',
        'payment_type',
        'payment_date',
        'collected_by',
        'receipt_number',
        'payment_status',
        'office_account_id',
        'journal_entry_id',
        'notes',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (!$payment->payment_date) {
                $payment->payment_date = now();
            }
            if (auth()->check() && !$payment->collected_by) {
                $payment->collected_by = auth()->id();
            }
            if (!$payment->receipt_number) {
                $lastPayment = static::whereDate('created_at', today())->latest('id')->first();
                $nextNumber = $lastPayment ? ((int) substr($lastPayment->receipt_number, -4)) + 1 : 1;
                $payment->receipt_number = 'REC-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saved(function ($payment) {
            $payment->syncJournalEntry();
        });

        static::deleted(function ($payment) {
            if ($payment->journal_entry_id) {
                $entry = JournalEntry::find($payment->journal_entry_id);
                if ($entry) {
                    $entry->items()->delete();
                    $entry->delete();
                }
            }
        });
    }

    /**
     * Automatically create or update corresponding double-entry Journal Voucher
     */
    public function syncJournalEntry(): ?JournalEntry
    {
        if ((float) $this->amount > 0) {
            $paymentDate = $this->payment_date ? \Carbon\Carbon::parse($this->payment_date) : now();

            // 1. Resolve Accounting Period (Find open or create monthly)
            $period = AccountingPeriod::where('status', 'open')
                ->whereDate('start_date', '<=', $paymentDate->toDateString())
                ->whereDate('end_date', '>=', $paymentDate->toDateString())
                ->first();

            if (!$period) {
                $period = AccountingPeriod::where('status', 'open')->latest('id')->first();
            }

            if (!$period) {
                $period = AccountingPeriod::create([
                    'name' => $paymentDate->format('F Y'),
                    'type' => 'monthly',
                    'start_date' => $paymentDate->copy()->startOfMonth()->toDateString(),
                    'end_date' => $paymentDate->copy()->endOfMonth()->toDateString(),
                    'status' => 'open',
                    'remarks' => 'Auto-created for transactions in ' . $paymentDate->format('F Y'),
                ]);
            }

            // 2. Resolve Debit Account (Cash / Bank Asset)
            $debitCoaId = null;
            $officeAccount = $this->office_account_id ? OfficeAccount::find($this->office_account_id) : null;
            if ($officeAccount && $officeAccount->chart_of_account_id) {
                $debitCoaId = $officeAccount->chart_of_account_id;
            } else {
                $defaultAsset = ChartOfAccount::where('type', 'asset')
                    ->where(function ($q) {
                        $q->where('name', 'like', '%cash%')->orWhere('name', 'like', '%bank%');
                    })->first() 
                    ?? ChartOfAccount::where('type', 'asset')->first() 
                    ?? ChartOfAccount::first();
                $debitCoaId = $defaultAsset?->id;
            }

            // 3. Resolve Credit Account (Sales Revenue / Order Income)
            $creditCoa = ChartOfAccount::where('type', 'revenue')
                ->where(function ($q) {
                    $q->where('name', 'like', '%sales%')->orWhere('name', 'like', '%revenue%')->orWhere('name', 'like', '%order%');
                })->first() 
                ?? ChartOfAccount::where('type', 'revenue')->first() 
                ?? ChartOfAccount::first();
            $creditCoaId = $creditCoa?->id;

            if (!$debitCoaId || !$creditCoaId) {
                return null;
            }

            $order = $this->customerOrder ?? ($this->invoice?->customerOrder);
            $orderNo = $order ? $order->order_no : 'N/A';
            $buyerName = $order?->customer?->name ?? 'Direct Customer';
            $referenceNumber = 'JV-PAY-' . ($this->receipt_number ?: $this->id);

            // Entry status: completed -> posted, pending -> draft, others -> void
            $entryStatus = match($this->payment_status) {
                'completed' => 'posted',
                'pending' => 'draft',
                default => 'void',
            };

            // 4. Create or retrieve JournalEntry
            $entry = null;
            if ($this->journal_entry_id) {
                $entry = JournalEntry::find($this->journal_entry_id);
            }

            if (!$entry) {
                $entry = JournalEntry::where('reference_number', $referenceNumber)->first();
            }

            $entryData = [
                'period_id' => $period->id,
                'date' => $paymentDate->toDateString(),
                'reference_number' => $referenceNumber,
                'note' => 'Auto-posted payment receipt ' . $this->receipt_number . ' from ' . $buyerName . ($orderNo !== 'N/A' ? " (Order #{$orderNo})" : ''),
                'status' => $entryStatus,
                'created_by' => $this->collected_by ?? (auth()->id() ?? User::first()?->id),
            ];

            if ($entry) {
                $entry->update($entryData);
                $entry->items()->delete();
            } else {
                $entry = JournalEntry::create($entryData);
            }

            // 5. Post Balanced Debit & Credit Items
            $accountLabel = $officeAccount ? $officeAccount->account_name : 'Cash / Bank';

            // Debit Asset (Cash / Bank account balance increases)
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $debitCoaId,
                'debit' => (float) $this->amount,
                'credit' => 0.00,
                'description' => 'Receipt via ' . $accountLabel . ' - ' . $buyerName,
            ]);

            // Credit Revenue (Income recognized)
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_of_account_id' => $creditCoaId,
                'debit' => 0.00,
                'credit' => (float) $this->amount,
                'description' => 'Payment received from ' . $buyerName . ($orderNo !== 'N/A' ? " (Order: {$orderNo})" : ''),
            ]);

            // 6. Link to Payment quietly
            if ($this->journal_entry_id !== $entry->id) {
                $this->journal_entry_id = $entry->id;
                $this->saveQuietly();
            }

            return $entry;
        }

        return null;
    }

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function account()
    {
        return $this->belongsTo(OfficeAccount::class, 'office_account_id');
    }

    public function officeAccount()
    {
        return $this->belongsTo(OfficeAccount::class, 'office_account_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
