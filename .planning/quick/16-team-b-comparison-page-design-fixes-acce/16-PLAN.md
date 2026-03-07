---
phase: 16-quick-task
plan: 16
type: execute
wave: 1
depends_on: []
files_modified: [resources/views/comparison.blade.php]
autonomous: true
requirements: []
user_setup: []

must_haves:
  truths:
    - "All interactive elements have visible focus indicators when tabbing"
    - "All buttons, links, and FAQ items are fully keyboard navigable (Enter/Space)"
    - "Color contrast meets WCAG AA (4.5:1 for normal text, 3:1 for large text)"
    - "All dynamic content changes announced to screen readers (ARIA)"
    - "Mobile touch targets are 44px minimum (already met, verify preserved)"
  artifacts:
    - path: "resources/views/comparison.blade.php"
      provides: "Comparison page with WCAG AA accessibility"
      min_lines: 1046
  key_links:
    - from: "CSS focus-visible styles"
      to: "all interactive elements (.btn-*, .comp-faq-item)"
      via: "outline and box-shadow properties"
      pattern: "focus-visible.*outline"
    - from: "JS keyboard handler"
      to: "FAQ expand/collapse"
      via: "keydown listener with aria-expanded update"
      pattern: "addEventListener.*keydown"
---

<objective>
Fix WCAG AA accessibility violations on comparison page: focus indicators, keyboard navigation, color contrast, and ARIA labels.

Purpose: Meet accessibility standards for blind/low-vision users and keyboard-only users. Ensure the comparison page is fully navigable without a mouse.
Output: Fully accessible comparison page ready for production
</objective>

<execution_context>
@D:\Claude\projects\LLM-Resayil\CLAUDE.md
@D:\Claude\projects\LLM-Resayil\.planning\STATE.md
</execution_context>

<context>
Comparison page at `/comparison` provides detailed feature matrix and cost breakdown vs OpenRouter. Already has basic focus styles on buttons (`focus-visible` with outline) and FAQ JavaScript keyboard handling (Enter/Space), but gaps exist.

From comparison.blade.php analysis:
- Lines 101-104, 129-132: `.btn-primary` and `.btn-secondary` have `:focus-visible` with 2px gold outline (GOOD)
- Lines 393-396: `.comp-faq-item` has `:focus-visible` (GOOD)
- Lines 1025-1042: FAQ JS has keydown handler with aria-expanded toggle (GOOD)
- Missing: focus indicator on all touch targets, contrast verification on text-muted, ARIA labels for icons
- Color vars: `--comp-text-muted: #a0a8b5` on dark bg `#0f1115` — contrast ratio ~4.2:1 (borderline)
</context>

<tasks>

<task type="auto">
  <name>Task 1: Fix focus indicators, keyboard accessibility, and ARIA labels</name>
  <files>resources/views/comparison.blade.php</files>
  <action>
Apply these atomic fixes to comparison.blade.php:

