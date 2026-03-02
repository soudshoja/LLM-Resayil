<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Api\ChatCompletionsController;
use App\Http\Controllers\Api\ModelsController;

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

// OpenAI-compatible LLM API — /v1/... (API key auth only)
Route::prefix('v1')->middleware('api.key.auth')->group(function () {
    Route::post('/chat/completions', [ChatCompletionsController::class, 'store']);
    Route::get('/models', [ModelsController::class, 'index']);
});

// Internal protected endpoints (API key auth)
Route::middleware('api.key.auth')->group(function () {
    // Billing API endpoints
    Route::prefix('billing')->group(function () {
        Route::get('/subscription', [BillingController::class, 'getSubscription']);
        Route::get('/topup-packs', [BillingController::class, 'getTopupPacks']);
        Route::get('/topup-history', [BillingController::class, 'getTopupHistory']);
    });

    // Admin notification endpoints
    Route::prefix('admin/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/send', [NotificationController::class, 'sendManual']);
        Route::post('/test', [NotificationController::class, 'testTemplate']);
    });
});
