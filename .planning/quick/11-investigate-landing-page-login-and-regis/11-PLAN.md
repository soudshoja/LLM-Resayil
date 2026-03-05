---
phase: quick-11
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: []
autonomous: true
requirements: [INVESTIGATE-01]
must_haves:
  truths:
    - "A diagnosis report exists at .planning/quick/11-investigate-landing-page-login-and-regis/DIAGNOSIS.md"
    - "Every identified failure has a root cause (not just a symptom)"
    - "The report distinguishes prod vs dev behavior where they differ"
  artifacts:
    - path: ".planning/quick/11-investigate-landing-page-login-and-regis/DIAGNOSIS.md"
      provides: "Root cause findings for login and registration failures on /landing/3"
  key_links:
    - from: "template-3.blade.php JS"
      to: "POST /register/otp and POST /register"
      via: "fetch() with Accept: application/json + X-CSRF-TOKEN header"
      pattern: "fetch.*register"
---

<objective>
Investigate why login and registration do not work on the landing page at
https://llm.resayil.io/landing/3 and https://llmdev.resayil.io/landing/3.

Purpose: Produce a precise diagnosis so a follow-up fix can be implemented without
guesswork. Zero code changes in this plan — investigation only.

Output: DIAGNOSIS.md with root causes, HTTP response details, and a prioritised fix list.
</objective>

<execution_context>
@D:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
</execution_context>

<context>
@D:/Claude/projects/LLM-Resayil/CLAUDE.md
@D:/Claude/projects/LLM-Resayil/resources/views/landing/template-3.blade.php
@D:/Claude/projects/LLM-Resayil/app/Http/Controllers/Auth/RegisteredUserController.php
@D:/Claude/projects/LLM-Resayil/app/Http/Controllers/Auth/AuthenticatedSessionController.php
@D:/Claude/projects/LLM-Resayil/routes/web.php

<pre_analysis>
## What source-code reading has already revealed

### Login on /landing/3
The landing page has NO embedded login form. "Sign In" is a plain anchor:
  href="/login"
This simply navigates the user to the separate /login page. The /login page
uses its own AJAX form. The landing page itself cannot break login — but Playwright
must confirm /login is reachable and the flow completes end-to-end.

### Registration on /landing/3 — AJAX flow (2 steps)
Step 1: JS sends POST /register/otp
  Fields: { name, email, phone: '965'+localDigits, password, password_confirmation }
  Headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' }
  Expected response: 200 JSON { step: 'verify', phone: '...' }

Step 2: JS sends POST /register
  Fields: same payload + { otp_code }
  Expected response: 201 JSON { message: 'Registration successful.' }
  On success: JS redirects to /dashboard

### Potential failure points — confirm/deny each with Playwright/curl

H1: CSRF token missing or empty in landing page HTML
  - The meta[name="csrf-token"] tag IS in template-3.blade.php (line 6)
  - JS reads it via document.querySelector('meta[name="csrf-token"]')
  - Must confirm the rendered value is non-empty on both prod and dev

H2: POST /register/otp returns non-200 or wrong JSON shape
  - Backend sendOtp() returns JSON { step: 'verify', phone: '...' } on success
  - JS checks: if (res.ok && data.step === 'verify') to proceed to step 2
  - Any 4xx/5xx or missing step field will show an error or stall

H3: guest middleware on /register/otp causes 302 for unauthenticated AJAX request
  - RedirectIfAuthenticated only blocks logged-in users — guests can POST fine
  - But if a 302 is returned (e.g., server config rewriting), fetch() follows it
    silently, returning HTML, and res.json() throws → caught as "network error"
  - Likely DENIED but must confirm via response headers

H4: Phone number validation rejects the submitted value
  - Input name="phone_local" collects local digits only (e.g., 9XXXXXXX)
  - JS prepends '965' and sends phone: '965XXXXXXXX'
  - Backend rule: 'phone' => 'required|numeric|unique:users,phone'
  - The '965...' string IS numeric, so format is fine
  - Only fails if that phone already exists in the DB

