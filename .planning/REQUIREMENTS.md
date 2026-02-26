# Requirements: LLM Resayil Portal

**Defined:** 2026-02-26
**Core Value:** Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

## v1 Requirements

### Authentication

- [ ] **AUTH-01**: User can register with email, phone, and password
- [ ] **AUTH-02**: User can log in and maintain authenticated session
- [ ] **AUTH-03**: User can log out from any page

### API Keys

- [ ] **KEY-01**: User can generate new API keys with custom labels
- [ ] **KEY-02**: User can view API key prefix (first 12 chars)
- [ ] **KEY-03**: User can delete their own API keys
- [ ] **KEY-04**: Full API key shown exactly once on creation

### Billing & Subscriptions

- [x] **SUB-01**: User can view available subscription tiers (Basic/Pro/Enterprise)
- [x] **SUB-02**: User can select a plan and initiate MyFatoorah payment
- [x] **SUB-03**: User sees current plan status and expiry date on dashboard
- [x] **TOP-01**: User can view and purchase credit top-up packs (5K/15K/50K)
- [x] **TOP-02**: Top-up credits added to account immediately after payment

### API Access

- [ ] **API-01**: POST /v1/chat/completions accepts Bearer token and proxies to Ollama
- [ ] **API-02**: Credits deducted per request (1x local, 2x cloud)
- [ ] **API-03**: Service returns 402 with message when credits exhausted
- [ ] **API-04**: Streaming response support for real-time token generation
- [ ] **API-05**: GET /v1/models returns list of accessible models per tier

### Rate Limiting

- [ ] **RATE-01**: Basic tier: 10 requests per minute
- [ ] **RATE-02**: Pro tier: 30 requests per minute
- [ ] **RATE-03**: Enterprise tier: 60 requests per minute

### Cloud Failover

- [ ] **QUEUE-01**: Queue depth checked before each request
- [ ] **QUEUE-02**: Auto-failover to :cloud model when local queue > 3
- [ ] **CLOUD-01**: Daily cloud budget tracking with 500 request cap
- [ ] **CLOUD-02**: Cloud disabled at 500 requests/day

### WhatsApp Notifications (Bilingual Arabic/English)

- [ ] **NOTIF-01**: Welcome message on registration
- [ ] **NOTIF-02**: Subscription activated confirmation
- [ ] **NOTIF-03**: Credit warning at 20% remaining
- [ ] **NOTIF-04**: Credits exhausted with top-up link
- [ ] **NOTIF-05**: Top-up purchase confirmation
- [ ] **NOTIF-06**: Renewal reminder 3 days before expiry
- [ ] **NOTIF-07**: Cloud budget at 80% admin alert
- [ ] **NOTIF-08**: Cloud budget at 100% (cloud disabled) admin alert
- [ ] **NOTIF-09**: IP banned by fail2ban admin alert
- [ ] **NOTIF-10**: New Enterprise customer registered admin alert

### User Dashboard

- [ ] **DASH-01**: Credits remaining and total displayed
- [ ] **DASH-02**: Usage chart (requests over time)
- [ ] **DASH-03**: API keys list with quick actions
- [ ] **DASH-04**: Quick top-up button visible
- [ ] **DASH-05**: Usage table with model breakdown

### Admin Dashboard

- [ ] **ADMIN-01**: Platform overview (total users, revenue, usage)
- [ ] **ADMIN-02**: User management table
- [ ] **ADMIN-03**: Admin can manually adjust user credits
- [ ] **ADMIN-04**: Revenue by tier dashboard
- [ ] **ADMIN-05**: Cloud budget status display

### Model Access Control

- [ ] **MODEL-01**: Basic tier: llama3.2:3b, smollm2:135m, qwen2.5-coder:14b, mistral-small3.2:24b
- [ ] **MODEL-02**: Pro tier: all Basic models + qwen3-30b-40k, Qwen3-VL-32B, qwen3.5:cloud, deepseek-v3.2:cloud, gpt-oss:20b
- [ ] **MODEL-03**: Enterprise tier: all Pro models + priority queue
- [ ] **MODEL-04**: Never expose glm-4.7-flash, bge-m3, nomic-embed-text

### Team Management (Enterprise Only)

- [ ] **TEAM-01**: Team members can be added by owner
- [ ] **TEAM-02**: Roles: admin vs member
- [ ] **TEAM-03**: Members can be removed by admin
- [ ] **TEAM-04**: Team dashboard accessible to Enterprise users

### Landing Page

- [ ] **LP-01**: Hero section with value proposition
- [ ] **LP-02**: How it works (3-4 step explanation)
- [ ] **LP-03**: Pricing section with 3 tiers
- [ ] **LP-04**: Model list section
- [ ] **LP-05**: API code example section
- [ ] **LP-06**: Sign-up CTA with redirect to registration

