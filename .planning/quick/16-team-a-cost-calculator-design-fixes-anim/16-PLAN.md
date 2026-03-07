---
phase: 16-quick-task
plan: 16
type: execute
wave: 1
depends_on: []
files_modified: [resources/views/cost-calculator.blade.php]
autonomous: true
requirements: []
user_setup: []

must_haves:
  truths:
    - "Slider supports keyboard input (arrow keys adjust value)"
    - "Result values animate smoothly when slider/dropdown change"
    - "All color contrast meets WCAG AA (4.5:1 for normal text)"
    - "All interactive elements keyboard navigable with visible focus"
    - "Mobile touch targets are 44px minimum"
  artifacts:
    - path: "resources/views/cost-calculator.blade.php"
      provides: "Cost calculator with animations, a11y, mobile support"
      min_lines: 1087
  key_links:
    - from: "CSS slider styles"
      to: "focus and keyboard event handlers"
      via: "aria attributes and tabindex"
      pattern: "aria-valuenow|aria-valuetext"
    - from: "JavaScript event listeners"
      to: "result value animations"
      via: "animation: slideUp class application"
      pattern: "calculateCosts|addEventListener"
    - from: "Mobile media queries"
      to: "touch target sizing"
      via: "min-height: 44px on buttons"
      pattern: "@media.*44px"
---

<objective>
Fix cost calculator design: slider keyboard support, animated result updates, color contrast, keyboard navigation, and mobile touch targets.

Purpose: Ensure cost calculator is fully accessible (WCAG AA), responsive on mobile, and provides smooth animated feedback on all interactions.
Output: Production-ready cost calculator with accessibility and animation enhancements
</objective>

<execution_context>
@D:\Claude\projects\LLM-Resayil\CLAUDE.md
@D:\Claude\projects\LLM-Resayil\.planning\STATE.md
@D:\Claude\projects\LLM-Resayil\.claude\COST_CALCULATOR_DESIGN_SUMMARY.md
</execution_context>

<context>
Cost calculator at `/cost-calculator` provides interactive pricing comparison with slider input, dropdown selects, and real-time cost calculations. Already has basic structure and animations, but several enhancements needed.

From cost-calculator.blade.php analysis:
- Lines 686-703: Slider input with ARIA attributes (good foundation)
- Lines 995-1004: Slider input event listener triggers calculateCosts (no animation delay)
- Lines 1026-1038: FAQ keyboard handlers exist (Enter/Space toggles)
- Lines 710-717: Number input allows direct entry (good)
- Missing: Slider arrow key support, animated value updates, text contrast verification
- Color vars: `--text-muted: #8a92a0` on dark bg `#0f1115` — contrast ratio ~4.6:1 (meets WCAG AA)
- Mobile slider thumb: 26px on mobile (line 635-643) — adequate for touch targets
</context>

<tasks>

<task type="auto">
  <name>Task 1: Add slider keyboard support and enhance animations</name>
  <files>resources/views/cost-calculator.blade.php</files>
  <action>
Apply these atomic fixes to cost-calculator.blade.php:

**1. Slider Keyboard Support (JavaScript, lines 995-1004):**
- Add keydown listener to slider input to support arrow keys
- LEFT/DOWN arrows decrease value by step (1M tokens)
- RIGHT/UP arrows increase value by step (1M tokens)
- Page Down decreases by 10x step, Page Up increases by 10x step
- Action: Add new event listener after line 1004 (after slider input listener)

```javascript
// Keyboard support for slider (arrow keys)
elements.slider.addEventListener('keydown', function(e) {
    const step = parseInt(this.step) || 1000000;
    let newValue = parseInt(this.value);

    if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
        e.preventDefault();
        newValue = Math.max(parseInt(this.min), newValue - step);
    } else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
        e.preventDefault();
        newValue = Math.min(parseInt(this.max), newValue + step);
    } else if (e.key === 'PageDown') {
        e.preventDefault();
        newValue = Math.max(parseInt(this.min), newValue - (step * 10));
    } else if (e.key === 'PageUp') {
        e.preventDefault();
        newValue = Math.min(parseInt(this.max), newValue + (step * 10));
    }

    if (newValue !== parseInt(this.value)) {
        this.value = newValue;
        this.dispatchEvent(new Event('input', { bubbles: true }));
    }
});
```

**2. Animated Result Updates (CSS + JavaScript):**
- Results already have slideUp animation (line 280)
- Enhance: Add animation trigger class on every calculation
- Action: Modify calculateCosts() function (lines 1047-1080) to trigger animation
- Add CSS to restart animation on each update:

```css
/* At end of <style> block (after line 662) */
.result-value.animate {
    animation: none;
}

.result-value.animate {
    animation: slideUp 0.4s ease-out;
}
```

- Modify JavaScript to apply animate class:
  - Before updating text content, remove animate class
  - Update content
  - Trigger reflow/repaint with offsetHeight
  - Re-add animate class to trigger animation

**3. Number Input Keyboard Support:**
- Number input already has min/max constraints (lines 709-715)
- Verify input event handler (lines 1007-1019) enforces constraints
- Action: Confirm existing code works, no changes needed

**4. Dropdown Keyboard Navigation:**
- Select elements are natively keyboard accessible
- Verify line 722 (model-tier) and line 731 (workload-type) respond to arrow keys
- Action: Add aria-label to dropdowns for clarity (optional enhancement)

