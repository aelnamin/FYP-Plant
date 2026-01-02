<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // sellers/chats/index
    public function index()
    {
        $sellerId = Auth::id();

        $buyerIds = Message::where('sender_id', $sellerId)
            ->orWhere('receiver_id', $sellerId)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->reject(fn($id) => $id == $sellerId);

        $buyers = User::whereIn('id', $buyerIds)->get();

        return view('sellers.chats.index', compact('buyers'));
    }

    // sellers/chats/show
    public function show($buyerId)
    {
        $sellerId = Auth::id();

        $messages = Message::where(function ($q) use ($sellerId, $buyerId) {
            $q->where('sender_id', $sellerId)
                ->where('receiver_id', $buyerId);
        })
            ->orWhere(function ($q) use ($sellerId, $buyerId) {
                $q->where('sender_id', $buyerId)
                    ->where('receiver_id', $sellerId);
            })
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        $buyer = User::findOrFail($buyerId);

        return view('sellers.chats.show', compact('messages', 'buyer'));
    }

    // send reply
    public function send(Request $request, $buyerId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(), // seller
            'receiver_id' => $buyerId,
            'message' => $request->message,
        ]);

        return back();
    }
}
