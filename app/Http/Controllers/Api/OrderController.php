<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /** POST /api/orders/checkout */
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'notes'       => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'address_id'  => 'nullable|exists:addresses,id',
            // Frontend cart items sent directly
            'items'              => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.qty'        => 'required_with:items|integer|min:1',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
        ]);

        // Resolve cart: prefer items sent from frontend, fallback to DB cart
        $frontendItems = $request->input('items', []);

        if (!empty($frontendItems)) {
            // Build cart from frontend payload
            $cartItems = collect($frontendItems)->map(function ($item) {
                $product = \App\Models\Product::find($item['product_id']);
                $variant = isset($item['variant_id'])
                    ? \App\Models\ProductVariant::find($item['variant_id'])
                    : null;
                return (object)[
                    'product'    => $product,
                    'variant'    => $variant,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'qty'        => $item['qty'],
                ];
            })->filter(fn($i) => $i->product !== null);
        } else {
            // Fallback to database session cart
            $cartQuery = $request->user()
                ? CartItem::where('user_id', $request->user()->id)
                : CartItem::where('session_id', $request->session()->getId());
            $cartItems = $cartQuery->with(['product', 'variant'])->get();
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(fn($i) =>
            ($i->product->price + ($i->variant?->extra_price ?? 0)) * $i->qty
        );

        $deliveryFee = (float) config('shop.delivery_fee', 5.99);
        $discount    = 0;
        $coupon      = null;

        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper($data['coupon_code']))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal + $deliveryFee - $discount;

        $order = DB::transaction(function () use (
            $request, $data, $cartItems, $subtotal, $deliveryFee, $discount, $total, $coupon, $frontendItems
        ) {
            $order = Order::create([
                'user_id'       => $request->user()?->id,
                'address_id'    => $data['address_id'] ?? null,
                'status'        => 'placed',
                'subtotal'      => $subtotal,
                'delivery_fee'  => $deliveryFee,
                'discount'      => $discount,
                'total'         => $total,
                'coupon_code'   => $coupon?->code,
                'notes'         => $data['notes'] ?? null,
                'guest_name'    => $data['name'],
                'guest_phone'   => $data['phone'],
                'guest_email'   => $data['email'] ?? $request->user()?->email,
                'guest_address' => $data['address'] . ', ' . $data['city'],
            ]);

            foreach ($cartItems as $item) {
                $unitPrice = $item->product->price + ($item->variant?->extra_price ?? 0);
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'variant_id'   => $item->variant_id,
                    'product_name' => $item->product->name_en ?? $item->product->name_ar,
                    'unit_price'   => $unitPrice,
                    'qty'          => $item->qty,
                    'total'        => $unitPrice * $item->qty,
                ]);

                // Decrease stock
                $item->product->decrement('stock', $item->qty);
            }

            // Increment coupon usage
            $coupon?->increment('used_count');

            // Clear DB cart:
            // - always when we used the DB cart directly
            // - also when we used the frontend payload (auth user may still have DB cart items)
            if (empty($frontendItems)) {
                $cartItems->each->delete();
            } elseif ($request->user()) {
                CartItem::where('user_id', $request->user()->id)->delete();
            }

            return $order->load('items');
        });

        // Send order confirmation email (queued — non-blocking)
        $recipientEmail = $order->guest_email;
        if ($recipientEmail) {
            Mail::to($recipientEmail)->queue(new OrderPlaced($order));
        }

        return response()->json([
            'message' => 'Order placed successfully.',
            'order'   => $this->formatOrder($order),
        ], 201);
    }

    /** GET /api/orders — customer's own orders */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $orders->map(fn($o) => $this->formatOrder($o)),
            'meta' => ['total' => $orders->total(), 'last_page' => $orders->lastPage()],
        ]);
    }

    /** GET /api/orders/:id */
    public function show(Request $request, int $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->findOrFail($id);

        return response()->json(['data' => $this->formatOrder($order)]);
    }

    /** GET /api/orders/:id/track */
    public function track(Request $request, int $id)
    {
        $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

        $steps = [
            'placed'             => 1,
            'preparing'          => 2,
            'awaiting_shipment'  => 3,
            'shipped'            => 4,
            'in_transit'         => 5,
            'delivered'          => 6,
        ];

        return response()->json([
            'data' => [
                'id'           => $order->id,
                'status'       => $order->status,
                'current_step' => $steps[$order->status] ?? 0,
                'total_steps'  => 6,
                'created_at'   => $order->created_at->toDateTimeString(),
            ],
        ]);
    }

    private function formatOrder(Order $order): array
    {
        return [
            'id'           => $order->id,
            'status'       => $order->status,
            'subtotal'     => $order->subtotal,
            'delivery_fee' => $order->delivery_fee,
            'discount'     => $order->discount,
            'total'        => $order->total,
            'coupon_code'  => $order->coupon_code,
            'notes'        => $order->notes,
            'guest_name'   => $order->guest_name,
            'guest_phone'  => $order->guest_phone,
            'created_at'   => $order->created_at->toDateTimeString(),
            'items'        => $order->items->map(fn($i) => [
                'id'           => $i->id,
                'product_name' => $i->product_name,
                'unit_price'   => $i->unit_price,
                'qty'          => $i->qty,
                'total'        => $i->total,
                'product'      => $i->product ? ['slug' => $i->product->slug, 'images' => $i->product->images ?? []] : null,
            ]),
        ];
    }
}
