<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:seller']);
    }

    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())->firstOrFail();

        /* ======================
           SELLER PRODUCTS
        ====================== */
        $sellerProductIds = Product::where('seller_id', $seller->id)->pluck('id');
        $total_products = $sellerProductIds->count();

        $low_stock_count = Product::where('seller_id', $seller->id)
            ->where('stock_quantity', '<=', 10)
            ->count();

        $inventoryProducts = Product::where('seller_id', $seller->id)
            ->latest()
            ->take(5)
            ->get();

        /* ======================
           ORDERS FILTERED BY SELLER PRODUCTS
        ====================== */
        $ordersQuery = Order::whereHas('items', function ($q) use ($sellerProductIds) {
            $q->whereIn('product_id', $sellerProductIds);
        });

        $total_orders = (clone $ordersQuery)->distinct()->count();
        $paid_orders = (clone $ordersQuery)->where('status', 'paid')->distinct()->count();
        $pending_orders = (clone $ordersQuery)->where('status', 'pending')->distinct()->count();

        /* ======================
           TOTAL REVENUE (SELLER ONLY)
        ====================== */
        $total_revenue = OrderItem::whereIn('product_id', $sellerProductIds)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['paid', 'shipped', 'delivered']);
            })
            ->sum(DB::raw('quantity * price'));


        /* ======================
    MONTH REVENUE
 ====================== */
        $month_revenue = OrderItem::whereIn('product_id', $sellerProductIds)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['paid', 'shipped', 'delivered'])
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->sum(DB::raw('quantity * price'));



        /* ======================
           RECENT ORDERS WITH SELLER ITEMS
        ====================== */
        $recentOrders = (clone $ordersQuery)
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Add seller-specific total for recent orders
        $recentOrders->transform(function ($order) use ($sellerProductIds) {
            $order->seller_total = $order->items
                ->whereIn('product_id', $sellerProductIds)
                ->sum(fn($item) => $item->quantity * $item->price);
            return $order;
        });

        /* ======================
           BASIC ANALYTICS
        ====================== */
        $avg_order_value = $paid_orders > 0
            ? $total_revenue / $paid_orders
            : 0;

        $conversion_rate = 0; // placeholder (you can calculate: paid_orders / total_visitors * 100)
        $avg_rating = 0;       // placeholder (if you have product reviews)

        $best_selling_product = Product::where('seller_id', $seller->id)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->value('product_name');

        return view('sellers.dashboard', compact(
            'seller',
            'total_products',
            'low_stock_count',
            'inventoryProducts',
            'total_orders',
            'paid_orders',
            'pending_orders',
            'total_revenue',
            'month_revenue',
            'recentOrders',
            'avg_order_value',
            'conversion_rate',
            'best_selling_product',
            'avg_rating'
        ));
    }
}

