---
phase: 09-enhancements
plan: 02
verified: 2026-03-05T00:00:00Z
status: passed
score: 5/5 must-haves verified
re_verification: false
gaps: []
human_verification:
  - test: "Visit https://llmdev.resayil.io/dashboard after making an API call this month"
    expected: "Savings This Month card shows a green USD savings figure vs GPT-4o, not 0.00"
    why_human: "Requires live DB data and a browser; can't verify rendering with real token data programmatically"
  - test: "Make an API call via curl to dev using a non-admin API key, then reload /dashboard"
    expected: "The new call appears in Recent API Usage with non-null values in Input and Output columns"
    why_human: "Requires a live Ollama response returning prompt_eval_count and eval_count"
  - test: "Switch to Arabic locale at llmdev.resayil.io/locale/ar and reload /dashboard"
    expected: "Savings card renders in Arabic with no layout breakage"
    why_human: "Visual rendering with RTL layout requires a browser"
---

# Phase 09 Plan 02: Savings Dashboard — Verification Report

**Phase Goal:** Add a Savings Dashboard — log real token split per API call, create CostService with public model pricing, show Savings This Month widget and enhanced usage table comparing cost vs GPT-4o on the user dashboard.
**Verified:** 2026-03-05
**Status:** passed
**Re-verification:** No — initial verification

---

## Goal Achievement

### Observable Truths

| # | Truth | Status | Evidence |
|---|-------|--------|----------|
| 1 | New usage log rows store prompt_tokens and completion_tokens as separate integers | VERIFIED | Migration adds both nullable int columns; UsageLog $fillable and $casts include both; OllamaProxy logUsage() and CreditService deductCredits() store them |
| 2 | Old rows with NULL prompt_tokens/completion_tokens display correctly without errors | VERIFIED | Dashboard template uses `is_null($log->prompt_tokens) ? '&mdash;'` guard; no hard-cast of null |
| 3 | The dashboard shows a Savings This Month card comparing cost vs GPT-4o in USD and KWD | VERIFIED | `.savings-card` CSS + HTML present at line 14/156; getMonthlySavings() called; benchmark savings rendered with `savings_usd` and `savings_kwd` |
| 4 | The Recent API Usage table shows a vs GPT-4o column with the USD equivalent cost per call | VERIFIED | `getRecentCallComparisons()` called at dashboard.blade.php line 453; table headers include `usage_input_tokens`, `usage_output_tokens`, `usage_vs_gpt4o` translation keys at lines 460-463 |
| 5 | Savings display tilde prefix for rows with NULL token split (blended rate), exact for split rows | VERIFIED | `$row['is_estimate'] ? '~' : ''` pattern at line 476; savings card also uses `$savings['benchmark']['is_estimate'] ? '~' : ''` at lines 165, 168, 186, 188 |

**Score:** 5/5 truths verified

---

## Required Artifacts

| Artifact | Expected | Status | Details |
|----------|----------|--------|---------|
| `database/migrations/2026_03_04_000001_add_token_split_to_usage_logs.php` | Migration adding prompt_tokens + completion_tokens nullable int columns | VERIFIED | File exists; `$table->integer('prompt_tokens')->nullable()->after('tokens_used')` and `$table->integer('completion_tokens')->nullable()->after('prompt_tokens')` present |
| `app/Services/CostService.php` | Public model pricing table and savings calculation methods | VERIFIED | File exists with all 6 required public/private methods; 5 pricing entries; correct constants (CREDIT_TO_USD=0.001, USD_TO_KWD=0.306, BLENDED_INPUT_RATIO=0.70, BLENDED_OUTPUT_RATIO=0.30) |
| `resources/views/dashboard.blade.php` | Savings widget + enhanced usage table with Input/Output/vs-GPT-4o columns | VERIFIED | `.savings-card` at line 14 (CSS) and line 156 (HTML); enhanced table headers at lines 460-463; toggleSavingsTable JS at line 811 |

---

## Key Link Verification

