<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // buyers/chats/index
    public function index()
    {
        $buyerId = Auth::id();

        // Get all unique seller user IDs that the buyer has messages with
        $sellerIds = Message::where('sender_id', $buyerId)
            ->orWhere('receiver_id', $buyerId)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->reject(fn($id) => $id == $buyerId);

        // Load only users who have a seller record and eager load seller
        $sellers = User::whereIn('id', $sellerIds)
            ->whereHas('seller')
            ->with('seller')
            ->get();

        return view('buyer.chats.index', compact('sellers'));
    }

    public function startChat($sellerId)
    {
        // Check if seller exists
        $seller = User::findOrFail($sellerId);

        // Redirect to chat page (show)
        return redirect()->route('buyer.chats.show', $seller->id);
    }


    // buyers/chats/show
    public function show($sellerId)
    {
        $buyerId = Auth::id();

        // Load messages between buyer and seller
        $messages = Message::where(function ($q) use ($buyerId, $sellerId) {
            $q->where('sender_id', $buyerId)->where('receiver_id', $sellerId);
        })
            ->orWhere(function ($q) use ($buyerId, $sellerId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $buyerId);
            })
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Load seller user and their business name
        $seller = User::with('seller')->findOrFail($sellerId);

        return view('buyer.chats.show', compact('messages', 'seller'));
    }

    // buyer sends message
    public function send(Request $request, $sellerId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(), // buyer
            'receiver_id' => $sellerId,
            'message' => $request->message,
        ]);

        return back();
    }
}
