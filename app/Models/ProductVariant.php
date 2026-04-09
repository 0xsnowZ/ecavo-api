<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'attribute', 'value', 'extra_price', 'stock'];

    protected $casts = ['extra_price' => 'decimal:2'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
