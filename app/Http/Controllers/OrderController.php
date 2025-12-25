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
        return Order::with(['buyer', 'items'])->findOrFail($id);
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
}
