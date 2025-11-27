<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Application;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('auth-admin/AdminDashboard', [
            'title' => 'Dashboard',
            'stats' => [/* your data */]
        ]);
    }

    public function applications(Request $request): Response
    {
        // Fetch 25 applications per page
        $applications = Application::paginate(25);

        return Inertia::render('auth-admin/AdminApplications', [
            'title' => 'Applications',
            'applications' => $applications->items(), // current page rows
            'pagination' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
            ],
        ]);
    }

    public function showApplicationDetails(Application $application): Response
    {
        return Inertia::render('auth-admin/AdminApplicationDetail', [
            'application' => $application,
            'title' => 'Application Details',
        ]);
    }

    public function users(): Response
    {
        return Inertia::render('auth-admin/AdminUsers', [
            'title' => 'Users',
            'users' => [/* your data */]
        ]);
    }

    public function variables(): Response
    {
        return Inertia::render('auth-admin/AdminVariables', [
            'title' => 'Variables',
            'variables' => [/* your data */]
        ]);
    }
     public function show()
    {
        return Inertia::render('AdminLogin'); 
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful']);
        }

        return response()->json(['message' => 'Invalid credentials'], 422);
    }

}
