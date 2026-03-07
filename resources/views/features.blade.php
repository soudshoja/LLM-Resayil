@extends('layouts.app')

@section('title', $pageTitle ?? __('features.page_title'))

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   FEATURES PAGE — COMPLETE REDESIGN
   Dark Luxury · Gold Accents · Premium SaaS
══════════════════════════════════════════════════ */

/* ── Shell ── */
.feat-page { background: var(--bg-secondary); min-height: 100vh; }

/* ── Shared helpers ── */
.ft-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: var(--gold); margin-bottom: 1rem;
}
.ft-label::before { content: ''; display: block; width: 18px; height: 2px; background: var(--gold); border-radius: 2px; }
.ft-h2 {
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 800; line-height: 1.15;
    letter-spacing: -0.025em; margin-bottom: 0.85rem;
}
.ft-h2 span {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.ft-sub { font-size: 1rem; color: var(--text-secondary); line-height: 1.7; max-width: 600px; }
.ft-section { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 5rem; }
.ft-center { text-align: center; }
.ft-center .ft-sub { margin-left: auto; margin-right: auto; }

/* ── Scroll-reveal ── */
.fu { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fu.on { opacity: 1; transform: none; }
.d1 { transition-delay: 0.10s; }
.d2 { transition-delay: 0.20s; }
.d3 { transition-delay: 0.30s; }
@media (prefers-reduced-motion: reduce) { .fu { opacity:1; transform:none; transition:none; } }

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.feat-hero {
    position: relative; overflow: hidden;
    padding: 5.5rem 1.5rem 4.5rem;
    text-align: center;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(212,175,55,0.08) 0%, transparent 70%);
}
.feat-hero::after {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,0.016) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,0.016) 1px, transparent 1px);
    background-size: 72px 72px;
}
.feat-hero-inner { max-width: 780px; margin: 0 auto; position: relative; z-index: 1; }
.feat-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.22);
    border-radius: 100px; padding: 0.45rem 1.1rem;
    font-size: 0.82rem; font-weight: 600; color: var(--gold);
    margin-bottom: 1.75rem;
}
.feat-eyebrow svg { width: 14px; height: 14px; }
.feat-hero-h1 {
    font-size: clamp(2.4rem, 6vw, 4rem);
    font-weight: 900; line-height: 1.08; letter-spacing: -0.03em;
    margin-bottom: 1.25rem;
}
.feat-hero-h1 span {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.feat-hero-lead { font-size: 1.1rem; color: var(--text-secondary); line-height: 1.72; margin-bottom: 2.75rem; max-width: 640px; margin-left: auto; margin-right: auto; }

/* Hero stats row */
.feat-stats {
    display: flex; align-items: center; justify-content: center;
    gap: 2.5rem; flex-wrap: wrap;
}
.feat-stat-item { text-align: center; }
.feat-stat-val {
    display: block; font-size: 2.2rem; font-weight: 900;
    color: var(--gold); line-height: 1; margin-bottom: 0.3rem;
}
.feat-stat-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; letter-spacing: 0.02em; }
.feat-stats-sep { width: 1px; height: 40px; background: var(--border); }
@media (max-width: 520px) { .feat-stats-sep { display: none; } }

/* ═══════════════════════════════════════
   FEATURE GRID (9 cards)
═══════════════════════════════════════ */
.feat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
@media (max-width: 900px) { .feat-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .feat-grid { grid-template-columns: 1fr; } }

.feat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px; padding: 2rem;
    position: relative; overflow: hidden;
    transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
    cursor: default;
}
.feat-card::before {
    content: ''; position: absolute; inset: 0; border-radius: 20px;
    background: linear-gradient(135deg, rgba(212,175,55,0.06) 0%, transparent 60%);
    opacity: 0; transition: opacity 0.3s;
}
.feat-card:hover {
    border-color: rgba(212,175,55,0.35);
    box-shadow: 0 0 32px rgba(212,175,55,0.08), 0 12px 40px rgba(0,0,0,0.3);
    transform: translateY(-4px);
}
.feat-card:hover::before { opacity: 1; }
.feat-card.wide { grid-column: span 2; }
@media (max-width: 900px) { .feat-card.wide { grid-column: span 1; } }

.feat-card-icon {
    width: 48px; height: 48px;
    background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.25rem; transition: all 0.3s; position: relative;
}
.feat-card:hover .feat-card-icon { background: rgba(212,175,55,0.18); box-shadow: 0 0 18px rgba(212,175,55,0.22); }
.feat-card-icon svg { width: 22px; height: 22px; color: var(--gold); }
.feat-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; position: relative; }
.feat-card p  { font-size: 0.88rem; color: var(--text-secondary); line-height: 1.65; position: relative; }

