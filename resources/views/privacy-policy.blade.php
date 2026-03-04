@extends('layouts.app')

@section('title', __('privacy.page_title'))

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
        transition: all 0.2s; cursor: pointer; line-height: 1.4;
    }
    .toc-link:hover { color: var(--legal-text); border-left-color: rgba(212,175,55,0.3); background: rgba(212,175,55,0.03); }
    .toc-link.active { color: var(--legal-gold); border-left-color: var(--legal-gold); background: rgba(212,175,55,0.05); }
    .toc-meta { padding: 1.5rem 1.5rem 0; margin-top: 1.5rem; border-top: 1px solid var(--legal-border); }
    .toc-meta p { font-size: 0.75rem; color: var(--legal-muted); line-height: 1.6; margin-bottom: 0.4rem; }
    .toc-meta strong { color: var(--legal-secondary); font-weight: 500; }

    /* ── Progress bar ── */
    .reading-progress { position: fixed; top: 64px; left: 0; right: 0; height: 2px; z-index: 100; background: rgba(212,175,55,0.08); }
    .reading-progress-bar { height: 100%; width: 0%; background: linear-gradient(90deg, var(--legal-gold), #eac558); transition: width 0.1s linear; }

    /* ── Main content ── */
    .legal-main { flex: 1; min-width: 0; padding: 3.5rem 4rem 5rem; max-width: 820px; }

    .legal-hero { margin-bottom: 3rem; padding-bottom: 2.5rem; border-bottom: 1px solid var(--legal-border); }
    .legal-eyebrow { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; font-weight: 600; color: var(--legal-gold); text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem; display: block; }
    .legal-hero h1 { font-size: 2rem; font-weight: 700; color: var(--legal-text); margin-bottom: 0.75rem; line-height: 1.25; }
    .legal-meta-row { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
    .legal-meta-item { font-size: 0.8rem; color: var(--legal-muted); display: flex; align-items: center; gap: 0.4rem; }
    .legal-meta-item svg { width: 14px; height: 14px; opacity: 0.6; }

    /* ── Sections ── */
    .legal-section { margin-bottom: 3rem; scroll-margin-top: 80px; }
    .legal-section h2 { font-size: 1rem; font-weight: 700; color: var(--legal-gold); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; }
    .legal-section h2 .sec-num { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.2); color: var(--legal-gold); padding: 0.2rem 0.5rem; border-radius: 4px; flex-shrink: 0; }
    .legal-section p { font-size: 0.9rem; color: var(--legal-secondary); line-height: 1.85; margin-bottom: 0.75rem; }
    .legal-section ul { margin: 0.5rem 0 0.75rem 0; padding: 0; list-style: none; }
    .legal-section ul li { font-size: 0.9rem; color: var(--legal-secondary); line-height: 1.75; padding: 0.35rem 0 0.35rem 1.25rem; position: relative; border-bottom: 1px solid rgba(26,31,46,0.5); }
    .legal-section ul li:last-child { border-bottom: none; }
    .legal-section ul li::before { content: '→'; position: absolute; left: 0; color: rgba(212,175,55,0.4); font-size: 0.75rem; top: 0.42rem; }

    /* Data table */
    .data-table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: 0.875rem; }
    .data-table th { text-align: left; padding: 0.65rem 1rem; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--legal-muted); background: rgba(0,0,0,0.3); border-bottom: 1px solid var(--legal-border); }
    .data-table td { padding: 0.75rem 1rem; color: var(--legal-secondary); border-bottom: 1px solid rgba(26,31,46,0.4); vertical-align: top; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:nth-child(even) td { background: rgba(255,255,255,0.01); }
    .data-table strong { color: var(--legal-text); font-weight: 500; }

    /* Callout */
    .legal-callout { background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.15); border-left: 3px solid var(--legal-gold); border-radius: 0 8px 8px 0; padding: 1rem 1.25rem; margin: 1rem 0; }
    .legal-callout p { color: var(--legal-text); font-size: 0.875rem; margin: 0; }
    .legal-callout strong { color: var(--legal-gold); }

    /* Third-party chips */
    .third-party-list { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.75rem; }
    .tp-item { background: var(--legal-card); border: 1px solid var(--legal-border); border-radius: 8px; padding: 0.85rem 1.1rem; }
    .tp-name { font-size: 0.875rem; font-weight: 600; color: var(--legal-text); margin-bottom: 0.2rem; }
    .tp-desc { font-size: 0.8rem; color: var(--legal-muted); line-height: 1.5; }

    /* Footer */
    .legal-footer { padding-top: 2.5rem; border-top: 1px solid var(--legal-border); text-align: center; }
    .legal-footer a { font-size: 0.8rem; color: var(--legal-muted); text-decoration: none; transition: color 0.2s; margin: 0 0.5rem; }
    .legal-footer a:hover { color: var(--legal-text); }

    @media (max-width: 900px) { .legal-sidebar { display: none; } .legal-main { padding: 2rem 1.5rem 4rem; max-width: 100%; } }
    @media (max-width: 480px) { .legal-main { padding: 1.5rem 1rem 3rem; } }
