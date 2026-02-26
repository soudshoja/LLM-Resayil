<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\WhatsAppNotificationService;
use Illuminate\Console\Command;

class SendFailedNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:retry-failed
                            {--limit=10 : Maximum number of notifications to retry}
                            {--all : Retry all failed notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry sending failed WhatsApp notifications';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $whatsappService): int
    {
        $limit = $this->option('all') ? PHP_INT_MAX : (int) $this->option('limit');

        $this->info('Starting failed notifications retry...');

        $failedNotifications = Notification::where('status', 'failed')
            ->where('retry_count', '<', 3)
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();

        $processed = 0;
        $success = 0;
        $failed = 0;

        foreach ($failedNotifications as $notification) {
            $this->info("Retrying notification {$notification->id}...");

            $message = $notification->message;
            $language = $notification->language;
            $phone = $notification->phone;

            if (empty($phone)) {
                $this->warn("Notification {$notification->id}: No phone number, skipping");
                $notification->error_message = 'No phone number';
                $notification->save();
                $processed++;
                continue;
            }

            $result = $whatsappService->send($phone, $message, $language);

            $notification->retry_count = $notification->retry_count + 1;

            if ($result['status'] === 'success') {
                $notification->status = 'sent';
                $notification->sent_at = now();
                $notification->error_message = null;
                $notification->save();
                $this->info("Notification {$notification->id}: Sent successfully");
                $success++;
            } else {
                $notification->status = 'failed';
                $notification->error_message = $result['message'] ?? 'Unknown error';
                $notification->save();
                $this->warn("Notification {$notification->id}: Failed - {$result['message']}");
                $failed++;
            }

            $processed++;
        }

        $this->info("\n=== Retry Summary ===");
        $this->info("Processed: {$processed}");
        $this->info("Success: {$success}");
        $this->info("Failed: {$failed}");

        return self::SUCCESS;
    }
}
