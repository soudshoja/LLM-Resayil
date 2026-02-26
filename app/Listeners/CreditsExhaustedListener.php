<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditsExhaustedListener implements ShouldQueue
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
        // Dispatch notification job with top-up link
        SendWhatsAppNotification::dispatch(
            'credits_exhausted_' . $user->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'credits_exhausted',
            []
        );
    }
}
