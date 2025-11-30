<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Application;
use App\Models\SchoolYear;

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
        $applications = Application::with('schoolYear') // Remove 'user' for now
            ->orderByDesc('created_at')
            ->paginate(30);

        return Inertia::render('auth-admin/AdminApplications', [
            'title' => 'Applications',
            'applications' => $applications->items(),
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
        $application->load('schoolYear');

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

    public function applicationPeriods()
    {
        $schoolYears = SchoolYear::withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();
        $currentActive = SchoolYear::active()->first();
        
        return Inertia::render('auth-admin/AdminApplicationPeriods', [
            'schoolYears' => $schoolYears,
            'currentActive' => $currentActive,
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
  
    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'application_status' => 'required|in:Accepted,Waitlist,Rejected',
        ]);

        $application->application_status = $validated['application_status'];
        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

}
