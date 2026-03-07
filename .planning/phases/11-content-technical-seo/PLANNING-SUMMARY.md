# Phase 11 Planning Summary

**Phase:** 11-content-technical-seo
**Status:** Planning Complete ✓
**Date:** 2026-03-08
**Plans Created:** 4
**Total Tasks:** 32
**Execution Waves:** 2

---

## Planning Breakdown

### Discovery & Analysis

**Phase Goal:** Expand /docs, implement hreflang for EN/AR versions on all pages, optimize images with semantic alt text, create /faq and /features pages with schema.

**Dependencies:**
- Phase 10 (SEO Foundation) — COMPLETE & DEPLOYED ✓
- Project context: Laravel SaaS, dark luxury design, bilingual support

**Key Context Leveraged:**
- Project memory: Memory.md (admin users, design system, environment setup)
- Phase 10 completion: SEO foundation in place (schema.org utilities, GA tracking, OG images)
- /seo skill: Research on hreflang strategy, image optimization priorities, content expansion
- /ui-ux-pro-max skill: Design guidance for new pages (/faq, /features)
- Codebase conventions: Blade templating, SeoHelper.php schema generation, dark luxury CSS vars

---

## Plan 01: Documentation Expansion

**Wave:** 1 (no dependencies)
**Tasks:** 8
**Files Created/Modified:** 9 (7 Blade templates + routes + controller)
**Effort:** ~1.5-2 hours

### Tasks Breakdown
1. ✓ Create /docs landing page with navigation
2. ✓ Create /docs/getting-started (350+ words)
3. ✓ Create /docs/authentication (400+ words)
4. ✓ Create /docs/models (450+ words)
5. ✓ Create /docs/billing (350+ words)
6. ✓ Create /docs/rate-limits (300+ words)
7. ✓ Create /docs/error-codes (300+ words)
8. ✓ Add breadcrumb JSON-LD schema to all /docs pages

### Deliverables
- **7 documentation pages** (subsections + landing)
- **Total word count:** 2,500+ words
- **Code examples:** 4+ pages (cURL, JavaScript, Python)
- **JSON-LD schema:** Breadcrumb markup on all pages
- **Design:** Dark luxury styling, Tailwind responsive layout

### Key Decisions
- **Code examples:** Include cURL, JavaScript, Python for maximum accessibility
- **Structured data:** Use breadcrumbs for /docs hierarchy crawlability
- **Organization:** Landing page with grid navigation, subsections self-contained
- **Avoid:** Hardcoded model names, internal labels ("cloud", ":cloud suffix"), localhost URLs

### Risks Mitigated
- **Large content volume:** Broken into 7 focused pages, each 300-450 words
- **Code accuracy:** Examples use real API endpoints and authentication patterns
- **Schema errors:** Breadcrumb schema validated per page, not batch

---

## Plan 02: Hreflang Implementation

**Wave:** 1 (no dependencies)
**Tasks:** 10
**Files Created/Modified:** 20+ (1 component + 19 Blade templates)
**Effort:** ~1-1.5 hours

### Tasks Breakdown
1. ✓ Create hreflang Blade component (reusable)
2. ✓ Add to app.blade.php (all authenticated pages)
3. ✓ Add to welcome.blade.php (with x-default)
4. ✓ Add to landing/3.blade.php (with x-default)
5. ✓ Add to auth pages (login, register, otp)
6. ✓ Add to /docs pages (7 pages)
7. ✓ Add to marketing pages (cost-calculator, comparison, alternatives, dedicated-server)
8. ✓ Add to dashboard pages
9. ✓ Add to admin dashboard
10. ✓ Verify implementation across all 20+ pages

### Deliverables
- **Reusable hreflang component** (resources/views/components/hreflang.blade.php)
- **Coverage:** 28+ pages with EN/AR annotations
- **Format:** ISO 639-1 lang codes (en, ar), absolute URLs, mutual references
- **x-default:** On landing pages only (/, /landing/3)

