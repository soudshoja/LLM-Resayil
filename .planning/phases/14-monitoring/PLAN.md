---
phase: 14-monitoring
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Http/Controllers/Api/ChatCompletionsController.php
  - app/Console/Kernel.php
  - app/Console/Commands/SendDailySummary.php
  - app/Console/Commands/AlertQueueFailures.php
  - config/services.php
  - config/sentry.php
  - .env.example
autonomous: true
requirements: [MON-01, MON-02, MON-03, MON-04, MON-05]

must_haves:
  truths:
    - "Sentry captures unhandled Laravel exceptions and reports them to sentry.io"
    - "ChatCompletionsController records response_time_ms in usage_logs on every successful API call"
    - "Failed queue jobs trigger an immediate WhatsApp alert to admin"
    - "Daily cron at 8 AM sends a WhatsApp summary: requests, credits consumed, active users (last 24h)"
    - "UptimeRobot monitors both https://llm.resayil.io and https://llm.resayil.io/v1/models every 5 minutes"
  artifacts:
    - path: "app/Console/Commands/SendDailySummary.php"
      provides: "Artisan command: monitoring:daily-summary"
      exports: ["handle"]
    - path: "app/Console/Commands/AlertQueueFailures.php"
      provides: "Artisan command: monitoring:alert-queue-failures"
      exports: ["handle"]
    - path: "config/sentry.php"
      provides: "Sentry SDK configuration"
      contains: "dsn"
  key_links:
    - from: "app/Http/Controllers/Api/ChatCompletionsController.php"
      to: "usage_logs.response_time_ms"
      via: "microtime() delta passed to CreditService::deductCredits()"
      pattern: "response_time_ms"
    - from: "App\\Console\\Commands\\AlertQueueFailures"
      to: "WhatsAppNotificationService::send()"
      via: "config('services.whatsapp.admin_phones')"
      pattern: "admin_phones"
    - from: "App\\Console\\Commands\\SendDailySummary"
      to: "WhatsAppNotificationService::send()"
      via: "config('services.whatsapp.admin_phones')"
      pattern: "admin_phones"
---

<objective>
Add three layers of monitoring to LLM Resayil:
1. Error tracking via Sentry (unhandled exceptions surface in sentry.io dashboard)
2. API latency capture (response_time_ms already in usage_logs schema — just not being written)
3. Two new artisan commands wired into the scheduler: queue failure alerts and daily stats summary via WhatsApp

Purpose: The platform currently has zero observability. When the GPU OOMs or a queue job fails, nobody
knows until a user complains. This plan closes the highest-priority gaps with minimal new infrastructure,
reusing the existing WhatsAppNotificationService (Phase 4) and the existing usage_logs schema.

Output:
- Sentry installed and capturing exceptions on dev + prod
- response_time_ms populated in usage_logs on every API call
- Daily WhatsApp summary to admin at 8 AM
- Queue failure WhatsApp alert firing within 5 minutes of a failed job
- UptimeRobot configured (human step — free account, 2 minutes of clicking)
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@C:/Users/User/OneDrive - City Travelers/LLM-Resayil/.planning/ROADMAP.md
@C:/Users/User/OneDrive - City Travelers/LLM-Resayil/.planning/STATE.md
@C:/Users/User/OneDrive - City Travelers/LLM-Resayil/CLAUDE.md

<interfaces>
<!-- Key types and contracts the executor needs. Extracted from codebase. -->

From app/Services/WhatsAppNotificationService.php:
```php
// Send a WhatsApp message. $phone is E.164 format.
// Returns ['status' => 'success'|'error', 'message_id' => ...]
public function send(string $phone, string $message, ?string $language = null): array

// Admin phones are configured in config/services.php:
// 'whatsapp' => ['admin_phones' => explode(',', env('WHATSAPP_ADMIN_PHONES', ''))]
// Access via: config('services.whatsapp.admin_phones', [])
```

