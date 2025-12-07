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
        
        // Get filters from request
        $selectedYear = $request->selected_year ?? 'all';
        $distributionYear = $request->distribution_year ?? ($activeYear ? $activeYear->id : 'all');
        $recentFilter = $request->recent_filter ?? 'all';
        
        // Check if this is a partial update (Inertia visit with 'only')
        $isPartialUpdate = $request->header('X-Inertia-Partial-Data') 
            && $request->header('X-Inertia-Partial-Component');
        
        // Calculate stats
        $stats = $this->calculateDashboardStats($selectedYear, $distributionYear, $recentFilter);
        
        // Prepare response data
        $responseData = [
            'activeYear' => $activeYear,
            'allSchoolYears' => $allSchoolYears,
            'stats' => $stats,
            'filters' => [
                'selected_year' => $selectedYear,
                'distribution_year' => $distributionYear,
                'recent_filter' => $recentFilter,
            ],
        ];
        
        // If partial update, only return requested props
        if ($isPartialUpdate) {
            $partialData = $request->header('X-Inertia-Partial-Data');
            $partialProps = explode(',', $partialData);
            
            // Filter response to only include requested props
            $filteredData = array_intersect_key($responseData, array_flip($partialProps));
            return Inertia::render('auth-admin/AdminDashboard', $filteredData);
        }
        
        // Full page load - include everything
        $responseData['activeApplications'] = Application::with('schoolYear')
            ->whereHas('schoolYear', fn($q) => $q->where('is_active', true))
            ->orderByDesc('created_at')
            ->paginate(10);
        
        $responseData['archivedApplications'] = Application::with('schoolYear')
            ->whereHas('schoolYear', fn($q) => $q->where('is_active', false))
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return Inertia::render('auth-admin/AdminDashboard', $responseData);
    }

// ADD THIS NEW METHOD AFTER THE DASHBOARD METHOD
    // RENAME THIS METHOD from getDashboardStats to calculateDashboardStats
// AND update its parameters
private function calculateDashboardStats($selectedYear, $distributionYear, $recentFilter)
{
    $activeYear = SchoolYear::where('is_active', true)->first();
    
    // Calculate stats based on selected_year
    if ($selectedYear === 'all') {
        $totalApplications = Application::count();
        $acceptedApplications = Application::where('application_status', 'Accepted')->count();
        $rejectedApplications = Application::where('application_status', 'Rejected')->count();
        $pendingApplications = Application::where('application_status', 'For Review')->count();
        $waitlistApplications = Application::where('application_status', 'Waitlist')->count();
    } else {
        $yearId = (int) $selectedYear;
        $totalApplications = Application::where('school_year_id', $yearId)->count();
        $acceptedApplications = Application::where('school_year_id', $yearId)
            ->where('application_status', 'Accepted')->count();
        $rejectedApplications = Application::where('school_year_id', $yearId)
            ->where('application_status', 'Rejected')->count();
        $pendingApplications = Application::where('school_year_id', $yearId)
            ->where('application_status', 'For Review')->count();
        $waitlistApplications = Application::where('school_year_id', $yearId)
            ->where('application_status', 'Waitlist')->count();
    }
    
    // Recent applications
    $recentQuery = Application::with('schoolYear')
        ->orderByDesc('created_at')
        ->limit(10);
    
    if ($selectedYear !== 'all') {
        $recentQuery->where('school_year_id', (int) $selectedYear);
    }
    
    if ($recentFilter !== 'all') {
        $statusMap = [
            'accepted' => 'Accepted',
            'rejected' => 'Rejected', 
            'pending' => 'For Review'
        ];
        
        if (isset($statusMap[$recentFilter])) {
            $recentQuery->where('application_status', $statusMap[$recentFilter]);
        }
    }
    
    $recentApplications = $recentQuery->get();
    
    // Calculate rates
    $acceptanceRate = $totalApplications > 0 ? round(($acceptedApplications / $totalApplications) * 100) : 0;
    $rejectionRate = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100) : 0;
    $waitlistRate = $totalApplications > 0 ? round(($waitlistApplications / $totalApplications) * 100) : 0;
    $pendingRate = $totalApplications > 0 ? round(($pendingApplications / $totalApplications) * 100) : 0;
    
    // Add distribution stats based on distribution_year
    if ($distributionYear === 'all') {
        $distributionStats = [
            'totalApplications' => Application::count(),
            'acceptedApplications' => Application::where('application_status', 'Accepted')->count(),
            'rejectedApplications' => Application::where('application_status', 'Rejected')->count(),
            'pendingApplications' => Application::where('application_status', 'For Review')->count(),
            'waitlistApplications' => Application::where('application_status', 'Waitlist')->count(),
        ];
    } else {
        $yearId = (int) $distributionYear;
        $distributionStats = [
            'totalApplications' => Application::where('school_year_id', $yearId)->count(),
            'acceptedApplications' => Application::where('school_year_id', $yearId)
                ->where('application_status', 'Accepted')->count(),
            'rejectedApplications' => Application::where('school_year_id', $yearId)
                ->where('application_status', 'Rejected')->count(),
            'pendingApplications' => Application::where('school_year_id', $yearId)
                ->where('application_status', 'For Review')->count(),
            'waitlistApplications' => Application::where('school_year_id', $yearId)
                ->where('application_status', 'Waitlist')->count(),
        ];
    }
    
    // Return array, not JSON response
    return [
        'totalApplications' => $totalApplications,
        'acceptedApplications' => $acceptedApplications,
        'rejectedApplications' => $rejectedApplications,
        'pendingApplications' => $pendingApplications,
        'waitlistApplications' => $waitlistApplications,
        'recentApplications' => $recentApplications,
        'acceptanceRate' => $acceptanceRate,
        'rejectionRate' => $rejectionRate,
        'waitlistRate' => $waitlistRate,
        'pendingRate' => $pendingRate,
        'distributionStats' => $distributionStats,
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
  
    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'application_status' => 'required|in:Accepted,Waitlist,Rejected',
        ]);

        $application->application_status = $validated['application_status'];
        $application->save();

        return back()->with('success', 'Application status updated successfully.');
    }

}
