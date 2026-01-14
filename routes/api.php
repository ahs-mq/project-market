<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("projects", ProjectController::class);

//register/login routes
Route::post('/register', [authcontroller::class, 'register'])->middleware('throttle:3,1');
Route::post('/login', [authcontroller::class, 'login'])->middleware('throttle:3,1');
Route::post('/logout', [authcontroller::class, 'logout'])->middleware('auth:sanctum');
