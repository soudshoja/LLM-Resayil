# Full-Cycle Debug Session — 2026-03-05

**Scope:** Live site audit on `llmdev.resayil.io` using 4 parallel debug agents
**Branch audited:** `dev` (commit `be360dc`)
**Agents:** UI Visual, Registration+Auth, API+Gateway, Server Logs

---

## Fixes Applied This Session

| Fix | Commit | Status |
|-----|--------|--------|
| Our Cost column: use `tokens*multiplier` not `credits*0.001` | `19c5847` | ✅ dev + deployed |
| Dashboard welcome: pass `:name` param to translation | `be360dc` | ✅ dev + deployed |
| Prod `MAIL_MAILER=log` → `sendmail` | server `.env` only | ✅ prod only |

---

## Issues Found — Prioritised

### 🔴 CRITICAL

#### BUG-01: Chat Completions API returns Ollama-native format, NOT OpenAI-compatible

**File:** `app/Models/OllamaProxy.php:179` + `app/Http/Controllers/Api/ChatCompletionsController.php:162`

**Root cause:** `proxyChatCompletions()` calls Ollama's `/api/chat` endpoint and passes the raw body back without transformation.

**Actual response (wrong):**
```json
{
  "model": "llama3.2:3b",
  "created_at": "2026-03-04T22:51:08Z",
  "message": {"role": "assistant", "content": "Hello."},
  "done": true,
  "done_reason": "stop",
  "prompt_eval_count": 30,
  "eval_count": 3
}
```

**Expected OpenAI format:**
```json
{
  "id": "chatcmpl-xxx",
  "object": "chat.completion",
  "created": 1234567890,
  "model": "llama3.2:3b",
  "choices": [{"index": 0, "message": {"role": "assistant", "content": "Hello."}, "finish_reason": "stop"}],
  "usage": {"prompt_tokens": 30, "completion_tokens": 3, "total_tokens": 33}
}
```

**Streaming also broken:** sends raw Ollama chunks and `data: {"done": true}` instead of `data: [DONE]`.

**Impact:** Any client using the OpenAI Python/Node SDK will fail with `KeyError: choices` / cannot read `choices[0].message.content`.

**Fix needed in `OllamaProxy::proxyChatCompletions()`:**
- Non-streaming: wrap raw Ollama response body into OpenAI format before `response()->json()`
- Streaming: transform each Ollama chunk into OpenAI SSE delta format; send `data: [DONE]` at end

---

#### BUG-02: Billing/Plans page shows raw translation keys

**URL:** `/billing/plans`
**Severity:** Blocks subscription purchase — page is unusable for users

**Broken keys (EN):**
- `billing.choose_your_plan` — page heading
- `billing.unlock_llm_power` — subtitle
- `billing.start_free_trial` — CTA button
- `billing.start_monthly_plan` (×3) — plan buttons
- `billing.10_requests_minute`, `billing.30_requests_minute`, `billing.60_requests_minute` — feature lists
- `billing.topup_no_bonus`, `billing.topup_bonus`, `billing.topup_bonus_20` — top-up labels

**Also:** `:tier` placeholder not substituted in API keys addon sentence.

**Root cause:** Phase 09-05 (translation) is incomplete — `resources/lang/en/billing.php` missing these keys.

**Fix:** Add missing keys to `resources/lang/en/billing.php` (and AR equivalent).

---

### 🟡 MEDIUM

#### BUG-03: Double UsageLog creation per non-streaming API call

**Files:** `app/Models/OllamaProxy.php:175-177` AND `app/Http/Controllers/Api/ChatCompletionsController.php:157-158`

**Problem:** For non-streaming requests:
1. `OllamaProxy::proxyChatCompletions()` calls `$this->logUsage()` → creates `UsageLog` record
2. `ChatCompletionsController::store()` then calls `creditService->deductCredits()` → creates ANOTHER `UsageLog` record + decrements user credits

Result: 2 log rows per request, but only 1 credit deduction. Recent API Usage table shows duplicate entries.

**Verify:** `SELECT model, tokens_used, created_at FROM usage_logs WHERE user_id=X ORDER BY created_at DESC LIMIT 20` — check for duplicate timestamps.

**Fix:** Remove `$this->logUsage()` call from `OllamaProxy::proxyChatCompletions()` (lines 175-177). Let the controller handle all credit/logging logic.

Also note: `OllamaProxy::logUsage()` at line 272 hardcodes multiplier (`cloud=*2, local=*1`) ignoring per-model rates — wrong for models with 0.5x or 3.5x multipliers.

---

#### BUG-04: Login error message is generic

**URL:** `/login`
**Actual:** `"Validation failed."` on wrong credentials
**Expected:** `"These credentials do not match our records."`

**Fix:** Check `LoginController` / `AuthenticatedSessionController` error handling — Laravel default message should be used.

---

### 🟢 LOW

#### BUG-05: KNET reference in dashboard Top Up Credits widget

**URL:** `/dashboard` — "Top Up Credits" section
**Text:** "Payments processed securely via KNET / credit card"
**Note:** Quick-4 plan was to remove Kuwait/KNET references. This one was missed.

---

#### BUG-06: Native `alert()` dialog on model catalog

**URL:** `/` or dashboard — clicking model without API key
**Issue:** `alert("Please enter your API key...")` — browser native dialog
**Fix:** Replace with inline toast or modal

---

#### BUG-07: Admin nav overflow (authenticated)

**Affects:** All pages when logged in as admin
**Issue:** 12+ nav links cause horizontal overflow — Profile/Logout cut off
**Fix:** Responsive nav wrapping or hamburger menu for auth links

---

#### BUG-08: No `schedule:run` cron on either server

**Finding:** Crontab only has one entry for `queue:work` on prod. No `schedule:run` exists.
**Impact:** Any `Kernel.php` scheduled commands never run
**Fix:** Add `* * * * * php /path/artisan schedule:run` to crontab for prod

---

#### BUG-09: No `queue:work` cron for dev

**Finding:** Dev has no cron at all — queued jobs accumulate unprocessed
**Impact:** Low (dev only, mail is `log` driver anyway)

---

## What's Working Correctly ✅

- Dark luxury theme renders on all pages
- EN|AR language switcher visible
- Login / logout flow
- Registration form (all 5 fields), OTP step renders
- WhatsApp OTP send triggered on registration
- Dashboard: stats cards, Savings widget, Model Catalog, API Keys section
- **Our Cost column — fixed and verified correct** (7,764 tokens → $0.0078, not old wrong $0.0430)
- **Welcome heading — fixed** (`:name` now interpolated)
- Models endpoint (`GET /api/v1/models`) — fully OpenAI-compatible, 42 models
- Credit deduction + usage logging (working, but see BUG-03 re: double logging)
- KWD pricing on all pricing pages
- MyFatoorah integration code confirmed present and reachable
- Admin dashboard/models management
- Profile page (all 3 sections)
- Credits informational page
- **Prod email — fixed** (`sendmail` now active)
- No JS console errors on any page

---

## Next Phase Recommendations

Priority order for Phase 09-06 (or insert phase):

1. **BUG-01** — OpenAI format transformation in OllamaProxy (CRITICAL — breaks all SDK clients)
2. **BUG-02** — Complete billing.php EN/AR translation keys (Phase 09-05 remainder)
3. **BUG-03** — Remove double-logging from OllamaProxy (MEDIUM — data integrity)
4. **BUG-04** — Login error message (LOW effort, good UX)
5. **BUG-05** — Remove KNET text from dashboard widget

---

*Debug session run: 2026-03-05 | Agents: 4 parallel | Commits: 2 | Server fixes: 1*
