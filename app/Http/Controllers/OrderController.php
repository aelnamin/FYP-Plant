<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $order = Order::with([
            'items.product.images',
            'buyer',
            'items.product.seller',
            'delivery',
            'transaction',
        ])
            ->where('id', $id)
            ->where('buyer_id', auth()->id())
            ->firstOrFail();

        // Seller filter
        $sellerId = $request->query('seller');
        $selectedSeller = null;

        if ($sellerId) {
            $items = $order->items->filter(
                fn($item) => $item->product->seller_id == $sellerId
            );

            $selectedSeller = optional(
                $order->items->first(fn($item) => $item->product->seller_id == $sellerId)
            )->product?->seller;
        } else {
            $items = $order->items;
        }

        // =========================
        // ✅ ORDER-LEVEL SUBTOTAL
        // =========================
        $orderSubtotal = $order->items->sum(
            fn($item) => $item->price * $item->quantity
        );

        // =========================
        // ✅ DELIVERY RULE (SAME AS PROFILE)
        // =========================
        $deliveryFee = $orderSubtotal >= 150 ? 0 : 10.60;

        // =========================
        // DISPLAY TOTAL (CURRENT VIEW)
        // =========================
        $subtotal = $items->sum(
            fn($item) => $item->price * $item->quantity
        );

        $total = $subtotal + $deliveryFee;

        return view('buyer.order-details', compact(
            'order',
            'items',
            'sellerId',
            'selectedSeller',
            'subtotal',
            'deliveryFee',
            'total'
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
        $user = auth()->user();

        // Ensure the buyer owns the order
        if ($order->buyer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // seller filter
        $sellerId = $request->query('seller');

        // Update items
        if ($sellerId) {
            $order->items()
                ->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
                ->update(['seller_status' => 'completed']);
        } else {
            $order->items()->update(['seller_status' => 'completed']);
        }

        // Update overall order status
        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(), // timestamp
        ]);

        return redirect()->back()->with('success', 'Order marked as received!');
    }


    // Buyer/OrderController.php
    public function received(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->buyer_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sellerId = $request->query('seller');

        // Update items
        if ($sellerId) {
            $order->items()
                ->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
                ->update(['seller_status' => 'completed']);
        } else {
            $order->items()->update(['seller_status' => 'completed']);
        }

        // Update order status
        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
        ]);

        // Return updated status info
        return response()->json([
            'status' => 'COMPLETED',
            'statusInfo' => [
                'bg' => 'bg-success',
                'textColor' => 'text-white',
                'text' => 'Order Completed',
                'icon' => 'check-circle',
            ]
        ]);
    }


}
