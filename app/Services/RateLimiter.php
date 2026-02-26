<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RateLimiter
{
    /**
     * Rate limits per tier.
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
            $current = Redis::get($key);

            if ($current === false) {
                return [
                    'allowed' => true,
                    'remaining' => $limit,
                    'limit' => $limit,
                ];
            }

            $current = (int) $current;

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

            // Allow request if Redis fails (fail open)
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
        $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');
        $limit = $this->getRateLimit($tier);

        try {
            $current = Redis::get($key);

            if ($current === false) {
                Redis::setex($key, 60 - now()->format('s'), 1);
                return true;
            }

            $current = (int) $current;

            if ($current >= $limit) {
                return false;
            }

            Redis::incr($key);

            // Extend expiry if we're still within the window
            Redis::expire($key, 60 - now()->format('s'));

            return true;
        } catch (\Exception $e) {
            Log::error('RateLimiter increment error', ['error' => $e->getMessage()]);

            // Allow request if Redis fails (fail open)
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
            $current = Redis::get($key);
            $ttl = Redis::ttl($key);

            return [
                'limit' => $limit,
                'current' => $current ? (int) $current : 0,
                'remaining' => $current ? max(0, $limit - (int) $current) : $limit,
                'ttl' => $ttl,
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
            return (bool) Redis::del($key);
        } catch (\Exception $e) {
            Log::error('RateLimiter reset error', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
