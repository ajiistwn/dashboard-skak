<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CrewAndCastController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/crew-and-cast', [CrewAndCastController::class, 'index'])
    ->name('crew-and-cast.index');
