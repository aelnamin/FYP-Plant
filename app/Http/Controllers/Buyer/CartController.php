<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Normalize variants and images for cart items
     */
    private function normalizeVariants($cartItems)
    {
        return $cartItems->transform(function ($item) {
            // Decode variants from product
            $variants = $item->product->variants;

            if (is_string($variants)) {
                $variants = json_decode($variants, true) ?: [];
            } elseif (!is_array($variants)) {
                $variants = [];
            }

            $item->product->variants = array_map('trim', $variants);

            // FIX: If cart variant is empty/null, but product has variants
            if (empty($item->variant) && !empty($variants)) {
                $item->variant = $variants[0];
            } elseif (empty($item->variant)) {
                $item->variant = 'Standard';
            }

            // Set image URL
            $image = optional($item->product->images->first())->image_path;
            $item->image_url = $image ? asset('images/' . $image) : asset('images/default.png');

            return $item;
        });
    }

    /**
     * Show cart page
     */
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with(['product.seller', 'product.images'])->get();
        $cartItems = $this->normalizeVariants($cartItems);

        return view('buyer.cart', compact('cartItems'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $id)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $product = Product::findOrFail($id);

        // Normalize variants
        $variants = is_array($product->variants)
            ? $product->variants
            : (json_decode($product->variants, true) ?? []);

        // SIMPLE VALIDATION - ALWAYS accept variant field
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant' => 'nullable|string', // Always allow variant field
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Get variant - trim it
        $variant = trim($validated['variant'] ?? '');

        // If variant is empty but product has variants, use first one
        if (empty($variant) && !empty($variants)) {
            $variant = $variants[0];
        }

        // Find existing cart item
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant', $variant)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'variant' => $variant, // This will now save the variant
                'quantity' => $validated['quantity'],
            ]);
        }

        // Response handling
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'count' => $cart->items()->sum('quantity'),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart');
    }

    /**
     * Get cart sidebar (AJAX)
     */
    public function sidebar()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with(['product.seller', 'product.images'])->get();
        $cartItems = $this->normalizeVariants($cartItems);

        return view('buyer.cart-sidebar', compact('cartItems'));
    }

    /**
     * Update quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }



    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Item removed!');
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with(['product.seller', 'product.images'])->get();
        $cartItems = $this->normalizeVariants($cartItems);

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('buyer.checkout', compact('cartItems'));
    }
}




