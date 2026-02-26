<?php

namespace App\Services;

use App\Models\UsageLog;
use Illuminate\Support\Facades\DB;

class CreditService
{
    /**
     * Credit cost multipliers.
     */
    protected array $costMultipliers = [
        'local' => 1,
        'cloud' => 2,
    ];

    /**
     * Check if user has sufficient credits.
     */
    public function checkCredits($user, int $estimatedCost): array
    {
        $currentCredits = (int) $user->credits;

        return [
            'hasEnough' => $currentCredits >= $estimatedCost,
            'current' => $currentCredits,
            'required' => $estimatedCost,
            'remaining' => $currentCredits - $estimatedCost,
        ];
    }

    /**
     * Deduct credits for a request.
     */
    public function deductCredits($user, int $tokensUsed, string $provider, string $model): array
    {
        $costMultiplier = $this->costMultipliers[$provider] ?? 1;
        $creditsDeducted = $tokensUsed * $costMultiplier;

        if ($user->credits < $creditsDeducted) {
            return [
                'success' => false,
                'deducted' => 0,
                'error' => 'Insufficient credits',
                'required' => $creditsDeducted,
                'current' => $user->credits,
            ];
        }

        DB::beginTransaction();

        try {
            // Update user credits
            $user->decrement('credits', $creditsDeducted);

            // Create usage log entry
            $usageLog = UsageLog::create([
                'user_id' => $user->id,
                'model' => $model,
                'tokens_used' => $tokensUsed,
                'credits_deducted' => $creditsDeducted,
                'provider' => $provider,
            ]);

            DB::commit();

            return [
                'success' => true,
                'deducted' => $creditsDeducted,
                'remaining' => $user->credits,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'deducted' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Log usage to database.
     */
    public function logUsage($user, ?string $apiKeyId, string $model, int $tokensUsed, int $credits, string $provider, int $responseTime = 0, int $statusCode = 200): UsageLog
    {
        return UsageLog::create([
            'user_id' => $user->id,
            'api_key_id' => $apiKeyId,
            'model' => $model,
            'tokens_used' => $tokensUsed,
            'credits_deducted' => $credits,
            'provider' => $provider,
            'response_time_ms' => $responseTime,
            'status_code' => $statusCode,
        ]);
    }

    /**
     * Handle credit exhaustion response.
     */
    public function handleCreditExhausted($user): array
    {
        return [
            'error' => [
                'message' => 'Insufficient credits',
                'code' => 402,
            ],
            'top_up_url' => '/dashboard/topup',
            'current_credits' => $user->credits,
        ];
    }

    /**
     * Calculate cost for a request.
     */
    public function calculateCost(int $tokensUsed, string $provider): int
    {
        $costMultiplier = $this->costMultipliers[$provider] ?? 1;

        return $tokensUsed * $costMultiplier;
    }

    /**
     * Add credits to user account.
     */
    public function addCredits($user, int $amount): bool
    {
        $user->increment('credits', $amount);

        return true;
    }

    /**
     * Get user's credit status.
     */
    public function getCreditStatus($user): array
    {
        $tier = $user->subscription_tier ?? 'basic';
        $tierLimits = [
            'basic' => 1000,
            'pro' => 5000,
            'enterprise' => 20000,
        ];
        $tierLimit = $tierLimits[$tier] ?? 1000;
        $used = $tierLimit - (int) $user->credits;
        $percentage = $tierLimit > 0 ? round(($used / $tierLimit) * 100) : 0;

        return [
            'tier' => $tier,
            'tier_limit' => $tierLimit,
            'current_credits' => (int) $user->credits,
            'used' => $used,
            'percentage_used' => $percentage,
            'low_warning' => $percentage >= 80,
            'exhausted' => (int) $user->credits <= 0,
        ];
    }

    /**
     * Check if credits are low (20% or less remaining).
     */
    public function areCreditsLow($user): bool
    {
        $status = $this->getCreditStatus($user);

        return $status['low_warning'] || $status['exhausted'];
    }
}
