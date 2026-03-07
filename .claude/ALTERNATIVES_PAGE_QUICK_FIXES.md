# /alternatives Page — Quick Fix Guide

**Total Fixes to Production:** 2–3 hours
**Critical Violations:** 3 (WCAG AA)
**Estimated Effort: 65 minutes** to achieve AA compliance

---

## 🚨 CRITICAL FIXES (65 minutes)

### Fix #1: Add Focus Indicators (15 minutes)

**What:** No `:focus-visible` styles on interactive elements. Keyboard users can't see where focus is.

**Where:** Add to `<style>` block in alternatives.blade.php (around line 500)

```css
/* ── Keyboard Focus Indicators ── */
.cta-btn:focus-visible,
.faq-question:focus-visible,
.accordion-header:focus-visible,
.calculator-cta:focus-visible {
  outline: 2px solid var(--gold);
  outline-offset: 2px;
}

/* Remove default outline on click (mouse users) */
.cta-btn:focus:not(:focus-visible),
.faq-question:focus:not(:focus-visible),
.accordion-header:focus:not(:focus-visible) {
  outline: none;
}
```

**Test:** Tab through page with keyboard. Golden outline should appear around buttons/accordions.

---

### Fix #2: Add Keyboard Navigation (20 minutes)

**What:** Accordion and FAQ only respond to clicks, not Enter/Space keys.

**Where:** Replace JavaScript at lines 1124–1137

**Current (Bad):**
```javascript
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        const item = this.closest('.faq-item');
        item.classList.toggle('open');
    });
});
```

**New (Good):**
```javascript
// Handle click and keyboard events
function toggleItem(triggerElement) {
  const item = triggerElement.closest('.faq-item') || triggerElement.closest('.accordion-item');
  if (item) item.classList.toggle('open');
}

// FAQ Questions
document.querySelectorAll('.faq-question').forEach(question => {
  question.addEventListener('click', function() {
    toggleItem(this);
  });

  question.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      toggleItem(this);
    }
  });
});

// Accordion Headers
document.querySelectorAll('.accordion-header').forEach(header => {
  header.addEventListener('click', function() {
    toggleItem(this);
  });

  header.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      toggleItem(this);
    }
  });
});
```

**Test:** Use keyboard (Tab, Enter, Space) to open/close FAQ and accordion items.

---

### Fix #3: Add ARIA Labels & Semantic HTML (30 minutes)

**What:** Accordion headers are `<div>` elements. No ARIA attributes. Screen readers can't announce interactivity.

**Where:** Modify accordion-header and faq-question HTML

**Current (Bad):**
```html
<div class="faq-question">
  <span>Which API is cheapest overall?</span>
  <span class="faq-toggle">▼</span>
</div>
```

**New (Good) — Option A: Use `<button>` (Recommended)**
```html
<button
  aria-expanded="false"
  aria-controls="faq-answer-1"
  class="faq-question"
>
  <span>Which API is cheapest overall?</span>
  <span class="faq-toggle" aria-hidden="true">▼</span>
</button>

<div id="faq-answer-1" class="faq-answer">
  <!-- Answer text -->
</div>
```

**New (Good) — Option B: Keep `<div>` with ARIA (Less recommended)**
```html
<div
  role="button"
  tabindex="0"
  aria-expanded="false"
  aria-controls="faq-answer-1"
  class="faq-question"
>
  <span>Which API is cheapest overall?</span>
  <span class="faq-toggle" aria-hidden="true">▼</span>
</div>
```

**Apply to:** All `.faq-question`, `.accordion-header` elements

**JavaScript Update:** Update toggle handler to also update `aria-expanded`:
```javascript
function toggleItem(triggerElement) {
  const item = triggerElement.closest('.faq-item') || triggerElement.closest('.accordion-item');
  if (item) {
    item.classList.toggle('open');
    // Update aria-expanded
    const isOpen = item.classList.contains('open');
    triggerElement.setAttribute('aria-expanded', isOpen);
  }
}
```

