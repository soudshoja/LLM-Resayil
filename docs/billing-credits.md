# Billing & Credits

LLM Resayil operates on a credit-based system. This guide explains how credits work, subscription plans, pricing, and how to manage your billing.

## What Are Credits?

Credits are the internal currency on LLM Resayil. Every time you make an API call, credits are deducted based on the number of tokens (words/sub-words) the model generates.

- **1 credit** = 1 output token from a local model
- **2 credits** = 1 output token from a cloud model

You can view your credit balance anytime at https://llm.resayil.io/dashboard.

## Credit Costs by Model Type

### Local Models (1 credit per token)

These models run on dedicated GPU infrastructure managed by LLM Resayil:

- llama3.2:3b
- qwen2.5:7b
- gemma2:9b
- mistral:7b
- And 11 others

**Example**: A 100-token response costs 100 credits.

### Cloud Models (2 credits per token)

These models are proxied via external cloud services (ollama.com):

- deepseek-v3
- deepseek-r1
- Various other high-end models

**Example**: A 100-token response costs 200 credits.

## Subscription Plans

Choose a subscription tier to get monthly credits and higher rate limits.

### Starter Plan

- **Price**: 15 KWD/month
- **Credits/month**: 15,000
- **Rate limit**: 10 requests/minute
- **API keys**: 1
- **Best for**: Hobbyists, small projects, testing

### Basic Plan

- **Price**: 25 KWD/month
- **Credits/month**: 25,000
- **Rate limit**: 10 requests/minute
- **API keys**: 2
- **Best for**: Small businesses, side projects

### Pro Plan

- **Price**: 45 KWD/month
- **Credits/month**: 50,000
- **Rate limit**: 30 requests/minute
- **API keys**: 3
- **Best for**: Growing businesses, production applications

### Enterprise Plan

- **Price**: Custom
- **Credits/month**: Custom
- **Rate limit**: 60 requests/minute
- **API keys**: Unlimited
- **Best for**: Large organizations, high-volume workloads

Contact support at info@resayil.io for Enterprise pricing and custom agreements.

## Free 7-Day Trial

Start with a risk-free **7-day free trial**:

1. Go to https://llm.resayil.io/billing/plans
2. Click "Start Free Trial"
3. Provide a valid payment method (KNET or credit card)
4. Your trial account is activated immediately
5. After 7 days, your account will automatically convert to the **Starter Plan** (15 KWD/month)

**What you get during trial**:
- Full access to all 45 models
- Trial credits (enough to explore all model families)
- 10 requests/minute rate limit
- 1 API key

**Can you cancel**? Yes — before day 7 ends, you can cancel at https://llm.resayil.io/billing/subscription to avoid the recurring charge. After cancellation, your account remains active until day 7, then is suspended.

## Credit Top-ups

If your monthly credits run out, purchase additional credits anytime:

1. Go to https://llm.resayil.io/billing/plans
2. Select a top-up package:

| Credits | Price (KWD) | Cost per Credit |
|---------|-------------|-----------------|
| 5,000 | 2 | 0.0004 KWD |
| 15,000 | 5 | 0.00033 KWD |
| 50,000 | 15 | 0.0003 KWD |

3. Choose your payment method (KNET or credit card)
4. Complete payment
5. Credits are added to your account immediately

**Bulk pricing**: Larger packages offer better per-credit rates. For the best value, buy the largest package that suits your needs.

## Monthly Credit Renewal

- **Subscription credits** renew automatically on the first day of each month
- **Purchased top-up credits** never expire (use them anytime)
- Unused subscription credits **do not roll over** to the next month
- If your plan changes (e.g., Starter → Pro), the new monthly allowance starts on the next billing cycle

### Example Timeline

- **March 1**: Starter plan activated, 15,000 credits added
- **March 15**: Purchase 5,000 top-up credits (now have 20,000 total)
- **March 28**: Use 18,000 credits (2,000 remaining)
- **April 1**: Remaining 2,000 credits + 15,000 new monthly credits = 17,000 total

## What Happens When Credits Run Out?

When you run out of credits, API requests return **HTTP 402 (Payment Required)**:

```json
{
  "error": {
    "code": "insufficient_credits",
    "message": "Insufficient credits. Please purchase a top-up at /billing/plans"
  }
}
```

Your account is **not suspended** — it simply cannot make new API calls until you purchase more credits.

