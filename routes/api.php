<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProblemController;
use App\Http\Controllers\Api\SolutionController;
use App\Http\Controllers\Api\ProgressController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('exercises', ExerciseController::class)->only(['index', 'show']);
    Route::get('problems/{problem}', [ProblemController::class, 'show']);
    Route::apiResource('solutions', SolutionController::class)->only(['store', 'update', 'destroy']);
    Route::post('progress', [ProgressController::class, 'update']);
});
