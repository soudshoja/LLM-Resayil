<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OtpController extends Controller
{
    public function sendPhoneOtp(Request $request, OtpService $otp)
    {
        $request->validate([
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore(Auth::id()),
            ],
        ]);

        try {
            $otp->send($request->phone);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send SMS. Check the phone number and try again.'], 500);
        }

        return response()->json(['step' => 'verify', 'phone' => $request->phone]);
    }

    public function verifyPhoneOtp(Request $request, OtpService $otp)
    {
        $request->validate([
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore(Auth::id()),
            ],
            'otp_code' => 'required|string|size:6',
        ]);

        try {
            $verified = $otp->verify($request->phone, $request->otp_code);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        if (!$verified) {
            return response()->json(['message' => 'Incorrect code. Please try again.'], 422);
        }

        $user = Auth::user();
        $user->phone = $request->phone;
        $user->phone_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Phone number updated successfully.']);
    }
}
