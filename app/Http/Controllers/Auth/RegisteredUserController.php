<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'credits' => 0,
            'subscription_tier' => 'basic',
        ]);

        // Log in user immediately after registration
        Auth::login($user, $request->has('remember'));

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user->load('apiKeys', 'subscriptions'),
        ], 201);
    }
}
