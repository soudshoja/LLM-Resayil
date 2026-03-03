# Billing Complete + Recurring Payments + WhatsApp Timeline

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Fix billing bugs, wire up MyFatoorah recurring trial flow, add WhatsApp trial timeline, and polish payment methods UI.

**Architecture:** 4 parallel agents — each agent owns a distinct slice with no shared state. Agent-A fixes bugs; Agent-B wires the trial→recurring flow; Agent-C adds WhatsApp scheduled jobs; Agent-D polishes payment methods UI. Merge order: A → B → C → D (or all into main if clean).

**Tech Stack:** Laravel 10, MyFatoorah v2 API (ExecutePayment / getPaymentStatus), Resayil WhatsApp API, DB queue, file cache.

---

## STATUS OVERVIEW (read before touching anything)

Already correct — DO NOT change:
- `BillingService.php` — prices, credits, credit costs all match spec
- `billing/plans.blade.php` — trial section, tier cards, top-up packs all correct
- `layouts/app.blade.php` line 84 — Billing nav link already present
- `MyFatoorahRecurringService.php` — recurring subscription methods exist
- `billing/payment-methods.blade.php` — base view exists

Bugs to fix:
1. `AdminController` methods return `redirect()->back()` but admin dashboard JS calls `fetch()` expecting JSON
2. `AdminController::setUserTier` validation excludes `starter`
3. API key modal has no "Create" button (JS `saveKey()` exists but never called)
4. `PaymentController::initiateSubscriptionPayment` validates tier as `basic,pro,enterprise` — missing `starter`
5. `PaymentController::initiateTopupPayment` validates credits as `5000,15000,50000` but packs are `500,1100,3000`
6. `MyFatoorahService::createInvoice` uses wrong endpoint (`/v2/createInvoice` instead of `/v2/ExecutePayment`)

New to build:
- Trial registration → card capture → recurring subscription flow
- Store `myfatoorah_payment_profile_id` on users
- WhatsApp Day-1/Day-6 notifications + Day-7 auto-charge job
- Payment Methods nav link + UI polish

---

## AGENT A — Fix AdminController + PaymentController Bugs

**Files:**
- Modify: `app/Http/Controllers/Admin/AdminController.php`
- Modify: `app/Http/Controllers/Billing/PaymentController.php`
- Modify: `resources/views/admin/dashboard.blade.php`

### Task A1: Fix AdminController to return JSON

The admin dashboard uses `fetch()` with `.then(response => response.json())`. All controller methods must return JSON, not redirects.

**Modify:** `app/Http/Controllers/Admin/AdminController.php`

Replace all four methods:

```php
public function createApiKeyForUser(Request $request, $userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $keyName = $request->input('key_name', 'Admin Created Key');
    $apiKey = bin2hex(random_bytes(32));
    $hashedKey = hash('sha256', $apiKey);

    ApiKeys::create([
        'user_id' => $user->id,
        'name' => $keyName,
        'key' => $hashedKey,
        'permissions' => 'read,write',
    ]);

    return response()->json(['success' => true, 'message' => $apiKey]);
}

public function setUserCredits(Request $request, $userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $credits = $request->input('credits');
    if ($credits === null || $credits < 0) {
        return response()->json(['success' => false, 'message' => 'Credits must be a non-negative number.'], 422);
    }

    $user->credits = (int) $credits;
    $user->save();

    return response()->json(['success' => true, 'message' => "Credits updated to {$credits}."]);
}

public function setUserTier(Request $request, $userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $tier = $request->input('tier');
    if (!in_array($tier, ['starter', 'basic', 'pro', 'enterprise'])) {
        return response()->json(['success' => false, 'message' => 'Invalid tier.'], 422);
    }

    $user->subscription_tier = $tier;
    $user->save();

    return response()->json(['success' => true, 'message' => "Tier updated to {$tier}."]);
}

public function setUserExpiry(Request $request, $userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    $expiry = $request->input('expiry');

    if (empty($expiry)) {
        $user->subscription_expiry = null;
    } else {
        try {
            $user->subscription_expiry = \Carbon\Carbon::parse($expiry);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid date format.'], 422);
        }
    }

    $user->save();
    return response()->json(['success' => true, 'message' => 'Expiry updated.']);
}
```

