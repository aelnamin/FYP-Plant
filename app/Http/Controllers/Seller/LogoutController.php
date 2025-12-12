<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        Auth::guard('seller')->logout(); // Make sure 'seller' guard is used if you have one
        return redirect('/'); // Redirect to your main website homepage
    }
}
