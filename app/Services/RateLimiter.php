<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RateLimiter
{
    /**
     * Rate limits per tier (requests per minute).
     */
    protected array $rateLimits = [
        'basic' => 10,
        'pro' => 30,
        'enterprise' => 60,
    ];

    /**
     * Get rate limit for tier.
     */
    public function getRateLimit(string $tier): int
    {
        return $this->rateLimits[$tier] ?? $this->rateLimits['basic'];
    }

    /**
     * Check rate limit for user.
     */
    public function checkRateLimit(string $userId, string $tier): array
    {
        $limit = $this->getRateLimit($tier);
        $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');

        try {
            $current = (int) Cache::get($key, 0);

            if ($current >= $limit) {
                return [
                    'allowed' => false,
                    'remaining' => 0,
                    'limit' => $limit,
                ];
            }

            return [
                'allowed' => true,
                'remaining' => $limit - $current,
                'limit' => $limit,
            ];
        } catch (\Exception $e) {
            Log::error('RateLimiter check error', ['error' => $e->getMessage()]);

            // Allow request if cache fails (fail open)
            return [
                'allowed' => true,
                'remaining' => $limit,
                'limit' => $limit,
            ];
        }
    }

    /**
     * Increment rate limit counter.
     */
    public function incrementRateLimit(string $userId, string $tier): bool
    {
        $limit = $this->getRateLimit($tier);
        $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');

        try {
            // TTL: remaining seconds in the current minute
            $ttl = 60 - (int) now()->format('s');
            if ($ttl <= 0) {
                $ttl = 60;
            }

            $current = (int) Cache::get($key, 0);

            if ($current >= $limit) {
                return false;
            }

            Cache::put($key, $current + 1, $ttl);

            return true;
        } catch (\Exception $e) {
            Log::error('RateLimiter increment error', ['error' => $e->getMessage()]);

            // Allow request if cache fails (fail open)
            return true;
        }
    }

    /**
     * Get rate limit status for user.
     */
    public function getRateLimitStatus(string $userId, string $tier): array
    {
        $limit = $this->getRateLimit($tier);
        $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');

        try {
            $current = (int) Cache::get($key, 0);

            return [
                'limit' => $limit,
                'current' => $current,
                'remaining' => max(0, $limit - $current),
                'ttl' => 60 - (int) now()->format('s'),
            ];
        } catch (\Exception $e) {
            Log::error('RateLimiter status error', ['error' => $e->getMessage()]);

            return [
                'limit' => $limit,
                'current' => 0,
                'remaining' => $limit,
                'ttl' => 60,
            ];
        }
    }

    /**
     * Reset rate limit for user (useful for admin actions).
     */
    public function resetRateLimit(string $userId, string $tier): bool
    {
        $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');

        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::error('RateLimiter reset error', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
