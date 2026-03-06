@extends('layouts.app')

@section('title', 'LLM Cost Calculator — Compare API Pricing')

@push('styles')
<style>
    /* ── CSS Custom Properties ── */
    :root {
        --calc-gold:        #d4af37;
        --calc-gold-dim:    rgba(212,175,55,0.55);
        --calc-gold-glow:   rgba(212,175,55,0.20);
        --calc-gold-faint:  rgba(212,175,55,0.08);
        --calc-bg:          #0f1115;
        --calc-card:        #13161d;
        --calc-secondary:   #1a1e28;
        --calc-border:      rgba(255,255,255,0.08);
        --calc-border-gold: rgba(212,175,55,0.30);
        --calc-text:        #e8eaf0;
        --calc-text-sub:    #b0b8c8;
        --calc-text-muted:  #8a92a0;
        --calc-green:       #22c55e;
        --calc-green-bg:    rgba(34,197,94,0.12);
        --calc-green-border:rgba(34,197,94,0.30);
        --calc-radius-sm:   8px;
        --calc-radius-md:   12px;
        --calc-radius-lg:   16px;
        --calc-radius-xl:   20px;
        --calc-shadow-gold: 0 0 24px rgba(212,175,55,0.18);
        --calc-shadow-card: 0 4px 24px rgba(0,0,0,0.35);
    }

    /* ── Motion preference ── */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* ── Base reset for this page ── */
    main { padding: 0; max-width: 100%; margin: 0; }

    /* ══════════════════════════════════════════════════
       HERO SECTION
    ══════════════════════════════════════════════════ */
    .calc-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(
            160deg,
            rgba(212,175,55,0.07) 0%,
            rgba(212,175,55,0.02) 50%,
            transparent 100%
        );
        border-bottom: 1px solid var(--calc-border);
        padding: 5rem 2rem 4rem;
        text-align: center;
    }

    /* Decorative radial glow behind heading */
    .calc-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 300px;
        background: radial-gradient(ellipse, rgba(212,175,55,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .calc-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--calc-green-bg);
        border: 1px solid var(--calc-green-border);
        color: #86efac;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }

    .calc-hero-badge-dot {
        width: 7px;
        height: 7px;
        background: var(--calc-green);
        border-radius: 50%;
        animation: heroPulse 2s ease-in-out infinite;
    }

    @keyframes heroPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(0.8); }
    }

    .calc-hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        color: var(--calc-text);
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .calc-hero .hero-accent {
        background: linear-gradient(135deg, var(--calc-gold) 0%, #f0d060 50%, var(--calc-gold) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmerText 4s linear infinite;
    }

    @keyframes shimmerText {
        0%   { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    .calc-hero p {
        font-size: 1.1rem;
        color: var(--calc-text-sub);
        max-width: 640px;
        margin: 0 auto 2rem;
        line-height: 1.65;
    }

    /* Hero stats row */
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .hero-stat {
        text-align: center;
    }

    .hero-stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--calc-gold);
        line-height: 1.2;
    }

    .hero-stat-label {
        display: block;
        font-size: 0.78rem;
        color: var(--calc-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-top: 0.2rem;
    }

    .hero-stat-divider {
        width: 1px;
        background: var(--calc-border);
        align-self: stretch;
    }

    /* ══════════════════════════════════════════════════
       MAIN WRAPPER
    ══════════════════════════════════════════════════ */
    .calc-wrapper {
        max-width: 1300px;
        margin: 0 auto;
        padding: 3rem 2rem 3rem;
    }

    /* ══════════════════════════════════════════════════
       CALCULATOR GRID
    ══════════════════════════════════════════════════ */
    .calc-grid {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 2.5rem;
        margin-bottom: 3.5rem;
        align-items: start;
    }

    /* ══════════════════════════════════════════════════
       LEFT: INPUT CARD
    ══════════════════════════════════════════════════ */
    .calc-inputs-card {
        background: var(--calc-card);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-lg);
        padding: 2rem;
        box-shadow: var(--calc-shadow-card);
        position: sticky;
        top: 1.5rem;
    }

    .card-heading {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--calc-text);
        margin-bottom: 1.75rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--calc-border);
    }

    .card-heading-icon {
        width: 32px;
        height: 32px;
        background: var(--calc-gold-faint);
        border: 1px solid var(--calc-border-gold);
        border-radius: var(--calc-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-heading-icon svg {
        width: 16px;
        height: 16px;
        color: var(--calc-gold);
    }

    /* ── Form Groups ── */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        margin-bottom: 1.5rem;
    }

    .form-group:last-of-type {
        margin-bottom: 0;
    }

    .form-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--calc-text-sub);
        letter-spacing: 0.02em;
    }

    .form-label-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--calc-gold);
        font-variant-numeric: tabular-nums;
        background: var(--calc-gold-faint);
        border: 1px solid var(--calc-border-gold);
        padding: 0.15rem 0.55rem;
        border-radius: 5px;
        letter-spacing: 0;
    }

    /* ── Slider Track ── */
    .slider-track-wrapper {
        position: relative;
        padding: 0.25rem 0;
    }

    .slider-track-bg {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--calc-secondary);
        border-radius: 3px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .slider-track-fill {
        position: absolute;
        top: 50%;
        left: 0;
        height: 6px;
        background: linear-gradient(to right, var(--calc-gold), #f0d060);
        border-radius: 3px;
        transform: translateY(-50%);
        pointer-events: none;
        transition: width 0.08s ease;
        width: 0%;
    }

    /* ── Range Input ── */
    .slider-input {
        position: relative;
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background: transparent;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
        cursor: pointer;
        z-index: 1;
        /* Transparent track — visual track done via overlay divs */
    }

    .slider-input::-webkit-slider-runnable-track {
        height: 6px;
        background: transparent;
        border-radius: 3px;
    }

    .slider-input::-moz-range-track {
        height: 6px;
        background: transparent;
        border-radius: 3px;
    }

    /* Thumb — Desktop 24px, larger click area via padding trick */
    .slider-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--calc-gold);
        cursor: pointer;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.25), 0 2px 8px rgba(0,0,0,0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        border: 2px solid #0f1115;
        margin-top: -9px;
    }

    .slider-input::-webkit-slider-thumb:hover {
        transform: scale(1.18);
        box-shadow: 0 0 0 6px rgba(212,175,55,0.18), 0 4px 12px rgba(0,0,0,0.4);
    }

    .slider-input::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--calc-gold);
        cursor: pointer;
        border: 2px solid #0f1115;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.25), 0 2px 8px rgba(0,0,0,0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .slider-input::-moz-range-thumb:hover {
        transform: scale(1.18);
    }

    /* Focus ring on slider */
    .slider-input:focus-visible {
        outline: none;
    }

    .slider-input:focus-visible::-webkit-slider-thumb {
        box-shadow: 0 0 0 3px #0f1115, 0 0 0 5px var(--calc-gold);
    }

    .slider-input:focus-visible::-moz-range-thumb {
        box-shadow: 0 0 0 3px #0f1115, 0 0 0 5px var(--calc-gold);
    }

    /* Mobile: larger thumb for touch */
    @media (max-width: 768px) {
        .slider-input::-webkit-slider-thumb {
            width: 30px;
            height: 30px;
            margin-top: -12px;
        }
        .slider-input::-moz-range-thumb {
            width: 30px;
            height: 30px;
        }
    }

    /* ── Slider value display ── */
    .slider-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--calc-text-sub);
        background: var(--calc-secondary);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-sm);
        padding: 0.55rem 1rem;
        margin-top: 0.75rem;
        font-variant-numeric: tabular-nums;
    }

    .slider-display-num {
        color: var(--calc-gold);
        font-weight: 700;
    }

    .slider-hint {
        font-size: 0.78rem;
        color: var(--calc-text-muted);
        text-align: center;
        margin-top: 0.4rem;
    }

    /* ── Select / Number Inputs ── */
    .form-input {
        width: 100%;
        background: var(--calc-secondary);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-sm);
        padding: 0.75rem 1rem;
        color: var(--calc-text);
        font-size: 0.95rem;
        font-family: inherit;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        min-height: 44px;
        appearance: auto;
    }

    .form-input:hover {
        border-color: var(--calc-gold-dim);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--calc-gold);
        box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
    }

    .form-input:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: 2px;
    }

    /* ── Calculate Button ── */
    .btn-calculate {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        min-height: 52px;
        padding: 0.9rem 1.5rem;
        margin-top: 1.5rem;
        background: linear-gradient(135deg, var(--calc-gold) 0%, #f0d060 100%);
        color: #0a0d14;
        border: none;
        border-radius: var(--calc-radius-md);
        font-weight: 700;
        font-size: 1rem;
        font-family: inherit;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        letter-spacing: 0.01em;
        position: relative;
        overflow: hidden;
    }

    .btn-calculate::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(212,175,55,0.38);
    }

    .btn-calculate:hover::after {
        opacity: 1;
    }

    .btn-calculate:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(212,175,55,0.25);
    }

    .btn-calculate:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: 3px;
        box-shadow: 0 0 0 4px rgba(212,175,55,0.15);
    }

    /* ══════════════════════════════════════════════════
       RIGHT: RESULTS CARD
    ══════════════════════════════════════════════════ */
    .calc-results {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .results-card {
        background: var(--calc-card);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-lg);
        padding: 2rem;
        box-shadow: var(--calc-shadow-card);
    }

    /* ── Result Items grid ── */
    .result-items-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: var(--calc-secondary);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-md);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        opacity: 0;
        animation: resultReveal 0.4s ease-out forwards;
    }

    .result-item:nth-child(1) { animation-delay: 0.05s; }
    .result-item:nth-child(2) { animation-delay: 0.12s; }
    .result-item:nth-child(3) { animation-delay: 0.19s; }

    @keyframes resultReveal {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* "Our platform" result item — highlighted */
    .result-item.result-featured {
        border-color: var(--calc-border-gold);
        background: linear-gradient(135deg, rgba(212,175,55,0.07) 0%, rgba(212,175,55,0.03) 100%);
        box-shadow: 0 0 0 1px rgba(212,175,55,0.12), var(--calc-shadow-gold);
    }

    .result-item.result-featured:hover {
        border-color: rgba(212,175,55,0.55);
    }

    .result-item:hover {
        border-color: var(--calc-gold-dim);
    }

    .result-item-left {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        min-width: 0;
    }

    .result-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--calc-gold);
        background: var(--calc-gold-faint);
        border: 1px solid var(--calc-border-gold);
        padding: 0.1rem 0.45rem;
        border-radius: 4px;
        width: fit-content;
        margin-bottom: 0.1rem;
    }

    .result-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--calc-text-sub);
        white-space: nowrap;
    }

    .result-label-sub {
        font-size: 0.75rem;
        color: var(--calc-text-muted);
        margin-top: 0.1rem;
    }

    .result-value {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--calc-gold);
        letter-spacing: -0.03em;
        font-variant-numeric: tabular-nums;
        line-height: 1;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .result-item:not(.result-featured) .result-value {
        color: var(--calc-text);
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* ── Savings Summary Badge ── */
    .savings-summary {
        background: linear-gradient(135deg, rgba(212,175,55,0.12) 0%, rgba(212,175,55,0.06) 100%);
        border: 1px solid rgba(212,175,55,0.35);
        border-radius: var(--calc-radius-md);
        padding: 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .savings-summary::before {
        content: '';
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        width: 200px;
        height: 80px;
        background: radial-gradient(ellipse, rgba(212,175,55,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .savings-summary-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--calc-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.5rem;
    }

    .savings-main {
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--calc-gold);
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 0.35rem;
        font-variant-numeric: tabular-nums;
    }

    .savings-sub {
        font-size: 0.82rem;
        color: var(--calc-text-muted);
    }

    /* ── Percentage comparison row ── */
    .savings-percents {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .savings-percent-item {
        background: var(--calc-secondary);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-sm);
        padding: 0.75rem 1rem;
        text-align: center;
    }

    .savings-percent-item-label {
        font-size: 0.72rem;
        color: var(--calc-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.3rem;
    }

    .savings-percent-item-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--calc-green);
        font-variant-numeric: tabular-nums;
    }

    /* ── Animation for value updates ── */
    @keyframes valueSlideUp {
        from { opacity: 0; transform: translateY(6px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .value-updated {
        animation: valueSlideUp 0.28s ease-out forwards;
    }

    /* ══════════════════════════════════════════════════
       INFO / HOW WE CALCULATE
    ══════════════════════════════════════════════════ */
    .info-section {
        background: var(--calc-gold-faint);
        border: 1px solid rgba(212,175,55,0.16);
        border-radius: var(--calc-radius-md);
        padding: 2rem;
        margin-bottom: 3rem;
    }

    .info-section-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1rem;
    }

    .info-section-header svg {
        width: 18px;
        height: 18px;
        color: var(--calc-gold);
        flex-shrink: 0;
    }

    .info-section-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--calc-text);
        margin: 0;
    }

    .pricing-rates {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .pricing-rate-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid var(--calc-border);
        background: var(--calc-secondary);
        color: var(--calc-text-sub);
    }

    .pricing-rate-pill.pill-featured {
        background: var(--calc-gold-faint);
        border-color: var(--calc-border-gold);
        color: var(--calc-gold);
    }

    .pricing-rate-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.7;
    }

    .info-section-note {
        font-size: 0.82rem;
        color: var(--calc-text-muted);
        line-height: 1.6;
        margin-top: 0.75rem;
    }

    .info-section-links {
        margin-top: 1.25rem;
        font-size: 0.9rem;
        color: var(--calc-text-sub);
        line-height: 1.7;
    }

    .info-link {
        color: var(--calc-gold);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: opacity 0.15s ease;
    }

    .info-link:hover {
        opacity: 0.75;
    }

    .info-link:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: 2px;
        border-radius: 2px;
    }

    /* ══════════════════════════════════════════════════
       FAQ SECTION
    ══════════════════════════════════════════════════ */
    .faq-section {
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--calc-text);
        text-align: center;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }

    .section-subtitle {
        text-align: center;
        font-size: 0.95rem;
        color: var(--calc-text-muted);
        margin-bottom: 2.5rem;
        max-width: 480px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 1rem;
    }

    .faq-item {
        background: var(--calc-card);
        border: 1px solid var(--calc-border);
        border-radius: var(--calc-radius-md);
        overflow: hidden;
        transition: border-color 0.2s ease;
    }

    .faq-item:hover {
        border-color: var(--calc-gold-dim);
    }

    .faq-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
        padding: 1.25rem 1.5rem;
        background: transparent;
        border: none;
        color: var(--calc-text);
        font-size: 0.95rem;
        font-weight: 600;
        font-family: inherit;
        text-align: start;
        cursor: pointer;
        transition: background 0.15s ease;
        min-height: 56px;
    }

    .faq-trigger:hover {
        background: rgba(255,255,255,0.03);
    }

    .faq-trigger:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: -2px;
        border-radius: var(--calc-radius-md);
    }

    .faq-chevron {
        width: 18px;
        height: 18px;
        color: var(--calc-gold);
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }

    .faq-item[data-open="true"] .faq-chevron {
        transform: rotate(180deg);
    }

    .faq-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .faq-item[data-open="true"] .faq-body {
        max-height: 400px;
    }

    .faq-answer {
        padding: 0 1.5rem 1.25rem;
        font-size: 0.9rem;
        color: var(--calc-text-sub);
        line-height: 1.65;
        border-top: 1px solid var(--calc-border);
        padding-top: 1rem;
    }

    /* ══════════════════════════════════════════════════
       CTA SECTION
    ══════════════════════════════════════════════════ */
    .cta-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.04) 100%);
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: var(--calc-radius-xl);
        padding: 3.5rem 2rem;
        text-align: center;
        margin-bottom: 4rem;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle, rgba(212,175,55,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .cta-section h2 {
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 800;
        color: var(--calc-text);
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .cta-section p {
        font-size: 1rem;
        color: var(--calc-text-sub);
        max-width: 520px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.95rem 2rem;
        min-height: 52px;
        background: linear-gradient(135deg, var(--calc-gold) 0%, #f0d060 100%);
        color: #0a0d14;
        border: none;
        border-radius: var(--calc-radius-md);
        font-weight: 700;
        font-size: 1rem;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        letter-spacing: 0.01em;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(212,175,55,0.40);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-primary:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: 3px;
        box-shadow: 0 0 0 4px rgba(212,175,55,0.15);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.95rem 2rem;
        min-height: 52px;
        background: transparent;
        color: var(--calc-gold);
        border: 1px solid rgba(212,175,55,0.40);
        border-radius: var(--calc-radius-md);
        font-weight: 600;
        font-size: 1rem;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
        border-color: rgba(212,175,55,0.65);
        transform: translateY(-1px);
    }

    .btn-secondary:focus-visible {
        outline: 2px solid var(--calc-gold);
        outline-offset: 3px;
    }

    .cta-footer-note {
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: var(--calc-text-sub);
        line-height: 1.6;
    }

    /* ══════════════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .calc-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .calc-inputs-card {
            position: static;
        }

        .hero-stat-divider {
            display: none;
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .calc-hero {
            padding: 3.5rem 1.5rem 3rem;
        }

        .calc-hero h1 {
            font-size: 1.75rem;
        }

        .calc-hero p {
            font-size: 0.97rem;
        }

        .hero-stats {
            gap: 1.5rem;
        }

        .calc-wrapper {
            padding: 2rem 1.25rem 2rem;
        }

        .calc-inputs-card,
        .results-card {
            padding: 1.5rem;
        }

        .result-value {
            font-size: 1.5rem;
        }

        .result-item:not(.result-featured) .result-value {
            font-size: 1.25rem;
        }

        .savings-main {
            font-size: 2rem;
        }

        .form-input {
            font-size: 16px; /* Prevent iOS auto-zoom */
        }

        .cta-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-primary,
        .btn-secondary {
            justify-content: center;
        }

        .savings-percents {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .calc-hero h1 {
            font-size: 1.45rem;
        }

        .hero-stats {
            gap: 1rem;
        }

        .hero-stat-value {
            font-size: 1.2rem;
        }

        .result-value {
            font-size: 1.3rem;
        }

        .savings-main {
            font-size: 1.7rem;
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }

        .pricing-rates {
            flex-direction: column;
        }

        .cta-section {
            padding: 2.5rem 1.25rem;
        }
    }

    /* ── RTL support (Arabic) ── */
    [dir="rtl"] .form-label {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .slider-track-fill {
        right: 0;
        left: auto;
        background: linear-gradient(to left, var(--calc-gold), #f0d060);
    }

    [dir="rtl"] .result-item {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .card-heading {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .savings-percents {
        direction: rtl;
    }

    [dir="rtl"] .faq-trigger {
        text-align: end;
        flex-direction: row-reverse;
    }

    [dir="rtl"] .pricing-rates {
        flex-direction: row-reverse;
    }
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section class="calc-hero" aria-labelledby="calc-hero-heading">
    <div class="calc-hero-badge" aria-hidden="true">
        <span class="calc-hero-badge-dot"></span>
        Live Price Comparison
    </div>

    <h1 id="calc-hero-heading">
        LLM API <span class="hero-accent">Cost Calculator</span>
    </h1>

    <p>See exactly how much you'll save with LLM Resayil. Compare real-time pricing against OpenAI and OpenRouter — no sign-up required.</p>

    <div class="hero-stats" role="list" aria-label="Key pricing facts">
        <div class="hero-stat" role="listitem">
            <span class="hero-stat-value">15×</span>
            <span class="hero-stat-label">Cheaper than OpenAI</span>
        </div>
        <div class="hero-stat-divider" aria-hidden="true"></div>
        <div class="hero-stat" role="listitem">
            <span class="hero-stat-value">8×</span>
            <span class="hero-stat-label">Cheaper than OpenRouter</span>
        </div>
        <div class="hero-stat-divider" aria-hidden="true"></div>
        <div class="hero-stat" role="listitem">
            <span class="hero-stat-value">$0</span>
            <span class="hero-stat-label">Monthly Minimum</span>
        </div>
    </div>
</section>

{{-- ══ MAIN WRAPPER ══ --}}
<div class="calc-wrapper">

    {{-- ── Calculator Grid ── --}}
    <div class="calc-grid">

        {{-- ── LEFT: Inputs ── --}}
        <div>
            <div class="calc-inputs-card">
                <h2 class="card-heading">
                    <span class="card-heading-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/>
                        </svg>
                    </span>
                    Configure Your Usage
                </h2>

                {{-- Token Slider --}}
                <div class="form-group">
                    <label class="form-label" for="tokens-slider">
                        <span>Monthly Token Usage</span>
                        <span class="form-label-value" id="tokens-label-value" aria-live="polite" aria-atomic="true">1M</span>
                    </label>

                    <div class="slider-track-wrapper">
                        <div class="slider-track-bg" aria-hidden="true"></div>
                        <div class="slider-track-fill" id="slider-track-fill" aria-hidden="true"></div>
                        <input
                            type="range"
                            id="tokens-slider"
                            class="slider-input"
                            min="1000000"
                            max="10000000000"
                            step="1000000"
                            value="1000000"
                            aria-label="Monthly token usage"
                            aria-describedby="slider-hint"
                            aria-valuemin="1000000"
                            aria-valuemax="10000000000"
                            aria-valuenow="1000000"
                            aria-valuetext="1 million tokens per month"
                        >
                    </div>

                    <div class="slider-display" aria-hidden="true">
                        <span class="slider-display-num" id="slider-display-num">1,000,000</span>
                        <span>tokens / month</span>
                    </div>
                    <div id="slider-hint" class="slider-hint">
                        Drag or use arrow keys (1M step) — Page Up/Down for 10M steps
                    </div>
                </div>

                {{-- Direct numeric input --}}
                <div class="form-group">
                    <label class="form-label" for="tokens-input">
                        Or enter token count directly:
                    </label>
                    <input
                        type="number"
                        id="tokens-input"
                        class="form-input"
                        min="1000000"
                        max="10000000000"
                        step="1000000"
                        value="1000000"
                        placeholder="e.g. 5000000"
                        aria-label="Token count (numeric input)"
                    >
                </div>

                {{-- Model Tier --}}
                <div class="form-group">
                    <label class="form-label" for="model-tier">Model Tier</label>
                    <select class="form-input" id="model-tier" aria-describedby="model-tier-hint">
                        <option value="small">Small — e.g. Mistral 7B</option>
                        <option value="medium" selected>Medium — e.g. Llama 70B</option>
                        <option value="large">Large — e.g. GPT-4 Equivalent</option>
                    </select>
                    <span id="model-tier-hint" class="slider-hint">Larger tiers cost more per token across all providers</span>
                </div>

                {{-- Workload Type --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="workload-type">Workload Type</label>
                    <select class="form-input" id="workload-type">
                        <option value="production" selected>Production</option>
                        <option value="development">Development</option>
                        <option value="batch">Batch Processing</option>
                    </select>
                </div>

                <button class="btn-calculate" id="calc-btn" type="button" aria-label="Recalculate costs with current settings">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Calculate My Savings
                </button>
            </div>
        </div>

        {{-- ── RIGHT: Results ── --}}
        <div
            class="calc-results"
            role="region"
            aria-label="Cost comparison results"
            aria-live="polite"
            aria-atomic="false"
        >
            <div class="results-card">
                <h2 class="card-heading">
                    <span class="card-heading-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </span>
                    Monthly Cost Comparison
                </h2>

                {{-- Result items --}}
                <div class="result-items-grid" role="list">

                    {{-- Our platform — featured --}}
                    <div class="result-item result-featured" role="listitem" aria-label="LLM Resayil monthly cost">
                        <div class="result-item-left">
                            <span class="result-badge" aria-label="Best value">
                                <svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Best Value
                            </span>
                            <span class="result-label">LLM Resayil</span>
                            <span class="result-label-sub">Our platform</span>
                        </div>
                        <div
                            class="result-value"
                            id="result-llm"
                            aria-label="LLM Resayil monthly cost"
                            aria-valuenow="0"
                        >$0.00</div>
                    </div>

                    {{-- OpenAI --}}
                    <div class="result-item" role="listitem" aria-label="OpenAI monthly cost">
                        <div class="result-item-left">
                            <span class="result-label">OpenAI</span>
                            <span class="result-label-sub">GPT-4 API</span>
                        </div>
                        <div
                            class="result-value"
                            id="result-openai"
                            aria-label="OpenAI monthly cost"
                            aria-valuenow="0"
                        >$0.00</div>
                    </div>

                    {{-- OpenRouter --}}
                    <div class="result-item" role="listitem" aria-label="OpenRouter monthly cost">
                        <div class="result-item-left">
                            <span class="result-label">OpenRouter</span>
                            <span class="result-label-sub">Aggregated routing</span>
                        </div>
                        <div
                            class="result-value"
                            id="result-openrouter"
                            aria-label="OpenRouter monthly cost"
                            aria-valuenow="0"
                        >$0.00</div>
                    </div>
                </div>

                {{-- Savings badge --}}
                <div class="savings-summary" aria-label="Total savings summary">
                    <div class="savings-summary-label">Your monthly savings vs OpenAI</div>
                    <div
                        class="savings-main"
                        id="savings-amount"
                        aria-label="Monthly savings amount"
                        aria-live="polite"
                        aria-atomic="true"
                    >$0.00</div>
                    <div class="savings-sub" id="savings-amount-sub">saved every month</div>

                    <div class="savings-percents">
                        <div class="savings-percent-item" role="group" aria-label="Savings percentage vs OpenAI">
                            <div class="savings-percent-item-label">vs OpenAI</div>
                            <div
                                class="savings-percent-item-value"
                                id="savings-percent-openai"
                                aria-label="Savings percentage vs OpenAI"
                                aria-live="polite"
                                aria-atomic="true"
                            >0%</div>
                        </div>
                        <div class="savings-percent-item" role="group" aria-label="Savings percentage vs OpenRouter">
                            <div class="savings-percent-item-label">vs OpenRouter</div>
                            <div
                                class="savings-percent-item-value"
                                id="savings-percent-router"
                                aria-label="Savings percentage vs OpenRouter"
                                aria-live="polite"
                                aria-atomic="true"
                            >0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── How We Calculate ── --}}
    <div class="info-section" role="complementary" aria-label="Pricing methodology">
        <div class="info-section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <h3>How We Calculate Your Costs</h3>
        </div>

        <div class="pricing-rates" role="list" aria-label="Pricing rates used">
            <div class="pricing-rate-pill pill-featured" role="listitem">
                <span class="pricing-rate-pill-dot" aria-hidden="true"></span>
                LLM Resayil: $0.001 / 1K tokens
            </div>
            <div class="pricing-rate-pill" role="listitem">
                <span class="pricing-rate-pill-dot" aria-hidden="true"></span>
                OpenAI: $0.015 / 1K tokens
            </div>
            <div class="pricing-rate-pill" role="listitem">
                <span class="pricing-rate-pill-dot" aria-hidden="true"></span>
                OpenRouter: $0.008 / 1K tokens
            </div>
        </div>

        <p class="info-section-note">
            Calculations use current market rates and are updated regularly. Actual costs may vary by model selection, additional features, and volume agreements. All figures assume standard pricing without custom contracts.
        </p>

        <p class="info-section-links">
            See a <a href="/comparison" class="info-link">detailed comparison with OpenRouter</a>, or explore <a href="/alternatives" class="info-link">alternative LLM APIs</a>.
        </p>
    </div>

    {{-- ── FAQ ── --}}
    <section class="faq-section" aria-labelledby="faq-heading">
        <h2 class="section-title" id="faq-heading">Frequently Asked Questions</h2>
        <p class="section-subtitle">Everything you need to know about our pricing and this calculator.</p>

        <div class="faq-grid">

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-accuracy"
                    id="faq-btn-accuracy"
                >
                    <span>How accurate is this calculator?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-accuracy" role="region" aria-labelledby="faq-btn-accuracy" hidden>
                    <div class="faq-answer">
                        Our calculator uses current market pricing rates and is updated regularly. Results are accurate for estimation purposes. For production environments with volume discounts or custom agreements, please contact our sales team for a personalized quote.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-cheaper"
                    id="faq-btn-cheaper"
                >
                    <span>Why is LLM Resayil cheaper?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-cheaper" role="region" aria-labelledby="faq-btn-cheaper" hidden>
                    <div class="faq-answer">
                        We optimize infrastructure costs and pass savings to users. Our pay-per-token model eliminates monthly minimums. No hidden fees or overages. Plus, access to open-source models with commercial licenses removes vendor lock-in premiums charged by competitors.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-production"
                    id="faq-btn-production"
                >
                    <span>Can I use this for production estimates?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-production" role="region" aria-labelledby="faq-btn-production" hidden>
                    <div class="faq-answer">
                        Yes, this calculator is designed for production cost estimates. All pricing is based on current published rates. For guaranteed pricing, SLAs, or enterprise agreements, contact our sales team at support@resayil.io with your usage profile.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-tiers"
                    id="faq-btn-tiers"
                >
                    <span>Do pricing tiers affect the calculation?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-tiers" role="region" aria-labelledby="faq-btn-tiers" hidden>
                    <div class="faq-answer">
                        Model tier selection affects pricing rates. Larger models (like GPT-4 equivalents) are more expensive per token than smaller models (like Mistral 7B). The calculator uses representative pricing for each tier. See our detailed pricing page for model-specific rates.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-discount"
                    id="faq-btn-discount"
                >
                    <span>Are there volume discounts?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-discount" role="region" aria-labelledby="faq-btn-discount" hidden>
                    <div class="faq-answer">
                        Yes! Enterprise customers with high monthly volumes qualify for volume discounts. Contact our sales team to discuss your specific use case and get a custom pricing proposal tailored to your needs.
                    </div>
                </div>
            </div>

            <div class="faq-item" data-open="false">
                <button
                    class="faq-trigger"
                    aria-expanded="false"
                    aria-controls="faq-body-change"
                    id="faq-btn-change"
                >
                    <span>How often do prices change?</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="faq-body" id="faq-body-change" role="region" aria-labelledby="faq-btn-change" hidden>
                    <div class="faq-answer">
                        We update pricing quarterly to reflect market conditions. Existing users are grandfathered into their current rates for 12 months. Price increases (if any) are announced 30 days in advance via email and dashboard notifications.
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── CTA ── --}}
    <div class="cta-section" role="complementary" aria-label="Call to action">
        <h2>Ready to Start Saving?</h2>
        <p>Join thousands of developers who've switched to LLM Resayil and dramatically cut their API costs.</p>

        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                Start Free — 1,000 Credits
            </a>
            <a href="/billing/plans" class="btn-secondary">
                View Pricing Plans
            </a>
        </div>

        <p class="cta-footer-note">
            Check our <a href="/pricing" class="info-link">detailed pricing page</a>, or see how we <a href="/comparison" class="info-link">compare to competitors</a>.
        </p>
    </div>

</div>{{-- /.calc-wrapper --}}

{{-- FAQPage Schema for SEO --}}
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
                "text": "Yes! Enterprise customers with high monthly volumes qualify for volume discounts. Contact our sales team to discuss your specific use case and get a custom pricing proposal tailored to your needs."
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
(function () {
    'use strict';

    // ── Pricing constants (USD per 1K tokens) ──────────────────────────────
    const PRICING = {
        llmResayil: { small: 0.0005, medium: 0.001,  large: 0.0015 },
        openAI:     { small: 0.015,  medium: 0.015,  large: 0.030  },
        openRouter: { small: 0.005,  medium: 0.008,  large: 0.015  }
    };

    // ── DOM refs ───────────────────────────────────────────────────────────
    const $ = id => document.getElementById(id);

    const el = {
        slider:              $('tokens-slider'),
        tokensInput:         $('tokens-input'),
        tokenLabelValue:     $('tokens-label-value'),
        sliderDisplayNum:    $('slider-display-num'),
        sliderTrackFill:     $('slider-track-fill'),
        modelTier:           $('model-tier'),
        workloadType:        $('workload-type'),
        resultLLM:           $('result-llm'),
        resultOpenAI:        $('result-openai'),
        resultOpenRouter:    $('result-openrouter'),
        savingsAmount:       $('savings-amount'),
        savingsAmountSub:    $('savings-amount-sub'),
        savingsPercentOAI:   $('savings-percent-openai'),
        savingsPercentRouter:$('savings-percent-router'),
        calcBtn:             $('calc-btn'),
    };

    // ── Formatters ─────────────────────────────────────────────────────────
    function fmtShort(n) {
        if (n >= 1e9)  return (n / 1e9).toFixed(1).replace(/\.0$/, '') + 'B';
        if (n >= 1e6)  return (n / 1e6).toFixed(1).replace(/\.0$/, '') + 'M';
        if (n >= 1e3)  return (n / 1e3).toFixed(1).replace(/\.0$/, '') + 'K';
        return n.toString();
    }

    function fmtCurrency(n) {
        if (Math.abs(n) >= 1e6) return '$' + (n / 1e6).toFixed(2) + 'M';
        if (Math.abs(n) >= 1e3) return '$' + (n / 1e3).toFixed(2) + 'K';
        return '$' + n.toFixed(2);
    }

    // ── Slider track fill ──────────────────────────────────────────────────
    function updateTrackFill(value) {
        const min = Number(el.slider.min);
        const max = Number(el.slider.max);
        const pct = ((value - min) / (max - min)) * 100;
        el.sliderTrackFill.style.width = pct + '%';
    }

    // ── Animate value update ───────────────────────────────────────────────
    function animateValue(element, newText) {
        element.classList.remove('value-updated');
        // Force reflow to restart animation
        void element.offsetWidth;
        element.textContent = newText;
        element.classList.add('value-updated');
    }

    // ── Sync slider + number input ─────────────────────────────────────────
    function syncFromSlider(value) {
        const v = Number(value);
        el.tokensInput.value           = v;
        el.tokenLabelValue.textContent = fmtShort(v);
        el.sliderDisplayNum.textContent = v.toLocaleString();
        el.slider.setAttribute('aria-valuenow', v);
        el.slider.setAttribute('aria-valuetext', fmtShort(v) + ' tokens per month');
        updateTrackFill(v);
    }

    // ── Main calculation ───────────────────────────────────────────────────
    function calculateCosts() {
        const tokens = Number(el.slider.value);
        const tier   = el.modelTier.value;

        const llmCost    = (tokens / 1000) * PRICING.llmResayil[tier];
        const openaiCost = (tokens / 1000) * PRICING.openAI[tier];
        const routerCost = (tokens / 1000) * PRICING.openRouter[tier];

        const savingsOAI    = openaiCost - llmCost;
        const savingsRouter = routerCost - llmCost;
        const pctOAI        = openaiCost > 0 ? ((savingsOAI / openaiCost) * 100).toFixed(1) : '0.0';
        const pctRouter     = routerCost > 0 ? ((savingsRouter / routerCost) * 100).toFixed(1) : '0.0';

        // Update values with animation
        animateValue(el.resultLLM,           fmtCurrency(llmCost));
        animateValue(el.resultOpenAI,         fmtCurrency(openaiCost));
        animateValue(el.resultOpenRouter,     fmtCurrency(routerCost));
        animateValue(el.savingsAmount,        fmtCurrency(savingsOAI));
        animateValue(el.savingsPercentOAI,    pctOAI + '%');
        animateValue(el.savingsPercentRouter, pctRouter + '%');

        // ARIA value updates
        el.resultLLM.setAttribute('aria-valuenow',           llmCost.toFixed(2));
        el.resultOpenAI.setAttribute('aria-valuenow',        openaiCost.toFixed(2));
        el.resultOpenRouter.setAttribute('aria-valuenow',    routerCost.toFixed(2));
        el.savingsAmount.setAttribute('aria-valuenow',       savingsOAI.toFixed(2));
        el.savingsPercentOAI.setAttribute('aria-valuenow',   pctOAI);
        el.savingsPercentRouter.setAttribute('aria-valuenow',pctRouter);

        // Sub-label context
        el.savingsAmountSub.textContent = savingsOAI > 0
            ? 'saved every month vs OpenAI'
            : 'no savings at this usage level';
    }

    // ── Event listeners ────────────────────────────────────────────────────

    // Slider drag
    el.slider.addEventListener('input', function () {
        syncFromSlider(this.value);
        calculateCosts();
    });

    // Keyboard navigation for slider
    el.slider.addEventListener('keydown', function (e) {
        const step = Number(this.step) || 1000000;
        const min  = Number(this.min);
        const max  = Number(this.max);
        let val    = Number(this.value);
        let changed = false;

        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowDown':
                e.preventDefault();
                val     = Math.max(min, val - step);
                changed = true;
                break;
            case 'ArrowRight':
            case 'ArrowUp':
                e.preventDefault();
                val     = Math.min(max, val + step);
                changed = true;
                break;
            case 'PageDown':
                e.preventDefault();
                val     = Math.max(min, val - step * 10);
                changed = true;
                break;
            case 'PageUp':
                e.preventDefault();
                val     = Math.min(max, val + step * 10);
                changed = true;
                break;
            case 'Home':
                e.preventDefault();
                val     = min;
                changed = true;
                break;
            case 'End':
                e.preventDefault();
                val     = max;
                changed = true;
                break;
        }

        if (changed) {
            this.value = val;
            syncFromSlider(val);
            calculateCosts();
        }
    });

    // Number input
    el.tokensInput.addEventListener('input', function () {
        let val = parseInt(this.value, 10) || 1000000;
        val = Math.max(1000000, Math.min(10000000000, val));
        el.slider.value = val;
        syncFromSlider(val);
        calculateCosts();
    });

    // Dropdowns
    el.modelTier.addEventListener('change', calculateCosts);
    el.workloadType.addEventListener('change', calculateCosts);

    // Calculate button (redundant but reassuring UX)
    el.calcBtn.addEventListener('click', calculateCosts);

    // ── FAQ accordion ──────────────────────────────────────────────────────
    document.querySelectorAll('.faq-item').forEach(function (item) {
        const trigger = item.querySelector('.faq-trigger');
        const body    = item.querySelector('.faq-body');

        if (!trigger || !body) return;

        trigger.addEventListener('click', function () {
            const isOpen = item.dataset.open === 'true';
            const nowOpen = !isOpen;

            item.dataset.open = nowOpen;
            trigger.setAttribute('aria-expanded', nowOpen);

            if (nowOpen) {
                body.removeAttribute('hidden');
            } else {
                body.setAttribute('hidden', '');
            }
        });

        // Keyboard: Enter and Space already trigger click on <button>
        // Space is handled natively; no extra keydown needed
    });

    // ── Initial state ──────────────────────────────────────────────────────
    syncFromSlider(el.slider.value);
    calculateCosts();

})();
</script>
@endpush

@endsection
