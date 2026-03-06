# LLM Resayil — SEO Reference & Update Guide

> Last updated: 2026-03-06 | Use this file to track and update all SEO metadata

---

## HOW TO UPDATE

| What you want to change | Where to edit |
|---|---|
| Title / description / keywords / OG image for any page | `app/Helpers/SeoHelper.php` — the `$metadata` array |
| Global fallback meta tags (when no page key is found) | `app/Helpers/SeoHelper.php` — the `getPageMeta()` fallback return |
| Global OG defaults used in `<head>` | `resources/views/layouts/app.blade.php` lines 7–18 |
| Organization schema (JSON-LD) | `resources/views/layouts/app.blade.php` lines 154–185 |
| SoftwareApplication schema (JSON-LD) | `resources/views/welcome.blade.php` lines 13–??? |
| Canonical URL logic | `resources/views/layouts/app.blade.php` line 19 |
| robots.txt rules | `public/robots.txt` |
| Sitemap URL | `public/robots.txt` line 75 |
| Footer links (all pages) | `resources/views/layouts/app.blade.php` lines 356–391 |
| Google Analytics tag ID | `resources/views/layouts/app.blade.php` lines 188–193 |
| GA event tracking code | `resources/views/layouts/app.blade.php` lines 196–205 |
| Per-page `@stack('styles')` additions | Individual view files |
| Per-page `@stack('scripts')` additions | Individual view files (injected before `</body>`) |
| Add a new page with SEO | 1. Add key to `SeoHelper::$metadata`, 2. Pass meta vars in route/controller, 3. Extend `layouts.app` in the view |

---

## 1. GLOBAL LAYOUT (`resources/views/layouts/app.blade.php`)

### Title Pattern

```html
<title>{{ $pageTitle ?? (@yield('title', 'LLM Resayil')) }}</title>
```

Priority order:
1. `$pageTitle` variable passed from controller/route closure (sourced from `SeoHelper::getPageMeta()`)
2. `@yield('title', ...)` from child view's `@section('title', '...')`
3. Fallback literal: `LLM Resayil`

### Meta Tags (Global Defaults)

| Tag | Attribute | Default value (when `$pageDescription` / `$pageKeywords` not set) |
|---|---|---|
| `<meta name="description">` | `content` | `Affordable OpenAI-compatible LLM API with 45+ models. Pay-per-token pricing, free tier with 1,000 credits.` |
| `<meta name="keywords">` | `content` | `llm api, openai alternative, ai api` |
| `<meta name="csrf-token">` | `content` | `{{ csrf_token() }}` (dynamic) |
| `<meta charset>` | — | `UTF-8` |
| `<meta name="viewport">` | `content` | `width=device-width, initial-scale=1.0` |

### Open Graph Defaults

| OG Property | Value / Fallback |
|---|---|
| `og:title` | `$pageTitle` ?? `@yield('title', 'LLM Resayil')` |
| `og:description` | `$pageDescription` ?? `''` (empty string — no global default) |
| `og:image` | `$ogImage` ?? `https://llm.resayil.io/og-images/og-default.png` |
| `og:url` | `{{ request()->url() }}` (dynamic, current request URL) |
| `og:type` | `$ogType` ?? `website` |

### Twitter Card Defaults

| Twitter Tag | Value / Fallback |
|---|---|
| `twitter:card` | `summary_large_image` (hardcoded) |
| `twitter:title` | `$pageTitle` ?? `@yield('title', 'LLM Resayil')` |
| `twitter:description` | `$pageDescription` ?? `''` (empty string) |
| `twitter:image` | `$ogImage` ?? `https://llm.resayil.io/og-images/og-default.png` |

### Canonical URL

```html
<link rel="canonical" href="{{ url(request()->getPathInfo()) }}">
```

Generated dynamically from the current request path. Always points to the production domain configured in `APP_URL`. No per-page overrides — the same logic applies everywhere.

### Google Analytics

- **Tag ID:** `G-M0T3YYQP7X`
- **Conditional:** Wrapped in `@if(app()->isProduction())` — fires ONLY on prod (`llm.resayil.io`), never on dev/staging
- **Script loaded:** `https://www.googletagmanager.com/gtag/js?id=G-M0T3YYQP7X` (async)
- **Location in file:** Lines 186–207

```javascript
gtag('config', 'G-M0T3YYQP7X');
```

### GA Event Tracking