### Task A2: Add "Create Key" button to API key modal

**Modify:** `resources/views/admin/dashboard.blade.php`

Find the key modal footer:
```html
<div class="modal-footer">
    <button class="btn-save btn-save-secondary" onclick="closeModal('keyModal')">Close</button>
</div>
```

Replace with:
```html
<div class="modal-footer">
    <button class="btn-save btn-save-secondary" onclick="closeModal('keyModal')">Close</button>
    <button class="btn-save btn-save-primary" id="createKeyBtn" onclick="saveKey()">Create Key</button>
</div>
```

Also in the JS `saveKey()` function, after `document.getElementById('keyResult').textContent = data.message;` add:
```js
document.getElementById('createKeyBtn').style.display = 'none';
```

### Task A3: Fix PaymentController validation

**Modify:** `app/Http/Controllers/Billing/PaymentController.php`

Line ~52 — change validation:
```php
// FROM:
'tier' => 'required|in:basic,pro,enterprise',
// TO:
'tier' => 'required|in:starter,basic,pro,enterprise',
```

Line ~104 — change topup credits validation:
```php
// FROM:
'credits' => 'required|in:5000,15000,50000',
// TO:
'credits' => 'required|in:500,1100,3000',
```

### Task A4: Add starter to tier modal dropdown

**Modify:** `resources/views/admin/dashboard.blade.php`

The tier select already has starter — verify it's there. If missing, add:
```html
<option value="starter">Starter</option>
```
as first option before Basic.

### Task A5: Commit

```bash
git add app/Http/Controllers/Admin/AdminController.php \
        app/Http/Controllers/Billing/PaymentController.php \
        resources/views/admin/dashboard.blade.php
git commit -m "fix: admin controller returns JSON, fix payment validation"
```

---

## AGENT B — Trial Registration + MyFatoorah Recurring Flow

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_add_myfatoorah_fields_to_users.php`
- Modify: `app/Services/MyFatoorahService.php`
- Modify: `app/Http/Controllers/Billing/PaymentController.php`
- Modify: `app/Http/Controllers/Billing/WebhookController.php`
- Modify: `app/Models/User.php`

### Task B1: Migration — add MyFatoorah fields to users

```bash
php artisan make:migration add_myfatoorah_fields_to_users_table
```

Edit the generated file:

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('myfatoorah_payment_profile_id')->nullable()->after('trial_credits_remaining');
        $table->string('myfatoorah_subscription_id')->nullable()->after('myfatoorah_payment_profile_id');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['myfatoorah_payment_profile_id', 'myfatoorah_subscription_id']);
    });
}
```

Run: `php artisan migrate`

### Task B2: Add fillable fields to User model

**Modify:** `app/Models/User.php`

Add to `$fillable` array:
```php
'myfatoorah_payment_profile_id',
'myfatoorah_subscription_id',
```

### Task B3: Fix MyFatoorahService to use correct API endpoints

The current `createInvoice` uses `/v2/createInvoice` (old). Replace with `/v2/ExecutePayment`.

**Modify:** `app/Services/MyFatoorahService.php`

Replace `createInvoice()` method:

