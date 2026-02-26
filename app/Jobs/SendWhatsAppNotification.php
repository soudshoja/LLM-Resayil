<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The notification ID to send.
     *
     * @var string
     */
    public $notificationId;

    /**
     * The user ID (nullable for admin notifications).
     *
     * @var string|null
     */
    public $userId;

    /**
     * The recipient phone number (nullable if user_id set).
     *
     * @var string|null
     */
    public $phone;

    /**
     * The language code (en/ar).
     *
     * @var string
     */
    public $language;

    /**
     * The template code.
     *
     * @var string
     */
    public $templateCode;

    /**
     * The metadata for message formatting.
     *
     * @var array
     */
    public $metadata;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $notificationId,
        ?string $userId = null,
        ?string $phone = null,
        string $language = 'ar',
        string $templateCode = '',
        array $metadata = []
    ) {
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->phone = $phone;
        $this->language = $language;
        $this->templateCode = $templateCode;
        $this->metadata = $metadata;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, \Illuminate\Contracts\Queue\JobMiddleware>
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping($this->notificationId)];
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppNotificationService $whatsappService): void
    {
        $notification = Notification::find($this->notificationId);

        if (!$notification) {
            \Log::warning("Notification {$this->notificationId} not found");
            return;
        }

        $template = NotificationTemplate::where('code', $this->templateCode)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            $notification->status = 'failed';
            $notification->error_message = 'Template not found: ' . $this->templateCode;
            $notification->save();
            \Log::error("Template {$this->templateCode} not found for notification {$this->notificationId}");
            return;
        }

        // Get template content for language
        $content = $template->getTemplate($this->language);

        // Format message with metadata
        $message = $this->formatMessage($content, $this->metadata);

        // Determine phone number
        $phone = $this->phone;

        if (!$phone && $this->userId) {
            $user = \App\Models\User::find($this->userId);
            if ($user && $user->phone) {
                $phone = $user->phone;
            }
        }

        if (!$phone) {
            $notification->status = 'failed';
            $notification->error_message = 'No phone number available';
            $notification->save();
            \Log::error("No phone number for notification {$this->notificationId}");
            return;
        }

        // Send notification
        $result = $whatsappService->send($phone, $message, $this->language);

        if ($result['status'] === 'success') {
            $notification->status = 'sent';
            $notification->message = $message;
            $notification->sent_at = now();
            $notification->error_message = null;
            $notification->save();

            \Log::info("Notification {$this->notificationId} sent successfully", [
                'phone' => $phone,
                'message_id' => $result['message_id'] ?? null,
            ]);
        } else {
            $notification->status = 'failed';
            $notification->error_message = $result['message'] ?? 'Unknown error';
            $notification->retry_count = ($notification->retry_count ?? 0) + 1;
            $notification->save();

            \Log::error("Notification {$this->notificationId} failed", [
                'phone' => $phone,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }

    /**
     * Format template content with metadata.
     */
    protected function formatMessage(string $content, array $metadata): string
    {
        foreach ($metadata as $key => $value) {
            $placeholder = '{' . $key . '}';
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}
