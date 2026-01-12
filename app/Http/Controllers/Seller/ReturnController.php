<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    // Show all returns to this seller
    public function index()
    {
        // Get the authenticated user's seller record
        $seller = Auth::user()->seller; // make sure User model has seller() relation

        if (!$seller) {
            abort(403, 'You are not a seller.');
        }

        // Get all returns for this seller
        $returns = ReturnRequest::where('seller_id', $seller->id)
            ->latest()
            ->get();

        return view('sellers.returns.index', compact('returns'));
    }

    // Update return status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:processing,approved,rejected',
            'seller_note' => 'nullable|string',
        ]);

        $seller = Auth::user()->seller;

        $return = ReturnRequest::where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        // If approved & refund → deduct revenue
        if ($request->status === 'approved' && $return->request_type === 'refund') {
            $orderItem = $return->orderItem;

            if ($orderItem) { // <-- check it exists
                $orderItem->seller_status = 'refunded';
                $orderItem->save();
            } else {
                return back()->with('error', 'Associated order item not found.');
            }
        }



        // If replacement → save seller note
        if ($return->request_type === 'replacement') {
            $return->seller_note = $request->seller_note;
        }

        // Safe status assignment
        $return->status = $request->status;
        $return->save();

        return back()->with('success', 'Return updated successfully.');
    }

}