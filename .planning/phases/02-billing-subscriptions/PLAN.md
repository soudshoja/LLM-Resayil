---
phase: 02-billing-subscriptions
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - database/migrations/xxxx_create_topup_purchases_table.php
  - app/Models/TopupPurchase.php
  - app/Services/MyFatoorahService.php
  - app/Http/Controllers/Billing/PaymentController.php
  - app/Http/Controllers/Billing/WebhookController.php
  - routes/web.php
  - routes/api.php
  - app/Providers/AppServiceProvider.php
autonomous: true
requirements:
  - SUB-01
  - SUB-02
  - SUB-03
  - TOP-01
  - TOP-02
user_setup:
  - service: myfatoorah
    why: "Payment gateway integration for KWD transactions"
    env_vars:
      - name: MYFATOORAH_API_KEY
        source: "MyFatoorah Dashboard -> Settings -> API Keys"
      - name: MYFATOORAH_BASE_URL
        source: "MyFatoorah Dashboard -> Settings -> API Configuration"
        default: "https://apitest.myfatoorah.com"
    dashboard_config:
      - task: "Configure callback URLs"
        location: "MyFatoorah Dashboard -> Settings -> Callback URLs"
        instructions: "Add https://llm.resayil.io/billing/webhook for payment notifications"
must_haves:
  truths:
    - "User can view all three subscription tiers (Basic/Pro/Enterprise) with pricing and features"
    - "User can select a plan and is redirected to MyFatoorah for KWD payment"
    - "After payment, subscription is activated and user sees plan status and expiry on dashboard"
    - "User can view and purchase credit top-up packs (5K/15K/50K credits)"
    - "Credits are added to account immediately after payment confirmation"
  artifacts:
    - path: "database/migrations/xxxx_create_topup_purchases_table.php"
      provides: "Topup purchases table for credit packs"
      contains: "create_topup_purchases_table"
    - path: "app/Models/TopupPurchase.php"
      provides: "TopupPurchase model with user relationship"
      exports: ["user", "statusIsPaid"]
    - path: "app/Services/MyFatoorahService.php"
      provides: "MyFatoorah API integration service"
      exports: ["createInvoice", "verifyPayment", "getPaymentStatus"]
    - path: "app/Services/BillingService.php"
      provides: "Billing business logic service"
      exports: ["subscribeUser", "topupCredits", "getActiveSubscription"]
    - path: "app/Http/Controllers/Billing/PaymentController.php"
      provides: "Payment initiate and redirect endpoint"
      exports: ["initiateSubscriptionPayment", "initiateTopupPayment"]
    - path: "app/Http/Controllers/Billing/WebhookController.php"
      provides: "MyFatoorah webhook handler for payment notifications"
      exports: ["handleWebhook"]
    - path: "routes/web.php"
      provides: "Billing routes for web interface"
      contains: "billing/, payment/, webhook"
    - path: "routes/api.php"
      provides: "Billing API routes"
      contains: "billing/, topup/"
  key_links:
    - from: "app/Http/Controllers/Billing/PaymentController.php"
      to: "app/Services/MyFatoorahService.php"
      via: "MyFatoorahService::createInvoice()"
      pattern: "MyFatoorahService::createInvoice\\("
    - from: "app/Http/Controllers/Billing/WebhookController.php"
      to: "app/Services/BillingService.php"
      via: "BillingService::subscribeUser() or BillingService::topupCredits()"
      pattern: "BillingService::(subscribeUser|topupCredits)\\("
    - from: "database/migrations/xxxx_create_topup_purchases_table.php"
      to: "app/Models/TopupPurchase.php"
      via: "TopupPurchase model"
      pattern: "class TopupPurchase extends Model"
---

# Phase 2 Plan 01: Billing & Subscriptions Foundation

## Objective

Create the billing and subscription management system including MyFatoorah payment integration, subscription tier management, and credit top-up functionality.

Purpose: This plan establishes the core billing infrastructure that enables users to select subscription tiers, purchase credit packs, and complete payments via MyFatoorah. It provides the foundation for all subsequent billing-related features.

Output: Complete billing system with MyFatoorah integration, payment flow, webhook handling, and credit management.

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `database/migrations/xxxx_create_topup_purchases_table.php` | Topup purchases table for credit packs |
| `app/Models/TopupPurchase.php` | Eloquent model for topup purchases |
| `app/Services/MyFatoorahService.php` | MyFatoorah API integration service |
| `app/Services/BillingService.php` | Billing business logic service |
| `app/Http/Controllers/Billing/PaymentController.php` | Payment initiate and redirect endpoint |
| `app/Http/Controllers/Billing/WebhookController.php` | MyFatoorah webhook handler |
| `routes/web.php` | Web routes for billing pages |
| `routes/api.php` | API routes for billing operations |
| `app/Providers/AppServiceProvider.php` | Register billing service provider |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create topup purchases migration and model</name>
  <files>
    - database/migrations/xxxx_create_topup_purchases_table.php
    - app/Models/TopupPurchase.php
  </files>
  <action>
Create the topup purchases table and model:

