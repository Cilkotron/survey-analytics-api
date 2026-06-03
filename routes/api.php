<?php

use App\Http\Controllers\Api\V1\AnalyticsController;
use App\Http\Controllers\Api\V1\ResponseController;
use App\Http\Controllers\Api\V1\SurveyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('surveys', SurveyController::class);
    Route::apiResource('responses', ResponseController::class)->only(['index', 'store']);

    Route::prefix('analytics')->group(function () {
        Route::get('surveys/{survey}', [AnalyticsController::class, 'survey']);
        Route::get('dashboard', [AnalyticsController::class, 'dashboard']);
    });
});
