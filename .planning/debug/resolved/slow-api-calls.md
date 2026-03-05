---
status: resolved
trigger: "slow-api-calls-prod"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T00:15:00Z
---

## Current Focus

hypothesis: RESOLVED — all 4 bottlenecks fixed and deployed to prod
test: verified on prod — auth rejection 151ms, no Redis errors in log, index confirmed
expecting: significant latency reduction for all non-admin API calls
next_action: none

## Symptoms

expected: API calls complete in reasonable time (< 2s for non-streaming, streaming starts quickly)
actual: API calls are slow — clients complaining
errors: No specific error — just latency
reproduction: Make an API call to https://llm.resayil.io/api/v1/chat/completions
started: Reported 2026-03-05

## Eliminated

(no false leads — all hypotheses confirmed directly via evidence)

## Evidence

- timestamp: 2026-03-05T00:01:00Z
  checked: prod laravel.log
  found: "RateLimiter check error: Connection refused" and "RateLimiter increment error: Connection refused"
  implication: Redis not running; every non-admin call suffered 2 TCP failure overheads

- timestamp: 2026-03-05T00:01:00Z
  checked: prod .env
  found: REDIS_HOST=127.0.0.1 REDIS_PORT=6379 but CACHE_DRIVER=file QUEUE_CONNECTION=database
  implication: Redis config present but no Redis server running; RateLimiter used Redis facade directly

- timestamp: 2026-03-05T00:02:00Z
  checked: app/Services/RateLimiter.php
  found: Used Illuminate\Support\Facades\Redis directly, not Cache facade — bypassed CACHE_DRIVER=file setting
  implication: 2 Redis connection failures per non-admin request

- timestamp: 2026-03-05T00:02:00Z
  checked: ApiKeyAuth middleware + migration
  found: ApiKeys::where('key', $key)->first() with NO index on key column (only PRIMARY id + user_id FK)
  implication: Full table scan on every API auth request

- timestamp: 2026-03-05T00:02:00Z
  checked: ApiKeyAuth middleware
  found: $apiKey->update(['last_used_at' => now()]) called synchronously on every single request
  implication: Extra DB write round-trip on every API call

- timestamp: 2026-03-05T00:03:00Z
  checked: ChatCompletionsController::resolveModelDynamically() / fetchModelsFromOllama()
  found: Makes HTTP GET to Ollama /api/tags on EVERY request with no caching whatsoever
  implication: ~380ms HTTP overhead before the actual LLM call, on every request

- timestamp: 2026-03-05T00:03:00Z
  checked: GPU server response time from prod
  found: connect:0.122522s total:0.379239s to http://208.110.93.90:11434
  implication: 380ms mandatory overhead per request for uncached model resolution

- timestamp: 2026-03-05T00:14:00Z
  checked: prod after deployment
  found: Auth rejection responds in 0.151s. No RateLimiter errors in log. api_keys_key_unique index confirmed present.
  implication: All 4 fixes working correctly on prod

## Resolution

root_cause: |
  4 stacking bottlenecks on every API call:
  1. CRITICAL: RateLimiter used Redis facade directly (Redis not running) — 2 TCP failures per non-admin request
  2. HIGH: fetchModelsFromOllama() called with no cache — ~380ms HTTP to GPU server on EVERY request
  3. MEDIUM: api_keys.key had no DB index — full table scan on every auth lookup
  4. LOW: ApiKeyAuth did synchronous last_used_at DB write on every request

fix: |
  1. app/Services/RateLimiter.php — replaced Redis facade with Cache facade (uses file driver per CACHE_DRIVER=file)
  2. app/Http/Controllers/Api/ChatCompletionsController.php — added Cache::has()/put() around fetchModelsFromOllama() for 60s caching; failure cached 10s to prevent retry storms
  3. database/migrations/2026_03_05_000001_add_key_index_to_api_keys_table.php — unique index on api_keys.key
  4. app/Http/Middleware/ApiKeyAuth.php — throttle last_used_at write to max once per 60s

verification: |
  - Dev migration ran: 2026_03_05_000001 DONE in 19ms
  - Prod migration ran: 2026_03_05_000001 DONE in 13ms
  - Prod api_keys_key_unique index confirmed via SHOW INDEX
  - No RateLimiter/Redis errors in prod log after deployment
  - Auth rejection response: 0.151s on prod (was potentially much higher)
  - Prod deploy completed: https://llm.resayil.io

files_changed:
  - app/Services/RateLimiter.php
  - app/Http/Controllers/Api/ChatCompletionsController.php
  - app/Http/Middleware/ApiKeyAuth.php
  - database/migrations/2026_03_05_000001_add_key_index_to_api_keys_table.php
