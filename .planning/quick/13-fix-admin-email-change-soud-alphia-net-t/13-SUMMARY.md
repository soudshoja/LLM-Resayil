---
phase: quick-13
plan: 01
subsystem: auth
tags: [admin, email, registration, validation]
tech-stack:
  added: []
  patterns: [try-catch QueryException, closure use() capture]
key-files:
  modified:
    - app/Models/User.php
    - app/Http/Controllers/Auth/RegisteredUserController.php
    - app/Http/Controllers/ContactController.php
decisions:
  - "Admin email changed to shoja.soud@gmail.com — runtime behavior only; planning docs left unchanged"
  - "store() drops unique: rules — OTP ownership proof makes re-validation redundant; DB constraint remains as final guard"
metrics:
  duration: "~15 minutes"
  completed: "2026-03-06"
  tasks_completed: 3
  files_modified: 3
---

# Quick Task 13: Fix Admin Email + Registration Step 2 Unique Validation

**One-liner:** Changed admin email to shoja.soud@gmail.com in isAdmin(), admin notifications, and contact form; removed redundant unique validators from registration Step 2 store() to prevent 422 race errors.

## What Was Changed

### File 1: `app/Models/User.php`

**Change:** `isAdmin()` method updated — replaced `soud@alphia.net` with `shoja.soud@gmail.com`.

```php
// Before
return in_array($this->email, [
    'admin@llm.resayil.io',
    'soud@alphia.net',
]);

// After
return in_array($this->email, [
    'admin@llm.resayil.io',
    'shoja.soud@gmail.com',
]);
```

All admin-gated features (rate limit bypass, credit check bypass, admin dashboard, model admin panel) now recognize `shoja.soud@gmail.com` instead of `soud@alphia.net`.

### File 2: `app/Http/Controllers/Auth/RegisteredUserController.php`

**Change 1:** Admin notification list updated.

```php
// Before
$adminEmails = ['soud@alphia.net', 'admin@llm.resayil.io'];

// After
$adminEmails = ['shoja.soud@gmail.com', 'admin@llm.resayil.io'];
```

**Change 2:** Registration Step 2 (`store()`) — removed `unique:users,email` and `unique:users,phone` from validator, wrapped `User::create()` in try/catch.

```php
// Before (causes 422 if user retries Step 2)
'email' => 'nullable|email|unique:users,email',
'phone' => 'required|numeric|unique:users,phone',

// After (uniqueness already guaranteed by Step 1 + OTP)
'email' => 'nullable|email',
'phone' => 'required|numeric',
```

The `User::create()` is now wrapped in `try/catch (\Illuminate\Database\QueryException)` to return a user-friendly 422 if a DB-level unique violation occurs (race condition safety net).

### File 3: `app/Http/Controllers/ContactController.php`

**Change:** Contact form email recipient updated.

```php
// Before
$message->to('soud@alphia.net')

// After
$message->to('shoja.soud@gmail.com')
```

## Deviation: Rule 1 Auto-Fix — Closure Scope Bug in ContactController

**Found during:** Task 1 review of ContactController.php
**Issue:** The `$message->from($validated['email'], $validated['full_name'])` line inside the Mail closure referenced `$validated` but the closure had no `use ($validated)` capture. This would cause a PHP undefined variable error in the closure.
**Fix:** Added `use ($validated)` to the closure signature while making the email change.

```php
// Before (broken — $validated not captured)
}, function ($message) {
    $message->to('soud@alphia.net')...
    $message->from($validated['email'], $validated['full_name']);
});

// After (fixed)
}, function ($message) use ($validated) {
    $message->to('shoja.soud@gmail.com')...
    $message->from($validated['email'], $validated['full_name']);
});
```

**Files modified:** `app/Http/Controllers/ContactController.php`
**Commit:** `0d3e3ba`

## Deployment

- Pushed to `origin/dev` — remote updated
- Deployed to `llmdev.resayil.io` via `bash deploy.sh dev`
- All 6 deployment steps completed successfully (pull, env restore, composer, migrations, cache clear)

## Verification Results

| Check | Result |
|-------|--------|
| `shoja.soud@gmail.com` in User.php | FOUND (line 112) |
| `shoja.soud@gmail.com` in RegisteredUserController.php | FOUND (line 105) |
| `shoja.soud@gmail.com` in ContactController.php | FOUND (lines 24, 32) |
| `soud@alphia.net` in any of the 3 files | NOT FOUND (0 matches) |
| `sendOtp()` still has `unique:users,email` | YES |
| `sendOtp()` still has `unique:users,phone` | YES |
| `store()` has no `unique:` rules | CONFIRMED (grep returned empty) |
| `User::create()` wrapped in QueryException catch | YES (line 96) |
| PHP syntax — RegisteredUserController.php | No syntax errors |
| PHP syntax — User.php | No syntax errors |
| PHP syntax — ContactController.php | No syntax errors |
| Dev server remote grep — shoja.soud@gmail.com | 4 matches across 3 files |
| Login test `admin@llm.resayil.io` | "Login successful." (HTTP 200) |

## Commits

| Hash | Description |
|------|-------------|
| `0d3e3ba` | fix: change admin email from soud@alphia.net to shoja.soud@gmail.com |
| `9cd3f23` | fix: remove duplicate unique validation from registration Step 2 (store) |

## Self-Check: PASSED

- `app/Models/User.php` contains `shoja.soud@gmail.com` — CONFIRMED
- `app/Http/Controllers/Auth/RegisteredUserController.php` contains `shoja.soud@gmail.com` — CONFIRMED
- `app/Http/Controllers/ContactController.php` contains `shoja.soud@gmail.com` — CONFIRMED
- Commits `0d3e3ba` and `9cd3f23` exist in git log — CONFIRMED
- Dev deployment successful — CONFIRMED
- Login test passed — CONFIRMED
