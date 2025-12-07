<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SchoolYearController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Routes
|--------------------------------------------------------------------------
*/

// Regular user login page (web guard)
Route::middleware(['guest:web', 'preventCaching'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('login');
    Route::get('auth/google', [GoogleController::class, 'loginGoogle'])->name('login.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.googlecallback');
});

// Admin login page (admin guard)
Route::middleware(['guest:admin', 'preventCaching'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'show'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'authenticate']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

// Applicant/User routes (web guard)
Route::middleware(['auth:web', 'role:Applicant', 'preventCaching'])->group(function () {
    Route::get('/dashboard', [ApplicationController::class, 'index'])->name('dashboard');
    Route::get('/application/form', [ApplicationController::class, 'form'])->name('application.form');
    Route::post('/application/submit', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/logout', [OAuthController::class, 'destroy'])->name('logout');
});

// Admin routes (admin guard)
Route::middleware(['auth:admin', 'role:Admin', 'preventCaching'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');
    Route::get('/admin/applications/{application}', [AdminController::class, 'showApplicationDetails'])->name('admin.applications.show');
    Route::get('/admin/application-periods', [AdminController::class, 'applicationPeriods'])->name('admin.application.periods');

    // School years
    Route::put('/admin/school-years/{schoolYear}', [SchoolYearController::class, 'update'])->name('admin.school-years.update');
    Route::post('/admin/school-years/{schoolYear}/close', [SchoolYearController::class, 'closeApplications'])->name('admin.application.periods.close');
    Route::post('/admin/school-years', [SchoolYearController::class, 'store'])->name('admin.school-years.store');
    Route::post('/admin/school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])->name('admin.school-years.activate');
    Route::delete('/admin/school-years/{schoolYear}', [SchoolYearController::class, 'destroy'])->name('admin.school-years.delete');

    // File downloads
    Route::get('/admin/files/download/{filePath}', [FileController::class, 'download'])
        ->name('admin.files.download')
        ->where('filePath', '.*');

    // Update application status
    Route::post('/admin/applications/{application}/status', [AdminController::class, 'updateStatus'])->name('admin.applications.updateStatus');

    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
});

// SuperAdmin routes (admin guard + role SuperAdmin)
Route::middleware(['auth:admin', 'role:SuperAdmin', 'preventCaching'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/superadmin/edit-user', [SuperAdminController::class, 'editUser'])->name('superadmin.editUser');
    Route::get('/superadmin/add-user', [SuperAdminController::class, 'addUser'])->name('superadmin.addUser');
    Route::post('/superadmin/admins', [SuperAdminController::class, 'store'])->name('superadmin.admins.store');
    Route::get('/superadmin/admins/{admin}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.admins.edit');
    Route::put('/superadmin/admins/{admin}', [SuperAdminController::class, 'update'])->name('superadmin.admins.update');
    Route::post('/superadmin/logout', [LoginController::class, 'logout'])->name('superadmin.logout');
});
