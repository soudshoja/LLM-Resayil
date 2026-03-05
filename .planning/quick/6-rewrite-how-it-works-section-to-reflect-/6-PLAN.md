---
phase: quick-6
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - resources/views/landing/template-3.blade.php
autonomous: true
requirements: [QUICK-6]
must_haves:
  truths:
    - "The 4 How It Works steps accurately reflect the real user journey: phone-based signup, credit top-up, API key generation, and using the API"
    - "Step 1 does NOT say 'no credit card required' (trial requires card verification)"
    - "Step 1 references phone number + WhatsApp OTP verification (actual registration flow)"
    - "Step 3 code example shows the correct base URL: https://llm.resayil.io/api/v1"
    - "Steps target non-technical end users, not developers"
    - "All existing CSS classes (.hiw-step, .hiw-num, .hiw-content, .hiw-code, .hiw-badges) and HTML structure are preserved"
    - "Change is deployed to dev and visually verified at https://llmdev.resayil.io/landing/3"
  artifacts:
    - path: "resources/views/landing/template-3.blade.php"
      provides: "Updated How It Works section with accurate 4-step user journey"
      contains: "hiw-step"
  key_links:
    - from: "How It Works section"
      to: "Actual registration flow (phone OTP)"
      via: "Step 1 copy"
      pattern: "phone|WhatsApp|OTP"
    - from: "Step 3 code example"
      to: "Real API endpoint"
      via: "base_url string"
      pattern: "llm.resayil.io/api/v1"
---

<objective>
Rewrite the "How It Works" section in template-3.blade.php to accurately reflect the real user journey — phone signup with WhatsApp OTP verification, credit top-up via card payment, API key generation from the dashboard, and plugging the API key into existing apps.

Purpose: The current copy has inaccuracies (claims "no credit card required" when the trial requires card verification; does not mention phone/WhatsApp OTP signup). The target audience is non-technical end users. The section must build trust with accurate, plain-language copy.

Output: Updated template-3.blade.php with 4 rewritten How It Works steps, committed, pushed to dev, deployed, and verified.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md

<!-- KEY FACTS FROM CODEBASE READING -->
<!-- Registration flow (RegisteredUserController.php):
     - Step 1: POST /register/otp — validates name, email, phone, password; sends OTP via WhatsApp (OtpService)
     - Step 2: POST /register — verifies OTP code, creates user with subscription_tier='basic'
     - Phone number is required and unique; WhatsApp OTP is how identity is verified

     Trial/billing flow (PaymentController.php):
     - Trial charges 0.100 KWD to capture card — NOT free, card IS required
     - initiateTrialPayment() → MyFatoorah → callback activates starter tier
     - Top-up packs: 500/1100/3000 credits

     API key flow (routes/web.php):
     - POST /api-keys → ApiKeysController@store — creates key instantly, no approval queue
     - Keys visible in dashboard

     API usage:
     - Base URL: https://llm.resayil.io/api/v1
     - Same OpenAI SDK format: Authorization: Bearer {key}
     - Credits deducted per token; cloud models cost 2x, local 1x

     Current How It Works HTML location in template-3.blade.php:
     - Section: id="how-it-works" (line ~497)
     - 4 × .hiw-step divs with .hiw-num + .hiw-content
     - Step 3 has a .hiw-code block (Python example)
     - Step 4 has a .hiw-badges block (Cursor, VS Code, etc.)
     - CSS classes must not change
-->
</context>

<tasks>

<task type="auto">
  <name>Task 1: Rewrite the How It Works 4-step copy in template-3.blade.php</name>
  <files>resources/views/landing/template-3.blade.php</files>
  <action>
Locate the "HOW IT WORKS" section (around line 496, `id="how-it-works"`). Replace only the inner content of the 4 `.hiw-step` divs. Keep all class names, div structure, aria attributes, and surrounding section markup unchanged.

Rewrite the 4 steps as follows (exact replacement HTML for the content inside each `.hiw-content`):

**Step 1 — "Create Your Account"**
- h3: `Create Your Account`
- p: `Register in under a minute using your name, email, and phone number. We will send a verification code to your WhatsApp to confirm your identity — quick and secure.`
- No .hiw-code or .hiw-badges block in this step.

**Step 2 — "Add Credits to Your Wallet"**
- h3: `Add Credits to Your Wallet`
- p: `Top up your credit balance using your credit or debit card. Credits are what you spend on API calls — each request deducts a small amount based on the model and tokens used. <strong>Pay only for what you use.</strong>`
- No .hiw-code or .hiw-badges block in this step.

