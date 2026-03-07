<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\Billing\PaymentMethodsController;
use App\Http\Controllers\Billing\WebhookController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdminModelController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;

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

// Landing page templates
Route::get('/landing/1', fn() => view('landing.template-1'))->name('landing.1');
Route::get('/landing/2', fn() => view('landing.template-2'))->name('landing.2');
Route::get('/landing/3', fn() => view('landing.template-3'))->name('landing.3');

// Locale switcher — sets session locale and redirects back (no auth required)
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale')->where('locale', 'en|ar');

// Also accept POST (used by JS form submit)
Route::post('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->where('locale', 'en|ar');

// AJAX locale switcher — sets session locale, returns JSON (no redirect)
Route::post('/locale/ajax/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return response()->json(['locale' => $locale]);
})->where('locale', 'en|ar');

// Web routes group
Route::group([], function () {

    // Auth routes - web interface
    Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
    Route::post('/register/otp', [App\Http\Controllers\Auth\RegisteredUserController::class, 'sendOtp'])->middleware('guest');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth');

    // Dashboard (protected)
    Route::get('/dashboard', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('dashboard');
        return view('dashboard', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->middleware('auth');

    // Profile (protected)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/phone/otp', [App\Http\Controllers\OtpController::class, 'sendPhoneOtp'])->name('profile.phone.otp');
        Route::post('/profile/phone/verify', [App\Http\Controllers\OtpController::class, 'verifyPhoneOtp'])->name('profile.phone.verify');
    });

    // API Keys routes (web interface)
    Route::get('/api-keys', [ApiKeysController::class, 'index'])->middleware('auth');
    Route::post('/api-keys', [ApiKeysController::class, 'store'])->middleware('auth');
    Route::delete('/api-keys/{id}', [ApiKeysController::class, 'destroy'])->middleware('auth');

    // Billing routes (protected)
    Route::middleware('auth')->group(function () {
        // Subscription plans page
        Route::get('/billing/plans', [PaymentController::class, 'index'])->name('billing.plans');

        // Trial payment routes
        Route::post('/billing/trial/start', [PaymentController::class, 'initiateTrialPayment'])->name('billing.trial.start');
        Route::get('/billing/trial/callback', [PaymentController::class, 'handleTrialCallback'])->name('billing.trial.callback');

        // Payment methods page
        Route::get('/billing/payment-methods', [PaymentMethodsController::class, 'index'])->name('billing.payment-methods');
        Route::post('/billing/payment-methods', [PaymentMethodsController::class, 'store'])->name('billing.payment-methods.store');
        Route::delete('/billing/payment-methods/{id}', [PaymentMethodsController::class, 'destroy'])->name('billing.payment-methods.destroy');

        // Payment initiation
        Route::post('/billing/payment/subscription', [PaymentController::class, 'initiateSubscriptionPayment']);
        Route::post('/billing/payment/topup', [PaymentController::class, 'initiateTopupPayment']);
        Route::post('/billing/payment/extra-key', [PaymentController::class, 'initiateExtraKeyPayment'])->name('billing.extra-key.pay');
        Route::get('/billing/extra-key/callback', [PaymentController::class, 'handleExtraKeyCallback'])->name('billing.extra-key.callback');

        // Topup callback — MyFatoorah redirects here after payment with ?paymentId=XXX
        Route::get('/billing/topup/callback', [PaymentController::class, 'handleTopupCallback'])->name('billing.topup.callback');

    });

    // Webhook handler (outside auth — MyFatoorah calls this without session)
    Route::post('/billing/webhook', [WebhookController::class, 'handleWebhook'])->name('billing.webhook');

    // Credits documentation page
    Route::get('/credits', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('credits');
        return view('credits', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('credits');

    // Documentation landing page
    Route::get('/docs', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs');
        return view('docs.index', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.index');

    // Documentation subsections
    Route::get('/docs/getting-started', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.getting-started');
        return view('docs.getting-started', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.getting-started');

    Route::get('/docs/authentication', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.authentication');
        return view('docs.authentication', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.authentication');

    Route::get('/docs/models', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.models');
        $models = collect(config('models.models'))
            ->filter(fn($m) => $m['is_active'] ?? true);
        $localModels = $models->filter(fn($m) => ($m['type'] ?? 'local') === 'local');
        $cloudModels = $models->filter(fn($m) => ($m['type'] ?? 'local') === 'cloud');
        return view('docs.models', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
            'models' => $models,
            'localModels' => $localModels,
            'cloudModels' => $cloudModels,
        ]);
    })->name('docs.models');

    Route::get('/docs/billing', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.billing');
        return view('docs.billing', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.billing');

    Route::get('/docs/rate-limits', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.rate-limits');
        return view('docs.rate-limits', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.rate-limits');

    Route::get('/docs/error-codes', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.error-codes');
        return view('docs.error-codes', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.error-codes');

    Route::get('/docs/usage', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.usage');
        return view('docs.usage', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.usage');

    Route::get('/docs/topup', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.topup');
        return view('docs.topup', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.topup');

    Route::get('/docs/credits', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('docs.credits');
        return view('docs.credits', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('docs.credits');

    // Contact page (public, no auth required)
    Route::get('/contact', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('contact');
        return view('contact', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('contact');

    // Contact form submission (public, no auth required)
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    // Home route
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    // Static/legal pages
    Route::get('/about', [WelcomeController::class, 'about'])->name('about');
    Route::get('/privacy-policy', [WelcomeController::class, 'privacy'])->name('privacy-policy');
    Route::get('/terms-of-service', [WelcomeController::class, 'terms'])->name('terms-of-service');

    // Comparison and informational pages
    Route::get('/comparison', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('comparison');
        return view('comparison', [
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('comparison');

    Route::get('/alternatives', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('alternatives');
        return view('alternatives', [
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('alternatives');

    Route::get('/cost-calculator', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('cost-calculator');
        return view('cost-calculator', [
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('cost-calculator');

    Route::get('/dedicated-server', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('dedicated-server');
        return view('dedicated-server', [
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('dedicated-server');

    Route::get('/pricing', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('pricing');
        return view('pricing', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('pricing');

    Route::get('/features', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('features');
        return view('features', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('features');

    Route::get('/faq', function () {
        $meta = \App\Helpers\SeoHelper::getPageMeta('faq');
        return view('faq', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    })->name('faq');

    // Sitemap
    Route::get('/sitemap.xml', function () {
        $routes = [
            ['url' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['url' => '/comparison', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => '/alternatives', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => '/cost-calculator', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['url' => '/dedicated-server', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => '/pricing', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => '/features', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => '/faq', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => '/docs', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => '/credits', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => '/contact', 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['url' => '/register', 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['url' => '/login', 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];
        return response()->view('sitemap', compact('routes'))
            ->header('Content-Type', 'application/xml');
    })->name('sitemap');

    // Model catalog for dashboard (session auth, no API key required)
    Route::get('/models/catalog', [App\Http\Controllers\Api\ModelsController::class, 'index'])->middleware('auth');

    // Admin routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            $meta = \App\Helpers\SeoHelper::getPageMeta('admin.dashboard');
            return view('admin.dashboard', [
                'pageTitle' => $meta['title'],
                'pageDescription' => $meta['description'],
                'pageKeywords' => $meta['keywords'],
                'ogImage' => $meta['ogImage'],
                'ogType' => $meta['ogType'],
            ]);
        })->name('admin.dashboard');
        Route::get('/api-settings', [ApiSettingsController::class, 'index'])->name('admin.api-settings');
        Route::put('/api-settings', [ApiSettingsController::class, 'update'])->name('admin.api-settings.update');
        Route::get('/monitoring', function () {
            $meta = \App\Helpers\SeoHelper::getPageMeta('admin.dashboard');
            return view('admin.monitoring', [
                'pageTitle' => 'Monitoring — LLM Resayil',
                'pageDescription' => 'System monitoring dashboard',
                'pageKeywords' => 'monitoring, system health',
                'ogImage' => $meta['ogImage'],
                'ogType' => $meta['ogType'],
            ]);
        })->name('admin.monitoring');

        // Model management routes
        Route::get('/models', [AdminModelController::class, 'index'])->name('admin.models');
        Route::post('/models/update', [AdminModelController::class, 'update'])->name('admin.models.update');

        // User management routes
        Route::post('/users/{user}/keys', [AdminController::class, 'createApiKeyForUser'])->name('admin.users.keys.create');
        Route::post('/users/{user}/credits', [AdminController::class, 'setUserCredits'])->name('admin.users.credits.set');
        Route::post('/users/{user}/tier', [AdminController::class, 'setUserTier'])->name('admin.users.tier.set');
        Route::post('/users/{user}/expiry', [AdminController::class, 'setUserExpiry'])->name('admin.users.expiry.set');
    });

    // Team routes (Enterprise only)
    Route::middleware(['auth', 'enterprise'])->prefix('teams')->group(function () {
        Route::get('/', [TeamMemberController::class, 'index'])->name('teams.index');
        Route::post('/members', [TeamMemberController::class, 'store'])->name('teams.members.store');
        Route::delete('/members/{id}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
    });
});
