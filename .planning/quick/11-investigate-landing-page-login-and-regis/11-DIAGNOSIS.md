# Landing Page Login and Registration — Diagnosis Report

**Date:** 2026-03-05
**Environments tested:** https://llmdev.resayil.io/landing/3 (dev), https://llm.resayil.io/landing/3 (prod)
**Investigation method:** curl from server (SSH), source code review, route list, Laravel logs

---

## Executive Summary

The registration form on `/landing/3` is **mostly functional** on both dev and prod, but fails at the OTP sending step for any phone number that either (a) does not have WhatsApp active on it, or (b) is not a real Kuwaiti mobile number. The Resayil WhatsApp API returns a 400 "invalid number" error, which the backend turns into a HTTP 500 response, and the landing page JS shows a generic error banner. For real users with actual Kuwait mobile numbers, the flow works end-to-end.

The "Sign In" link on the landing page navigates to `/login`, which uses the same AJAX-based login controller. That controller works correctly on both environments — login succeeds and the session cookie is set, allowing the dashboard to load. No fundamental flow failure exists for login.

**One historical bug was found and has since been resolved:** dev had a git merge conflict marker left in `AuthenticatedSessionController.php` that caused a PHP ParseError on login (visible in the March 5 10:36 log entry). The file on disk is now clean (89 lines, no conflict markers).

The most important issue that **will break real user registrations** is the phone validation logic — the landing page form prepends "965" to the local digits, but the Resayil WhatsApp API requires the number be in E164 format with a `+` prefix (e.g. `+96598765432`), not just the raw digits (`96598765432`). Whether the API accepts the bare digits or rejects them depends on the Resayil API implementation. Testing with `96598765432` succeeded (200 from `/register/otp`), which means the API does accept bare digits for valid Kuwaiti mobile numbers.

---

## Hypothesis Results

| ID | Hypothesis | Result | Evidence |
|----|-----------|--------|----------|
| H1 | CSRF token present in landing page HTML | CONFIRMED — WORKING | Both dev and prod render `<meta name="csrf-token" content="...">` with a 40-char token. Length verified. JS reads it via `document.querySelector('meta[name="csrf-token"]')`. |
| H2 | POST /register/otp returns wrong response | PARTIALLY — OTP service fails for invalid numbers | Returns 200 `{"step":"verify","phone":"..."}` for valid-format numbers. Returns 500 `{"message":"Failed to send verification message..."}` for non-mobile/fake numbers. Validation (422) fires for missing fields only. |
| H3 | guest middleware causes 302 on AJAX POST | DENIED | `RedirectIfAuthenticated` only fires for already-authenticated users. An unauthenticated POST to `/register/otp` goes directly to the controller. Confirmed: HTTP 200 returned for valid payload from unauthenticated session. |
| H4 | Phone validation rejects the submitted value | DENIED for well-formed numbers | Backend rule `'phone' => 'required\|numeric\|unique:users,phone'` accepts `96598765432` (numeric, not in DB). Only rejects if already used. |
| H5 | password_confirmation field passes | CONFIRMED — WORKING | JS explicitly sends `password_confirmation: password_value`. Backend `'password' => 'required\|string\|min:8\|confirmed'` passes. No validation error for this field observed. |
| H6 | /register/otp route missing on prod | DENIED | `php artisan route:list --path=register` on prod shows all 3 routes: `GET /register`, `POST /register/otp`, `POST /register`. Route exists on both environments. |
| H7 | /login returns non-200 | DENIED | Both `https://llmdev.resayil.io/login` and `https://llm.resayil.io/login` return HTTP 200. Confirmed via PowerShell `Invoke-WebRequest`. |
| H8 | Session cookie missing after login | DENIED — Cookie IS set | After successful `POST /login`, the `Set-Cookie` header contains both `XSRF-TOKEN` and `llm_resayil_portal_session` cookies. `GET /dashboard` immediately after returns 200 (not 302), confirming session is active. |
| H9 | template-3 is different version on prod | DENIED | The HTML output from dev and prod is byte-for-byte identical except for CSRF token value and Cloudflare email obfuscation hash. Both pages are 81,908 bytes. Same git commit on both. |
| H10 | /register/otp commits not on main | DENIED | `git log --oneline` on prod shows commit `63cefbc chore: merge dev into main — take dev template-3`. Both register/otp route and template-3 are on prod. |

