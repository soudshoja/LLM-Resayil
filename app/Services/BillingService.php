<?php

namespace App\Services;

use App\Models\TopupPurchase;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Subscription tier pricing in KWD.
     */
    protected array $tierPricing = [
        'basic' => 2.5,
        'pro' => 7.5,
        'enterprise' => 25.0,
    ];

    /**
     * Credit pack pricing in KWD.
     */
    protected array $creditPacks = [
        5000 => 5.0,
        15000 => 12.0,
        50000 => 35.0,
    ];

    /**
     * Subscribe a user to a tier.
     *
     * @param string $userId User ID
     * @param string $tier Subscription tier (basic, pro, enterprise)
     * @param string|null $myfatoorahInvoiceId MyFatoorah invoice ID
     * @return Subscription Created subscription
     */
    public function subscribeUser(string $userId, string $tier, ?string $myfatoorahInvoiceId = null): Subscription
    {
        // Validate tier
        if (!in_array($tier, ['basic', 'pro', 'enterprise'])) {
            throw new \InvalidArgumentException("Invalid subscription tier: {$tier}");
        }

        return DB::transaction(function () use ($userId, $tier, $myfatoorahInvoiceId) {
            $user = User::findOrFail($userId);

            // Calculate dates
            $startsAt = now();
            $endsAt = now()->addDays(30);

            // Cancel any existing active subscription
            Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Create new subscription
            $subscription = Subscription::create([
                'user_id' => $userId,
                'tier' => $tier,
                'status' => 'active',
                'MyFatoorah_invoice_id' => $myfatoorahInvoiceId,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'credits_purchased' => $this->getTierCredits($tier),
                'credits_used' => 0,
                'auto_renew' => false,
            ]);

            // Update user's subscription info
            $user->subscription_tier = $tier;
            $user->subscription_expiry = $endsAt;
            $user->save();

            return $subscription;
        });
    }

    /**
     * Add credits to a user's account via top-up.
     *
     * @param string $userId User ID
     * @param int $credits Number of credits (5000, 15000, 50000)
     * @param float $price Price in KWD
     * @param string|null $myfatoorahInvoiceId MyFatoorah invoice ID
     * @return TopupPurchase Created topup purchase
     */
    public function topupCredits(string $userId, int $credits, float $price, ?string $myfatoorahInvoiceId = null): TopupPurchase
    {
        // Validate credits
        if (!in_array($credits, [5000, 15000, 50000])) {
            throw new \InvalidArgumentException("Invalid credit pack: {$credits}");
        }

        return DB::transaction(function () use ($userId, $credits, $price, $myfatoorahInvoiceId) {
            $user = User::findOrFail($userId);

            // Create topup purchase record
            $topup = TopupPurchase::create([
                'user_id' => $userId,
                'credits' => $credits,
                'price' => $price,
                'status' => 'completed',
                'transaction_id' => $myfatoorahInvoiceId,
                'payment_method' => 'myfatoorah',
                'paid_at' => now(),
            ]);

            // Add credits to user's account
            $user->increment('credits', $credits);

            return $topup;
        });
    }

    /**
     * Get the active subscription for a user.
     *
     * @param string $userId User ID
     * @return Subscription|null Active subscription or null
     */
    public function getActiveSubscription(string $userId): ?Subscription
    {
        return Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Cancel a user's active subscription.
     *
     * @param string $userId User ID
     * @return bool Success status
     */
    public function cancelSubscription(string $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $subscription = Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$subscription) {
                return false;
            }

            $subscription->status = 'cancelled';
            $subscription->save();

            // Reset user's subscription tier to basic
            $user = User::findOrFail($userId);
            $user->subscription_tier = 'basic';
            $user->subscription_expiry = null;
            $user->save();

            return true;
        });
    }

    /**
     * Get the number of credits included in a tier.
     *
     * @param string $tier Subscription tier
     * @return int Number of credits
     */
    public function getTierCredits(string $tier): int
    {
        $credits = [
            'basic' => 100,
            'pro' => 500,
            'enterprise' => 2000,
        ];

        return $credits[$tier] ?? 100;
    }

    /**
     * Get the price for a subscription tier.
     *
     * @param string $tier Subscription tier
     * @return float Price in KWD
     */
    public function getTierPrice(string $tier): float
    {
        return $this->tierPricing[$tier] ?? $this->tierPricing['basic'];
    }

    /**
     * Get the price for a credit pack.
     *
     * @param int $credits Number of credits
     * @return float|null Price in KWD or null if invalid pack
     */
    public function getCreditPackPrice(int $credits): ?float
    {
        return $this->creditPacks[$credits] ?? null;
    }

    /**
     * Get available credit packs.
     *
     * @return array Credit packs with prices
     */
    public function getCreditPacks(): array
    {
        return $this->creditPacks;
    }

    /**
     * Get subscription tiers with pricing.
     *
     * @return array Tiers with pricing and credits
     */
    public function getSubscriptionTiers(): array
    {
        $tiers = [];
        foreach ($this->tierPricing as $tier => $price) {
            $tiers[] = [
                'tier' => $tier,
                'price' => $price,
                'credits' => $this->getTierCredits($tier),
            ];
        }
        return $tiers;
    }
}
