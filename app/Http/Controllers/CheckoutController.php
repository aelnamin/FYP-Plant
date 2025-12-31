<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
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

    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->route('buyer.cart')
                ->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')
                ->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {

            $order = Order::create([
                'buyer_id' => $user->id,
                'status' => 'Paid',
                'total_amount' => $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                }),
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();

            return redirect()->route('buyer.dashboard')
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('buyer.cart')
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

}

