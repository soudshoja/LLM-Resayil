---
status: fixing
trigger: "Top-up credit button on https://llm.resayil.io/billing/plans does not work on live/prod"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T12:00:00Z
---

## Current Focus

hypothesis: CONFIRMED — two bugs: (1) /billing/plans was 500 for new users before merge; (2) credits never added after payment because no dedicated topup callback route exists
test: Implementing fix — add handleTopupCallback method + route + create pending TopupPurchase record
expecting: After fix: topup button → payment → credits added correctly
next_action: Implement fix in PaymentController and routes/web.php

## Symptoms

expected: Clicking a top-up credit button initiates a MyFatoorah KNET payment flow and credits are added after payment
actual: /billing/plans was returning 500 for new users (before merge); after payment, credits are never added
errors: HTTP 500 on /billing/plans (confirmed in access logs Mar 2-3); credits silently not added after payment
reproduction: Visit https://llm.resayil.io/billing/plans while logged in, click any top-up button
started: Reported after dev→prod merge

## Eliminated

- hypothesis: JS modal broken
  evidence: Modal opens correctly, payment methods (KNET + VISA/MASTER) are cached and shown
  timestamp: 2026-03-05

- hypothesis: Missing env vars
  evidence: MYFATOORAH_API_KEY and MYFATOORAH_BASE_URL are correctly set on prod
  timestamp: 2026-03-05

- hypothesis: Wrong API endpoint
  evidence: getAvailablePaymentMethods uses /v2/InitiatePayment (correct); cache has valid data
  timestamp: 2026-03-05

- hypothesis: Form not submitting
  evidence: Access log shows multiple POST /billing/payment/topup requests on Mar 2
  timestamp: 2026-03-05

## Evidence

- timestamp: 2026-03-05
  checked: prod access logs
  found: /billing/plans returned HTTP 500 (31 bytes) on Mar 2 twice and Mar 3 once for real users
  implication: Page was crashing for some users before the dev→prod merge on Mar 5

- timestamp: 2026-03-05
  checked: prod access logs for POST /billing/payment/topup
  found: Multiple 302/169-byte redirects back (failures) on Mar 2, then one 302/243-byte success at 10:48:23
  implication: Topup button form DOES submit; 169-byte = redirect-back with error; 243-byte = redirect to MyFatoorah

- timestamp: 2026-03-05
  checked: PaymentController::initiateTopupPayment()
  found: Stores pending_topup in SESSION only, does NOT create TopupPurchase DB record
  implication: Webhook handler (handleWebhook) looks for TopupPurchase by transaction_id in DB — finds nothing — credits never added

- timestamp: 2026-03-05
  checked: WebhookController::handlePaidInvoice
  found: Calls verifyPayment($invoiceId) but verifyPayment uses KeyType=PaymentId — WRONG for Invoice ID
  implication: Even if a DB record existed, the verify call uses wrong key type

- timestamp: 2026-03-05
  checked: trial flow (billing.trial.callback route)
  found: Trial has a dedicated GET callback route that verifies with paymentId and activates trial — this works
  implication: Topup needs the same pattern — dedicated callback route with paymentId verification

- timestamp: 2026-03-05
  checked: cache file
  found: myfatoorah_payment_methods_1 cache has KNET + VISA/MASTER correctly
  implication: Modal currently shows correct payment methods; page returns 200 after merge

## Resolution

root_cause: Two issues: (1) /billing/plans was returning 500 for new users (fixed by merge). (2) No dedicated topup callback route — initiateTopupPayment stores pending data only in session, but webhook handler looks for DB record; also uses wrong key type in verifyPayment. Credits are NEVER added after successful topup payment.

fix: (1) Add handleTopupCallback method to PaymentController mirroring handleTrialCallback pattern — verifies paymentId, reads session pending_topup, adds credits via BillingService, redirects with success. (2) Change topup callback_url to route('billing.topup.callback'). (3) Add route in web.php. (4) Create pending TopupPurchase record in initiateTopupPayment for better tracking.

verification: Test topup flow end-to-end: click button, modal shows, select payment method, form submits, redirect to MyFatoorah, simulate callback, verify credits added
files_changed:
  - app/Http/Controllers/Billing/PaymentController.php
  - routes/web.php
