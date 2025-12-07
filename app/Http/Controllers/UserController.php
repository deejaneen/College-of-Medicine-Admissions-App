<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Services\ActionLogger;

class UserController extends Controller
{
    // List users (for AG Grid)
    public function index(Request $request): Response
    {
        $users = User::all()->toArray();
        
        // Optional: filter by search term
        return Inertia::render('auth-admin/AdminUsers', [
            'title' => 'Users',
            'users' => $users,
        ]);

       
    }

    // Other CRUD methods (optional)
    public function store(Request $request) { /* ... */ }
    public function update(Request $request, User $user) { /* ... */ }
    public function destroy(User $user) { /* ... */ }
}
