<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Or your login route
        }

        // 2. Check if authenticated user is an admin
        // Make sure your User model has an isAdmin() method or access the is_admin property
        if (!Auth::user()->isAdmin()) { // Or Auth::user()->is_admin
            // If not an admin, redirect them or show an error
            // For example, redirect to home with an error message
            // return redirect('/')->with('error', 'You do not have admin access.');
            // Or, abort with a 403 Forbidden error
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}