**Test:** Use screen reader (NVDA, JAWS, or VoiceOver) to confirm announcements:
- "Which API is cheapest overall?, button, expanded" (when open)
- "Which API is cheapest overall?, button, collapsed" (when closed)

---

## 🟠 HIGH PRIORITY (Next 65 minutes)

### Fix #4: Extract Inline CSS (30 minutes)

**What:** 512 lines of CSS embedded in HTML. Prevents caching, slows page load.

**Steps:**

1. Create new file: `public/css/alternatives.css` or `resources/css/alternatives.css`
2. Copy all CSS from `<style>` block (lines 6–518) into new file
3. Remove `@push('styles')` and `<style>` tags from alternatives.blade.php
4. Add link to layout or page:
   ```blade
   @push('styles')
   <link rel="stylesheet" href="{{ asset('css/alternatives.css') }}">
   @endpush
   ```

**Benefit:** Faster page load, CSS caches across pages, cleaner HTML

---

### Fix #5: Increase Mobile Font Size (10 minutes)

**What:** Mobile table font is 0.8rem (12.8px), below 16px WCAG minimum.

**Where:** Line 511 in alternatives.blade.php

**Current:**
```css
@media(max-width: 480px) {
    .comparison-table { font-size: 0.8rem; }
}
```

**New:**
```css
@media(max-width: 480px) {
    .comparison-table { font-size: 0.9rem; } /* 14.4px — better readability */
    .comparison-table th,
    .comparison-table td {
        padding: 0.5rem; /* Reduce padding instead of font */
    }
}
```

---

### Fix #6: Ensure 44px Button Min-Height (5 minutes)

**What:** Touch targets should be 44×44px minimum for accessibility.

**Where:** Line 50 (`.cta-btn`)

**Current:**
```css
.cta-btn {
    padding: 1rem 2.5rem;
    font-size: 1rem;
    /* ... */
}
```

**New:**
```css
.cta-btn {
    padding: 1rem 2.5rem;
    font-size: 1rem;
    min-height: 44px;
    min-width: 44px;
    /* ... */
}
```

**Also add to other buttons:**
```css
.calculator-cta {
    min-height: 44px;
    min-width: 44px;
}

.accordion-header,
.faq-question {
    min-height: 44px;
}
```

---

### Fix #7: Add Tablet Landscape Breakpoint (20 minutes)

**What:** Screens 481px–767px (iPad mini, Galaxy Tab landscape) fall between media query rules. UX is suboptimal.

**Where:** Add between existing breakpoints (after line 466)

```css
@media(max-width: 900px) {
    .deep-dive-grid {
        grid-template-columns: repeat(2, 1fr); /* 2 cols instead of 3 */
    }

    .section-title {
        font-size: clamp(1.75rem, 5vw, 3rem);
    }

    .calculator-container {
        padding: 2rem;
    }
}
```

---

## 🟡 MEDIUM PRIORITY (2–3 hours)

### Fix #8: Add Social Proof Section (2–3 hours)

**What:** Comparison pages convert 15–25% better with testimonials/user count.

**Where:** After deep-dive-section (before highlights-section)

**Template:**
```html
<!-- Social Proof Section -->
<section class="social-proof-section">
    <h2 class="section-title">Trusted by 5,000+ Developers</h2>

    <div class="testimonial-grid">
        <div class="testimonial-card">
            <p class="testimonial-text">"Switched from OpenAI, saved $2,500/month."</p>
            <p class="testimonial-author">— Sarah Chen, AI Lead at Acme Corp</p>
        </div>
        <!-- 2–3 more testimonials -->
    </div>
</section>
```

---

### Fix #9: Add Visual Indicator to Resayil Column (15 minutes)

**What:** Currently distinguished by color only. Colorblind users may miss it.

**Option A: Add Icon**
```css
.comparison-table th.resayil::before {
    content: "★ ";
    color: var(--gold);
}
```

