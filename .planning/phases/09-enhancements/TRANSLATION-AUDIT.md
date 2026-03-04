# Translation Audit Report -- LLM Resayil

**Date:** 2026-03-04
**Branch:** `dev`
**Audited by:** Claude Opus 4.6 (automated deep dive)

---

## Executive Summary

| Metric | Count |
|---|---|
| Total Blade views audited | 27 |
| Views fully translated (COMPLETE) | 12 |
| Views partially translated (PARTIAL) | 6 |
| Views with ZERO translation (HARDCODED) | 9 |
| Estimated hardcoded English strings | ~230 |
| Missing AR translation keys (vs EN) | ~170 |
| Critical structural bugs in lang files | 3 |
| Translation files with corrupted text | 4 |

---

## 1. View-by-View Translation Status

### COMPLETE -- All user-facing strings use `__()` or `@lang()`

| View | Translation namespace(s) | Notes |
|---|---|---|
| `layouts/app.blade.php` | `navigation.*` | Navbar, language switcher, all links |
| `welcome.blade.php` | `welcome.*` | ~130 keys, hero, pricing, CTA, footer |
| `profile.blade.php` | `profile.*` | ~48 keys, JS strings also translated |
| `billing/plans.blade.php` | `billing.*` | Plans, topups, payment modal |
| `contact.blade.php` | `contact.*` | Form, contact info, validation |
| `auth/login.blade.php` | `auth.*` | Login form, links |
| `auth/register.blade.php` | `auth.*` | Registration, OTP flow, JS strings |
| `admin/monitoring.blade.php` | `admin.*` | Stats, tables (keys exist in EN+AR) |
| `admin/api-settings.blade.php` | `admin.*` | All form labels translated (keys exist) |
| `teams/dashboard.blade.php` | `teams.*` | Sidebar, header, includes components |
| `teams/components/add-member-form.blade.php` | `teams.*` | Form labels, buttons |
| `teams/components/team-table.blade.php` | `teams.*` | Table headers, empty state |

### PARTIAL -- Most strings translated, some hardcoded

| View | Namespace | Hardcoded strings | Details |
|---|---|---|---|
| `dashboard.blade.php` | `dashboard.*` | ~12 strings | `Plan:`, `Expires:`, JS: `Loading models...`, `No models available`, `Error loading models`, `No models match your filters`, `Model not found`, `Copied!`, `Please enter your API key`, `Payments processed securely via KNET / credit card` |
| `credits.blade.php` | `credits.*` | ~12 strings | Billing Flow section (lines 781-811): `Billing Flow`, `How a request is charged`, `Every API call follows the same 5-step flow`, `Make Request`, `Call /api/v1/chat/completions`, `Model Responds`, `Response generated`, `Tokens Counted`, `Output tokens measured`, `Credits Deducted`, `From your balance`, `Balance Updated`, `Visible on dashboard`, `Best Value` ribbon |
| `docs.blade.php` | `docs.*` | 3 strings | Hardcoded dates: `March 2, 2026` appears 3 times |
| `billing/payment-methods.blade.php` | `billing.*` | 2 strings | `Exp: ...` prefix on line 69, inline `style` attributes with hardcoded payment brand labels `KNET`, `VISA`, `Mastercard` (though these are brand names, acceptable) |
| `admin/dashboard.blade.php` | `admin.*` | 0 in view | View uses `__('admin.*')` everywhere BUT ~40 keys are MISSING from `en/admin.php` and `ar/admin.php` -- they render as literal key strings |
| `admin/models.blade.php` | `admin.*` | 0 in view | Same problem: ~50 keys used in view MISSING from both EN and AR lang files |

### HARDCODED -- Zero or near-zero `__()` calls, 100% English

| View | Estimated hardcoded strings | Priority |
|---|---|---|
| `about.blade.php` | ~55 strings | HIGH (public-facing) |
| `privacy-policy.blade.php` | ~35 strings | MEDIUM (legal page) |
| `terms-of-service.blade.php` | ~35 strings | MEDIUM (legal page) |
| `errors/401.blade.php` | ~5 strings | LOW (uses `@lang` in title only, body hardcoded) |
| `errors/403.blade.php` | ~5 strings | LOW (entirely hardcoded) |
| `errors/404.blade.php` | ~5 strings | LOW (entirely hardcoded) |
| `errors/500.blade.php` | ~6 strings | LOW (entirely hardcoded) |
| `emails/contact.blade.php` | ~5 strings | LOW (internal email) |
| `teams/components/role-badge.blade.php` | 0 | N/A (uses `@lang`) |

