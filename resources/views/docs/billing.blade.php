@extends('layouts.app')

@section('title', 'Billing & Credits — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page Styles ── */
    .docs-page {
        background: var(--bg-secondary);
        padding: 3rem 2rem;
        min-height: 100vh;
    }

    .docs-content {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .docs-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .docs-breadcrumb a {
        color: var(--gold);
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-breadcrumb a:hover {
        opacity: 0.8;
    }

    .docs-breadcrumb span {
        color: var(--text-secondary);
    }

    /* Page Title */
    .docs-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: var(--text-primary);
    }

    .docs-title span {
        color: var(--gold);
    }

    .docs-intro {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    /* Sections */
    .docs-section {
        margin-bottom: 3rem;
    }

    .docs-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .docs-section h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .docs-section p {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .docs-section ul {
        list-style: none;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .docs-section li {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 0.75rem;
        position: relative;
        padding-left: 1rem;
    }

    .docs-section li:before {
        content: "▸";
        color: var(--gold);
        position: absolute;
        left: 0;
    }

    .docs-section strong {
        color: var(--text-primary);
    }

    /* Code Blocks */
    .docs-code-block {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        overflow-x: auto;
    }

    .docs-code-block code {
        display: block;
        font-size: 0.85rem;
        line-height: 1.6;
        color: #a0d468;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .docs-code-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Info Box */
    .docs-info-box {
        background: rgba(212, 175, 55, 0.08);
        border-left: 4px solid var(--gold);
        border-radius: 4px;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-info-box p {
        margin: 0;
    }

    /* Link */
    .docs-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .docs-link:hover {
        opacity: 0.8;
    }

    /* Table */
    .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.95rem;
    }

    .docs-table th,
    .docs-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .docs-table th {
        background: var(--bg-card);
        font-weight: 600;
        color: var(--text-primary);
    }

    .docs-table td {
        color: var(--text-secondary);
    }

    .docs-table code {
        background: rgba(0, 0, 0, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-family: 'Monaco', 'Courier New', monospace;
        color: #a0d468;
    }

    /* Next Section Link */
    .docs-next-section {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-section h3 {
        margin-bottom: 1rem;
    }

    .docs-next-section a {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: var(--gold);
        color: var(--bg-primary);
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-next-section a:hover {
        opacity: 0.85;
    }

    @media (max-width: 768px) {
        .docs-title {
            font-size: 1.5rem;
        }

        .docs-section h2 {
            font-size: 1.25rem;
        }

        .docs-code-block {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-table {
            font-size: 0.85rem;
        }

        .docs-table th,
        .docs-table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="docs-page">
    <div class="docs-content">

        <!-- Breadcrumb -->
        <div class="docs-breadcrumb">
            <a href="{{ route('welcome') }}">Home</a>
            <span>&rarr;</span>
            <a href="{{ route('docs.index') }}">Documentation</a>
            <span>&rarr;</span>
            <span>Billing & Credits</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Billing & <span>Credits</span></h1>
        <p class="docs-intro">
            Understand how LLM Resayil's credit system works, how we calculate token consumption,
            and how to manage your account balance and costs.
        </p>

        <!-- Section 1: How Credits Work -->
        <section class="docs-section">
            <h2>How Credits Work</h2>
            <p>
                LLM Resayil uses a credit-based billing system. Instead of monthly subscriptions, you purchase credits
                that are consumed based on your API usage. This pay-per-token model means you only pay for what you actually use.
            </p>

            <h3>What Are Credits?</h3>
            <p>
                Credits are our standard unit of account. One credit is equivalent to processing approximately 1,000 tokens
                across all models (rates vary slightly by model). When you make an API request, the appropriate number of credits
                is deducted from your account based on the tokens used.
            </p>

            <h3>Starting Balance</h3>
            <p>
                New accounts receive <strong>1,000 free credits</strong> to get started. Use these to explore the API and test
                different models. No credit card is required for the free tier. When your free credits are exhausted, you'll need
                to purchase more credits to continue using the API.
            </p>

            <div class="docs-info-box">
                <p><strong>Free Credits Validity:</strong> Free credits are valid for 30 days from account creation.
                After 30 days, your free credits will expire. Purchased credits do not expire and remain valid indefinitely.</p>
            </div>
        </section>

        <!-- Section 2: Token Consumption -->
        <section class="docs-section">
            <h2>Token Consumption</h2>
            <p>
                Understanding how tokens are counted is crucial for budgeting. The LLM Resayil API counts two types of tokens:
                the tokens in your input (prompt) and the tokens in the model's output (completion).
            </p>

            <h3>Prompt Tokens</h3>
            <p>
                <strong>Prompt tokens</strong> are the tokens in your request message. These are counted from the moment
                you send your message to the model, including any system prompts, message history, and the user's query.
                Longer conversations and more context mean more prompt tokens and higher costs.
            </p>

            <h3>Completion Tokens</h3>
            <p>
                <strong>Completion tokens</strong> are the tokens the model generates in its response. These count from the first
                token the model outputs to the end of the response. The <code>max_tokens</code> parameter you set in your request
                limits the maximum number of completion tokens that can be generated.
            </p>

            <h3>Cost Calculation</h3>
            <p>
                Your total token consumption for a request is:
            </p>
            <div class="docs-code-block">
                <code>Total Tokens = Prompt Tokens + Completion Tokens</code>
            </div>

            <p>
                And the credit consumption depends on the model and token type (rates vary slightly). As a general approximation:
            </p>
            <div class="docs-code-block">
                <code>Credits Used ≈ (Prompt Tokens + Completion Tokens) / 1000</code>
            </div>

            <h3>Example Calculation</h3>
            <p>
                Suppose you send a 100-token request and the model responds with 200 tokens:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Example</div>
                <code>Prompt Tokens: 100
Completion Tokens: 200
Total Tokens: 300
Credits Used: 0.3</code>
            </div>

            <p>
                This would consume approximately 0.3 credits from your account. The exact rate depends on the model.
                Check your <a href="{{ route('dashboard') }}" class="docs-link">dashboard</a> for model-specific pricing.
            </p>
        </section>

        <!-- Section 3: Viewing Your Usage -->
        <section class="docs-section">
            <h2>Viewing Your Usage</h2>
            <p>
                Track your API usage and credit consumption from your account dashboard:
            </p>

            <h3>Dashboard Analytics</h3>
            <ul>
                <li><strong>Current Credit Balance:</strong> Shows available credits remaining in your account</li>
                <li><strong>Usage This Month:</strong> Total tokens and credits consumed in the current billing period</li>
                <li><strong>Cost Breakdown by Model:</strong> See which models are consuming the most credits</li>
                <li><strong>API Call History:</strong> View transaction logs and usage patterns</li>
            </ul>

            <h3>API Response Usage Information</h3>
            <p>
                Every API response includes token and usage information. Use this to understand exactly what each
                request costs and to implement client-side cost tracking:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Usage Information in Response</div>
                <code>"usage": {
  "prompt_tokens": 45,
  "completion_tokens": 128,
  "total_tokens": 173
}</code>
            </div>

            <h3>Budgeting with Usage Information</h3>
            <p>
                Use the response <code>usage</code> field to:
            </p>
            <ul>
                <li>Calculate cost per API call in your application</li>
                <li>Implement per-user or per-session quotas</li>
                <li>Alert users when they're approaching limits</li>
                <li>Generate cost reports and projections</li>
            </ul>
        </section>

        <!-- Section 4: Top-Up & Subscriptions -->
        <section class="docs-section">
            <h2>Top-Up and Subscription Options</h2>
            <p>
                When your credits run low, you have several options to continue using the API:
            </p>

            <h3>Pay-As-You-Go Top-Up</h3>
            <p>
                Purchase credits anytime through your dashboard. Top-ups are processed immediately via
                <a href="https://myfatoorah.com" class="docs-link">MyFatoorah</a> payment gateway. Available payment methods
                vary by region. Purchased credits are valid indefinitely and never expire.
            </p>

            <h3>Subscription Plans</h3>
            <p>
                Consider purchasing a subscription plan for predictable, ongoing usage. Plans offer monthly credit allocations
                at discounted rates and may include higher rate limits. View available plans on our <a href="{{ route('pricing') }}" class="docs-link">Pricing</a> page.
            </p>

            <h3>How to Top-Up</h3>
            <ol style="list-style: decimal; margin-left: 2rem;">
                <li>Log in to your <a href="{{ route('dashboard') }}" class="docs-link">LLM Resayil dashboard</a></li>
                <li>Go to <strong>Billing</strong> → <strong>Top-Up Credits</strong></li>
                <li>Select the amount of credits you want to purchase</li>
                <li>Complete payment via MyFatoorah</li>
                <li>Credits are added to your account immediately after payment confirmation</li>
            </ol>

            <div class="docs-info-box">
                <p><strong>Payment Methods:</strong> MyFatoorah accepts multiple payment methods including credit cards,
                debit cards, and local payment options depending on your region. See their website for full details.</p>
            </div>
        </section>

        <!-- Section 5: Account Limits & Quotas -->
        <section class="docs-section">
            <h2>Account Limits & Quotas</h2>
            <p>
                Your account's usage limits depend on your subscription tier. All users have rate limits on the number of
                requests they can make per minute or per day. See the <a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> guide
                for complete information on quotas by tier.
            </p>

            <h3>Account Status Impacts</h3>
            <ul>
                <li><strong>Free Tier:</strong> 1,000 starting credits, standard rate limits</li>
                <li><strong>Paid Account (Low Balance):</strong> When credits run low, continue using the API with standard limits</li>
                <li><strong>Suspended Account:</strong> If billing issues occur, your account may be temporarily suspended</li>
            </ul>

            <h3>Credit Monitoring</h3>
            <p>
                The API will include your remaining credit balance in response headers for monitoring. When your balance
                gets low, consider setting up automatic top-ups or subscribing to a plan to avoid service interruptions.
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>Related Resources</h2>
            <ul>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits & Quotas</a> — Request limits by tier</li>
                <li><a href="{{ route('docs.models') }}" class="docs-link">Available Models</a> — Token consumption varies by model</li>
                <li><a href="{{ route('pricing') }}" class="docs-link">Pricing</a> — Subscription plans and rates</li>
                <li><a href="{{ route('dashboard') }}" class="docs-link">Dashboard</a> — View your usage and account balance</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>Ready to learn more?</h3>
            <p>Understand request rate limits and quota management.</p>
            <a href="{{ route('docs.rate-limits') }}">Go to Rate Limits & Quotas →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Billing & Credits', 'url' => route('docs.billing')]
  ];

  $schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_map(function($item, $key) {
      return [
        '@type' => 'ListItem',
        'position' => $key + 1,
        'name' => $item['name'],
        'item' => $item['url']
      ];
    }, $breadcrumbs, array_keys($breadcrumbs))
  ];
@endphp

<script type="application/ld+json">
  @json($schema)
</script>

@endsection
