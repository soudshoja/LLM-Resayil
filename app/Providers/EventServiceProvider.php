<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\UserRegistered::class => [
            \App\Listeners\WelcomeNotificationListener::class,
        ],
        \App\Events\SubscriptionPaid::class => [
            \App\Listeners\SubscriptionConfirmationListener::class,
            \App\Listeners\TopUpConfirmationListener::class,
        ],
        \App\Events\CreditsLow::class => [
            \App\Listeners\CreditWarningListener::class,
        ],
        \App\Events\CreditsExhausted::class => [
            \App\Listeners\CreditsExhaustedListener::class,
        ],
        \App\Events\SubscriptionExpiring::class => [
            \App\Listeners\RenewalReminderListener::class,
        ],
        \App\Events\CloudBudgetWarning::class => [
            \App\Listeners\CloudBudgetAlertListener::class,
        ],
        \App\Events\CloudBudgetExceeded::class => [
            \App\Listeners\CloudBudgetAlertListener::class,
        ],
        \App\Events\IPBanned::class => [
            \App\Listeners\AdminAlertListener::class,
        ],
        \App\Events\NewEnterpriseCustomer::class => [
            \App\Listeners\AdminAlertListener::class,
            \App\Listeners\NewEnterpriseCustomerListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
