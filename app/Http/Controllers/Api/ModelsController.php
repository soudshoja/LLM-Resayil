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
     *
     * Cloud models (Enterprise only) are listed using their clean client-facing
     * names. The $cloudModelMap below translates them to the internal Ollama
     * names at request time, so clients never see the `:cloud` suffix.
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
            // cloud models (Enterprise only) — proxied to ollama.com
            'qwen3.5:397b',
            'devstral-2:123b',
            'deepseek-v3.1:671b',
            'deepseek-v3.2',
        ],
    ];

    /**
     * Map clean client-facing model names to their internal Ollama names.
     *
     * Cloud-proxy models on the Ollama server use a `:cloud` naming convention.
     * This map is used by resolveModelName() so that upstream routing is
     * transparent to API clients.
     */
    protected array $cloudModelMap = [
        'qwen3.5:397b'       => 'qwen3.5:cloud',
        'devstral-2:123b'    => 'devstral-2:123b-cloud',
        'deepseek-v3.1:671b' => 'deepseek-v3.1:671b-cloud',
        'deepseek-v3.2'      => 'deepseek-v3.2:cloud',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->proxy = new OllamaProxy();
    }

    /**
     * Return the OpenAI-compatible model list for the authenticated user's tier.
     *
     * The catalog is driven entirely by $tierModels — no Ollama round-trip is
     * needed here. Cloud models are included for Enterprise users and are
     * exposed with their clean client-facing names (no `:cloud` suffix).
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

        $tier   = $user->subscription_tier ?? 'basic';
        $models = $this->tierModels[$tier] ?? $this->tierModels['basic'];

        $data = array_map(fn(string $id) => [
            'id'       => $id,
            'object'   => 'model',
            'created'  => 1740000000,
            'owned_by' => 'llm-resayil',
        ], $models);

        return response()->json([
            'object' => 'list',
            'data'   => $data,
        ]);
    }

    /**
     * Resolve a client-facing model name to the internal Ollama model name.
     *
     * For regular models the name passes through unchanged. For cloud-proxy
     * models the clean name is translated to the `:cloud`-suffixed variant
     * that the Ollama server understands.
     */
    public function resolveModelName(string $clientName): string
    {
        return $this->cloudModelMap[$clientName] ?? $clientName;
    }
}
