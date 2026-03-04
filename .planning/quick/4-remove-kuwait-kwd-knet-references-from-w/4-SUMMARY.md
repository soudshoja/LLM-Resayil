---
phase: quick-4
plan: 01
subsystem: i18n / marketing-copy
tags: [copy, localization, internationalization, welcome-page]
dependency_graph:
  requires: []
  provides: [COPY-01]
  affects: [resources/lang/en/welcome.php, resources/lang/ar/welcome.php]
tech_stack:
  added: []
  patterns: [Laravel localization, PHP translation arrays]
key_files:
  created: []
  modified:
    - resources/lang/en/welcome.php
    - resources/lang/ar/welcome.php
decisions:
  - "KWD pricing amount keys (five_kwd, ten_kwd, twenty_five_kwd, 15_kwd_month) preserved unchanged — product still priced in KWD but marketing copy is now globally neutral"
  - "join_developers key appears twice in both EN and AR files (duplicate PHP array key — last one wins); both occurrences updated for consistency"
  - "AR hero headline restructured from Kuwait-specific 'منصة API للنماذج اللغوية المميزة في الكويت' to global 'المنصة الأولى / API للمطورين / عالي الأداء'"
metrics:
  duration: "~4 minutes"
  completed: "2026-03-04T17:04:46Z"
  tasks_completed: 2
  tasks_total: 2
  files_changed: 2
---

# Quick Task 4: Remove Kuwait/KWD/KNET References from Welcome Page — Summary

**One-liner:** Replaced all Kuwait/KNET marketing prose in EN and AR welcome.php with globally-neutral developer-first copy while preserving KWD pricing amount keys unchanged.

## What Was Changed

### Task 1 — EN welcome.php

| Key | From | To |
|-----|------|----|
| `title` | `LLM Resayil - OpenAI-Compatible LLM API` | `LLM Resayil — OpenAI-Compatible LLM API Gateway` |
| `subtitle` | `...Pay Per Use in Kuwaiti Dinar` | `...Pay Per Use, Credit-Based` |
| `hero_subtitle` (line 7) | `...all in Kuwait Dinar.` | `...no subscriptions, no commitments.` |
| `register_top_up_desc` | `...via KNET or credit card.` | `...via secure online payment.` |
| `all_prices_kwd` | `All prices in Kuwaiti Dinar...` | `Simple credit-based pricing...` |
| `join_developers` (line 80) | `...Pay with KNET or credit card.` | `Sign up free and start calling models in minutes.` |
| `step_1_desc` | `...via KNET or credit card.` | `...using your preferred payment method.` |
| `step_1_description` | `...via KNET or credit card.` | `...using your preferred payment method.` |
| `card_required_trial` | `...via KNET / credit card.` | `...via credit card.` |
| `payments_secure` | `...via KNET / credit card` | `...via credit card` |
| `hero_headline_before` | `Kuwait's Premium` | `The Developer-First` |
| `hero_subtitle` (line 244) | `...priced in Kuwaiti Dinar.` | `...no subscriptions, no commitments.` |
| `trust_kuwait_based` | `Kuwait-Based` | `High-Performance Inference` |
| `pricing_subtitle` | `All prices in Kuwaiti Dinar...` | `Simple credit-based pricing...` |
| `join_developers` (line 277) | `...Pay with KNET or credit card.` | `Sign up free and start calling models in minutes.` |

### Task 2 — AR welcome.php

