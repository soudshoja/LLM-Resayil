# Dev → Prod Merge Risk Report

**Date:** 2026-03-05
**Dev HEAD:** 8496db0
**Prod HEAD (main):** 351857a
**Commits on dev not on main:** 34 commits
**Commits on main not on dev:** 10 commits (true bi-directional divergence)
**Net new app files changed by dev:** 28 non-planning files

---

## VERDICT

**NO-GO — one manual pre-flight step required before merging.**

The merge is structurally sound (all migrations safe, no new env vars, no schema blockers), but one file will produce a git merge conflict that must be resolved manually, and the welcome page redesign requires explicit user sign-off before it appears on prod. Neither issue is a technical emergency — both have clear, fast resolutions. Complete the pre-flight checklist below, then execute the merge commands.

---

## Pre-Flight Checklist (must complete before deploy)

1. **Review welcome page on dev** — Visit https://llmdev.resayil.io and confirm the redesigned `welcome.blade.php` is acceptable for prod. Specifically check: hero copy, how-it-works steps, pricing tiers (Starter 15 KWD / Basic 25 KWD / Pro 45 KWD), and no region-specific copy that was not intentional.

2. **Confirm soud@alphia.net exists on prod DB** — After merge, `User::isAdmin()` will grant full admin bypass to this account on prod. If the account does not yet exist on prod, create it via the registration flow at https://llm.resayil.io/register before merging, then confirm admin access works.

3. **Merge with the single conflict resolved** — When running `git merge dev`, git will halt on `resources/views/landing/template-3.blade.php`. Run the exact command shown in the Merge Commands section below to take dev's version.

4. **Run migration after deploy** — `deploy.sh prod` already runs `php artisan migrate --force`. No manual SQL needed. Confirm it completes without errors (check the deploy output).

---

## Risk Analysis

### Risk 1: Merge Conflict — template-3.blade.php
**Severity:** BLOCKER (merge will halt without resolution)
**File:** `resources/views/landing/template-3.blade.php`
**Issue:** Main has a 1,895-line original version (with isProduction GA wrapping applied). Dev has a redesigned 976-line version. The branches diverged on this file from both sides — main applied two GA-related commits directly to its copy, while dev rewrote the file entirely. Git cannot auto-merge these.
**Fix:** Immediately after running `git merge dev`, resolve with:
```bash
git checkout --theirs resources/views/landing/template-3.blade.php
git add resources/views/landing/template-3.blade.php
```
Dev's version already contains the GA isProduction wrap (verified) — taking `--theirs` (dev) is correct and safe.
**Status:** Known, has exact resolution.

---

### Risk 2: subscription_tier Enum Schema Drift
**Severity:** MEDIUM (not a merge blocker, ongoing schema debt)
**Issue:** The `users` table `subscription_tier` enum was defined in the original migration as `['basic', 'pro', 'enterprise']` only. The `'starter'` and `'admin'` values were added manually to both prod and dev databases — no migration file covers these values.
- `deploy.sh` uses `migrate --force` (incremental) so the merge itself will NOT cause a 500 error.
- However, if `php artisan migrate:fresh` is ever run on prod, existing users with `starter` or `admin` tier will have invalid enum values and queries against those rows will fail.
- New developer environments cloning the repo cannot use starter/admin tiers without manual SQL.

**Remediation SQL** (run once per environment, not via deploy — manual only):
```sql
ALTER TABLE users
  MODIFY COLUMN subscription_tier
  ENUM('basic','pro','enterprise','starter','admin')
  DEFAULT 'basic';
```

**Migration file to create (post-merge, non-urgent):**
```php
// database/migrations/2026_03_06_000001_extend_subscription_tier_enum.php
DB::statement("ALTER TABLE users MODIFY COLUMN subscription_tier ENUM('basic','pro','enterprise','starter','admin') DEFAULT 'basic'");
```
**Status:** Not a merge blocker. Schedule as a follow-up task.

---

