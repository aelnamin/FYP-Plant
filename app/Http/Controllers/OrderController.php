<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['buyer', 'items'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'total' => 'required|numeric'
        ]);

        return Order::create($validated);
    }


    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product.images', 'buyer', 'items.product.seller', 'delivery', 'transaction',])
            ->where('id', $id)
            ->where('buyer_id', auth()->id())
            ->firstOrFail(); // ✅ FIX

        // Optional seller filter
        $sellerId = $request->query('seller');
        $selectedSeller = null;

        if ($sellerId) {
            $items = $order->items->filter(
                fn($item) => $item->product->seller_id == $sellerId
            );

            $selectedSeller = $order->items
                ->first(fn($item) => $item->product->seller_id == $sellerId)
                ?->product
                    ?->seller;
        } else {
            $items = $order->items;
        }

        $sellerCount = $sellerId
            ? 1
            : $items->pluck('product.seller_id')->unique()->count();

        $deliveryPerSeller = 10.60;
        $deliveryFee = $deliveryPerSeller * $sellerCount;
        $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
        $total = $subtotal + $deliveryFee;

        return view('buyer.order-details', compact(
            'order',
            'items',
            'sellerId',
            'selectedSeller',
            'subtotal',
            'deliveryFee',
            'total',
            'sellerCount'
        ));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'buyer_id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|string',
            'total' => 'sometimes|numeric'
        ]);

        $order->update($validated);

        return $order;
    }

    public function destroy($id)
    {
        return Order::destroy($id);
    }
    public function showReviewPage($id)
    {
        $order = Order::with(['items.product.seller', 'items.product.images'])
            ->findOrFail($id);

        // Check if order is eligible for review
        if (strtoupper($order->status) !== 'SHIPPED' && strtoupper($order->status) !== 'DELIVERED') {
            return redirect()->back()->with('error', 'This order is not yet eligible for review.');
        }

        return view('buyer.orders.review-order', compact('order'));
    }

    public function markAsReceived(Request $request, Order $order)
    {
        // Only allow buyer to mark order as received
        $user = auth()->user();

        if ($order->buyer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow if order is delivered
        if (!in_array(strtoupper($order->status), ['DELIVERED'])) {
            return redirect()->back()->with('error', 'You cannot mark this order as received.');
        }

        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(), // optional, if you track completion
        ]);

        return redirect()->back()->with('success', 'Order marked as received!');
    }

    // Buyer/OrderController.php
    public function received(Order $order)
    {
        $order->status = 'COMPLETED';
        $order->save();

        // Return JSON response for AJAX
        return response()->json([
            'status' => strtoupper($order->status),
            'statusInfo' => [
                'bg' => 'bg-success',
                'textColor' => 'text-white',
                'text' => 'Order Completed',
                'icon' => 'check-circle'
            ]
        ]);
    }


}
