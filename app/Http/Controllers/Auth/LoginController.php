<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
class LoginController extends Controller
{
    /**
     * Show the login form
     */
  public function show()
    {
        // Check if already logged in as admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            
            // Redirect to appropriate dashboard based on role
            if (str_contains($admin->role, 'SuperAdmin')) {
                return redirect()->route('superadmin.dashboard');
            }
            
            return redirect()->route('admin.dashboard');
        }
        
        // Return Inertia response - headers will be handled by middleware
        return Inertia::render('AdminLogin');
    }

    /**
     * Handle login request
     */
   public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Use the admin guard
        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            
            $admin = Auth::guard('admin')->user();
           
            // Check if admin is active
            if (str_contains($admin->role, 'Inactive')) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'This account is inactive. Please contact a SuperAdmin.',
                ]);
            }
            
            // Redirect based on role
            if (str_contains($admin->role, 'SuperAdmin')) {
                return redirect()->route('superadmin.dashboard');
            }
            
            return redirect()->route('admin.dashboard');
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}