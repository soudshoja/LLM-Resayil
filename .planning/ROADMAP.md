# Roadmap: LLM Resayil Portal

**Defined:** 2026-02-26
**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

## Phases

- [x] **Phase 1: Foundation & Auth** - Project scaffolding, authentication, and session management
- [x] **Phase 2: Billing & Subscriptions** - Subscription tiers, credit top-ups, and MyFatoorah payment integration
- [x] **Phase 3: API Access** - OpenAI-compatible proxy with rate limiting, model access control, and cloud failover
- [x] **Phase 4: Notifications** - WhatsApp notification system for all user and admin events
- [x] **Phase 5: Dashboards** - User dashboard, admin dashboard, landing page, and enterprise team management
- [x] **Phase 6: MySQL Production Setup** - Database migrations, environment configuration, and production deployment
- [x] **Phase 7: Backend Services** - Fix API endpoint (config files, throttle, OllamaProxy), verify end-to-end API flow
- [x] **Phase 8: User Documentation** - Complete markdown API docs + /docs Blade page with syntax highlighting — 100% COMPLETE
- [x] **Phase 9: User Profile & Billing Enhancements** - Full Arabic translations, token-split logging, translation keys, profile management — 100% COMPLETE
- [x] **Phase 10: SEO Foundation** - Schema markup, metadata, comparison pages, cost calculator, internal linking, WCAG AA fixes — 100% COMPLETE & DEPLOYED
- [ ] **Phase 11: Content & Technical SEO** - Expand docs, hreflang, image optimization, FAQ/features pages
- [ ] **Phase 12: E-E-A-T Authority** - Team page, testimonials, case studies, credentials
- [ ] **Phase 13: Performance & Launch** - Core Web Vitals, monitoring, SEO go-live checklist

## Phase Details

### Phase 1: Foundation & Auth

**Goal:** Users can register, log in, and maintain authenticated sessions to access platform features

**Depends on:** Nothing (foundational phase)

**Requirements:** AUTH-01, AUTH-02, AUTH-03

**Success Criteria:**
1. User can register account with email, phone, and password via web form
2. User can log in and remains authenticated across browser sessions
3. User can log out from any page and their session is terminated

**Plans:** 1 plan complete (Plan 01 - Foundation & Auth)

**Completed:**
- Authentication system with Laravel Breeze-style conventions
- API key management (CRUD operations)
- Session persistence with remember_token
- API key authentication middleware

---

### Phase 2: Billing & Subscriptions

**Goal:** Users can select subscription tiers, purchase credit packs, and complete payments via MyFatoorah

**Depends on:** Phase 1

**Requirements:** SUB-01, SUB-02, SUB-03, TOP-01, TOP-02

**Success Criteria:**
1. User can view all three subscription tiers (Basic/Pro/Enterprise) with pricing and features
2. User can select a plan and is redirected to MyFatoorah for KWD payment
3. After payment, subscription is activated and user sees plan status and expiry on dashboard
4. User can view and purchase credit top-up packs (5K/15K/50K credits)
5. Credits are added to account immediately after payment confirmation

**Plans:** 1 plan complete (Plan 01 - Foundation)

**Completed:**
- Topup purchases database table and model
- MyFatoorah API integration service
- Billing business logic service
- Payment controller for subscription and top-up
- Webhook controller for payment notifications
- Billing routes for web and API

---

### Phase 3: API Access

**Goal:** Users can make API calls to /v1/chat/completions with credit-based billing, rate limiting, and cloud failover

**Depends on:** Phase 1, Phase 2 (for credit tracking)

**Requirements:** API-01, API-02, API-03, API-04, API-05, RATE-01, RATE-02, RATE-03, QUEUE-01, QUEUE-02, CLOUD-01, CLOUD-02, MODEL-01, MODEL-02, MODEL-03, MODEL-04

**Success Criteria:**
1. API endpoint accepts Bearer token authentication and proxies requests to Ollama
2. Credits are deducted per request (1x for local, 2x for cloud models)
3. When credits exhausted, service returns 402 with top-up link
4. Streaming responses work for real-time token generation
5. Rate limits enforced per tier (Basic:10/min, Pro:30/min, Enterprise:60/min)
6. Queue depth checked before each request, auto-failover to cloud when local queue > 3
7. Daily cloud budget tracked with 500 request cap
8. Model access control: Basic sees only small/medium models, Pro gets cloud models, Enterprise gets priority queue
9. Restricted models (glm-4.7-flash, bge-m3, nomic-embed-text) never exposed via API

**Plans:** 1/1 plans complete
- Plan 01: OpenAI-compatible API with rate limiting, model access control, and cloud failover

