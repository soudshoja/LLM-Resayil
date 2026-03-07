---
gsd_state_version: 1.0
milestone: v1.2
milestone_name: seo-optimization
status: in-progress
last_updated: "2026-03-07T18:00:00.000Z"
last_activity: 2026-03-07
progress:
  total_phases: 14
  completed_phases: 11
  total_plans: 20
  completed_plans: 20
---

# State: LLM Resayil Portal — v1.2 SEO Optimization

**Current Position:** Phase 11 COMPLETE, Phase 12 mostly complete, Phase 13 planned
**Phase:** 13 (Usage Visibility)
**Status:** Planned / Queued

**Last Activity:** 2026-03-07
**Result:** Phase 11 (Content & Technical SEO) all 4 plans complete — deployed to prod as v1.11.0. Phase 12 (Saied API Fix) infrastructure bugs resolved, streaming/routing fix in progress. Phase 13 (Usage Visibility) fully planned and ready to execute.

---

## Milestone v1.2 Context

**Source:** Full SEO audit on llm.resayil.io completed 2026-03-06
**Audit Score:** 62/100 (moderate SEO health)

**Key Findings:**
- Technical SEO: 78/100 (good)
- Content Quality: 52/100 (thin documentation)
- Schema Markup: 35/100 (critical gap)
- E-E-A-T: 35/100 (no authority signals)
- AI Search Readiness: 48/100 (at risk)

**Critical Issues to Fix:**
1. Zero schema markup (blocks AI Overviews)
2. Missing meta descriptions (50% of pages)
3. Thin /docs (737 words, needs 2,500+)
4. No E-E-A-T signals (no team, testimonials, case studies)
5. Robots.txt blocks AI crawlers from /pricing, /features

---

## Phases Overview

| Phase | Name | Goal | Status |
|-------|------|------|--------|
| 10 | SEO Foundation | Schema, metadata, robots.txt, titles | COMPLETE |
| 11 | Content & Technical SEO | Docs, hreflang, image alt, FAQ/features | COMPLETE — v1.11.0 on prod |
| 12 | Saied API Fix | Fix API infrastructure bugs for external client | MOSTLY COMPLETE |
| 13 | Usage Visibility | Usage logs, admin key mgmt, credit top-up fix | PLANNED |
| 14 | Monitoring | Sentry, WhatsApp alerts, UptimeRobot | PLANNED |

---

## Phase 11 Summary (COMPLETE — v1.11.0 on prod)

**Deployed 2026-03-07. All 8 public routes return 200 on prod.**

- 11-01: Documentation expansion — 7 docs pages, 2,450+ words, 14 code examples, JSON-LD breadcrumb schema
- 11-02: Hreflang implementation — 18 pages with en/ar hreflang tags
- 11-03: Image alt text audit — 100% WCAG compliant, 0 missing alt text
- 11-04: FAQ + Features pages — FAQ with 15 items, Features with 87 translation calls, FAQPage + Product schema

**Key fixes made (Rule 1 auto-fixes):**
- Fixed broken route() references across 7 files: `route('docs')` → `route('docs.index')`, `route('dashboard')` → `url('/dashboard')`
- Created lang/en/faq.php, lang/en/features.php, lang/ar/faq.php, lang/ar/features.php
- Merged dev → main, deployed to prod, tagged v1.11.0

---

## Phase 12 Summary (MOSTLY COMPLETE)

**Goal:** Fix API infrastructure so external clients (Saied/OpenClaw) can use the LLM API

**Bugs fixed:**
- Bug A: Created routes/v1.php loaded without /api prefix → /v1/ routes now work (commits committed)
- Bug B: Non-issue — models table empty is correct, API queries Ollama directly
- Bug C: Created cache + sessions tables via migration (commit: 005461a)
- Streaming code fix: Added StreamedResponse to return type union + early delegation in store() (commit: 1c70c86)
- qwen3-vl:235b-instruct-cloud model added to GPU server + app config (credit_multiplier: 3.5, context 128k)
- Fresh performance test account created: perftest@llm.resayil.io (50k credits, enterprise)

**Performance test results recorded (.planning/phases/12-saied-api-fix/perf-results-*.md):**
- Effective context limit: ~105k tokens (fails with 503 above that)
- 4 concurrent users: all pass, true parallelism confirmed (3.23x speedup)
- Local (smollm2:135m): 1.24s avg | Cloud (qwen3-vl:235b): 5.95s avg
- Streaming: broken due to LiteSpeed buffering (fix in progress)

**Still in progress / blockers:**
- LiteSpeed SSE buffering fix — Header: X-Accel-Buffering: no in .htaccess (agent running)
- /v1/ endpoint 404 — Cloudflare may be caching old 404; need cache purge or reconfigure
- Saied's AWS client is sending a stale API key — needs to update OpenClaw config (action on Saied)

---

## Phase 13: Usage Visibility (NEXT)

**Plan files:** `.planning/phases/13-usage-visibility/13-PLAN.md`, `.planning/phases/13-usage-visibility/RESEARCH.md`

**4 plans, 3 waves:**
- Wave 1: Fix credit top-up bug (`credits = x` instead of `credits += x`) + `created_by` migration + ApiKeyAuth status check
- Wave 2a: User usage dashboard (/usage route, CSV export)
- Wave 2b: Admin usage views (by user, by API key)
- Wave 3: Admin API key management + user key badges + human checkpoint