---

## 2. Translation File Inventory

### EN files (`resources/lang/en/`)

| File | Key count | Used by view(s) | Status |
|---|---|---|---|
| `welcome.php` | ~130 | `welcome.blade.php` | OK |
| `dashboard.php` | ~114 | `dashboard.blade.php` | Missing some keys used in view JS |
| `navigation.php` | 28 | `layouts/app.blade.php` | OK |
| `profile.php` | 48 | `profile.blade.php` | OK |
| `billing.php` | ~194 | `billing/plans.blade.php`, `billing/payment-methods.blade.php` | Has duplicate keys |
| `auth.php` | 53 + extras | `auth/login.blade.php`, `auth/register.blade.php` | **SYNTAX ERROR** -- code after closing `];` |
| `admin.php` | 100 | `admin/*.blade.php` | **MISSING ~90 keys** used in admin/dashboard + admin/models |
| `contact.php` | 34 | `contact.blade.php` | OK |
| `credits.php` | 33 | `credits.blade.php` | Missing billing flow keys |
| `docs.php` | ~135 | `docs.blade.php` | OK |
| `about.php` | 57 | `about.blade.php` | **EXISTS but view does NOT use it** (view is hardcoded) |
| `teams.php` | ~81 (with nested) | `teams/*.blade.php` | OK |
| `roles.php` | 2 | (referenced) | OK |
| `actions.php` | 8 | (referenced) | OK |

### AR files (`resources/lang/ar/`)

| File | Key count | Matches EN? | Issues |
|---|---|---|---|
| `welcome.php` | ~130 | Yes | Line 173: Japanese chars in `one_thousand_credits` |
| `dashboard.php` | ~146 | Partial | Has extra keys not in EN; missing some EN keys |
| `navigation.php` | ~14 | **NO** | **CRITICAL: Nested structure** -- keys under `'navigation' => [...]` while EN is flat. All `__('navigation.dashboard')` etc. fail in AR. |
| `profile.php` | 48 | **NO** | **Key name mismatches**: `title`->`my_profile`, `subtitle`->`update_info`, `profile_info`->`profile_information` |
| `billing.php` | ~109 | Partial | Missing ~85 EN keys (trial, plan features, payment methods, etc.) |
| `auth.php` | 53 | Yes | OK (EN has syntax error but AR is clean) |
| `admin.php` | 100 | Yes | Matches EN 1:1 (both missing the ~90 admin dashboard/models keys) |
| `contact.php` | 33 | **NO** | Different key names: EN `get_in_touch`/`full_name`/`email_address`/`mobile_number` vs AR `name`/`email`/`subject` |
| `credits.php` | ~94 | Partial | Has billing flow keys that EN is missing; EN is much smaller |
| `docs.php` | 18 | **NO** | AR has only 18 keys vs EN's ~135 keys -- **missing ~117 keys** |
| `about.php` | 43 | **NO** | Completely different key structure from EN; EN has ~57 keys |
| `teams.php` | ~81 (with nested) | Yes | OK -- well matched |
| `roles.php` | 2 | Yes | OK |
| `actions.php` | 8 | Yes | OK |
| `errors.php` | 26 | EN: N/A | AR-only file; EN error views are hardcoded |
| `legal.php` | ~100 | EN: N/A | AR-only umbrella file for about/privacy/terms |
| `privacy.php` | ~39 | EN: N/A | AR-only; EN view is hardcoded |
| `terms.php` | ~39 | EN: N/A | AR-only; EN view is hardcoded |

---

## 3. Critical Bugs

### BUG 1: AR `navigation.php` -- Nested vs flat structure

**File:** `resources/lang/ar/navigation.php`
**Severity:** CRITICAL -- Arabic navigation completely broken

```php
// EN (correct -- flat keys):
return [
    'dashboard' => 'Dashboard',
    'api_keys'  => 'API Keys',
    ...
];

// AR (WRONG -- nested):
return [
    'navigation' => [
        'dashboard' => '...',   // __('navigation.dashboard') looks for flat key, not nested
        'api_keys'  => '...',
    ],
    'brand'    => 'LLM Resayil',  // This flat key works
    'language' => '...',           // This flat key works
];
```