| From | To | Via | Status | Details |
|------|----|-----|--------|---------|
| `app/Models/OllamaProxy.php` | `usage_logs.prompt_tokens` | `logUsage()` updated to accept and store prompt_tokens + completion_tokens | VERIFIED | Line 270: 9-parameter signature with `?int $promptTokens = null, ?int $completionTokens = null`; line 283-284: both stored in UsageLog::create() |
| `resources/views/dashboard.blade.php` | `app/Services/CostService.php` | CostService instantiated via app() helper in blade template | VERIFIED | Line 153: `$costService = app(\App\Services\CostService::class);` — correct factory method, not `new CostService()` |

---

## Requirements Coverage

| Requirement | Source Plan | Description | Status | Evidence |
|-------------|-------------|-------------|--------|----------|
| COST-01 | 09-02-PLAN.md | Real token split logging from Ollama counts | SATISFIED | OllamaProxy non-streaming captures `prompt_eval_count`/`eval_count`; CreditService and ChatCompletionsController both store and pass the split through |
| COST-02 | 09-02-PLAN.md | CostService with public model pricing and savings calculation | SATISFIED | CostService.php exists with full implementation; calculatePublicCost(1000, 500, 'gpt-4o') = 0.0125 (verified by code inspection: (1000/1M * 5.00) + (500/1M * 15.00) = 0.005 + 0.0075 = 0.0125) |
| COST-03 | 09-02-PLAN.md | Dashboard savings card and enhanced usage table | SATISFIED | Savings card present; usage table has Input, Output, vs GPT-4o columns; EN and AR i18n keys all 14 present in both language files |

---

## Anti-Patterns Found

| File | Line | Pattern | Severity | Impact |
|------|------|---------|----------|--------|
| `app/Http/Controllers/Api/ChatCompletionsController.php` | 261, 268 | `$isAdmin` used inside `response()->stream()` closure but NOT captured in `use()` list | WARNING | Admin bypass is silently broken for streaming requests: `$isAdmin` will be undefined inside the closure, PHP will emit a notice, and the condition `!$isAdmin` will evaluate as `true` (undefined → null → `!null = true`), meaning admin streaming calls will attempt credit deduction. This is a pre-existing design issue amplified by the stream refactor; regular user streaming is unaffected. |

**Note on double-logging:** The architecture has two places that write UsageLog rows — `OllamaProxy::logUsage()` (primary, called from proxy) and `CreditService::deductCredits()` (secondary, called from controller). Both were updated correctly. This dual-logger pattern is an existing architectural quirk noted in the plan and handled intentionally.

---

## Human Verification Required

### 1. Savings card with real data

**Test:** Make at least one API call on the dev server this month using a valid API key, then visit https://llmdev.resayil.io/dashboard
**Expected:** Savings This Month card shows a non-zero green savings amount in USD, and KWD figure beneath it
**Why human:** Requires a live DB row with credits_deducted > 0 and a browser to render the card

### 2. Token split columns in usage table

**Test:** After a successful non-streaming API call, reload the dashboard and inspect the Recent API Usage table
**Expected:** The new call shows integers in the Input and Output columns (not em-dashes), and a non-tilde-prefixed USD amount in the vs GPT-4o column
**Why human:** Requires Ollama to return `prompt_eval_count` and `eval_count` in the response body — depends on live GPU server

### 3. Arabic locale rendering

**Test:** Switch to Arabic at https://llmdev.resayil.io/locale/ar and open the dashboard
**Expected:** Savings card and usage table render in Arabic with no layout breakage; RTL text direction maintained
**Why human:** Visual RTL rendering requires a browser; Arabic strings are present in ar/dashboard.php but display quality is visual

---

## Gaps Summary

No blocking gaps. All 5 observable truths are verified against the actual codebase. All required artifacts exist with substantive implementations. Both key links (OllamaProxy to usage_logs and dashboard to CostService) are wired correctly.

