# Billing & Admin Enhancement Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement a complete subscription-based billing system with trial, recurring payments via MyFatoorah, enhanced admin user management with action buttons, and new pricing structure (Starter/Basic/Pro tiers with credit costs by model size).

**Architecture:**
- Add trial subscription system with 7-day free trial, auto-billing to Starter tier
- MyFatoorah recurring payment gateway for credit card subscriptions
- Admin dashboard enhanced with inline action buttons for user management (set credits, tier, expiry, create API key)
- New pricing: 3 monthly tiers (Starter 15KWD, Basic 25KWD, Pro 45KWD) with tier-specific API key allowances
- Credit costs based on model size (Small/Medium/Large) with local vs cloud multipliers

**Tech Stack:** Laravel 11, Blade templates, MyFatoorah API, MySQL

---

## Context: Current State

**Existing Billing Infrastructure:**
- `BillingService` - handles subscription, top-up, tier management
- `MyFatoorahService` - invoice creation, payment verification
- `PaymentController` - initiates subscription and top-up payments
- `WebhookController` - handles MyFatoorah payment notifications
- `Subscriptions` model - tracks user subscriptions
- `TopupPurchase` model - tracks one-time credit purchases

**Current Pricing (OUTDATED - needs update):**
- Tiers: basic (99 KWD/year), pro (299 KWD/year), enterprise (999 KWD/year)
- Credits: basic=100, pro=500, enterprise=2000
- Top-ups: 5k/5 KWD, 15k/12 KWD, 50k/35 KWD

**Required New Pricing:**
- Subscription Tiers (Monthly):
  - Starter: 15 KWD/month - 1,000 credits + 1 API key
  - Basic: 25 KWD/month - 3,000 credits + 1 API key
  - Pro: 45 KWD/month - 10,000 credits + 2 API keys
- Credit Costs (per 1000 tokens):
  - Small (3-14B): 0.5 (local) / 1.0 (cloud)
  - Medium (20-30B): 1.5 (local) / 2.5 (cloud)
  - Large (70B+): 3.0 (local) / 3.5 (cloud)
- Additional API Keys (monthly add-on):
  - Starter: 1st FREE, 2nd=5 KWD, 3rd=10 KWD
  - Basic: 1st FREE, 2nd=3 KWD, 3rd=7 KWD
  - Pro: 1st-2nd FREE, 3rd=2 KWD, 4th=5 KWD
- Credit Top-ups: 5k/5 KWD (+0%), 10k/10 KWD (+10%), 25k/25 KWD (+20%)

**WhatsApp Automation Timeline:**
- Day 1: Welcome message after registration
- Day 6: "1 day left in trial" notification
- Day 7: Auto-charge 15 KWD → Starter plan activated
- User can upgrade anytime to Basic/Pro

---

## Implementation Tasks

### Task 1: Create TrialSubscription Service

**Files:**
- Create: `app/Services/TrialSubscriptionService.php`

**Step 1: Write the service class**

Create `app/Services/TrialSubscriptionService.php` with methods:
- `startTrial($userId)` - creates trial subscription, grants 1000 credits
- `checkTrialExpiry($userId)` - checks if trial is about to expire ( Day 6)
- `upgradeToPaid($userId, $tier)` - upgrades from trial to paid subscription
- `cancelTrial($userId)` - cancels trial and resets to free tier

**Step 2: Run validation**
```bash
php artisan tinker --execute="app(\App\Services\TrialSubscriptionService::class);"
```
Expected: No errors

**Step 3: Commit**
```bash
git add app/Services/TrialSubscriptionService.php
git commit -m "feat: add TrialSubscriptionService for free trial handling"
```

---

### Task 2: Update BillingService with New Pricing

**Files:**
- Modify: `app/Services/BillingService.php`

**Step 1: Update tier pricing and credits**

Replace existing arrays with:
```php
protected array $tierPricing = [
    'starter' => 15.0,
    'basic' => 25.0,
    'pro' => 45.0,
];

protected array $tierCredits = [
    'starter' => 1000,
    'basic' => 3000,
    'pro' => 10000,
];
```

**Step 2: Add new methods**

```php
public function getTierCredits(string $tier): int
public function getTierApiKeys(string $tier): int
public function getAdditionalApiKeyCost(string $tier, int $keyNumber): float
public function getCreditCostPer1000(string $tier, string $modelSize, bool $isCloud): float
public function getCreditTopupPrice(int $credits): float
public function getCreditTopupBonus(int $credits): int
```

**Step 3: Update existing methods to use new arrays**

**Step 4: Run validation**
```bash
php artisan tinker --execute="app(\App\Services\BillingService::class)->getSubscriptionTiers();"
```

**Step 5: Commit**
```bash
git add app/Services/BillingService.php
git commit -m "refactor: update BillingService with new pricing structure"
```

