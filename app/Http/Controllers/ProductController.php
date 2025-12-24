<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    /* -------------------------------------------------------
       PUBLIC INDEX (not admin)
    ------------------------------------------------------- */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }


    /* -------------------------------------------------------
       ADMIN: LIST PRODUCTS
    ------------------------------------------------------- */
    public function adminIndex()
    {
        $products = Product::with('images')
            ->where('approval_status', 'Approved')
            ->get();

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $product = Product::with([
            'images',
            'seller',
            'reviews.user'
        ])->findOrFail($id);

        $sameSellerProducts = Product::with('images')
            ->where('seller_id', $product->seller_id)
            ->where('id', '!=', $product->id)
            ->where('approval_status', 'Approved')
            ->take(4)
            ->get();

        $averageRating = round($product->reviews->avg('rating'), 1);
        $totalReviews = $product->reviews->count();

        $hasPurchased = false;
        $hasReviewed = false;

        if ($user && method_exists($user, 'orders')) {
            $hasPurchased = $user->orders()
                ->where('status', 'completed')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();

            $hasReviewed = $product->reviews()
                ->where('user_id', $user->id)
                ->exists();
        }

        // ✅ SAFE variants handling
        $variants = [];
        if (!empty($product->variants)) {
            $decoded = json_decode($product->variants, true);
            $variants = is_array($decoded) ? $decoded : [];
        }

        return view('products.show', compact(
            'product',
            'averageRating',
            'totalReviews',
            'hasPurchased',
            'hasReviewed',
            'sameSellerProducts',
            'variants'
        ));
    }

    /* -------------------------------------------------------
       BROWSE PRODUCTS
    ------------------------------------------------------- */
    public function browse(Request $request)
    {
        $query = Product::with(['images', 'seller'])
            ->where('approval_status', 'Approved');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $products->appends($request->only('search', 'category'));

        $categories = Category::all();

        return view('products.browse', compact('products', 'categories'));
    }

    /* -------------------------------------------------------
       ADMIN CREATE PRODUCT
    ------------------------------------------------------- */
    public function adminCreate()
    {
        $categories = Category::all();
        $sellers = Seller::all();

        return view('admin.products.create', compact('categories', 'sellers'));
    }

    /* -------------------------------------------------------
       ADMIN STORE PRODUCT
    ------------------------------------------------------- */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'variants' => 'nullable|string', // JSON
            'images.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully!');
    }

    /* -------------------------------------------------------
       ADMIN EDIT PRODUCT
    ------------------------------------------------------- */
    public function adminEdit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        $sellers = Seller::all();

        return view('admin.products.edit', compact('product', 'categories', 'sellers'));
    }

    /* -------------------------------------------------------
       ADMIN UPDATE PRODUCT
    ------------------------------------------------------- */
    public function adminUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'variants' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        $product->update($validated);

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    $file = public_path('images/' . $image->image_path);
                    if (file_exists($file)) {
                        unlink($file);
                    }
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('images'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /* -------------------------------------------------------
       DELETE IMAGE
    ------------------------------------------------------- */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        $file = public_path('images/' . $image->image_path);
        if (file_exists($file)) {
            unlink($file);
        }

        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    /* -------------------------------------------------------
       ADMIN DELETE PRODUCT
    ------------------------------------------------------- */
    public function adminDestroy($id)
    {
        Product::destroy($id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted!');
    }
}