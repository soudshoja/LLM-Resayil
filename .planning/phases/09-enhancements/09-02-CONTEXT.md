# Phase 09-02: Real Token Cost Comparison — Context

**Gathered:** 2026-03-04 (redesigned from original estimator approach)
**Status:** Ready for planning
**Source:** User session — redesign decision to use real token data

<domain>
## Phase Boundary

Add a **Savings Dashboard** to the user dashboard that uses **actual logged token counts** from past API calls to show users:
1. What they paid (credits + KWD)
2. What the same usage would have cost on GPT-4, GPT-4o, Claude 3.5 Sonnet, Gemini 1.5 Pro
3. Total money saved by using LLM Resayil

**No token estimator.** No pre-call estimation. 100% based on real historical data.

</domain>

<decisions>
## Implementation Decisions

### Database — Locked
- Add `prompt_tokens` (int, nullable) and `completion_tokens` (int, nullable) to `usage_logs`
- Existing rows stay with NULL (gracefully handled in calculations)
- New calls log both values separately from Ollama response fields (`prompt_eval_count`, `eval_count`)

### CreditService — Locked
- Update `logUsage()` to accept optional `prompt_tokens` and `completion_tokens` params
- Pass through from the API controller that calls Ollama (it already receives the full response)

### Public Model Pricing — Locked (embedded in CostService, not DB)
| Public Model | Input $/1M | Output $/1M |
|---|---|---|
| GPT-3.5 Turbo | $0.50 | $1.50 |
| GPT-4 | $3.00 | $6.00 |
| GPT-4o | $5.00 | $15.00 |
| Claude 3.5 Sonnet | $3.00 | $15.00 |
| Gemini 1.5 Pro | $3.50 | $10.50 |

- For rows with NULL split (old data): use blended rate (70% input / 30% output assumption)
- Show "~" prefix on estimates from blended rate, exact values for split data

### CostService — Locked
New `app/Services/CostService.php`:
- `getPublicModelRates()` — returns the pricing table above
- `calculatePublicCost(int $promptTokens, int $completionTokens, string $publicModel): float` — USD
- `calculatePublicCostBlended(int $totalTokens, string $publicModel): float` — for old data
- `getMonthlySavings(User $user): array` — aggregates this month's logs, returns savings vs each public model
- `getRecentCallComparisons(User $user, int $limit = 10): array` — per-call comparison for usage table

### Our Credit → USD conversion — Locked
- 1 credit = 0.001 USD (0.0003 KWD at 1 KWD ≈ 3.27 USD)

### Dashboard Widget — Locked
- Add "Savings This Month" card to the existing dashboard (next to or below the credits card)
- Shows: "You saved ~X KWD vs GPT-4o this month"
- One comparison model shown prominently (GPT-4o as benchmark), toggle for others
- Click/expand to see full table: GPT-3.5, GPT-4, GPT-4o, Claude 3.5, Gemini

### Usage Log Table Enhancement — Locked
- Existing "Recent API Usage" table on dashboard gets two new columns:
  - "What you paid" (already shown as credits)
  - "vs GPT-4o" — what this call would have cost in USD on GPT-4o

### No Token Estimator — Locked
- Remove `estimateTokens()` from the original 09-02 design
- No pre-call estimation UI
- All data is post-call, 100% accurate

### Claude's Discretion
- Exact placement of savings widget on dashboard (above/below/beside existing cards)
- Whether to show savings as KWD or USD (or toggle) — recommend showing both
- Color coding (green for savings, muted for costs)
- Whether to add sparkline/chart for monthly trend

</decisions>

<specifics>
## Specific Implementation Details

### Where Ollama returns token counts
The Ollama API response (non-streaming) includes:
```json
{
  "prompt_eval_count": 128,
  "eval_count": 256
}
```
The streaming response sends a final chunk with these fields. The existing `ChatController` (or equivalent) that proxies to Ollama already receives this — just needs to pass to `logUsage()`.

### Migration
```php
// Add to usage_logs
$table->integer('prompt_tokens')->nullable()->after('tokens_used');
$table->integer('completion_tokens')->nullable()->after('prompt_tokens');
```

### CreditService::logUsage() new signature
```php
public function logUsage(
    $user, ?string $apiKeyId, string $model, int $tokensUsed, int $credits,
    string $provider, int $responseTime = 0, int $statusCode = 200,
    ?int $promptTokens = null, ?int $completionTokens = null
): UsageLog
```

### Files to touch
- `database/migrations/` — new migration for usage_logs columns
- `app/Services/CreditService.php` — update logUsage()
- `app/Services/CostService.php` — new file
- `app/Http/Controllers/Api/ChatController.php` (or the Ollama proxy controller) — pass token split to logUsage()
- `resources/views/dashboard.blade.php` — add savings widget + enhance usage table

</specifics>

<deferred>
## Deferred

- Pre-call token estimation (intentionally dropped)
- Per-model cost breakdown chart/graph (future phase)
- CSV export of usage with cost comparison
- Real-time credit → KWD rate from external API (use fixed rate for now)
- Comparison with Mistral API, Together AI (only GPT/Claude/Gemini for now)

</deferred>

---

*Phase: 09-02*
*Context redesigned: 2026-03-04 — real token data, no estimator*
