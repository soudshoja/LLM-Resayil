# Phase 7 Plan 05: Mobile Phone OTP Verification + Homepage Pricing Update

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** (1) Registration and phone changes on the profile page require OTP verification via SMS (Twilio). Users submit their details, receive a 6-digit SMS code, enter it, and only then is their account created / phone number saved. (2) The homepage pricing section is updated to show accurate annual prices (99/299/999 KWD) and correct feature lists that match the real billing page and tier access control.

**Architecture:**
- OTP flow: `RegisteredUserController` gains two endpoints — `sendOtp` (validate form, generate + SMS code, store in `otp_codes`, return step-2 screen) and `store` (verify code, create user, log in). Profile phone update similarly goes through `OtpController`.
- `OtpCode` model backed by `otp_codes` table. `OtpService` wraps Twilio SDK: `send(phone)` generates code, stores it, sends SMS; `verify(phone, code)` checks code freshness, attempts, and marks used.
- Homepage: only the pricing `<section>` block (`welcome.blade.php` lines 89–133) is replaced. No other homepage content changes.

**Tech Stack:** Laravel 11, Blade, Twilio `twilio/sdk` Composer package, `otp_codes` DB table, `phone_verified_at` column on `users`

---

## Task 1: Database migrations

**Files:**
- Create: `database/migrations/YYYY_MM_DD_000001_add_phone_verified_at_to_users_table.php`
- Create: `database/migrations/YYYY_MM_DD_000002_create_otp_codes_table.php`

**Step 1: Read existing users migration to confirm column names**
```bash
ls database/migrations/ | grep users
cat database/migrations/*create_users_table*
```

**Step 2: Create the `phone_verified_at` migration**

Run:
```bash
php artisan make:migration add_phone_verified_at_to_users_table --table=users
```

