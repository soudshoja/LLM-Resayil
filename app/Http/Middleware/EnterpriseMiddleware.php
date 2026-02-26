<?php

namespace App\Http\Middleware;

use App\Models\TeamMember;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnterpriseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 401);
            }

            return redirect()->route('login');
        }

        // Check if user is on Enterprise tier
        if ($user->subscription_tier !== 'enterprise') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden. This feature is available to Enterprise tier only.',
                ], 403);
            }

            return redirect()->route('dashboard')->with('error', 'This feature is available to Enterprise tier only.');
        }

        // Optionally check if user has team access (has an active subscription)
        $hasTeamAccess = TeamMember::where('team_owner_id', $user->id)->exists();

        if (!$hasTeamAccess && $request->expectsJson()) {
            return response()->json([
                'message' => 'No team found. Please add team members to access this feature.',
            ], 403);
        }

        return $next($request);
    }
}
