<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;

class RecentlyViewedController extends Controller
{
    /**
     * POST /api/recently-viewed/{product_id}   [auth:sanctum]
     *
     * Records (or refreshes) a view for the authenticated user.
     * Uses updateOrCreate so there is always a single row per user+product,
     * and viewed_at is refreshed on every visit.
     */
    public function track(Request $request, int $productId): \Illuminate\Http\JsonResponse
    {
        if (!Product::active()->where('id', $productId)->exists()) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        ProductView::updateOrCreate(
            [
                'user_id'    => $request->user()->id,
                'product_id' => $productId,
            ],
            ['viewed_at' => now()]
        );

        return response()->json(['message' => 'Tracked.']);
    }

    /**
     * GET /api/recently-viewed?ids=1,2,3   [public — no auth middleware]
     *
     * Hydrates product data for the given comma-separated IDs.
     * The frontend always sends these IDs from localStorage — this works
     * identically for guests and logged-in users. No auth needed here.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $ids = collect(explode(',', $request->get('ids', '')))
            ->map(fn($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->take(10)
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // Fetch and preserve the order the frontend sent (most-recent first)
        $products = Product::with(['category'])
            ->withCount(['reviews as review_count'])
            ->withAvg(['reviews as avg_rating' => fn($q) => $q->where('approved', true)], 'rating')
            ->active()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn($p) => $ids->search($p->id))
            ->values();

        return response()->json([
            'data' => $products->map(fn($p) => $this->format($p)),
        ]);
    }

    /** Same compact shape as ProductController::formatListItem */
    private function format(Product $p): array
    {
        return [
            'id'               => $p->id,
            'slug'             => $p->slug,
            'name_ar'          => $p->name_ar,
            'name_en'          => $p->name_en,
            'name_fr'          => $p->name_fr,
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
}