**Completed:**
- OllamaProxy model with local/cloud routing
- RateLimiter service with Redis-based rate limiting
- CloudFailover service with queue depth checking
- ModelAccessControl service for tier-based access
- CreditService for credit-based billing
- ChatCompletionsController for OpenAI-compatible API
- ModelsController for model listing
- RateLimit middleware

---

### Phase 4: Notifications

**Goal:** WhatsApp notifications sent for all key user and admin events in Arabic/English bilingual format

**Depends on:** Phase 1 (user exists), Phase 2 (billing events), Phase 3 (API usage tracking)

**Requirements:** NOTIF-01, NOTIF-02, NOTIF-03, NOTIF-04, NOTIF-05, NOTIF-06, NOTIF-07, NOTIF-08, NOTIF-09, NOTIF-10

**Success Criteria:**
1. Welcome WhatsApp message sent in both Arabic and English upon registration
2. Subscription confirmation with plan details sent after successful payment
3. Credit warning notification sent when credits reach 20% remaining
4. Credits exhausted notification includes top-up link in both languages
5. Top-up purchase confirmation sent immediately after payment
6. Renewal reminder sent 3 days before subscription expiry
7. Cloud budget at 80% triggers admin alert
8. Cloud budget at 100% triggers admin alert and disables cloud
9. IP banned by fail2ban triggers admin alert
10. New Enterprise customer registration triggers admin alert

**Plans:** 1 plan complete
- Plan 01: WhatsApp notification system (NOTIF-01 through NOTIF-10)

**Completed:**
- WhatsApp API integration service
- Notification templates for all events
- Notification controller and routes
- Cron job setup for scheduled notifications

---

### Phase 5: Dashboards

**Goal:** User dashboard shows usage data, API keys, and top-up options; admin dashboard shows platform metrics; Enterprise team management functionality

**Depends on:** Phase 1, Phase 2, Phase 3 (for usage data), Phase 4 (for notifications)

**Requirements:** DASH-01, DASH-02, DASH-03, DASH-04, DASH-05, ADMIN-01, ADMIN-02, ADMIN-03, ADMIN-04, ADMIN-05, LP-01, LP-02, LP-03, LP-04, LP-05, LP-06, TEAM-01, TEAM-02, TEAM-03, TEAM-04

**Success Criteria:**
1. User dashboard displays credits remaining and total in clear display
2. Usage chart shows requests over time
3. API keys list shows all keys with quick delete actions
4. Quick top-up button visible on user dashboard
5. Usage table shows model breakdown per request
6. Admin dashboard shows platform overview (users, revenue, cloud budget)
7. Admin can manually adjust user credits from admin panel
8. Admin sees revenue breakdown by subscription tier
9. Admin dashboard displays cloud budget status with visual indicators
10. Landing page has hero section, how it works, pricing, model list, code examples
11. Landing page sign-up CTA redirects to registration
12. Enterprise team members can be added with admin/member roles
13. Team dashboard accessible to Enterprise users

**Plans:** 3/3 plans complete
- Plan 01: Landing page and user dashboard (LP-01 through LP-06, DASH-01 through DASH-05)
- Plan 02: Admin dashboard (ADMIN-01 through ADMIN-05)
- Plan 03: Enterprise team management (TEAM-01 through TEAM-04)

**Completed:**
- Landing page with hero, how-it-works, pricing, models, code sections
- User dashboard with credits, usage charts, API keys, top-up
- Admin dashboard with overview, users, revenue breakdown, cloud budget
- Chart.js configuration for usage visualization
- Team members database table with UUID primary keys
- TeamMember Eloquent model with admin/member role scopes
- TeamMemberController with full CRUD operations
- Enterprise middleware for tier-based access control
- Team management dashboard views

---

### Phase 6: MySQL Production Setup

**Goal:** Run all database migrations, seed initial data, and configure the production environment so the Laravel app is fully operational on the production MySQL server (resayili_llm_resayil on cPanel/WHM at llm.resayil.io)

**Depends on:** Phase 5

**Requirements:** None (operational phase for deployment)

**Success Criteria:**
1. Queue jobs table migration generated locally with php artisan queue:table
2. Production .env file configured with all required credentials (DB, Redis, MyFatoorah, WhatsApp, Cloud Ollama)
3. APP_KEY generated on production with php artisan key:generate
4. All 10 database migrations executed on production (9 existing + jobs table)
5. All seeders executed (SubscriptionPlanSeeder, NotificationTemplateSeeder, UserSeeder)
6. Production database contains all required seed data (3 tiers, 20+ templates, 1 admin user)
7. Application loads at https://llm.resayil.io without errors
8. Admin can log in with seeded credentials

**Plans:** 1 plan (Plan 01 - MySQL Production Setup & Deployment)

Plan 01 covers:
- Generate queue jobs table migration locally
- Configure production .env with all credentials
- Run all 10 migrations on production
- Execute all seeders with initial data
- Verify production application is operational

