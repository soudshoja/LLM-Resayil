# Pending Tasks — Execute After /clear

4 tasks to run in parallel with agent team + Haiku where possible.

---

## Task 1: Docs Page Redesign (`resources/views/docs.blade.php`)

**Problem:** Current page is visually flat/generic. Content is correct, CSS needs full overhaul.

**Design Direction (from UI skill):**
- Fonts: **IBM Plex Sans** (body) + **JetBrains Mono** (code) — developer-focused
- Google Fonts URL: `https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap`
- Keep dark luxury color vars: `--bg-primary: #0f1115`, `--gold: #d4af37`
- Body bg slightly darker: `#090b0f` (more "code editor" feel)

**Key changes to CSS & HTML:**
1. Replace Inter with IBM Plex Sans for body text
2. Replace `Courier New` with JetBrains Mono for code elements
3. Code blocks: add a **language header bar** (dark strip at top showing "bash" / "json" / "python" / "javascript") — injected via JS reading `language-*` class from `<code>` elements
4. Sidebar: add `v1` version badge at top, group sections with a label "Reference" above the 5 API sections
5. Section h2: add subtle left border `3px solid rgba(212,175,55,0.4)` + `padding-left: 1rem`
6. Getting Started steps: render as **numbered card boxes** (dark card with gold circle number on left) instead of plain `<ol>`
7. Tables: striped rows (alternate `rgba(255,255,255,0.02)`), remove outer border radius on table itself
8. Callout boxes: 3 variants — info (gold), warning (amber), error (red) using a class
9. Error cards: add a monospace status code block at top-right corner
10. Tab buttons: pill style (not underline), active tab gets gold background fill
11. Footer: centered, muted, `border-top: 1px solid var(--border)`
12. Mobile: sidebar overlay (not push), hamburger is an SVG icon (not ≡ char)

**Content stays identical — only CSS + minor structural HTML changes.**

**File:** `resources/views/docs.blade.php` — full rewrite of `<style>` block + minor HTML tweaks
**No new routes or controllers needed.**

---

## Task 2: Usage Detail per API Call (Dashboard)

**Goal:** Show developers a detailed breakdown of each API call cost in the dashboard.

**Files to modify:**
- `resources/views/dashboard.blade.php` — enhance the "Recent Usage" table section

**Current table columns:** Model | Tokens | Credits Used | Time

**New table columns:** Model | Input Tokens | Output Tokens | Total Tokens | Multiplier | Credits | Time

**Also add 3 summary cards ABOVE the table:**
- Total tokens this week
- Total credits this week
- Avg credits per request

**Data source:** `UsageLog` model — check what columns exist:
- File: `app/Http/Controllers/DashboardController.php` (or wherever dashboard data is built)
- `UsageLog` fields likely: `prompt_tokens`, `completion_tokens`, `total_tokens`, `credits_used`, `model`, `created_at`
- Need to check if `prompt_tokens` and `completion_tokens` are stored separately or just `total_tokens`

**If only `total_tokens` exists:** Show Total | Multiplier | Credits columns (skip split)
**If split tokens exist:** Show full Input | Output | Total | Multiplier | Credits

**Multiplier logic:** if model is cloud (credit_cost_multiplier = 2) → show "2×", else "1×"
- Get multiplier from `config/models.php` or join with `models` table

**Frontend only changes** — no migration needed unless adding columns.

---

## Task 3: Landing Page Hero Slider

**Goal:** Add an autoplay carousel to the landing page hero section showcasing models/features.

**File:** `resources/views/welcome.blade.php`

**Placement:** Inside the existing hero section, BELOW the headline/CTA buttons, ABOVE the code block or as a standalone section after the hero.

**Slide content (5 slides):**
1. ⚡ Llama 3.2 3B — "Fastest for everyday tasks · 1 credit/token"
2. 🧠 DeepSeek V3.1 671B — "Frontier reasoning at your fingertips · 2 credits/token"
3. 💬 Qwen 3.5 397B — "Largest MoE available · multilingual · 2 credits/token"
4. 🔌 OpenAI-Compatible — "Drop-in replacement · zero code changes"
5. 📊 45+ Models — "One API. Local + Cloud. Pay per token."

**Implementation:**
- Pure CSS + vanilla JS (no libraries)
- Auto-play every 4 seconds, pause on hover
- Slide transition: `transform: translateX()` with `transition: 0.5s ease`
- Pagination dots at bottom
- Prev/next arrows (SVG chevrons, gold color)
- Touch swipe support on mobile
- Match existing dark luxury theme — card background `var(--bg-card)`, gold accents
- Respect `prefers-reduced-motion` (disable autoplay if set)

**No new routes/controllers — frontend only.**

---

## Task 4: Arabic Language Support (RTL)

**Goal:** Add Arabic locale with RTL layout + language switcher in navbar.

**Files to create:**
- `resources/lang/ar/auth.php`
- `resources/lang/ar/validation.php`
- `resources/lang/ar/welcome.php` (landing page strings)
- `resources/lang/ar/dashboard.php`
- `resources/lang/ar/billing.php`
- `resources/lang/ar/navigation.php`
- `resources/lang/en/navigation.php` (English baseline)
- `app/Http/Middleware/SetLocale.php` (already exists in git status!)

**Files to modify:**
- `config/app.php` — add `'ar'` to supported locales
- `routes/web.php` — add locale prefix group OR use middleware
- `resources/views/layouts/app.blade.php` — add `dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"` to `<html>`, add locale switcher to nav
- `resources/views/welcome.blade.php` — replace hardcoded strings with `__('welcome.key')`