One custom GA event is fired globally (inside the same `@if(app()->isProduction())` block):

| Event Name | Trigger | Parameters sent |
|---|---|---|
| `internal_link_click` | Click on any `<a href="/">` (internal link, starts with `/`) | `link_destination` (full href), `link_text` (trimmed text content), `page_source` (current pathname) |

### Schema / Structured Data (JSON-LD)

**Organization schema** — present in `layouts/app.blade.php` (global, on all pages):

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "LLM Resayil",
  "url": "https://llm.resayil.io",
  "logo": "https://llm.resayil.io/logo.png",
  "description": "Powerful AI assistant platform with 50+ models. Write faster, answer anything, and get results instantly. 1,000 free credits—no credit card required.",
  "foundingDate": "2024-01-01",
  "headquarters": { "@type": "Place", "address": { "@type": "PostalAddress", "addressCountry": "KW" } },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Support",
    "email": "support@resayil.io",
    "availableLanguage": ["en", "ar"]
  },
  "areaServed": { "@type": "Country", "name": "Worldwide" },
  "sameAs": ["https://twitter.com/LLMResayil", "https://facebook.com/LLMResayil"]
}
```

**SoftwareApplication schema** — present in `resources/views/welcome.blade.php` (homepage only):

```json
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "LLM Resayil",
  "description": "Powerful AI assistant platform with 50+ models. Write faster, answer anything, and get results instantly.",
  "applicationCategory": "DeveloperApplication",
  "operatingSystem": "Web",
  "url": "https://llm.resayil.io",
  "image": "https://llm.resayil.io/og-image.png",
  "offers": [
    {
      "@type": "Offer",
      "priceCurrency": "KWD",
      "price": "0",
      "name": "Free Trial",
      "description": "Start free with 1,000 credits. No credit card required. Valid for 7 days."
    }
  ]
}
```

### @stack Positions

| Stack | Location | Purpose |
|---|---|---|
| `@stack('styles')` | Line 152, inside `<head>`, after all global CSS | Per-page CSS injection |
| `@stack('scripts')` | Line 393, just before `</body>` | Per-page JS injection |

### Global Footer Links

All links appear in every page's footer (3 sections / clusters).

**Cluster 1: Pricing & Savings**

| URL | Link Text |
|---|---|
| `/pricing` | Pricing Plans |
| `/cost-calculator` | Cost Calculator |
| `/comparison` | Compare OpenRouter |
| `/alternatives` | LLM Alternatives |

**Cluster 2: Developer Tools**

| URL | Link Text |
|---|---|
| `/docs` | API Documentation |
| `/features` | Available Models |
| `/api-keys` | API Keys |
| `/credits` | How Credits Work |

**Cluster 3: Learn & Compare**

| URL | Link Text |
|---|---|
| `/about` | About Us |
| `/contact` | Contact Support |
| `/privacy-policy` | Privacy |

**Footer copyright line:**
`LLM Resayil © 2026. Affordable, OpenAI-compatible LLM API.`

---

## 2. ROBOTS.TXT (`public/robots.txt`)

Sitemap declared: `https://llm.resayil.io/sitemap.xml`

Crawl rate directives (global):
- `Crawl-delay: 1`
- `Request-rate: 10/1s`

### Rules by Agent

| User-Agent | Allowed Paths | Disallowed Paths |
|---|---|---|
| `*` (all) | `/`, `/docs`, `/pricing`, `/features`, `/comparison`, `/vs-ollama`, `/alternatives`, `/cost-calculator`, `/faq`, `/blog` | `/admin`, `/admin/`, `/dashboard`, `/profile`, `/billing`, `/api-keys`, `/api/`, `/login`, `/register`, `/checkout` |
| `GPTBot` | `/`, `/docs`, `/pricing`, `/features`, `/comparison`, `/vs-ollama`, `/cost-calculator`, `/faq`, `/blog` | `/admin`, `/admin/`, `/dashboard`, `/login` |
| `ClaudeBot` | `/`, `/docs`, `/pricing`, `/comparison` | `/admin`, `/admin/`, `/dashboard` |
| `PerplexityBot` | `/`, `/docs`, `/pricing`, `/features`, `/comparison` | `/admin`, `/admin/` |
| `anthropic-ai` | `/` | `/admin`, `/admin/` |
| `Googlebot` | `/` | _(none explicitly listed)_ |

### Paths NOT in robots.txt Allowed Lists (notable gaps)

