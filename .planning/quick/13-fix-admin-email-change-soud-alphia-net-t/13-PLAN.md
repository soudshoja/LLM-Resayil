---
phase: quick-13
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Models/User.php
  - app/Http/Controllers/Auth/RegisteredUserController.php
  - app/Http/Controllers/ContactController.php
autonomous: true
requirements: []

must_haves:
  truths:
    - "shoja.soud@gmail.com is recognized as admin (isAdmin() returns true)"
    - "soud@alphia.net is no longer recognized as admin"
    - "Admin registration notification emails go to shoja.soud@gmail.com and admin@llm.resayil.io"
    - "Contact form submissions are emailed to shoja.soud@gmail.com"
    - "Login works for existing users on dev"
    - "Registration step 2 does not fail with unique-violation when phone/email were already validated in step 1"
  artifacts:
    - path: "app/Models/User.php"
      provides: "isAdmin() returns true for shoja.soud@gmail.com"
      contains: "shoja.soud@gmail.com"
    - path: "app/Http/Controllers/Auth/RegisteredUserController.php"
      provides: "Admin notification to shoja.soud@gmail.com"
      contains: "shoja.soud@gmail.com"
    - path: "app/Http/Controllers/ContactController.php"
      provides: "Contact form email to shoja.soud@gmail.com"
      contains: "shoja.soud@gmail.com"
  key_links:
    - from: "User::isAdmin()"
      to: "AdminMiddleware, ChatCompletionsController, ModelAccessControl"
      via: "$user->isAdmin()"
      pattern: "isAdmin\\(\\)"
---

<objective>
Change the admin email from soud@alphia.net to shoja.soud@gmail.com in all hardcoded locations and the isAdmin() method. Then fix the registration Step 2 double-unique-validation bug that causes new registrations to fail if the validator re-checks uniqueness for fields already validated in Step 1.

Purpose: The admin's email address has changed. All admin recognition, notification, and contact form routing must reflect the new address. The registration bug means users who complete Step 1 (OTP send) may hit 422 errors at Step 2 (account creation) in edge cases.

Output: Updated PHP files deployed to dev.
</objective>

<execution_context>
@D:/Claude/get-shit-done/workflows/execute-plan.md
</execution_context>

<context>
@D:/Claude/projects/LLM-Resayil/CLAUDE.md

## Investigation Summary

### Admin Email Change — 3 files need updating

**File 1: `app/Models/User.php`**
```php
// Current isAdmin() (lines 108-114):
public function isAdmin(): bool
{
    return in_array($this->email, [
        'admin@llm.resayil.io',
        'soud@alphia.net',          // ← change to shoja.soud@gmail.com
    ]);
}
```

**File 2: `app/Http/Controllers/Auth/RegisteredUserController.php`**
```php
// Current (line 99):
$adminEmails = ['soud@alphia.net', 'admin@llm.resayil.io'];
// ← change soud@alphia.net to shoja.soud@gmail.com
```

**File 3: `app/Http/Controllers/ContactController.php`**
```php
// Current (line 32):
$message->to('soud@alphia.net')
// ← change to shoja.soud@gmail.com
```

### Login Status — WORKING (no fix needed)
Tested: `POST /login` with JSON body returns 200 + session cookie for both admin accounts.
Login controller correctly handles email-only login (no phone field sent by form).

### Registration Bug — REAL issue to fix

**Root cause:** `store()` (Step 2) re-validates `unique:users,email` and `unique:users,phone` AGAIN after Step 1 already validated them. This causes Step 2 to fail with 422 if:
- The user retries Step 2 after an earlier partial failure (race conditions)
- The email was checked unique in Step 1, but the user session carried over a stale CSRF and re-submits

More importantly: the `unique` check in Step 2 is logically wrong — the user has already proven ownership of the phone via OTP. If the phone is already in the DB it means they're trying to register twice. The Step 2 validator should only re-check that the OTP is valid; email/phone uniqueness was handled at Step 1.

**Fix:** In `store()`, remove `unique:users,email` and `unique:users,phone` from the validation rules. Keep all other rules. The uniqueness is already guaranteed by Step 1 (OTP was sent to an unregistered phone). If a race condition occurs, the `User::create()` will throw a DB unique constraint exception — wrap it with a try/catch that returns a 422.

### Dev DB state (confirmed)
Users on dev: soud@alphia.net (admin), admin@llm.resayil.io (basic), testuser@llmdev.resayil.io (basic)
All have valid bcrypt hashes. Login confirmed working for both admin accounts.
</context>

<tasks>

<task type="auto">
  <name>Task 1: Change soud@alphia.net to shoja.soud@gmail.com in all 3 files</name>
  <files>app/Models/User.php, app/Http/Controllers/Auth/RegisteredUserController.php, app/Http/Controllers/ContactController.php</files>
  <action>
Make three targeted replacements:

**1. `app/Models/User.php` — isAdmin() array:**
Change `'soud@alphia.net'` to `'shoja.soud@gmail.com'` in the isAdmin() method. The array should be:
```php
return in_array($this->email, [
    'admin@llm.resayil.io',
    'shoja.soud@gmail.com',
]);
```

**2. `app/Http/Controllers/Auth/RegisteredUserController.php` — admin notification list:**
Change line 99 from:
```php
$adminEmails = ['soud@alphia.net', 'admin@llm.resayil.io'];
```
to:
```php
$adminEmails = ['shoja.soud@gmail.com', 'admin@llm.resayil.io'];
```

**3. `app/Http/Controllers/ContactController.php` — contact form recipient:**
Find the `$message->to('soud@alphia.net')` line and change it to:
```php
$message->to('shoja.soud@gmail.com')
```

Do NOT change any planning documents or non-PHP files. Do NOT change CLAUDE.md or MEMORY.md — those reference soud@alphia.net for historical/context reasons and do not drive runtime behavior.
  </action>
  <verify>
grep -r "shoja.soud@gmail.com" app/Models/User.php app/Http/Controllers/Auth/RegisteredUserController.php app/Http/Controllers/ContactController.php
grep -r "soud@alphia.net" app/Models/User.php app/Http/Controllers/Auth/RegisteredUserController.php app/Http/Controllers/ContactController.php
  </verify>
  <done>
- `grep shoja.soud@gmail.com` returns 3 matches (one per file)
- `grep soud@alphia.net` returns 0 matches in those 3 files
  </done>
</task>

<task type="auto">
  <name>Task 2: Fix registration Step 2 double-unique-validation bug</name>
  <files>app/Http/Controllers/Auth/RegisteredUserController.php</files>
  <action>
In `RegisteredUserController::store()` (the Step 2 handler), remove the `unique:users,email` and `unique:users,phone` rules from the validator. These checks belong only in Step 1 (`sendOtp()`), not Step 2.

**Current `store()` validator rules:**
```php
$validator = Validator::make($request->all(), [
    'name'     => 'nullable|string|max:255',
    'email'    => 'nullable|email|unique:users,email',
    'phone'    => 'required|numeric|unique:users,phone',
    'password' => 'required|string|min:8|confirmed',
    'otp_code' => 'required|string|size:6',
]);
```

**Replace with (remove unique checks):**
```php
$validator = Validator::make($request->all(), [
    'name'     => 'nullable|string|max:255',
    'email'    => 'nullable|email',
    'phone'    => 'required|numeric',
    'password' => 'required|string|min:8|confirmed',
    'otp_code' => 'required|string|size:6',
]);
```

Also wrap `User::create()` in a try/catch to handle the (now unlikely) DB unique constraint violation:

**Current:**
```php
$user = User::create([
    'phone'               => $request->phone,
    ...
]);
```

**Replace with:**
```php
try {
    $user = User::create([
        'phone'               => $request->phone,
        'email'               => $request->email,
        'name'                => $request->name,
        'password'            => Hash::make($request->password),
        'credits'             => 1000,
        'subscription_tier'   => 'basic',
        'phone_verified_at'   => now(),
    ]);
} catch (\Illuminate\Database\QueryException $e) {
    return response()->json([
        'message' => 'This phone number or email is already registered. Please log in instead.',
    ], 422);
}
```

Do not change anything else in the method.
  </action>
  <verify>