```php
/**
 * Create payment via ExecutePayment and return invoice URL.
 * PaymentMethodId: 0 = all methods, 1 = KNET, 2 = Visa/MC
 */
public function createInvoice(array $data): array
{
    $this->validateApiKey();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Content-Type' => 'application/json',
    ])->post($this->baseUrl . '/v2/ExecutePayment', [
        'PaymentMethodId'   => $data['payment_method_id'] ?? 0,
        'InvoiceValue'      => $data['amount'],
        'DisplayCurrencyIso'=> 'KWD',
        'CallBackUrl'       => $data['callback_url'],
        'ErrorUrl'          => $data['error_callback_url'],
        'Language'          => 'en',
        'CustomerName'      => $data['customer_name'] ?? 'Customer',
        'CustomerEmail'     => $data['customer_email'] ?? '',
        'CustomerReference' => $data['user_id'] ?? '',
        'InvoiceItems'      => [[
            'ItemName'  => $data['item_name'] ?? 'LLM Resayil Subscription',
            'Quantity'  => 1,
            'UnitPrice' => $data['amount'],
        ]],
        'UserDefinedField'  => json_encode([
            'user_id' => $data['user_id'] ?? '',
            'type'    => $data['type'] ?? 'subscription',
            'tier'    => $data['tier'] ?? '',
        ]),
    ]);

    if ($response->failed()) {
        Log::error('MyFatoorah ExecutePayment failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        throw new \Exception('Failed to create payment: ' . $response->status());
    }

    $result = $response->json();

    if (!($result['IsSuccess'] ?? false)) {
        throw new \Exception('MyFatoorah error: ' . ($result['Message'] ?? 'Unknown'));
    }

    return [
        'invoice_id'  => $result['Data']['InvoiceId'] ?? null,
        'invoice_url' => $result['Data']['PaymentURL'] ?? null,
        'status'      => 'pending',
    ];
}

/**
 * Verify payment status using PaymentId (from callback querystring).
 */
public function verifyPayment(string $paymentId): array
{
    $this->validateApiKey();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Content-Type'  => 'application/json',
    ])->post($this->baseUrl . '/v2/getPaymentStatus', [
        'Key'     => $paymentId,
        'KeyType' => 'PaymentId',
    ]);

    if ($response->failed()) {
        throw new \Exception('Failed to verify payment: ' . $response->status());
    }

    $result = $response->json();

    return [
        'payment_status'  => $result['Data']['InvoiceStatus'] ?? 'unknown',
        'transaction_id'  => $result['Data']['InvoiceTransactions'][0]['PaymentId'] ?? null,
        'amount'          => $result['Data']['InvoiceValue'] ?? 0,
        'customer_name'   => $result['Data']['CustomerName'] ?? '',
        'customer_email'  => $result['Data']['CustomerEmail'] ?? '',
    ];
}
```

### Task B4: Add trial payment initiation to PaymentController

**Modify:** `app/Http/Controllers/Billing/PaymentController.php`

Add `initiateTrialPayment()` method:

```php
/**
 * Start free trial — redirect to MyFatoorah to capture card.
 * We charge 0 KWD by using the minimum-allowed 0.100 KWD
 * as a card verification fee, then refund or credit.
 * On callback success: set trial_started_at, grant 1000 credits, send WhatsApp.
 */
public function initiateTrialPayment(Request $request)
{
    $user = Auth::user();

    if ($user->trial_started_at) {
        return redirect()->route('billing.plans')->with('error', 'Trial already activated.');
    }

    try {
        $callbackUrl = route('billing.trial.callback');
        $errorCallbackUrl = url('/billing/plans?error=payment_failed');

        $invoice = $this->myfatoorahService->createInvoice([
            'user_id'              => $user->id,
            'amount'               => 0.100,  // Minimum KWD for card verification
            'customer_name'        => $user->name,
            'customer_email'       => $user->email,
            'item_name'            => 'LLM Resayil — Card Verification (Trial)',
            'type'                 => 'trial',
            'tier'                 => 'starter',
            'callback_url'         => $callbackUrl,
            'error_callback_url'   => $errorCallbackUrl,
        ]);

        Session::put('pending_trial', [
            'user_id'    => $user->id,
            'invoice_id' => $invoice['invoice_id'],
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect()->away($invoice['invoice_url']);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to initiate trial: ' . $e->getMessage());
    }
}

/**
 * Trial payment callback — MyFatoorah redirects here after card entry.
 */
public function handleTrialCallback(Request $request)
{
    $paymentId = $request->get('paymentId');

    if (!$paymentId) {
        return redirect()->route('billing.plans')->with('error', 'Invalid callback. No payment ID.');
    }

    try {
        $status = $this->myfatoorahService->verifyPayment($paymentId);

        if ($status['payment_status'] !== 'Paid') {
            return redirect()->route('billing.plans')->with('error', 'Payment verification failed.');
        }

        $user = Auth::user();
        $pending = Session::get('pending_trial');

        if (!$pending || $pending['user_id'] !== $user->id) {
            return redirect()->route('billing.plans')->with('error', 'Session mismatch. Try again.');
        }

        Session::forget('pending_trial');

        // Activate trial
        $user->trial_started_at = now();
        $user->subscription_tier = 'starter';
        $user->subscription_expiry = now()->addDays(7);
        $user->myfatoorah_payment_profile_id = $status['transaction_id']; // store for recurring
        $user->save();

        // Grant 1000 trial credits
        $this->billingService->grantTrialCredits($user->id);

        // Queue Day-1 WhatsApp welcome
        dispatch(new \App\Jobs\SendTrialWelcome($user->id))->delay(now()->addMinutes(1));

        return redirect()->route('billing.plans')
            ->with('success', 'Free trial activated! 1,000 credits added. Welcome to LLM Resayil.');

    } catch (\Exception $e) {
        return redirect()->route('billing.plans')->with('error', 'Trial activation failed: ' . $e->getMessage());
    }
}
```