These routes exist in `routes/web.php` but are not explicitly listed in `robots.txt` Allow rules:
- `/about`
- `/contact`
- `/privacy-policy`
- `/terms-of-service`
- `/cost-calculator` (listed for `*` and `GPTBot` but NOT for `ClaudeBot`/`PerplexityBot`)
- `/dedicated-server`

---

## 3. PAGE-BY-PAGE SEO INVENTORY

All metadata originates from `app/Helpers/SeoHelper.php` via `SeoHelper::getPageMeta($key)`.

### Public / Marketing Pages

| Page | URL | SeoHelper Key | Title | Description (truncated) | OG Image |
|---|---|---|---|---|---|
| Home | `/` | `welcome` | LLM Resayil — OpenAI Alternative API | LLM Resayil: Affordable OpenAI-compatible API. 45+ models... | `og-home.png` |
| Comparison | `/comparison` | `comparison` | LLM Resayil vs Competitors — Cost & Speed Comparison | LLM Resayil vs. OpenRouter: Cost comparison, speed benchmarks... | `og-comparison.png` |
| Alternatives | `/alternatives` | `alternatives` | OpenAI Alternatives — LLM Resayil | Explore OpenAI alternatives. Compare features, pricing... | `og-alternatives.png` |
| Cost Calculator | `/cost-calculator` | `cost-calculator` | LLM Cost Calculator — Compare Pricing | Calculate your LLM API costs. Compare pricing across OpenAI... | `og-calculator.png` |
| Dedicated Server | `/dedicated-server` | `dedicated-server` | Resayil LLM + Dedicated Server Hosting: Enterprise Infrastructure | Dedicated server hosting with Resayil LLM API. Full control... | `og-dedicated-server.png` |
| About | `/about` | `about` | About Us — LLM Resayil | About LLM Resayil: Our mission is to provide affordable... | `og-about.png` |
| Contact | `/contact` | `contact` | Contact Us — LLM Resayil | Get in touch with LLM Resayil support... | `og-contact.png` |
| Privacy Policy | `/privacy-policy` | `privacy-policy` | Privacy Policy — LLM Resayil | LLM Resayil Privacy Policy. How we collect, use... | `og-privacy.png` |
| Terms of Service | `/terms-of-service` | `terms-of-service` | Terms of Service — LLM Resayil | LLM Resayil Terms of Service. Read our terms... | `og-terms.png` |
| Blog | `/blog` | `blog` | Blog — LLM Resayil | Stay updated with LLM Resayil blog... | `og-blog.png` |
| Landing (Template 3) | `/landing/3` | `landing.3` | LLM Resayil — Affordable AI API Platform | LLM Resayil: Affordable OpenAI-compatible API... | `og-landing.png` |

**Note:** `/pricing` and `/features` are linked in the footer and listed in robots.txt but have **no route in `routes/web.php`** and no SeoHelper key — they will 404.

### Auth Pages

| Page | URL | SeoHelper Key | Title | View |
|---|---|---|---|---|
| Login | `/login` | `login` | Login — LLM Resayil | `auth.login` |
| Register | `/register` | `register` | Create Account — LLM Resayil | `auth.register` |

### Authenticated / App Pages

| Page | URL | SeoHelper Key | Title | Auth Required |
|---|---|---|---|---|
| Dashboard | `/dashboard` | `dashboard` | Dashboard — LLM Resayil | Yes |
| API Keys | `/api-keys` | `api-keys` | API Keys — LLM Resayil | Yes |
| Billing Plans | `/billing/plans` | `billing.plans` | Pricing Plans — LLM Resayil | Yes |
| Payment Methods | `/billing/payment-methods` | `billing.payment-methods` | Payment Methods — LLM Resayil | Yes |
| Credits | `/credits` | `credits` | Credits System — LLM Resayil | No (public route, no auth middleware) |
| Docs | `/docs` | `docs` | API Documentation — LLM Resayil | No (public route) |
| Profile | `/profile` | `profile` | Profile Settings — LLM Resayil | Yes |
| Teams | `/teams` | `teams.index` | Teams — LLM Resayil | Yes (Enterprise only) |

### Admin Pages