One warning-level anti-pattern exists: the `$isAdmin` variable is not captured in the `stream()` closure's `use()` list (ChatCompletionsController line 261), which means admin streaming requests are not properly bypassed. This does not affect the savings dashboard feature or regular user flows — it is a pre-existing admin-bypass gap in the streaming path, amplified by the Task 3 refactor.

---

## Verification Detail by Task

### Task 1: Migration and UsageLog model update
- Migration file at correct path: EXISTS
- `prompt_tokens` nullable int `after('tokens_used')`: VERIFIED
- `completion_tokens` nullable int `after('prompt_tokens')`: VERIFIED
- UsageLog `$fillable` includes `prompt_tokens`, `completion_tokens`: VERIFIED (lines 40-41)
- UsageLog `$casts` includes both as `'integer'`: VERIFIED (lines 55-56)

### Task 2: CostService
- File exists at `app/Services/CostService.php`: VERIFIED
- Constants: CREDIT_TO_USD=0.001, USD_TO_KWD=0.306, BLENDED_INPUT_RATIO=0.70, BLENDED_OUTPUT_RATIO=0.30: VERIFIED
- `getPublicModelRates()` returns exactly 5 entries (gpt-3.5-turbo, gpt-4, gpt-4o, claude-3.5, gemini-1.5): VERIFIED
- `calculatePublicCost()` math correct: VERIFIED by code inspection
- `calculatePublicCostBlended()` delegates to calculatePublicCost with 70/30 split: VERIFIED
- `getMonthlySavings()` queries usage_logs for current month, builds comparisons with benchmark=gpt-4o: VERIFIED
- `getRecentCallComparisons()` queries with orderByDesc, limit, annotates with gpt4o_cost_usd: VERIFIED
- `emptySavingsResult()` private method returns correct zero-filled structure: VERIFIED
- All exported methods from plan's must_haves present: VERIFIED

### Task 3: Real token split logging
- OllamaProxy.logUsage() signature has 2 nullable optional params: VERIFIED (line 270)
- Non-streaming path captures prompt_eval_count/eval_count WITHOUT char estimate bug: VERIFIED (lines 165-173)
- Streaming path calls logUsage() without additional params (default null,null): VERIFIED (line 151, backward compatible)
- CreditService.deductCredits() extended with ?int $promptTokens, ?int $completionTokens: VERIFIED (line 52)
- CreditService.logUsage() extended with same params: VERIFIED (line 105)
- ChatCompletionsController.store() extracts token split and passes to deductCredits(): VERIFIED (lines 144-158)
- ChatCompletionsController has NO estimateTokens() method: VERIFIED (grep returned no matches)
- Stream path passes null, null to deductCredits(): VERIFIED (line 287-288)

### Task 4: Dashboard savings widget and enhanced usage table
- CSS for savings-card and all sub-classes present in @push('styles'): VERIFIED
- @php block instantiates CostService via app() helper: VERIFIED (line 153)
- getMonthlySavings() called and savings card HTML rendered: VERIFIED (lines 154-156)
- Toggle button and collapsible comparison table present: VERIFIED
- Recent usage section uses $costService->getRecentCallComparisons(): VERIFIED (line 453)
- Table headers: Model | Input | Output | Tokens | vs GPT-4o | Credits Used | Time: VERIFIED
- Null-guard for prompt_tokens/completion_tokens using em-dash: VERIFIED (lines 473-474)
- Tilde prefix for is_estimate rows: VERIFIED (line 476)
- toggleSavingsTable JS function in @push('scripts'): VERIFIED (line 811)
- EN dashboard.php: 14 savings keys present (including usage_vs_gpt4o not listed in plan's 13 but present): VERIFIED
- AR dashboard.php: all savings keys present: VERIFIED

### Task 5: Migration + deploy
- SUMMARY.md confirms: "Migration status: `2026_03_04_000001_add_token_split_to_usage_logs ... [2] Ran`"
- Cannot verify live server state programmatically from local; flagged for human verification

---

_Verified: 2026-03-05_
_Verifier: Claude (gsd-verifier)_
