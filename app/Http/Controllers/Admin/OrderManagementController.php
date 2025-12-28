<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product');

        // Optional: filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Optional: search by order ID or buyer name
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('buyer', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('id', $search);
        }

        // Optional: sort orders
        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort == 'amount_high') {
            $query->orderBy('total_amount', 'desc');
        } else { // newest by default
            $query->orderBy('created_at', 'desc');
        }

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
