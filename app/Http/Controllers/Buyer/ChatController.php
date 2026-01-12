<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show chat page with optional active seller
     * URL:
     *  - /buyer/chats
     *  - /buyer/chats/{seller}
     */
    public function index(Request $request, $sellerId = null)
    {
        $buyerId = Auth::id();

        // Get all unique seller IDs
        $sellerIds = Message::where('sender_id', $buyerId)
            ->orWhere('receiver_id', $buyerId)
            ->get(['sender_id', 'receiver_id']) // fetch all sender & receiver IDs
            ->flatMap(fn($msg) => [$msg->sender_id, $msg->receiver_id]) // get both IDs
            ->unique()
            ->reject(fn($id) => $id == $buyerId); // remove buyer ID


        $sellers = User::whereIn('id', $sellerIds)
            ->whereHas('seller')
            ->with('seller')
            ->get();

        $activeSeller = null;
        $messages = collect();

        if ($sellerId) {
            $activeSeller = User::with('seller')->findOrFail($sellerId);

            // ✅ MARK SELLER → BUYER MESSAGES AS READ
            Message::where('sender_id', $sellerId)
                ->where('receiver_id', $buyerId)
                ->whereNull('read_at')
                ->update([
                    'read_at' => now()
                ]);

            // Get full conversation
            $messages = Message::where(function ($q) use ($buyerId, $sellerId) {
                $q->where('sender_id', $buyerId)
                    ->where('receiver_id', $sellerId);
            })
                ->orWhere(function ($q) use ($buyerId, $sellerId) {
                    $q->where('sender_id', $sellerId)
                        ->where('receiver_id', $buyerId);
                })
                ->with('sender')
                ->orderBy('created_at')
                ->get();
        }

        return view('buyer.chats.index', compact('sellers', 'activeSeller', 'messages'));
    }
    public function startChat($sellerId)
    {
        // Check if seller exists
        $seller = User::findOrFail($sellerId);

        // Redirect to chat page (show)
        return redirect()->route('buyer.chats.show', $seller->id);
    }

    /**
     * Buyer sends message
     */
    public function send(Request $request, $sellerId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $sellerId,
            'message' => $request->message,
        ]);

        return back();
    }
}
