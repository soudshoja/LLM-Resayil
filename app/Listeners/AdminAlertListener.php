<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminAlertListener implements ShouldQueue
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
    public function handle(string $alertType, array $data = []): void
    {
        // Admin phone numbers from config
        $adminPhones = config('services.whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            \Log::warning('No admin phone numbers configured for admin alerts');
            return;
        }

        $templateCode = match($alertType) {
            'ip_banned' => 'ip_banned',
            'new_enterprise' => 'new_enterprise',
            default => 'ip_banned',
        };

        foreach ($adminPhones as $phone) {
            SendWhatsAppNotification::dispatch(
                'admin_alert_' . $alertType . '_' . now()->timestamp,
                null,
                $phone,
                'en',
                $templateCode,
                $data
            );
        }
    }
}
