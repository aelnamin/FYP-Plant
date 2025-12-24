<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product.images')->get();

        $cartItems->transform(function ($item) {
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('images/default.png');
            return $item;
        });

        return view('buyer.cart', compact('cartItems'));
    }

    // Add item to cart (AJAX)
    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Make variant optional
        $request->validate([
            'variant' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Use null if variant not selected
        $variant = $request->variant ?? null;

        // Find existing item with same product + variant
        $item = $cart->items()
            ->where('product_id', $id)
            ->where('variant', $variant)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->quantity ?? 1);
        } else {
            $cart->items()->create([
                'product_id' => $id,
                'variant' => $variant,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $cart->items()->sum('quantity')
        ]);
    }

    // Get cart sidebar for AJAX
    public function sidebar()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product.images')->get();

        $cartItems->transform(function ($item) {
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('images/default.png');
            return $item;
        });

        return view('buyer.cart-sidebar', compact('cartItems'));
    }

    // Update quantity for a cart item
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Item removed!');
    }

    // Checkout page
    public function checkout()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product.images')->get();

        $cartItems->transform(function ($item) {
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('images/default.png');
            return $item;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('buyer.checkout', compact('cartItems'));
    }
}


