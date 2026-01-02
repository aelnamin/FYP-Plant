<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Order Report
        $ordersQuery = Order::query();

        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $ordersQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $ordersQuery->whereDate('created_at', '<=', $request->to);
        }

        $orders = $ordersQuery->latest()->get();

        // Payment Summary
        $totalRevenue = Order::whereIn('status', ['Paid', 'Shipped'])
            ->sum('total_amount');

        $totalPaidOrders = Order::whereIn('status', ['Paid', 'Shipped'])->count();

        // Seller Performance
        $sellerPerformance = Seller::withCount('products')
            ->get()
            ->map(function ($seller) {

                $seller->total_orders = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('products.seller_id', $seller->id)
                    ->count();

                $seller->total_sales = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('products.seller_id', $seller->id)
                    ->whereIn('orders.status', ['Paid', 'Shipped'])
                    ->sum(DB::raw('order_items.price * order_items.quantity'));

                return $seller;
            });

        return view('admin.reports.index', compact(
            'orders',
            'totalRevenue',
            'totalPaidOrders',
            'sellerPerformance'
        ));
    }
}
