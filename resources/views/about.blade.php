@extends('layouts.app')

@section('title', __('about.title'))

@push('styles')
<style>
    /* ─────────────────────────────────────────
       ABOUT PAGE — Dark Luxury Redesign
       Design System: bg #0f1115 / gold #d4af37
    ───────────────────────────────────────── */

    .about-wrap {
        background: #0a0d14;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }

    /* ══════════════════════════════════════
       1. HERO
    ══════════════════════════════════════ */
    .about-hero {
        position: relative;
        padding: 6rem 2rem 5rem;
        text-align: center;
        overflow: hidden;
    }

    /* Radial gold glow */
    .about-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 55% at 50% -5%, rgba(212,175,55,0.11) 0%, transparent 65%),
            radial-gradient(ellipse 40% 30% at 80% 20%, rgba(212,175,55,0.05) 0%, transparent 60%),
            radial-gradient(ellipse 30% 25% at 10% 80%, rgba(212,175,55,0.03) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Dot grid pattern */
    .about-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(212,175,55,0.07) 1px, transparent 1px);
        background-size: 28px 28px;
        mask-image: radial-gradient(ellipse 70% 60% at center, black, transparent);
        pointer-events: none;
    }

    .hero-inner {
        position: relative;
        z-index: 1;
        max-width: 780px;
        margin: 0 auto;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 700;
        color: #d4af37;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        background: rgba(212,175,55,0.09);
        border: 1px solid rgba(212,175,55,0.22);
        padding: 0.35rem 0.9rem;
        border-radius: 20px;
        margin-bottom: 1.75rem;
    }

    .hero-eyebrow::before {
        content: '//';
        opacity: 0.45;
    }

    .about-hero h1 {
        font-size: clamp(2.1rem, 5.5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.12;
        color: #e0e5ec;
        margin-bottom: 1.25rem;
    }

    .about-hero h1 .gold-gradient {
        background: linear-gradient(135deg, #d4af37 0%, #eac558 50%, #d4af37 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .about-hero p {
        font-size: 1.1rem;
        color: #a0a8b5;
        line-height: 1.75;
        max-width: 580px;
        margin: 0 auto 2.5rem;
    }

    .hero-cta {
        display: flex;
        gap: 0.85rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ── Buttons (shared) ── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #d4af37, #eac558);
        color: #0f1115;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.72rem 1.75rem;
        border-radius: 9px;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        border: none;
        box-shadow: 0 0 22px rgba(212,175,55,0.22);
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 28px rgba(212,175,55,0.32);
        color: #0f1115;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(212,175,55,0.32);
        color: #d4af37;
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.72rem 1.75rem;
        border-radius: 9px;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: rgba(212,175,55,0.09);
        border-color: #d4af37;
    }

    /* ══════════════════════════════════════
       2. STATS BAR
    ══════════════════════════════════════ */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid #1e2230;
        border-bottom: 1px solid #1e2230;
    }

    .stat-block {
        padding: 2rem 1.5rem;
        text-align: center;
        border-right: 1px solid #1e2230;
        position: relative;
        overflow: hidden;
        transition: background 0.3s;
        cursor: default;
    }

    .stat-block:last-child {
        border-right: none;
    }

    .stat-block:hover {
        background: rgba(212,175,55,0.03);
    }

    .stat-block::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #d4af37, transparent);
        transition: width 0.4s ease;
    }

    .stat-block:hover::after {
        width: 75%;
    }

    .stat-num {
        font-size: 2.25rem;
        font-weight: 800;
        color: #d4af37;
        line-height: 1;
        margin-bottom: 0.4rem;
        font-variant-numeric: tabular-nums;
    }

    .stat-label {
        font-size: 0.78rem;
        color: #6b7280;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* ══════════════════════════════════════
       3. SECTION WRAPPER & LABELS
    ══════════════════════════════════════ */
    .about-section {
        max-width: 1100px;
        margin: 0 auto;
        padding: 5rem 2rem;
    }

    .section-overline {
        display: block;
        font-size: 0.68rem;
        font-weight: 700;
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.75rem;
    }

    .section-title {
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        font-weight: 700;
        color: #e0e5ec;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .section-body {
        font-size: 1rem;
        color: #a0a8b5;
        line-height: 1.8;
        max-width: 640px;
    }

    /* Gold accent divider */
    .gold-divider {
        max-width: 1100px;
        margin: 0 auto;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.25), transparent);
    }

    /* ══════════════════════════════════════
       4. MISSION & VALUES — Cards
    ══════════════════════════════════════ */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-top: 3rem;
    }

    .value-card {
        background: #13161d;
        border: 1px solid #1e2230;
        border-radius: 14px;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        cursor: default;
    }

    .value-card:hover {
        transform: translateY(-3px);
        border-color: rgba(212,175,55,0.35);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 0 1px rgba(212,175,55,0.08);
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.25), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .value-card:hover::before {
        opacity: 1;
    }

    .value-icon {
        width: 46px;
        height: 46px;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.22);
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .value-icon svg {
        width: 21px;
        height: 21px;
        color: #d4af37;
    }

    .value-title {
        font-size: 1rem;
        font-weight: 700;
        color: #e0e5ec;
        margin-bottom: 0.5rem;
    }

    .value-desc {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.7;
    }

    /* ══════════════════════════════════════
       5. WHAT WE OFFER — Bento Grid
    ══════════════════════════════════════ */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: minmax(190px, auto);
        gap: 1px;
        background: #1e2230;
        border: 1px solid #1e2230;
        border-radius: 16px;
        overflow: hidden;
        margin-top: 3rem;
    }

    .bento-cell {
        background: #13161d;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: background 0.25s;
        cursor: default;
    }

    .bento-cell:hover {
        background: #161921;
    }

    .bento-cell::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .bento-cell:hover::before {
        opacity: 1;
    }

    .bento-cell.wide {
        grid-column: span 2;
    }

    .bento-cell.tall {
        grid-row: span 2;
    }

    .bento-icon {
        width: 44px;
        height: 44px;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .bento-icon svg {
        width: 20px;
        height: 20px;
        color: #d4af37;
    }

    .bento-title {
        font-size: 1rem;
        font-weight: 700;
        color: #e0e5ec;
        margin-bottom: 0.5rem;
    }

    .bento-desc {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.65;
    }

    .bento-badge {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(212,175,55,0.10);
        color: #d4af37;
        border: 1px solid rgba(212,175,55,0.2);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        margin-top: 1rem;
        letter-spacing: 0.03em;
    }

    .bento-big-num {
        font-size: 4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #d4af37, rgba(212,175,55,0.35));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
    }

    /* Terminal block inside bento */
    .terminal-block {
        background: #05070a;
        border: 1px solid rgba(212,175,55,0.13);
        border-radius: 9px;
        padding: 1.25rem 1.5rem;
        font-family: 'Courier New', monospace;
        font-size: 0.78rem;
        line-height: 1.9;
        margin-top: 1.25rem;
    }

    .t-prompt { color: #d4af37; }
    .t-cmd    { color: #e2e8f0; }
    .t-str    { color: #7dd3a8; }
    .t-out    { color: #4b5563; }

    /* ══════════════════════════════════════
       6. STORY SECTION
    ══════════════════════════════════════ */
    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-top: 3rem;
    }

    .story-text p {
        font-size: 1rem;
        color: #a0a8b5;
        line-height: 1.85;
        margin-bottom: 1.1rem;
    }

    .story-text p:last-child {
        margin-bottom: 0;
    }

    .story-highlight {
        color: #e0e5ec;
        font-weight: 500;
    }

    .story-visual {
        background: #13161d;
        border: 1px solid #1e2230;
        border-radius: 16px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .story-visual::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(212,175,55,0.06), transparent 70%);
        pointer-events: none;
    }

    .story-stat-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 0;
        border-bottom: 1px solid #1e2230;
    }

    .story-stat-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .story-stat-row:first-child {
        padding-top: 0;
    }

    .story-stat-icon {
        width: 38px;
        height: 38px;
        background: rgba(212,175,55,0.10);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .story-stat-icon svg {
        width: 17px;
        height: 17px;
        color: #d4af37;
    }

    .story-stat-text {
        flex: 1;
    }

    .story-stat-value {
        font-size: 1rem;
        font-weight: 700;
        color: #e0e5ec;
        line-height: 1.2;
    }

    .story-stat-sub {
        font-size: 0.78rem;
        color: #6b7280;
        margin-top: 0.15rem;
    }

    /* ══════════════════════════════════════
       7. CTA STRIP
    ══════════════════════════════════════ */
    .cta-strip {
        background: linear-gradient(135deg, rgba(212,175,55,0.07), rgba(212,175,55,0.02));
        border-top: 1px solid rgba(212,175,55,0.14);
        border-bottom: 1px solid rgba(212,175,55,0.14);
        padding: 5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-strip::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 300px;
        background: radial-gradient(ellipse, rgba(212,175,55,0.06), transparent 70%);
        pointer-events: none;
    }

    .cta-inner {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-strip h2 {
        font-size: clamp(1.75rem, 3.5vw, 2.25rem);
        font-weight: 800;
        color: #e0e5ec;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .cta-strip h2 .gold-gradient {
        background: linear-gradient(135deg, #d4af37, #eac558);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .cta-strip p {
        color: #a0a8b5;
        margin-bottom: 2.25rem;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .cta-buttons {
        display: flex;
        gap: 0.85rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ══════════════════════════════════════
       8. FOOTER NAV
    ══════════════════════════════════════ */
    .about-footer-nav {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        border-top: 1px solid #1e2230;
    }

    .about-footer-nav a {
        font-size: 0.82rem;
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
    }

    .about-footer-nav a:hover {
        color: #e0e5ec;
    }

    .about-footer-nav span {
        color: #1e2230;
    }

    /* ══════════════════════════════════════
       PRICING STRIP
    ══════════════════════════════════════ */
    .pricing-strip {
        background: #13161d;
        border: 1px solid #1e2230;
        border-radius: 14px;
        overflow: hidden;
        margin-top: 3rem;
    }

    .pricing-strip-header {
        padding: 1.1rem 2rem;
        border-bottom: 1px solid #1e2230;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pricing-strip-header span {
        font-size: 0.85rem;
        color: #d4af37;
        font-weight: 600;
    }

    .pricing-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        border-bottom: 1px solid #1e2230;
    }

    .pricing-row:last-child {
        border-bottom: none;
    }

    .pricing-cell {
        padding: 0.9rem 2rem;
        font-size: 0.875rem;
        color: #a0a8b5;
        border-right: 1px solid #1e2230;
    }

    .pricing-cell:last-child {
        border-right: none;
    }

    .pricing-cell.th {
        font-size: 0.68rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: rgba(0,0,0,0.2);
        padding-top: 0.7rem;
        padding-bottom: 0.7rem;
    }

    .pricing-cell strong {
        color: #d4af37;
        font-weight: 700;
    }

    /* ══════════════════════════════════════
       REDUCED MOTION
    ══════════════════════════════════════ */
    @media (prefers-reduced-motion: reduce) {
        .value-card,
        .stat-block::after,
        .btn-primary,
        .btn-secondary { transition: none; }
    }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @media (max-width: 1024px) {
        .bento-grid { grid-template-columns: 1fr 1fr; }
        .bento-cell.wide { grid-column: span 2; }
        .bento-cell.tall { grid-row: span 1; }
        .story-grid { grid-template-columns: 1fr; gap: 2.5rem; }
        .values-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 768px) {
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
        .stat-block:nth-child(2) { border-right: none; }
        .stat-block:nth-child(3) { border-top: 1px solid #1e2230; border-right: 1px solid #1e2230; }
        .pricing-row { grid-template-columns: 1fr 1fr; }
        .pricing-cell.th:nth-child(3),
        .pricing-cell.th:nth-child(4),
        .pricing-cell:nth-child(3),
        .pricing-cell:nth-child(4) { display: none; }
    }

    @media (max-width: 600px) {
        .about-hero { padding: 4rem 1.5rem 3.5rem; }
        .about-section { padding: 3.5rem 1.5rem; }
        .bento-grid { grid-template-columns: 1fr; }
        .bento-cell.wide { grid-column: span 1; }
        .values-grid { grid-template-columns: 1fr; }
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .cta-strip { padding: 3.5rem 1.5rem; }
        .cta-buttons { flex-direction: column; }
        .cta-buttons .btn-primary,
        .cta-buttons .btn-secondary { justify-content: center; }
    }

    /* ── RTL ── */
    [dir="rtl"] .section-body,
    [dir="rtl"] .story-text p,
    [dir="rtl"] .value-desc,
    [dir="rtl"] .bento-desc {
        direction: rtl;
        text-align: right;
    }

    [dir="rtl"] .pricing-cell {
        text-align: right;
    }

    [dir="rtl"] .story-stat-row {
        flex-direction: row-reverse;
    }
</style>
@endpush

@section('content')
<div class="about-wrap">

    {{-- ══ 1. HERO ══ --}}
    <div class="about-hero">
        <div class="hero-inner">
            @if(app()->getLocale() === 'ar')
                <span class="hero-eyebrow" dir="rtl" style="font-family:'Tajawal',sans-serif;">LLM Resayil — من نحن</span>
                <h1 dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    ذكاء اصطناعي <span class="gold-gradient">قوي</span> لمطوري المنطقة
                </h1>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    منصة API للذكاء الاصطناعي تمنح المطورين والشركات في الكويت والمنطقة العربية وصولاً سهلاً
                    إلى أكثر من 45 نموذج AI متقدم — بدفع فقط مقابل ما تستخدمه، بالدينار الكويتي.
                </p>
                <div class="hero-cta">
                    <a href="/register" class="btn-primary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ابدأ الآن مجاناً
                    </a>
                    <a href="/docs" class="btn-secondary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        استعرض التوثيق
                    </a>
                </div>
            @else
                <span class="hero-eyebrow">LLM Resayil — About Us</span>
                <h1>Powerful AI for <span class="gold-gradient">Regional Developers</span></h1>
                <p>
                    An AI API platform built for developers and businesses in Kuwait and the Arab region —
                    giving you easy access to 45+ advanced AI models with pay-per-use pricing in Kuwaiti Dinar.
                </p>
                <div class="hero-cta">
                    <a href="/register" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Get Started Free
                    </a>
                    <a href="/docs" class="btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        View Documentation
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ══ 2. STATS BAR ══ --}}
    <div class="stats-bar">
        <div class="stat-block">
            <div class="stat-num">45+</div>
            @if(app()->getLocale() === 'ar')
                <div class="stat-label" style="font-family:'Tajawal',sans-serif;">نموذج AI</div>
            @else
                <div class="stat-label">AI Models</div>
            @endif
        </div>
        <div class="stat-block">
            <div class="stat-num">99.5%</div>
            @if(app()->getLocale() === 'ar')
                <div class="stat-label" style="font-family:'Tajawal',sans-serif;">وقت التشغيل</div>
            @else
                <div class="stat-label">Uptime Target</div>
            @endif
        </div>
        <div class="stat-block">
            <div class="stat-num">1:1</div>
            @if(app()->getLocale() === 'ar')
                <div class="stat-label" style="font-family:'Tajawal',sans-serif;">متوافق مع OpenAI</div>
            @else
                <div class="stat-label">OpenAI-Compatible</div>
            @endif
        </div>
        <div class="stat-block">
            <div class="stat-num">KWD</div>
            @if(app()->getLocale() === 'ar')
                <div class="stat-label" style="font-family:'Tajawal',sans-serif;">عملة محلية</div>
            @else
                <div class="stat-label">Local Currency</div>
            @endif
        </div>
    </div>

    {{-- ══ 3. MISSION ══ --}}
    <div class="about-section">
        @if(app()->getLocale() === 'ar')
            <span class="section-overline" dir="rtl" style="font-family:'Tajawal',sans-serif;">مهمتنا</span>
            <h2 class="section-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                جعل الذكاء الاصطناعي متاحاً للجميع في المنطقة
            </h2>
            <p class="section-body" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                نؤمن بأن كل مطور وشركة في العالم العربي تستحق الوصول إلى أحدث نماذج الذكاء الاصطناعي
                دون عوائق الدفع الدولي أو الأسعار المرتفعة. LLM Resayil يزيل هذه العوائق بتقديم
                واجهة API متوافقة مع معايير الصناعة، بأسعار معقولة، وبعملة محلية.
            </p>
        @else
            <span class="section-overline">Our Mission</span>
            <h2 class="section-title">Making AI Accessible Across the Region</h2>
            <p class="section-body">
                We believe every developer and business in the Arab world deserves access to cutting-edge AI models
                without the friction of international payment barriers or inflated pricing. LLM Resayil removes
                those barriers by delivering an industry-standard API at fair prices in local currency.
            </p>
        @endif

        {{-- Values grid --}}
        <div class="values-grid">
            {{-- Value 1: Accessibility --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">إمكانية الوصول</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">واجهة API موحدة تعمل بنفس طريقة OpenAI — بدون منحنى تعلم جديد.</div>
                @else
                    <div class="value-title">Accessibility</div>
                    <div class="value-desc">A unified API that works exactly like OpenAI's — no new learning curve required.</div>
                @endif
            </div>

            {{-- Value 2: Transparency --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">الشفافية</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">أسعار واضحة بالدينار الكويتي. تعرف بالضبط ما تدفعه — رصيد واضح، لا مفاجآت.</div>
                @else
                    <div class="value-title">Transparency</div>
                    <div class="value-desc">Clear pricing in Kuwaiti Dinar. Know exactly what you're paying — visible balance, no surprises.</div>
                @endif
            </div>

            {{-- Value 3: Performance --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">الأداء</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">بنية تحتية موثوقة مع تجاوز فشل تلقائي لضمان استمرارية الخدمة وأوقات استجابة سريعة.</div>
                @else
                    <div class="value-title">Performance</div>
                    <div class="value-desc">Reliable infrastructure with automatic failover ensuring service continuity and fast response times.</div>
                @endif
            </div>

            {{-- Value 4: Security --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">الأمان</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">مفاتيح API متعددة، مصادقة آمنة، وخصوصية كاملة لبياناتك ونماذجك.</div>
                @else
                    <div class="value-title">Security</div>
                    <div class="value-desc">Multiple API keys, secure authentication, and full privacy for your data and model interactions.</div>
                @endif
            </div>

            {{-- Value 5: Regional Focus --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">تركيز إقليمي</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">مبني للمطورين العرب — بدعم ثنائي اللغة ومدفوعات محلية عبر KNET وبطاقات الائتمان.</div>
                @else
                    <div class="value-title">Regional Focus</div>
                    <div class="value-desc">Built for Arab developers — bilingual support and local payments via KNET and credit cards.</div>
                @endif
            </div>

            {{-- Value 6: Flexibility --}}
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="value-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">المرونة</div>
                    <div class="value-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">45+ نموذج لكل حالة استخدام. من المهام البسيطة إلى الاستنتاج المعقد — تختار النموذج المناسب.</div>
                @else
                    <div class="value-title">Flexibility</div>
                    <div class="value-desc">45+ models for every use case. From simple tasks to complex reasoning — you choose the right model.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="gold-divider"></div>

    {{-- ══ 4. WHAT WE OFFER — Bento Grid ══ --}}
    <div style="max-width:1100px;margin:0 auto;padding:5rem 2rem">
        @if(app()->getLocale() === 'ar')
            <span class="section-overline" dir="rtl" style="font-family:'Tajawal',sans-serif;">ما نقدمه</span>
        @else
            <span class="section-overline">What We Offer</span>
        @endif

        <div class="bento-grid">

            {{-- Wide: OpenAI-compatible API --}}
            <div class="bento-cell wide">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">واجهة API متوافقة مع OpenAI</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">انتقل من OpenAI إلى LLM Resayil بتغيير سطر واحد من الكود. نقطة نهاية واحدة، نفس الصيغة، توفير 70–90% من التكاليف.</div>
                @else
                    <div class="bento-title">OpenAI-Compatible API</div>
                    <div class="bento-desc">Migrate from OpenAI by changing a single line of code. Same format, same SDKs — save 70–90% on costs immediately.</div>
                @endif
                <div class="terminal-block">
                    <span class="t-prompt">$</span> <span class="t-cmd">curl https://llm.resayil.io/api/v1/chat/completions \</span><br>
                    &nbsp;&nbsp;<span class="t-cmd">-H </span><span class="t-str">"Authorization: Bearer $API_KEY"</span> \<br>
                    &nbsp;&nbsp;<span class="t-cmd">-d </span><span class="t-str">'{"model":"llama3.2:3b","messages":[...]}'</span><br>
                    <span class="t-out">→ 200 OK — response in milliseconds</span>
                </div>
            </div>

            {{-- Tall: 45+ Models --}}
            <div class="bento-cell tall">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/>
                    </svg>
                </div>
                <div class="bento-big-num">45+</div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">نموذج AI متقدم</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">Meta Llama وMistral وNeuralChat وOrca وغيرها. نموذج مناسب لكل حجم مهمة وميزانية.</div>
                    <span class="bento-badge" style="font-family:'Tajawal',sans-serif;">يضاف المزيد باستمرار</span>
                @else
                    <div class="bento-title">Powerful AI Models</div>
                    <div class="bento-desc">Meta Llama, Mistral, NeuralChat, Orca and more. The right model for every task size and budget.</div>
                    <span class="bento-badge">Growing library</span>
                @endif
            </div>

            {{-- Pay per use --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">ادفع مقابل الاستخدام فقط</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">لا اشتراكات شهرية. ادفع فقط مقابل الرموز التي تستخدمها. الأرصدة لا تنتهي صلاحيتها.</div>
                    <span class="bento-badge" style="font-family:'Tajawal',sans-serif;">يبدأ من 2 KWD</span>
                @else
                    <div class="bento-title">Pay Per Use</div>
                    <div class="bento-desc">No monthly subscriptions. Pay only for the tokens you use. Credits never expire.</div>
                    <span class="bento-badge">From 2 KWD</span>
                @endif
            </div>

            {{-- Local payments --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">مدفوعات KNET</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">شحن الرصيد عبر KNET وبطاقات الائتمان بالدينار الكويتي — فوري وبدون رسوم تحويل.</div>
                    <span class="bento-badge" style="font-family:'Tajawal',sans-serif;">MyFatoorah</span>
                @else
                    <div class="bento-title">KNET Payments</div>
                    <div class="bento-desc">Top up via KNET and credit cards in Kuwaiti Dinar — instant, no conversion fees.</div>
                    <span class="bento-badge">MyFatoorah</span>
                @endif
            </div>

            {{-- Usage visibility --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">رؤية الاستخدام</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">مخططات الاستخدام اليومي والشهري، تكلفة حسب النموذج، وسجل كامل للمعاملات — في الوقت الفعلي.</div>
                @else
                    <div class="bento-title">Usage Visibility</div>
                    <div class="bento-desc">Daily and monthly usage charts, per-model cost breakdown, and full transaction history — in real-time.</div>
                @endif
            </div>

            {{-- Streaming --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <div class="bento-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">دعم البث المباشر</div>
                    <div class="bento-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">Server-Sent Events (SSE) لاستجابات في الوقت الفعلي — مثالي لتطبيقات الدردشة.</div>
                @else
                    <div class="bento-title">Streaming Support</div>
                    <div class="bento-desc">Server-Sent Events (SSE) for real-time token streaming — ideal for chat applications.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="gold-divider"></div>

    {{-- ══ 5. PRICING OVERVIEW ══ --}}
    <div style="max-width:1100px;margin:0 auto;padding:5rem 2rem">
        @if(app()->getLocale() === 'ar')
            <span class="section-overline" dir="rtl" style="font-family:'Tajawal',sans-serif;">الأسعار</span>
            <h2 class="section-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">حزم الرصيد — ادفع فقط مقابل ما تستخدمه</h2>
        @else
            <span class="section-overline">Pricing</span>
            <h2 class="section-title">Credit Packs — Pay Only For What You Use</h2>
        @endif

        <div class="pricing-strip">
            <div class="pricing-strip-header">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01"/></svg>
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">حزم شحن الرصيد</span>
                @else
                    <span>Credit Top-Up Packs</span>
                @endif
            </div>
            <div class="pricing-row">
                @if(app()->getLocale() === 'ar')
                    <div class="pricing-cell th" dir="rtl" style="font-family:'Tajawal',sans-serif;">الرصيد</div>
                    <div class="pricing-cell th" dir="rtl" style="font-family:'Tajawal',sans-serif;">السعر (KWD)</div>
                    <div class="pricing-cell th" dir="rtl" style="font-family:'Tajawal',sans-serif;">للنماذج الاقتصادية</div>
                    <div class="pricing-cell th" dir="rtl" style="font-family:'Tajawal',sans-serif;">للنماذج المتقدمة</div>
                @else
                    <div class="pricing-cell th">Credits</div>
                    <div class="pricing-cell th">Price (KWD)</div>
                    <div class="pricing-cell th">Economy Models</div>
                    <div class="pricing-cell th">Premium Models</div>
                @endif
            </div>
            <div class="pricing-row">
                @if(app()->getLocale() === 'ar')
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;"><strong>5,000</strong> رصيد</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">2.000 KWD</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">5,000 رمز</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">2,500 رمز</div>
                @else
                    <div class="pricing-cell"><strong>5,000</strong> credits</div>
                    <div class="pricing-cell">2.000 KWD</div>
                    <div class="pricing-cell">5,000 tokens</div>
                    <div class="pricing-cell">2,500 tokens</div>
                @endif
            </div>
            <div class="pricing-row">
                @if(app()->getLocale() === 'ar')
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;"><strong>15,000</strong> رصيد</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">5.000 KWD</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">15,000 رمز</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">7,500 رمز</div>
                @else
                    <div class="pricing-cell"><strong>15,000</strong> credits</div>
                    <div class="pricing-cell">5.000 KWD</div>
                    <div class="pricing-cell">15,000 tokens</div>
                    <div class="pricing-cell">7,500 tokens</div>
                @endif
            </div>
            <div class="pricing-row">
                @if(app()->getLocale() === 'ar')
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;"><strong>50,000</strong> رصيد</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">15.000 KWD</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">50,000 رمز</div>
                    <div class="pricing-cell" dir="rtl" style="font-family:'Tajawal',sans-serif;">25,000 رمز</div>
                @else
                    <div class="pricing-cell"><strong>50,000</strong> credits</div>
                    <div class="pricing-cell">15.000 KWD</div>
                    <div class="pricing-cell">50,000 tokens</div>
                    <div class="pricing-cell">25,000 tokens</div>
                @endif
            </div>
        </div>
    </div>

    <div class="gold-divider"></div>

    {{-- ══ 6. STORY / WHY WE EXIST ══ --}}
    <div class="about-section">
        @if(app()->getLocale() === 'ar')
            <span class="section-overline" dir="rtl" style="font-family:'Tajawal',sans-serif;">قصتنا</span>
            <h2 class="section-title" dir="rtl" style="font-family:'Tajawal',sans-serif;">لماذا بنينا LLM Resayil</h2>
        @else
            <span class="section-overline">Our Story</span>
            <h2 class="section-title">Why We Built LLM Resayil</h2>
        @endif

        <div class="story-grid">
            <div class="story-text">
                @if(app()->getLocale() === 'ar')
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        بدأت القصة من إحباط حقيقي: كان المطورون الكويتيون والعرب يواجهون عقبات يومية للوصول
                        إلى نماذج الذكاء الاصطناعي — من بطاقات ائتمان دولية إلى أسعار بالدولار مرتفعة إلى
                        عدم دعم المنطقة.
                    </p>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        <span class="story-highlight">LLM Resayil وُجد ليحل هذه المشكلة.</span> بنينا منصة
                        تعطيك نفس الجودة التقنية التي تتوقعها من كبار مزودي الذكاء الاصطناعي، لكن
                        بأسعار إقليمية ودعم محلي وواجهة عربية كاملة.
                    </p>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        اليوم، تدعم منصتنا مطورين وشركات ناشئة وفرق مؤسسية عبر المنطقة — تبني تطبيقات
                        ذكاء اصطناعي حقيقية بكفاءة وبتكلفة معقولة.
                    </p>
                @else
                    <p>
                        The story started with a real frustration: developers in Kuwait and across the Arab world
                        faced daily friction accessing AI models — international credit cards, dollar-denominated
                        pricing, and lack of regional support.
                    </p>
                    <p>
                        <span class="story-highlight">LLM Resayil was built to solve exactly that.</span>
                        We created a platform that gives you the same technical quality you'd expect from
                        leading AI providers, but with regional pricing, local payment support, and
                        full bilingual experience.
                    </p>
                    <p>
                        Today, our platform supports developers, startups, and enterprise teams across
                        the region — building real AI-powered applications efficiently and cost-effectively.
                    </p>
                @endif
            </div>

            <div class="story-visual">
                <div class="story-stat-row">
                    <div class="story-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="story-stat-text">
                        @if(app()->getLocale() === 'ar')
                            <div class="story-stat-value" dir="rtl" style="font-family:'Tajawal',sans-serif;">99.5% وقت تشغيل مستهدف</div>
                            <div class="story-stat-sub" dir="rtl" style="font-family:'Tajawal',sans-serif;">بنية تحتية موثوقة مع تجاوز فشل تلقائي</div>
                        @else
                            <div class="story-stat-value">99.5% Uptime Target</div>
                            <div class="story-stat-sub">Reliable infrastructure with automatic failover</div>
                        @endif
                    </div>
                </div>
                <div class="story-stat-row">
                    <div class="story-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/>
                        </svg>
                    </div>
                    <div class="story-stat-text">
                        @if(app()->getLocale() === 'ar')
                            <div class="story-stat-value" dir="rtl" style="font-family:'Tajawal',sans-serif;">45+ نموذج AI</div>
                            <div class="story-stat-sub" dir="rtl" style="font-family:'Tajawal',sans-serif;">من النماذج الخفيفة إلى النماذج الضخمة عالية الأداء</div>
                        @else
                            <div class="story-stat-value">45+ AI Models</div>
                            <div class="story-stat-sub">From lightweight to large high-performance models</div>
                        @endif
                    </div>
                </div>
                <div class="story-stat-row">
                    <div class="story-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <div class="story-stat-text">
                        @if(app()->getLocale() === 'ar')
                            <div class="story-stat-value" dir="rtl" style="font-family:'Tajawal',sans-serif;">متوافق 100% مع OpenAI</div>
                            <div class="story-stat-sub" dir="rtl" style="font-family:'Tajawal',sans-serif;">نفس الصيغة — نقطة نهاية مختلفة فقط</div>
                        @else
                            <div class="story-stat-value">100% OpenAI-Compatible</div>
                            <div class="story-stat-sub">Same format — different endpoint only</div>
                        @endif
                    </div>
                </div>
                <div class="story-stat-row">
                    <div class="story-stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                        </svg>
                    </div>
                    <div class="story-stat-text">
                        @if(app()->getLocale() === 'ar')
                            <div class="story-stat-value" dir="rtl" style="font-family:'Tajawal',sans-serif;">مدفوعات محلية بالدينار الكويتي</div>
                            <div class="story-stat-sub" dir="rtl" style="font-family:'Tajawal',sans-serif;">KNET وبطاقات الائتمان — فوري وبدون عمولات</div>
                        @else
                            <div class="story-stat-value">Local KWD Payments</div>
                            <div class="story-stat-sub">KNET and credit cards — instant, no conversion fees</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gold-divider"></div>

    {{-- ══ 7. CTA STRIP ══ --}}
    <div class="cta-strip">
        <div class="cta-inner">
            @if(app()->getLocale() === 'ar')
                <h2 dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    جاهز <span class="gold-gradient">للبناء؟</span>
                </h2>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    أنشئ حسابك المجاني الآن واحصل على 1,000 رصيد لبدء التجربة — لا تحتاج إلى بطاقة ائتمانية.
                </p>
                <div class="cta-buttons">
                    <a href="/register" class="btn-primary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        إنشاء حساب مجاني
                    </a>
                    <a href="/docs" class="btn-secondary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        استعرض التوثيق
                    </a>
                </div>
            @else
                <h2>Ready to <span class="gold-gradient">Start Building?</span></h2>
                <p>
                    Create your free account and receive 1,000 credits instantly — no credit card required.
                    Start integrating AI models into your application in minutes.
                </p>
                <div class="cta-buttons">
                    <a href="/register" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Create Free Account
                    </a>
                    <a href="/docs" class="btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Explore Documentation
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ══ Footer nav ══ --}}
    <nav class="about-footer-nav">
        <a href="/docs">
            @if(app()->getLocale() === 'ar')
                <span style="font-family:'Tajawal',sans-serif;">التوثيق</span>
            @else
                Documentation
            @endif
        </a>
        <span>·</span>
        <a href="/billing/plans">
            @if(app()->getLocale() === 'ar')
                <span style="font-family:'Tajawal',sans-serif;">الأسعار</span>
            @else
                Pricing
            @endif
        </a>
        <span>·</span>
        <a href="/contact">
            @if(app()->getLocale() === 'ar')
                <span style="font-family:'Tajawal',sans-serif;">التواصل</span>
            @else
                Contact
            @endif
        </a>
        <span>·</span>
        <a href="/terms-of-service">
            @if(app()->getLocale() === 'ar')
                <span style="font-family:'Tajawal',sans-serif;">الشروط والأحكام</span>
            @else
                Terms
            @endif
        </a>
        <span>·</span>
        <a href="/privacy-policy">
            @if(app()->getLocale() === 'ar')
                <span style="font-family:'Tajawal',sans-serif;">الخصوصية</span>
            @else
                Privacy
            @endif
        </a>
    </nav>

</div>
@endsection
