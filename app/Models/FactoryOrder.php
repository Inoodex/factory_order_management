<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FactoryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id',
        'etd_price',
        'sub_price',
        'aetd_date',
        'fob_price',
    ];

    protected $casts = [
        'aetd_date' => 'date',
        'etd_price' => 'decimal:2',
        'sub_price' => 'decimal:2',
        'fob_price' => 'decimal:2',
    ];

    public function customerOrder(): BelongsTo
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function followup(): HasOne
    {
        return $this->hasOne(FactoryFollowup::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->aetd_date) {
            return false;
        }
        return $this->aetd_date->isPast();
    }
}