| Page | URL | SeoHelper Key | Title | Notes |
|---|---|---|---|---|
| Admin Dashboard | `/admin` | `admin.dashboard` | Admin Dashboard — LLM Resayil | Admin middleware |
| Monitoring | `/admin/monitoring` | _(inline, not SeoHelper)_ | Monitoring — LLM Resayil | Hardcoded inline in `web.php` |
| Models | `/admin/models` | — | — | No SeoHelper call found |

### Landing Templates (Dev Only)

| Page | URL | View | Notes |
|---|---|---|---|
| Template 1 | `/landing/1` | `landing.template-1` | No SeoHelper call |
| Template 2 | `/landing/2` | `landing.template-2` | No SeoHelper call |
| Template 3 | `/landing/3` | `landing.template-3` | No SeoHelper call (metadata in SeoHelper but not wired to route closure) |

---

## 4. CONTROLLERS — METADATA PASSED TO VIEWS

All controllers use `SeoHelper::getPageMeta($key)` and pass five variables to views:

```php
$meta = \App\Helpers\SeoHelper::getPageMeta('key');
return view('view-name', [
    'pageTitle'       => $meta['title'],
    'pageDescription' => $meta['description'],
    'pageKeywords'    => $meta['keywords'],
    'ogImage'         => $meta['ogImage'],
    'ogType'          => $meta['ogType'],
]);
```

### Per-Controller Summary

| Controller | File | SeoHelper Key(s) | View(s) |
|---|---|---|---|
| `WelcomeController::index()` | `app/Http/Controllers/WelcomeController.php` | `welcome` | `welcome` |
| `WelcomeController::about()` | same | `about` | `about` |
| `WelcomeController::privacy()` | same | `privacy-policy` | `privacy-policy` |
| `WelcomeController::terms()` | same | `terms-of-service` | `terms-of-service` |
| `ApiKeysController::index()` | `app/Http/Controllers/ApiKeysController.php` | `api-keys` | `api-keys` |
| `AuthenticatedSessionController::create()` | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | `login` | `auth.login` |
| `RegisteredUserController::create()` | `app/Http/Controllers/Auth/RegisteredUserController.php` | `register` | `auth.register` |
| `PaymentController::index()` | `app/Http/Controllers/Billing/PaymentController.php` | `billing.plans` | `billing.plans` |
| `PaymentMethodsController::index()` | `app/Http/Controllers/Billing/PaymentMethodsController.php` | `billing.payment-methods` | `billing.payment-methods` |
| `ProfileController::show()` | `app/Http/Controllers/ProfileController.php` | `profile` | `profile` |
| `TeamMemberController::index()` | `app/Http/Controllers/TeamMemberController.php` | `teams.index` | `teams.dashboard` |

### Route Closure Pages (in `routes/web.php`, no dedicated controller)

| Route | SeoHelper Key | View |
|---|---|---|
| `GET /dashboard` | `dashboard` | `dashboard` |
| `GET /credits` | `credits` | `credits` |
| `GET /docs` | `docs` | `docs` |
| `GET /contact` | `contact` | `contact` |
| `GET /comparison` | `comparison` | `comparison` |
| `GET /alternatives` | `alternatives` | `alternatives` |
| `GET /cost-calculator` | `cost-calculator` | `cost-calculator` |
| `GET /dedicated-server` | `dedicated-server` | `dedicated-server` |
| `GET /admin` | `admin.dashboard` | `admin.dashboard` |
| `GET /admin/monitoring` | `admin.dashboard` (ogImage only) + hardcoded title/desc | `admin.monitoring` |

---

## 5. ALL GET ROUTES

Complete list of GET routes from `routes/web.php`:

