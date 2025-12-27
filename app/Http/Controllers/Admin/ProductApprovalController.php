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
     * Approve product
     */
    public function approve($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'approval_status' => 'Approved'
        ]);

        return back()->with('success', 'Product approved successfully.');
    }

    /**
     * Reject product
     */
    public function reject($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'approval_status' => 'Rejected'
        ]);

        return back()->with('success', 'Product rejected successfully.');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                unlink(public_path($img->image_path));
            }
            $img->delete();
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
