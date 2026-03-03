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

        // Resolve model name dynamically from Ollama
        $modelResolution = $this->resolveModelDynamically($validated['model']);

        if ($modelResolution === null) {
            return response()->json([
                'error' => [
                    'message' => "Model '{$validated['model']}' not found.",
                    'code' => 404,
                ],
            ], 404);
        }

        $modelId = $modelResolution['display_id'];
        $ollamaName = $modelResolution['ollama_name'];
        $isCloudModel = $modelResolution['type'] === 'cloud';
        $creditMultiplier = $modelResolution['credit_multiplier'];

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
            $cost = $this->creditService->calculateCost($tokensUsed, $provider, $modelId);

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

        // Resolve model name dynamically from Ollama
        $modelResolution = $this->resolveModelDynamically($validated['model']);

        if ($modelResolution === null) {
            return response()->json([
                'error' => [
                    'message' => "Model '{$validated['model']}' not found.",
                    'code' => 404,
                ],
            ], 404);
        }

        $modelId = $modelResolution['display_id'];
        $ollamaName = $modelResolution['ollama_name'];
        $isCloudModel = $modelResolution['type'] === 'cloud';
        $creditMultiplier = $modelResolution['credit_multiplier'];

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
                $cost = $this->creditService->calculateCost($tokensUsed, $provider, $modelId);

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
     * Resolve a client-facing model name to Ollama model info dynamically.
     *
     * Queries Ollama for the full model list and returns resolution info including
     * the ollama_name, display_id, type, and credit_multiplier.
     *
     * Falls back to config/models.php if Ollama is unreachable.
     *
     * @param string $clientModel The client-facing model name
     * @return array|null Array with keys: display_id, ollama_name, type, credit_multiplier
     */
    protected function resolveModelDynamically(string $clientModel): ?array
    {
        // Try to fetch from Ollama
        $models = $this->fetchModelsFromOllama();

        if ($models === null) {
            // Fallback to config
            $models = $this->fallbackToConfig();
        }

        // Check if client model matches a display_id
        if (isset($models[$clientModel])) {
            return [
                'display_id' => $clientModel,
                'ollama_name' => $models[$clientModel]['ollama_name'],
                'type' => $models[$clientModel]['type'],
                'credit_multiplier' => $models[$clientModel]['credit_multiplier'],
            ];
        }

        // Try to find by ollama_name (for backward compatibility)
        foreach ($models as $displayId => $modelData) {
            if (($modelData['ollama_name'] ?? '') === $clientModel) {
                return [
                    'display_id' => $displayId,
                    'ollama_name' => $modelData['ollama_name'],
                    'type' => $modelData['type'],
                    'credit_multiplier' => $modelData['credit_multiplier'],
                ];
            }
        }

        return null;
    }

    /**
     * Fetch models from Ollama GPU server and infer metadata (mirrors ModelsController).
     */
    protected function fetchModelsFromOllama(): ?array
    {
        $ollamaUrl = env('OLLAMA_GPU_URL', 'http://localhost:11434');

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($ollamaUrl . '/api/tags');

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            if (!isset($data['models']) || !is_array($data['models'])) {
                return null;
            }

            $models = [];

            foreach ($data['models'] as $model) {
                $ollamaName = $model['name'] ?? null;

                if (!$ollamaName) {
                    continue;
                }

                $displayId = $this->getDisplayId($ollamaName);
                $size = $model['size'] ?? 0;

                $metadata = [
                    'ollama_name' => $ollamaName,
                    'type' => $this->inferType($ollamaName),
                    'category' => $this->inferCategory($displayId),
                    'family' => $this->inferFamily($displayId),
                    'size' => $this->inferSize($displayId, $size),
                    'credit_multiplier' => str_ends_with($ollamaName, ':cloud') || str_contains($ollamaName, '-cloud') ? 2.0 : 1.0,
                    'name' => $this->formatDisplayName($displayId),
                ];

                // Merge with config overrides if they exist
                $configOverride = config('models.models.' . $displayId);
                if ($configOverride) {
                    $metadata = array_merge($metadata, array_filter([
                        'description' => $configOverride['description'] ?? null,
                        'context_window' => $configOverride['context_window'] ?? null,
                        'params' => $configOverride['params'] ?? null,
                        'quantization' => $configOverride['quantization'] ?? null,
                        'license' => $configOverride['license'] ?? null,
                        'credit_multiplier' => $configOverride['credit_multiplier'] ?? $metadata['credit_multiplier'],
                    ]));
                }

                $models[$displayId] = $metadata;
            }

            return $models;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the display ID (client-facing name) by stripping cloud suffixes.
     */
    protected function getDisplayId(string $ollamaName): string
    {
        if (str_ends_with($ollamaName, ':cloud')) {
            return substr($ollamaName, 0, -6);
        }

        if (str_ends_with($ollamaName, '-cloud')) {
            return substr($ollamaName, 0, -6);
        }

        return $ollamaName;
    }

    /**
     * Infer model type (local or cloud) from ollama name.
     */
    protected function inferType(string $ollamaName): string
    {
        if (str_ends_with($ollamaName, ':cloud') || str_contains($ollamaName, '-cloud')) {
            return 'cloud';
        }

        return 'local';
    }

    /**
     * Infer model category based on display_id.
     */
    protected function inferCategory(string $displayId): string
    {
        $lower = strtolower($displayId);

        if (str_contains($lower, 'embed') || str_contains($lower, 'nomic-embed') ||
            str_contains($lower, 'bge') || str_contains($lower, 'minilm') ||
            str_contains($lower, 'gte') || str_contains($lower, 'e5-') ||
            str_contains($lower, 'arctic-embed') || str_contains($lower, 'nvidia-embed')) {
            return 'embedding';
        }

        if (str_contains($lower, 'coder') || str_contains($lower, 'starcoder') ||
            str_contains($lower, 'codellama') || str_contains($lower, 'codestral') ||
            str_contains($lower, 'devstral')) {
            return 'code';
        }

        if ((str_contains($lower, 'vl') || str_contains($lower, '-vision') ||
            (str_contains($lower, 'glm-') && (str_contains($lower, 'flash') || str_contains($lower, '4.7') || str_contains($lower, '5'))))) {
            return 'vision';
        }

        if (str_contains($lower, 'kimi-k2-thinking') || str_contains($lower, 'deepseek-r') ||
            str_contains($lower, 'qwen3') || str_contains($lower, 'kimi-k2')) {
            return 'thinking';
        }

        if (str_contains($lower, 'command-r') || str_contains($lower, 'firefunction')) {
            return 'tools';
        }

        return 'chat';
    }

    /**
     * Infer model family from display_id.
     */
    protected function inferFamily(string $displayId): string
    {
        $lower = strtolower($displayId);

        $prefix = explode(':', $lower)[0];
        $prefix = explode('-', $prefix)[0];

        $familyMap = [
            'llama' => 'Llama',
            'qwen' => 'Qwen',
            'mistral' => 'Mistral',
            'mixtral' => 'Mixtral',
            'ministral' => 'Mistral',
            'codestral' => 'Mistral',
            'deepseek' => 'DeepSeek',
            'gemma' => 'Gemma',
            'phi' => 'Phi',
            'glm' => 'GLM',
            'kimi' => 'Kimi',
            'minimax' => 'MiniMax',
            'gemini' => 'Gemini',
            'cogito' => 'Cogito',
            'nemotron' => 'Nvidia',
            'rnj' => 'RNJ',
            'nomic' => 'Nomic',
            'bge' => 'BGE',
            'all-minilm' => 'All-MiniLM',
            'minilm' => 'MiniLM',
            'snowflake' => 'Snowflake',
            'e5' => 'E5',
            'gte' => 'GTE',
            'starcoder' => 'StarCoder',
            'codellama' => 'CodeLlama',
            'devstral' => 'Devstral',
            'command' => 'Cohere',
            'firefunction' => 'Fireworks',
            'gpt' => 'GPT',
            'yi' => 'Yi',
        ];

        foreach ($familyMap as $key => $family) {
            if (str_starts_with($prefix, $key)) {
                return $family;
            }
        }

        return ucfirst(explode(':', $displayId)[0]);
    }

    /**
     * Infer model size based on display_id and byte size.
     */
    protected function inferSize(string $displayId, int $bytes): string
    {
        $lower = strtolower($displayId);

        if ($bytes < 500000000) {
            if (str_contains($lower, '3b') || str_contains($lower, '7b') || str_contains($lower, '8b') ||
                str_contains($lower, 'mini') || str_contains($lower, 'nano') ||
                str_contains($lower, 'small') || str_contains($lower, 'flash')) {
                return 'small';
            }
            if (str_contains($lower, '70b') || str_contains($lower, '671b') || str_contains($lower, '405b') ||
                str_contains($lower, '397b') || str_contains($lower, '104b') || str_contains($lower, '123b')) {
                return 'large';
            }
            return 'medium';
        }

        if ($bytes < 2_000_000_000) {
            return 'small';
        }
        if ($bytes < 15_000_000_000) {
            return 'medium';
        }

        return 'large';
    }

    /**
     * Format display name from display_id.
     */
    protected function formatDisplayName(string $displayId): string
    {
        $name = str_replace([':', '-'], ' ', $displayId);
        $name = ucwords($name);
        $name = preg_replace_callback('/\b([a-z]+[0-9]+[bm])\b/i', function ($matches) {
            return strtoupper($matches[1]);
        }, $name);

        return $name;
    }

    /**
     * Fallback to config/models.php when Ollama is unreachable.
     */
    protected function fallbackToConfig(): array
    {
        $models = config('models.models');

        return array_filter($models, fn($model) => $model['is_active'] ?? true);
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