/* ═══════════════════════════════════════
   46+ MODELS HIGHLIGHT
═══════════════════════════════════════ */
.models-highlight {
    background: linear-gradient(140deg, var(--bg-card), rgba(212,175,55,0.04));
    border: 1px solid rgba(212,175,55,0.3);
    border-radius: 24px; padding: 3rem 2.5rem;
    position: relative; overflow: hidden;
}
.models-highlight::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.models-highlight-inner {
    display: grid; grid-template-columns: auto 1fr;
    gap: 3rem; align-items: center;
}
@media (max-width: 720px) { .models-highlight-inner { grid-template-columns: 1fr; gap: 2rem; text-align: center; } }
.models-big-num {
    font-size: clamp(4rem, 10vw, 6.5rem);
    font-weight: 900; line-height: 1;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    letter-spacing: -0.03em;
}
.models-right h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; }
.models-right p  { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem; }
.models-cats {
    display: flex; flex-wrap: wrap; gap: 0.5rem;
}
.model-cat-tag {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(255,255,255,0.04); border: 1px solid var(--border);
    border-radius: 100px; padding: 0.35rem 0.9rem;
    font-size: 0.78rem; font-weight: 600; color: var(--text-secondary);
    transition: border-color 0.2s, color 0.2s;
}
.model-cat-tag:hover { border-color: rgba(212,175,55,0.3); color: var(--gold); }
.model-cat-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--gold); opacity: 0.6; }

/* ═══════════════════════════════════════
   INTEGRATION SECTION
═══════════════════════════════════════ */
.integration-wrap {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px; overflow: hidden;
}
.integration-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0; align-items: stretch;
}
@media (max-width: 760px) { .integration-inner { grid-template-columns: 1fr; } }

.integration-left {
    padding: 2.5rem;
    border-right: 1px solid var(--border);
}
@media (max-width: 760px) { .integration-left { border-right: none; border-bottom: 1px solid var(--border); } }
[dir="rtl"] .integration-left { border-right: none; border-left: 1px solid var(--border); }
@media (max-width: 760px) { [dir="rtl"] .integration-left { border-left: none; border-bottom: 1px solid var(--border); } }

.integration-left h3 { font-size: 1.3rem; font-weight: 800; margin-bottom: 0.75rem; }
.integration-left p  { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.7; margin-bottom: 1.5rem; }
.compat-list { list-style: none; }
.compat-list li {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.88rem; color: var(--text-secondary); padding: 0.35rem 0;
}
.compat-list .chk { color: var(--gold); font-weight: 700; flex-shrink: 0; }

