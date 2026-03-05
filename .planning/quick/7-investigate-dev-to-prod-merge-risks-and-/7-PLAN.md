---
phase: quick-7
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: []
autonomous: true
requirements: [QUICK-7]
must_haves:
  truths:
    - "Every commit on dev-not-on-main is categorized as safe / needs-action / needs-decision"
    - "All DB schema risks are identified with exact remediation SQL"
    - "Any merge conflict files are named with resolution instructions"
    - "A clear GO / NO-GO verdict is produced with a numbered pre-flight checklist"
  artifacts:
    - path: ".planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-REPORT.md"
      provides: "Go/no-go report with pre-flight steps"
  key_links:
    - from: "dev branch (8496db0)"
      to: "main branch (351857a)"
      via: "git diff analysis"
      pattern: "git log main..dev"
---

<objective>
Investigate every commit on dev that is not yet on main (prod) and produce a go/no-go merge report.

Purpose: Prevent a bad push to llm.resayil.io by surfacing every risk — DB schema mismatches, missing migrations, enum drift, merge conflicts, env var gaps, and code assumptions that break on prod.

Output: `.planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-REPORT.md` — a standalone document the user can read and act on before running `deploy.sh prod`.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md
@.planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-PLAN.md

<!-- Pre-gathered intelligence (do not re-run these — facts are embedded below) -->

<interfaces>
<!-- Key facts already established during planning. Executor must use these, not re-derive. -->

COMMIT DELTA (dev commits NOT on main — 35 commits):
Most recent dev HEAD: 8496db0  (docs: save session state)
Prod HEAD on main:    351857a  (fix(analytics): wrap GA tag in isProduction())

FILES CHANGED (39 files total, no migrations among them):
  app/Exceptions/Handler.php
  app/Http/Controllers/Api/ChatCompletionsController.php
  app/Http/Controllers/ApiKeysController.php
  app/Http/Controllers/Billing/PaymentController.php
  app/Http/Middleware/AdminMiddleware.php
  app/Models/OllamaProxy.php
  app/Models/User.php
  app/Services/BillingService.php
  app/Services/CostService.php
  app/Services/ModelAccessControl.php
  lang/ar/api_keys.php           (NEW FILE)
  lang/ar/billing.php            (NEW FILE)
  lang/en/api_keys.php           (NEW FILE)
  lang/en/billing.php            (modified — additive keys only)
  resources/lang/ar/api_keys.php (NEW FILE)
  resources/lang/ar/billing.php  (NEW FILE)
  resources/lang/ar/dashboard.php(NEW FILE)
  resources/lang/en/api_keys.php (NEW FILE)
  resources/lang/en/billing.php  (modified — additive keys only)
  resources/lang/en/dashboard.php(modified — additive keys only)
  resources/views/api-keys.blade.php       (NEW FILE)
  resources/views/billing/plans.blade.php  (modified)
  resources/views/credits.blade.php        (modified)
  resources/views/dashboard.blade.php      (modified)
  resources/views/landing/template-3.blade.php (modified — MERGE CONFLICT)
  resources/views/layouts/app.blade.php    (modified)
  resources/views/welcome.blade.php        (modified — pending review todo)
  routes/web.php                           (modified — adds /landing/1,2,3)
  + .planning/ docs only

MIGRATION STATUS:
  Migration `2026_03_04_000001_add_token_split_to_usage_logs.php` IS on main branch at position 18
  (before prod HEAD 351857a at position 1). Deploy.sh runs `php artisan migrate --force`.
  Conclusion: migration was already applied to prod when 351857a was deployed. NOT a risk.

SUBSCRIPTION_TIER ENUM:
  Migration file `create_users_table.php` only has: ['basic', 'pro', 'enterprise']
  App code uses 'starter' and 'admin' tiers extensively (BillingService, PaymentController, etc.)
  Both were added MANUALLY to prod DB — no migration file exists for either.
  Risk: If `php artisan migrate --fresh` ever runs on prod, these tiers are lost and existing
  users with starter/admin tier will have invalid enum values.
  Current deploy uses `migrate --force` (incremental) so this is NOT an immediate merge risk,
  but is an ongoing schema-drift debt that needs a migration file.

MERGE CONFLICTS:
  Only ONE file has conflicts: `resources/views/landing/template-3.blade.php`
  Main has old 1895-line version. Dev has redesigned 974-line version.
  Resolution: always take dev's version (`git checkout --theirs resources/views/landing/template-3.blade.php`)
  Other files are clean fast-forward merges.

isAdmin() REFACTOR STATUS (all files on dev):
  FIXED (uses isAdmin()):
    - app/Http/Controllers/Api/ChatCompletionsController.php   ✓
    - app/Http/Middleware/AdminMiddleware.php                  ✓
    - app/Services/BillingService.php                         ✓
    - app/Services/ModelAccessControl.php                     ✓
    - app/Http/Controllers/Billing/PaymentController.php      ✓
    - resources/views/billing/plans.blade.php                 ✓
    - resources/views/layouts/app.blade.php                   ✓
    - resources/views/dashboard.blade.php                     ✓
  All 8 files are correct on dev — linter-revert concern in MEMORY.md is outdated.