H5: Password confirmation field missing
  - Landing page has no separate confirm-password input
  - JS explicitly sends password_confirmation = password value
  - Backend rule: 'password' => 'required|string|min:8|confirmed'
  - The 'confirmed' rule checks for password_confirmation field matching password
  - JS sends it — should pass

H6: /register/otp route not on prod
  - Template 3 was built on dev; if never merged to main, prod 404s this route
  - Must check route:list on prod server

H7: /login page returns non-200 HTTP status (prod or dev)
  - Must confirm GET /login is reachable from both environments

H8: After successful AJAX register, session not set (cookie missing)
  - Auth::login($user) IS called in store() — session should be set
  - Must check Set-Cookie headers in the response

H9: template-3 is an older version on prod without the AJAX registration form
  - If /landing/3 on prod shows a different version of the page, the form may not
    be present or may have a different implementation

H10: The /register route on prod is only on dev branch (not merged to main yet)
  - Check git log on prod for the template-3 + register/otp commit
</pre_analysis>
</context>

<tasks>

<task type="auto">
  <name>Task 1: Playwright and curl investigation of /landing/3 on dev and prod</name>
  <files>.planning/quick/11-investigate-landing-page-login-and-regis/DIAGNOSIS.md</files>
  <action>
Run a systematic investigation using Playwright (if available) or curl (fallback).
Do NOT modify any application files.

## Step A: Check Playwright availability
```bash
npx playwright --version 2>/dev/null || echo "NOT_AVAILABLE"
```

## Step B: Playwright investigation (if available)

Create /tmp/landing-investigate.js:

```javascript
const { chromium } = require('playwright');

async function investigate(url, label) {
  console.log('\n=== ' + label + ' ===');
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  const networkLog = [];
  const consoleErrors = [];

  page.on('request', req => {
    if (/register|login/.test(req.url())) {
      networkLog.push({ type: 'REQ', method: req.method(), url: req.url(), body: req.postData() });
    }
  });
  page.on('response', async res => {
    if (/register|login/.test(res.url())) {
      let body = '';
      try { body = await res.text(); } catch(e) { body = '[unreadable]'; }
      networkLog.push({ type: 'RES', status: res.status(), url: res.url(), body: body.slice(0, 600) });
    }
  });
  page.on('console', msg => { if (msg.type() === 'error') consoleErrors.push(msg.text()); });

  await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
  console.log('Title:', await page.title());

  const csrf = await page.$eval('meta[name="csrf-token"]', el => el.content).catch(() => 'NOT_FOUND');
  console.log('CSRF token:', csrf ? csrf.slice(0,20)+'...' : 'MISSING');

  const regForm = await page.$('#reg-form');
  console.log('Registration form present:', !!regForm);
  if (!regForm) { await browser.close(); return; }

  const testPhone = '' + (90000000 + Math.floor(Math.random() * 9000000));
  const testEmail = 'tst' + Date.now() + '@example.com';
  await page.fill('#r-name', 'Test User');
  await page.fill('#r-email', testEmail);
  await page.fill('#r-phone', testPhone);
  await page.fill('#r-pw', 'TestPass123');
  await page.click('#reg-btn');
  await page.waitForTimeout(3000);

  const errEl = await page.$eval('#gerr', el => ({ on: el.classList.contains('on'), msg: el.textContent.trim() })).catch(() => null);
  const step2On = await page.$eval('#reg-step2', el => el.classList.contains('on')).catch(() => false);
  console.log('Error shown:', errEl);
  console.log('Step 2 (OTP input) visible:', step2On);
  console.log('Network log:', JSON.stringify(networkLog, null, 2));
  console.log('Console errors:', consoleErrors);
  await browser.close();
}

(async () => {
  try { await investigate('https://llmdev.resayil.io/landing/3', 'DEV'); } catch(e) { console.error('DEV error:', e.message); }
  try { await investigate('https://llm.resayil.io/landing/3', 'PROD'); } catch(e) { console.error('PROD error:', e.message); }
})();
```

Run: `node /tmp/landing-investigate.js`

## Step C: curl investigation (always run, even if Playwright is available — more detail)

