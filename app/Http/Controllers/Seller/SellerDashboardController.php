<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\OrderItem;

class SellerDashboardController extends Controller
{
    public function index()
    {
        // Get seller linked to logged-in user
        $seller = Seller::where('user_id', auth()->id())->firstOrFail();

        // Total products
        $total_products = $seller->products()->count();

        // Total orders (sum of order items for seller's products)
        $total_orders = OrderItem::whereIn('product_id', $seller->products->pluck('id'))->count();

        // Recent products (latest 6 products)
        $recentProducts = $seller->products()->latest()->take(6)->get();

        // Optional: sales data for chart
        // Example: monthly sales (you can customize)
        $salesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $salesData = [5, 10, 7, 12, 6, 9];

        return view('sellers.dashboard', compact(
            'seller',
            'total_products',
            'total_orders',
            'recentProducts',
            'salesLabels',
            'salesData'
        ));
    }
}
