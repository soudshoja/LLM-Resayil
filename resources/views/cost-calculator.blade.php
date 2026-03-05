@extends('layouts.app')

@section('title', 'LLM Cost Calculator')

@push('styles')
<style>
    /* ── CSS Custom Properties (Accessibility) ── */
    :root {
        --text-muted: #8a92a0; /* Updated from #6b7280 for WCAG AA 4.5:1 contrast on #0f1115 */
    }

    /* ── Main Layout ── */
    main { padding: 0; max-width: 100%; margin: 0; }

    /* ── Hero Section ── */
    .calc-hero {
        background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%);
        border-bottom: 1px solid var(--border);
        padding: 4rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
    }
    .calc-hero h1 {
        font-size: clamp(1.75rem, 5vw, 2.5rem);
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        letter-spacing: -0.01em;
    }
    .calc-hero .hero-accent {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .calc-hero p {
        font-size: 1.05rem;
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── Main Container ── */
    .calc-wrapper {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 2rem 3rem;
    }

    /* ── Calculator Grid ── */
    .calc-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    /* ── Left Column: Inputs ── */
    .calc-inputs {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }

    .calc-inputs-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 2rem;
    }

    .calc-inputs-card h2 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ── Form Groups ── */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
    }

    .form-label-value {
        color: var(--gold);
        font-weight: 600;
    }

    /* ── Slider Input ── */
    .slider-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .slider-input {
        width: 100%;
        height: 6px;
        border-radius: 4px;
        background: linear-gradient(to right, var(--bg-primary), var(--gold), var(--gold-light));
        outline: none;
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
    }

    .slider-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--gold);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(212,175,55,0.4);
        transition: all 0.2s;
        border: 2px solid rgba(255,255,255,0.2);
    }

    .slider-input::-webkit-slider-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(212,175,55,0.6);
    }

    .slider-input::-webkit-slider-thumb:focus {
        outline: 2px solid var(--gold);
        outline-offset: 2px;
    }

    .slider-input::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--gold);
        cursor: pointer;
        border: 2px solid rgba(255,255,255,0.2);
        box-shadow: 0 2px 8px rgba(212,175,55,0.4);
        transition: all 0.2s;
    }

    .slider-input::-moz-range-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(212,175,55,0.6);
    }

    .slider-input:focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 2px;
    }

    .slider-display {
        font-size: 0.95rem;
        color: var(--text-secondary);
        text-align: center;
        padding: 0.6rem;
        background: var(--bg-primary);
        border-radius: 6px;
        font-family: 'Courier New', monospace;
    }

    .slider-hint {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-align: center;
        margin-top: 0.5rem;
        font-style: italic;
    }

    /* ── Select Input ── */
    .form-input {
        width: 100%;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s;
    }

    .form-input:hover {
        border-color: var(--gold-muted);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(212,175,55,0.1);
    }

    /* ── Calculate Button ── */
    .btn-calculate {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: #0a0d14;
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212,175,55,0.3);
    }

    .btn-calculate:active {
        transform: translateY(0);
    }

    /* ── Right Column: Results ── */
    .calc-results {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .results-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 2rem;
    }

    .results-card h2 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    /* ── Result Items ── */
    .result-item {
        padding: 1.25rem;
        background: var(--bg-primary);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 10px;
        transition: all 0.2s;
    }

    .result-item:hover {
        border-color: var(--gold-muted);
        background: rgba(212,175,55,0.03);
    }

    .result-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .result-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--gold);
        font-family: 'Courier New', monospace;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Savings Badge ── */
    .savings-badge {
        background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.1));
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        text-align: center;
    }

    .savings-badge h3 {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .savings-percentage {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gold);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    /* ── Comparison Details ── */
    .comparison-section {
        border-top: 1px solid var(--border);
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }

    .comparison-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .comparison-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem;
        background: var(--bg-primary);
        border-radius: 8px;
        margin-bottom: 0.6rem;
        font-size: 0.9rem;
    }

    .comparison-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .comparison-value {
        color: var(--gold);
        font-weight: 600;
        font-family: 'Courier New', monospace;
    }

    /* ── Info Section ── */
    .info-section {
        background: rgba(212,175,55,0.05);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 3rem;
    }

    .info-section h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-section p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .info-section p:last-child {
        margin-bottom: 0;
    }

    /* ── FAQ Section ── */
    .faq-section {
        margin-bottom: 4rem;
    }

    .faq-section h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2rem;
        text-align: center;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .faq-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s;
        outline: none;
    }

    .faq-item:hover {
        border-color: var(--gold-muted);
        transform: translateY(-2px);
    }

    .faq-item:focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 2px;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
    }

    .faq-question {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .faq-icon {
        width: 20px;
        height: 20px;
        color: var(--gold);
        transition: transform 0.3s;
    }

    .faq-item.open .faq-icon {
        transform: rotate(180deg);
    }

    .faq-answer {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        display: none;
        animation: slideDown 0.3s ease-out;
    }

    .faq-item.open .faq-answer {
        display: block;
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

    /* ── CTA Section ── */
    .cta-section {
        background: linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.05));
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 14px;
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 4rem;
    }

    .cta-section h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .cta-section p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 1.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary, .btn-secondary {
        padding: 0.9rem 1.8rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: #0a0d14;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212,175,55,0.3);
    }

    .btn-secondary {
        background: transparent;
        color: var(--gold);
        border: 1px solid var(--gold-muted);
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
    }

    /* ── Trust Signals ── */
    .trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(5,150,105,0.15);
        border: 1px solid rgba(5,150,105,0.3);
        color: #6ee7b7;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        justify-content: center;
    }

    /* ── Responsive Design ── */
    @media(max-width: 1024px) {
        .calc-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .calc-hero {
            padding: 3rem 2rem;
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 768px) {
        main { padding: 0; }

        .calc-hero {
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
        }

        .calc-hero h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .calc-hero p {
            font-size: 0.95rem;
        }

        .calc-wrapper {
            padding: 0 1.5rem 2rem;
        }

        .calc-inputs-card, .results-card {
            padding: 1.5rem;
        }

        .result-value {
            font-size: 1.8rem;
        }

        .savings-percentage {
            font-size: 2rem;
        }

        .form-input, .slider-display {
            font-size: 16px; /* Prevent iOS zoom */
        }

        .btn-calculate {
            padding: 0.9rem;
            font-size: 0.95rem;
            min-height: 44px;
        }

        .comparison-item {
            font-size: 0.85rem;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            min-height: 44px;
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }

        /* Mobile slider thumb — increase to 26px for touch accessibility */
        .slider-input::-webkit-slider-thumb {
            width: 26px;
            height: 26px;
        }

        .slider-input::-moz-range-thumb {
            width: 26px;
            height: 26px;
        }
    }

    @media(max-width: 480px) {
        .calc-hero h1 {
            font-size: 1.25rem;
        }

        .result-value {
            font-size: 1.5rem;
        }

        .savings-percentage {
            font-size: 1.75rem;
        }

        .info-section, .savings-badge {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="calc-hero">
    <h1>LLM <span class="hero-accent">Cost Calculator</span></h1>
    <p>See how much you'll save with LLM Resayil. Compare pricing across OpenAI, OpenRouter, and our platform in real-time.</p>
</div>

<div class="calc-wrapper">
    <!-- Main Calculator -->
    <div class="calc-grid">
        <!-- Left Column: Inputs -->
        <div class="calc-inputs">
            <div class="calc-inputs-card">
                <h2>Input Your Usage</h2>

                <div class="form-group">
                    <label class="form-label" for="tokens-slider">
                        <span>Monthly Token Usage</span>
                        <span class="form-label-value" id="tokens-display">1M</span>
                    </label>
                    <div class="slider-wrapper">
                        <input
                            type="range"
                            id="tokens-slider"
                            class="slider-input"
                            min="1000000"
                            max="10000000000"
                            step="1000000"
                            value="1000000"
                            aria-label="Monthly token usage slider"
                            aria-describedby="slider-help"
                            aria-valuemin="1000000"
                            aria-valuemax="10000000000"
                            aria-valuenow="1000000"
                            aria-valuetext="1M tokens per month"
                        >
                        <div class="slider-display" id="slider-display">1,000,000 tokens/month</div>
                        <div id="slider-help" class="slider-hint">Drag to adjust usage from 1M to 10B tokens</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tokens-input">Or enter directly:</label>
                    <input
                        type="number"
                        id="tokens-input"
                        class="form-input"
                        min="1000000"
                        max="10000000000"
                        step="1000000"
                        value="1000000"
                        placeholder="Enter token count"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">Model Tier</label>
                    <select class="form-input" id="model-tier">
                        <option value="small">Small (e.g., Mistral 7B)</option>
                        <option value="medium" selected>Medium (e.g., Llama 70B)</option>
                        <option value="large">Large (e.g., GPT-4 Equivalent)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Workload Type</label>
                    <select class="form-input" id="workload-type">
                        <option value="production" selected>Production</option>
                        <option value="development">Development</option>
                        <option value="batch">Batch Processing</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Right Column: Results -->
        <div class="calc-results" aria-live="polite" aria-label="Cost comparison results" role="status">
            <div class="results-card">
                <h2>Cost Comparison</h2>

                <div class="result-item">
                    <div class="result-label">LLM Resayil</div>
                    <div class="result-value" id="result-llm" role="status" aria-valuenow="0" aria-label="LLM Resayil cost">$0.00</div>
                </div>

                <div class="result-item">
                    <div class="result-label">vs OpenAI</div>
                    <div class="result-value" id="result-openai" role="status" aria-valuenow="0" aria-label="OpenAI cost">$0.00</div>
                </div>

                <div class="result-item">
                    <div class="result-label">vs OpenRouter</div>
                    <div class="result-value" id="result-openrouter" role="status" aria-valuenow="0" aria-label="OpenRouter cost">$0.00</div>
                </div>

                <div class="savings-badge">
                    <h3>Total Monthly Savings vs OpenAI</h3>
                    <div class="savings-percentage" id="savings-amount" role="status" aria-valuenow="0" aria-label="Total savings">$0.00</div>
                </div>

                <div class="comparison-section">
                    <h3>Percentage Savings</h3>
                    <div class="comparison-item">
                        <span class="comparison-label">vs OpenAI</span>
                        <span class="comparison-value" id="savings-percent-openai" role="status" aria-valuenow="0" aria-label="Percentage savings vs OpenAI">0%</span>
                    </div>
                    <div class="comparison-item">
                        <span class="comparison-label">vs OpenRouter</span>
                        <span class="comparison-value" id="savings-percent-router" role="status" aria-valuenow="0" aria-label="Percentage savings vs OpenRouter">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How We Calculate Section — CLUSTER 1: COST/ROI -->
    <div class="info-section">
        <h3>How We Calculate Your Costs</h3>
        <p><strong>Pricing rates used:</strong></p>
        <p>
            LLM Resayil: $0.001 per 1K tokens •
            OpenAI: $0.015 per 1K tokens •
            OpenRouter: $0.008 per 1K tokens
        </p>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 1rem;">
            Calculations are based on current market rates and are updated regularly. Actual costs may vary depending on specific model selection, additional features, and volume discounts. All calculations assume standard model pricing without special agreements.
        </p>
        <p style="color: var(--text-secondary); margin-top: 1.5rem; font-size: 0.9rem;">
            See a <a href="/comparison" style="color: var(--gold); font-weight: 600; text-decoration: underline;">detailed comparison with OpenRouter</a>, or explore <a href="/alternatives" style="color: var(--gold); font-weight: 600; text-decoration: underline;">alternative LLM APIs</a>.
        </p>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item" data-faq="accuracy" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>How accurate is this calculator?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    Our calculator uses current market pricing rates and is updated regularly. Results are accurate for estimation purposes. For production environments with volume discounts or custom agreements, please contact our sales team for a personalized quote.
                </div>
            </div>

            <div class="faq-item" data-faq="cheaper" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>Why is LLM Resayil cheaper?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    We optimize infrastructure costs and pass savings to users. Our pay-per-token model eliminates monthly minimums. No hidden fees or overages. Plus, access to open-source models with commercial licenses removes vendor lock-in premiums charged by competitors.
                </div>
            </div>

            <div class="faq-item" data-faq="production" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>Can I use this for production estimates?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    Yes, this calculator is designed for production cost estimates. All pricing is based on current published rates. For guaranteed pricing, SLAs, or enterprise agreements, contact our sales team at support@resayil.io with your usage profile.
                </div>
            </div>

            <div class="faq-item" data-faq="models" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>Do pricing tiers affect the calculation?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    Model tier selection affects pricing rates. Larger models (like GPT-4 equivalents) are more expensive per token than smaller models (like Mistral 7B). The calculator uses representative pricing for each tier. See our detailed pricing page for model-specific rates.
                </div>
            </div>

            <div class="faq-item" data-faq="discount" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>Are there volume discounts?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    Yes! Enterprise customers with high monthly volumes (>100B tokens) qualify for volume discounts. Contact our sales team to discuss your specific use case and get a custom pricing proposal tailored to your needs.
                </div>
            </div>

            <div class="faq-item" data-faq="change" role="button" tabindex="0" aria-expanded="false">
                <div class="faq-question">
                    <span>How often do prices change?</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
                <div class="faq-answer">
                    We update pricing quarterly to reflect market conditions. Existing users are grandfathered into their current rates for 12 months. Price increases (if any) are announced 30 days in advance via email and dashboard notifications.
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section — CLUSTER 1: COST/ROI -->
    <div class="cta-section">
        <h2>Ready to Start Saving?</h2>
        <p>Join thousands of developers who've switched to LLM Resayil and cut their API costs significantly.</p>
        <div class="cta-buttons">
            <a href="/register" class="btn-primary">Start Free with 1,000 Credits</a>
            <a href="/billing/plans" class="btn-secondary">View Pricing Plans</a>
        </div>
        <p style="color: var(--text-secondary); margin-top: 1.5rem; font-size: 0.9rem;">
            Check our <a href="/pricing" style="color: var(--gold); text-decoration: underline;">detailed pricing</a>, or see how we <a href="/comparison" style="color: var(--gold); text-decoration: underline;">compare to competitors</a>.
        </p>
    </div>
</div>

<!-- FAQPage Schema for SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "How accurate is this calculator?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Our calculator uses current market pricing rates and is updated regularly. Results are accurate for estimation purposes. For production environments with volume discounts or custom agreements, please contact our sales team for a personalized quote."
            }
        },
        {
            "@type": "Question",
            "name": "Why is LLM Resayil cheaper?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "We optimize infrastructure costs and pass savings to users. Our pay-per-token model eliminates monthly minimums. No hidden fees or overages. Plus, access to open-source models with commercial licenses removes vendor lock-in premiums charged by competitors."
            }
        },
        {
            "@type": "Question",
            "name": "Can I use this for production estimates?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes, this calculator is designed for production cost estimates. All pricing is based on current published rates. For guaranteed pricing, SLAs, or enterprise agreements, contact our sales team at support@resayil.io with your usage profile."
            }
        },
        {
            "@type": "Question",
            "name": "Do pricing tiers affect the calculation?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Model tier selection affects pricing rates. Larger models (like GPT-4 equivalents) are more expensive per token than smaller models (like Mistral 7B). The calculator uses representative pricing for each tier."
            }
        },
        {
            "@type": "Question",
            "name": "Are there volume discounts?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes! Enterprise customers with high monthly volumes (>100B tokens) qualify for volume discounts. Contact our sales team to discuss your specific use case and get a custom pricing proposal tailored to your needs."
            }
        },
        {
            "@type": "Question",
            "name": "How often do prices change?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "We update pricing quarterly to reflect market conditions. Existing users are grandfathered into their current rates for 12 months. Price increases (if any) are announced 30 days in advance via email and dashboard notifications."
            }
        }
    ]
}
</script>

