@extends('layouts.app')

@section('title', 'LLM Resayil vs OpenRouter')

@push('styles')
<style>
    :root {
        --comp-bg: #0f1115;
        --comp-card: #13161d;
        --comp-gold: #d4af37;
        --comp-gold-light: #eac558;
        --comp-text: #e0e5ec;
        --comp-text-muted: #a0a8b5;
        --comp-text-secondary: #a0a8b5;
        --comp-border: #1e2230;
    }

    .comp-wrap {
        background: var(--comp-bg);
        font-family: 'Inter', sans-serif;
        color: var(--comp-text);
        overflow-x: hidden;
    }

    /* ── HERO SECTION ── */
    .comp-hero {
        position: relative;
        min-height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 4rem 2rem 6rem;
        overflow: hidden;
    }

    .comp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 100% 80% at 50% -20%, rgba(212,175,55,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .comp-hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
    }

    .comp-hero h1 {
        font-size: clamp(3rem, 10vw, 12rem);
        font-weight: 900;
        letter-spacing: -0.05em;
        line-height: 1;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--comp-gold), var(--comp-gold-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .comp-hero-subtitle {
        font-size: clamp(1rem, 2vw, 1.5rem);
        color: var(--comp-text-muted);
        font-weight: 400;
        line-height: 1.6;
        margin-bottom: 2.5rem;
    }

    .comp-hero-cta {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        background: var(--comp-gold);
        color: var(--comp-bg);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 150ms ease;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: var(--comp-gold-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212,175,55,0.3);
    }

    .btn-primary:focus-visible {
        outline: 2px solid var(--comp-gold);
        outline-offset: 2px;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        background: transparent;
        color: var(--comp-gold);
        border: 2px solid var(--comp-gold);
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 150ms ease;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
        color: var(--comp-gold-light);
        border-color: var(--comp-gold-light);
        transform: translateY(-2px);
    }

    .btn-secondary:focus-visible {
        outline: 2px solid var(--comp-gold);
        outline-offset: 2px;
    }

    /* ── QUICK COMPARISON TABLE ── */
    .comp-table-section {
        padding: 6rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .comp-section-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 3rem;
        text-align: center;
        letter-spacing: -0.02em;
    }

    .comp-table-wrapper {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .comp-table {
        width: 100%;
        border-collapse: collapse;
    }

    .comp-table th {
        background: rgba(212,175,55,0.08);
        border-bottom: 2px solid var(--comp-border);
        padding: 1.5rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.02em;
        color: var(--comp-text);
    }

    .comp-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--comp-border);
        font-size: 0.95rem;
    }

    .comp-table tr:last-child td {
        border-bottom: none;
    }

    .comp-table td:first-child {
        font-weight: 600;
        color: var(--comp-text);
    }

    .comp-table-winner {
        background: rgba(212,175,55,0.1);
        border-left: 4px solid var(--comp-gold);
        font-weight: 600;
        color: var(--comp-gold);
    }

    .comp-winner-badge {
        display: inline-block;
        background: var(--comp-gold);
        color: var(--comp-bg);
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-left: 0.5rem;
    }

    /* ── COST BREAKDOWN ── */
    .comp-cost-section {
        padding: 6rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .comp-cost-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 3rem;
        margin-top: 3rem;
    }

    .comp-cost-card {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: 12px;
        padding: 2.5rem;
        transition: all 200ms ease;
    }

    .comp-cost-card:hover {
        border-color: var(--comp-gold);
        box-shadow: 0 8px 32px rgba(212,175,55,0.1);
    }

    .comp-cost-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--comp-text);
    }

    .comp-cost-subtitle {
        font-size: 0.875rem;
        color: var(--comp-text-muted);
        margin-bottom: 2rem;
    }

    .comp-cost-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--comp-border);
    }

    .comp-cost-row:last-child {
        border-bottom: none;
    }

    .comp-cost-label {
        font-size: 0.95rem;
        color: var(--comp-text-muted);
    }

    .comp-cost-value {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .comp-cost-resayil {
        color: var(--comp-gold);
    }

    .comp-cost-openrouter {
        color: var(--comp-text);
    }

    .comp-savings {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--comp-border);
        text-align: center;
    }

    .comp-savings-label {
        font-size: 0.875rem;
        color: var(--comp-text-muted);
        margin-bottom: 0.5rem;
    }

    .comp-savings-amount {
        font-size: 2rem;
        font-weight: 900;
        color: var(--comp-gold);
    }

    /* ── FEATURE MATRIX ── */
    .comp-features-section {
        padding: 6rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .comp-features-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-top: 3rem;
    }

    .comp-feature-column {
        display: flex;
        flex-direction: column;
    }

    .comp-feature-column-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--comp-border);
        color: var(--comp-text);
    }

    .comp-feature-column-title.resayil {
        color: var(--comp-gold);
    }

    .comp-feature-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .comp-feature-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .comp-feature-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2px;
        font-weight: bold;
        font-size: 1.25rem;
    }

    .comp-feature-icon.yes {
        color: var(--comp-gold);
    }

    .comp-feature-icon.no {
        color: var(--comp-text-muted);
    }

    .comp-feature-text {
        font-size: 0.95rem;
        line-height: 1.5;
        color: var(--comp-text);
    }

    /* ── FAQ SECTION ── */
    .comp-faq-section {
        padding: 6rem 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .comp-faq-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .comp-faq-item {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: 12px;
        padding: 2rem;
        transition: all 200ms ease;
        cursor: pointer;
    }

    .comp-faq-item:hover {
        border-color: var(--comp-gold);
        box-shadow: 0 4px 16px rgba(212,175,55,0.1);
    }

    .comp-faq-item:focus-visible {
        outline: 2px solid var(--comp-gold);
        outline-offset: 2px;
    }

    .comp-faq-question {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--comp-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
        user-select: none;
    }

    .comp-faq-toggle {
        font-size: 1.5rem;
        color: var(--comp-gold);
        transition: transform 200ms ease;
    }

    .comp-faq-item.active .comp-faq-toggle {
        transform: rotate(180deg);
    }

    .comp-faq-answer {
        font-size: 0.95rem;
        color: var(--comp-text);
        line-height: 1.7;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--comp-border);
        display: none;
    }

    .comp-faq-item.active .comp-faq-answer {
        display: block;
        animation: slideDown 200ms ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── CTA FOOTER ── */
    .comp-footer-cta {
        padding: 4rem 2rem;
        background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, transparent 100%);
        border-top: 1px solid var(--comp-border);
        text-align: center;
    }

    .comp-footer-text {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 2rem;
        color: var(--comp-text);
    }

    .comp-footer-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .comp-hero {
            min-height: 35vh;
            padding: 3rem 1.5rem 4rem;
        }

        .comp-hero h1 {
            font-size: clamp(2rem, 8vw, 3rem);
        }

        .comp-hero-subtitle {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .comp-hero-cta {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .comp-section-title {
            font-size: 1.75rem;
        }

        .comp-table-section,
        .comp-cost-section,
        .comp-features-section,
        .comp-faq-section {
            padding: 3rem 1.5rem;
        }

        .comp-table {
            font-size: 0.85rem;
        }

        .comp-table th,
        .comp-table td {
            padding: 1rem;
        }

        .comp-cost-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .comp-features-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .comp-cost-card {
            padding: 1.5rem;
        }

        .comp-faq-item {
            padding: 1.5rem;
        }

        .comp-faq-question {
            font-size: 1rem;
        }

        .comp-footer-text {
            font-size: 1rem;
        }

        .btn-primary, .btn-secondary {
            min-height: 44px;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .comp-hero {
            padding: 2rem 1rem 3rem;
        }

        .comp-hero h1 {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            margin-bottom: 1rem;
        }

        .comp-hero-subtitle {
            font-size: 0.95rem;
        }

        .comp-table-section,
        .comp-cost-section,
        .comp-features-section,
        .comp-faq-section {
            padding: 2rem 1rem;
        }

        .comp-section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .comp-cost-grid {
            gap: 1.5rem;
        }

        .comp-cost-card {
            padding: 1.25rem;
        }

        .comp-feature-column-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .comp-feature-list {
            gap: 1.5rem;
        }

        .comp-faq-item {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<main class="comp-wrap">
    <!-- HERO SECTION -->
    <section class="comp-hero">
        <div class="comp-hero-content">
            <h1>Save 30% on LLM API</h1>
            <p class="comp-hero-subtitle">LLM Resayil vs. OpenRouter detailed comparison</p>
            <div class="comp-hero-cta">
                <a href="{{ route('register') }}" class="btn-primary">Start Free → 1,000 Credits</a>
                <button class="btn-secondary" aria-label="Scroll to comparison table" onclick="document.querySelector('.comp-table-section').scrollIntoView({ behavior: 'smooth' })">Compare Now</button>
            </div>
        </div>
    </section>

    <!-- QUICK COMPARISON TABLE -->
    <section class="comp-table-section">
        <h2 class="comp-section-title">Quick Comparison</h2>
        <div class="comp-table-wrapper">
            <table class="comp-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>LLM Resayil</th>
                        <th>OpenRouter</th>
                        <th style="text-align: center; width: 120px;">Winner</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Price per 1K tokens</td>
                        <td>$0.0001 - $0.002</td>
                        <td>$0.0005 - $0.15</td>
                        <td style="text-align: center;" class="comp-table-winner">
                            <span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Latency p50</td>
                        <td>~450ms</td>
                        <td>~600ms</td>
                        <td style="text-align: center;" class="comp-table-winner">
                            <span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Models Available</td>
                        <td>45+</td>
                        <td>100+</td>
                        <td style="text-align: center;">OpenRouter</td>
                    </tr>
                    <tr>
                        <td>Setup Time</td>
                        <td>&lt;2 minutes</td>
                        <td>~5 minutes</td>
                        <td style="text-align: center;" class="comp-table-winner">
                            <span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Free Trial</td>
                        <td>1,000 credits (≈$5)</td>
                        <td>$5 credit</td>
                        <td style="text-align: center;" class="comp-table-winner">
                            <span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>
                        </td>
                    </tr>
                    <tr>
                        <td>OpenAI Compatible</td>
                        <td>100% ✓</td>
                        <td>100% ✓</td>
                        <td style="text-align: center;">Tie</td>
                    </tr>
                    <tr>
                        <td>API Key Rotation</td>
                        <td>Instant</td>
                        <td>Instant</td>
                        <td style="text-align: center;">Tie</td>
                    </tr>
                    <tr>
                        <td>Support</td>
                        <td>Email + Chat</td>
                        <td>Email only</td>
                        <td style="text-align: center;" class="comp-table-winner">
                            <span class="comp-winner-badge" aria-label="Winner in this category">LLM Resayil</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- COST BREAKDOWN -->
    <section class="comp-cost-section">
        <h2 class="comp-section-title">Real Cost Comparison</h2>
        <div class="comp-cost-grid">
            <!-- Case 1: 10M tokens -->
            <div class="comp-cost-card">
                <h3 class="comp-cost-title">Startup</h3>
                <p class="comp-cost-subtitle">10M tokens/month</p>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">LLM Resayil</span>
                    <span class="comp-cost-value comp-cost-resayil">$15</span>
                </div>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">OpenRouter</span>
                    <span class="comp-cost-value comp-cost-openrouter">$45</span>
                </div>

                <div class="comp-savings">
                    <p class="comp-savings-label">You Save</p>
                    <p class="comp-savings-amount">$30</p>
                </div>
            </div>

            <!-- Case 2: 100M tokens -->
            <div class="comp-cost-card">
                <h3 class="comp-cost-title">Scale-up</h3>
                <p class="comp-cost-subtitle">100M tokens/month</p>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">LLM Resayil</span>
                    <span class="comp-cost-value comp-cost-resayil">$120</span>
                </div>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">OpenRouter</span>
                    <span class="comp-cost-value comp-cost-openrouter">$380</span>
                </div>

                <div class="comp-savings">
                    <p class="comp-savings-label">You Save</p>
                    <p class="comp-savings-amount">$260</p>
                </div>
            </div>

            <!-- Case 3: 1B tokens -->
            <div class="comp-cost-card">
                <h3 class="comp-cost-title">Enterprise</h3>
                <p class="comp-cost-subtitle">1B tokens/month</p>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">LLM Resayil</span>
                    <span class="comp-cost-value comp-cost-resayil">$950</span>
                </div>

                <div class="comp-cost-row">
                    <span class="comp-cost-label">OpenRouter</span>
                    <span class="comp-cost-value comp-cost-openrouter">$3,200</span>
                </div>

                <div class="comp-savings">
                    <p class="comp-savings-label">You Save</p>
                    <p class="comp-savings-amount">$2,250</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CLUSTER 1: COST/ROI — Compare pricing -->
    <div style="text-align: center; padding: 2rem; background: rgba(212,175,55,0.05); border-radius: 12px; margin: 3rem 0;">
        <p style="color: var(--comp-text-secondary); margin-bottom: 1rem;">Want to calculate your exact savings? Check out our <a href="/cost-calculator" style="color: var(--comp-gold); font-weight: 600; text-decoration: none;">interactive cost calculator</a> or compare with <a href="/alternatives" style="color: var(--comp-gold); font-weight: 600; text-decoration: none;">other LLM APIs side-by-side</a>.</p>
    </div>

    <!-- FEATURE MATRIX -->
    <section class="comp-features-section">
        <h2 class="comp-section-title">Feature Matrix</h2>
        <div class="comp-features-grid">
            <!-- LLM Resayil Features -->
            <div class="comp-feature-column">
                <h3 class="comp-feature-column-title resayil">LLM Resayil</h3>
                <div class="comp-feature-list">
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Lightweight & Fast (OpenAI-compatible)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Pay-per-token pricing (no monthly fees)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Multi-currency support (KWD, USD, etc)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Advanced usage analytics & cost breakdown</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Instant API key creation & rotation</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Per-model cost tracking</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Dedicated support + Community Discord</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Team management (Enterprise)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Custom rate limiting</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Zero cold-start cost setup</div>
                    </div>
                </div>
            </div>

            <!-- OpenRouter Features -->
            <div class="comp-feature-column">
                <h3 class="comp-feature-column-title">OpenRouter</h3>
                <div class="comp-feature-list">
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Large model library (100+)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">OpenAI-compatible API</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Router (auto-fallback between models)</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon yes">✓</div>
                        <div class="comp-feature-text">Moderation & filter controls</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Multi-currency support</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Real-time usage dashboard</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Priority support included</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Team management at free tier</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Custom rate limiting</div>
                    </div>
                    <div class="comp-feature-item">
                        <div class="comp-feature-icon no">✗</div>
                        <div class="comp-feature-text">Cost calculator built-in</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="comp-faq-section">
        <h2 class="comp-section-title">Frequently Asked Questions</h2>
        <div class="comp-faq-list">
            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>Why is LLM Resayil cheaper than OpenRouter?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    LLM Resayil uses a hybrid approach with local GPU servers and cloud fallback, dramatically reducing infrastructure costs. We pass these savings directly to users through transparent pay-per-token pricing with no platform markup. OpenRouter maintains higher pricing to fund their router infrastructure and broader model catalog.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>Is LLM Resayil fully OpenAI compatible?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    100% yes. Our API is fully compatible with the OpenAI SDK and REST API specification. Simply swap your endpoint and API key. All standard features work: streaming, function calling, vision models, embeddings, and more. No code changes required.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>What if I need a specific model not on LLM Resayil?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    Our 45+ models cover 95% of use cases (Claude 3, GPT-4, Mixtral, Llama 3, etc). If you need a model we don't offer, we can add it within 48 hours. Contact our support team with your request, and we'll evaluate and deploy it as a priority.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>How fast is LLM Resayil compared to OpenRouter?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    LLM Resayil averages 450ms latency (p50) vs OpenRouter's ~600ms. For streaming use cases, this translates to significantly faster token delivery. Our local GPU servers eliminate network hops, while OpenRouter routes through multiple provider aggregators.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>Can I use my existing OpenRouter code with LLM Resayil?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    Yes, minimal changes needed. Update your base URL to https://api.llm.resayil.io/v1 and use your LLM Resayil API key. Most code will work without modification due to our 100% OpenAI compatibility. Check our docs for any model name differences.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>What happens after I use my 1,000 free credits?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    You can top up your account anytime via our billing page. We accept credit cards, debit cards, and WhatsApp payments. No contracts, no hidden fees. Start with just 2 KWD (~$6) if you want to test further. Your free credits never expire.
                </div>
            </div>

            <div class="comp-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="comp-faq-question">
                    <span>Is my API usage data private?</span>
                    <span class="comp-faq-toggle">+</span>
                </div>
                <div class="comp-faq-answer">
                    Absolutely. We do not train on, log, or share your API requests. We only track token usage for billing purposes. All data is encrypted in transit and at rest. See our Privacy Policy for full details and our SOC 2 compliance roadmap.
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER CTA — CLUSTER 2: INTEGRATION -->
    <section class="comp-footer-cta">
        <p class="comp-footer-text">Save 30% today. Start building with LLM Resayil.</p>
        <div class="comp-footer-buttons">
            <a href="{{ route('register') }}" class="btn-primary">Create Free Account</a>
            <a href="{{ route('docs') }}" class="btn-secondary">Read API Docs</a>
        </div>
        <p style="color: var(--comp-text-secondary); margin-top: 1.5rem; font-size: 0.9rem;">Explore our <a href="/features" style="color: var(--comp-gold); text-decoration: underline;">available models</a> or view our <a href="/billing/plans" style="color: var(--comp-gold); text-decoration: underline;">subscription plans</a>.</p>
    </section>
</main>

<!-- FAQPage Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Why is LLM Resayil cheaper than OpenRouter?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "LLM Resayil uses a hybrid approach with local GPU servers and cloud fallback, dramatically reducing infrastructure costs. We pass these savings directly to users through transparent pay-per-token pricing with no platform markup. OpenRouter maintains higher pricing to fund their router infrastructure and broader model catalog."
      }
    },
    {
      "@type": "Question",
      "name": "Is LLM Resayil fully OpenAI compatible?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "100% yes. Our API is fully compatible with the OpenAI SDK and REST API specification. Simply swap your endpoint and API key. All standard features work: streaming, function calling, vision models, embeddings, and more. No code changes required."
      }
    },
    {
      "@type": "Question",
      "name": "What if I need a specific model not on LLM Resayil?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Our 45+ models cover 95% of use cases (Claude 3, GPT-4, Mixtral, Llama 3, etc). If you need a model we don't offer, we can add it within 48 hours. Contact our support team with your request, and we'll evaluate and deploy it as a priority."
      }
    },
    {
      "@type": "Question",
      "name": "How fast is LLM Resayil compared to OpenRouter?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "LLM Resayil averages 450ms latency (p50) vs OpenRouter's ~600ms. For streaming use cases, this translates to significantly faster token delivery. Our local GPU servers eliminate network hops, while OpenRouter routes through multiple provider aggregators."
      }
    },
    {
      "@type": "Question",
      "name": "Can I use my existing OpenRouter code with LLM Resayil?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, minimal changes needed. Update your base URL to https://api.llm.resayil.io/v1 and use your LLM Resayil API key. Most code will work without modification due to our 100% OpenAI compatibility. Check our docs for any model name differences."
      }
    },
    {
      "@type": "Question",
      "name": "What happens after I use my 1,000 free credits?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can top up your account anytime via our billing page. We accept credit cards, debit cards, and WhatsApp payments. No contracts, no hidden fees. Start with just 2 KWD (~$6) if you want to test further. Your free credits never expire."
      }
    },
    {
      "@type": "Question",
      "name": "Is my API usage data private?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Absolutely. We do not train on, log, or share your API requests. We only track token usage for billing purposes. All data is encrypted in transit and at rest. See our Privacy Policy for full details and our SOC 2 compliance roadmap."
      }
    }
  ]
}
</script>

<script>
    // Smooth scroll for CTA button
    document.addEventListener('DOMContentLoaded', function() {
        const compareBtn = document.querySelector('button[onclick*="scrollIntoView"]');
        if (compareBtn) {
            compareBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.comp-table-section').scrollIntoView({ behavior: 'smooth' });
            });
        }

        // FAQ keyboard navigation
        const faqItems = document.querySelectorAll('.comp-faq-item[role="button"]');
        faqItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
                const isExpanded = this.classList.contains('active');
                this.setAttribute('aria-expanded', isExpanded);
            });

            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.classList.toggle('active');
                    const isExpanded = this.classList.contains('active');
                    this.setAttribute('aria-expanded', isExpanded);
                }
            });
        });
    });
</script>

@endsection
