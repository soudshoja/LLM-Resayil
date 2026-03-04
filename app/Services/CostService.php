<?php

namespace App\Services;

use App\Models\UsageLog;

class CostService
{
    const CREDIT_TO_USD      = 0.001;
    const USD_TO_KWD         = 0.306;
    const BLENDED_INPUT_RATIO  = 0.70;
    const BLENDED_OUTPUT_RATIO = 0.30;

    /**
     * Return public model pricing rates (USD per 1M tokens).
     * Exactly 5 entries.
     */
    public function getPublicModelRates(): array
    {
        return [
            'gpt-3.5-turbo' => ['name' => 'GPT-3.5 Turbo',   'input' => 0.50,  'output' => 1.50],
            'gpt-4'         => ['name' => 'GPT-4',            'input' => 3.00,  'output' => 6.00],
            'gpt-4o'        => ['name' => 'GPT-4o',           'input' => 5.00,  'output' => 15.00],
            'claude-3.5'    => ['name' => 'Claude 3.5',       'input' => 3.00,  'output' => 15.00],
            'gemini-1.5'    => ['name' => 'Gemini 1.5 Pro',   'input' => 3.50,  'output' => 10.50],
        ];
    }

    /**
     * Calculate what a given number of prompt + completion tokens would cost
     * on a specific public model (USD).
     *
     * Example: calculatePublicCost(1000, 500, 'gpt-4o')
     *   = (1000/1_000_000 * 5.00) + (500/1_000_000 * 15.00)
     *   = 0.005 + 0.0075
     *   = 0.0125
     */
    public function calculatePublicCost(int $promptTokens, int $completionTokens, string $publicModel): float
    {
        $rates = $this->getPublicModelRates();

        if (!isset($rates[$publicModel])) {
            return 0.0;
        }

        $rate       = $rates[$publicModel];
        $inputCost  = ($promptTokens     / 1_000_000) * $rate['input'];
        $outputCost = ($completionTokens / 1_000_000) * $rate['output'];

        return round($inputCost + $outputCost, 6);
    }

    /**
     * Calculate public model cost using a blended 70/30 split when only
     * total token count is available (e.g. streaming rows with NULL split).
     */
    public function calculatePublicCostBlended(int $totalTokens, string $publicModel): float
    {
        $promptTokens     = (int) round($totalTokens * self::BLENDED_INPUT_RATIO);
        $completionTokens = (int) round($totalTokens * self::BLENDED_OUTPUT_RATIO);

        return $this->calculatePublicCost($promptTokens, $completionTokens, $publicModel);
    }

    /**
     * Convert credits to USD.
     */
    public function creditsToUsd(int $credits): float
    {
        return round($credits * self::CREDIT_TO_USD, 6);
    }

    /**
     * Convert USD to KWD.
     */
    public function usdToKwd(float $usd): float
    {
        return round($usd * self::USD_TO_KWD, 4);
    }

    /**
     * Calculate monthly savings for a user compared to all public models.
     * Returns structured array with benchmark (GPT-4o) prominently included.
     */
    public function getMonthlySavings($user): array
    {
        $logs = UsageLog::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->get(['credits_deducted', 'tokens_used', 'prompt_tokens', 'completion_tokens']);

        if ($logs->isEmpty()) {
            return $this->emptySavingsResult();
        }

        $rates          = $this->getPublicModelRates();
        $ourCostUsd     = 0.0;
        $publicTotals   = array_fill_keys(array_keys($rates), 0.0);
        $hasAnyEstimate = false;

        foreach ($logs as $log) {
            $ourCostUsd += $this->creditsToUsd((int) $log->credits_deducted);

            $isBlended = is_null($log->prompt_tokens) || is_null($log->completion_tokens);
            if ($isBlended) {
                $hasAnyEstimate = true;
            }

            foreach ($rates as $key => $rate) {
                if ($isBlended) {
                    $publicTotals[$key] += $this->calculatePublicCostBlended((int) $log->tokens_used, $key);
                } else {
                    $publicTotals[$key] += $this->calculatePublicCost(
                        (int) $log->prompt_tokens,
                        (int) $log->completion_tokens,
                        $key
                    );
                }
            }
        }

        $comparisons = [];
        $benchmark   = null;

        foreach ($publicTotals as $key => $publicCostUsd) {
            $savingsUsd = max(0.0, $publicCostUsd - $ourCostUsd);

            $entry = [
                'model'           => $key,
                'name'            => $rates[$key]['name'],
                'public_cost_usd' => round($publicCostUsd, 4),
                'public_cost_kwd' => $this->usdToKwd($publicCostUsd),
                'savings_usd'     => round($savingsUsd, 4),
                'savings_kwd'     => $this->usdToKwd($savingsUsd),
                'is_estimate'     => $hasAnyEstimate,
            ];

            $comparisons[] = $entry;

            if ($key === 'gpt-4o') {
                $benchmark = $entry;
            }
        }

        return [
            'our_cost_usd' => round($ourCostUsd, 4),
            'our_cost_kwd' => $this->usdToKwd($ourCostUsd),
            'comparisons'  => $comparisons,
            'benchmark'    => $benchmark,
            'total_calls'  => $logs->count(),
        ];
    }

    /**
     * Return the most recent API calls for a user, each annotated with
     * the equivalent GPT-4o cost for comparison.
     */
    public function getRecentCallComparisons($user, int $limit = 10): array
    {
        $logs = UsageLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();

        $result = [];

        foreach ($logs as $log) {
            $isBlended = is_null($log->prompt_tokens) || is_null($log->completion_tokens);

            if ($isBlended) {
                $gpt4oCostUsd = $this->calculatePublicCostBlended((int) $log->tokens_used, 'gpt-4o');
            } else {
                $gpt4oCostUsd = $this->calculatePublicCost(
                    (int) $log->prompt_tokens,
                    (int) $log->completion_tokens,
                    'gpt-4o'
                );
            }

            $multiplier   = (float) (config('models.models.' . $log->model . '.credit_multiplier')
                ?? ($log->provider === 'cloud' ? 2.0 : 1.0));
            $ourCostUsd   = round(($log->tokens_used * $multiplier / 1000) * 0.001, 6);

            $result[] = [
                'log'            => $log,
                'our_cost_usd'   => $ourCostUsd,
                'gpt4o_cost_usd' => round($gpt4oCostUsd, 6),
                'is_estimate'    => $isBlended,
            ];
        }

        return $result;
    }

    /**
     * Build an empty savings result when the user has no logs this month.
     * All numeric fields are 0.0 and is_estimate is false.
     */
    private function emptySavingsResult(): array
    {
        $rates       = $this->getPublicModelRates();
        $comparisons = [];
        $benchmark   = null;

        foreach ($rates as $key => $rate) {
            $entry = [
                'model'           => $key,
                'name'            => $rate['name'],
                'public_cost_usd' => 0.0,
                'public_cost_kwd' => 0.0,
                'savings_usd'     => 0.0,
                'savings_kwd'     => 0.0,
                'is_estimate'     => false,
            ];

            $comparisons[] = $entry;

            if ($key === 'gpt-4o') {
                $benchmark = $entry;
            }
        }

        return [
            'our_cost_usd' => 0.0,
            'our_cost_kwd' => 0.0,
            'comparisons'  => $comparisons,
            'benchmark'    => $benchmark,
            'total_calls'  => 0,
        ];
    }
}