**Key bugs to fix:**
1. Admin credit top-up overwrites instead of adds
2. CreditService::deductCredits() never passes api_key_id/response_time_ms/status_code — usage_logs always get 0/null for these fields

---

## Phase 14: Monitoring (PLANNED)

**Plan file:** `.planning/phases/14-monitoring/PLAN.md`

**3 tasks:**
1. Sentry integration + response_time_ms fix
2. WhatsApp alerts (queue failures + daily summary)
3. UptimeRobot setup for all critical routes

---

## Key Bugs Still Open

1. **Streaming broken:** LiteSpeed buffers SSE — fix: `Header set X-Accel-Buffering "no"` in .htaccess
2. **/v1/ 404:** Cloudflare may cache old 404 — need to purge or reconfigure
3. **Saied's client:** sending stale API key — needs to update OpenClaw (action on Saied, not us)
4. **Cloudflare decision:** split setup recommended (llm.resayil.io proxied for web, API on different subdomain DNS-only) — api.resayil.io is taken/points elsewhere, need different subdomain
5. **CreditService bug:** deductCredits() never passes api_key_id/response_time_ms/status_code — usage_logs always get 0/null for these fields (Phase 13 Wave 1 fix)

---

## Project Reference

**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

**Previous Milestone:** v1.1 (documentation-links) — Complete
**Current Milestone:** v1.2 (seo-optimization) — In Progress
**Next Milestone:** TBD

---

## Accumulated Context

### What Shipped in v1.1
- Landing page with hero CTA, features, pricing
- User dashboard with usage analytics
- Admin dashboard with platform metrics
- WhatsApp notifications (bilingual)
- Enterprise team management
- API documentation links + usage breakdown

### What Shipped in v1.11.0
- FAQ page (15 items, FAQPage JSON-LD schema)
- Features page (8 features, Product JSON-LD schema, fully bilingual EN/AR)
- Hreflang on 18 pages
- 7 expanded /docs sub-pages
- 100% image alt text coverage
- Broken route() references fixed across 7 files

### What's Known About the Codebase
- **Stack:** Laravel + Blade + Tailwind CSS on cPanel
- **Models:** All use UUID PKs with booted() UUID hook
- **Architecture:** API at `/v1/` (not `/api/v1/`), ApiKeyAuth middleware with setUserResolver()
- **Database:** MySQL with resayili_ prefix
- **Cache/Queue:** Database driver for both
- **Design System:** Dark luxury (bg #0f1115, gold #d4af37), Inter + Tajawal fonts
- **Admin Check:** `$user->isAdmin()` checks subscription_tier='admin' or email in hardcoded list
- **Payment:** MyFatoorah integration (KWD currency)
- **Notifications:** Resayil WhatsApp API (bilingual templates)
- **Proxy:** Cloudflare in front of llm.resayil.io (causes SSE buffering, potential 100s timeout, 404 caching)
- **GPU server:** Ollama at 208.110.93.90 — handles local model requests
- **Models:** Local (Ollama) + Cloud (qwen3-vl:235b-instruct-cloud, credit_multiplier: 3.5)

### Deployment Workflow
```
Dev: llmdev.resayil.io (branch: dev)
Prod: llm.resayil.io (branch: main)
Deploy: SSH to whm-server, run deploy.sh
DB Migrations: Always use migrate --force on prod (additive only)
```

### Critical Rules for This Project
- NEVER run migrate:fresh, migrate:reset, db:seed, TRUNCATE, DROP on prod
- NEVER manually edit prod DB (use app registration flow or tinker)
- Always create migrations for schema changes
- Cherry-pick critical fixes to main immediately
- Tag prod releases with semver after every merge to main
- Run risk investigation before every dev→prod merge

### Known Tech Debt / Future Work
- `max_tokens` NOT enforced by OllamaProxy (fix pending)
- High variance in API response times (GPU scheduling issue)
- Cold TCP connections to GPU server (needs keep-alive)
- LiteSpeed SSE buffering blocks streaming responses
- Cloudflare: api.resayil.io taken — need alternate subdomain for DNS-only API access

---

## Decisions Made

1. **2026-03-07** — Phase 12: /v1/ routes loaded without /api prefix to match OpenAI client expectations
2. **2026-03-07** — Phase 12: qwen3-vl:235b credit_multiplier set to 3.5 (3x GPU cost vs local)
3. **2026-03-07** — Phase 12: Cloudflare split setup recommended but deferred (api.resayil.io taken)
4. **2026-03-07** — Phase 13 planned as 4-plan wave structure; Phase 14 planned as separate monitoring phase

---

## Next Up

**-> Phase 12 finish:** LiteSpeed SSE fix + /v1/ 404 investigation
**-> Phase 13: Usage Visibility** — Fix admin credit top-up, fix usage logging, add admin API key view

`/gsd:plan-phase 13`

<sub>`/clear` first -> fresh context window</sub>

---

*State initialized: 2026-03-06 after v1.2 milestone planning*
*Updated: 2026-03-07 — Phase 11 COMPLETE (v1.11.0 prod), Phase 12 mostly complete, Phase 13+14 planned*