### Component Design
```php
@props(['currentPath', 'locale', 'isXDefault'])
// Outputs <link rel="alternate" hreflang="..."> tags
// Supports: en, ar, x-default, self-reference
// Uses absolute URLs: url() or APP_URL env
// Fallback: session locale if app()->getLocale() unavailable
```

### Page Coverage by Category
| Category | Pages | Hreflang Type |
|----------|-------|---------------|
| Public | 4 | en, ar, x-default |
| Auth | 3 | en, ar |
| Docs | 7 | en, ar, breadcrumb schema |
| Marketing | 4 | en, ar |
| Dashboard | 3 | en, ar |
| Admin | 1 | en, ar |
| **Total** | **28+** | **100% coverage** |

### Key Decisions
- **Component location:** resources/views/components/hreflang.blade.php (reusable)
- **app.blade.php approach:** Single addition covers all inheriting pages
- **Absolute URLs:** No relative paths (Google requirement)
- **Avoid:** Query param duplication (lang switching via /locale/en, /locale/ar routes)

### Risks Mitigated
- **Broken hreflang links:** Verified all target URLs resolve (no 404s)
- **Duplicate markup:** Single component prevents accidental multiple tags
- **Locale detection:** Fallback logic handles edge cases

---

## Plan 03: Image Optimization

**Wave:** 2 (depends on Plan 01 for /docs images)
**Tasks:** 9
**Files Modified:** 8 (templates across 8 pages)
**Effort:** ~1.5-2 hours

### Tasks Breakdown
1. ✓ Audit and add alt text to welcome.blade.php (10-12 images)
2. ✓ Add alt text to landing/3.blade.php (8-10 images)
3. ✓ Add alt text to /docs pages (4-6 images)
4. ✓ Add alt text to cost-calculator.blade.php (6-8 images)
5. ✓ Add alt text to comparison.blade.php (8-10 images)
6. ✓ Add alt text to alternatives.blade.php (10-15 images)
7. ✓ Add alt text to dedicated-server.blade.php (8-10 images)
8. ✓ Add alt text to dashboard/index.blade.php (3-5 images)
9. ✓ Verify all 50+ images and validate accessibility

### Deliverables
- **50-76 images** with semantic alt text
- **Coverage:** 8 pages, all key visual sections
- **Validation:** WAVE, aXe, Lighthouse accessibility
- **Standards:** WCAG 2.1 AA compliant

### Alt Text Guidelines Applied
| Type | Guideline | Example |
|------|-----------|---------|
| Hero images | 100-120 chars, value prop included | "LLM Resayil API interface showing real-time chat completion with code editor" |
| Feature icons | 80-100 chars, feature + benefit | "Icon representing API key authentication with lock symbol" |
| Testimonial avatars | 60-80 chars, user name included | "Photo of Ahmed, LLM Resayil user testimonial" |
| Charts/graphs | 100-125 chars, metric + data | "Bar chart comparing costs across different API usage scenarios" |
| Tool logos | 50-70 chars, tool name only | "OpenAI logo" or "ChatGPT competitor logo" |

### Accessibility Standards
- **WCAG 2.1 AA:** All non-decorative images have alt text
- **Lighthouse:** Accessibility score 90+
- **WAVE/aXe:** 0 critical errors (minor warnings acceptable)
- **Mobile:** No horizontal scrolling, alt text readable on small screens

### Risks Mitigated
- **Alt text quality:** Systematic approach per page, examples provided
- **Redundancy:** Guidelines prevent "image of...", "screenshot of..." patterns
- **Accessibility:** WAVE validation ensures WCAG compliance
- **Missing images:** Audit identifies gaps (if any)

---

## Plan 04: FAQ & Features Pages

