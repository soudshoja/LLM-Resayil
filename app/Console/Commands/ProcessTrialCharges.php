<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BillingService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessTrialCharges extends Command
{
    protected $signature = 'trial:process-charges';
    protected $description = 'Subscribe users whose 7-day trial has expired to Starter plan';

    public function handle(BillingService $billing, WhatsAppNotificationService $whatsapp): void
    {
        // Find users whose trial expired (started 7+ days ago) and not yet auto-charged
        $expiredUsers = User::whereNotNull('trial_started_at')
            ->whereNull('myfatoorah_subscription_id')
            ->where('trial_started_at', '<=', now()->subDays(7))
            ->get();

        $this->info("Found {$expiredUsers->count()} expired trials.");

        foreach ($expiredUsers as $user) {
            try {
                $subscription = $billing->subscribeUser($user->id, 'starter');

                $user->subscription_tier = 'starter';
                $user->subscription_expiry = now()->addDays(30);
                $user->myfatoorah_subscription_id = 'auto-billed-' . $subscription->id;
                $user->save();

                if ($user->phone) {
                    $whatsapp->send(
                        $user->phone,
                        "✅ تم تفعيل اشتراك Starter — 15 KWD/شهر.\n" .
                        "✅ Starter plan activated — 15 KWD/month.\n\n" .
                        "رصيدك الجديد: 1,000 رصيد\n" .
                        "New balance: 1,000 credits\n\n" .
                        "Manage: https://llm.resayil.io/billing/plans"
                    );
                }

                $this->info("Activated Starter for: {$user->email}");
                Log::info("Trial auto-charged: {$user->email}");

            } catch (\Exception $e) {
                $this->error("Failed for {$user->email}: " . $e->getMessage());
                Log::error("Trial auto-charge failed", [
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