From database/migrations/2024_02_26_100001_create_usage_logs_table.php:
```
usage_logs columns (all already exist on prod):
  id                uuid PK
  user_id           uuid FK → users
  api_key_id        uuid FK → api_keys (nullable)
  model             string
  tokens_used       integer
  prompt_tokens     integer nullable  (added migration 2026_03_04)
  completion_tokens integer nullable  (added migration 2026_03_04)
  credits_deducted  integer
  provider          enum('local','cloud')
  response_time_ms  integer  ← EXISTS but currently written as 0 always
  status_code       integer
  created_at / updated_at timestamps
```

From app/Http/Controllers/Api/ChatCompletionsController.php:
```php
// CreditService::deductCredits() signature (currently called as):
$this->creditService->deductCredits($user, $tokensUsed, $provider, $modelId,
    $promptTokens, $completionTokens);
// We will add $responseTimeMs as a 7th parameter.
```

From app/Console/Kernel.php — existing schedule entries (do NOT remove any):
```php
$schedule->call(...)->hourly();           // credit warning check
$schedule->call(...)->dailyAt('09:00');  // renewal reminder
$schedule->command('trial:send-reminders')->dailyAt('10:00');
$schedule->command('trial:process-charges')->dailyAt('09:00');
$schedule->command('notifications:retry-failed')->everyFifteenMinutes();
$schedule->call(...)->everySixHours();   // cloud budget check
$schedule->call(...)->hourly();          // new enterprise check
```

From app/Services/CreditService.php (find this file before editing):
```php
// deductCredits writes to usage_logs. Locate the UsageLog::create([...]) call
// and add 'response_time_ms' => $responseTimeMs to the insert array.
```

Admin phone config pattern used by existing listeners:
```php
$adminPhones = config('services.whatsapp.admin_phones', []);
foreach ($adminPhones as $phone) {
    $whatsapp->send($phone, $message, 'ar');
}
```
</interfaces>
</context>

<tasks>

<task type="auto">
  <name>Task 1: Install Sentry and wire response_time_ms into usage_logs</name>
  <files>
    composer.json,
    config/sentry.php,
    app/Http/Middleware/SentryContext.php,
    app/Providers/AppServiceProvider.php,
    app/Services/CreditService.php,
    app/Http/Controllers/Api/ChatCompletionsController.php,
    .env.example
  </files>
  <action>
**Step 1 — Install Sentry SDK:**

```bash
ssh whm-server "cd ~/llmdev.resayil.io && composer require sentry/sentry-laravel --no-interaction"
```

Then publish Sentry config:

```bash
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan vendor:publish --provider='Sentry\Laravel\ServiceProvider'"
```

This creates `config/sentry.php`. The default file is correct. Verify it contains `'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN'))`.

**Step 2 — Add env vars:**

Add to `.env.example` (for documentation):
```
SENTRY_LARAVEL_DSN=
SENTRY_TRACES_SAMPLE_RATE=0.1
```

Add to dev `.env` on server (replace placeholder with real DSN from sentry.io — see user_setup below):
```bash
ssh whm-server "echo 'SENTRY_LARAVEL_DSN=https://YOUR_KEY@oNNNN.ingest.sentry.io/NNNNN' >> ~/llmdev.resayil.io/.env"
ssh whm-server "echo 'SENTRY_TRACES_SAMPLE_RATE=0.1' >> ~/llmdev.resayil.io/.env"
```

**Step 3 — Wire Sentry into Laravel bootstrap:**

In `bootstrap/app.php` (Laravel 11), add inside the `withExceptions` closure:
```php
->withExceptions(function (Exceptions $exceptions) {
    \Sentry\Laravel\Integration::handles($exceptions);
})
```

If `bootstrap/app.php` already has a `withExceptions` block, add the Sentry line inside it — do not create a duplicate block.

**Step 4 — Fix response_time_ms capture:**

Open `app/Services/CreditService.php`. Find the `deductCredits()` method signature and the `UsageLog::create([...])` call inside it.

Current signature (approximate):
```php
public function deductCredits(User $user, int $tokensUsed, string $provider, string $modelId,
    ?int $promptTokens = null, ?int $completionTokens = null): void
```

