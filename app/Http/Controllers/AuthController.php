<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid login credentials.']);
        }

        $user = Auth::user();


        // Redirect based on role
        switch ($user->role) {

            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'seller':
                return redirect()->route('sellers.dashboard');

            case 'buyer':
                return redirect()->route('home');

            default:
                Auth::logout();
                return back()->withErrors(['email' => 'Your account role is not recognized.']);
        }
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
