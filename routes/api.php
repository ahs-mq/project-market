<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Project routes
Route::apiResource("projects", ProjectController::class);
Route::get('/projects/filter', [ProjectController::class, 'filter']);
Route::get('/projects/search', [ProjectController::class, 'search']);
//register/login routes
Route::post('/register', [authcontroller::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [authcontroller::class, 'login'])->middleware('throttle:3,1');
Route::post('/logout', [authcontroller::class, 'logout'])->middleware('auth:sanctum');

//Order status for projects
Route::prefix('order')->middleware('auth:sanctum')->group(function () {
    Route::post('/send_offer', [OrderController::class, 'send_offer']);
    Route::post('/accept', [OrderController::class, 'accept']);
    Route::post('/reject', [OrderController::class, 'reject']);
    Route::post('/complete', [OrderController::class, 'complete']);
});