### Risk 3: Welcome Page Redesign Needs Visual Sign-Off
**Severity:** LOW (no technical risk, UX/content decision)
**Issue:** The welcome page (`/`) was fully redesigned on dev starting from commit `a5adcf2` and further refined through `be2c473`. A todo file explicitly flags this: `.planning/todos/pending/2026-03-05-review-welcome-page-redesign-before-prod-merge.md`. The redesign is a complete rewrite from 667 lines (main) to 644 lines (dev) with a new dark-luxury hero, slider card, steps section, and pricing grid.
**File:** `resources/views/welcome.blade.php`
**Confirm these on https://llmdev.resayil.io:**
- Hero headline and subheading look polished and non-technical
- "How It Works" steps reflect actual product flow (OTP signup → top-up → API key → plug in)
- Pricing tiers: Starter 15 KWD, Basic 25 KWD, Pro 45 KWD (4-column grid)
- No hardcoded Kuwait/KNET copy that should not be on prod
- Trial section and CTA buttons work correctly
**Status:** User decision required before merge (pre-flight step 1 above).

---

### Risk 4: soud@alphia.net Admin Access Arrives on Prod
**Severity:** INFO (intentional, verify account)
**Issue:** `User::isAdmin()` on dev returns `true` for both `admin@llm.resayil.io` AND `soud@alphia.net`. After merge, `soud@alphia.net` will have full admin bypass on prod including:
- Rate limit bypass on API calls
- Credit check bypass (unlimited free API usage)
- Navigation links to `/admin`, `/admin/monitoring`, `/admin/models`
- Unlimited API key creation without payment flow

This change was intentional and requested. The only risk is if the account does not yet exist on prod DB — the code will grant admin privileges to any login from that email address, but the account must exist.

**Prerequisite:** If `soud@alphia.net` account does not exist on prod, register it at https://llm.resayil.io/register (or insert directly into DB) before merging. Suggested password from MEMORY.md: `SoudAdmin2026!`. Subscription tier should be set to `admin` via direct DB update after registration.

**Status:** Intentional change. Confirm account exists on prod before merging.

---

### Risk 5: OllamaProxy Format Change Affects Token Counting
**Severity:** INFO (correctness improvement, not a risk)
**Issue:** `ChatCompletionsController` was updated to read token counts from `$content['usage']['prompt_tokens']` and `$content['usage']['completion_tokens']` (OpenAI format) instead of the old `$content['prompt_eval_count']` and `$content['eval_count']` (Ollama native format). This is coordinated with the OllamaProxy BUG-01 fix that now transforms the response to OpenAI format before returning it. Both changes ship together in the same merge — they are consistent.

**If anything went wrong:** Usage log entries would have 0 tokens (not credits — `tokens_used` in `usage_logs`). The fallback path (`mb_strlen / 3`) would activate. This would under-count but not crash.

**Status:** Changes are mutually consistent. No action required.

---

## Migration Status — Full Inventory

All 17 migration files are present on both branches. No new migrations exist on dev that are not on main.

| # | Migration File | On Dev | On Main | Ran on Prod | Notes |
|---|---------------|--------|---------|-------------|-------|
| 1 | 2024_02_26_000001_create_users_table.php | ✓ | ✓ | ✓ | enum only has basic/pro/enterprise — see Risk 2 |
| 2 | 2024_02_26_000002_create_api_keys_table.php | ✓ | ✓ | ✓ | |
| 3 | 2024_02_26_000003_create_subscriptions_table.php | ✓ | ✓ | ✓ | |
| 4 | 2024_02_26_000004_create_notification_templates_table.php | ✓ | ✓ | ✓ | |
| 5 | 2024_02_26_000005_create_notifications_table.php | ✓ | ✓ | ✓ | |
| 6 | 2024_02_26_100001_create_usage_logs_table.php | ✓ | ✓ | ✓ | |
| 7 | 2024_02_26_100002_create_cloud_budgets_table.php | ✓ | ✓ | ✓ | |
| 8 | 2026_02_26_063647_create_topup_purchases_table.php | ✓ | ✓ | ✓ | |
| 9 | 2026_02_26_072500_create_team_members_table.php | ✓ | ✓ | ✓ | |
| 10 | 2026_02_26_143253_create_jobs_table.php | ✓ | ✓ | ✓ | |
| 11 | 2026_03_02_000001_add_status_to_api_keys_table.php | ✓ | ✓ | ✓ | |
| 12 | 2026_03_02_032411_create_models_table.php | ✓ | ✓ | ✓ | |
| 13 | 2026_03_02_054432_add_phone_verified_at_to_users_table.php | ✓ | ✓ | ✓ | |
| 14 | 2026_03_02_054436_create_otp_codes_table.php | ✓ | ✓ | ✓ | |
| 15 | 2026_03_02_064849_add_trial_fields_to_users_table.php | ✓ | ✓ | ✓ | |
| 16 | 2026_03_02_200000_add_myfatoorah_fields_to_users_table.php | ✓ | ✓ | ✓ | |
| 17 | 2026_03_04_000001_add_token_split_to_usage_logs.php | ✓ | ✓ | ✓ | adds prompt_tokens + completion_tokens cols; already on main, already ran on prod |

