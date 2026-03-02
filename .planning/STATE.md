---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
status: in_progress
last_updated: "2026-03-02T12:00:00Z"
progress:
  total_phases: 7
  completed_phases: 7
  total_plans: 13
  completed_plans: 12
  percent: 92
---

# State: LLM Resayil Portal

**Last Updated:** 2026-03-02 (Phase 7 Plan 02 in progress — cloud models + monitoring)

## Project Reference

**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

**Current Focus:** Phase 7 Plan 02 — Cloud model names + admin monitoring + model catalog

**Project Context:**
- Laravel SaaS for OpenAI-compatible LLM API access
- MyFatoorah payments (KWD currency)
- Resayil WhatsApp notifications (bilingual Arabic/English)
- Ollama proxy with cloud failover
- 3 subscription tiers (Basic/Pro/Enterprise)
- Credit-based billing system
- Enterprise team management

---

## Current Position

**Phase:** Phase 7 - Backend Services
**Plan:** 02 - Model Selection + Monitoring
**Status:** Plan 01 COMPLETE (API working). Plan 02 in progress (cloud model remapping + monitoring page)
**Progress:** All 6 original phases complete + Phase 7 Plan 01 complete
**Active Requirements:** None

---

## Phase 7 — Complete Log

### Plan 01: Fix API Endpoint ✅ COMPLETE

**Bugs fixed:**
- `config/cache.php` and other config files missing from git → pulled from server, committed
- `ThrottleRequests` in api middleware group hit non-existent `cache` DB table → removed
- `OllamaProxy` hardcoded GPU URL → reads from `OLLAMA_GPU_URL` env
- Welcome page showed `/v1` base URL → fixed to `/api/v1`
- `ApiKeyAuth` middleware used `$request->merge()` → fixed to `setUserResolver()`
- `api_keys` table missing `status` column → migration added (default 'active')
- API routes used `auth:sanctum` → removed, use `api.key.auth` only
- `ChatCompletionsController` return type mismatch `Response` vs `JsonResponse` → fixed
- `OllamaProxy::proxyChatCompletions()` used `Str::of()->count()` (doesn't exist) → `mb_strlen()`
- `UsageLog` model missing UUID booted hook + `$incrementing=false` → fixed
- `ModelAccessControl` closure missing `$tier` in `use()` → fixed
- `CACHE_STORE=file` missing from `.env` (was `CACHE_DRIVER`) → added on server

**Verified working:**
- `POST /api/v1/chat/completions` with `llama3.2:3b` → returns "Hello." ✅
- `GET /api/v1/models` → returns model list ✅

**Additional page fixes (from Chrome audit):**
- `/api-keys` was returning raw JSON → now redirects to dashboard
- `/billing/plans` was 500 (missing view) → created `billing/plans.blade.php`
- `/teams` was returning raw JSON → now returns `teams.dashboard` Blade view
- Queue worker cron added: every minute, `--stop-when-empty --max-time=55`

---

### Plan 02: Model Selection + Monitoring 🔄 IN PROGRESS

**Completed:**
- ✅ Dashboard: model catalog section added (per-tier, click to copy model ID)
- ✅ Dashboard: local/cloud provider badge removed from usage table
- ✅ `ModelsController`: returns tier-filtered model list, no `:cloud` aliases visible
- ✅ Admin monitoring page at `/admin/monitoring` (per-user calls/tokens/credits, top models, recent calls)
- ✅ "Monitor" nav link added for admin

**In progress (agents running):**
- 🔄 Cloud model clean names exposed in catalog (Enterprise: deepseek-v3.1:671b, qwen3.5:397b, etc.)
- 🔄 `OllamaProxy` + `ChatCompletionsController` remap clean names → internal `:cloud` names

**Cloud model mapping (internal → client-facing):**
| Client name | Ollama internal |
|---|---|
| `qwen3.5:397b` | `qwen3.5:cloud` |
| `devstral-2:123b` | `devstral-2:123b-cloud` |
| `deepseek-v3.1:671b` | `deepseek-v3.1:671b-cloud` |
| `deepseek-v3.2` | `deepseek-v3.2:cloud` |

---

## Infrastructure Status

| Service | Status | Notes |
|---------|--------|-------|
| Web app | ✅ Live | https://llm.resayil.io |
| Ollama GPU | ✅ Running | 208.110.93.90:11434 — 17 models |
| Queue worker | ✅ Cron | every minute, --stop-when-empty |
| Redis | ⚪ N/A | Shared hosting — using DB queue + file cache |
| API endpoint | ✅ Working | /api/v1/chat/completions verified |
| Admin monitoring | ✅ Live | /admin/monitoring |

---

## Decisions (this session)

- [Phase 07-01]: Removed `ThrottleRequests` from api middleware — custom `RateLimiter` service handles this
- [Phase 07-01]: `setUserResolver()` over `$request->merge()` for API key auth — makes `$request->user()` work natively
- [Phase 07-01]: `QUEUE_CONNECTION=database` + `CACHE_DRIVER=file` — Redis not available on shared hosting, fail-open acceptable for now
- [Phase 07-02]: Cloud models exposed with clean names, no `:cloud` suffix — clients don't need routing details
- [Phase 07-02]: Enterprise tier gets cloud models (deepseek 671B, qwen 397B) — Basic/Pro local models only
- [Phase 07-02]: Provider badge removed from client dashboard — local/cloud routing is internal

---

## Next Actions

1. ⏳ Wait for cloud model remapping agents to finish → deploy + test `deepseek-v3.1:671b` call
2. Test MyFatoorah payment flow (KWD subscription + top-up)
3. Test enterprise team management flow

---

## Completed Requirements

AUTH-01, AUTH-02, AUTH-03, KEY-01, KEY-02, KEY-03, KEY-04, LP-01 through LP-06, DASH-01 through DASH-05, ADMIN-01 through ADMIN-05, NOTIF-01 through NOTIF-10, SUB-01, SUB-02, SUB-03, TOP-01, TOP-02, API-01 through API-05, RATE-01 through RATE-03, QUEUE-01, QUEUE-02, CLOUD-01, CLOUD-02, MODEL-01 through MODEL-04, TEAM-01, TEAM-02, TEAM-03, TEAM-04

*State file last updated: 2026-03-02 — Phase 7 Plan 01 complete, Plan 02 in progress*
