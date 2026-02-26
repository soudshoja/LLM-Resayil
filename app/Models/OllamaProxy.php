<?php

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OllamaProxy
{
    protected Client $client;

    protected string $localUrl = 'http://208.110.93.90:11434';

    protected ?string $cloudUrl;

    protected ?string $cloudApiKey;

    protected string $model;

    /**
     * Constructor.
     */
    public function __construct(?string $model_name = null)
    {
        $this->client = new Client([
            'timeout' => 300,
            'connect_timeout' => 10,
        ]);

        $this->cloudUrl = env('OLLAMA_CLOUD_URL');
        $this->cloudApiKey = env('CLOUD_API_KEY');
        $this->model = $model_name ?? 'local';
    }

    /**
     * Get Ollama URL based on provider.
     */
    protected function getOllamaUrl(string $provider): string
    {
        return $provider === 'cloud' && $this->cloudUrl ? $this->cloudUrl : $this->localUrl;
    }

    /**
     * Get Authorization header for cloud provider.
     */
    protected function getAuthHeaders(string $provider): array
    {
        if ($provider === 'cloud' && $this->cloudApiKey) {
            return [
                'Authorization' => 'Bearer ' . $this->cloudApiKey,
                'Content-Type' => 'application/json',
            ];
        }

        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Proxy chat completions to Ollama.
     */
    public function proxyChatCompletions(\Illuminate\Http\Request $request, string $provider, string $model): \Symfony\Component\HttpFoundation\Response
    {
        $url = $this->getOllamaUrl($provider) . '/api/chat';

        $headers = $this->getAuthHeaders($provider);

        $body = [
            'model' => $model,
            'messages' => $request->input('messages', []),
            'stream' => $request->input('stream', false),
        ];

        if ($temperature = $request->input('temperature')) {
            $body['temperature'] = $temperature;
        }

        if ($maxTokens = $request->input('max_tokens')) {
            $body['max_tokens'] = $maxTokens;
        }

        if ($topP = $request->input('top_p')) {
            $body['top_p'] = $topP;
        }

        if ($frequencyPenalty = $request->input('frequency_penalty')) {
            $body['frequency_penalty'] = $frequencyPenalty;
        }

        if ($presencePenalty = $request->input('presence_penalty')) {
            $body['presence_penalty'] = $presencePenalty;
        }

        try {
            $startTime = now()->timestamp * 1000;

            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => $body,
                'stream' => $request->input('stream', false),
            ]);

            $endTime = now()->timestamp * 1000;
            $responseTime = $endTime - $startTime;

            if ($request->input('stream', false)) {
                return response()->stream(function () use ($response, $request, $provider, $model, $responseTime) {
                    $totalTokens = 0;
                    $content = '';

                    foreach ($response->getIterator() as $chunk) {
                        $data = json_decode($chunk, true);

                        if ($data) {
                            if (isset($data['message']['content'])) {
                                $content .= $data['message']['content'];
                            }
                            if (isset($data['message']['tool_calls'])) {
                                $content .= json_encode($data['message']['tool_calls']);
                            }
                            if (isset($data['prompt_eval_count'])) {
                                $totalTokens += $data['prompt_eval_count'];
                            }
                            if (isset($data['eval_count'])) {
                                $totalTokens += $data['eval_count'];
                            }
                        }

                        echo 'data: ' . json_encode($data) . "\n\n";
                        ob_flush();
                        flush();
                    }

                    // Log usage after stream completes
                    $this->logUsage($request->user(), $request->input('api_key_id'), $model, $totalTokens, $provider, $responseTime, 200);

                    echo 'data: {"done": true}' . "\n\n";
                    ob_flush();
                    flush();
                }, 200, [
                    'Content-Type' => 'text/event-stream',
                    'X-Accel-Buffering' => 'no',
                ]);
            }

            $responseTime = now()->timestamp * 1000 - $startTime;
            $body = json_decode($response->getBody(), true);

            // Extract token usage from response
            $totalTokens = 0;
            if (isset($body['prompt_eval_count'])) {
                $totalTokens += $body['prompt_eval_count'];
            }
            if (isset($body['eval_count'])) {
                $totalTokens += $body['eval_count'];
            }
            if (isset($body['message']['content'])) {
                $totalTokens += Str::of($body['message']['content'])->count() / 3;
            }

            $this->logUsage($request->user(), $request->input('api_key_id'), $model, $totalTokens, $provider, $responseTime, $response->getStatusCode());

            return response()->json($body, $response->getStatusCode());
        } catch (GuzzleException $e) {
            Log::error('Ollama proxy error', [
                'provider' => $provider,
                'model' => $model,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => [
                    'message' => 'Failed to connect to Ollama service',
                    'code' => 503,
                ],
            ], 503);
        }
    }

    /**
     * Proxy models request to Ollama.
     */
    public function proxyModels(): array
    {
        $url = $this->localUrl . '/api/tags';

        try {
            $response = $this->client->get($url);
            $body = json_decode($response->getBody(), true);

            return $body['models'] ?? [];
        } catch (GuzzleException $e) {
            Log::error('Ollama proxy models error', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Get filtered model list based on allowed models.
     */
    public function getModelList(array $allowedModels): array
    {
        $allModels = $this->proxyModels();
        $filtered = [];

        $restrictedModels = ['glm-4.7-flash', 'bge-m3', 'nomic-embed-text'];

        foreach ($allModels as $model) {
            $modelName = is_string($model) ? $model : ($model['name'] ?? '');

            if (in_array($modelName, $restrictedModels)) {
                continue;
            }

            if (empty($allowedModels) || in_array($modelName, $allowedModels)) {
                $filtered[] = is_string($model) ? [
                    'id' => $model,
                    'object' => 'model',
                    'created' => 0,
                ] : $model;
            }
        }

        return [
            'object' => 'list',
            'data' => $filtered,
        ];
    }

    /**
     * Check local queue depth.
     */
    public function checkLocalQueue(): int
    {
        $url = $this->localUrl . '/api/ps';

        try {
            $response = $this->client->get($url);
            $body = json_decode($response->getBody(), true);

            // Return number of processes in queue
            return isset($body['models']) ? count($body['models']) : 0;
        } catch (GuzzleException $e) {
            Log::error('Ollama queue check error', ['error' => $e->getMessage()]);

            return 0;
        }
    }

    /**
     * Log usage to database.
     */
    protected function logUsage($user, ?string $apiKeyId, string $model, int $tokensUsed, string $provider, int $responseTime, int $statusCode): void
    {
        $credits = $provider === 'cloud' ? $tokensUsed * 2 : $tokensUsed;

        \App\Models\UsageLog::create([
            'user_id' => $user->id,
            'api_key_id' => $apiKeyId,
            'model' => $model,
            'tokens_used' => $tokensUsed,
            'credits_deducted' => $credits,
            'provider' => $provider,
            'response_time_ms' => $responseTime,
            'status_code' => $statusCode,
        ]);
    }
}
