@extends('layouts.app')

@section('title', __('comparison.title'))

@push('styles')
<style>
    /* ── CSS CUSTOM PROPERTIES ── */
    :root {
        --comp-bg: #0f1115;
        --comp-card: #13161d;
        --comp-gold: #d4af37;
        --comp-gold-light: #eac558;
        --comp-gold-dim: rgba(212, 175, 55, 0.15);
        --comp-text: #e0e5ec;
        /* #c0c8d4 passes 4.5:1 on #0f1115 and 4.5:1 on #13161d */
        --comp-text-muted: #c0c8d4;
        --comp-border: #1e2230;
        --comp-card-hover: #16191f;
        --comp-row-hover: rgba(212, 175, 55, 0.05);
        --comp-zebra: rgba(255, 255, 255, 0.025);
        --comp-focus-ring: 2px solid #d4af37;
        --comp-focus-offset: 3px;
        --comp-radius: 12px;
        --comp-transition: 200ms ease;
    }

    /* ── BASE ── */
    .comp-wrap {
        background: var(--comp-bg);
        font-family: 'Inter', 'Tajawal', sans-serif;
        color: var(--comp-text);
        overflow-x: hidden;
    }

    .comp-wrap *:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: var(--comp-focus-offset);
        border-radius: 4px;
    }

    /* ── HERO SECTION ── */
    .comp-hero {
        position: relative;
        min-height: 44vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 5rem 2rem 7rem;
        overflow: hidden;
    }

    .comp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 120% 90% at 50% -10%, rgba(212,175,55,0.13) 0%, transparent 65%),
            radial-gradient(ellipse 60% 40% at 80% 80%, rgba(212,175,55,0.04) 0%, transparent 60%);
        pointer-events: none;
    }

    .comp-hero-content {
        position: relative;
        z-index: 2;
        max-width: 820px;
    }

    .comp-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(212, 175, 55, 0.12);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 100px;
        padding: 0.35rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--comp-gold);
        margin-bottom: 1.5rem;
    }

    .comp-hero h1 {
        font-size: clamp(2.25rem, 6vw, 4.5rem);
        font-weight: 900;
        letter-spacing: -0.04em;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        color: var(--comp-text);
    }

    .comp-hero h1 .gold-word {
        background: linear-gradient(135deg, var(--comp-gold), var(--comp-gold-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .comp-hero-subtitle {
        font-size: clamp(1rem, 2vw, 1.2rem);
        color: var(--comp-text-muted);
        font-weight: 400;
        line-height: 1.65;
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .comp-hero-cta {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    /* ── BUTTONS ── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        min-height: 48px;
        background: var(--comp-gold);
        color: #0f1115;
        border: 2px solid var(--comp-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background var(--comp-transition), transform var(--comp-transition), box-shadow var(--comp-transition);
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary:hover {
        background: var(--comp-gold-light);
        border-color: var(--comp-gold-light);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212,175,55,0.35);
    }

    .btn-primary:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: var(--comp-focus-offset);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        min-height: 48px;
        background: transparent;
        color: var(--comp-gold);
        border: 2px solid var(--comp-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background var(--comp-transition), color var(--comp-transition), border-color var(--comp-transition), transform var(--comp-transition);
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
        color: var(--comp-gold-light);
        border-color: var(--comp-gold-light);
        transform: translateY(-2px);
    }

    .btn-secondary:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: var(--comp-focus-offset);
    }

    @media (prefers-reduced-motion: reduce) {
        .btn-primary:hover,
        .btn-secondary:hover {
            transform: none;
        }
    }

    /* ── SECTION SHARED ── */
    .comp-section-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 5rem 2rem;
    }

    .comp-section-inner--narrow {
        max-width: 900px;
    }

    .comp-section-title {
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        font-weight: 800;
        margin-bottom: 0.75rem;
        text-align: center;
        letter-spacing: -0.03em;
        color: var(--comp-text);
    }

    .comp-section-subtitle {
        text-align: center;
        color: var(--comp-text-muted);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ── QUICK COMPARISON TABLE ── */
    .comp-table-scroll-wrapper {
        position: relative;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: var(--comp-radius);
        /* Fade-edge indicator on the right when scrollable */
    }

    .comp-table-scroll-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 48px;
        height: 100%;
        background: linear-gradient(to right, transparent, rgba(15, 17, 21, 0.9));
        pointer-events: none;
        border-radius: 0 var(--comp-radius) var(--comp-radius) 0;
        /* Hidden when fully scrolled right — JS adds class to toggle */
        opacity: 1;
        transition: opacity 200ms ease;
    }

    .comp-table-scroll-wrapper.no-overflow::after {
        opacity: 0;
    }

    .comp-table-outer {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: var(--comp-radius);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .comp-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 520px;
    }

    /* Header row */
    .comp-table thead tr {
        background: rgba(212,175,55,0.07);
        border-bottom: 2px solid var(--comp-border);
    }

    .comp-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--comp-text);
        white-space: nowrap;
    }

    /* Gold column header */
    .comp-table th.col-resayil {
        color: var(--comp-gold);
        border-bottom: 2px solid var(--comp-gold);
        background: rgba(212,175,55,0.07);
    }

    .comp-table td {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--comp-border);
        font-size: 0.95rem;
        color: var(--comp-text);
        line-height: 1.5;
        transition: background var(--comp-transition);
    }

    /* Zebra striping */
    .comp-table tbody tr:nth-child(even) td {
        background: var(--comp-zebra);
    }

    /* Row hover */
    .comp-table tbody tr:hover td {
        background: var(--comp-row-hover);
    }

    .comp-table tr:last-child td {
        border-bottom: none;
    }

    /* Feature column */
    .comp-table td:first-child {
        font-weight: 600;
        color: var(--comp-text);
        white-space: nowrap;
    }

    /* LLM Resayil column — gold highlight */
    .comp-table td.col-resayil {
        color: var(--comp-gold);
        font-weight: 600;
        border-left: 3px solid rgba(212, 175, 55, 0.4);
        background: rgba(212, 175, 55, 0.04);
    }

    .comp-table tbody tr:nth-child(even) td.col-resayil {
        background: rgba(212, 175, 55, 0.07);
    }

    .comp-table tbody tr:hover td.col-resayil {
        background: rgba(212, 175, 55, 0.11);
    }

    /* Winner column */
    .comp-table td.col-winner {
        text-align: center;
        white-space: nowrap;
    }

    /* Winner badge */
    .comp-winner-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: var(--comp-gold);
        color: #0f1115;
        padding: 0.3rem 0.75rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.04em;
    }

    .comp-winner-badge-svg {
        width: 10px;
        height: 10px;
        flex-shrink: 0;
    }

    .comp-tie-text {
        font-size: 0.82rem;
        color: var(--comp-text-muted);
        font-style: italic;
    }

    .comp-openrouter-winner {
        font-size: 0.82rem;
        color: var(--comp-text-muted);
        font-weight: 600;
    }

    /* ── COST BREAKDOWN ── */
    .comp-cost-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .comp-cost-card {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: var(--comp-radius);
        padding: 2rem;
        transition: border-color var(--comp-transition), box-shadow var(--comp-transition);
        position: relative;
        overflow: hidden;
    }

    .comp-cost-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--comp-gold), var(--comp-gold-light));
        opacity: 0;
        transition: opacity var(--comp-transition);
    }

    .comp-cost-card:hover {
        border-color: rgba(212, 175, 55, 0.4);
        box-shadow: 0 8px 32px rgba(212,175,55,0.1);
    }

    .comp-cost-card:hover::before {
        opacity: 1;
    }

    .comp-cost-card-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--comp-border);
    }

    .comp-cost-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--comp-text);
        margin-bottom: 0.25rem;
    }

    .comp-cost-subtitle {
        font-size: 0.85rem;
        color: var(--comp-text-muted);
    }

    .comp-cost-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--comp-border);
    }

    .comp-cost-row:last-child {
        border-bottom: none;
    }

    .comp-cost-label {
        font-size: 0.9rem;
        color: var(--comp-text-muted);
    }

    .comp-cost-value {
        font-weight: 700;
        font-size: 1.05rem;
    }

    .comp-cost-resayil {
        color: var(--comp-gold);
    }

    .comp-cost-openrouter {
        color: var(--comp-text);
        text-decoration: line-through;
        opacity: 0.6;
        font-weight: 500;
    }

    .comp-savings {
        margin-top: 1.5rem;
        padding: 1rem;
        background: rgba(212, 175, 55, 0.08);
        border-radius: 8px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        text-align: center;
    }

    .comp-savings-label {
        font-size: 0.78rem;
        color: var(--comp-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.35rem;
        font-weight: 600;
    }

    .comp-savings-amount {
        font-size: 1.85rem;
        font-weight: 900;
        color: var(--comp-gold);
        line-height: 1.1;
    }

    /* ── CLUSTER CALLOUT ── */
    .comp-cluster-callout {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem 3rem;
    }

    .comp-cluster-inner {
        text-align: center;
        padding: 1.75rem 2rem;
        background: rgba(212,175,55,0.05);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: var(--comp-radius);
    }

    .comp-cluster-inner p {
        color: var(--comp-text-muted);
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .comp-cluster-inner a {
        color: var(--comp-gold);
        font-weight: 600;
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
        transition: color var(--comp-transition);
    }

    .comp-cluster-inner a:hover {
        color: var(--comp-gold-light);
    }

    .comp-cluster-inner a:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: var(--comp-focus-offset);
        border-radius: 2px;
    }

    /* ── FEATURE MATRIX ── */
    .comp-features-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-top: 3rem;
    }

    .comp-feature-column {
        display: flex;
        flex-direction: column;
    }

    .comp-feature-column-title {
        font-size: 1.35rem;
        font-weight: 800;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--comp-border);
        color: var(--comp-text);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .comp-feature-column-title.resayil {
        color: var(--comp-gold);
        border-bottom-color: rgba(212, 175, 55, 0.4);
    }

    .comp-feature-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .comp-feature-item {
        display: flex;
        gap: 0.875rem;
        align-items: flex-start;
    }

    /* SVG icon wrapper */
    .comp-feature-icon {
        flex-shrink: 0;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }

    .comp-feature-icon svg {
        width: 18px;
        height: 18px;
    }

    .comp-feature-text {
        font-size: 0.95rem;
        line-height: 1.55;
        color: var(--comp-text);
    }

    /* ── FAQ SECTION ── */
    .comp-faq-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 3rem;
    }

    .comp-faq-item {
        background: var(--comp-card);
        border: 1px solid var(--comp-border);
        border-radius: var(--comp-radius);
        overflow: hidden;
        transition: border-color var(--comp-transition), box-shadow var(--comp-transition);
    }

    .comp-faq-item:hover {
        border-color: rgba(212, 175, 55, 0.35);
        box-shadow: 0 4px 16px rgba(212,175,55,0.08);
    }

    /* Semantic <button> for FAQ trigger */
    .comp-faq-trigger {
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        padding: 1.5rem 1.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        text-align: left;
        color: var(--comp-text);
        font-family: inherit;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
        min-height: 44px;
        transition: background var(--comp-transition);
    }

    .comp-faq-trigger:hover {
        background: rgba(255,255,255,0.025);
    }

    .comp-faq-trigger:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: 0;
        border-radius: var(--comp-radius);
    }

    /* Gold arrow indicator */
    .comp-faq-arrow {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        color: var(--comp-gold);
        transition: transform 250ms ease;
    }

    .comp-faq-trigger[aria-expanded="true"] .comp-faq-arrow {
        transform: rotate(180deg);
    }

    /* Answer panel — smooth height transition via max-height */
    .comp-faq-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 280ms ease;
    }

    .comp-faq-panel[aria-hidden="false"] {
        max-height: 600px;
    }

    .comp-faq-answer {
        padding: 0 1.75rem 1.5rem;
        border-top: 1px solid var(--comp-border);
        margin-top: 0;
    }

    .comp-faq-answer p {
        font-size: 0.95rem;
        color: var(--comp-text-muted);
        line-height: 1.75;
        margin: 0;
        padding-top: 1.25rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .comp-faq-panel {
            transition: none;
        }
        .comp-faq-arrow {
            transition: none;
        }
    }

    /* ── FOOTER CTA SECTION ── */
    .comp-footer-cta {
        border-top: 1px solid var(--comp-border);
        background: linear-gradient(180deg, rgba(212,175,55,0.04) 0%, transparent 100%);
    }

    .comp-footer-cta-inner {
        max-width: 700px;
        margin: 0 auto;
        padding: 5rem 2rem;
        text-align: center;
    }

    .comp-footer-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--comp-text);
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .comp-footer-subtitle {
        font-size: 1rem;
        color: var(--comp-text-muted);
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .comp-footer-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .comp-footer-links {
        font-size: 0.875rem;
        color: var(--comp-text-muted);
        line-height: 1.6;
    }

    .comp-footer-links a {
        color: var(--comp-gold);
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
        font-weight: 600;
        transition: color var(--comp-transition);
    }

    .comp-footer-links a:hover {
        color: var(--comp-gold-light);
    }

    .comp-footer-links a:focus-visible {
        outline: var(--comp-focus-ring);
        outline-offset: 2px;
        border-radius: 2px;
    }

    /* ── FADE-IN ANIMATION ── */
    .comp-fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 400ms ease, transform 400ms ease;
    }

    .comp-fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .comp-fade-in {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .comp-cost-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .comp-hero {
            min-height: auto;
            padding: 3.5rem 1.5rem 5rem;
        }

        .comp-hero h1 {
            font-size: clamp(1.75rem, 7vw, 2.75rem);
        }

        .comp-hero-subtitle {
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .comp-hero-cta {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }

        .comp-section-inner {
            padding: 3.5rem 1.5rem;
        }

        .comp-cost-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .comp-features-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }

        .comp-cost-card {
            padding: 1.5rem;
        }

        .comp-faq-trigger {
            padding: 1.25rem 1.25rem;
            font-size: 0.95rem;
        }

        .comp-faq-answer {
            padding: 0 1.25rem 1.25rem;
        }

        .comp-footer-cta-inner {
            padding: 3.5rem 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .comp-hero {
            padding: 2.5rem 1rem 4rem;
        }

        .comp-section-inner {
            padding: 2.5rem 1rem;
        }

        .comp-section-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .comp-cost-card {
            padding: 1.25rem;
        }

        .comp-feature-column-title {
            font-size: 1.15rem;
        }

        .comp-feature-list {
            gap: 1rem;
        }

        .comp-faq-trigger {
            padding: 1rem 1rem;
            font-size: 0.9rem;
        }

        .comp-faq-answer {
            padding: 0 1rem 1rem;
        }

        .comp-cluster-callout {
            padding: 0 1rem 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<main class="comp-wrap" id="main-content">

    <!-- ═══════════════════════════════
         HERO SECTION
    ═══════════════════════════════ -->
    <section class="comp-hero" aria-labelledby="hero-heading">
        <div class="comp-hero-content">
            <span class="comp-hero-eyebrow" aria-hidden="true">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M6 1L7.5 4.5H11L8.5 6.8L9.5 10.5L6 8.5L2.5 10.5L3.5 6.8L1 4.5H4.5L6 1Z" fill="currentColor"/>
                </svg>
                {{ __('comparison.hero_eyebrow') }}
            </span>
            <h1 id="hero-heading">
                {!! __('comparison.hero_title', ['percent' => '<span class="gold-word">30%</span>']) !!}
            </h1>
            <p class="comp-hero-subtitle">
                {{ __('comparison.hero_subtitle') }}
            </p>
            <div class="comp-hero-cta">
                <a href="{{ route('register') }}" class="btn-primary">
                    {{ __('comparison.hero_cta_primary') }}
                </a>
                <button type="button" class="btn-secondary" aria-label="{{ __('comparison.hero_cta_secondary') }}">
                    {{ __('comparison.hero_cta_secondary') }}
                </button>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════
         QUICK COMPARISON TABLE
    ═══════════════════════════════ -->
    <section aria-labelledby="table-heading">
        <div class="comp-section-inner comp-table-section-anchor">
            <h2 class="comp-section-title" id="table-heading">{{ __('comparison.quick_comparison_title') }}</h2>
            <p class="comp-section-subtitle">
                {{ __('comparison.quick_comparison_subtitle') }}
            </p>
            <div class="comp-table-scroll-wrapper" id="tableScrollWrapper" role="region" aria-label="{{ __('comparison.table_aria') }}">
                <div class="comp-table-outer">
                    <table class="comp-table" aria-label="{{ __('comparison.table_aria') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('comparison.col_feature') }}</th>
                                <th scope="col" class="col-resayil">LLM Resayil</th>
                                <th scope="col">OpenRouter</th>
                                <th scope="col" style="text-align: center; width: 140px;">{{ __('comparison.col_winner') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_price_per_1k') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_price') }}</td>
                                <td>{{ __('comparison.val_openrouter_price') }}</td>
                                <td class="col-winner">
                                    <span class="comp-winner-badge" role="img" aria-label="{{ __('comparison.winner_label') }}">
                                        <svg class="comp-winner-badge-svg" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                                            <path d="M5 0.5L6.2 3.6H9.5L6.9 5.6L7.9 9L5 7.1L2.1 9L3.1 5.6L0.5 3.6H3.8L5 0.5Z" fill="currentColor"/>
                                        </svg>
                                        LLM Resayil
                                    </span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_latency') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_latency') }}</td>
                                <td>{{ __('comparison.val_openrouter_latency') }}</td>
                                <td class="col-winner">
                                    <span class="comp-winner-badge" role="img" aria-label="{{ __('comparison.winner_label') }}">
                                        <svg class="comp-winner-badge-svg" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                                            <path d="M5 0.5L6.2 3.6H9.5L6.9 5.6L7.9 9L5 7.1L2.1 9L3.1 5.6L0.5 3.6H3.8L5 0.5Z" fill="currentColor"/>
                                        </svg>
                                        LLM Resayil
                                    </span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_models') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_models') }}</td>
                                <td>{{ __('comparison.val_openrouter_models') }}</td>
                                <td class="col-winner">
                                    <span class="comp-openrouter-winner" aria-label="OpenRouter">OpenRouter</span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_setup_time') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_setup') }}</td>
                                <td>{{ __('comparison.val_openrouter_setup') }}</td>
                                <td class="col-winner">
                                    <span class="comp-winner-badge" role="img" aria-label="{{ __('comparison.winner_label') }}">
                                        <svg class="comp-winner-badge-svg" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                                            <path d="M5 0.5L6.2 3.6H9.5L6.9 5.6L7.9 9L5 7.1L2.1 9L3.1 5.6L0.5 3.6H3.8L5 0.5Z" fill="currentColor"/>
                                        </svg>
                                        LLM Resayil
                                    </span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_free_trial') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_trial') }}</td>
                                <td>{{ __('comparison.val_openrouter_trial') }}</td>
                                <td class="col-winner">
                                    <span class="comp-winner-badge" role="img" aria-label="{{ __('comparison.winner_label') }}">
                                        <svg class="comp-winner-badge-svg" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                                            <path d="M5 0.5L6.2 3.6H9.5L6.9 5.6L7.9 9L5 7.1L2.1 9L3.1 5.6L0.5 3.6H3.8L5 0.5Z" fill="currentColor"/>
                                        </svg>
                                        LLM Resayil
                                    </span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_openai_compat') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_both_compat') }}</td>
                                <td>{{ __('comparison.val_both_compat') }}</td>
                                <td class="col-winner">
                                    <span class="comp-tie-text" aria-label="{{ __('comparison.tie_label') }}">{{ __('comparison.tie_label') }}</span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_api_key_rotation') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_both_rotation') }}</td>
                                <td>{{ __('comparison.val_both_rotation') }}</td>
                                <td class="col-winner">
                                    <span class="comp-tie-text" aria-label="{{ __('comparison.tie_label') }}">{{ __('comparison.tie_label') }}</span>
                                </td>
                            </tr>
                            <tr class="comp-fade-in">
                                <td>{{ __('comparison.row_support') }}</td>
                                <td class="col-resayil">{{ __('comparison.val_resayil_support') }}</td>
                                <td>{{ __('comparison.val_openrouter_support') }}</td>
                                <td class="col-winner">
                                    <span class="comp-winner-badge" role="img" aria-label="{{ __('comparison.winner_label') }}">
                                        <svg class="comp-winner-badge-svg" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                                            <path d="M5 0.5L6.2 3.6H9.5L6.9 5.6L7.9 9L5 7.1L2.1 9L3.1 5.6L0.5 3.6H3.8L5 0.5Z" fill="currentColor"/>
                                        </svg>
                                        LLM Resayil
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════
         COST BREAKDOWN
    ═══════════════════════════════ -->
    <section aria-labelledby="cost-heading">
        <div class="comp-section-inner">
            <h2 class="comp-section-title" id="cost-heading">{{ __('comparison.cost_comparison_title') }}</h2>
            <p class="comp-section-subtitle">
                {{ __('comparison.cost_comparison_subtitle') }}
            </p>
            <div class="comp-cost-grid">
                <!-- Case 1: 10M tokens -->
                <div class="comp-cost-card comp-fade-in" aria-label="{{ __('comparison.cost_startup_title') }}">
                    <div class="comp-cost-card-header">
                        <h3 class="comp-cost-title">{{ __('comparison.cost_startup_title') }}</h3>
                        <p class="comp-cost-subtitle">{{ __('comparison.cost_startup_subtitle') }}</p>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">LLM Resayil</span>
                        <span class="comp-cost-value comp-cost-resayil">$15</span>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">OpenRouter</span>
                        <span class="comp-cost-value comp-cost-openrouter" aria-label="$45">$45</span>
                    </div>
                    <div class="comp-savings">
                        <p class="comp-savings-label">{{ __('comparison.you_save_label') }}</p>
                        <p class="comp-savings-amount" aria-label="$30">$30</p>
                    </div>
                </div>

                <!-- Case 2: 100M tokens -->
                <div class="comp-cost-card comp-fade-in" aria-label="{{ __('comparison.cost_scaleup_title') }}">
                    <div class="comp-cost-card-header">
                        <h3 class="comp-cost-title">{{ __('comparison.cost_scaleup_title') }}</h3>
                        <p class="comp-cost-subtitle">{{ __('comparison.cost_scaleup_subtitle') }}</p>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">LLM Resayil</span>
                        <span class="comp-cost-value comp-cost-resayil">$120</span>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">OpenRouter</span>
                        <span class="comp-cost-value comp-cost-openrouter" aria-label="$380">$380</span>
                    </div>
                    <div class="comp-savings">
                        <p class="comp-savings-label">{{ __('comparison.you_save_label') }}</p>
                        <p class="comp-savings-amount" aria-label="$260">$260</p>
                    </div>
                </div>

                <!-- Case 3: 1B tokens -->
                <div class="comp-cost-card comp-fade-in" aria-label="{{ __('comparison.cost_enterprise_title') }}">
                    <div class="comp-cost-card-header">
                        <h3 class="comp-cost-title">{{ __('comparison.cost_enterprise_title') }}</h3>
                        <p class="comp-cost-subtitle">{{ __('comparison.cost_enterprise_subtitle') }}</p>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">LLM Resayil</span>
                        <span class="comp-cost-value comp-cost-resayil">$950</span>
                    </div>
                    <div class="comp-cost-row">
                        <span class="comp-cost-label">OpenRouter</span>
                        <span class="comp-cost-value comp-cost-openrouter" aria-label="$3,200">$3,200</span>
                    </div>
                    <div class="comp-savings">
                        <p class="comp-savings-label">{{ __('comparison.you_save_label') }}</p>
                        <p class="comp-savings-amount" aria-label="$2,250">$2,250</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CLUSTER CALLOUT — COST/ROI -->
    <div class="comp-cluster-callout">
        <div class="comp-cluster-inner">
            <p>
                {!! __('comparison.cluster_callout', [
                    'calc_link' => '<a href="/cost-calculator">' . __('comparison.cluster_calc_link') . '</a>',
                    'alt_link'  => '<a href="/alternatives">' . __('comparison.cluster_alt_link') . '</a>',
                ]) !!}
            </p>
        </div>
    </div>

    <!-- ═══════════════════════════════
         FEATURE MATRIX
    ═══════════════════════════════ -->
    <section aria-labelledby="features-heading">
        <div class="comp-section-inner">
            <h2 class="comp-section-title" id="features-heading">{{ __('comparison.feature_matrix_title') }}</h2>
            <p class="comp-section-subtitle">
                {{ __('comparison.feature_matrix_subtitle') }}
            </p>
            <div class="comp-features-grid">
                <!-- LLM Resayil Features -->
                <div class="comp-feature-column comp-fade-in">
                    <h3 class="comp-feature-column-title resayil">
                        <!-- Gold star SVG -->
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                            <path d="M9 1L11.2 6.5H17L12.4 10L14.2 16L9 12.8L3.8 16L5.6 10L1 6.5H6.8L9 1Z" fill="#d4af37"/>
                        </svg>
                        LLM Resayil
                    </h3>
                    <ul class="comp-feature-list" aria-label="LLM Resayil features">
                        @foreach([
                            __('comparison.feature_resayil_lightweight'),
                            __('comparison.feature_resayil_pay_per_token'),
                            __('comparison.feature_resayil_multicurrency'),
                            __('comparison.feature_resayil_analytics'),
                            __('comparison.feature_resayil_api_keys'),
                            __('comparison.feature_resayil_cost_tracking'),
                            __('comparison.feature_resayil_support'),
                            __('comparison.feature_resayil_team'),
                            __('comparison.feature_resayil_rate_limiting'),
                            __('comparison.feature_resayil_zero_cost_setup'),
                        ] as $feat)
                        <li class="comp-feature-item">
                            <span class="comp-feature-icon" aria-hidden="true">
                                <svg viewBox="0 0 18 18" fill="none">
                                    <circle cx="9" cy="9" r="8.5" fill="rgba(212,175,55,0.15)" stroke="#d4af37" stroke-width="1"/>
                                    <path d="M5 9L7.5 11.5L13 6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="comp-feature-text">{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- OpenRouter Features -->
                <div class="comp-feature-column comp-fade-in">
                    <h3 class="comp-feature-column-title">OpenRouter</h3>
                    <ul class="comp-feature-list" aria-label="OpenRouter features">
                        @foreach([
                            ['check', __('comparison.feature_openrouter_model_library')],
                            ['check', __('comparison.feature_openrouter_openai_compat')],
                            ['check', __('comparison.feature_openrouter_router')],
                            ['check', __('comparison.feature_openrouter_moderation')],
                            ['x',     __('comparison.feature_openrouter_no_multicurrency')],
                            ['x',     __('comparison.feature_openrouter_no_dashboard')],
                            ['x',     __('comparison.feature_openrouter_no_priority_support')],
                            ['x',     __('comparison.feature_openrouter_no_team')],
                            ['x',     __('comparison.feature_openrouter_no_rate_limiting')],
                            ['x',     __('comparison.feature_openrouter_no_calc')],
                        ] as [$icon, $feat])
                        <li class="comp-feature-item">
                            <span class="comp-feature-icon" aria-hidden="true">
                                @if($icon === 'check')
                                <svg viewBox="0 0 18 18" fill="none">
                                    <circle cx="9" cy="9" r="8.5" fill="rgba(136,146,164,0.1)" stroke="#8892a4" stroke-width="1"/>
                                    <path d="M5 9L7.5 11.5L13 6" stroke="#8892a4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                @else
                                <svg viewBox="0 0 18 18" fill="none">
                                    <circle cx="9" cy="9" r="8.5" fill="rgba(100,100,120,0.08)" stroke="#3a3d4a" stroke-width="1"/>
                                    <path d="M6 6L12 12M12 6L6 12" stroke="#4a4f62" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                @endif
                            </span>
                            <span class="comp-feature-text" @if($icon === 'x') style="color: var(--comp-text-muted);" @endif>{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════
         FAQ SECTION
    ═══════════════════════════════ -->
    <section aria-labelledby="faq-heading">
        <div class="comp-section-inner comp-section-inner--narrow">
            <h2 class="comp-section-title" id="faq-heading">{{ __('comparison.faq_title') }}</h2>
            <p class="comp-section-subtitle">
                {{ __('comparison.faq_subtitle') }}
            </p>

            <div class="comp-faq-list" id="faqList">

                <!-- FAQ 1 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-1"
                        id="faq-trigger-1"
                    >
                        <span>{{ __('comparison.faq_q1') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-1"
                        role="region"
                        aria-labelledby="faq-trigger-1"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a1') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-2"
                        id="faq-trigger-2"
                    >
                        <span>{{ __('comparison.faq_q2') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-2"
                        role="region"
                        aria-labelledby="faq-trigger-2"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a2') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-3"
                        id="faq-trigger-3"
                    >
                        <span>{{ __('comparison.faq_q3') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-3"
                        role="region"
                        aria-labelledby="faq-trigger-3"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a3') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-4"
                        id="faq-trigger-4"
                    >
                        <span>{{ __('comparison.faq_q4') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-4"
                        role="region"
                        aria-labelledby="faq-trigger-4"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a4') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-5"
                        id="faq-trigger-5"
                    >
                        <span>{{ __('comparison.faq_q5') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-5"
                        role="region"
                        aria-labelledby="faq-trigger-5"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a5') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-6"
                        id="faq-trigger-6"
                    >
                        <span>{{ __('comparison.faq_q6') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-6"
                        role="region"
                        aria-labelledby="faq-trigger-6"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a6') }}</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="comp-faq-item">
                    <button
                        type="button"
                        class="comp-faq-trigger"
                        aria-expanded="false"
                        aria-controls="faq-answer-7"
                        id="faq-trigger-7"
                    >
                        <span>{{ __('comparison.faq_q7') }}</span>
                        <svg class="comp-faq-arrow" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div
                        class="comp-faq-panel"
                        id="faq-answer-7"
                        role="region"
                        aria-labelledby="faq-trigger-7"
                        aria-hidden="true"
                    >
                        <div class="comp-faq-answer">
                            <p>{{ __('comparison.faq_a7') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════
         FOOTER CTA
    ═══════════════════════════════ -->
    <section class="comp-footer-cta" aria-labelledby="cta-heading">
        <div class="comp-footer-cta-inner">
            <h2 class="comp-footer-title" id="cta-heading">
                {!! __('comparison.footer_cta_title', ['percent' => '30%']) !!}
            </h2>
            <p class="comp-footer-subtitle">
                {{ __('comparison.footer_cta_subtitle') }}
            </p>
            <div class="comp-footer-buttons">
                <a href="{{ route('register') }}" class="btn-primary">{{ __('comparison.footer_cta_primary') }}</a>
                <a href="{{ route('docs.index') }}" class="btn-secondary">{{ __('comparison.footer_cta_secondary') }}</a>
            </div>
            <p class="comp-footer-links">
                {!! __('comparison.footer_cta_links', [
                    'models_link' => '<a href="/features">' . __('comparison.footer_models_link') . '</a>',
                    'plans_link'  => '<a href="/billing/plans">' . __('comparison.footer_plans_link') . '</a>',
                ]) !!}
            </p>
        </div>
    </section>

</main>

<!-- ── FAQPage Structured Data ── -->
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
        "text": "Our 45+ models cover 95% of use cases (Claude 3, GPT-4, Mixtral, Llama 3, and more). If you need a model we don't offer, we can add it within 48 hours. Contact our support team with your request, and we'll evaluate and deploy it as a priority."
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
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Compare Now button smooth scroll ──
    const compareBtn = document.querySelector('button[aria-label*="Scroll down to the comparison table"]');
    if (compareBtn) {
        compareBtn.addEventListener('click', function () {
            const target = document.querySelector('.comp-table-section-anchor');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // ── 2. Table scroll fade-edge detector ──
    const scrollWrapper = document.getElementById('tableScrollWrapper');
    if (scrollWrapper) {
        function checkOverflow() {
            const hasOverflow = scrollWrapper.scrollWidth > scrollWrapper.clientWidth;
            const atRight = scrollWrapper.scrollLeft + scrollWrapper.clientWidth >= scrollWrapper.scrollWidth - 4;
            if (!hasOverflow || atRight) {
                scrollWrapper.classList.add('no-overflow');
            } else {
                scrollWrapper.classList.remove('no-overflow');
            }
        }
        checkOverflow();
        scrollWrapper.addEventListener('scroll', checkOverflow, { passive: true });
        window.addEventListener('resize', checkOverflow, { passive: true });
    }

    // ── 3. FAQ accordion — semantic <button> with aria-expanded / aria-hidden ──
    const faqTriggers = document.querySelectorAll('.comp-faq-trigger');
    faqTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const panelId = trigger.getAttribute('aria-controls');
            const panel = document.getElementById(panelId);
            const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

            // Toggle this item
            trigger.setAttribute('aria-expanded', String(!isExpanded));
            panel.setAttribute('aria-hidden', String(isExpanded));

            // Optionally close all others (accordion behaviour)
            faqTriggers.forEach(function (otherTrigger) {
                if (otherTrigger !== trigger) {
                    const otherId = otherTrigger.getAttribute('aria-controls');
                    const otherPanel = document.getElementById(otherId);
                    otherTrigger.setAttribute('aria-expanded', 'false');
                    if (otherPanel) {
                        otherPanel.setAttribute('aria-hidden', 'true');
                    }
                }
            });
        });
    });

    // ── 4. Intersection observer — fade-in on scroll ──
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReduced) {
        const fadeEls = document.querySelectorAll('.comp-fade-in');
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        fadeEls.forEach(function (el, i) {
            // Stagger table rows
            el.style.transitionDelay = (i * 50) + 'ms';
            observer.observe(el);
        });
    } else {
        // Respect reduced motion — show everything immediately
        document.querySelectorAll('.comp-fade-in').forEach(function (el) {
            el.classList.add('visible');
        });
    }

});
</script>

@endsection
