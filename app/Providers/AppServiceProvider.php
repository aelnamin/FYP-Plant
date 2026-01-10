<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Message;

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
                $q->whereIn('product_id', $sellerProductIds)
                    ->whereNotIn('seller_status', ['shipped', 'delivered', 'completed', 'cancelled']);
            })
                ->distinct('id')
                ->count('id');

            // Share variable with view
            $view->with('notShippedOrdersCount', $notShippedOrdersCount);

        });

        View::composer('layouts.sellers-main', function ($view) {

            if (!Auth::check()) {
                return;
            }

            $seller = Seller::where('user_id', Auth::id())->first();
            if (!$seller) {
                return;
            }

            $sellerProductIds = Product::where('seller_id', $seller->id)->pluck('id');

            $notShippedOrdersCount = Order::whereHas('items', function ($q) use ($sellerProductIds) {
                $q->whereIn('product_id', $sellerProductIds)
                    ->whereNotIn('seller_status', ['shipped', 'delivered', 'completed', 'cancelled']);
            })
                ->distinct('id')
                ->count('id');

            $view->with('notShippedOrdersCount', $notShippedOrdersCount);
        });

        View::composer('layouts.main', function ($view) {

            if (!Auth::check() || Auth::user()->role !== 'buyer') {
                return;
            }

            $unreadCount = Message::where('receiver_id', Auth::id())
                ->whereNull('deleted_at')
                ->count(); // add ->whereNull('read_at') if you have it

            $view->with('unreadCount', $unreadCount);
        });

    }

}
