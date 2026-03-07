# Phase 08: Usage Visibility & API Key Management

## Goal
Give users full visibility into their API consumption, and give admins a system-wide view of usage per user, per API key, plus the ability to manage all user-generated keys.

## Background
- `usage_logs` table exists but has 0 rows — `CreditService.deductCredits()` silently fails (billing bug found during saied debug session)
- Admins currently have no view of user-generated API keys — the "API Settings" admin page is actually a system credentials manager (Ollama URL, payment keys, WhatsApp, Redis) — NOT user key management
- Admin "API Settings" should be renamed to "System Settings" or "Integration Settings" to avoid confusion

## Features

### Fix 1: Admin Credit Top-Up Bug (Replace vs Add)
**File:** `app/Http/Controllers/Admin/AdminController.php` line 67
**Bug:** `$user->credits = (int) $credits` — REPLACES the balance instead of adding to it
**Fix:** Change to `$user->increment('credits', (int) $credits)` — adds on top of existing balance
**Example:** User has 100 credits, admin enters 200 → result should be 300, currently gives 200

### Fix 2: Usage Logging / Credit Deduction Silently Failing
**Root cause:** `CreditService::deductCredits()` writes `prompt_tokens` and `completion_tokens` to `usage_logs`,
but those columns were added to dev DB manually and never migrated to prod. Every DB transaction rolls back silently.
**Fix:** Create and run a migration: `add_token_columns_to_usage_logs_table` adding `prompt_tokens` (nullable int) and `completion_tokens` (nullable int)
- Also add `api_key_id` (nullable uuid FK) and `response_time_ms` (nullable int) and `status_code` (int, default 200) if missing
- Run: `php artisan migrate --force` on prod after merging
- After fix, every API call will write to usage_logs and deduct credits correctly

### Feature 1: User Usage Dashboard
Users can see their own API call history:
- Table: timestamp, model used, tokens (prompt + completion), credits deducted, status (success/error)
- Summary cards: total calls today/this month, total credits used, credits remaining
- Filter by date range and model
- Export as CSV

### Feature 2: Admin — Usage by User
Admin can see system-wide usage:
- Table: user name/email, total calls, total tokens, total credits consumed, last active
- Drill down into a specific user to see their full call log
- Filter by date range, subscription tier, model

### Feature 3: Admin — Usage by API Key
Admin can see usage broken down by API key:
- Table: key name, owner (user), prefix (masked), total calls, last used, status
- See call history for any specific key

### Fix 3: Formalise api_keys Schema via Migration
The `api_keys` table is missing columns that were added to prod manually (no migration file):
- `status` enum('active','revoked') default 'active' — exists in prod DB but not in migration
- `created_by` uuid nullable FK → users.id — NEW: who created the key
Migration to create: `add_status_and_created_by_to_api_keys_table`
- `$table->string('status')->default('active')->after('permissions');` (if not exists)
- `$table->uuid('created_by')->nullable()->after('status');`
- `$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');`

### Feature 4: Admin — User API Key Management
Rename current "API Settings" → "Integration Settings" (system credentials)
Add new "User API Keys" admin section:
- List ALL keys in the system — every key ever created, for every user
- Columns: key name, owner (user), prefix (masked), status (active/revoked), **created by** (Admin name or "Self"), created_at, last_used_at, total calls
- "Created by" logic: if `created_by` is null or equals `user_id` → show "Self"; else show the admin's name
- Admin can revoke/reactivate any key (fix security bug: `ApiKeyAuth` must check `status='active'`)
- Admin can CREATE a key for any user via UI (calls existing `createApiKeyForUser()`, sets `created_by = auth()->id()`)
  - Key appears in both: admin's "User API Keys" list AND the user's own API keys dashboard

### Feature 5: User API Keys — Show "Created By"
On the user's own API keys page:
- Add a "Created by" badge on each key: "You" or "Admin" (show admin name only if user is admin, otherwise just "Admin")
- User cannot delete or modify keys created by admin (read-only badge)

## Design System
- Dark Luxury: bg `#0f1115`, gold `#d4af37`, card `#13161d`
- Fonts: Inter (Latin) + Tajawal (Arabic)
- CSS vars: `--gold`, `--bg-card`, `--bg-secondary`, `--border`, `--text-muted`

## Dev/Prod Rules (from CLAUDE.md)
- All schema changes via migrations only — never manual DB edits
- PHP binary: `/opt/cpanel/ea-php82/root/usr/bin/php`
- Test on dev (llmdev.resayil.io) before prod

## Success Criteria
- [ ] Every API call writes to usage_logs and deducts credits correctly
- [ ] Users see their full call history on their dashboard
- [ ] Admin can see usage per user and per API key
- [ ] Admin can revoke any user API key and it actually blocks access
- [ ] "API Settings" renamed to "Integration Settings" in admin nav
