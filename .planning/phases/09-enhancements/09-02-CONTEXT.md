# Phase 9-02: Cost Comparison Dashboard - Context

**Gathered:** 2026-03-04
**Status:** Ready for planning
**Source:** User request - show token costs and comparison with public models

## Phase Boundary

Add a cost comparison feature to the user dashboard that:
1. Shows how much each model costs in credits per token
2. Allows comparison with public models (GPT, Gemini, Claude) in USD
3. Estimates token usage based on input/output
4. Calculates estimated cost before making API calls

## User Need

Users want to understand:
- "If I use ChatGPT-based model, how much would it cost me?"
- "Is this cheaper than using OpenAI directly?"
- "How many tokens will this request consume?"

## Requirements

### COST-01: Cost Display per Model
Each model card in the catalog must show:
- Credit multiplier (1x = Standard, 2x = Premium)
- Estimated credits per 1K tokens
- Equivalent USD cost (based on user's credits balance)

### COST-02: Cost Comparison Table
Add a toggle/section to show comparison with public models:
| Model | Our Credits | OpenAI Equivalent | Est. USD Saved |
|-------|-------------|-------------------|----------------|
| smollm2:135m | 1.5 credits | GPT-3.5 | $0.45 |
| gemma3:27b | 2 credits | GPT-4 | $1.20 |
| qwen3-next:80b | 2 credits | Claude 3.5 Sonnet | $1.50 |

### COST-03: Token Estimator
Before making a request, show:
- Input tokens: [user enters text or uses estimate]
- Output tokens: [estimated based on input length]
- Total cost: [calculated]

### COST-04: Recent Usage Cost Breakdown
On dashboard, show:
- Total credits used this month
- Equivalent USD value
- Cost by model breakdown (top 5 models)

## Implementation Plan

### Backend
1. **Create `CostService`** (`app/Services/CostService.php`)
   - `calculateModelCost($model, $tokens)` - Returns credits and USD
   - `compareWithPublicModels($model)` - Returns comparison data
   - `estimateTokens($text)` - Rough estimation (1 token ~ 4 chars)
   - `getPublicModelRates()` - Public model pricing (GPT, Gemini, Claude)

2. **Update `ModelsController`**
   - Add cost data to `/models/catalog` endpoint
   - Include: `credits_per_1k_tokens`, `usd_equivalent`, `public_comparison`

3. **Update `CreditService`**
   - Already has cost calculation - extend it
   - Add `getCostBreakdown($user)` for monthly stats

### Frontend
1. **Model Card Enhancement**
   - Add cost tag: "1.5 credits/1K tokens"
   - Add USD comparison: "vs GPT-3.5 ($0.50)"

2. **Cost Comparison Panel**
   - Collapsible section on dashboard
   - Toggle between "Our Models" and "Public Comparison"
   - Table format with sorting

3. **Token Estimator**
   - Input text box for prompt
   - Auto-estimate output tokens (10-30% of input)
   - Show: "Estimated cost: 15 credits"

4. **Usage Cost Breakdown**
   - Add to Recent API Usage section
   - Show: "Total cost this week: 150 credits ($0.75)"

## Data Sources

### Our Model Pricing
```
Standard models (credit_multiplier: 1):
- smollm2, gemma, mistral, qwen (small/medium)
- 1 credit per 1000 tokens

Premium models (credit_multiplier: 2):
- qwen3-next, kimi, deepseek, large reasoning
- 2 credits per 1000 tokens
```

### Public Model Pricing (OpenAI/Anthropic)
```
GPT-3.5 Turbo: $0.50 / 1M input, $1.50 / 1M output
GPT-4: $3 / 1M input, $6 / 1M output
GPT-4o: $5 / 1M input, $15 / 1M output
Claude 3.5 Sonnet: $3 / 1M input, $15 / 1M output
Claude 3 Opus: $15 / 1M input, $75 / 1M output
Gemini 1.5 Pro: $3.50 / 1M input, $10.50 / 1M output
```

## Success Criteria

1. User can see cost per model on dashboard
2. User can toggle comparison view to see public model prices
3. User can estimate cost before making request
4. Cost breakdown shows monthly spending
5. All calculations accurate within 10% tolerance

## Notes

- Credit conversion rate: 1 KWD = ~3.27 USD (use current rate)
- Our pricing: ~0.0003 KWD per credit = ~0.001 USD per credit
- Show both credits and USD equivalent for clarity
- Allow user to toggle between KWD and USD

---

*Phase: 09-02*
*Context gathered: 2026-03-04 from user session*
