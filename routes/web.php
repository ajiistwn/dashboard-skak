<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dashboard\Company\CompanyController;
use App\Http\Controllers\Auth\GoogleAuthController;

Route::get('/', function () {
    return Inertia::render('auth/login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');


    Route::get('/company', [CompanyController::class, 'index'])->name('company');

    Route::get('employee', function () {
        return Inertia::render('employee');
    })->name('employee');
});

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleCallback']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
