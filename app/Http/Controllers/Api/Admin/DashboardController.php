<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** GET /api/admin/dashboard/stats */
    public function stats()
    {
        $orderCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentOrders = Order::with('items')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($o) => [
                'id'         => $o->id,
                'status'     => $o->status,
                'total'      => $o->total,
                'guest_name' => $o->guest_name,
                'guest_phone'=> $o->guest_phone,
                'created_at' => $o->created_at->toDateTimeString(),
                'items_count'=> $o->items->count(),
            ]);

        return response()->json([
            'stats' => [
                'total_orders'    => Order::count(),
                'total_revenue'   => Order::where('status', 'delivered')->sum('total'),
                'total_products'  => Product::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'by_status'       => $orderCounts,
            ],
            'recent_orders' => $recentOrders,
        ]);
    }
}
