<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

// Public routes (NO auth middleware)
Route::get('/', [IndexController::class, 'index'])->name('login');

Route::get('auth/google', [GoogleController::class, 'loginGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.googlecallback');

// Protected routes (WITH auth middleware)
Route::middleware(['auth', 'role:Applicant'])->group(function () {
    Route::get('/application', [ApplicationController::class, 'show'])->name('application');
    Route::get('/dashboard', [ApplicationController::class, 'index'])->name('dashboard');
    Route::post('logout', [OAuthController::class, 'destroy'])->name('logout');
    Route::get('/application/form', [ApplicationController::class, 'form'])->name('application.form');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
});