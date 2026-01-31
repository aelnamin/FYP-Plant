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

        // Create Order 
        $order = Order::create([
            'buyer_id' => $user->id,
            'total_amount' => $total,
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

        $sellerId = request()->query('seller');

        // Filter items if seller ID is given
        $items = $sellerId
            ? $order->items->filter(fn($item) => $item->product->seller_id == $sellerId)
            : $order->items;

        // Calculate free delivery based on ALL items in the order
        $orderSubtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
        $shipping = $orderSubtotal >= 150 ? 0 : 10.60;

        // Subtotal for displayed items
        $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);

        // Total
        $total = $subtotal + $shipping;

        $transaction = $order->transaction;

        return view('buyer.transactions.show', compact(
            'order',
            'items',
            'subtotal',
            'shipping',
            'total',
            'transaction'
        ));
    }

}
