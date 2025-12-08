<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;

class SellerController extends Controller
{
    public function dashboard()
    {
        return view('sellers.dashboard', [
            'total_products' => Product::where('seller_id', auth()->id())->count(),
            'total_orders' => Order::where('seller_id', auth()->id())->count(),
        ]);
    }
}
