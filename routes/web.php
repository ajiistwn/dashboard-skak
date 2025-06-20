<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Dashboard\Company\CompanyController;
use App\Http\Controllers\Dashboard\Company\EmployeeController;
use App\Http\Controllers\Dashboard\Utils\UserController;


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

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Route::prefix('users')->group(function () {
//     // GET /users → ambil semua user
//     Route::get('/', [UserController::class, 'index']);

//     // GET /users/{id} → ambil user berdasarkan ID
//     Route::get('/{id}', [UserController::class, 'show'])->name('users.show');

//     // POST /users → tambah user baru
//     Route::post('/', [UserController::class, 'store']);

//     // PUT /users/{id} → update user
//     Route::put('/{id}', [UserController::class, 'update']);
//     Route::patch('/{id}', [UserController::class, 'update']); // opsional, dukung PATCH juga

//     // DELETE /users/{id} → hapus user
//     Route::delete('/{id}', [UserController::class, 'destroy']);
// });

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleCallback']);



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
