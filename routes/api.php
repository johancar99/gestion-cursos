<?php

use App\Interfaces\Http\Controllers\AuthenticationController;
use App\Interfaces\Http\Controllers\UserController;
use App\Interfaces\Http\Controllers\CourseController;
use App\Interfaces\Http\Controllers\StudentController;
use App\Interfaces\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1 routes
Route::prefix('v1')->group(function () {
    
    // Authentication routes (sin autenticación para login)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'login']);
        Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
    });

    // User routes (con autenticación y permisos)
    Route::prefix('users')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [UserController::class, 'store'])->middleware('permission:create users');
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view users');
        Route::get('/all', [UserController::class, 'all'])->middleware('permission:view users');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('permission:view users');
        Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:edit users');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:delete users');
    });

    // Course routes (con autenticación y permisos)
    Route::prefix('courses')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [CourseController::class, 'store'])->middleware('permission:create courses');
        Route::get('/', [CourseController::class, 'index'])->middleware('permission:view courses');
        Route::get('/all', [CourseController::class, 'all'])->middleware('permission:view courses');
        Route::get('/{id}', [CourseController::class, 'show'])->middleware('permission:view courses');
        Route::put('/{id}', [CourseController::class, 'update'])->middleware('permission:edit courses');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->middleware('permission:delete courses');
    });

    // Student routes (con autenticación y permisos)
    Route::prefix('students')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [StudentController::class, 'store'])->middleware('permission:create students');
        Route::get('/', [StudentController::class, 'index'])->middleware('permission:view students');
        Route::get('/all', [StudentController::class, 'all'])->middleware('permission:view students');
        Route::get('/{id}', [StudentController::class, 'show'])->middleware('permission:view students');
        Route::put('/{id}', [StudentController::class, 'update'])->middleware('permission:edit students');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->middleware('permission:delete students');
    });

    // Enrollment routes (con autenticación y permisos)
    Route::prefix('enrollments')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [EnrollmentController::class, 'store'])->middleware('permission:create enrollments');
        Route::get('/', [EnrollmentController::class, 'index'])->middleware('permission:view enrollments');
        Route::get('/{id}', [EnrollmentController::class, 'show'])->middleware('permission:view enrollments');
        Route::get('/course/{courseId}', [EnrollmentController::class, 'byCourse'])->middleware('permission:view enrollments');
        Route::get('/student/{studentId}', [EnrollmentController::class, 'byStudent'])->middleware('permission:view enrollments');
        Route::delete('/{id}', [EnrollmentController::class, 'destroy'])->middleware('permission:delete enrollments');
    });
}); 