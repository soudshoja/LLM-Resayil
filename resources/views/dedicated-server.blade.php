@extends('layouts.app')

@section('title', __('dedicated-server.title'))

@push('styles')
<style>
    :root {
        --ds-bg: #0f1115;
        --ds-card: #13161d;
        --ds-gold: #d4af37;
        --ds-gold-light: #eac558;
        --ds-text: #e0e5ec;
        --ds-text-muted: #b8c0cc;
        --ds-text-dim: #8892a4;
        --ds-border: rgba(255,255,255,0.08);
        --ds-border-solid: #1e2230;
        --ds-secondary: #1a1e28;
    }

    /* ── BASE ── */
    .ds-wrap {
        background: var(--ds-bg);
        font-family: 'Inter', sans-serif;
        color: var(--ds-text);
        overflow-x: hidden;
    }

    /* ── REDUCED MOTION ── */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        .ds-fade-in { opacity: 1 !important; transform: none !important; }
    }

    /* ── FADE-IN ANIMATION ── */
    .ds-fade-in {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 400ms ease, transform 400ms ease;
    }
    .ds-fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .ds-fade-in.delay-1 { transition-delay: 100ms; }
    .ds-fade-in.delay-2 { transition-delay: 200ms; }
    .ds-fade-in.delay-3 { transition-delay: 300ms; }

    /* ── HERO ── */
    .ds-hero {
        position: relative;
        min-height: 55vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 6rem 2rem 7rem;
        overflow: hidden;
    }

    .ds-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 130% 90% at 50% -5%, rgba(212,175,55,0.18) 0%, transparent 65%);
        pointer-events: none;
    }

    .ds-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.3), transparent);
    }

    .ds-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1000px;
    }

    .ds-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(212,175,55,0.1);
        border: 1px solid rgba(212,175,55,0.3);
        border-radius: 100px;
        padding: 0.4rem 1.2rem;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--ds-gold);
        margin-bottom: 2rem;
    }

    .ds-hero-eyebrow-dot {
        width: 6px;
        height: 6px;
        background: var(--ds-gold);
        border-radius: 50%;
        flex-shrink: 0;
    }

    .ds-hero h1 {
        font-size: clamp(2.8rem, 10vw, 4.5rem);
        font-weight: 900;
        letter-spacing: -0.05em;
        line-height: 1.05;
        margin-bottom: 1.75rem;
        color: var(--ds-text);
    }

    .ds-hero h1 .ds-hero-gold {
        background: linear-gradient(135deg, var(--ds-gold) 0%, var(--ds-gold-light) 60%, var(--ds-gold) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ds-hero-subtitle {
        font-size: clamp(1rem, 2.2vw, 1.25rem);
        color: var(--ds-text-muted);
        font-weight: 400;
        line-height: 1.75;
        margin-bottom: 2.5rem;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ── HARDWARE SPEC BADGES ── */
    .ds-hero-badges {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .ds-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--ds-secondary);
        border: 1px solid var(--ds-border-solid);
        border-radius: 8px;
        padding: 0.55rem 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--ds-text);
        white-space: nowrap;
    }

    .ds-badge-icon {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
        color: var(--ds-gold);
    }

    .ds-badge-value {
        color: var(--ds-gold);
        font-weight: 700;
    }

    /* ── HERO CTA ── */
    .ds-hero-cta {
        display: flex;
        gap: 1.25rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        background: var(--ds-gold);
        color: #0f1115;
        border: 2px solid var(--ds-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background 200ms ease, transform 200ms ease, box-shadow 200ms ease;
        text-decoration: none;
        min-height: 48px;
        line-height: 1;
    }

    .btn-primary:hover {
        background: var(--ds-gold-light);
        border-color: var(--ds-gold-light);
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(212,175,55,0.35);
        color: #0f1115;
        text-decoration: none;
    }

    .btn-primary:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 3px;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        background: transparent;
        color: var(--ds-gold);
        border: 2px solid var(--ds-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background 200ms ease, transform 200ms ease, color 200ms ease;
        text-decoration: none;
        min-height: 48px;
        line-height: 1;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
        color: var(--ds-gold-light);
        border-color: var(--ds-gold-light);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-secondary:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 3px;
    }

    /* ── SECTION WRAPPER ── */
    .ds-section {
        padding: 7rem 2rem;
        max-width: 1300px;
        margin: 0 auto;
    }

    .ds-section--alt {
        background: linear-gradient(180deg, transparent 0%, rgba(212,175,55,0.025) 50%, transparent 100%);
        max-width: 100%;
        padding-left: 0;
        padding-right: 0;
    }

    .ds-section--alt .ds-section-inner {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .ds-section-title {
        font-size: clamp(2rem, 6vw, 3rem);
        font-weight: 900;
        margin-bottom: 1rem;
        letter-spacing: -0.03em;
        color: var(--ds-text);
        line-height: 1.1;
    }

    .ds-section-sub {
        font-size: 1.05rem;
        color: var(--ds-text-muted);
        line-height: 1.7;
        max-width: 620px;
        margin-bottom: 3.5rem;
    }

    .ds-section-header {
        margin-bottom: 4rem;
    }

    .ds-section-header.centered {
        text-align: center;
    }

    .ds-section-header.centered .ds-section-sub {
        margin-left: auto;
        margin-right: auto;
    }

    /* ── VALUE GRID ── */
    .ds-value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .ds-value-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border-solid);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        transition: border-color 250ms ease, box-shadow 250ms ease, transform 250ms ease;
        position: relative;
        overflow: hidden;
    }

    .ds-value-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ds-gold), var(--ds-gold-light));
        opacity: 0;
        transition: opacity 250ms ease;
    }

    .ds-value-card:hover {
        border-color: rgba(212,175,55,0.4);
        box-shadow: 0 12px 40px rgba(212,175,55,0.12);
        transform: translateY(-3px);
    }

    .ds-value-card:hover::after {
        opacity: 1;
    }

    /* ── CSS GEOMETRIC ICONS ── */
    .ds-icon-shape {
        width: 52px;
        height: 52px;
        margin-bottom: 1.5rem;
        position: relative;
        flex-shrink: 0;
    }

    /* Lightning bolt — API simplicity */
    .ds-icon-bolt {
        position: relative;
    }
    .ds-icon-bolt::before {
        content: '';
        position: absolute;
        top: 4px;
        left: 14px;
        width: 0;
        height: 0;
        border-left: 12px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 26px solid var(--ds-gold);
    }
    .ds-icon-bolt::after {
        content: '';
        position: absolute;
        top: 22px;
        left: 18px;
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 12px solid transparent;
        border-top: 26px solid var(--ds-gold);
    }

    /* Shield — security/control */
    .ds-icon-shield {
        position: relative;
    }
    .ds-icon-shield::before {
        content: '';
        position: absolute;
        top: 4px;
        left: 6px;
        width: 40px;
        height: 36px;
        background: transparent;
        border: 3px solid var(--ds-gold);
        border-radius: 4px 4px 20px 20px;
        clip-path: polygon(0 0, 100% 0, 100% 60%, 50% 100%, 0 60%);
    }
    .ds-icon-shield::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 19px;
        width: 14px;
        height: 14px;
        border: 2.5px solid var(--ds-gold);
        border-radius: 50%;
    }

    /* Coin stack — cost efficiency */
    .ds-icon-coin {
        position: relative;
    }
    .ds-icon-coin::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: 40px;
        height: 14px;
        background: var(--ds-gold);
        border-radius: 7px;
        opacity: 0.5;
    }
    .ds-icon-coin::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 6px;
        width: 40px;
        height: 22px;
        background: var(--ds-gold);
        border-radius: 4px 4px 11px 11px;
    }

    /* Server rack — infrastructure */
    .ds-icon-server {
        position: relative;
    }
    .ds-icon-server::before {
        content: '';
        position: absolute;
        top: 4px;
        left: 4px;
        width: 44px;
        height: 18px;
        border: 2.5px solid var(--ds-gold);
        border-radius: 4px;
        box-shadow: 0 20px 0 0 transparent, 0 20px 0 0 transparent;
    }
    .ds-icon-server::after {
        content: '';
        position: absolute;
        top: 30px;
        left: 4px;
        width: 44px;
        height: 18px;
        border: 2.5px solid var(--ds-gold);
        border-radius: 4px;
        opacity: 0.6;
    }

    /* Diamond — enterprise */
    .ds-icon-diamond {
        position: relative;
    }
    .ds-icon-diamond::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: 40px;
        height: 40px;
        background: transparent;
        border: 3px solid var(--ds-gold);
        border-radius: 4px;
        transform: rotate(45deg);
    }

    /* Chart bars — performance */
    .ds-icon-chart {
        position: relative;
    }
    .ds-icon-chart::before {
        content: '';
        position: absolute;
        bottom: 8px;
        left: 4px;
        width: 10px;
        height: 24px;
        background: var(--ds-gold);
        border-radius: 2px;
        opacity: 0.6;
        box-shadow: 16px 8px 0 0 var(--ds-gold), 32px -4px 0 0 var(--ds-gold);
    }
    .ds-icon-chart::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 4px;
        right: 4px;
        height: 2px;
        background: rgba(212,175,55,0.3);
        border-radius: 1px;
    }

    /* Globe — multi-region */
    .ds-icon-globe {
        position: relative;
    }
    .ds-icon-globe::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: 40px;
        height: 40px;
        border: 3px solid var(--ds-gold);
        border-radius: 50%;
    }
    .ds-icon-globe::after {
        content: '';
        position: absolute;
        top: 6px;
        left: 19px;
        width: 14px;
        height: 40px;
        border: 2px solid rgba(212,175,55,0.5);
        border-radius: 50%;
    }

    /* Lock — compliance */
    .ds-icon-lock {
        position: relative;
    }
    .ds-icon-lock::before {
        content: '';
        position: absolute;
        top: 4px;
        left: 12px;
        width: 28px;
        height: 20px;
        border: 3px solid var(--ds-gold);
        border-bottom: none;
        border-radius: 14px 14px 0 0;
    }
    .ds-icon-lock::after {
        content: '';
        position: absolute;
        top: 21px;
        left: 6px;
        width: 40px;
        height: 26px;
        background: var(--ds-gold);
        border-radius: 4px;
        opacity: 0.8;
    }

    /* Network nodes — architecture */
    .ds-icon-network {
        position: relative;
    }
    .ds-icon-network::before {
        content: '';
        position: absolute;
        top: 4px;
        left: 14px;
        width: 24px;
        height: 24px;
        border: 3px solid var(--ds-gold);
        border-radius: 50%;
    }
    .ds-icon-network::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 4px;
        width: 14px;
        height: 14px;
        background: var(--ds-gold);
        border-radius: 50%;
        box-shadow: 30px 0 0 0 var(--ds-gold);
        opacity: 0.7;
    }

    .ds-value-title {
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        color: var(--ds-text);
        line-height: 1.3;
    }

    .ds-value-text {
        font-size: 0.97rem;
        color: var(--ds-text-muted);
        line-height: 1.7;
    }

    /* ── COMPARISON SECTION ── */
    .ds-comparison-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .ds-comparison-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border-solid);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        transition: border-color 250ms ease, box-shadow 250ms ease;
    }

    .ds-comparison-card:hover {
        border-color: rgba(212,175,55,0.35);
        box-shadow: 0 10px 36px rgba(212,175,55,0.1);
    }

    .ds-comparison-card.highlight {
        border-color: var(--ds-gold);
        box-shadow: 0 0 0 1px rgba(212,175,55,0.2), 0 16px 48px rgba(212,175,55,0.2);
        position: relative;
    }

    .ds-comparison-card.highlight::before {
        content: 'RECOMMENDED';
        position: absolute;
        top: -13px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ds-gold);
        color: #0f1115;
        padding: 0.3rem 1.2rem;
        border-radius: 100px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        white-space: nowrap;
    }

    .ds-comparison-label {
        font-size: 0.75rem;
        color: var(--ds-text-dim);
        font-weight: 700;
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .ds-comparison-card.highlight .ds-comparison-label {
        color: var(--ds-gold);
    }

    .ds-comparison-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 1.75rem;
        color: var(--ds-text);
        line-height: 1.2;
    }

    .ds-comparison-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ds-comparison-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .ds-comp-icon {
        flex-shrink: 0;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }

    .ds-comp-icon--check {
        background: rgba(212,175,55,0.15);
    }

    .ds-comp-icon--x {
        background: rgba(255,80,80,0.12);
    }

    .ds-comp-icon svg {
        width: 12px;
        height: 12px;
    }

    .ds-comp-text {
        font-size: 0.93rem;
        color: var(--ds-text-muted);
        line-height: 1.5;
    }

    /* ── PRICING TIERS ── */
    .ds-tiers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        align-items: start;
    }

    .ds-tier-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border-solid);
        border-radius: 16px;
        padding: 3rem 2.25rem;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: border-color 250ms ease, box-shadow 250ms ease, transform 250ms ease;
    }

    .ds-tier-card:hover {
        border-color: rgba(212,175,55,0.3);
        box-shadow: 0 8px 32px rgba(212,175,55,0.1);
    }

    .ds-tier-card.featured {
        border-color: var(--ds-gold);
        box-shadow: 0 0 0 1px rgba(212,175,55,0.25), 0 24px 64px rgba(212,175,55,0.22);
    }

    .ds-tier-card.featured:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 0 1px rgba(212,175,55,0.3), 0 32px 80px rgba(212,175,55,0.28);
    }

    .ds-tier-popular {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ds-gold);
        color: #0f1115;
        padding: 0.35rem 1.25rem;
        border-radius: 100px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        white-space: nowrap;
    }

    .ds-tier-name {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
        color: var(--ds-text);
    }

    .ds-tier-sub {
        font-size: 0.88rem;
        color: var(--ds-text-dim);
        line-height: 1.55;
        margin-bottom: 2rem;
    }

    /* Price display */
    .ds-tier-pricing {
        padding: 1.75rem 0;
        border-top: 1px solid var(--ds-border-solid);
        border-bottom: 1px solid var(--ds-border-solid);
        margin-bottom: 2rem;
    }

    .ds-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
    }

    .ds-price-currency {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--ds-gold);
        align-self: flex-start;
        margin-top: 0.4rem;
    }

    .ds-price-value {
        font-size: 3.5rem;
        font-weight: 900;
        color: var(--ds-gold);
        letter-spacing: -0.04em;
        line-height: 1;
    }

    .ds-price-value.custom-price {
        font-size: 2.2rem;
    }

    .ds-price-period {
        font-size: 0.9rem;
        color: var(--ds-text-dim);
        margin-top: 0.4rem;
    }

    /* Spec rows */
    .ds-tier-specs {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 2rem;
    }

    .ds-spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.7rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .ds-spec-row:last-child {
        border-bottom: none;
    }

    .ds-spec-label {
        font-size: 0.88rem;
        color: var(--ds-text-dim);
    }

    .ds-spec-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ds-gold);
    }

    /* Feature list with SVG checkmarks */
    .ds-tier-features {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        margin-bottom: 2.25rem;
        flex-grow: 1;
    }

    .ds-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.93rem;
        color: var(--ds-text);
        line-height: 1.5;
    }

    .ds-feature-check-svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .ds-tier-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 2rem;
        background: var(--ds-gold);
        color: #0f1115;
        border: 2px solid var(--ds-gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.97rem;
        cursor: pointer;
        transition: background 200ms ease, transform 200ms ease, box-shadow 200ms ease;
        text-decoration: none;
        text-align: center;
        min-height: 48px;
        margin-top: auto;
    }

    .ds-tier-cta:hover {
        background: var(--ds-gold-light);
        border-color: var(--ds-gold-light);
        color: #0f1115;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212,175,55,0.32);
        text-decoration: none;
    }

    .ds-tier-cta:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 3px;
    }

    .ds-tier-cta--outline {
        background: transparent;
        color: var(--ds-gold);
    }

    .ds-tier-cta--outline:hover {
        background: rgba(212,175,55,0.1);
        color: var(--ds-gold-light);
    }

    /* ── SPECS GRID (hardware overview) ── */
    .ds-specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .ds-spec-card {
        background: var(--ds-secondary);
        border: 1px solid var(--ds-border-solid);
        border-radius: 12px;
        padding: 1.75rem 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        transition: border-color 200ms ease, box-shadow 200ms ease;
    }

    .ds-spec-card:hover {
        border-color: rgba(212,175,55,0.3);
        box-shadow: 0 6px 20px rgba(212,175,55,0.08);
    }

    .ds-spec-card-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: 10px;
        background: rgba(212,175,55,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ds-spec-card-icon svg {
        width: 22px;
        height: 22px;
        color: var(--ds-gold);
    }

    .ds-spec-card-text {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .ds-spec-card-name {
        font-size: 0.8rem;
        color: var(--ds-text-dim);
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .ds-spec-card-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--ds-text);
    }

    .ds-spec-card-note {
        font-size: 0.78rem;
        color: var(--ds-text-dim);
        margin-top: 0.1rem;
    }

    /* ── USE CASES / BENEFITS ── */
    .ds-benefits-scroll {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .ds-benefits-scroll {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            gap: 1rem;
            padding-bottom: 1rem;
        }
        .ds-benefit-card {
            scroll-snap-align: start;
            min-width: 260px;
            flex-shrink: 0;
        }
    }

    .ds-benefit-card {
        background: var(--ds-card);
        border: 1px solid var(--ds-border-solid);
        border-radius: 14px;
        padding: 2rem 1.75rem;
        transition: border-color 200ms ease, box-shadow 200ms ease, transform 200ms ease;
    }

    .ds-benefit-card:hover {
        border-color: rgba(212,175,55,0.3);
        box-shadow: 0 8px 28px rgba(212,175,55,0.1);
        transform: translateY(-2px);
    }

    .ds-benefit-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(212,175,55,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .ds-benefit-icon-wrap svg {
        width: 24px;
        height: 24px;
        color: var(--ds-gold);
    }

    .ds-benefit-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        color: var(--ds-text);
        line-height: 1.3;
    }

    .ds-benefit-desc {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
        line-height: 1.65;
    }

    /* ── ARCHITECTURE ── */
    .ds-arch-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .ds-arch-visual {
        background: var(--ds-card);
        border: 2px solid var(--ds-border-solid);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
    }

    .ds-arch-node {
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--ds-border-solid);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        font-size: 0.93rem;
        font-weight: 600;
        color: var(--ds-text);
        text-align: center;
    }

    .ds-arch-node.highlight-node {
        background: rgba(212,175,55,0.12);
        border-color: var(--ds-gold);
        color: var(--ds-gold);
        font-weight: 700;
    }

    .ds-arch-connector {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 0;
    }

    .ds-arch-connector svg {
        width: 20px;
        height: 20px;
        color: var(--ds-text-dim);
    }

    .ds-arch-points {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }

    .ds-arch-point {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .ds-arch-step {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(212,175,55,0.12);
        border: 2px solid var(--ds-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--ds-gold);
        line-height: 1;
    }

    .ds-arch-point-body {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .ds-arch-point-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ds-text);
    }

    .ds-arch-point-desc {
        font-size: 0.9rem;
        color: var(--ds-text-muted);
        line-height: 1.65;
    }

    /* ── FAQ ── */
    .ds-faq-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 860px;
        margin: 0 auto;
    }

    .ds-faq-item {
        background: var(--ds-card);
        border: 1px solid var(--ds-border-solid);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 200ms ease;
    }

    .ds-faq-item:has(.ds-faq-btn[aria-expanded="true"]) {
        border-color: rgba(212,175,55,0.35);
    }

    .ds-faq-btn {
        width: 100%;
        background: none;
        border: none;
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        cursor: pointer;
        text-align: left;
        min-height: 64px;
        color: var(--ds-text);
    }

    .ds-faq-btn:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: -2px;
    }

    .ds-faq-question-text {
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.45;
        color: var(--ds-text);
    }

    .ds-faq-chevron {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        color: var(--ds-gold);
        transition: transform 250ms ease;
    }

    .ds-faq-btn[aria-expanded="true"] .ds-faq-chevron {
        transform: rotate(180deg);
    }

    .ds-faq-answer {
        display: none;
        padding: 0 1.75rem 1.5rem;
        font-size: 0.95rem;
        color: var(--ds-text-muted);
        line-height: 1.8;
        border-top: 1px solid var(--ds-border-solid);
        padding-top: 1.25rem;
    }

    .ds-faq-answer.open {
        display: block;
        animation: ds-slide-down 200ms ease;
    }

    @keyframes ds-slide-down {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── FOOTER CTA ── */
    .ds-footer-cta {
        padding: 6rem 2rem;
        background: linear-gradient(135deg, rgba(212,175,55,0.07) 0%, transparent 60%);
        border-top: 1px solid var(--ds-border-solid);
        text-align: center;
    }

    .ds-footer-headline {
        font-size: clamp(1.8rem, 5vw, 3rem);
        font-weight: 900;
        margin-bottom: 1.25rem;
        color: var(--ds-text);
        letter-spacing: -0.03em;
        line-height: 1.1;
    }

    .ds-footer-text {
        font-size: 1.05rem;
        color: var(--ds-text-muted);
        margin-bottom: 2.75rem;
        max-width: 640px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.75;
    }

    .ds-footer-btns {
        display: flex;
        gap: 1.25rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }

    .ds-contact-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .ds-contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.93rem;
        color: var(--ds-text-muted);
    }

    .ds-contact-item svg {
        width: 18px;
        height: 18px;
        color: var(--ds-gold);
        flex-shrink: 0;
    }

    .ds-contact-link {
        color: var(--ds-gold);
        text-decoration: none;
        font-weight: 600;
        transition: color 150ms ease;
    }

    .ds-contact-link:hover {
        color: var(--ds-gold-light);
        text-decoration: none;
    }

    .ds-contact-link:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
        border-radius: 2px;
    }

    .ds-footer-explore {
        font-size: 0.9rem;
        color: var(--ds-text-dim);
        margin-top: 2rem;
    }

    .ds-footer-explore a {
        color: var(--ds-gold);
        text-decoration: none;
        font-weight: 600;
        transition: color 150ms ease;
    }

    .ds-footer-explore a:hover {
        color: var(--ds-gold-light);
    }

    .ds-footer-explore a:focus-visible {
        outline: 2px solid var(--ds-gold);
        outline-offset: 2px;
        border-radius: 2px;
    }

    /* ── RTL ARABIC ── */
    [dir="rtl"] .ds-hero h1,
    [dir="rtl"] .ds-hero-subtitle,
    [dir="rtl"] .ds-section-sub {
        font-family: 'Tajawal', sans-serif;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .ds-arch-grid {
            grid-template-columns: 1fr;
        }
        .ds-arch-visual {
            max-width: 480px;
            margin: 0 auto;
        }
    }

    @media (max-width: 768px) {
        .ds-section {
            padding: 4.5rem 1.5rem;
        }
        .ds-hero {
            padding: 4rem 1.5rem 5rem;
            min-height: 50vh;
        }
        .ds-hero-badges {
            gap: 0.5rem;
        }
        .ds-badge {
            font-size: 0.78rem;
            padding: 0.45rem 0.85rem;
        }
        .ds-tiers-grid {
            gap: 3rem;
        }
        .ds-tier-card.featured {
            order: -1;
        }
    }

    @media (max-width: 480px) {
        .ds-hero {
            padding: 3rem 1rem 4rem;
        }
        .ds-section {
            padding: 3.5rem 1rem;
        }
        .ds-hero-badges {
            justify-content: flex-start;
        }
        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }
        .ds-hero-cta {
            flex-direction: column;
        }
        .ds-specs-grid {
            grid-template-columns: 1fr 1fr;
        }
        .ds-footer-btns {
            flex-direction: column;
            align-items: stretch;
        }
        .ds-footer-btns .btn-primary,
        .ds-footer-btns .btn-secondary {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<main class="ds-wrap">

    {{-- ═══════════════════════════════════════════ --}}
    {{-- HERO                                        --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-hero" aria-labelledby="hero-heading">
        <div class="ds-hero-content">

            <div class="ds-hero-eyebrow" aria-hidden="true">
                <span class="ds-hero-eyebrow-dot"></span>
                {{ __('dedicated-server.hero_eyebrow') }}
            </div>

            <h1 id="hero-heading">
                {{ __('dedicated-server.hero_title') }}
                <br>
                <span class="ds-hero-gold">{{ __('dedicated-server.hero_title_highlight') }}</span>
            </h1>

            <p class="ds-hero-subtitle">
                {{ __('dedicated-server.hero_subtitle') }}
            </p>

            {{-- Hardware spec badge row --}}
            <div class="ds-hero-badges" role="list" aria-label="Available hardware specifications">
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
                    {{ __('dedicated-server.hero_badge_cpu') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_cpu_val') }}</span>
                </span>
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 19v-3"/><path d="M10 19v-3"/><path d="M14 19v-3"/><path d="M18 19v-3"/><path d="M8 11V9"/><path d="M16 11V9"/><path d="M12 11V9"/><path d="M2 15h20"/><path d="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1.1a2 2 0 0 0 0 3.837V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5.1a2 2 0 0 0 0-3.837Z"/></svg>
                    {{ __('dedicated-server.hero_badge_ram') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_ram_val') }}</span>
                </span>
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
                    {{ __('dedicated-server.hero_badge_storage') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_storage_val') }}</span>
                </span>
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    {{ __('dedicated-server.hero_badge_uptime') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_uptime_val') }}</span>
                </span>
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    {{ __('dedicated-server.hero_badge_bandwidth') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_bandwidth_val') }}</span>
                </span>
                <span class="ds-badge" role="listitem">
                    <svg class="ds-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    {{ __('dedicated-server.hero_badge_api') }} <span class="ds-badge-value">{{ __('dedicated-server.hero_badge_api_val') }}</span>
                </span>
            </div>

            <div class="ds-hero-cta">
                <a href="{{ route('register') }}" class="btn-primary">{{ __('dedicated-server.hero_cta_primary') }}</a>
                <a href="{{ route('contact') }}" class="btn-secondary">{{ __('dedicated-server.hero_cta_secondary') }}</a>
            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- VALUE PROPOSITION                           --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section" role="region" aria-labelledby="value-heading">
        <div class="ds-section-header">
            <h2 class="ds-section-title" id="value-heading">{{ __('dedicated-server.value_title') }}</h2>
            <p class="ds-section-sub">{{ __('dedicated-server.value_subtitle') }}</p>
        </div>
        <div class="ds-value-grid">

            <article class="ds-value-card ds-fade-in delay-1">
                <div class="ds-icon-shape ds-icon-bolt" role="img" aria-label="API simplicity icon"></div>
                <h3 class="ds-value-title">{{ __('dedicated-server.value_1_title') }}</h3>
                <p class="ds-value-text">{{ __('dedicated-server.value_1_text') }}</p>
            </article>

            <article class="ds-value-card ds-fade-in delay-2">
                <div class="ds-icon-shape ds-icon-shield" role="img" aria-label="Security and control icon"></div>
                <h3 class="ds-value-title">{{ __('dedicated-server.value_2_title') }}</h3>
                <p class="ds-value-text">{{ __('dedicated-server.value_2_text') }}</p>
            </article>

            <article class="ds-value-card ds-fade-in delay-3">
                <div class="ds-icon-shape ds-icon-coin" role="img" aria-label="Cost efficiency icon"></div>
                <h3 class="ds-value-title">{{ __('dedicated-server.value_3_title') }}</h3>
                <p class="ds-value-text">{{ __('dedicated-server.value_3_text') }}</p>
            </article>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- COMPARISON                                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section ds-section--alt" role="region" aria-labelledby="comparison-heading">
        <div class="ds-section-inner">
            <div class="ds-section-header centered">
                <h2 class="ds-section-title" id="comparison-heading">{{ __('dedicated-server.comparison_title') }}</h2>
                <p class="ds-section-sub">{{ __('dedicated-server.comparison_subtitle') }}</p>
            </div>
            <div class="ds-comparison-grid">

                {{-- Self-hosted --}}
                <div class="ds-comparison-card ds-fade-in delay-1">
                    <p class="ds-comparison-label">{{ __('dedicated-server.self_hosted_label') }}</p>
                    <h3 class="ds-comparison-title">{{ __('dedicated-server.self_hosted_title') }}</h3>
                    <ul class="ds-comparison-items" aria-label="{{ __('dedicated-server.self_hosted_title') }} features">
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--x" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#ff5050" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="2" x2="10" y2="10"/><line x1="10" y1="2" x2="2" y2="10"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.self_hosted_con1') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--x" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#ff5050" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="2" x2="10" y2="10"/><line x1="10" y1="2" x2="2" y2="10"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.self_hosted_con2') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--x" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#ff5050" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="2" x2="10" y2="10"/><line x1="10" y1="2" x2="2" y2="10"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.self_hosted_con3') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.self_hosted_pro1') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.self_hosted_pro2') }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Cloud-only --}}
                <div class="ds-comparison-card ds-fade-in delay-2">
                    <p class="ds-comparison-label">{{ __('dedicated-server.cloud_label') }}</p>
                    <h3 class="ds-comparison-title">{{ __('dedicated-server.cloud_title') }}</h3>
                    <ul class="ds-comparison-items" aria-label="{{ __('dedicated-server.cloud_title') }} features">
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.cloud_pro1') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.cloud_pro2') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.cloud_pro3') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--x" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#ff5050" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="2" x2="10" y2="10"/><line x1="10" y1="2" x2="2" y2="10"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.cloud_con1') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--x" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#ff5050" stroke-width="2.5" stroke-linecap="round"><line x1="2" y1="2" x2="10" y2="10"/><line x1="10" y1="2" x2="2" y2="10"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.cloud_con2') }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Resayil Hybrid --}}
                <div class="ds-comparison-card highlight ds-fade-in delay-3" aria-label="Recommended: Resayil plus Dedicated approach">
                    <p class="ds-comparison-label">{{ __('dedicated-server.hybrid_label') }}</p>
                    <h3 class="ds-comparison-title">{{ __('dedicated-server.hybrid_title') }}</h3>
                    <ul class="ds-comparison-items" aria-label="{{ __('dedicated-server.hybrid_title') }} features">
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.hybrid_pro1') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.hybrid_pro2') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.hybrid_pro3') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.hybrid_pro4') }}</span>
                        </li>
                        <li class="ds-comparison-item">
                            <span class="ds-comp-icon ds-comp-icon--check" aria-hidden="true">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="2,6 5,9 10,3"/></svg>
                            </span>
                            <span class="ds-comp-text">{{ __('dedicated-server.hybrid_pro5') }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- PRICING TIERS                               --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section" role="region" aria-labelledby="tiers-heading">
        <div class="ds-section-header centered">
            <h2 class="ds-section-title" id="tiers-heading">{{ __('dedicated-server.tiers_title') }}</h2>
            <p class="ds-section-sub">{{ __('dedicated-server.tiers_subtitle') }}</p>
        </div>
        <div class="ds-tiers-grid">

            {{-- Starter --}}
            <article class="ds-tier-card ds-fade-in delay-1" aria-labelledby="tier-starter-name">
                <h3 class="ds-tier-name" id="tier-starter-name">{{ __('dedicated-server.starter_name') }}</h3>
                <p class="ds-tier-sub">{{ __('dedicated-server.starter_sub') }}</p>

                <div class="ds-tier-pricing">
                    <div class="ds-price-row">
                        <span class="ds-price-currency" aria-hidden="true">$</span>
                        <span class="ds-price-value">299</span>
                        <span class="ds-price-period">{{ __('dedicated-server.per_month') }}</span>
                    </div>
                </div>

                <div class="ds-tier-specs" aria-label="{{ __('dedicated-server.starter_name') }} tier hardware specifications">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_cpu') }}</span>
                        <span class="ds-spec-value">4-core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_ram') }}</span>
                        <span class="ds-spec-value">16 GB</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_storage') }}</span>
                        <span class="ds-spec-value">256 GB SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_bandwidth') }}</span>
                        <span class="ds-spec-value">5 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_api_calls') }}</span>
                        <span class="ds-spec-value">Up to 100K/mo</span>
                    </div>
                </div>

                <ul class="ds-tier-features" aria-label="{{ __('dedicated-server.starter_name') }} tier features">
                    @foreach([
                        __('dedicated-server.starter_feature1'),
                        __('dedicated-server.starter_feature2'),
                        __('dedicated-server.starter_feature3'),
                        __('dedicated-server.starter_feature4'),
                        __('dedicated-server.starter_feature5'),
                    ] as $feat)
                    <li class="ds-feature-item">
                        <svg class="ds-feature-check-svg" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <circle cx="10" cy="10" r="9" fill="rgba(212,175,55,0.12)"/>
                            <polyline points="6,10 9,13 14,7" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $feat }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('contact') }}" class="ds-tier-cta ds-tier-cta--outline" aria-label="{{ __('dedicated-server.get_started') }} — {{ __('dedicated-server.starter_name') }}">{{ __('dedicated-server.get_started') }}</a>
            </article>

            {{-- Professional (featured) --}}
            <article class="ds-tier-card featured ds-fade-in delay-2" aria-labelledby="tier-pro-name">
                <span class="ds-tier-popular" aria-label="{{ __('dedicated-server.most_popular') }}">{{ __('dedicated-server.most_popular') }}</span>
                <h3 class="ds-tier-name" id="tier-pro-name">{{ __('dedicated-server.professional_name') }}</h3>
                <p class="ds-tier-sub">{{ __('dedicated-server.professional_sub') }}</p>

                <div class="ds-tier-pricing">
                    <div class="ds-price-row">
                        <span class="ds-price-currency" aria-hidden="true">$</span>
                        <span class="ds-price-value">799</span>
                        <span class="ds-price-period">{{ __('dedicated-server.per_month') }}</span>
                    </div>
                </div>

                <div class="ds-tier-specs" aria-label="{{ __('dedicated-server.professional_name') }} tier hardware specifications">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_cpu') }}</span>
                        <span class="ds-spec-value">8-core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_ram') }}</span>
                        <span class="ds-spec-value">64 GB</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_storage') }}</span>
                        <span class="ds-spec-value">1 TB SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_bandwidth') }}</span>
                        <span class="ds-spec-value">10 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_api_calls') }}</span>
                        <span class="ds-spec-value">Up to 500K/mo</span>
                    </div>
                </div>

                <ul class="ds-tier-features" aria-label="{{ __('dedicated-server.professional_name') }} tier features">
                    @foreach([
                        __('dedicated-server.professional_feature1'),
                        __('dedicated-server.professional_feature2'),
                        __('dedicated-server.professional_feature3'),
                        __('dedicated-server.professional_feature4'),
                        __('dedicated-server.professional_feature5'),
                    ] as $feat)
                    <li class="ds-feature-item">
                        <svg class="ds-feature-check-svg" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <circle cx="10" cy="10" r="9" fill="rgba(212,175,55,0.2)"/>
                            <polyline points="6,10 9,13 14,7" stroke="#d4af37" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $feat }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('contact') }}" class="ds-tier-cta" aria-label="{{ __('dedicated-server.get_started') }} — {{ __('dedicated-server.professional_name') }}">{{ __('dedicated-server.get_started') }}</a>
            </article>

            {{-- Enterprise --}}
            <article class="ds-tier-card ds-fade-in delay-3" aria-labelledby="tier-enterprise-name">
                <h3 class="ds-tier-name" id="tier-enterprise-name">{{ __('dedicated-server.enterprise_name') }}</h3>
                <p class="ds-tier-sub">{{ __('dedicated-server.enterprise_sub') }}</p>

                <div class="ds-tier-pricing">
                    <div class="ds-price-row">
                        <span class="ds-price-value custom-price">{{ __('dedicated-server.custom_price') }}</span>
                    </div>
                    <div class="ds-price-period" style="margin-top:0.5rem;">{{ __('dedicated-server.contact_sales_for_pricing') }}</div>
                </div>

                <div class="ds-tier-specs" aria-label="{{ __('dedicated-server.enterprise_name') }} tier hardware specifications">
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_cpu') }}</span>
                        <span class="ds-spec-value">16+ core</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_ram') }}</span>
                        <span class="ds-spec-value">256 GB+</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_storage') }}</span>
                        <span class="ds-spec-value">4 TB+ SSD</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_bandwidth') }}</span>
                        <span class="ds-spec-value">20 Tbps</span>
                    </div>
                    <div class="ds-spec-row">
                        <span class="ds-spec-label">{{ __('dedicated-server.spec_api_calls') }}</span>
                        <span class="ds-spec-value">{{ __('dedicated-server.spec_unlimited') }}</span>
                    </div>
                </div>

                <ul class="ds-tier-features" aria-label="{{ __('dedicated-server.enterprise_name') }} tier features">
                    @foreach([
                        __('dedicated-server.enterprise_feature1'),
                        __('dedicated-server.enterprise_feature2'),
                        __('dedicated-server.enterprise_feature3'),
                        __('dedicated-server.enterprise_feature4'),
                        __('dedicated-server.enterprise_feature5'),
                    ] as $feat)
                    <li class="ds-feature-item">
                        <svg class="ds-feature-check-svg" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <circle cx="10" cy="10" r="9" fill="rgba(212,175,55,0.12)"/>
                            <polyline points="6,10 9,13 14,7" stroke="#d4af37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>{{ $feat }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('contact') }}" class="ds-tier-cta ds-tier-cta--outline" aria-label="{{ __('dedicated-server.contact_sales') }} — {{ __('dedicated-server.enterprise_name') }}">{{ __('dedicated-server.contact_sales') }}</a>
            </article>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- BENEFITS / USE CASES                        --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section ds-section--alt" role="region" aria-labelledby="benefits-heading">
        <div class="ds-section-inner">
            <div class="ds-section-header centered">
                <h2 class="ds-section-title" id="benefits-heading">{{ __('dedicated-server.benefits_title') }}</h2>
                <p class="ds-section-sub">{{ __('dedicated-server.benefits_subtitle') }}</p>
            </div>
            <div class="ds-benefits-scroll" role="list">

                <article class="ds-benefit-card ds-fade-in delay-1" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_finance_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_finance_desc') }}</p>
                </article>

                <article class="ds-benefit-card ds-fade-in delay-2" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_healthcare_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_healthcare_desc') }}</p>
                </article>

                <article class="ds-benefit-card ds-fade-in delay-3" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_saas_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_saas_desc') }}</p>
                </article>

                <article class="ds-benefit-card ds-fade-in delay-1" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_highvol_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_highvol_desc') }}</p>
                </article>

                <article class="ds-benefit-card ds-fade-in delay-2" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_regulated_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_regulated_desc') }}</p>
                </article>

                <article class="ds-benefit-card ds-fade-in delay-3" role="listitem">
                    <div class="ds-benefit-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M6 21V9a9 9 0 0 0 9 9"/>
                        </svg>
                    </div>
                    <h3 class="ds-benefit-title">{{ __('dedicated-server.benefit_multitenant_title') }}</h3>
                    <p class="ds-benefit-desc">{{ __('dedicated-server.benefit_multitenant_desc') }}</p>
                </article>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- HOW IT WORKS (Architecture)                 --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section" role="region" aria-labelledby="arch-heading">
        <div class="ds-section-header">
            <h2 class="ds-section-title" id="arch-heading">{{ __('dedicated-server.arch_title') }}</h2>
            <p class="ds-section-sub">{{ __('dedicated-server.arch_subtitle') }}</p>
        </div>
        <div class="ds-arch-grid">

            {{-- Visual diagram --}}
            <div class="ds-arch-visual" aria-hidden="true">
                <div class="ds-arch-node">{{ __('dedicated-server.arch_node_apps') }}</div>
                <div class="ds-arch-connector">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19,12 12,19 5,12"/>
                    </svg>
                </div>
                <div class="ds-arch-node">{{ __('dedicated-server.arch_node_server') }}</div>
                <div class="ds-arch-connector">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="15,8 19,12 15,16"/><polyline points="9,8 5,12 9,16"/>
                    </svg>
                </div>
                <div class="ds-arch-node highlight-node">{{ __('dedicated-server.arch_node_api') }}</div>
            </div>

            {{-- Step list --}}
            <div class="ds-arch-points">
                <div class="ds-arch-point">
                    <div class="ds-arch-step" aria-hidden="true">1</div>
                    <div class="ds-arch-point-body">
                        <div class="ds-arch-point-title">{{ __('dedicated-server.arch_step1_title') }}</div>
                        <div class="ds-arch-point-desc">{{ __('dedicated-server.arch_step1_desc') }}</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-step" aria-hidden="true">2</div>
                    <div class="ds-arch-point-body">
                        <div class="ds-arch-point-title">{{ __('dedicated-server.arch_step2_title') }}</div>
                        <div class="ds-arch-point-desc">{{ __('dedicated-server.arch_step2_desc') }}</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-step" aria-hidden="true">3</div>
                    <div class="ds-arch-point-body">
                        <div class="ds-arch-point-title">{{ __('dedicated-server.arch_step3_title') }}</div>
                        <div class="ds-arch-point-desc">{{ __('dedicated-server.arch_step3_desc') }}</div>
                    </div>
                </div>
                <div class="ds-arch-point">
                    <div class="ds-arch-step" aria-hidden="true">4</div>
                    <div class="ds-arch-point-body">
                        <div class="ds-arch-point-title">{{ __('dedicated-server.arch_step4_title') }}</div>
                        <div class="ds-arch-point-desc">{{ __('dedicated-server.arch_step4_desc') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- FAQ                                         --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-section ds-section--alt" role="region" aria-labelledby="faq-heading">
        <div class="ds-section-inner">
            <div class="ds-section-header centered">
                <h2 class="ds-section-title" id="faq-heading">{{ __('dedicated-server.faq_title') }}</h2>
                <p class="ds-section-sub">{{ __('dedicated-server.faq_subtitle') }}</p>
            </div>

            <div class="ds-faq-list">

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-1" id="faq-btn-1">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q1') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-1" role="region" aria-labelledby="faq-btn-1">
                        {{ __('dedicated-server.faq_a1') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-2" id="faq-btn-2">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q2') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-2" role="region" aria-labelledby="faq-btn-2">
                        {{ __('dedicated-server.faq_a2') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-3" id="faq-btn-3">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q3') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-3" role="region" aria-labelledby="faq-btn-3">
                        {{ __('dedicated-server.faq_a3') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-4" id="faq-btn-4">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q4') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-4" role="region" aria-labelledby="faq-btn-4">
                        {{ __('dedicated-server.faq_a4') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-5" id="faq-btn-5">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q5') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-5" role="region" aria-labelledby="faq-btn-5">
                        {{ __('dedicated-server.faq_a5') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-6" id="faq-btn-6">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q6') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-6" role="region" aria-labelledby="faq-btn-6">
                        {{ __('dedicated-server.faq_a6') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-7" id="faq-btn-7">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q7') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-7" role="region" aria-labelledby="faq-btn-7">
                        {{ __('dedicated-server.faq_a7') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-8" id="faq-btn-8">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q8') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-8" role="region" aria-labelledby="faq-btn-8">
                        {{ __('dedicated-server.faq_a8') }}
                    </div>
                </div>

                <div class="ds-faq-item">
                    <button class="ds-faq-btn" aria-expanded="false" aria-controls="faq-answer-9" id="faq-btn-9">
                        <span class="ds-faq-question-text">{{ __('dedicated-server.faq_q9') }}</span>
                        <svg class="ds-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6,9 12,15 18,9"/></svg>
                    </button>
                    <div class="ds-faq-answer" id="faq-answer-9" role="region" aria-labelledby="faq-btn-9">
                        {{ __('dedicated-server.faq_a9') }}
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════ --}}
    {{-- FOOTER CTA                                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <section class="ds-footer-cta" role="region" aria-labelledby="cta-heading">
        <h2 class="ds-footer-headline" id="cta-heading">{{ __('dedicated-server.footer_cta_title') }}</h2>
        <p class="ds-footer-text">
            {{ __('dedicated-server.footer_cta_text') }}
        </p>
        <div class="ds-footer-btns">
            <a href="{{ route('register') }}" class="btn-primary">{{ __('dedicated-server.footer_cta_primary') }}</a>
            <a href="{{ route('contact') }}" class="btn-secondary">{{ __('dedicated-server.footer_cta_secondary') }}</a>
        </div>
        <div class="ds-contact-row" aria-label="Contact options">
            <span class="ds-contact-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <a href="mailto:{{ __('dedicated-server.footer_contact_email') }}" class="ds-contact-link">{{ __('dedicated-server.footer_contact_email') }}</a>
            </span>
            <span class="ds-contact-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <a href="{{ route('contact') }}" class="ds-contact-link">{{ __('dedicated-server.footer_live_chat') }}</a>
            </span>
        </div>
        <p class="ds-footer-explore">
            {!! __('dedicated-server.footer_explore', [
                'docs_link'       => '<a href="' . route('docs.index') . '">' . __('dedicated-server.footer_docs_link') . '</a>',
                'plans_link'      => '<a href="' . route('billing.plans') . '">' . __('dedicated-server.footer_plans_link') . '</a>',
                'comparison_link' => '<a href="' . route('comparison') . '">' . __('dedicated-server.footer_comparison_link') . '</a>',
            ]) !!}
        </p>
    </section>

</main>

{{-- FAQPage Schema --}}
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
        "text": "Yes. Starter and Professional tiers have fixed specs, but Enterprise tier is fully customizable. Contact sales to discuss specific CPU, RAM, storage, or GPU requirements."
      }
    },
    {
      "@type": "Question",
      "name": "What SLA do you offer?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Starter: 99.5% uptime. Professional: 99.8% uptime + 4-hour response SLA. Enterprise: 99.95% uptime + 1-hour response SLA with dedicated account manager."
      }
    },
    {
      "@type": "Question",
      "name": "How do I migrate from self-hosted Ollama?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Update your model endpoints to point to Resayil API URLs and use your API key. Since Resayil is OpenAI-compatible, most code changes are minimal. Our support team provides migration assistance."
      }
    },
    {
      "@type": "Question",
      "name": "Is there a minimum contract?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Starter and Professional tiers are month-to-month with no lock-in. Enterprise contracts are custom and discussed during sales."
      }
    },
    {
      "@type": "Question",
      "name": "Can I run other workloads on the server?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. The dedicated server is yours to use. Run your applications, databases, caches, or any other workloads. We provide bare metal or managed Linux with root access."
      }
    },
    {
      "@type": "Question",
      "name": "What if I need more capacity later?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Upgrade anytime. Move from Starter to Professional to Enterprise, or modify your server specs. API scaling is automatic — just use more tokens and the API handles it."
      }
    }
  ]
}
</script>

<script>
(function() {
    'use strict';

    // ── FAQ Accordion ──
    document.querySelectorAll('.ds-faq-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            var answerId = btn.getAttribute('aria-controls');
            var answer = document.getElementById(answerId);

            btn.setAttribute('aria-expanded', !expanded);

            if (!expanded) {
                answer.classList.add('open');
            } else {
                answer.classList.remove('open');
            }
        });

        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                btn.click();
            }
        });
    });

    // ── Scroll-triggered fade-in with staggered delay on pricing cards ──
    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReduced && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.ds-fade-in').forEach(function(el) {
            observer.observe(el);
        });
    } else {
        // Immediately show all for reduced motion or no IntersectionObserver
        document.querySelectorAll('.ds-fade-in').forEach(function(el) {
            el.classList.add('visible');
        });
    }

})();
</script>

@endsection
