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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createApiKeyForUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
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
        return redirect()->back()->with('success', "API key created successfully. Copy this key - it won't be shown again:\n\n$apiKey");
    }

    /**
     * Set exact credit balance for a user via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUserCredits(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $credits = $request->input('credits');

        if ($credits === null || $credits < 0) {
            return redirect()->back()->with('error', 'Credits must be a non-negative number.');
        }

        $user->credits = $credits;
        $user->save();

        return redirect()->back()->with('success', "User credits updated to {$credits}.");
    }

    /**
     * Change user's subscription tier via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUserTier(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $tier = $request->input('tier');

        if (!in_array($tier, ['basic', 'pro', 'enterprise'])) {
            return redirect()->back()->with('error', 'Invalid tier. Must be basic, pro, or enterprise.');
        }

        $user->subscription_tier = $tier;
        $user->save();

        return redirect()->back()->with('success', "User tier updated to {$tier}.");
    }

    /**
     * Set subscription expiry date for a user via POST endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUserExpiry(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $expiry = $request->input('expiry');

        if (empty($expiry)) {
            $user->subscription_expiry = null;
        } else {
            try {
                $user->subscription_expiry = \Carbon\Carbon::parse($expiry);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Invalid date format. Use YYYY-MM-DD or relative dates.');
            }
        }

        $user->save();

        return redirect()->back()->with('success', 'Subscription expiry updated successfully.');
    }
}
