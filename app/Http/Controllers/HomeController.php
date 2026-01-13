<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // -------------------------
    // HOME PAGE
    // -------------------------
    public function index()
    {
        // Best Sellers (products with sales)
        $bestSellers = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved') // assuming same column as buyer dashboard
            ->whereHas('orderItems') // only products with at least 1 sale
            ->withCount([
                'orderItems as total_sold' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }
            ])
            ->orderByDesc('total_sold')
            ->take(8)
            ->get();

        // Latest Products (latest approved products)
        $latestProducts = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved')
            ->latest()
            ->take(8)
            ->get();

        // Top Sellers (approved sellers, ranked by average rating)
        $topSellers = Seller::with('user')
            ->withAvg('reviews', 'rating') // through hasManyThrough from products
            ->where('verification_status', 'Approved')
            ->orderByDesc('reviews_avg_rating')
            ->take(4)
            ->get();

        // Categories for filtering
        $categories = Category::all();

        return view('guest.home', compact(
            'bestSellers',
            'latestProducts',
            'topSellers',
            'categories'
        ));
    }


    // -------------------------
    // SEARCH BAR (HOME PAGE)
    // -------------------------
    public function search(Request $request)
    {
        $search = $request->search;

        return redirect()->route('products.browse', ['search' => $search]);
    }
}
