<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes (guest middleware)
Route::middleware(['guest', 'preventCaching'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('login');
    Route::get('auth/google', [GoogleController::class, 'loginGoogle'])->name('login.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.googlecallback');
    Route::get('/admin/login', [AdminController::class, 'show'])
        ->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])
        ->name('admin.login.submit');

});

// Protected routes (WITH auth middleware)
Route::middleware(['auth', 'role:Applicant', 'preventCaching'])->group(function () {
    // Route::get('/application', [ApplicationController::class, 'show'])->name('application');
    Route::get('/dashboard', [ApplicationController::class, 'index'])->name('dashboard');
    Route::get('/application/form', [ApplicationController::class, 'form'])->name('application.form');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('logout', [OAuthController::class, 'destroy'])->name('logout');
});
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');
Route::get('/admin/applications/{application}', [AdminController::class, 'showApplicationDetails'])->name('admin.applications.show');
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
Route::get('/admin/variables', [AdminController::class, 'variables'])->name('admin.variables');
Route::middleware(['auth', 'role:Admin', 'preventCaching'])->group(function () {
   
});