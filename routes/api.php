<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CrewAndCastController;
use App\Http\Controllers\Api\FileAndDataController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\TaskProgressProjectController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::apiResource('crew-and-cast', CrewAndCastController::class);
Route::get('file-and-data/category-documents', [FileAndDataController::class, 'getCategoryDocuments'])
    ->name('file-and-data.category-documents');
Route::get('task-progress-project/progress-project', [TaskProgressProjectController::class, 'getProgressProject'])
    ->name('task-progress-project.progress-project');
Route::apiResource('file-and-data', FileAndDataController::class);
Route::apiResource('agenda', AgendaController::class);
Route::apiResource('task-progress-project', TaskProgressProjectController::class);


