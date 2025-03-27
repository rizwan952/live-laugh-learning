<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CalendarController as AdminCalendarController;


//Web routs
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('courses', [CourseController::class, 'getCourses']);
Route::get('reviews', [CourseController::class, 'getReviews']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::get('get-calendar', [AdminCalendarController::class, 'index']);
});

// Admin routs
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('resources', [DashboardController::class, 'getResources']);

    Route::post('course', [AdminCourseController::class, 'createCourse']);
    Route::put('course/update/{course}', [AdminCourseController::class, 'updateCourse']);

    Route::get('orders', [AdminOrderController::class, 'getOrders']);
    Route::put('order/{order}', [AdminOrderController::class, 'updateOrder']);
    Route::apiResource('calendar', AdminCalendarController::class);
    Route::apiResource('reviews', AdminReviewController::class);

});


// Student routs
Route::prefix('student')->middleware(['auth:sanctum', 'role:student'])->group(function () {
    Route::get('orders', [OrderController::class, 'getOrders']);
    Route::post('order', [OrderController::class, 'order']);
    Route::put('order/{order}/package-lessons', [OrderController::class, 'updateOrderLessons']);
    Route::apiResource('reviews', ReviewController::class);
});

//Stripe WEBHOOK
Route::post('stripe/webhook', [OrderController::class, 'handleWebhook']);
