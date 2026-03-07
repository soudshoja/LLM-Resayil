# Phase 12 — API Test & Monitoring Findings

**Date:** 2026-03-07
**Tested by:** Automated SSH + curl from cPanel server
**API Base URL:** https://llm.resayil.io/v1
**Saied's API key:** `1b5ef32d40c846d5918ffbd24ac4a6307dc73f32711c7fd10380213ce2967b3e`
**User ID:** `a20de43f-e2d7-40ff-92cd-54ee5002c4cb`

---

## Task 1: API Tests

### Test 1 — Small Context, smollm2:135m

**Prompt:** "Say hello in one sentence."
**Status:** PASS
**HTTP:** 200
**Response time:** 706ms
**Response body (first 200 chars):**
```json
{"id":"chatcmpl-33a1383a...","model":"smollm2:135m","choices":[{"message":{"role":"assistant","content":"Hello! I'm here to help you have fun and learn with your language learning adventure."}}]}
```
**Notes:** Fast, clean response. Standard OpenAI-compatible shape.

---

### Test 2 — Medium Context (~500 tokens), smollm2:135m

**Prompt:** ~150-word prompt explaining supervised learning, cross-validation.
**Status:** PASS (on retry with correct JSON encoding)
**HTTP:** 200
**Response time:** 2,414ms
**Response body (first 200 chars):**
```json
{"id":"chatcmpl-d53f28e7...","model":"smollm2:135m","choices":[{"message":{"role":"assistant","content":"Supervised learning is a type of machine learning technique that uses labeled data to train models..."}}]}
```
**Notes:** First attempt returned HTTP 302 redirect — this was a curl shell-escaping issue in the test script (the JSON payload was corrupted by shell variable expansion). On a clean retry with `--data-binary` and proper quoting, it returned 200 cleanly. The actual API handles medium context correctly. 2.4s is acceptable for a 135M model.

---

### Test 3 — Large Context (~2000 tokens), smollm2:135m

**Prompt:** Long prompt covering distributed systems: CAP theorem, Paxos/Raft, Cassandra/DynamoDB/Spanner, Kafka/RabbitMQ, service mesh, observability, circuit breakers.
**Status:** PASS
**HTTP:** 200
**Response time:** 2,781ms
**Response body (first 200 chars):**
```json
{"id":"chatcmpl-1d988f0b...","model":"smollm2:135m","choices":[{"message":{"role":"assistant","content":"1. CAP theorem (Completeness Theorem): A database has consistency if its integrity constraints are satisfied..."}}]}
```
**Notes:** Large context handled without error. 2.8s response despite large input shows the GPU pipeline is efficient. The model (135M) produces lower-quality output on complex topics — expected for its size.

---

### Test 4 — rnj-1:8b-cloud Model, Short Prompt

**Prompt:** "What is 2+2?"
**Status:** PASS
**HTTP:** 200
**Response time:** 1,182ms
**Response body (first 200 chars):**
```json
{"id":"chatcmpl-1ac6c19b...","model":"rnj-1:8b-cloud","choices":[{"message":{"role":"assistant","content":"2 + 2 equals 4."},"finish_reason":"stop"}],"usage":{"prompt_tokens":43,"completion_tokens":9,"total_tokens":52}}
```
**Notes:** The `rnj-1:8b-cloud` model works correctly. The `-cloud` suffix routing (presumably forwarded to an external cloud provider or vLLM) functions. Usage token counts are returned, which smollm2:135m tests also included.

---

### Test 5 — /v1/models Endpoint

**Status:** PASS
**HTTP:** 200
**Response time:** 477ms
**Full model list returned:**
```
smollm2:135m
rnj-1:8b
gemma3:27b
qwen3-coder-next
kimi-k2:1t
qwen3-vl:235b-instruct
mistral-large-3:675b
ministral-3:8b
ministral-3:3b
nemotron-3-nano:30b
qwen3-coder:480b
cogito-2.1:671b
gemma3:12b
ministral-3:14b
llama3.2:3b
llama3.2:latest
qwen3.5
qwen3-30b-40k:latest
deepseek-v3.1:671b
hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M
mistral-small3.2:24b-instruct-2506-q4_K_M
alibayram/Qwen3-30B-A3B-Instruct-2507:latest
qwen2.5-coder:14b
```
**Notes:** 23 models listed. OpenAI-compatible format with `owned_by`, `family`, `category`, `size` fields. The models endpoint is working correctly and requires auth (Bearer token validated).

---

### Test 6 — Streaming Response (stream: true)

**Status:** FAIL
**HTTP:** 500
**Response time:** 571ms
**Response body:** `{"message":"Server error."}`

**Root cause identified from laravel.log:**
```
[2026-03-07 10:02:50] production.ERROR:
App\Http\Controllers\Api\ChatCompletionsController::store():
Return value must be of type Illuminate\Http\Response|Illuminate\Http\JsonResponse,
Symfony\Component\HttpFoundation\StreamedResponse returned
at .../ChatCompletionsController.php:163
```

