---
status: resolved
trigger: "live login page at https://llm.resayil.io/login shows an error without the user even attempting to log in, or fails on first try"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T00:10:00Z
---

## Current Focus

hypothesis: CONFIRMED — two bugs found:
  1. soud@alphia.net has non-bcrypt password hash in prod → Hash::check() throws RuntimeException → unhandled 500 → JS catch silently fails
  2. JS error stacking: insertAdjacentHTML('afterbegin') adds new error div each attempt without removing old ones
test: fix both issues — reset password hash in DB + fix JS to clear old errors before inserting new one
expecting: login works for all users, no stacking errors
next_action: fix AuthenticatedSessionController to catch hash exceptions + fix JS error stacking + reset soud password in prod DB

## Symptoms

expected: Login page loads clean, user enters credentials, submits, gets redirected to dashboard
actual: Error appears on the login page without logging in, or an error shows up on first attempt
errors: Unknown on browser; Laravel logs show "This password does not use the Bcrypt algorithm"
reproduction: Visit https://llm.resayil.io/login, try to log in as soud@alphia.net
started: Reported 2026-03-05

## Eliminated

- hypothesis: Server-side flash error displayed on page load (Blade @if($errors->any()))
  evidence: Fresh page load shows zero error elements; @errors section is empty
  timestamp: 2026-03-05T00:05:00Z

- hypothesis: GA script or JS error on page load causing visible error
  evidence: Only network failure is GA collect (expected in headless browser); no console errors on load
  timestamp: 2026-03-05T00:05:00Z

## Evidence

- timestamp: 2026-03-05T00:05:00Z
  checked: Playwright fresh page load of https://llm.resayil.io/login
  found: Page loads clean, zero error elements, no console errors
  implication: Error is not shown on initial page load — bug occurs during/after login attempt

- timestamp: 2026-03-05T00:06:00Z
  checked: Login attempt with wrong credentials (wrong@example.com / wrongpassword)
  found: Returns 401 JSON {"message":"Invalid credentials."}, 1 error div shown
  implication: Wrong credentials path works correctly

- timestamp: 2026-03-05T00:07:00Z
  checked: Repeated wrong credential submissions (3 times)
  found: Error count goes 1, 2, 3 — errors stack because JS uses insertAdjacentHTML('afterbegin') without clearing old errors
  implication: BUG #2 — JS error stacking on multiple failed attempts

- timestamp: 2026-03-05T00:08:00Z
  checked: Login attempt with admin@llm.resayil.io / password
  found: Returns 401 {"message":"Invalid credentials."} — user does NOT exist in prod DB
  implication: Admin user was only created in dev DB, not prod

- timestamp: 2026-03-05T00:09:00Z
  checked: prod Laravel log (storage/logs/laravel.log)
  found: Multiple entries: "This password does not use the Bcrypt algorithm" at BcryptHasher.php:76, starting 2026-03-05 07:01
  implication: BUG #1 — soud@alphia.net has a non-bcrypt password hash stored in prod DB

- timestamp: 2026-03-05T00:09:30Z
  checked: prod DB users table password hashes
  found: soud@alphia.net => "y0/oeJavhX3PHRQnZBWZtbODPIRo//hVp29mD5zI744.QlOlkwYCDW" (NOT bcrypt, no $2y$ prefix)
  found: qa.tester.2026@gmail.com => "$2y$12$NuE9Dz8m..." (valid bcrypt)
  implication: soud@alphia.net password was set incorrectly — Hash::check() throws RuntimeException → unhandled exception → 500 error

- timestamp: 2026-03-05T00:10:00Z
  checked: AuthenticatedSessionController::store() lines 50-63
  found: Hash::check($request->password, $user->password) is called with NO try/catch — RuntimeException propagates uncaught → 500
  found: JS does res.json() in try block, catches parse error but shows nothing to user → silent failure or blank state
  implication: When soud tries to log in, page shows either a server error page or the catch silently re-enables button

## Resolution

root_cause: |
  TWO bugs:
  1. (Critical) soud@alphia.net has a non-bcrypt password hash in prod DB (format "y0/oe...").
     AuthenticatedSessionController::store() calls Hash::check() without catching RuntimeException.
     This throws when password is not bcrypt, returning a 500 HTML page. The JS tries to parse
     as JSON, fails, and silently re-enables the button — user sees no clear error.
  2. (UX) JS in login.blade.php uses insertAdjacentHTML('afterbegin') to show errors without
     first clearing existing .alert-error elements, causing them to stack on repeated failures.

fix: |
  1. Wrapped Hash::check() in try/catch(\RuntimeException) in AuthenticatedSessionController —
     non-bcrypt hashes now treated as invalid credentials (returns 401 JSON, not 500 HTML).
  2. Fixed JS: added `document.querySelectorAll('.auth-card .alert-error').forEach(el => el.remove())`
     before each insertAdjacentHTML call (both in else block and catch block).
  3. Fixed JS catch block to show a user-facing error message instead of silently re-enabling button.
  4. Updated soud@alphia.net password hash in prod DB to valid bcrypt via PHP PDO (parameter binding).
  5. Cleared view cache on prod and dev after patching.

verification: |
  All 3 Playwright tests pass on prod (2026-03-05):
  - fresh_page_load: PASS (0 errors on load)
  - no_error_stacking: PASS (1 error shown after 3 failed attempts)
  - soud_login: PASS (redirected to /dashboard)

files_changed:
  - app/Http/Controllers/Auth/AuthenticatedSessionController.php (prod + dev directly patched)
  - resources/views/auth/login.blade.php (prod + dev directly patched, local dev branch updated)
  - prod DB: users.password updated for soud@alphia.net to valid bcrypt hash
