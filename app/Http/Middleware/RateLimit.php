<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimit
{
    /**
     * Rate limiter service.
     */
    protected RateLimiter $rateLimiter;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rateLimiter = new RateLimiter();
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get user from request (should be set by ApiKeyAuth middleware)
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Get user's subscription tier
        $tier = $user->subscription_tier ?? 'basic';

        // Check rate limit
        $rateLimit = $this->rateLimiter->checkRateLimit($user->id, $tier);

        if (!$rateLimit['allowed']) {
            return response()->json([
                'error' => [
                    'message' => 'Rate limit exceeded',
                    'code' => 429,
                ],
            ], 429, [
                'X-RateLimit-Limit' => $rateLimit['limit'],
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => now()->addMinute()->timestamp,
            ]);
        }

        // Increment counter for this request
        $this->rateLimiter->incrementRateLimit($user->id, $tier);

        // Get updated limit status
        $status = $this->rateLimiter->getRateLimitStatus($user->id, $tier);

        // Add rate limit headers to response
        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', $status['limit']);
        $response->headers->set('X-RateLimit-Remaining', $status['remaining']);
        $response->headers->set('X-RateLimit-Reset', now()->addMinute()->timestamp);

        return $response;
    }
}
