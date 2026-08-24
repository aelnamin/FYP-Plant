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
        $activeProducts = Product::query()
            ->where('seller_id', $seller->id)
            ->whereNull('deleted_at');

        $total_products = (clone $activeProducts)->count();

        $low_stock_count = (clone $activeProducts)
            ->where('stock_quantity', '<=', 10)
            ->count();

        $inventoryProducts = (clone $activeProducts)
            ->with('images')
            ->latest()
            ->take(5)
            ->get();

        /* ======================
           SELLER ORDER ITEMS
        ====================== */
        // Keep historical sales attributable even if a product was later deleted,
        // but never count deleted order items or deleted orders.
        $sellerOrderItems = OrderItem::query()
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('products.seller_id', $seller->id)
            ->whereNull('order_items.deleted_at')
            ->whereNull('orders.deleted_at');

        $total_orders = (clone $sellerOrderItems)
            ->distinct()
            ->count('order_items.order_id');

        // Count each seller order once at its least-advanced item stage. This
        // prevents a mixed-status order from appearing in multiple cards.
        $sellerFulfillmentPerOrder = (clone $sellerOrderItems)
            ->selectRaw("order_items.order_id, MIN(CASE
                WHEN LOWER(order_items.seller_status) = 'pending' THEN 1
                WHEN LOWER(order_items.seller_status) = 'paid' THEN 2
                WHEN LOWER(order_items.seller_status) = 'shipped' THEN 3
                WHEN LOWER(order_items.seller_status) IN ('delivered', 'completed') THEN 4
                ELSE 1 END) as fulfillment_stage")
            ->groupBy('order_items.order_id');

        $ordersByFulfillmentStage = DB::query()
            ->fromSub($sellerFulfillmentPerOrder, 'seller_order_fulfillment')
            ->selectRaw('fulfillment_stage, COUNT(*) as total')
            ->groupBy('fulfillment_stage')
            ->pluck('total', 'fulfillment_stage');

        $pending_orders = (int) $ordersByFulfillmentStage->get(1, 0);
        $paid_orders = (int) $ordersByFulfillmentStage->get(2, 0);
        $orders_to_ship = $paid_orders;

        // Revenue is recognized only after payment. Pending, cancelled, and
        // refunded items are deliberately excluded.
        $revenueStatuses = ['paid', 'shipped', 'delivered', 'completed'];
        $recognizedRevenueItems = (clone $sellerOrderItems)
            ->whereIn(DB::raw('LOWER(order_items.seller_status)'), $revenueStatuses);

        $total_revenue = (clone $recognizedRevenueItems)
            ->sum(DB::raw('order_items.quantity * order_items.price'));

        $monthStart = Carbon::now('Asia/Kuala_Lumpur')->startOfMonth()->utc();
        $monthEnd = Carbon::now('Asia/Kuala_Lumpur')->endOfMonth()->utc();

        $month_revenue = (clone $recognizedRevenueItems)
            ->whereBetween('order_items.created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('order_items.quantity * order_items.price'));



        /* ======================
           RECENT ORDERS WITH SELLER ITEMS
        ====================== */
        $recentOrders = Order::query()
            ->whereNull('orders.deleted_at')
            ->whereHas('items.product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->with([
                'user:id,name,email',
                'items' => function ($query) use ($seller) {
                    $query->whereNull('order_items.deleted_at')
                        ->whereHas('product', fn($productQuery) => $productQuery->where('seller_id', $seller->id));
                },
            ])
            ->latest()
            ->take(5)
            ->get();

        $statusRank = [
            'pending' => 1,
            'paid' => 2,
            'shipped' => 3,
            'delivered' => 4,
            'completed' => 4,
        ];

        $recentOrders->transform(function ($order) use ($statusRank) {
            $order->seller_total = $order->items->sum(fn($item) => $item->quantity * $item->price);
            $order->seller_status = $order->items
                ->map(fn($item) => strtolower((string) $item->seller_status))
                ->sortBy(fn($status) => $statusRank[$status] ?? 0)
                ->first() ?? 'pending';

            return $order;
        });

        return view('sellers.dashboard', compact(
            'seller',
            'total_products',
            'low_stock_count',
            'inventoryProducts',
            'total_orders',
            'paid_orders',
            'pending_orders',
            'orders_to_ship',
            'total_revenue',
            'month_revenue',
            'recentOrders'
        ));
    }
}

