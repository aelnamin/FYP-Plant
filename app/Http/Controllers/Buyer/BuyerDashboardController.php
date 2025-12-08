<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BuyerDashboardController extends Controller
{
    // Middleware to ensure only logged-in buyers can access
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Session::get('role') !== 'buyer') {
                return redirect()->route('login.form')->with('login_error', 'Access denied.');
            }
            return $next($request);
        });
    }

    // Show the buyer dashboard
    public function index()
    {
        $name = Session::get('name'); // Example: get buyer's name from session

        return view('buyer.dashboard', compact('name'));
    }
}
