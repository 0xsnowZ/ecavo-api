<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /** GET /api/wishlist */
    public function index(Request $request)
    {
        $items = Wishlist::where('user_id', $request->user()->id)
            ->with('product')
            ->get()
            ->map(fn($w) => [
                'id'      => $w->id,
                'product' => [
                    'id'       => $w->product->id,
                    'name_ar'  => $w->product->name_ar,
                    'name_en'  => $w->product->name_en,
                    'slug'     => $w->product->slug,
                    'price'    => $w->product->price,
                    'images'   => $w->product->images ?? [],
                    'discount_percent' => $w->product->discount_percent,
                ],
            ]);

        return response()->json(['data' => $items]);
    }

    /** POST /api/wishlist/toggle/:product_id */
    public function toggle(Request $request, int $productId)
    {
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'تم الإزالة من المفضلة.', 'wishlisted' => false]);
        }

        Wishlist::create(['user_id' => $request->user()->id, 'product_id' => $productId]);
        return response()->json(['message' => 'تم الإضافة إلى المفضلة.', 'wishlisted' => true], 201);
    }
}
