<?php

namespace App\Services;

class ModelAccessControl
{
    /**
     * Restricted models that are never exposed via API.
     */
    protected array $restrictedModels = [
        'glm-4.7-flash',
        'bge-m3',
        'nomic-embed-text',
    ];

    /**
     * Model definitions by tier.
     */
    protected array $tierModels = [
        'basic' => [
            'llama3.2:3b',
            'smollm2:135m',
            'qwen2.5-coder:14b',
            'mistral-small3.2:24b',
        ],
        'pro' => [
            'llama3.2:3b',
            'smollm2:135m',
            'qwen2.5-coder:14b',
            'mistral-small3.2:24b',
            'qwen3-30b-40k',
            'Qwen3-VL-32B',
            'qwen3.5:cloud',
            'deepseek-v3.2:cloud',
            'gpt-oss:20b',
        ],
        'enterprise' => [
            'llama3.2:3b',
            'smollm2:135m',
            'qwen2.5-coder:14b',
            'mistral-small3.2:24b',
            'qwen3-30b-40k',
            'Qwen3-VL-32B',
            'qwen3.5:cloud',
            'deepseek-v3.2:cloud',
            'gpt-oss:20b',
        ],
    ];

    /**
     * Tier priority levels.
     */
    protected array $tierPriority = [
        'basic' => 1,
        'pro' => 2,
        'enterprise' => 3,
    ];

    /**
     * Get allowed models for tier.
     */
    public function getAllowedModels(string $tier): array
    {
        $tier = strtolower($tier);

        return $this->tierModels[$tier] ?? $this->tierModels['basic'];
    }

    /**
     * Check if model is allowed for tier.
     */
    public function isModelAllowed(string $model, string $tier): bool
    {
        // Always block restricted models
        if (in_array($model, $this->restrictedModels)) {
            return false;
        }

        // Enterprise gets all models
        if (strtolower($tier) === 'enterprise') {
            return true;
        }

        $allowedModels = $this->getAllowedModels($tier);

        return in_array($model, $allowedModels);
    }

    /**
     * Get tier priority level.
     */
    public function getTierPriority(string $tier): int
    {
        $tier = strtolower($tier);

        return $this->tierPriority[$tier] ?? $this->tierPriority['basic'];
    }

    /**
     * Filter models by tier access.
     */
    public function filterModels(array $models, string $tier): array
    {
        $allowedModels = $this->getAllowedModels($tier);

        return array_filter($models, function ($model) use ($allowedModels) {
            $modelName = is_string($model) ? $model : ($model['id'] ?? $model['name'] ?? '');

            return $this->isModelAllowed($modelName, $tier);
        });
    }

    /**
     * Check if tier includes cloud models.
     */
    public function tierHasCloudAccess(string $tier): bool
    {
        $tier = strtolower($tier);

        return $tier === 'pro' || $tier === 'enterprise';
    }

    /**
     * Get restricted models list.
     */
    public function getRestrictedModels(): array
    {
        return $this->restrictedModels;
    }

    /**
     * Check if model is restricted.
     */
    public function isRestrictedModel(string $model): bool
    {
        return in_array($model, $this->restrictedModels);
    }

    /**
     * Get all available models across all tiers.
     */
    public function getAllAvailableModels(): array
    {
        $allModels = [];

        foreach ($this->tierModels as $models) {
            $allModels = array_merge($allModels, $models);
        }

        return array_values(array_unique($allModels));
    }
}
