---
phase: quick-9
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: [CLAUDE.md]
autonomous: true
requirements: [QUICK-9]

must_haves:
  truths:
    - "CLAUDE.md exists at the project root with dev-to-prod best practices"
    - "Any Claude agent starting a session reads and follows these rules automatically"
    - "All 5 best practices are present and actionable"
  artifacts:
    - path: "CLAUDE.md"
      provides: "Project-level agent instructions"
      contains: "Dev-to-Prod Best Practices"
  key_links:
    - from: "CLAUDE.md"
      to: "Every Claude agent session"
      via: "Auto-loaded by Claude Code at session start"
      pattern: "CLAUDE.md"
---

<objective>
Create CLAUDE.md at the project root containing dev-to-prod best practices so every Claude agent session automatically inherits and follows them.

Purpose: Prevent the class of mistakes that caused the prod DB manual edit, the missing migration incident (prompt_tokens/completion_tokens nullable cols), and the linter-revert-of-isAdmin() issue by encoding institutional knowledge as enforced rules.
Output: CLAUDE.md with project overview + Dev-to-Prod Best Practices section.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md
@README.md

Project background (from memory):
- Laravel SaaS — OpenAI-compatible LLM API, pay-per-use credits
- Two environments: dev branch → llmdev.resayil.io | main branch → llm.resayil.io (prod)
- Deploy: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"` (dev) / `bash ~/llm.resayil.io/deploy.sh prod` (prod)
- PHP binary: `/opt/cpanel/ea-php82/root/usr/bin/php`
- Key past incident: `prompt_tokens`+`completion_tokens` migration ran on dev only — would have 500'd prod if pushed without running migrate first
- Key past incident: linter reverted isAdmin() refactor in several files silently
- Key past incident: admin account created by editing DB directly instead of going through register flow
</context>

<tasks>

<task type="auto">
  <name>Task 1: Create CLAUDE.md with project instructions and dev-to-prod best practices</name>
  <files>CLAUDE.md</files>
  <action>
Create `/CLAUDE.md` (project root). CLAUDE.md is auto-loaded by Claude Code at the start of every session — write it as direct instructions to Claude agents, not as documentation for humans.

Structure the file as follows:

---

# LLM Resayil — Project Instructions for Claude

## Project Overview

Brief: Laravel SaaS at https://llm.resayil.io — OpenAI-compatible LLM API with pay-per-use credits.

Key facts agents need:
- Dev branch → llmdev.resayil.io (staging)
- Main branch → llm.resayil.io (production)
- PHP binary on server: `/opt/cpanel/ea-php82/root/usr/bin/php`
- Deploy dev: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
- Deploy prod: `ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"`
- All models use UUID PKs (`$incrementing = false`, `$keyType = 'string'`, booted() UUID hook)
- Admin check: `$user->isAdmin()` — never hardcode admin email strings

## Design System

- Dark Luxury: bg `#0f1115`, gold `#d4af37`, card `#13161d`
- Fonts: Inter (Latin) + Tajawal (Arabic)
- CSS vars: `--gold`, `--bg-card`, `--bg-secondary`, `--border`, `--text-muted`
- NEVER expose "local", "cloud proxy", "local GPU", or "cloud" labels to regular users

## Google Analytics

- Tag ID: `G-M0T3YYQP7X` — present in `layouts/app.blade.php` and all landing templates
- Wrapped in `@if(app()->isProduction())` — fires ONLY on prod
- DO NOT remove or modify the GA tag

## Dev-to-Prod Best Practices

These are MANDATORY rules. Follow them in every session without exception.

### 1. Always Use Migrations — Never Alter the DB Schema Manually

Every schema change (new column, new table, index, enum extension, constraint) MUST go through a Laravel migration file.

```bash
# Create a migration
/opt/cpanel/ea-php82/root/usr/bin/php artisan make:migration add_foo_to_bar_table

# Run on dev (after committing the migration file)
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate"

# Run on prod (after merging to main)
ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force"
```

**Why:** The `prompt_tokens`/`completion_tokens` nullable columns were added to dev's DB directly. This caused a divergence where pushing dev to prod without running migrate would have 500'd every API call. Manual DB changes are invisible to git history, team members, and future deployments.

**Exception:** None. If MyFatoorah or cPanel requires a manual step, document it in the PR description — still create a migration for the schema part.

### 2. Never Modify the Production Database Directly

Do not use cPanel phpMyAdmin, direct MySQL CLI, or any tool to INSERT, UPDATE, or DELETE rows on `resayili_llm_resayil` (prod DB) during development.

