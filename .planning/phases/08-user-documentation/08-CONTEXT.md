# Phase 8: User Documentation - Context

**Gathered:** 2026-03-03
**Status:** Ready for planning
**Source:** Direct user request in session

<domain>
## Phase Boundary

Create end-user documentation for API consumers (developers using llm.resayil.io).
Covers: getting started, authentication, API reference, models, billing, errors, code examples.

Delivered as:
1. Markdown files under `docs/` in the repo
2. A rendered `/docs` page inside the portal (Blade view, dark luxury theme)

</domain>

<decisions>
## Implementation Decisions

### Audience
- End-users / API consumers (developers integrating the LLM API)
- NOT an admin guide (admin knows the system internally)

### Output Format (LOCKED — user confirmed)
- Markdown files in `/docs` directory in repo root
- A `/docs` portal page served from the Laravel app (Blade view)
- Both must exist — the Blade page is the primary UX, markdown is the source

### Content to Cover
- Getting started: register → get API key → make first call
- Authentication: how API key auth works (`Authorization: Bearer <key>`)
- API base URL: `https://llm.resayil.io/api/v1`
- Endpoints: `POST /api/v1/chat/completions`, `GET /api/v1/models`
- Models: how to list models, what families/types are available
- Credits: what they are, how deduction works (1× local, 2× cloud), how to top up
- Rate limits: Basic 10/min, Pro 30/min, Enterprise 60/min
- Error codes: 401 (bad key), 402 (no credits), 429 (rate limit), 5xx (server error)
- Code examples: curl, Python (openai SDK), JavaScript (openai SDK), n8n HTTP node
- Bilingual note: platform is Arabic/English but docs can be English-first

### Design (LOCKED)
- Dark luxury theme: bg `#0f1115`, gold `#d4af37`, card `#13161d`
- CSS vars: `--gold`, `--bg-card`, `--bg-secondary`, `--border`, `--text-muted`
- Fonts: Inter (Latin) + Tajawal (Arabic)
- Sidebar navigation for sections, code blocks with syntax highlighting (highlight.js or prism)
- Mobile responsive

### Portal Page
- Route: `GET /docs` — public (no auth required)
- Named route: `docs`
- Nav link added to public navbar (welcome page) and authenticated navbar
- Single Blade view: `resources/views/docs.blade.php`
- Sections navigable via anchor links / sidebar

### Markdown Files
- `docs/getting-started.md`
- `docs/authentication.md`
- `docs/api-reference.md`
- `docs/models.md`
- `docs/billing-credits.md`
- `docs/rate-limits.md`
- `docs/error-codes.md`
- `docs/code-examples.md`

### Claude's Discretion
- Exact sidebar/layout structure
- Whether to use a JS-powered sidebar or pure CSS scroll-spy
- Whether to show a copy button on code blocks
- Ordering of sections

</decisions>

<specifics>
## Specific Details

**API Base URL:** `https://llm.resayil.io/api/v1`
**Auth header:** `Authorization: Bearer YOUR_API_KEY`
**Chat endpoint:** `POST /api/v1/chat/completions` — OpenAI-compatible
**Models endpoint:** `GET /api/v1/models`
**Credit costs:** local models = 1 credit/token, cloud models = 2 credits/token
**Tiers:** Starter (15 KWD), Basic (25 KWD), Pro (45 KWD) monthly
**Top-ups:** 5,000 / 15,000 / 50,000 credits

**Error HTTP codes:**
- 401: Invalid or missing API key
- 402: Insufficient credits
- 429: Rate limit exceeded
- 503: Model unavailable / Ollama offline

</specifics>

<deferred>
## Deferred Ideas

- Arabic translation of docs page — out of scope for this phase
- Interactive API playground (try it live) — future v2 feature
- Versioned API docs — not needed yet (single version)
- PDF export — out of scope

</deferred>

---

*Phase: 08-user-documentation*
*Context gathered: 2026-03-03 from session conversation*
