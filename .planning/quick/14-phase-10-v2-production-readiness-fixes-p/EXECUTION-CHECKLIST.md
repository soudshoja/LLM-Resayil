# Execution Checklist — Task 14 Parallel Team Launch

**Quick Mode Plan:** `.planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md`
**Status:** Ready for 4 parallel agent teams
**Launch Time:** Now
**Expected Completion:** 4–6 hours (parallel)

---

## Pre-Execution Setup

- [x] Plan created: 14-PLAN.md (509 lines)
- [x] Summary written: SUMMARY.md
- [x] Git commits ready (2 commits)
- [x] Design review analyzed (cost-calculator)
- [x] Critical context provided (4 pages, specific violations)
- [x] Files to modify identified (all 4 blade templates)
- [x] Verification steps documented

---

## Team A — /cost-calculator

**Agent Launch Command:**
```
/gsd:quick execute .planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md --task "Task 1: Team A"
```

**Deliverables:**
- [ ] Slider aria-label, aria-describedby, aria-value* attributes
- [ ] FAQ keyboard navigation (Enter/Space)
- [ ] Text contrast #8a92a0 on #0f1115
- [ ] Mobile slider thumb 26px
- [ ] Number input fallback
- [ ] All verification steps passed
- [ ] Commit ready with message: `fix(cost-calculator): add WCAG AA accessibility (slider ARIA, FAQ keyboard nav, contrast)`

**Expected Duration:** 90–120 minutes
**Status:** Pending launch

---

## Team B — /comparison

**Agent Launch Command:**
```
/gsd:quick execute .planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md --task "Task 2: Team B"
```

**Deliverables:**
- [ ] HTML validation: 0 errors (W3C)
- [ ] Table rows properly nested
- [ ] CSS variable --comp-text-secondary defined
- [ ] Focus styling on all interactive elements
- [ ] Mobile buttons >= 44px
- [ ] FAQ keyboard navigation
- [ ] All verification steps passed
- [ ] Commit ready with message: `fix(comparison): WCAG AA compliance (HTML validation, focus states, mobile buttons)`

**Expected Duration:** 90–120 minutes
**Status:** Pending launch

---

## Team C — /alternatives

**Agent Launch Command:**
```
/gsd:quick execute .planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md --task "Task 3: Team C"
```

**Deliverables:**
- [ ] Accordion keyboard navigation (Enter/Space)
- [ ] Focus states on all interactive elements
- [ ] Mobile text >= 14px (15-16px preferred)
- [ ] CSS extracted to separate file
- [ ] Tablet breakpoint (768px–1024px) working
- [ ] All verification steps passed
- [ ] Commit ready with message: `fix(alternatives): keyboard nav, focus states, CSS extraction, mobile optimization`

**Expected Duration:** 120–150 minutes
**Status:** Pending launch

---

## Team D — /dedicated-server

**Agent Launch Command:**
```
/gsd:quick execute .planning/quick/14-phase-10-v2-production-readiness-fixes-p/14-PLAN.md --task "Task 4: Team D"
```

**Deliverables:**
- [ ] All icons have aria-label or aria-hidden
- [ ] FAQ keyboard navigation (Enter/Space)
- [ ] Focus indicators on all interactive elements
- [ ] Emoji have aria-labels
- [ ] Footer polish and accessibility
- [ ] All verification steps passed
- [ ] Commit ready with message: `fix(dedicated-server): ARIA labels, keyboard FAQ nav, focus styling, footer polish`

**Expected Duration:** 90–120 minutes
**Status:** Pending launch

---

## Synchronization & Commits

When all 4 teams complete:

### Merge & Deploy
```bash
# All at once (don't commit individually)
cd /d/Claude/projects/LLM-Resayil

# Deploy to dev
ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"

# Test all 4 pages on dev
# https://llmdev.resayil.io/cost-calculator
# https://llmdev.resayil.io/comparison
# https://llmdev.resayil.io/alternatives
# https://llmdev.resayil.io/dedicated-server

# If all pass QA:
git checkout main
git merge dev
git push origin main

# Deploy to prod
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"

# Tag
git tag v1.10.0
git push origin --tags
```

---

## Success Criteria — Global

**All Teams Must Achieve:**
- [ ] Zero HTML validation errors (W3C)
- [ ] WCAG AA compliance (4.5:1 contrast, keyboard nav, ARIA labels)
- [ ] Focus indicators visible (gold #d4af37)
- [ ] Mobile touch targets >= 44px
- [ ] Keyboard navigation working (Tab, Enter, Space)
- [ ] No ARIA violations
- [ ] Page looks identical (design-wise) — only accessibility/validation fixes

---

## Known Constraints

- **No logic changes** — Only accessibility/design/validation fixes
- **No new dependencies** — Use only existing libraries
- **No breaking changes** — Backward compatible
- **Parallel execution** — Teams work independently, no blocking dependencies
- **Same Git branch** — All work on dev, merge together

---

## Risk Assessment

**Risk Level:** LOW
- No database changes
- No API modifications
- No authentication changes
- Only HTML/CSS/ARIA additions
- Fully reversible if needed

---

## Testing Sequence

1. **Each team tests independently** (60+ min into execution)
2. **Team synchronization** (after completion)
3. **Dev deployment** (all teams confirm ready)
4. **Full QA round** (all 4 pages on dev)
5. **Prod deployment** (if all pass)

---

## Communication Checklist

- [ ] All 4 teams notified of launch
- [ ] Each team has their PLAN.md task section
- [ ] Verification steps clear and testable
- [ ] Synchronization point defined (when to merge commits)
- [ ] Rollback plan communicated (revert commits if needed)

---

## Next Steps After Completion

1. ✓ All teams commit and push
2. ✓ Merge to main
3. ✓ Deploy to prod
4. ✓ Submit URLs to Google Search Console (new pages indexed)
5. ✓ Monitor Lighthouse scores (should all be 90+)
6. ✓ Verify SERP presence of all 4 new pages

---

**Plan Status:** READY FOR IMMEDIATE EXECUTION
**Date:** 2026-03-06
**Last Updated:** 07:05 UTC
