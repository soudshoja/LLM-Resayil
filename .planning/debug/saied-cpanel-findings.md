---
status: investigating
created: 2026-03-07
updated: 2026-03-07T12:50:00Z
---

## Current Focus

hypothesis: API is broken at the infrastructure level for ALL users — saied's issue is not account-specific
next_action: review two distinct failure modes found; confirm root cause

---

## User Record (saied = Saeid Shoja)

NOTE: The LIKE '%saied%' query returned empty because the name is "Saeid" not "saied".
Correct user was identified via full table scan.

| Field | Value |
|-------|-------|
| UUID | a20de43f-e2d7-40ff-92cd-54ee5002c4cb |
| Name | Saeid Shoja |
| Email | iamshoja@gmail.com |
| Phone | 96555524870 (from OTP records) |
| Credits | 1500 (NOT zero — credits are healthy) |
| subscription_tier | basic |
| subscription_expiry | NULL |
| trial_started_at | NULL |
| trial_credits_remaining | 0 |
| created_at | 2026-03-05 11:01:30 |

## API Keys (saied)

| prefix | status | last_used_at |
|--------|--------|--------------|
| 1b5ef32d40c8 | active | NULL (admin-created, never used) |
| fb68430943d7 | active | 2026-03-07 09:28:48 |
| 89996a35ffdd | active | 2026-03-07 09:31:45 |

All 3 keys are status=active. Two keys were used today at 09:28 and 09:31 server time.

## Usage Logs

usage_logs table: 0 rows total. Despite last_used_at timestamps on the API keys,
no usage was ever recorded. This is consistent with the API returning 500 before
reaching the usage logging code.

## Notifications

notifications table: 0 rows. No notifications sent to saied (or anyone).

## Subscriptions

subscriptions table: 0 rows. saied has no subscription record.

---

## Infrastructure Status

### Finding 1 (CRITICAL): API returns HTTP 500 locally

```
curl -v http://127.0.0.1:9000/v1/models -H "Authorization: Bearer test"
=> HTTP/1.0 500 Internal Server Error
=> {"message":"Server error."}
```

The artisan serve process is running (started Mar06, port 9000).
Every API request returns 500. No useful error in laravel.log
(APP_DEBUG=false swallows the real exception — laravel.log has only 1 tinker error).

### Finding 2 (CRITICAL): Domain returns HTTP 404 for /v1/ routes

```
curl -v https://llm.resayil.io/v1/models
=> HTTP/2 404
=> content-type: text/html
=> x-turbo-charged-by: LiteSpeed
```

LiteSpeed/Cloudflare returns a 404 HTML page for /v1/models.
This means real user requests via the domain NEVER reach the artisan serve backend.
The LiteSpeed proxy is not configured to forward /v1/ paths to port 9000.

### Finding 3 (CRITICAL): models table is EMPTY

```sql
DESCRIBE models:
  model_id varchar(255) PK
  is_active tinyint(1) default 1
  credit_multiplier_override double nullable
  created_at, updated_at

SELECT * FROM models: (0 rows)
```

No models are seeded. The /v1/models endpoint would return an empty array or fail
depending on implementation. /v1/chat/completions would fail to find any valid model.

### Finding 4: Queue workers crash in a loop

