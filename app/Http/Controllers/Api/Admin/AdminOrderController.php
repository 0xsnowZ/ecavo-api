<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    const STATUSES = [
        'placed', 'preparing', 'awaiting_shipment',
        'shipped', 'in_transit', 'delivered',
        'no_answer', 'postponed', 'wrong_address',
        'cancelled', 'returned',
    ];

    /** GET /api/admin/orders */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%");
            });
        }
        if ($from = $request->get('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders = $query->paginate($request->get('per_page', 25));

        return response()->json([
            'data' => $orders->items(),
            'meta' => [
                'total'        => $orders->total(),
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
            ],
        ]);
    }

    /** PATCH /api/admin/orders/:id/status */
    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->update(['status' => $data['status']]);

        // Notify the customer (queued — non-blocking)
        $recipientEmail = $order->guest_email
            ?? $order->user?->email;
        if ($recipientEmail) {
            Mail::to($recipientEmail)->queue(new OrderStatusChanged($order, $oldStatus));
        }

        return response()->json([
            'message' => 'تم تحديث حالة الطلب.',
            'order'   => ['id' => $order->id, 'status' => $order->status],
        ]);
    }

    /** PUT /api/admin/orders/:id — edit order details */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'guest_name'    => 'nullable|string|max:255',
            'guest_phone'   => 'nullable|string|max:20',
            'guest_address' => 'nullable|string',
            'notes'         => 'nullable|string',
            'coupon_code'   => 'nullable|string',
            'discount'      => 'nullable|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update($data);

        // Recalculate total if discount changed
        if (isset($data['discount'])) {
            $order->update([
                'total' => $order->subtotal + $order->delivery_fee - $order->discount,
            ]);
        }

        return response()->json([
            'message' => 'تم تحديث الطلب بنجاح.',
            'order'   => $order->fresh('items.product'),
        ]);
    }

    /** GET /api/admin/orders/:id */
    public function show(int $id)
    {
        $order = Order::with(['items.product', 'items.variant', 'user', 'address'])->findOrFail($id);
        return response()->json(['data' => $order]);
    }
}
