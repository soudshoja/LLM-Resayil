<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKeys;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $scope = null): Response
    {
        $authorization = $request->header('Authorization');

        if (!$authorization) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Authorization header required.',
            ], 401);
        }

        // Extract Bearer token from Authorization header
        $parts = explode(' ', $authorization);
        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Invalid authorization header format.',
            ], 401);
        }

        $key = $parts[1];

        // Query ApiKeys table for matching key
        $apiKey = ApiKeys::where('key', $key)->first();

        if (!$apiKey) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Invalid API key.',
            ], 401);
        }

        // Check permissions include required scope
        if ($scope) {
            $permissions = $apiKey->permissions;
            if (!in_array($scope, $permissions)) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => "Permission '{$scope}' required.",
                ], 403);
            }
        }

        // Update last_used_at timestamp
        $apiKey->update(['last_used_at' => now()]);

        // Set authenticated user so $request->user() works in controllers
        $user = $apiKey->user;
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
