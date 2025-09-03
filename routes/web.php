<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index'])->name('dashboard');
Route::get('/home', [ApplicationController::class, 'show']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
