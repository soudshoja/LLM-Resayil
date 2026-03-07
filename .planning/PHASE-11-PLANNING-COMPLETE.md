# Phase 11: Content & Technical SEO — Planning Complete ✅

**Date:** 2026-03-08
**Status:** PLANNING COMPLETE AND READY FOR EXECUTION
**Plans Created:** 4 (01-Documentation, 02-Hreflang, 03-Images, 04-FAQ/Features)
**Total Tasks:** 32
**Total Effort:** 4-6 hours execution time

---

## 📋 Planning Summary

Phase 11 planning is **COMPLETE**. All 4 PLAN.md files have been created with comprehensive task breakdowns, dependencies, and verification criteria.

### Files Created

Location: `.planning/phases/11-content-technical-seo/`

1. **README.md**
   - Quick navigation, file manifest, success metrics
   - Start here for orientation

2. **PHASE-README.md**
   - Phase overview, goals, wave structure
   - Plan summaries, deliverables
   - Deployment checklist, post-execution checklist

3. **PLANNING-SUMMARY.md**
   - Detailed breakdown of all 4 plans
   - Task sequences, content examples
   - Quality assurance checkpoints, success metrics
   - Risks & mitigation strategies

4. **EXECUTION-GUIDE.md**
   - Step-by-step execution instructions
   - Per-plan commands and duration estimates
   - Testing & validation procedures
   - Troubleshooting (common issues & fixes)
   - Deployment checklist and rollback plan

5. **11-01-PLAN.md** — Documentation Expansion
   - **Wave:** 1 (no dependencies)
   - **Duration:** ~1.5-2 hours
   - **Tasks:** 8 (7 pages + schema)
   - **Output:** 7 docs pages, 2,500+ words, breadcrumb schema
   - **Files:** 9 (routes, controller, 7 templates)

6. **11-02-PLAN.md** — Hreflang Implementation
   - **Wave:** 1 (parallel with Plan 01)
   - **Duration:** ~1-1.5 hours
   - **Tasks:** 10 (component + integration)
   - **Output:** Hreflang on 28+ pages, EN/AR mutual references
   - **Files:** 20+ (1 component, 19 templates)

7. **11-03-PLAN.md** — Image Optimization
   - **Wave:** 2 (depends on Plan 01)
   - **Duration:** ~1.5-2 hours
   - **Tasks:** 9 (audits + alt text)
   - **Output:** 50+ images with semantic alt text, WCAG AA
   - **Files:** 8 (templates across 8 pages)

8. **11-04-PLAN.md** — FAQ & Features Pages
   - **Wave:** 2 (depends on Plan 01)
   - **Duration:** ~1-1.5 hours
   - **Tasks:** 7 (pages + schema + nav)
   - **Output:** 2 new pages, 12-15 FAQ items, 6-8 features, schema markup
   - **Files:** 4 (routes, controller, 2 templates)

---

## 🎯 Phase Goals (All Addressed)

| Goal | Plan | Status |
|------|------|--------|
| Expand /docs 737→2,500+ words | 01 | ✅ Planned (8 tasks) |
| Implement hreflang EN/AR all pages | 02 | ✅ Planned (10 tasks) |
| Add semantic alt text 50+ images | 03 | ✅ Planned (9 tasks) |
| Create /faq with schema | 04 | ✅ Planned (3 tasks) |
| Create /features with schema | 04 | ✅ Planned (4 tasks) |
| Add breadcrumb schema to /docs | 01 | ✅ Planned (1 task) |

---

## 📊 Execution Overview

### Wave 1 (Can run in parallel)
**Plans 01 + 02 → 2-3 hours total**

- **Plan 01:** 7 new documentation pages, 2,500+ words, breadcrumb schema
- **Plan 02:** Hreflang component, 28+ pages with language variants

Command:
```bash
/gsd:execute-phase 11 --plans 01,02
```

### Wave 2 (After Wave 1)
**Plans 03 + 04 → 2-3 hours total**

- **Plan 03:** Semantic alt text on 50+ images (8 pages)
- **Plan 04:** /faq (12-15 items) and /features (6-8 features) with schema

