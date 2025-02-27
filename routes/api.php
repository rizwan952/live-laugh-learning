<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


Route::post('login',[AuthController::class, 'login']);
Route::post('register',[AuthController::class, 'register']);


//Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
//    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
//    // Additional admin routes
//});
//
//Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
//    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
//    // Additional student routes
//});

