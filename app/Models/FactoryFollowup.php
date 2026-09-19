<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class FactoryFollowup extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_NOT_STARTED = 'Not Started';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_DELAYED = 'Delayed';

    public const PPS_STATUSES = [
        'Pending',
        'Submitted',
        'Approved',
        'Approved with Comments',
        'Rejected',
        'Revised',
    ];

    public const SHS_STATUSES = [
        'Pending',
        'Sent',
        'Approved',
        'Rejected',
    ];

    public const PRODUCTION_STATUSES = [
        'Not Started',
        'In Progress',
        'Completed',
        'Delayed',
    ];

    protected $fillable = [
        'factory_order_id',
        'pps_date',
        'pps_comments_status',
        'shs_sending_date',
        'shs_comments_status',
        'knitting_status',
        'dyeing_status',
        'cutting_status',
        'fob_price',
        'sub_price',
    ];

    protected $casts = [
        'pps_date' => 'date',
        'shs_sending_date' => 'date',
        'fob_price' => 'decimal:2',
        'sub_price' => 'decimal:2',
    ];

    public function factoryOrder(): BelongsTo
    {
        return $this->belongsTo(FactoryOrder::class);
    }

    public function customerOrder(): HasOneThrough
    {
        return $this->hasOneThrough(
            CustomerOrder::class,
            FactoryOrder::class,
            'id', // Foreign key on factory_orders table
            'id', // Foreign key on customer_orders table
            'factory_order_id', // Local key on factory_followups table
            'customer_order_id' // Local key on factory_orders table
        );
    }

    public function getCurrentStageAttribute(): string
    {
        if ($this->cutting_status === self::STATUS_COMPLETED || $this->shs_comments_status === 'Approved') {
            return 'completed';
        }
        if ($this->cutting_status === self::STATUS_IN_PROGRESS || $this->dyeing_status === self::STATUS_COMPLETED) {
            return 'cutting';
        }
        if ($this->dyeing_status === self::STATUS_IN_PROGRESS || $this->knitting_status === self::STATUS_COMPLETED) {
            return 'dyeing';
        }
        if ($this->knitting_status === self::STATUS_IN_PROGRESS || str_contains($this->pps_comments_status ?? '', 'Approved')) {
            return 'knitting';
        }
        return 'sampling';
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->cutting_status === self::STATUS_COMPLETED || $this->shs_comments_status === 'Approved') {
            return 100;
        }
        $score = 10;
        if (str_contains($this->pps_comments_status ?? '', 'Approved')) {
            $score += 20;
        } elseif (($this->pps_comments_status ?? '') === 'Submitted') {
            $score += 10;
        }

        if ($this->knitting_status === self::STATUS_COMPLETED) {
            $score += 20;
        } elseif ($this->knitting_status === self::STATUS_IN_PROGRESS) {
            $score += 10;
        }

        if ($this->dyeing_status === self::STATUS_COMPLETED) {
            $score += 20;
        } elseif ($this->dyeing_status === self::STATUS_IN_PROGRESS) {
            $score += 10;
        }

        if ($this->cutting_status === self::STATUS_COMPLETED) {
            $score += 20;
        } elseif ($this->cutting_status === self::STATUS_IN_PROGRESS) {
            $score += 10;
        }

        return min(95, $score);
    }
}