### C1: Get CSRF token from landing page (dev)
```bash
curl -s -c /tmp/ck-dev.txt "https://llmdev.resayil.io/landing/3" -o /tmp/landing-dev.html
CSRF_DEV=$(grep -oP '(?<=name="csrf-token" content=")[^"]+' /tmp/landing-dev.html | head -1)
echo "DEV CSRF (first 20): ${CSRF_DEV:0:20}..."
echo "DEV CSRF length: ${#CSRF_DEV}"
```

### C2: Get CSRF token from landing page (prod)
```bash
curl -s -c /tmp/ck-prod.txt "https://llm.resayil.io/landing/3" -o /tmp/landing-prod.html
CSRF_PROD=$(grep -oP '(?<=name="csrf-token" content=")[^"]+' /tmp/landing-prod.html | head -1)
echo "PROD CSRF (first 20): ${CSRF_PROD:0:20}..."
echo "PROD CSRF length: ${#CSRF_PROD}"
# Also check if registration form exists on prod
grep -c 'reg-form\|register/otp' /tmp/landing-prod.html && echo "Form/route found on prod" || echo "Form/route NOT found on prod"
```

### C3: POST to /register/otp on DEV
```bash
PHONE_DEV="965$(shuf -i 90000000-99999999 -n 1)"
EMAIL_DEV="tst$(date +%s)@example.com"
echo "Testing with phone=$PHONE_DEV email=$EMAIL_DEV"

curl -v -b /tmp/ck-dev.txt -c /tmp/ck-dev.txt \
  -X POST "https://llmdev.resayil.io/register/otp" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: $CSRF_DEV" \
  -d "{\"name\":\"Test User\",\"email\":\"$EMAIL_DEV\",\"phone\":\"$PHONE_DEV\",\"password\":\"TestPass123\",\"password_confirmation\":\"TestPass123\"}" \
  2>&1
```

### C4: POST to /register/otp on PROD
```bash
PHONE_PROD="965$(shuf -i 90000000-99999999 -n 1)"
EMAIL_PROD="tst$(date +%s)p@example.com"
echo "Testing with phone=$PHONE_PROD email=$EMAIL_PROD"

curl -v -b /tmp/ck-prod.txt -c /tmp/ck-prod.txt \
  -X POST "https://llm.resayil.io/register/otp" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: $CSRF_PROD" \
  -d "{\"name\":\"Test User\",\"email\":\"$EMAIL_PROD\",\"phone\":\"$PHONE_PROD\",\"password\":\"TestPass123\",\"password_confirmation\":\"TestPass123\"}" \
  2>&1
```

### C5: Check /login reachability
```bash
echo "DEV /login:"; curl -s -o /dev/null -w "%{http_code}" "https://llmdev.resayil.io/login"
echo ""
echo "PROD /login:"; curl -s -o /dev/null -w "%{http_code}" "https://llm.resayil.io/login"
echo ""
```

### C6: Test login endpoint directly (DEV)
```bash
LANDING_LOGIN=$(curl -s -c /tmp/ck-login-dev.txt "https://llmdev.resayil.io/login")
CSRF_LOGIN=$(echo "$LANDING_LOGIN" | grep -oP '(?<=name="csrf-token" content=")[^"]+' | head -1)
curl -v -b /tmp/ck-login-dev.txt -c /tmp/ck-login-dev.txt \
  -X POST "https://llmdev.resayil.io/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: $CSRF_LOGIN" \
  -d '{"email":"admin@llm.resayil.io","password":"password"}' \
  2>&1 | grep -E "HTTP|message|error|location|Set-Cookie" | head -20
```

### C7: Check route list on server (confirm /register/otp exists on both)
```bash
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:list --path=register 2>&1 | head -20"
ssh whm-server "cd ~/llm.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:list --path=register 2>&1 | head -20"
```

### C8: Check Laravel error logs for recent 422/500 errors related to landing page
```bash
ssh whm-server "tail -80 ~/llmdev.resayil.io/storage/logs/laravel.log 2>/dev/null | grep -A5 'register\|ValidationException\|419\|CSRF' | head -60"
ssh whm-server "tail -80 ~/llm.resayil.io/storage/logs/laravel.log 2>/dev/null | grep -A5 'register\|ValidationException\|419\|CSRF' | head -60"
```

