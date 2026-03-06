@extends('layouts.app')

@section('title', 'OpenAI Alternatives — LLM Resayil')

@push('styles')
<link href="{{ asset('css/alternatives.css') }}" rel="stylesheet">
@endpush

@section('content')
<main>

    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="hero-section" aria-labelledby="hero-headline">
        <div class="hero-eyebrow" aria-label="2025 LLM API Comparison Guide">
            <span class="hero-eyebrow-dot" aria-hidden="true"></span>
            2025 LLM API Comparison Guide
        </div>

        <h1 class="hero-headline" id="hero-headline">
            Top 5 <span class="highlight">OpenAI Alternatives</span>
        </h1>

        <p class="hero-subheadline">
            Cost, speed, models, and features compared. Find the best LLM API for your team — and discover why LLM Resayil is up to 10x cheaper.
        </p>

        <div class="hero-cta">
            <a href="#comparison-matrix" class="cta-btn primary" aria-label="Jump to feature comparison matrix">
                Compare Now
            </a>
            <a href="{{ route('register') }}" class="cta-btn secondary" aria-label="Create a free LLM Resayil account">
                Start Free
            </a>
        </div>

        <div class="hero-stats" role="list" aria-label="Key statistics">
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">45+</span>
                <span class="hero-stat-label">Models</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">$0.0001</span>
                <span class="hero-stat-label">Per 1K tokens</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">100%</span>
                <span class="hero-stat-label">OpenAI Compatible</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">&lt;5 min</span>
                <span class="hero-stat-label">Setup time</span>
            </div>
        </div>
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         COMPARISON MATRIX
    ═══════════════════════════════════════════════════════════ --}}
    <section class="matrix-section" id="comparison-matrix" aria-labelledby="matrix-title">
        <h2 class="section-title" id="matrix-title">Feature Comparison Matrix</h2>
        <p class="section-description">
            Head-to-head breakdown of the 5 most popular LLM API alternatives. Scroll horizontally on mobile or tap any provider for details.
        </p>

        {{-- Desktop Table --}}
        <div style="overflow-x: auto;" role="region" aria-label="Feature comparison table — scroll horizontally on mobile">
            <table class="comparison-table" aria-describedby="matrix-title">
                <thead>
                    <tr>
                        <th scope="col" style="width: 18%;">Feature</th>
                        <th scope="col" class="resayil" style="width: 16.5%;">LLM Resayil</th>
                        <th scope="col" style="width: 16.5%;">OpenRouter</th>
                        <th scope="col" style="width: 16.5%;">Claude API</th>
                        <th scope="col" style="width: 16.5%;">Ollama</th>
                        <th scope="col" style="width: 16%;">Together AI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="header-cell" scope="row">Pricing (/1K tokens)</td>
                        <td class="resayil">From $0.0001</td>
                        <td>$0.0008–$0.02</td>
                        <td>$0.003–$0.03</td>
                        <td>Free (local)</td>
                        <td>$0.0005–$0.01</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Model Availability</td>
                        <td class="resayil">45+ models</td>
                        <td>100+ routed</td>
                        <td>Claude 3.5 only</td>
                        <td>100s (community)</td>
                        <td>50+ open models</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">OpenAI Compatible?</td>
                        <td class="resayil">
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-cross" role="img" aria-label="No">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                    <path d="M2 2L9 9M9 2L2 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Latency (p50)</td>
                        <td class="resayil">1–3s</td>
                        <td>1–5s</td>
                        <td>1–4s</td>
                        <td>&lt;500ms (local)</td>
                        <td>500ms–2s</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Support Quality</td>
                        <td class="resayil">Email + Discord</td>
                        <td>Community-driven</td>
                        <td>Tier-based</td>
                        <td>Community</td>
                        <td>Community</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Best Use Case</td>
                        <td class="resayil">Price-sensitive teams</td>
                        <td>Model flexibility</td>
                        <td>Quality/instruction</td>
                        <td>Offline/privacy</td>
                        <td>Speed + fine-tuning</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Setup Time</td>
                        <td class="resayil">&lt;5 min</td>
                        <td>&lt;5 min</td>
                        <td>&lt;5 min</td>
                        <td>30 min–2h</td>
                        <td>&lt;5 min</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">Data Privacy / OSS</td>
                        <td class="resayil">
                            <span class="icon-partial" role="img" aria-label="Partial — secure and encrypted">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                    <circle cx="6" cy="6" r="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="M6 4V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <circle cx="6" cy="8.5" r="0.75" fill="currentColor"/>
                                </svg>
                            </span>
                            <span style="margin-left: 0.4rem; font-size: 0.85rem;">Secure, encrypted</span>
                        </td>
                        <td>Closed</td>
                        <td>Closed</td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes — open source">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span style="margin-left: 0.4rem; font-size: 0.85rem;">Open-source</span>
                        </td>
                        <td>Open models</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Mobile Accordion --}}
        <div class="comparison-accordion" role="list" aria-label="Provider comparison — expanded cards">

            {{-- LLM Resayil --}}
            <div class="accordion-item open" role="listitem">
                <button class="accordion-header resayil"
                        aria-expanded="true"
                        aria-controls="accordion-content-resayil"
                        id="accordion-btn-resayil">
                    <span>LLM Resayil</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-resayil" role="region" aria-labelledby="accordion-btn-resayil">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">Pricing (/1K tokens)</span>
                            <span class="accordion-value">From $0.0001</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Models</span>
                            <span class="accordion-value">45+</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">OpenAI Compatible</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Latency (p50)</span>
                            <span class="accordion-value">1–3s</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Best For</span>
                            <span class="accordion-value">Price-sensitive teams</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Setup Time</span>
                            <span class="accordion-value">&lt;5 min</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- OpenRouter --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-openrouter"
                        id="accordion-btn-openrouter">
                    <span>OpenRouter</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-openrouter" role="region" aria-labelledby="accordion-btn-openrouter">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">Pricing (/1K tokens)</span>
                            <span class="accordion-value">$0.0008–$0.02</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Models</span>
                            <span class="accordion-value">100+ routed</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">OpenAI Compatible</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Latency (p50)</span>
                            <span class="accordion-value">1–5s</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Best For</span>
                            <span class="accordion-value">Model flexibility</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Setup Time</span>
                            <span class="accordion-value">&lt;5 min</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Claude API --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-claude"
                        id="accordion-btn-claude">
                    <span>Claude API</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-claude" role="region" aria-labelledby="accordion-btn-claude">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">Pricing (/1K tokens)</span>
                            <span class="accordion-value">$0.003–$0.03</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Models</span>
                            <span class="accordion-value">Claude 3.5 only</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">OpenAI Compatible</span>
                            <span class="accordion-value" aria-label="No">
                                <span class="icon-cross" role="img" aria-hidden="true">
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                        <path d="M2 2L9 9M9 2L2 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Latency (p50)</span>
                            <span class="accordion-value">1–4s</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Best For</span>
                            <span class="accordion-value">Quality/instruction</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Setup Time</span>
                            <span class="accordion-value">&lt;5 min</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ollama --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-ollama"
                        id="accordion-btn-ollama">
                    <span>Ollama</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-ollama" role="region" aria-labelledby="accordion-btn-ollama">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">Pricing (/1K tokens)</span>
                            <span class="accordion-value">Free (local)</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Models</span>
                            <span class="accordion-value">100s available</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">OpenAI Compatible</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Latency (p50)</span>
                            <span class="accordion-value">&lt;500ms</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Best For</span>
                            <span class="accordion-value">Offline/privacy</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Setup Time</span>
                            <span class="accordion-value">30 min–2h</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Together AI --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-together"
                        id="accordion-btn-together">
                    <span>Together AI</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-together" role="region" aria-labelledby="accordion-btn-together">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">Pricing (/1K tokens)</span>
                            <span class="accordion-value">$0.0005–$0.01</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Models</span>
                            <span class="accordion-value">50+ open models</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">OpenAI Compatible</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Latency (p50)</span>
                            <span class="accordion-value">500ms–2s</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Best For</span>
                            <span class="accordion-value">Speed + fine-tuning</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">Setup Time</span>
                            <span class="accordion-value">&lt;5 min</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /.comparison-accordion --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         DEEP DIVE CARDS
    ═══════════════════════════════════════════════════════════ --}}
    <section class="deep-dive-section" aria-labelledby="deep-dive-title">
        <h2 class="section-title" id="deep-dive-title">Deep Dive: Each Alternative</h2>
        <p class="section-description">
            Not all LLM APIs are equal. Here's what each one does best — and where LLM Resayil outperforms them.
        </p>

        <div class="deep-dive-grid">

            {{-- LLM Resayil — featured, spans full row --}}
            <article class="deep-dive-card featured fade-up" aria-labelledby="card-title-resayil">
                <div class="card-header-band">
                    <div class="card-avatar avatar-resayil" aria-hidden="true">LR</div>
                    <div class="card-title-group">
                        <h3 id="card-title-resayil">LLM Resayil</h3>
                        <div class="deep-dive-tagline">Best Value</div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">
                        LLM Resayil is the cheapest production-ready LLM API, starting at $0.0001 per 1K tokens. Built for price-sensitive teams, it combines on-server and cloud-routed models into one unified API. With 45+ models and true OpenAI compatibility, you can migrate existing code in minutes.
                    </p>
                    <p class="deep-dive-content">
                        The platform offers 1,000 free credits on signup — no card required — and transparent credit-based billing. Perfect for startups, research labs, and cost-conscious enterprises.
                    </p>
                    <ul class="deep-dive-list" aria-label="LLM Resayil highlights">
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            From $0.0001/1K tokens — 10x cheaper than OpenAI
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            45+ models: Mistral, Llama, DeepSeek, Qwen, and cloud-routed Claude
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            100% OpenAI-compatible REST API — no code changes needed
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            1–3s median latency (faster on local models)
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            No monthly commitments — pay only for what you use
                        </li>
                    </ul>
                </div>
            </article>

            {{-- OpenRouter --}}
            <article class="deep-dive-card fade-up delay-1" aria-labelledby="card-title-openrouter">
                <div class="card-header-band">
                    <div class="card-avatar avatar-openrouter" aria-hidden="true">OR</div>
                    <div class="card-title-group">
                        <h3 id="card-title-openrouter">OpenRouter</h3>
                        <div class="deep-dive-tagline">Maximum Flexibility</div>
                        <span class="vs-badge" aria-label="Compared to LLM Resayil">vs LLM Resayil</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">
                        OpenRouter routes your requests across 100+ LLM providers under one API key. Great for teams that need to experiment with many models or want automatic fallback. Pricing ranges from $0.0008–$0.02 per 1K tokens — 8–200x more than LLM Resayil.
                    </p>
                    <ul class="deep-dive-list" aria-label="OpenRouter highlights">
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            100+ routed models (GPT, Claude, Gemini, Llama, etc.)
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            OpenAI-compatible API with automatic fallback
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Streaming, function calling, vision supported
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            No official support — community-driven only
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Pricing 8–200x higher than LLM Resayil
                        </li>
                    </ul>
                </div>
            </article>

            {{-- Claude API --}}
            <article class="deep-dive-card fade-up delay-2" aria-labelledby="card-title-claude">
                <div class="card-header-band">
                    <div class="card-avatar avatar-claude" aria-hidden="true">CA</div>
                    <div class="card-title-group">
                        <h3 id="card-title-claude">Claude API</h3>
                        <div class="deep-dive-tagline">Best Reasoning & Quality</div>
                        <span class="vs-badge" aria-label="Compared to LLM Resayil">vs LLM Resayil</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">
                        Anthropic's Claude 3.5 (Sonnet, Opus) is the gold standard for reasoning and instruction-following. Not OpenAI-compatible — requires its own SDK. Best when output quality justifies the 30–300x price premium.
                    </p>
                    <ul class="deep-dive-list" aria-label="Claude API highlights">
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Best-in-class reasoning and instruction-following
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Extended 200K context windows
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Tier-based support including enterprise
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            NOT OpenAI-compatible — requires Anthropic SDK
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            $0.003–$0.03/1K tokens — 30–300x pricier
                        </li>
                    </ul>
                </div>
            </article>

            {{-- Ollama --}}
            <article class="deep-dive-card fade-up delay-3" aria-labelledby="card-title-ollama">
                <div class="card-header-band">
                    <div class="card-avatar avatar-ollama" aria-hidden="true">OL</div>
                    <div class="card-title-group">
                        <h3 id="card-title-ollama">Ollama</h3>
                        <div class="deep-dive-tagline">Offline &amp; Private</div>
                        <span class="vs-badge" aria-label="Compared to LLM Resayil">vs LLM Resayil</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">
                        Free, open-source LLM runner for macOS, Linux, and Windows. Zero API costs, zero data transmission, and sub-500ms latency when your GPU is powerful enough. Setup takes 30 min–2h.
                    </p>
                    <ul class="deep-dive-list" aria-label="Ollama highlights">
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Free and open-source (MIT license)
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Run locally — no data leaves your machine
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            OpenAI-compatible server with 100s of models
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Requires GPU — CPU is impractically slow
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            High setup and infrastructure overhead
                        </li>
                    </ul>
                </div>
            </article>

            {{-- Together AI --}}
            <article class="deep-dive-card fade-up delay-4" aria-labelledby="card-title-together">
                <div class="card-header-band">
                    <div class="card-avatar avatar-together" aria-hidden="true">TA</div>
                    <div class="card-title-group">
                        <h3 id="card-title-together">Together AI</h3>
                        <div class="deep-dive-tagline">Speed + Open Models</div>
                        <span class="vs-badge" aria-label="Compared to LLM Resayil">vs LLM Resayil</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">
                        Together AI specializes in fast inference on open-source models with built-in fine-tuning. If you need sub-second latency or custom model training, Together AI is the specialist. Pricing is 5–100x higher than LLM Resayil.
                    </p>
                    <ul class="deep-dive-list" aria-label="Together AI highlights">
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            500ms–2s latency (optimized for speed)
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            50+ open models with fine-tuning available
                        </li>
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            OpenAI-compatible with streaming and vision
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            $0.0005–$0.01/1K — 5–100x pricier than us
                        </li>
                        <li>
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Community support only (no dedicated team)
                        </li>
                    </ul>
                </div>
            </article>

        </div>{{-- /.deep-dive-grid --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         WHY CHOOSE US — Feature Highlights (SVG icons, no emoji)
    ═══════════════════════════════════════════════════════════ --}}
    <section class="highlights-section" aria-labelledby="highlights-title">
        <h2 class="section-title" id="highlights-title">Why LLM Resayil Stands Out</h2>
        <p class="section-description">
            Six reasons why thousands of developers choose LLM Resayil over the alternatives.
        </p>

        <div class="highlights-grid">

            <div class="highlight-item fade-up">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Coin/cost SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M12 7v1m0 8v1M9.5 10a2.5 2.5 0 0 1 5 0c0 1.5-1 2-2.5 2.5S9.5 13 9.5 14.5a2.5 2.5 0 0 0 5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h4>10x Cheaper Than OpenAI</h4>
                <p>
                    Starting at $0.0001 per 1K tokens. Our aggressive pricing means you pay less while maintaining quality across all model tiers.
                </p>
            </div>

            <div class="highlight-item fade-up delay-1">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Plug/compatible SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2v4M8 6h8M7 6v5a5 5 0 0 0 10 0V6M12 16v6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h4>100% OpenAI Compatible</h4>
                <p>
                    Drop-in replacement for OpenAI. Update one line of code — the endpoint URL. No SDK changes, no refactoring.
                </p>
            </div>

            <div class="highlight-item fade-up delay-2">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Lightning/hybrid SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M13 2L4.5 13.5H11L11 22L19.5 10.5H13L13 2Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h4>Hybrid: Local + Cloud Models</h4>
                <p>
                    Run local models for ultra-low latency, or route to cloud providers for cutting-edge capabilities. One API, your choice.
                </p>
            </div>

            <div class="highlight-item fade-up delay-3">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Grid/models SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                </div>
                <h4>45+ Models in One API</h4>
                <p>
                    Mistral, Llama, DeepSeek, Qwen, and Claude — all routed through a single, unified endpoint with one API key.
                </p>
            </div>

            <div class="highlight-item fade-up delay-4">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Rocket/free SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C12 2 7 6 7 12v3l-2 2v1h10v-1l-2-2v-3c0-6 5-10 5-10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 18a3 3 0 0 0 6 0" stroke="currentColor" stroke-width="1.75"/>
                        <circle cx="12" cy="7" r="1.25" fill="currentColor"/>
                    </svg>
                </div>
                <h4>Free to Start</h4>
                <p>
                    1,000 free credits on signup. No credit card required. Start building today, pay only if you scale beyond the free tier.
                </p>
            </div>

            <div class="highlight-item fade-up delay-5">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Lock/security SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <h4>Data Security &amp; Transparency</h4>
                <p>
                    All data encrypted in transit and at rest. Transparent billing with audit logs. Know exactly what you're paying for.
                </p>
            </div>

        </div>{{-- /.highlights-grid --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         COST CALCULATOR CTA
    ═══════════════════════════════════════════════════════════ --}}
    <section class="calculator-section" aria-labelledby="calculator-title">
        <div class="calculator-container fade-up">
            <h2 class="calculator-title" id="calculator-title">Calculate Your Savings</h2>
            <p class="calculator-description">
                Input your monthly token usage and see exactly how much you'll save switching from OpenAI or any competitor to LLM Resayil.
            </p>
            <a href="{{ route('cost-calculator') }}" class="calculator-cta" aria-label="Open interactive cost calculator tool">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="14" height="14" rx="2.5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5.5 6h7M5.5 9h7M5.5 12h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Open Cost Calculator
            </a>
        </div>
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         FAQ SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="faq-section" aria-labelledby="faq-title">
        <h2 class="section-title" id="faq-title">Frequently Asked Questions</h2>
        <p class="section-description">
            Common questions about LLM API pricing, compatibility, and choosing the right provider.
        </p>

        <div class="faq-container" role="list" aria-label="FAQ list">

            <div class="faq-item open" role="listitem">
                <button class="faq-question"
                        aria-expanded="true"
                        aria-controls="faq-answer-1"
                        id="faq-btn-1">
                    <span>Which API is cheapest overall?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-1" role="region" aria-labelledby="faq-btn-1">
                    <div class="faq-answer-inner">
                        <strong>LLM Resayil</strong> is the cheapest at $0.0001 per 1K input tokens. OpenRouter and Together AI are close (around $0.0005–$0.0008), but Resayil edges them out for pure cost efficiency. Ollama is free if you run it locally, but requires your own hardware and setup. OpenAI and Claude API are 10x+ more expensive.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-2"
                        id="faq-btn-2">
                    <span>Is LLM Resayil truly OpenAI-compatible?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-2" role="region" aria-labelledby="faq-btn-2">
                    <div class="faq-answer-inner">
                        Yes, 100%. LLM Resayil implements the OpenAI API specification. You can use the OpenAI Python SDK, JavaScript SDK, or any third-party SDK that supports OpenAI-compatible endpoints. Change one line of code — the <code>base_url</code> parameter — and you're done. The models, response formats, and error handling are all identical.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-3"
                        id="faq-btn-3">
                    <span>Can I migrate from OpenAI to LLM Resayil easily?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-3" role="region" aria-labelledby="faq-btn-3">
                    <div class="faq-answer-inner">
                        Yes. If you're already using the OpenAI SDK, you just need to change the <code>base_url</code> (or <code>api_base</code>) to <code>https://api.llm.resayil.io</code>. No other code changes needed. Model names stay the same. You can start with a small test to verify outputs, then gradually migrate your workload.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-4"
                        id="faq-btn-4">
                    <span>Which API is fastest?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-4" role="region" aria-labelledby="faq-btn-4">
                    <div class="faq-answer-inner">
                        <strong>Ollama</strong> is fastest (sub-500ms latency) because it runs locally with zero network overhead. For cloud APIs, <strong>Together AI</strong> (500ms–2s) and <strong>LLM Resayil</strong> (1–3s, faster on local models) are the quickest. OpenRouter and Claude API typically see 1–5s latency due to routing overhead.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-5"
                        id="faq-btn-5">
                    <span>Do I need my own GPU for Ollama?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-5" role="region" aria-labelledby="faq-btn-5">
                    <div class="faq-answer-inner">
                        Not strictly — Ollama can run on CPU, but it will be very slow (minutes per request). For practical use, you need a GPU: NVIDIA (CUDA), AMD (ROCm), or Mac Silicon. Setup takes 30 minutes to 2 hours depending on your hardware and OS.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-6"
                        id="faq-btn-6">
                    <span>Should I use a cloud API or run Ollama locally?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-6" role="region" aria-labelledby="faq-btn-6">
                    <div class="faq-answer-inner">
                        <strong>Use Ollama if:</strong> You need maximum privacy, have latency-sensitive real-time apps, or want zero API costs for development. <strong>Use a cloud API if:</strong> You want zero infrastructure overhead, automatic scaling, and access to the latest models. LLM Resayil offers the best middle ground — low cost, minimal setup, and cloud reliability.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-7"
                        id="faq-btn-7">
                    <span>What models does LLM Resayil support?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-7" role="region" aria-labelledby="faq-btn-7">
                    <div class="faq-answer-inner">
                        LLM Resayil supports 45+ models including Mistral 7B, Llama 2/3, DeepSeek, Qwen, and cloud-routed access to GPT-4, GPT-3.5, and Claude 3.5. Check the dashboard model catalog for the full updated list. New models are added monthly.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-8"
                        id="faq-btn-8">
                    <span>Is there a free tier?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-8" role="region" aria-labelledby="faq-btn-8">
                    <div class="faq-answer-inner">
                        Yes. Every new account gets 1,000 free credits — enough for ~5M tokens on budget models. No credit card required. Once you exhaust the free credits, pay-as-you-go with no monthly minimums.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-9"
                        id="faq-btn-9">
                    <span>How do I get support?</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-9" role="region" aria-labelledby="faq-btn-9">
                    <div class="faq-answer-inner">
                        LLM Resayil offers email support and a Discord community. For production workloads, our dedicated support team is available at <a href="mailto:support@llm.resayil.io" style="color: var(--gold);">support@llm.resayil.io</a>. OpenAI, Claude API, and OpenRouter offer tier-based support. Ollama and Together AI are mostly community-driven.
                    </div>
                </div>
            </div>

        </div>{{-- /.faq-container --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         FOOTER CTA BANNER — Gold background, dark text
    ═══════════════════════════════════════════════════════════ --}}
    <section class="footer-cta-section" aria-labelledby="footer-cta-headline">
        <h2 class="footer-cta-headline" id="footer-cta-headline">
            Ready to Switch? Start with <span class="cta-brand">LLM Resayil</span>
        </h2>
        <p class="footer-cta-tagline">
            Get 1,000 free credits. No credit card required. 100% OpenAI-compatible.
        </p>
        <div class="footer-cta-buttons">
            <a href="{{ route('register') }}" class="cta-btn primary-dark" aria-label="Create a free LLM Resayil account">
                Create Free Account
            </a>
            <a href="{{ route('cost-calculator') }}" class="cta-btn secondary-dark" aria-label="Open cost savings calculator">
                Calculate Savings
            </a>
        </div>
    </section>

    {{-- Internal link box --}}
    <div class="internal-links-box">
        <p>
            Need help deciding? Try our <a href="/cost-calculator">cost calculator</a> to compare prices, or read our <a href="/comparison">detailed OpenRouter vs LLM Resayil comparison</a>.
        </p>
    </div>

