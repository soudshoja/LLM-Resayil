<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\Billing\WebhookController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\Admin\ApiSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth routes - web interface
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth');

// Dashboard (protected)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// API Keys routes (web interface)
Route::get('/api-keys', [ApiKeysController::class, 'index'])->middleware('auth');
Route::post('/api-keys', [ApiKeysController::class, 'store'])->middleware('auth');
Route::delete('/api-keys/{id}', [ApiKeysController::class, 'destroy'])->middleware('auth');

// Billing routes (protected)
Route::middleware('auth')->group(function () {
    // Subscription plans page
    Route::get('/billing/plans', [PaymentController::class, 'index']);

    // Payment initiation
    Route::post('/billing/payment/subscription', [PaymentController::class, 'initiateSubscriptionPayment']);
    Route::post('/billing/payment/topup', [PaymentController::class, 'initiateTopupPayment']);

    // Webhook handler (public, but verified by MyFatoorah)
    Route::post('/billing/webhook', [WebhookController::class, 'handleWebhook']);
});

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/api-settings', [ApiSettingsController::class, 'index'])->name('admin.api-settings');
    Route::put('/api-settings', [ApiSettingsController::class, 'update'])->name('admin.api-settings.update');
});

// Team routes (Enterprise only)
Route::middleware(['auth', 'enterprise'])->prefix('teams')->group(function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('teams.index');
    Route::post('/members', [TeamMemberController::class, 'store'])->name('teams.members.store');
    Route::delete('/members/{id}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
});
