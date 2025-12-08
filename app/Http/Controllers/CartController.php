<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Show cart items
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Add item to cart
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->product_name,
                "price" => $product->price,
                "quantity" => $quantity,
                "image" => $product->images->first()->image_path ?? null,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('buyer.cart')->with('success', 'Item added to cart!');
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $quantity = $request->input('quantity', 1);
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('buyer.cart')->with('success', 'Cart updated!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('buyer.cart')->with('success', 'Item removed from cart!');
    }
}