Change to:
```php
public function deductCredits(User $user, int $tokensUsed, string $provider, string $modelId,
    ?int $promptTokens = null, ?int $completionTokens = null, int $responseTimeMs = 0): void
```

In the `UsageLog::create([...])` array, change:
```php
'response_time_ms' => 0,
```
to:
```php
'response_time_ms' => $responseTimeMs,
```

**Step 5 — Pass timing from ChatCompletionsController:**

In `ChatCompletionsController::store()`, add timing before the proxy call:

```php
$startTime = microtime(true);
$response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);
$responseTimeMs = (int) ((microtime(true) - $startTime) * 1000);
```

Then update the two `deductCredits()` calls in `store()` to pass `$responseTimeMs` as 7th argument:
```php
$this->creditService->deductCredits($user, $tokensUsed, $provider, $modelId,
    $promptTokens, $completionTokens, $responseTimeMs);
```

Do the same in the streaming closure in `stream()` — capture `$startTime` before the `proxyChatCompletions` call and pass `$responseTimeMs` to the deductCredits call inside the closure.

Note: Do NOT use `request()->server('REQUEST_TIME_FLOAT')` — it measures from NGINX, not the actual GPU call. Use microtime() bracketing the proxy call specifically.
  </action>
  <verify>
    <automated>
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan sentry:test" 2>&1
    </automated>
    Manual check: Log into sentry.io and confirm the test event appeared in the project.
    DB check: After one test API call, run:
    ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute=\"echo App\\\Models\\\UsageLog::latest()->value('response_time_ms');\""
    Should return a non-zero integer (e.g., 847 for an 847ms GPU call).
  </verify>
  <done>
    - sentry/sentry-laravel in composer.json require block
    - config/sentry.php exists with DSN env var wired
    - Sentry test event visible in sentry.io dashboard
    - usage_logs.response_time_ms contains real millisecond values (not 0) after API calls
  </done>
</task>

<task type="auto">
  <name>Task 2: Queue failure alert command + daily summary command</name>
  <files>
    app/Console/Commands/AlertQueueFailures.php,
    app/Console/Commands/SendDailySummary.php,
    app/Console/Kernel.php,
    config/services.php
  </files>
  <action>
**Step 1 — Ensure admin_phones config key exists:**

Open `config/services.php`. The `whatsapp` array currently has `api_url` and `api_key`. Add `admin_phones`:

```php
'whatsapp' => [
    'api_url'      => env('RESAYIL_API_URL', 'https://api.resayil.io/v1/messages'),
    'api_key'      => env('RESAYIL_API_KEY'),
    'admin_phones' => array_filter(explode(',', env('WHATSAPP_ADMIN_PHONES', ''))),
],
```

The `array_filter` removes empty strings when the env var is not set.

**Step 2 — Create AlertQueueFailures command:**

Create `app/Console/Commands/AlertQueueFailures.php`:

```php
<?php

namespace App\Console\Commands;

use App\Services\WhatsAppNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AlertQueueFailures extends Command
{
    protected $signature   = 'monitoring:alert-queue-failures';
    protected $description = 'Check for failed queue jobs in the last 5 minutes and alert admin via WhatsApp';

    public function handle(WhatsAppNotificationService $whatsapp): int
    {
        $cutoff = now()->subMinutes(5);

        $failedJobs = DB::table('failed_jobs')
            ->where('failed_at', '>=', $cutoff)
            ->get();

        if ($failedJobs->isEmpty()) {
            return Command::SUCCESS;
        }

        $count   = $failedJobs->count();
        $sample  = $failedJobs->first();
        $jobName = class_basename(json_decode($sample->payload, true)['displayName'] ?? 'Unknown');
        $error   = substr($sample->exception, 0, 200);

        $message = "⚠️ LLM Resayil — Queue Alert\n\n"
            . "{$count} job(s) failed in the last 5 minutes.\n"
            . "Latest: {$jobName}\n"
            . "Error: {$error}\n\n"
            . "Check: ssh whm-server \"cd ~/llmdev.resayil.io && php artisan queue:failed\"";

        $adminPhones = config('services.whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            $this->warn('No admin phones configured (WHATSAPP_ADMIN_PHONES env var).');
            return Command::SUCCESS;
        }

        foreach ($adminPhones as $phone) {
            $whatsapp->send(trim($phone), $message, 'en');
        }

        $this->info("Alert sent for {$count} failed job(s).");

        return Command::SUCCESS;
    }
}
```

