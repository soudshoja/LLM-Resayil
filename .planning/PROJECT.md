# LLM Resayil Portal — llm.resayil.io

## What This Is

A Laravel-based SaaS platform that provides OpenAI-compatible API access to Ollama models hosted on a GPU server. Users get API keys with credit-based billing (KWD currency), subscription tiers (Basic/Pro/Enterprise), and MyFatoorah payment integration. The platform acts as a proxy between users and Ollama, handling authentication, credit deduction, rate limiting, and cloud failover.

## Core Value

Users can access powerful LLMs via a simple OpenAI-compatible API with pay-per-use credits, no infrastructure management, and automatic failover to cloud models when local capacity is exceeded.

## Requirements

### Validated

(None yet — ship to validate)

### Active

- **AUTH-01**: User can register with email, phone, and password
- **AUTH-02**: User can log in and maintain authenticated session
- **AUTH-03**: User can generate and manage API keys with read/write/delete permissions
- **BILL-01**: User can select subscription tier (Basic/Pro/Enterprise) and pay via MyFatoorah
- **BILL-02**: User can purchase credit top-up packs (5K/15K/50K credits)
- **API-01**: API endpoint `/v1/chat/completions` accepts Bearer token auth and proxies to Ollama
- **API-02**: Credits are deducted per request based on token usage (1x for local, 2x for cloud)
- **API-03**: Service returns 402 when credits exhausted with top-up link
- **RATE-01**: Rate limiting per tier (Basic:10/min, Pro:30/min, Enterprise:60/min)
- **QUEUE-01**: Queue depth monitoring; auto-failover to cloud model when local queue > 3
- **CLOUD-01**: Daily cloud budget tracking with 500 request cap per day
- **NOTIF-01**: Welcome WhatsApp message on registration (bilingual)
- **NOTIF-02**: Subscription confirmation with plan details (bilingual)
- **NOTIF-03**: Credit warning at 20% remaining (bilingual)
- **NOTIF-04**: Credits exhausted notification with top-up link (bilingual)
- **NOTIF-05**: Top-up purchase confirmation (bilingual)
- **NOTIF-06**: Renewal reminder 3 days before expiry (bilingual)
- **NOTIF-07**: Cloud budget at 80% admin alert
- **NOTIF-08**: Cloud budget at 100% (cloud disabled) admin alert
- **NOTIF-09**: IP banned by fail2ban admin alert
- **NOTIF-10**: New Enterprise customer registration admin alert
- **ADMIN-01**: Admin dashboard showing platform overview, users, revenue, cloud budget
- **ADMIN-02**: Admin can manually adjust user credits
- **MODEL-01**: Model access control per tier (Basic: local small/medium only)
- **MODEL-02**: Pro models (local + cloud) accessible to Pro/Enterprise users
- **MODEL-03**: Enterprise models (Pro + priority queue) for Enterprise users
- **TEAM-01**: Team member management for Enterprise (add/remove sub-users with roles)

### Out of Scope

- Email verification flow — Phone-based auth sufficient for Gulf market
- OAuth login (Google/GitHub) — Email/password + phone is standard for target users
- Self-hosted deployment — SaaS only, hosted on cPanel
- Mobile apps — Web-first portal, responsive design
- Real-time features beyond WhatsApp notifications — Cron-based scheduling acceptable
- Multi-language support beyond Arabic/English — No additional languages planned

## Context

This is a **bilingual (Arabic/English) Gulf B2B SaaS** for LLM API access. The target market is Arabic-speaking businesses in Kuwait and the Gulf region who need AI capabilities but want to avoid infrastructure complexity.

**Technical environment:**
- Backend: Laravel on cPanel (shared hosting at 152.53.86.223)
- Database: MySQL with resayili_ prefix
- AI inference: Ollama on dedicated GPU server (208.110.93.90) at port 11434
- Payment: MyFatoorah gateway (KWD currency)
- Notifications: Resayil WhatsApp API (bilingual templates)

**Existing infrastructure (DO NOT TOUCH):**
- collect.resayil.io: Existing Laravel app on same cPanel
- ollama-n8n container: Serves n8n on separate VPS
- vllm container: Internal dev use only
- open-webui container: Port 3000, internal only

**User expectations:**
- Dark luxury UI with Gulf B2B professional aesthetic
- Arabic-first with RTL support
- Fast response times (local Ollama for speed)
- Clear pricing and credit management
- WhatsApp notifications for all key events

## Constraints

- **Tech Stack**: Laravel + MySQL + Blade + Tailwind CSS — must match existing collect.resayil.io pattern
- **Hosting**: cPanel shared hosting, no Docker containers on this server
- **GPU Server**: Ollama only, no portal code deployment
- **Security**: All secrets in cPanel ~/.env only, never in git
- **Model Restrictions**: glm-4.7-flash, bge-m3, nomic-embed-text never exposed via portal API
- **Cloud Cap**: 500 requests/day max, then local-only fallback
- **Currency**: KWD only (MyFatoorah requirement)
- **Language**: Arabic + English bilingual throughout, Arabic-first design

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Laravel on cPanel | Matches existing collect.resayil.io stack, proven shared hosting deployment | Consistent运维 approach |
| MyFatoorah for payments | Standard payment gateway in Kuwait/Gulf region, supports KWD | Local compliance |
| Resayil WhatsApp for notifications | Existing integration pattern from collect.resayil.io | Reuse verified solution |
| OpenAI-compatible API | Standard format, users can use existing clients (LangChain, etc.) | Low barrier to entry |
| Credit-based billing | Simpler than pure per-request for users, supports monthly + top-up | Predictable costs |
| Auto-failover to cloud | Ensures service availability during local queue congestion | Better UX |
| Dual pricing (1x local, 2x cloud) | Reflects actual infrastructure cost difference | Sustainable economics |

---

*Last updated: 2026-02-26 after initialization*
