<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerManagementController extends Controller
{
    /**
     * Display a listing of sellers with optional search and status filter.
     */
    public function index(Request $request)
    {
        $query = Seller::with('user'); // eager load user for display

        // Search by business name or user's email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%$search%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('email', 'like', "%$search%");
                    });
            });
        }

        // Filter by verification status
        if ($status = $request->input('status')) {
            $query->where('verification_status', $status);
        }

        $sellers = $query->latest()->paginate(10);

        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Approve a seller.
     */
    public function approve(Seller $seller)
    {
        $seller->update(['verification_status' => 'Approved']);

        return redirect()->back()->with('success', 'Seller approved successfully.');
    }

    /**
     * Reject a seller.
     */
    public function reject(Seller $seller)
    {
        $seller->update(['verification_status' => 'Rejected']);

        return redirect()->back()->with('success', 'Seller rejected successfully.');
    }

    /**
     * Show seller details.
     */
    public function show(Seller $seller)
    {
        $seller->load('user', 'products.category');
        return view('admin.sellers.show', compact('seller'));
    }
}
