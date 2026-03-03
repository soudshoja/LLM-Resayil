# Phase 9: User Profile Management — Context

**Gathered:** 2026-03-03
**Status:** Ready for planning
**Source:** User requirements + codebase audit

<domain>
## Phase Boundary

Users can manage their own account from `/profile`:
- Change their **password** (current password required)
- Change their **email** address
- Change their **mobile number** — requires WhatsApp OTP verification on the NEW number before saving

This phase covers the profile page UI, backend controller fixes, and bug fixes to the existing partial implementation. It does NOT cover admin-side user management (already handled in Phase 7).

</domain>

<decisions>
## Implementation Decisions

### Existing Infrastructure (REUSE — do not rebuild)
- `app/Http/Controllers/ProfileController.php` — `show()`, `update()` (name+email), `updatePassword()` — already exists, needs minor fixes
- `app/Http/Controllers/OtpController.php` — `sendPhoneOtp()`, `verifyPhoneOtp()` — already exists and works
- `app/Services/OtpService.php` — `send($phone)`, `verify($phone, $code)` — already implemented
- `app/Models/OtpCode.php` — already implemented with 10-min expiry, 3-attempt limit
- `resources/views/profile.blade.php` — already exists with all three sections, needs redesign + bug fixes
- Routes in `routes/web.php` — all five routes already registered

### Password Change
- Requires current password verification (already implemented in `ProfileController::updatePassword()`)
- Min 8 chars, confirmation field (already implemented)
- No OTP required for password change
- Show success message inline

### Email Change
- Direct update — no email OTP required (user confirmed this)
- Unique validation against other users (already implemented)
- **Fix needed:** Reset `email_verified_at` to null when email changes
- Show success message inline

### Mobile Number Change — OTP Flow
- Two-step AJAX flow (already working):
  1. User enters new number → POST `/profile/phone/otp` → WhatsApp OTP sent
  2. User enters 6-digit code → POST `/profile/phone/verify` → number saved + `phone_verified_at = now()`
- **Bug fix needed:** After successful verification, the "Verified" badge must update in-page without reload
- Phone format: numeric, E.164 compatible (OtpService handles formatting)
- "Resend code" and "Change number" links (already in UI)

### Model Fixes
- **Fix:** Add `phone_verified_at` to `$casts` in `app/Models/User.php` as `'datetime'`

### UI Requirements
- **Dark luxury design system** — must match the rest of the site
  - bg: `#0f1115`, gold: `#d4af37`, card bg: `#13161d`
  - Fonts: Inter (Latin) + Tajawal (Arabic)
  - CSS classes from `layouts/app.blade.php` globals: `.card`, `.btn`, `.btn-gold`, `.btn-outline`, `.form-group`, `.form-label`, `.form-input`, `.alert`, `.alert-success`, `.alert-error`, `.badge`
- **No inline styles** — use CSS classes only; any custom styles go in `@push('styles')`
- Three sections as separate cards: Profile Info, Phone Number, Change Password
- Subscription tier badge visible in Profile Info section

### Arabic Translation
- All hardcoded strings must use `__('profile.key')` helper
- Lang keys already exist in `resources/lang/ar/profile.php` and `resources/lang/en/profile.php`
- Verify keys exist before using; add missing keys to both files

### Claude's Discretion
- Order of sections on page (recommended: Profile Info → Phone → Password)
- Whether to show name field in Profile Info or remove it (keep it — users should be able to update their display name)
- Password strength indicator (optional, keep it simple)
- Mobile input placeholder format

</decisions>

<specifics>
## Specific Files to Modify

| File | Change |
|------|--------|
| `app/Models/User.php` | Add `phone_verified_at` to `$casts` |
| `app/Http/Controllers/ProfileController.php` | Reset `email_verified_at` on email change |
| `resources/views/profile.blade.php` | Full redesign: CSS classes, bug fix (badge update), `__()` wrapping |
| `resources/lang/en/profile.php` | Verify/add missing translation keys |
| `resources/lang/ar/profile.php` | Verify/add missing Arabic translation keys |

## Known Bugs to Fix

1. **Badge not updating after OTP verify** — JS success handler in profile.blade.php (line ~184) updates the phone text but not the `Verified`/`Unverified` badge element. Must update badge HTML too.
2. **`email_verified_at` not reset** — `ProfileController::update()` saves new email but doesn't reset `email_verified_at = null`.
3. **`phone_verified_at` not cast** — `User::$casts` missing `'phone_verified_at' => 'datetime'`.

## Existing Routes (already registered — do not add again)
```
GET  /profile                 → ProfileController@show
POST /profile                 → ProfileController@update
POST /profile/password        → ProfileController@updatePassword
POST /profile/phone/otp       → OtpController@sendPhoneOtp
POST /profile/phone/verify    → OtpController@verifyPhoneOtp
```

</specifics>

<deferred>
## Deferred

- Email change via OTP (user explicitly said no OTP for email)
- Profile photo / avatar upload
- Two-factor authentication setup
- Account deletion flow
- Notification preferences panel

</deferred>

---

*Phase: 09-enhancements*
*Context gathered: 2026-03-03*