# Check unique rules are gone from store()
grep -A 10 "public function store" app/Http/Controllers/Auth/RegisteredUserController.php | grep "unique"
# Should return nothing (no unique rules in store)

# Check sendOtp still has unique rules
grep -A 10 "public function sendOtp" app/Http/Controllers/Auth/RegisteredUserController.php | grep "unique"
# Should show unique:users,email and unique:users,phone
  </verify>
  <done>
- `store()` validator has no `unique:` rules
- `sendOtp()` validator still has `unique:users,email` and `unique:users,phone`
- `User::create()` is wrapped in try/catch for QueryException
  </done>
</task>

<task type="auto">
  <name>Task 3: Commit and deploy to dev</name>
  <files>app/Models/User.php, app/Http/Controllers/Auth/RegisteredUserController.php, app/Http/Controllers/ContactController.php</files>
  <action>
Stage and commit the changes, then deploy to dev:

```bash
cd /d/Claude/projects/LLM-Resayil
git add app/Models/User.php app/Http/Controllers/Auth/RegisteredUserController.php app/Http/Controllers/ContactController.php
git commit -m "fix: change admin email to shoja.soud@gmail.com; fix registration step 2 unique validation"
git push origin dev
ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
```

After deploy, verify on dev:

1. Check that `shoja.soud@gmail.com` returns true from isAdmin():
```bash
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute=\"echo App\Models\User::where('email','shoja.soud@gmail.com')->first()?->isAdmin() ? 'IS_ADMIN' : 'NOT_ADMIN'\""
```
(This will return NOT_ADMIN because the user doesn't exist on dev — that is expected. The code change is what matters.)

2. Test login still works:
```bash
CSRF=$(curl -s -c /tmp/c.txt https://llmdev.resayil.io/login | grep -o 'csrf-token" content="[^"]*"' | grep -o '"[^"]*"$' | tr -d '"')
curl -s -b /tmp/c.txt -c /tmp/c.txt -X POST https://llmdev.resayil.io/login \
  -H "Content-Type: application/json" -H "X-CSRF-TOKEN: $CSRF" \
  -d '{"email":"admin@llm.resayil.io","password":"password","remember":0}' | grep -o '"message":"[^"]*"'
```
Expected: `"message":"Login successful."`

3. Check php syntax is valid on deployed file:
```bash
ssh whm-server "/opt/cpanel/ea-php82/root/usr/bin/php -l ~/llmdev.resayil.io/app/Http/Controllers/Auth/RegisteredUserController.php"
ssh whm-server "/opt/cpanel/ea-php82/root/usr/bin/php -l ~/llmdev.resayil.io/app/Models/User.php"
```
  </action>
  <verify>
git log --oneline -3
ssh whm-server "grep -n 'shoja.soud' ~/llmdev.resayil.io/app/Models/User.php ~/llmdev.resayil.io/app/Http/Controllers/Auth/RegisteredUserController.php ~/llmdev.resayil.io/app/Http/Controllers/ContactController.php"
  </verify>
  <done>
- Git commit exists with the 3 changed files
- Dev server files contain `shoja.soud@gmail.com` (confirmed by remote grep)
- Login test returns "Login successful."
- PHP syntax check passes (No syntax errors detected)
  </done>
</task>

</tasks>

<verification>
After all tasks complete:

1. `shoja.soud@gmail.com` appears in exactly 3 source files: User.php, RegisteredUserController.php, ContactController.php
2. `soud@alphia.net` does NOT appear in those 3 source files
3. `sendOtp()` still has `unique:users,email` and `unique:users,phone`
4. `store()` does NOT have `unique:` rules but DOES have try/catch around User::create()
5. Dev login works (HTTP 200, "Login successful." message)
6. PHP lint passes on all modified files
</verification>

<success_criteria>
- Admin email updated to shoja.soud@gmail.com in all runtime files (not planning docs)
- Registration Step 2 no longer fails due to double unique validation
- Dev deployment successful, login confirmed working
- No regression in existing admin functionality
</success_criteria>

<output>
After completion, create `.planning/quick/13-fix-admin-email-change-soud-alphia-net-t/13-SUMMARY.md` with:
- What was changed and in which files
- Confirmation that login works on dev
- Any issues found during execution
</output>
