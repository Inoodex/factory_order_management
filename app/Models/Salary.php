<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_name',
        'month',
        'basic_salary',
        'overtime_amount',
        'bonus',
        'allowances',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'other_deductions',
        'net_salary',
        'paid_amount',
        'payment_status',
        'payment_date',
        'payment_method',
        'account_number',
        'bank_name',
        'bank_branch',
        'routing_number',
        'transaction_id',
        'journal_entry_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'month' => 'string',
        'basic_salary' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($salary) {
            // Calculate gross salary
            $salary->gross_salary = $salary->basic_salary
                + $salary->overtime_amount
                + $salary->bonus
                + $salary->allowances;

            // Calculate net salary
            $totalDeductions = $salary->tax_deduction
                + $salary->insurance_deduction
                + $salary->other_deductions;

            $salary->net_salary = $salary->gross_salary - $totalDeductions;

            // If payment status is paid, set paid amount = net salary
            if ($salary->payment_status === 'paid' && !$salary->paid_amount) {
                $salary->paid_amount = $salary->net_salary;
            }

            if (auth()->check() && !$salary->created_by) {
                $salary->created_by = auth()->id();
            }
        });

        static::updating(function ($salary) {
            // Recalculate gross salary
            $salary->gross_salary = $salary->basic_salary
                + $salary->overtime_amount
                + $salary->bonus
                + $salary->allowances;

            // Recalculate net salary
            $totalDeductions = $salary->tax_deduction
                + $salary->insurance_deduction
                + $salary->other_deductions;

            $salary->net_salary = $salary->gross_salary - $totalDeductions;

            // Update payment status based on paid amount
            if ($salary->paid_amount > 0) {
                if ($salary->paid_amount >= $salary->net_salary) {
                    $salary->payment_status = 'paid';
                    $salary->paid_amount = $salary->net_salary;
                } else {
                    $salary->payment_status = 'partial';
                }
            } else {
                $salary->payment_status = 'pending';
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->payment_status) {
            'paid' => 'success',
            'partial' => 'warning',
            'pending' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'paid' => 'Paid',
            'partial' => 'Partial',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