</style>
@endpush

@section('content')

<div class="reading-progress"><div class="reading-progress-bar" id="progressBar"></div></div>

<div class="legal-page">

    {{-- ── Sidebar ── --}}
    <aside class="legal-sidebar">
        <div class="toc-header">{{ __('privacy.toc_header') }}</div>
        <a class="toc-link active" href="#intro">{{ __('privacy.toc_introduction') }}</a>
        <a class="toc-link" href="#data-collected">{{ __('privacy.toc_data_collected') }}</a>
        <a class="toc-link" href="#how-used">{{ __('privacy.toc_how_used') }}</a>
        <a class="toc-link" href="#api-content">{{ __('privacy.toc_api_content') }}</a>
        <a class="toc-link" href="#third-party">{{ __('privacy.toc_third_party') }}</a>
        <a class="toc-link" href="#retention">{{ __('privacy.toc_retention') }}</a>
        <a class="toc-link" href="#security">{{ __('privacy.toc_security') }}</a>
        <a class="toc-link" href="#rights">{{ __('privacy.toc_rights') }}</a>
        <a class="toc-link" href="#updates">{{ __('privacy.toc_updates') }}</a>
        <a class="toc-link" href="#contact">{{ __('privacy.toc_contact') }}</a>
        <div class="toc-meta">
            <p><strong>{{ __('privacy.last_updated_label') }}</strong><br>{{ __('privacy.last_updated_date') }}</p>
            <p><strong>{{ __('privacy.version_label') }}</strong><br>{{ __('privacy.version_number') }}</p>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <main class="legal-main" id="legalContent">

        <div class="legal-hero">
            <span class="legal-eyebrow">{{ __('privacy.eyebrow') }}</span>
            <h1>{{ __('privacy.page_title') }}</h1>
            <div class="legal-meta-row">
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ __('privacy.last_updated_label') }} {{ __('privacy.last_updated_date') }}
                </span>
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    {{ __('privacy.read_time') }}
                </span>
                <span class="legal-meta-item">{{ __('privacy.brand_name') }}</span>
            </div>
        </div>

        <div class="legal-section" id="intro">
            <h2><span class="sec-num">01</span> {{ __('privacy.section_introduction') }}</h2>
            <p>{{ __('privacy.introduction_text') }}</p>
        </div>

        <div class="legal-section" id="data-collected">
            <h2><span class="sec-num">02</span> {{ __('privacy.section_data_collected') }}</h2>
            <table class="data-table">
                <thead>
                    <tr><th>{{ __('privacy.table_category') }}</th><th>{{ __('privacy.table_data') }}</th><th>{{ __('privacy.table_purpose') }}</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ __('privacy.category_account') }}</strong></td>
                        <td>{{ __('privacy.data_account') }}</td>
                        <td>{{ __('privacy.purpose_account') }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('privacy.category_usage_logs') }}</strong></td>
                        <td>{{ __('privacy.data_usage_logs') }}</td>
                        <td>{{ __('privacy.purpose_usage_logs') }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('privacy.category_payment') }}</strong></td>
                        <td>{{ __('privacy.data_payment') }}</td>
                        <td>{{ __('privacy.purpose_payment') }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('privacy.category_session') }}</strong></td>
                        <td>{{ __('privacy.data_session') }}</td>
                        <td>{{ __('privacy.purpose_session') }}</td>
                    </tr>
                </tbody>
            </table>
            <p>{!! __('privacy.no_tracking') !!}</p>
        </div>

        <div class="legal-section" id="how-used">
            <h2><span class="sec-num">03</span> {{ __('privacy.section_how_used') }}</h2>
            <ul>
                <li>{{ __('privacy.use_authenticate') }}</li>
                <li>{{ __('privacy.use_payments') }}</li>
                <li>{{ __('privacy.use_track_api') }}</li>
                <li>{{ __('privacy.use_whatsapp') }}</li>
                <li>{{ __('privacy.use_support') }}</li>
            </ul>
            <div class="legal-callout">
                <p>{!! __('privacy.no_sell_data') !!}</p>
            </div>
        </div>

        <div class="legal-section" id="api-content">
            <h2><span class="sec-num">04</span> {{ __('privacy.section_api_content') }}</h2>
            <p>{!! __('privacy.api_content_text_1') !!}</p>
            <p>{{ __('privacy.api_content_text_2') }}</p>
        </div>

        <div class="legal-section" id="third-party">
            <h2><span class="sec-num">05</span> {{ __('privacy.section_third_party') }}</h2>
            <div class="third-party-list">
                <div class="tp-item">
                    <div class="tp-name">{{ __('privacy.tp_myfatoorah_name') }}</div>
                    <div class="tp-desc">{!! __('privacy.tp_myfatoorah_desc') !!}</div>
                </div>
                <div class="tp-item">
                    <div class="tp-name">{{ __('privacy.tp_resayil_name') }}</div>
                    <div class="tp-desc">{{ __('privacy.tp_resayil_desc') }}</div>
                </div>
                <div class="tp-item">
                    <div class="tp-name">{{ __('privacy.tp_google_fonts_name') }}</div>
                    <div class="tp-desc">{{ __('privacy.tp_google_fonts_desc') }}</div>
                </div>
            </div>
        </div>

        <div class="legal-section" id="retention">
            <h2><span class="sec-num">06</span> {{ __('privacy.section_retention') }}</h2>
            <ul>
                <li>{{ __('privacy.retention_account') }}</li>
                <li>{{ __('privacy.retention_usage') }}</li>
                <li>{{ __('privacy.retention_otp') }}</li>
                <li>{{ __('privacy.retention_session') }}</li>
            </ul>
            <p>{{ __('privacy.retention_deletion') }}</p>
        </div>

        <div class="legal-section" id="security">
            <h2><span class="sec-num">07</span> {{ __('privacy.section_security') }}</h2>
            <p>{{ __('privacy.security_text') }}</p>
        </div>

        <div class="legal-section" id="rights">
            <h2><span class="sec-num">08</span> {{ __('privacy.section_rights') }}</h2>
            <p>{!! __('privacy.rights_text') !!}</p>
        </div>

        <div class="legal-section" id="updates">
            <h2><span class="sec-num">09</span> {{ __('privacy.section_updates') }}</h2>
            <p>{{ __('privacy.updates_text') }}</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="sec-num">10</span> {{ __('privacy.section_contact') }}</h2>
            <p>{!! __('privacy.contact_text') !!}</p>
        </div>

        <div class="legal-footer">
            <a href="/about">{{ __('privacy.footer_about') }}</a>
            <a href="/terms-of-service">{{ __('privacy.footer_terms') }}</a>
            <a href="/contact">{{ __('privacy.footer_contact') }}</a>
        </div>

    </main>
</div>

@push('scripts')
<script>
const bar = document.getElementById('progressBar');
const content = document.getElementById('legalContent');
window.addEventListener('scroll', () => {
    const rect = content.getBoundingClientRect();
    const total = content.offsetHeight - window.innerHeight;
    const progress = Math.min(100, Math.max(0, (-rect.top / total) * 100));
    bar.style.width = progress + '%';
});

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

function scrollTo(id) { document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' }); }
</script>
@endpush
@endsection
