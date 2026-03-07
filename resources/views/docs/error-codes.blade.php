@extends('layouts.app')

@section('title', 'Error Codes & Troubleshooting — API Documentation')

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
            <span>Error Codes & Troubleshooting</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Error Codes & <span>Troubleshooting</span></h1>
        <p class="docs-intro">
            Learn about common API errors, their causes, and how to resolve them.
            Use this guide to debug issues and implement error handling in your applications.
        </p>

        <!-- Section 1: Understanding Error Responses -->
        <section class="docs-section">
            <h2>Understanding Error Responses</h2>
            <p>
                When the LLM Resayil API encounters an error, it returns a JSON response with details about what went wrong.
                Understanding how to parse these responses is essential for building robust error handling in your applications.
            </p>

            <h3>Error Response Structure</h3>
            <p>
                All error responses follow this format:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Error Response Format</div>
                <code>{
  "error": {
    "code": "error_code",
    "message": "Human-readable error message",
    "type": "error_category",
    "details": {
      "field": "optional field that caused the error"
    }
  }
}</code>
            </div>

            <h3>Error Categories</h3>
            <ul>
                <li><strong>authentication_error:</strong> Invalid or missing API key</li>
                <li><strong>authorization_error:</strong> Authenticated but not authorized</li>
                <li><strong>validation_error:</strong> Invalid request parameters</li>
                <li><strong>rate_limit_error:</strong> Rate limit exceeded</li>
                <li><strong>server_error:</strong> Internal server error</li>
                <li><strong>service_error:</strong> Service temporarily unavailable</li>
            </ul>

            <h3>HTTP Status Codes</h3>
            <p>
                The HTTP status code in the response header indicates the general category of the error:
            </p>
            <ul>
                <li><strong>4xx:</strong> Client error (your request was malformed or invalid)</li>
                <li><strong>5xx:</strong> Server error (something went wrong on our side)</li>
            </ul>
        </section>

        <!-- Section 2: Common Status Codes -->
        <section class="docs-section">
            <h2>Common HTTP Status Codes</h2>
            <p>
                Below is a detailed reference of the most common status codes you'll encounter when using the LLM Resayil API:
            </p>

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Typical Cause</th>
                        <th>How to Fix</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>200</code></td>
                        <td>OK</td>
                        <td>Request was successful</td>
                        <td>No action needed. Check the response body for results.</td>
                    </tr>
                    <tr>
                        <td><code>400</code></td>
                        <td>Bad Request</td>
                        <td>Invalid JSON, missing required field, or invalid parameter value</td>
                        <td>Check your request JSON for syntax errors, missing fields, or invalid values. See validation errors in response.</td>
                    </tr>
                    <tr>
                        <td><code>401</code></td>
                        <td>Unauthorized</td>
                        <td>Missing, invalid, or expired API key</td>
                        <td>Verify your API key is correct and included in the Authorization header. Generate a new key if needed.</td>
                    </tr>
                    <tr>
                        <td><code>403</code></td>
                        <td>Forbidden</td>
                        <td>Authenticated but not authorized (account suspended, tier issue)</td>
                        <td>Check your account status in the dashboard. Contact support if your account is suspended.</td>
                    </tr>
                    <tr>
                        <td><code>429</code></td>
                        <td>Too Many Requests</td>
                        <td>Rate limit exceeded</td>
                        <td>Implement exponential backoff and retry after the Retry-After header suggests. See Rate Limits guide.</td>
                    </tr>
                    <tr>
                        <td><code>500</code></td>
                        <td>Internal Server Error</td>
                        <td>Unexpected server error</td>
                        <td>Retry after a short delay. If persists, contact support with request ID from error response.</td>
                    </tr>
                    <tr>
                        <td><code>503</code></td>
                        <td>Service Unavailable</td>
                        <td>Server is temporarily down for maintenance or overloaded</td>
                        <td>Retry after a few seconds. Check our status page for planned maintenance. Implement exponential backoff.</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Section 3: Detailed Error Examples -->
        <section class="docs-section">
            <h2>Error Response Examples</h2>

            <h3>401 Unauthorized — Invalid API Key</h3>
            <p>
                You're sending an invalid or expired API key in the Authorization header.
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">401 Response Example</div>
                <code>HTTP/1.1 401 Unauthorized

{
  "error": {
    "code": "invalid_api_key",
    "message": "Invalid API key provided.",
    "type": "authentication_error"
  }
}</code>
            </div>

            <p><strong>Solutions:</strong></p>
            <ul>
                <li>Verify your API key is correct (copy from dashboard, no extra spaces)</li>
                <li>Ensure the Authorization header format is: <code>Authorization: Bearer YOUR_KEY</code></li>
                <li>Generate a new key if you think the current one is compromised</li>
                <li>Check that your key hasn't been revoked</li>
            </ul>

            <h3>400 Bad Request — Missing Field</h3>
            <p>
                Your request is missing a required parameter.
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">400 Response Example</div>
                <code>HTTP/1.1 400 Bad Request

{
  "error": {
    "code": "missing_required_field",
    "message": "Missing required field: 'messages'",
    "type": "validation_error",
    "details": {
      "field": "messages"
    }
  }
}</code>
            </div>

            <p><strong>Solutions:</strong></p>
            <ul>
                <li>Check that your request includes all required fields (model, messages)</li>
                <li>Ensure messages is an array of message objects with role and content</li>
                <li>Validate your JSON syntax before sending</li>
            </ul>

            <h3>429 Too Many Requests — Rate Limited</h3>
            <p>
                You've exceeded your rate limit. You'll receive a 429 response with a Retry-After header.
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">429 Response Example</div>
                <code>HTTP/1.1 429 Too Many Requests
