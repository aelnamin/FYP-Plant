<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $products = $seller->products()->latest()->get();

        return view('sellers.inventory.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('sellers.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'approval_status' => 'Pending',
        ]);

        // Save images into PUBLIC/images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()
            ->route('sellers.inventory.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $product = $seller->products()->findOrFail($id);
        $categories = Category::all();

        return view('sellers.inventory.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $product = $seller->products()->findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);

        // Remove selected old images
        if ($request->remove_images) {
            foreach ($request->remove_images as $imgId) {
                $img = ProductImage::find($imgId);
                if ($img) {
                    // Delete from public/images
                    if (file_exists(public_path($img->image_path))) {
                        unlink(public_path($img->image_path));
                    }
                    $img->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()
            ->route('sellers.inventory.index')
            ->with('success', 'Product updated successfully!');
    }

    public function show($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $product = $seller->products()->findOrFail($id);

        return view('sellers.inventory.show', compact('product'));
    }

    public function destroy($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $product = $seller->products()->findOrFail($id);

        // Delete images from public/images
        foreach ($product->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
            $img->delete();
        }

        $product->delete();

        return redirect()
            ->route('sellers.inventory.index')
            ->with('success', 'Product deleted successfully!');
    }
}
