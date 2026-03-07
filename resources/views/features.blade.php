@extends('layouts.app')

@section('title', $pageTitle ?? __('features.page_title'))

@push('styles')
<style>
    /* ── Features Page Styles ── */
    .features-wrap {
        background: var(--bg-secondary);
    }

    /* Hero */
    .features-hero {
        padding: 4rem 2rem 3rem;
        text-align: center;
        max-width: 820px;
        margin: 0 auto;
    }

    .features-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.3);
        border-radius: 2rem;
        color: var(--gold);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 1.5rem;
    }

    .features-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.25rem;
    }

    .features-hero h1 span {
        color: var(--gold);
    }

    .features-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    @media (max-width: 600px) {
        .features-hero h1 { font-size: 2rem; }
    }

    .hero-stats {
        display: flex;
        gap: 2.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }

    .hero-stat {
        text-align: center;
    }

    .hero-stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gold);
        line-height: 1;
        display: block;
    }

    .hero-stat-label {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }

    /* Section layout */
    .features-section {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem 3rem;
    }

    .features-section-label {
        color: var(--gold);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.75rem;
        text-align: center;
    }

    .features-section-title {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 0.75rem;
    }

    .features-section-subtitle {
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 2.5rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Core features grid */
    .core-features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 900px) {
        .core-features-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 560px) {
        .core-features-grid { grid-template-columns: 1fr; }
    }

    .feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        transition: border-color 0.2s, transform 0.2s;
    }

    .feature-card:hover {
        border-color: rgba(212,175,55,0.3);
        transform: translateY(-2px);
    }

    .feature-card-icon {
        font-size: 1.6rem;
        margin-bottom: 0.75rem;
        display: block;
    }

    .feature-card h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .feature-card p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* OpenAI compatible section */
    .compat-section {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 3rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: center;
    }

    @media (max-width: 720px) {
        .compat-section { grid-template-columns: 1fr; }
    }

    .compat-left h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .compat-left p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.7;
        margin-bottom: 1.25rem;
    }

    .compat-list {
        list-style: none;
    }

    .compat-list li {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        padding: 0.35rem 0;
    }

    .compat-list .check {
        color: var(--gold);
        font-weight: 700;
        flex-shrink: 0;
    }

    .compat-code {
        background: rgba(0,0,0,0.4);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        color: var(--text-secondary);
        line-height: 1.8;
        overflow-x: auto;
    }

    .compat-code .code-comment {
        color: var(--text-muted);
    }

    .compat-code .code-key {
        color: #7dd3fc;
    }

    .compat-code .code-val {
        color: #86efac;
    }

    .compat-code .code-gold {
        color: var(--gold);
    }

    /* Model categories */
    .model-category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 700px) {
        .model-category-grid { grid-template-columns: 1fr; }
    }

    .model-category-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .model-category-card h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--gold);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }

    .model-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .model-tag {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    /* Benefits table */
    .benefits-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .benefits-table th {
        padding: 1rem 1.25rem;
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid var(--border);
        text-align: left;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
    }

    .benefits-table th:not(:first-child) {
        text-align: center;
    }

    .benefits-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .benefits-table td:not(:first-child) {
        text-align: center;
    }

    .benefits-table tr:last-child td {
        border-bottom: none;
    }

    .benefits-table .yes {
        color: #6ee7b7;
        font-weight: 700;
    }

    .benefits-table .no {
        color: var(--text-muted);
    }

    .benefits-table .ours {
        color: var(--gold);
        font-weight: 700;
    }

    /* CTA section */
    .features-cta-section {
        text-align: center;
        padding: 2rem 2rem 4rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .features-cta-section h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .features-cta-section p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-outline-gold {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 1.5rem;
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 8px;
        color: var(--gold);
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-outline-gold:hover {
        background: rgba(212,175,55,0.08);
        border-color: var(--gold);
        color: var(--gold);
    }
</style>
@endpush

@section('content')
<div class="features-wrap">

    <!-- Hero -->
    <div class="features-hero">
        <div class="features-badge">{{ __('features.hero_badge') }}</div>
        <h1>{{ __('features.hero_title_plain') }} <span>{{ __('features.hero_title_gold') }}</span> {{ __('features.hero_title_suffix') }}</h1>
        <p class="features-hero-lead">
            {{ __('features.hero_lead') }}
        </p>

        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-value">{{ __('features.stat_models_value') }}</span>
                <div class="hero-stat-label">{{ __('features.stat_models_label') }}</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ __('features.stat_endpoint_value') }}</span>
                <div class="hero-stat-label">{{ __('features.stat_endpoint_label') }}</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ __('features.stat_credits_value') }}</span>
                <div class="hero-stat-label">{{ __('features.stat_credits_label') }}</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ __('features.stat_fees_value') }}</span>
                <div class="hero-stat-label">{{ __('features.stat_fees_label') }}</div>
            </div>
        </div>
    </div>

    <!-- OpenAI Compatible -->
    <section class="features-section">
        <div class="compat-section">
            <div class="compat-left">
                <h3>{{ __('features.compat_title') }}</h3>
                <p>
                    {{ __('features.compat_desc') }}
                </p>
                <ul class="compat-list">
                    <li><span class="check">✓</span> <code>/v1/chat/completions</code> {{ __('features.compat_item_chat') }}</li>
                    <li><span class="check">✓</span> {{ __('features.compat_item_streaming') }}</li>
                    <li><span class="check">✓</span> {{ __('features.compat_item_models') }}</li>
                    <li><span class="check">✓</span> {{ __('features.compat_item_auth') }}</li>
                    <li><span class="check">✓</span> {{ __('features.compat_item_sdks') }}</li>
                </ul>
            </div>
            <div class="compat-code">
