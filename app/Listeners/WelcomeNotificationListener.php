<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeNotificationListener implements ShouldQueue
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
    public function handle(User $user): void
    {
        // Dispatch notification job
        SendWhatsAppNotification::dispatch(
            $user->id,
            $user->id,
            null,
            $user->language ?? 'ar',
            'welcome',
            [
                'credits' => 0,
            ]
        );
    }
}
