<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index()
    {
        // Seller sees all orders containing their products
        $orders = Order::with('items.product')
            ->latest()
            ->get();

        return view('sellers.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Shipped'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated');
    }
}
