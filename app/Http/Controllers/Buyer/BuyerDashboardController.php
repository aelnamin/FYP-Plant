<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
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

        // Best Sellers (products that have sales)
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

        // Top Sellers (approved sellers)
        $topSellers = Seller::storefrontTopSellers()
            ->take(4)
            ->get();


        $categories = Category::query()
            ->select(['id', 'category_name'])
            ->orderBy('id')
            ->get();


        return view('buyer.dashboard', compact(
            'user',
            'categories',
            'bestSellers',
            'latestProducts',
            'topSellers'
        ));
    }
}
