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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Buyer\CartController as BuyerCartController;
use App\Http\Controllers\Buyer\BuyerProfileController;
use App\Http\Controllers\Seller\SellerProfileController;
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
   Route::resource('users', UserManagementController::class);
   Route::get('users/{user}/reports', [UserManagementController::class, 'reports'])->name('users.reports');

   Route::get('/products/pending', [ProductApprovalController::class, 'index'])
      ->name('admin.products.pending');

   Route::post('/products/{id}/approve', [ProductApprovalController::class, 'approve'])
      ->name('admin.products.approve');

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

});