**Explanation:** The `store()` method has a strict PHP return type declaration of `Response|JsonResponse`. When `stream: true` is passed, the controller internally calls `response()->stream()` which produces a `StreamedResponse` — a different class that doesn't satisfy the declared return type. PHP 8.x enforces this at runtime and throws a `TypeError`, which Laravel catches and returns as a 500.

**The streaming logic does exist** (found at lines 167–293 in ChatCompletionsController.php), but it is unreachable through `store()` because the type signature rejects it. The route appears to send all requests through `store()` rather than routing `stream: true` requests to the separate `stream()` method.

**Fix required:** Either change the return type of `store()` to include `StreamedResponse`, or add routing logic that redirects `stream: true` requests to the `stream()` method before hitting the type check.

---

### API Test Summary

| Test | Model | Status | HTTP | Time |
|------|-------|--------|------|------|
| 1. Small context | smollm2:135m | PASS | 200 | 706ms |
| 2. Medium context (~500 tokens) | smollm2:135m | PASS | 200 | 2,414ms |
| 3. Large context (~2000 tokens) | smollm2:135m | PASS | 200 | 2,781ms |
| 4. rnj-1:8b-cloud short prompt | rnj-1:8b-cloud | PASS | 200 | 1,182ms |
| 5. /v1/models endpoint | — | PASS | 200 | 477ms |
| 6. Streaming (stream: true) | smollm2:135m | FAIL | 500 | 571ms |

**5/6 tests pass. The only failure is streaming, with a clear and fixable root cause.**

---

## Task 2: Monitoring Inventory

### cPanel Server (152.53.86.223) — Laravel App

#### Laravel Packages
- **Laravel Telescope:** NOT installed
- **Laravel Horizon:** NOT installed

#### Error Tracking
- **Sentry:** NOT configured (not in .env)
- **Bugsnag / Rollbar / Honeybadger:** NOT configured
- No error tracking service of any kind is active.

#### Log Aggregation
- **Papertrail / Logtail / Datadog:** NOT configured
- Logs are local only.

#### Log Status (storage/logs/)
```
total 28K
-rw-r--r-- 1 resayili resayili 11K Mar  7 13:02 laravel.log
-rw-r--r-- 1 resayili resayili 14K Mar  2 05:06 queue.log
```
- laravel.log is 11KB — recent activity. Contains the TypeError from the streaming bug.
- queue.log is 14KB — queue worker output.
- No log rotation configured (both files are small so not yet a problem, but will grow).

#### Last laravel.log Errors
1. **PHP Parse error (psysh)** — a tinker/psysh parse error, likely from debugging session. Not a production issue.
2. **StreamedResponse TypeError** — the streaming bug identified above (Test 6).

#### Cron Jobs
```
* * * * * /opt/cpanel/ea-php82/.../php artisan queue:work --stop-when-empty --max-time=55
           >> storage/logs/queue.log 2>&1
```
- One cron: queue worker runs every minute, stops when queue is empty, max 55s runtime.
- No health check crons.
- No uptime ping crons.
- No log rotation crons.

#### Running Monitoring Agents
- None. No Datadog, NewRelic, Netdata, Prometheus, or Zabbix agents running.

#### Uptime Monitoring
- No UptimeRobot, Statuspage, or similar service configured or detected.

---

### GPU Server (208.110.93.90) — Ollama / vLLM / Inference

#### Running Containers
```
vllm/vllm-openai:latest     — port 8001, up 12 days (vLLM serving)
ollama/ollama:latest        — port 11434, up 12 days (Ollama)
open-webui                  — port 3000, up 12 days, status: healthy
```

#### GPU / Hardware
```
GPU: NVIDIA RTX A6000
VRAM: 25,846 MiB / 49,140 MiB used (52%)
GPU Temp: 44C, Power: 67W / 300W
GPU Util: 0% (idle between requests)

Processes using GPU:
  VLLM::EngineCore   — 25,030 MiB
  /usr/bin/ollama    — 802 MiB
```
**Server resources (healthy):**
- RAM: 15Gi used / 251Gi total — enormous headroom
- Disk: 90G / 224G used (40%)
- Uptime: 12 days
- Load average: 0.05 (essentially idle)

#### Monitoring: nvidia_gpu_exporter (FOUND — active)
```
Service: nvidia_gpu_exporter.service
Status: active (running) since Mon 2026-02-23 10:08:41 UTC — 12 days uptime
Binary: /usr/bin/nvidia_gpu_exporter
Port: 9835 (Prometheus metrics endpoint)
Memory: 25.6 MB
CPU: 1h 4min cumulative
```
- Exposes Prometheus-format metrics at `http://localhost:9835/metrics`
- Metrics include: go_gc_duration_seconds, go_goroutines, GPU utilization, VRAM, temperature, power
- **BUT: no Prometheus server is scraping these metrics** — the exporter is running but its data goes nowhere. No Grafana, no alerting, no dashboards.

