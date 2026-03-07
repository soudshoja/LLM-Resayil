@extends('layouts.app')

@section('title', 'Available Models — API Documentation')

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
            <span>Available Models</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Available <span>Models</span></h1>
        <p class="docs-intro">
            LLM Resayil provides access to 45+ large language models from leading providers.
            Choose the right model for your use case based on speed, accuracy, and cost.
        </p>

        <!-- Section 1: Available Models -->
        <section class="docs-section">
            <h2>Popular Models</h2>
            <p>
                Our platform includes a curated selection of the most powerful and efficient language models available today.
                Each model has different strengths, performance characteristics, and token consumption rates. Below are some
                of our most popular models:
            </p>

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Provider</th>
                        <th>Context Window</th>
                        <th>Best For</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>mistral</code></td>
                        <td>Mistral AI</td>
                        <td>32K tokens</td>
                        <td>Speed, general tasks, low latency</td>
                    </tr>
                    <tr>
                        <td><code>llama2</code></td>
                        <td>Meta</td>
                        <td>4K tokens</td>
                        <td>Reasoning, dialogue, complex queries</td>
                    </tr>
                    <tr>
                        <td><code>neural-chat</code></td>
                        <td>Intel</td>
                        <td>4K tokens</td>
                        <td>Conversational AI, customer support</td>
                    </tr>
                    <tr>
                        <td><code>deepseek</code></td>
                        <td>DeepSeek</td>
                        <td>4K tokens</td>
                        <td>Code generation, technical tasks</td>
                    </tr>
                    <tr>
                        <td><code>qwen</code></td>
                        <td>Alibaba</td>
                        <td>8K tokens</td>
                        <td>Multilingual, Chinese content</td>
                    </tr>
                </tbody>
            </table>

            <div class="docs-info-box">
                <p><strong>Note:</strong> We currently maintain 45+ models total. This table shows the most popular options.
                For a complete list of available models, check your dashboard or <a href="{{ route('contact') }}" class="docs-link">contact support</a>.</p>
            </div>
        </section>

        <!-- Section 2: Model Selection Guide -->
        <section class="docs-section">
            <h2>Model Selection Guide</h2>
            <p>
                Choosing the right model depends on your specific requirements. Here's a guide to help you decide:
            </p>

            <h3>Use Mistral When You Need:</h3>
            <ul>
                <li><strong>Speed:</strong> Mistral is one of the fastest models with latency under 1 second on average</li>
                <li><strong>Low Cost:</strong> Fastest token consumption means lower credits used per request</li>
                <li><strong>General Tasks:</strong> Works well for content generation, summarization, and general Q&A</li>
                <li><strong>Low Latency APIs:</strong> Best for real-time applications and user-facing features</li>
            </ul>

            <h3>Use Llama 2 When You Need:</h3>
            <ul>
                <li><strong>Reasoning:</strong> Better for complex logical tasks and multi-step reasoning</li>
                <li><strong>Longer Context:</strong> Handles longer documents and conversations better</li>
                <li><strong>Quality Over Speed:</strong> More accurate responses at the cost of slightly longer latency</li>
                <li><strong>Open Source:</strong> Community-supported model with extensive documentation</li>
            </ul>

            <h3>Use Neural Chat When You Need:</h3>
            <ul>
                <li><strong>Conversation:</strong> Optimized for dialogue and back-and-forth interactions</li>
                <li><strong>Customer Support:</strong> Trained to be helpful, polite, and contextually aware</li>
                <li><strong>User Experience:</strong> Natural, conversational responses</li>
            </ul>

            <h3>Use Deepseek When You Need:</h3>
            <ul>
                <li><strong>Code Generation:</strong> Specialized for writing and explaining code</li>
                <li><strong>Technical Tasks:</strong> Better understanding of programming concepts</li>
                <li><strong>Debugging:</strong> Can analyze and explain technical errors</li>
            </ul>

            <h3>Use Qwen When You Need:</h3>
            <ul>
                <li><strong>Multilingual Support:</strong> Excellent for Chinese and other Asian languages</li>
                <li><strong>Asian Language Tasks:</strong> Better cultural and linguistic understanding</li>
                <li><strong>Extended Context:</strong> 8K token window for longer documents</li>
            </ul>
        </section>

        <!-- Section 3: Model Capabilities -->
        <section class="docs-section">
            <h2>Universal Model Capabilities</h2>
            <p>
                All LLM Resayil models share common capabilities:
            </p>

            <h3>Supported Request Types</h3>
            <ul>
                <li><strong>Text Completion:</strong> Continue text from a prompt</li>
                <li><strong>Chat Interface:</strong> Multi-turn conversations with message history</li>
                <li><strong>System Prompts:</strong> Define model behavior and personality with a system message</li>
                <li><strong>Streaming Responses:</strong> Real-time token-by-token output for better UX</li>
                <li><strong>Temperature & Top-P:</strong> Control randomness and diversity of responses</li>
            </ul>

            <h3>API Request Format</h3>
            <p>
                All models use the OpenAI-compatible Chat Completions endpoint:
            </p>
            <div class="docs-code-block">
                <div class="docs-code-label">Endpoint</div>
                <code>POST https://llm.resayil.io/api/v1/chat/completions</code>
            </div>

            <h3>Example Request</h3>
            <div class="docs-code-block">
                <div class="docs-code-label">Chat Completion Request</div>
                <code>{
  "model": "mistral",
  "messages": [
    {"role": "system", "content": "You are a helpful assistant."},
    {"role": "user", "content": "Explain quantum computing in simple terms."}
  ],
  "temperature": 0.7,
  "top_p": 0.9,
  "max_tokens": 500,
  "stream": false
}</code>
            </div>

            <h3>Output Tokens Per Model</h3>
            <p>
                Each model has different maximum output lengths. The <code>max_tokens</code> parameter limits the response length,
                but each model has its own absolute maximum. All models return <code>usage</code> information showing
                <code>prompt_tokens</code> and <code>completion_tokens</code> consumed, allowing you to calculate costs accurately.
            </p>
        </section>

        <!-- Section 4: Token Consumption -->
        <section class="docs-section">
            <h2>Token Consumption & Pricing</h2>
            <p>
                Token consumption varies by model. Smaller, faster models consume fewer tokens per request,
                while larger models with more capabilities consume more. Check the <a href="{{ route('docs.billing') }}" class="docs-link">Billing & Credits</a> guide
                for detailed pricing information and token consumption rates per model.
            </p>

            <p>
                Every API response includes a <code>usage</code> field showing exactly how many tokens were consumed,
                making it easy to forecast costs and budget your API usage:
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">Usage Information in Response</div>
                <code>"usage": {
  "prompt_tokens": 15,
  "completion_tokens": 42,
  "total_tokens": 57
}</code>
            </div>
        </section>

        <!-- Section 5: Model Availability -->
        <section class="docs-section">
            <h2>Model Availability & Status</h2>

            <h3>Checking Available Models</h3>
            <p>
                Models are listed in your dashboard and available via the models API endpoint.
                All models listed are available and ready to use immediately. There are no access restrictions
                based on subscription tier—all tiers can access all 45+ models.
            </p>

            <h3>Model Updates</h3>
            <p>
                We regularly update our model catalog to include the latest and most capable models.
                When new models are released, they're tested for quality and then added to our platform.
                Existing models may be updated to newer versions automatically for continuous improvements.
            </p>

            <h3>Deprecations</h3>
            <p>
                If a model is ever deprecated, we provide at least 30 days notice and guidance on migration
                to replacement models. You'll be notified via email and dashboard notifications.
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>Related Resources</h2>
            <ul>
                <li><a href="{{ route('docs.getting-started') }}" class="docs-link">Getting Started</a> — Your first API request</li>
                <li><a href="{{ route('docs.billing') }}" class="docs-link">Billing & Credits</a> — Token consumption rates</li>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> — Request quotas per tier</li>
                <li><a href="{{ route('pricing') }}" class="docs-link">Pricing</a> — Subscription tiers and costs</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>Ready to start building?</h3>
            <p>Learn about token consumption and billing to understand costs.</p>
            <a href="{{ route('docs.billing') }}">Go to Billing & Credits →</a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Available Models', 'url' => route('docs.models')]
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