**Impact:** All navbar links (`Dashboard`, `API Keys`, `Billing`, etc.) show English fallback keys when locale is AR. Only `Language`, `English`, `Arabic`, and `Brand` display in Arabic.

**Missing flat keys in AR:** `about`, `dashboard`, `api_keys`, `billing`, `credits`, `payment_methods`, `team`, `profile`, `logout`, `login`, `get_started`, `docs`, `contact`, `admin`, `monitor`, `models`, `portal_title`, `og_description_default`, `og_description_short`, `meta_description_default`

### BUG 2: AR `profile.php` -- Mismatched key names

**File:** `resources/lang/ar/profile.php`
**Severity:** HIGH -- Profile page title and sections show English in AR

| View uses | EN key | AR key (wrong) |
|---|---|---|
| `__('profile.title')` | `title` => `My Profile` | `my_profile` => `...` |
| `__('profile.subtitle')` | `subtitle` => `Update your name...` | `update_info` => `...` |
| `__('profile.profile_info')` | `profile_info` => `Profile Information` | `profile_information` => `...` |

### BUG 3: EN `auth.php` -- PHP syntax error

**File:** `resources/lang/en/auth.php`
**Severity:** HIGH -- File has code after closing `];` on line 53

```php
// Line 53: ];        <-- Array ends here
// Lines 55-84: Additional key definitions OUTSIDE the returned array
    'register' => 'Register',    // <-- These are NOT in the array
    'create_account' => '...',
    // ... ~30 more keys
];                                // <-- Syntax error: unexpected ];
```

**Impact:** PHP parse error when including this file. Laravel may silently fail or crash. The duplicate keys after line 53 (OTP verification section) are not being loaded.

---

## 4. Corrupted Arabic Text

Several AR translation files contain corrupted characters (Japanese/Chinese characters mixed into Arabic text):

| File | Line | Key | Corrupted text |
|---|---|---|---|
| `ar/welcome.php` | 173 | `one_thousand_credits` | `1,000 رصيد م含まれ` (Japanese chars) |
| `ar/about.php` | 14 | `our_team_desc` | `ي shareوا` (English mixed in) |
| `ar/about.php` | 18 | `why_openai_compat_desc` | `切换 بسيطة` (Chinese chars) |
| `ar/legal.php` | 22 | `why_openai_compat_desc` | `切换 بسيطة` (Chinese chars) |
| `ar/credits.php` | 62 | `api_models_endpoint` | `终结 /api/v1/models` (Chinese chars) |
| `ar/privacy.php` | 5 | `subtitle` | `保护 بياناتك` (Chinese chars) |
| `ar/privacy.php` | 23 | `third_party_desc` | `لل行 المطلوبة` (Chinese chars) |
| `ar/legal.php` | 90 | `limitation_of_liability_desc` | `ت(result` (broken code) |
| `ar/terms.php` | 29 | `limitation_of_liability_desc` | `ت(result` (broken code) |
| `ar/billing.php` | 10 | `auto_bill_to_starter` | `تautomatic إعادة` (English mixed in) |
| `ar/billing.php` | 62 | `manage_your_methods` | `إدارة طرق الدفع للilles` (French chars) |
| `ar/billing.php` | 40 | `priority_cloud_failover` | `.failover سحابي` (English dot prefix) |
| `ar/terms.php` | 17 | `payments_desc` | `مfqوعة شهرياً` (corrupted) |
| `ar/legal.php` | 78 | `payments_desc` | `مfqوعة شهرياً` (corrupted) |

---

## 5. Missing Translation Keys (EN keys not in AR)

### navigation.php -- 20 missing AR keys
All flat keys missing (see Bug 1): `about`, `dashboard`, `api_keys`, `billing`, `credits`, `payment_methods`, `team`, `profile`, `logout`, `login`, `get_started`, `docs`, `contact`, `admin`, `monitor`, `models`, `portal_title`, `og_description_default`, `og_description_short`, `meta_description_default`

