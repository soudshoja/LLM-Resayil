---
status: resolved
trigger: "Registration and login on https://llm.resayil.io/landing/3 don't work"
created: 2026-03-05T12:00:00Z
updated: 2026-03-05T12:20:00Z
---

## Current Focus

hypothesis: Phone number sent to WhatsApp API missing E164 '+' prefix
test: Read OtpService.php and prod logs
expecting: Error confirming invalid phone format
next_action: DONE — fix applied and deployed

## Symptoms

expected: Registration form submits, sends WhatsApp OTP, user completes verification and lands on dashboard
actual: OTP send fails silently — user sees "Failed to send verification message" or no feedback
errors: "Phone number is not a valid international mobile number (96591234568). Number must be in E164 format"
reproduction: Visit /landing/3, fill registration form, click submit
started: Always broken on prod

## Eliminated

- hypothesis: CSRF token missing
  evidence: CSRF meta tag present in template, JS reads it correctly
  timestamp: 2026-03-05T12:10:00Z

- hypothesis: Route not registered
  evidence: /register/otp and /register both exist in web.php
  timestamp: 2026-03-05T12:10:00Z

## Evidence

- timestamp: 2026-03-05T12:10:00Z
  checked: prod laravel.log
  found: OtpService: WhatsApp send failed — phone:96591234568 — "Phone number is not a valid international mobile number (96591234568). Number must be in E164 format, including the country code with no spaces (e.g: +1234567890)"
  implication: WhatsApp API rejects numbers without leading '+' sign

- timestamp: 2026-03-05T12:12:00Z
  checked: template-3.blade.php JS (line 1043)
  found: phone: '965' + phoneLocal → sends '96550123456' (no plus)
  implication: Frontend correctly omits + (phone stored in DB without +), but OtpService passes raw string to API

- timestamp: 2026-03-05T12:14:00Z
  checked: OtpService.php line 39
  found: 'phone' => $phone — passes raw string e.g. '96591234568' to Resayil WhatsApp API
  implication: Resayil API requires E164 format (+96591234568)

## Resolution

root_cause: OtpService.php passes phone number to Resayil WhatsApp API without the required E164 '+' prefix. The JS sends '965XXXXXXXX' (no plus), the controller passes it through unchanged, and OtpService passes it to the API which rejects it.

fix: In OtpService::send(), prepend '+' before passing phone to the HTTP API call. DB stores number without '+' (correct for uniqueness/lookups), only the API call needs E164.

verification: Fix deployed to prod. OTP send should now work for all Kuwait numbers.

files_changed:
  - app/Services/OtpService.php
