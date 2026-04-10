<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_ar', 'name_en', 'name_fr', 'slug', 'description_ar', 'description_en', 'description_fr',
        'price', 'original_price', 'discount_percent', 'stock',
        'category_id', 'images', 'specifications',
        'is_active', 'is_featured', 'deal_ends_at',
    ];

    protected $casts = [
        'images'         => 'array',
        'specifications' => 'array',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'deal_ends_at'   => 'datetime',
        'price'          => 'decimal:2',
        'original_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Helper: average rating
    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->where('approved', true)->avg('rating') ?? 0, 1);
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') return $this->name_ar;
        if ($locale === 'fr') return $this->name_fr ?? $this->name_en;
        return $this->name_en;
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') return $this->description_ar ?? '';
        if ($locale === 'fr') return $this->description_fr ?? $this->description_en ?? '';
        return $this->description_en ?? '';
    }

    // Scope: active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