Command:
```bash
/gsd:execute-phase 11 --plans 03,04
```

---

## ✅ Planning Quality Checks

All 4 PLAN.md files include:

- ✅ **Frontmatter:** phase, plan, type, wave, depends_on, files_modified, autonomous, requirements, must_haves
- ✅ **Objective:** Clear what and why
- ✅ **Context:** References to relevant files and prior decisions
- ✅ **Tasks:** 7-10 tasks per plan with specific actions
- ✅ **Verification:** Automated commands for each task
- ✅ **Done Criteria:** Measurable acceptance criteria
- ✅ **Success Metrics:** Phase-level success criteria
- ✅ **Output:** Post-execution summary files documented

---

## 📋 Task Summary by Plan

### Plan 01: Documentation Expansion (8 tasks)
1. Create /docs landing page with navigation
2. Create /docs/getting-started (350+ words)
3. Create /docs/authentication (400+ words)
4. Create /docs/models (450+ words)
5. Create /docs/billing (350+ words)
6. Create /docs/rate-limits (300+ words)
7. Create /docs/error-codes (300+ words)
8. Add breadcrumb JSON-LD schema to all pages

**Output:** 7 pages, 2,500+ words, code examples (cURL, JS, Python), breadcrumb schema

### Plan 02: Hreflang Implementation (10 tasks)
1. Create hreflang Blade component
2. Add to app.blade.php (authenticated pages)
3. Add to welcome.blade.php (landing, x-default)
4. Add to landing/3.blade.php (consumer, x-default)
5. Add to auth pages (login, register, otp)
6. Add to /docs pages (7 subsections)
7. Add to marketing pages (cost-calc, comparison, alternatives, dedicated-server)
8. Add to dashboard pages
9. Add to admin dashboard
10. Verify hreflang on all 28+ pages

**Output:** Reusable component, 28+ pages with EN/AR hreflang, mutual references, x-default on landing

### Plan 03: Image Optimization (9 tasks)
1. Audit & add alt text to welcome.blade.php (10-12 images)
2. Add alt text to landing/3.blade.php (8-10 images)
3. Add alt text to /docs pages (4-6 images)
4. Add alt text to cost-calculator.blade.php (6-8 images)
5. Add alt text to comparison.blade.php (8-10 images)
6. Add alt text to alternatives.blade.php (10-15 images)
7. Add alt text to dedicated-server.blade.php (8-10 images)
8. Add alt text to dashboard/index.blade.php (3-5 images)
9. Verify 50+ images, validate accessibility

**Output:** 50-76 images with semantic alt text, WCAG 2.1 AA, WAVE validated (0 errors), Lighthouse 90+

### Plan 04: FAQ & Features Pages (7 tasks)
1. Create /faq route and controller method
2. Create /features route and controller method
3. Create faq.blade.php (12-15 FAQ items, FAQPage schema)
4. Create features.blade.php (6-8 features, ProductFeature schema)
5. Add navigation links to /faq and /features
6. Validate FAQPage and ProductFeature schemas
7. Test mobile responsiveness and accessibility

**Output:** 2 new pages, 12-15 FAQ items (100-200 words each), 6-8 features (150-250 words each), schema markup (0 errors)

---

## 🎓 Key Planning Decisions

### 1. Decomposition Strategy
- **Vertical slices:** Each plan is self-contained (docs, hreflang, images, faq/features)
- **Wave structure:** Plan 01 and 02 can run in parallel (no shared files) → Wave 1
- **Plan 03 depends on Plan 01** for /docs images (Wave 2)
- **Plan 04 depends on Plan 01** for navigation cross-links (Wave 2)

### 2. Content Specifications
- **Docs:** 2,500+ words across 7 pages (300-450 words each), code examples
- **Hreflang:** 28+ pages covered, reusable component prevents duplication
- **Alt text:** 50-76 images, semantic format (no "image of..."), 50-125 char length
- **FAQ:** 12-15 items with substantive answers (100-200 words minimum)
- **Features:** 6-8 features with benefits (150-250 words each)