---

### Task 3: Create MyFatoorah Recurring Payment Gateway

**Files:**
- Create: `app/Services/MyFatoorahRecurringService.php`

**Step 1: Write recurring payment service**

Create `app/Services/MyFatoorahRecurringService.php`:
- `createRecurringSubscription($userId, $tier, $paymentMethodId)` - creates recurring subscription
- `suspendSubscription($subscriptionId)` - suspends recurring payment
- `resumeSubscription($subscriptionId)` - re-enables recurring payment
- `getSubscriptions($userId)` - lists user's recurring subscriptions

**Step 2: Implement MyFatoorah payment profile creation**

Use MyFatoorah's payment profiles API for recurring billing with credit cards.

**Step 3: Commit**
```bash
git add app/Services/MyFatoorahRecurringService.php
git commit -m "feat: add MyFatoorahRecurringService for recurring payment gateway"
```

---

### Task 4: Create Payment Methods Page

**Files:**
- Create: `resources/views/billing/payment-methods.blade.php`
- Create: `app/Http/Controllers/Billing/PaymentMethodsController.php`

**Step 1: Create controller**

```php
// app/Http/Controllers/Billing/PaymentMethodsController.php
public function index() - show saved payment methods
public function store() - add new payment method via MyFatoorah
public function destroy($id) - remove payment method
```

**Step 2: Create Blade view**

`resources/views/billing/payment-methods.blade.php`:
- Table of saved payment methods (KNET, Visa, Mastercard)
- "Add Payment Method" button
- Card icon indicators for each method type
- Delete confirmation modal

**Step 3: Add routes**

Add to `routes/web.php`:
```php
Route::get('/billing/payment-methods', [PaymentMethodsController::class, 'index']);
Route::post('/billing/payment-methods', [PaymentMethodsController::class, 'store']);
Route::delete('/billing/payment-methods/{id}', [PaymentMethodsController::class, 'destroy']);
```

**Step 4: Commit**
```bash
git add resources/views/billing/payment-methods.blade.php app/Http/Controllers/Billing/PaymentMethodsController.php routes/web.php
git commit -m "feat: add payment methods page for managing saved cards"
```

---

### Task 5: Update Billing Service to Handle Trial Credits & Auto-Billing

**Files:**
- Modify: `app/Services/BillingService.php`
- Create: `database/migrations/xxxx_xx_xx_add_trial_fields_to_users_table.php`

**Step 1: Add trial fields migration**

```bash
php artisan make:migration add_trial_fields_to_users_table --table=users
```

Add columns:
- `trial_started_at` - timestamp
- `trial_credits_granted` - boolean (default false)
- `trial_credits_remaining` - integer (default 1000)
- `auto_billed` - boolean (default false)

**Step 2: Update BillingService**

Add trial-specific methods:
- `grantTrialCredits($userId)` - grants 1000 credits on trial start
- `processTrialExpiry($userId)` - auto-bills user when trial ends
- `canAccessModel($userId, $modelSize)` - checks if user can access model during trial

**Step 3: Commit**
```bash
git add database/migrations/xxxx_xx_xx_add_trial_fields_to_users_table.php app/Services/BillingService.php
git commit -m "feat: add trial credits and auto-billing to BillingService"
```

---

### Task 6: Create WhatsApp Trial Timeline Service

**Files:**
- Create: `app/Services/WhatsAppTrialTimelineService.php`

**Step 1: Write the service**

```php
// app/Services/WhatsAppTrialTimelineService.php
public function sendWelcomeMessage($userId) - Day 1
public function sendExpiryReminder($userId) - Day 6 (1 day left)
public function processExpiry($userId) - Day 7 (auto-charge)
```

**Step 2: Integrate with queue**

Add jobs for WhatsApp messages to process asynchronously.

**Step 3: Commit**
```bash
git add app/Services/WhatsAppTrialTimelineService.php
git commit -m "feat: add WhatsApp trial timeline service"
```

---

### Task 7: Update Pricing Blade View with New Structure

**Files:**
- Modify: `resources/views/billing/plans.blade.php`

**Step 1: Update header**

Change "Choose Your Plan" to "Subscription Plans with Free Trial"

**Step 2: Add Trial Card**

Add a new "Free Trial" card showing:
- 7 days free access
- Starter tier features
- Small models only (3-14B)
- 1,000 credits
- 1 API key
- Credit card signup
- Auto-billing after trial

**Step 3: Update Subscription Tiers**

Replace existing tiers with:
- **Starter** - 15 KWD/month - 1,000 credits + 1 API key
- **Basic** - 25 KWD/month - 3,000 credits + 1 API key
- **Pro** - 45 KWD/month - 10,000 credits + 2 API keys

**Step 4: Update Top-Up Cards**