### admin.php -- ~90 missing keys (both EN and AR)
Keys used in `admin/dashboard.blade.php`: `total_users`, `active_subscriptions`, `total_api_calls`, `cloud_budget_used`, `all_users`, `name_email`, `phone`, `plan`, `joined`, `actions`, `credits_action`, `tier_action`, `expiry_action`, `api_key_action`, `set_credits`, `user_id`, `enter_credits`, `cancel`, `save`, `set_subscription_tier`, `tier`, `starter`, `basic`, `pro`, `set_subscription_expiry`, `expiry_date`, `clear_expiry`, `create_api_key`, `key_name`, `enter_key_name`, `admin_created_key`, `api_key_created`, `copy_to_clipboard`, `close`, `create_key`, `invalid_credits`, `credits_error`, `tier_error`, `expiry_error`, `key_error`, `key_copied`

Keys used in `admin/models.blade.php`: `model_management`, `configure_models`, `total_models`, `active_models`, `local_models`, `cloud_models`, `family`, `category`, `type`, `size`, `all`, `chat`, `code`, `embedding`, `vision`, `thinking`, `tools`, `local`, `cloud`, `small`, `medium`, `large`, `search_models`, `enable`, `disable`, `model_id`, `multiplier`, `status`, `override_label`, `edit`, `edit_model_settings`, `credit_multiplier_override`, `leave_empty_default`, `use_default_from_config`, `active`, `inactive`, `save_changes`, `model_enabled`, `model_disabled`, `update_failed`, `select_models_update`, `enable_models`, `showing`, `of`, `models_page`, `saving`, `model_settings_saved`, `failed_save`

### billing.php -- ~85 missing AR keys
`title`, `billing`, `top_up`, `free_trial_active`, `trial_expires`, `current_plan_label`, `renews_date`, `choose_plan`, `plan`, `choose_plan_subtitle`, `credits_label`, `1_free_key`, `how_credits_work`, `start_trial_card`, `trial_payment_note`, `enterprise`, `contact_us`, `start_monthly`, `billed_monthly`, `1_key`, `2_keys`, `unlimited_keys`, `small_models`, `all_sizes`, `priority_failover`, `10_requests_min`, `30_requests_min`, `60_requests_min`, `bonus_10`, `bonus_20`, `free`, `included_with_plan`, `one_time_purchase`, `create_free_key`, `buy_extra_key`, `max_keys_reached`, `choose_payment`, `select_how_to_pay`, `loading_methods`, `trial_desc`, `subscription_desc`, `topup_desc`, `extra_key_desc`, `manage_saved_methods`, `exp`, `confirm_delete_method`, `no_methods_saved`, `add_card_desc`, `redirect_secure`, `add_card_btn`, `card_verification`, `payment_security`, `security_text`, and many duplicate/aliased keys

### docs.php -- ~117 missing AR keys
AR has only 18 keys. Missing nearly all content: `title`, `subtitle`, `overview`, `quick_start`, `authentication`, `error_handling`, `models_endpoint`, `chat_completions`, `usage_examples`, `python_sdk`, `node_sdk`, `curl_examples`, `response_format`, `streaming`, `rate_limits`, `billing`, `error_codes`, `back_to_home`, `endpoint`, `method`, `description`, `example_request`, `example_response`, `parameters`, `required`, `optional`, `type`, `default`, `auth_header`, `auth_description`, `api_key`, `bearer_token`, and ~85 more

### contact.php -- ~20 missing AR keys
AR uses different key names. Missing from AR: `get_in_touch`, `full_name`, `email_address`, `mobile_number`, `send_message`, `send_message_btn`, `message_sent`, `message_success`, `available_on_request`, `respond_24h`, `use_contact_form`, `placeholder_full_name`, `placeholder_email`, `placeholder_mobile`, `placeholder_message`, `need_help_title`, `need_help_desc`, `contact_form_response`, `validation_required`, `validation_email`, `validation_phone`, `support`

### credits.php -- ~15 missing EN keys
EN credits.php (33 keys) is much smaller than AR (94 keys). AR has billing flow keys that EN is missing: `billing_flow`, `how_request_charged`, `every_api_call`, `make_request`, `call_api`, `model_responds`, `response_generated`, `tokens_counted`, `tokens_measured`, `credits_deducted`, `from_balance`, `balance_updated`, `visible_dashboard`, `best_value`, and many more

### about.php -- Completely mismatched
EN has 57 keys (matching current hardcoded about.blade.php content). AR has 43 keys with a DIFFERENT structure. The about.blade.php view does NOT use `__()` at all, so neither file is consumed.