### Task B5: Register trial routes

**Modify:** `routes/web.php`

Inside the `auth` billing group, add:
```php
Route::post('/billing/trial/start', [PaymentController::class, 'initiateTrialPayment'])->name('billing.trial.start');
Route::get('/billing/trial/callback', [PaymentController::class, 'handleTrialCallback'])->name('billing.trial.callback');
```

### Task B6: Update billing/plans.blade.php "Start Free Trial" button

Change the trial form `action`:
```html
{{-- FROM: --}}
<form method="POST" action="/billing/payment/subscription" style="width: 100%;">
    @csrf
    <input type="hidden" name="tier" value="starter">
    <button type="submit" class="plan-cta plan-cta-gold" style="width: 100%;">Start Free Trial</button>
</form>

{{-- TO: --}}
<form method="POST" action="{{ route('billing.trial.start') }}" style="width: 100%;">
    @csrf
    <button type="submit" class="plan-cta plan-cta-gold" style="width: 100%;">Start Free Trial — Card Required</button>
</form>
```

### Task B7: Commit

```bash
git add database/migrations/ app/Services/MyFatoorahService.php \
        app/Http/Controllers/Billing/PaymentController.php \
        app/Models/User.php routes/web.php \
        resources/views/billing/plans.blade.php
git commit -m "feat: trial registration with MyFatoorah card capture + recurring flow"
```

---

## AGENT C — WhatsApp Trial Timeline Jobs

**Files:**
- Create: `app/Jobs/SendTrialWelcome.php`
- Create: `app/Jobs/SendTrialReminder.php`
- Create: `app/Console/Commands/ProcessTrialCharges.php`
- Modify: `app/Console/Kernel.php`

### Task C1: Create SendTrialWelcome job

**Create:** `app/Jobs/SendTrialWelcome.php`

```php
<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrialWelcome implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $userId) {}

    public function handle(WhatsAppNotificationService $whatsapp): void
    {
        $user = User::find($this->userId);
        if (!$user || !$user->phone) return;

        $whatsapp->send(
            $user->phone,
            "مرحباً {$user->name}! 🎉\n\n" .
            "تم تفعيل تجربتك المجانية في LLM Resayil.\n" .
            "✅ 1,000 رصيد جاهز للاستخدام\n" .
            "✅ وصول كامل لنماذج Starter لمدة 7 أيام\n\n" .
            "Welcome {$user->name}! Your 7-day free trial is active.\n" .
            "1,000 credits added. API endpoint: https://llm.resayil.io/api/v1\n\n" .
            "Upgrade anytime at: https://llm.resayil.io/billing/plans"
        );
    }
}
```

### Task C2: Create SendTrialReminder job

**Create:** `app/Jobs/SendTrialReminder.php`

```php
<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrialReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $userId) {}

    public function handle(WhatsAppNotificationService $whatsapp): void
    {
        $user = User::find($this->userId);
        if (!$user || !$user->phone) return;

        $whatsapp->send(
            $user->phone,
            "⏰ تذكير: يتبقى يوم واحد فقط على انتهاء تجربتك المجانية!\n\n" .
            "⏰ Reminder: 1 day left in your free trial!\n\n" .
            "سيتم تحصيل 15 KWD تلقائياً للاشتراك في خطة Starter.\n" .
            "Auto-charge of 15 KWD for Starter plan tomorrow.\n\n" .
            "لإلغاء أو الترقية: https://llm.resayil.io/billing/plans\n" .
            "Cancel or upgrade: https://llm.resayil.io/billing/plans"
        );
    }
}
```

### Task C3: Create ProcessTrialCharges command

