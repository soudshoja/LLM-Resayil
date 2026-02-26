<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditWarningListener implements ShouldQueue
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
        $totalCredits = match($user->subscription_tier) {
            'basic' => 1000,
            'pro' => 5000,
            'enterprise' => 20000,
            default => 1000,
        };

        $remainingPercent = $totalCredits > 0 ? round(($user->credits / $totalCredits) * 100) : 0;

        // Dispatch notification job
        SendWhatsAppNotification::dispatch(
            'credit_warn_' . $user->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'credit_warning',
            [
                'remaining' => $remainingPercent,
            ]
        );
    }
}