**For creating user accounts:** Use the app's register flow at https://llm.resayil.io/register. Then elevate via artisan tinker if needed:

```bash
ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute=\"App\\\Models\\\User::where('email','x@example.com')->update(['subscription_tier'=>'admin'])\""
```

**Why:** The `soud@alphia.net` admin account was created by directly inserting into the prod DB. This bypassed the registration flow (no OTP, no welcome email, no MyFatoorah customer record), leaving the account in a partially inconsistent state.

**Exception:** Emergency read-only queries to diagnose a live outage are acceptable. Never write.

### 3. Cherry-Pick Critical Prod-Only Fixes Immediately

When a critical bug is found and fixed on dev, cherry-pick to main and deploy to prod immediately — do NOT wait for the next full dev→prod merge cycle.

```bash
# After fixing on dev and getting the commit SHA:
git checkout main
git cherry-pick <commit-sha>
git push origin main
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
git checkout dev
```

**Why:** The email notification fix (commit `0fc5a7a`) and the OllamaProxy format fixes sat on dev for days while prod was broken. Users experienced 500 errors and malformed API responses the entire time.

**Criteria for immediate cherry-pick:** Any bug that causes 5xx errors, data corruption, failed payments, or broken core user flows (login, API calls, top-up).

### 4. Run Pre-Merge Risk Investigation Before Every Dev→Prod Push

Before merging dev into main, run a risk investigation using `/gsd:quick` to identify migration risks, env var gaps, and breaking changes.

```bash
# In the project directory, before merging:
# Ask: /gsd:quick investigate "dev-to-prod merge risk for commits since <last-merge-sha>"
```

The investigation MUST check:
- **Migrations:** Any new migration files in `database/migrations/` not yet run on prod?
- **Env vars:** Any new `config('x')` or `env('X')` calls not present in prod `.env`?
- **Schema divergence:** Any manual DB changes on dev not captured in a migration?
- **Enum changes:** Any enum columns extended? (These require manual ALTER on MySQL — migrations may not handle them on shared hosting)
- **Queue/cache changes:** Any new queue jobs or cache keys that require worker restart?

Only proceed with the merge after all risks are documented and mitigated.

### 5. Tag Prod Releases After Every Merge to Main

After every successful merge to main and prod deployment, create a semver git tag and push it.

```bash
# After confirming prod is healthy:
git tag v1.x.0
git push origin --tags
```

Tag naming:
- `v1.x.0` — new feature or enhancement shipped to prod
- `v1.x.y` — hotfix / patch to prod
- Increment the middle digit for each planned dev→prod cycle
- Increment the patch digit for cherry-picked hotfixes

**Why:** Without tags, git log is the only way to identify what's on prod. Tags make rollback, changelog generation, and incident postmortems dramatically faster.

**Check current tags before tagging:**
```bash
git tag --sort=-version:refname | head -5
```

---

*These instructions are maintained in `CLAUDE.md` at the project root. Update them when new architectural decisions are made.*
  </action>
  <verify>
    <automated>test -f D:/Claude/projects/LLM-Resayil/CLAUDE.md && grep -c "Dev-to-Prod Best Practices" D:/Claude/projects/LLM-Resayil/CLAUDE.md | grep -q "1" && echo "PASS: CLAUDE.md exists with best practices section" || echo "FAIL"</automated>
  </verify>
  <done>
    CLAUDE.md exists at project root, contains all 5 best practices (migrations, no direct prod DB writes, cherry-pick critical fixes, pre-merge risk investigation, release tagging), and is written as agent-facing instructions (imperative, not descriptive).
  </done>
</task>

</tasks>

<verification>
- CLAUDE.md exists at `D:\Claude\projects\LLM-Resayil\CLAUDE.md`
- File contains section "Dev-to-Prod Best Practices" with all 5 rules
- Each rule includes the "Why" rationale grounded in real past incidents
- Each rule includes actionable commands or steps
- File is written for Claude agents to parse and follow, not as human documentation
</verification>

<success_criteria>
Any Claude agent starting a session on this project will read CLAUDE.md, internalize the 5 dev-to-prod rules, and apply them without being explicitly reminded — especially the migration rule and the pre-merge investigation rule.
</success_criteria>

<output>
After completion, create `.planning/quick/9-add-dev-to-prod-best-practices-to-claude/9-SUMMARY.md` with what was done, the file created, and any notes.
</output>
