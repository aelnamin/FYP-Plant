<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Seller;

class SellerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:seller']);
    }

    /**
     * Show all Paid or Shipped orders containing this seller's products
     */
    public function index()
    {
        // Get the seller record for the logged-in user
        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller) {
            // No seller record found for this user
            return view('sellers.orders.index', ['orders' => collect(), 'sellerId' => null]);
        }

        $sellerId = $seller->id; // This is the seller_id used in products table

        // Fetch orders that contain at least one product from this seller
        $orders = Order::whereIn('status', ['Pending', 'Paid', 'Shipped'])
            ->whereHas('items.product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->with(['items.product.images', 'buyer'])
            ->latest()
            ->get();

        return view('sellers.orders.index', compact('orders', 'sellerId'));
    }

    /**
     * Show single order details for this seller
     */
    public function show($orderId)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        if (!$seller) {
            abort(403, "You don't have a seller account.");
        }

        $sellerId = $seller->id;

        $order = Order::with(['items.product.images', 'buyer'])
            ->findOrFail($orderId);

        return view('sellers.orders.show', compact('order', 'sellerId'));
    }

    public function markAsPaid($orderId)
    {
        $order = Order::where('status', 'Pending')->findOrFail($orderId);
        $order->update(['status' => 'Paid']);
        return back()->with('success', 'Order marked as Paid.');
    }

    /**
     * Mark order as Shipped
     */
    public function markAsShipped($orderId)
    {

        $order = Order::where('status', 'Paid')->findOrFail($orderId);
        $order->update(['status' => 'Shipped']);

        return back()->with('success', 'Order marked as shipped.');
    }
}
