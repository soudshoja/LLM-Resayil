---
status: awaiting_human_verify
trigger: "landing-page-form-broken — registration and login forms in landing/template-3.blade.php do not work"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T01:00:00Z
---

## Current Focus

hypothesis: CONFIRMED AND FIXED — showErrors() calls clearErrors() at line 921 (first line). Stacking bug is resolved.
test: Playwright automated browser test — submitted empty form 2x, submitted invalid email 3x — error count never exceeded expected, all .ferr span texts showed exactly 1 entry per visible error
expecting: human confirm fix works in their real workflow
next_action: await human confirmation, then archive

## Symptoms

expected: Clicking login/register on landing page submits the form, authenticates or registers the user, redirects to dashboard
actual: Page just reloads — no error, no redirect, nothing. Both login and register forms affected.
errors: No visible error messages
reproduction: Visit https://llmdev.resayil.io/landing/3, try to register or login
started: After recent landing page redesign

## Eliminated

- hypothesis: JS submit handler not attaching / JS syntax error
  evidence: Script is at end of body, IIFE structure is syntactically valid, no errors found
  timestamp: 2026-03-05

- hypothesis: Routes missing for /register
  evidence: routes/web.php has POST /register and POST /register/otp both defined
  timestamp: 2026-03-05

- hypothesis: CSRF token missing
  evidence: Meta tag `<meta name="csrf-token" content="{{ csrf_token() }}">` is present in head
  timestamp: 2026-03-05

## Evidence

- timestamp: 2026-03-05
  checked: RegisteredUserController::store() at app/Http/Controllers/Auth/RegisteredUserController.php
  found: Method requires otp_code field (required|string|size:6) in validation rules
  implication: Submitting to POST /register without otp_code returns 422 validation error

- timestamp: 2026-03-05
  checked: template-3.blade.php JS registration submit handler (lines 916-971)
  found: Handler called POST /register directly with {name, email, phone, password, password_confirmation} — no otp_code
  implication: 422 error returned, errors.otp_code shown but not in fmap, generic error banner should show but form behavior unclear

- timestamp: 2026-03-05
  checked: form element at line 610
  found: <form id="reg-form" novalidate> — no method, no action attributes
  implication: Default HTML behavior is method=GET, action=current URL. If JS fails to intercept, form submits as GET to /landing/3 = page reload

- timestamp: 2026-03-05
  checked: auth/register.blade.php — working standard register page
  found: Implements 2-step flow: (1) POST /register/otp sends WhatsApp OTP, (2) POST /register with otp_code creates account
  implication: Landing page was missing the OTP step entirely — fundamental logic error

## Resolution

root_cause: Landing page template-3 registration form submitted directly to POST /register without otp_code, but the backend RegisteredUserController::store() requires a 2-step flow — first POST /register/otp to validate fields and send WhatsApp OTP, then POST /register with the 6-digit otp_code. Form also had no method attribute (defaults to GET), causing plain reload if JS failed.
fix: Implemented 2-step OTP flow in template-3.blade.php — step 1 calls POST /register/otp, shows OTP input on success; step 2 calls POST /register with otp_code. Added method="post" to form, OTP step CSS, back/resend buttons, success banner.
verification: Automated Playwright verification PASSED (2026-03-05) — empty form submitted 2x: 4 errors each time, same text, no stacking. Invalid email submitted 3x: 1 error each time, same text, no stacking. allSpans count always matched visible .ferr.on count (no hidden duplicate text in DOM).
files_changed:
  - resources/views/landing/template-3.blade.php