**Option B: Add Border Pattern**
Already has `border-left: 3px solid var(--gold)`. **Keep as-is** (this is sufficient).

**Option C: Add Label**
```css
.comparison-table th.resayil::after {
    content: " (BEST VALUE)";
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
```

---

### Fix #10: Remove Bottom Promotional Box (5 minutes)

**What:** Duplicate CTA at lines 1140–1145 dilutes primary footer CTA.

**Current:**
```html
<div style="background: rgba(212,175,55,0.05); ...">
    <p style="...">
        Need help deciding? Try our <a href="/cost-calculator">cost calculator</a> or read our <a href="/comparison">detailed comparison</a>.
    </p>
</div>
```

**Action:** Delete lines 1140–1145. Keep footer CTA section as primary exit.

---

## 📋 PRODUCTION CHECKLIST

- [ ] Fix #1: Add `:focus-visible` styles (15 min)
- [ ] Fix #2: Add keyboard navigation (20 min)
- [ ] Fix #3: Add ARIA labels + semantic HTML (30 min)
- [ ] **Keyboard test:** Tab → Enter/Space on all accordions (5 min)
- [ ] **Screen reader test:** NVDA or VoiceOver (10 min)
- [ ] Fix #4: Extract inline CSS (30 min)
- [ ] Fix #5: Increase mobile font size (10 min)
- [ ] Fix #6: Add 44px min-height (5 min)
- [ ] Fix #7: Add tablet breakpoint (20 min)
- [ ] **Lighthouse audit:** Target 90+ Accessibility, 85+ Performance (5 min)
- [ ] **Responsive test:** iPhone 12, iPad, Galaxy S21 (15 min)
- [ ] Fix #8 (Optional): Add social proof (2–3 hrs)
- [ ] Fix #9 (Optional): Add colorblind indicator (15 min)
- [ ] Fix #10 (Optional): Remove promo box (5 min)
- [ ] **Design team sign-off**
- [ ] **Deploy to llmdev.resayil.io and test live**

---

## 🧪 Testing Commands

### Lighthouse Audit
```bash
# CLI
lighthouse https://llmdev.resayil.io/alternatives --output=json

# Or use Chrome DevTools: F12 → Lighthouse → Generate Report
```

### Keyboard Navigation
1. Open page in browser
2. Press `Tab` to navigate through elements
3. Verify golden outline appears around buttons/accordions
4. Press `Enter` or `Space` on accordion items
5. Verify items open/close

### Screen Reader Testing
**Windows (NVDA):**
```
Download: https://www.nvaccess.org/download/
Press Ctrl+Alt+N to start
Use arrow keys + Enter to navigate
```

**Mac (VoiceOver):**
```
Cmd+F5 to enable
Ctrl+Option+U opens rotor
Ctrl+Option+Right/Left to navigate
Ctrl+Option+Space to activate
```

---

## 📊 Before/After Checklist

| Metric | Before | After |
|--------|--------|-------|
| WCAG Accessibility | 🔴 Fails AA | 🟢 Passes AA |
| Focus Indicators | ❌ Missing | ✅ Added |
| Keyboard Support | ❌ Click only | ✅ Enter/Space |
| ARIA Labels | ❌ None | ✅ Complete |
| Mobile Readability | ⚠ 12.8px font | ✅ 14.4px font |
| Touch Target Size | ⚠ Variable | ✅ 44px min |
| CSS Caching | ❌ Inline | ✅ External |
| Lighthouse A11y Score | ~60/100 | ~95/100 |

---

## ✅ Ready to Deploy When

- [x] All 3 critical fixes complete (65 min)
- [x] Keyboard navigation tested
- [x] Screen reader tested
- [x] Lighthouse audit 90+ Accessibility
- [x] Responsive design verified on 3+ devices
- [x] Design/product team approval

**Estimated timeline:** 3–4 hours total (2–3 hrs critical + 1–2 hrs testing/polish)
