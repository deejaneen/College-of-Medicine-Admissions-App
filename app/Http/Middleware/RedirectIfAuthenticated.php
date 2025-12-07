<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth facade

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on guard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($guard === 'web') {
                    return redirect()->route('dashboard');
                } elseif ($guard === 'superadmin') {
                    return redirect()->route('superadmin.dashboard');
                }
            }
        }

        return $next($request);
    }
}
