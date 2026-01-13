<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    // Show the seller shop page
    public function sellerShop(Seller $seller)
    {
        // Eager load all necessary relationships
        $seller->load([
            'products' => function ($query) {
                $query->with(['images', 'category'])
                    ->where('approval_status', 'approved') // Only show approved products
                    ->orderBy('created_at', 'desc');
            },
            'user'
        ]);

        // Calculate total sold count for each product
        $seller->products->each(function ($product) {
            $product->total_sold = DB::table('order_items')
                ->where('product_id', $product->id)
                ->sum('quantity');
        });

        // Calculate shop stats
        $seller->products_count = $seller->products->count();
        $seller->in_stock_products_count = $seller->products->where('stock_quantity', '>', 0)->count();

        // Calculate total orders for this seller
        $seller->orders_count = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('orders.status', 'completed') // Only count completed orders
            ->distinct('orders.id')
            ->count('orders.id');

        // Get best selling products (top 4) - based on actual sales data
        $bestSellers = Product::where('seller_id', $seller->id)
            ->where('approval_status', 'approved')
            ->with(['images', 'category'])
            ->whereHas('orderItems')
            ->withCount([
                'orderItems as total_sold' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }
            ])
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // Calculate seller rating (from all product reviews)
        $ratingData = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.id) as total_reviews')
            ->first();

        $averageRating = round($ratingData->avg_rating ?? 0, 1);
        $totalReviews = $ratingData->total_reviews ?? 0;


        return view('buyer.seller-shop', compact(
            'seller',
            'bestSellers',
            'averageRating',
            'totalReviews'
        ));

    }

    // Alternative simplified method
    public function sellerShopAlternative(Seller $seller)
    {
        // Load seller with user relationship
        $seller->load(['user']);

        // Get approved products with their images and category
        $products = Product::where('seller_id', $seller->id)
            ->where('approval_status', 'approved')
            ->with(['images', 'category'])
            ->latest()
            ->get();

        // Calculate total sold for each product
        foreach ($products as $product) {
            $product->total_sold = DB::table('order_items')
                ->where('product_id', $product->id)
                ->sum('quantity');
        }

        $seller->products = $products;
        $seller->products_count = $products->count();
        $seller->in_stock_products_count = $products->where('stock_quantity', '>', 0)->count();

        // Get best selling products (top 4)
        $bestSellers = Product::where('seller_id', $seller->id)
            ->where('approval_status', 'approved')
            ->with(['images', 'category'])
            ->whereHas('orderItems')
            ->withCount([
                'orderItems as total_sold' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }
            ])
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // Calculate total orders for this seller
        $seller->orders_count = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('orders.status', 'completed')
            ->distinct('orders.id')
            ->count('orders.id');

        return view('buyer.seller-shop', compact('seller', 'bestSellers'));
    }
}