### 3. Schema Implementation
- **Breadcrumb:** Applied to /docs subsections only (7 pages)
- **FAQPage:** 12-15 items in mainEntity array
- **ProductFeature:** 6-8 features in hasFeature array
- **Validation:** All use @json() helper, validate with schema.org

### 4. Accessibility & Mobile
- **WCAG 2.1 AA:** All new pages meet standard
- **Lighthouse:** Target 90+ accessibility score
- **Mobile:** Tailwind responsive grids (1 col small, 2-3 cols large)
- **Alt text:** All 50+ images for screen reader support

### 5. Navigation Integration
- **Hreflang component:** Single reusable Blade component, added to 20+ templates
- **Cross-page links:** /faq and /features linked from welcome, landing/3, cost-calculator, docs
- **Main nav:** Links to /faq and /features added to app.blade.php

---

## 🚀 What Executor Will Receive

When execution begins, executor receives:
1. ✅ 4 fully-specified PLAN.md files (not rough sketches)
2. ✅ EXECUTION-GUIDE.md with step-by-step commands
3. ✅ Per-plan estimated duration (1-2 hours each)
4. ✅ Specific files to create/modify (no "figure it out")
5. ✅ Code examples and templates (not "write this yourself")
6. ✅ Verification commands (curl, grep, schema validator)
7. ✅ Common issues & fixes (troubleshooting guide)
8. ✅ Wave dependencies (what to do in parallel vs sequential)

Executor does NOT need to:
- ❌ Research how to implement hreflang (plan specifies)
- ❌ Decide what pages to add alt text to (8 pages listed)
- ❌ Determine FAQ items (12-15 suggested)
- ❌ Design page layout (dark luxury + Tailwind specified)
- ❌ Validate schema (tools and commands provided)

---

## 📊 Resource Allocation

### Total Effort Estimate
- **Plan 01:** 1.5-2 hours (7 pages, 2,500 words, schema)
- **Plan 02:** 1-1.5 hours (1 component, 28+ pages)
- **Plan 03:** 1.5-2 hours (50+ images, 8 pages)
- **Plan 04:** 1-1.5 hours (2 pages, schema, validation)
- **Total:** 5-7 hours (can be done 4-6 with parallelization)

### Wave Parallelization
- **Wave 1:** Plans 01 + 02 in parallel (0 file conflicts) → 2-3 hours
- **Wave 2:** Plans 03 + 04 in parallel (only Plan 01 dependency) → 2-3 hours
- **Total with parallelization:** 4-6 hours

---

## ✨ Success Metrics (Measurable)

| Metric | Target | How to Verify |
|--------|--------|--------------|
| Documentation pages | 7 pages at /docs* | curl all 7 routes, expect 200 |
| Documentation word count | 2,500+ words total | wc -w across all 7 pages |
| Code examples | 4+ pages | grep -c "```" or grep -c "cURL" |
| Hreflang coverage | 28+ pages | grep -c "hreflang" on sample pages |
| Hreflang format | Absolute URLs | grep "https://" in hreflang href |
| Image alt text | 50+ images | find . -name "*.blade.php" | xargs grep -o 'alt="' | wc -l |
| Alt text quality | No "image of..." | grep -i "image of" should return 0 |
| Schema validation | 0 errors | Use schema.org validator |
| Lighthouse score | 90+ | Lighthouse audit on all new pages |
| Mobile responsive | 100% | Device emulation test |
| No 404s | 0 broken links | curl all cross-page links |

---

## 🔒 Risk Mitigation

All major risks have mitigation strategies in PLANNING-SUMMARY.md:

1. **Large content volume** → Broken into 8 focused tasks with per-task estimates
2. **Hreflang on 28+ pages** → Single reusable component, automated verification
3. **Alt text quality** → Guidelines provided, WAVE validation, task-by-task audit
4. **Schema errors** → Early validation (per-plan, not at end), schema.org validator provided
5. **Mobile layout issues** → Tailwind grid, device testing specified
6. **Broken navigation** → All cross-page links tested in final verification task

---

## 🎯 Next Steps for User

1. **Review Planning:**
   - Read `PHASE-README.md` (overview)
   - Skim `PLANNING-SUMMARY.md` (metrics, risks)
   - Check individual PLAN files for specifics