Retry-After: 5
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1699564899

{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "You have exceeded your rate limit. Please retry after 5 seconds.",
    "type": "rate_limit_error"
  }
}</code>
            </div>

            <p><strong>Solutions:</strong></p>
            <ul>
                <li>Wait the number of seconds specified in the Retry-After header</li>
                <li>Implement exponential backoff for automatic retries</li>
                <li>Check your tier's rate limits and consider upgrading if you frequently hit limits</li>
                <li>Implement client-side rate limiting to stay below your quota</li>
            </ul>

            <h3>500 Internal Server Error</h3>
            <p>
                Something went wrong on our servers. These are rare and usually temporary.
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">500 Response Example</div>
                <code>HTTP/1.1 500 Internal Server Error

{
  "error": {
    "code": "internal_server_error",
    "message": "An unexpected error occurred. Please try again later.",
    "type": "server_error",
    "request_id": "req_12345678"
  }
}</code>
            </div>

            <p><strong>Solutions:</strong></p>
            <ul>
                <li>Retry the request after a few seconds</li>
                <li>Implement exponential backoff for automatic retries</li>
                <li>If the error persists, <a href="{{ route('contact') }}" class="docs-link">contact support</a> with the request_id from the error</li>
            </ul>
        </section>

        <!-- Section 4: Debugging Checklist -->
        <section class="docs-section">
            <h2>Debugging Checklist</h2>
            <p>
                When you encounter an error, work through this checklist to identify and resolve the issue:
            </p>

            <h3>For All Errors</h3>
            <ul>
                <li>Check your API key is correct and properly formatted in the Authorization header</li>
                <li>Verify the request URL is exactly: <code>https://llm.resayil.io/api/v1/chat/completions</code></li>
                <li>Ensure you're using POST method, not GET</li>
                <li>Verify Content-Type header is set to <code>application/json</code></li>
                <li>Check that your request JSON is valid (no syntax errors)</li>
            </ul>

            <h3>For 400 Errors</h3>
            <ul>
                <li>Include all required fields in your request: model, messages</li>
                <li>Verify the model name is valid and exists in your account</li>
                <li>Ensure messages is an array of objects with "role" and "content"</li>
                <li>Check that parameter values are of the correct type (strings, numbers, booleans)</li>
                <li>Use the "details" field in the error response to identify the problematic field</li>
            </ul>

            <h3>For 401/403 Errors</h3>
            <ul>
                <li>Verify your account is active and not suspended</li>
                <li>Check that your API key hasn't been revoked</li>
                <li>Ensure your subscription tier is active</li>
                <li>Confirm you have enough credits to make API calls</li>
            </ul>

            <h3>For 429 Errors</h3>
            <ul>
                <li>Check the <code>X-RateLimit-Remaining</code> header in your responses</li>
                <li>Implement exponential backoff retry logic</li>
                <li>Reduce the frequency of your requests</li>
                <li>Consider upgrading to a higher tier for increased limits</li>
            </ul>

            <h3>For 500/503 Errors</h3>
            <ul>
                <li>Retry the request after 5-10 seconds</li>
                <li>Implement exponential backoff for multiple retries</li>
                <li>Check our <a href="{{ route('contact') }}" class="docs-link">status page</a> for known issues</li>
                <li>Contact support if errors persist with the request ID</li>
            </ul>
        </section>

        <!-- Section 5: Best Practices -->
        <section class="docs-section">
            <h2>Error Handling Best Practices</h2>

            <h3>1. Always Parse Error Responses</h3>
            <p>
                Don't just check the HTTP status code. Parse the error response JSON to understand what went wrong
                and provide meaningful feedback to your users.
            </p>

            <h3>2. Implement Retry Logic</h3>
            <p>
                Use exponential backoff with jitter for retryable errors (429, 500, 503). See the Rate Limits guide for details.
            </p>

            <h3>3. Log Errors for Debugging</h3>
            <p>
                Log full error responses including status codes, error codes, and request IDs. This makes debugging much easier.
            </p>

            <h3>4. Provide User-Friendly Messages</h3>
            <p>
                Don't expose raw error messages to users. Map error codes to friendly messages and provide actionable guidance.
            </p>

            <h3>5. Monitor Rate Limits Proactively</h3>
            <p>
                Check <code>X-RateLimit-Remaining</code> in responses and throttle requests before hitting the limit.
            </p>

            <h3>6. Set Appropriate Timeouts</h3>
            <p>
                Set request timeouts to at least 30 seconds to allow for slower models and network latency.
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>Related Resources</h2>
            <ul>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits & Quotas</a> — Rate limit errors and 429 responses</li>
                <li><a href="{{ route('docs.authentication') }}" class="docs-link">Authentication</a> — API key authentication and 401 errors</li>
                <li><a href="{{ route('contact') }}" class="docs-link">Contact Support</a> — Get help with persistent errors</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>Done with documentation?</h3>
            <p>Ready to start building with LLM Resayil.</p>
            <a href="{{ route('register') }}">Create Free Account →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Error Codes & Troubleshooting', 'url' => route('docs.error-codes')]
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
