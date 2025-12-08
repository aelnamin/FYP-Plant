<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItem::with(['order', 'product'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'price'      => 'required|numeric'
        ]);

        return OrderItem::create($validated);
    }

    public function show($id)
    {
        return OrderItem::with(['order', 'product'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'order_id'   => 'sometimes|exists:orders,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity'   => 'sometimes|integer|min:1',
            'price'      => 'sometimes|numeric'
        ]);

        $item->update($validated);

        return $item;
    }

    public function destroy($id)
    {
        return OrderItem::destroy($id);
    }
}
