<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Seller\SellerRegisterController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Buyer\BuyerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Seller\DeliveryController;
use App\Http\Controllers\Buyer\ReviewController;
use App\Http\Controllers\Buyer\TransactionController;
use App\Http\Controllers\Seller\OrderManagementController;
use App\Http\Controllers\Admin\AdminComplaintController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\Buyer\ChatController as BuyerChatController;
use App\Http\Controllers\Seller\ChatController as SellerChatController;
use App\Http\Controllers\Buyer\CartController as BuyerCartController;
use App\Http\Controllers\Seller\PlantMonitoringController;
use App\Http\Controllers\Buyer\BuyerProfileController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\Admin\ProductApprovalController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Home & Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');
Route::get('/browse', [ProductController::class, 'browse'])->name('products.browse');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [LoginController::class, 'processLogin']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/* -----------------------------------------
   SELLER REGISTRATION (NEW)
------------------------------------------*/
Route::get('/seller/register', [SellerRegisterController::class, 'showForm'])
   ->name('seller.register');

Route::post('/seller/register', [SellerRegisterController::class, 'register'])
   ->name('seller.register.submit');

/*
|--------------------------------------------------------------------------
| Buyer Cart Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->group(function () {
   Route::get('/cart', [BuyerCartController::class, 'index'])->name('buyer.cart');
   Route::get('/buyer/cart/sidebar', [BuyerCartController::class, 'sidebar'])->name('buyer.cart.sidebar');
   Route::post('/cart/add/{id}', [BuyerCartController::class, 'add'])->name('cart.add');
   Route::put('/cart/update/{id}', [BuyerCartController::class, 'update'])->name('cart.update');
   Route::delete('/cart/remove/{id}', [BuyerCartController::class, 'remove'])->name('cart.remove');

});


/*
 |----------------------------------------------------------------------
 | Admin Routes
 |----------------------------------------------------------------------
 */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
   Route::get('/dashboard', [AdminDashboardController::class, 'index'])
      ->name('dashboard');

   Route::resource('users', UserManagementController::class);
   Route::get('users/{user}/reports', [UserManagementController::class, 'reports'])->name('users.reports');

   Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
   Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
   Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
   Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');

   Route::get('/products/pending', [ProductApprovalController::class, 'index'])
      ->name('products.pending');

   Route::post('/products/{id}/approve', [ProductApprovalController::class, 'approve'])->name('products.approve');
   Route::post('/products/{id}/reject', [ProductApprovalController::class, 'reject'])->name('products.reject');

   Route::get('/sellers', [SellerManagementController::class, 'index'])->name('sellers.index');
   Route::get('/sellers/{seller}', [SellerManagementController::class, 'show'])->name('sellers.show');
   Route::post('/sellers/{seller}/approve', [SellerManagementController::class, 'approve'])->name('sellers.approve');
   Route::post('/sellers/{seller}/reject', [SellerManagementController::class, 'reject'])->name('sellers.reject');

   // Payment simulation: mark order as Paid
   Route::post('orders/{order}/mark-paid', [PaymentController::class, 'markAsPaid'])
      ->name('orders.markPaid');

   // view all orders
   Route::get('orders', [PaymentController::class, 'index'])
      ->name('orders.index');
   Route::get('orders/{order}', [PaymentController::class, 'show'])
      ->name('orders.show');

   Route::resource('complaints', ComplaintController::class);

   //reports
   Route::get('/reports', [ReportController::class, 'index'])
      ->name('reports.index');

   Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
   Route::get('/complaints/statistics', [AdminComplaintController::class, 'statistics'])->name('complaints.statistics');
   Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
   Route::put('/complaints/{complaint}', [AdminComplaintController::class, 'update'])->name('complaints.update');
   Route::post('/complaints/{complaint}/assign', [AdminComplaintController::class, 'assign'])->name('complaints.assign');

});


/*
|--------------------------------------------------------------------------
| Seller Dashboard Route
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seller'])
   ->prefix('seller')
   ->group(function () {
      Route::get('/dashboard', [SellerDashboardController::class, 'index'])
         ->name('sellers.dashboard');
   });


/*
 |----------------------------------------------------------------------
 | Seller Routes
 |----------------------------------------------------------------------
 */
Route::middleware(['auth', 'role:seller'])
   ->prefix('seller/inventory')
   ->name('sellers.inventory.')
   ->group(function () {


      Route::get('/', [InventoryController::class, 'index'])->name('index');

      Route::get('/create', [InventoryController::class, 'create'])->name('create');
      Route::post('/', [InventoryController::class, 'store'])->name('store');

      Route::get('/{id}', [InventoryController::class, 'show'])->name('show');
      Route::get('/{id}/edit', [InventoryController::class, 'edit'])->name('edit');

      Route::put('/{id}', [InventoryController::class, 'update'])->name('update');
      Route::delete('/{id}', [InventoryController::class, 'destroy'])->name('destroy');

   });

