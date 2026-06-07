<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
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
            'payment_method'    => 'nullable|string|in:cod,stripe',
            'payment_intent_id' => 'nullable|string|required_if:payment_method,stripe',
            // Frontend cart items sent directly
            'items'              => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.qty'           => 'required_with:items|integer|min:1',
            'items.*.variant_label' => 'nullable|string',
            'items.*.variant_ids'   => 'nullable|array',
            'items.*.variant_ids.*' => 'exists:product_variants,id',
        ], [
            'items.*.variant_ids.*.exists' => 'One or more selected variations are no longer available or have been updated. Please clear your cart and add the items again.',
            'items.*.product_id.exists'    => 'A product in your cart is no longer available.',
        ]);

        // Resolve cart: prefer items sent from frontend, fallback to DB cart
        $frontendItems = $request->input('items', []);

        if (!empty($frontendItems)) {
            // Build cart from frontend payload
            $cartItems = collect($frontendItems)->map(function ($item) {
                $product = Product::find($item['product_id']);
                $extraPrice = 0;
                if (!empty($item['variant_ids'])) {
                    $extraPrice = ProductVariant::whereIn('id', $item['variant_ids'])->sum('extra_price');
                }

                return (object)[
                    'product'       => $product,
                    'variant_label' => $item['variant_label'] ?? null,
                    'extra_price'   => $extraPrice,
                    'product_id'    => $item['product_id'],
                    'qty'           => $item['qty'],
                ];
            })->filter(fn($i) => $i->product !== null);
        } else {
            // Fallback to database session cart
            $cartQuery = $request->user()
                ? CartItem::where('user_id', $request->user()->id)
                : CartItem::where('session_id', $request->session()->getId());
            
            $cartItems = $cartQuery->with(['product', 'variant'])->get()->map(function($i) {
                return (object)[
                    'product'       => $i->product,
                    'variant_label' => $i->variant?->value,
                    'extra_price'   => $i->variant?->extra_price ?? 0,
                    'product_id'    => $i->product_id,
                    'qty'           => $i->qty,
                ];
            });
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        $paymentMethod = $data['payment_method'] ?? 'cod';
        
        // Verify Stripe Payment
        if ($paymentMethod === 'stripe') {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
                $intent = \Stripe\PaymentIntent::retrieve($data['payment_intent_id']);
                if ($intent->status !== 'succeeded') {
                    return response()->json(['message' => 'Payment not successful.'], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Payment verification failed: ' . $e->getMessage()], 400);
            }
        }

        // Calculate totals
        $subtotal = $cartItems->sum(fn($i) =>
            ($i->product->price + $i->extra_price) * $i->qty
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
            $request, $data, $cartItems, $subtotal, $deliveryFee, $discount, $total, $coupon, $frontendItems, $paymentMethod
        ) {
            $order = Order::create([
                'user_id'       => $request->user()?->id,
                'address_id'    => $data['address_id'] ?? null,
                'status'        => 'placed',
                'payment_method'=> $paymentMethod,
                'payment_id'    => $data['payment_intent_id'] ?? null,
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
                $unitPrice = $item->product->price + $item->extra_price;
                $productName = $item->product->name_en ?? $item->product->name_ar;
                if (!empty($item->variant_label)) {
                    $productName .= ' (' . $item->variant_label . ')';
                }

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'variant_id'   => null,
                    'product_name' => $productName,
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

        // Send order confirmation email immediately
        $recipientEmail = $order->guest_email;
        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                // Don't fail the order if email fails — just log it
                \Illuminate\Support\Facades\Log::error('Order email failed: ' . $e->getMessage());
            }
        }

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewOrderNotification($order));

        return response()->json([
            'message' => 'Order placed successfully.',
            'order'   => $this->formatOrder($order),
        ], 201);
    }

    /** POST /api/orders/create-payment-intent */
    public function createPaymentIntent(Request $request)
    {
        $data = $request->validate([
            'items'              => 'nullable|array',
            'items.*.product_id'    => 'required_with:items|exists:products,id',
            'items.*.qty'           => 'required_with:items|integer|min:1',
            'items.*.variant_label' => 'nullable|string',
            'items.*.variant_ids'   => 'nullable|array',
            'items.*.variant_ids.*' => 'exists:product_variants,id',
            'coupon_code'           => 'nullable|string',
        ]);

        $frontendItems = $request->input('items', []);

        if (!empty($frontendItems)) {
            $cartItems = collect($frontendItems)->map(function ($item) {
                $product = Product::find($item['product_id']);
                $extraPrice = 0;
                if (!empty($item['variant_ids'])) {
                    $extraPrice = ProductVariant::whereIn('id', $item['variant_ids'])->sum('extra_price');
                }
                return (object)[
                    'product'     => $product,
                    'extra_price' => $extraPrice,
                    'qty'         => $item['qty'],
                ];
            })->filter(fn($i) => $i->product !== null);
        } else {
            $cartQuery = $request->user()
                ? CartItem::where('user_id', $request->user()->id)
                : CartItem::where('session_id', $request->session()->getId());
            $cartItems = $cartQuery->with(['product', 'variant'])->get()->map(function($i) {
                return (object)[
                    'product'     => $i->product,
                    'extra_price' => $i->variant?->extra_price ?? 0,
                    'qty'         => $i->qty,
                ];
            });
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        $subtotal = $cartItems->sum(fn($i) =>
            ($i->product->price + $i->extra_price) * $i->qty
        );

        $deliveryFee = (float) config('shop.delivery_fee', 5.99);
        $discount    = 0;

        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper($data['coupon_code']))->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal + $deliveryFee - $discount;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int) round($total * 100),
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
