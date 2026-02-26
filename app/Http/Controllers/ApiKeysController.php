<?php

namespace App\Http\Controllers;

use App\Models\ApiKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiKeysController extends Controller
{
    /**
     * Display a listing of the user's API keys.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $apiKeys = $user->apiKeys()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Transform keys to show prefix instead of full key
        $apiKeys->getCollection()->transform(function ($apiKey) {
            return [
                'id' => $apiKey->id,
                'name' => $apiKey->name,
                'prefix' => $apiKey->prefix,
                'permissions' => $apiKey->permissions,
                'last_used_at' => $apiKey->last_used_at,
                'created_at' => $apiKey->created_at->toISOString(),
                'updated_at' => $apiKey->updated_at->toISOString(),
            ];
        });

        return response()->json([
            'data' => $apiKeys,
        ]);
    }

    /**
     * Store a newly created API key.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Generate 64-character random hex key
        $key = bin2hex(random_bytes(32));

        // Compute prefix = first 12 chars
        $prefix = substr($key, 0, 12);

        // Create the API key
        $apiKey = ApiKeys::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'key' => $key,
            'prefix' => $prefix,
            'permissions' => ['read', 'write'],
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'API key created successfully.',
            'key' => $key, // Show full key only ONCE
            'prefix' => $prefix,
        ], 201);
    }

    /**
     * Remove the specified API key from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $apiKey = $user->apiKeys()->where('id', $id)->first();

        if (!$apiKey) {
            return response()->json([
                'message' => 'API key not found.',
            ], 404);
        }

        $apiKey->delete();

        return response()->json([
            'message' => 'API key deleted successfully.',
        ]);
    }
}
