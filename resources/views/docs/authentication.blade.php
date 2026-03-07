@extends('layouts.app')

@section('title', 'Authentication — API Documentation')

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
            <span>Authentication</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">API <span>Authentication</span></h1>
        <p class="docs-intro">
            Learn how to authenticate with the LLM Resayil API using API keys and Bearer tokens.
            Understand best practices for securing your credentials and managing your API keys.
        </p>

        <!-- Section 1: API Key Authentication -->
        <section class="docs-section">
            <h2>API Key Authentication</h2>
            <p>
                LLM Resayil uses Bearer token authentication with API keys. Every API request must include your API key
                in the Authorization header. API keys are permanent credentials associated with your account and can be
                managed from your dashboard.
            </p>

            <h3>How API Keys Work</h3>
            <p>
                When you create an API key:
            </p>
            <ul>
                <li><strong>It's account-specific:</strong> The key is tied to your account and inherits your subscription tier and credit limits.</li>
                <li><strong>It never expires:</strong> API keys are permanent until you manually revoke them.</li>
                <li><strong>It represents full access:</strong> Anyone with your key can make API requests and consume your credits.</li>
                <li><strong>Multiple keys allowed:</strong> You can create multiple keys for different applications or teammates.</li>
            </ul>

            <h3>Finding and Generating Keys</h3>
            <p>
                To manage your API keys:
            </p>
            <ol style="list-style: decimal; margin-left: 2rem;">
                <li>Log in to your <a href="{{ route('dashboard') }}" class="docs-link">LLM Resayil dashboard</a></li>
                <li>Navigate to <strong>API Keys</strong> in the left sidebar</li>
                <li>Click <strong>"Generate New Key"</strong> to create a new key</li>
                <li>Your key will display once—copy it immediately and store it securely</li>
            </ol>

            <div class="docs-info-box">
                <p><strong>Important:</strong> API keys are displayed only once after creation. If you lose your key, you'll need
                to generate a new one. Save your keys in a secure location like a password manager.</p>
            </div>
        </section>

        <!-- Section 2: Bearer Token Format -->
        <section class="docs-section">
            <h2>Authorization Header Format</h2>
            <p>
                Every request to the LLM Resayil API must include an Authorization header with your API key in Bearer token format.
                The header must be formatted exactly as shown below:
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">Authorization Header</div>
                <code>Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <p>
                Replace <strong>YOUR_API_KEY</strong> with your actual API key. The word "Bearer" must be included and is case-sensitive.
                A space must separate "Bearer" and your key.
            </p>

            <h3>Examples in Different Languages</h3>

            <p><strong>cURL:</strong></p>
            <div class="docs-code-block">
                <code>curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Hello"}]}'</code>
            </div>

            <p><strong>JavaScript (fetch):</strong></p>
            <div class="docs-code-block">
                <code>const response = await fetch('https://llm.resayil.io/api/v1/chat/completions', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    model: 'mistral',
    messages: [{ role: 'user', content: 'Hello' }]
  })
});
const data = await response.json();</code>
            </div>

            <p><strong>Python (requests):</strong></p>
            <div class="docs-code-block">
                <code>import requests