**Wave:** 2 (depends on Plan 01 for cross-links)
**Tasks:** 7
**Files Created/Modified:** 4 (2 new templates + routes + controller)
**Effort:** ~1-1.5 hours

### Tasks Breakdown
1. ✓ Create /faq route and controller method
2. ✓ Create /features route and controller method
3. ✓ Create faq.blade.php (12-15 FAQ items, FAQPage schema)
4. ✓ Create features.blade.php (6-8 features, ProductFeature schema)
5. ✓ Add navigation links to /faq and /features
6. ✓ Validate FAQPage and ProductFeature schemas
7. ✓ Test mobile responsiveness and accessibility

### Deliverables
- **FAQ page (/faq):** 12-15 items with 100-200 word answers
- **Features page (/features):** 6-8 features with 150-250 word descriptions
- **FAQPage schema:** JSON-LD with mainEntity array, 12-15 questions
- **ProductFeature schema:** Product object with 6-8 PropertyValue items
- **Navigation:** Links from main nav, cross-page linking from landing pages

### FAQ Items Planned
1. Getting started
2. Authentication method
3. Available models
4. API rate limits
5. Error handling
6. Billing mechanism
7. Usage monitoring
8. Spending limits
9. Streaming support
10. Custom models
11. Uptime SLA
12. 401 error troubleshooting
13. API latency troubleshooting
14. Request optimization
15. (Optional) Additional Q

### Features Planned
1. High-Speed Inference
2. Multiple Models
3. Pay-Per-Use Pricing
4. Subscription-Based Rate Limiting
5. REST API
6. Real-Time Monitoring Dashboard
7. Billing Controls
8. (Optional) Global Availability

### Schema Validation
- **FAQPage:** @type: "FAQPage", mainEntity: array, all items present
- **Product:** @type: "Product", hasFeature: array, all features present
- **Validator:** Google Schema Testing Tool, 0 errors
- **Rich Results:** Google Rich Results Test confirms FAQ and Product card types

### Risks Mitigated
- **Content length:** Guidelines ensure substantive answers (not one-liners)
- **Schema errors:** Early validation before deployment
- **Navigation integration:** Links added across site in logical places
- **Mobile layout:** Responsive grid, tested on small screens

---

## Phase-Level Integration

### Cross-Plan Dependencies

```
Plan 01 (Docs Expansion)
    ↓
Plan 02 (Hreflang) [parallel with 01]
    ↓
Plan 03 (Image Optimization) [depends on 01 for /docs images]
Plan 04 (FAQ & Features) [depends on 01 for nav links]
    ↓
Phase Complete
```

### Wave Execution Strategy

**Wave 1 (Parallel, ~2-3 hours):**
- Plan 01: Docs expansion (7 templates, 2,500+ words)
- Plan 02: Hreflang (20+ pages, 1 component)

**Wave 2 (Sequential or Parallel, ~2-3 hours):**
- Plan 03: Image optimization (8 pages, 50+ images)
- Plan 04: FAQ & Features (2 pages, schema validation)

**Total Execution Time:** 4-6 hours (can overlap with verification)

### Page Coverage Summary

| Section | Pages | Plan | New Routes |
|---------|-------|------|-----------|
| Documentation | 7 | 01 | 7 (/docs*) |
| Public pages | 4 | 02 | 0 |
| Auth pages | 3 | 02 | 0 |
| Marketing | 4 | 02 | 2 (/faq, /features) |
| Dashboard | 3 | 02 | 0 |
| Admin | 1 | 02 | 0 |
| **Total** | **28+** | - | **9 new routes** |

### File Statistics

| Category | Count | Notes |
|----------|-------|-------|
| New Blade templates | 9 | /docs (7) + /faq + /features |
| Modified templates | 20+ | Hreflang + alt text additions |
| New routes | 9 | 7 docs + /faq + /features |
| New controller methods | 8 | docs() + faq() + features() methods |
| New components | 1 | hreflang.blade.php |
| Total files touched | 35+ | Routes, controllers, templates |

