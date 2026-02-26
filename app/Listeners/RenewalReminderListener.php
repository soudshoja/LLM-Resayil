<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\Subscriptions;
use Illuminate\Contracts\Queue\ShouldQueue;

class RenewalReminderListener implements ShouldQueue
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

        // Dispatch notification job
        SendWhatsAppNotification::dispatch(
            'renewal_' . $subscription->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'renewal_reminder',
            []
        );
    }
}
