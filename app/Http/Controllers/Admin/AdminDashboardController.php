<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Complaint;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /* ======================
         | BASIC METRICS
         ====================== */
        $totalUsers = User::count();
        $totalSellers = Seller::count();

        // Only approved products from approved sellers
        $totalProducts = Product::where('approval_status', 'Approved')
            ->whereHas('seller', function ($q) {
                $q->where('verification_status', 'Approved');
            })
            ->count();

        $totalOrders = Order::count();
        $totalTransactions = Transaction::count();

        /* ======================
         | REVENUE
         ====================== */
        $totalRevenue = Order::where('status', 'paid')
            ->sum('total_amount');

        /* ======================
         | ORDER STATUS COUNTS
         ====================== */
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders = Order::where('status', 'paid')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        /* ======================
         | PENDING ITEMS
         ====================== */
        $pendingSellers = Seller::where('verification_status', 'Pending')->count();
        $pendingProducts = Product::where('approval_status', 'Pending')->count();
        $openComplaints = Complaint::where('status', 'Pending')->count();

        /* ======================
         | RECENT DATA
         ====================== */

        // Recent users
        $recentUsers = User::latest()
            ->limit(6)
            ->get();

        // Recent products (approved only)
        $recentProducts = Product::with('seller', 'images')
            ->where('approval_status', 'Approved')
            ->whereHas('seller', function ($q) {
                $q->where('verification_status', 'Approved');
            })
            ->latest()
            ->limit(6)
            ->get();

        // ✅ Recent orders (FIX for undefined variable)
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        /* ======================
         | RETURN VIEW
         ====================== */
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalProducts',
            'totalOrders',
            'totalTransactions',
            'totalRevenue',
            'pendingOrders',
            'paidOrders',
            'cancelledOrders',
            'pendingSellers',
            'pendingProducts',
            'openComplaints',
            'recentUsers',
            'recentProducts',
            'recentOrders'
        ));
    }
}

