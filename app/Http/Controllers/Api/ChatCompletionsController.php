<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OllamaProxy;
use App\Services\RateLimiter;
use App\Services\CloudFailover;
use App\Services\CreditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ChatCompletionsController extends Controller
{
    protected OllamaProxy $proxy;

    protected RateLimiter $rateLimiter;

    protected CloudFailover $cloudFailover;

    protected CreditService $creditService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
        $this->rateLimiter = new RateLimiter();
        $this->cloudFailover = new CloudFailover();
        $this->creditService = new CreditService();
    }

    /**
     * Handle chat completions request (non-streaming).
     */
    public function store(Request $request): Response|JsonResponse
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

        // Admin bypass: admin@llm.resayil.io bypasses rate limits, credit checks, and model access
        $isAdmin = auth()->user()->email === 'admin@llm.resayil.io';

        // Check rate limit (bypass for admin)
        if (!$isAdmin) {
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
        }

        // Check credits (bypass for admin)
        if (!$isAdmin) {
            $estimatedCost = $this->creditService->calculateCost(100, 'local'); // Estimate 100 tokens
            $creditsCheck = $this->creditService->checkCredits($user, $estimatedCost);

            if (!$creditsCheck['hasEnough']) {
                return response()->json($this->creditService->handleCreditExhausted($user), 402);
            }
        }

        // Use model registry to resolve model name and get model info
        $models = config('models.models');
        $modelId = $validated['model'];

        if (!isset($models[$modelId])) {
            // Check if the modelId is an ollama_name that needs to be resolved
            $resolvedModelId = $this->resolveModelFromRegistry($modelId, $models);
            if ($resolvedModelId === null) {
                return response()->json([
                    'error' => [
                        'message' => "Model '{$modelId}' not found.",
                        'code' => 404,
                    ],
                ], 404);
            }
            $modelId = $resolvedModelId;
        }

        $modelConfig = $models[$modelId];
        $isCloudModel = ($modelConfig['type'] ?? 'local') === 'cloud';
        $creditMultiplier = $modelConfig['credit_multiplier'] ?? 1.0;
        $ollamaName = $modelConfig['ollama_name'] ?? $modelId;

        // Determine provider (local or cloud)
        $provider = $isCloudModel ? 'cloud' : 'local';

        // Use resolved ollama name for proxy request
        $modelName = $ollamaName;

        // Update rate limit counter (bypass for admin)
        if (!$isAdmin) {
            $tier = $user->subscription_tier ?? 'basic';
            $this->rateLimiter->incrementRateLimit($user->id, $tier);
        }

        // Record cloud usage if applicable (bypass for admin)
        if ($provider === 'cloud' && !$isAdmin) {
            if (!$this->cloudFailover->recordCloudRequest($user)) {
                // Fall back to local if cloud limit exceeded
                $provider = 'local';
            }
        }

        // Forward request to Ollama
        $response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);

        // Deduct credits on successful response (bypass for admin)
        if ($response->getStatusCode() === 200 && !$isAdmin) {
            $content = json_decode($response->getContent(), true);
            $tokensUsed = $this->estimateTokens($content);
            $cost = $this->creditService->calculateCost($tokensUsed, $provider);

            if ($cost > 0) {
                $this->creditService->deductCredits($user, $tokensUsed, $provider, $modelId);
            }
        }

        return $response;
    }

    /**
     * Handle chat completions request (streaming).
     */
    public function stream(Request $request): Response|JsonResponse
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

        // Admin bypass: admin@llm.resayil.io bypasses rate limits, credit checks, and model access
        $isAdmin = auth()->user()->email === 'admin@llm.resayil.io';

        // Check rate limit (bypass for admin)
        if (!$isAdmin) {
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
        }

        // Check credits (bypass for admin)
        if (!$isAdmin) {
            $estimatedCost = $this->creditService->calculateCost(100, 'local');
            $creditsCheck = $this->creditService->checkCredits($user, $estimatedCost);

            if (!$creditsCheck['hasEnough']) {
                return response()->json($this->creditService->handleCreditExhausted($user), 402);
            }
        }

        // Use model registry to resolve model name and get model info
        $models = config('models.models');
        $modelId = $validated['model'];

        if (!isset($models[$modelId])) {
            // Check if the modelId is an ollama_name that needs to be resolved
            $resolvedModelId = $this->resolveModelFromRegistry($modelId, $models);
            if ($resolvedModelId === null) {
                return response()->json([
                    'error' => [
                        'message' => "Model '{$modelId}' not found.",
                        'code' => 404,
                    ],
                ], 404);
            }
            $modelId = $resolvedModelId;
        }

        $modelConfig = $models[$modelId];
        $isCloudModel = ($modelConfig['type'] ?? 'local') === 'cloud';
        $creditMultiplier = $modelConfig['credit_multiplier'] ?? 1.0;
        $ollamaName = $modelConfig['ollama_name'] ?? $modelId;

        // Determine provider
        $provider = $isCloudModel ? 'cloud' : 'local';

        // Use resolved ollama name for proxy request
        $modelName = $ollamaName;

        // Update rate limit counter (bypass for admin)
        if (!$isAdmin) {
            $tier = $user->subscription_tier ?? 'basic';
            $this->rateLimiter->incrementRateLimit($user->id, $tier);
        }

        // Record cloud usage if applicable (bypass for admin)
        if ($provider === 'cloud' && !$isAdmin) {
            if (!$this->cloudFailover->recordCloudRequest($user)) {
                $provider = 'local';
            }
        }

        // Return streaming response
        return response()->stream(function () use ($request, $provider, $modelName, $user, $validated, $modelId) {
            $response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);

            // Stream the response
            echo $response->getContent();

            // Deduct credits on successful response (bypass for admin)
            if ($response->getStatusCode() === 200 && !$isAdmin) {
                $content = json_decode($response->getContent(), true);
                $tokensUsed = $this->estimateTokens($content);
                // Use the provider determined before the request (cloud vs local)
                $cost = $this->creditService->calculateCost($tokensUsed, $provider);

                if ($cost > 0) {
                    $this->creditService->deductCredits($user, $tokensUsed, $provider, $modelId);
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Resolve a client-facing model name or ollama_name to the model registry ID.
     *
     * @param string $modelId The model identifier to resolve
     * @param array $models The model registry
     * @return string|null The registry model ID if found, null otherwise
     */
    protected function resolveModelFromRegistry(string $modelId, array $models): ?string
    {
        foreach ($models as $registryId => $modelConfig) {
            // Check if the model_id matches
            if ($registryId === $modelId) {
                return $registryId;
            }
            // Check if the ollama_name matches
            if (($modelConfig['ollama_name'] ?? '') === $modelId) {
                return $registryId;
            }
        }
        return null;
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
        $content = $response['message']['content'] ?? '';
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
