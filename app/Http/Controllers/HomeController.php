<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // -------------------------
    // HOME PAGE
    // -------------------------
    public function index()
    {
        // Best seller = products with highest sales or random if no sales table
        $bestSellers = Product::with('images', 'seller')
            ->where('approved_by', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Latest products
        $latestProducts = Product::with('images', 'seller')
            ->where('approved_by', true)
            ->latest()
            ->take(4)
            ->get();

        // Top Sellers
        $topSellers = Seller::take(4)->get();

        return view('guest/home', compact('bestSellers', 'latestProducts', 'topSellers'));
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