1. **Migration** (xxxx_create_topup_purchases_table.php):
   - id (UUID primary key)
   - user_id (foreign to users)
   - credits (integer: 5000, 15000, 50000)
   - price (decimal: KWD currency)
   - status (enum: pending, completed, failed, cancelled)
   - transaction_id (nullable, MyFatoorah transaction ID)
   - payment_method (nullable)
   - paid_at (nullable timestamp)
   - created_at / updated_at

2. **Model** (app/Models/TopupPurchase.php):
   - Fillable: user_id, credits, price, status, transaction_id, payment_method, paid_at
   - Casts: credits to integer, price to decimal, paid_at to datetime
   - BelongsTo relationship: user()
   - Scopes: scopePending(), scopeCompleted(), scopeFailed()

All models use UUID primary keys.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan make:migration create_topup_purchases_table && php artisan make:model TopupPurchase
  </verify>
  <done>
  - Migration file created with all required fields
  - TopupPurchase model created with relationships
  - php artisan migrate:fresh --force succeeds
  </done>
</task>

<task type="auto">
  <name>Task 2: Create MyFatoorah API integration service</name>
  <files>
    - app/Services/MyFatoorahService.php
  </files>
  <action>
Create MyFatoorah service following the existing pattern from collect.resayil.io:

1. **Constructor**: Load MYFATOORAH_API_KEY and MYFATOORAH_BASE_URL from env
2. **createInvoice($data)**: Create invoice in MyFatoorah
   - Accepts: user_id, amount (KWD), currency_code (KWD), invoice_expiry, customer_name, customer_email, callback_url, error_callback_url
   - Returns: invoice_id, invoice_url, status
   - Uses POST /v2/createInvoice endpoint
3. **verifyPayment($invoiceId)**: Verify payment status
   - Uses GET /v2/getPaymentStatus endpoint
   - Returns: payment_status, transaction_id, amount, customer_name
4. **getPaymentStatus($paymentId)**: Get payment details
   - Uses GET /v2/getPayment endpoint
   - Returns: full payment details

Handle errors gracefully with descriptive messages.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\MyFatoorahService::class)));"
  </verify>
  <done>
  - MyFatoorahService class created
  - createInvoice() method implemented
  - verifyPayment() method implemented
  - getPaymentStatus() method implemented
  - Uses Laravel HTTP client for API calls
  </done>
</task>

<task type="auto">
  <name>Task 3: Create billing service for business logic</name>
  <files>
    - app/Services/BillingService.php
  </files>
  <action>
Create billing service with core business logic:

1. **subscribeUser($userId, $tier, $myfatoorahInvoiceId)**
   - Validates tier is basic/pro/enterprise
   - Creates subscription record with starts_at=now, ends_at=now + 30 days
   - Updates user subscription_tier and subscription_expiry
   - Sets subscription status to 'active'
   - Returns subscription object

2. **topupCredits($userId, $credits, $price, $myfatoorahInvoiceId)**
   - Validates credits is 5000, 15000, or 50000
   - Creates topup purchase record with status 'completed'
   - Adds credits to user.credits
   - Returns topup purchase object

3. **getActiveSubscription($userId)**
   - Returns user's active subscription
   - Includes tier, expiry, status

4. **cancelSubscription($userId)**
   - Sets subscription status to 'cancelled'
   - Sets user subscription_tier back to 'basic'

All methods use database transactions for atomicity.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\BillingService::class)));"
  </verify>
  <done>
  - BillingService class created
  - subscribeUser() creates subscription and updates user
  - topupCredits() adds credits to user account
  - getActiveSubscription() returns current subscription
  - cancelSubscription() handles subscription cancellation
  </done>
</task>

<task type="auto">
  <name>Task 4: Create payment controller for web interface</name>
  <files>
    - app/Http/Controllers/Billing/PaymentController.php
  </files>
  <action>
Create payment controller for initiating payments:

1. **constructor**: Inject MyFatoorahService and BillingService
2. **index()**: Display subscription tiers page
   - Basic: 2.5 KWD/month, 100 credits, local models
   - Pro: 7.5 KWD/month, 500 credits, local + cloud models
   - Enterprise: 25 KWD/month, 2000 credits, priority queue
3. **initiateSubscriptionPayment(Request $request)**
   - Validates tier (basic/pro/enterprise)
   - Creates invoice via MyFatoorahService
   - Redirects user to MyFatoorah invoice URL
   - Stores pending subscription in session
4. **initiateTopupPayment(Request $request)**
   - Validates credits pack (5000/15000/50000)
   - Calculates price based on pack
   - Creates invoice via MyFatoorahService
   - Redirects to MyFatoorah
   - Stores pending topup in session

Use Laravel session to track pending transactions.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --path=billing/payment
  </verify>
  <done>
  - PaymentController class created
  - index() shows subscription tiers
  - initiateSubscriptionPayment() creates MyFatoorah invoice
  - initiateTopupPayment() creates top-up invoice
  - Redirects to MyFatoorah for payment
  </done>
</task>

<task type="auto">
  <name>Task 5: Create webhook controller for payment notifications</name>
  <files>
    - app/Http/Controllers/Billing/WebhookController.php
  </files>
  <action>