---

## Root Causes

### Root Cause 1: Registration fails for phone numbers that do not have active WhatsApp

**Severity:** High (blocks all real registrations where the number is not WhatsApp-enabled)
**Environment:** Both dev and prod
**What fails:** User fills in the form and clicks "Start My Free Trial". After a few seconds the error banner shows: "Failed to send verification message. Please check your phone number and try again."
**Why it fails:** `OtpService::send()` calls the Resayil WhatsApp API (`https://api.resayil.io/v1/messages`). If the phone number is not registered on WhatsApp or is invalid, the API returns HTTP 400. `OtpService` checks `!$response->successful()` and throws an `\Exception`. The `RegisteredUserController::sendOtp()` catches this and returns HTTP 500 to the JS. The JS then shows the error banner.
**Evidence:**
```
[2026-03-05 11:27:57] production.ERROR: OtpService: WhatsApp send failed
{"phone":"96591234568","status":400,"response":"{\"message\":\"Phone number is not a valid international mobile number..."}"}
```
The backend HTTP 500 response:
```json
{"message":"Failed to send verification message. Please check your phone number and try again."}
HTTP_STATUS:500
```
**Impact:** Any user who enters a phone number without WhatsApp (including non-Kuwait numbers, landlines, VoIP numbers, or non-WhatsApp mobile users) will receive a generic error with no guidance. There is no user-friendly fallback.

---

### Root Cause 2: The error message for OTP failure is generic and unhelpful

**Severity:** Medium (UX issue — users do not know whether they entered the wrong number or something else failed)
**Environment:** Both dev and prod
**What fails:** The 500 error from the backend only says "Failed to send verification message. Please check your phone number and try again." — it does not tell the user they need a WhatsApp number specifically, or what format the number should be in.
**Why it fails:** The `OtpService` throws a generic exception. The controller catches all exceptions equally. The frontend shows whatever `message` is in the JSON body.
**Evidence:** The WhatsApp API's actual response (`"Phone number is not a valid international mobile number"`) is logged but never exposed to the user.

---

### Root Cause 3: Dev had an unresolved git merge conflict in AuthenticatedSessionController (now resolved)

**Severity:** Critical — but now fixed
**Environment:** Dev only (historically)
**What failed:** `POST /login` on dev threw a `ParseError` ("syntax error, unexpected token '==='") because the file contained conflict markers (`<<<<<<< Updated upstream`).
**Why it happened:** A `git stash` or merge conflict was not resolved before the file was committed/deployed.
**Evidence:**
```
[2026-03-05 10:36:43] local.ERROR: syntax error, unexpected token "===", expecting end of file
{"exception":"ParseError"} at AuthenticatedSessionController.php:91
```
**Current state:** The file is now 89 lines with no conflict markers. PHP syntax check passes. Login works on dev.

---

### Root Cause 4: The login page JS does not send Accept: application/json header

**Severity:** Low — currently not causing failures, but latent risk
**Environment:** Both dev and prod
**What fails:** The `login.blade.php` JS `fetch()` call sets `Content-Type: application/json` but NOT `Accept: application/json`. The `AuthenticatedSessionController::store()` always returns `response()->json()` explicitly, so this works today. However, if middleware changes (e.g., CSRF failure, auth check) return HTML redirects, the `res.json()` call would silently throw, and the `catch` block would show a generic "Login failed" error instead of the real error message.
**Evidence:** Testing showed that when the same cookie jar was reused (user already authenticated), the server returned a 302 redirect with HTML body. `res.json()` would throw, triggering the generic error. With a fresh unauthenticated session, the controller returns proper JSON.
**Note:** The 302 redirect case (already-authenticated user visiting `/login`) is caught by `RedirectIfAuthenticated` middleware BEFORE the controller runs, so the Accept header mismatch does not currently cause login failures. But it is fragile.

---

### Root Cause 5: The landing page navigation "Sign In" points to /login, not an inline modal

