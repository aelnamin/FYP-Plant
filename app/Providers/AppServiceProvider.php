<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\OrderItem;

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
        // Vercel terminates TLS before forwarding the request to PHP. Ensure
        // every generated route, form action, asset URL, and redirect remains
        // HTTPS in production while leaving local HTTP development unchanged.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        // Share "not shipped yet" orders count with seller layout.
        // Keep this as one composer: duplicate registration previously ran
        // the same remote-database queries twice on every seller page.
        View::composer('layouts.sellers-main', function ($view) {

            // Ensure user is logged in
            if (!Auth::check()) {
                return;
            }

            // Resolve the seller and count its outstanding orders in one query.
            // This layout is rendered on every seller page, so avoiding separate
            // seller and product-ID queries materially reduces remote DB traffic.
            $notShippedOrdersCount = OrderItem::query()
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('sellers', 'products.seller_id', '=', 'sellers.id')
                ->where('sellers.user_id', Auth::id())
                ->whereNotIn('order_items.seller_status', [
                    'shipped',
                    'delivered',
                    'completed',
                    'cancelled',
                ])
                ->distinct()
                ->count('order_items.order_id');

            // Share variable with view
            $view->with('notShippedOrdersCount', $notShippedOrdersCount);

        });

    }

}
