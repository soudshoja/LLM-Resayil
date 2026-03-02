<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiKeys;
use App\Models\Subscriptions;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create an API key for a user via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function createApiKeyForUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $keyName = $request->input('key_name', 'Admin Created Key');

        // Generate API key (raw hex, same as ApiKeysController)
        $apiKey = bin2hex(random_bytes(32));
        $prefix = substr($apiKey, 0, 12);

        ApiKeys::create([
            'user_id'     => $user->id,
            'name'        => $keyName,
            'key'         => $apiKey,
            'prefix'      => $prefix,
            'permissions' => ['read', 'write'],
            'status'      => 'active',
        ]);

        return response()->json(['success' => true, 'message' => $apiKey]);
    }

    /**
     * Set exact credit balance for a user via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserCredits(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $credits = $request->input('credits');

        if ($credits === null || (int)$credits < 0) {
            return response()->json(['success' => false, 'message' => 'Credits must be a non-negative number.'], 422);
        }

        $user->credits = (int) $credits;
        $user->save();

        return response()->json(['success' => true, 'message' => "Credits updated to {$credits}."]);
    }

    /**
     * Change user's subscription tier via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserTier(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $tier = $request->input('tier');

        if (!in_array($tier, ['starter', 'basic', 'pro', 'enterprise'])) {
            return response()->json(['success' => false, 'message' => 'Invalid tier.'], 422);
        }

        $user->subscription_tier = $tier;
        $user->save();

        return response()->json(['success' => true, 'message' => "Tier updated to {$tier}."]);
    }

    /**
     * Set subscription expiry date for a user via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserExpiry(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $expiry = $request->input('expiry');

        if (empty($expiry)) {
            $user->subscription_expiry = null;
        } else {
            try {
                $user->subscription_expiry = \Carbon\Carbon::parse($expiry);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Invalid date format.'], 422);
            }
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Expiry updated.']);
    }
}
