# Phase 11 Execution Guide

**For:** `/gsd:execute-phase` orchestrator and Claude executor
**Phase:** 11-content-technical-seo
**Plans:** 4 (01, 02, 03, 04)
**Waves:** 2 (parallel → sequential)

---

## Quick Start

### Wave 1 Execution (Parallel)
```bash
/gsd:execute-phase 11 --plans 01,02
```

Expected runtime: 2-3 hours (combined)
- Plan 01 (Docs): ~1.5-2 hours (7 templates, 2,500+ words)
- Plan 02 (Hreflang): ~1-1.5 hours (1 component, 20+ templates)

### Wave 2 Execution (After Wave 1)
```bash
/gsd:execute-phase 11 --plans 03,04
```

Expected runtime: 2-3 hours (combined)
- Plan 03 (Images): ~1.5-2 hours (50+ images, 8 pages)
- Plan 04 (FAQ/Features): ~1-1.5 hours (2 pages, schema validation)

### Full Phase (Sequential)
```bash
/gsd:execute-phase 11
```

Executes all 4 plans in wave order (01+02 parallel, then 03+04 parallel).
Expected runtime: 4-6 hours total.

---

## Pre-Execution Checklist

- [ ] Phase 10 (SEO Foundation) is COMPLETE and DEPLOYED to prod
- [ ] Current branch: `dev`
- [ ] `.planning/phases/11-content-technical-seo/` directory exists
- [ ] All 4 PLAN.md files present (11-01, 11-02, 11-03, 11-04)
- [ ] Project environment: `.env` loaded with `APP_URL`, `APP_DEBUG=false`
- [ ] Database: Current dev migrations applied
- [ ] Routes: No conflicts with existing /faq, /features, /docs routes
- [ ] Design system: Tailwind CSS configured, dark luxury colors available

### Critical Dependencies

**Phase 10 Must Be Complete:**
- SeoHelper.php exists (for schema generation utilities)
- Google Analytics tag present in layouts
- OG images directory: `/public/og-images/` (20+ images)
- Responsive Tailwind layout patterns established

**Project Structure:**
- Routes: `routes/web.php` (main site routes)
- Controllers: `app/Http/Controllers/PageController.php`
- Views: `resources/views/pages/`, `resources/views/docs/`, `resources/views/layouts/`
- Assets: Tailwind CSS, Inter font (Latin), Tajawal font (Arabic)

---

## Execution Workflow

### Plan 01: Documentation Expansion

**Duration:** 1.5-2 hours
**Autonomy:** Fully autonomous (no checkpoints)

**Task Sequence:**
1. Create /docs landing page (1 route + 1 template)
2. Create /docs/getting-started (1 route + 1 template + code examples)
3. Create /docs/authentication (1 route + 1 template + code examples)
4. Create /docs/models (1 route + 1 template)
5. Create /docs/billing (1 route + 1 template)
6. Create /docs/rate-limits (1 route + 1 template)
7. Create /docs/error-codes (1 route + 1 template)
8. Add breadcrumb JSON-LD schema to all 7 pages

**Execution Notes:**
- All routes added to `routes/web.php` at once (or per-task)
- All controller methods added to `PageController.php`
- Each Blade template: 300-450 words, dark luxury styling
- Code examples: cURL, JavaScript, Python (not pseudocode)
- Breadcrumb schema: `@php $schema = [...] @endphp` before `</head>`

**Verification During Execution:**
```bash
# After routes/controller added:
curl -s http://llmdev.resayil.io/docs | grep -q "Documentation" && echo "✓"

# After schema added:
curl -s http://llmdev.resayil.io/docs/getting-started | grep -q "BreadcrumbList" && echo "✓"
```

