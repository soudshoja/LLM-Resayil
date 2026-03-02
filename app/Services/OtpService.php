<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate a 6-digit OTP, persist it, and send via Resayil WhatsApp API.
     * Invalidates any previous unused OTPs for the same phone.
     *
     * @throws \Exception if WhatsApp sending fails
     */
    public function send(string $phone): void
    {
        // Expire previous codes for this phone
        OtpCode::where('phone', $phone)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone'      => $phone,
            'code'       => $code,
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        $message = $this->buildOTPMessage($code, 10);

        $response = Http::withHeaders([
            'Token'        => config('services.whatsapp.api_key'),
            'Content-Type' => 'application/json',
        ])->timeout(15)->post(config('services.whatsapp.api_url'), [
            'phone'   => $phone,
            'message' => $message,
        ]);

        if (!$response->successful() || isset($response->json()['error'])) {
            Log::error('OtpService: WhatsApp send failed', [
                'phone'    => $phone,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('Failed to send verification message. Please check your phone number and try again.');
        }
    }

    /**
     * Verify a submitted code against the most recent valid OTP for the phone.
     *
     * Returns true on success, false on wrong code.
     * Throws \Exception with user-facing message on expired/exceeded/not-found.
     *
     * @throws \Exception
     */
    public function verify(string $phone, string $submittedCode): bool
    {
        $otp = OtpCode::findValid($phone);

        if (!$otp) {
            throw new \Exception('No valid OTP found for this number. Please request a new code.');
        }

        // Increment attempts before checking to prevent brute force
        $otp->increment('attempts');

        if ($otp->hasExceededAttempts()) {
            throw new \Exception('Too many incorrect attempts. Please request a new code.');
        }

        if ($otp->code !== $submittedCode) {
            return false;
        }

        // Mark as used
        $otp->update(['used_at' => now()]);

        return true;
    }

    private function buildOTPMessage(string $code, int $minutes): string
    {
        return "رمز التحقق الخاص بك: *{$code}*\n\nYour verification code: *{$code}*\n\nصالح لمدة {$minutes} دقائق\nValid for {$minutes} minutes\n\n⚠️ لا تشارك هذا الرمز مع أحد\n⚠️ Do not share this code with anyone";
    }
}