</main>

{{-- ── FAQ Schema (SEO) ── --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Which API is cheapest overall?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "LLM Resayil is the cheapest at $0.0001 per 1K input tokens. OpenRouter and Together AI are close (around $0.0005–$0.0008), but Resayil edges them out for pure cost efficiency. Ollama is free if you run it locally, but requires your own hardware and setup. OpenAI and Claude API are 10x+ more expensive."
      }
    },
    {
      "@type": "Question",
      "name": "Is LLM Resayil truly OpenAI-compatible?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, 100%. LLM Resayil implements the OpenAI API specification. You can use the OpenAI Python SDK, JavaScript SDK, or any other third-party SDK that supports OpenAI-compatible endpoints. Change one line of code — the base_url parameter — and you're done."
      }
    },
    {
      "@type": "Question",
      "name": "Can I migrate from OpenAI to LLM Resayil easily?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. If you're already using the OpenAI SDK, you just need to change the base_url (or api_base) to https://api.llm.resayil.io. No other code changes needed. Model names stay the same (e.g., gpt-4 maps to a compatible model on Resayil's platform)."
      }
    },
    {
      "@type": "Question",
      "name": "Which API is fastest?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ollama is fastest (sub-500ms latency) because it runs locally on your hardware with zero network overhead. For cloud APIs, Together AI (500ms–2s) and LLM Resayil (1–3s, faster on local models) are the quickest."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need my own GPU for Ollama?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Not strictly — Ollama can run on CPU, but it will be very slow (minutes per request). For practical use, you need a GPU: NVIDIA (CUDA), AMD (ROCm), or Mac Silicon (built-in). Once set up, you can run models like Mistral 7B with less than 1s per token latency."
      }
    }
  ]
}
</script>

{{-- ── Accordion + FAQ Toggle Script ── --}}
<script>
(function () {
    'use strict';

    /* ── Generic toggle helper ── */
    function toggleDisclosure(button, contentId, itemEl) {
        var isOpen = itemEl.classList.contains('open');
        itemEl.classList.toggle('open');
        button.setAttribute('aria-expanded', String(!isOpen));
    }

    /* ── FAQ ── */
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleDisclosure(btn, btn.getAttribute('aria-controls'), btn.closest('.faq-item'));
        });
        /* Enter/Space already fire click on <button>; no extra keydown needed */
    });

    /* ── Comparison Accordion ── */
    document.querySelectorAll('.accordion-header').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleDisclosure(btn, btn.getAttribute('aria-controls'), btn.closest('.accordion-item'));
        });
    });

    /* ── Scroll-reveal via IntersectionObserver ── */
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.fade-up').forEach(function (el) {
            observer.observe(el);
        });
    } else {
        /* Fallback: just show everything immediately */
        document.querySelectorAll('.fade-up').forEach(function (el) {
            el.classList.add('visible');
        });
    }
}());
</script>

@endsection