@push('scripts')
<script>
    // Pricing constants (in dollars per 1K tokens)
    const PRICING = {
        llmResayil: {
            small: 0.0005,
            medium: 0.001,
            large: 0.0015
        },
        openAI: {
            small: 0.015,
            medium: 0.015,
            large: 0.03
        },
        openRouter: {
            small: 0.005,
            medium: 0.008,
            large: 0.015
        }
    };

    const elements = {
        slider: document.getElementById('tokens-slider'),
        tokensInput: document.getElementById('tokens-input'),
        tokensDisplay: document.getElementById('tokens-display'),
        sliderDisplay: document.getElementById('slider-display'),
        modelTier: document.getElementById('model-tier'),
        workloadType: document.getElementById('workload-type'),
        resultLLM: document.getElementById('result-llm'),
        resultOpenAI: document.getElementById('result-openai'),
        resultOpenRouter: document.getElementById('result-openrouter'),
        savingsAmount: document.getElementById('savings-amount'),
        savingsPercent: document.getElementById('savings-percent-openai'),
        savingsPercentRouter: document.getElementById('savings-percent-router')
    };

    function formatNumber(num) {
        if (num >= 1000000000) return (num / 1000000000).toFixed(1) + 'B';
        if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
        if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
        return num.toString();
    }

    function formatCurrency(num) {
        return '$' + num.toFixed(2);
    }

    // Update slider display and sync with number input
    elements.slider.addEventListener('input', function() {
        const value = parseInt(this.value);
        elements.tokensInput.value = value;
        elements.tokensDisplay.textContent = formatNumber(value);
        elements.sliderDisplay.textContent = value.toLocaleString() + ' tokens/month';
        // Update ARIA attributes
        this.setAttribute('aria-valuenow', value);
        this.setAttribute('aria-valuetext', formatNumber(value) + ' tokens per month');
        calculateCosts();
    });

    // Sync number input with slider
    elements.tokensInput.addEventListener('input', function() {
        let value = parseInt(this.value) || 0;
        // Enforce min/max constraints
        value = Math.max(1000000, Math.min(10000000000, value));
        this.value = value;
        elements.slider.value = value;
        elements.tokensDisplay.textContent = formatNumber(value);
        elements.sliderDisplay.textContent = value.toLocaleString() + ' tokens/month';
        // Update ARIA attributes
        elements.slider.setAttribute('aria-valuenow', value);
        elements.slider.setAttribute('aria-valuetext', formatNumber(value) + ' tokens per month');
        calculateCosts();
    });

    // Update on dropdown change
    elements.modelTier.addEventListener('change', calculateCosts);
    elements.workloadType.addEventListener('change', calculateCosts);

    // FAQ toggle with keyboard support
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', function() {
            toggleFAQ(this);
        });

        // Keyboard navigation: Enter and Space
        item.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleFAQ(this);
            }
        });
    });

    function toggleFAQ(item) {
        item.classList.toggle('open');
        const isOpen = item.classList.contains('open');
        item.setAttribute('aria-expanded', isOpen);
    }

    // Update cost display with aria-valuenow
    function calculateCosts() {
        const tokens = parseInt(elements.slider.value);
        const tier = elements.modelTier.value;

        // Get pricing for this tier
        const llmPrice = PRICING.llmResayil[tier];
        const openaiPrice = PRICING.openAI[tier];
        const routerPrice = PRICING.openRouter[tier];

        // Calculate monthly costs
        const llmCost = (tokens / 1000) * llmPrice;
        const openaiCost = (tokens / 1000) * openaiPrice;
        const routerCost = (tokens / 1000) * routerPrice;

        // Calculate savings
        const savingsVsOpenAI = openaiCost - llmCost;
        const savingsVsRouter = routerCost - llmCost;
        const savingsPercentOpenAI = openaiCost > 0 ? ((savingsVsOpenAI / openaiCost) * 100).toFixed(1) : 0;
        const savingsPercentRouter = routerCost > 0 ? ((savingsVsRouter / routerCost) * 100).toFixed(1) : 0;

        // Update DOM
        elements.resultLLM.textContent = formatCurrency(llmCost);
        elements.resultLLM.setAttribute('aria-valuenow', llmCost.toFixed(2));
        elements.resultOpenAI.textContent = formatCurrency(openaiCost);
        elements.resultOpenAI.setAttribute('aria-valuenow', openaiCost.toFixed(2));
        elements.resultOpenRouter.textContent = formatCurrency(routerCost);
        elements.resultOpenRouter.setAttribute('aria-valuenow', routerCost.toFixed(2));
        elements.savingsAmount.textContent = formatCurrency(savingsVsOpenAI);
        elements.savingsAmount.setAttribute('aria-valuenow', savingsVsOpenAI.toFixed(2));
        elements.savingsPercent.textContent = savingsPercentOpenAI + '%';
        elements.savingsPercent.setAttribute('aria-valuenow', savingsPercentOpenAI);
        elements.savingsPercentRouter.textContent = savingsPercentRouter + '%';
        elements.savingsPercentRouter.setAttribute('aria-valuenow', savingsPercentRouter);
    }

    // Initial calculation
    calculateCosts();
</script>
@endpush

@endsection
