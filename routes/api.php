<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\ChatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;


//Web routs
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('courses', [CourseController::class, 'getCourses']);
Route::get('reviews', [CourseController::class, 'getReviews']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', [ProfileController::class, 'getProfile']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    Route::get('get-calendar', [AdminCalendarController::class, 'index']);

    #Future chat routs
    Route::prefix('chat')->group(function () {
        Route::post('send', [ChatController::class, 'sendMessage']);
        Route::get('conversation/{conversation}', [ChatController::class, 'getConversation']);
        Route::get('get-unread-messages/{conversation}', [ChatController::class, 'getUnreadMessages']);
        Route::get('conversations', [ChatController::class, 'getConversations']);
    });
});

// Admin routs
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('resources', [DashboardController::class, 'getResources']);
    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    Route::get('course', [AdminCourseController::class, 'getCourses']);
    Route::post('course', [AdminCourseController::class, 'createCourse']);
    Route::put('course/update/{course}', [AdminCourseController::class, 'updateCourse']);

    Route::get('orders', [AdminOrderController::class, 'getOrders']);
    Route::put('order/{order}', [AdminOrderController::class, 'updateOrder']);
    Route::put('order-lesson/{orderPackageLesson}', [AdminOrderController::class, 'updateOrderLesson']);
    Route::apiResource('calendar', AdminCalendarController::class);
    Route::apiResource('reviews', AdminReviewController::class);
    Route::apiResource('student', AdminStudentController::class);

    Route::put('timeslot/{timeSlot}', [AdminCalendarController::class, 'updateTimeSlot']);
    Route::delete('timeslot/{timeSlot}', [AdminCalendarController::class, 'deleteTimeSlot']);
});


// Student routs
Route::prefix('student')->middleware(['auth:sanctum', 'role:student'])->name('student.')->group(function () {
    Route::get('orders', [OrderController::class, 'getOrders']);
    Route::post('order', [OrderController::class, 'order']);
    Route::put('order/{order}/package-lessons', [OrderController::class, 'updateOrderLessons']);
    //    Refund route
    Route::put('lesson-refund/{orderPackageLesson}', [OrderController::class, 'initiateRefund']);

    Route::apiResource('reviews', ReviewController::class);
});

//Stripe WEBHOOK
Route::post('stripe/webhook', [OrderController::class, 'handleWebhook']);
