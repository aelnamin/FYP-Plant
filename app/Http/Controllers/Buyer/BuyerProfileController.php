<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BuyerProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get orders with items and their sellers
        $orders = $user->orders()
            ->with(['items.product.images', 'items.product.seller'])
            ->latest()
            ->get();

        // Create grouped orders by seller within each order
        $groupedOrders = collect();

        foreach ($orders as $order) {
            // Group items in this order by seller
            $sellerGroups = $order->items->groupBy('product.seller_id');

            foreach ($sellerGroups as $sellerId => $items) {
                $firstItem = $items->first();
                $seller = $firstItem->product->seller ?? null;

                // ===============================
                // USE SELLER-SPECIFIC STATUS
                // ===============================
                $sellerStatus = $firstItem->seller_status ?? 'Pending';

                // Calculate totals for this seller's items
                $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
                $deliveryFee = 10.60; // Per seller delivery fee, adjust if needed
                $total = $subtotal + $deliveryFee;

                $groupedOrders->push([
                    'order_id' => $order->id,
                    'seller_id' => $sellerId,
                    'seller_name' => $seller->business_name ?? 'Unknown Seller',
                    'order_date' => $order->created_at,
                    'status' => $sellerStatus, // <-- Seller-specific status
                    'items' => $items,
                    'item_count' => $items->count(),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $total,
                ]);
            }
        }

        // Sort by order date (most recent first)
        $groupedOrders = $groupedOrders->sortByDesc('order_date')->values();

        return view('buyer.profile', compact('user', 'groupedOrders'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        // =========================
        // HANDLE PROFILE PICTURE
        // =========================
        if ($request->hasFile('profile_picture')) {

            // Delete old photo (optional but recommended)
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            $imageName = 'profile_' . $user->id . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(
                public_path('uploads/profile'),
                $imageName
            );

            $profilePath = 'uploads/profile/' . $imageName;
        } else {
            $profilePath = $user->profile_picture;
        }

        // =========================
        // UPDATE USER DATA
        // =========================
        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'profile_picture' => $profilePath,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated.');
    }
}
