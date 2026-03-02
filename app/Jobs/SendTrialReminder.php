<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrialReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $userId) {}

    public function handle(WhatsAppNotificationService $whatsapp): void
    {
        $user = User::find($this->userId);
        if (!$user || !$user->phone) {
            return;
        }

        $whatsapp->send(
            $user->phone,
            "⏰ تذكير: يتبقى يوم واحد فقط على انتهاء تجربتك المجانية!\n\n" .
            "⏰ Reminder: 1 day left in your free trial!\n\n" .
            "سيتم تحصيل 15 KWD تلقائياً للاشتراك في خطة Starter.\n" .
            "Auto-charge of 15 KWD for Starter plan will happen tomorrow.\n\n" .
            "لإلغاء أو الترقية / Cancel or upgrade:\n" .
            "https://llm.resayil.io/billing/plans"
        );
    }
}
