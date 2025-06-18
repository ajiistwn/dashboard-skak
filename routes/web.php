<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleAuthController;



Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('company', function () {
        return Inertia::render('company');
    })->name('company');
});


// Route::get('/auth/google/redirect', function (Request $request) {
//     return Socialite::driver('google')->redirect();
// });
// Route::get('/auth/google/callback', function (Request $request) {
//     dd($request->all());
// });

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleCallback']);



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
