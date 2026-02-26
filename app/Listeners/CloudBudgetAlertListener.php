<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloudBudgetAlertListener implements ShouldQueue
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
    public function handle(int $usagePercent, string $alertType = 'warning'): void
    {
        // Admin phone numbers from config
        $adminPhones = config('services.whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            \Log::warning('No admin phone numbers configured for cloud budget alerts');
            return;
        }

        $templateCode = $usagePercent >= 100 ? 'cloud_budget_100' : 'cloud_budget_80';

        foreach ($adminPhones as $phone) {
            SendWhatsAppNotification::dispatch(
                'cloud_budget_' . $alertType . '_' . now()->timestamp,
                null,
                $phone,
                'en',
                $templateCode,
                [
                    'limit' => '1000',
                    'used' => $usagePercent . '%',
                ]
            );
        }
    }
}