Edit the generated file:
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->timestamp('phone_verified_at')->nullable()->after('phone');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone_verified_at');
    });
}
```

**Step 3: Create the `otp_codes` migration**

Run:
```bash
php artisan make:migration create_otp_codes_table
```

Edit the generated file:
```php
public function up(): void
{
    Schema::create('otp_codes', function (Blueprint $table) {
        $table->id();
        $table->string('phone', 30)->index();
        $table->string('code', 6);
        $table->unsignedTinyInteger('attempts')->default(0);
        $table->timestamp('expires_at');
        $table->timestamp('used_at')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('otp_codes');
}
```

**Step 4: Run locally and verify**
```bash
php artisan migrate
php artisan tinker --execute="Schema::hasColumn('users','phone_verified_at') ? 'OK' : 'MISSING';"
php artisan tinker --execute="Schema::hasTable('otp_codes') ? 'OK' : 'MISSING';"
```

**Step 5: Commit**
```bash
git add database/migrations/
git commit -m "feat: add phone_verified_at to users + create otp_codes table"
```

---

## Task 2: OtpCode Eloquent model

**Files:**
- Create: `app/Models/OtpCode.php`

**Context:** Must follow project UUID conventions — BUT `otp_codes` uses auto-increment `id` (no UUID needed here since codes are short-lived and never referenced externally). Standard Eloquent model is fine.

**Step 1: Create the model**
```bash
php artisan make:model OtpCode
```

**Step 2: Edit `app/Models/OtpCode.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'phone',
        'code',
        'attempts',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    /**
     * Return the most recent unused, non-expired OTP for the given phone.
     */
    public static function findValid(string $phone): ?static
    {
        return static::where('phone', $phone)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', 3)
            ->latest()
            ->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= 3;
    }
}
```

**Step 3: Commit**
```bash
git add app/Models/OtpCode.php
git commit -m "feat: add OtpCode model with findValid() helper"
```

---

## Task 3: Install Twilio SDK + create OtpService

**Files:**
- Modify: `composer.json` (via composer require)
- Create: `app/Services/OtpService.php`
- Modify: `.env` (document new keys — do not commit .env itself)
- Modify: `config/services.php` (add twilio block)

**Step 1: Install Twilio SDK**
```bash
composer require twilio/sdk
```

**Step 2: Add Twilio config to `config/services.php`**

Open `config/services.php` and append inside the return array:
```php
'twilio' => [
    'sid'  => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'from' => env('TWILIO_FROM'),
],
```

**Step 3: Add keys to `.env` (and `.env.example`)**

Add to `.env` (locally) and document in `.env.example`:
```
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_TOKEN=your_auth_token_here
TWILIO_FROM=+1xxxxxxxxxx
```

**Step 4: Create `app/Services/OtpService.php`**
```php
<?php

namespace App\Services;

use App\Models\OtpCode;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Facades\Log;

class OtpService
{
    private TwilioClient $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * Generate a 6-digit OTP, persist it, and send via SMS.
     * Invalidates any previous unused OTPs for the same phone.
     *
     * @throws \Exception if SMS sending fails
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

        $this->twilio->messages->create($phone, [
            'from' => config('services.twilio.from'),
            'body' => "Your LLM Resayil verification code is: {$code}. Valid for 10 minutes.",
        ]);
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
}
```

**Step 5: Commit**
```bash
git add app/Services/OtpService.php config/services.php composer.json composer.lock
git commit -m "feat: add OtpService with Twilio SMS send/verify, install twilio/sdk"
```

---

## Task 4: Update RegisteredUserController — two-step registration

**Files:**
- Modify: `app/Http/Controllers/Auth/RegisteredUserController.php`
- Modify: `routes/web.php`

**Context:** Currently `GET /register` shows the form and `POST /register` creates the user immediately. We need to split `POST /register` into two steps:
1. `POST /register/otp` — validate form fields, send OTP, return JSON `{step: 'verify'}` so the JS can show the OTP input screen.
2. `POST /register` — validate OTP code, create user, log in (the existing endpoint, updated).

The form data (name, email, phone, password, password_confirmation) is kept in the browser between steps (already in JS). The OTP step only sends `phone` + `code` to the server for verification.

**Step 1: Add the new route in `routes/web.php`**

Inside the guest-only registration block, add:
```php
Route::post('/register/otp', [RegisteredUserController::class, 'sendOtp'])->middleware('guest');
```

**Step 2: Rewrite `RegisteredUserController`**
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
                'message' => 'Failed to send verification SMS. Please check your phone number and try again.',
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
            'phone'             => $request->phone,
            'email'             => $request->email,
            'name'              => $request->name,
            'password'          => Hash::make($request->password),
            'credits'           => 0,
            'subscription_tier' => 'basic',
            'phone_verified_at' => now(),
        ]);

        Auth::login($user, $request->has('remember'));

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => $user->load('apiKeys', 'subscriptions'),
        ], 201);
    }
}
```

**Step 3: Commit**
```bash
git add app/Http/Controllers/Auth/RegisteredUserController.php routes/web.php
git commit -m "feat: split registration into sendOtp + store steps with OTP verification"
```

---

## Task 5: Two-step registration UI

**Files:**
- Modify: `resources/views/auth/register.blade.php`

**Context:** Currently a single-form page with JS that `POST /register` and redirects. Need to convert to a two-screen flow entirely in JavaScript (no full-page redirect between steps):
- Screen 1: existing form fields (name, email, phone, password, password_confirmation) — submits to `POST /register/otp`
- Screen 2: OTP input (single 6-digit field) — submits to `POST /register` with all original form data + otp_code
- "Resend code" link on screen 2 calls `POST /register/otp` again with the same phone

**Step 1: Replace the `register.blade.php` content**

Keep the same card/auth-container CSS. Replace the `<form>` and `<script>` section with:

```blade
@extends('layouts.app')

@section('title', 'Register')

@push('styles')
<style>
    .auth-container { min-height: calc(100vh - 64px); display: flex; align-items: center; justify-content: center; padding: 2rem; }
    .auth-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; }
    .auth-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.4rem; }
    .auth-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.75rem; }
    .auth-footer { text-align: center; margin-top: 1.25rem; font-size: 0.875rem; color: var(--text-muted); }
    .auth-footer a { color: var(--gold); text-decoration: none; }
    .otp-input { font-size: 2rem; text-align: center; letter-spacing: 0.5rem; font-weight: 700; }
    #step-verify { display: none; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div id="step-register">
            <div class="auth-title">Create Account</div>
            <div class="auth-subtitle">Start accessing powerful LLMs today</div>

            <div id="alert-area-register"></div>

            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Ahmad Al-Rashidi">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number <span style="color:var(--error)">*</span></label>
                    <input type="tel" name="phone" class="form-input" placeholder="96550000000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span style="color:var(--error)">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Min. 8 characters" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password <span style="color:var(--error)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
                </div>
                <button type="submit" id="btn-register" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    Send Verification Code
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </div>

        <div id="step-verify">
            <div class="auth-title">Verify Phone</div>
            <div class="auth-subtitle" id="verify-subtitle">Enter the 6-digit code sent to your phone.</div>

            <div id="alert-area-verify"></div>

            <form id="verifyForm">
                <div class="form-group" style="margin-bottom:1.5rem">
                    <label class="form-label">Verification Code</label>
                    <input type="text" name="otp_code" id="otp-input" class="form-input otp-input"
                           maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
                </div>
                <button type="submit" id="btn-verify" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;font-size:0.95rem">
                    Create Account
                </button>
            </form>

            <div class="auth-footer" style="margin-top:1rem">
                Wrong number? <a href="#" id="btn-back">Go back</a>
                &nbsp;&middot;&nbsp;
                <a href="#" id="btn-resend">Resend code</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
let formData = {};

// Step 1: Send OTP
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-register');
    const alertArea = document.getElementById('alert-area-register');
    alertArea.innerHTML = '';
    btn.textContent = 'Sending code...';
    btn.disabled = true;

    formData = {
        name: form.name.value,
        email: form.email.value,
        phone: form.phone.value,
        password: form.password.value,
        password_confirmation: form.password_confirmation.value,
    };

    try {
        const res = await fetch('/register/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(formData)
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            document.getElementById('verify-subtitle').textContent =
                `Enter the 6-digit code sent to ${formData.phone}.`;
            document.getElementById('step-register').style.display = 'none';
            document.getElementById('step-verify').style.display = 'block';
            document.getElementById('otp-input').focus();
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Failed to send code.');
            alertArea.innerHTML = `<div class="alert alert-error">${msgs}</div>`;
            btn.textContent = 'Send Verification Code';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
        btn.textContent = 'Send Verification Code';
        btn.disabled = false;
    }
});

// Step 2: Verify OTP + create account
document.getElementById('verifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify');
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    btn.textContent = 'Verifying...';
    btn.disabled = true;

    const payload = { ...formData, otp_code: document.getElementById('otp-input').value };

    try {
        const res = await fetch('/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(payload)
        });
        const json = await res.json();
        if (res.ok || res.status === 201) {
            window.location.href = '/dashboard';
        } else {
            const msgs = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message || 'Verification failed.');
            alertArea.innerHTML = `<div class="alert alert-error">${msgs}</div>`;
            btn.textContent = 'Create Account';
            btn.disabled = false;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
        btn.textContent = 'Create Account';
        btn.disabled = false;
    }
});

// Back button
document.getElementById('btn-back').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('step-verify').style.display = 'none';
    document.getElementById('step-register').style.display = 'block';
    document.getElementById('btn-register').textContent = 'Send Verification Code';
    document.getElementById('btn-register').disabled = false;
});

// Resend code
document.getElementById('btn-resend').addEventListener('click', async function(e) {
    e.preventDefault();
    const alertArea = document.getElementById('alert-area-verify');
    alertArea.innerHTML = '';
    this.textContent = 'Sending...';

    try {
        const res = await fetch('/register/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(formData)
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            alertArea.innerHTML = '<div class="alert alert-success">A new code has been sent.</div>';
            document.getElementById('otp-input').value = '';
        } else {
            alertArea.innerHTML = `<div class="alert alert-error">${json.message || 'Failed to resend.'}</div>`;
        }
    } catch (err) {
        alertArea.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
    }
    this.textContent = 'Resend code';
});
</script>
@endpush
```

**Step 2: Commit**
```bash
git add resources/views/auth/register.blade.php
git commit -m "feat: two-step registration UI — form fields + OTP verification screen"
```

---

## Task 6: Add phone field to profile page with OTP verification

**Files:**
- Create: `app/Http/Controllers/OtpController.php`
- Modify: `routes/web.php`
- Modify: `resources/views/profile.blade.php`

**Context:** The profile page at `/profile` (`resources/views/profile.blade.php`) currently shows name, email, and password — but no phone field. We need to add a "Phone Number" section that lets authenticated users view their current phone and update it by going through OTP verification. The phone change uses the same `OtpService` but sends the code to the *new* phone number before saving it.

**Step 1: Create `app/Http/Controllers/OtpController.php`**
```php
<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    /**
     * Send OTP to the new phone number the user wants to set.
     * Validates that the phone isn't already taken by another user.
     */
    public function sendPhoneOtp(Request $request, OtpService $otp)
    {
        $request->validate([
            'phone' => [
                'required',
                'numeric',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore(Auth::id()),
            ],
        ]);

        try {
            $otp->send($request->phone);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send SMS. Check the phone number and try again.'], 500);
        }

        return response()->json(['step' => 'verify', 'phone' => $request->phone]);
    }

    /**
     * Verify OTP and save new phone number to the authenticated user.
     */
    public function verifyPhoneOtp(Request $request, OtpService $otp)
    {
        $request->validate([
            'phone'    => [
                'required',
                'numeric',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore(Auth::id()),
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
```

**Step 2: Add routes to `routes/web.php`**

Inside the existing `Route::middleware('auth')->group(...)` block that contains the profile routes:
```php
Route::post('/profile/phone/otp', [App\Http\Controllers\OtpController::class, 'sendPhoneOtp'])->name('profile.phone.otp');
Route::post('/profile/phone/verify', [App\Http\Controllers\OtpController::class, 'verifyPhoneOtp'])->name('profile.phone.verify');
```

**Step 3: Add phone section to `resources/views/profile.blade.php`**

After the closing `</div>` of the "Profile Information" card (around line 41) and before the "Change Password" card, insert a new card:

```blade
{{-- Phone Number --}}
<div class="card mb-6">
    <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.5rem">Phone Number</h2>
    <p class="text-secondary text-sm" style="margin-bottom:1.25rem">
        Your verified phone number. Changing it requires SMS verification.
    </p>

    <div id="phone-current" style="margin-bottom:1rem">
        <span style="font-size:0.875rem;color:var(--text-muted)">Current: </span>
        <span style="font-weight:600">{{ auth()->user()->phone ?? '—' }}</span>
        @if(auth()->user()->phone_verified_at)
            <span class="badge badge-green" style="margin-left:0.5rem;font-size:0.7rem">Verified</span>
        @else
            <span class="badge" style="margin-left:0.5rem;font-size:0.7rem;background:rgba(255,80,80,0.15);color:#ff5050">Unverified</span>
        @endif
    </div>

    <div id="phone-alert"></div>

    {{-- Step 1: enter new phone --}}
    <div id="phone-step-enter">
        <form id="phoneForm" style="display:flex;gap:0.75rem;align-items:flex-end">
            <div class="form-group" style="flex:1;margin-bottom:0">
                <label class="form-label">New Phone Number</label>
                <input type="tel" id="new-phone-input" name="phone" class="form-input" placeholder="96550000000" required>
            </div>
            <button type="submit" id="btn-send-phone-otp" class="btn btn-outline" style="padding:0.6rem 1.25rem;white-space:nowrap">
                Send Code
            </button>
        </form>
    </div>

    {{-- Step 2: enter OTP --}}
    <div id="phone-step-verify" style="display:none">
        <p class="text-sm text-secondary" style="margin-bottom:1rem" id="phone-verify-hint">
            Enter the code sent to your new number.
        </p>
        <form id="phoneVerifyForm" style="display:flex;gap:0.75rem;align-items:flex-end">
            <div class="form-group" style="flex:1;margin-bottom:0">
                <label class="form-label">Verification Code</label>
                <input type="text" id="phone-otp-input" name="otp_code" class="form-input"
                       maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="------" required>
            </div>
            <button type="submit" id="btn-verify-phone" class="btn btn-gold" style="padding:0.6rem 1.25rem;white-space:nowrap">
                Confirm
            </button>
        </form>
        <div style="margin-top:0.75rem;font-size:0.8rem;color:var(--text-muted)">
            <a href="#" id="phone-back-link" style="color:var(--gold)">Change number</a>
            &nbsp;&middot;&nbsp;
            <a href="#" id="phone-resend-link" style="color:var(--gold)">Resend code</a>
        </div>
    </div>
</div>
```

**Step 4: Add inline JavaScript for phone verification in profile**

Append the following before `@endsection` or in `@push('scripts')`:

```blade
@push('scripts')
<script>
const PHONE_CSRF = '{{ csrf_token() }}';
let pendingPhone = '';

document.getElementById('phoneForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-send-phone-otp');
    const alert = document.getElementById('phone-alert');
    alert.innerHTML = '';
    pendingPhone = document.getElementById('new-phone-input').value;
    btn.textContent = 'Sending...';
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok && json.step === 'verify') {
            document.getElementById('phone-verify-hint').textContent =
                `Enter the 6-digit code sent to ${pendingPhone}.`;
            document.getElementById('phone-step-enter').style.display = 'none';
            document.getElementById('phone-step-verify').style.display = 'block';
            document.getElementById('phone-otp-input').focus();
        } else {
            alert.innerHTML = `<div class="alert alert-error">${json.message || 'Failed to send code.'}</div>`;
            btn.textContent = 'Send Code';
            btn.disabled = false;
        }
    } catch (err) {
        alert.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
        btn.textContent = 'Send Code';
        btn.disabled = false;
    }
});

document.getElementById('phoneVerifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-verify-phone');
    const alert = document.getElementById('phone-alert');
    alert.innerHTML = '';
    btn.textContent = 'Verifying...';
    btn.disabled = true;

    try {
        const res = await fetch('/profile/phone/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone, otp_code: document.getElementById('phone-otp-input').value })
        });
        const json = await res.json();
        if (res.ok) {
            alert.innerHTML = '<div class="alert alert-success">Phone number updated successfully.</div>';
            document.getElementById('phone-step-verify').style.display = 'none';
            document.getElementById('phone-step-enter').style.display = 'block';
            // Update displayed phone
            document.querySelector('#phone-current span:nth-child(2)').textContent = pendingPhone;
            document.querySelector('#phone-current .badge').textContent = 'Verified';
            document.querySelector('#phone-current .badge').className = 'badge badge-green';
            document.querySelector('#phone-current .badge').style = 'margin-left:0.5rem;font-size:0.7rem';
            btn.textContent = 'Confirm';
            btn.disabled = false;
        } else {
            alert.innerHTML = `<div class="alert alert-error">${json.message || 'Verification failed.'}</div>`;
            btn.textContent = 'Confirm';
            btn.disabled = false;
        }
    } catch (err) {
        alert.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
        btn.textContent = 'Confirm';
        btn.disabled = false;
    }
});

document.getElementById('phone-back-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('phone-step-verify').style.display = 'none';
    document.getElementById('phone-step-enter').style.display = 'block';
    document.getElementById('btn-send-phone-otp').textContent = 'Send Code';
    document.getElementById('btn-send-phone-otp').disabled = false;
});

document.getElementById('phone-resend-link').addEventListener('click', async function(e) {
    e.preventDefault();
    const alert = document.getElementById('phone-alert');
    alert.innerHTML = '';
    this.textContent = 'Sending...';

    try {
        const res = await fetch('/profile/phone/otp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PHONE_CSRF },
            body: JSON.stringify({ phone: pendingPhone })
        });
        const json = await res.json();
        if (res.ok) {
            alert.innerHTML = '<div class="alert alert-success">A new code was sent.</div>';
            document.getElementById('phone-otp-input').value = '';
        } else {
            alert.innerHTML = `<div class="alert alert-error">${json.message || 'Failed to resend.'}</div>`;
        }
    } catch (err) {
        alert.innerHTML = '<div class="alert alert-error">An error occurred.</div>';
    }
    this.textContent = 'Resend code';
});
</script>
@endpush
```

**Step 5: Commit**
```bash
git add app/Http/Controllers/OtpController.php routes/web.php resources/views/profile.blade.php
git commit -m "feat: add phone OTP verification to profile page — view/update verified phone"
```

---

## Task 7: Update homepage pricing cards

**Files:**
- Modify: `resources/views/welcome.blade.php`

**Context:** The pricing section (lines 89–133) currently shows incorrect monthly prices (10/50/200 KWD) and incorrect feature lists. The actual billing page (`billing/plans.blade.php`) shows annual prices: Basic 99 KWD/yr, Pro 299 KWD/yr, Enterprise 999 KWD/yr. Feature lists must reflect the real tier access defined in `ModelAccessControl` and `ModelsController`.

Real features per tier:
- **Basic (99 KWD/yr):** 10,000 credits/month, 10 req/min, llama3.2:3b + smollm2:135m models, API key access
- **Pro (299 KWD/yr):** 50,000 credits/month, 30 req/min, + qwen2.5-coder:14b + mistral-small3.2:24b models, API key access
- **Enterprise (999 KWD/yr):** Unlimited credits, 60 req/min, all models including cloud (deepseek-v3, qwen3.5, devstral), team management, priority queue

**Step 1: Read the full pricing section to confirm current line numbers**
```bash
sed -n '89,133p' resources/views/welcome.blade.php
```

**Step 2: Replace the pricing section**

Replace the entire `<!-- Pricing -->` section (lines 89–133) with:

```blade
<!-- Pricing -->
<section class="section" id="pricing">
    <div class="section-title">
        <h2>Simple, Transparent Pricing</h2>
        <p>All prices in Kuwaiti Dinar. Billed annually. Credits roll over month-to-month.</p>
    </div>
    <div class="pricing-grid">
        <div class="pricing-card">
            <div class="plan-name">Basic</div>
            <div class="plan-price">99 <span>KWD/yr</span></div>
            <ul class="plan-features">
                <li>10,000 credits/month</li>
                <li>llama3.2:3b &amp; smollm2:135m</li>
                <li>10 requests/minute</li>
                <li>API key access</li>
            </ul>
            <a href="/register" class="btn btn-outline" style="width:100%;justify-content:center">Get Started</a>
        </div>
        <div class="pricing-card featured">
            <div class="plan-name">Pro</div>
            <div class="plan-price">299 <span>KWD/yr</span></div>
            <ul class="plan-features">
                <li>50,000 credits/month</li>
                <li>+ qwen2.5-coder:14b &amp; mistral-small3.2:24b</li>
                <li>30 requests/minute</li>
                <li>Priority queue</li>
                <li>API key access</li>
            </ul>
            <a href="/register" class="btn btn-gold" style="width:100%;justify-content:center">Get Started</a>
        </div>
        <div class="pricing-card">
            <div class="plan-name">Enterprise</div>
            <div class="plan-price">999 <span>KWD/yr</span></div>
            <ul class="plan-features">
                <li>Unlimited credits</li>
                <li>All models incl. cloud (deepseek-v3, qwen3.5)</li>
                <li>60 requests/minute</li>
                <li>Team management</li>
                <li>Priority queue + dedicated support</li>
            </ul>
            <a href="/register" class="btn btn-outline" style="width:100%;justify-content:center">Contact Sales</a>
        </div>
    </div>
</section>
```

**Step 3: Commit**
```bash
git add resources/views/welcome.blade.php
git commit -m "fix: update homepage pricing to annual rates (99/299/999 KWD) with correct feature lists"
```

---

## Task 8: Deploy and verify

**Step 1: Push and deploy**
```bash
git push
ssh whm-server "cd ~/llm.resayil.io && git stash && git pull && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear"
```

**Step 2: Add Twilio credentials to server .env**
```bash
ssh whm-server "cd ~/llm.resayil.io && nano .env"
# Add: TWILIO_SID, TWILIO_TOKEN, TWILIO_FROM
```

**Step 3: Verify each feature**

Registration OTP flow:
- Visit `/register`
- Fill in name, email, phone (real mobile), password
- Click "Send Verification Code" — should see OTP input screen
- Check phone for SMS code
- Enter code → redirected to `/dashboard`
- Confirm `users` row has `phone_verified_at` set

Profile phone update:
- Visit `/profile`
- Confirm phone number displayed with "Verified" badge
- Enter a different phone number, click "Send Code"
- Enter SMS code, click "Confirm" — success alert shown, badge updates

Homepage pricing:
- Visit `/` — scroll to pricing section
- Confirm prices read 99 / 299 / 999 KWD/yr
- Confirm feature lists match actual tier access