2. **Approve Execution:**
   - Confirm Wave 1 and Wave 2 timeline works
   - Ask any clarification questions before executor starts
   - Approve dev deployment time

3. **Execute Phase:**
   ```bash
   /gsd:execute-phase 11 --plans 01,02    # Wave 1 (parallel)
   /gsd:execute-phase 11 --plans 03,04    # Wave 2 (after Wave 1)
   ```

4. **Monitor Execution:**
   - Watch for any executor questions/blockers
   - Verify dev deployment works
   - Review SUMMARY files created by executor

5. **Deploy & Test:**
   - User tests on dev server (llmdev.resayil.io)
   - User approves for prod deployment
   - Executor merges to main and deploys

6. **Post-Deployment:**
   - Monitor Google Search Console for indexing
   - Track Rich Results (FAQ cards, Product cards)
   - Monitor organic traffic to new pages

---

## 📁 File Structure

```
.planning/phases/11-content-technical-seo/
├── README.md                          ← Quick start & navigation
├── PHASE-README.md                    ← Phase overview
├── PLANNING-SUMMARY.md                ← Detailed breakdown
├── EXECUTION-GUIDE.md                 ← Step-by-step execution
├── 11-01-PLAN.md                      ← Documentation Expansion
├── 11-02-PLAN.md                      ← Hreflang Implementation
├── 11-03-PLAN.md                      ← Image Optimization
└── 11-04-PLAN.md                      ← FAQ & Features Pages

(After execution — created by executor)
├── 11-01-SUMMARY.md                   ← Plan 01 execution summary
├── 11-02-SUMMARY.md                   ← Plan 02 execution summary
├── 11-03-SUMMARY.md                   ← Plan 03 execution summary
└── 11-04-SUMMARY.md                   ← Plan 04 execution summary
```

---

## ✅ Planning Validation

All plans meet the following criteria:

- ✅ **Specificity:** No vague instructions (e.g., "add authentication" → "add Bearer token auth to docs")
- ✅ **Completeness:** All required frontmatter fields present
- ✅ **Autonomy:** No checkpoints blocking execution (all tasks are autonomous)
- ✅ **Verification:** Automated commands provided for each task
- ✅ **Dependencies:** Wave structure minimizes sequential work
- ✅ **Scope:** Each task 15-60 min execution time
- ✅ **Context:** References to relevant project files
- ✅ **Acceptance:** Clear "done" criteria for each task
- ✅ **Quality Gates:** Success metrics measurable and testable

---

## 🎓 Key Insights for Executor

1. **Hreflang is critical but simple:**
   - Single component handles all 28+ pages
   - Absolute URLs required (not relative paths)
   - Validate mutual references (EN ↔ AR)

2. **Alt text quality matters more than quantity:**
   - No "image of..." or "screenshot of..." (redundant)
   - Semantic: describe content + context
   - 50-125 characters (Google truncates longer)

3. **Schema validation prevents deployment issues:**
   - Validate early (per-page, not at end)
   - Use schema.org validator (not just "looks right")
   - FAQPage and ProductFeature are common rich result types

4. **Content length is a feature, not a bug:**
   - FAQ answers: 100-200 words minimum (not one-liners)
   - Feature descriptions: 150-250 words (tell the story)
   - Docs pages: 300-450 words each (substantial reference)

5. **Mobile is non-negotiable:**
   - Test on actual small screens (not just devtools)
   - Tailwind grids handle responsiveness
   - Verify no horizontal scrolling

---

## 🚀 Ready for Execution

**Status:** ✅ PLANNING COMPLETE
**Quality:** ✅ ALL CHECKS PASSED
**Documentation:** ✅ COMPREHENSIVE (7 files, 5,000+ lines)
**Executor-Ready:** ✅ YES (step-by-step guide provided)

**Awaiting:** User approval to proceed with execution

---

**For questions or clarifications, review the detailed files or ask before execution begins.**

**Estimated total execution time: 4-6 hours (with parallelization)**

---

*Planning completed: 2026-03-08*
*Status: Ready for immediate execution*
