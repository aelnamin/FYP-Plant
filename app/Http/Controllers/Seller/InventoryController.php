<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the seller's products.
     */
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())
            ->with('images', 'category')
            ->get();

        return view('sellers.inventory.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $categories = Category::all();
        return view('sellers.inventory.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'seller_id' => Auth::id(),
                'category_id' => $request->category_id,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'sunlight_requirement' => $request->sunlight_requirement,
                'watering_frequency' => $request->watering_frequency,
                'difficulty_level' => $request->difficulty_level,
                'growth_stage' => $request->growth_stage,
                'approval_status' => 'Pending'
            ]);

            // Save Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $filename = time() . '_' . $img->getClientOriginalName();
                    $img->storeAs('public/products', $filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'storage/products/' . $filename
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Product added successfully and awaiting approval.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form.
     */
    public function edit(Product $inventory)
    {
        // Ensure product belongs to seller
        if ($inventory->seller_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();

        return view('sellers.inventory.edit', [
            'product' => $inventory,
            'categories' => $categories
        ]);
    }

    /**
     * Update existing product.
     */
    public function update(Request $request, Product $inventory)
    {
        if ($inventory->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        DB::beginTransaction();
        try {
            $inventory->update([
                'category_id' => $request->category_id,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'sunlight_requirement' => $request->sunlight_requirement,
                'watering_frequency' => $request->watering_frequency,
                'difficulty_level' => $request->difficulty_level,
                'growth_stage' => $request->growth_stage,
                'approval_status' => 'Pending' // Re-approve if edited
            ]);

            // Add new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $filename = time() . '_' . $img->getClientOriginalName();
                    $img->storeAs('public/products', $filename);

                    ProductImage::create([
                        'product_id' => $inventory->id,
                        'image_path' => 'storage/products/' . $filename
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Product updated. Waiting for admin approval.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $inventory)
    {
        if ($inventory->seller_id !== Auth::id()) {
            abort(403);
        }

        // Delete product images
        foreach ($inventory->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
            $img->delete();
        }

        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Product removed successfully.');
    }
}
