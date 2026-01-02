<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        // Start query
        $query = $seller->products()->with(['images', 'category']);

        // Optional: Search by product name
        if ($search = $request->input('search')) {
            $query->where('product_name', 'like', "%$search%");
        }

        // Paginate instead of get()
        $products = $query->latest()->paginate(10); // ✅ This fixes links()

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

            'sunlight_requirement' => 'nullable|string',
            'watering_frequency' => 'nullable|string',
            'difficulty_level' => 'nullable|string',
            'growth_stage' => 'nullable|string',

            'health_condition' => 'nullable|string|max:255',

            'variants_input' => 'nullable|string', // ✅ VARIANTS
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Convert variants to JSON
        $variants = null;
        if ($request->filled('variants_input')) {
            $variants = json_encode(
                array_map('trim', explode(',', $request->variants_input))
            );
        }

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,

            'sunlight_requirement' => $request->sunlight_requirement,
            'watering_frequency' => $request->watering_frequency,
            'difficulty_level' => $request->difficulty_level,
            'growth_stage' => $request->growth_stage,

            'health_condition' => $request->health_condition,

            'variants' => $variants,
            'approval_status' => 'Pending',
        ]);

        // Save images
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

            'sunlight_requirement' => 'nullable|string',
            'watering_frequency' => 'nullable|string',
            'difficulty_level' => 'nullable|string',
            'growth_stage' => 'nullable|string',

            'variants_input' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // ✅ Convert variants to JSON
        $variants = null;
        if ($request->filled('variants_input')) {
            $variants = json_encode(
                array_map('trim', explode(',', $request->variants_input))
            );
        }

        $product->update([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,

            'sunlight_requirement' => $request->sunlight_requirement,
            'watering_frequency' => $request->watering_frequency,
            'difficulty_level' => $request->difficulty_level,
            'growth_stage' => $request->growth_stage,


            'health_condition' => $request->health_condition,

            'variants' => $variants,
        ]);

        // Remove images
        if ($request->remove_images) {
            foreach ($request->remove_images as $imgId) {
                $img = ProductImage::find($imgId);
                if ($img) {
                    $file = public_path('images/' . $img->image_path);
                    if (file_exists($file))
                        unlink($file);
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

        foreach ($product->images as $img) {
            $file = public_path('images/' . $img->image_path);
            if (file_exists($file))
                unlink($file);
            $img->delete();
        }

        $product->delete();

        return redirect()
            ->route('sellers.inventory.index')
            ->with('success', 'Product deleted successfully!');
    }
}
