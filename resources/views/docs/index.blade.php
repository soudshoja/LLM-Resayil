@extends('layouts.app')

@section('title', 'API Documentation — LLM Resayil')

@push('styles')
<style>
    /* ── Documentation Landing Page ── */
    .docs-wrap {
        background: var(--bg-secondary);
        padding: 3rem 2rem;
    }

    /* Breadcrumb Navigation */
    .docs-breadcrumb {
        max-width: 1200px;
        margin: 0 auto 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
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

    /* Hero Section */
    .docs-hero {
        max-width: 900px;
        margin: 0 auto 3rem;
        text-align: center;
    }

    .docs-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: var(--text-primary);
    }

    .docs-hero h1 span {
        color: var(--gold);
    }

    .docs-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto 1.5rem;
        line-height: 1.7;
    }

    .docs-quick-example {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem auto 0;
        max-width: 600px;
        text-align: left;
    }

    .docs-quick-example p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .docs-quick-example pre {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem;
        overflow-x: auto;
        font-size: 0.8rem;
        line-height: 1.5;
        color: #a0d468;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    /* Documentation Grid Section */
    .docs-section {
        max-width: 1200px;
        margin: 4rem auto 0;
    }

    .docs-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--text-primary);
    }

    .docs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .doc-card:hover {
        border-color: rgba(212, 175, 55, 0.5);
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(212, 175, 55, 0.15);
    }

    .doc-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .doc-card p {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        flex: 1;
        margin-bottom: 1.5rem;
    }

    .doc-card .doc-link-arrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gold);
        font-weight: 600;
        font-size: 0.9rem;
        transition: gap 0.2s;
    }

    .doc-card:hover .doc-link-arrow {
        gap: 0.75rem;
    }

    .doc-card .doc-link-arrow svg {
        width: 18px;
        height: 18px;
        transition: transform 0.2s;
    }

    .doc-card:hover .doc-link-arrow svg {
        transform: translateX(4px);
    }

    /* Help / Next Steps Section */
    .docs-next-steps {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 2.5rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-steps h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .docs-next-steps p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .docs-next-steps a {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: var(--gold);
        color: var(--bg-primary);
        border-radius: 8px;
        font-weight: 600;
        transition: opacity 0.2s;
        text-decoration: none;
        margin: 0 0.5rem;
    }

    .docs-next-steps a:hover {
        opacity: 0.85;
    }

    .docs-footer-links {
        margin-top: 2rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .docs-footer-links a {
        color: var(--gold);
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-footer-links a:hover {
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .docs-hero h1 {
            font-size: 1.75rem;
        }

        .docs-hero-lead {
            font-size: 1rem;
        }

        .docs-quick-example {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-quick-example pre {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="docs-wrap">

    <!-- Breadcrumb Navigation -->
    <div class="docs-breadcrumb">
        <a href="{{ route('welcome') }}">Home</a>
        <span>&rarr;</span>
        <span>Documentation</span>
    </div>

    <!-- Hero Section -->
    <div class="docs-hero">
        <h1>API <span>Documentation</span></h1>
        <p class="docs-hero-lead">
            Learn how to integrate LLM Resayil into your applications. Our API is OpenAI-compatible, supports 45+ models,
            and offers pay-per-token pricing. Get started in minutes with clear documentation and code examples.
        </p>

        <!-- Quick Start Example -->
        <div class="docs-quick-example">
            <p><strong>Quick Example:</strong> Make your first API request</p>
            <pre><code>curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "mistral",
    "messages": [{"role": "user", "content": "Hello!"}],
    "max_tokens": 100
  }'</code></pre>
        </div>
    </div>

    <!-- Documentation Sections -->
    <section class="docs-section">
        <h2>Get Started</h2>

        <div class="docs-grid">
            <!-- Getting Started -->
            <a href="{{ route('docs.getting-started') }}" class="doc-card">
                <h3>Getting Started</h3>
                <p>
                    Learn the basics. Discover how to register, obtain your API key, and make your first request to the LLM Resayil API.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Authentication -->
            <a href="{{ route('docs.authentication') }}" class="doc-card">
                <h3>Authentication</h3>
                <p>
                    Understand API key authentication, Bearer token format, and how to securely manage your credentials.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Available Models -->
            <a href="{{ route('docs.models') }}" class="doc-card">
                <h3>Available Models</h3>
                <p>
                    Explore our 45+ available models including Mistral, Llama 2, Neural Chat, and more. Learn their capabilities.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Billing & Credits -->
            <a href="{{ route('docs.billing') }}" class="doc-card">
                <h3>Billing & Credits</h3>
                <p>
                    Learn how our credit system works, token consumption rates, and how to manage your account balance.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Rate Limits -->
            <a href="{{ route('docs.rate-limits') }}" class="doc-card">
                <h3>Rate Limits & Quotas</h3>
                <p>
                    Understand request limits, quota resets, and best practices for handling rate limit responses.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Error Codes -->
            <a href="{{ route('docs.error-codes') }}" class="doc-card">
                <h3>Error Codes & Troubleshooting</h3>
                <p>
                    Reference guide for common errors, status codes, and how to debug and resolve API issues.
                </p>
                <div class="doc-link-arrow">
                    Read Guide
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </section>

    <!-- Next Steps -->
    <div class="docs-next-steps">
        <h2>Ready to Build?</h2>
        <p>Choose your next step below to get started with LLM Resayil.</p>
        <div>
            <a href="{{ route('docs.getting-started') }}">Start with Getting Started</a>
            <a href="{{ route('register') }}">Create Free Account</a>
        </div>
        <div class="docs-footer-links">
            <p>
                Need help? Visit our <a href="{{ route('contact') }}">Contact page</a>,
                check out <a href="{{ route('welcome') }}">features</a>,
                or view <a href="{{ route('pricing') }}">pricing plans</a>.
            </p>
        </div>
    </div>

</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')]
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
