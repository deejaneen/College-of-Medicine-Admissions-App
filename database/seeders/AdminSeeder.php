<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'ane@doain.com',
            'password' => bcrypt('password123'),
            'role' => 'SuperAdmin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'otjan@domain.com',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'ane@doman.co',
            'password' => bcrypt('password123'),
            'role' => 'SuperAdmin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'otjane@dmain.c',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'ane@doain.o',
            'password' => bcrypt('password123'),
            'role' => 'SuperAdmin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'otjae@domain.i',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'ane@doin.com',
            'password' => bcrypt('password123'),
            'role' => 'SuperAdmin',
        ]);
        Admin::create([
            'name' => 'Jane Doe',
            'email' => 'otje@domin.com',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);
    }
}
