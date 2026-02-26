<?php

namespace App\Services;

use App\Models\OllamaProxy;
use App\Models\CloudBudget;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CloudFailover
{
    protected OllamaProxy $proxy;

    protected int $queueThreshold = 3;

    protected int $dailyLimit = 500;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
    }

    /**
     * Check if cloud failover should be used.
     */
    public function shouldUseCloud($user): bool
    {
        if (!$this->isCloudAvailable()) {
            return false;
        }

        if (!$this->checkDailyLimit($user)['allowed']) {
            return false;
        }

        $queueDepth = $this->proxy->checkLocalQueue();

        return $queueDepth > $this->queueThreshold;
    }

    /**
     * Get cloud model name for local model.
     */
    public function getCloudModelName(string $model): string
    {
        // Cloud models are identified by :cloud suffix
        if (str_contains($model, ':cloud')) {
            return $model;
        }

        return $model . ':cloud';
    }

    /**
     * Check if cloud model is available for user.
     */
    public function isCloudAvailable(): bool
    {
        $cloudUrl = env('OLLAMA_CLOUD_URL');
        $cloudApiKey = env('CLOUD_API_KEY');

        return !empty($cloudUrl) && !empty($cloudApiKey);
    }

    /**
     * Check daily cloud budget limit.
     */
    public function checkDailyLimit($user): array
    {
        $today = now()->toDateString();

        $budget = CloudBudget::firstOrCreate(
            ['date' => $today],
            ['requests_today' => 0, 'daily_limit' => $this->dailyLimit]
        );

        // Reset if past midnight
        if ($budget->last_reset_at && $budget->last_reset_at->isToday() === false) {
            $budget->requests_today = 0;
            $budget->last_reset_at = now();
        }

        $used = $budget->requests_today;
        $limit = $budget->daily_limit;

        return [
            'allowed' => $used < $limit,
            'used' => $used,
            'limit' => $limit,
            'remaining' => $limit - $used,
        ];
    }

    /**
     * Record a cloud request.
     */
    public function recordCloudRequest($user): bool
    {
        $today = now()->toDateString();

        $budget = CloudBudget::firstOrCreate(
            ['date' => $today],
            ['requests_today' => 0, 'daily_limit' => $this->dailyLimit]
        );

        if ($budget->requests_today >= $budget->daily_limit) {
            return false;
        }

        $budget->increment('requests_today');

        // Update last reset time if needed
        if ($budget->last_reset_at && $budget->last_reset_at->isToday() === false) {
            $budget->update(['last_reset_at' => now()]);
        }

        return true;
    }

    /**
     * Get list of cloud model names.
     */
    public function getCloudModels(): array
    {
        // Return models with :cloud suffix
        return [
            'qwen3.5:cloud',
            'deepseek-v3.2:cloud',
            'gpt-oss:20b',
        ];
    }

    /**
     * Check if model is a cloud model.
     */
    public function isCloudModel(string $model): bool
    {
        return str_contains($model, ':cloud');
    }

    /**
     * Calculate credit cost based on provider.
     */
    public function getCreditCost(string $model, int $tokens): int
    {
        return $this->isCloudModel($model) ? $tokens * 2 : $tokens;
    }

    /**
     * Get current daily cloud usage.
     */
    public function getDailyUsage(): int
    {
        $today = now()->toDateString();

        $budget = CloudBudget::where('date', $today)->first();

        return $budget ? $budget->requests_today : 0;
    }

    /**
     * Get cloud usage percentage.
     */
    public function getUsagePercentage(): int
    {
        $usage = $this->getDailyUsage();
        $limit = $this->dailyLimit;

        return $limit > 0 ? round(($usage / $limit) * 100) : 0;
    }
}
