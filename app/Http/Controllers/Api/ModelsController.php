<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // No external dependencies needed - all data from config/models.php
    }

    /**
     * Return the OpenAI-compatible model list for the authenticated user.
     *
     * All 45 models are now exposed to all users. Tiers only affect rate limits
     * and credit costs (cloud models cost 2x credits).
     *
     * Disabled models (is_active = false) are excluded from the list.
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

        // Get all active models from the registry
        $models = config('models.models');

        // Filter to only active models
        $activeModels = array_filter($models, fn($model) => $model['is_active'] ?? true);

        $data = array_map(function (string $modelId, array $modelData) {
            return [
                'id'       => $modelId,
                'object'   => 'model',
                'created'  => 1740000000,
                'owned_by' => 'llm-resayil',
                'family'   => $modelData['family'] ?? null,
                'type'     => $modelData['type'] ?? null,
                'size'     => $modelData['size'] ?? null,
            ];
        }, array_keys($activeModels), $activeModels);

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
        $models = config('models.models');

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
     *
     * This method reads the mapping from config/models.php for consistency.
     */
    public function resolveModelName(string $clientName): string
    {
        $models = config('models.models');

        if (isset($models[$clientName])) {
            return $models[$clientName]['ollama_name'] ?? $clientName;
        }

        return $clientName;
    }
}
