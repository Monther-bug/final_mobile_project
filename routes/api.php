<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProblemController;
use App\Http\Controllers\Api\SolutionController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserStatsController;
use App\Http\Controllers\Api\DailyChallengeController;

use App\Http\Controllers\Api\AuthController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('exercises', [ExerciseController::class, 'index']);
Route::get('exercises/{exercise}', [ExerciseController::class, 'show']);
Route::get('problems/{problem}', [ProblemController::class, 'show']);

Route::get('leaderboard', [LeaderboardController::class, 'index']);

Route::get('categories', [CategoryController::class, 'index']);

Route::get('daily-challenge', [DailyChallengeController::class, 'index']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User stats and profile
    Route::get('user/stats', [UserStatsController::class, 'stats']);
    Route::get('user/profile', [UserStatsController::class, 'profile']);

    Route::post('solutions', [SolutionController::class, 'store']);
    Route::put('solutions/{solution}', [SolutionController::class, 'update']);
    Route::delete('solutions/{solution}', [SolutionController::class, 'destroy']);
    Route::get('solutions/{solution}', [SolutionController::class, 'show']);
    Route::get('user/history', [SolutionController::class, 'history']);
    
    Route::get('user/progress', [ProgressController::class, 'index']);
    Route::post('progress', [ProgressController::class, 'update']);
    
    Route::get('problems/{problem}/hint', [ProblemController::class, 'hint']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});
