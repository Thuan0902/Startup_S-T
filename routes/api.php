<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Analytics Routes
Route::post('/analytics/record-click', [AnalyticsController::class, 'recordClick']);
Route::post('/analytics/start-session', [AnalyticsController::class, 'startSession']);
Route::post('/analytics/end-session', [AnalyticsController::class, 'endSession']);
Route::get('/analytics/daily-stats', [AnalyticsController::class, 'getDailyStats']);
Route::get('/analytics/stats', [AnalyticsController::class, 'getStats']);
Route::get('/analytics/three-day-stats', [AnalyticsController::class, 'getThreeDayStats']);
Route::get('/analytics/export-daily', [AnalyticsController::class, 'exportDailyData']);
