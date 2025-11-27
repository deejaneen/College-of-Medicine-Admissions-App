<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        // User not logged in
        if (!$user) {
            // Redirect to landing page
            return redirect()->route('login');
        }

        // User does not have required role
        if ($user->role !== $role) {
            return redirect()->route('login');
        }

        // User is authorized, continue request
        return $next($request);
    }
}
