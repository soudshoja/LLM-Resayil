<?php

namespace App\Console\Commands;

use App\Jobs\SendTrialReminder;
use App\Models\User;
use Illuminate\Console\Command;

class SendTrialReminders extends Command
{
    protected $signature = 'trial:send-reminders';
    protected $description = 'Send WhatsApp reminder to users whose trial expires in ~1 day';

    public function handle(): void
    {
        // Users who started trial 6-7 days ago (reminder window)
        $users = User::whereNotNull('trial_started_at')
            ->whereNull('myfatoorah_subscription_id')
            ->whereBetween('trial_started_at', [
                now()->subDays(7)->startOfDay(),
                now()->subDays(6)->endOfDay(),
            ])
            ->get();

        $this->info("Sending reminders to {$users->count()} users.");

        foreach ($users as $user) {
            dispatch(new SendTrialReminder($user->id));
            $this->info("Queued reminder for: {$user->email}");
        }
    }
}
