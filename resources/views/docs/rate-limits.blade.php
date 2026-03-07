@extends('layouts.app')

@section('title', 'Rate Limits & Quotas — API Documentation')

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
            <span>Rate Limits & Quotas</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Rate Limits & <span>Quotas</span></h1>
        <p class="docs-intro">
            Understand how LLM Resayil enforces rate limits and quotas to ensure fair usage and service stability.
            Learn how to handle rate limit responses and implement backoff strategies.
        </p>

        <!-- Section 1: Rate Limiting Overview -->
        <section class="docs-section">
            <h2>Rate Limiting Overview</h2>
            <p>
                LLM Resayil applies rate limits to prevent abuse and ensure fair access to our API for all users.
                Rate limits are based on your subscription tier and are enforced at multiple levels: per-minute and per-day quotas.
            </p>

            <h3>Why Rate Limits?</h3>
            <ul>
                <li><strong>Fair Usage:</strong> Prevents any single account from monopolizing resources</li>
                <li><strong>Service Stability:</strong> Protects the infrastructure from being overwhelmed</li>
                <li><strong>Cost Control:</strong> Helps you avoid accidentally consuming excessive credits</li>
                <li><strong>Spam Prevention:</strong> Reduces abuse and malicious usage patterns</li>
            </ul>

            <h3>How Limits Are Applied</h3>
            <p>
                Limits are calculated in UTC time. Your quota resets at the beginning of each minute (for per-minute limits)
                and at the start of each day (for per-day limits). When you exceed a limit, you'll receive a <strong>429 Too Many Requests</strong> response
                and must wait for the quota to reset before retrying.
            </p>

            <div class="docs-info-box">
                <p><strong>Note:</strong> Your specific tier and limits are displayed in your dashboard.
                Contact support if you need higher limits for legitimate use cases.</p>
            </div>
        </section>

        <!-- Section 2: Tier-Based Limits -->
        <section class="docs-section">
            <h2>Tier-Based Rate Limits</h2>
            <p>
                Your rate limits depend on your subscription tier. Here's a breakdown of typical limits for each tier:
            </p>

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Tier</th>
                        <th>Requests/Min</th>
                        <th>Requests/Day</th>
                        <th>Max Tokens/Request</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Free</strong></td>
                        <td>5</td>
                        <td>100</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td><strong>Basic</strong></td>
                        <td>20</td>
                        <td>500</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td><strong>Pro</strong></td>
                        <td>100</td>
                        <td>5,000</td>
                        <td>2,000</td>
                    </tr>
                    <tr>
                        <td><strong>Premium</strong></td>
                        <td>500</td>
                        <td>50,000</td>
                        <td>4,000</td>
                    </tr>
                </tbody>
            </table>

            <h3>Understanding Your Tier</h3>
            <p>
                To find your current tier:
            </p>
            <ol style="list-style: decimal; margin-left: 2rem;">
                <li>Log in to your <a href="{{ route('dashboard') }}" class="docs-link">LLM Resayil dashboard</a></li>
                <li>Go to <strong>Billing</strong> or <strong>Account Settings</strong></li>
                <li>Your subscription tier will be displayed</li>
            </ol>

            <h3>Tier Upgrades</h3>
            <p>
                If you're frequently hitting rate limits, upgrade your subscription plan from your dashboard.
                Upgrades take effect immediately, and you'll gain access to higher request quotas and token limits.
            </p>
        </section>

        <!-- Section 3: Handling Rate Limit Errors -->
        <section class="docs-section">
            <h2>Handling Rate Limit Responses</h2>
            <p>
                When you exceed your rate limit, you'll receive a <strong>429 Too Many Requests</strong> HTTP response.
                Here's how to recognize and handle rate limit errors:
            </p>

            <h3>429 Response Format</h3>
            <div class="docs-code-block">
                <div class="docs-code-label">HTTP Status 429 Response</div>
                <code>{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Rate limit exceeded. Please retry after some time.",
    "type": "rate_limit_error"
  }
}</code>
            </div>

            <h3>Rate Limit Headers</h3>
            <p>
                When you receive a rate limit response, check these HTTP response headers for additional information:
            </p>
            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Header</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>X-RateLimit-Limit</code></td>
                        <td>Your maximum requests per minute (e.g., 20)</td>
                    </tr>
                    <tr>
                        <td><code>X-RateLimit-Remaining</code></td>
                        <td>Requests remaining in the current minute</td>
                    </tr>
                    <tr>
                        <td><code>X-RateLimit-Reset</code></td>
                        <td>Unix timestamp when the quota resets</td>
                    </tr>
                    <tr>
                        <td><code>Retry-After</code></td>
                        <td>Seconds to wait before retrying</td>
                    </tr>
                </tbody>
            </table>

            <h3>Checking Remaining Quota</h3>
            <p>
                Always check the <code>X-RateLimit-Remaining</code> header in your responses to monitor how close you are
                to the rate limit. This allows you to implement proactive throttling:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">JavaScript Example</div>
                <code>const response = await fetch(apiUrl, options);