**1. Focus Indicators (CSS, lines 6-594):**
- Add `:focus-visible` to `.comp-faq-item` (already in CSS at line 393, verify it's present)
- Add `:focus` fallback for older browsers: `outline: 2px solid var(--comp-gold); outline-offset: 2px;`
- Ensure all interactive elements have visible outline (buttons already have, verify table winner badges don't need)

**2. Keyboard Navigation (HTML + JS, lines 865-1042):**
- `<div class="comp-faq-item" role="button">` is correct for keyboard nav (has tabindex="0")
- Verify all FAQ items have `tabindex="0"` and role="button" (lines 865, 875, 885, etc.)
- JS keyboard handler at lines 1034-1041 already handles Enter/Space — no change needed

**3. Color Contrast (CSS):**
- Text-muted color `#a0a8b5` on bg `#0f1115` = ~4.2:1 ratio (meets 4.5:1 for WCAG AA only for large text 18pt+)
- FAQ answer text uses `.comp-faq-answer` with `color: var(--comp-text-muted)` at line 421 (normal text 0.95rem/15px)
- CHANGE line 421: `color: var(--comp-text-muted);` → `color: var(--comp-text);` (use full text color for answers)
- Subtitle at line 63-69 uses `--comp-text-muted` but is larger (clamp 1-1.5rem), keeps existing color (acceptable)

**4. ARIA Labels (HTML):**
- Line 868 (and similar): FAQ toggle icon `<span class="comp-faq-toggle">+</span>` is hidden from screen readers (good, used for visual state)
- Line 629, 637, 651, 658, 678: winner badge `<span class="comp-winner-badge">LLM Resayil</span>` needs aria-label
- CHANGE lines 629, 637, 651, 658, 678: `<span class="comp-winner-badge">LLM Resayil</span>` → `<span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>`
- Line 1017: scroll button `onclick` is accessible (keyboard handled by :focus-visible), verify focus works

**5. Mobile Touch Targets:**
- Lines 79-104 (.btn-primary) and 106-132 (.btn-secondary): padding 1rem 2.5rem + 44px min-height (via responsive line 541)
- Mobile buttons at line 540-544 adjusted to min-height: 44px — already meets WCAG AA
- FAQ items: padding 2rem (desktop), 1.5rem (mobile, line 529) — acceptable (not tight touch target)

**6. Semantic HTML Check:**
- Line 605: `<button onclick="...">` is semantic button with JS handler — acceptable for smooth scroll
- Consider: Add `aria-label="Scroll to comparison table"` to button for clarity
- CHANGE line 605: `<button class="btn-secondary" onclick="...">` → `<button class="btn-secondary" aria-label="Scroll to comparison table" onclick="...">`

**Summary of changes:**
1. Line 421: Change FAQ answer text color to full contrast
2. Lines 629, 637, 651, 658, 678: Add aria-label to winner badges
3. Line 605: Add aria-label to scroll button
4. Verify all interactive elements have focus-visible styles (they do)

Test with browser DevTools:
- Tab through all interactive elements (h1, buttons, FAQ items, links) — all should show gold outline
- Press Enter/Space on FAQ items — should expand/collapse
- Check color contrast: Lines 421 (changed color), verify 4.5:1+ on normal text
  </action>
  <verify>
    <automated>
# Manual verification (no automated test framework)
1. Open resources/views/comparison.blade.php
2. Verify 4 changes applied:
   - Line 421: text color changed from text-muted to text
   - Lines 629, 637, 651, 658, 678: aria-label="Winner in this category" added
   - Line 605: aria-label="Scroll to comparison table" added
3. Deploy to llmdev.resayil.io and test in browser:
   - Tab focus: Gold outline visible on all buttons, FAQ items, links
   - Keyboard: Enter/Space toggles FAQ items; links keyboard-accessible
   - Color: Verify FAQ answer text is readable (no longer muted gray)
4. Check with Chrome DevTools Lighthouse Accessibility audit:
   - All focus indicators present
   - No low-contrast text warnings
   - All aria-labels properly set
    </automated>
  </verify>
  <done>
Comparison page meets WCAG AA accessibility standards:
- All interactive elements have focus indicators (gold outline)
- All keyboard navigation works (Tab, Enter, Space, arrow keys on links)
- Color contrast 4.5:1+ on all text
- ARIA labels present on winner badges and action buttons
- Mobile touch targets 44px minimum
  </done>
</task>

</tasks>

<verification>
Comparison page accessibility audit (Chrome DevTools Lighthouse):
- Accessibility score 90+ (currently ~75-80 due to contrast/focus gaps)
- No low-contrast text warnings
- All interactive elements keyboard accessible
- All form elements properly labeled (if any)
- FAQPage schema still valid (no changes to schema markup)
</verification>

<success_criteria>
1. All 4 HTML/CSS changes committed to comparison.blade.php
2. Focus indicators visible on Tab (gold outline 2px solid)
3. FAQ items expand/collapse with Enter/Space (JS already handles)
4. FAQ answer text meets 4.5:1 contrast ratio
5. Winner badges have descriptive aria-labels
6. Scroll button has aria-label for context
7. Lighthouse Accessibility score improved from ~75 to 90+
8. No WCAG AA violations remain
</success_criteria>

<output>
After completion, create `.planning/quick/16-team-b-comparison-page-design-fixes-acce/16-SUMMARY.md` with:
- Changes applied (line numbers + before/after)
- Lighthouse accessibility score
- Date completed
- Ready for merge to dev
</output>
