---
gsd_state_version: 1.0
milestone: v1.1
milestone_name: documentation-links
status: complete
last_updated: "2026-03-03T13:30:00Z"
progress:
  total_phases: 7
  completed_phases: 7
  total_plans: 15
  completed_plans: 15
  percent: 100
---

## v1.1 Enhancements (Under Discussion)

### Enhancement: Landing Page UI Improvements (COMPLETED)

**Status:** Completed - 2026-03-03

**Changes made to `welcome.blade.php`:**
- Updated hero CTA: "Get Started" instead of "Start Free Trial"
- Added "Documentation" button to hero CTA
- Added footer links: "How Credits Work", "API Docs", "Pricing Details"
- All links use correct color palette (gold #d4af37)

---

### Enhancement: Usage Detail per API Call

**Status:** Proposed - needs discussion

**Goal:** Show users detailed breakdown of their API call costs including:
- Tokens used (input + output)
- Cost multiplier (1x for local, 2x for cloud)
- Credits deducted per call
- Estimated vs actual cost comparison

**Proposed implementation:**

#### 1. Dashboard Recent Usage Table Enhancement
Currently shows: Model, Tokens, Credits Used, Time

Enhanced to show:
| Model | Input Tokens | Output Tokens | Total | Multiplier | Credits | Time |
|-------|-------------|---------------|-------|------------|---------|------|
| llama3.2:3b | 45 | 120 | 165 | 1x | 1 | 2h ago |

#### 2. Add Cost Breakdown on Model Detail
When clicking a model in catalog, show:
- **Cost estimate:** ~0.25 credits per 1000 tokens (local)
- **Actual costs logged per call** in usage history

#### 3. Daily/Weekly Usage Summary
Add summary cards showing:
- Total tokens this week
- Total credits this week
- Average cost per request
- Most expensive models used

#### 4. Cost Calculator Tool
Pre-call estimate: user inputs message length, gets estimated cost before making API call.

---

## Phase 8 — Pending

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
**Progress:** All 7 phases + all 15 plans complete (v1.1 milestone achieved)
**Active Requirements:** None

**Plan Completion Status:**
| Phase | Plan 01 | Plan 02 | Plan 03 | Plan 04 | Plan 05 | Plan 06 | Plan 07 |
|-------|---------|---------|---------|---------|---------|---------|---------|
| 07-backend-services | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## Phase 7 — Complete Log

### Plan 07: UI Fixes + Billing Enhancements + Admin Unlimited Access ✅ COMPLETE

**Completed:** 2026-03-03

**Summary:**
- Agent 1: Homepage pricing redesign — matches `/billing/plans` card style with free trial box
- Agent 2: Dashboard layout fix — API Keys + Top Up Credits side-by-side; admin key creation modal
- Agent 3: 4 bug fixes — enterprise tier pricing, admin unlimited keys, clean model names, smollm2 size

**Key Achievements:**
- Enterprise tier with appropriate API key limits (2-3 free, 4+ paid)
- Admin can create unlimited API keys without payment flow
- Usage logs show clean model names (`:cloud` and `-cloud` suffixes removed)
- SmollLM2:135m correctly classified as 'small' for pricing

**Commits:** 0188355, b29db5c, 3c25cbd, 207b82e, 4488cb4

---

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

## Enhancement: Usage Detail per API Call (Under Discussion)

**Status:** Proposed - needs discussion
**Target:** v1.1 enhancement

**Goal:** Show users detailed breakdown of their API call costs including:
- Input tokens vs output tokens
- Cost multiplier (1x for local, 2x for cloud)
- Credits deducted per call

**Proposed dashboard enhancement:**

| Model | Input Tokens | Output Tokens | Total | Multiplier | Credits | Time |
|-------|-------------|---------------|-------|------------|---------|------|
| llama3.2:3b | 45 | 120 | 165 | 1x | 1 | 2h ago |
| qwen3.5:cloud | 100 | 300 | 400 | 2x | 1 | 5h ago |

**Also consider:**
- Daily/Weekly usage summary cards
- Cost calculator tool (pre-call estimate)
- Export usage history to CSV

---

## v1.2 Enhancements

### Enhancement: Landing Page Hero Slider

**Status:** Proposed - needs discussion
**Target:** v1.2 enhancement

**Goal:** Add an attractive slider/carousel to the landing page to showcase:
- Featured models with visuals
- Platform features
- Customer testimonials
- Live stats (users, API calls, models)

**Recommended slider implementation:**

#### 1. Hero Slider (Autoplay, Auto-Pause on Hover)
- 3-5 slides with gradient backgrounds
- Model cards or feature highlights
- Smooth fade/slide transitions
- Pagination dots + prev/next arrows
- Mobile-responsive touch gestures

#### 2. Content Ideas for Slides
| Slide | Content |
|-------|---------|
| 1 | Llama 3.2 3B - "Fastest for everyday tasks" |
| 2 | DeepSeek V3.1 671B - "Frontier reasoning" |
| 3 | Qwen 3.5 397B - "Largest MoE available" |
| 4 | 45+ models - "One API for all" |
| 5 | 100ms avg latency - "Blazing fast responses" |

#### 3. UI Components Needed
- CSS slide container with `transform: translateX()`
- JavaScript for auto-play + manual navigation
- Touch swipe support for mobile
- Smooth fade transitions (CSS opacity + transform)

#### 4. Design Considerations
- Match existing dark luxury theme
- Gold accent colors (#d4af37)
- Inter/Tajawal fonts
- 16px+ readable font size
- Proper contrast ratios (4.5:1 minimum)

**Anti-patterns to avoid:**
- Don't auto-play audio
- Don't use fast flickering animations
- Don't hide important info behind slider
- Include accessibility labels for screen readers

---

## v1.3 Enhancements

### Enhancement: Contact Us Page

**Status:** Completed - 2026-03-03
**Target:** v1.3 enhancement

**Goal:** Add a Contact Us page for users to send emails directly to soud@alphia.net

**Implementation:**

#### Files Created/Modified:
- `app/Http/Controllers/ContactController.php` - Contact form controller
- `resources/views/contact.blade.php` - Contact page UI with dark luxury theme
- `resources/views/emails/contact.blade.php` - Email template
- `routes/web.php` - Added `/contact` routes
- `resources/views/welcome.blade.php` - Added contact section at bottom

#### Contact Form Fields:
- Full Name (required)
- Email Address (required) - User's email for reply
- Mobile Number (required) - For quick contact
- Message (required, 10-5000 chars)

#### Email Delivery:
- Email sent to: `soud@alphia.net`
- Subject: "New Contact Form Submission - LLM Resayil"
- User receives success redirect with confirmation

#### UI Design:
- Two-column layout: Contact info (left) + Form (right)
- Gold accent icons for Email, Phone, Message
- Dark luxury theme matching existing design system
- Responsive grid for mobile
- Form validation with error messages
- Success confirmation after submission

---

## v1.4 Enhancements

### Enhancement: Arabic Language Support

**Status:** Proposed - needs discussion
**Target:** v1.4 enhancement

**Goal:** Add full Arabic language support to the website with RTL (Right-to-Left) layout

**Implementation Approach:**

#### 1. Laravel Localization Setup
- Create `resources/lang/ar/` directory with translation files
- Create `resources/lang/en/` directory (default language)
- Update `config/app.php` to support `ar` locale
- Add locale switcher in navbar

#### 2. Translation Files to Create

**en/**
- `auth.php` - Login/register text
- `pagination.php` - Pagination links
- `passwords.php` - Password reset text
- `validation.php` - Form validation messages
- `welcome.php` - Landing page content
- `dashboard.php` - Dashboard text
- `billing.php` - Billing/plans text
- `contact.php` - Contact page text

**ar/**
- All same files with Arabic translations
- RTL-compatible content

#### 3. UI/UX Design Considerations

**RTL Layout Adjustments:**
- Flip nav links order for Arabic
- Reverse card layouts (pricing, models)
- Adjust icon positioning
- Mirror form inputs

**Typography:**
- Tajawal as primary font (already in use)
- Inter for English text
- Arabic-specific font weights (400, 500, 700, 900)

**Direction:**
- `dir="rtl"` on `<html>` when Arabic selected
- CSS uses logical properties where possible
- Arabic text flows right to left

#### 4. Locale Detection
- URL prefix: `/ar/` and `/en/` routes
- Session storage for user preference
- Browser language detection (fallback)
- Default to Arabic for Kuwait region

#### 5. Files to Modify
- `routes/web.php` - Group routes by locale
- `resources/views/layouts/app.blade.php` - Locale switcher + RTL support
- `resources/views/welcome.blade.php` - Translations
- `resources/views/dashboard.blade.php` - Translations
- `resources/views/billing/plans.blade.php` - Translations
- `resources/views/contact.blade.php` - Translations

#### 6. Design Considerations (UI Pro)
- Maintain dark luxury theme in both languages
- Gold accents (#d4af37) work in both LTR/RTL
- Inter (Latin) + Tajawal (Arabic) font pairing is ideal
- Ensure 4.5:1 contrast ratio for Arabic text
- Touch targets minimum 44x44px for RTL interactions

**Anti-patterns to avoid:**
- Don't just reverse text without checking grammar
- Don't use inline styles that assume LTR direction
- Don't forget to flip icons (like search, arrows)
- Test with real Arabic content (not just placeholder)

#### 7. Content Translation Notes
- Model names remain in English (Llama, Qwen, DeepSeek)
- Technical terms (API, token, credit) may stay in English
- All UI buttons, labels, and descriptions translated
- Documentation pages (`/docs`, `/credits`) also need Arabic

---

*State file last updated: 2026-03-05 — Full-cycle debug session: 4 agents, 2 code fixes on dev, 1 prod .env fix*

### Debug Session 2026-03-05 — Issues Found

See full report: `.planning/phases/09-enhancements/09-DEBUG-SESSION-2026-03-05.md`

| ID | Severity | Status | Issue |
|----|----------|--------|-------|
| BUG-01 | 🔴 CRITICAL | Open | Chat completions API returns Ollama-native format, not OpenAI — breaks all SDK clients |
| BUG-02 | 🔴 CRITICAL | Open | Billing/plans page shows raw `billing.xxx` translation keys — page unusable |
| BUG-03 | 🟡 MEDIUM | Open | Double UsageLog per non-streaming API call (proxy + controller both create log) |
| BUG-04 | 🟡 MEDIUM | Open | Login error shows "Validation failed." — should be "These credentials do not match" |
| BUG-05 | 🟢 LOW | Open | KNET text still in dashboard Top Up Credits widget |
| BUG-06 | 🟢 LOW | Open | Native alert() dialog on model catalog (should be inline toast) |
| BUG-07 | 🟢 LOW | Open | Admin nav overflow when logged in |
| BUG-08 | 🟢 LOW | Open | No schedule:run cron on either server |
| BUG-01-fix | ✅ FIXED | Dev | Our Cost column: tokens*multiplier calc (commit 19c5847) |
| BUG-02-fix | ✅ FIXED | Dev | Dashboard :name placeholder interpolation (commit be360dc) |
| BUG-03-fix | ✅ FIXED | Prod | MAIL_MAILER=log → sendmail on prod (.env only) |

### Quick Tasks Completed

| # | Description | Date | Commit | Directory |
|---|-------------|------|--------|-----------|
| 1 | Fix API models endpoint: hide type field and filter embedding models | 2026-03-04 | 517f0c2 | [1-fix-api-models-endpoint-hide-type-field-](./quick/1-fix-api-models-endpoint-hide-type-field-/) |
| 3 | Admin email notification when new user registers (soud@alphia.net) | 2026-03-05 | b6d626e | [3-add-admin-email-notification-when-a-new-](./quick/3-add-admin-email-notification-when-a-new-/) |
| 4 | Remove Kuwait/KWD/KNET references from welcome page marketing copy (EN + AR) | 2026-03-05 | b2bdca6 | [4-remove-kuwait-kwd-knet-references-from-w](./quick/4-remove-kuwait-kwd-knet-references-from-w/) |
| 5 | Fix @json Blade compiler bug in dashboard (pre-compute $dashLang in @php) | 2026-03-05 | f17fa94 | dev only |
| 6 | Add "Our Cost ($)" column to Recent API Usage table (alongside vs GPT-4o) | 2026-03-05 | 429fe06 | dev only |
