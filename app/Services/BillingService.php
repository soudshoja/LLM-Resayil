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
        'starter' => 15,
        'basic' => 25,
        'pro' => 45,
    ];

    /**
     * Credits included per tier per month.
     */
    protected array $tierCredits = [
        'starter' => 1000,
        'basic' => 3000,
        'pro' => 10000,
    ];

    /**
     * API keys included per tier.
     */
    protected array $tierApiKeys = [
        'starter' => 1,
        'basic' => 1,
        'pro' => 2,
    ];

    /**
     * Credit pack pricing in KWD.
     */
    protected array $creditPacks = [
        500 => 5.0,
        1100 => 10.0,
        3000 => 25.0,
    ];

    /**
     * Credit top-up bonuses (percentage).
     */
    protected array $creditTopupBonuses = [
        500 => 0,
        1100 => 10,
        3000 => 20,
    ];

    /**
     * Model size categories.
     */
    protected array $modelSizes = [
        'small' => 'small',    // 3-14B parameters
        'medium' => 'medium',  // 20-30B parameters
        'large' => 'large',    // 70B+ parameters
    ];

    /**
     * Credit cost per 1000 tokens based on model size and type.
     * Format: [$localSmall, $localMedium, $localLarge, $cloudSmall, $cloudMedium, $cloudLarge]
     */
    protected array $creditCosts = [
        'small' => [
            'local' => 0.5,
            'cloud' => 1.0,
        ],
        'medium' => [
            'local' => 1.5,
            'cloud' => 2.5,
        ],
        'large' => [
            'local' => 3.0,
            'cloud' => 3.5,
        ],
    ];

    /**
     * Additional API key costs by tier.
     * Format: [tier => [keyNumber => cost]]
     */
    protected array $additionalApiKeyCosts = [
        'starter' => [
            2 => 5,   // 2nd key: 5 KWD
            3 => 10,  // 3rd key: 10 KWD
        ],
        'basic' => [
            2 => 3,   // 2nd key: 3 KWD
            3 => 7,   // 3rd key: 7 KWD
        ],
        'pro' => [
            3 => 2,   // 3rd key: 2 KWD
            4 => 5,   // 4th key: 5 KWD
        ],
        'enterprise' => [
            2 => 0,
            3 => 0,
            4 => 2,
            5 => 5,
        ],
    ];

    /**
     * Subscribe a user to a tier.
     *
     * @param string $userId User ID
     * @param string $tier Subscription tier (starter, basic, pro)
     * @param string|null $myfatoorahInvoiceId MyFatoorah invoice ID
     * @return Subscription Created subscription
     */
    public function subscribeUser(string $userId, string $tier, ?string $myfatoorahInvoiceId = null): Subscription
    {
        // Validate tier
        if (!in_array($tier, ['starter', 'basic', 'pro'])) {
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
     * @param int $credits Number of credits (500, 1100, 3000)
     * @param float $price Price in KWD
     * @param string|null $myfatoorahInvoiceId MyFatoorah invoice ID
     * @return TopupPurchase Created topup purchase
     */
    public function topupCredits(string $userId, int $credits, float $price, ?string $myfatoorahInvoiceId = null): TopupPurchase
    {
        // Validate credits
        if (!array_key_exists($credits, $this->creditPacks)) {
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

            // Reset user's subscription tier to starter
            $user = User::findOrFail($userId);
            $user->subscription_tier = 'starter';
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
        return $this->tierCredits[$tier] ?? 1000;
    }

    /**
     * Get the number of API keys included in a tier.
     *
     * @param string $tier Subscription tier
     * @return int Number of API keys
     */
    public function getTierApiKeys(string $tier): int
    {
        return $this->tierApiKeys[$tier] ?? 1;
    }

    /**
     * Get the price for a subscription tier.
     *
     * @param string $tier Subscription tier
     * @return float Price in KWD
     */
    public function getTierPrice(string $tier): float
    {
        return $this->tierPricing[$tier] ?? $this->tierPricing['starter'];
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
     * Get the bonus percentage for a credit top-up.
     *
     * @param int $credits Number of credits
     * @return int Bonus percentage (0, 10, or 20)
     */
    public function getCreditTopupBonus(int $credits): int
    {
        return $this->creditTopupBonuses[$credits] ?? 0;
    }

    /**
     * Get actual credits received (including bonus) for a top-up.
     *
     * @param int $credits Base credits
     * @return int Total credits including bonus
     */
    public function getActualTopupCredits(int $credits): int
    {
        $bonusPercent = $this->getCreditTopupBonus($credits);
        return (int) round($credits * (1 + $bonusPercent / 100));
    }

    /**
     * Get available credit packs with prices and bonuses.
     *
     * @return array Credit packs with prices and bonuses
     */
    public function getCreditPacks(): array
    {
        $packs = [];
        foreach ($this->creditPacks as $credits => $price) {
            $packs[] = [
                'credits' => $credits,
                'price' => $price,
                'bonus' => $this->getCreditTopupBonus($credits),
                'total_credits' => $this->getActualTopupCredits($credits),
            ];
        }
        return $packs;
    }

    /**
     * Get the credit cost per 1000 tokens for a model.
     *
     * @param string $tier Subscription tier (for reference, not used in current pricing)
     * @param string $modelSize Model size category (small, medium, large)
     * @param bool $isCloud Whether using cloud failover model
     * @return float Credit cost per 1000 tokens
     */
    public function getCreditCostPer1000(string $tier, string $modelSize, bool $isCloud): float
    {
        $size = $modelSize ?? 'small';

        if (!isset($this->creditCosts[$size])) {
            $size = 'small';
        }

        $key = $isCloud ? 'cloud' : 'local';

        return $this->creditCosts[$size][$key] ?? $this->creditCosts['small']['local'];
    }

    /**
     * Get the cost for an additional API key.
     *
     * @param string $tier Subscription tier
     * @param int $keyNumber Which key number (2nd, 3rd, 4th, etc.)
     * @return float|null Cost in KWD or null if key number not applicable
     */
    public function getAdditionalApiKeyCost(string $tier, int $keyNumber): ?float
    {
        // Admin has no key limit
        if (auth()->check() && auth()->user()->email === 'admin@llm.resayil.io') {
            return 0.0;
        }

        if ($keyNumber < 2) {
            return 0.0; // First key is free
        }

        if (!isset($this->additionalApiKeyCosts[$tier])) {
            return null;
        }

        return $this->additionalApiKeyCosts[$tier][$keyNumber] ?? null;
    }

    /**
     * Get subscription tiers with pricing, credits, and API keys.
     *
     * @return array Tiers with pricing, credits, and API keys
     */
    public function getSubscriptionTiers(): array
    {
        $tiers = [];
        foreach ($this->tierPricing as $tier => $price) {
            $tiers[] = [
                'tier' => $tier,
                'price' => $price,
                'credits' => $this->getTierCredits($tier),
                'api_keys' => $this->getTierApiKeys($tier),
            ];
        }
        return $tiers;
    }

    /**
     * Grant trial credits to a user (7-day free trial).
     *
     * @param string $userId User ID
     * @return User Updated user
     */
    public function grantTrialCredits(string $userId): User
    {
        $user = User::findOrFail($userId);

        // Grant 1000 trial credits
        $user->increment('credits', 1000);

        // Track trial credits granted
        $user->trial_credits_remaining = 1000;
        $user->save();

        return $user;
    }

    /**
     * Process trial expiry and auto-bill user.
     *
     * @param string $userId User ID
     * @return Subscription|false Created subscription or false if no trial found
     */
    public function processTrialExpiry(string $userId)
    {
        $user = User::find($userId);

        if (!$user || !$user->trial_started_at) {
            return false;
        }

        // Auto-bill to Starter tier at end of trial
        return $this->subscribeUser($userId, 'starter');
    }

    /**
     * Check if user can access a model during trial.
     *
     * Trial users can only access small models (3-14B).
     *
     * @param string $userId User ID
     * @param string $modelSize Model size category
     * @return bool Can access model
     */
    public function canAccessModelDuringTrial(string $userId, string $modelSize): bool
    {
        $user = User::find($userId);

        // If no trial, allow normal access
        if (!$user || !$user->trial_started_at) {
            return true;
        }

        // Trial users can only access small models
        return $modelSize === 'small';
    }

    /**
     * Check if trial is about to expire (within X days).
     *
     * @param string $userId User ID
     * @param int $daysBefore How many days before expiry to check
     * @return bool True if trial expires within specified days
     */
    public function isTrialExpiringSoon(string $userId, int $daysBefore = 1): bool
    {
        $user = User::find($userId);

        if (!$user || !$user->trial_started_at) {
            return false;
        }

        $trialExpiry = $user->trial_started_at->copy()->addDays(7);
        $daysRemaining = now()->diffInDays($trialExpiry, false);

        return $daysRemaining <= $daysBefore;
    }

    /**
     * Get trial expiry date for a user.
     *
     * @param string $userId User ID
     * @return \Carbon\Carbon|null Expiry date or null if no trial
     */
    public function getTrialExpiry(string $userId): ?\Carbon\Carbon
    {
        $user = User::find($userId);

        if (!$user || !$user->trial_started_at) {
            return null;
        }

        return $user->trial_started_at->copy()->addDays(7);
    }
}