USER.PHP isAdmin() METHOD (dev version):
  Returns true for: 'admin@llm.resayil.io' AND 'soud@alphia.net'
  This is what gets merged to prod — both admins will have full bypass.

OllamaProxy CHANGES (BUG-01 / BUG-03 fix):
  Streaming: Now emits OpenAI SSE format chunks instead of raw Ollama JSON.
  Non-streaming: Transforms Ollama response to OpenAI format.
  Token counts: Read from OpenAI-format response['usage']['prompt_tokens'] and ['completion_tokens'].
  Double logUsage: Removed from non-streaming path.
  These are breaking fixes — prod currently returns wrong format to SDK clients.

ChatCompletionsController token counting change:
  Before: reads `$content['prompt_eval_count']` and `$content['eval_count']` (Ollama native)
  After:  reads `$content['usage']['prompt_tokens']` and `$content['usage']['completion_tokens']`
  This is consistent with OllamaProxy now returning OpenAI format — no issue.

CostService change:
  Added `our_cost_usd` calculation to dashboard usage rows.
  Reads `$log->tokens_used` and model multiplier from config. No schema dependency.

ApiKeysController:
  GET /api-keys now returns a web view (`api-keys.blade.php`) for non-JSON requests
  instead of redirecting to /dashboard. Additive, no breaking change.

Handler.php:
  Auth/Validation exceptions now check `expectsJson() || is('api/*')` before returning JSON.
  Web requests get proper redirects. This is a correctness fix, not a breaking change.

WELCOME PAGE:
  Pending review todo: `2026-03-05-review-welcome-page-redesign-before-prod-merge.md`
  The redesign (commit a5adcf2) is on dev. User has not yet reviewed it for sign-off.
  This is a USER DECISION item, not a technical risk.

ENV VARS:
  No new `env()` calls found in dev vs main. No new env vars needed.

LANDING ROUTES:
  routes/web.php adds /landing/1, /landing/2, /landing/3.
  These are accessible on prod after merge but not advertised. No auth required.
  Template 1 and 2 may not be relevant anymore since template 3 was chosen.
  Low risk — routes just render blade views.

LANG DIRECTORY DUAL STRUCTURE:
  lang/ (root) — Laravel 10 standard location, used by the app
  resources/lang/ — legacy location, synced to match lang/
  Both directories are tracked in git and kept in sync. No conflict risk.
  On main, lang/en/billing.php and resources/lang/en/billing.php have different content
  because they evolved separately. Dev merged them with the same keys — the merge will
  produce correct files in both locations.
</interfaces>
</context>

<tasks>

<task type="auto">
  <name>Task 1: Produce the go/no-go merge report</name>
  <files>.planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-REPORT.md</files>
  <action>
Write `7-REPORT.md` using ONLY the pre-gathered intelligence in the `<interfaces>` block above.
Do NOT run git commands or read files — all facts are already provided.

Structure the report exactly as follows:

---

# Dev → Prod Merge Risk Report

**Date:** 2026-03-05
**Dev HEAD:** 8496db0
**Prod HEAD (main):** 351857a
**Commits to merge:** 35 commits, 39 files

---

## VERDICT

[State GO or NO-GO at the top with one sentence rationale]

---

## Pre-Flight Checklist (must complete before deploy)

[Numbered list of REQUIRED steps only. Each step is actionable and specific.
Do NOT include optional or nice-to-have items here.]

---

## Risk Analysis

### Risk 1: Merge Conflict — template-3.blade.php
**Severity:** BLOCKER (merge will halt without resolution)
**File:** `resources/views/landing/template-3.blade.php`
**Issue:** Main has 1895-line old version; dev has redesigned 974-line version. Git cannot auto-merge.
**Fix:** When running the merge, run:
  ```bash
  git checkout --theirs resources/views/landing/template-3.blade.php
  git add resources/views/landing/template-3.blade.php
  ```
**Status:** Known, has resolution

### Risk 2: subscription_tier Enum Schema Drift
**Severity:** MEDIUM (not a merge blocker, but ongoing debt)
**Issue:** The `users` table `subscription_tier` enum was defined in migrations as
  `['basic', 'pro', 'enterprise']` only. The 'starter' and 'admin' values were added
  manually to both prod and dev DBs. No migration file covers these values.
  `deploy.sh` uses `migrate --force` (incremental) — so the merge itself will NOT
  cause a 500. However:
  - If `migrate:fresh` is ever run, all starter/admin users break.
  - New developer environments will fail trying to use these tiers.
**Fix (post-merge, non-urgent):** Create a migration:
  ```php
  // In a new migration file:
  DB::statement("ALTER TABLE users MODIFY COLUMN subscription_tier ENUM('basic','pro','enterprise','starter','admin') DEFAULT 'basic'");
  ```
