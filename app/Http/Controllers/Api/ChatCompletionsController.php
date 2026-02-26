<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OllamaProxy;
use App\Services\RateLimiter;
use App\Services\CloudFailover;
use App\Services\ModelAccessControl;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatCompletionsController extends Controller
{
    protected OllamaProxy $proxy;

    protected RateLimiter $rateLimiter;

    protected CloudFailover $cloudFailover;

    protected ModelAccessControl $modelAccess;

    protected CreditService $creditService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
        $this->rateLimiter = new RateLimiter();
        $this->cloudFailover = new CloudFailover();
        $this->modelAccess = new ModelAccessControl();
        $this->creditService = new CreditService();
    }

    /**
     * Handle chat completions request (non-streaming).
     */
    public function store(Request $request): Response
    {
        // Validate request
        $validated = $request->validate([
            'model' => 'required|string',
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:system,user,assistant',
            'messages.*.content' => 'required|string',
            'stream' => 'boolean',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens' => 'nullable|integer|min:1',
            'top_p' => 'nullable|numeric|min:0|max:1',
            'frequency_penalty' => 'nullable|numeric|min:-2|max:2',
            'presence_penalty' => 'nullable|numeric|min:-2|max:2',
        ]);

        // Get user from request (set by ApiKeyAuth middleware)
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthenticated.',
                    'code' => 401,
                ],
            ], 401);
        }

        // Check rate limit
        $tier = $user->subscription_tier ?? 'basic';
        $rateLimit = $this->rateLimiter->checkRateLimit($user->id, $tier);

        if (!$rateLimit['allowed']) {
            return response()->json([
                'error' => [
                    'message' => 'Rate limit exceeded',
                    'code' => 429,
                ],
                'retry_after' => 60 - now()->format('s'),
            ], 429, [
                'X-RateLimit-Limit' => $rateLimit['limit'],
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => now()->addMinute()->timestamp,
            ]);
        }

        // Check credits
        $estimatedCost = $this->creditService->calculateCost(100, 'local'); // Estimate 100 tokens
        $creditsCheck = $this->creditService->checkCredits($user, $estimatedCost);

        if (!$creditsCheck['hasEnough']) {
            return response()->json($this->creditService->handleCreditExhausted($user), 402);
        }

        // Get model access control
        $allowedModels = $this->modelAccess->getAllowedModels($tier);

        if (!$this->modelAccess->isModelAllowed($validated['model'], $tier)) {
            return response()->json([
                'error' => [
                    'message' => 'Model not accessible for your tier',
                    'code' => 403,
                ],
                'available_models' => $allowedModels,
            ], 403);
        }

        // Determine provider (local or cloud)
        $provider = $this->cloudFailover->shouldUseCloud($user) ? 'cloud' : 'local';
        $modelName = $provider === 'cloud' ? $this->cloudFailover->getCloudModelName($validated['model']) : $validated['model'];

        // Update rate limit counter
        $this->rateLimiter->incrementRateLimit($user->id, $tier);

        // Record cloud usage if applicable
        if ($provider === 'cloud') {
            if (!$this->cloudFailover->recordCloudRequest($user)) {
                // Fall back to local if cloud limit exceeded
                $provider = 'local';
            }
        }

        // Forward request to Ollama
        $response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);

        // Deduct credits on successful response
        if ($response->getStatusCode() === 200) {
            $content = json_decode($response->getContent(), true);
            $tokensUsed = $this->estimateTokens($content);
            $cost = $this->creditService->calculateCost($tokensUsed, $provider);

            if ($cost > 0) {
                $this->creditService->deductCredits($user, $tokensUsed, $provider, $validated['model']);
            }
        }

        return $response;
    }

    /**
     * Handle chat completions request (streaming).
     */
    public function stream(Request $request): Response
    {
        // Validate request
        $validated = $request->validate([
            'model' => 'required|string',
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:system,user,assistant',
            'messages.*.content' => 'required|string',
            'stream' => 'boolean',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens' => 'nullable|integer|min:1',
            'top_p' => 'nullable|numeric|min:0|max:1',
            'frequency_penalty' => 'nullable|numeric|min:-2|max:2',
            'presence_penalty' => 'nullable|numeric|min:-2|max:2',
        ]);

        // Get user from request
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthenticated.',
                    'code' => 401,
                ],
            ], 401);
        }

        // Check rate limit
        $tier = $user->subscription_tier ?? 'basic';
        $rateLimit = $this->rateLimiter->checkRateLimit($user->id, $tier);

        if (!$rateLimit['allowed']) {
            return response()->json([
                'error' => [
                    'message' => 'Rate limit exceeded',
                    'code' => 429,
                ],
            ], 429);
        }

        // Check credits
        $estimatedCost = $this->creditService->calculateCost(100, 'local');
        $creditsCheck = $this->creditService->checkCredits($user, $estimatedCost);

        if (!$creditsCheck['hasEnough']) {
            return response()->json($this->creditService->handleCreditExhausted($user), 402);
        }

        // Get model access control
        if (!$this->modelAccess->isModelAllowed($validated['model'], $tier)) {
            return response()->json([
                'error' => [
                    'message' => 'Model not accessible for your tier',
                    'code' => 403,
                ],
            ], 403);
        }

        // Determine provider
        $provider = $this->cloudFailover->shouldUseCloud($user) ? 'cloud' : 'local';
        $modelName = $provider === 'cloud' ? $this->cloudFailover->getCloudModelName($validated['model']) : $validated['model'];

        // Update rate limit counter
        $this->rateLimiter->incrementRateLimit($user->id, $tier);

        // Record cloud usage if applicable
        if ($provider === 'cloud') {
            if (!$this->cloudFailover->recordCloudRequest($user)) {
                $provider = 'local';
            }
        }

        // Return streaming response
        return response()->stream(function () use ($request, $provider, $modelName, $user, $validated) {
            $response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);

            // Stream the response
            echo $response->getContent();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Estimate token count from response.
     */
    protected function estimateTokens(?array $response): int
    {
        if (!$response || !isset($response['message']['content'])) {
            return 0;
        }

        // Estimate tokens from content (rough approximation: 3 chars = 1 token)
        $content = $response['message']['content'];
        $tokenEstimate = (int) (mb_strlen($content) / 3);

        // Add prompt tokens if available
        if (isset($response['prompt_eval_count'])) {
            $tokenEstimate += $response['prompt_eval_count'];
        }
        if (isset($response['eval_count'])) {
            $tokenEstimate += $response['eval_count'];
        }

        return max($tokenEstimate, 10); // Minimum 10 tokens
    }
}
