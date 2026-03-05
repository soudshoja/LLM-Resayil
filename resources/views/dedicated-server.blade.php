@extends('layouts.app')

@section('title', 'Resayil LLM + Dedicated Server Hosting')

@push('styles')
<style>
    :root {
        --ds-bg: #0f1115;
        --ds-card: #13161d;
        --ds-gold: #d4af37;
        --ds-gold-light: #eac558;
        --ds-text: #e0e5ec;
        --ds-text-muted: #b8c0cc;
        --ds-text-muted-hover: #d0d8e0;
        --ds-border: #1e2230;
    }

    .ds-wrap {
        background: var(--ds-bg);
        font-family: 'Inter', sans-serif;
        color: var(--ds-text);
        overflow-x: hidden;
    }

    /* ── HERO SECTION ── */
    .ds-hero {
        position: relative;
        min-height: 45vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 5rem 2rem 6rem;
        overflow: hidden;
    }

    .ds-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 120% 100% at 50% -10%, rgba(212,175,55,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .ds-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1000px;
    }

    .ds-hero h1 {
        font-size: clamp(3rem, 12vw, 4.5rem);
        font-weight: 900;
        letter-spacing: -0.06em;
        line-height: 1;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--ds-gold), var(--ds-gold-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ds-hero-subtitle {
        font-size: clamp(1.1rem, 2.5vw, 1.6rem);
        color: var(--ds-text-muted);
        font-weight: 400;
        line-height: 1.7;
        margin-bottom: 3rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .ds-hero-cta {
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
        padding: 1.1rem 3rem;
        background: var(--ds-gold);
        color: var(--ds-bg);
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 200ms ease;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: var(--ds-gold-light);
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(212,175,55,0.35);
    }

    .btn-primary:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1.1rem 3rem;
        background: transparent;
        color: var(--ds-gold);
        border: 2px solid var(--ds-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 200ms ease;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
        color: var(--ds-gold-light);
        border-color: var(--ds-gold-light);
        transform: translateY(-3px);
    }

    .btn-secondary:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
    }

    /* ── VALUE PROPOSITION CARDS ── */
    .ds-value-section {
        padding: 7rem 2rem;
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-section-title {
        font-size: clamp(2.5rem, 8vw, 3.5rem);
        font-weight: 900;
        margin-bottom: 4rem;
        text-align: center;
        letter-spacing: -0.03em;
        color: var(--ds-text);
    }

    .ds-value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .ds-value-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 14px;
        padding: 3rem 2.5rem;
        transition: all 250ms ease;
        position: relative;
        overflow: hidden;
    }

    .ds-value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--ds-gold), var(--ds-gold-light));
        opacity: 0;
        transition: opacity 250ms ease;
    }

    .ds-value-card:hover {
        border-color: var(--ds-gold);
        box-shadow: 0 12px 40px rgba(212,175,55,0.15);
        transform: translateY(-4px);
    }

    .ds-value-card:hover::before {
        opacity: 1;
    }

    .ds-value-card:focus-visible {
        outline: 3px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .ds-value-icon {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        display: block;
    }

    .ds-value-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: var(--ds-text);
    }

    .ds-value-text {
        font-size: 0.95rem;
        color: var(--ds-text-muted);
        line-height: 1.7;
    }

    /* ── COMPARISON SECTION ── */
    .ds-comparison-section {
        padding: 7rem 2rem;
        background: linear-gradient(180deg, transparent 0%, rgba(212,175,55,0.03) 100%);
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-comparison-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 3rem;
        margin-top: 4rem;
    }

    .ds-comparison-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 14px;
        padding: 3rem 2.5rem;
        transition: all 250ms ease;
    }

    .ds-comparison-card:hover {
        border-color: var(--ds-gold);
        box-shadow: 0 12px 40px rgba(212,175,55,0.15);
    }

    .ds-comparison-card:focus-visible {
        outline: 3px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .ds-comparison-label {
        font-size: 0.85rem;
        color: var(--ds-text-muted);
        font-weight: 700;
        letter-spacing: 0.08em;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
    }

    .ds-comparison-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: var(--ds-text);
    }

    .ds-comparison-items {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ds-comparison-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .ds-item-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(212,175,55,0.15);
        color: var(--ds-gold);
        font-weight: bold;
    }

    .ds-item-text {
        font-size: 0.95rem;
        color: var(--ds-text-muted);
        line-height: 1.6;
    }

    .ds-comparison-highlight {
        background: rgba(212,175,55,0.1);
        border: 2px solid var(--ds-gold);
    }

    .ds-comparison-highlight .ds-comparison-label {
        color: var(--ds-gold);
    }

    /* ── INFRASTRUCTURE TIERS ── */
    .ds-tiers-section {
        padding: 7rem 2rem;
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-tiers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 3rem;
        margin-top: 4rem;
    }

    .ds-tier-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 14px;
        padding: 3.5rem 2.5rem;
        transition: all 250ms ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .ds-tier-card.featured {
        border-color: var(--ds-gold);
        box-shadow: 0 20px 60px rgba(212,175,55,0.25);
        transform: scale(1.05);
    }

    .ds-tier-card.featured::before {
        content: 'MOST POPULAR';
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ds-gold);
        color: var(--ds-bg);
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    .ds-tier-card:focus-visible {
        outline: 3px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .ds-tier-name {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--ds-text);
    }

    .ds-tier-subtitle {
        font-size: 0.85rem;
        color: var(--ds-text-muted);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .ds-tier-specs {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        margin-bottom: 2.5rem;
        flex-grow: 1;
    }

    .ds-spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--ds-border);
    }

    .ds-spec-label {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
    }

    .ds-spec-value {
        font-weight: 700;
        color: var(--ds-gold);
    }

    .ds-tier-pricing {
        margin-bottom: 2rem;
        padding: 2rem 0;
        border-top: 2px solid var(--ds-border);
        border-bottom: 2px solid var(--ds-border);
    }

    .ds-price-value {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--ds-gold);
        margin-bottom: 0.5rem;
    }

    .ds-price-period {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
    }

    .ds-tier-features {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .ds-feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: var(--ds-text);
    }

    .ds-feature-check {
        color: var(--ds-gold);
        font-weight: bold;
    }

    .ds-tier-cta {
        padding: 1.1rem 2rem;
        background: var(--ds-gold);
        color: var(--ds-bg);
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 200ms ease;
        text-decoration: none;
        text-align: center;
        font-size: 1rem;
        display: block;
        margin-top: auto;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ds-tier-card.featured .ds-tier-cta {
        background: var(--ds-gold);
    }

    .ds-tier-cta:hover {
        background: var(--ds-gold-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212,175,55,0.3);
    }

    .ds-tier-cta:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
    }

    /* ── USE CASES SECTION ── */
    .ds-usecases-section {
        padding: 7rem 2rem;
        background: linear-gradient(180deg, transparent 0%, rgba(212,175,55,0.02) 100%);
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-usecases-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.5rem;
        margin-top: 4rem;
    }

    .ds-usecase-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 12px;
        padding: 2.5rem;
        transition: all 200ms ease;
    }

    .ds-usecase-card:hover {
        border-color: var(--ds-gold);
        box-shadow: 0 10px 32px rgba(212,175,55,0.12);
        transform: translateY(-3px);
    }

    .ds-usecase-card:focus-visible {
        outline: 3px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .ds-usecase-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .ds-usecase-title {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        color: var(--ds-text);
    }

    .ds-usecase-desc {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
        line-height: 1.6;
    }

    /* ── ARCHITECTURE SECTION ── */
    .ds-architecture-section {
        padding: 7rem 2rem;
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-arch-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-top: 4rem;
    }

    .ds-arch-visual {
        background: var(--ds-card);
        border: 2px solid var(--ds-border);
        border-radius: 14px;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        align-items: center;
        text-align: center;
    }

    .ds-arch-component {
        background: rgba(212,175,55,0.1);
        border: 2px solid var(--ds-border);
        border-radius: 8px;
        padding: 1.5rem 2rem;
        width: 100%;
        font-weight: 600;
        color: var(--ds-text);
    }

    .ds-arch-component.resayil {
        background: rgba(212,175,55,0.15);
        border-color: var(--ds-gold);
        color: var(--ds-gold);
    }

    .ds-arch-arrow {
        color: var(--ds-text-muted);
        font-size: 1.5rem;
    }

    .ds-arch-description {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ds-arch-point {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .ds-arch-point-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(212,175,55,0.15);
        border-radius: 50%;
        color: var(--ds-gold);
        font-weight: bold;
        font-size: 1.2rem;
    }

    .ds-arch-point-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .ds-arch-point-title {
        font-weight: 700;
        color: var(--ds-text);
        font-size: 0.95rem;
    }

    .ds-arch-point-desc {
        color: var(--ds-text-muted);
        font-size: 0.85rem;
        line-height: 1.6;
    }

    /* ── FAQ SECTION ── */
    .ds-faq-section {
        padding: 7rem 2rem;
        background: linear-gradient(180deg, transparent 0%, rgba(212,175,55,0.03) 100%);
        max-width: 950px;
        margin: 0 auto;
    }

    .ds-faq-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        margin-top: 4rem;
    }

    .ds-faq-item {
        background: var(--ds-card);
        border: 1px solid var(--ds-border);
        border-radius: 12px;
        padding: 2rem;
        transition: all 200ms ease;
        cursor: pointer;
    }

    .ds-faq-item:hover {
        border-color: var(--ds-gold);
        box-shadow: 0 8px 24px rgba(212,175,55,0.1);
    }

    .ds-faq-item:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
    }

    .ds-faq-question {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ds-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
        user-select: none;
    }

    .ds-faq-toggle {
        font-size: 1.5rem;
        color: var(--ds-gold);
        transition: transform 200ms ease;
        flex-shrink: 0;
    }

    .ds-faq-item.active .ds-faq-toggle {
        transform: rotate(180deg);
    }

    .ds-faq-answer {
        font-size: 0.95rem;
        color: var(--ds-text-muted);
        line-height: 1.8;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--ds-border);
        display: none;
    }

    .ds-faq-item.active .ds-faq-answer {
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
    .ds-footer-cta {
        padding: 5rem 2rem;
        background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, transparent 100%);
        border-top: 2px solid var(--ds-border);
        text-align: center;
    }

    .ds-footer-headline {
        font-size: clamp(1.5rem, 5vw, 2.5rem);
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: var(--ds-text);
    }

    .ds-footer-text {
        font-size: 1.05rem;
        color: var(--ds-text-muted);
        margin-bottom: 2.5rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }

    .ds-footer-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .ds-footer-links {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
        margin-top: 2rem;
    }

    .ds-footer-link {
        color: var(--ds-gold);
        text-decoration: none;
        font-weight: 600;
        transition: color 150ms ease;
    }

    .ds-footer-link:hover {
        color: var(--ds-gold-light);
    }

    .ds-footer-link:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .ds-arch-grid {
            grid-template-columns: 1fr;
        }

        .ds-tier-card.featured {
            transform: scale(1);
        }
    }

    @media (max-width: 768px) {
        .ds-hero {
            min-height: 40vh;
            padding: 3rem 1.5rem 4rem;
        }

        .ds-hero h1 {
            font-size: clamp(2rem, 8vw, 3rem);
        }

        .ds-hero-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .ds-hero-cta {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .ds-section-title {
            font-size: 2rem;
        }

        .ds-value-section,
        .ds-comparison-section,
        .ds-tiers-section,
        .ds-usecases-section,
        .ds-architecture-section,
        .ds-faq-section {
            padding: 4rem 1.5rem;
        }

        .ds-value-grid,
        .ds-comparison-grid,
        .ds-tiers-grid,
        .ds-usecases-grid {
            gap: 2rem;
        }

        .ds-tier-card {
            padding: 2.5rem 1.75rem;
        }

        .ds-arch-grid {
            gap: 2.5rem;
        }

        .ds-arch-visual {
            padding: 2rem;
        }

        .ds-footer-text {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .ds-hero {
            padding: 2rem 1rem 3rem;
        }

        .ds-hero h1 {
            font-size: clamp(1.75rem, 6vw, 2.5rem);
            margin-bottom: 0.75rem;
        }

        .ds-hero-subtitle {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .ds-section-title {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .ds-value-section,
        .ds-comparison-section,
        .ds-tiers-section,
        .ds-usecases-section,
        .ds-architecture-section,
        .ds-faq-section {
            padding: 2.5rem 1rem;
        }

        .ds-value-card,
        .ds-comparison-card,
        .ds-tier-card,
        .ds-usecase-card,
        .ds-faq-item {
            padding: 1.75rem 1.25rem;
        }

        .ds-tier-card {
            transform: scale(1) !important;
        }

        .ds-arch-component {
            padding: 1.25rem 1.5rem;
        }

        .ds-footer-headline {
            font-size: 1.5rem;
        }

        .btn-primary, .btn-secondary {
            padding: 1rem 1.75rem;
            font-size: 0.95rem;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ds-tier-cta {
            min-height: 44px;
        }
    }
</style>
@endpush

<main class="ds-wrap">
    <!-- HERO SECTION -->
    <section class="ds-hero">
        <div class="ds-hero-content">
            <h1>Dedicated Server + Resayil LLM API</h1>
            <p class="ds-hero-subtitle">Enterprise-grade infrastructure for maximum control, compliance, and performance</p>
            <div class="ds-hero-cta">
                <a href="{{ route('register') }}" class="btn-primary">Start Free Trial</a>
                <a href="{{ route('contact') }}" class="btn-secondary">Contact Sales</a>
            </div>
        </div>
    </section>

    <!-- VALUE PROPOSITION CARDS -->
    <section class="ds-value-section" role="region" aria-labelledby="value-section-title">
        <h2 class="ds-section-title" id="value-section-title">Why Dedicated + API?</h2>
        <div class="ds-value-grid">
            <div class="ds-value-card" tabindex="0">
                <span class="ds-value-icon" aria-label="lightning bolt - security">⚡</span>
                <h3 class="ds-value-title">API Simplicity</h3>
                <p class="ds-value-text">No model management overhead. Resayil handles updates, scaling, and reliability while you focus on your application logic.</p>
            </div>
            <div class="ds-value-card" tabindex="0">
                <span class="ds-value-icon" aria-label="padlock - encrypted">🔒</span>
                <h3 class="ds-value-title">Complete Control</h3>
                <p class="ds-value-text">Your dedicated server runs your applications. Data stays within your infrastructure. Full compliance with regulatory requirements.</p>
            </div>
            <div class="ds-value-card" tabindex="0">
                <span class="ds-value-icon" aria-label="money bag - cost effective">💰</span>
                <h3 class="ds-value-title">Cost Efficiency</h3>
                <p class="ds-value-text">Pay-per-use API pricing with no monthly minimums. Dedicated hardware cost is predictable and scales with your needs.</p>
            </div>
        </div>
    </section>

    <!-- COMPARISON SECTION -->
    <section class="ds-comparison-section" role="region" aria-labelledby="comparison-section-title">
        <h2 class="ds-section-title" id="comparison-section-title">The Infrastructure Debate</h2>
        <div class="ds-comparison-grid">
            <!-- Self-Hosted Ollama -->
            <div class="ds-comparison-card" tabindex="0">
                <p class="ds-comparison-label">Self-Hosted Approach</p>
                <h3 class="ds-comparison-title">Self-Hosted Ollama</h3>
                <div class="ds-comparison-items">
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="x mark">✗</div>
                        <p class="ds-item-text">Complex setup & maintenance</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="x mark">✗</div>
                        <p class="ds-item-text">Model updates = downtime</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="x mark">✗</div>
                        <p class="ds-item-text">DevOps team required</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">100% data privacy</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">No cloud vendor lock-in</p>
                    </div>
                </div>
            </div>

            <!-- Cloud API -->
            <div class="ds-comparison-card" tabindex="0">
                <p class="ds-comparison-label">Cloud-Only Approach</p>
                <h3 class="ds-comparison-title">Generic Cloud API</h3>
                <div class="ds-comparison-items">
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Easy to integrate</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Zero infrastructure cost</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Automatic scaling</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="x mark">✗</div>
                        <p class="ds-item-text">Higher per-token costs</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="x mark">✗</div>
                        <p class="ds-item-text">Data sent to vendor</p>
                    </div>
                </div>
            </div>

            <!-- Resayil Hybrid -->
            <div class="ds-comparison-card ds-comparison-highlight" tabindex="0">
                <p class="ds-comparison-label">Hybrid Approach</p>
                <h3 class="ds-comparison-title">Resayil + Dedicated</h3>
                <div class="ds-comparison-items">
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Best of both worlds</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">API simplicity + control</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Data stays on-premises</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Predictable pricing</p>
                    </div>
                    <div class="ds-comparison-item">
                        <div class="ds-item-icon" aria-label="checkmark">✓</div>
                        <p class="ds-item-text">Enterprise support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFRASTRUCTURE TIERS -->
    <section class="ds-tiers-section" role="region" aria-labelledby="tiers-section-title">
        <h2 class="ds-section-title" id="tiers-section-title">Hosting Tiers</h2>
        <div class="ds-tiers-grid">
            <!-- Starter Tier -->
            <div class="ds-tier-card" tabindex="0">
                <h3 class="ds-tier-name">Starter</h3>
                <p class="ds-tier-subtitle">Small dedicated server for development & early production</p>

                <div class="ds-tier-specs">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">CPU</span>
                        <span class="ds-spec-value">4-core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">RAM</span>
                        <span class="ds-spec-value">16 GB</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Storage</span>
                        <span class="ds-spec-value">256 GB SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Bandwidth</span>
                        <span class="ds-spec-value">5 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">API Calls</span>
                        <span class="ds-spec-value">Up to 100K/mo</span>
                    </div>
                </div>

                <div class="ds-tier-pricing">
                    <div class="ds-price-value">$299</div>
                    <div class="ds-price-period">/month</div>
                </div>

                <div class="ds-tier-features">
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Resayil API access included</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Standard support (8h response)</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Basic monitoring</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>1 public IP address</span>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="ds-tier-cta">Get Started</a>
            </div>

            <!-- Professional Tier -->
            <div class="ds-tier-card featured" tabindex="0">
                <h3 class="ds-tier-name">Professional</h3>
                <p class="ds-tier-subtitle">Medium dedicated server for production applications</p>

                <div class="ds-tier-specs">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">CPU</span>
                        <span class="ds-spec-value">8-core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">RAM</span>
                        <span class="ds-spec-value">64 GB</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Storage</span>
                        <span class="ds-spec-value">1 TB SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Bandwidth</span>
                        <span class="ds-spec-value">10 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">API Calls</span>
                        <span class="ds-spec-value">Up to 500K/mo</span>
                    </div>
                </div>

                <div class="ds-tier-pricing">
                    <div class="ds-price-value">$799</div>
                    <div class="ds-price-period">/month</div>
                </div>

                <div class="ds-tier-features">
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Priority API support</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>4h SLA response time</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Advanced monitoring & alerts</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>4 public IP addresses</span>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="ds-tier-cta">Get Started</a>
            </div>

            <!-- Enterprise Tier -->
            <div class="ds-tier-card" tabindex="0">
                <h3 class="ds-tier-name">Enterprise</h3>
                <p class="ds-tier-subtitle">Large dedicated server with white-glove support</p>

                <div class="ds-tier-specs">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">CPU</span>
                        <span class="ds-spec-value">16+ core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">RAM</span>
                        <span class="ds-spec-value">256 GB+</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Storage</span>
                        <span class="ds-spec-value">4 TB+ SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">Bandwidth</span>
                        <span class="ds-spec-value">20 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">API Calls</span>
                        <span class="ds-spec-value">Unlimited</span>
                    </div>
                </div>

                <div class="ds-tier-pricing">
                    <div class="ds-price-value">Custom</div>
                    <div class="ds-price-period">Contact sales</div>
                </div>

                <div class="ds-tier-features">
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Dedicated account manager</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>1h SLA response time</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Custom configurations</span>
                    </div>
                    <div class="ds-feature-item">
                        <span class="ds-feature-check" aria-label="checkmark">✓</span>
                        <span>Unlimited IP addresses</span>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="ds-tier-cta">Contact Sales</a>
            </div>
        </div>
    </section>

    <!-- USE CASES SECTION -->
    <section class="ds-usecases-section" role="region" aria-labelledby="usecases-section-title">
        <h2 class="ds-section-title" id="usecases-section-title">Perfect For</h2>
        <div class="ds-usecases-grid">
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon" aria-label="bank">🏦</span>
                <h3 class="ds-usecase-title">Financial Services</h3>
                <p class="ds-usecase-desc">Regulatory compliance (SOC 2, HIPAA), data sovereignty, and zero data sharing requirements met with dedicated infrastructure.</p>
            </div>
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon" aria-label="hospital">🏥</span>
                <h3 class="ds-usecase-title">Healthcare</h3>
                <p class="ds-usecase-desc">Patient data privacy, HIPAA compliance, and encrypted on-premise processing without cloud data exposure.</p>
            </div>
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon" aria-label="rocket">🚀</span>
                <h3 class="ds-usecase-title">Enterprise SaaS</h3>
                <p class="ds-usecase-desc">White-label AI features, customer data isolation, and guaranteed uptime with multi-region failover.</p>
            </div>
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon" aria-label="analytics">📊</span>
                <h3 class="ds-usecase-title">High-Volume Production</h3>
                <p class="ds-usecase-desc">Millions of API calls, predictable costs, and dedicated compute resources without sharing capacity with others.</p>
            </div>
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon" aria-label="security">🔐</span>
                <h3 class="ds-usecase-title">Regulated Industries</h3>
                <p class="ds-usecase-desc">Government, defense, and critical infrastructure needs with full audit trails and compliance reporting.</p>
            </div>
            <div class="ds-usecase-card" tabindex="0">
                <span class="ds-usecase-icon">🌍</span>
                <h3 class="ds-usecase-title">Multi-Tenant Platforms</h3>
                <p class="ds-usecase-desc">Customer-specific models, isolated analytics, and dedicated capacity per tenant with consolidated billing.</p>
            </div>
        </div>
    </section>

    <!-- ARCHITECTURE SECTION -->
    <section class="ds-architecture-section" role="region" aria-labelledby="architecture-section-title">
        <h2 class="ds-section-title" id="architecture-section-title">How It Works</h2>
        <div class="ds-arch-grid">
            <div class="ds-arch-visual">
                <div class="ds-arch-component">Your Applications</div>
                <div class="ds-arch-arrow">↓</div>
                <div class="ds-arch-component">Your Dedicated Server</div>
                <div class="ds-arch-arrow">↔</div>
                <div class="ds-arch-component resayil">Resayil LLM API</div>
            </div>
            <div class="ds-arch-description">
                <div class="ds-arch-point">
                    <div class="ds-arch-point-icon">1</div>
                    <div class="ds-arch-point-text">
                        <div class="ds-arch-point-title">Your Infrastructure</div>
                        <div class="ds-arch-point-desc">Applications run on a dedicated server under your full control. Data processing, storage, and business logic all stay on-premises.</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-point-icon">2</div>
                    <div class="ds-arch-point-text">
                        <div class="ds-arch-point-title">Resayil Connection</div>
                        <div class="ds-arch-point-desc">Your applications call the Resayil API for LLM inference only. No sensitive data leaves your infrastructure.</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-point-icon">3</div>
                    <div class="ds-arch-point-text">
                        <div class="ds-arch-point-title">Model Management</div>
                        <div class="ds-arch-point-desc">Resayil handles 45+ models, automatic scaling, failover, and updates. You don't manage infrastructure.</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-point-icon">4</div>
                    <div class="ds-arch-point-text">
                        <div class="ds-arch-point-title">Predictable Costs</div>
                        <div class="ds-arch-point-desc">Pay-per-token for API calls + fixed monthly server cost. No surprises, easy budget planning.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="ds-faq-section" role="region" aria-labelledby="faq-section-title">
        <h2 class="ds-section-title" id="faq-section-title">Frequently Asked Questions</h2>
        <div class="ds-faq-list">
            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>Can I host Resayil models on my dedicated server?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    No, that would require self-hosted Ollama. Resayil Dedicated offers the API approach: your server calls Resayil's inference endpoints. This keeps model management, scaling, and updates out of your operations while data stays on-premises.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>How is this different from self-hosted Ollama?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Self-hosted Ollama requires you to manage models, VRAM, failover, and updates. Resayil Dedicated gives you dedicated infrastructure for your apps (data stays on-premises) while Resayil handles all model operations. You get 95% of the control with 0% of the complexity.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>What's included in the dedicated server pricing?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    The monthly price covers hardware (CPU, RAM, storage), bandwidth, server management, OS, security updates, and monitoring. Resayil API access is included. Additional charges apply only when you call the API (pay-per-token, same as pay-as-you-go).
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>Can I customize the server configuration?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Yes. Starter and Professional tiers have fixed specs, but Enterprise tier is fully customizable. Contact sales to discuss specific CPU, RAM, storage, or GPU requirements. Custom configurations are available on a case-by-case basis.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>What SLA do you offer?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Starter: 99.5% uptime. Professional: 99.8% uptime + 4-hour response SLA. Enterprise: 99.95% uptime + 1-hour response SLA with dedicated account manager and custom terms upon request.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>How do I migrate from self-hosted Ollama?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Update your model endpoints to point to Resayil API URLs and use your API key. Since Resayil is OpenAI-compatible, most code changes are minimal (just swap base URL + API key). Our support team provides migration assistance and can help with load testing before go-live.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>Is there a minimum contract?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Starter and Professional tiers are month-to-month with no lock-in. Enterprise contracts are custom and discussed during sales. We offer discounts for annual or multi-year commitments if you prefer predictable, budgeted costs.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>Can I run other workloads on the server?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Yes. The dedicated server is yours to use. Run your applications, databases, caches, or any other workloads. We provide bare metal or managed Linux. You have root/admin access and can install anything you need.
                </div>
            </div>

            <div class="ds-faq-item" role="button" tabindex="0" aria-expanded="false">
                <div class="ds-faq-question">
                    <span>What if I need more capacity later?</span>
                    <span class="ds-faq-toggle">+</span>
                </div>
                <div class="ds-faq-answer">
                    Upgrade anytime. Move from Starter to Professional to Enterprise, or modify your server specs. We'll coordinate downtime-free upgrades where possible. API scaling is automatic — just use more tokens, and the API handles it.
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER CTA -->
    <section class="ds-footer-cta" role="region" aria-labelledby="footer-cta-title">
        <h2 class="ds-footer-headline" id="footer-cta-title">Ready to Deploy?</h2>
        <p class="ds-footer-text">Get dedicated infrastructure with enterprise-grade LLM API. Start your free trial today or schedule a demo with our team to discuss your specific compliance and performance needs.</p>
        <div class="ds-footer-buttons">
            <a href="{{ route('register') }}" class="btn-primary">Start Free Trial</a>
            <a href="{{ route('contact') }}" class="btn-secondary">Schedule Demo</a>
        </div>
        <div class="ds-footer-links">
            Explore our <a href="{{ route('docs') }}" class="ds-footer-link">API documentation</a>,
            review <a href="{{ route('billing.plans') }}" class="ds-footer-link">pricing plans</a>,
            or compare <a href="{{ route('comparison') }}" class="ds-footer-link">alternatives</a>.
        </div>
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
      "name": "Can I host Resayil models on my dedicated server?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No, that would require self-hosted Ollama. Resayil Dedicated offers the API approach: your server calls Resayil's inference endpoints. This keeps model management, scaling, and updates out of your operations while data stays on-premises."
      }
    },
    {
      "@type": "Question",
      "name": "How is this different from self-hosted Ollama?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Self-hosted Ollama requires you to manage models, VRAM, failover, and updates. Resayil Dedicated gives you dedicated infrastructure for your apps (data stays on-premises) while Resayil handles all model operations. You get 95% of the control with 0% of the complexity."
      }
    },
    {
      "@type": "Question",
      "name": "What's included in the dedicated server pricing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The monthly price covers hardware (CPU, RAM, storage), bandwidth, server management, OS, security updates, and monitoring. Resayil API access is included. Additional charges apply only when you call the API (pay-per-token, same as pay-as-you-go)."
      }
    },
    {
      "@type": "Question",
      "name": "Can I customize the server configuration?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Starter and Professional tiers have fixed specs, but Enterprise tier is fully customizable. Contact sales to discuss specific CPU, RAM, storage, or GPU requirements. Custom configurations are available on a case-by-case basis."
      }
    },
    {
      "@type": "Question",
      "name": "What SLA do you offer?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Starter: 99.5% uptime. Professional: 99.8% uptime + 4-hour response SLA. Enterprise: 99.95% uptime + 1-hour response SLA with dedicated account manager and custom terms upon request."
      }
    },
    {
      "@type": "Question",
      "name": "How do I migrate from self-hosted Ollama?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Update your model endpoints to point to Resayil API URLs and use your API key. Since Resayil is OpenAI-compatible, most code changes are minimal (just swap base URL + API key). Our support team provides migration assistance and can help with load testing before go-live."
      }
    },
    {
      "@type": "Question",
      "name": "Is there a minimum contract?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Starter and Professional tiers are month-to-month with no lock-in. Enterprise contracts are custom and discussed during sales. We offer discounts for annual or multi-year commitments if you prefer predictable, budgeted costs."
      }
    },
    {
      "@type": "Question",
      "name": "Can I run other workloads on the server?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. The dedicated server is yours to use. Run your applications, databases, caches, or any other workloads. We provide bare metal or managed Linux. You have root/admin access and can install anything you need."
      }
    },
    {
      "@type": "Question",
      "name": "What if I need more capacity later?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Upgrade anytime. Move from Starter to Professional to Enterprise, or modify your server specs. We'll coordinate downtime-free upgrades where possible. API scaling is automatic — just use more tokens, and the API handles it."
      }
    }
  ]
}
</script>

<script>
    // FAQ Accordion with Keyboard Support
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.ds-faq-item');
        faqItems.forEach(item => {
            const toggleFAQ = function() {
                item.classList.toggle('active');
                const isExpanded = item.classList.contains('active');
                item.setAttribute('aria-expanded', isExpanded);
            };

            item.addEventListener('click', toggleFAQ);

            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleFAQ();
                }
            });
        });
    });
</script>

@endsection