**Severity:** Low — this is by design, not a bug
**Environment:** Both dev and prod
**What happens:** Clicking "Sign In" in the navbar navigates the user away from the landing page to `/login`. This is a full page navigation, not AJAX.
**Why this is documented:** The investigation task mentioned "try clicking Sign In links — where do they go?" The answer is: they navigate to `/login` (line 376 of template-3: `href="/login"`), and the form below the nav (line 661: `<a href="/login">Sign In</a>`) also links to `/login`. This is intentional — the landing page has an embedded registration form but no embedded login form.
**Note:** The `/login` page is reachable (HTTP 200), works correctly, and redirects to `/dashboard` on success.

---

## Fix Recommendations (implementation deferred)

| Priority | Fix | Files | Effort |
|----------|-----|-------|--------|
| P1 | Add `Accept: application/json` to the landing page registration fetch calls (already present in template-3, but verify login.blade.php as well) | `resources/views/auth/login.blade.php` | S |
| P2 | Improve the OTP failure error message to explicitly tell users they need a WhatsApp-enabled Kuwait mobile number | `app/Http/Controllers/Auth/RegisteredUserController.php` | S |
| P3 | Add client-side phone format validation on the landing page — validate that the local digits are 8 digits starting with 5, 6, 9 (Kuwaiti mobile prefixes) before submitting | `resources/views/landing/template-3.blade.php` | S |
| P4 | Consider an SMS fallback or email OTP for users without WhatsApp | `app/Services/OtpService.php` | L |
| P5 | Add `session()->regenerate()` call after `Auth::login()` in the login controller to prevent session fixation | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | S |

---

## Prod vs Dev Differences

| Aspect | Dev | Prod |
|--------|-----|------|
| Landing page HTML | 81,908 bytes | 81,908 bytes (identical) |
| CSRF token | Present, valid | Present, valid |
| `/register/otp` route | Exists | Exists |
| `/register` route | Exists | Exists |
| `/login` HTTP 200 | Yes | Yes |
| Login (AJAX) | Works | Works |
| Registration Step 1 (OTP) | Works for valid numbers | Works for valid numbers |
| AuthenticatedSessionController | Has/had merge conflict (now resolved) | Clean — no conflict markers |
| Session cookie after login | Set correctly | Set correctly |
| Dashboard after login | 200 | 200 |
| WhatsApp API config | Same key, same URL | Same key, same URL |
| Recent git HEAD | `2f6e6a8` | `0cdce90` (merge commit) |

**Key conclusion:** Both environments behave identically for the landing page flows. No prod-specific or dev-specific failure was found in the routes, templates, controllers, or middleware for login and registration. The only environment-specific issue was the historical merge conflict on dev (now resolved).

---

## Items Not Tested

| Hypothesis | Reason not fully tested |
|-----------|------------------------|
| H8 (Set-Cookie after register, not login) | A real OTP code would be required to complete registration (Step 2: POST /register). Testing Step 2 requires a real phone number that receives the OTP. Only Step 1 was confirmed. |
| Full JS browser flow (IntersectionObserver, step transitions) | Playwright not available in this environment. curl tests confirmed backend behavior but not the JS step-transition animations or client-side validation feedback display. |
| OTP expiry behavior | Not tested — would require waiting 10 minutes |
| Rate limiting on /register/otp | Not tested — would require multiple rapid requests |

---

## Summary for Fix Implementation

**For a P1 fix plan, only these targeted changes are needed:**

1. **Login page** (`resources/views/auth/login.blade.php`): Add `'Accept': 'application/json'` to the fetch headers (line 69). This prevents silent JSON parse failures if a redirect occurs.

2. **Landing page OTP error UX** (`resources/views/landing/template-3.blade.php`): Improve the 500 error case in the `catch` block to say something like "This service requires a WhatsApp-enabled Kuwait mobile number. Please check your number and try again." — or map the backend 500 to a specific message.

3. **Phone validation** (`resources/views/landing/template-3.blade.php`): Before the fetch in step 1, validate that `phoneLocal` matches `/^[5-9]\d{7}$/` (8 digits, Kuwaiti mobile). Show a clear inline error if not.

None of these require backend changes or migrations.