Note: This reads from the `failed_jobs` table (created by the existing jobs migration in this project). It does NOT read from the `jobs` table — that's the pending queue. We only alert on already-failed jobs so alerts are never false positives.

**Step 3 — Create SendDailySummary command:**

Create `app/Console/Commands/SendDailySummary.php`:

```php
<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendDailySummary extends Command
{
    protected $signature   = 'monitoring:daily-summary';
    protected $description = 'Send daily API usage summary to admin via WhatsApp';

    public function handle(WhatsAppNotificationService $whatsapp): int
    {
        $since = now()->subDay();

        // Total API requests in last 24h
        $totalRequests = DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->count();

        // Total credits consumed in last 24h
        $totalCredits = DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->sum('credits_deducted');

        // Unique active users in last 24h
        $activeUsers = DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->distinct('user_id')
            ->count('user_id');

        // Local vs cloud split
        $localRequests = DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->where('provider', 'local')
            ->count();

        $cloudRequests = $totalRequests - $localRequests;

        // Avg response time (p50 approximation — skip nulls)
        $avgResponseMs = (int) DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->where('response_time_ms', '>', 0)
            ->avg('response_time_ms');

        // Failed requests (non-200 status)
        $failedRequests = DB::table('usage_logs')
            ->where('created_at', '>=', $since)
            ->where('status_code', '!=', 200)
            ->count();

        // Total registered users
        $totalUsers = User::count();

        $date = now()->format('Y-m-d');

        $message = "📊 LLM Resayil — Daily Summary ({$date})\n\n"
            . "Requests: {$totalRequests} ({$localRequests} local / {$cloudRequests} cloud)\n"
            . "Credits consumed: " . number_format((int) $totalCredits) . "\n"
            . "Active users: {$activeUsers} / {$totalUsers} total\n"
            . "Avg response: {$avgResponseMs}ms\n"
            . "Failed requests: {$failedRequests}\n\n"
            . "Dashboard: https://llm.resayil.io/admin";

        $adminPhones = config('services.whatsapp.admin_phones', []);

        if (empty($adminPhones)) {
            $this->warn('No admin phones configured (WHATSAPP_ADMIN_PHONES env var).');
            return Command::SUCCESS;
        }

        foreach ($adminPhones as $phone) {
            $whatsapp->send(trim($phone), $message, 'en');
        }

        $this->info('Daily summary sent to ' . count($adminPhones) . ' admin(s).');

        return Command::SUCCESS;
    }
}
```

**Step 4 — Register commands in Kernel.php:**

Open `app/Console/Kernel.php`. Add two new schedule entries inside `schedule()`, after the existing entries (do NOT remove any existing entries):

```php
// Queue failure check — every 5 minutes
$schedule->command('monitoring:alert-queue-failures')->everyFiveMinutes();

// Daily stats summary — 8 AM server time
$schedule->command('monitoring:daily-summary')->dailyAt('08:00');
```

**Step 5 — Add env var to .env.example:**

Add to `.env.example`:
```
# Admin WhatsApp numbers for monitoring alerts (comma-separated E.164 format)
WHATSAPP_ADMIN_PHONES=+96512345678,+96598765432
```
  </action>
  <verify>
    <automated>
# Test queue failure command (will report "no failures" cleanly if queue is healthy)
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan monitoring:alert-queue-failures"

# Test daily summary command
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan monitoring:daily-summary"