### Phase 8: User Documentation

**Goal:** End-user API docs (markdown + in-portal /docs page) for API consumers

**Depends on:** Phase 7

**Requirements:** DOC-01, DOC-02, DOC-03

**Success Criteria:**
1. Eight markdown files under `docs/` with API reference content
2. Public `/docs` page with sidebar navigation and syntax highlighting
3. Code examples in 4 languages (cURL, Python, JavaScript, n8n)
4. All pages accessible without authentication

**Status:** 10% complete (skeleton exists, full API docs pending)

**Plans:** 0/1 plans executed
- [ ] 08-01: Create markdown docs and /docs Blade page — PLAN.md written (600+ lines detailed spec), awaiting execution

**Pending Details:**
- 8 markdown files: getting-started, authentication, api-reference, models, billing-credits, rate-limits, error-codes, code-examples
- Blade /docs page with sidebar nav, highlight.js syntax highlighting, copy buttons, tab-based code example switcher
- Full self-contained HTML (own nav, not extending layouts.app)

---

### Phase 9: User Profile & Billing Enhancements

**Goal:** (09-01) Full Arabic translations for all landing pages. (09-02) Token-split logging and cost comparison dashboard. (09-03) User profile management (password/email/phone with OTP). (09-04) Language switcher UX. (09-05) Translation key backfill.

**Depends on:** Phase 1 (auth), Phase 3 (API usage), Phase 4 (WhatsApp OTP infrastructure)

**Status:** 40% complete (2/5 sub-plans executed, 3 pending)

**Completed Plans:**
- [x] **09-02: Savings Dashboard with token-split logging** (deployed 2026-03-05)
  - Real token count split from Ollama responses (prompt_tokens + completion_tokens)
  - CostService with multi-model price comparison (GPT-4o, Claude 3, Gemini 2.0)
  - Migration: `prompt_tokens` + `completion_tokens` nullable columns on usage_logs
  - Dashboard savings widget showing monthly savings % vs 5 public LLM APIs
  - Status: ✅ Complete and live in production

- [x] **09-01: Full Arabic translations** (deployed 2026-03-07)
  - 100% translation coverage for welcome.blade.php
  - Dynamic language switching via /locale/en and /locale/ar routes
  - All sections bilingual: navigation, hero, features, pricing, testimonials, footer, buttons
  - RTL/LTR support with dynamic `dir` attribute
  - Default: Arabic (app()->getLocale() === 'ar')
  - Status: ✅ Complete and live in production

**Pending Plans:**
- [ ] **09-03: User profile management** (25 KB PLAN.md written) — password change, email update, phone OTP verification, profile view redesign
- [ ] **09-04: Language switcher enhancements** (2.9 KB PLAN.md written) — UX improvements, mobile menu integration
- [ ] **09-05: Translation key backfill** (12.8 KB PLAN.md written) — dashboard, billing, admin pages translation coverage

---

### Phase 10: SEO Foundation (v1.2 Milestone)

**Goal:** Implement schema markup, metadata, comparison pages, and cost calculator to establish competitive SEO advantage

**Depends on:** Phase 5 (dashboards/landing page exist)

**Status:** 0% (Specifications complete, design fixes in progress — requires WCAG AA compliance on 4 pages)

**Deliverables (from Phase 10 v2 Completion Report, 2026-03-06):**

**Complete Design Specifications:**
- [x] Schema Markup (240+ lines JSON-LD) — Organization + SoftwareApplication + FAQPage (3 types)
- [x] Metadata (100% coverage) — 20+ pages with keyword-optimized meta descriptions
- [x] Comparison Pages (11,400+ words) — 3 high-design pages:
  - `/comparison` — LLM Resayil vs OpenRouter (3,800 words, 6 sections, 7 FAQ entries)
  - `/alternatives` — Top 5 OpenAI alternatives (3,800 words, 7 sections, 9 FAQ entries)
  - `/dedicated-server` — Enterprise hosting + API hybrid (2,800 words, 8 sections, 9 FAQ entries)
- [x] Cost Calculator — Interactive real-time widget (unique tool, no competitors)
- [x] Internal Linking (55+ links) — 3 content clusters (Cost/ROI, API/Integration, Education)
- [x] OG Images (20+ SVG files) — 1200×630px, dark luxury design
- [x] Robots.txt — Strategic AI crawler rules (GPTBot, ClaudeBot, PerplexityBot)

**Design Fixes Required (from Quick Task 14 — 4 teams, in progress):**
- [ ] Team A: /cost-calculator — WCAG AA compliance fixes (currently 8/10 score)
- [ ] Team B: /comparison — HTML validation, focus states (currently 7.8/10 score)
- [ ] Team C: /alternatives — Keyboard navigation, mobile typography (currently 7.8/10 score)
- [ ] Team D: /dedicated-server — Accessibility polish (currently 8.5/10 score)