**Conclusion: Zero new migrations. No pre-migrate step needed. `deploy.sh prod` will find nothing to run and skip cleanly.**

---

## Schema Changes Summary

| Table | Column / Change | Type | Status |
|-------|----------------|------|--------|
| usage_logs | prompt_tokens (nullable int) | new column | Already on prod (migration ran when 351857a was deployed) |
| usage_logs | completion_tokens (nullable int) | new column | Already on prod |
| users | subscription_tier enum | missing 'starter' and 'admin' values | Schema drift — added manually, NOT via migration (see Risk 2) |

No new tables. No index changes. No foreign key changes.

---

## Environment Variable Changes

Zero new `env()` calls in the dev codebase vs main. No `.env` or `.env.example` changes in the diff. Prod's `.env` does not need to change before this merge.

| Variable | Status |
|----------|--------|
| All existing vars | Unchanged |
| New vars added | None |

---

## Code Categorized by Category

### Category A: Critical Bug Fixes (push to prod immediately)

| Commit | Change | Impact |
|--------|--------|--------|
| `aaa9e34` | OllamaProxy: streaming now emits OpenAI SSE format | Fixes all SDK clients using streaming |
| `71f96be` | OllamaProxy: non-streaming transforms to OpenAI format, removes double logUsage | Fixes non-streaming SDK clients + prevents double billing |
| `255b881` | Handler.php: web users get login redirect instead of JSON 401 | Fixes web UX for unauthenticated access |
| `0ee7106` | AuthenticatedSessionController: remove `exists:users` validation | Fixes BUG-04 — invalid credentials now returns correct message |
| `142b2e7` + `2d3a01f` | RegisteredUserController: fix Log import, add country code selector | Fixes PHP error on registration |
| `be360dc` | Dashboard: pass `:name` param to welcome_back translation | Fixes literal ":name" appearing in heading |
| `f17fa94` | Dashboard: pre-compute `$dashLang` in @php block | Fixes Blade `@json` compiler crash |
| `f118f9d` | Credits page: close code-box div, fix bullet tag | Fixes broken HTML layout |

### Category B: Features (safe to deploy, additive)

| Commit | Change | Impact |
|--------|--------|--------|
| `91de9fc` | User::isAdmin() method, soud@alphia.net admin access | Additive method, new admin account |
| `429fe06` + `19c5847` | Dashboard: "Our Cost ($)" column in usage table | Dashboard enhancement only |
| `d9fc146` | 3 landing page templates added | New routes /landing/1,2,3 — no auth required |
| `228833e` | ApiKeysController: web view for GET /api-keys | Replaces JSON response with proper Blade view |
| `0fc5a7a` | Email: plain HTML instead of mail component | Fixes registration email on prod |

### Category C: Content / Translation (safe, additive)

| Commit | Change | Impact |
|--------|--------|--------|
| `acf35b4` + `fb9209d` | Billing AR translations, api-keys translations, lang/ sync | All additive keys, no existing key renamed |
| `9e24d7e` + `b2bdca6` | Remove Kuwait/KNET references from welcome lang files | Content correctness |
| `1addf2d` + `8dca914` | How-it-works section rewrite | Reflects actual product flow |

### Category D: User Decision Required

| Commit | Change | Risk |
|--------|--------|------|
| `a5adcf2` + `be2c473` | Welcome page full redesign | Content must be reviewed before prod (pre-flight step 1) |

