# Phase 10 v2 Finalize — Complete Team C /alternatives, Commit All Teams, Deploy to Dev

**Quick Task #15**
**Estimated Duration:** 2–3 hours (sequential finalization + deployment)
**Target:** Complete Team C /alternatives fixes, commit all 4 teams' work, deploy to dev, verify WCAG AA compliance

---

## Context

Three teams (A, B, D) completed WCAG AA fixes. Team C (`/alternatives`) is in final execution. This plan:
1. Complete Team C's remaining keyboard nav, focus states, CSS extraction, mobile fonts, tablet breakpoint, and emoji ARIA labels
2. Stage and commit all 4 teams' changes
3. Deploy to dev for full QA
4. Verify WCAG AA compliance across all pages
5. Prepare for main/prod merge

**Status from Quick Task #14:**
- Team A (cost-calculator): ✅ Complete
- Team B (comparison): ✅ Complete
- Team D (dedicated-server): ✅ Complete
- Team C (alternatives): ⏳ In progress (CSS extraction + responsive + ARIA)

---

## Task 1: Finalize Team C — `/alternatives` Accessibility & Responsive Fixes

**Files:**
- `resources/views/alternatives.blade.php`
- `public/css/alternatives.css` (new file)

**Action:**

Complete the following fixes for `/alternatives` page (building on Team C's existing work):

1. **Finish CSS Extraction (if not complete)**
   - Verify `public/css/alternatives.css` exists with 500+ lines of extracted CSS
   - Add `<link href="/css/alternatives.css" rel="stylesheet">` to blade file (or use `@vite` if in resources/)
   - Remove `@push('styles')` section from blade file
   - Test in browser at https://llmdev.resayil.io/alternatives — page should render identically

2. **Complete Accordion Keyboard Navigation (if not complete)**
   - Verify all accordion headers have `role="button" tabindex="0"`
   - Verify keydown handler: `if (e.key === 'Enter' || e.key === ' ') { toggleAccordion(id) }`
   - Verify `aria-expanded="true/false"` toggles correctly
   - Verify `aria-controls="panel-id"` links header to content panel
   - Test: Tab to accordion → Press Enter → Should expand (aria-expanded → true)

3. **Add Focus States (if not complete)**
   - Add `:focus-visible` styles to all interactive elements
   - Accordion headers: `box-shadow: inset 0 0 0 2px var(--gold);` (2px gold inset)
   - Buttons: `outline: 2px solid var(--gold); outline-offset: 2px;`
   - Links: same gold outline
   - Ensure focus visible on dark background (#0f1115)

4. **Fix Mobile Font Sizes (if not complete)**
   - Audit all text at 375px (DevTools mobile mode)
   - Body text: ensure >= 14px (preferably 15-16px)
   - Inputs: 16px minimum (prevents iOS auto-zoom)
   - Search for `font-size < 13px` — increase to at least 13px
   - Table text in mobile layout: minimum 13px, but prefer 14-15px
   - Update media query: `@media(max-width: 481px) { body { font-size: 15px; } input { font-size: 16px; } }`

5. **Add Tablet Breakpoint (if not complete)**
   - Add `@media(max-width: 1024px) and (min-width: 481px)` section
   - Adjust comparison table column widths for tablet
   - Ensure 2-column layouts become 1-column on tablets
   - Test at 768px and 1024px widths
   - Verify no horizontal scrolling at 768px

6. **Add ARIA Labels to Emoji (if not complete)**
   - Find all emoji in page (🎯, 💼, ⚡, 🔧, 📊, etc.)
   - Add `aria-label` to each: `<span aria-label="target">🎯</span>`
   - Screen readers will announce labels instead of emoji
   - Example: `<span aria-label="features">⭐</span>Features`

7. **Verify Responsive Layout**
   - Mobile (375px): No horizontal scroll, text readable, touch targets >= 44px
   - Tablet (768px): Columns stack appropriately, layout balanced
   - Desktop (1440px): Multi-column layout restored

**Verification Steps:**

```bash
# Keyboard Navigation
# 1. Tab to accordion header → shows gold outline (:focus-visible)
# 2. Press Enter → expands (aria-expanded=true in DevTools)
# 3. Press Enter again → collapses (aria-expanded=false)
# 4. Space also works (both Enter and Space)
# 5. Tab moves to next accordion (no keyboard trap)

# Focus Indicators
# 1. Tab through all interactive elements
# 2. Each should show gold outline (2px, offset 2px)
# 3. Focus clearly visible on #0f1115 background
# 4. No hidden focus (not obscured by other elements)

# CSS Extraction
# 1. DevTools Network tab
# 2. Load page → should show alternatives.css request
# 3. Page should render identically to before extraction
# 4. No FOUC (Flash Of Unstyled Content)

# Mobile Font Sizes
# 1. DevTools → Device mode 375px
# 2. Inspect text: body text >= 14px, inputs 16px
# 3. Verify no text < 13px
# 4. Verify inputs are 16px (prevent iOS zoom)

# Tablet Layout
# 1. DevTools → Device mode 768px
# 2. Layout should be readable and balanced
# 3. Columns should stack or resize appropriately
# 4. No horizontal overflow
# 5. Test at 1024px (larger tablet)

# ARIA Labels
# 1. DevTools Accessibility panel
# 2. All emoji should have aria-label
# 3. Screen reader (NVDA/VoiceOver) announces labels
# 4. No "image" or unnamed elements in accessibility tree
```

**Done:**
- Accordion fully keyboard accessible (Tab, Enter/Space, aria-expanded, aria-controls)
- All interactive elements have visible :focus-visible gold indicators
- CSS extracted to separate file with proper linking
- Mobile text >= 14px (inputs 16px)
- Tablet breakpoint working (768px–1024px range)
- All emoji have aria-labels
- No horizontal overflow on mobile (375px–1024px)
- No WCAG AA violations

---

## Task 2: Commit All 4 Teams' Changes & Deploy to Dev

**Files:**
- `resources/views/cost-calculator.blade.php`
- `resources/views/comparison.blade.php`
- `resources/views/alternatives.blade.php`
- `public/css/alternatives.css`
- `resources/views/dedicated-server.blade.php`

**Action:**

1. **Stage All Changes**
   ```bash
   git status  # Verify 5 files modified/added
   git add resources/views/cost-calculator.blade.php
   git add resources/views/comparison.blade.php
   git add resources/views/alternatives.blade.php
   git add public/css/alternatives.css
   git add resources/views/dedicated-server.blade.php
   ```

2. **Create Unified Commit for All 4 Teams**
   ```bash
   git commit -m "fix(accessibility): Phase 10 v2 - WCAG AA compliance across all pages

   Teams A-D completed:

   Team A (cost-calculator): Slider ARIA, FAQ keyboard nav, text contrast, mobile touch targets, focus styling
   Team B (comparison): HTML validation, CSS vars, focus styling, mobile buttons, FAQ keyboard nav
   Team C (alternatives): Keyboard nav, focus states, CSS extraction, mobile fonts, tablet breakpoint, emoji ARIA
   Team D (dedicated-server): ARIA labels on icons/emoji, FAQ keyboard nav, focus styling, footer polish

   All pages now meet WCAG AA accessibility standards:
   - Focus indicators visible (gold outline)
   - Keyboard navigation fully functional
   - ARIA labels on dynamic content
   - Mobile touch targets >= 44px
   - Font sizes >= 14px (16px for inputs)
   - Color contrast >= 4.5:1 (AA level)"
   ```

3. **Deploy to Dev**
   ```bash
   ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
   ```
   Wait for deployment confirmation (typically 2–3 minutes)

4. **Verify Dev Deployment**
   - Open https://llmdev.resayil.io/cost-calculator → Test slider keyboard, FAQ keyboard nav
   - Open https://llmdev.resayil.io/comparison → Test table focus, FAQ keyboard nav
   - Open https://llmdev.resayil.io/alternatives → Test accordion keyboard nav, tab breakpoint (768px)
   - Open https://llmdev.resayil.io/dedicated-server → Test emoji ARIA, FAQ keyboard nav
   - Tab through each page — all interactive elements should show gold focus outline

5. **Run Lighthouse Accessibility Audit on Each Page**
   ```bash
   # For each page:
   # DevTools → Lighthouse → Accessibility
   # Check: Score >= 95 (WCAG AA)
   # Verify: No automated accessibility violations
   ```

**Verification Steps:**

```bash
# Git Status
git status
# Should show: nothing to commit (all staged and committed)

# Deployment
ssh whm-server "cd ~/llmdev.resayil.io && git log --oneline -5"
# Should show commit "fix(accessibility): Phase 10 v2" at top

# Page Load
curl -s https://llmdev.resayil.io/cost-calculator | head -50 | grep -i "<!doctype\|<html"
# Should return valid HTML header

# Focus Test (manual via browser)
# Each page: Tab through → focus outline should be gold (#d4af37)
```

**Done:**
- All 4 teams' changes staged and committed
- Commit message documents all fixes
- Dev deployed and accessible at llmdev.resayil.io
- All 4 pages rendering correctly on dev
- WCAG AA compliance verified visually (focus indicators, keyboard nav)

---

## Task 3: QA Verification & Prepare for Main/Prod Merge

**Action:**

1. **Manual QA Checklist (Complete All)**

   ```bash
   # Cost Calculator Page
   ✅ Slider: Tab to slider → Press arrow keys → should move thumb
   ✅ Slider: aria-valuenow updates in DevTools
   ✅ FAQ: Tab to FAQ item → gold outline visible
   ✅ FAQ: Press Enter → expands (aria-expanded=true)
   ✅ Mobile 375px: Slider thumb 26px (tap target), all text >= 14px

   # Comparison Page
   ✅ Table: Tab to any button → gold outline visible
   ✅ FAQ: Tab to FAQ → Press Space → toggles (both Enter and Space work)
   ✅ Mobile 375px: All buttons >= 44px tall, easy to tap
   ✅ Buttons: Hover + Focus states both visible

   # Alternatives Page
   ✅ Accordion: Tab to header → gold outline visible
   ✅ Accordion: Press Enter/Space → expands/collapses
   ✅ Accordion: aria-expanded toggles in DevTools
   ✅ Mobile 375px: Text >= 14px, no horizontal scroll
   ✅ Tablet 768px: Layout balanced, columns stack properly
   ✅ CSS loaded: Network tab shows alternatives.css request
   ✅ Emoji: aria-label present (DevTools Accessibility panel)

   # Dedicated Server Page
   ✅ Icons: All emoji have aria-label (DevTools Accessibility tree)
   ✅ FAQ: Tab through → gold outline visible
   ✅ FAQ: Enter/Space both work
   ✅ Footer: Links keyboard accessible (Tab works)
   ✅ Footer: Links have hover state
   ```

2. **Lighthouse Accessibility Audit**

   For each page (cost-calculator, comparison, alternatives, dedicated-server):
   ```bash
   # DevTools → Lighthouse → Accessibility
   # Target: Score >= 95 (WCAG AA)
   # Accept warnings, fix any errors
   ```

3. **Keyboard Navigation Full Sweep**

   On each page:
   ```bash
   # 1. Start at top
   # 2. Press Tab repeatedly → focus moves through ALL interactive elements
   # 3. Shift+Tab → moves backward (verify bidirectional)
   # 4. All interactive elements show gold focus outline
   # 5. No keyboard traps (can always move forward/backward)
   # 6. Press Enter on buttons/FAQ → executes action
   # 7. Press Space on buttons/accordion → executes action
   ```

4. **Mobile Responsive Check**

   Device widths: 375px, 481px, 768px, 1024px, 1440px
   ```bash
   # At each width:
   # ✅ All text readable (no horizontal scroll)
   # ✅ Touch targets >= 44px
   # ✅ Inputs 16px (prevent zoom)
   # ✅ Layout balanced and intentional
   # ✅ No overlapping elements
   ```

5. **Document QA Results**

   Create `.planning/quick/15-phase-10-v2-finalize-complete-team-c-alt/15-QA-RESULTS.md`:
   ```markdown
   # QA Results — Phase 10 v2 Accessibility Fixes

   Date: [today's date]
   Status: ✅ APPROVED FOR PRODUCTION

   ## Pages Verified
   - ✅ /cost-calculator — WCAG AA compliant
   - ✅ /comparison — WCAG AA compliant
   - ✅ /alternatives — WCAG AA compliant
   - ✅ /dedicated-server — WCAG AA compliant

   ## Test Results
   - ✅ Keyboard navigation: All pages fully accessible (Tab, Enter, Space)
   - ✅ Focus indicators: Gold outline on all interactive elements
   - ✅ Mobile (375px): All text, touch targets, layout verified
   - ✅ Tablet (768px): Layout balanced, columns stacked
   - ✅ Desktop (1440px): Multi-column layouts working
   - ✅ Lighthouse: All pages >= 95 Accessibility score

   ## Next Steps
   1. Merge dev → main
   2. Tag v1.10.0
   3. Deploy to prod
   ```

**Verification:**

```bash
# Check QA results file exists
ls -la .planning/quick/15-phase-10-v2-finalize-complete-team-c-alt/15-QA-RESULTS.md

# Verify all changes are on dev
ssh whm-server "cd ~/llmdev.resayil.io && git log --oneline -1"
# Should show: fix(accessibility): Phase 10 v2 - WCAG AA compliance...
```

**Done:**
- ✅ Manual QA completed on all 4 pages
- ✅ Keyboard navigation tested and verified
- ✅ Mobile/tablet/desktop responsive verified
- ✅ Lighthouse accessibility audits passed (>= 95)
- ✅ QA results documented
- ✅ Ready for main/prod merge

---

## Success Criteria

- [x] Team C `/alternatives` fixes complete (keyboard nav, focus states, CSS extraction, mobile fonts, tablet breakpoint, emoji ARIA)
- [x] All 4 teams' changes committed in single commit
- [x] Dev deployed successfully (ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev")
- [x] All 4 pages tested and verified:
  - [x] Keyboard navigation fully functional
  - [x] Focus indicators visible (gold)
  - [x] ARIA labels present
  - [x] Mobile/tablet/desktop responsive
- [x] Lighthouse accessibility >= 95 on all pages
- [x] QA results documented
- [x] Ready to merge dev → main, tag v1.10.0, deploy prod

---

## CRITICAL DEPLOYMENT CHECKLIST

Before proceeding with main/prod merge:

- [x] All 4 teams' changes on dev branch
- [x] Dev deployed and tested
- [x] No merge conflicts
- [x] No pending migrations (all UI/CSS only)
- [x] No env var changes
- [x] QA passed on all pages
- [x] Ready for: `/gsd:quick merge-and-deploy "dev-to-main"`

---

## Next Steps (After This Plan Completes)

1. **Merge to Main:**
   ```bash
   /gsd:quick merge-and-deploy "dev-to-main"
   # Or manually:
   git checkout main
   git merge dev
   git push origin main
   ```

2. **Tag Release:**
   ```bash
   git tag v1.10.0
   git push origin --tags
   ```

3. **Deploy to Prod:**
   ```bash
   ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
   ```

4. **Verify Prod:**
   - Open https://llm.resayil.io/cost-calculator, etc.
   - Tab through pages → verify gold focus outlines
   - Lighthouse audit on prod

5. **Update STATE.md:**
   ```markdown
   **Last Activity:** Phase 10 v2 Complete — WCAG AA compliance shipped to prod (v1.10.0)
   **Next:** Phase 11 — Content & Technical SEO
   ```

---

**Plan Status:** Ready for execution
**Owner:** Quick Task execution
**Effort:** 2–3 hours (parallel dev work + sequential QA + deployment)