**Expected SEO Impact (from completion report):**
- +20-30% SERP visibility (metadata optimization)
- +10-15% CTR improvement (keyword-rich titles)
- Top 3 ranking: "openai alternative" (8 weeks)
- Top 1 ranking: "llm api cost calculator" (4 weeks — zero competition)
- 40-50% organic traffic growth (Month 2-3)

**Code Delivery Stats:**
- 4,000+ lines of new code
- 1,500+ lines of modified code
- 489+ CSS classes
- 14,200+ words of new content
- 8 files created/modified

---

### Phase 11: Content & Technical SEO

**Goal:** Expand /docs, implement hreflang, optimize images, add breadcrumb schema

**Depends on:** Phase 10 completion

**Status:** In Progress (Plans 02/03/04 complete, 3/4)

**Plans:**
- [ ] 11-01: Expand /docs content (getting-started, authentication, models)
- [x] 11-02: Implement hreflang and language switching infrastructure — COMPLETE (18 pages covered)
- [x] 11-03: Image alt text optimization — COMPLETE (audit: 0 missing alt text)
- [x] 11-04: FAQ and features pages with schema markup — COMPLETE (15 FAQ items, 8 features, FAQPage + Product schema)

**Requirements:**
- Expand /docs from 737 → 2,500+ words with code examples
- Implement hreflang for EN/AR versions on all pages
- Add semantic alt text to 50+ images (COMPLETE - existing SVG/img already accessible)
- Create /faq and /features pages with schema
- Add breadcrumb schema to /docs subsections

---

### Phase 12: E-E-A-T Authority Building

**Goal:** Build entity authority through team page, testimonials, case studies, competitive comparisons

**Depends on:** Phase 11 completion

**Status:** Planned (TBD)

---

### Phase 13: Performance & Launch

**Goal:** Core Web Vitals optimization, monitoring setup, go-live readiness

**Depends on:** Phases 10-12 completion

**Status:** Planned — Final phase in v1.2 SEO optimization milestone

---

**Goal:** Users can update their password, email address, and verified mobile number from the profile page. Mobile number changes require WhatsApp OTP confirmation before the new number is saved.

**Depends on:** Phase 1 (auth), Phase 4 (WhatsApp OTP infrastructure)

**Requirements:**
- Password change: current password required, new password min 8 chars with confirmation
- Email change: unique validation, save directly (no email OTP needed)
- Mobile change: two-step AJAX flow — send OTP to new number via WhatsApp, verify 6-digit code, then save
- UI: dark luxury design system (cards, gold accents, Inter+Tajawal fonts), no inline styles
- After phone OTP verification, "Verified" badge updates in-page without reload
- Arabic translation support (`__()` wrapping) for all strings
- Fix: `phone_verified_at` cast as datetime in User model
- Fix: `email_verified_at` reset to null when email changes

**Plans:** 1 plan
- [ ] 09-01-PLAN.md — Backend fixes (model cast, email_verified_at reset, lang keys) + profile view redesign (CSS classes, __() wrapping, JS badge fix)

---

## Progress Summary

| Phase | Plans Complete | Status | Last Update |
|-------|----------------|--------|-----------|
| 1 - Foundation & Auth | 1/1 | ✅ 100% Complete | 2026-02-26 |
| 2 - Billing & Subscriptions | 1/1 | ✅ 100% Complete | 2026-02-26 |
| 3 - API Access | 1/1 | ✅ 100% Complete | 2026-02-26 |
| 4 - Notifications | 1/1 | ✅ 100% Complete | 2026-02-26 |
| 5 - Dashboards | 3/3 | ✅ 100% Complete | 2026-02-26 |
| 6 - MySQL Production Setup | 1/1 | ✅ 100% Complete | 2026-03-02 |
| 7 - Backend Services | 7/7 | ✅ 100% Complete | 2026-03-03 |
| 8 - User Documentation | 1/1 | ✅ 100% Complete | 2026-03-07 |
| 9 - User Profile & Billing | 5/5 | ✅ 100% Complete | 2026-03-07 |
| 10 - SEO Foundation | 1/1 | ✅ 100% Complete & Deployed | 2026-03-08 |
| 11 - Content & Technical SEO | 1/4 | 🔄 In Progress | 2026-03-07 |
| 12 - E-E-A-T Authority | 0/1 | 📋 Planned | TBD |
| 13 - Performance & Launch | 0/1 | 📋 Planned | TBD |

---

*Roadmap defined: 2026-02-26*
*Last updated: 2026-03-07 - Phase status corrected based on actual completion (Phase 8: 10%, Phase 9: 40%, Phase 10: 0% with specs complete)*