### Quick Recovery

1. Go to https://llm.resayil.io/billing/plans
2. Purchase a top-up (5k, 15k, or 50k credits)
3. Complete payment within 2-3 minutes
4. Resume making API calls

## Checking Your Usage

### Dashboard

Visit https://llm.resayil.io/dashboard to see:

- **Current credit balance**
- **Monthly usage chart** (credits used this month)
- **Recent API calls** with token counts and costs
- **Credits remaining** (monthly + top-up)

### Usage History

The dashboard shows your last 100 API calls, including:
- Timestamp
- Model used
- Tokens consumed (prompt + completion)
- Credits deducted
- Response time

Download usage reports for accounting or cost analysis.

## Payment Methods

LLM Resayil accepts two payment methods via MyFatoorah:

### KNET (Kuwait)

Local debit cards issued by Kuwaiti banks. Fastest checkout for domestic users.

- Accepted by all Kuwaiti banks
- No foreign transaction fees
- Instant processing

### International Credit Cards

Visa and Mastercard issued worldwide.

- Accepted globally
- May incur currency conversion fees
- Instant processing

## Invoices & Receipts

- **Subscription invoices** are generated automatically on the first day of each month
- **Top-up invoices** are generated immediately upon purchase
- View all invoices at https://llm.resayil.io/billing/invoices
- Download invoices for your records (PDF format)

## Plan Changes & Downgrades

### Upgrade (e.g., Starter → Pro)

1. Go to https://llm.resayil.io/billing/subscription
2. Select a higher plan
3. Pay the prorated difference for the current month
4. Effective immediately (your new rate limits and API key limits apply at once)

### Downgrade (e.g., Pro → Starter)

1. Go to https://llm.resayil.io/billing/subscription
2. Select a lower plan
3. Downgrade is effective at the start of the next billing cycle (you keep higher limits until then)
4. No refund issued for the current month

### Cancel Subscription

1. Go to https://llm.resayil.io/billing/subscription
2. Click "Cancel Subscription"
3. Confirm cancellation
4. Your account remains active with existing credits until the current month ends
5. After your plan expires, no new charges occur (you can still use top-up credits)

## Enterprise & Custom Billing

For large-volume use cases, contact support at info@resayil.io to discuss:

- Custom credit allowances
- Volume discounts
- Dedicated support
- Custom rate limits
- Invoice-based billing
- SLA agreements

## Cost Examples

### Example 1: Small Project Using Local Models

- Monthly subscription: Starter (15 KWD = 15,000 credits)
- Average request: 100-token response = 100 credits
- Requests per month: 150
- Credits used: 15,000
- Cost: 15 KWD/month

### Example 2: Growing Business Using Mix of Models

- Monthly subscription: Pro (45 KWD = 50,000 credits)
- Requests: 200 local (100 tokens each) + 100 cloud (150 tokens each)
- Local cost: 200 × 100 = 20,000 credits
- Cloud cost: 100 × 150 × 2 = 30,000 credits
- Total: 50,000 credits
- Cost: 45 KWD/month

### Example 3: Burst Usage with Top-ups

- Base subscription: Starter (15 KWD = 15,000 credits)
- Usage month 1: 12,000 credits (within limit)
- Burst month 2: 30,000 credits needed
  - Use 15,000 monthly credits
  - Purchase 15,000 top-up (5 KWD)
- Total month 2 cost: 15 + 5 = 20 KWD

## Billing FAQs

### Q: Do unused monthly credits roll over?

**A**: No. Subscription credits are reset monthly. Top-up credits never expire.

### Q: Can I pause my subscription?

**A**: No, but you can downgrade to the free tier (if available) or cancel. Top-up credits remain usable.

### Q: What if I'm charged twice?

**A**: Check your billing dashboard for duplicate invoices. Contact support at info@resayil.io with invoice numbers for investigation and refunds if applicable.

### Q: Is there a discount for annual billing?

**A**: Currently available only for Enterprise plans. Contact support for details.

### Q: Can I get a refund if I don't use all my credits?

**A**: Monthly subscription credits cannot be refunded. Top-up credits can be refunded if unused within 30 days (subject to verification).

## Support

For billing inquiries or issues:

- **Dashboard**: https://llm.resayil.io/billing/plans
- **Email**: info@resayil.io
- **Response time**: Usually within 24 hours
