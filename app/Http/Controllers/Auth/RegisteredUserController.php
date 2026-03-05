<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Step 1: Validate registration fields and send OTP to the phone number.
     * Does NOT create the user yet.
     */
    public function sendOtp(Request $request, OtpService $otp)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'required|numeric|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $otp->send($request->phone);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send verification message. Please check your phone number and try again.',
            ], 500);
        }

        return response()->json(['step' => 'verify', 'phone' => $request->phone]);
    }

    /**
     * Step 2: Verify OTP code, then create user and log in.
     */
    public function store(Request $request, OtpService $otp)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'required|numeric|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $verified = $otp->verify($request->phone, $request->otp_code);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        if (!$verified) {
            return response()->json([
                'message' => 'Incorrect code. Please try again.',
                'errors'  => ['otp_code' => ['The verification code is incorrect.']],
            ], 422);
        }

        $user = User::create([
            'phone'               => $request->phone,
            'email'               => $request->email,
            'name'                => $request->name,
            'password'            => Hash::make($request->password),
            'credits'             => 1000,
            'subscription_tier'   => 'basic',
            'phone_verified_at'   => now(),
        ]);

        Auth::login($user);

        // Notify admins of new registration
        $adminEmails = ['shoja.soud@gmail.com', 'admin@llm.resayil.io'];
        $mailData = [
            'name'         => $user->name,
            'email'        => $user->email,
            'phone'        => $user->phone,
            'registeredAt' => $user->created_at->format('Y-m-d H:i:s T'),
        ];
        foreach ($adminEmails as $adminEmail) {
            try {
                Mail::send('emails.new-user-registered', $mailData, function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                            ->subject('New User Registration — LLM Resayil')
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });
                Log::info('Admin registration notification sent to ' . $adminEmail);
            } catch (\Exception $e) {
                Log::error('Admin registration notification failed for ' . $adminEmail . ': ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Registration successful.',
        ], 201);
    }
}
