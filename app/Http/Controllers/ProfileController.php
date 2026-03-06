<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $meta = \App\Helpers\SeoHelper::getPageMeta('profile');
        return view('profile', [
            'user' => $request->user(),
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', __('profile.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', function ($attr, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_success', __('profile.password_updated'));
    }
}
