<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\ReviewController;


//Web routs
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('get-courses', [CourseController::class, 'getCourses']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('get-profile', [AuthController::class, 'getProfile']);
});

// Admin routs
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('get-resources', [DashboardController::class, 'getResources']);

    Route::post('course/create', [AdminCourseController::class, 'createCourse']);
    Route::put('course/update/{course}', [AdminCourseController::class, 'updateCourse']);
});


// Student routs
Route::prefix('student')->middleware(['auth:sanctum', 'role:student'])->group(function () {
    Route::get('course/orders', [OrderController::class, 'getOrders']);
    Route::post('course/order', [OrderController::class, 'order']);
    Route::apiResource('reviews', ReviewController::class);
});

//Stripe WEBHOOK
Route::post('stripe/webhook', [OrderController::class, 'handleWebhook']);
