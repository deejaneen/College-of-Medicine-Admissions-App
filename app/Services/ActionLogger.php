<?php

namespace App\Services;

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class ActionLogger
{
    public static function log(string $action, string $tableName, string $details = null)
    {
        $logData = [
            'action' => $action,
            'table_name' => $tableName,
            'details' => $details,
            'performed_at' => now(),
        ];

        // Check admin guard first
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $logData['performed_by'] = $admin->id;
            $logData['user_type'] = 'admin'; 
            $logData['user_role'] = $admin->role ?? 'admin'; 
        } 
        // Check for applicants (web guard)
        elseif (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $logData['performed_by'] = $user->id;
            $logData['user_type'] = 'user'; 
            $logData['user_role'] = $user->role ?? 'applicant'; 
        }
        // Fallback to default Auth::check()
        elseif (Auth::check()) {
            $user = Auth::user();
            $logData['performed_by'] = $user->id;
            $logData['user_type'] = get_class($user); 
            $logData['user_role'] = $user->role ?? 'user';
        }
        // No one is authenticated
        else {
            $logData['performed_by'] = null;
            $logData['user_type'] = 'guest';
        }

        ActionLog::create($logData);
    }
}