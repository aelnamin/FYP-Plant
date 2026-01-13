<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * List complaints for seller
     */
    public function index()
    {
        $sellerId = auth()->user()->seller?->id;

        $complaints = Complaint::where(function ($query) use ($sellerId) {
            $query->whereNull('handled_by')
                ->orWhere('handled_by', $sellerId);
        })
            ->latest()
            ->paginate(10);

        return view('sellers.complaints.index', compact('complaints'));
    }

    /**
     * View a complaint
     */
    public function show(Complaint $complaint)
    {
        return view('sellers.complaints.show', compact('complaint'));
    }

    /**
     * Respond to complaint
     */
    public function respond(Request $request, Complaint $complaint)
    {
        $request->validate([
            'seller_response' => 'required|string|min:5',
            'status' => 'required|in:' . implode(',', [
                Complaint::STATUS_PENDING,
                Complaint::STATUS_IN_PROGRESS,
                Complaint::STATUS_RESOLVED,
                Complaint::STATUS_CLOSED,
                Complaint::STATUS_REJECTED,
            ]),
        ]);

        // Get the seller ID from the currently logged-in user
        $sellerId = auth()->user()->seller?->id;

        $complaint->update([
            'seller_response' => $request->seller_response,
            'handled_by' => $sellerId,
            'status' => $request->status, // match the validated input
        ]);


        return redirect()
            ->route('sellers.complaints.index')
            ->with('success', 'Complaint updated successfully.');
    }

}