<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'items.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order ID or buyer name
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('buyer', function ($qb) use ($search) {
                    $qb->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });

        }

        // Sorting
        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort === 'amount_high') {
            $query->orderBy('total_amount', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->get();

        return view('sellers.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Shipped',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Order status updated');
    }
}
