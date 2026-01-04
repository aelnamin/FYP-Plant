<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;

class HelpCenterController extends Controller
{
    // Show the help center page
    public function index()
    {
        // Fetch all complaints submitted by the logged-in buyer
        $complaints = Complaint::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.help-center', compact('complaints'));
    }

    // Store a new complaint
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending', // default status
        ]);

        return redirect()->route('buyer.help-center')
            ->with('success', 'Your complaint has been submitted successfully!');
    }

    // Optional: View a single complaint
    public function show($id)
    {
        $complaint = Complaint::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('buyer.help-center-show', compact('complaint'));
    }
}