const remaining = response.headers.get('X-RateLimit-Remaining');
const limit = response.headers.get('X-RateLimit-Limit');

if (remaining < limit * 0.2) {
  console.warn(`Only ${remaining} requests remaining!`);
}</code>
            </div>
        </section>

        <!-- Section 4: Implementing Backoff -->
        <section class="docs-section">
            <h2>Implementing Backoff Strategies</h2>
            <p>
                When you hit a rate limit, implement exponential backoff to gracefully retry your requests.
                This is more reliable than immediately retrying and helps distribute load.
            </p>

            <h3>Exponential Backoff with Jitter</h3>
            <p>
                The recommended approach is exponential backoff with jitter to avoid thundering herd problems:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Python Example: Exponential Backoff</div>
                <code>import time
import random

def make_api_call_with_backoff(api_url, data, max_retries=5):
    for attempt in range(max_retries):
        try:
            response = requests.post(api_url, json=data, timeout=10)
            if response.status_code == 200:
                return response.json()
            elif response.status_code == 429:
                # Rate limited - implement backoff
                wait_time = (2 ** attempt) + random.uniform(0, 1)
                print(f"Rate limited. Waiting {wait_time:.1f} seconds...")
                time.sleep(wait_time)
            else:
                response.raise_for_status()
        except Exception as e:
            print(f"Error: {e}")
            if attempt < max_retries - 1:
                wait_time = (2 ** attempt) + random.uniform(0, 1)
                time.sleep(wait_time)
            else:
                raise
    raise Exception("Max retries exceeded")</code>
            </div>

            <h3>Backoff Strategy Details</h3>
            <ul>
                <li><strong>Start Small:</strong> Begin with 1 second wait, then 2s, 4s, 8s, etc.</li>
                <li><strong>Add Jitter:</strong> Add random 0-1 second to prevent synchronized retries from multiple clients</li>
                <li><strong>Max Wait Time:</strong> Cap maximum wait at 60 seconds to avoid indefinite delays</li>
                <li><strong>Retry Limit:</strong> Set a maximum retry limit (typically 5-10 attempts)</li>
            </ul>
        </section>

        <!-- Section 5: Best Practices -->
        <section class="docs-section">
            <h2>Best Practices for Rate Limit Management</h2>

            <h3>1. Batch Requests</h3>
            <p>
                When possible, combine multiple requests into a single API call. This counts as one request toward your quota
                while processing more data, making efficient use of your limits.
            </p>

            <h3>2. Implement Client-Side Rate Limiting</h3>
            <p>
                Don't rely solely on server-side rate limiting. Implement client-side throttling to stay well below your quota:
            </p>
            <div class="docs-code-block">
                <code>// Limit to 80% of max requests to stay safe
const MAX_SAFE_RATE = 0.8;
const maxRequestsPerMinute = 20; // Your tier limit
const safeRate = maxRequestsPerMinute * MAX_SAFE_RATE; // 16 req/min
const delayBetweenRequests = 60000 / safeRate; // ~3750ms</code>
            </div>

            <h3>3. Cache Responses When Possible</h3>
            <p>
                Cache API responses to avoid repeated requests for the same queries. This dramatically reduces API usage
                and keeps you well within rate limits.
            </p>

            <h3>4. Stagger High-Volume Work</h3>
            <p>
                If you need to process large amounts of data, spread requests over time rather than sending them all at once.
                This prevents burst limit violations while maintaining consistent throughput.
            </p>

            <h3>5. Monitor and Alert</h3>
            <p>
                Set up alerts in your application when <code>X-RateLimit-Remaining</code> drops below 20% of your limit.
                This gives you early warning to take action before you start getting 429 errors.
            </p>

            <h3>6. Upgrade When Needed</h3>
            <p>
                If your application legitimately needs higher rate limits, upgrade your subscription tier or contact support
                about enterprise options. It's better to be proactive than to experience service degradation.
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>Related Resources</h2>
            <ul>
                <li><a href="{{ route('docs.billing') }}" class="docs-link">Billing & Credits</a> — Token consumption and costs</li>
                <li><a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> — Understanding HTTP status codes</li>
                <li><a href="{{ route('pricing') }}" class="docs-link">Pricing</a> — Subscription tiers and rates</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>Need more help?</h3>
            <p>Learn about common errors and how to troubleshoot them.</p>
            <a href="{{ route('docs.error-codes') }}">Go to Error Codes & Troubleshooting →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Rate Limits & Quotas', 'url' => route('docs.rate-limits')]
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
