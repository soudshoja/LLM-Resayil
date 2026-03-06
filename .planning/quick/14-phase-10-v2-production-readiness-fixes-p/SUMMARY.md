# Task 14 — Phase 10 v2 Production Readiness Fixes

**Date:** 2026-03-06
**Status:** PLAN COMPLETE — Ready for parallel execution
**Teams:** 4 parallel agent teams
**Duration:** 4–6 hours total
**Pages Fixed:** /cost-calculator, /comparison, /alternatives, /dedicated-server

---

## What's Being Fixed

### Team A — /cost-calculator (Score: 8.2/10)
- Slider aria-label, aria-describedby, aria-value* attributes
- FAQ keyboard navigation (Enter/Space)
- Text contrast fix (#6b7280 → #8a92a0)
- Mobile slider thumb 26px
- Number input fallback

### Team B — /comparison (Score: 7.8/10)
- HTML validation errors (malformed table rows)
- CSS variable undefined (--comp-text-secondary)
- Focus styling on all interactive elements
- Mobile button minimum height 44px
- FAQ keyboard navigation

### Team C — /alternatives (Score: 7.8/10)
- Accordion keyboard navigation
- Focus states on all interactive elements
- Mobile font sizes >= 14px
- CSS extraction (separate file)
- Tablet breakpoint (768px–1024px)

### Team D — /dedicated-server (Score: 8.5/10)
- ARIA labels on all icons/emojis
- FAQ keyboard navigation (onclick → button)
- Focus styling on all interactive elements
- Footer polish and accessibility
- Semantic HTML improvements

---

## Key Metrics

| Metric | Target |
|--------|--------|
| HTML Validation Errors | 0 |
| WCAG AA Compliance | 100% |
| Color Contrast | >= 4.5:1 |
| Mobile Touch Targets | >= 44px |
| Focus Indicators | All interactive elements |
| Keyboard Navigation | Tab, Enter, Space working |

---

## Execution Plan

1. **Teams Launch Simultaneously** → 4 parallel executions (T+0)
2. **15 min:** Initial exploration, violations identified
3. **60 min:** Core fixes in place, testing starts
4. **120 min:** All tasks complete verification
5. **180 min:** Testing complete, commits ready

**Total: 4–6 hours parallel (NOT sequential)**

---

## Verification Gate

Each team must pass before commit:
- ✓ Keyboard navigation (Tab, Enter, Space)
- ✓ Focus indicators visible (gold #d4af37)
- ✓ Color contrast >= 4.5:1
- ✓ Mobile >= 44px touch targets
- ✓ ARIA labels on all icons
- ✓ HTML validation 0 errors (W3C)

---

## Deployment

After all teams complete:
1. Commit to dev (all teams at once)
2. Deploy to dev: `ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"`
3. Full QA (all 4 pages on dev)
4. Merge dev → main
5. Deploy to prod
6. Tag: v1.10.0

---

## Estimated Effort

- **Team A:** 90–120 min
- **Team B:** 90–120 min
- **Team C:** 120–150 min (CSS extraction adds complexity)
- **Team D:** 90–120 min

**Parallel Total:** 120–150 min (NOT 390–510 min sequential)

---

## Next Steps

1. Launch 4 agents with /gsd:execute-quick on 14-PLAN.md
2. Each team works independently on their page
3. Synchronize commits when all teams complete
4. Deploy together to dev
5. QA + merge to prod

**Plan File:** `.planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md`
