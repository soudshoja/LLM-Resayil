@extends('layouts.app')

@section('title', 'Terms of Service')

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
        <div class="toc-header">Contents</div>
        <a class="toc-link active" href="#acceptance" onclick="scrollTo('acceptance')">1. Acceptance</a>
        <a class="toc-link" href="#service" onclick="scrollTo('service')">2. Description of Service</a>
        <a class="toc-link" href="#account" onclick="scrollTo('account')">3. Account Registration</a>
        <a class="toc-link" href="#credits" onclick="scrollTo('credits')">4. Credits & Payments</a>
        <a class="toc-link" href="#usage" onclick="scrollTo('usage')">5. API Usage & Rate Limits</a>
        <a class="toc-link" href="#prohibited" onclick="scrollTo('prohibited')">6. Prohibited Uses</a>
        <a class="toc-link" href="#availability" onclick="scrollTo('availability')">7. Service Availability</a>
        <a class="toc-link" href="#termination" onclick="scrollTo('termination')">8. Termination</a>
        <a class="toc-link" href="#changes" onclick="scrollTo('changes')">9. Changes to Terms</a>
        <a class="toc-link" href="#contact" onclick="scrollTo('contact')">10. Contact</a>
        <div class="toc-meta">
            <p><strong>Last updated</strong><br>March 2026</p>
            <p><strong>Version</strong><br>1.0</p>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <main class="legal-main" id="legalContent">

        <div class="legal-hero">
            <span class="legal-eyebrow">Legal</span>
            <h1>Terms of Service</h1>
            <div class="legal-meta-row">
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Last updated: March 2026
                </span>
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    ~5 min read
                </span>
                <span class="legal-meta-item">LLM Resayil</span>
            </div>
        </div>

        <div class="legal-section" id="acceptance">
            <h2><span class="sec-num">01</span> Acceptance of Terms</h2>
            <p>By creating an account or using the LLM Resayil API or web portal ("Service"), you agree to be bound by these Terms of Service. If you do not agree, do not use the Service.</p>
        </div>

        <div class="legal-section" id="service">
            <h2><span class="sec-num">02</span> Description of Service</h2>
            <p>LLM Resayil provides access to large language models (LLMs) via an OpenAI-compatible REST API. The Service includes:</p>
            <ul>
                <li>API access to 45+ AI models (local GPU inference and cloud proxies)</li>
                <li>A web portal for managing API keys, credits, and usage history</li>
                <li>Credit-based billing processed via MyFatoorah (KNET / credit card)</li>
                <li>Account verification via WhatsApp OTP through Resayil messaging platform</li>
            </ul>
        </div>

        <div class="legal-section" id="account">
            <h2><span class="sec-num">03</span> Account Registration</h2>
            <p>You must provide a valid phone number for WhatsApp OTP verification to create an account. You are responsible for maintaining the confidentiality of your API keys and account credentials.</p>
            <p>You must notify us immediately of any unauthorised use of your account or API keys. We are not liable for losses caused by unauthorised use where you failed to notify us promptly.</p>
        </div>

        <div class="legal-section" id="credits">
            <h2><span class="sec-num">04</span> Credits &amp; Payments</h2>
            <p>Access to the API is credit-based. Credits are purchased in KWD via KNET or credit card through MyFatoorah. Current top-up tiers:</p>
            <ul>
                <li>5,000 credits — 2.000 KWD</li>
                <li>15,000 credits — 5.000 KWD</li>
                <li>50,000 credits — 15.000 KWD</li>
            </ul>
            <div class="legal-callout">
                <p><strong>Important:</strong> Credits are non-refundable once used. Unused credits do not expire. Prices are subject to change with 14 days' notice.</p>
            </div>
        </div>

        <div class="legal-section" id="usage">
            <h2><span class="sec-num">05</span> API Usage &amp; Rate Limits</h2>
            <p>API usage is subject to per-minute rate limits based on your account tier. Credit multipliers apply: local models consume 1 credit per token; cloud models consume 2 credits per token.</p>
            <p>Attempting to circumvent rate limits or abuse the Service — including automated scraping, denial-of-service attacks, or reselling access without authorisation — will result in immediate account suspension.</p>
        </div>

        <div class="legal-section" id="prohibited">
            <h2><span class="sec-num">06</span> Prohibited Uses</h2>
            <p>You must not use the Service to:</p>
            <ul>
                <li>Generate illegal, harmful, or deceptive content</li>
                <li>Violate any applicable law or regulation</li>
                <li>Infringe intellectual property or privacy rights of others</li>
                <li>Attempt to reverse-engineer, probe, or exploit Service infrastructure</li>
                <li>Resell or sublicense API access without written permission</li>
                <li>Bypass security controls or authentication mechanisms</li>
            </ul>
        </div>

        <div class="legal-section" id="availability">
            <h2><span class="sec-num">07</span> Service Availability</h2>
            <p>We strive for high availability but do not guarantee uninterrupted access. The Service is provided "as is" without warranties of any kind. We are not liable for losses resulting from downtime, model output errors, or data loss.</p>
        </div>

        <div class="legal-section" id="termination">
            <h2><span class="sec-num">08</span> Termination</h2>
            <p>We reserve the right to suspend or terminate accounts that violate these terms, at our sole discretion, with or without notice. You may close your account at any time by contacting us. Unused credits are non-refundable upon termination.</p>
        </div>

        <div class="legal-section" id="changes">
            <h2><span class="sec-num">09</span> Changes to Terms</h2>
            <p>We may update these terms at any time. Continued use of the Service after changes constitutes acceptance. Material changes will be communicated via email or a notice on the portal.</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="sec-num">10</span> Contact</h2>
            <p>For questions about these terms, please use our <a href="/contact" style="color:var(--legal-gold)">contact form</a>.</p>
        </div>

        <div class="legal-footer">
            <a href="/about">About</a>
            <a href="/privacy-policy">Privacy Policy</a>
            <a href="/contact">Contact</a>
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