.integration-right { padding: 0; background: #080b10; }
.code-topbar-2 {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.25rem;
    background: rgba(255,255,255,0.03);
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.code-dots-2 { display: flex; gap: 0.45rem; }
.code-dot-2 { width: 11px; height: 11px; border-radius: 50%; }
.code-dot-2.r { background: #ff5f57; }
.code-dot-2.y { background: #febc2e; }
.code-dot-2.g { background: #28c840; }
.code-fname { font-size: 0.76rem; color: var(--text-muted); font-family: 'Courier New', monospace; }
.code-body-2 {
    padding: 1.5rem 1.75rem;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem; line-height: 1.9;
    overflow-x: auto;
}
.code-body-2 .cc { color: var(--text-muted); }
.code-body-2 .k  { color: #c792ea; }
.code-body-2 .kw { color: #7dd3fc; }
.code-body-2 .s  { color: #a5f3a0; }
.code-body-2 .n  { color: #e0e5ec; }
.code-body-2 .go { color: var(--gold); }

/* ═══════════════════════════════════════
   COMPARISON TABLE
═══════════════════════════════════════ */
.compare-table-wrap {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden; margin-top: 2.5rem;
    overflow-x: auto;
}
.compare-table {
    width: 100%; border-collapse: collapse; min-width: 560px;
}
.compare-table thead tr { background: rgba(0,0,0,0.25); border-bottom: 1px solid var(--border); }
.compare-table th {
    padding: 1rem 1.25rem; text-align: left;
    font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted);
}
.compare-table th:not(:first-child) { text-align: center; }
.compare-table td {
    padding: 1rem 1.25rem; font-size: 0.88rem;
    color: var(--text-secondary); border-bottom: 1px solid var(--border);
}
.compare-table td:not(:first-child) { text-align: center; }
.compare-table tbody tr:last-child td { border-bottom: none; }
.compare-table tbody tr:hover td { background: rgba(255,255,255,0.012); }
.yes { color: #6ee7b7; font-weight: 700; }
.no  { color: var(--text-muted); }
.ours { color: var(--gold); font-weight: 700; }

/* ═══════════════════════════════════════
   FINAL CTA
═══════════════════════════════════════ */
.feat-cta-band {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    border-radius: 24px; padding: 4.5rem 2.5rem;
    text-align: center; position: relative; overflow: hidden;
}
.feat-cta-band::before {
    content: ''; position: absolute; inset: 0; border-radius: 24px;
    background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.12) 100%);
    pointer-events: none;
}
.feat-cta-band h2 {
    font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 900;
    color: #0a0d14; letter-spacing: -0.02em; line-height: 1.15;
    margin-bottom: 1rem; position: relative;
}
.feat-cta-band p {
    font-size: 1rem; color: rgba(10,13,20,0.7);
    margin-bottom: 2.25rem; line-height: 1.7; position: relative;
}
.feat-cta-btns { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; }
.btn-cta-dark {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: #0a0d14; color: var(--gold);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-dark:hover { background: #13161d; color: var(--gold); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
.btn-cta-outline {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: transparent; color: #0a0d14;
    border: 2px solid rgba(10,13,20,0.3);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-outline:hover { background: rgba(10,13,20,0.08); border-color: rgba(10,13,20,0.5); color: #0a0d14; }

/* ── RTL overrides ── */
[dir="rtl"] .ft-label::before { display: none; }
[dir="rtl"] .ft-label::after  { content: ''; display: block; width: 18px; height: 2px; background: var(--gold); border-radius: 2px; }
[dir="rtl"] .compat-list li   { flex-direction: row-reverse; }
[dir="rtl"] .compare-table th,
[dir="rtl"] .compare-table td { text-align: right; }
[dir="rtl"] .compare-table th:not(:first-child),
[dir="rtl"] .compare-table td:not(:first-child) { text-align: center; }
</style>
@endpush

@section('content')
<div class="feat-page">

    <!-- ═══════════════════════════════════════
         HERO
    ═══════════════════════════════════════ -->
    <div class="feat-hero">
        <div class="feat-hero-inner">
            @if(app()->getLocale() === 'ar')
                <div class="feat-eyebrow" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    قدرات المنصة
                </div>
                <h1 class="feat-hero-h1" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    منصة API واحدة. <span>46+ نموذج.</span>
                </h1>
                <p class="feat-hero-lead" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    اوصل إلى أكبر مكتبة نماذج ذكاء اصطناعي مفتوحة المصدر عبر واجهة API واحدة متوافقة مع OpenAI. سرعة، موثوقية، وتسعير بالدفع مقابل الاستخدام.
                </p>
                <div class="feat-stats" dir="rtl">
                    <div class="feat-stat-item">
                        <span class="feat-stat-val" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_models_value') }}</span>
                        <div class="feat-stat-label" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_models_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_endpoint_value') }}</span>
                        <div class="feat-stat-label" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_endpoint_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_credits_value') }}</span>
                        <div class="feat-stat-label" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_credits_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_fees_value') }}</span>
                        <div class="feat-stat-label" style="font-family:'Tajawal',sans-serif;">{{ __('features.stat_fees_label') }}</div>
                    </div>
                </div>
            @else
                <div class="feat-eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    Platform Capabilities
                </div>
                <h1 class="feat-hero-h1">
                    One API. <span>46+ AI Models.</span>
                </h1>
                <p class="feat-hero-lead">
                    Access the largest library of open-source AI models through a single OpenAI-compatible API. Fast, reliable, pay-per-use infrastructure built for developers.
                </p>
                <div class="feat-stats">
                    <div class="feat-stat-item">
                        <span class="feat-stat-val">{{ __('features.stat_models_value') }}</span>
                        <div class="feat-stat-label">{{ __('features.stat_models_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val">{{ __('features.stat_endpoint_value') }}</span>
                        <div class="feat-stat-label">{{ __('features.stat_endpoint_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val">{{ __('features.stat_credits_value') }}</span>
                        <div class="feat-stat-label">{{ __('features.stat_credits_label') }}</div>
                    </div>
                    <div class="feat-stats-sep" aria-hidden="true"></div>
                    <div class="feat-stat-item">
                        <span class="feat-stat-val">{{ __('features.stat_fees_value') }}</span>
                        <div class="feat-stat-label">{{ __('features.stat_fees_label') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         FEATURE GRID (9 cards)
    ═══════════════════════════════════════ -->
    <section class="ft-section" style="padding-top:4rem;">
        <div class="ft-center" style="margin-bottom:3rem;">
            @if(app()->getLocale() === 'ar')
                <div class="ft-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">{{ __('features.core_label') }}</div>
                <h2 class="ft-h2 fu" dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.core_title') }}</h2>
                <p class="ft-sub fu d1" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">{{ __('features.core_subtitle') }}</p>
            @else
                <div class="ft-label fu" style="justify-content:center;">{{ __('features.core_label') }}</div>
                <h2 class="ft-h2 fu d1">{{ __('features.core_title') }}</h2>
                <p class="ft-sub fu d2" style="margin:0 auto;">{{ __('features.core_subtitle') }}</p>
            @endif
        </div>

        <div class="feat-grid">
            <!-- 1: API Keys -->
            <div class="feat-card fu">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_keys_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_keys_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_keys_title') }}</h3>
                    <p>{{ __('features.feat_keys_desc') }}</p>
                @endif
            </div>

            <!-- 2: Dashboard -->
            <div class="feat-card fu d1">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_dashboard_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_dashboard_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_dashboard_title') }}</h3>
                    <p>{{ __('features.feat_dashboard_desc') }}</p>
                @endif
            </div>

            <!-- 3: 46+ Models -->
            <div class="feat-card fu d2">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_models_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_models_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_models_title') }}</h3>
                    <p>{{ __('features.feat_models_desc') }}</p>
                @endif
            </div>

            <!-- 4: Credits (wide) -->
            <div class="feat-card wide fu" style="background: linear-gradient(140deg, var(--bg-card), rgba(212,175,55,0.04));">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_credits_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_credits_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_credits_title') }}</h3>
                    <p>{{ __('features.feat_credits_desc') }}</p>
                @endif
            </div>

            <!-- 5: Auth & Security -->
            <div class="feat-card fu d1">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_auth_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_auth_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_auth_title') }}</h3>
                    <p>{{ __('features.feat_auth_desc') }}</p>
                @endif
            </div>

            <!-- 6: Streaming -->
            <div class="feat-card fu d2">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">البث المباشر (Streaming)</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">بث الردود في الوقت الفعلي عبر SSE، مثل OpenAI تماماً. مثالي لتطبيقات الدردشة والمساعدين الذكيين.</p>
                @else
                    <h3>Real-Time Streaming</h3>
                    <p>Server-sent events streaming for real-time responses, identical to OpenAI. Perfect for chat apps and AI assistants.</p>
                @endif
            </div>

            <!-- 7: Language -->
            <div class="feat-card fu">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_lang_title') }}</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.feat_lang_desc') }}</p>
                @else
                    <h3>{{ __('features.feat_lang_title') }}</h3>
                    <p>{{ __('features.feat_lang_desc') }}</p>
                @endif
            </div>

            <!-- 8: Rate limiting -->
            <div class="feat-card fu d1">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">حدود معدل الطلبات</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">حدود واضحة ومتدرجة لمعدل الطلبات. لا إنفاق مفاجئ، لا حصص مبهمة — شفافية كاملة على الاستخدام.</p>
                @else
                    <h3>Transparent Rate Limits</h3>
                    <p>Clear, tiered rate limits per account. No surprise overages, no opaque quotas — full visibility into your usage at all times.</p>
                @endif
            </div>

            <!-- 9: Docs & SDKs -->
            <div class="feat-card fu d2">
                <div class="feat-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">توثيق شامل</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">توثيق كامل مع أمثلة بلغات متعددة. يعمل مع Python SDK وJavaScript SDK وأي عميل HTTP.</p>
                @else
                    <h3>Full Documentation</h3>
                    <p>Comprehensive docs with examples in multiple languages. Works with the Python SDK, JavaScript SDK, and any HTTP client.</p>
                @endif
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         46+ AI MODELS HIGHLIGHT
    ═══════════════════════════════════════ -->
    <section class="ft-section" style="padding-top:1rem;">
        <div class="models-highlight fu">
            <div class="models-highlight-inner">
                <div>
                    <div class="models-big-num">46+</div>
                </div>
                <div class="models-right">
                    @if(app()->getLocale() === 'ar')
                        <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.models_title') }}</h3>
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.models_subtitle') }} <code style="background:rgba(255,255,255,0.06); padding:0.1rem 0.4rem; border-radius:4px; font-size:0.85em;">/v1/models</code> {{ __('features.models_subtitle_suffix') }}</p>
                        <div class="models-cats" dir="rtl">
                            <div class="model-cat-tag" style="font-family:'Tajawal',sans-serif;"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_chat') }}</div>
                            <div class="model-cat-tag" style="font-family:'Tajawal',sans-serif;"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_coding') }}</div>
                            <div class="model-cat-tag" style="font-family:'Tajawal',sans-serif;"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_reasoning') }}</div>
                            <div class="model-cat-tag" style="font-family:'Tajawal',sans-serif;"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_multilingual') }}</div>
                        </div>
                    @else
                        <h3>{{ __('features.models_title') }}</h3>
                        <p>{{ __('features.models_subtitle') }} <code style="background:rgba(255,255,255,0.06); padding:0.1rem 0.4rem; border-radius:4px; font-size:0.85em;">/v1/models</code> {{ __('features.models_subtitle_suffix') }}</p>
                        <div class="models-cats">
                            <div class="model-cat-tag"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_chat') }}</div>
                            <div class="model-cat-tag"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_coding') }}</div>
                            <div class="model-cat-tag"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_reasoning') }}</div>
                            <div class="model-cat-tag"><div class="model-cat-dot" aria-hidden="true"></div>{{ __('features.cat_multilingual') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         INTEGRATION SECTION
    ═══════════════════════════════════════ -->
    <section class="ft-section" style="padding-top:2rem;">
        <div class="ft-center" style="margin-bottom:2.5rem;">
            @if(app()->getLocale() === 'ar')
                <div class="ft-label fu" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">التكامل</div>
                <h2 class="ft-h2 fu d1" dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.compat_title') }}</h2>
            @else
                <div class="ft-label fu" style="justify-content:center;">Integration</div>
                <h2 class="ft-h2 fu d1">{{ __('features.compat_title') }}</h2>
            @endif
        </div>

        <div class="integration-wrap fu d2">
            <div class="integration-inner">
                <div class="integration-left">
                    @if(app()->getLocale() === 'ar')
                        <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.compat_title') }}</h3>
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.compat_desc') }}</p>
                        <ul class="compat-list" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <li><span class="chk">✓</span> <code>/v1/chat/completions</code> {{ __('features.compat_item_chat') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_streaming') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_models') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_auth') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_sdks') }}</li>
                        </ul>
                    @else
                        <h3>{{ __('features.compat_title') }}</h3>
                        <p>{{ __('features.compat_desc') }}</p>
                        <ul class="compat-list">
                            <li><span class="chk">✓</span> <code>/v1/chat/completions</code> {{ __('features.compat_item_chat') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_streaming') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_models') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_auth') }}</li>
                            <li><span class="chk">✓</span> {{ __('features.compat_item_sdks') }}</li>
                        </ul>
                    @endif
                </div>
                <div class="integration-right">
                    <div class="code-topbar-2">
                        <div class="code-dots-2" aria-hidden="true">
                            <div class="code-dot-2 r"></div>
                            <div class="code-dot-2 y"></div>
                            <div class="code-dot-2 g"></div>
                        </div>
                        <span class="code-fname">example.py</span>
                        <span style="font-size:0.72rem; color:var(--text-muted);">OpenAI SDK</span>
                    </div>
                    <div class="code-body-2" role="region" aria-label="{{ app()->getLocale() === 'ar' ? 'مثال كود' : 'Code example' }}">
<span class="cc"># {{ app()->getLocale() === 'ar' ? 'متوافق مع OpenAI — فقط غيّر base_url' : 'OpenAI-compatible — just change base_url' }}</span>
<span class="k">from</span> <span class="n">openai</span> <span class="k">import</span> <span class="n">OpenAI</span>

<span class="n">client</span> = <span class="n">OpenAI</span>(
    <span class="kw">base_url</span>=<span class="go">"https://llm.resayil.io/api/v1"</span>,
    <span class="kw">api_key</span>=<span class="s">"your-api-key"</span>,
)

<span class="n">response</span> = <span class="n">client</span>.<span class="n">chat</span>.<span class="n">completions</span>.<span class="n">create</span>(
    <span class="kw">model</span>=<span class="s">"llama3.1:8b"</span>,
    <span class="kw">messages</span>=[{
        <span class="s">"role"</span>: <span class="s">"user"</span>,
        <span class="s">"content"</span>: <span class="s">"{{ app()->getLocale() === 'ar' ? 'مرحباً' : 'Hello!' }}"</span>
    }]
)
<span class="n">print</span>(<span class="n">response</span>.<span class="n">choices</span>[<span class="s">0</span>].<span class="n">message</span>.<span class="n">content</span>)
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         COMPARISON TABLE
    ═══════════════════════════════════════ -->
    <section class="ft-section" style="padding-top:1rem;">
        <div class="ft-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="ft-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">{{ __('features.compare_label') }}</div>
                <h2 class="ft-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.compare_title') }}</h2>
                <p class="ft-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">{{ __('features.compare_subtitle') }}</p>
            @else
                <div class="ft-label" style="justify-content:center;">{{ __('features.compare_label') }}</div>
                <h2 class="ft-h2">{{ __('features.compare_title') }}</h2>
                <p class="ft-sub" style="margin:0 auto;">{{ __('features.compare_subtitle') }}</p>
            @endif
        </div>

        <div class="compare-table-wrap fu d1">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>{{ __('features.table_feature') }}</th>
                        <th style="color:var(--gold);">{{ __('features.table_us') }}</th>
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
        </div>

        <div style="text-align:center; margin-top:1.25rem;">
            @if(app()->getLocale() === 'ar')
                <a href="/comparison" style="color:var(--gold); font-size:0.88rem; font-weight:600; font-family:'Tajawal',sans-serif;" dir="rtl">{{ __('features.full_comparison_link') }} &larr;</a>
            @else
                <a href="/comparison" style="color:var(--gold); font-size:0.88rem; font-weight:600;">{{ __('features.full_comparison_link') }} &rarr;</a>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         FINAL CTA BAND
    ═══════════════════════════════════════ -->
    <section class="ft-section" style="padding-top:1rem; padding-bottom:4rem;">
        <div class="feat-cta-band fu">
            @if(app()->getLocale() === 'ar')
                <h2 dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.cta_title') }}</h2>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ __('features.cta_desc') }}</p>
                <div class="feat-cta-btns">
                    <a href="/register" class="btn-cta-dark" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        {{ __('features.cta_register') }}
                    </a>
                    <a href="/docs" class="btn-cta-outline" style="font-family:'Tajawal',sans-serif;">{{ __('features.cta_docs') }}</a>
                </div>
            @else
                <h2>{{ __('features.cta_title') }}</h2>
                <p>{{ __('features.cta_desc') }}</p>
                <div class="feat-cta-btns">
                    <a href="/register" class="btn-cta-dark">
                        {{ __('features.cta_register') }}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <a href="/docs" class="btn-cta-outline">{{ __('features.cta_docs') }}</a>
                </div>
            @endif
        </div>
    </section>

</div>

<!-- Product Schema -->
<script type="application/ld+json">
@php
$product = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => 'LLM Resayil API',
    'url' => url('/features'),
    'description' => 'OpenAI-compatible AI models API with 46+ models, pay-per-token pricing, and no monthly subscriptions.',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.8',
        'ratingCount' => '250',
        'bestRating' => '5',
        'worstRating' => '1'
    ],
    'hasFeature' => [
        ['@type' => 'PropertyValue', 'name' => '46+ AI Models', 'description' => 'Access 46+ OpenAI-compatible AI models including Llama, Qwen, Gemma, Mistral, DeepSeek, and more.'],
        ['@type' => 'PropertyValue', 'name' => 'Pay-Per-Use Pricing', 'description' => 'Only pay for output tokens consumed. Input tokens are free. Credits never expire.'],
        ['@type' => 'PropertyValue', 'name' => 'OpenAI-Compatible REST API', 'description' => 'Drop-in replacement with identical API format. Works with Python SDK, JavaScript SDK, and cURL.'],
        ['@type' => 'PropertyValue', 'name' => 'Real-Time Dashboard', 'description' => 'Live usage statistics, cost breakdown, and API activity monitoring.'],
        ['@type' => 'PropertyValue', 'name' => 'Arabic & English UI', 'description' => 'Full RTL support for Arabic. Dashboard, billing, and documentation available in both languages.']
    ]
];
@endphp
{!! json_encode($product, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<script>
(function () {
    'use strict';
    /* Scroll-reveal */
    var fus = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('on'); io.unobserve(en.target); }
            });
        }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
        fus.forEach(function (el) { io.observe(el); });
    } else {
        fus.forEach(function (el) { el.classList.add('on'); });
    }
})();
</script>
@endsection
