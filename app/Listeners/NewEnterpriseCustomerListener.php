<?php

namespace App\Listeners;

use App\Jobs\SendWhatsAppNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewEnterpriseCustomerListener implements ShouldQueue
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
        // Admin phone numbers from config
        $adminPhones = config('services.whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            \Log::warning('No admin phone numbers configured for enterprise alerts');
            return;
        }

        $apiKeysCount = $user->apiKeys()->count();

        foreach ($adminPhones as $phone) {
            SendWhatsAppNotification::dispatch(
                'enterprise_' . $user->id,
                null,
                $phone,
                'en',
                'new_enterprise',
                [
                    'customer_name' => $user->name ?? 'Unknown',
                    'email' => $user->email ?? 'N/A',
                    'api_keys_count' => $apiKeysCount,
                ]
            );
        }
    }
}