Route::middleware(['auth', 'role:seller'])
   ->prefix('seller')
   ->name('sellers.')
   ->group(function () {
      Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile');
      Route::put('/profile', [SellerProfileController::class, 'update'])->name('profile.update');

      Route::get('products/create', [ProductController::class, 'create'])->name('products.create');

      Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
      Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
      Route::post('/orders/{order}/ship', [SellerOrderController::class, 'markAsShipped'])->name('orders.ship');

      // Plant Monitoring
      Route::get('/plants', [PlantMonitoringController::class, 'index'])
         ->name('plants.index');

      Route::post('/plants/{product}/growth', [PlantMonitoringController::class, 'storeGrowth'])
         ->name('plants.growth.store');

      Route::post('/plants/{product}/care', [PlantMonitoringController::class, 'storeCare'])
         ->name('plants.care.store');

      // API routes for JS fetch
      Route::get('/plants/{product}/growth-data', [PlantMonitoringController::class, 'getGrowthData'])
         ->name('plants.growth.data');

      Route::get('/plants/{product}/care-data', [PlantMonitoringController::class, 'getCareData'])
         ->name('plants.care.data');

      //seller chat route
   
      Route::get('/chats', [SellerChatController::class, 'index'])
         ->name('chats.index');

      Route::get('/chats/{buyer}', [SellerChatController::class, 'show'])
         ->name('chats.show');

      Route::post('/chats/{buyer}/send', [SellerChatController::class, 'send'])
         ->name('chats.send');

      // routes/web.php
      Route::get(
         '/plants/{product}/care-report',
         [PlantMonitoringController::class, 'printCareReport']
      )->name('plants.care-report');

      Route::get('/plants', [PlantMonitoringController::class, 'index'])->name('plants.index'); // list all plants
      Route::get('/plants/{plant}', [PlantMonitoringController::class, 'show'])->name('plants.show'); // show single plant
   
      //delivery route
      Route::post(
         'orders/{order}/delivery',
         [DeliveryController::class, 'store']
      )->name('deliveries.store');

      Route::patch(
         '/deliveries/{delivery}/delivered',
         [DeliveryController::class, 'markAsDelivered']
      )->name('deliveries.delivered');


      Route::post('orders/{order}/paid', [SellerOrderController::class, 'markAsPaid'])->name('orders.paid');
      Route::post('orders/{order}/shipped', [SellerOrderController::class, 'markAsShipped'])->name('orders.shipped');

   });


//Buyer
Route::middleware(['auth', 'role:buyer'])->group(function () {

   Route::get('buyer/checkout', [CheckoutController::class, 'checkout'])
      ->name('buyer.checkout');

   Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
      ->name('checkout.placeOrder');

   Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])
      ->name('buyer.dashboard');

   // Profile
   Route::get('/profile', [BuyerProfileController::class, 'index'])
      ->name('buyer.profile');

   Route::put('/profile/update', [BuyerProfileController::class, 'update'])
      ->name('buyer.profile.update');

   Route::post(
      '/place-order',
      [TransactionController::class, 'store']
   )->name('buyer.place.order');

   Route::get('/buyer/orders/{order}', [OrderController::class, 'show'])
      ->name('buyer.orders.details');

   Route::get(
      '/transactions',
      [TransactionController::class, 'index']
   )->name('buyer.transactions.index');

   Route::get(
      '/transactions/{order}',
      [TransactionController::class, 'show']
   )->name('buyer.transactions.show');

   // Review routes (per product)
   Route::get('reviews', [ReviewController::class, 'index'])->name('buyer.reviews.index'); // My Reviews page
   Route::get('reviews/{product}', [ReviewController::class, 'create'])->name('buyer.reviews.create'); // Leave review
   Route::post('reviews/{product}', [ReviewController::class, 'store'])->name('buyer.reviews.store'); // Submit review



   Route::get('/buyer/order/{id}', [OrderController::class, 'show'])
      ->name('buyer.orders.show');

   Route::get('/buyer/order/{id}', [OrderController::class, 'show'])
      ->name('buyer.order-details');

   // For viewing specific seller's items within an order
   Route::get('/orders/{order}/{seller}', [OrderController::class, 'show'])
      ->name('buyer.order-details.seller');

   // For viewing entire order (all sellers or single seller order)
   Route::get('/orders/{order}', [OrderController::class, 'show'])
      ->name('buyer.order-details');



   Route::get('/buyer/orders/{order}/review', [OrderController::class, 'showReviewPage'])
      ->name('buyer.orders.review');

   Route::get('/orders/{order}/reviews', [ReviewController::class, 'reviewOrder'])
      ->name('orders.reviews.index');

   Route::post('/orders/{order}/reviews', [ReviewController::class, 'storeOrderReviews'])
      ->name('orders.reviews.store');
   Route::get('/reviews/create/{product}', [ReviewController::class, 'create'])
      ->name('buyer.reviews.create');

   //Chat routes

   Route::get('/chats', [BuyerChatController::class, 'index'])
      ->name('buyer.chats.index');

   Route::get('/chats/{seller}', [BuyerChatController::class, 'show'])
      ->name('buyer.chats.show');

   Route::post('/chats/{seller}/send', [BuyerChatController::class, 'send'])
      ->name('buyer.chats.send');

   Route::get('/buyer/chats/{seller}/fetch', [BuyerChatController::class, 'fetchNewMessages'])
      ->name('buyer.chats.fetch');


   // NEW: start chat (redirect to show page)
   Route::get('/chats/start/{seller}', [\App\Http\Controllers\Buyer\ChatController::class, 'startChat'])->name('buyer.chats.start');

   //transaction
   Route::get('buyer/transactions/{order}', [TransactionController::class, 'show'])
      ->name('buyer.transactions.show');


   Route::get('/help-center', [HelpCenterController::class, 'index'])
      ->name('buyer.help-center');

   Route::resource('complaints', ComplaintController::class);
   Route::get('/complaints/create/{order_id?}', [ComplaintController::class, 'create'])
      ->name('complaints.create.with-order');
   Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');


   Route::patch('/buyer/orders/{order}/received', [OrderController::class, 'markAsReceived'])
      ->name('buyer.orders.received');
});

Route::get('/privacy-policy', function () {
   return view('privacy-policy');
})->name('privacy.policy');





