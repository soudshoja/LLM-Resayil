<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\Subscriptions;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionConfirmationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Subscriptions $subscription): void
    {
        $user = $subscription->user;

        // Get plan details
        $planName = match($subscription->tier) {
            'basic' => 'Basic',
            'pro' => 'Pro',
            'enterprise' => 'Enterprise',
            default => $subscription->tier,
        };

        // Calculate credits based on plan
        $credits = match($subscription->tier) {
            'basic' => 1000,
            'pro' => 5000,
            'enterprise' => 20000,
            default => 0,
        };

        // Format expiry date
        $expiryDate = $subscription->ends_at->format('Y-m-d');

        // Dispatch notification job
        SendWhatsAppNotification::dispatch(
            'sub_conf_' . $subscription->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'subscription_confirmed',
            [
                'plan_name' => $planName,
                'expiry_date' => $expiryDate,
                'credits' => $credits,
            ]
        );
    }
}