### Category E: Documentation Only (no prod impact)

| Commits | Content |
|---------|---------|
| `8496db0`, `a1acd6a`, `1a5d004`, `a64d0f0`, `b35bca5`, `0d5c4d9`, `e13ee2d` | .planning/ files only — never deployed to prod |

---

## Merge Conflict Detail

**Only one file will conflict:** `resources/views/landing/template-3.blade.php`

- Main version: 1,895 lines (original template + GA tag applied in-place)
- Dev version: 976 lines (full redesign for end users, already includes GA isProduction wrap)
- Resolution: always take dev's version — it is the current authoritative version

**All other overlapping files auto-resolve** because the main-only commits (GA tag, dashboard fixes) were subsequently also applied to dev with equivalent or superseding changes. Git will merge these cleanly.

---

## Safe Changes (no action required)

| Change | Why Safe |
|--------|----------|
| Migration 2026_03_04 (token split) | Already on main; ran on prod when 351857a was deployed. Zero risk. |
| isAdmin() refactor across 8 files | Complete and correct on dev. MEMORY.md note about linter revert is outdated — verified all 8 files use isAdmin() on dev. |
| OllamaProxy BUG-01/BUG-03 fix | Streaming emits correct OpenAI SSE; non-streaming transforms correctly. Token counting updated consistently in ChatCompletionsController. |
| Handler.php exception routing | Web users get redirects; API routes still return JSON. Correct and safe. |
| ApiKeysController web view | Additive — web users now get Blade view instead of JSON dump. |
| Translation files (lang/ and resources/lang/) | All changes are additive keys. No existing key removed or renamed. Dual directory structure is intentional and kept in sync. |
| CostService our_cost_usd | Dashboard cosmetic column only. No schema dependency. |
| routes/web.php /landing/1,2,3 | Unauthenticated blade views. No side effects. |
| Google Analytics (GA) tag | Already on prod in both app.blade.php AND all landing templates. Dev version is identical — wrapped in @if(app()->isProduction()). |
| No new env vars | git diff main..dev -- .env .env.example is empty. Confirmed. |

---

## Post-Merge Verification Steps

Run these after `deploy.sh prod` completes:

1. **API format check:**
   ```bash
   curl -s -X POST https://llm.resayil.io/api/v1/chat/completions \
     -H "Authorization: Bearer YOUR_API_KEY" \
     -H "Content-Type: application/json" \
     -d '{"model":"llama3.2:3b","messages":[{"role":"user","content":"hi"}]}' \
     | jq '.choices[0].message.content'
   ```
   Expected: quoted string response, not an Ollama-native JSON object.

2. **Admin nav check (soud@alphia.net):** Log in at https://llm.resayil.io. Confirm nav shows Admin, Monitor, and Models links.

3. **Billing plans check:** Visit https://llm.resayil.io/billing/plans. Confirm no raw `billing.xxx` translation keys are visible.

4. **Welcome page check:** Visit https://llm.resayil.io. Confirm the redesigned welcome page renders correctly.

5. **Migration confirmation:** Check deploy output for `php artisan migrate --force` — should say "Nothing to migrate" or run cleanly.

6. **Schedule subscription_tier migration:** Create `2026_03_06_000001_extend_subscription_tier_enum.php` as a follow-up task to permanently fix the enum schema drift.

---

## Merge Commands (exact sequence)

```bash
# 1. On your local machine — ensure you are on main with a clean tree
git checkout main
git status   # should be clean

# 2. Run the merge
git merge dev

# 3. git will HALT with a conflict on template-3.blade.php
# Resolve it by taking dev's version:
git checkout --theirs resources/views/landing/template-3.blade.php
git add resources/views/landing/template-3.blade.php

# 4. Complete the merge commit
git commit -m "merge: dev → main (34 dev commits, 28 app files, 10 main-only commits resolved)"

# 5. Push to remote
git push origin main

# 6. Deploy to prod
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
```

---

*Investigation performed 2026-03-05. No code was modified during this investigation. All findings are based on git diff analysis between dev HEAD 8496db0 and main HEAD 351857a.*
