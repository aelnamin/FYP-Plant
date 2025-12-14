<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;

class BuyerDashboardController extends Controller
{
    /**
     * Ensure only authenticated buyers can access this controller
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:buyer']);
    }

    /**
     * Show Buyer Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Best Sellers (example: latest approved products)
        $bestSellers = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved')
            ->latest()
            ->take(8)
            ->get();

        // Latest Products (latest approved products)
        $latestProducts = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved')
            ->latest()
            ->take(8)
            ->get();

        // Top Sellers (approved sellers)
        $topSellers = Seller::where('verification_status', 'Approved')
            ->latest()
            ->take(4)
            ->get();

        return view('buyer.dashboard', compact(
            'user',
            'bestSellers',
            'latestProducts',
            'topSellers'
        ));
    }
}
