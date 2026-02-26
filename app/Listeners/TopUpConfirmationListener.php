<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopUpConfirmationListener implements ShouldQueue
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
    public function handle(User $user, int $amount, int $creditsAdded): void
    {
        // Dispatch notification job
        SendWhatsAppNotification::dispatch(
            'topup_conf_' . $user->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'topup_confirmed',
            [
                'amount' => $amount,
                'current_credits' => $creditsAdded,
            ]
        );
    }
}
