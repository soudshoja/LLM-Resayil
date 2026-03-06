@extends('layouts.app')

@section('title', $pageTitle ?? 'Features & Models — LLM Resayil')

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
        <div class="features-badge">Platform Features</div>
        <h1>45+ Models, One <span>OpenAI-Compatible</span> API</h1>
        <p class="features-hero-lead">
            LLM Resayil gives you access to a growing library of LLM models through a single, familiar API.
            No vendor lock-in, no complex setup — just change your base URL and start building.
        </p>

        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-value">45+</span>
                <div class="hero-stat-label">Available Models</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">1</span>
                <div class="hero-stat-label">API Endpoint</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">1,000</span>
                <div class="hero-stat-label">Free Credits</div>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">0</span>
                <div class="hero-stat-label">Monthly Fees</div>
            </div>
        </div>
    </div>

    <!-- OpenAI Compatible -->
    <section class="features-section">
        <div class="compat-section">
            <div class="compat-left">
                <h3>Drop-in OpenAI Replacement</h3>
                <p>
                    The API follows the OpenAI specification exactly. If you already use OpenAI's SDK or any
                    OpenAI-compatible client, switching takes one line of code — change the base URL, keep everything else.
                </p>
                <ul class="compat-list">
                    <li><span class="check">✓</span> <code>/v1/chat/completions</code> endpoint</li>
                    <li><span class="check">✓</span> Streaming responses (SSE)</li>
                    <li><span class="check">✓</span> Model listing via <code>/v1/models</code></li>
                    <li><span class="check">✓</span> Bearer token authentication</li>
                    <li><span class="check">✓</span> Works with OpenAI Python & JS SDKs</li>
                </ul>
            </div>
            <div class="compat-code">
<span class="code-comment"># Python — one-line change</span>
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
        <div class="features-section-label">What You Get</div>
        <h2 class="features-section-title">Everything You Need</h2>
        <p class="features-section-subtitle">From API keys and usage tracking to team management — all in one place.</p>

        <div class="core-features-grid">
            <div class="feature-card">
                <span class="feature-card-icon">&#128273;</span>
                <h3>Multiple API Keys</h3>
                <p>Create unlimited API keys per account. Assign keys to different projects, revoke them individually, and monitor usage per key.</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128200;</span>
                <h3>Usage Dashboard</h3>
                <p>Real-time credit balance, per-request cost breakdown, usage history, and monthly summaries — all visible in your dashboard.</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#129689;</span>
                <h3>45+ Language Models</h3>
                <p>Llama, Qwen, Gemma, Mistral, DeepSeek, and more. New models added regularly. See the full list via the API.</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128357;</span>
                <h3>Pay-Per-Token Credits</h3>
                <p>No subscriptions. Buy credits as needed. Credits never expire. Only output tokens are charged — input is free.</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#128274;</span>
                <h3>Secure Authentication</h3>
                <p>API keys with bearer token auth. OTP-verified account registration. No unauthorized access.</p>
            </div>
            <div class="feature-card">
                <span class="feature-card-icon">&#127760;</span>
                <h3>Arabic & English UI</h3>
                <p>Full RTL support for Arabic. The dashboard, billing, and all pages are available in both English and Arabic.</p>
            </div>
        </div>
    </section>

    <!-- Available Models -->
    <section class="features-section" style="padding-top: 1rem;">
        <div class="features-section-label">Model Library</div>
        <h2 class="features-section-title">Available Models</h2>
        <p class="features-section-subtitle">A curated selection of open-source models, continuously updated. Query <code style="background: var(--bg-card); padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.85em;">/v1/models</code> for the current list.</p>

        <div class="model-category-grid">
            <div class="model-category-card">
                <h4>Chat & Instruction</h4>
                <div class="model-tags">
                    <span class="model-tag">Llama 3.1 8B</span>
                    <span class="model-tag">Llama 3.1 70B</span>
                    <span class="model-tag">Qwen 2.5</span>
                    <span class="model-tag">Gemma 2</span>
                    <span class="model-tag">Mistral 7B</span>
                    <span class="model-tag">+ more</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>Coding</h4>
                <div class="model-tags">
                    <span class="model-tag">Qwen 2.5 Coder</span>
                    <span class="model-tag">DeepSeek Coder</span>
                    <span class="model-tag">CodeLlama</span>
                    <span class="model-tag">+ more</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>Reasoning</h4>
                <div class="model-tags">
                    <span class="model-tag">DeepSeek R1</span>
                    <span class="model-tag">DeepSeek V3</span>
                    <span class="model-tag">Qwen QwQ</span>
                    <span class="model-tag">+ more</span>
                </div>
            </div>
            <div class="model-category-card">
                <h4>Multilingual</h4>
                <div class="model-tags">
                    <span class="model-tag">Arabic-optimized</span>
                    <span class="model-tag">Aya Expanse</span>
                    <span class="model-tag">Command R</span>
                    <span class="model-tag">+ more</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison table -->
    <section class="features-section" style="padding-top: 1rem;">
        <div class="features-section-label">Why Choose Us</div>
        <h2 class="features-section-title">LLM Resayil vs. Alternatives</h2>
        <p class="features-section-subtitle">See how we compare on the features that matter most.</p>

        <table class="benefits-table">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>LLM Resayil</th>
                    <th>OpenAI</th>
                    <th>OpenRouter</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Free tier (no card)</td>
                    <td class="ours">1,000 credits</td>
                    <td class="no">Paid only</td>
                    <td class="yes">Yes</td>
                </tr>
                <tr>
                    <td>OpenAI-compatible API</td>
                    <td class="ours">Yes</td>
                    <td class="yes">Yes</td>
                    <td class="yes">Yes</td>
                </tr>
                <tr>
                    <td>Input tokens charged</td>
                    <td class="ours">No (free)</td>
                    <td class="no">Yes</td>
                    <td class="no">Yes</td>
                </tr>
                <tr>
                    <td>Pricing currency</td>
                    <td class="ours">KWD</td>
                    <td>USD</td>
                    <td>USD</td>
                </tr>
                <tr>
                    <td>Credits expire</td>
                    <td class="ours">Never</td>
                    <td class="no">Yes</td>
                    <td class="yes">No</td>
                </tr>
                <tr>
                    <td>Arabic language UI</td>
                    <td class="ours">Full RTL</td>
                    <td class="no">No</td>
                    <td class="no">No</td>
                </tr>
                <tr>
                    <td>Shared credit balance across keys</td>
                    <td class="ours">Yes</td>
                    <td class="yes">Yes</td>
                    <td class="yes">Yes</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 1rem;">
            <a href="/comparison" style="color: var(--gold); font-size: 0.9rem; font-weight: 600;">See the full cost comparison &rarr;</a>
        </div>
    </section>

    <!-- CTA -->
    <div class="features-cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Create a free account, get 1,000 credits, and make your first API call in under 5 minutes.</p>
        <div class="cta-buttons">
            <a href="/register" class="btn btn-gold">Create Free Account</a>
            <a href="/docs" class="btn-outline-gold">Read the Docs</a>
        </div>
    </div>

</div>
@endsection
