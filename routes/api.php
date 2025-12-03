<?php

use App\Http\Controllers\Api\V1\ClueController;
use App\Http\Controllers\Api\V1\OnlineCourseController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\IsfahanProfessionController;
use App\Http\Controllers\Api\V1\ProfessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiKey;

Route::middleware(['throttle:30,1', ApiKey::class])->prefix('v1')->group(function () {
    Route::get('/courses/online', [OnlineCourseController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/professions', [ProfessionController::class, 'index']);
    Route::get('/professions/{profession}', [ProfessionController::class, 'show']);
    Route::get('/professions/{profession}/courses', [ProfessionController::class, 'courses']);

    Route::prefix('clues')->group(function () {
        Route::post('/store', [ClueController::class, 'store']);
    });

    Route::prefix('isfahan')->group(function () {
        Route::get('/professions', [IsfahanProfessionController::class, 'index']);
        Route::get('/professions/{profession}/courses', [IsfahanProfessionController::class, 'courses']);
    });
});
