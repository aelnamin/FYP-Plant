<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'courier_name' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255|unique:deliveries,tracking_number',
        ]);

        $order = Order::findOrFail($orderId);

        // ✅ CORRECT seller ID
        $seller = auth()->user()->seller;

        if (!$seller) {
            return back()->with('error', 'Seller profile not found.');
        }

        $sellerId = $seller->id;

        // ✅ Correct ownership check
        $sellerItems = $order->items()
            ->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->get();

        if ($sellerItems->isEmpty()) {
            return back()->with('error', 'No items in this order belong to you.');
        }

        // Prevent duplicate delivery per seller
        if ($order->deliveries()->where('seller_id', $sellerId)->exists()) {
            return back()->with('error', 'You already shipped this order.');
        }

        // ✅ seller_id will NO LONGER be null
        Delivery::create([
            'order_id' => $order->id,
            'seller_id' => $sellerId,
            'courier_name' => $request->courier_name,
            'tracking_number' => $request->tracking_number,
            'status' => 'Shipped',
            'shipped_at' => now(),
        ]);

        // Update seller_status
        $sellerItems->each(
            fn($item) =>
            $item->update(['seller_status' => 'Shipped'])
        );

        return back()->with('success', 'Order shipped successfully.');
    }



    public function markAsDelivered(Delivery $delivery)
    {
        $sellerId = auth()->user()->seller->id;

        if ($delivery->seller_id !== $sellerId) {
            return back()->with('error', 'You cannot modify this delivery.');
        }

        if ($delivery->delivered_at) {
            return back()->with('error', 'Delivery already marked as delivered.');
        }

        $delivery->update([
            'delivered_at' => now(),
            'status' => 'Delivered',
        ]);

        // Update seller_status for items
        $delivery->order->items()
            ->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->update(['seller_status' => 'Delivered']);

        return back()->with('success', 'Order marked as delivered.');
    }


}