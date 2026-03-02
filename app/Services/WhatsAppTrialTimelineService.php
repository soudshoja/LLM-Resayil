<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class WhatsAppTrialTimelineService
{
    /**
     * The WhatsApp notification service instance.
     */
    protected WhatsAppNotificationService $whatsappService;

    /**
     * The trial subscription service instance.
     */
    protected TrialSubscriptionService $trialSubscriptionService;

    /**
     * The billing service instance.
     */
    protected BillingService $billingService;

    /**
     * Create a new WhatsApp trial timeline service instance.
     */
    public function __construct(
        WhatsAppNotificationService $whatsappService,
        TrialSubscriptionService $trialSubscriptionService,
        BillingService $billingService
    ) {
        $this->whatsappService = $whatsappService;
        $this->trialSubscriptionService = $trialSubscriptionService;
        $this->billingService = $billingService;
    }

    /**
     * Send welcome message on trial start (Day 1).
     *
     * @param string $userId User ID
     * @return array Response with status
     */
    public function sendWelcomeMessage(string $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        if (!$user->phone) {
            return ['status' => 'error', 'message' => 'User has no phone number'];
        }

        $language = $this->whatsappService->getLanguage($user);

        if ($language === 'ar') {
            $message = "مرحباً بك في LLM Resayil! 🎉\n\nلقد تم تفعيل تجربتك المجانية لمدة 7 أيام!\n\n✅ مميزات التجربة:\n• 1000 أرصدة مجانية\n• جميع مميزات خطة Starter\n• وصول إلى النماذج الصغيرة (3-14B)\n\nاستمتع بالتجربة وقم بالترقية في أي وقت! 🚀";
        } else {
            $message = "Welcome to LLM Resayil! 🎉\n\nYour 7-day free trial has been activated!\n\n✅ Trial Benefits:\n• 1,000 free credits\n• Full Starter tier features\n• Access to small models (3-14B)\n\nEnjoy your trial and upgrade anytime! 🚀";
        }

        return $this->whatsappService->send($user->phone, $message, $language);
    }

    /**
     * Send expiry reminder on Day 6 (1 day left).
     *
     * @param string $userId User ID
     * @return array Response with status
     */
    public function sendExpiryReminder(string $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        if (!$user->phone) {
            return ['status' => 'error', 'message' => 'User has no phone number'];
        }

        $language = $this->whatsappService->getLanguage($user);
        $expiryDate = $this->billingService->getTrialExpiry($userId);

        if ($language === 'ar') {
            $message = "تنبيه: تنتهي تجربتك المجانية غداً! ⏰\n\nتبقى يوم واحد فقط في تجربتك المجانية.\n\n✨ خياراتك:\n1. ترقية تلقائية لـ Starter (15 KWD/شهر)\n2. إلغاء التجربة في أي وقت\n3. الترقية إلى Basic أو Pro\n\nلا تفوت الفرصة! 🚀";
        } else {
            $message = "Alert: Your trial expires tomorrow! ⏰\n\nOnly 1 day left in your free trial.\n\n✨ Your options:\n1. Auto-bill to Starter (15 KWD/month)\n2. Cancel anytime\n3. Upgrade to Basic or Pro\n\nDon't miss out! 🚀";
        }

        return $this->whatsappService->send($user->phone, $message, $language);
    }

    /**
     * Process trial expiry and auto-charge on Day 7.
     *
     * @param string $userId User ID
     * @return array Response with subscription status
     */
    public function processTrialExpiry(string $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $expiry = $this->billingService->getTrialExpiry($userId);

        if (!$expiry || now()->lessThan($expiry)) {
            return ['status' => 'error', 'message' => 'Trial not yet expired'];
        }

        // Auto-bill to Starter tier
        try {
            $subscription = $this->billingService->processTrialExpiry($userId);

            // Send confirmation message
            $language = $this->whatsappService->getLanguage($user);

            if ($language === 'ar') {
                $message = "تم تجديد اشتراكك تلقائياً! ✅\n\nتم تحويل 15 KWD وتم تفعيل خطة Starter لمدّة شهر.\n\n✅ عدد الأرصدة: 1000\n✅ عدد مفاتيح API: 1\n\nفي أي وقت يمكنك الترقية أو الإلغاء. 🙏";
            } else {
                $message = "Your subscription has been renewed! ✅\n\n15 KWD has been charged and Starter tier activated for 1 month.\n\n✅ Credits: 1,000\n✅ API Keys: 1\n\nYou can upgrade or cancel anytime. 🙏";
            }

            $smsResult = $this->whatsappService->send($user->phone, $message, $language);

            Log::info('Trial auto-renewal processed', [
                'user_id' => $userId,
                'subscription_id' => $subscription->id,
                'sms_result' => $smsResult,
            ]);

            return [
                'status' => 'success',
                'subscription_id' => $subscription->id,
                'tier' => 'starter',
                'sms_result' => $smsResult,
            ];
        } catch (\Exception $e) {
            Log::error('Trial auto-renewal failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);

            return ['status' => 'error', 'message' => 'Failed to process renewal: ' . $e->getMessage()];
        }
    }

    /**
     * Send upgrade confirmation message.
     *
     * @param string $userId User ID
     * @param string $newTier New tier name
     * @return array Response with status
     */
    public function sendUpgradeConfirmation(string $userId, string $newTier): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        if (!$user->phone) {
            return ['status' => 'error', 'message' => 'User has no phone number'];
        }

        $language = $this->whatsappService->getLanguage($user);
        $credits = $this->billingService->getTierCredits($newTier);

        if ($language === 'ar') {
            $message = "تمت الترقية بنجاح! 🎉\n\nلقد تم ترقيتك إلى خطة {$newTier}\n\n✅ عدد الأرصدة: " . number_format($credits) . "\n✅ مفاتيح API: " . $this->billingService->getTierApiKeys($newTier) . "\n\nاستمتع بالمميزات الإضافية! 🚀";
        } else {
            $message = "Upgrade successful! 🎉\n\nYou've been upgraded to {$newTier} tier\n\n✅ Credits: " . number_format($credits) . "\n✅ API Keys: " . $this->billingService->getTierApiKeys($newTier) . "\n\nEnjoy the additional features! 🚀";
        }

        return $this->whatsappService->send($user->phone, $message, $language);
    }

    /**
     * Send cancellation confirmation message.
     *
     * @param string $userId User ID
     * @return array Response with status
     */
    public function sendCancellationConfirmation(string $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        if (!$user->phone) {
            return ['status' => 'error', 'message' => 'User has no phone number'];
        }

        $language = $this->whatsappService->getLanguage($user);

        if ($language === 'ar') {
            $message = "تم إلغاء اشتراكك بنجاح. ✅\n\nتم إلغاء اشتراكك التلقائي وتم تحويل حسابك إلى خطة Starter المجانية.\n\nنتمنى لك يوماً سعيداً! 🙏";
        } else {
            $message = "Your subscription has been cancelled. ✅\n\nYour auto-billing has been cancelled and your account has been downgraded to the free Starter tier.\n\nWe hope you have a great day! 🙏";
        }

        return $this->whatsappService->send($user->phone, $message, $language);
    }

    /**
     * Send billing failure notification.
     *
     * @param string $userId User ID
     * @return array Response with status
     */
    public function sendBillingFailure(string $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        if (!$user->phone) {
            return ['status' => 'error', 'message' => 'User has no phone number'];
        }

        $language = $this->whatsappService->getLanguage($user);

        if ($language === 'ar') {
            $message = "فشلت عملية الدفع! ⚠️\n\nلم يتم تحويل المبلغ للاشتراك التلقائي. يُرجى تحديث بيانات الدفع أو التواصل مع الدعم.\n\nلتجنب إيقاف الخدمة، يُرجى إكمال الدفع يدوياً. 🙏";
        } else {
            $message = "Payment failed! ⚠️\n\nThe auto-billing transaction could not be processed. Please update your payment method or contact support.\n\nTo avoid service interruption, please complete payment manually. 🙏";
        }

        return $this->whatsappService->send($user->phone, $message, $language);
    }

    /**
     * Check all trials expiring today and send reminders.
     *
     * @return array Results of all notifications sent
     */
    public function checkExpiringTrials(): array
    {
        $users = User::whereNotNull('trial_started_at')
            ->where('auto_billed', false)
            ->get();

        $results = [];

        foreach ($users as $user) {
            $daysRemaining = $this->billingService->getTrialExpiry($user->id);

            if ($daysRemaining === null) {
                continue;
            }

            $expiryDate = $this->billingService->getTrialExpiry($user->id);

            if ($expiryDate && now()->format('Y-m-d') === $expiryDate->subDay()->format('Y-m-d')) {
                // Day 6: Send expiry reminder
                $results[$user->id] = $this->sendExpiryReminder($user->id);
            }
        }

        return $results;
    }

    /**
     * Process all expired trials and auto-bill users.
     *
     * @return array Results of all processed trials
     */
    public function processExpiredTrials(): array
    {
        $users = User::whereNotNull('trial_started_at')
            ->where('auto_billed', false)
            ->get();

        $results = [];

        foreach ($users as $user) {
            $expiry = $this->billingService->getTrialExpiry($user->id);

            if ($expiry && now()->greaterThanOrEqualTo($expiry)) {
                $results[$user->id] = $this->processTrialExpiry($user->id);
            }
        }

        return $results;
    }
}
