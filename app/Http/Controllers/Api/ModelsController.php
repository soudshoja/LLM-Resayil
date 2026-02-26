<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OllamaProxy;
use App\Services\ModelAccessControl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModelsController extends Controller
{
    protected OllamaProxy $proxy;

    protected ModelAccessControl $modelAccess;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
        $this->modelAccess = new ModelAccessControl();
    }

    /**
     * Handle models list request.
     */
    public function index(Request $request): Response
    {
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

        // Get user's subscription tier
        $tier = $user->subscription_tier ?? 'basic';

        // Get allowed models for tier
        $allowedModels = $this->modelAccess->getAllowedModels($tier);

        // Fetch all models from Ollama
        $allModels = $this->proxy->proxyModels();

        // Filter to allowed models
        $filteredModels = $this->modelAccess->filterModels($allModels, $tier);

        // Format response in OpenAI-compatible format
        $formattedModels = array_map(function ($model) {
            if (is_string($model)) {
                return [
                    'id' => $model,
                    'object' => 'model',
                    'created' => 0,
                ];
            }

            return [
                'id' => $model['id'] ?? $model['name'] ?? 'unknown',
                'object' => 'model',
                'created' => $model['created'] ?? 0,
            ];
        }, $filteredModels);

        return response()->json([
            'object' => 'list',
            'data' => $formattedModels,
        ]);
    }
}
