# Phase 09-01 — Homepage Fixes + Arabic Completion

## Goal
Fix all issues found in the 2026-03-03 homepage audit and complete Arabic translation coverage.

---

## HIGH Priority Tasks

### 1. Carousel JS Skip Bug
**File:** `resources/views/welcome.blade.php` (slider JS section)
**Problem:** Next button jumps from slide 1 → slide 3, skipping slide 2.
**Fix:** Find `currentIndex` initialization in the carousel IIFE. It likely starts at 1 or the autoplay fires once on mount. Set `let currentIndex = 0` and ensure no `goTo()` call runs during initialization.

### 2. Fix Credit Top-Up Amounts on Homepage
**File:** `resources/views/welcome.blade.php` (pricing section, top-ups block)
**Problem:** Shows 500 / 1,100 / 3,000 credits. Correct is 5,000 / 15,000 / 50,000.
**Fix:** Find the top-up add-on grid and update the three packages to match config.

### 3. Replace Personal Email in Contact Section
**File:** `resources/views/welcome.blade.php` (contact section)
**Problem:** `soud@alphia.net` hardcoded — should be a support alias.
**Fix:** Replace with `support@resayil.io` (or whatever the official support email is).

### 4. Fix "How It Works" RTL Step Order
**File:** `resources/views/welcome.blade.php` (How It Works section CSS)
**Problem:** In Arabic RTL mode, numbered steps show 3→2→1 visually.
**Fix:** Add CSS: `[dir="rtl"] .how-it-works-grid { flex-direction: row; }` (or the actual class name of that grid container) so numbered sequence stays 1→2→3 left-to-right even in RTL.

---

## MEDIUM Priority Tasks

### 5. Complete Arabic Translations
Sections still needing `__()` coverage:
- `welcome.blade.php` — How It Works headings/steps, Pricing section text, Available Models section, footer CTA ("Ready to get started?")
- Carousel slide content (model names can stay English, but section heading + badges like "Local GPU" / "Cloud Proxy" / "1 credit / token" need translation)
- Contact form labels, placeholders, "Send Message" button
- Dashboard.blade.php — all labels (Opus agent may have handled; verify)
- Billing views — plan names, features, CTAs

**Lang keys to add to `ar/welcome.php`:**
- `how_it_works` → كيف يعمل
- `how_it_works_subtitle` → ثلاث خطوات بسيطة للوصول إلى نماذج AI
- `step_register` → سجّل وأضف رصيداً
- `step_api_key` → احصل على مفتاح API
- `step_make_calls` → ابدأ طلبات API
- `available_models` → النماذج المتاحة
- `available_models_subtitle` → 45+ نموذج من أبرز مختبرات AI
- `ready_to_start` → هل أنت مستعد للبدء؟
- `create_free_account` → إنشاء حساب مجاني
- `local_gpu` → GPU محلي
- `cloud_proxy` → سحابي
- `explore_our_model_lineup` → استكشف نماذجنا

---

## MISSING PAGE

### 6. Create Credits Page (`/credits`)
**Problem:** Route `/credits` exists and is linked in homepage nav but no proper page.
**Requirements:**
- Explain the credit system (1 credit = 1 token local, 2 credits = 1 token cloud)
- Show top-up packages with KNET payment flow
- Show user's current balance (if logged in)
- Show usage history summary
- Bilingual (en/ar)
- Dark luxury theme matching rest of site

---

## LOW Priority

### 7. Footer CTA Arabic Fix
`resources/views/welcome.blade.php` — "Ready to get started?" heading and paragraph need `__()` calls.

### 8. Mobile Testing
Test on 390px viewport:
- Nav overflow (5 items in single row)
- Pricing grid stacking
- Carousel card layout
- Code block horizontal scroll

---

## Execution Order
1. Fix carousel JS bug (10 min)
2. Fix top-up amounts (5 min)
3. Replace personal email (2 min)
4. Fix How It Works RTL (5 min)
5. Complete Arabic translations for welcome.blade.php sections (30 min)
6. Create credits page (1-2 hours)
7. Mobile testing pass

## Files to Change
- `resources/views/welcome.blade.php`
- `resources/lang/ar/welcome.php`
- `resources/lang/en/welcome.php`
- `resources/views/credits.blade.php` (create)
- `routes/web.php` (if credits route needs updating)

## Deploy
Always deploy to dev first: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
Test on https://llmdev.resayil.io before merging to main.
