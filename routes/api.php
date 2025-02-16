<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\ReviewsController;

// the routes for the api

// the registration, login route 
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// the routes that need authentication
Route::middleware('auth:sanctum')->group(function () {

    //------------------------------------------------------------------------------------------

    // the user routes
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::delete('/user', [UserController::class, 'delete']);
    Route::get('users', [UserController::class, 'users']);



    //------------------------------------------------------------------------------------------


    // the course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('courses', [CourseController::class, 'createCourse']);
    Route::put('courses/{id}', [CourseController::class, 'updateCourse']);
    Route::delete('courses/{id}', [CourseController::class, 'deleteCourse']);

    //------------------------------------------------------------------------------------------


    // the enrollment routes
    Route::post('enrollment', [EnrollmentController::class, 'enroll']);
    Route::delete('enrollment/{id}', [EnrollmentController::class, 'unenroll']);
    Route::get('enrollment', [EnrollmentController::class, 'myCourses']);
    Route::put('enrollment/{id}', [EnrollmentController::class, 'setState']);
    Route::get('enrollment/{id}', [EnrollmentController::class, 'courseEnrollments']);


    //------------------------------------------------------------------------------------------


    // the lesson routes
    Route::post('course/lesson', [LessonsController::class, 'addLesson']);
    Route::put('course/lesson/{id}', [LessonsController::class, 'updateLesson']);
    Route::delete('course/lesson/{id}', [LessonsController::class, 'deleteLesson']);
    Route::get('course/{id}/lesson', [LessonsController::class, 'courseLessons']);

    //------------------------------------------------------------------------------------------

    // the review routes
    Route::post('course/{id}/reviews', [ReviewsController::class, 'addReview']);
    Route::put('reviews/{review_id}', [ReviewsController::class, 'updateReview']);
    Route::delete('reviews/{review_id}', [ReviewsController::class, 'deleteReview']);
    Route::get('course/{id}/reviews', [ReviewsController::class, 'courseReviews']);

    //------------------------------------------------------------------------------------------
});

