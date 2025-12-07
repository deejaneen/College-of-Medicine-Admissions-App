<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Application;
use App\Models\ActionLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create School Years first
        // SchoolYear::factory(12)->create();
        
        // // 2. CALL AdminSeeder BEFORE anything that needs admins
        // $this->call(AdminSeeder::class);
        
        // // 3. Create Users
        // User::factory(100)->create();
        
        // // 4. Create Applications
        // Application::factory(10)->create();
        
        // // 5. FINALLY create ActionLogs (needs Admins and Users)
        // ActionLog::factory()->count(50)->create(); // 70% admin, 30% applicant
        ActionLog::factory()->count(20)->adminAction()->create();
        ActionLog::factory()->count(20)->applicantAction()->create();
        ActionLog::factory()->count(10)->superadminAction()->create();
        ActionLog::factory()->count(5)->guestAction()->create();
        
    }
}