<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index()
    {
        return response()->json(Coupon::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'             => 'required|string|unique:coupons,code',
            'discount_type'    => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        $coupon = Coupon::create($validated);

        return response()->json($coupon, 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'             => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_type'    => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        $coupon->update($validated);

        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted successfully']);
    }

    public function toggleActive(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return response()->json($coupon);
    }
}
