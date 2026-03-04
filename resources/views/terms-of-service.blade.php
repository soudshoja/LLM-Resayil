@extends('layouts.app')

@section('title', __('terms.title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --legal-bg: #090b0f;
        --legal-card: #0e1117;
        --legal-border: #1a1f2e;
        --legal-gold: #d4af37;
        --legal-text: #e2e8f0;
        --legal-muted: #64748b;
        --legal-secondary: #94a3b8;
    }

    .legal-page { display: flex; gap: 0; background: var(--legal-bg); font-family: 'IBM Plex Sans', 'Inter', sans-serif; min-height: 100vh; }

    /* ── Sidebar TOC ── */
    .legal-sidebar {
        width: 260px; flex-shrink: 0;
        position: sticky; top: 64px;
        height: calc(100vh - 64px);
        overflow-y: auto;
        border-right: 1px solid var(--legal-border);
        padding: 2rem 0;
        background: #07090c;
    }
    .toc-header {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem; font-weight: 600; color: var(--legal-muted);
        text-transform: uppercase; letter-spacing: 0.15em;
        padding: 0 1.5rem; margin-bottom: 0.75rem;
    }
    .toc-link {
        display: block;
        padding: 0.45rem 1.5rem;
        font-size: 0.8rem; color: var(--legal-muted);
        text-decoration: none; border-left: 2px solid transparent;
        transition: all 0.2s; cursor: pointer;
        line-height: 1.4;
    }
    .toc-link:hover { color: var(--legal-text); border-left-color: rgba(212,175,55,0.3); background: rgba(212,175,55,0.03); }
    .toc-link.active { color: var(--legal-gold); border-left-color: var(--legal-gold); background: rgba(212,175,55,0.05); }
    .toc-meta {
        padding: 1.5rem 1.5rem 0;
        margin-top: 1.5rem;
        border-top: 1px solid var(--legal-border);
    }
    .toc-meta p { font-size: 0.75rem; color: var(--legal-muted); line-height: 1.6; margin-bottom: 0.4rem; }
    .toc-meta strong { color: var(--legal-secondary); font-weight: 500; }

    /* ── Progress bar ── */
    .reading-progress {
        position: fixed; top: 64px; left: 0; right: 0;
        height: 2px; z-index: 100;
        background: rgba(212,175,55,0.08);
    }
    .reading-progress-bar {
        height: 100%; width: 0%;
        background: linear-gradient(90deg, var(--legal-gold), #eac558);
        transition: width 0.1s linear;
    }

    /* ── Main content ── */
    .legal-main { flex: 1; min-width: 0; padding: 3.5rem 4rem 5rem; max-width: 820px; }

    .legal-hero { margin-bottom: 3rem; padding-bottom: 2.5rem; border-bottom: 1px solid var(--legal-border); }
    .legal-eyebrow {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; font-weight: 600; color: var(--legal-gold);
        text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem; display: block;
    }
    .legal-hero h1 { font-size: 2rem; font-weight: 700; color: var(--legal-text); margin-bottom: 0.75rem; line-height: 1.25; }
    .legal-meta-row { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
    .legal-meta-item { font-size: 0.8rem; color: var(--legal-muted); display: flex; align-items: center; gap: 0.4rem; }
    .legal-meta-item svg { width: 14px; height: 14px; opacity: 0.6; }

    /* ── Sections ── */
    .legal-section { margin-bottom: 3rem; scroll-margin-top: 80px; }
    .legal-section h2 {
        font-size: 1rem; font-weight: 700; color: var(--legal-gold);
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .legal-section h2 .sec-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; background: rgba(212,175,55,0.1);
        border: 1px solid rgba(212,175,55,0.2);
        color: var(--legal-gold); padding: 0.2rem 0.5rem; border-radius: 4px;
        flex-shrink: 0;
    }
    .legal-section p { font-size: 0.9rem; color: var(--legal-secondary); line-height: 1.85; margin-bottom: 0.75rem; }
    .legal-section ul { margin: 0.5rem 0 0.75rem 0; padding: 0; list-style: none; }
    .legal-section ul li {
        font-size: 0.9rem; color: var(--legal-secondary); line-height: 1.75;
        padding: 0.35rem 0 0.35rem 1.25rem; position: relative;
        border-bottom: 1px solid rgba(26,31,46,0.5);
    }
    .legal-section ul li:last-child { border-bottom: none; }
    .legal-section ul li::before {
        content: '→'; position: absolute; left: 0;
        color: rgba(212,175,55,0.4); font-size: 0.75rem; top: 0.42rem;
    }

    /* Highlight boxes */
    .legal-callout {
        background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.15);
        border-left: 3px solid var(--legal-gold);
        border-radius: 0 8px 8px 0; padding: 1rem 1.25rem;
        margin: 1rem 0;
    }
    .legal-callout p { color: var(--legal-text); font-size: 0.875rem; margin: 0; }
    .legal-callout strong { color: var(--legal-gold); }

    /* ── Footer nav ── */
    .legal-footer { padding-top: 2.5rem; border-top: 1px solid var(--legal-border); text-align: center; }
    .legal-footer a { font-size: 0.8rem; color: var(--legal-muted); text-decoration: none; transition: color 0.2s; margin: 0 0.5rem; }
    .legal-footer a:hover { color: var(--legal-text); }

    @media (max-width: 900px) {
        .legal-sidebar { display: none; }
        .legal-main { padding: 2rem 1.5rem 4rem; max-width: 100%; }
    }
    @media (max-width: 480px) { .legal-main { padding: 1.5rem 1rem 3rem; } }
</style>
@endpush

@section('content')

<div class="reading-progress"><div class="reading-progress-bar" id="progressBar"></div></div>

<div class="legal-page">

    {{-- ── Sidebar ── --}}
    <aside class="legal-sidebar">
        <div class="toc-header">{{ __('terms.toc_header') }}</div>
        <a class="toc-link active" href="#acceptance" onclick="scrollTo('acceptance')">{{ __('terms.toc_acceptance') }}</a>
        <a class="toc-link" href="#service" onclick="scrollTo('service')">{{ __('terms.toc_service') }}</a>
        <a class="toc-link" href="#account" onclick="scrollTo('account')">{{ __('terms.toc_account') }}</a>
        <a class="toc-link" href="#credits" onclick="scrollTo('credits')">{{ __('terms.toc_credits') }}</a>
        <a class="toc-link" href="#usage" onclick="scrollTo('usage')">{{ __('terms.toc_usage') }}</a>
        <a class="toc-link" href="#prohibited" onclick="scrollTo('prohibited')">{{ __('terms.toc_prohibited') }}</a>
        <a class="toc-link" href="#availability" onclick="scrollTo('availability')">{{ __('terms.toc_availability') }}</a>
        <a class="toc-link" href="#termination" onclick="scrollTo('termination')">{{ __('terms.toc_termination') }}</a>
        <a class="toc-link" href="#changes" onclick="scrollTo('changes')">{{ __('terms.toc_changes') }}</a>
        <a class="toc-link" href="#contact" onclick="scrollTo('contact')">{{ __('terms.toc_contact') }}</a>
        <div class="toc-meta">
            <p><strong>{{ __('terms.toc_last_updated') }}</strong><br>{{ __('terms.toc_last_updated_value') }}</p>
            <p><strong>{{ __('terms.toc_version') }}</strong><br>{{ __('terms.toc_version_value') }}</p>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <main class="legal-main" id="legalContent">

        <div class="legal-hero">
            <span class="legal-eyebrow">{{ __('terms.eyebrow') }}</span>
            <h1>{{ __('terms.title') }}</h1>
            <div class="legal-meta-row">
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ __('terms.last_updated') }}
                </span>
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    {{ __('terms.read_time') }}
                </span>
                <span class="legal-meta-item">{{ __('terms.brand') }}</span>
            </div>
        </div>

        <div class="legal-section" id="acceptance">
            <h2><span class="sec-num">{{ __('terms.sec_01') }}</span> {{ __('terms.acceptance_title') }}</h2>
            <p>{{ __('terms.acceptance_desc') }}</p>
        </div>

        <div class="legal-section" id="service">
            <h2><span class="sec-num">{{ __('terms.sec_02') }}</span> {{ __('terms.service_title') }}</h2>
            <p>{{ __('terms.service_desc') }}</p>
            <ul>
                <li>{{ __('terms.service_item_api') }}</li>
                <li>{{ __('terms.service_item_portal') }}</li>
                <li>{{ __('terms.service_item_billing') }}</li>
                <li>{{ __('terms.service_item_otp') }}</li>
            </ul>
        </div>

        <div class="legal-section" id="account">
            <h2><span class="sec-num">{{ __('terms.sec_03') }}</span> {{ __('terms.account_title') }}</h2>
            <p>{{ __('terms.account_desc') }}</p>
            <p>{{ __('terms.account_unauthorized') }}</p>
        </div>

        <div class="legal-section" id="credits">
            <h2><span class="sec-num">{{ __('terms.sec_04') }}</span> {{ __('terms.credits_title') }}</h2>
            <p>{{ __('terms.credits_desc') }}</p>
            <ul>
                <li>{{ __('terms.credits_tier_1') }}</li>
                <li>{{ __('terms.credits_tier_2') }}</li>
                <li>{{ __('terms.credits_tier_3') }}</li>
            </ul>
            <div class="legal-callout">
                <p>{!! __('terms.credits_callout') !!}</p>
            </div>
        </div>

        <div class="legal-section" id="usage">
            <h2><span class="sec-num">{{ __('terms.sec_05') }}</span> {{ __('terms.usage_title') }}</h2>
            <p>{{ __('terms.usage_desc') }}</p>
            <p>{{ __('terms.usage_abuse') }}</p>
        </div>

        <div class="legal-section" id="prohibited">
            <h2><span class="sec-num">{{ __('terms.sec_06') }}</span> {{ __('terms.prohibited_title') }}</h2>
            <p>{{ __('terms.prohibited_desc') }}</p>
            <ul>
                <li>{{ __('terms.prohibited_illegal') }}</li>
                <li>{{ __('terms.prohibited_law') }}</li>
                <li>{{ __('terms.prohibited_ip') }}</li>
                <li>{{ __('terms.prohibited_reverse') }}</li>
                <li>{{ __('terms.prohibited_resell') }}</li>
                <li>{{ __('terms.prohibited_bypass') }}</li>
            </ul>
        </div>

        <div class="legal-section" id="availability">
            <h2><span class="sec-num">{{ __('terms.sec_07') }}</span> {{ __('terms.availability_title') }}</h2>
            <p>{{ __('terms.availability_desc') }}</p>
        </div>

        <div class="legal-section" id="termination">
            <h2><span class="sec-num">{{ __('terms.sec_08') }}</span> {{ __('terms.termination_title') }}</h2>
            <p>{{ __('terms.termination_desc') }}</p>
        </div>

        <div class="legal-section" id="changes">
            <h2><span class="sec-num">{{ __('terms.sec_09') }}</span> {{ __('terms.changes_title') }}</h2>
            <p>{{ __('terms.changes_desc') }}</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="sec-num">{{ __('terms.sec_10') }}</span> {{ __('terms.contact_title') }}</h2>
            <p>{!! __('terms.contact_desc') !!}</p>
        </div>

        <div class="legal-footer">
            <a href="/about">{{ __('terms.footer_about') }}</a>
            <a href="/privacy-policy">{{ __('terms.footer_privacy') }}</a>
            <a href="/contact">{{ __('terms.footer_contact') }}</a>
        </div>

    </main>
</div>

@push('scripts')
<script>
// Reading progress
const bar = document.getElementById('progressBar');
const content = document.getElementById('legalContent');
window.addEventListener('scroll', () => {
    const rect = content.getBoundingClientRect();
    const total = content.offsetHeight - window.innerHeight;
    const progress = Math.min(100, Math.max(0, (-rect.top / total) * 100));
    bar.style.width = progress + '%';
});

// TOC scroll-spy
const sections = document.querySelectorAll('.legal-section[id]');
const tocLinks = document.querySelectorAll('.toc-link');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            tocLinks.forEach(l => l.classList.remove('active'));
            const active = document.querySelector(`.toc-link[href="#${e.target.id}"]`);
            if (active) active.classList.add('active');
        }
    });
}, { rootMargin: '-20% 0px -75% 0px' });
sections.forEach(s => observer.observe(s));

// Smooth scroll
function scrollTo(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endpush
@endsection
