# Phase 10 v2 Production Readiness Fixes — Parallel Team Execution

**Quick Plan for 4 Parallel Agent Teams**
**Estimated Duration:** 4–6 hours total (parallel execution)
**Target:** WCAG AA compliance + HTML validation + design refinement across 4 pages

---

## Overview

Four agent teams work **simultaneously** on accessibility violations, HTML validation errors, and design refinements across:
- `/cost-calculator` — Slider ARIA, FAQ keyboard nav, text contrast, mobile touch targets
- `/comparison` — HTML validation, CSS vars, focus styling, mobile buttons
- `/alternatives` — Keyboard nav, focus states, CSS extraction, mobile fonts
- `/dedicated-server` — ARIA labels, FAQ keyboard support, focus styling, footer polish

Each team performs testing (keyboard navigation, focus indicators, contrast) and uses ui-ux-pro-max skill to validate fixes.

---

## Prerequisites

All changes merged to dev branch. Files ready at:
- `resources/views/cost-calculator.blade.php`
- `resources/views/comparison.blade.php`
- `resources/views/alternatives.blade.php`
- `resources/views/dedicated-server.blade.php`

Deployed to dev at: `https://llmdev.resayil.io/[page]`

---

## Task 1: Team A — /cost-calculator Accessibility & Mobile Fixes

**Files:**
- `resources/views/cost-calculator.blade.php`

**Action:**
Fix the following accessibility violations and mobile issues:

1. **Slider ARIA Labels (HIGH PRIORITY)**
   - Add `aria-label="Monthly token usage slider"` to range input (line ~880)
   - Add `aria-describedby="slider-help"` to reference help text
   - Add `aria-valuemin`, `aria-valuemax`, `aria-valuenow` attributes to slider
   - Create `id="slider-help"` div with hint text: "Drag to adjust usage from 1M to 10B tokens"
   - Update slider aria-valuetext on input change

2. **FAQ Keyboard Navigation (HIGH PRIORITY)**
   - Add `role="button" tabindex="0"` to each `.faq-item` (line ~720)
   - Add JavaScript keydown handler: `if (e.key === 'Enter' || e.key === ' ') { toggleFAQ() }`
   - Update toggleFAQ function to add/remove 'open' class on keyboard
   - Add `aria-expanded` attribute (true/false based on state)

