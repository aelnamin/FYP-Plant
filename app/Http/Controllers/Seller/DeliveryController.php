<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function store(Request $request, $orderId)
    {

        // Validate input
        $request->validate([
            'courier_name' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255|unique:deliveries,tracking_number',
        ]);

        $order = Order::findOrFail($orderId);

        // Prevent duplicate delivery
        if ($order->delivery) {
            return back()->with('error', 'Delivery already exists for this order.');
        }

        // Create delivery
        Delivery::create([
            'order_id' => $order->id,
            'courier_name' => $request->courier_name,
            'tracking_number' => $request->tracking_number,
            'status' => 'Shipped',
            'shipped_at' => now(),
        ]);

        // Optional: update order status
        $order->update([
            'status' => 'Shipped',
        ]);

        return back()->with('success', 'Delivery created successfully.');
    }

    public function markAsDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);
        $delivery = $order->delivery;

        if ($delivery && !$delivery->delivered_at) {
            $delivery->update([
                'delivered_at' => now(),
                'status' => 'Delivered', // Optional: update status in delivery table
            ]);

            $order->update([
                'status' => 'Delivered', // update order status
            ]);

            return back()->with('success', 'Order marked as delivered.');
        }

        return back()->with('error', 'Delivery not found or already delivered.');
    }

}
