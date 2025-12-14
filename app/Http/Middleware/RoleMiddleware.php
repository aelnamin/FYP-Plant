<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  The required role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('login'); // redirect to login route
        }

        $user = Auth::user();

        // Must match role (case-insensitive for safety)
        if (strtolower($user->role) !== strtolower($role)) {
            // Optional: redirect back with message instead of abort
            abort(403, 'Unauthorized access.');
        }

        // All good, continue request
        return $next($request);
    }
}