| Key | From | To |
|-----|------|----|
| `subtitle` | `...بالدينار الكويتي` | `...بنظام الرصيد` |
| `hero_subtitle` (line 7) | `...كل ذلك بالدينار الكويتي.` | `...بدون اشتراكات أو التزامات.` |
| `register_top_up_desc` | `...عبر KNET أو بطاقة الائتمان.` | `...عبر الدفع الإلكتروني الآمن.` |
| `all_prices_kwd` | `جميع الأسعار بالدينار الكويتي...` | `أسعار شفافة بنظام الرصيد...` |
| `join_developers` (line 80) | `...ادفع عبر KNET أو بطاقة ائتمان.` | `...سجّل مجانًا وابدأ استدعاء النماذج في دقائق.` |
| `step_1_desc` | `...عبر KNET أو بطاقة الائتمان.` | `...طريقة الدفع المفضلة لديك.` |
| `step_1_description` | `...عبر KNET أو بطاقة الائتمان.` | `...طريقة الدفع المفضلة لديك.` |
| `card_required_trial` | `...عبر KNET أو بطاقة الائتمان.` | `...عبر بطاقة الائتمان.` |
| `payments_secure` | `...عبر KNET / بطاقة الائتمان` | `...عبر بطاقة الائتمان` |
| `join_developers` (line 239) | `...ادفع عبر KNET أو بطاقة ائتمان.` | `...سجّل مجانًا وابدأ استدعاء النماذج في دقائق.` |
| `hero_headline_before` | `منصة` | `المنصة الأولى` |
| `hero_headline_gold` | `API للنماذج اللغوية` | `API للمطورين` |
| `hero_headline_after` | `المميزة في الكويت` | `عالي الأداء` |
| `hero_subtitle` (line 244) | `...بالدينار الكويتي.` | `...بدون اشتراكات أو التزامات.` |
| `trust_kuwait_based` | `مقره الكويت` | `استدلال عالي الأداء` |
| `pricing_subtitle` | `جميع الأسعار بالدينار الكويتي...` | `أسعار شفافة بنظام الرصيد...` |

## Verification Output

### EN Kuwait/KNET prose check
```
grep -n "Kuwait\|KNET\|Kuwaiti" resources/lang/en/welcome.php
(no output — CLEAN)
```

### AR Kuwait/KNET prose check (excluding KWD pricing keys)
```
grep -n "كويتي\|KNET\|الكويت\|Kuwait" resources/lang/ar/welcome.php \
  | grep -v "five_kwd\|ten_kwd\|twenty_five_kwd\|15_kwd_month\|kwd_month"
(no output — CLEAN)
```

### KWD Pricing Keys Preserved
```
resources/lang/en/welcome.php:34:    '15_kwd_month' => '15 KWD / month',
resources/lang/en/welcome.php:199:    'five_kwd' => '5 KWD',
resources/lang/en/welcome.php:201:    'ten_kwd' => '10 KWD',
resources/lang/en/welcome.php:204:    'twenty_five_kwd' => '25 KWD',
resources/lang/ar/welcome.php:34:    '15_kwd_month' => '15 دينار كويتي / شهر',
resources/lang/ar/welcome.php:199:    'five_kwd' => '5 دينار كويتي',
resources/lang/ar/welcome.php:201:    'ten_kwd' => '10 دينار كويتي',
resources/lang/ar/welcome.php:204:    'twenty_five_kwd' => '25 دينار كويتي',
```

### Key String Checks
```
EN hero_headline_before: "The Developer-First"    ✓
EN trust_kuwait_based:   "High-Performance Inference"    ✓
AR trust_kuwait_based:   "استدلال عالي الأداء"    ✓
AR hero_headline_after:  "عالي الأداء"    ✓ (no longer contains الكويت)
```

## Deviations from Plan

None — plan executed exactly as written. The second `join_developers` in the AR file (at line 239, before the hero headline block) was confirmed present and updated (the plan noted it might not exist — it did).

## Commits

| Task | Commit | Message |
|------|--------|---------|
| Task 1 — EN | `9e24d7e` | `chore(quick-4): remove Kuwait/KNET references from EN welcome.php` |
| Task 2 — AR | `b2bdca6` | `chore(quick-4): remove Kuwait/KNET references from AR welcome.php` |

## Self-Check: PASSED

- `resources/lang/en/welcome.php` — modified, committed `9e24d7e` ✓
- `resources/lang/ar/welcome.php` — modified, committed `b2bdca6` ✓
- Both files grep-verified clean of Kuwait/KNET marketing prose ✓
- KWD pricing keys intact in both files ✓
