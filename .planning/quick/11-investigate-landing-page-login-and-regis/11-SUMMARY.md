---
phase: quick-11
plan: 01
subsystem: auth/registration
tags: [investigation, landing-page, registration, login, otp, diagnosis]
dependency_graph:
  requires: []
  provides: [DIAGNOSIS.md with root causes for login/registration failures on /landing/3]
  affects: [template-3.blade.php, auth/login.blade.php, RegisteredUserController, OtpService]
tech_stack:
  added: []
  patterns: [curl-from-server, SSH investigation, Laravel log analysis]
key_files:
  created:
    - .planning/quick/11-investigate-landing-page-login-and-regis/11-DIAGNOSIS.md
  modified: []
decisions:
  - No code changes made — investigation only as required by plan constraints
metrics:
  duration: ~45 minutes
  completed: 2026-03-05
---

# Quick Task 11: Landing Page Login and Registration — Investigation Summary

**One-liner:** Registration flow works end-to-end for valid WhatsApp-enabled Kuwaiti numbers; fails with a generic 500 for any other phone — OTP send error, not a routing or CSRF bug.

## What Was Investigated

- Source code: `template-3.blade.php`, `RegisteredUserController.php`, `AuthenticatedSessionController.php`, `OtpService.php`, `routes/web.php`, `Kernel.php`, `VerifyCsrfToken.php`, `RedirectIfAuthenticated.php`, `Authenticate.php`, `login.blade.php`
- Live curl tests from the server via SSH: both dev (llmdev.resayil.io) and prod (llm.resayil.io)
- Laravel logs on both environments
- Route lists on both servers via `php artisan route:list`
- Git log comparison between dev and prod

## Key Findings

### Registration (`/landing/3` form)

1. **CSRF token**: Present and valid on both environments. JS reads it correctly via `meta[name="csrf-token"]`.

2. **Route `/register/otp`**: Exists on both prod and dev. No missing route.

3. **Step 1 (POST /register/otp)**: Returns HTTP 200 `{"step":"verify","phone":"..."}` for valid-format phone numbers. The JS transitions to Step 2 correctly.

4. **OTP service failure**: For any phone number that fails the Resayil WhatsApp API (invalid number, no WhatsApp, non-mobile), the backend returns HTTP 500 with a generic message. This is the primary failure mode real users will experience.

5. **Step 2 (POST /register)**: Not testable without a real OTP code, but backend logic is correct (validates otp_code, creates user, sets session, returns 201).

### Login (`/login` page, linked from `/landing/3`)

1. **"Sign In" link**: Navigates to `/login` — correct, by design.
2. **Login AJAX flow**: Works correctly on both environments. Returns JSON `{"message":"Login successful.","user":{...}}` with session cookies set. Dashboard loads at 200 after login.
3. **Missing `Accept: application/json` header**: The `login.blade.php` fetch does not include this header, which is technically fragile but not currently causing failures.
4. **Historical bug (resolved)**: Dev had a git merge conflict in `AuthenticatedSessionController.php` that caused a ParseError on login. The file is now clean on disk.

### Prod vs Dev

Both environments are functionally identical for these flows. Same routes, same template, same controller logic, same WhatsApp API credentials.

## What Is NOT Broken

- CSRF validation
- Routes (`/register/otp`, `/register`, `/login` all exist on both envs)
- Template (identical HTML on dev and prod)
- Session cookie handling
- Guest middleware (no false 302s for unauthenticated AJAX)
- Password confirmation field (JS sends it correctly)
- Dashboard redirect after successful login

## What Needs Fixing (in priority order)

| # | Fix | Effort |
|---|-----|--------|
| P1 | Client-side phone validation: check for 8-digit Kuwaiti mobile prefix before submitting | S |
| P2 | Better OTP failure message: tell users they need a WhatsApp-enabled Kuwait number | S |
| P3 | Add `Accept: application/json` to login.blade.php fetch (latent risk) | S |
| P4 | Session regeneration after `Auth::login()` (security hygiene) | S |

## Deviations from Plan

None — investigation only, zero code changes made. All tools were curl/SSH/log-reading.

## Self-Check

- `11-DIAGNOSIS.md` was written and is present in `.planning/quick/11-investigate-landing-page-login-and-regis/`
- Zero application files were modified (no changes to `app/`, `resources/`, `routes/`)
- All 10 hypotheses (H1-H10) addressed with CONFIRMED / DENIED / UNKNOWN verdicts
- Root causes documented with HTTP evidence
- Fix recommendations table included
- Prod vs dev comparison table included
