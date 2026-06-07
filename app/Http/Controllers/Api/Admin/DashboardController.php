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
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Trends: Today vs Yesterday
        $placedToday = Order::where('status', 'placed')->where('created_at', '>=', $today)->count();
        $placedYesterday = Order::where('status', 'placed')->whereBetween('created_at', [$yesterday, $today])->count();
        
        $shippingToday = Order::whereIn('status', ['shipped', 'in_transit'])->where('updated_at', '>=', $today)->count();
        $shippingYesterday = Order::whereIn('status', ['shipped', 'in_transit'])->whereBetween('updated_at', [$yesterday, $today])->count();
        
        $issuesToday = Order::whereIn('status', ['cancelled', 'returned'])->where('updated_at', '>=', $today)->count();
        $issuesYesterday = Order::whereIn('status', ['cancelled', 'returned'])->whereBetween('updated_at', [$yesterday, $today])->count();

        $avgRating = \App\Models\Review::where('approved', true)->avg('rating') ?? 0;

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

        $topProducts = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name_en as nameEn',
                'products.name_ar as nameAr',
                'products.name_fr as nameFr',
                \Illuminate\Support\Facades\DB::raw('SUM(order_items.qty) as sales'),
                \Illuminate\Support\Facades\DB::raw('SUM(order_items.total) as revenue')
            )
            ->where('orders.created_at', '>=', now()->subDays(7))
            ->whereNotIn('orders.status', ['cancelled', 'returned'])
            ->groupBy('products.id', 'products.name_en', 'products.name_ar', 'products.name_fr')
            ->orderByDesc('sales')
            ->take(5)
            ->get();

        $startDate = now()->subDays(6)->startOfDay();
        $trendData = Order::select(
                \Illuminate\Support\Facades\DB::raw('DATE(created_at) as date'),
                \Illuminate\Support\Facades\DB::raw('COUNT(id) as orders'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as revenue')
            )
            ->where('created_at', '>=', $startDate)
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenueTrend[] = [
                'date'    => $date,
                'orders'  => $trendData->has($date) ? (int) $trendData[$date]->orders : 0,
                'revenue' => $trendData->has($date) ? (float) $trendData[$date]->revenue : 0,
            ];
        }

        return response()->json([
            'stats' => [
                'total_orders'    => Order::count(),
                'total_revenue'   => Order::where('status', 'delivered')->sum('total'),
                'total_products'  => Product::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'by_status'       => $orderCounts,
                'trends'          => [
                    'placed'   => $placedToday - $placedYesterday,
                    'shipping' => $shippingToday - $shippingYesterday,
                    'issues'   => $issuesToday - $issuesYesterday,
                    'rating'   => round((float) $avgRating, 1),
                ]
            ],
            'recent_orders' => $recentOrders,
            'top_products'  => $topProducts,
            'revenue_trend' => $revenueTrend,
        ]);
    }
}
