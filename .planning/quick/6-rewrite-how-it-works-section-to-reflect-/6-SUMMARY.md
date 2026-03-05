---
phase: quick-6
plan: 01
subsystem: landing
tags: [landing, copy, how-it-works, template-3]
dependency_graph:
  requires: []
  provides: [accurate-hiw-copy]
  affects: [template-3.blade.php]
tech_stack:
  added: []
  patterns: [blade-template-editing]
key_files:
  modified:
    - resources/views/landing/template-3.blade.php
decisions:
  - How It Works steps rewritten to match real registration flow (phone OTP, credit top-up, API key, plug-in)
  - Code example and badges moved from Step 3/4 old to Step 4 only
  - Section subtitle updated to plain-language summary of the 4-step journey
metrics:
  duration: ~10 minutes
  completed: 2026-03-05
  tasks_completed: 2
  files_modified: 1
---

# Quick Task 6: Rewrite How It Works Section Summary

**One-liner:** Rewrote the 4-step How It Works section on template-3 landing page to reflect the real user journey — WhatsApp OTP phone signup, credit top-up via card, API key generation, and one-line SDK integration.

## What Was Changed

### Section subtitle (`.ssub`)
- **Before:** "No chat interface. No new tool to learn. Just plug our endpoint into your existing app and go."
- **After:** "No new tools to learn. Register, add credits, grab your API key, and you are ready to go."

### Step 1
- **Before:** "Sign Up & Choose a Plan" — claimed "7-day free trial — no credit card required"
- **After:** "Create Your Account" — explains phone number + WhatsApp OTP verification

### Step 2
- **Before:** "Generate Your API Key" (was Step 2 in old flow)
- **After:** "Add Credits to Your Wallet" — explains credit top-up via card, pay-per-use model

### Step 3
- **Before:** "Point Your App at Our Endpoint" — had code example with base_url
- **After:** "Get Your API Key" — dashboard API key generation, no code block in this step

### Step 4
- **Before:** "Use It Everywhere" — badges block but no code example
- **After:** "Plug It Into Your App" — code example showing `https://llm.resayil.io/api/v1` as base_url + updated badges (added ChatGPT Plugins)

## Key Facts Verified
- No mention of "no credit card required" in the How It Works steps
- WhatsApp OTP verification explicitly mentioned in Step 1
- Correct base URL `https://llm.resayil.io/api/v1` shown in Step 4 code block
- All CSS classes preserved: `.hiw-step`, `.hiw-num`, `.hiw-content`, `.hiw-code`, `.hiw-badges`, `.hiw-str`, `.hiw-code-comment`

## Git Commit

`8dca914` — feat(landing): rewrite how-it-works section to reflect real user journey — phone OTP signup, credit top-up, API key, plug-in

## Deployment

- Pushed to: `origin/dev`
- Deployed to: `https://llmdev.resayil.io`
- Dev server confirmed at commit: `8dca914`

## Visual Verification

Playwright screenshots taken of https://llmdev.resayil.io/landing/3#how-it-works:

- Steps 1-3 visible: "Create Your Account" (WhatsApp OTP copy), "Add Credits to Your Wallet" (pay-per-use copy), "Get Your API Key" (one-click dashboard copy)
- Step 4 visible: "Plug It Into Your App" with code block showing correct base_url in gold, all 6 badges rendered (ChatGPT Plugins, Cursor, n8n, Python SDK, Node.js, Any OpenAI SDK)
- Dark luxury styling intact — gold numbered circles, dark card backgrounds, hover effects functional

## Self-Check

- [x] `resources/views/landing/template-3.blade.php` modified with correct content
- [x] Commit `8dca914` exists on dev branch
- [x] Dev server shows commit `8dca914`
- [x] No "no credit card required" text in How It Works steps
- [x] WhatsApp mentioned in Step 1
- [x] `https://llm.resayil.io/api/v1` in Step 4 code block
- [x] All CSS class names preserved

## Self-Check: PASSED
