<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /* ======================
         | BASIC METRICS
         ====================== */
        $usersByRole = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalUsers = $usersByRole->sum();
        $totalBuyers = (int) $usersByRole->get('buyer', 0);
        $totalSellerAccounts = (int) $usersByRole->get('seller', 0);

        $activeSellers = Seller::query()->whereNull('deleted_at');
        $activeProducts = Product::query()->whereNull('deleted_at');
        $totalSellers = (clone $activeSellers)
            ->whereRaw('LOWER(verification_status) = ?', ['approved'])
            ->count();


        // Only approved products from approved sellers
        $totalProducts = (clone $activeProducts)
            ->whereRaw('LOWER(approval_status) = ?', ['approved'])
            ->whereHas('seller', function ($q) {
                $q->whereNull('deleted_at')
                    ->whereRaw('LOWER(verification_status) = ?', ['approved']);
            })
            ->count();

        /* ======================
         | ORDER STATUS COUNTS
         ====================== */
        // The seller fulfillment status is stored on order_items, not orders.
        // Use the least-advanced item as each order's current, exclusive stage.
        $fulfillmentStagePerOrder = DB::table('order_items')
            ->selectRaw("order_id, MIN(CASE
                WHEN LOWER(seller_status) = 'pending' THEN 1
                WHEN LOWER(seller_status) = 'paid' THEN 2
                WHEN LOWER(seller_status) = 'shipped' THEN 3
                WHEN LOWER(seller_status) IN ('delivered', 'completed') THEN 4
                ELSE 1 END) as fulfillment_stage")
            ->whereNull('deleted_at')
            ->groupBy('order_id');

        $ordersByFulfillmentStage = DB::query()
            ->fromSub($fulfillmentStagePerOrder, 'order_fulfillment')
            ->join('orders', 'orders.id', '=', 'order_fulfillment.order_id')
            ->whereNull('orders.deleted_at')
            ->selectRaw('fulfillment_stage, COUNT(*) as total')
            ->groupBy('fulfillment_stage')
            ->pluck('total', 'fulfillment_stage');

        $pendingOrders = (int) $ordersByFulfillmentStage->get(1, 0);
        $paidOrders = (int) $ordersByFulfillmentStage->get(2, 0);
        $shippedOrders = (int) $ordersByFulfillmentStage->get(3, 0);
        $deliveredOrders = (int) $ordersByFulfillmentStage->get(4, 0);

        /* ======================
         | PENDING ITEMS
         ====================== */
        $pendingSellers = (clone $activeSellers)
            ->whereRaw('LOWER(verification_status) = ?', ['pending'])
            ->count();
        $pendingProducts = (clone $activeProducts)
            ->whereRaw('LOWER(approval_status) = ?', ['pending'])
            ->count();

        /* ======================
         | RECENT DATA
         ====================== */

        // Recent products (approved only)
        $recentProducts = Product::with('seller', 'images')
            ->whereNull('products.deleted_at')
            ->whereRaw('LOWER(approval_status) = ?', ['approved'])
            ->whereHas('seller', function ($q) {
                $q->whereNull('deleted_at')
                    ->whereRaw('LOWER(verification_status) = ?', ['approved']);
            })
            ->latest()
            ->limit(6)
            ->get();

        /* ======================
         | RETURN VIEW
         ====================== */
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBuyers',
            'totalSellerAccounts',
            'totalSellers',
            'totalProducts',
            'pendingOrders',
            'paidOrders',
            'shippedOrders',
            'deliveredOrders',
            'pendingSellers',
            'pendingProducts',
            'recentProducts'
        ));
    }
}

