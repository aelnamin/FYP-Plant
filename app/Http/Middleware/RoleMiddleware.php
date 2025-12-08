<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // must be logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        // must match role
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
