<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** GET /api/products */
    public function index(Request $request)
    {
        $query = Product::with(['category'])->withCount(['reviews as review_count'])
            ->withAvg(['reviews as avg_rating' => fn($q) => $q->where('approved', true)], 'rating')
            ->active();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->get('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
        }

        // Featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Price range
        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        // Sort
        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->withCount('reviews')->orderByDesc('reviews_count'),
            'discount'   => $query->orderByDesc('discount_percent'),
            default      => $query->latest(),
        };

        $products = $query->paginate($request->get('per_page', 16));

        return response()->json([
            'data'  => collect($products->items())->map(fn($p) => $this->formatListItem($p)),
            'meta'  => [
                'total'        => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
            ],
        ]);
    }

    /** GET /api/products/:slug */
    public function show(string $slug)
    {
        $product = Product::with(['category', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatProduct($product),
        ]);
    }

    /** GET /api/categories/:slug/products */
    public function byCategory(string $slug, Request $request)
    {
        $request->merge(['category' => $slug]);
        return $this->index($request);
    }

    /** Compact shape returned by the list endpoint (used by ProductCard) */
    private function formatListItem(Product $p): array
    {
        return [
            'id'               => $p->id,
            'slug'             => $p->slug,
            'name_ar'          => $p->name_ar,
            'name_en'          => $p->name_en,
            'name_fr'          => $p->name_fr,
            'description_ar'   => $p->description_ar,
            'description_en'   => $p->description_en,
            'description_fr'   => $p->description_fr,
            'price'            => (float) $p->price,
            'original_price'   => $p->original_price ? (float) $p->original_price : null,
            'discount_percent' => $p->discount_percent,
            'stock'            => $p->stock,
            'images'           => $p->images ?? [],
            'is_featured'      => $p->is_featured,
            'deal_ends_at'     => $p->deal_ends_at?->toIso8601String(),
            'avg_rating'       => round((float) ($p->avg_rating ?? 0), 1),
            'review_count'     => (int) ($p->review_count ?? 0),
            'category'         => $p->category ? [
                'id'      => $p->category->id,
                'name_ar' => $p->category->name_ar,
                'name_en' => $p->category->name_en,
                'name_fr' => $p->category->name_fr,
                'slug'    => $p->category->slug,
            ] : null,
        ];
    }

    private function formatProduct(Product $p): array
    {
        return [
            'id'               => $p->id,
            'name_ar'          => $p->name_ar,
            'name_en'          => $p->name_en,
            'slug'             => $p->slug,
            'description_ar'   => $p->description_ar,
            'description_en'   => $p->description_en,
            'price'            => $p->price,
            'original_price'   => $p->original_price,
            'discount_percent' => $p->discount_percent,
            'stock'            => $p->stock,
            'images'           => $p->images ?? [],
            'specifications'   => $p->specifications ?? [],
            'is_featured'      => $p->is_featured,
            'deal_ends_at'     => $p->deal_ends_at?->toIso8601String(),
            'avg_rating'       => $p->avg_rating,
            'review_count'     => $p->reviews->count(),
            'category'         => [
                'id'      => $p->category->id,
                'name_ar' => $p->category->name_ar,
                'name_en' => $p->category->name_en,
                'slug'    => $p->category->slug,
            ],
            'variants'         => $p->variants->map(fn($v) => [
                'id'          => $v->id,
                'attribute'   => $v->attribute,
                'value'       => $v->value,
                'extra_price' => $v->extra_price,
                'stock'       => $v->stock,
            ]),
            'reviews'          => $p->reviews->where('approved', true)->take(5)->map(fn($r) => [
                'id'         => $r->id,
                'rating'     => $r->rating,
                'comment'    => $r->comment,
                'created_at' => $r->created_at->toDateString(),
                'user'       => ['name' => $r->user->name],
            ]),
        ];
    }
}
