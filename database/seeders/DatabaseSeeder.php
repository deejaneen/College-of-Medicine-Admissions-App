<?php

namespace Database\Seeders;


use App\Models\Application;
use App\Models\SchoolYear;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //  User::factory(10)->create();
        //  SchoolYear::factory(2)->create();
         Application::factory(40)->create();

    }
}