response = requests.post(
  'https://llm.resayil.io/api/v1/chat/completions',
  headers={
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  json={
    'model': 'mistral',
    'messages': [{'role': 'user', 'content': 'Hello'}]
  }
)
data = response.json()</code>
            </div>
        </section>

        <!-- Section 3: API Key Lifecycle -->
        <section class="docs-section">
            <h2>API Key Lifecycle</h2>
            <p>
                Understanding how to manage your API keys is crucial for security and operational continuity.
            </p>

            <h3>Key Generation</h3>
            <p>
                When you generate a new API key:
            </p>
            <ul>
                <li>You'll see the key once on the confirmation screen</li>
                <li>Copy and save it immediately—you won't see it again</li>
                <li>The key is immediately active and ready to use</li>
                <li>Multiple keys can be active simultaneously</li>
            </ul>

            <h3>Best Practices for Key Management</h3>
            <ul>
                <li><strong>Never commit to version control:</strong> Store keys in environment variables or secret management tools, never in code or .env files checked into git.</li>
                <li><strong>Use different keys for different apps:</strong> Create separate keys for development, staging, and production environments.</li>
                <li><strong>Rotate keys regularly:</strong> Even if not compromised, rotate keys periodically for security hygiene.</li>
                <li><strong>Use minimal permissions:</strong> If your platform supports it, use keys with restricted access rather than full account access.</li>
                <li><strong>Monitor key usage:</strong> Regularly review which keys are active and revoke unused ones.</li>
            </ul>

            <h3>Revoking Keys</h3>
            <p>
                If you suspect a key has been compromised or you no longer need it:
            </p>
            <ol style="list-style: decimal; margin-left: 2rem;">
                <li>Go to <strong>API Keys</strong> in your dashboard</li>
                <li>Click <strong>"Revoke"</strong> next to the key you want to disable</li>
                <li>Confirm the revocation</li>
                <li>The key is immediately deactivated and cannot be used for new requests</li>
            </ol>

            <div class="docs-info-box">
                <p><strong>After Revocation:</strong> Any requests using a revoked key will return a <strong>401 Unauthorized</strong> error.
                If you're using this key in production, update your application to use a new key before revoking the old one.</p>
            </div>
        </section>

        <!-- Section 4: Error Handling -->
        <section class="docs-section">
            <h2>Authentication Error Handling</h2>
            <p>
                If your authentication fails, you'll receive an error response. Here's how to diagnose and fix common auth issues:
            </p>

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Status Code</th>
                        <th>Error</th>
                        <th>Cause</th>
                        <th>Solution</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>401</td>
                        <td>Unauthorized</td>
                        <td>Missing, invalid, or malformed API key</td>
                        <td>Check that Authorization header is present and formatted correctly. Verify your API key hasn't expired or been revoked.</td>
                    </tr>
                    <tr>
                        <td>401</td>
                        <td>Invalid API Key</td>
                        <td>The provided key doesn't exist or is invalid</td>
                        <td>Generate a new key from your dashboard and update your application.</td>
                    </tr>
                    <tr>
                        <td>401</td>
                        <td>Key Revoked</td>
                        <td>The API key has been revoked</td>
                        <td>Generate a new key and deploy it to your application.</td>
                    </tr>
                    <tr>
                        <td>403</td>
                        <td>Forbidden</td>
                        <td>Authenticated but not authorized (account suspended or tier issue)</td>
                        <td>Check your account status and subscription tier in the dashboard.</td>
                    </tr>
                </tbody>
            </table>

            <h3>401 Unauthorized Response Example</h3>
            <div class="docs-code-block">
                <div class="docs-code-label">Example Error Response</div>
                <code>{
  "error": {
    "code": "invalid_api_key",
    "message": "Invalid API key provided.",
    "type": "authentication_error"
  }
}</code>
            </div>
        </section>

        <!-- Section 5: Security Best Practices -->
        <section class="docs-section">
            <h2>Security Best Practices</h2>

            <h3>1. Environment Variables</h3>
            <p>
                Always load your API key from environment variables, not from hardcoded strings or config files:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Python Example</div>
                <code>import os
from openai import OpenAI

api_key = os.getenv('LLM_RESAYIL_API_KEY')
client = OpenAI(api_key=api_key, base_url='https://llm.resayil.io/api/v1')</code>
            </div>

            <h3>2. HTTPS Only</h3>
            <p>
                Always use HTTPS when communicating with the LLM Resayil API. Never send API keys over unencrypted HTTP connections.
            </p>

            <h3>3. Request Authentication Headers</h3>
            <p>
                Use the Authorization header method shown above. Do not pass your API key as a query parameter.
            </p>

            <h3>4. Access Control</h3>
            <p>
                Limit which team members have access to your API keys. Provide different keys for different applications
                and teammates to minimize blast radius if a key is compromised.
            </p>

            <h3>5. Monitoring and Alerts</h3>
            <p>
                Regularly review your dashboard for suspicious activity. If you notice requests from unexpected IP addresses
                or unusual traffic patterns, revoke the relevant key immediately.
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>Related Resources</h2>
            <ul>
                <li><a href="{{ route('docs.getting-started') }}" class="docs-link">Getting Started</a> — Your first API request</li>
                <li><a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> — Troubleshooting authentication issues</li>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> — Request quotas and limits</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>What's next?</h3>
            <p>Explore the available models and their capabilities.</p>
            <a href="{{ route('docs.models') }}">Go to Available Models →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Authentication', 'url' => route('docs.authentication')]
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
