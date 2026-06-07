<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /api/reviews/eligible
     * List all order items the user can still review (delivered, not already reviewed).
     */
    public function eligible(Request $request)
    {
        $user = $request->user();

        $items = OrderItem::with(['product:id,slug,name_en,name_ar,images', 'order:id,status,created_at'])
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            ->whereDoesntHave('review', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest('id')
            ->get();

        return response()->json(['data' => $items]);
    }

    /**
     * POST /api/reviews
     * Submit a review for an eligible order item.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        // 1. Verify this order_item belongs to the authenticated user and is delivered
        $orderItem = OrderItem::with('order')
            ->where('id', $data['order_item_id'])
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            ->first();

        if (!$orderItem) {
            return response()->json([
                'message' => 'Order item not found, not yours, or order not delivered yet.'
            ], 403);
        }

        // 2. Prevent duplicate reviews for this order item
        if (Review::where('user_id', $user->id)
                  ->where('order_item_id', $data['order_item_id'])
                  ->exists()) {
            return response()->json(['message' => 'Already reviewed.'], 409);
        }

        $review = Review::create([
            'user_id'       => $user->id,
            'product_id'    => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating'        => $data['rating'],
            'comment'       => $data['comment'] ?? null,
            'approved'      => false, // Admin must approve first
        ]);

        return response()->json([
            'message' => 'Review submitted. Pending approval.',
            'data'    => $review
        ], 201);
    }
}
