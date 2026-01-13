<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Best Sellers (products that have sales)
        $bestSellers = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved')
            ->whereHas('orderItems') // only products with at least 1 sale
            ->withCount([
                'orderItems as total_sold' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }
            ])
            ->orderByDesc('total_sold') // sort by sales
            ->take(8)
            ->get();

        // Latest Products (latest approved products)
        $latestProducts = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved')
            ->latest()
            ->take(8)
            ->get();

        // Top Sellers (approved sellers)
        $topSellers = Seller::with('user')
            ->withAvg('reviews', 'rating') // calculates average rating through products
            ->where('verification_status', 'Approved')
            ->orderByDesc('reviews_avg_rating') // order by average rating
            ->take(4)
            ->get();


        $categories = Category::all();


        return view('buyer.dashboard', compact(
            'user',
            'categories',
            'bestSellers',
            'latestProducts',
            'topSellers'
        ));
    }
}
