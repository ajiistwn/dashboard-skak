<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CrewAndCastController;

Route::get('/crew-and-casts', [CrewAndCastController::class, 'index']);

Route::get('/test', function () {
    return response()->json(['message' => 'tes berhasil']);
});
