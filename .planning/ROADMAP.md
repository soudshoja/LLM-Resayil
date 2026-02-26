# Roadmap: LLM Resayil Portal

**Defined:** 2026-02-26
**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

## Phases

- [ ] **Phase 1: Foundation & Auth** - Project scaffolding, authentication, and session management
- [ ] **Phase 2: Billing & Subscriptions** - Subscription tiers, credit top-ups, and MyFatoorah payment integration
- [ ] **Phase 3: API Access** - OpenAI-compatible proxy with rate limiting, model access control, and cloud failover
- [ ] **Phase 4: Notifications** - WhatsApp notification system for all user and admin events
- [ ] **Phase 5: Dashboards** - User dashboard, admin dashboard, and landing page

## Phase Details

### Phase 1: Foundation & Auth

**Goal:** Users can register, log in, and maintain authenticated sessions to access platform features

**Depends on:** Nothing (foundational phase)

**Requirements:** AUTH-01, AUTH-02, AUTH-03

**Success Criteria:**
1. User can register account with email, phone, and password via web form
2. User can log in and remains authenticated across browser sessions
3. User can log out from any page and their session is terminated

**Plans:** TBD

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

**Plans:** TBD

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

**Plans:** TBD

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

**Plans:** TBD

---

### Phase 5: Dashboards

**Goal:** User dashboard shows usage data, API keys, and top-up options; admin dashboard shows platform metrics

**Depends on:** Phase 1, Phase 2, Phase 3 (for usage data), Phase 4 (for notifications)

**Requirements:** DASH-01, DASH-02, DASH-03, DASH-04, DASH-05, ADMIN-01, ADMIN-02, ADMIN-03, ADMIN-04, ADMIN-05, LP-01, LP-02, LP-03, LP-04, LP-05, LP-06

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

**Plans:** TBD

---

## Progress

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1 - Foundation & Auth | 1/3 | In progress | Plan 01 completed |
| 2 - Billing & Subscriptions | 0/3 | Not started | - |
| 3 - API Access | 0/5 | Not started | - |
| 4 - Notifications | 0/4 | Not started | - |
| 5 - Dashboards | 0/5 | Not started | - |

---

*Roadmap defined: 2026-02-26*
