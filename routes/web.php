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
use App\Http\Controllers\Buyer\ReviewController;
use App\Http\Controllers\Buyer\TransactionController;
use App\Http\Controllers\Seller\OrderManagementController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
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

      Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
      Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
      Route::post('/orders/{order}/ship', [SellerOrderController::class, 'markAsShipped'])->name('orders.ship');

      Route::get('/plants/monitor', [PlantMonitoringController::class, 'index'])
         ->name('plants.monitor');

      Route::post('/plants/{product}/growth', [PlantMonitoringController::class, 'storeGrowth'])
         ->name('plants.growth.store');

      Route::post('/plants/{product}/care', [PlantMonitoringController::class, 'storeCare'])
         ->name('plants.care.store');

      Route::get('/seller/plants', [PlantMonitoringController::class, 'index'])
         ->name('seller.plants.monitor');

      Route::get('/seller/plants', [PlantMonitoringController::class, 'index'])
         ->name('seller.plants.monitor');

      Route::post(
         '/seller/plants/{product}/growth',
         [PlantMonitoringController::class, 'storeGrowth']
      )->name('seller.plants.growth.store');

      Route::post(
         '/seller/plants/{product}/care',
         [PlantMonitoringController::class, 'storeCare']
      )->name('seller.plants.care.store');
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


});
