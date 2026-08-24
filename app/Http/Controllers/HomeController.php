<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Seller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // -------------------------
    // HOME PAGE
    // -------------------------
    public function index()
    {
        // Best Sellers (products with sales)
        $salesByProduct = OrderItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('product_id');

        $bestSellers = Product::forStorefrontCards()
            ->joinSub($salesByProduct, 'sales', function ($join) {
                $join->on('sales.product_id', '=', 'products.id');
            })
            ->addSelect('sales.total_sold')
            ->where('approval_status', 'Approved')
            ->orderByDesc('total_sold')
            ->take(8)
            ->get();

        // Latest Products (latest approved products)
        $latestProducts = Product::forStorefrontCards()
            ->where('approval_status', 'Approved')
            ->latest('products.created_at')
            ->take(8)
            ->get();

        // Top Sellers (approved sellers, ranked by average rating)
        $topSellers = Seller::storefrontTopSellers()
            ->take(4)
            ->get();

        // Categories for filtering
        $categories = Category::query()
            ->select(['id', 'category_name'])
            ->orderBy('id')
            ->get();

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
