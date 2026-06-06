<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartQuery(Request $request)
    {
        if ($request->user()) {
            return CartItem::where('user_id', $request->user()->id);
        }
        return CartItem::where('session_id', $request->session()->getId());
    }

    private function cartResponse(Request $request, ?Coupon $coupon = null): array
    {
        $items = $this->getCartQuery($request)
            ->with(['product', 'variant'])
            ->get();

        $subtotal = $items->sum(function ($item) {
            $price = $item->product->price + ($item->variant?->extra_price ?? 0);
            return $price * $item->qty;
        });

        $deliveryFee  = $subtotal > 0 ? (float) config('shop.delivery_fee', 5.99) : 0;
        $discount     = 0;
        $couponCode   = null;

        if ($coupon && $coupon->isValid($subtotal)) {
            $discount   = $coupon->calculateDiscount($subtotal);
            $couponCode = $coupon->code;
        }

        return [
            'items' => $items->map(fn($i) => [
                'id'         => $i->id,
                'qty'        => $i->qty,
                'product'    => [
                    'id'     => $i->product->id,
                    'name_ar'=> $i->product->name_ar,
                    'name_en'=> $i->product->name_en,
                    'slug'   => $i->product->slug,
                    'price'  => $i->product->price,
                    'images' => $i->product->images ?? [],
                ],
                'variant'    => $i->variant ? [
                    'id'          => $i->variant->id,
                    'attribute'   => $i->variant->attribute,
                    'value'       => $i->variant->value,
                    'extra_price' => $i->variant->extra_price,
                ] : null,
                'line_total' => ($i->product->price + ($i->variant?->extra_price ?? 0)) * $i->qty,
            ]),
            'subtotal'     => round($subtotal, 2),
            'delivery_fee' => $deliveryFee,
            'discount'     => $discount,
            'total'        => round($subtotal + $deliveryFee - $discount, 2),
            'coupon_code'  => $couponCode,
        ];
    }

    /** GET /api/cart */
    public function index(Request $request)
    {
        return response()->json($this->cartResponse($request));
    }

    /** POST /api/cart/add */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty'        => 'integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stock < ($data['qty'] ?? 1)) {
            return response()->json(['message' => 'المنتج غير متوفر بالمخزون.'], 422);
        }

        $filter = [
            'product_id' => $data['product_id'],
            'variant_id' => $data['variant_id'] ?? null,
        ];

        if ($request->user()) {
            $filter['user_id'] = $request->user()->id;
        } else {
            $filter['session_id'] = $request->session()->getId();
        }

        $item = CartItem::firstOrNew($filter);
        $item->qty = ($item->qty ?? 0) + ($data['qty'] ?? 1);
        $item->save();

        return response()->json($this->cartResponse($request), 201);
    }

    /** PATCH /api/cart/update/:item_id */
    public function update(Request $request, int $itemId)
    {
        $data = $request->validate(['qty' => 'required|integer|min:1|max:99']);

        $item = $this->getCartQuery($request)->with('product')->findOrFail($itemId);

        if ($item->product->stock < $data['qty']) {
            return response()->json(['message' => 'الكمية المطلوبة غير متوفرة في المخزون.'], 422);
        }

        $item->update(['qty' => $data['qty']]);

        return response()->json($this->cartResponse($request));
    }

    /** DELETE /api/cart/remove/:item_id */
    public function remove(Request $request, int $itemId)
    {
        $this->getCartQuery($request)->findOrFail($itemId)->delete();
        return response()->json($this->cartResponse($request));
    }

    /** POST /api/cart/apply-coupon */
    public function applyCoupon(Request $request)
    {
        $data   = $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($data['code']))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json(['message' => 'كود الخصم غير صالح أو منتهي الصلاحية.'], 422);
        }

        return response()->json($this->cartResponse($request, $coupon));
    }
}
