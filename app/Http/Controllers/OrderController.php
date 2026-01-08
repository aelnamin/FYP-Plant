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


    public function show($id)
    {
        $order = Order::with(['items.product.images', 'buyer', 'items.product.seller', 'delivery'])
            ->where('buyer_id', auth()->id())
            ->findOrFail($id);

        return view('buyer.order-details', compact('order'));
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

    public function markAsReceived(Order $order)
    {
        // Only allow if order has been shipped
        if (strtoupper($order->status) !== 'SHIPPED') {
            return back()->with('error', 'Order cannot be marked as received.');
        }

        // Update order
        $order->update([
            'status' => 'DELIVERED',    // buyer marks received → Delivered
            'received_at' => now(),
        ]);

        // Update delivery table if exists
        if ($order->delivery) {
            $order->delivery->update([
                'status' => 'Delivered',
                'delivered_at' => now(),
            ]);
        }

        return back()->with('success', 'Order marked as received!');
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
