<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;


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
            return view('sellers.orders.index', [
                'orders' => collect(),
                'sellerId' => null
            ]);
        }

        $sellerId = $seller->id;

        // Fetch all orders containing this seller's products
        $orders = Order::whereHas('items.product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })
            ->with(['items.product.images', 'buyer', 'deliveries'])
            ->latest()
            ->get();

        // Map orders to include seller-specific items, subtotal, delivery, and total
        $orders = $orders->map(function ($order) use ($sellerId) {

            // Filter items that belong to this seller
            $sellerItems = $order->items->filter(fn($item) => $item->product && $item->product->seller_id == $sellerId);

            if ($sellerItems->count() <= 0)
                return null; // skip if no items for this seller

            // Subtotal for this seller
            $productSubtotal = $sellerItems->sum(fn($item) => $item->price * $item->quantity);

            // Total order (all sellers/items) to check for free delivery
            $orderTotal = $order->items->sum(fn($item) => $item->price * $item->quantity);

            // Delivery fee: free if total order >= 150
            $deliveryPerSeller = $orderTotal >= 150 ? 0 : 10.60;

            // Seller total = seller subtotal + delivery
            $sellerTotal = $productSubtotal + $deliveryPerSeller;

            // Attach to order object for Blade
            $order->sellerItems = $sellerItems;
            $order->productSubtotal = $productSubtotal;
            $order->deliveryPerSeller = $deliveryPerSeller;
            $order->sellerTotal = $sellerTotal;
            $order->sellerStatus = $sellerItems->first()->seller_status;

            return $order;
        })
            ->filter() // remove null orders
            ->values(); // reindex collection

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
        $seller = Seller::where('user_id', Auth::id())->firstOrFail();

        // Update only this seller's items
        OrderItem::where('order_id', $orderId)
            ->whereHas('product', fn($q) => $q->where('seller_id', $seller->id))
            ->update(['seller_status' => 'paid']);

        //  Check if ALL items in this order are paid
        $allPaid = OrderItem::where('order_id', $orderId)
            ->where('seller_status', '!=', 'paid')
            ->doesntExist();

        //  If yes → update transaction status
        if ($allPaid) {
            Transaction::where('order_id', $orderId)
                ->update(['status' => 'paid']);
        }


        return back()->with('success', 'Your products have been marked as Paid.');
    }

    public function markAsShipped($orderId)
    {
        $seller = Seller::where('user_id', Auth::id())->firstOrFail();

        // Update only this seller's items
        OrderItem::where('order_id', $orderId)
            ->whereHas('product', fn($q) => $q->where('seller_id', $seller->id))
            ->update(['seller_status' => 'shipped']);

        return back()->with('success', 'Your products have been marked as Shipped.');
    }

    public function markAsDelivered($orderId)
    {
        $seller = Seller::where('user_id', Auth::id())->firstOrFail();

        OrderItem::where('order_id', $orderId)
            ->whereHas('product', fn($q) => $q->where('seller_id', $seller->id))
            ->update(['seller_status' => 'delivered']);

        return back()->with('success', 'Your products have been marked as Delivered.');
    }

}
