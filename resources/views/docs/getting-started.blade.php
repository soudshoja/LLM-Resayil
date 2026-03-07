@extends('layouts.app')

@section('title', 'Getting Started — API Documentation')

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
            <span>Getting Started</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Getting <span>Started</span></h1>
        <p class="docs-intro">
            Learn the basics of LLM Resayil in just a few minutes. This guide will walk you through registration,
            obtaining your API key, and making your first API request.
        </p>

        <!-- Section 1: What is LLM Resayil? -->
        <section class="docs-section">
            <h2>What is LLM Resayil?</h2>
            <p>
                LLM Resayil is an OpenAI-compatible API that provides access to 45+ large language models. Whether you need
                fast inference with Mistral, powerful reasoning with Llama 2, or specialized models for specific tasks,
                LLM Resayil lets you access them all with a single, unified API. Our pay-per-token pricing means you only
                pay for what you use—no monthly subscriptions, no hidden fees. Start with 1,000 free credits and scale up
                as your needs grow.
            </p>
        </section>

        <!-- Section 2: Getting Your API Key -->
        <section class="docs-section">
            <h2>Getting Your API Key</h2>
            <p>
                To use the LLM Resayil API, you'll need an API key. Here's how to get one in three simple steps:
            </p>

            <h3>Step 1: Register or Log In</h3>
            <p>
                Visit <a href="{{ route('register') }}" class="docs-link">https://llm.resayil.io/register</a> to create
                a free account. If you already have an account, simply <a href="{{ route('login') }}" class="docs-link">log in</a>.
                Registration takes less than two minutes and comes with 1,000 free credits to get started.
            </p>

            <h3>Step 2: Navigate to API Keys</h3>
            <p>
                After logging in, go to your dashboard and click on <strong>"API Keys"</strong> in the left sidebar. This page
                shows all your active API keys and allows you to manage them.
            </p>

            <h3>Step 3: Copy Your API Key</h3>
            <p>
                Click the <strong>"Generate New Key"</strong> button to create a new API key. Your key will be displayed once—copy it
                immediately and store it somewhere safe. You'll use this key to authenticate all your API requests. Never share your
                API key publicly or commit it to version control.
            </p>

            <div class="docs-info-box">
                <p><strong>Security Tip:</strong> Treat your API key like a password. Store it in environment variables, not in code.
                If you accidentally expose your key, revoke it immediately from the API Keys page and generate a new one.</p>
            </div>
        </section>

        <!-- Section 3: Your First Request -->
        <section class="docs-section">
            <h2>Your First Request</h2>
            <p>
                Now that you have an API key, let's make your first API request. The LLM Resayil API uses the same format as OpenAI's
                Chat Completions endpoint, so if you've used OpenAI before, you'll feel right at home.
            </p>

            <h3>Understanding the Authorization Header</h3>
            <p>
                Every API request must include an <strong>Authorization</strong> header with your API key in the following format:
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">Authorization Header Format</div>
                <code>Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <p>
                Replace <strong>YOUR_API_KEY</strong> with the actual API key you generated in the previous step.
                The word "Bearer" is required and case-sensitive.
            </p>

            <h3>Making a Chat Completion Request</h3>
            <p>
                Here's a complete example of making a chat completion request using cURL. Copy this and replace
                <strong>YOUR_API_KEY</strong> with your actual key:
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">cURL Example</div>
                <code>curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "mistral",
    "messages": [
      {
        "role": "user",
        "content": "Hello! What is your name?"
      }
    ],
    "max_tokens": 100
  }'</code>
            </div>

            <h3>Request Parameters Explained</h3>
            <p>Here's what each parameter in the request means:</p>
            <ul>
                <li><strong>model:</strong> The name of the model to use (e.g., "mistral", "llama2", "neural-chat"). See our <a href="{{ route('docs.models') }}" class="docs-link">Models guide</a> for available options.</li>
                <li><strong>messages:</strong> An array of message objects with "role" (user, assistant, or system) and "content" (the text).</li>
                <li><strong>max_tokens:</strong> The maximum number of tokens the model should generate in its response.</li>
                <li><strong>temperature (optional):</strong> Controls randomness. Lower values (0.1) make responses more deterministic; higher values (0.9) make them more creative.</li>
                <li><strong>top_p (optional):</strong> Controls diversity via nucleus sampling. Typical value is 0.9.</li>
            </ul>

            <h3>Understanding the Response</h3>
            <p>
                When your request is successful, you'll receive a JSON response. Here's what a typical response looks like:
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">Example Response</div>
                <code>{
  "id": "chatcmpl-123456",
  "object": "chat.completion",
  "created": 1234567890,
  "model": "mistral",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "My name is Mistral. I am an AI assistant..."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 10,
    "completion_tokens": 25,
    "total_tokens": 35
  }
}</code>
            </div>

            <p>Key fields in the response:</p>
            <ul>
                <li><strong>choices:</strong> An array containing the model's response. The first choice (index 0) contains the actual message.</li>
                <li><strong>message.content:</strong> The actual text response from the model.</li>
                <li><strong>usage:</strong> Token consumption breakdown. Use this to estimate costs.</li>
                <li><strong>finish_reason:</strong> Why the model stopped (usually "stop" for successful completion).</li>
            </ul>
        </section>

        <!-- Section 4: What's Next? -->
        <section class="docs-section">
            <h2>What's Next?</h2>
            <p>
                Congratulations on making your first API request! Here are some suggested next steps to continue building:
            </p>
            <ul>
                <li><strong>Explore Models:</strong> Head to the <a href="{{ route('docs.models') }}" class="docs-link">Available Models</a> guide to learn about all 45+ models and their capabilities.</li>
                <li><strong>Learn Authentication:</strong> Read the <a href="{{ route('docs.authentication') }}" class="docs-link">Authentication</a> guide for best practices on managing API keys securely.</li>
                <li><strong>Understand Billing:</strong> Visit the <a href="{{ route('docs.billing') }}" class="docs-link">Billing & Credits</a> page to understand token consumption and pricing.</li>
                <li><strong>Handle Errors:</strong> Check out the <a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> guide to learn how to handle common issues.</li>
                <li><strong>Rate Limits:</strong> Learn about rate limits and how to implement backoff strategies in the <a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> guide.</li>
            </ul>
        </section>

        <!-- Common Issues -->
        <section class="docs-section">
            <h2>Common Issues</h2>

            <h3>401 Unauthorized Error</h3>
            <p>
                This error means your API key is missing, invalid, or incorrectly formatted in the Authorization header.
                Double-check that you're using the correct key and that it's prefixed with "Bearer ".
            </p>

            <h3>429 Too Many Requests</h3>
            <p>
                You've exceeded your rate limit for the current time window. Wait a moment before retrying, or upgrade your
                subscription tier for higher limits. See the <a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> guide for details.
            </p>

            <h3>Connection Timeout</h3>
            <p>
                If your request times out, try again with a longer timeout value. Cold connections to our API can take 1-3 seconds.
                Once connected, subsequent requests are typically much faster.
            </p>

            <div class="docs-info-box">
                <p><strong>Need Help?</strong> If you're stuck, <a href="{{ route('contact') }}" class="docs-link">contact our support team</a>
                or visit the <a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> guide for more troubleshooting tips.</p>
            </div>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>Ready to dive deeper?</h3>
            <p>Learn about API authentication and best practices.</p>
            <a href="{{ route('docs.authentication') }}">Go to Authentication Guide →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Getting Started', 'url' => route('docs.getting-started')]
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