#### Ollama Metrics
- `GET /metrics` → 404 (Ollama does not expose a metrics endpoint natively)
- Ollama API `/api/tags` works — returns all 23 loaded models

#### Crontab
- No crontab for soudshoja user.

#### Other Monitoring
- **Netdata:** Not installed
- **Prometheus:** Not running (no container, no process)
- **Grafana:** Not running
- **Datadog / NewRelic / Telegraf / Zabbix:** Not found
- **No alerting scripts found** (deploy.sh was the only .sh file found, contains no alert logic)
- **Port 4001:** Open but no process found via ss — likely a service not started or firewall rule remnant

#### vLLM Health
- vLLM is receiving internet scan traffic (bots hitting / and /wiki) — expected for public-facing port
- No application errors visible in last 15 log lines

---

## Monitoring Gap Assessment

### What Exists
| Component | Tool | Coverage |
|-----------|------|----------|
| GPU metrics collection | nvidia_gpu_exporter | Partial — collecting but not stored/alerted |
| Queue processing | cron + queue.log | Minimal — logs exist, no alerting |
| Application logs | local laravel.log | Minimal — local only, no aggregation |
| Container health | Docker healthcheck (open-webui) | Partial — one container only |

### What Is Missing (Critical Gaps)

**1. No error alerting**
Errors like the streaming TypeError happen silently. Nothing notifies the team. You only find out when a user reports it (or you check logs manually).
**Recommendation:** Add Sentry (free tier sufficient). One line in Laravel .env: `SENTRY_LARAVEL_DSN=...`

**2. No uptime monitoring**
If llm.resayil.io goes down, nobody is automatically notified.
**Recommendation:** UptimeRobot free tier — monitors every 5 minutes, sends email/Telegram on down.

**3. GPU metrics not stored or alerted**
nvidia_gpu_exporter runs but no Prometheus scrapes it. VRAM is at 52% — if vLLM loads more models it could OOM without warning.
**Recommendation:** Either add a lightweight Prometheus + Grafana (docker-compose, 30 min setup), or use Grafana Cloud free tier (5GB logs, 10K metrics free) pointed at the exporter.

**4. No log aggregation**
laravel.log and queue.log are local. If the server has a disk issue, logs are gone. Cannot search across time.
**Recommendation:** Logtail or Papertrail (both have free tiers). Laravel has a built-in Logtail driver.

**5. No queue health monitoring**
Queue worker runs every minute but queue.log is last updated 5 days ago (Mar 2). Either the queue has been idle or the cron stopped writing. No alert if queue backs up.
**Recommendation:** Add a `queue:monitor` threshold or Horizon (free, adds queue dashboard and alerts).

**6. No GPU VRAM alert**
If Ollama or vLLM gets an OOM kill, inference stops silently. VRAM headroom is 48% now but cloud models may increase load.
**Recommendation:** Simple cron script: `nvidia-smi --query-gpu=memory.used --format=csv,noheader` compared to threshold, send Telegram/email if >90%.

---

## Bug Found: Streaming Endpoint (Actionable Fix)

**File:** `/home/resayili/llm.resayil.io/app/Http/Controllers/Api/ChatCompletionsController.php`
**Line:** 163 (the `store()` method return type declaration)

**Current return type:**
```php
public function store(Request $request): Response|JsonResponse
```

**Problem:** When `stream: true`, the method internally returns `response()->stream(...)` which is a `Symfony\Component\HttpFoundation\StreamedResponse`. This does not satisfy `Response|JsonResponse` at PHP runtime.

**Fix options (pick one):**

Option A — Add StreamedResponse to the return type:
```php
use Symfony\Component\HttpFoundation\StreamedResponse;

public function store(Request $request): Response|JsonResponse|StreamedResponse
```

Option B — Route streaming requests to the existing `stream()` method before returning:
In `store()`, detect `$validated['stream'] === true` early and `return $this->stream($request)` before hitting the type-checked return path.

Option B is cleaner because the `stream()` method (line 169) already has the correct implementation.

---

## Summary

**API:** 5/6 tests pass. Streaming fails due to a PHP return type constraint — easily fixed.
**Monitoring:** Essentially none. The nvidia_gpu_exporter is running but its data goes nowhere. No error tracking, no uptime monitoring, no alerting of any kind.
**Priority fixes:**
1. Fix streaming bug in ChatCompletionsController (30 min)
2. Add Sentry to Laravel (15 min, free)
3. Add UptimeRobot for llm.resayil.io (5 min, free)
4. Wire Prometheus to nvidia_gpu_exporter + basic VRAM alert (1–2 hours)