Create webhook controller for MyFatoorah payment notifications:

1. **handleWebhook(Request $request)**
   - Validates signature/hmac from MyFatoorah
   - Verifies payment status via MyFatoorahService
   - Handles different statuses:
     - 'Paid': Calls BillingService::topupCredits() or subscribeUser()
     - 'Cancelled': Marks transaction as failed
     - 'Expired': Marks transaction as expired
   - Returns 200 OK to acknowledge receipt

2. **Security**:
   - Verify MyFatoorah HMAC signature
   - Check transaction not already processed
   - Log all webhook events

Use Laravel logging for debugging.
  </action>
  <verify>
  curl -X POST http://localhost:8000/billing/webhook -H "Content-Type: application/json" -d '{"InvoiceId":"test123","PaymentStatus":"Paid"}'
  </verify>
  <done>
  - WebhookController class created
  - Validates MyFatoorah HMAC signature
  - Processes paid invoices
  - Updates subscription or topup purchase status
  - Returns 200 OK for successful processing
  </done>
</task>

<task type="auto">
  <name>Task 6: Configure billing routes</name>
  <files>
    - routes/web.php
    - routes/api.php
  </files>
  <action>
Configure billing routes:

**routes/web.php**:
```php
// Billing routes (protected)
Route::middleware('auth')->group(function () {
    // Subscription plans page
    Route::get('/billing/plans', [PaymentController::class, 'index']);

    // Payment initiation
    Route::post('/billing/payment/subscription', [PaymentController::class, 'initiateSubscriptionPayment']);
    Route::post('/billing/payment/topup', [PaymentController::class, 'initiateTopupPayment']);

    // Webhook handler (public, but verified)
    Route::post('/billing/webhook', [WebhookController::class, 'handleWebhook']);
});

**routes/api.php**:
```php
Route::middleware(['auth:sanctum', 'api.key.auth'])->group(function () {
    // Get user's subscription status
    Route::get('/billing/subscription', [BillingController::class, 'getSubscription']);

    // Get available top-up packs
    Route::get('/billing/topup-packs', [BillingController::class, 'getTopupPacks']);

    // Get user's top-up history
    Route::get('/billing/topup-history', [BillingController::class, 'getTopupHistory']);
});
```

Create BillingController for API endpoints if not already created.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --path=billing
  </verify>
  <done>
  - All billing routes registered in web.php
  - All billing API routes registered in api.php
  - Webhook route accessible at /billing/webhook
  </done>
</task>

<task type="auto">
  <name>Task 7: Register billing service provider</name>
  <files>
    - app/Providers/AppServiceProvider.php
  </files>
  <action>
Register billing services in AppServiceProvider:

1. **register()** method:
   - Bind MyFatoorahService::class
   - Bind BillingService::class

2. **boot()** method:
   - No additional configuration needed

Ensure services are properly bound for dependency injection.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="app(\App\Services\MyFatoorahService::class);"
  </verify>
  <done>
  - MyFatoorahService registered in service container
  - BillingService registered in service container
  - Services available via dependency injection
  </done>
</task>

</tasks>

---

## Verification

### Phase 2 Plan 01 Complete When:
- [ ] Topup purchases table created and migration ran
- [ ] TopupPurchase model with relationships exists
- [ ] MyFatoorahService created with API integration
- [ ] BillingService created with business logic
- [ ] PaymentController created with payment initiation
- [ ] WebhookController created with payment handling
- [ ] All billing routes registered
- [ ] Services registered in AppServiceProvider

### Success Criteria from Phase 2:
1. User can view all three subscription tiers (Basic/Pro/Enterprise) with pricing and features
2. User can select a plan and is redirected to MyFatoorah for KWD payment
3. After payment, subscription is activated and user sees plan status and expiry on dashboard
4. User can view and purchase credit top-up packs (5K/15K/50K credits)
5. Credits are added to account immediately after payment confirmation

---

## Wave Structure

| Wave | Plan | Tasks | Notes |
|------|------|-------|-------|
| 1 | 02-01 | 7 | Foundation - database, services, controllers, routes |

---

## Output

After completion, create `.planning/phases/02-billing-subscriptions/02-billing-subscriptions-01-SUMMARY.md` documenting:

```markdown
# Phase 2 Plan 01 Summary

## What Was Built
- Topup purchases database table and model
- MyFatoorah API integration service
- Billing business logic service
- Payment controller for subscription and top-up
- Webhook controller for payment notifications
- Billing routes for web and API

## Files Created
- database/migrations/xxxx_create_topup_purchases_table.php
- app/Models/TopupPurchase.php
- app/Services/MyFatoorahService.php
- app/Services/BillingService.php
- app/Http/Controllers/Billing/PaymentController.php
- app/Http/Controllers/Billing/WebhookController.php
- app/Http/Controllers/Billing/BillingController.php
- routes/web.php (updated)
- routes/api.php (updated)
- app/Providers/AppServiceProvider.php (updated)

## Next Steps
- Phase 2 Plan 02: User dashboard UI for billing
- Phase 2 Plan 03: Testing and verification
```