# Verify commands are registered
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan list monitoring"
    </automated>
  </verify>
  <done>
    - `php artisan list monitoring` shows both monitoring:alert-queue-failures and monitoring:daily-summary
    - Running monitoring:daily-summary sends a WhatsApp to WHATSAPP_ADMIN_PHONES with date, request count, credits, active users
    - Running monitoring:alert-queue-failures exits cleanly when no recent failures; sends WhatsApp when failed_jobs rows exist
    - Both commands appear in Kernel.php schedule at correct frequencies
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <name>Task 3: UptimeRobot setup + prod deployment</name>
  <what-built>
    Tasks 1 and 2 have been deployed to dev (llmdev.resayil.io). Sentry is capturing errors,
    response_time_ms is being recorded, and both monitoring commands are scheduled.
    This checkpoint covers: (a) verifying dev is healthy, (b) UptimeRobot setup (2 minutes of clicking),
    (c) deploying to prod.
  </what-built>
  <how-to-verify>
    **Part A — Verify dev:**

    1. Make a test API call to llmdev.resayil.io:
       ```bash
       curl -s -X POST https://llmdev.resayil.io/v1/chat/completions \
         -H "Authorization: Bearer YOUR_DEV_API_KEY" \
         -H "Content-Type: application/json" \
         -d '{"model":"llama3.2:3b","messages":[{"role":"user","content":"ping"}]}' | jq .
       ```
    2. Check usage_logs has a non-zero response_time_ms:
       ```bash
       ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute=\"echo App\\\Models\\\UsageLog::latest()->value('response_time_ms');\""
       ```
    3. Log into sentry.io — confirm the project shows events (or the "Up and running" confirmation).

    **Part B — UptimeRobot (free, ~2 minutes):**

    1. Go to https://uptimerobot.com and create a free account (or log in).
    2. Click "Add New Monitor":
       - Type: HTTP(s)
       - Friendly Name: LLM Resayil — Homepage
       - URL: https://llm.resayil.io
       - Monitoring Interval: 5 minutes
       - Alert Contact: your email
       - Save
    3. Add a second monitor:
       - Type: HTTP(s)
       - Friendly Name: LLM Resayil — Models API
       - URL: https://llm.resayil.io/v1/models
       - Monitoring Interval: 5 minutes
       - Alert Contact: your email
       - Save
    4. Both monitors should show green within 2-3 minutes.

    **Part C — Deploy to prod:**

    ```bash
    ssh whm-server "cd ~/llm.resayil.io && bash deploy.sh prod"
    ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force"
    ```

    Add SENTRY_LARAVEL_DSN and WHATSAPP_ADMIN_PHONES to prod .env:
    ```bash
    ssh whm-server "echo 'SENTRY_LARAVEL_DSN=https://YOUR_KEY@oNNNN.ingest.sentry.io/NNNNN' >> ~/llm.resayil.io/.env"
    ssh whm-server "echo 'WHATSAPP_ADMIN_PHONES=+96512345678' >> ~/llm.resayil.io/.env"
    ```

    Then clear config cache:
    ```bash
    ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear"
    ```

    Verify prod API works after deploy:
    ```bash
    curl -s https://llm.resayil.io/v1/models | jq '.data | length'
    ```
    Should return a number > 0.
  </how-to-verify>
  <resume-signal>
    Type "approved" after UptimeRobot shows green for both monitors and prod deploy succeeds.
    Or describe any issues encountered.
  </resume-signal>
</task>

</tasks>

<verification>
After all tasks complete, verify the full monitoring stack:

1. **Sentry:** Trigger a test exception with `php artisan sentry:test` on prod — appears in sentry.io within 30 seconds.

2. **Latency tracking:** Check that recent usage_logs rows have response_time_ms > 0:
   ```bash
   ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan tinker --execute=\"App\\\Models\\\UsageLog::where('response_time_ms','>',0)->count();\""
   ```
   Should return > 0 after the first successful API call post-deploy.

3. **Daily summary:** Run manually to confirm message arrives on WhatsApp:
   ```bash
   ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan monitoring:daily-summary"
   ```

4. **UptimeRobot:** Both monitors green at https://uptimerobot.com/dashboard.

