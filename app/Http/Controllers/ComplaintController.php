<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class ComplaintController extends Controller
{
    /**
     * List buyer's complaints
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // adjust per-page number as needed


        return view('buyer.complaints.index', compact('complaints'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $problemTypes = Complaint::getProblemTypes();

        $orders = Order::where('buyer_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->get();



        return view('buyer.complaints.create', compact('problemTypes', 'orders'));
    }

    /**
     * Store complaint
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_item' => 'required|string',
            'problem_type' => 'required|in:' . implode(',', array_keys(Complaint::getProblemTypes())),
            'complaint_message' => 'required|string|min:10',
        ]);

        // Ensure correct format
        if (!str_contains($request->order_item, '-')) {
            return back()->withErrors([
                'order_item' => 'Invalid order item selection'
            ])->withInput();
        }

        // Split order_id and product_id
        [$orderId, $productId] = explode('-', $request->order_item);

        // Find order item
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->with('product')
            ->firstOrFail();

        // Get seller from product
        $sellerId = $orderItem->product->seller_id;

        // Save complaint
        Complaint::create([
            'user_id' => Auth::id(),
            'order_id' => $orderId,
            'seller_id' => $sellerId,
            'problem_type' => $request->problem_type,
            'complaint_message' => $request->complaint_message,
            'status' => Complaint::STATUS_PENDING,
        ]);

        return redirect()
            ->route('complaints.index')
            ->with('success', 'Complaint submitted successfully.');
    }

    /**
     * View complaint
     */
    public function show(Complaint $complaint)
    {
        abort_if($complaint->user_id !== Auth::id(), 403);

        return view('buyer.complaints.show', compact('complaint'));
    }
}