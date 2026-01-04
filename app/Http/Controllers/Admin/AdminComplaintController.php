<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminComplaintController extends Controller
{
    /**
     * Display a listing of all complaints.
     */
    // In AdminComplaintController@index
    public function index(Request $request)
    {
        $query = Complaint::with(['buyer:id,name,email', 'seller:id,name', 'admin:id,name', 'order:id,buyer_id,total_amount,status']);

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('problem_type')) {
            $query->where('problem_type', $request->problem_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_message', 'like', "%{$search}%")
                    ->orWhere('complaint_id', 'like', "%{$search}%")
                    ->orWhereHas('buyer', fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('seller', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $statuses = [
            'all' => 'All Statuses',
            Complaint::STATUS_PENDING => 'Pending',
            Complaint::STATUS_IN_PROGRESS => 'In Progress',
            Complaint::STATUS_RESOLVED => 'Resolved',
            Complaint::STATUS_CLOSED => 'Closed',
        ];

        $problemTypes = ['' => 'All Problem Types'] + Complaint::getProblemTypes();

        return view('admin.complaints.index', compact('complaints', 'statuses', 'problemTypes'));
    }


    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['buyer', 'seller', 'admin', 'order']);
        $admins = User::where('role', 'admin')->get();

        return view('admin.complaints.show', compact('complaint', 'admins'));
    }

    /**
     * Update the complaint status and admin response.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_response' => 'nullable|string|max:1000',
            'handled_by' => 'nullable|exists:users,id',
        ]);

        // If admin is responding, set handled_by to current admin
        if ($request->filled('admin_response')) {
            $validated['handled_by'] = Auth::id();
        }

        $complaint->update($validated);

        return redirect()->back()
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Assign complaint to admin.
     */
    public function assign(Request $request, Complaint $complaint)
    {
        $request->validate([
            'handled_by' => 'required|exists:users,id',
        ]);

        $complaint->update([
            'handled_by' => $request->handled_by,
            'status' => Complaint::STATUS_IN_PROGRESS,
        ]);

        return redirect()->back()
            ->with('success', 'Complaint assigned successfully.');
    }

    /**
     * Get complaint statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::where('status', Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved' => Complaint::where('status', Complaint::STATUS_RESOLVED)->count(),
            'closed' => Complaint::where('status', Complaint::STATUS_CLOSED)->count(),
        ];

        return view('admin.complaints.statistics', compact('stats'));
    }
}