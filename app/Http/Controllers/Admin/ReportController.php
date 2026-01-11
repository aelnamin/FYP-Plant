<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;

class ReportController extends Controller
{
    public function index()
    {
        /* ============================
           STATUS FILTER
        ============================ */
        $completedStatus = ['Completed']; // Only finalized orders count for revenue

        /* ============================
           SUMMARY CARDS
        ============================ */

        // Total Revenue (only completed orders)
        $totalRevenue = OrderItem::whereIn('seller_status', $completedStatus)
            ->sum(DB::raw('price * quantity'));

        // Total Orders (distinct completed orders)
        $totalOrders = OrderItem::whereIn('seller_status', $completedStatus)
            ->distinct('order_id')
            ->count('order_id');

        // Total Registered Sellers (all sellers in DB)
        $totalRegisteredSellers = Seller::count();

        // Total Active Sellers (sellers with at least one completed sale)
        $totalActiveSellers = Seller::whereHas('products.orderItems', function ($query) use ($completedStatus) {
            $query->whereIn('seller_status', $completedStatus);
        })->count();

        // Total Products Sold (quantity of completed items)
        $totalProductsSold = OrderItem::whereIn('seller_status', $completedStatus)
            ->sum('quantity');

        /* ============================
           SALES TREND (MONTHLY)
        ============================ */

        $monthlySales = OrderItem::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(price * quantity) as revenue'),
            DB::raw('COUNT(DISTINCT order_id) as orders')
        )
            ->whereIn('seller_status', $completedStatus)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        /* ============================
           SELLER STATUS BREAKDOWN
        ============================ */

        $statusBreakdown = OrderItem::select(
            'seller_status',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('seller_status')
            ->get();

        /* ============================
           TOP SELLERS
        ============================ */

        $topSellers = Seller::select(
            'sellers.id',
            'sellers.business_name',
            DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'),
            DB::raw('COUNT(DISTINCT order_items.order_id) as completed_orders')
        )
            ->join('products', 'products.seller_id', '=', 'sellers.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->whereIn('order_items.seller_status', $completedStatus)
            ->groupBy('sellers.id', 'sellers.business_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        /* ============================
           TOP PRODUCTS
        ============================ */

        $topProducts = Product::select(
            'products.product_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.price * order_items.quantity) as revenue')
        )
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->whereIn('order_items.seller_status', $completedStatus)
            ->groupBy('products.product_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        /* ============================
           RETURN VIEW
        ============================ */

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalRegisteredSellers',
            'totalActiveSellers',
            'totalProductsSold',
            'monthlySales',
            'statusBreakdown',
            'topSellers',
            'topProducts'
        ));
    }
}
