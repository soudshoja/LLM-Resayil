<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class TrialSubscriptionService
{
    /**
     * The billing service instance.
     */
    protected BillingService $billingService;

    /**
     * Create a new trial subscription service instance.
     */
    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Start a free trial for a user.
     *
     * @param string $userId User ID
     * @return User Updated user
     */
    public function startTrial(string $userId): User
    {
        return DB::transaction(function () use ($userId) {
            $user = User::findOrFail($userId);

            // Set trial fields
            $user->trial_started_at = now();
            $user->trial_credits_remaining = 1000;
            $user->auto_billed = false;
            $user->subscription_tier = 'starter';
            $user->save();

            // Grant 1000 trial credits
            $user->increment('credits', 1000);

            return $user;
        });
    }

    /**
     * Check if a user's trial is expiring and how many days remain.
     *
     * @param string $userId User ID
     * @return int|null Number of days remaining, or null if no active trial
     */
    public function checkTrialExpiry(string $userId): ?int
    {
        $user = User::find($userId);

        if (!$user || !$user->trial_started_at) {
            return null;
        }

        // Trial duration is 7 days
        $trialExpiry = $user->trial_started_at->copy()->addDays(7);
        $daysRemaining = now()->diffInDays($trialExpiry, false);

        return max(0, $daysRemaining);
    }

    /**
     * Upgrade a user from trial to a paid subscription.
     *
     * @param string $userId User ID
     * @param string $tier Subscription tier (starter, basic, pro)
     * @return Subscription Created subscription
     */
    public function upgradeToPaid(string $userId, string $tier)
    {
        return DB::transaction(function () use ($userId, $tier) {
            $user = User::findOrFail($userId);

            // Mark as auto-billed
            $user->auto_billed = true;
            $user->subscription_tier = $tier;
            $user->save();

            // Create subscription via BillingService
            return $this->billingService->subscribeUser($userId, $tier);
        });
    }

    /**
     * Cancel a user's trial subscription.
     *
     * @param string $userId User ID
     * @return bool Success status
     */
    public function cancelTrial(string $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = User::find($userId);

            if (!$user) {
                return false;
            }

            // Reset trial fields
            $user->trial_started_at = null;
            $user->trial_credits_remaining = 0;
            $user->auto_billed = false;
            $user->subscription_tier = 'basic';
            $user->save();

            return true;
        });
    }
}