Update with new prices:
- 5 KWD → 500 credits
- 10 KWD → 1,100 credits (+10%)
- 25 KWD → 3,000 credits (+20%)

**Step 5: Update CTA buttons**

Change to: "Start Free Trial" or "Subscribe to [Tier]"

**Step 6: Commit**
```bash
git add resources/views/billing/plans.blade.php
git commit -m "feat: update pricing view with new tiers and trial"
```

---

### Task 8: Update Admin Dashboard with User Action Buttons

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`

**Step 1: Add action buttons column**

Add a new column to the users table with buttons:
- **Set Credits** - modal with credits input
- **Set Tier** - dropdown (Starter, Basic, Pro)
- **Set Expiry** - date picker
- **Create API Key** - generates and shows key

**Step 2: Create inline modals**

Add Bootstrap-style modals for each action:
- Credits input (number, min 0)
- Tier select (Starter, Basic, Pro)
- Expiry date picker
- API key generation (auto-generate, show once, copy to clipboard)

**Step 3: Add CSS for action buttons**

```css
.action-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
```

**Step 4: Add JavaScript**

Add JS to handle button clicks, show modals, and submit to admin routes.

**Step 5: Commit**
```bash
git add resources/views/admin/dashboard.blade.php
git commit -m "feat: add user action buttons to admin dashboard"
```

---

### Task 9: Add Nav Link to /billing/plans

**Files:**
- Find: Navigation component/layout file

**Step 1: Add Billing link**

Add a navigation link to `/billing/plans` that's visible to authenticated users.

**Step 2: Verify**

Check navigation appears in header and shows correct billing page.

**Step 3: Commit**
```bash
git add resources/views/layouts/*.blade.php
git commit -m "feat: add billing nav link to navigation"
```

---

### Task 10: Update User Model for New Fields

**Files:**
- Modify: `app/Models/User.php`

**Step 1: Add fillable fields**

```php
' trial_started_at',
'trial_credits_remaining',
'auto_billed',
```

**Step 2: Add relationship methods**

```php
public function trialSubscription()
public function paymentMethods()
```

**Step 3: Commit**
```bash
git add app/Models/User.php
git commit -m "refactor: add trial fields to User model"
```

---

### Task 11: Create Subscription Model Updates

**Files:**
- Modify: `app/Models/Subscriptions.php`

**Step 1: Add trial subscription support**

Update the model to support trial status:
```php
protected $fillable = [
    ...
    'is_trial',
    'trial_started_at',
    'trial_expiry_at',
    'auto_renew',
];
```

**Step 2: Add scopes**

```php
public function scopeTrial($query)
public function scopeActive($query)
public function scopeRecurring($query)
```

**Step 3: Commit**
```bash
git add app/Models/Subscriptions.php
git commit -m "feat: add trial subscription support to Subscriptions model"
```

---

### Task 12: Run Verification Tests

**Files:**
- Run database migration
- Test pricing calculations

**Step 1: Run migrations**

```bash
php artisan migrate --pretend
php artisan migrate
```

**Step 2: Test BillingService**

```bash
php artisan tinker
> $bs = app(\App\Services\BillingService::class);
> $bs->getSubscriptionTiers();
> $bs->getCreditTopupPrice(10000);
> $bs->getCreditCostPer1000('starter', 'small', false);
```

**Step 3: Test admin routes**

```bash
php artisan route:list | grep admin
php artisan route:list | grep billing
```

**Step 4: Commit**
```bash
git add -A
git commit -m "refactor: run verification tests and fix any issues"
```

---

## Summary of New Features

| Feature | Description |
|---------|-------------|
| Free Trial | 7-day trial with Starter tier features, 1000 credits, small models only |
| Auto-Billing | Trial expires Day 7 → auto-charge 15 KWD → Starter plan |
| WhatsApp Timeline | Day 1 welcome, Day 6 reminder, Day 7 auto-charge |
| Recurring Gateway | MyFatoorah payment profiles for credit card subscriptions |
| Payment Methods | Dedicated page to manage saved cards (KNET, Visa, Mastercard) |
| New Tiers | Starter (15KWD), Basic (25KWD), Pro (45KWD) monthly |
| Model-Based Costs | Credit costs based on model size (Small/Medium/Large) + local/cloud |
| API Key Add-ons | Tier-based additional key pricing |
| Credit Top-ups | Fixed options with bonuses (10% for 10k, 20% for 25k) |
| Admin Actions | Inline buttons for credits, tier, expiry, API key |

---

## Execution Handoff

**Plan complete and saved to `docs/plans/2026-03-02-billing-admin-enhancements.md`. Two execution options:**

**1. Subagent-Driven (this session)** - I dispatch fresh subagent per task, review between tasks, fast iteration

**2. Parallel Session (separate)** - Open new session with executing-plans, batch execution with checkpoints

**Which approach?**
