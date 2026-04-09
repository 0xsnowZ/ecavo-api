<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'value', 'max_uses',
        'used_count', 'min_order_amount', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
        'value'            => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    public function isValid(float $orderAmount = 0): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($orderAmount < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->discount_type === 'percent') {
            return round($amount * $this->value / 100, 2);
        }
        return min($this->value, $amount);
    }
}