<span class="code-comment">{{ __('features.code_comment') }}</span>
<span class="code-key">from</span> openai <span class="code-key">import</span> OpenAI

client = OpenAI(
    <span class="code-key">base_url</span>=<span class="code-gold">"https://llm.resayil.io/api/v1"</span>,
    <span class="code-key">api_key</span>=<span class="code-val">"your-api-key"</span>,
)

response = client.chat.completions.create(
    <span class="code-key">model</span>=<span class="code-val">"llama3.1:8b"</span>,
    <span class="code-key">messages</span>=[{
        <span class="code-key">"role"</span>: <span class="code-val">"user"</span>,
        <span class="code-key">"content"</span>: <span class="code-val">"Hello!"</span>
    }]
)
            </div>
        </div>
    </section>

    <!-- Core Features -->
    <section class="features-section" style="padding-top: 1rem;">
        <div class="features-section-label">{{ __('features.core_label') }}</div>
        <h2 class="features-section-title">{{ __('features.core_title') }}</h2>
        <p class="features-section-subtitle">{{ __('features.core_subtitle') }}</p>

        <div class="core-features-grid">
            <div class="feature-card">
                <span class="feature-card-icon">&#128273;</span>
                <h3>{{ __('features.feat_keys_title') }}</h3>
                <p>{{ __('features.feat_keys_desc') }}</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128200;</span>
                <h3>{{ __('features.feat_dashboard_title') }}</h3>
                <p>{{ __('features.feat_dashboard_desc') }}</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#129689;</span>
                <h3>{{ __('features.feat_models_title') }}</h3>
                <p>{{ __('features.feat_models_desc') }}</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128357;</span>
                <h3>{{ __('features.feat_credits_title') }}</h3>
                <p>{{ __('features.feat_credits_desc') }}</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128274;</span>
                <h3>{{ __('features.feat_auth_title') }}</h3>
                <p>{{ __('features.feat_auth_desc') }}</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#127760;</span>
                <h3>{{ __('features.feat_lang_title') }}</h3>
                <p>{{ __('features.feat_lang_desc') }}</p>
            </div>
        </div>
    </section>

    <!-- Available Models -->
    <section class="features-section" style="padding-top: 1rem;">
        <div class="features-section-label">{{ __('features.models_label') }}</div>
        <h2 class="features-section-title">{{ __('features.models_title') }}</h2>
        <p class="features-section-subtitle">{{ __('features.models_subtitle') }} <code style="background: var(--bg-card); padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.85em;">/v1/models</code> {{ __('features.models_subtitle_suffix') }}</p>

        <div class="model-category-grid">
            <div class="model-category-card">
                <h4>{{ __('features.cat_chat') }}</h4>
                <div class="model-tags">
                    <span class="model-tag">Llama 3.1 8B</span>
                    <span class="model-tag">Llama 3.1 70B</span>
                    <span class="model-tag">Qwen 2.5</span>
                    <span class="model-tag">Gemma 2</span>
                    <span class="model-tag">Mistral 7B</span>
                    <span class="model-tag">{{ __('features.model_more') }}</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>{{ __('features.cat_coding') }}</h4>
                <div class="model-tags">
                    <span class="model-tag">Qwen 2.5 Coder</span>
                    <span class="model-tag">DeepSeek Coder</span>
                    <span class="model-tag">CodeLlama</span>
                    <span class="model-tag">{{ __('features.model_more') }}</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>{{ __('features.cat_reasoning') }}</h4>
                <div class="model-tags">
                    <span class="model-tag">DeepSeek R1</span>
                    <span class="model-tag">DeepSeek V3</span>
                    <span class="model-tag">Qwen QwQ</span>
                    <span class="model-tag">{{ __('features.model_more') }}</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>{{ __('features.cat_multilingual') }}</h4>
                <div class="model-tags">
                    <span class="model-tag">{{ __('features.model_arabic_optimized') }}</span>
                    <span class="model-tag">Aya Expanse</span>
                    <span class="model-tag">Command R</span>
                    <span class="model-tag">{{ __('features.model_more') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison table -->
    <section class="features-section" style="padding-top: 1rem;">
        <div class="features-section-label">{{ __('features.compare_label') }}</div>
        <h2 class="features-section-title">{{ __('features.compare_title') }}</h2>
        <p class="features-section-subtitle">{{ __('features.compare_subtitle') }}</p>

        <table class="benefits-table">
            <thead>
                <tr>
                    <th>{{ __('features.table_feature') }}</th>
                    <th>{{ __('features.table_us') }}</th>
                    <th>{{ __('features.table_openai') }}</th>
                    <th>{{ __('features.table_openrouter') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ __('features.row_free_tier') }}</td>
                    <td class="ours">{{ __('features.row_free_tier_us') }}</td>
                    <td class="no">{{ __('features.row_free_tier_openai') }}</td>
                    <td class="yes">{{ __('features.row_free_tier_openrouter') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_compat') }}</td>
                    <td class="ours">{{ __('features.row_compat_yes') }}</td>
                    <td class="yes">{{ __('features.row_compat_yes') }}</td>
                    <td class="yes">{{ __('features.row_compat_yes') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_input_tokens') }}</td>
                    <td class="ours">{{ __('features.row_input_tokens_us') }}</td>
                    <td class="no">{{ __('features.row_input_tokens_yes') }}</td>
                    <td class="no">{{ __('features.row_input_tokens_yes') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_currency') }}</td>
                    <td class="ours">{{ __('features.row_currency_us') }}</td>
                    <td>{{ __('features.row_currency_usd') }}</td>
                    <td>{{ __('features.row_currency_usd') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_expire') }}</td>
                    <td class="ours">{{ __('features.row_expire_us') }}</td>
                    <td class="no">{{ __('features.row_expire_openai') }}</td>
                    <td class="yes">{{ __('features.row_expire_openrouter') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_arabic') }}</td>
                    <td class="ours">{{ __('features.row_arabic_us') }}</td>
                    <td class="no">{{ __('features.row_arabic_no') }}</td>
                    <td class="no">{{ __('features.row_arabic_no') }}</td>
                </tr>
                <tr>
                    <td>{{ __('features.row_shared_credits') }}</td>
                    <td class="ours">{{ __('features.row_compat_yes') }}</td>
                    <td class="yes">{{ __('features.row_compat_yes') }}</td>
                    <td class="yes">{{ __('features.row_compat_yes') }}</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 1rem;">
            <a href="/comparison" style="color: var(--gold); font-size: 0.9rem; font-weight: 600;">{{ __('features.full_comparison_link') }} &rarr;</a>
        </div>
    </section>

    <!-- CTA -->
    <div class="features-cta-section">
        <h2>{{ __('features.cta_title') }}</h2>
        <p>{{ __('features.cta_desc') }}</p>
        <div class="cta-buttons">
            <a href="/register" class="btn btn-gold">{{ __('features.cta_register') }}</a>
            <a href="/docs" class="btn-outline-gold">{{ __('features.cta_docs') }}</a>
        </div>
    </div>

</div>

<!-- Product/ProductFeature Schema Markup -->
<script type="application/ld+json">
@php
$product = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => 'LLM Resayil API',
    'url' => url('/features'),
    'description' => 'OpenAI-compatible LLM API with 45+ models, pay-per-token pricing, and no monthly subscriptions.',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.8',
        'ratingCount' => '250',
        'bestRating' => '5',
        'worstRating' => '1'
    ],
    'hasFeature' => [
        [
            '@type' => 'PropertyValue',
            'name' => 'High-Speed Inference',
            'description' => 'Sub-second response times optimized for production use. Powered by efficient model runtime and network optimization with automatic failover to cloud models when local capacity is exceeded. Get consistent, fast responses even under high load.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Multiple Models',
            'description' => 'Access 45+ OpenAI-compatible LLM models from leading providers. Choose by speed, cost, or capability. Includes Llama, Qwen, Gemma, Mistral, DeepSeek, and more. New models added regularly based on research and community requests.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Pay-Per-Use Pricing',
            'description' => 'Only pay for tokens consumed. No minimum commitments or monthly fees. Flexible credit system with credits that never expire. Input tokens are free—only output tokens are charged. Start with 1,000 free credits.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Subscription-Based Rate Limiting',
            'description' => 'Per-tier rate limits (Free, Basic, Pro, Admin). Free tier: 10 req/min. Basic: 100 req/min. Pro: 500 req/min. Transparent quotas prevent unexpected overages and let you control spending predictably.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'OpenAI-Compatible REST API',
            'description' => 'Drop-in OpenAI replacement with identical API format. Simple HTTP endpoints following OpenAI specification. Easy integration with existing OpenAI code—works with Python SDK, JavaScript SDK, and cURL. No vendor lock-in.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Real-Time Monitoring Dashboard',
            'description' => 'Live usage statistics, cost breakdown, API activity, model-specific metrics. See your credit balance, token count, daily usage trends, and monthly summaries. Real-time updates as you make API calls.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Billing Controls',
            'description' => 'Set subscription tier, manage credits, view transaction history, export usage reports. Full transparency and control over expenses. Monitor spending in real-time with cost projections.'
        ],
        [
            '@type' => 'PropertyValue',
            'name' => 'Arabic & English UI',
            'description' => 'Full RTL support for Arabic. Dashboard, billing, documentation, and all pages available in both English and Arabic. Accessible to Arabic-speaking developers worldwide with native language experience.'
        ]
    ]
];
@endphp
{!! json_encode($product, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection
