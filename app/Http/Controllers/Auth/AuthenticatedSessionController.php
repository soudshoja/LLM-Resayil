<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect(\App\Providers\RouteServiceProvider::HOME);
        }

        $meta = \App\Helpers\SeoHelper::getPageMeta('login');
        return view('auth.login', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required_without:email|numeric',
            'email' => 'required_without:phone|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('phone', 'email', 'password');

        // Try to authenticate with phone or email
        $user = null;
        if ($request->has('phone')) {
            $user = User::where('phone', $request->phone)->first();
        } elseif ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        }

        $passwordValid = false;
        if ($user) {
            try {
                $passwordValid = Hash::check($request->password, $user->password);
            } catch (\RuntimeException $e) {
                $passwordValid = false;
            }
        }

        if (!$user || !$passwordValid) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Log in user
        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user->load('apiKeys', 'subscriptions'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
