---
gsd_state_version: 1.0
milestone: v1.1
milestone_name: documentation-links
status: complete
last_updated: "2026-03-03T12:30:00Z"
progress:
  total_phases: 7
  completed_phases: 7
  total_plans: 15
  completed_plans: 15
  percent: 100
---

# State: LLM Resayil Portal

**Last Updated:** 2026-03-03 (Phase 7 complete - Documentation + Billing Links)

## Project Reference

**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

**Current Focus:** v1.1 — Phase 7 Plan 07 in progress (UI fixes + billing enhancements + admin unlimited)

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
**Plan:** 04 - UI/Admin/Profile Improvements
**Status:** COMPLETE - Dynamic model loading, category tags, admin filters, profile page, branding cleanup
**Progress:** All 6 original phases + Phase 7 Plans 01, 02, 03, 04 complete
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

### Plan 02: Model Selection + Monitoring ✅ COMPLETE

**Completed:**
- ✅ Dashboard: model catalog section added (per-tier, click to copy model ID)
- ✅ Dashboard: local/cloud provider badge removed from usage table
- ✅ `ModelsController`: returns tier-filtered model list, no `:cloud` aliases visible
- ✅ Admin monitoring page at `/admin/monitoring` (per-user calls/tokens/credits, top models, recent calls)
- ✅ "Monitor" nav link added for admin
- ✅ Cloud model clean names exposed, OllamaProxy remaps clean → internal `:cloud` names
- ✅ All 4 cloud models verified working end-to-end (authenticated Ollama with ollama.com)
- ✅ All 30 ollama.com cloud proxy models pulled to GPU server (30 cloud + 15 local = 45 total)

---

### Plan 03: Full Model Catalog + Admin Model Panel ✅ COMPLETE

**Completed:**
- ✅ **Agent 1 - Model Registry:**
  - Created `database/migrations/2026_03_02_032411_create_models_table.php` (model_id PK, is_active, credit_multiplier_override)
  - Created `app/Models/ModelConfig.php` (Eloquent model with UUID hook)
  - Created `config/models.php` (45 models with full metadata + Ollama name mapping)
  - Rewrote `app/Http/Controllers/Api/ModelsController.php` (all models, no tier filter)
  - Updated `app/Http/Controllers/Api/ChatCompletionsController.php` (registry-based resolution, admin bypass, tier removal)

- ✅ **Agent 2 - Searchable Dashboard UI:**
  - Replaced hardcoded model grid with dynamic JS fetch from `/api/v1/models`
  - Added filters: family dropdown, type (local/cloud), size (small/medium/large), text search
  - Click model → detail panel with curl + Python + n8n snippets
  - Dark luxury theme maintained with Inter + Tajawal fonts

- ✅ **Agent 3 - Admin Model Panel:**
  - Created `app/Http/Controllers/AdminModelController.php` (index, update, createApiKey, setCredits, setTier, setExpiry)
  - Created `resources/views/admin/models.blade.php` (model list, toggle, edit modal)
  - Extended `AdminController` with user management methods
  - Added routes: `/admin/models`, `/admin/users/{user}/keys`, credits/tier/expiry POST endpoints

**Decisions:**
- All 45 models (15 local + 30 cloud) available to all tiers — tiers only affect rate limits + credit costs
- Models disabled by admin disappear from API + dashboard instantly
- Admin (`admin@llm.resayil.io`) bypasses rate limits, credit checks, model access checks
- Cloud models cost 2x credits, local models cost 1x — enforced per model in registry (DB-overridable)

---

### Plan 04: UI/Admin/Profile Improvements COMPLETE

**Completed:**
- Dynamic Ollama model loading: `ModelsController` queries `GET {OLLAMA_GPU_URL}/api/tags` live; falls back to `config/models.php` on error
- `ChatCompletionsController` updated to use `resolveModelDynamically()` instead of static registry
- Category tags with emoji badges (chat/code/embedding/vision/thinking/tools) added to dashboard model catalog
- Type filter (Local/Cloud) hidden from regular users — admin-only visibility
- Admin models page (`/admin/models`) fully rewritten: live Ollama load, Family/Category/Type/Size/Search filters, category column with emoji badges
- Admin models routing fix: `PUT /admin/models/{id}` → `POST /admin/models/update` with `model_id` in body (avoids slash-in-ID routing failures)
- Profile page: `GET/POST /profile` + `POST /profile/password`, `ProfileController`, `profile.blade.php`, nav link added
- MyFatoorah branding removed from welcome, billing/plans, and dashboard — replaced with "KNET / credit card"
- HuggingFace family inference fix: `inferFamily()` extracts last path segment for names containing `/`

**Decisions:**
- [Phase 07-04]: Admin models route uses POST body for model_id — URL-safe approach for IDs with slashes
- [Phase 07-04]: Type filter hidden from non-admin users — local/cloud routing is internal detail
- [Phase 07-04]: Profile password change requires current password confirmation before accepting new password