**Step 3 — "Get Your API Key"**
- h3: `Get Your API Key`
- p: `From your dashboard, generate an API key in one click. Copy it — you will use it in the next step. No approval process, no waiting.`
- No .hiw-code or .hiw-badges block in this step. Remove the existing .hiw-code block from step 3.

**Step 4 — "Plug It Into Your App"**
- h3: `Plug It Into Your App`
- p: `Replace your current AI endpoint with ours — it is a single line change. Use the exact same OpenAI SDK you already have. Works with <strong>ChatGPT plugins, Cursor, n8n, Python, Node.js</strong> — any tool that supports OpenAI.`
- Keep the .hiw-code block but update its content to show the one-line change clearly:
  ```
  <div class="hiw-code" aria-label="Code example: replace base_url with llm.resayil.io/api/v1">
      <span class="hiw-code-comment"># Python — one line change</span>
      <span>client = OpenAI(</span>
      <span>&nbsp;&nbsp;&nbsp;&nbsp;base_url=<span class="hiw-str">"https://llm.resayil.io/api/v1"</span>,</span>
      <span>&nbsp;&nbsp;&nbsp;&nbsp;api_key=<span class="hiw-str">"your-api-key-here"</span>,</span>
      <span>)</span>
  </div>
  ```
- Keep the .hiw-badges block with these badges: `ChatGPT Plugins`, `Cursor`, `n8n`, `Python SDK`, `Node.js`, `Any OpenAI SDK`

Also update the section subtitle (`.ssub`) currently reading: "No chat interface. No new tool to learn. Just plug our endpoint into your existing app and go." — change to: "No new tools to learn. Register, add credits, grab your API key, and you are ready to go."

Do NOT touch any CSS, the section header h2, the `.slabel`, or any other part of the file.
  </action>
  <verify>
    <automated>grep -n "Create Your Account\|Add Credits to Your Wallet\|Get Your API Key\|Plug It Into Your App" resources/views/landing/template-3.blade.php</automated>
  </verify>
  <done>All 4 step headings updated, no mention of "no credit card required", WhatsApp OTP mentioned in step 1, code example in step 4 shows correct base_url.</done>
</task>

<task type="auto">
  <name>Task 2: Commit, push to dev, deploy, and verify</name>
  <files></files>
  <action>
Run the following in sequence:

1. Stage and commit:
   ```
   git add resources/views/landing/template-3.blade.php
   git commit -m "feat(landing): rewrite how-it-works section to reflect real user journey — phone OTP signup, credit top-up, API key, plug-in"
   ```

2. Push to dev branch:
   ```
   git push origin dev
   ```

3. Deploy to dev server:
   ```
   ssh whm-server "cd ~/llmdev.resayil.io && bash deploy.sh dev"
   ```

4. Take a screenshot of the How It Works section on the deployed dev site to confirm it rendered correctly. The target URL is `https://llmdev.resayil.io/landing/3#how-it-works`.

If the deploy.sh returns a non-zero exit code, report the error output and stop.
  </action>
  <verify>
    <automated>ssh whm-server "cd ~/llmdev.resayil.io && git log --oneline -1"</automated>
  </verify>
  <done>Latest commit SHA is visible on dev server. The How It Works section at https://llmdev.resayil.io/landing/3 shows the 4 updated steps with accurate copy.</done>
</task>

</tasks>

<verification>
After both tasks complete:
- Visit https://llmdev.resayil.io/landing/3 and scroll to the "How It Works" section
- Confirm: Step 1 mentions WhatsApp verification, Step 2 covers credit top-up, Step 3 covers API key generation, Step 4 shows the code example
- Confirm: No mention of "no credit card required" anywhere in the section
- Confirm: The section subtitle has been updated
- Confirm: All existing visual styling is intact (.hiw-step hover effects, .hiw-num gold circle, code block dark background)
</verification>

<success_criteria>
- 4 How It Works steps rewritten with accurate, non-technical copy matching the real user journey
- Step 1: Phone + WhatsApp OTP signup (not "register in 30 seconds with no credit card")
- Step 2: Credit top-up via card payment (not subscription plans)
- Step 3: API key generation from dashboard (unchanged intent, simplified)
- Step 4: One-line code change, OpenAI SDK compatibility, correct base URL
- Deployed to dev and visually confirmed
</success_criteria>

<output>
After completion, create `.planning/quick/6-rewrite-how-it-works-section-to-reflect-/6-SUMMARY.md` with:
- What was changed (4 step headings + copy + subtitle)
- The git commit SHA
- Confirmation that dev site shows the updated section
</output>