**Common Issues & Fixes:**
- Route not found → Check routes/web.php syntax, Route::get() with correct namespace
- Template not rendering → Check view path in controller (view('pages.docs'))
- Styling broken → Verify Tailwind classes (bg-\[#0f1115\], text-\[#d4af37\])
- Schema not showing → Check JSON-LD placement in `<head>`, not in body

---

### Plan 02: Hreflang Implementation

**Duration:** 1-1.5 hours
**Autonomy:** Fully autonomous (no checkpoints)

**Task Sequence:**
1. Create hreflang Blade component (resources/views/components/hreflang.blade.php)
2. Add to app.blade.php (authenticated pages: dashboard, docs, admin, billing, profile)
3. Add to welcome.blade.php (public landing, isXDefault=true)
4. Add to landing/3.blade.php (consumer landing, isXDefault=true)
5. Add to auth pages (login, register, otp)
6. Add to /docs pages (all 7 pages inherit from app.blade.php, verify)
7. Add to marketing pages (cost-calculator, comparison, alternatives, dedicated-server)
8. Add to user dashboard pages (dashboard/index, dashboard/billing)
9. Add to admin dashboard
10. Verify hreflang on all 28+ pages

**Execution Notes:**
- Component should handle absolute URLs: `url()->to()` or `route()`
- Accept props: `$currentPath`, `$locale`, `$isXDefault`
- Output 4 link tags: hreflang="en", hreflang="ar", hreflang="x-default" (if set), hreflang="{current}"
- x-default only on landing pages (/, /landing/3)
- No hardcoded URLs (use helpers)

**Component Template:**
```blade
@props(['currentPath' => request()->path(), 'locale' => app()->getLocale(), 'isXDefault' => false])

@php
    $enUrl = url()->to(str_replace('/ar', '', $currentPath));
    $arUrl = str_contains($currentPath, '/ar')
        ? url()->to($currentPath)
        : url()->to($currentPath . (str_contains($currentPath, '?') ? '&lang=ar' : '?lang=ar'));

    $currentUrl = url()->current();
@endphp

<link rel="alternate" hreflang="en" href="{{ $enUrl }}" />
<link rel="alternate" hreflang="ar" href="{{ $arUrl }}" />
@if ($isXDefault)
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}" />
@endif
<link rel="alternate" hreflang="{{ $locale }}" href="{{ $currentUrl }}" />
```

**Verification During Execution:**
```bash
# Check component exists:
grep -q "@props" resources/views/components/hreflang.blade.php && echo "✓"

# Check added to app.blade.php:
grep -q "x-hreflang" resources/views/layouts/app.blade.php && echo "✓"

# Check on actual pages:
curl -s http://llmdev.resayil.io/docs | grep -c "hreflang" # Should be 3-4
curl -s http://llmdev.resayil.io/ | grep "x-default" && echo "✓"
```

**Common Issues & Fixes:**
- Component not found → Check file location (resources/views/components/hreflang.blade.php)
- Hreflang not showing → Verify @include or <x-hreflang> in template
- Broken URLs in hreflang → Check route() calls, env('APP_URL') present
- x-default on wrong pages → Only on / and /landing/3
- Duplicate hreflang tags → app.blade.php only (not repeated in child templates)

---

### Plan 03: Image Optimization

**Duration:** 1.5-2 hours
**Autonomy:** Fully autonomous (no checkpoints)
**Depends on:** Plan 01 (for /docs images created)

**Task Sequence:**
1. Audit welcome.blade.php, add alt text to 10-12 images
2. Audit landing/3.blade.php, add alt text to 8-10 images
3. Audit /docs pages, add alt text to 4-6 images
4. Audit cost-calculator.blade.php, add alt text to 6-8 images
5. Audit comparison.blade.php, add alt text to 8-10 images
6. Audit alternatives.blade.php, add alt text to 10-15 images
7. Audit dedicated-server.blade.php, add alt text to 8-10 images
8. Audit dashboard/index.blade.php, add alt text to 3-5 images
9. Verify all 50+ images, validate accessibility with WAVE/aXe

**Execution Notes:**
- Audit first: Use `grep -n "<img" file.blade.php` to find all images
- Alt text guidelines: 50-125 characters, semantic (no "image of..."), context-specific
- Process: For each `<img>`, add or update `alt="..."` attribute
- Validation: WAVE browser extension (0 missing alt text errors)

**Alt Text Formula:**
```
[Object/Action] + [Context/Benefit]

Examples:
- "LLM Resayil dashboard showing real-time API usage statistics" (hero)
- "Icon representing API key authentication with lock symbol" (icon)
- "Chart comparing costs across different API usage scenarios" (chart)
```

**Verification During Execution:**
```bash
# Count alt attributes per page:
grep -o 'alt="[^"]*"' resources/views/welcome.blade.php | wc -l

# Check for bad patterns:
grep -i 'image of\|picture of' resources/views/**/*.blade.php # Should return 0

# Total across all pages:
find resources/views -name "*.blade.php" | xargs grep -o 'alt="' | wc -l # Should be 50+
```

**Common Issues & Fixes:**
- Alt text is empty → Use semantic pattern above
- Alt text says "image of..." → Remove "image of", keep description
- Alt text too long → Trim to 125 characters max
- Images missing (not in template) → Note in SUMMARY (no alt needed)
- WAVE reports errors → Fix per error (missing alt, color contrast, etc.)

---

### Plan 04: FAQ & Features Pages

**Duration:** 1-1.5 hours
**Autonomy:** Fully autonomous (no checkpoints)
**Depends on:** Plan 01 (for navigation cross-links)

**Task Sequence:**
1. Create /faq route and PageController::faq() method
2. Create /features route and PageController::features() method
3. Create faq.blade.php (12-15 FAQ items, 100-200 words each, FAQPage schema)
4. Create features.blade.php (6-8 features, 150-250 words each, ProductFeature schema)
5. Add navigation links to /faq and /features (main nav, cross-page links)
6. Validate FAQPage and ProductFeature schemas (schema.org, Google Rich Results)
7. Test mobile responsiveness and accessibility (Lighthouse, WAVE)

**Execution Notes:**
- FAQ items: Substantial answers, not one-liners. 100-200 words per item.
- Features: Detailed descriptions with benefits. 150-250 words per feature.
- Schema: JSON-LD in `<head>`, `@json()` helper, validate early
- Navigation: Add to app.blade.php nav + cross-page links from welcome, landing/3, cost-calculator
- Mobile: Tailwind responsive grid, test on small screens

**FAQ Schema Structure:**
```php
@php
$faqs = [
    ['question' => 'How do I get started?', 'answer' => 'Full answer text (100+ words)...'],
    // ... 12-15 items
];

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($item) => [
        '@type' => 'Question',
        'name' => $item['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item['answer']
        ]
    ], $faqs)
];
@endphp

<script type="application/ld+json">@json($schema)</script>
```

**Features Schema Structure:**
```php
@php
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => 'LLM Resayil API',
    'hasFeature' => [
        [
            '@type' => 'PropertyValue',
            'name' => 'Feature Name',
            'description' => 'Feature description (150+ words)...'
        ],
        // ... 6-8 features
    ]
];
@endphp

<script type="application/ld+json">@json($schema)</script>
```

**Verification During Execution:**
```bash
# Routes exist:
curl -s http://llmdev.resayil.io/faq | head -1 && echo "✓"
curl -s http://llmdev.resayil.io/features | head -1 && echo "✓"

# Schema present:
curl -s http://llmdev.resayil.io/faq | grep -q "FAQPage" && echo "✓"
curl -s http://llmdev.resayil.io/features | grep -q "ProductFeature\|hasFeature" && echo "✓"

# Item counts:
curl -s http://llmdev.resayil.io/faq | grep -o '"question"' | wc -l # Should be 12+
curl -s http://llmdev.resayil.io/features | grep -o '"name"' | wc -l # Should be 6+
```

**Common Issues & Fixes:**
- Routes not found → Check web.php syntax, Route::get('/faq', ...)
- Template not rendering → Check view path (view('pages.faq'), view('pages.features'))
- Schema validation errors → Use schema.org validator, check @json() formatting
- Mobile layout broken → Verify Tailwind grid classes, test on small screen
- Navigation links missing → Add to app.blade.php nav and cross-page templates

---

## Testing & Validation

### Per-Plan Verification

**Plan 01 (Docs):**
- [ ] All 7 routes return 200 status
- [ ] Total word count: 2,500+ across all pages
- [ ] Code examples on 4+ pages (cURL, JavaScript, Python)
- [ ] Breadcrumb schema validates (0 errors)
- [ ] Mobile responsive (1 column on small, multi-column on large)

**Plan 02 (Hreflang):**
- [ ] Component created and reusable
- [ ] All 28+ pages include hreflang tags
- [ ] Lang codes valid (en, ar)
- [ ] URLs absolute (https://..., not relative)
- [ ] No broken hreflang links (all 200 status)
- [ ] Landing pages have x-default
- [ ] Mutual references (EN ↔ AR)

**Plan 03 (Images):**
- [ ] 50+ images audited
- [ ] All have semantic alt text (no "image of...")
- [ ] Alt text: 50-125 characters
- [ ] WAVE: 0 missing alt text errors
- [ ] Lighthouse Accessibility: 90+
- [ ] Mobile layout intact

**Plan 04 (FAQ/Features):**
- [ ] /faq and /features routes exist
- [ ] 12-15 FAQ items with 100-200 word answers
- [ ] 6-8 features with 150-250 word descriptions
- [ ] FAQPage schema: 0 errors
- [ ] ProductFeature schema: 0 errors
- [ ] Google Rich Results: FAQ and Product cards recognized
- [ ] Mobile responsive, Lighthouse 90+

### Full Phase Validation

```bash
# After all 4 plans complete:

# Routes check:
for route in docs docs/getting-started docs/authentication docs/models \
             docs/billing docs/rate-limits docs/error-codes faq features; do
  curl -s "http://llmdev.resayil.io/$route" | head -1 && echo "✓ /$route"
done

# Hreflang check (sample):
curl -s http://llmdev.resayil.io/ | grep -c "hreflang" && echo "✓ Hreflang on landing"
curl -s http://llmdev.resayil.io/docs | grep -c "hreflang" && echo "✓ Hreflang on /docs"

# Alt text check (sample):
curl -s http://llmdev.resayil.io/ | grep -c 'alt="' && echo "✓ Alt text on welcome"

# Schema check (sample):
curl -s http://llmdev.resayil.io/faq | grep -q "FAQPage" && echo "✓ FAQPage schema"
curl -s http://llmdev.resayil.io/features | grep -q "ProductFeature" && echo "✓ ProductFeature schema"
```

---

## Deployment Checklist

### Before Deployment
- [ ] All 4 plans executed and verified
- [ ] No 404s in cross-page links
- [ ] Schema validation: 0 errors on all new pages
- [ ] Mobile responsive verified
- [ ] Accessibility: Lighthouse 90+

### Development Deployment
```bash
# Commit to dev branch:
git add -A
git commit -m "feat(11): content & technical SEO - docs expansion, hreflang, images, faq/features"

# Push to dev:
git push origin dev

# Deploy to staging:
ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"

# Verify on dev:
curl -s http://llmdev.resayil.io/faq | head -1
```

### Production Deployment
```bash
# Only after user approval & dev verification:
git checkout main
git merge dev
git push origin main

# Deploy to prod:
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"

# Verify on prod:
curl -s https://llm.resayil.io/faq | head -1

# Tag release:
git tag v1.x.0
git push origin --tags
```

---

## Monitoring Post-Execution

### Immediate (1-2 hours)
- [ ] All routes responding (no 500 errors)
- [ ] Links working (no 404s)
- [ ] Page renders correctly (no styling broken)

### Short-term (1-7 days)
- [ ] Google Search Console: New URLs indexed
- [ ] Rich Results Test: FAQ and Product cards recognized
- [ ] Mobile: Pages render correctly on mobile devices

### Medium-term (1-2 weeks)
- [ ] FAQ pages appear in "People also ask"
- [ ] /faq and /features in Google index
- [ ] Docs pages ranking for relevant queries
- [ ] Image search indexing improved (50+ alt text images)

### Metrics to Track
- Google Search Console: Indexed pages, coverage, mobile usability
- Rich Results: FAQ cards, Product cards appearing in SERPs
- Analytics: Traffic to /faq, /features, /docs pages
- Images: Image search impressions and clicks
- Backlinks: Any natural backlinks to new pages

---

## Rollback Plan (If Needed)

### Quick Rollback
```bash
# If critical issues on prod:
git revert HEAD  # Reverts the merge commit
git push origin main

ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"

# Remove tag:
git tag -d v1.x.0
git push origin --delete v1.x.0
```

### Partial Rollback (If One Plan Fails)
```bash
# If Plan 03 (images) has issues but others are fine:
git revert <commit-sha-plan-03>
git push origin dev

# Deploy without Plan 03:
ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
```

### Debug & Fix
```bash
# If 404s found:
- Check routes/web.php syntax
- Verify controller methods exist
- Test with: curl -v http://llmdev.resayil.io/faq

# If styling broken:
- Verify Tailwind CSS loaded
- Check color vars (--gold, --bg-card)
- Test on fresh browser session (clear cache)

# If schema invalid:
- Use schema.org validator
- Check @json() formatting
- Verify all required fields present
```

---

## Executor Notes

### Key Mindset
- Plans are prompts, not documents
- Execution is autonomous (no asking for clarification)
- Verification is immediate (after each task, not at end)
- Context 50% budget: 4-6 hours execution fits comfortably in single window

### Quality Assurance
- Code examples must work (test cURL commands)
- Alt text must be semantic (not generic)
- Schema must validate (use real validators)
- Mobile must work (test on emulator or device)
- Accessibility must pass (Lighthouse 90+)

### File Organization
- All new .blade.php files go to `resources/views/pages/` or `resources/views/docs/`
- All routes go to `routes/web.php`
- All controller methods go to `app/Http/Controllers/PageController.php`
- New component goes to `resources/views/components/hreflang.blade.php`

### Contingencies
- If word count is hard to hit: Each section can be 200-250 words (not just 100-150)
- If schema validation is slow: Validate on 5-10 pages (not all 50+)
- If Lighthouse is under 90: Focus on critical issues (color contrast, missing alt)
- If time is short: Defer optional items (isXDefault on all pages, all image audits)

---

**Ready for execution. Questions? Ask before starting.**
