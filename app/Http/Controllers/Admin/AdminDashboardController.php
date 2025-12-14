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
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalTransactions = Transaction::count();


        // pending items
        $pendingSellers = Seller::where('verification_status', 'Pending')->count();
        $pendingProducts = Product::where('approval_status', 'Pending')->count();
        $openComplaints = Complaint::where('status', 'Pending')->count();


        // recent lists (limit for preview)
        $recentUsers = User::orderBy('created_at', 'desc')->limit(6)->get();
        $recentProducts = Product::with('seller')->orderBy('created_at', 'desc')->limit(6)->get();


        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalProducts',
            'totalOrders',
            'totalTransactions',
            'pendingSellers',
            'pendingProducts',
            'openComplaints',
            'recentUsers',
            'recentProducts'
        ));
    }
}
