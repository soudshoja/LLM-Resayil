---
phase: quick-05
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Http/Controllers/Auth/AuthenticatedSessionController.php
autonomous: false
requirements: [AUTH-FIX-LOGIN]
must_haves:
  truths:
    - "Login with a valid email returns a session (redirects to dashboard)"
    - "Login with a wrong email returns 'Invalid credentials' — NOT 'Validation failed'"
    - "A test user can be created via admin panel without OTP"
    - "Created test user can successfully log in on llm.resayil.io"
  artifacts:
    - path: "app/Http/Controllers/Auth/AuthenticatedSessionController.php"
      provides: "Login validator without exists: rules"
      contains: "'email' => 'required_without:phone|email'"
  key_links:
    - from: "Login form POST /login"
      to: "AuthenticatedSessionController::store()"
      via: "Validator::make() rules"
      pattern: "required_without:phone\\|email'"
---

<objective>
Cherry-pick the login fix (commit 0ee7106) from dev to main, deploy to production, create a
QA test user via the admin panel, and verify the full login flow works on llm.resayil.io.

Purpose: BUG-04 — login with a wrong email showed "Validation failed." (from `exists:users,email`
rule) instead of "These credentials do not match our records." The fix removes the `exists:` rules
so the validator no longer reveals whether an email exists.

Output: Prod running the fixed auth controller, verified login flow passing.
</objective>

<execution_context>
@/d/Claude/projects/LLM-Resayil/.planning/quick/5-deploy-login-fix-to-prod-create-test-use/5-PLAN.md
</execution_context>

<context>
@/d/Claude/projects/LLM-Resayil/.planning/STATE.md
@/d/Claude/projects/LLM-Resayil/.planning/ROADMAP.md

Fix commit: 0ee7106
Changed file: app/Http/Controllers/Auth/AuthenticatedSessionController.php
Change: removed `exists:users,email` and `exists:users,phone` from Validator rules

Prod deploy command: ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
Admin panel: https://llm.resayil.io/admin
Admin credentials: admin@llm.resayil.io / password
</context>

<tasks>

<task type="auto">
  <name>Task 1: Cherry-pick login fix to main and deploy to prod</name>
  <files>app/Http/Controllers/Auth/AuthenticatedSessionController.php</files>
  <action>
    Cherry-pick commit 0ee7106 from dev onto main, then push and deploy to production.

    Steps:
    1. Switch to main branch:
       git checkout main

    2. Cherry-pick the fix commit:
       git cherry-pick 0ee7106

       This applies only the two-line auth fix. No other dev changes (savings dashboard,
       usage table, nav changes) will be included — those are NOT ready for prod.

    3. Push main to origin:
       git push origin main

    4. Deploy to production:
       ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"

    5. Switch back to dev so local branch stays on dev:
       git checkout dev

    If cherry-pick conflicts: the only changed file is
    `app/Http/Controllers/Auth/AuthenticatedSessionController.php` lines 28-29.
    Resolve by keeping the NEW version (without `exists:users,email` / `exists:users,phone`).
  </action>
  <verify>
    <automated>ssh whm-server "grep -n 'exists:users' ~/llm.resayil.io/app/Http/Controllers/Auth/AuthenticatedSessionController.php" && echo "FAIL: exists rule still present" || echo "PASS: exists rules removed"</automated>
  </verify>
  <done>
    Prod AuthenticatedSessionController has NO `exists:users,email` or `exists:users,phone` rules.
    Deploy script completed without error.
  </done>
</task>

<task type="auto">
  <name>Task 2: Create QA test user via admin panel API</name>
  <files></files>
  <action>
    Create the test user directly via the admin panel (bypasses OTP requirement).

    Option A — Use the admin web UI at https://llm.resayil.io/admin:
    - Log in as admin@llm.resayil.io / password
    - Find "Create User" or "Add User" button
    - Fill: Name=QA Tester, Email=qa.tester.2026@gmail.com, Password=QATest2026!
    - Set phone to a placeholder (e.g. 96500000001) — admin bypass means no OTP sent

    Option B — Create via artisan on the server (if admin UI has no create button):
    ssh whm-server "/opt/cpanel/ea-php82/root/usr/bin/php ~/llm.resayil.io/artisan tinker --execute=\"
      \\\$u = new App\\\Models\\\User();
      \\\$u->name = 'QA Tester';
      \\\$u->email = 'qa.tester.2026@gmail.com';
      \\\$u->password = bcrypt('QATest2026!');
      \\\$u->phone = '96500000001';
      \\\$u->email_verified_at = now();
      \\\$u->save();
      echo \\\$u->id;
    \""

    Use Option B if Option A is not available or has no create-user form.
  </action>
  <verify>
    <automated>ssh whm-server "/opt/cpanel/ea-php82/root/usr/bin/php ~/llm.resayil.io/artisan tinker --execute=\"echo App\\\Models\\\User::where('email','qa.tester.2026@gmail.com')->count();\""</automated>
  </verify>
  <done>
    User with email qa.tester.2026@gmail.com exists in prod database. Count = 1.
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <what-built>
    - Login fix deployed to prod (no more `exists:users` validation)
    - QA test user created (qa.tester.2026@gmail.com / QATest2026!)
  </what-built>
  <how-to-verify>
    Test A — Wrong email error message:
    1. Visit https://llm.resayil.io/login
    2. Enter email: notreal@example.com, password: anything
    3. Submit
    4. Expected: "These credentials do not match our records." (or similar)
    5. Must NOT show: "Validation failed." or any validation error about email/phone

    Test B — Correct login works:
    1. Visit https://llm.resayil.io/login
    2. Enter email: qa.tester.2026@gmail.com, password: QATest2026!
    3. Submit
    4. Expected: Redirect to dashboard, user is logged in as "QA Tester"
    5. Must NOT show any error

    Test C — Wrong password on valid email:
    1. Visit https://llm.resayil.io/login
    2. Enter email: qa.tester.2026@gmail.com, password: WrongPassword
    3. Submit
    4. Expected: "These credentials do not match our records."
    5. Must NOT show: "Validation failed."
  </how-to-verify>
  <resume-signal>Type "approved" if all 3 tests pass, or describe which test failed and what you saw</resume-signal>
</task>

</tasks>

<verification>
All three test scenarios on llm.resayil.io:
- Wrong email → "credentials do not match" (not "Validation failed")
- Correct credentials → successful dashboard redirect
- Wrong password → "credentials do not match" (not "Validation failed")
</verification>

<success_criteria>
- Prod main branch includes commit 0ee7106 (cherry-picked)
- `AuthenticatedSessionController.php` on prod has NO `exists:users` rules
- QA test user exists in prod DB
- Login with wrong email/password shows correct error message on llm.resayil.io
- Login with correct credentials succeeds on llm.resayil.io
</success_criteria>

<output>
After completion, note results in session memory. No SUMMARY.md needed for quick tasks.
</output>