**Create:** `app/Console/Commands/ProcessTrialCharges.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BillingService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessTrialCharges extends Command
{
    protected $signature = 'trial:process-charges';
    protected $description = 'Auto-charge users whose 7-day trial has ended';

    public function handle(BillingService $billing, WhatsAppNotificationService $whatsapp): void
    {
        // Find users whose trial ended today (trial_started_at = 7 days ago)
        $expiredUsers = User::whereNotNull('trial_started_at')
            ->whereNull('myfatoorah_subscription_id') // not yet auto-charged
            ->where('trial_started_at', '<=', now()->subDays(7))
            ->get();

        foreach ($expiredUsers as $user) {
            try {
                // Subscribe user to Starter via BillingService
                $subscription = $billing->subscribeUser($user->id, 'starter');

                // Update user: clear trial, set active subscription
                $user->subscription_tier = 'starter';
                $user->subscription_expiry = now()->addDays(30);
                $user->myfatoorah_subscription_id = 'auto-billed-' . $subscription->id;
                $user->save();

                // WhatsApp: subscription activated
                if ($user->phone) {
                    $whatsapp->send(
                        $user->phone,
                        "✅ تم تفعيل اشتراك Starter الخاص بك — 15 KWD/شهر.\n" .
                        "✅ Your Starter subscription is now active — 15 KWD/month.\n" .
                        "3,000 رصيد جديد متاح. New credits: 1,000 added.\n\n" .
                        "Manage: https://llm.resayil.io/billing/plans"
                    );
                }

                $this->info("Activated Starter for user: {$user->email}");
                Log::info("Trial auto-charged: {$user->email}");

            } catch (\Exception $e) {
                $this->error("Failed for {$user->email}: " . $e->getMessage());
                Log::error("Trial auto-charge failed: {$user->email}", ['error' => $e->getMessage()]);
            }
        }

        $this->info("Processed {$expiredUsers->count()} expired trials.");
    }
}
```

### Task C4: Create SendTrialReminders command

**Create:** `app/Console/Commands/SendTrialReminders.php`

```php
<?php

namespace App\Console\Commands;

use App\Jobs\SendTrialReminder;
use App\Models\User;
use Illuminate\Console\Command;

class SendTrialReminders extends Command
{
    protected $signature = 'trial:send-reminders';
    protected $description = 'Send WhatsApp reminder to users whose trial expires in 1 day';

    public function handle(): void
    {
        // Users whose trial expires tomorrow (started 6 days ago)
        $users = User::whereNotNull('trial_started_at')
            ->whereNull('myfatoorah_subscription_id')
            ->whereBetween('trial_started_at', [
                now()->subDays(7),
                now()->subDays(6),
            ])
            ->get();

        foreach ($users as $user) {
            dispatch(new SendTrialReminder($user->id));
            $this->info("Reminder queued for: {$user->email}");
        }
    }
}
```

### Task C5: Register commands in Kernel.php

**Modify:** `app/Console/Kernel.php`

In the `schedule()` method, add:

```php
// Day-6 trial reminder — runs daily at 10:00 AM
$schedule->command('trial:send-reminders')->dailyAt('10:00');

// Day-7 trial auto-charge — runs daily at 09:00 AM
$schedule->command('trial:process-charges')->dailyAt('09:00');
```

Also add to `$commands` array:
```php
\App\Console\Commands\ProcessTrialCharges::class,
\App\Console\Commands\SendTrialReminders::class,
```

### Task C6: Verify Kernel schedule is registered in cron

The cron must call `php artisan schedule:run` every minute. This should already be in cPanel cron. Verify with:
```bash
php artisan schedule:list
```
Expected output shows both `trial:send-reminders` and `trial:process-charges`.

### Task C7: Commit

```bash
git add app/Jobs/SendTrialWelcome.php app/Jobs/SendTrialReminder.php \
        app/Console/Commands/ProcessTrialCharges.php \
        app/Console/Commands/SendTrialReminders.php \
        app/Console/Kernel.php
git commit -m "feat: WhatsApp trial timeline — Day 1 welcome, Day 6 reminder, Day 7 auto-charge"
```

---

