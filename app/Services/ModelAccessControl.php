<?php

namespace App\Services;

use App\Models\User;

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
     * Check if a user can access the given model.
     *
     * Rules:
     *  - Admin (admin@llm.resayil.io) always returns true.
     *  - Restricted models are always blocked.
     *  - All active models are available to starter/basic/pro users.
     *  - TRIAL users (trial_started_at set, subscription_tier = 'starter',
     *    myfatoorah_subscription_id is null) may only use 'small' size models.
     */
    public function canAccessModel(User $user, string $model): bool
    {
        // Admin bypasses all restrictions
        if ($user->isAdmin()) {
            return true;
        }

        // Always block restricted models
        if (in_array($model, $this->restrictedModels)) {
            return false;
        }

        // Trial restriction: starter tier with trial_started_at set but no paid subscription
        if (
            $user->trial_started_at !== null &&
            $user->subscription_tier === 'starter' &&
            $user->myfatoorah_subscription_id === null
        ) {
            $size = config('models.models.' . $model . '.size');
            return $size === 'small';
        }

        // All active models available to all tiers (starter/basic/pro)
        return true;
    }

    /**
     * Check if model is allowed for a given tier string (legacy helper).
     * Delegates to canAccessModel logic without a User object — used for
     * non-user-context checks (e.g. listing available models for a tier).
     * Restricted models are always blocked; everything else is allowed.
     */
    public function isModelAllowed(string $model, string $tier): bool
    {
        // Always block restricted models
        if (in_array($model, $this->restrictedModels)) {
            return false;
        }

        return true;
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
     * Filter models array, removing restricted ones.
     * For user-aware filtering, use canAccessModel() directly.
     */
    public function filterModels(array $models, string $tier): array
    {
        return array_values(array_filter($models, function ($model) {
            $modelName = is_string($model) ? $model : ($model['id'] ?? $model['name'] ?? '');
            return !in_array($modelName, $this->restrictedModels);
        }));
    }

    /**
     * Get all available models from the registry (excluding restricted).
     */
    public function getAllAvailableModels(): array
    {
        $registry = config('models.models', []);
        $all = array_keys($registry);

        return array_values(array_filter($all, fn ($m) => !in_array($m, $this->restrictedModels)));
    }
}
