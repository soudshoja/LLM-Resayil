<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Credit warning check - runs every hour
        $schedule->call(function () {
            $users = \App\Models\User::where('credits', '>', 0)->get();

            foreach ($users as $user) {
                $totalCredits = match($user->subscription_tier) {
                    'basic' => 1000,
                    'pro' => 5000,
                    'enterprise' => 20000,
                    default => 1000,
                };

                $remainingPercent = $totalCredits > 0 ? round(($user->credits / $totalCredits) * 100) : 0;

                if ($remainingPercent <= 20) {
                    event(new \App\Events\CreditsLow($user));
                }
            }
        })->hourly();

        // Renewal reminder - runs daily at 9 AM
        $schedule->call(function () {
            $expiringSubscriptions = \App\Models\Subscriptions::whereBetween('ends_at', [
                now(),
                now()->addDays(3),
            ])->where('status', 'active')->get();

            foreach ($expiringSubscriptions as $subscription) {
                event(new \App\Events\SubscriptionExpiring($subscription));
            }
        })->dailyAt('09:00');

        // Failed notifications retry - every 15 minutes
        $schedule->command('notifications:retry-failed')->everyFifteenMinutes();

        // Cloud budget check - every 6 hours
        $schedule->call(function () {
            $cloudUsage = $this->getCloudUsagePercentage();

            if ($cloudUsage >= 80) {
                if ($cloudUsage >= 100) {
                    event(new \App\Events\CloudBudgetExceeded());
                } else {
                    event(new \App\Events\CloudBudgetWarning());
                }
            }
        })->everySixHours();

        // New enterprise customer check - hourly
        $schedule->call(function () {
            $newEnterprise = \App\Models\Subscriptions::where('tier', 'enterprise')
                ->where('created_at', '>=', now()->subHour())
                ->where('status', 'active')
                ->get();

            foreach ($newEnterprise as $subscription) {
                $user = $subscription->user;
                event(new \App\Events\NewEnterpriseCustomer($user));
            }
        })->hourly();
    }

    /**
     * Get current cloud usage percentage.
     */
    private function getCloudUsagePercentage(): int
    {
        // Placeholder implementation - should be replaced with actual cloud usage tracking
        // Returns a simulated value for testing
        $totalResources = 1000;
        $usedResources = 800; // Simulated usage

        return $totalResources > 0 ? round(($usedResources / $totalResources) * 100) : 0;
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