## AGENT D — Payment Methods UI + Nav Polish

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/billing/payment-methods.blade.php`

### Task D1: Add Payment Methods nav link

**Modify:** `resources/views/layouts/app.blade.php`

After the Billing link (line ~84), add:
```html
<a href="/billing/payment-methods">Payment Methods</a>
```

Result should be:
```html
<a href="/billing/plans" style="color:var(--gold)">Billing</a>
<a href="/billing/payment-methods">Payment Methods</a>
```

### Task D2: Polish payment methods page with proper card logos

**Modify:** `resources/views/billing/payment-methods.blade.php`

Replace the `add-method-section` form with a version that clearly shows KNET/Visa/Mastercard support and explains the flow:

```html
<div class="add-method-section">
    <h2 class="add-method-title">Add Payment Method</h2>
    <p class="text-secondary text-sm" style="margin-bottom: 1.5rem;">
        You'll be redirected to our secure payment page. We accept:
    </p>
    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <div style="background: #0055a5; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem;">KNET</div>
        <div style="background: #1a1f71; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem; font-style: italic;">VISA</div>
        <div style="background: #eb001b; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem;">Mastercard</div>
    </div>
    <form method="POST" action="{{ route('billing.payment-methods.store') }}">
        @csrf
        <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
        <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
        <button type="submit" class="btn-add btn-add-primary">
            Add Card via Secure Checkout →
        </button>
    </form>
    <p class="text-xs text-muted" style="margin-top: 0.75rem;">
        A 0.100 KWD card verification charge will be made and credited back to your account.
    </p>
</div>
```

### Task D3: Improve "no payment methods" empty state

Replace the empty-state block with:
```html
<div class="card" style="text-align: center; padding: 3rem;">
    <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 1.5rem;">
        <div style="background: #0055a5; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px;">KNET</div>
        <div style="background: #1a1f71; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px; font-style: italic;">VISA</div>
        <div style="background: #eb001b; color: white; font-weight: 700; padding: 0.5rem 1.25rem; border-radius: 8px;">Mastercard</div>
    </div>
    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">No payment methods saved</h2>
    <p class="text-secondary text-sm" style="margin-bottom: 1.5rem;">
        Add a card below to enable recurring subscriptions and auto-billing.
    </p>
</div>
```

### Task D4: Add current subscription status to plans page

**Modify:** `resources/views/billing/plans.blade.php`

At the top of `@section('content')` before `<main>`, add:
```html
@if(auth()->user()->trial_started_at && !auth()->user()->myfatoorah_subscription_id)
    @php $trialExpiry = auth()->user()->trial_started_at->addDays(7); @endphp
    <div style="background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); border-radius: 8px; padding: 0.75rem 1rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: #6ee7b7;">
        ✅ Free trial active — expires {{ $trialExpiry->format('d M Y') }}
        ({{ $trialExpiry->diffForHumans() }})
    </div>
@elseif(auth()->user()->subscription_tier !== 'starter' || auth()->user()->subscription_expiry)
    <div style="background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; padding: 0.75rem 1rem; max-width: 1200px; margin: 1rem auto; font-size: 0.875rem; color: var(--gold);">
        ⚡ Current plan: {{ ucfirst(auth()->user()->subscription_tier) }}
        @if(auth()->user()->subscription_expiry)
            — renews {{ auth()->user()->subscription_expiry->format('d M Y') }}
        @endif
    </div>
@endif
```

### Task D5: Commit

```bash
git add resources/views/layouts/app.blade.php \
        resources/views/billing/payment-methods.blade.php \
        resources/views/billing/plans.blade.php
git commit -m "feat: payment methods UI polish, KNET/Visa/MC logos, nav link, trial status banner"
```

---

## STATE.md Update (after all agents complete)

Update `.planning/STATE.md`:
- Add Plan 06 to Phase 7 complete log
- Update Last Updated date
- All requirements: BILL-11, BILL-12, RECUR-04 through RECUR-06, TRIAL-06 through TRIAL-08 complete

---

## Deployment Checklist

Run on production server (`whm-server`, path `~/llm.resayil.io`):

```bash
git pull
/opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force
/opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear
/opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear
/opt/cpanel/ea-php82/root/usr/bin/php artisan view:clear
```

Verify `php artisan schedule:list` shows both trial commands.
Set `MYFATOORAH_BASE_URL=https://api.myfatoorah.com` in production `.env` (remove test URL).
