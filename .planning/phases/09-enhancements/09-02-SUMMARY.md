---
phase: 09-enhancements
plan: 02
subsystem: billing/dashboard
tags: [savings, token-logging, cost-comparison, dashboard]
dependency_graph:
  requires: []
  provides: [token-split-logging, cost-service, savings-dashboard]
  affects: [usage_logs, dashboard, api-chat-completions]
tech_stack:
  added: [CostService]
  patterns: [blended-rate-fallback, nullable-token-split, service-class-via-app-helper]
key_files:
  created:
    - database/migrations/2026_03_04_000001_add_token_split_to_usage_logs.php
    - app/Services/CostService.php
  modified:
    - app/Models/UsageLog.php
    - app/Models/OllamaProxy.php
    - app/Services/CreditService.php
    - app/Http/Controllers/Api/ChatCompletionsController.php
    - resources/views/dashboard.blade.php
    - resources/lang/en/dashboard.php
    - resources/lang/ar/dashboard.php
decisions:
  - "No token estimator: use only Ollama's prompt_eval_count and eval_count for real counts"
  - "Streaming path stores NULL split: blended 70/30 rate covers these rows in CostService"
  - "1 credit = 0.001 USD, USD to KWD = 0.306 constant"
  - "GPT-4o is the primary benchmark for savings display"
  - "OllamaProxy::logUsage() is the primary logger; CreditService::deductCredits() is secondary logger"
  - "estimateTokens() method removed from ChatCompletionsController entirely"
metrics:
  duration: "~3 hours (tasks 1-4 in prior session, task 5 deploy 2026-03-05)"
  completed: "2026-03-05"
  tasks_completed: 5
  files_modified: 9
requirements_fulfilled: [COST-01, COST-02, COST-03]
---

# Phase 09 Plan 02: Savings Dashboard — Summary

**One-liner:** Real token split logging from Ollama counts + CostService with GPT-4o comparison widget on dashboard.

---

## What Was Built

### Task 1: Migration + UsageLog Model Update
Added two nullable integer columns to the `usage_logs` table:
- `prompt_tokens` (nullable int, after `tokens_used`)
- `completion_tokens` (nullable int, after `prompt_tokens`)

Updated `app/Models/UsageLog.php` to include both in `$fillable` and `$casts`.

### Task 2: CostService
Created `app/Services/CostService.php` with:
- `getPublicModelRates()` — returns 5 public model pricing entries (GPT-3.5 Turbo, GPT-4, GPT-4o, Claude 3.5, Gemini 1.5 Pro) in USD per 1M tokens
- `calculatePublicCost(promptTokens, completionTokens, model)` — exact cost using real split
- `calculatePublicCostBlended(totalTokens, model)` — 70/30 blended rate for NULL-split rows
- `getMonthlySavings(user)` — monthly aggregate: our cost vs all 5 public models, GPT-4o as benchmark
- `getRecentCallComparisons(user, limit)` — per-call GPT-4o equivalent cost for usage table
- `creditsToUsd()`, `usdToKwd()` helpers
- Constants: `CREDIT_TO_USD = 0.001`, `USD_TO_KWD = 0.306`, `BLENDED_INPUT_RATIO = 0.70`, `BLENDED_OUTPUT_RATIO = 0.30`

### Task 3: Real Token Split Logging
Three files updated to wire real Ollama token counts through the system:

**OllamaProxy.php:**
- `logUsage()` signature extended with `?int $promptTokens = null, ?int $completionTokens = null` (backward compatible)
- Non-streaming path: captures `prompt_eval_count` and `eval_count` from Ollama response body; falls back to char/3 estimate ONLY if both are absent (not on top of them — bug fixed)
- Streaming path unchanged (passes null, null — handled by blended rate)

**CreditService.php:**
- `deductCredits()` and `logUsage()` signatures extended with same nullable parameters
- Both `UsageLog::create()` calls in those methods now store `prompt_tokens` and `completion_tokens`

**ChatCompletionsController.php:**
- Non-streaming credit deduction now extracts `prompt_eval_count`/`eval_count` from decoded response, passes through to `deductCredits()`
- `estimateTokens()` method removed entirely (was triple-counting tokens)
- Stream path passes null, null for token split

### Task 4: Dashboard Savings Widget + Enhanced Usage Table
**dashboard.blade.php:**
- Savings card inserted between `stats-grid` and Model Catalog
- Card shows: GPT-4o savings this month in USD + KWD, tilde prefix when any row uses blended estimate
- "Compare all models" toggle reveals full 5-model comparison table
- Recent API Usage table thead extended: Model | Input | Output | Tokens | vs GPT-4o | Credits Used | Time
- Old rows (NULL split) show em-dash in Input/Output columns, tilde-prefixed estimate in vs GPT-4o

**Translation keys added (13 new keys each):**
- `en/dashboard.php`: `savings_this_month`, `savings_vs_gpt4o`, `savings_this_month_label`, `savings_estimate_note`, `savings_show_all`, `savings_hide`, `savings_no_data`, `savings_col_model`, `savings_col_public_cost`, `savings_col_our_cost`, `savings_col_saved`, `usage_input_tokens`, `usage_output_tokens`, `usage_vs_gpt4o`
- `ar/dashboard.php`: All 13 keys translated to Arabic

### Task 5: Migration + Deploy to Dev
- Code pushed to `origin/dev` (5 commits)
- Migration `2026_03_04_000001_add_token_split_to_usage_logs` ran on `llmdev.resayil.io` — **Ran** status confirmed
- Deploy completed at https://llmdev.resayil.io
- Cache permissions error on deploy is a known shared hosting limitation — non-blocking

---

## Key Decisions Honored from CONTEXT.md

| Decision | Implementation |
|----------|---------------|
| No token estimator | `estimateTokens()` removed; char/3 used ONLY as fallback when Ollama returns no counts at all |
| 1 credit = 0.001 USD | `CostService::CREDIT_TO_USD = 0.001` |
| USD to KWD = 0.306 | `CostService::USD_TO_KWD = 0.306` |
| Blended 70/30 for NULL split | `calculatePublicCostBlended()` applies 70% input / 30% output |
| GPT-4o as primary benchmark | `getMonthlySavings()` extracts `$benchmark` entry for `gpt-4o` key |

---

## Architecture Note: Dual Logger Pattern

The codebase has two places that create `UsageLog` rows:

1. **Primary:** `OllamaProxy::logUsage()` — called for every proxied request with full response data including token counts from Ollama
2. **Secondary:** `CreditService::deductCredits()` and `CreditService::logUsage()` — called from `ChatCompletionsController::store()` for credit deduction; also creates a UsageLog row

Both paths were updated to accept and store `prompt_tokens` and `completion_tokens`. The streaming path through OllamaProxy stores NULL split (streaming accumulates totalTokens but not per-chunk split — deferred enhancement).

---

## Deviations from Plan

None. All 5 tasks executed as specified in the plan.

The cache clear permission error during deploy (`ERROR Failed to clear cache`) is a pre-existing shared hosting issue unrelated to this plan's changes. It does not affect correctness or the migration.

---

## Verification Results

- Migration status: `2026_03_04_000001_add_token_split_to_usage_logs ... [2] Ran`
- Deploy completed: https://llmdev.resayil.io
- Git push: `dev` branch at commit `0753e1b`
