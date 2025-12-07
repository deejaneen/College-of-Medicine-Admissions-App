<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Admin;
use App\Models\Application;
use App\Models\ApplicationPeriod;
use App\Models\SchoolYear;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActionLog>
 */
class ActionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get existing IDs
        $userIds = User::pluck('id')->toArray();
        $adminIds = Admin::pluck('id')->toArray();
        $applicationIds = Application::pluck('id')->toArray();
        $periodIds = SchoolYear::pluck('id')->toArray();
        
        // Determine if this should be an admin/superadmin action or applicant action
        $isAdminAction = $this->faker->boolean(70); // 70% chance of being admin action
        
        if ($isAdminAction) {
            // ADMIN/SUPERADMIN ACTIONS
            $adminActions = [
                // Application actions
                'Approve Application',
                'Reject Application', 
                'Waitlist Application',
                
                // Application period actions
                'Add Application Period',
                'Edit Application Period',
                'Delete Application Period',
                'Close Application Period',
                'Activate Application Period',
                
                // Admin management actions (SuperAdmin only)
                'Create New Admin',
                'Create New SuperAdmin',
                'Edit Admin',
                'Edit SuperAdmin',
                'Delete Admin',
                'Delete SuperAdmin',
            ];
            
            $action = $this->faker->randomElement($adminActions);
            $performerId = $this->faker->randomElement($adminIds);
            
            // Get the admin to determine role
            $admin = Admin::find($performerId);
            
            // NEW: Add user_type, user_model, user_role
            $userType = 'admin';
            $userModel = 'Admin';
            $userRole = $admin ? ($admin->role ?? 'admin') : 'admin';
            
            // Determine table based on action
            if (str_contains($action, 'Application')) {
                $tableName = 'applications';
                $details = $this->generateApplicationDetails($action, $applicationIds);
            } elseif (str_contains($action, 'Period')) {
                $tableName = 'application_periods';
                $details = $this->generatePeriodDetails($action, $periodIds);
            } else {
                $tableName = 'admins';
                $details = $this->generateAdminDetails($action, $adminIds);
            }
        } else {
            // APPLICANT ACTIONS
            $applicantActions = [
                'Create Account',
                'Submit Application',
            ];
            
            $action = $this->faker->randomElement($applicantActions);
            $performerId = $this->faker->randomElement($userIds);
            
            // Get the user to determine role
            $user = User::find($performerId);
            
            // NEW: Add user_type, user_model, user_role
            $userType = 'applicant';
            $userModel = 'User';
            $userRole = $user ? ($user->role ?? 'applicant') : 'applicant';
            
            if ($action === 'Create Account') {
                $tableName = 'users';
                $details = 'User registered for the application system';
            } else {
                $tableName = 'applications';
                $details = 'Application submitted for review';
            }
        }
        
        return [
            'action'        => $action,
            'table_name'    => $tableName,
            'performed_by'  => $performerId,
            
            // NEW COLUMNS
            'user_type'     => $userType,
            'user_model'    => $userModel,
            'user_role'     => $userRole,
            
            'details'       => $details,
            'performed_at'  => $this->faker->dateTimeBetween('-6 months'),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
    
    /**
     * Generate realistic application action details
     */
    private function generateApplicationDetails(string $action, array $applicationIds): string
    {
        $applicationId = $this->faker->randomElement($applicationIds);
        
        $detailsMap = [
            'Approve Application' => "Application #{$applicationId} was approved for admission",
            'Reject Application' => "Application #{$applicationId} was rejected",
            'Waitlist Application' => "Application #{$applicationId} was placed on waitlist",
        ];
        
        return $detailsMap[$action] ?? "{$action} on application #{$applicationId}";
    }
    
    /**
     * Generate realistic application period details
     */
    private function generatePeriodDetails(string $action, array $periodIds): string
    {
        $periodId = $this->faker->randomElement($periodIds);
        
        $detailsMap = [
            'Add Application Period' => "New application period #{$periodId} was created",
            'Edit Application Period' => "Application period #{$periodId} was updated",
            'Delete Application Period' => "Application period #{$periodId} was deleted",
            'Close Application Period' => "Application period #{$periodId} was closed for applications",
            'Activate Application Period' => "Application period #{$periodId} was activated",
        ];
        
        return $detailsMap[$action] ?? "{$action} on period #{$periodId}";
    }
    
    /**
     * Generate realistic admin management details
     */
    private function generateAdminDetails(string $action, array $adminIds): string
    {
        $targetAdminId = $this->faker->randomElement($adminIds);
        
        $detailsMap = [
            'Create New Admin' => "New admin user #{$targetAdminId} was created",
            'Create New SuperAdmin' => "New superadmin user #{$targetAdminId} was created",
            'Edit Admin' => "Admin user #{$targetAdminId} was updated",
            'Edit SuperAdmin' => "Superadmin user #{$targetAdminId} was updated",
            'Delete Admin' => "Admin user #{$targetAdminId} was deleted",
            'Delete SuperAdmin' => "Superadmin user #{$targetAdminId} was deleted",
        ];
        
        return $detailsMap[$action] ?? "{$action} on admin #{$targetAdminId}";
    }
    
    /**
     * State for admin actions only
     */
    public function adminAction(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'admin',
                'user_model' => 'Admin',
                'user_role' => $this->faker->randomElement(['admin', 'superadmin']),
            ];
        });
    }
    
    /**
     * State for applicant actions only
     */
    public function applicantAction(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'applicant',
                'user_model' => 'User',
                'user_role' => 'applicant',
            ];
        });
    }
    
    /**
     * State for superadmin actions only
     */
    public function superadminAction(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'admin',
                'user_model' => 'Admin',
                'user_role' => 'superadmin',
            ];
        });
    }
    
    /**
     * State for guest actions (no user)
     */
    public function guestAction(): Factory
    {
        return $this->state(function (array $attributes) {
            $guestActions = [
                'View Application Form',
                'View Homepage',
                'View FAQ',
                'View Contact Page',
            ];
            
            return [
                'action' => $this->faker->randomElement($guestActions),
                'table_name' => 'pages',
                'performed_by' => null,
                'user_type' => 'guest',
                'user_model' => null,
                'user_role' => null,
                'details' => 'Guest user viewed page',
            ];
        });
    }
}