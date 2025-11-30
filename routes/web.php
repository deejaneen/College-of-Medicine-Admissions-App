<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SchoolYearController;
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
    Route::post('/application/submit', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('logout', [OAuthController::class, 'destroy'])->name('logout');
});
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');

Route::get('/admin/applications/{application}', [AdminController::class, 'showApplicationDetails'])
    ->name('admin.applications.show');
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
Route::get('/admin/application-periods', [AdminController::class, 'applicationPeriods'])
    ->name('admin.application.periods');

// School years routes
Route::put('/admin/school-years/{schoolYear}', [SchoolYearController::class, 'update'])
    ->name('admin.school-years.update');
Route::post('/admin/school-years/{schoolYear}/close', [SchoolYearController::class, 'closeApplications'])
    ->name('admin.application.periods.close');
Route::post('/admin/school-years', [SchoolYearController::class, 'store'])
    ->name('admin.school-years.store');
Route::post('/admin/school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])
    ->name('admin.school-years.activate');
Route::delete('/admin/school-years/{schoolYear}', [SchoolYearController::class, 'destroy'])
    ->name('admin.school-years.delete');

// File download route
Route::get('/admin/files/download/{filePath}', [FileController::class, 'download'])
    ->name('admin.files.download')
    ->where('filePath', '.*');

// Update application status
Route::post('/admin/applications/{application}/status', [AdminController::class, 'updateStatus'])
    ->name('admin.applications.updateStatus');

Route::middleware(['auth', 'role:Admin', 'preventCaching'])->group(function () {});
