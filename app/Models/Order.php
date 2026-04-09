<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    const STATUSES = [
        'placed', 'preparing', 'awaiting_shipment',
        'shipped', 'in_transit', 'delivered',
        'no_answer', 'postponed', 'wrong_address',
        'cancelled', 'returned',
    ];

    protected $fillable = [
        'user_id', 'address_id', 'status',
        'subtotal', 'delivery_fee', 'discount', 'total',
        'coupon_code', 'notes',
        'guest_name', 'guest_phone', 'guest_address',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount'     => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
