# Phase 7 Plan 01: Fix API Endpoint

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Make `POST https://llm.resayil.io/api/v1/chat/completions` work end-to-end with an API key.

**Architecture:** Fix 4 bugs discovered during Phase 7 investigation: missing config files (not in git), ThrottleRequests middleware hitting non-existent `cache` DB table, OllamaProxy hardcoded URL, and wrong base URL in welcome page.

**Tech Stack:** Laravel 11, cPanel/LiteSpeed, Ollama at 208.110.93.90:11434, PHP 8.2

---

## Root Cause Summary

| # | Bug | Impact |
|---|-----|--------|
| A | `config/cache.php` etc. missing from git | Every deploy wipes configs → ThrottleRequests crashes |
| B | `throttle:api` in Kernel uses `Cache::` → tries `cache` DB table (doesn't exist) | All API requests → 500 |
| C | OllamaProxy hardcodes `http://208.110.93.90:11434` instead of reading env | Can't change GPU server without code change |
| D | Welcome page shows wrong base URL `/v1` (real is `/api/v1`) | Developer confusion |

---

### Task 1: Add missing config files to git

**Files:**
- Create: `config/cache.php` (copy from server: `~/llm.resayil.io/config/cache.php`)
- Create: `config/auth.php`
- Create: `config/cors.php`
- Create: `config/filesystems.php`
- Create: `config/hashing.php`
- Create: `config/logging.php`
- Create: `config/queue.php`
- Create: `config/session.php`

**Step 1: Pull missing configs from production server**

```bash
for f in cache auth cors filesystems hashing logging queue session; do
  scp whm-server:~/llm.resayil.io/config/${f}.php config/${f}.php
done
```

**Step 2: Verify files look correct**

```bash
head -5 config/cache.php config/session.php config/queue.php
```
Expected: valid PHP files starting with `<?php`

**Step 3: Commit**

```bash
git add config/
git commit -m "chore: add missing Laravel config files (cache, auth, session, etc.)"
```

---

### Task 2: Fix ThrottleRequests middleware — remove from API group

**Files:**
- Modify: `app/Http/Kernel.php`

**Context:** The `api` middleware group has `ThrottleRequests::class.':api'` by default. This uses `Cache::` with the `throttle:api` named limiter defined in `RouteServiceProvider`. On shared hosting without Redis and with only a `file` cache driver, it SHOULD work — but if `config/cache.php` was missing, it defaulted to `database` which needs a `cache` table.

With Task 1 done (config files in git + `CACHE_DRIVER=file`), the throttle should work. But to be safe and avoid double rate-limiting (we have our own `RateLimiter` service), remove `ThrottleRequests` from the api middleware group.

**Step 1: Open `app/Http/Kernel.php` and find the api group**

Line ~45:
```php
'api' => [
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

**Step 2: Remove ThrottleRequests from api group**

```php
'api' => [
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

**Step 3: Commit**

```bash
git add app/Http/Kernel.php
git commit -m "fix: remove ThrottleRequests from api middleware (use custom RateLimiter instead)"
```

---

### Task 3: Fix OllamaProxy — read URL from env instead of hardcode

**Files:**
- Modify: `app/Models/OllamaProxy.php`

**Step 1: Find the hardcoded URL in OllamaProxy**

Line ~14:
```php
protected string $localUrl = 'http://208.110.93.90:11434';
```

**Step 2: Replace with env-driven value**

```php
protected string $localUrl;

public function __construct(?string $model_name = null)
{
    $this->client = new Client([...]);
    $this->localUrl = env('OLLAMA_GPU_URL', 'http://208.110.93.90:11434');
    $this->cloudUrl = env('OLLAMA_CLOUD_URL');
    ...
}
```

**Step 3: Commit**

```bash
git add app/Models/OllamaProxy.php
git commit -m "fix: OllamaProxy reads OLLAMA_GPU_URL from env instead of hardcoding"
```

---

### Task 4: Fix welcome page — correct API base URL in code example

**Files:**
- Modify: `resources/views/welcome.blade.php`

**Step 1: Find code example in welcome.blade.php**

Search for `openai.base_url` or `/v1` in the view.

**Step 2: Update base URL to match actual route**

Change:
```python
openai.base_url = "https://llm.resayil.io/v1"
```
To:
```python
openai.base_url = "https://llm.resayil.io/api/v1"
```

Also update any `curl` examples showing `-H "..."` with the URL.

**Step 3: Commit**

```bash
git add resources/views/welcome.blade.php
git commit -m "fix: correct API base URL in welcome page code example (/api/v1 not /v1)"
```

---

### Task 5: Deploy and test end-to-end

**Step 1: Push all commits**

```bash
git push
```

**Step 2: Pull on server and clear caches**

```bash
ssh whm-server "cd ~/llm.resayil.io && git pull && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear"
```

**Step 3: Test API call**

Get the existing API key from DB:
```bash
ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker 2>&1 <<'EOF'
\$key = App\Models\ApiKeys::first();
echo \$key->key;
EOF"
```

Run the test:
```bash
ssh whm-server "curl -s -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H 'Authorization: Bearer <KEY_VALUE>' \
  -H 'Content-Type: application/json' \
  -d '{\"model\":\"llama3.2:3b\",\"messages\":[{\"role\":\"user\",\"content\":\"Say hello in one word\"}]}' \
  2>&1"
```

Expected: JSON response with `message.content` containing a greeting (not an error).

**Step 4: Test /models endpoint**

```bash
ssh whm-server "curl -s https://llm.resayil.io/api/v1/models \
  -H 'Authorization: Bearer <KEY_VALUE>' 2>&1"
```

Expected: JSON list of available models.

**Step 5: Commit state update**

Update `.planning/STATE.md`:
- Phase 7 Plan 01: COMPLETE
- API endpoint: WORKING

```bash
git add .planning/STATE.md
git commit -m "docs: Phase 7 Plan 01 complete - API endpoint working"
```
