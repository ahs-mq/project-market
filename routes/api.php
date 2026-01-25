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
// specific project routes must come before the resource route
Route::get('/projects/filter', [ProjectController::class, 'filter']);
Route::get('/projects/search', [ProjectController::class, 'search']);
Route::post('projects/{project}/send_offer', [ProjectController::class, 'send_offer'])->middleware('auth:sanctum');
Route::post('projects/{project}/accept', [ProjectController::class, 'accept'])->middleware('auth:sanctum');
Route::post('projects/{project}/reject', [ProjectController::class, 'reject'])->middleware('auth:sanctum');
Route::post('projects/{project}/cancel', [ProjectController::class, 'cancel'])->middleware('auth:sanctum');
Route::post('projects/{project}/complete', [ProjectController::class, 'complete'])->middleware('auth:sanctum');
Route::apiResource("projects", ProjectController::class);
//register/login routes
Route::post('/register', [authcontroller::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [authcontroller::class, 'login'])->middleware('throttle:3,1');
Route::post('/logout', [authcontroller::class, 'logout'])->middleware('auth:sanctum');

// //Order status for projects
// Route::prefix('order')->middleware('auth:sanctum')->group(function () {});