5. **Schedule:** Confirm new commands appear in schedule list:
   ```bash
   ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan schedule:list"
   ```
   Should show `monitoring:alert-queue-failures` (every 5 min) and `monitoring:daily-summary` (daily at 08:00).
</verification>

<success_criteria>
- [ ] Sentry captures Laravel exceptions on both dev and prod
- [ ] usage_logs.response_time_ms populated with real GPU response times (non-zero)
- [ ] monitoring:daily-summary delivers WhatsApp to admin with: date, request count, credits, active users, avg latency, failed count
- [ ] monitoring:alert-queue-failures fires WhatsApp within 5 minutes of a job failure
- [ ] UptimeRobot monitors both / and /v1/models at 5-min intervals with email alerting
- [ ] No migrations required (response_time_ms column already exists on prod)
- [ ] No new DB tables created (uses existing usage_logs and failed_jobs tables)
</success_criteria>

<user_setup>
Before Task 1 can complete, the executor needs a Sentry DSN:
- service: sentry.io
- why: Error tracking DSN required before php artisan sentry:test can pass
- steps:
  1. Go to https://sentry.io/signup/ — free tier (5K errors/month) is sufficient
  2. Create a new project: Platform = Laravel, Project name = llm-resayil
  3. Copy the DSN (format: https://KEY@oNNNN.ingest.sentry.io/PROJECT_ID)
  4. Set on dev: `ssh whm-server "echo 'SENTRY_LARAVEL_DSN=YOUR_DSN' >> ~/llmdev.resayil.io/.env"`
  5. Repeat for prod .env after Task 3 deploy

Before Task 3 can complete (UptimeRobot):
- service: uptimerobot.com
- why: Free uptime monitoring — no API access needed, web UI only
- steps: See Task 3 checkpoint instructions (Part B) — ~2 minutes of clicking
- env_vars: none (UptimeRobot is external, no Laravel integration needed)

WHATSAPP_ADMIN_PHONES env var:
- Add your WhatsApp number in E.164 format to both dev and prod .env
- Example: WHATSAPP_ADMIN_PHONES=+96512345678
- Multiple numbers: WHATSAPP_ADMIN_PHONES=+96512345678,+96598765432
</user_setup>

<output>
After completion, create `.planning/phases/14-monitoring/14-01-SUMMARY.md` with:
- What was installed (Sentry version, commands created)
- Env vars added to both dev and prod
- UptimeRobot monitor IDs/URLs
- Any deviations from this plan
- response_time_ms sample values observed (p50, p95 if visible)
</output>

---

## Phase 14 — What is NOT in this plan (intentional scope decisions)

**GPU Prometheus + Grafana (deferred):**
The GPU server already has `nvidia_gpu_exporter` running on port 9835. Wiring up Prometheus + Grafana
is real value but requires SSH access to 208.110.93.90 and Docker installation — a separate 2-hour
session. Treat as Phase 14 Plan 02 when GPU monitoring becomes urgent.

**Laravel Telescope (excluded entirely):**
Telescope is explicitly excluded from prod — too heavy (writes every request to DB). Useful locally
during development but adds no value here. Install locally with `composer require laravel/telescope --dev`
if needed for debugging; never deploy to prod.

**VRAM alert bash script (deferred with GPU work):**
The VRAM alert (bash cron → WhatsApp when VRAM > 90%) belongs alongside the Prometheus/Grafana work
since both require GPU server access. Documented here for the Phase 14 Plan 02 executor:
```bash
# GPU server cron (every 2 minutes):
*/2 * * * * /usr/bin/nvidia-smi --query-gpu=memory.used,memory.total --format=csv,noheader,nounits | \
  awk -F', ' '{pct=int($1/$2*100); if(pct>90) system("curl -s -X POST https://api.resayil.io/v1/messages ...")}'
```

**Laravel Horizon (excluded):**
Queue worker runs via `php artisan queue:work --stop-when-empty` on a cron every minute (cPanel shared
hosting constraint). Horizon requires a persistent daemon — not compatible with cPanel's process model.
The `monitoring:alert-queue-failures` command provides equivalent failure visibility without Horizon.
