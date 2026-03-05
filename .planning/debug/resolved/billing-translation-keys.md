---
status: resolved
trigger: "billing-translation-keys"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T10:45:00Z
---

## Current Focus

hypothesis: RESOLVED
test: Browser verified all 5 page/locale combinations — zero raw keys found
expecting: N/A
next_action: Archive

## Symptoms

expected: Billing pages show translated text (English/Arabic) based on locale
actual: Pages showing raw key strings like "billing.plans.title" instead of translated values
errors: No PHP errors — just untranslated keys displayed as-is
reproduction: Visit /billing/plans, /billing/payment-methods, /billing/api-keys while logged in
started: Ongoing issue, part of phase 09-05 (complete website translation)

## Eliminated

- hypothesis: Blade files use hardcoded key strings instead of __() calls
  evidence: plans.blade.php and payment-methods.blade.php correctly use __() and @lang() for all text
  timestamp: 2026-03-05T00:01:00Z

- hypothesis: blade files missing __() wrapper entirely
  evidence: All text is wrapped in __() or @lang(), the issue is in the translation files themselves
  timestamp: 2026-03-05T00:01:00Z

- hypothesis: Blade view cache holding stale compiled output
  evidence: Ran artisan cache:clear + view:clear — problem persisted. Not cache.
  timestamp: 2026-03-05T10:30:00Z

- hypothesis: Translation files missing from server
  evidence: Both lang/en/billing.php and lang/ar/billing.php present and parseable on server
  timestamp: 2026-03-05T10:32:00Z

## Evidence

- timestamp: 2026-03-05T00:01:00Z
  checked: lang/ar/ directory listing
  found: Only admin.php and welcome.php existed — billing.php missing entirely
  implication: Arabic locale had no billing translations at all — every billing.__() call returns raw key string

- timestamp: 2026-03-05T00:01:00Z
  checked: lang/en/billing.php vs keys used in plans.blade.php + payment-methods.blade.php
  found: billing.how_credits_work used in plans.blade.php line 148 but NOT defined in lang/en/billing.php
  implication: English locale showed raw key "billing.how_credits_work" for the How credits work link

- timestamp: 2026-03-05T00:01:00Z
  checked: resources/views/api-keys.blade.php (at /api-keys route, not /billing/api-keys)
  found: File uses hardcoded English strings — no __() calls at all
  implication: api-keys page is untranslated but is a separate issue from the billing pages

- timestamp: 2026-03-05T00:03:00Z
  checked: all blade keys vs both EN and AR billing.php after fixes
  found: All 82 real translation keys (excluding 3 route-name fragments) now present in both files
  implication: Fixes are complete and correct

- timestamp: 2026-03-05T10:28:00Z
  checked: browser verification after first deploy — billing/plans EN still showing raw keys
  found: billing.choose_your_plan, billing.start_monthly_plan, etc. — raw in EN but NOT in AR
  implication: Two separate translation file directories — lang/ and resources/lang/ — are both present

- timestamp: 2026-03-05T10:35:00Z
  checked: find command on server — found resources/lang/en/billing.php AND lang/en/billing.php
  found: resources/lang/ is the OLD translation directory (different key names like choose_plan vs choose_your_plan)
  implication: Laravel loads resources/lang/ first (takes precedence) — our new lang/ files are never reached

- timestamp: 2026-03-05T10:38:00Z
  checked: artisan tinker — __('billing.subscription_plans') vs __('billing.choose_your_plan')
  found: subscription_plans worked (present in BOTH old and new file), choose_your_plan only in new file
  implication: Confirmed — resources/lang/ shadow causes old keys to resolve, new keys to return raw strings

- timestamp: 2026-03-05T10:43:00Z
  checked: browser verification after syncing resources/lang/ with lang/
  found: PASS on all 5 checks (billing EN, billing AR, api-keys EN, api-keys AR, payment-methods EN)
  implication: Fix confirmed working

## Resolution

root_cause: |
  THREE root causes discovered:
  1. lang/ar/billing.php did not exist — Arabic locale returned raw key strings for ALL billing page text
  2. billing.how_credits_work was missing from lang/en/billing.php — English also showed raw key for the "How credits work" link
  3. CRITICAL: Laravel was loading translations from resources/lang/ (old directory) not lang/ (new directory).
     resources/lang/en/billing.php had completely different key names (choose_plan vs choose_your_plan, etc.)
     — causing EN billing pages to show raw keys even after the lang/en/billing.php was correct.
  4. api-keys.blade.php used zero __() calls — hardcoded English strings throughout.

fix: |
  1. Added 'how_credits_work' => 'How credits work' to lang/en/billing.php
  2. Created lang/ar/billing.php with full Arabic translations for all 82 keys
  3. Created lang/en/api_keys.php and lang/ar/api_keys.php (36 keys each)
  4. Replaced all hardcoded English strings in resources/views/api-keys.blade.php with __() calls
  5. Synced resources/lang/en/billing.php and resources/lang/ar/billing.php with the correct key names
  6. Added resources/lang/en/api_keys.php and resources/lang/ar/api_keys.php

verification: |
  Browser-verified via Playwright automation:
  - billing/plans EN: PASS (no raw keys)
  - billing/plans AR: PASS (no raw keys)
  - api-keys EN: PASS (no raw keys)
  - api-keys AR: PASS (no raw keys)
  - payment-methods EN: PASS (no raw keys)
  Also verified via artisan tinker: __('billing.choose_your_plan') = "Choose Your Plan"

files_changed:
  - lang/en/billing.php (added how_credits_work key)
  - lang/ar/billing.php (new file, 82 keys)
  - lang/en/api_keys.php (new file, 36 keys)
  - lang/ar/api_keys.php (new file, 36 keys)
  - resources/views/api-keys.blade.php (all hardcoded strings replaced with __() calls)
  - resources/lang/en/billing.php (synced with correct key names)
  - resources/lang/ar/billing.php (synced with correct key names)
  - resources/lang/en/api_keys.php (new file)
  - resources/lang/ar/api_keys.php (new file)
commits:
  - acf35b4 fix(i18n): add billing AR translations, fix EN missing key, translate api-keys page
  - fb9209d fix(i18n): sync resources/lang with lang/ — translations were loading from old location
