<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Buyer\BuyerProfileController;
use App\Http\Controllers\Admin\ProductApprovalController;
use App\Http\Controllers\Seller\InventoryController;

/* -------------------------
   HOME PAGE
-------------------------- */

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');
Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])->name('buyer.dashboard');
/* -------------------------
   AUTHENTICATION
-------------------------- */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
/* -------------------------
   PUBLIC PRODUCTS
-------------------------- */
Route::get('/browse', [ProductController::class, 'browse'])->name('products.browse');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');


/* -------------------------
   CART 
-------------------------- */
Route::middleware(['auth', 'role:buyer'])->group(function () {
   Route::get('/buyer/cart', [CartController::class, 'index'])->name('buyer.cart');
   Route::post('/buyer/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
   Route::patch('/buyer/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
   Route::delete('/buyer/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});



/* -------------------------
   PROTECTED ROUTES
-------------------------- */
Route::middleware('auth')->group(function () {

   /* -------------------------
        ADMIN AREA
     -------------------------- */
   Route::middleware('admin')->group(function () {
      Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])
         ->name('admin.dashboard');

      Route::get('/admin/products/pending', [ProductApprovalController::class, 'index'])
         ->name('admin.products.pending');

      Route::post('/admin/products/{id}/approve', [ProductApprovalController::class, 'approve'])
         ->name('admin.products.approve');
   });


   /* -------------------------
        SELLER AREA
     -------------------------- */
   Route::middleware('seller')->group(function () {
      Route::get('/seller/dashboard', [AuthController::class, 'sellerDashboard'])
         ->name('seller.dashboard');

      Route::get('/seller/inventory', [InventoryController::class, 'index'])
         ->name('seller.inventory');

      Route::get('/seller/inventory/create', [InventoryController::class, 'create'])
         ->name('seller.inventory.create');

      Route::post('/seller/inventory/store', [InventoryController::class, 'store'])
         ->name('seller.inventory.store');

      Route::get('/seller/inventory/{id}/edit', [InventoryController::class, 'edit'])
         ->name('seller.inventory.edit');

      Route::post('/seller/inventory/{id}/update', [InventoryController::class, 'update'])
         ->name('seller.inventory.update');

      Route::delete('/seller/inventory/{id}/delete', [InventoryController::class, 'destroy'])
         ->name('seller.inventory.delete');
   });


   /* -------------------------
        BUYER AREA
     -------------------------- */
   Route::middleware('role:buyer')->prefix('buyer')->group(function () {

      Route::get('/dashboard', [AuthController::class, 'buyerDashboard'])
         ->name('buyer.dashboard');

      // Profile
      Route::get('/profile', [BuyerProfileController::class, 'index'])
         ->name('buyer.profile');

      Route::put('/profile/update', [BuyerProfileController::class, 'update'])
         ->name('buyer.profile.update');

      // Password
      Route::get('/profile/change-password', [BuyerProfileController::class, 'changePasswordForm'])
         ->name('buyer.password.change');

      Route::post('/profile/change-password', [BuyerProfileController::class, 'updatePassword'])
         ->name('buyer.password.update');
   });
});
