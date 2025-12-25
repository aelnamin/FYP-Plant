<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    // List all orders for admin
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Show single order details
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    // Mark order as Paid (simulation)
    public function markAsPaid(Order $order)
    {
        $order->update(['status' => 'Paid']);
        return back()->with('success', "Order #{$order->id} marked as Paid.");
    }
}


