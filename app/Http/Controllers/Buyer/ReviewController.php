<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    // Show all reviews for the logged-in buyer
    public function index()
    {
        $reviews = Review::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.orders.reviews.index', compact('reviews'));
    }

    // Show review form for a single product
    public function create($productId)
    {
        $product = Product::with('images')->findOrFail($productId);
        $buyer = Auth::user();

        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $buyer->id)
            ->first();

        return view('buyer.orders.reviews.create', compact('product', 'existingReview'));
    }

    // Store review for a single product
    public function store(Request $request, $productId)
    {
        $buyer = Auth::user();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate review
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $buyer->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'user_id' => $buyer->id,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('buyer.reviews.index')->with('success', 'Review submitted successfully!');
    }

    public function order($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Ensure the logged-in user owns this order
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        return view('buyer.reviews.order', compact('order'));
    }

}