**Middleware approach:** `SetLocale.php` already exists — check its contents first before writing it.

**RTL CSS additions (add to app.blade.php or a separate rtl.css):**
- `[dir="rtl"] .sidebar` → flip positioning
- `[dir="rtl"] .nav-links` → `flex-direction: row-reverse`
- `[dir="rtl"] .card` → text-align right
- Arabic font: Tajawal already loaded in layout

**Locale switcher in navbar:**
```html
<div class="locale-switcher">
  <a href="{{ route('locale', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
  <span>|</span>
  <a href="{{ route('locale', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}">ع</a>
</div>
```

**Route for switching:**
```php
Route::get('/locale/{locale}', function($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('locale')->where('locale', 'en|ar');
```

---

## Execution Order (Parallel)

All 4 tasks are independent — run simultaneously:

| Agent | Task | Model | Est. complexity |
|---|---|---|---|
| Agent 1 | Docs page redesign (CSS rewrite) | Haiku | Medium |
| Agent 2 | Usage detail dashboard | Haiku | Small |
| Agent 3 | Hero slider | Haiku | Small |
| Agent 4 | Arabic RTL support | Sonnet | Large |

**After all agents complete:**
1. `git add` specific files only
2. Single commit: `feat: UI/UX improvements + Arabic support + usage detail + hero slider`
3. Push to `dev`
4. Deploy: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
5. Test on https://llmdev.resayil.io

---

---

## v2.1 — Static Pages (DONE — 2026-03-03)

Routes + views created:
- `GET /about` → `WelcomeController@about` → `resources/views/about.blade.php`
- `GET /privacy-policy` → `WelcomeController@privacy` → `resources/views/privacy-policy.blade.php`
- `GET /terms-of-service` → `WelcomeController@terms` → `resources/views/terms-of-service.blade.php`

---

## v2.2 — GEO Optimization (Generative Engine Optimization)

**Goal:** Make the docs page citeable by AI search engines (ChatGPT, Perplexity, AI Overviews).

### Task A: FAQ Section on Docs Page (`resources/views/docs.blade.php`)
Add a new section at the bottom of the docs sidebar and content area titled "Frequently Asked Questions".

**FAQ questions to include:**
1. What models are available on LLM Resayil?
2. Is LLM Resayil compatible with OpenAI's API?
3. How do credits work?
4. What payment methods are accepted?
5. Can I use LLM Resayil for commercial projects?
6. What is the rate limit per tier?
7. How do I get started with the API?
8. Is there a free trial?

### Task B: Make H2s Question-Based (docs page)
Where appropriate, rewrite section H2 headings as natural language questions:
- "Authentication" → "How do I authenticate API requests?"
- "Chat Completions" → "How do I send a chat completion request?"
- "Error Handling" → "What errors can the API return?"
- "Rate Limits" → "What are the API rate limits?"

### Task C: FAQ Schema Markup
Inject a `<script type="application/ld+json">` FAQ schema block in the `<head>` of docs.blade.php.

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is LLM Resayil compatible with OpenAI's API?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. LLM Resayil provides a fully OpenAI-compatible REST API. Just change your base URL to https://llm.resayil.io/api/v1 and use your LLM Resayil API key — no other code changes needed."
      }
    }
    /* ... all 8 FAQs ... */
  ]
}
```

**File:** `resources/views/docs.blade.php`
**No new routes needed.**

---

## v2.3 — Technical SEO & Schema (Week 2)

### Task A: `robots.txt` with AI Crawler Permissions
Create `public/robots.txt`:
```
User-agent: *
Allow: /
Disallow: /admin
Disallow: /api/

User-agent: GPTBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: anthropic-ai
Allow: /

Sitemap: https://llm.resayil.io/sitemap.xml
```

### Task B: JSON-LD Organization Schema on Homepage
In `resources/views/welcome.blade.php`, add to `<head>`:
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "LLM Resayil",
  "url": "https://llm.resayil.io",
  "description": "OpenAI-compatible LLM API with 45+ models. Pay per token. KNET payments.",
  "logo": "https://llm.resayil.io/favicon.ico",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer support",
    "url": "https://llm.resayil.io/contact"
  },
  "sameAs": []
}
</script>
```

Also add a `SoftwareApplication` schema for the API product.

### Task C: Open Graph Meta Tags
Add to `resources/views/layouts/app.blade.php` (in `<head>`):
```html
<meta property="og:title" content="@yield('title', 'LLM Resayil') — AI API for Developers">
<meta property="og:description" content="@yield('og_description', '45+ AI models. OpenAI-compatible. Pay per token. KNET payments.')">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('og-image.png') }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'LLM Resayil')">
<meta name="description" content="@yield('meta_description', '45+ open-source AI models via one OpenAI-compatible API. Local GPU inference + cloud proxies. Pay per token. KNET payments.')">
```

Also create `public/sitemap.xml` with all public routes.

**Files:**
- `public/robots.txt` (new)
- `public/sitemap.xml` (new)
- `resources/views/welcome.blade.php` (add schema)
- `resources/views/layouts/app.blade.php` (add OG tags)

---

## Important Notes for Execution

- **Always deploy to dev first** — never touch `main` or prod until verified on llmdev
- `SetLocale.php` already exists as untracked file — read it before modifying
- `resources/lang/` directory already exists as untracked — check its contents first
- `resources/views/contact.blade.php` + `app/Http/Controllers/ContactController.php` also untracked — don't touch
- Dashboard controller: check how `UsageLog` data is passed to dashboard view before modifying
- No npm packages — all JS vanilla, no build step
