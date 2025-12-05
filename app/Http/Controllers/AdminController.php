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
    public function dashboard(Request $request): Response
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $allSchoolYears = SchoolYear::orderBy('school_year', 'desc')->get();

        // Get year filter from request
        $yearFilter = $request->year_filter ?? 'all';
        $distributionYearFilter = $request->distribution_year_filter ?? ($activeYear ? $activeYear->id : 'all');
        $recentFilter = $request->recent_filter ?? 'all';
        $update = $request->update ?? ''; // What to update

        // Base queries
        $allApplicationsQuery = Application::with('schoolYear');

        // ========== STATS CALCULATION ==========
        // Calculate stats based on selected year
        if ($yearFilter === 'all' || $update === '') {
            // All years or initial load
            $stats = $this->calculateStatsForAllYears();
        } else {
            // Specific year
            $yearId = (int) $yearFilter;
            $stats = $this->calculateStatsForYear($yearId);
        }

        // ========== RECENT APPLICATIONS ==========
        $recentQuery = Application::with('schoolYear')
            ->orderByDesc('created_at')
            ->limit(10);

        // Apply recent filter if specified
        if ($recentFilter !== 'all' && $update === 'recent') {
            $statusMap = [
                'accepted' => 'Accepted',
                'rejected' => 'Rejected',
                'pending' => 'For Review'
            ];

            if (isset($statusMap[$recentFilter])) {
                $recentQuery->where('application_status', $statusMap[$recentFilter]);
            }
        }

        // Apply year filter to recent if specified
        if ($yearFilter !== 'all' && $update !== 'distribution') {
            $recentQuery->where('school_year_id', (int) $yearFilter);
        }

        $recentApplications = $recentQuery->get();

        // ========== DISTRIBUTION STATS ==========
        $distributionStats = $this->calculateDistributionStats($distributionYearFilter);

        // ========== PAGINATED APPLICATIONS ==========
        // Active applications
        $activeQuery = Application::with('schoolYear')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', true);
            })
            ->orderByDesc('created_at');

        $activeApplications = $activeQuery->paginate(10);

        // Archived applications  
        $archivedQuery = Application::with('schoolYear')
            ->whereHas('schoolYear', function ($query) {
                $query->where('is_active', false);
            })
            ->orderByDesc('created_at');

        $archivedApplications = $archivedQuery->paginate(10);

        return Inertia::render('auth-admin/AdminDashboard', [
            'activeApplications' => $activeApplications,
            'archivedApplications' => $archivedApplications,
            'activeYear' => $activeYear,
            'stats' => array_merge($stats, [
                'recentApplications' => $recentApplications,
                'distributionStats' => $distributionStats,
            ]),
            'allSchoolYears' => $allSchoolYears,
            'filters' => [
                'year_filter' => $yearFilter,
                'distribution_year_filter' => $distributionYearFilter,
                'recent_filter' => $recentFilter,
            ],
        ]);
    }

    // Helper method to calculate stats for all years
    private function calculateStatsForAllYears(): array
    {
        $total = Application::count();
        $accepted = Application::where('application_status', 'Accepted')->count();
        $rejected = Application::where('application_status', 'Rejected')->count();
        $pending = Application::where('application_status', 'For Review')->count();
        $waitlist = Application::where('application_status', 'Waitlist')->count();

        return [
            'totalApplications' => $total,
            'acceptedApplications' => $accepted,
            'rejectedApplications' => $rejected,
            'pendingApplications' => $pending,
            'waitlistApplications' => $waitlist,
            'acceptanceRate' => $total > 0 ? round(($accepted / $total) * 100) : 0,
            'rejectionRate' => $total > 0 ? round(($rejected / $total) * 100) : 0,
            'waitlistRate' => $total > 0 ? round(($waitlist / $total) * 100) : 0,
            'pendingRate' => $total > 0 ? round(($pending / $total) * 100) : 0,
        ];
    }
    private function calculateStatsForYear(int $yearId): array
    {
        $query = Application::where('school_year_id', $yearId);

        $total = $query->count();
        $accepted = $query->clone()->where('application_status', 'Accepted')->count();
        $rejected = $query->clone()->where('application_status', 'Rejected')->count();
        $pending = $query->clone()->where('application_status', 'For Review')->count();
        $waitlist = $query->clone()->where('application_status', 'Waitlist')->count();

        return [
            'totalApplications' => $total,
            'acceptedApplications' => $accepted,
            'rejectedApplications' => $rejected,
            'pendingApplications' => $pending,
            'waitlistApplications' => $waitlist,
            'acceptanceRate' => $total > 0 ? round(($accepted / $total) * 100) : 0,
            'rejectionRate' => $total > 0 ? round(($rejected / $total) * 100) : 0,
            'waitlistRate' => $total > 0 ? round(($waitlist / $total) * 100) : 0,
            'pendingRate' => $total > 0 ? round(($pending / $total) * 100) : 0,
        ];
    }
    // Helper method for distribution stats
    private function calculateDistributionStats($yearFilter): array
    {
        if ($yearFilter === 'all') {
            $query = Application::query();
        } else {
            $query = Application::where('school_year_id', (int) $yearFilter);
        }

        $total = $query->count();
        $accepted = $query->clone()->where('application_status', 'Accepted')->count();
        $rejected = $query->clone()->where('application_status', 'Rejected')->count();
        $pending = $query->clone()->where('application_status', 'For Review')->count();
        $waitlist = $query->clone()->where('application_status', 'Waitlist')->count();

        return [
            'totalApplications' => $total,
            'acceptedApplications' => $accepted,
            'rejectedApplications' => $rejected,
            'pendingApplications' => $pending,
            'waitlistApplications' => $waitlist,
        ];
    }
    public function showApplicationDetails($id)
    {
        $application = Application::with('schoolYear')->findOrFail($id);

        return Inertia::render('auth-admin/AdminApplicationDetail', [
            'title' => 'Application Details',
            'application' => $application,
        ]);
    }

    public function applications(Request $request): Response
    {
        $activePage = $request->query('active_page', 1);
        $archivedPage = $request->query('archived_page', 1);
        $activeYear = SchoolYear::where('is_active', true)->first();

        // Get all school years for the dropdown
        $allSchoolYears = SchoolYear::orderBy('school_year', 'desc')->get();

        // Get active applications (with pagination)
        $activeQuery = Application::with('schoolYear')
            ->orderByDesc('created_at');

        if ($activeYear) {
            $activeQuery->where('school_year_id', $activeYear->id);
        }

        $activeApplications = $activeQuery->paginate(10, ['*'], 'active_page', $activePage);

        // Get archived applications (with pagination) - UPDATED
        $archivedQuery = Application::with('schoolYear')
            ->orderByDesc('created_at');

        // Apply archived filter logic
        if ($activeYear) {
            // Start with excluding active year
            $archivedQuery->where('school_year_id', '!=', $activeYear->id);

            // If specific year filter is provided, use it instead
            if ($request->has('archived_year_filter') && $request->archived_year_filter !== 'all') {
                $filteredYearId = $request->archived_year_filter;
                $archivedQuery->where('school_year_id', $filteredYearId);
            }
        } else {
            // If no active year, show all as active, none as archived
            $archivedQuery->whereRaw('1=0'); // Empty result
        }

        $archivedApplications = $archivedQuery->paginate(10, ['*'], 'archived_page', $archivedPage);

        return Inertia::render('auth-admin/AdminApplications', [
            'title' => 'Applications',
            'activeApplications' => $activeApplications,
            'archivedApplications' => $archivedApplications,
            'activeYearId' => $activeYear ? $activeYear->id : null,
            'allSchoolYears' => $allSchoolYears,
            'activeYear' => $activeYear,
            'currentRoute' => $request->fullUrl(),
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
            ->paginate(10);
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
