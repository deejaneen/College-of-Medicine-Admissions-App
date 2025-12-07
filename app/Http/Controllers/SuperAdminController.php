<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\ActionLogger;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    /**
     * Main admin management page (list of all admins)
     */
   
    public function edit(Admin $admin)
    {
       
        
        return Inertia::render('auth-superadmin/SuperAdminEditUserDetails', [
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ],
        ]);
    }
  public function editUser(Request $request)
{
    $perPage = 10;
    
    // Get status filters from request
    $superadminStatus = $request->get('superadmin_status', 'all');
    $adminStatus = $request->get('admin_status', 'all');
    
    // Get SuperAdmins with status filter
    $superadminsQuery = Admin::where(function($query) {
        $query->where('role', 'SuperAdmin')
              ->orWhere('role', 'InactiveSuperAdmin');
    });
    
    // Apply status filter for SuperAdmins (SERVER-SIDE)
    if ($superadminStatus === 'active') {
        $superadminsQuery->where('role', 'SuperAdmin');
    } elseif ($superadminStatus === 'inactive') {
        $superadminsQuery->where('role', 'InactiveSuperAdmin');
    }
    
    
    $superadmins = $superadminsQuery
        ->latest()
        ->paginate($perPage, ['*'], 'superadmin_page')
        ->through(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'is_active' => !str_contains($admin->role, 'Inactive'),
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            ];
        });
    
    // Get Admins with status filter
    $adminsQuery = Admin::where(function($query) {
        $query->where('role', 'Admin')
              ->orWhere('role', 'InactiveAdmin');
    });
    
    // Apply status filter for Admins (SERVER-SIDE)
    if ($adminStatus === 'active') {
        $adminsQuery->where('role', 'Admin');
    } elseif ($adminStatus === 'inactive') {
        $adminsQuery->where('role', 'InactiveAdmin');
    }
    
    $admins = $adminsQuery
        ->latest()
        ->paginate($perPage, ['*'], 'admin_page')
        ->through(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'is_active' => !str_contains($admin->role, 'Inactive'),
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            ];
        });
    
    $currentUser = Auth::user();
    
    return Inertia::render('auth-superadmin/SuperAdminEditUser', [
        'superadmins' => $superadmins,
        'admins' => $admins,
        'currentUser' => [
            'id' => $currentUser->id,
            'name' => $currentUser->name,
            'email' => $currentUser->email,
            'role' => $currentUser->role,
        ],
        'filters' => [
            'superadmin_status' => $superadminStatus,
            'admin_status' => $adminStatus,
        ],
    ]);
}
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required|in:Admin,SuperAdmin,InactiveAdmin,InactiveSuperAdmin',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Update password only if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        // Uncomment when ready for logging
        ActionLogger::log(
            'update',
            'admins',
            "Updated {$admin->role} {$admin->name}"
        );

        // Redirect back to the edit user list page
        return back()->with('success', 'Admin updated successfully!');
    }

    /**
     * Create new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:Admin,SuperAdmin',
        ]);

        $admin = Admin::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        ActionLogger::log(
            'create',
            'admins',
            "Created {$admin->role} {$admin->name}"
        );
        
        // Redirect back with flash data (Inertia compatible)
        return redirect()->route('superadmin.addUser')->with([
            'success' => 'Admin created successfully!',
            'createdAdmin' => $admin->name,
        ]);
    }

    /**
     * Dashboard with action logs
     */
    public function dashboard()
    {
        $perPage = 10;
        
        $logs = ActionLog::with(['admin', 'user'])
            ->latest()
            ->paginate($perPage)
            ->through(function ($log) {
                $name = $log->admin ? $log->admin->name : 
                    ($log->user ? $log->user->name : 'System');
                
                if ($log->admin) {
                    $performerType = strtolower($log->admin->role) === 'superadmin' ? 'superadmin' : 'admin';
                    $performerIcon = 'admin_panel_settings';
                } elseif ($log->user) {
                    $performerType = 'applicant';
                    $performerIcon = 'person';
                } else {
                    $performerType = 'system';
                    $performerIcon = 'computer';
                }
                
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'table_name' => $log->table_name,
                    'performed_by' => $name,
                    'performer_type' => $performerType,
                    'performer_icon' => $performerIcon,
                    'performed_at' => $log->performed_at,
                    'details' => $log->details,
                ];
            });
        \Log::info('SuperAdminController@dashboard called');

        return Inertia::render('auth-superadmin/SuperAdminDashboard', [
            'logs' => $logs,
        ]);
    }

    /**
     * Add user page (if needed)
     */
    public function addUser()
    {
        return Inertia::render('auth-superadmin/SuperAdminAddUser');
    }
}