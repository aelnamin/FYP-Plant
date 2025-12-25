<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /**
     * Ensure only authenticated sellers can access
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:seller']);
    }

    /**
     * Show all Paid or Shipped orders that contain this seller's products
     */
    public function index()
    {
        $sellerId = Auth::id();

        $orders = Order::whereHas('items', function ($query) use ($sellerId) {
            // Only orders that have at least one product from this seller
            $query->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        })
            ->whereIn('status', ['Paid', 'Shipped'])
            ->with([
                'items.product',       // eager load products
                'items.product.seller',// eager load seller info
                'buyer'                // load buyer info
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sellers.orders.index', compact('orders'));
    }

    /**
     * Show details of a single order for this seller
     */
    public function show($orderId)
    {
        $sellerId = Auth::id();

        $order = Order::whereHas('items', function ($query) use ($sellerId) {
            $query->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        })
            ->with(['items.product', 'items.product.seller', 'buyer'])
            ->findOrFail($orderId);

        return view('sellers.orders.show', compact('order'));
    }

    /**
     * Mark an order as Shipped (only Paid orders)
     */
    public function markAsShipped($orderId)
    {
        $sellerId = Auth::id();

        $order = Order::whereHas('items', function ($query) use ($sellerId) {
            $query->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        })->findOrFail($orderId);

        if ($order->status !== 'Paid') {
            return back()->with('error', 'Only Paid orders can be marked as Shipped.');
        }

        $order->update([
            'status' => 'Shipped'
        ]);

        return back()->with('success', 'Order marked as Shipped successfully.');
    }
}