### C9: Check what git commits are on prod vs dev for the landing template
```bash
ssh whm-server "cd ~/llm.resayil.io && git log --oneline -10 2>&1"
ssh whm-server "cd ~/llmdev.resayil.io && git log --oneline -10 2>&1"
```

## Step D: Interpret results and write DIAGNOSIS.md

After all curl/Playwright output is collected, write the report:

```
D:/Claude/projects/LLM-Resayil/.planning/quick/11-investigate-landing-page-login-and-regis/DIAGNOSIS.md
```

Use this structure:

---
# Landing Page Login and Registration — Diagnosis Report
**Date:** 2026-03-05
**Environments tested:** https://llmdev.resayil.io/landing/3 (dev), https://llm.resayil.io/landing/3 (prod)

## Executive Summary
[1-2 paragraph plain-language summary of what is broken and why]

## Hypothesis Results

| ID | Hypothesis | Result | Evidence |
|----|-----------|--------|----------|
| H1 | CSRF token present in landing page | CONFIRMED/DENIED/UNKNOWN | [evidence] |
| H2 | /register/otp returns wrong response | CONFIRMED/DENIED/UNKNOWN | [HTTP status + body snippet] |
| H3 | guest middleware causes 302 on AJAX | CONFIRMED/DENIED/UNKNOWN | [response headers] |
| H4 | Phone validation rejects value | CONFIRMED/DENIED/UNKNOWN | [response body] |
| H5 | password_confirmation field passes | CONFIRMED/DENIED/UNKNOWN | [response body] |
| H6 | /register/otp route missing on prod | CONFIRMED/DENIED/UNKNOWN | [route:list output] |
| H7 | /login returns non-200 | CONFIRMED/DENIED/UNKNOWN | [HTTP status] |
| H8 | Session cookie missing after register | CONFIRMED/DENIED/UNKNOWN | [Set-Cookie headers] |
| H9 | template-3 is different version on prod | CONFIRMED/DENIED/UNKNOWN | [git log / curl] |
| H10 | /register/otp commits not on main | CONFIRMED/DENIED/UNKNOWN | [git log] |

## Root Causes

### Root Cause 1: [Title]
**Severity:** Critical / High / Medium / Low
**Environment:** Dev / Prod / Both
**What fails:** [Exact user-facing symptom]
**Why it fails:** [Technical cause — exact line, config key, or HTTP status]
**Evidence:** [Paste relevant output]

[Repeat for each cause found]

## Fix Recommendations (implementation deferred)

| Priority | Fix | Files | Effort |
|----------|-----|-------|--------|
| P1 | [description] | [files] | S/M/L |

## Prod vs Dev Differences
[Table or prose noting any behavioral difference between environments]

## Items Not Tested
[Any hypotheses that could not be tested, with reason]
---
  </action>
  <verify>
    <automated>test -f "D:/Claude/projects/LLM-Resayil/.planning/quick/11-investigate-landing-page-login-and-regis/DIAGNOSIS.md" && echo "DIAGNOSIS.md exists" || echo "MISSING"</automated>
  </verify>
  <done>
    DIAGNOSIS.md exists containing:
    - All 10 hypotheses with CONFIRMED / DENIED / UNKNOWN verdict plus evidence
    - At least one root cause with exact technical explanation
    - Fix recommendations table
    - Prod vs dev comparison
    No application source files were modified (git diff shows no changes to app/, resources/, routes/).
  </done>
</task>

</tasks>

<verification>
After the task completes:
- DIAGNOSIS.md is present in .planning/quick/11-investigate-landing-page-login-and-regis/
- git diff shows no changes to any application files
- The report contains root causes specific enough for a P1-fix plan to be written immediately after
</verification>

<success_criteria>
Investigation complete when DIAGNOSIS.md documents the exact technical reason why login
and/or registration fails on /landing/3, with enough specificity that the next plan can
implement a targeted fix without additional investigation.
</success_criteria>

<output>
No SUMMARY.md needed — this is a quick investigation task.
Output artifact: DIAGNOSIS.md in this same directory.
</output>
