<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout()
    {
        $user = Auth::user();

        // Get cart
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->route('buyer.cart')->with('error', 'Your cart is empty.');
        }

        // Get cart items with products
        $cartItems = \App\Models\CartItem::where('cart_id', $cart->id)
            ->with('product.images') // eager load images
            ->get();

        // Calculate total safely
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }

        return view('buyer.checkout', compact('cartItems', 'total'));
    }

    /**
     * Place order (we can improve this later)
     */
    public function placeOrder(Request $request)
    {
        // For now just a placeholder
        return redirect()->route('buyer.dashboard')
            ->with('success', 'Order placed successfully!');
    }
}

