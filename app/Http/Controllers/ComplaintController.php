<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the buyer's complaints.
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with(['seller', 'order', 'admin'])
            ->latest()
            ->paginate(10);

        $complaints = Complaint::where('user_id', Auth::id())
            ->with(['seller:id,name', 'order:id', 'admin:id,name']) // Specify columns to load
            ->latest()
            ->paginate(10);



        return view('buyer.complaints.index', compact('complaints'));
    }

    // Problem type constants
    const PROBLEM_MISSING_PARCEL = 'missing_parcel';
    const PROBLEM_DAMAGED_ITEM = 'damaged_item';
    const PROBLEM_WRONG_ITEM = 'wrong_item';
    const PROBLEM_LATE_DELIVERY = 'late_delivery';
    const PROBLEM_QUALITY_ISSUE = 'quality_issue';
    const PROBLEM_SELLER_BEHAVIOR = 'seller_behavior';
    const PROBLEM_REFUND_ISSUE = 'refund_issue';
    const PROBLEM_OTHER = 'other';

    /**
     * Get all problem types with labels
     */
    public static function getProblemTypes(): array
    {
        return [
            self::PROBLEM_MISSING_PARCEL => 'Missing Parcel',
            self::PROBLEM_DAMAGED_ITEM => 'Damaged Item',
            self::PROBLEM_WRONG_ITEM => 'Wrong Item',
            self::PROBLEM_LATE_DELIVERY => 'Late Delivery',
            self::PROBLEM_QUALITY_ISSUE => 'Quality Issue',
            self::PROBLEM_SELLER_BEHAVIOR => 'Seller Behavior',
            self::PROBLEM_REFUND_ISSUE => 'Refund Issue',
            self::PROBLEM_OTHER => 'Other',
        ];
    }



    /**
     * Show the form for creating a new complaint.
     */
    public function create(Request $request)
    {
        $order_id = $request->query('order_id');

        $orders = Order::where('buyer_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        $selectedOrder = $order_id
            ? Order::where('order_id', $order_id)
                ->where('buyer_id', Auth::id())
                ->first()
            : null;

        $problemTypes = Complaint::getProblemTypes();

        return view('buyer.complaints.create', compact(
            'orders',
            'selectedOrder',
            'problemTypes'
        ));
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'problem_type' => 'required',
            'complaint_message' => 'required',
        ]);

        // Get seller from order if order is selected
        $sellerId = null;
        if ($request->filled('order_id')) {
            $order = Order::find($request->order_id);
            $sellerId = $order->seller_id ?? null;
        }

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'seller_id' => $sellerId, // Auto-set from order
            'order_id' => $validated['order_id'] ?? null,
            'problem_type' => $validated['problem_type'],
            'complaint_message' => $validated['complaint_message'],
            'status' => Complaint::STATUS_PENDING,
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint submitted successfully.');
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        // Ensure buyer can only view their own complaints
        if ($complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $complaint->load(['seller', 'order', 'admin']);

        return view('buyer.complaints.show', compact('complaint'));
    }


}
