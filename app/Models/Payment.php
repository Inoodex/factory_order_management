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
