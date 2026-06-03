<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnalyticsController;
use App\Http\Controllers\Api\V1\ResponseController;
use App\Http\Controllers\Api\V1\SurveyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::apiResource('surveys', SurveyController::class);
    Route::apiResource('responses', ResponseController::class)->only(['index', 'store']);

    Route::prefix('analytics')->group(function () {
        Route::get('surveys/{survey}', [AnalyticsController::class, 'survey']);
        Route::get('dashboard', [AnalyticsController::class, 'dashboard']);
    });
});
