<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('buyer.transactions.index', compact('orders'));
    }

    public function store()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Prevent overselling
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return redirect()->back()
                    ->with('error', 'Insufficient stock for ' . $item->product->product_name);
            }
        }

        // Calculate total
        $total = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create Order ✅
        $order = Order::create([
            'buyer_id' => $user->id,
            'total_amount' => $total,   // ✅ FIXED
            'status' => 'Pending',
        ]);

        // Create Order Items + reduce stock
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant' => $item->variant,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->decrement('stock_quantity', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        return redirect()
            ->route('buyer.transactions.show', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function cancel(Order $order)
    {
        abort_if($order->buyer_id !== auth()->id(), 403);

        if ($order->status === 'Shipped') {
            return back()->with('error', 'Order already shipped and cannot be cancelled.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Order cancelled and stock restored.');
    }

    public function show(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);

        // Get seller ID from query parameter
        $sellerId = request()->query('seller');

        // If no seller ID, show all items or redirect
        if (!$sellerId) {
            return redirect()->route('buyer.order-details', ['order' => $order->id]);
        }

        // Filter items by seller
        $items = $order->items->filter(fn($item) => $item->product->seller_id == $sellerId);

        $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
        $shipping = 10.60;
        $total = $subtotal + $shipping;

        $transaction = $order->transaction;

        return view('buyer.transactions.show', compact('order', 'items', 'subtotal', 'shipping', 'total', 'transaction'));
    }

}
