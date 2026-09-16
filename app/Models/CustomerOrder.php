<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'supplier_id',
        'order_no',
        'style_no',
        'style_name',
        'style_image',
        'composition',
        'color_name',
        'color_qty',
        'order_date',
        'etd_date',
        'price',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'etd_date' => 'date',
        'price' => 'decimal:2',
        'color_qty' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function (CustomerOrder $order) {
            // Automatically initialize matching factory order and followup if not present
            if (!$order->factoryOrder()->exists()) {
                $factoryOrder = FactoryOrder::create([
                    'customer_order_id' => $order->id,
                    'etd_price' => $order->price,
                    'sub_price' => null,
                    'aetd_date' => $order->etd_date,
                    'fob_price' => null,
                ]);

                FactoryFollowup::create([
                    'factory_order_id' => $factoryOrder->id,
                    'pps_comments_status' => FactoryFollowup::STATUS_PENDING,
                    'shs_comments_status' => FactoryFollowup::STATUS_PENDING,
                    'knitting_status' => FactoryFollowup::STATUS_NOT_STARTED,
                    'dyeing_status' => FactoryFollowup::STATUS_NOT_STARTED,
                    'cutting_status' => FactoryFollowup::STATUS_NOT_STARTED,
                ]);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function factoryOrder(): HasOne
    {
        return $this->hasOne(FactoryOrder::class);
    }

    public function factoryFollowup(): HasOneThrough
    {
        return $this->hasOneThrough(FactoryFollowup::class, FactoryOrder::class, 'customer_order_id', 'factory_order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) ($this->color_qty * $this->price);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->etd_date) {
            return false;
        }
        return $this->etd_date->isPast();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->style_image) {
            return null;
        }
        return asset('storage/' . $this->style_image);
    }
}