---

## Infrastructure Status

| Service | Status | Notes |
|---------|--------|-------|
| Web app | ✅ Live | https://llm.resayil.io |
| Ollama GPU | ✅ Running | 208.110.93.90:11434 — 45 models (15 local + 30 cloud proxy) |
| Queue worker | ✅ Cron | every minute, --stop-when-empty |
| Redis | ⚪ N/A | Shared hosting — using DB queue + file cache |
| API endpoint | ✅ Working | /api/v1/chat/completions verified |
| Admin monitoring | ✅ Live | /admin/monitoring |
| Admin models | ✅ Live | /admin/models |
| Admin user actions | ✅ Live | Inline buttons for credits, tier, expiry, API key |
| Billing | ✅ Live | /billing/plans with new tiers and trial |

---

## Decisions (this session)

- [Phase 07-01]: Removed `ThrottleRequests` from api middleware — custom `RateLimiter` service handles this
- [Phase 07-01]: `setUserResolver()` over `$request->merge()` for API key auth — makes `$request->user()` work natively
- [Phase 07-01]: `QUEUE_CONNECTION=database` + `CACHE_DRIVER=file` — Redis not available on shared hosting, fail-open acceptable for now
- [Phase 07-02]: Cloud models exposed with clean names, no `:cloud` suffix — clients don't need routing details
- [Phase 07-02]: Enterprise tier gets cloud models (deepseek 671B, qwen 397B) — Basic/Pro local models only
- [Phase 07-02]: Provider badge removed from client dashboard — local/cloud routing is internal
- [Phase 07-03]: All 45 models available to all tiers — only rate limits and credit costs vary by tier
- [Phase 07-03]: Admin bypasses all restrictions — rate limits, credit checks, model access
- [Phase 07-03]: Cloud models cost 2× credits, local 1× — enforced from registry
- [Phase 07-04]: Admin models route uses POST body for model_id — URL-safe approach for IDs with slashes
- [Phase 07-04]: Type filter hidden from non-admin users — local/cloud routing is internal detail
- [Phase 07-04]: Profile password change requires current password confirmation before accepting new password
- [Phase 07-05]: New subscription tiers: Starter (15 KWD), Basic (25 KWD), Pro (45 KWD) monthly
- [Phase 07-05]: Credit costs based on model size with local/cloud multipliers
- [Phase 07-05]: Free 7-day trial with auto-billing to Starter tier
- [Phase 07-05]: MyFatoorah recurring payment gateway for credit card subscriptions
- [Phase 07-05]: Admin user management with inline action buttons

| Plan 02 | Complete | Cloud model remapping, monitoring page |
| Plan 03 | Complete | Full model catalog, admin panel, dashboard UI |
| Plan 04 | Complete | Dynamic model loading, category tags, admin filters, profile page, branding cleanup |
| Plan 05 | Complete | Free trial, recurring payments, new pricing, admin user actions |

---

## Completed Requirements

AUTH-01, AUTH-02, AUTH-03, KEY-01, KEY-02, KEY-03, KEY-04, LP-01 through LP-06, DASH-01 through DASH-05, ADMIN-01 through ADMIN-05, NOTIF-01 through NOTIF-10, SUB-01, SUB-02, SUB-03, TOP-01, TOP-02, API-01 through API-05, RATE-01 through RATE-03, QUEUE-01, QUEUE-02, CLOUD-01, CLOUD-02, MODEL-01 through MODEL-04, TEAM-01, TEAM-02, TEAM-03, TEAM-04, BILL-01 through BILL-10, TRIAL-01 through TRIAL-05, RECUR-01 through RECUR-03

---

## Plan 06: Documentation + Billing Links ✅ COMPLETE

**Completed:**
- ✅ Created `/docs` documentation hub page at `resources/views/docs.blade.php`
- ✅ Added `/docs` route to `routes/web.php`
- ✅ Added "Docs" and "Credits" nav links to `resources/views/layouts/app.blade.php`
- ✅ Added "How Credits Work" link to billing/plans trial section
- ✅ Added "How Credits Work" link to dashboard Top Up Credits section
- ✅ Deployed to production with `git push` and `git pull` on server

**Git Commits:**
```
d162e10 feat: add /docs page + How Credits Work links
1d1cd3f feat: add /credits page (How Credits Work) + Phase 8 plan + roadmap entry
eb44537 feat: AJAX toggle for admin models — no page reload on enable/disable
```

**Production URLs:**
- `/docs` → Documentation hub with 3 plan cards
- `/credits` → "How Credits Work" standalone page
- Navbar: "Docs" and "Credits" links visible for authenticated users

---

*State file last updated: 2026-03-03 — Phase 7 ALL plans complete (100%)*
