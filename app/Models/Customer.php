<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'session',
        'email',
        'phone',
        'address',
    ];

    public function customerOrders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }
}
