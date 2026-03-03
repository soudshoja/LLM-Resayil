<?php

namespace App\Http\Controllers;

use App\Models\ModelConfig;
use App\Models\User;
use App\Models\ApiKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminModelController extends Controller
{
    /**
     * Display a listing of all models with their configuration.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mc = app(\App\Http\Controllers\Api\ModelsController::class);
        $models = $mc->fetchModelsFromOllama() ?? $mc->fallbackToConfig();

        // Get current settings from database
        $modelConfigs = ModelConfig::pluck('is_active', 'model_id')->toArray();
        $multipliers = ModelConfig::whereNotNull('credit_multiplier_override')
            ->pluck('credit_multiplier_override', 'model_id')
            ->toArray();

        $modelList = [];
        foreach ($models as $modelId => $modelData) {
            $modelList[] = [
                'model_id'                  => $modelId,
                'name'                      => $modelData['name'] ?? $modelId,
                'family'                    => $modelData['family'] ?? 'Unknown',
                'type'                      => $modelData['type'] ?? 'local',
                'category'                  => $modelData['category'] ?? 'chat',
                'size'                      => $modelData['size'] ?? 'medium',
                'is_active'                 => $modelConfigs[$modelId] ?? true,
                'credit_multiplier'         => $modelData['credit_multiplier'] ?? 1.0,
                'credit_multiplier_override' => $multipliers[$modelId] ?? null,
                'ollama_name'               => $modelData['ollama_name'] ?? $modelId,
            ];
        }

        return view('admin.models', compact('modelList'));
    }

    /**
     * Update model configuration settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $id = $request->input('model_id');

        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'credit_multiplier_override' => 'nullable|numeric|min:0',
        ]);

        $modelConfig = ModelConfig::firstOrNew(['model_id' => $id]);
        $modelConfig->model_id = $id;

        if ($request->has('is_active')) {
            $modelConfig->is_active = $request->boolean('is_active');
        }

        if ($request->has('credit_multiplier_override')) {
            $modelConfig->credit_multiplier_override = $request->input('credit_multiplier_override');
        }

        $modelConfig->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Model settings updated successfully.');
    }

    /**
     * Create an API key for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createApiKey(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $keyName = $request->input('key_name', 'Admin Created Key');

        // Generate API key
        $apiKey = bin2hex(random_bytes(32));
        $hashedKey = hash('sha256', $apiKey);

        ApiKeys::create([
            'user_id' => $user->id,
            'name' => $keyName,
            'key' => $hashedKey,
            'permissions' => 'read,write',
        ]);

        // Return the raw key for user to copy (only shown once)
        return back()->with('success', "API key created successfully. Copy this key - it won't be shown again:\n\n$apiKey");

        // Note: In production, show the key in a modal or send via secure channel
    }

    /**
     * Set exact credit balance for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setCredits(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $credits = $request->input('credits');

        if ($credits === null || $credits < 0) {
            return back()->with('error', 'Credits must be a non-negative number.');
        }

        $user->credits = $credits;
        $user->save();

        // Log the adjustment
        \Log::info("Admin adjusted {$user->email}'s credits to {$credits}");

        return back()->with('success', "User credits updated to {$credits}.");
    }

    /**
     * Change user's subscription tier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setTier(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $tier = $request->input('tier');

        if (!in_array($tier, ['basic', 'pro', 'enterprise'])) {
            return back()->with('error', 'Invalid tier. Must be basic, pro, or enterprise.');
        }

        $user->subscription_tier = $tier;
        $user->save();

        return back()->with('success', "User tier updated to {$tier}.");
    }

    /**
     * Set subscription expiry date for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setExpiry(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $expiry = $request->input('expiry');

        if (empty($expiry)) {
            $user->subscription_expiry = null;
        } else {
            try {
                $user->subscription_expiry = \Carbon\Carbon::parse($expiry);
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid date format. Use YYYY-MM-DD or relative dates.');
            }
        }

        $user->save();

        return back()->with('success', 'Subscription expiry updated successfully.');
    }
}
