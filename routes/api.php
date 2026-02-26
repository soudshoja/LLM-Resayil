<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeysController;

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

// Protected API endpoints
Route::middleware(['auth:sanctum', 'api.key.auth'])->group(function () {
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });

    // API Keys routes
    Route::apiResource('api-keys', ApiKeysController::class);

    // Placeholder for future LLM API routes
    Route::post('/chat/completions', function () {
        return response()->json(['message' => 'LLM API endpoint - to be implemented']);
    });
});
