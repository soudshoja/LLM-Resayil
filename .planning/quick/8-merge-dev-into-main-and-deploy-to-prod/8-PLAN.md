---
phase: quick-8
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - resources/views/landing/template-3.blade.php
autonomous: false
requirements: []
must_haves:
  truths:
    - "main branch contains all dev commits"
    - "https://llm.resayil.io loads without error"
    - "https://llm.resayil.io/login renders the login form"
    - "https://llm.resayil.io/dashboard redirects unauthenticated users to login"
    - "https://llm.resayil.io/billing/plans renders pricing cards after login"
  artifacts:
    - path: "resources/views/landing/template-3.blade.php"
      provides: "Dev version of template-3 (conflict resolved with --theirs)"
  key_links:
    - from: "git merge dev"
      to: "origin/main"
      via: "git push"
      pattern: "fast-forward or merge commit"
    - from: "origin/main"
      to: "prod server"
      via: "deploy.sh prod"
      pattern: "git pull + artisan commands"
---

<objective>
Merge the dev branch into main, push to origin, deploy to production, and browser-verify the live site.

Purpose: Ship all accumulated dev work (translation fixes, landing pages, welcome redesign, API format fixes, billing fixes, auth fixes, isAdmin refactor, analytics) to production.
Output: Production site updated, all key pages verified working.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md

Known conflict: resources/views/landing/template-3.blade.php — always take dev version (--theirs).

Pre-deploy note from MEMORY.md: dev has a migration (prompt_tokens + completion_tokens nullable cols on usage_logs) that has NOT been run on prod. Must run migrations after deploy or API calls will 500.

Also: subscription_tier enum was extended manually on both DBs (no migration file) — no action needed for this.
</context>

<tasks>

<task type="auto">
  <name>Task 1: Merge dev into main, push, and deploy to prod</name>
  <files>resources/views/landing/template-3.blade.php</files>
  <action>
Run these commands in sequence from the repo root (D:\Claude\projects\LLM-Resayil):

1. Switch to main and merge dev:
   ```
   git checkout main
   git merge dev
   ```

2. If a merge conflict occurs on resources/views/landing/template-3.blade.php (expected):
   ```
   git checkout --theirs resources/views/landing/template-3.blade.php
   git add resources/views/landing/template-3.blade.php
   git commit -m "chore: merge dev into main — take dev template-3"
   ```
   If no conflict, git will auto-commit the merge.

3. Push main to origin:
   ```
   git push origin main
   ```

4. Deploy to production:
   ```
   ssh whm-server "cd ~/llm.resayil.io && bash deploy.sh prod"
   ```

5. Run migrations on prod (REQUIRED — prompt_tokens/completion_tokens columns):
   ```
   ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force"
   ```

If deploy.sh already runs migrations, skip step 5 (check deploy.sh output for "Running migrations").
  </action>
  <verify>
    <automated>ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan --version && git log --oneline -3"</automated>
  </verify>
  <done>Production server shows the latest commits from dev. No errors in deploy output. Migrations ran successfully.</done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <what-built>Merged and deployed all dev commits to https://llm.resayil.io</what-built>
  <how-to-verify>
Open a browser (logged-out session) and check each URL:

1. https://llm.resayil.io — Homepage loads, dark luxury theme, no blank page or 500 error
2. https://llm.resayil.io/login — Login form renders correctly, no translation key literals (no "auth.xxx" text visible)
3. https://llm.resayil.io/dashboard — Redirects to /login for unauthenticated users (do NOT get a 500 or blank page)

Then log in as admin@llm.resayil.io / password and check:

4. https://llm.resayil.io/dashboard — Dashboard loads with welcome message (not literal ":name"), usage table visible
5. https://llm.resayil.io/billing/plans — Pricing cards render (Starter 15 KWD / Basic 25 KWD / Pro 45 KWD), no raw "billing.xxx" translation keys visible

Expected: All 5 pages load correctly with no visible errors.
  </how-to-verify>
  <resume-signal>Type "approved" if all 5 pages look correct, or describe any issues found.</resume-signal>
</task>

</tasks>

<verification>
- git log --oneline main shows the dev commits (translation fixes, landing pages, API format fixes)
- No 500 errors on homepage, login, dashboard, billing/plans
- Prod DB has prompt_tokens and completion_tokens columns on usage_logs table
</verification>

<success_criteria>
- main branch is ahead of its previous HEAD by all dev commits
- https://llm.resayil.io serves the updated codebase
- Homepage, login, dashboard, billing/plans all render without errors
- Dashboard shows correct welcome message (no ":name" literal)
- Billing/plans shows pricing tiers (no "billing.xxx" literal keys)
</success_criteria>

<output>
After completion, create `.planning/quick/8-merge-dev-into-main-and-deploy-to-prod/8-SUMMARY.md` with:
- Merge commit SHA
- Deploy timestamp
- Any issues encountered and resolved
- Verification results for each URL
</output>
