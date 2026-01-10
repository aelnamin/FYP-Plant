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

        // total orders
        $total_orders = OrderItem::whereIn('product_id', $sellerProductIds)
            ->distinct('order_id')
            ->count();

        // paid orders
        $paid_orders = OrderItem::whereIn('product_id', $sellerProductIds)
            ->where('seller_status', 'paid')
            ->distinct('order_id')
            ->count();

        $sellerProductIds = $seller->products()->pluck('id');

        // Count distinct orders where seller_status = 'paid'
        $orders_to_ship = OrderItem::whereIn('product_id', $sellerProductIds)
            ->where('seller_status', 'pending')
            ->distinct('order_id')
            ->count();

        $pending_orders = $orders_to_ship;

        /* ======================
           TOTAL REVENUE (SELLER ONLY)
        ====================== */

        $total_revenue = OrderItem::whereIn('product_id', $sellerProductIds)
            ->whereIn('seller_status', ['pending', 'paid', 'shipped', 'delivered'])
            ->get()
            ->sum(fn($item) => $item->quantity * $item->price);


        /* ======================
    MONTH REVENUE
 ====================== */
        $month_revenue = OrderItem::whereIn('product_id', $sellerProductIds)
            ->whereIn('seller_status', ['pending', 'paid', 'shipped', 'delivered'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(fn($item) => $item->quantity * $item->price);


        /* ======================
      SALES LAST 7 DAYS FOR ANALYTICS
   ====================== */
        $sales_last_7_days = OrderItem::whereIn('product_id', $sellerProductIds)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['paid', 'shipped', 'delivered']);
            })
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay()) // last 7 days including today
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('d M'); // group by day
            })
            ->map(function ($items) {
                return $items->sum(fn($i) => $i->quantity * $i->price);
            });

        // Make sure all 7 days exist in labels (fill 0 if no sales)
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->format('d M');
            $last7Days[$day] = $sales_last_7_days->get($day, 0);
        }

        $sales_labels = $last7Days->keys()->toArray();  // Convert to array
        $sales_data = $last7Days->values()->map(fn($v) => (float) $v)->toArray();



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
            'avg_rating',
            'sales_labels',
            'sales_data'
        ));
    }
}

