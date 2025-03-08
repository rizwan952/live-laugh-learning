<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('get-courses', [CourseController::class, 'getCourses']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('get-profile', [AuthController::class, 'getProfile']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('get-resources', [DashboardController::class, 'getResources']);

    Route::post('course/create', [AdminCourseController::class, 'createCourse']);
    Route::put('course/update/{course}', [AdminCourseController::class, 'updateCourse']);
});



//
//Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
//    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
//    // Additional student routes
//});

