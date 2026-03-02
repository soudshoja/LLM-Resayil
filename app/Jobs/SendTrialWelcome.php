<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrialWelcome implements ShouldQueue
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
            "مرحباً {$user->name}! 🎉\n\n" .
            "تم تفعيل تجربتك المجانية في LLM Resayil.\n" .
            "✅ 1,000 رصيد جاهز للاستخدام\n" .
            "✅ وصول كامل لنماذج Starter لمدة 7 أيام\n\n" .
            "Welcome {$user->name}! Your 7-day free trial is active.\n" .
            "✅ 1,000 credits added to your account\n" .
            "✅ Access to all Starter models\n\n" .
            "API: https://llm.resayil.io/api/v1\n" .
            "Dashboard: https://llm.resayil.io/dashboard"
        );
    }
}
