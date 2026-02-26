<?php

namespace App\Providers;

use App\Services\MyFatoorahService;
use App\Services\BillingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register MyFatoorah service
        $this->app->singleton(MyFatoorahService::class, function ($app) {
            return new MyFatoorahService();
        });

        // Register Billing service
        $this->app->singleton(BillingService::class, function ($app) {
            return new BillingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
