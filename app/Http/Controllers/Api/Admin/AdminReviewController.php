<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /** GET /api/admin/reviews */
    public function index(Request $request)
    {
        $query = Review::with(['product:id,name_en,name_ar,slug', 'user:id,name,email'])
            ->latest();

        if ($request->has('approved')) {
            $query->where('approved', filter_var($request->get('approved'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $reviews->items(),
            'meta' => [
                'total'        => $reviews->total(),
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
            ],
        ]);
    }

    /** PATCH /api/admin/reviews/:id/approve */
    public function approve(int $id)
    {
        $review = Review::findOrFail($id);
        $review->update(['approved' => true]);

        return response()->json([
            'message' => 'تمت الموافقة على التقييم.',
            'data'    => $review,
        ]);
    }

    /** DELETE /api/admin/reviews/:id */
    public function destroy(int $id)
    {
        Review::findOrFail($id)->delete();
        return response()->json(['message' => 'تم حذف التقييم.']);
    }
}
