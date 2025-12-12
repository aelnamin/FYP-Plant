<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;

class SellerRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.seller-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',
            'phone'             => 'required|string|max:20',          // business phone
            'address'           => 'required|string|max:255',         // personal address
            'business_name'     => 'required|string|max:255',
            'business_address'  => 'required|string|max:255',
        ]);

        // Create user account (stores password hashed)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,     // business phone
            'address'  => $request->address,   // personal address
            'role'     => 'seller',
        ]);

        // Create seller profile
        Seller::create([
            'user_id'          => $user->id,
            'business_name'    => $request->business_name,
            'business_address' => $request->business_address,
        ]);

        return redirect()->route('auth.login')->with('success', 'Seller registered successfully!');
    }
}
