<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    // Show all returns of this buyer
    public function index()
    {
        $returns = ReturnRequest::where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('buyer.returns.index', compact('returns'));
    }

    // Show create return form
    public function create($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        // Check if a return request already exists for this order
        $existingReturn = ReturnRequest::where('buyer_id', Auth::id())
            ->where('order_id', $order->id)
            ->first(); // get first matching return

        return view('buyer.returns.create', compact('order', 'existingReturn'));
    }

    // Store new return request
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'request_type' => 'required|in:refund,replacement',
            'reason' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048',
        ]);

        // Verify order belongs to buyer
        $order = Order::where('id', $request->order_id)
            ->where('buyer_id', Auth::id())
            ->with('items')
            ->firstOrFail();


        if ($order->items->isEmpty()) {
            abort(500, 'No items found for this order.');
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '_' . $file->getClientOriginalName();

            // Move file to public/uploads/returns
            $file->move(public_path('uploads/returns'), $filename);

            // Save path for DB
            $imagePath = 'uploads/returns/' . $filename;
        }



        // Create return request for EACH item
        foreach ($order->items as $item) {
            ReturnRequest::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $item->product->seller_id, // each item may have a different seller
                'request_type' => $request->request_type,
                'reason' => $request->reason,
                'image' => $imagePath,
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('buyer.returns.index')
            ->with('success', 'Return request submitted successfully.');
    }



}