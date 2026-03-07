# Bug C Fix: Cache Table Missing

## Status: FIXED

## Problem
Queue workers were crashing immediately with:
```
SQLSTATE[42S02]: Table 'resayili_llm_resayil.cache' doesn't exist
```

The `cache` table (and `sessions` table) did not exist in the production database. No migration file existed for either table — they had never been created.

## Root Cause
Laravel's default cache driver requires a `cache` table when using the `database` cache driver. The project was set up without running `php artisan cache:table` and the resulting migration was never committed or run on production.

## Fix Applied

### Step 1: Find the app root
- Real server IP: `152.53.86.223` (SSH on port 22)
- App root: `/home/resayili/llm.resayil.io/`
- PHP binary: `/opt/cpanel/ea-php82/root/usr/bin/php`

### Step 2: Pre-fix migration status
All 18 existing migrations had `Ran` status (batches 1-3). No pending migrations existed. No cache migration file was present.

### Step 3: Create migration files
```bash
php artisan cache:table    # Created: 2026_03_07_095527_create_cache_table.php
php artisan session:table  # Created: 2026_03_07_095528_create_sessions_table.php
```

### Step 4: Run migrations
```bash
php artisan migrate --force
```
Both migrations ran in batch 4.

### Step 5: Verify tables created
```sql
SHOW TABLES LIKE 'cache';
-- Result: cache (EXISTS)

SHOW TABLES LIKE 'sessions';
-- Result: sessions (EXISTS)
```

Cache table structure:
| Field      | Type         | Key |
|------------|--------------|-----|
| key        | varchar(255) | PRI |
| value      | mediumtext   |     |
| expiration | int(11)      |     |

### Step 6: Test queue workers
```bash
timeout 8 php artisan queue:work --stop-when-empty 2>&1
# Exit code: 0 (clean exit, no crash)
```

### Step 7: Queue manager
Crontab already manages queue workers:
```
* * * * * /opt/cpanel/ea-php82/root/usr/bin/php /home/resayili/llm.resayil.io/artisan queue:work --stop-when-empty --max-time=55 >> storage/logs/queue.log 2>&1
```
This runs every minute. Next cron tick will start queue workers without crashing.

## All DB Tables (Post-Fix)
- api_keys
- cache (NEW)
- cache_locks (NEW)
- cloud_budgets
- jobs
- migrations
- models
- notification_templates
- notifications
- otp_codes
- sessions (NEW)
- subscriptions
- team_members
- topup_purchases
- usage_logs
- users

## Action Required
None. The migration files (`2026_03_07_095527_create_cache_table.php` and `2026_03_07_095528_create_sessions_table.php`) were generated on the server directly. These files should be committed to the git repository to keep the codebase in sync:

```bash
# On the server or locally after pulling:
git add database/migrations/2026_03_07_095527_create_cache_table.php
git add database/migrations/2026_03_07_095528_create_sessions_table.php
git commit -m "chore: add cache and session table migrations"
```

## Outcome
- `cache` table: EXISTS in prod DB
- `cache_locks` table: EXISTS in prod DB
- `sessions` table: EXISTS in prod DB
- Queue worker: starts cleanly (exit code 0)
- Cron: already configured to run queue workers every minute
