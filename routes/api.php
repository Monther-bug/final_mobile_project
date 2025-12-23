<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProblemController;
use App\Http\Controllers\Api\SolutionController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\LeaderboardController;

use App\Http\Controllers\Api\AuthController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('exercises', [ExerciseController::class, 'index']);
Route::get('exercises/{exercise}', [ExerciseController::class, 'show']);
Route::get('problems/{problem}', [ProblemController::class, 'show']);

Route::get('leaderboard', [LeaderboardController::class, 'index']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('solutions', [SolutionController::class, 'store']);
    Route::put('solutions/{solution}', [SolutionController::class, 'update']);
    Route::delete('solutions/{solution}', [SolutionController::class, 'destroy']);
    Route::get('user/history', [SolutionController::class, 'history']);
    
    Route::post('progress', [ProgressController::class, 'update']);
    
    Route::get('problems/{problem}/hint', [ProblemController::class, 'hint']);
});
