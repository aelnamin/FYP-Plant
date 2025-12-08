<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApprovalController extends Controller
{
    /**
     * Display all pending products.
     */
    public function index()
    {
        $pendingProducts = Product::where('approval_status', 'Pending')
            ->with('seller', 'images', 'category')
            ->get();

        return view('admin.product-approvals.index', compact('pendingProducts'));
    }

    /**
     * Show single product details.
     */
    public function show(Product $product_approval)
    {
        return view('admin.product-approvals.show', [
            'product' => $product_approval
        ]);
    }

    /**
     * Approve or reject product.
     */
    public function update(Request $request, Product $product_approval)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        $product_approval->update([
            'approval_status' => $request->status
        ]);

        return redirect()->route('product-approvals.index')
            ->with('success', 'Product status updated successfully.');
    }

    /**
     * Delete product (if required by admin).
     */
    public function destroy(Product $product_approval)
    {
        // Delete product images
        foreach ($product_approval->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
            $img->delete();
        }

        $product_approval->delete();

        return redirect()->route('product-approvals.index')
            ->with('success', 'Product deleted successfully.');
    }
}
