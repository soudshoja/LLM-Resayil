<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OllamaProxy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    protected OllamaProxy $proxy;

    /**
     * Models available per subscription tier.
     */
    protected array $tierModels = [
        'basic' => [
            'llama3.2:3b',
            'smollm2:135m',
        ],
        'pro' => [
            'llama3.2:3b',
            'smollm2:135m',
            'qwen2.5-coder:14b',
            'mistral-small3.2:24b-instruct-2506-q4_K_M',
        ],
        'enterprise' => [
            'llama3.2:3b',
            'smollm2:135m',
            'qwen2.5-coder:14b',
            'mistral-small3.2:24b-instruct-2506-q4_K_M',
            'glm-4.7-flash:latest',
            'qwen3-30b-40k:latest',
            'gpt-oss:20b',
            'hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M',
        ],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
    }

    /**
     * Handle models list request.
     */
    public function index(Request $request): JsonResponse
    {
        // User is resolved by ApiKeyAuth middleware via setUserResolver
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthenticated.',
                    'code'    => 401,
                ],
            ], 401);
        }

        // Determine subscription tier; fall back to 'basic' when not set
        $tier = $user->subscription_tier ?? 'basic';

        // Resolve the allowed model list for this tier (fall back to basic if tier is unknown)
        $allowedModels = $this->tierModels[$tier] ?? $this->tierModels['basic'];

        // Fetch all models from Ollama
        $allModels = $this->proxy->proxyModels();

        // Normalise every entry to a plain model-id string
        $normalised = array_map(function ($model) {
            if (is_string($model)) {
                return $model;
            }

            return $model['id'] ?? $model['name'] ?? null;
        }, $allModels);

        // Remove nulls
        $normalised = array_filter($normalised);

        // 1. Filter out cloud-routing models (internal implementation detail)
        $normalised = array_filter($normalised, function (string $id): bool {
            return !str_ends_with($id, ':cloud');
        });

        // 2. Filter to models the user's tier is allowed to access
        $normalised = array_filter($normalised, function (string $id) use ($allowedModels): bool {
            return in_array($id, $allowedModels, true);
        });

        // Build OpenAI-compatible model objects
        $data = array_values(array_map(function (string $id): array {
            return [
                'id'       => $id,
                'object'   => 'model',
                'created'  => 1740000000,
                'owned_by' => 'llm-resayil',
            ];
        }, $normalised));

        return response()->json([
            'object' => 'list',
            'data'   => $data,
        ]);
    }
}
