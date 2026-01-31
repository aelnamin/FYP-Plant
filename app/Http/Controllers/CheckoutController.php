<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Transaction;

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

        // Get cart items with product, seller, and images
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with(['product.seller', 'product.images'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Your cart is empty.');
        }

        // Normalize variants and images
        $cartItems = $cartItems->transform(function ($item) {
            $variants = $item->product->variants;

            if (is_string($variants)) {
                $variants = json_decode($variants, true) ?: [];
            } elseif (!is_array($variants)) {
                $variants = [];
            }

            $item->product->variants = array_map('trim', $variants);

            if (empty($item->variant) && !empty($variants)) {
                $item->variant = $variants[0];
            } elseif (empty($item->variant)) {
                $item->variant = 'Standard';
            }

            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('images/default.png');

            return $item;
        });

        // Total products amount
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Group items by seller to calculate delivery per seller
        $groupedCart = $cartItems->groupBy(fn($item) => $item->product->seller_id);

        // Delivery fee per seller (you can adjust RM 10.60 per seller)
        $deliveryFeePerSeller = 10.60;
        // Free shipping if total >= RM150
        $delivery = $total >= 150 ? 0 : count($groupedCart) * $deliveryFeePerSeller;

        return view('buyer.checkout', compact('cartItems', 'total', 'groupedCart', 'delivery'));
    }

    /**
     * Place order
     */
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


        $request->validate([
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Calculate totals
            $groupedCart = $cartItems->groupBy(fn($item) => $item->product->seller_id);
            $deliveryFeePerSeller = 10.60;

            $productsTotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            // Free shipping if total >= RM150
            $delivery = $productsTotal >= 150 ? 0 : count($groupedCart) * $deliveryFeePerSeller;

            $totalAmount = $productsTotal + $delivery;

            // Create order
            $order = Order::create([
                'buyer_id' => $user->id,
                'status' => 'Pending',
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
            ]);


            // Create order items 

            foreach ($cartItems as $item) {
                $product = \App\Models\Product::lockForUpdate()->find($item->product_id);

                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception('Insufficient stock for ' . $product->product_name);
                }

                $product->decrement('stock_quantity', $item->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'variant' => $item->variant ?? 'Standard',
                    'seller_status' => 'pending',  // ← ADDED
                ]);
            }

            // Create transaction
            Transaction::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // Clear cart
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();

            return redirect()->route('buyer.dashboard')
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('buyer.cart')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}

