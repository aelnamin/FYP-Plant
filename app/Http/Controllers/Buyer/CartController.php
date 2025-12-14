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
    // Show cart
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product.images')->get();

        // Add full image URL for each cart item
        $cartItems->transform(function ($item) {
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('default.png');
            return $item;
        });

        return view('buyer.cart', compact('cartItems'));
    }

    // Add to cart
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = $cart->items()->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('buyer.cart')
            ->with('success', 'Item added to cart!');
    }

    // Update quantity
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

        return redirect()->route('buyer.cart')
            ->with('success', 'Cart updated!');
    }

    // Remove item
    public function remove($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('buyer.cart')
            ->with('success', 'Item removed!');
    }

    // Checkout
    public function checkout()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product.images')->get();

        // Add full image URL for each cart item
        $cartItems->transform(function ($item) {
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('default.png');
            return $item;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('buyer.checkout', compact('cartItems'));
    }
}