**5. Focus Indicators (CSS):**
- Verify .slider-input:focus-visible exists (line 160-163) — YES, present
- Verify .form-input:focus exists (line 201-205) — YES, present
- Verify .faq-item:focus-visible exists (line 427-431) — YES, present
- Verify buttons have focus styles (btn-calculate, btn-primary, btn-secondary)
- Action: All focus styles already in place, no changes needed

**6. Color Contrast Verification:**
- `--text-muted: #8a92a0` on `#0f1115` = 4.6:1 (WCAG AA ✓)
- `.slider-hint` uses text-muted (line 177), acceptable for italic hint text
- `.slider-display` uses text-secondary (line 172), good contrast
- `.result-label` uses text-muted (line 269), 0.85rem/14px — marginal for normal text
- VERIFY but don't change (already meets AA, and label positioning makes it clear)

**7. Mobile Touch Targets:**
- `.slider-input` on mobile: 26px height (line 636) — adequate for touch
- `.btn-calculate` on mobile: min-height 44px (line 614) — meets WCAG AA ✓
- `.form-input` on mobile: 16px font (line 608, prevents zoom) ✓
- `.btn-primary, .btn-secondary` on mobile: min-height 44px (line 627) ✓
- Action: All touch targets already adequate, verify preserved

Test with browser DevTools:
- Tab through all interactive elements (slider, inputs, dropdowns, FAQ items, buttons)
- Use arrow keys on slider (LEFT/RIGHT/UP/DOWN to adjust, PAGE UP/DOWN for larger jumps)
- Verify result values animate when slider moves or dropdown changes
- Check on mobile (375px, 768px viewports):
  - Slider thumb visible and draggable
  - Buttons minimum 44px tall
  - No overlapping touch targets
  </action>
  <verify>
    <automated>
# Manual verification (no automated test framework)
1. Open resources/views/cost-calculator.blade.php
2. Verify 2 main changes applied:
   - Arrow key listener added to slider input (lines 1005-1027, after existing input listener)
   - Animate class trigger added to calculateCosts() function (add animate class to result-value elements)
   - CSS rules for .result-value.animate added (trigger animation restart)
3. Deploy to llmdev.resayil.io and test in browser:
   - Slider interaction:
     * Mouse drag works (existing functionality)
     * Arrow keys (LEFT/RIGHT/UP/DOWN) adjust slider by 1M tokens
     * Page Up/Down adjust by 10M tokens
     * Focus outline visible when tabbed to slider
   - Number input:
     * Type directly, enforces min/max constraints
     * Tab between input and slider, values sync
   - Result animations:
     * When slider moves, cost values animate (slide up + fade in)
     * When model tier changes, all values re-animate
     * When workload changes, all values re-animate
   - Dropdowns:
     * Arrow keys open/close and navigate options
     * Tab focuses dropdown, Enter/Space opens
   - FAQ:
     * Tab to FAQ items, Enter/Space toggles expand/collapse
     * Icon rotates, answer slides down
   - Mobile (Chrome DevTools 375px):
     * Slider thumb is 26px, easily draggable
     * Buttons are 44px+ tall
     * No overlapping interactive elements
     * Form inputs are 16px font (no iOS zoom)
4. Check with Chrome DevTools Lighthouse Accessibility audit:
   - All focus indicators visible (gold outlines)
   - Contrast ratio 4.5:1+ on all text
   - Keyboard navigation complete (no mouse required)
    </automated>
  </verify>
  <done>
Cost calculator meets full accessibility and animation standards:
- Slider fully keyboard controllable (arrow keys, page up/down)
- All result values animate smoothly on every calculation
- Color contrast 4.5:1+ on all text
- All interactive elements keyboard navigable with visible focus
- Mobile touch targets 44px minimum
- FAQ items fully keyboard accessible (Tab, Enter, Space)
  </done>
</task>

</tasks>

<verification>
Cost calculator accessibility and animation audit:
- Keyboard support: Arrow keys adjust slider, Page Up/Down for larger jumps
- Animation: All result values slide up and fade in on change
- Accessibility: Lighthouse score 90+ (no low-contrast warnings, all focus visible)
- Mobile: Touch targets 44px minimum, no overlapping elements
- Keyboard navigation: All elements accessible via Tab, no mouse required
</verification>

<success_criteria>
1. Slider arrow key support implemented (LEFT/RIGHT/UP/DOWN, PAGE UP/DOWN)
2. Number input synchronizes with slider via arrow keys
3. Result values animate on every slider movement and dropdown change
4. Focus indicators visible on all interactive elements when tabbed
5. Mobile touch targets 44px minimum (preserved from existing code)
6. FAQ items keyboard accessible (Tab, Enter/Space)
7. Color contrast 4.5:1+ verified on all text
8. No WCAG AA violations
9. All changes committed with atomic commit message
</success_criteria>

<output>
After completion, create `.planning/quick/16-team-a-cost-calculator-design-fixes-anim/16-SUMMARY.md` with:
- Changes applied (line numbers + before/after)
- Keyboard support verification (arrow keys, page keys)
- Animation enhancements documented
- Touch target verification
- Accessibility audit results
- Date completed
- Ready for merge to dev
</output>