| URL Pattern | Handler | Route Name | Auth |
|---|---|---|---|
| `GET /` | `WelcomeController::index()` | `welcome` | No |
| `GET /landing/1` | closure → `landing.template-1` | `landing.1` | No |
| `GET /landing/2` | closure → `landing.template-2` | `landing.2` | No |
| `GET /landing/3` | closure → `landing.template-3` | `landing.3` | No |
| `GET /locale/{locale}` | closure (session set + redirect) | `locale` | No |
| `GET /register` | `RegisteredUserController::create()` | `register` | Guest only |
| `GET /login` | `AuthenticatedSessionController::create()` | `login` | Guest only |
| `GET /dashboard` | closure → `dashboard` view | — | `auth` |
| `GET /profile` | `ProfileController::show()` | `profile` | `auth` |
| `GET /api-keys` | `ApiKeysController::index()` | — | `auth` |
| `GET /billing/plans` | `PaymentController::index()` | `billing.plans` | `auth` |
| `GET /billing/trial/callback` | `PaymentController::handleTrialCallback()` | `billing.trial.callback` | `auth` |
| `GET /billing/payment-methods` | `PaymentMethodsController::index()` | `billing.payment-methods` | `auth` |
| `GET /billing/extra-key/callback` | `PaymentController::handleExtraKeyCallback()` | `billing.extra-key.callback` | `auth` |
| `GET /billing/topup/callback` | `PaymentController::handleTopupCallback()` | `billing.topup.callback` | `auth` |
| `GET /credits` | closure → `credits` view | `credits` | No |
| `GET /docs` | closure → `docs` view | `docs` | No |
| `GET /contact` | closure → `contact` view | `contact` | No |
| `GET /about` | `WelcomeController::about()` | `about` | No |
| `GET /privacy-policy` | `WelcomeController::privacy()` | `privacy-policy` | No |
| `GET /terms-of-service` | `WelcomeController::terms()` | `terms-of-service` | No |
| `GET /comparison` | closure → `comparison` view | `comparison` | No |
| `GET /alternatives` | closure → `alternatives` view | `alternatives` | No |
| `GET /cost-calculator` | closure → `cost-calculator` view | `cost-calculator` | No |
| `GET /dedicated-server` | closure → `dedicated-server` view | `dedicated-server` | No |
| `GET /models/catalog` | `Api\ModelsController::index()` | — | `auth` |
| `GET /admin` | closure → `admin.dashboard` view | `admin.dashboard` | `auth` + `admin` |
| `GET /admin/api-settings` | `ApiSettingsController::index()` | `admin.api-settings` | `auth` + `admin` |
| `GET /admin/monitoring` | closure → `admin.monitoring` view | `admin.monitoring` | `auth` + `admin` |
| `GET /admin/models` | `AdminModelController::index()` | `admin.models` | `auth` + `admin` |
| `GET /teams` | `TeamMemberController::index()` | `teams.index` | `auth` + `enterprise` |

---

## 6. GA EVENT TRACKING

### Custom Events (currently tracked)

| Event Name | Where fired | Parameters |
|---|---|---|
| `internal_link_click` | Global (all pages, prod only) — click on any `<a href="/">` | `link_destination` (string), `link_text` (string), `page_source` (string) |

**Location:** `resources/views/layouts/app.blade.php` lines 196–205, inside the `@if(app()->isProduction())` block.

**Standard events** (fired automatically by gtag.js config):
- Page views (every page load, via `gtag('config', 'G-M0T3YYQP7X')`)

### No Per-Page Custom Events Found

No `gtag('event', ...)` calls were found in any individual view or controller files at time of this audit. All custom tracking is centralized in the layout.

---

## 7. QUICK UPDATE CHECKLIST

### When adding a new public page

1. Add a new key to `app/Helpers/SeoHelper.php` in the `$metadata` array:
   ```php
   'my-new-page' => [
       'title'       => 'Page Title — LLM Resayil',
       'description' => '150–160 char description.',
       'keywords'    => 'keyword1, keyword2, keyword3',
       'ogImage'     => 'https://llm.resayil.io/og-images/og-my-new-page.png',
       'ogType'      => 'website',
   ],
   ```
2. Add the route in `routes/web.php` using the SeoHelper pattern:
   ```php
   Route::get('/my-new-page', function () {
       $meta = \App\Helpers\SeoHelper::getPageMeta('my-new-page');
       return view('my-new-page', [
           'pageTitle'       => $meta['title'],
           'pageDescription' => $meta['description'],
           'pageKeywords'    => $meta['keywords'],
           'ogImage'         => $meta['ogImage'],
           'ogType'          => $meta['ogType'],
       ]);
   })->name('my-new-page');
   ```
3. Create the view extending `layouts.app`:
   ```blade
   @extends('layouts.app')
   @section('content')
   ...
   @endsection
   ```
4. Add an OG image to `public/og-images/og-my-new-page.png` (1200x630px recommended)
5. Add the URL to `public/robots.txt` `Allow` rules for relevant crawlers
6. Consider adding a footer link in `resources/views/layouts/app.blade.php`
7. Add to sitemap if a `sitemap.xml` is generated

### When updating existing metadata

Edit only `app/Helpers/SeoHelper.php`. No other files need to change.

### When adding a page-specific schema / JSON-LD block

