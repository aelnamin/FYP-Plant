<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category', 'images']);

        if ($request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('approval_status', $request->status);
        }

        $products = $query->latest()->paginate(7);

        return view('admin.products.index', compact('products'));
    }

    public function edit(Product $product)
    {
        $product->load('seller', 'category', 'images');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $product->update($request->only('product_name', 'price', 'stock_quantity', 'description'));

        return redirect()->route('admin.products.show', $product->id)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete images first if needed
        foreach ($product->images as $img) {
            if (file_exists(public_path('images/' . $img->image_path))) {
                unlink(public_path('images/' . $img->image_path));
            }
            $img->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }


    /**
     * Show single product details
     */
    public function show(Product $product)
    {
        // Load relationships for display
        $product->load('seller', 'category', 'images');

        return view('admin.products.show', compact('product'));
    }

}
