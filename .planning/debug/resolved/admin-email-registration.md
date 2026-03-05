---
status: resolved
trigger: "admin-email-not-sent-on-registration"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T12:30:00Z
---

## Current Focus

hypothesis: RESOLVED
test: Fix deployed to prod
expecting: Next registration sends to both admin emails with success log entry
next_action: none — monitoring via log

## Symptoms

expected: When a new user registers, an email notification is sent to soud@alphia.net and admin@llm.resayil.io
actual: New user registered successfully but no email received by admin
errors: Unknown — investigate
reproduction: Check prod logs after a recent registration
started: Just reported — new user registered recently

## Eliminated

- hypothesis: Queue worker issue — queued listeners not processing
  evidence: Mail::send() is synchronous (not queued), called directly in controller
  timestamp: 2026-03-05T12:00:00Z

- hypothesis: Missing email view file
  evidence: resources/views/emails/new-user-registered.blade.php exists on both dev and prod
  timestamp: 2026-03-05T12:00:00Z

- hypothesis: Sendmail not available on prod server
  evidence: /usr/sbin/sendmail exists (Exim), responds to commands
  timestamp: 2026-03-05T12:00:00Z

## Evidence

- timestamp: 2026-03-05T12:00:00Z
  checked: prod laravel.log line 133
  found: "[2026-03-04 23:38:59] production.ERROR: Admin registration notification failed: No hint path defined for [mail]. (View: .../emails/new-user-registered.blade.php)"
  implication: Email view used @component('mail::message') which requires unpublished mail package views — this was the original failure

- timestamp: 2026-03-05T12:00:00Z
  checked: git log for email view + commit 0fc5a7a
  found: Commit 0fc5a7a already fixed the view (replaced @component('mail::message') with plain HTML) and was deployed to prod as c96a1e1
  implication: The original mail::message bug is already fixed on prod

- timestamp: 2026-03-05T12:00:00Z
  checked: RegisteredUserController.php lines 98-112
  found: Mail::send only sends to 'soud@alphia.net' — 'admin@llm.resayil.io' is never included
  implication: admin@llm.resayil.io never receives registration notifications — this is Bug #2

- timestamp: 2026-03-05T12:00:00Z
  checked: EventServiceProvider.php
  found: UserRegistered event listener (WelcomeNotificationListener) does NOT notify admins — no admin notification listener is registered for UserRegistered
  implication: The admin email is done entirely in the controller via Mail::send, not via event/listener

- timestamp: 2026-03-05T12:00:00Z
  checked: prod log — only one registration error entry, no success entries
  found: No new registrations have occurred on prod since the view fix was deployed
  implication: Cannot confirm mail delivery from logs; fix is theoretical but correct

## Resolution

root_cause: |
  Two bugs:
  1. (Already fixed on prod — commit c96a1e1) Email view used @component('mail::message') which requires
     unpublished Laravel mail package views — throws "No hint path defined for [mail]".
     Fixed by replacing with plain HTML in the view file.
  2. RegisteredUserController only sent notification to soud@alphia.net, never to admin@llm.resayil.io.
     Fixed by iterating over both admin emails with individual try/catch and Log::info on success.

fix: |
  app/Http/Controllers/Auth/RegisteredUserController.php — changed single Mail::send to
  loop over ['soud@alphia.net', 'admin@llm.resayil.io'] with per-recipient try/catch and Log::info on success

verification: |
  Both dev and prod deployed successfully (commits f3003ad on dev, merged to main bda92b4).
  Verify on next registration: grep 'registration notification sent' ~/llm.resayil.io/storage/logs/laravel.log

files_changed:
  - app/Http/Controllers/Auth/RegisteredUserController.php