Every queue:work invocation fails immediately with:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'resayili_llm_resayil.cache' doesn't exist
SQL: select * from `cache` where `key` in (llm_resayil_portal_cache_illuminate:queue:restart)
```

The `cache` table does not exist. The queue worker checks this table on startup
to detect queue:restart signals. CACHE_DRIVER=file in .env, but Laravel's queue
restart mechanism uses the cache facade (which resolves to the database driver
for some internal reason, or there is a mismatch between app cache and queue cache config).

This means ALL queued jobs (WhatsApp notifications, payment callbacks, etc.) are failing.

---

## Account-Specific Verdict

saied's account is completely healthy:
- Credits: 1500 (not exhausted)
- API keys: all 3 are status=active
- subscription_tier: basic (valid enum value)
- No blocks, bans, or revocations

The API failure is INFRASTRUCTURE-WIDE. No user can make successful API calls.

---

## Root Cause Summary

Two distinct bugs cause the API to be completely down:

### Bug A: LiteSpeed not proxying /v1/ to artisan serve
Real requests via https://llm.resayil.io/v1/ return 404.
The public/.htaccess or LiteSpeed proxy rules do not forward API routes
to the backend PHP app running on port 9000.

### Bug B: The artisan serve backend returns 500 for all API requests
Even if the routing were fixed, requests would 500.
The most likely cause: models table is empty, causing a query failure in
ChatCompletionsController or ModelsController. APP_DEBUG=false hides the real error.

### Bug C (secondary): Queue worker crash loop
The `cache` table is missing. Queue workers fail on startup.
Fix: run `php artisan cache:table && php artisan migrate --force`
or set CACHE_DRIVER=array if database cache is not needed for queue restart signals.

---

## Database Schema Reference

### users table (prod enum values)
subscription_tier: enum('basic', 'pro', 'enterprise', 'admin') — 'starter' still missing

### All tables present on prod
api_keys, cloud_budgets, jobs, migrations, models, notification_templates,
notifications, otp_codes, subscriptions, team_members, topup_purchases, usage_logs, users

### Missing tables
- cache (needed by queue worker restart mechanism)

---

## Migrations Applied (prod)

All migrations up to 2026-03-05 are applied. No pending migrations visible.
The models table was created via migration 2026_03_02_032411_create_models_table
but was never seeded with model data.

---

## Environment Config (non-sensitive)

- APP_ENV: production
- APP_DEBUG: false
- CACHE_DRIVER: file / CACHE_STORE: file
- QUEUE_CONNECTION: database
- OLLAMA_GPU_URL: http://208.110.93.90:11434
- OLLAMA_CLOUD_URL: https://ollama.com
- CLOUD_API_KEY: present
- Redis: NOT available (not installed)
- SESSION_DRIVER: file

---

## SSH Connection Note

llm.resayil.io DNS resolves through Cloudflare (104.21.22.141, 172.67.205.31).
SSH must use the direct server IP: 152.53.86.223 (ports 22, 2222, 7822 all open).
Python paramiko with password auth works on port 22 of the direct IP.

---

## Recommended Fix Steps

### Step 1 — Diagnose the 500 error (enable debug temporarily)
```bash
ssh resayili@152.53.86.223
cd ~/llm.resayil.io
# Temporarily enable debug and hit the API:
APP_DEBUG=true /opt/cpanel/ea-php82/root/usr/bin/php -d memory_limit=512M artisan serve --host=127.0.0.1 --port=9001 &
curl -H "Authorization: Bearer fb68430943d74244dc813258bd9cfe1c98ba79c3c8d54029ad..." http://127.0.0.1:9001/v1/models
```

### Step 2 — Seed the models table
```bash
cd ~/llm.resayil.io
/opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute="
\App\Models\Models::create(['model_id' => 'llama3.2:latest', 'is_active' => 1]);
"
```
Or deploy a migration/seeder that creates the default model entries.

### Step 3 — Fix LiteSpeed routing for /v1/ API paths
Check ~/llm.resayil.io/public/.htaccess for proxy rewrite rules.
Ensure /v1/ requests are proxied to 127.0.0.1:9000.
Currently these return 404 HTML, meaning LiteSpeed serves them from public/ directly
without forwarding to the PHP backend.

### Step 4 — Fix queue worker cache crash
```bash
cd ~/llm.resayil.io
/opt/cpanel/ea-php82/root/usr/bin/php artisan cache:table
/opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force
```
This creates the missing cache table and stops the crash loop.

### Step 5 — Restart artisan serve after fixes
```bash
pkill -f "artisan serve"
cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php -d memory_limit=512M artisan serve --host=127.0.0.1 --port=9000 &
```

---

## Cloud Budget (for reference)

| date | requests_today | daily_limit |
|------|----------------|-------------|
| 2026-03-07 | 1 | 500 |
| 2026-03-05 | 5 | 500 |

Cloud budget is fine. Not contributing to the issue.
