<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModelsController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // No external dependencies needed
    }

    /**
     * Return the OpenAI-compatible model list for the authenticated user.
     *
     * Queries the Ollama GPU server directly for its model list, infers metadata
     * from model names, and merges with overrides from config/models.php.
     * Falls back to config on error.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthenticated.',
                    'code'    => 401,
                ],
            ], 401);
        }

        $models = $this->fetchModelsFromOllama();

        if ($models === null) {
            // Fallback to config on error
            $models = $this->fallbackToConfig();
        }

        $data = array_map(function (string $modelId, array $modelData) {
            return [
                'id'       => $modelId,
                'object'   => 'model',
                'created'  => 1740000000,
                'owned_by' => 'llm-resayil',
                'family'   => $modelData['family'] ?? null,
                'type'     => $modelData['type'] ?? null,
                'category' => $modelData['category'] ?? 'chat',
                'size'     => $modelData['size'] ?? null,
            ];
        }, array_keys($models), $models);

        return response()->json([
            'object' => 'list',
            'data'   => $data,
        ]);
    }

    /**
     * Return detailed information about a specific model.
     *
     * This endpoint provides comprehensive metadata about a model including
     * context window, parameters, quantization, and pricing.
     */
    public function show(Request $request, string $modelId): JsonResponse
    {
        $models = $this->fetchModelsFromOllama();

        if ($models === null) {
            $models = $this->fallbackToConfig();
        }

        if (!isset($models[$modelId])) {
            return response()->json([
                'error' => [
                    'message' => "Model '{$modelId}' not found.",
                    'code'    => 404,
                ],
            ], 404);
        }

        $model = $models[$modelId];

        return response()->json([
            'id'       => $modelId,
            'object'   => 'model',
            'created'  => 1740000000,
            'owned_by' => 'llm-resayil',
            'name'     => $model['name'] ?? $modelId,
            'family'   => $model['family'] ?? null,
            'type'     => $model['type'] ?? null,
            'category' => $model['category'] ?? 'chat',
            'size'     => $model['size'] ?? null,
            'context_window' => $model['context_window'] ?? null,
            'description' => $model['description'] ?? null,
            'license'  => $model['license'] ?? null,
            'params'   => $model['params'] ?? null,
            'quantization' => $model['quantization'] ?? null,
            'credit_multiplier' => $model['credit_multiplier'] ?? 1.0,
        ]);
    }

    /**
     * Resolve a client-facing model name to the internal Ollama model name.
     *
     * For regular models the name passes through unchanged. For cloud-proxy
     * models the clean name is translated to the `:cloud`-suffixed variant
     * that the Ollama server understands.
     */
    public function resolveModelName(string $clientName): ?string
    {
        $models = $this->fetchModelsFromOllama();

        if ($models === null) {
            $models = $this->fallbackToConfig();
        }

        if (isset($models[$clientName])) {
            return $models[$clientName]['ollama_name'] ?? $clientName;
        }

        return null;
    }

    /**
     * Fetch models from Ollama GPU server and infer metadata.
     *
     * Returns array keyed by display_id (client-facing name) with inferred metadata.
     * Returns null on error.
     */
    protected function fetchModelsFromOllama(): ?array
    {
        $ollamaUrl = env('OLLAMA_GPU_URL', 'http://localhost:11434');

        try {
            $response = Http::timeout(5)->get($ollamaUrl . '/api/tags');

            if (!$response->successful()) {
                Log::warning('Ollama /api/tags returned non-200 status', [
                    'status' => $response->status(),
                    'url' => $ollamaUrl . '/api/tags',
                ]);
                return null;
            }

            $data = $response->json();

            if (!isset($data['models']) || !is_array($data['models'])) {
                Log::warning('Ollama /api/tags response missing models array', [
                    'response' => $data,
                ]);
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
            Log::error('Error fetching models from Ollama', [
                'error' => $e->getMessage(),
                'url' => $ollamaUrl . '/api/tags',
            ]);
            return null;
        }
    }

    /**
     * Get the display ID (client-facing name) by stripping cloud suffixes.
     */
    protected function getDisplayId(string $ollamaName): string
    {
        // Strip :cloud suffix
        if (str_ends_with($ollamaName, ':cloud')) {
            return substr($ollamaName, 0, -6);
        }

        // Strip -cloud suffix
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

        // Embedding models
        if (str_contains($lower, 'embed') || str_contains($lower, 'nomic-embed') ||
            str_contains($lower, 'bge') || str_contains($lower, 'minilm') ||
            str_contains($lower, 'gte') || str_contains($lower, 'e5-') ||
            str_contains($lower, 'arctic-embed') || str_contains($lower, 'nvidia-embed')) {
            return 'embedding';
        }

        // Code models
        if (str_contains($lower, 'coder') || str_contains($lower, 'starcoder') ||
            str_contains($lower, 'codellama') || str_contains($lower, 'codestral') ||
            str_contains($lower, 'devstral')) {
            return 'code';
        }

        // Vision models
        if ((str_contains($lower, 'vl') || str_contains($lower, '-vision') ||
            (str_contains($lower, 'glm-') && (str_contains($lower, 'flash') || str_contains($lower, '4.7') || str_contains($lower, '5'))))) {
            return 'vision';
        }

        // Thinking models
        if (str_contains($lower, 'kimi-k2-thinking') || str_contains($lower, 'deepseek-r') ||
            str_contains($lower, 'qwen3') || str_contains($lower, 'kimi-k2')) {
            return 'thinking';
        }

        // Tools models
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

        // Handle HuggingFace-style paths like "hf.co/Org/Model" or "user/Model"
        // Use only the last path segment for inference
        if (str_contains($lower, '/')) {
            $segments = explode('/', $lower);
            $lower = end($segments);
        }

        // Extract the first part before : or -
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

        // Fallback: capitalize first word of the last path segment
        return ucfirst(explode(':', $lower)[0]);
    }

    /**
     * Infer model size based on display_id and byte size.
     */
    protected function inferSize(string $displayId, int $bytes): string
    {
        $lower = strtolower($displayId);

        // Cloud proxies are tiny (~400 bytes), infer from name
        if ($bytes < 500000000) { // < ~500MB
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

        // Local models: < 2GB → small, 2GB–15GB → medium, > 15GB → large
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
     * E.g., "llama3.2:3b" → "Llama 3.2 3B"
     */
    protected function formatDisplayName(string $displayId): string
    {
        // Remove version tags and replace separators with spaces
        $name = str_replace([':', '-'], ' ', $displayId);

        // Capitalize each word
        $name = ucwords($name);

        // Uppercase specific tokens like B, M, K
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
}
