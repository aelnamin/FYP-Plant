<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Complaint;
use Illuminate\Http\Request;


class AdminDashboardController extends Controller
{
    public function index()
    {
        // basic metrics
        $totalUsers = User::count();
        $totalSellers = Seller::count();

        // Only products where product is approved AND seller is approved
        $totalProducts = Product::where('approval_status', 'Approved')
            ->whereHas('seller', function ($q) {
                $q->where('verification_status', 'Approved');
            })
            ->count();

        $totalOrders = Order::count();
        $totalTransactions = Transaction::count();

        // TOTAL REVENUE (paid orders only)
        $totalRevenue = Order::where('status', 'paid')
            ->sum('total_amount');

        // pending items
        $pendingSellers = Seller::where('verification_status', 'Pending')->count();
        $pendingProducts = Product::where('approval_status', 'Pending')->count();
        $openComplaints = Complaint::where('status', 'Pending')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders = Order::where('status', 'paid')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // recent lists (only products from approved sellers)
        $recentUsers = User::latest()->limit(6)->get();
        $recentProducts = Product::with('seller')
            ->where('approval_status', 'Approved')
            ->whereHas('seller', function ($q) {
                $q->where('verification_status', 'Approved');
            })
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalProducts',
            'totalOrders',
            'totalTransactions',
            'totalRevenue',
            'pendingSellers',
            'pendingProducts',
            'openComplaints',
            'recentUsers',
            'recentProducts',
            'pendingOrders',
            'paidOrders',
            'cancelledOrders'
        ));
    }
}