---

## 6. Views That Need EN Translation Files Created

| View | Needs EN file | Reason |
|---|---|---|
| `errors/401.blade.php` | `en/errors.php` | AR has `errors.php`, EN does not |
| `errors/403.blade.php` | `en/errors.php` | Same |
| `errors/404.blade.php` | `en/errors.php` | Same |
| `errors/500.blade.php` | `en/errors.php` | Same |
| `privacy-policy.blade.php` | `en/privacy.php` | AR has `privacy.php`, EN does not |
| `terms-of-service.blade.php` | `en/terms.php` | AR has `terms.php`, EN does not |

---

## 7. Summary by Priority

### P0 -- Critical (Arabic site broken)
1. Fix AR `navigation.php` nested structure -> flat keys (AR navbar entirely broken)
2. Fix AR `profile.php` key name mismatches (3 keys)
3. Fix EN `auth.php` syntax error (code after `];`)

### P1 -- High (Public pages hardcoded)
4. Convert `about.blade.php` to use `__('about.*')` (55 strings)
5. Add ~90 missing keys to EN+AR `admin.php` for admin/dashboard + admin/models
6. Add ~85 missing AR keys to `billing.php`
7. Add ~117 missing AR keys to `docs.php`

### P2 -- Medium (Partial pages)
8. Translate hardcoded strings in `dashboard.blade.php` (~12 strings)
9. Translate hardcoded strings in `credits.blade.php` billing flow (~12 strings)
10. Convert `privacy-policy.blade.php` to use `__('privacy.*')` (35 strings)
11. Convert `terms-of-service.blade.php` to use `__('terms.*')` (35 strings)
12. Fix AR `contact.php` key mismatches with EN (~20 keys)

### P3 -- Low (Error pages, email)
13. Convert error views to use `__('errors.*')` + create EN `errors.php` (4 views, ~20 strings)
14. Fix corrupted Arabic text in 5 files (~14 strings)
15. Clean up `docs.blade.php` hardcoded dates
16. Translate `emails/contact.blade.php` (5 strings, low visibility)

---

## 8. Files to Modify (Complete List)

### Translation files to modify:
- `resources/lang/ar/navigation.php` -- flatten structure, add 20 keys
- `resources/lang/ar/profile.php` -- fix 3 key names
- `resources/lang/en/auth.php` -- fix syntax error
- `resources/lang/en/admin.php` -- add ~90 keys
- `resources/lang/ar/admin.php` -- add ~90 keys
- `resources/lang/ar/billing.php` -- add ~85 keys
- `resources/lang/ar/docs.php` -- add ~117 keys
- `resources/lang/ar/contact.php` -- add ~20 keys + fix key names
- `resources/lang/en/credits.php` -- add ~15 billing flow keys
- `resources/lang/en/errors.php` -- CREATE (26 keys)
- `resources/lang/ar/welcome.php` -- fix corrupted text (1 line)
- `resources/lang/ar/about.php` -- fix corrupted text + restructure to match EN
- `resources/lang/ar/credits.php` -- fix corrupted text (1 line)
- `resources/lang/ar/privacy.php` -- fix corrupted text (2 lines)
- `resources/lang/ar/terms.php` -- fix corrupted text (1 line)
- `resources/lang/ar/legal.php` -- fix corrupted text (3 lines)

### Blade views to modify:
- `resources/views/about.blade.php` -- wrap ~55 strings in `__('about.*')`
- `resources/views/privacy-policy.blade.php` -- wrap ~35 strings in `__('privacy.*')`
- `resources/views/terms-of-service.blade.php` -- wrap ~35 strings in `__('terms.*')`
- `resources/views/dashboard.blade.php` -- wrap ~12 strings in `__('dashboard.*')`
- `resources/views/credits.blade.php` -- wrap ~12 strings in `__('credits.*')`
- `resources/views/docs.blade.php` -- replace 3 hardcoded dates
- `resources/views/errors/401.blade.php` -- wrap ~5 strings in `__('errors.*')`
- `resources/views/errors/403.blade.php` -- wrap ~5 strings
- `resources/views/errors/404.blade.php` -- wrap ~5 strings
- `resources/views/errors/500.blade.php` -- wrap ~6 strings
- `resources/views/emails/contact.blade.php` -- wrap ~5 strings (optional)
