<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'name_fr', 'slug', 'parent_id', 'image', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Helper: return name based on locale
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') return $this->name_ar;
        if ($locale === 'fr') return $this->name_fr ?? $this->name_en;
        return $this->name_en;
    }
}
