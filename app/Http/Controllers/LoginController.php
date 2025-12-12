<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('login_error', 'Account not found.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('login_error', 'Invalid password.');
        }

        Session::flush();

        Session::put('user_id', $user->id);
        Session::put('name', $user->name);
        Session::put('email', $user->email);
        Session::put('role', $user->role);

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'seller':
                $seller = DB::table('sellers')->where('user_id', $user->id)->first();
                if ($seller) {
                    Session::put('seller_id', $seller->id);
                }
                return redirect()->route('sellers.dashboard');
            case 'buyer':
                return redirect()->route('buyer.dashboard');
            default:
                return back()->with('login_error', 'Unknown role.');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login.form');
    }
}