**Status:** Not a merge blocker. Schedule as a follow-up task.

### Risk 3: Welcome Page Redesign Needs Visual Sign-Off
**Severity:** LOW (no technical risk, but UX concern)
**Issue:** The welcome page (`/`) was redesigned on dev (commit a5adcf2) and has NOT been
  reviewed for prod. A todo file explicitly flags this.
**File:** `resources/views/welcome.blade.php`
**Fix:** Review https://llmdev.resayil.io before merging. Confirm:
  - Hero section looks correct
  - No Kuwait/region-specific copy leaked in
  - How-it-works section reflects actual product flow
  - Pricing tiers are correct (Starter 15 KWD, Basic 25 KWD, Pro 45 KWD)
**Status:** User decision required before merge.

### Risk 4: soud@alphia.net Admin Access Arrives on Prod
**Severity:** INFO (intentional, confirm it's desired)
**Issue:** `User::isAdmin()` on dev returns true for both `admin@llm.resayil.io` AND
  `soud@alphia.net`. After merge, soud@alphia.net will have full admin bypass on prod:
  - Rate limit bypass
  - Credit check bypass
  - Access to /admin, /admin/monitoring, /admin/models
  - Unlimited API key creation
**Prerequisite:** Confirm soud@alphia.net account exists on prod DB with correct password.
  If not, create it via the registration flow or directly in DB.
**Status:** Intentional — confirm account exists on prod.

---

## Safe Changes (no action required)

| Change | Why Safe |
|--------|----------|
| `2026_03_04_000001_add_token_split_to_usage_logs.php` migration | Already on main (position 18), was deployed when 351857a was pushed. |
| isAdmin() refactor across all 8 files | Complete and correct on dev. Memory note about linter revert is outdated. |
| OllamaProxy BUG-01/BUG-03 fix | Streaming now emits OpenAI SSE format; non-streaming transforms to OpenAI format. Critical correctness fix. |
| Handler.php auth exception fix | Web users now get login redirects; API users still get JSON 401. Correct behavior. |
| ApiKeysController web view | GET /api-keys now returns `api-keys.blade.php` for web users. Additive. |
| Translation files (lang/ and resources/lang/) | Additive keys only. No existing key renamed or removed. |
| CostService our_cost_usd | Dashboard-only cosmetic column. No schema change. |
| routes/web.php /landing/1,2,3 | Adds unauthenticated landing page routes. Low-traffic, no side effects. |
| No new env vars | Diff confirms zero new `env()` calls in dev vs main. |

---

## Post-Merge Steps (after deploy.sh prod runs)

1. Verify prod API returns OpenAI format: `curl -s -X POST https://llm.resayil.io/api/v1/chat/completions -H "Authorization: Bearer YOUR_KEY" -H "Content-Type: application/json" -d '{"model":"llama3.2:3b","messages":[{"role":"user","content":"hi"}]}' | jq .choices`
2. Verify soud@alphia.net admin nav shows: `/admin`, `/admin/monitoring`, `/admin/models` links.
3. Verify /billing/plans renders without raw translation keys.
4. Visit https://llm.resayil.io — confirm welcome page redesign looks correct.
5. Schedule the subscription_tier enum migration as a follow-up task.

---

## Merge Commands (exact sequence)

```bash
# 1. On your local machine
git checkout main
git merge dev

# 2. Resolve the ONE conflict:
git checkout --theirs resources/views/landing/template-3.blade.php
git add resources/views/landing/template-3.blade.php

# 3. Complete the merge
git commit -m "merge: dev → main (35 commits, 39 files)"
git push origin main

# 4. Deploy to prod
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
```

---

*Generated by investigation plan quick-7. No code was changed.*

---

The report must be self-contained and actionable. The user reads this before touching git.
  </action>
  <verify>File `.planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-REPORT.md` exists and contains all four sections: VERDICT, Pre-Flight Checklist, Risk Analysis, Safe Changes.</verify>
  <done>Report written. User can open it and decide whether to proceed with the merge.</done>
</task>

</tasks>

<verification>
Report covers:
- [ ] Migration status confirmed (not a risk)
- [ ] subscription_tier enum drift documented with remediation SQL
- [ ] Merge conflict file named with exact git commands to resolve
- [ ] isAdmin() refactor status confirmed correct (not a risk)
- [ ] Welcome page review flagged as user decision
- [ ] soud@alphia.net prod account prerequisite noted
- [ ] Zero new env vars confirmed
- [ ] Exact merge command sequence provided
- [ ] Post-merge verification steps listed
</verification>

<success_criteria>
7-REPORT.md exists with a clear GO or NO-GO verdict, an enumerated pre-flight checklist, and exact git commands. The user can read it and act without further investigation.
</success_criteria>

<output>
After completion, the executor does NOT need to create a SUMMARY — this is a single-task investigation quick plan.
The final artifact is `.planning/quick/7-investigate-dev-to-prod-merge-risks-and-/7-REPORT.md`.
</output>
