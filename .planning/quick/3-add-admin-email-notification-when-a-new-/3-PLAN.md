---
phase: quick-3
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Http/Controllers/Auth/RegisteredUserController.php
  - resources/views/emails/new-user-registered.blade.php
autonomous: true
requirements: [ADMIN-NOTIF-01]
must_haves:
  truths:
    - "Admin receives an email at soud@alphia.net immediately after a new user registers"
    - "Email contains the new user's name, email, and registration timestamp"
    - "Registration flow for the user is unaffected — failures in email sending do not break signup"
  artifacts:
    - path: "resources/views/emails/new-user-registered.blade.php"
      provides: "HTML email template for new user notification"
    - path: "app/Http/Controllers/Auth/RegisteredUserController.php"
      provides: "Mail::send() call after User::create() in store()"
  key_links:
    - from: "RegisteredUserController::store()"
      to: "soud@alphia.net"
      via: "Mail::send('emails.new-user-registered', ...) inside try/catch"
      pattern: "Mail::send.*new-user-registered"
---

<objective>
Send an admin notification email to soud@alphia.net whenever a new user completes registration.

Purpose: Give the admin (Soud) real-time awareness of new user signups without checking the database.
Output: Email view + Mail::send() call wired into RegisteredUserController::store() after successful User::create().
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md

<interfaces>
<!-- Key code the executor needs. No codebase exploration required. -->

From app/Http/Controllers/Auth/RegisteredUserController.php — store() method (lines 54-99):
```php
public function store(Request $request, OtpService $otp)
{
    // ... validation and OTP verification ...

    $user = User::create([
        'phone'               => $request->phone,
        'email'               => $request->email,
        'name'                => $request->name,
        'password'            => Hash::make($request->password),
        'credits'             => 1000,
        'subscription_tier'   => 'basic',
        'phone_verified_at'   => now(),
    ]);

    Auth::login($user);

    return response()->json([
        'message' => 'Registration successful.',
    ], 201);
}
```
INSERT the Mail::send() call AFTER Auth::login($user) and BEFORE the return statement.

Existing mail pattern from app/Http/Controllers/ContactController.php (use exact same approach):
```php
use Illuminate\Support\Facades\Mail;

try {
    Mail::send('emails.contact', [
        'fullName' => $validated['full_name'],
        ...
    ], function ($message) {
        $message->to('soud@alphia.net')
                ->subject('New Contact Form Submission - LLM Resayil');
        $message->from($validated['email'], $validated['full_name']);
    });
} catch (\Exception $e) {
    \Log::error('Contact form email failed: ' . $e->getMessage());
}
```

Existing email template pattern from resources/views/emails/contact.blade.php:
```blade
@component('mail::message')
# New Contact Form Submission

**Full Name:** {{ $fullName }}
**Email:** {{ $email }}
...
@endcomponent
```
</interfaces>
</context>

<tasks>

<task type="auto">
  <name>Task 1: Create new-user email template and wire Mail::send() into store()</name>
  <files>
    resources/views/emails/new-user-registered.blade.php
    app/Http/Controllers/Auth/RegisteredUserController.php
  </files>
  <action>
**Step A — Create email template** at `resources/views/emails/new-user-registered.blade.php`:

Use the same `@component('mail::message')` Markdown Mail pattern as `emails/contact.blade.php`.

Template variables passed will be: `$name`, `$email`, `$phone`, `$registeredAt`.

Template content:
```
@component('mail::message')
# New User Registration — LLM Resayil

A new user has just registered on the LLM Resayil portal.

**Name:** {{ $name ?: '(not provided)' }}

**Email:** {{ $email ?: '(not provided)' }}

**Phone:** {{ $phone }}

**Registered At:** {{ $registeredAt }}

---

*This notification was sent automatically from llm.resayil.io*
@endcomponent
```

**Step B — Add Mail::send() to RegisteredUserController::store()**:

1. Add `use Illuminate\Support\Facades\Mail;` to the imports at the top of the file (after the existing `use` statements).

2. After `Auth::login($user);` and before the `return response()->json(...)` call, insert:

```php
// Notify admin of new registration
try {
    Mail::send('emails.new-user-registered', [
        'name'         => $user->name,
        'email'        => $user->email,
        'phone'        => $user->phone,
        'registeredAt' => $user->created_at->format('Y-m-d H:i:s T'),
    ], function ($message) {
        $message->to('soud@alphia.net')
                ->subject('New User Registration — LLM Resayil')
                ->from(config('mail.from.address'), config('mail.from.name'));
    });
} catch (\Exception $e) {
    \Log::error('Admin registration notification failed: ' . $e->getMessage());
}
```

The try/catch is mandatory — email failures MUST NOT break user registration.
  </action>
  <verify>
    <automated>cd D:/Claude/projects/LLM-Resayil && php artisan view:clear && php artisan config:clear && php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; echo 'Bootstrap OK';" 2>&1 | tail -3</automated>
  </verify>
  <done>
    - `resources/views/emails/new-user-registered.blade.php` exists with `@component('mail::message')` wrapper and all 4 variables rendered
    - `RegisteredUserController.php` imports `Mail` facade and contains `Mail::send('emails.new-user-registered', ...)` inside a try/catch after `Auth::login($user)`
    - `php artisan view:clear` runs without errors (confirms template is valid Blade)
    - Registering a user on dev results in a log entry in `storage/logs/laravel.log` (MAIL_MAILER=log on dev)
  </done>
</task>

<task type="auto">
  <name>Task 2: Commit and deploy to dev</name>
  <files></files>
  <action>
1. Stage only the two changed files:
   ```
   git add app/Http/Controllers/Auth/RegisteredUserController.php
   git add resources/views/emails/new-user-registered.blade.php
   ```

2. Commit:
   ```
   git commit -m "feat(quick-3): admin email notification on new user registration"
   ```

3. Push to origin dev:
   ```
   git push origin dev
   ```

4. Deploy to dev server:
   ```
   ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
   ```

5. Tail the dev log briefly to confirm no startup errors:
   ```
   ssh whm-server "tail -20 ~/llmdev.resayil.io/storage/logs/laravel.log"
   ```
  </action>
  <verify>
    <automated>git log --oneline -3</automated>
  </verify>
  <done>
    - Commit `feat(quick-3): admin email notification on new user registration` exists in git log
    - Deploy script exits 0
    - `llmdev.resayil.io` is running (no 500 errors)
    - On dev server, MAIL_MAILER should be confirmed — if it is `log`, the email will appear in `storage/logs/laravel.log` after a test registration; if it is `smtp`, the email will arrive at soud@alphia.net
  </done>
</task>

</tasks>

<verification>
Manual smoke test after deploy:

1. Open https://llmdev.resayil.io/register in a browser
2. Complete a test registration with a new phone number
3. On success, SSH to dev server and run:
   `ssh whm-server "tail -30 ~/llmdev.resayil.io/storage/logs/laravel.log"`
4. Confirm a log entry contains "new-user-registered" or the mail content (when MAIL_MAILER=log)
5. If MAIL_MAILER is smtp on dev, check soud@alphia.net inbox for the notification email
</verification>

<success_criteria>
- Admin notification email is sent (or logged) immediately after every new user registration
- Email contains: user name (or "(not provided)"), email, phone, registration timestamp
- A Mail::send() exception never prevents the user from completing registration
- Both new files committed and deployed to llmdev.resayil.io
</success_criteria>

<output>
After completion, create `.planning/quick/3-add-admin-email-notification-when-a-new-/3-SUMMARY.md` with what was built, files modified, and commit hash.
</output>
