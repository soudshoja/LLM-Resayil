---
phase: quick-10
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - resources/views/billing/plans.blade.php
autonomous: true
requirements: [QUICK-10]
must_haves:
  truths:
    - "Clicking anywhere on a top-up card opens the payment modal"
    - "The cursor shows as pointer over the entire card area"
    - "The inner price button does not trigger a double modal open"
  artifacts:
    - path: "resources/views/billing/plans.blade.php"
      provides: "Top-up cards with full-box click area"
      contains: "onclick=\"openPaymentModal"
  key_links:
    - from: ".topup-card div"
      to: "openPaymentModal('topup', 'X')"
      via: "onclick on the card div"
      pattern: "topup-card.*onclick"
---

<objective>
Make each top-up credit card on /billing/plans fully clickable — clicking anywhere on the card box triggers the payment modal, not just the small price button inside it.

Purpose: Improves UX — users expect the entire card to be clickable, not just a small button within it.
Output: Modified billing/plans.blade.php with onclick on each .topup-card div; inner .topup-buy button stops propagation to prevent double-fire.
</objective>

<execution_context>
@D:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
</execution_context>

<context>
@D:\Claude\projects\LLM-Resayil\resources\views\billing\plans.blade.php
</context>

<interfaces>
<!-- Existing openPaymentModal signature (already in the file): -->
<!--   openPaymentModal(type, value) -->
<!--   e.g. openPaymentModal('topup', '500') -->

<!-- Current topup card HTML (lines ~256-273): -->
<!--
<div class="topup-card">
    <div class="topup-credits">500</div>
    <div class="topup-price">{{ __('billing.credits') }}</div>
    <div class="topup-bonus">{{ __('billing.topup_no_bonus') }}</div>
    <button type="button" class="topup-buy" onclick="openPaymentModal('topup', '500')">5 KWD</button>
</div>
-->

<!-- CSS already has cursor:pointer on .topup-card (line 41) — no CSS changes needed. -->
</interfaces>

<tasks>

<task type="auto">
  <name>Task 1: Make top-up cards fully clickable</name>
  <files>resources/views/billing/plans.blade.php</files>
  <action>
In resources/views/billing/plans.blade.php, locate the three .topup-card divs (around lines 256-273).

For each of the three .topup-card divs, add onclick directly on the card div element:

Card 1 (500 credits / 5 KWD):
  Change: `<div class="topup-card">`
  To:     `<div class="topup-card" onclick="openPaymentModal('topup', '500')">`

Card 2 (1,100 credits / 10 KWD):
  Change: `<div class="topup-card">`
  To:     `<div class="topup-card" onclick="openPaymentModal('topup', '1100')">`

Card 3 (3,000 credits / 25 KWD):
  Change: `<div class="topup-card">`
  To:     `<div class="topup-card" onclick="openPaymentModal('topup', '3000')">`

For each .topup-buy button inside those cards, change the onclick to stop propagation so clicking the button doesn't fire BOTH the button handler AND the card handler (which would open the modal twice):
  Change: `onclick="openPaymentModal('topup', 'X')"`
  To:     `onclick="event.stopPropagation(); openPaymentModal('topup', 'X')"`

Do NOT change any other part of the file. Do NOT touch CSS — .topup-card already has cursor:pointer defined on line 41.

Summary of changes (3 card divs + 3 buttons):
- .topup-card div (500)  → add onclick="openPaymentModal('topup', '500')"
- .topup-buy button (5 KWD) → change to event.stopPropagation(); openPaymentModal('topup', '500')
- .topup-card div (1100) → add onclick="openPaymentModal('topup', '1100')"
- .topup-buy button (10 KWD) → change to event.stopPropagation(); openPaymentModal('topup', '1100')
- .topup-card div (3000) → add onclick="openPaymentModal('topup', '3000')"
- .topup-buy button (25 KWD) → change to event.stopPropagation(); openPaymentModal('topup', '3000')"
  </action>
  <verify>
    <automated>grep -n "topup-card.*onclick\|topup-buy.*stopPropagation" D:\Claude\projects\LLM-Resayil\resources\views\billing\plans.blade.php</automated>
  </verify>
  <done>
All three .topup-card divs have onclick="openPaymentModal('topup', 'X')" on the div itself. All three .topup-buy buttons use event.stopPropagation() before calling openPaymentModal. Clicking anywhere on the card (not just the button) opens the payment modal exactly once.
  </done>
</task>

</tasks>

<verification>
After making the changes, verify by inspecting the file:
- 3 occurrences of `topup-card.*onclick` in the grep output
- 3 occurrences of `stopPropagation` near the .topup-buy buttons
- No syntax errors in the Blade template (the file is plain HTML/PHP — no compilation step needed locally)

On dev (after deploying): visit https://llmdev.resayil.io/billing/plans, click any area of a top-up card (not just the button) — the payment method modal should appear.
</verification>

<success_criteria>
- Clicking the card background/credits text/bonus text on any of the 3 top-up cards opens the payment modal
- cursor:pointer is already applied via existing CSS — no change needed
- Clicking the price button still opens the modal exactly once (stopPropagation prevents double-fire)
- No regressions to subscription plan cards, extra API key section, or the payment modal itself
</success_criteria>

<output>
No SUMMARY.md required for quick tasks. Commit the change with:
git add resources/views/billing/plans.blade.php
git commit -m "fix(billing): make full top-up card box clickable, not just inner button"
Then deploy to dev: ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
</output>