Use `@push('styles')` in the child view:
```blade
@push('styles')
<script type="application/ld+json">{ ... }</script>
@endpush
```
(Using the styles stack is the standard pattern in this project for injecting `<head>` content.)

---

## 8. TODO / GAPS

### Missing OG images (referenced in SeoHelper but may not exist on disk)

Check `public/og-images/` — the following images are referenced but were not verified to exist:

| File | Used by page |
|---|---|
| `og-default.png` | Fallback for all pages without a match |
| `og-home.png` | `/` |
| `og-docs.png` | `/docs` |
| `og-pricing.png` | `/billing/plans` |
| `og-credits.png` | `/credits` |
| `og-api-keys.png` | `/api-keys` |
| `og-dashboard.png` | `/dashboard` |
| `og-profile.png` | `/profile` |
| `og-contact.png` | `/contact` |
| `og-about.png` | `/about` |
| `og-login.png` | `/login` |
| `og-register.png` | `/register` |
| `og-payment-methods.png` | `/billing/payment-methods` |
| `og-privacy.png` | `/privacy-policy` |
| `og-terms.png` | `/terms-of-service` |
| `og-admin.png` | `/admin` |
| `og-teams.png` | `/teams` |
| `og-comparison.png` | `/comparison` |
| `og-alternatives.png` | `/alternatives` |
| `og-calculator.png` | `/cost-calculator` |
| `og-blog.png` | `/blog` |
| `og-landing.png` | `/landing/3` |
| `og-dedicated-server.png` | `/dedicated-server` |

### Broken footer links (404 routes)

These links appear in the global footer (`layouts/app.blade.php`) but have no matching route in `routes/web.php`:

| Footer URL | Footer Text |
|---|---|
| `/pricing` | Pricing Plans |
| `/features` | Available Models |

### Pages without a SeoHelper key

| Page | URL | Issue |
|---|---|---|
| Admin Monitoring | `/admin/monitoring` | Inline hardcoded title/description in `web.php`. Not using SeoHelper. |
| Admin Models | `/admin/models` | No SEO metadata passed at all |
| Admin API Settings | `/admin/api-settings` | No SEO metadata passed at all |
| Landing Template 1 | `/landing/1` | No SeoHelper call in route closure |
| Landing Template 2 | `/landing/2` | No SeoHelper call in route closure |
| Landing Template 3 | `/landing/3` | SeoHelper key `landing.3` exists but route closure does not use it |
| Blog | `/blog` | SeoHelper key `blog` exists but no route defined |

### Missing from robots.txt Allow lists

| Path | Notes |
|---|---|
| `/about` | Public page, not listed for any agent |
| `/contact` | Public page, not listed for any agent |
| `/privacy-policy` | Public legal page, not listed |
| `/terms-of-service` | Public legal page, not listed |
| `/dedicated-server` | Marketing page, not listed for any agent |
| `/vs-ollama` | Listed in robots.txt but no route exists in `web.php` |

### OG description fallback is empty string

When `$pageDescription` is not passed, the OG and Twitter description tags render as empty strings (`content=""`). The global `<meta name="description">` has a proper fallback but `og:description` and `twitter:description` do not. Consider unifying:

```blade
{{-- Current (line 11): --}}
<meta property="og:description" content="{{ $pageDescription ?? '' }}">

{{-- Recommended: --}}
<meta property="og:description" content="{{ $pageDescription ?? 'Affordable OpenAI-compatible LLM API with 45+ models.' }}">
```

### `docs.blade.php` is a standalone template

`resources/views/docs.blade.php` does NOT extend `layouts.app`. It has its own `<!DOCTYPE html>` and `<head>`. It therefore does not benefit from the global Organization schema, GA tag, or the canonical link tag in `layouts/app.blade.php`. The page receives `$pageTitle` etc. from the route closure but renders its own head using `@lang('docs.documentation') — LLM Resayil` as the hardcoded title pattern, ignoring `$pageTitle`.

### No sitemap.xml found

`robots.txt` declares `Sitemap: https://llm.resayil.io/sitemap.xml` but no sitemap generation package or static file was found in this audit. This should be verified — if the file does not exist at that URL, Googlebot will report an error in Search Console.

### Social accounts declared in schema may not exist

The Organization schema lists:
- `https://twitter.com/LLMResayil`
- `https://facebook.com/LLMResayil`

These should be verified as active, owned accounts.