## v2 Requirements

### Authentication

- [ ] **AUTH-10**: Email verification flow
- [ ] **AUTH-11**: Password reset via email link
- [ ] **AUTH-12**: OAuth login (Google, GitHub)

### Billing

- [ ] **SUB-20**: Invoice generation and download
- [ ] **SUB-21**: Payment history export (CSV/PDF)
- [ ] **SUB-22**: Automatic renewal with credit card

### Notifications

- [ ] **NOTIF-20**: Email notifications for account events
- [ ] **NOTIF-21**: In-app notification center

### Analytics

- [ ] **ANALYTICS-01**: Per-API-key usage breakdown
- [ ] **ANALYTICS-02**: Token usage by day/week/month
- [ ] **ANALYTICS-03**: Cost projections

### Admin Features

- [ ] **ADMIN-10**: Audit log for all admin actions
- [ ] **ADMIN-11**: User ban/suspend functionality
- [ ] **ADMIN-12**: System health monitoring

## Out of Scope

| Feature | Reason |
|---------|--------|
| Email verification flow | Phone-based auth sufficient for Gulf market |
| OAuth login (Google/GitHub) | Email/password + phone is standard for target users |
| Self-hosted deployment | SaaS only, hosted on cPanel |
| Mobile apps | Web-first portal, responsive design |
| Real-time features beyond WhatsApp | Cron-based scheduling acceptable |
| Multi-language beyond Arabic/English | No additional languages planned |
| Real-time chat | High complexity, not core to API value |

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| AUTH-01 | Phase 1 | Pending |
| AUTH-02 | Phase 1 | Pending |
| AUTH-03 | Phase 1 | Pending |
| KEY-01 | Phase 1 | Pending |
| KEY-02 | Phase 1 | Pending |
| KEY-03 | Phase 1 | Pending |
| KEY-04 | Phase 1 | Pending |
| SUB-01 | Phase 2 | Complete |
| SUB-02 | Phase 2 | Complete |
| SUB-03 | Phase 2 | Complete |
| TOP-01 | Phase 2 | Complete |
| TOP-02 | Phase 2 | Complete |
| API-01 | Phase 3 | Pending |
| API-02 | Phase 3 | Pending |
| API-03 | Phase 3 | Pending |
| API-04 | Phase 3 | Pending |
| API-05 | Phase 3 | Pending |
| RATE-01 | Phase 3 | Pending |
| RATE-02 | Phase 3 | Pending |
| RATE-03 | Phase 3 | Pending |
| QUEUE-01 | Phase 3 | Pending |
| QUEUE-02 | Phase 3 | Pending |
| CLOUD-01 | Phase 3 | Pending |
| CLOUD-02 | Phase 3 | Pending |
| MODEL-01 | Phase 3 | Pending |
| MODEL-02 | Phase 3 | Pending |
| MODEL-03 | Phase 3 | Pending |
| MODEL-04 | Phase 3 | Pending |
| NOTIF-01 | Phase 4 | Pending |
| NOTIF-02 | Phase 4 | Pending |
| NOTIF-03 | Phase 4 | Pending |
| NOTIF-04 | Phase 4 | Pending |
| NOTIF-05 | Phase 4 | Pending |
| NOTIF-06 | Phase 4 | Pending |
| NOTIF-07 | Phase 4 | Pending |
| NOTIF-08 | Phase 4 | Pending |
| NOTIF-09 | Phase 4 | Pending |
| NOTIF-10 | Phase 4 | Pending |
| DASH-01 | Phase 5 | Pending |
| DASH-02 | Phase 5 | Pending |
| DASH-03 | Phase 5 | Pending |
| DASH-04 | Phase 5 | Pending |
| DASH-05 | Phase 5 | Pending |
| ADMIN-01 | Phase 5 | Pending |
| ADMIN-02 | Phase 5 | Pending |
| ADMIN-03 | Phase 5 | Pending |
| ADMIN-04 | Phase 5 | Pending |
| ADMIN-05 | Phase 5 | Pending |
| TEAM-01 | Phase 5 | Pending |
| TEAM-02 | Phase 5 | Pending |
| TEAM-03 | Phase 5 | Pending |
| TEAM-04 | Phase 5 | Pending |
| LP-01 | Phase 5 | Pending |
| LP-02 | Phase 5 | Pending |
| LP-03 | Phase 5 | Pending |
| LP-04 | Phase 5 | Pending |
| LP-05 | Phase 5 | Pending |
| LP-06 | Phase 5 | Pending |

**Coverage:**
- v1 requirements: 64 total
- Mapped to phases: 64
- Unmapped: 0 ✓
- Phases: 5

---

*Requirements defined: 2026-02-26*
*Last updated: 2026-02-26 after roadmap creation*
