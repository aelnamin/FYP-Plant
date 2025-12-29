<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Share "not shipped yet" orders count with seller layout
        View::composer('layouts.sellers-main', function ($view) {

            // Ensure user is logged in
            if (!Auth::check()) {
                return;
            }

            $seller = Seller::where('user_id', Auth::id())->first();
            if (!$seller) {
                return;
            }

            // Get seller product IDs
            $sellerProductIds = Product::where('seller_id', $seller->id)->pluck('id');

            // Count orders NOT shipped yet (exclude shipped & delivered)
            $notShippedOrdersCount = Order::whereHas('items', function ($q) use ($sellerProductIds) {
                $q->whereIn('product_id', $sellerProductIds);
            })
                ->whereNotIn('status', ['shipped', 'delivered'])
                ->distinct()
                ->count();

            // Share variable with view
            $view->with('notShippedOrdersCount', $notShippedOrdersCount);
        });
    }
}
