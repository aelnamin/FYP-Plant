<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SellerProfileController extends Controller
{
    /**
     * Show the seller profile page
     */
    public function index()
    {
        return view('sellers.profile');
    }

    /**
     * Update the seller profile
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Update user fields
        $userData = $request->only(['name', 'phone']);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            // Delete old picture if exists
            if ($user->profile_picture && File::exists(public_path($user->profile_picture))) {
                File::delete(public_path($user->profile_picture));
            }

            // Store new picture
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $imageName);

            $userData['profile_picture'] = 'uploads/profile/' . $imageName;
        }

        // Save user fields
        $user->update($userData);

        // Update seller fields
        $sellerData = $request->only(['business_name', 'business_address']);
        $seller = $user->sellerProfile; // relation to sellers table

        if ($seller) {
            $seller->update($sellerData);
        } else {
            // Create seller record if it doesn't exist yet
            $user->sellerProfile()->create($sellerData);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}

