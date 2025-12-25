<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function dashboard()
    {
        $sellerId = Auth::id();

        // Total products owned by this seller
        $totalProducts = Product::where('seller_id', $sellerId)->count();

        // Total orders that contain this seller's products
        $totalOrders = Order::whereHas('items.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->count();

        return view('sellers.dashboard', [
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
        ]);
    }
}