3. **Text Contrast Fix (HIGH PRIORITY)**
   - Change `--text-muted` from `#6b7280` to `#8a92a0` (achieves 4.5:1 AA ratio on #0f1115)
   - Apply to: `.comparison-label`, `.result-label`, `.slider-hint`, `.faq-answer`, all muted text
   - Verify with contrast checker

4. **Mobile Slider Thumb (HIGH PRIORITY)**
   - Increase mobile slider thumb from 20px to 26px (line ~450)
   - Add @media(max-width: 768px) rule with `.slider-input::-webkit-slider-thumb { width: 26px; height: 26px; }`
   - Apply same to `-moz-range-thumb`
   - Add white border: `border: 2px solid rgba(255,255,255,0.3);`

5. **Live Region for Results (MEDIUM PRIORITY)**
   - Wrap result cards in `<div aria-live="polite" aria-label="Cost comparison results">`
   - Add `role="status"` to result value elements
   - Add `aria-valuenow` updates on calculate

6. **Input Fallback (MEDIUM PRIORITY)**
   - Add number input below slider with label "Or enter directly:"
   - Wire both slider and number input to sync on change
   - Ensure min/max constraints applied

**Verification Steps:**

```bash
# Keyboard Navigation Test
# 1. Tab through page — slider should be focusable
# 2. Tab to FAQ item — should show focus ring (gold outline)
# 3. Press Enter/Space on FAQ — should expand/collapse
# 4. Check aria-expanded changes in DevTools

# Focus Indicators Test
# - All interactive elements have visible focus ring (gold shadow)
# - Slider thumb focus state visible
# - FAQ items show :focus-visible styling

# Contrast Check
# - Open DevTools → Right-click element → Inspect
# - Use Lighthouse → Accessibility → Check color contrast >= 4.5:1
# - Specific check: #8a92a0 text on #0f1115 background = 4.53:1 ✓

# Mobile Slider Test
# - Visit on iPhone (or use DevTools device mode 375px)
# - Slider thumb should be 26px (larger, easier to tap)
# - Drag slider smoothly — no stuttering
```

**Done:**
- Slider fully keyboard accessible with proper ARIA
- FAQ items toggle via keyboard (Enter/Space)
- Text contrast meets WCAG AA (4.5:1 minimum)
- Mobile slider thumb 26px minimum for touch targets
- All aria attributes updated dynamically
- Number input fallback for keyboard entry

---

## Task 2: Team B — /comparison HTML Validation & Mobile Fixes

**Files:**
- `resources/views/comparison.blade.php`

**Action:**
Fix HTML validation errors and accessibility violations:

1. **HTML Validation (HIGH PRIORITY)**
   - Run W3C HTML validator on page
   - Fix malformed `</tr>` at line ~916 (likely missing opening `<tr>` or extra closing tag)
   - Verify all table rows properly nested: `<table><tbody><tr><td>...</td></tr></tbody></table>`
   - Check for unclosed tags, deprecated attributes
   - Verify comparison-table structure has proper `<thead>`, `<tbody>`

2. **CSS Variables (HIGH PRIORITY)**
   - Search for undefined `--comp-text-secondary`
   - Either define it in `:root` or replace with `var(--text-secondary)` throughout
   - Define missing comparison-specific colors if needed: `--comp-border`, `--comp-bg-row`
   - Add fallback colors: `color: var(--comp-text-secondary, #a0a8b5);`

3. **Focus Styling (HIGH PRIORITY)**
   - Add `:focus-visible` state to all clickable elements
   - Buttons: `.btn:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }`
   - Table cells with onclick: `[role="button"]:focus-visible { box-shadow: 0 0 0 2px var(--gold); }`
   - FAQ toggle items: same gold outline

4. **Mobile Button Touch Targets (HIGH PRIORITY)**
   - Ensure all `.btn` elements are >= 44px height on mobile
   - Add @media(max-width: 768px) rule: `.btn { min-height: 44px; padding: 0.75rem 1rem; }`
   - "Pricing" action buttons in table: min-height 44px, padding 0.75rem
   - Check CTA button at bottom: >= 48px height

5. **FAQ Keyboard Navigation (HIGH PRIORITY)**
   - Add `role="button" tabindex="0"` to FAQ items (line ~TBD)
   - Add Enter/Space key handler
   - Add `aria-expanded` attribute

6. **Table Accessibility (MEDIUM PRIORITY)**
   - Verify `<th scope="col">` on header cells
   - Add `role="rowheader"` to first column if needed
   - Add `aria-sort` to sortable columns (if applicable)

**Verification Steps:**

```bash
# HTML Validation
# 1. Copy page HTML from DevTools → https://validator.w3.org/
# 2. Check "Errors" section — should show 0 errors
# 3. Verify table structure: <tbody><tr><td>...</td></tr></tbody>

# Focus Testing
# 1. Tab through table — all buttons/cells should show focus
# 2. Focus ring should be gold, offset 2px, visible on dark bg
# 3. Keyboard interaction: click focused button via Enter key

# Mobile Button Test
# 1. DevTools → Device mode (375px width)
# 2. All buttons should be >= 44px tall
# 3. Tap any button — should be easy (no accidental taps on neighbors)

# Keyboard Navigation
# 1. Tab to FAQ item → shows focus ring
# 2. Press Enter/Space → expands/collapses
# 3. Press again → collapses
# 4. aria-expanded changes in DevTools
```

**Done:**
- HTML validation: 0 errors
- All table rows properly nested
- CSS variables defined or replaced with fallback
- Focus indicators visible (gold outline/shadow)
- Mobile buttons >= 44px minimum
- FAQ keyboard accessible (Enter/Space)
- ARIA attributes properly applied

---

## Task 3: Team C — /alternatives Keyboard Nav, Focus States & Mobile Optimization

**Files:**
- `resources/views/alternatives.blade.php`

**Action:**
Fix keyboard navigation, focus states, CSS extraction, and mobile font issues:

1. **Keyboard Navigation — Accordion (HIGH PRIORITY)**
   - Add `role="button" tabindex="0"` to accordion headers
   - Add keydown handler: `if (e.key === 'Enter' || e.key === ' ') { toggleAccordion() }`
   - Add `aria-expanded` attribute (true/false)
   - Add `aria-controls="panel-id"` linking to accordion panel
   - Ensure Tab moves sequentially through headers

2. **Focus States (HIGH PRIORITY)**
   - Add `:focus-visible` to all interactive elements
   - Accordion headers: `.accordion-header:focus-visible { box-shadow: inset 0 0 0 2px var(--gold); }`
   - Buttons: `.btn:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }`
   - Links: `a:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }`
   - Verify focus indicators visible on dark background

3. **Mobile Font Sizes (HIGH PRIORITY)**
   - Audit all text on mobile (DevTools 375px)
   - Search for `font-size` < 16px (causes zoom on iOS)
   - Update mobile inputs: `font-size: 16px;` (prevent auto-zoom)
   - Update paragraph text: minimum 14px, preferably 15-16px on mobile
   - Update table text: minimum 13px (can be smaller in dense layouts)
   - Body text should be ≥ 15px on mobile

4. **CSS Extraction (MEDIUM PRIORITY)**
   - Extract inline `@push('styles')` CSS from blade file into separate file
   - Create `public/css/alternatives.css` (or `resources/css/alternatives.css`)
   - Move all styles from `@push('styles')` section
   - Update blade to `@vite(['resources/css/alternatives.css'])` or `<link href="/css/alternatives.css">`
   - Verify styles still apply after extraction

5. **Tablet Breakpoint (MEDIUM PRIORITY)**
   - Add @media(max-width: 1024px) breakpoint for tablet
   - Adjust column widths for comparison table on tablet
   - Ensure 2-col layouts become 1-col or stack properly
   - Test at 768px and 1024px widths

6. **Comparison Table Optimization (MEDIUM PRIORITY)**
   - Make table scrollable horizontally on mobile if needed
   - Or collapse to card layout on mobile (one feature per card)
   - Ensure no horizontal overflow at 375px

**Verification Steps:**

```bash
# Keyboard Navigation — Accordion
# 1. Tab to accordion header → shows focus ring
# 2. Press Enter/Space → expands/collapses
# 3. Tab to next header → moves to next item (not stuck)
# 4. aria-expanded toggles in DevTools

# Focus States
# 1. Tab through all interactive elements
# 2. Each shows gold outline or box-shadow
# 3. Focus is clearly visible (contrast >= 3:1 with background)
# 4. No focus traps (can always Tab to next element)

# Mobile Font Sizes
# 1. DevTools → Device mode 375px
# 2. Inspect each text element
# 3. Body text >= 15px
# 4. Input fields 16px (prevent zoom)
# 5. No text should be < 13px

# CSS Extraction
# 1. Open Network tab in DevTools
# 2. Load page → should show alternatives.css request
# 3. Styles should apply (page looks identical)
# 4. No FOUC (Flash Of Unstyled Content)

# Tablet Breakpoint
# 1. DevTools → Device mode 768px (iPad)
# 2. Layout should be readable
# 3. Columns should stack or resize appropriately
# 4. Test at 1024px (larger iPad)
```

**Done:**
- Accordion fully keyboard accessible
- All interactive elements have visible focus states (gold indicators)
- Mobile text >= 14px (15-16px preferred)
- Inputs 16px to prevent iOS zoom
- CSS extracted to separate file
- Tablet breakpoint working (768px - 1024px range)
- No horizontal overflow on mobile

---

## Task 4: Team D — /dedicated-server ARIA Labels, Keyboard FAQ, Focus Styling & Footer

**Files:**
- `resources/views/dedicated-server.blade.php`

**Action:**
Add missing ARIA labels, implement keyboard FAQ navigation, fix focus states, and polish footer:

1. **ARIA Labels on All Icons & Interactive Elements (HIGH PRIORITY)**
   - Search page for `<svg>` icons without aria-label
   - Add `aria-label="Description"` or `aria-hidden="true"` to all SVGs
   - Emoji icons: Add `aria-label` (e.g., `<span aria-label="checkmark">✓</span>`)
   - Button icons: If icon-only button, wrap in button with aria-label
   - Example: `<button aria-label="Close modal"><svg aria-hidden="true">...</svg></button>`

2. **FAQ Keyboard Navigation (HIGH PRIORITY)**
   - Convert FAQ onclick handlers to `role="button" tabindex="0"` elements
   - Add keyboard handler: `if (e.key === 'Enter' || e.key === ' ') { toggleFAQ() }`
   - Add `aria-expanded` attribute
   - Test: Tab to FAQ → press Enter → expands
   - Test: Press Space → expands (both Enter and Space should work)

3. **Focus Styling (HIGH PRIORITY)**
   - Add `:focus-visible` to all interactive elements
   - FAQ buttons: `box-shadow: 0 0 0 2px var(--gold);`
   - CTA buttons: `outline: 2px solid var(--gold); outline-offset: 2px;`
   - Accordion/collapsible items: consistent focus ring
   - Navigation links: visible focus state

4. **Emoji ARIA Labels (MEDIUM PRIORITY)**
   - Find all emoji in page (🚀, 💡, ✓, 🔒, etc.)
   - Add `aria-label` to each: `<span aria-label="rocket">🚀</span>`
   - Or wrap in span: `<span class="emoji" aria-label="rocket">🚀</span>`
   - Screen readers will say "rocket" instead of trying to pronounce emoji

5. **Footer Polish (MEDIUM PRIORITY)**
   - Verify footer links have proper color (gold or secondary)
   - Add footer link hover states (underline or color change)
   - Check footer layout on mobile (should stack vertically)
   - Add footer bottom padding (ensure no content hidden)
   - Verify footer background (should be darker or match card color)
   - Test footer links keyboard navigation (Tab works)

6. **Semantic HTML (MEDIUM PRIORITY)**
   - Convert onclick divs to proper `<button>` elements where appropriate
   - Verify heading hierarchy (h1 → h2 → h3, no skips)
   - Use `<details>/<summary>` for expandable sections (native semantic elements)
   - Verify all form controls have labels

**Verification Steps:**

```bash
# ARIA Labels
# 1. DevTools → Accessibility tree (F12 → Accessibility panel)
# 2. All icons should have aria-label or aria-hidden=true
# 3. Screen reader test: Tab through page, listen for descriptions
# 4. No "image" or unnamed button elements in tree

# FAQ Keyboard Navigation
# 1. Tab to FAQ item → shows focus ring
# 2. Press Enter → expands, aria-expanded=true
# 3. Press Enter again → collapses, aria-expanded=false
# 4. Press Space → also works (both Enter and Space)
# 5. Tab moves between FAQ items in sequence

# Focus Styling
# 1. Tab through entire page
# 2. All interactive elements (buttons, links, accordions) show focus
# 3. Focus color is gold (#d4af37) with sufficient contrast
# 4. Focus is not hidden/obscured by other elements
# 5. No focus traps

# Emoji ARIA Labels
# 1. DevTools → Elements → Find emoji
# 2. Check for aria-label attribute
# 3. Screen reader test: Navigate to emoji, should announce label
# 4. Visual check: Page should look identical

# Footer
# 1. Desktop: Footer should be aligned properly, links visible
# 2. Mobile: Footer should stack vertically, links easy to tap
# 3. Keyboard: Tab to footer links, focus visible, clickable via Enter
# 4. Links should have hover state (color change or underline)
```

**Done:**
- All icons and interactive elements have ARIA labels or aria-hidden=true
- FAQ items fully keyboard accessible (Enter/Space work)
- aria-expanded toggles on keyboard interaction
- Focus indicators visible on all interactive elements (gold styling)
- Footer links have proper hover and focus states
- Emoji have aria-labels for screen readers
- Semantic HTML (buttons instead of onclick divs where appropriate)
- No ARIA violations in accessibility tree

---

## Parallel Execution Strategy

**Wave 1: All Teams Simultaneously**
- Team A starts on /cost-calculator (est. 90–120 min)
- Team B starts on /comparison (est. 90–120 min)
- Team C starts on /alternatives (est. 120–150 min)
- Team D starts on /dedicated-server (est. 90–120 min)

**Synchronization Points:**
- 15 min: Initial exploration, identify violations
- 60 min: Core fixes in place, start testing
- 120 min: All tasks complete verification, testing underway
- 180 min: Testing complete, prepare for commit

**Total Duration:** 4–6 hours (parallel, not sequential)

---

## Success Criteria

### All Teams Must Achieve:
- [ ] Zero HTML validation errors (W3C validator)
- [ ] WCAG AA accessibility compliance
  - [ ] Color contrast >= 4.5:1 (AA level)
  - [ ] Focus indicators visible and properly styled
  - [ ] Keyboard navigation working (Tab, Enter, Space)
  - [ ] ARIA labels on all icons and dynamic content
  - [ ] aria-expanded on accordion/FAQ items
- [ ] Mobile touch targets >= 44px (minimum)
- [ ] Mobile font sizes >= 14px (15-16px preferred)
- [ ] Focus rings visible on all interactive elements (gold #d4af37)

### Page-Specific:
**Team A (/cost-calculator):**
- [ ] Slider has aria-label, aria-describedby, aria-value* attributes
- [ ] FAQ items keyboard accessible (Enter/Space toggle)
- [ ] Text contrast #8a92a0 on #0f1115 (4.53:1 WCAG AA)
- [ ] Mobile slider thumb 26px
- [ ] Number input fallback present

**Team B (/comparison):**
- [ ] HTML validation: 0 errors (W3C)
- [ ] Table rows properly nested
- [ ] CSS variables defined (--comp-text-secondary)
- [ ] Focus styling on all buttons/cells
- [ ] Mobile buttons >= 44px

**Team C (/alternatives):**
- [ ] Accordion keyboard accessible
- [ ] Focus states on all interactive elements
- [ ] Mobile text >= 14px
- [ ] CSS extracted to separate file
- [ ] Tablet breakpoint (768px–1024px) working

**Team D (/dedicated-server):**
- [ ] All icons have aria-label or aria-hidden
- [ ] FAQ keyboard accessible (Enter/Space)
- [ ] Focus indicators on all interactive elements
- [ ] Emoji have aria-labels
- [ ] Footer properly styled and accessible

---

## Testing Checklist (All Teams)

After fixes, each team must verify:

### Keyboard Navigation
- [ ] Tab moves through all interactive elements
- [ ] Shift+Tab moves backward
- [ ] Enter activates buttons
- [ ] Space activates buttons/toggles
- [ ] No keyboard traps (can always move away)

### Focus Indicators
- [ ] All interactive elements show visible focus (gold outline/shadow)
- [ ] Focus is not hidden by other elements
- [ ] Focus contrast >= 3:1 with background

### Color Contrast
- [ ] Use Lighthouse or WebAIM Contrast Checker
- [ ] All text >= 4.5:1 (AA) for body text
- [ ] Interactive elements >= 3:1 for borders/outlines
- [ ] Large text (18pt+) >= 3:1

### Mobile Testing
- [ ] DevTools Device Mode at 375px width
- [ ] All text readable (no horizontal scroll)
- [ ] Touch targets >= 44px
- [ ] Input fields 16px (prevent zoom)
- [ ] Forms usable with one hand

### Screen Reader Testing (Optional)
- [ ] Use NVDA (Windows) or VoiceOver (Mac)
- [ ] All icons announced correctly
- [ ] Form labels read properly
- [ ] Expandable content announced (aria-expanded)

### HTML Validation
- [ ] Copy HTML to https://validator.w3.org/
- [ ] Check "Errors" section: should be empty
- [ ] Warnings acceptable (not errors)

---

## Deployment Notes

After all teams complete:
1. Merge fixes to dev branch (all teams commit together)
2. Deploy to dev: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
3. Full QA round: Test all 4 pages on dev
4. If approved: Merge dev → main, deploy to prod
5. Tag: `git tag v1.10.0 && git push origin --tags`

**Risk:** None — only accessibility/design fixes, no logic changes

---

## Skill Profiles

All teams using:
- **Primary:** UI/UX Pro Max (design validation, focus states, mobile optimization)
- **Secondary:** Accessibility Expert (WCAG AA compliance, ARIA best practices)
- **Tertiary:** Frontend Dev (HTML validation, JavaScript event handlers)

---

**Estimated Total Effort:** 4–6 hours parallel execution
**Commitment Level:** High priority — blocks production deployment
**Owner:** All 4 agent teams (parallel execution)