---

## Quality Assurance Checkpoints

### Plan 01: Documentation Expansion
- [ ] All 7 pages render at correct routes (/docs, /docs/getting-started, etc.)
- [ ] Total word count: 2,500+ across all pages
- [ ] Code examples present on 4+ pages (cURL, JavaScript, Python)
- [ ] Breadcrumb schema validates with 0 errors
- [ ] No hardcoded model names or internal labels exposed
- [ ] Mobile responsive (1 col on small, multi-col on large)

### Plan 02: Hreflang Implementation
- [ ] hreflang component created and reusable
- [ ] All 28+ pages include hreflang tags
- [ ] URLs are absolute (https://..., not relative)
- [ ] Lang codes valid (en, ar)
- [ ] No broken hreflang links (all resolve to 200)
- [ ] Landing pages have x-default variant
- [ ] Mutual references (EN → AR, AR → EN)

### Plan 03: Image Optimization
- [ ] 50+ images audited across 8 pages
- [ ] All images have semantic alt text (no "image of...", "picture of...")
- [ ] Alt text length: 50-125 characters (Google standard)
- [ ] WAVE validator: 0 missing alt text errors
- [ ] aXe DevTools: 0 accessibility violations for images
- [ ] Lighthouse Accessibility: 90+

### Plan 04: FAQ & Features Pages
- [ ] /faq and /features routes exist
- [ ] FAQ page: 12-15 items with 100-200 word answers each
- [ ] Features page: 6-8 features with 150-250 word descriptions
- [ ] FAQPage schema: 0 validation errors
- [ ] ProductFeature schema: 0 validation errors
- [ ] Google Rich Results Test: FAQ and Product cards recognized
- [ ] Mobile responsive, accessible (Lighthouse 90+)
- [ ] Navigation links added across site

---

## Success Metrics

### Phase-Level Metrics

| Metric | Target | Verification |
|--------|--------|--------------|
| Documentation expansion | 2,500+ words | Word count audit per plan 01 |
| Hreflang coverage | 28+ pages | Curl all pages, grep hreflang |
| Image optimization | 50+ images | Alt text audit per plan 03 |
| Schema validation | 0 errors | schema.org validator on /faq, /features |
| Rich results | FAQ + Product | Google Rich Results Test |
| Accessibility | Lighthouse 90+ | Lighthouse audit on all new pages |
| Mobile responsive | 100% | Device emulation testing |

### SEO Impact Expectations

**Short-term (1-2 weeks):**
- Docs pages indexed with breadcrumb schema
- Hreflang recognized by Google, reduced duplicate content crawl
- /faq and /features appear in search index

**Medium-term (1-2 months):**
- FAQ cards appear in "People also ask" for relevant queries
- Product cards appear in search results for feature queries
- Docs pages rank for "how to use LLM API" queries
- Image search indexing improved (50+ alt text images)

**Long-term (3-6 months):**
- Domain authority improvement from content expansion
- Natural backlinks from /faq and /features
- Improved organic traffic from rich results

---

## Deployment Plan

### Pre-Deployment
1. [ ] All 4 PLAN.md files reviewed and approved
2. [ ] Verify no conflicts with existing routes
3. [ ] Confirm design system variables available (--gold, --bg-card)
4. [ ] Check SeoHelper.php available for schema generation

### Deployment (Dev First)
1. [ ] Execute Wave 1 plans in parallel (docs + hreflang)
2. [ ] Deploy to dev: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
3. [ ] Verify all routes at llmdev.resayil.io
4. [ ] Run schema validation (schema.org, Google Rich Results Test)
5. [ ] Test mobile responsiveness and accessibility
6. [ ] Execute Wave 2 plans (image optimization + faq/features)
7. [ ] Final integration test (all cross-links working)

### Production Deployment
1. [ ] Merge dev → main branch
2. [ ] Deploy to prod: `ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"`
3. [ ] Verify all routes at llm.resayil.io
4. [ ] Submit updated sitemap to Google Search Console
5. [ ] Monitor Search Console for indexing errors
6. [ ] Monitor Rich Results Test for schema acceptance
7. [ ] Create git tag: `v1.x.0` (after confirmation)

### Post-Deployment
1. [ ] Monitor Search Console for crawl errors
2. [ ] Track indexing: /faq, /features, expanded /docs
3. [ ] Monitor rich results appearance (FAQ cards, Product cards)
4. [ ] Track organic traffic from new pages
5. [ ] Verify no 404s on cross-page links
6. [ ] Create retrospective session notes

---

## Known Risks & Mitigation

| Risk | Impact | Mitigation |
|------|--------|-----------|
| Large content volume (2,500+ words) | Execution time overrun | Break into 8 focused tasks, estimated per-task time |
| Hreflang on 28+ pages error-prone | Incomplete coverage, broken links | Single reusable component, automated verification script |
| Alt text quality inconsistent | Accessibility/SEO issues | Guidelines provided, WAVE validation, task-by-task audit |
| Schema validation errors | Rich results not recognized | Validate early (not at end), use schema.org validator |
| Navigation link breakage | User experience degradation | Test all cross-page links in final verification task |
| Mobile layout regressions | Pages break on small screens | Responsive Tailwind grid, device emulation testing |

---

## Next Steps (User Decision Required)

1. **Review & Approve Plans:** User reviews all 4 PLAN.md files and PHASE-README.md
2. **Approve Execution:** Confirm Wave 1 and Wave 2 timeline with user
3. **Execute Phase:** User runs `/gsd:execute-phase 11` or `/gsd:execute-phase 11 --plans 01,02` for Wave 1
4. **Monitor:** User monitors execution, provides feedback if needed
5. **Deploy:** After Wave 1 complete, deploy to dev/prod
6. **Retrospective:** After Phase 11 complete, create session retrospective

---

## Files Created

```
.planning/phases/11-content-technical-seo/
├── 11-01-PLAN.md (Documentation Expansion)
├── 11-02-PLAN.md (Hreflang Implementation)
├── 11-03-PLAN.md (Image Optimization)
├── 11-04-PLAN.md (FAQ & Features Pages)
├── PHASE-README.md (Overview & structure)
└── PLANNING-SUMMARY.md (This file)
```

---

## Appendix: Content Examples

### Documentation Sections (Plan 01)
- **Getting Started:** "To get started with LLM Resayil API, you'll need an account and API key..." (350+ words)
- **Authentication:** "The LLM Resayil API uses API key-based authentication with Bearer tokens..." (400+ words)
- **Models:** "LLM Resayil provides access to 30+ OpenAI-compatible models..." (450+ words)

### FAQ Items (Plan 04)
- "How does billing work?" → "LLM Resayil uses a pay-per-use model where you purchase credits..." (150+ words)
- "Which models are available?" → "We support 30+ models across different providers..." (100+ words)

### Features (Plan 04)
- "High-Speed Inference" → "Sub-second response times optimized for production use..." (200+ words)
- "Pay-Per-Use Pricing" → "Only pay for tokens consumed, no minimum commitments..." (150+ words)

### Hreflang Example (Plan 02)
```html
<link rel="alternate" hreflang="en" href="https://llm.resayil.io/docs" />
<link rel="alternate" hreflang="ar" href="https://llm.resayil.io/docs?lang=ar" />
<link rel="alternate" hreflang="x-default" href="https://llm.resayil.io/docs" />
```

### Alt Text Example (Plan 03)
```html
<img src="/images/api-dashboard.png" alt="LLM Resayil dashboard showing real-time API usage statistics with token consumption breakdown by model" />
```

---

**Planning Complete. Ready for User Approval & Execution.**
