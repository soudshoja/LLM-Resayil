@extends('layouts.app')

@section('title', 'Privacy Policy')

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
        <div class="toc-header">Contents</div>
        <a class="toc-link active" href="#intro">1. Introduction</a>
        <a class="toc-link" href="#data-collected">2. Data We Collect</a>
        <a class="toc-link" href="#how-used">3. How We Use Data</a>
        <a class="toc-link" href="#api-content">4. API Request Content</a>
        <a class="toc-link" href="#third-party">5. Third-Party Services</a>
        <a class="toc-link" href="#retention">6. Data Retention</a>
        <a class="toc-link" href="#security">7. Security</a>
        <a class="toc-link" href="#rights">8. Your Rights</a>
        <a class="toc-link" href="#updates">9. Policy Updates</a>
        <a class="toc-link" href="#contact">10. Contact</a>
        <div class="toc-meta">
            <p><strong>Last updated</strong><br>March 2026</p>
            <p><strong>Version</strong><br>1.0</p>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <main class="legal-main" id="legalContent">

        <div class="legal-hero">
            <span class="legal-eyebrow">Legal</span>
            <h1>Privacy Policy</h1>
            <div class="legal-meta-row">
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Last updated: March 2026
                </span>
                <span class="legal-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    ~6 min read
                </span>
                <span class="legal-meta-item">LLM Resayil</span>
            </div>
        </div>

        <div class="legal-section" id="intro">
            <h2><span class="sec-num">01</span> Introduction</h2>
            <p>LLM Resayil ("we", "us", "our") is committed to protecting your privacy. This policy explains what data we collect, how we use it, and your rights regarding that data when you use our API and web portal at llm.resayil.io.</p>
        </div>

        <div class="legal-section" id="data-collected">
            <h2><span class="sec-num">02</span> Data We Collect</h2>
            <table class="data-table">
                <thead>
                    <tr><th>Category</th><th>Data</th><th>Purpose</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Account</strong></td>
                        <td>Name, email, phone number</td>
                        <td>Registration, OTP verification, support</td>
                    </tr>
                    <tr>
                        <td><strong>Usage logs</strong></td>
                        <td>Model name, token count, credits deducted, timestamp</td>
                        <td>Billing, dashboard, rate limiting</td>
                    </tr>
                    <tr>
                        <td><strong>Payment</strong></td>
                        <td>Transaction ID, payment status (from MyFatoorah)</td>
                        <td>Credit top-up confirmation</td>
                    </tr>
                    <tr>
                        <td><strong>Session</strong></td>
                        <td>Session cookies, locale preference</td>
                        <td>Authentication, UI language</td>
                    </tr>
                </tbody>
            </table>
            <p>We do <strong style="color:var(--legal-text)">not</strong> collect advertising data, browser fingerprints, or third-party tracking identifiers.</p>
        </div>

        <div class="legal-section" id="how-used">
            <h2><span class="sec-num">03</span> How We Use Your Data</h2>
            <ul>
                <li>Authenticate your account and verify your phone number via WhatsApp OTP</li>
                <li>Process payments and top-up credits via MyFatoorah / KNET</li>
                <li>Track API usage for billing, rate limits, and dashboard display</li>
                <li>Send transactional WhatsApp messages (OTP codes, payment confirmations)</li>
                <li>Respond to support enquiries submitted via the contact form</li>
            </ul>
            <div class="legal-callout">
                <p><strong>We do not sell your data.</strong> We do not use your data for advertising, profiling, or any purpose beyond operating the Service.</p>
            </div>
        </div>

        <div class="legal-section" id="api-content">
            <h2><span class="sec-num">04</span> API Request Content</h2>
            <p>We do <strong style="color:var(--legal-text)">not</strong> store the content of your API requests (prompts) or model responses. Usage logs record only metadata — model name, token count, credits deducted, and timestamp.</p>
            <p>Your prompt data is forwarded directly to the model inference server and is not persisted to any database or log file.</p>
        </div>

        <div class="legal-section" id="third-party">
            <h2><span class="sec-num">05</span> Third-Party Services</h2>
            <div class="third-party-list">
                <div class="tp-item">
                    <div class="tp-name">MyFatoorah</div>
                    <div class="tp-desc">Payment processing via KNET and credit card. Card details are handled entirely by MyFatoorah — we never receive or store them. Subject to <a href="https://www.myfatoorah.com/privacy-policy" target="_blank" style="color:var(--legal-gold)">MyFatoorah's privacy policy</a>.</div>
                </div>
                <div class="tp-item">
                    <div class="tp-name">Resayil WhatsApp API</div>
                    <div class="tp-desc">Used to send OTP codes and account notifications to your registered phone number. Phone numbers are shared with this service only for transactional messaging.</div>
                </div>
                <div class="tp-item">
                    <div class="tp-name">Google Fonts</div>
                    <div class="tp-desc">IBM Plex Sans and JetBrains Mono are loaded from Google Fonts CDN on the web portal. Subject to Google's privacy policy.</div>
                </div>
            </div>
        </div>

        <div class="legal-section" id="retention">
            <h2><span class="sec-num">06</span> Data Retention</h2>
            <ul>
                <li>Account data — retained while your account is active</li>
                <li>Usage logs — retained for up to 12 months for billing and support</li>
                <li>OTP codes — expire after 10 minutes and are deleted after use or expiry</li>
                <li>Session data — expires after 2 hours of inactivity</li>
            </ul>
            <p>You may request deletion of your account and associated data by contacting us via the contact form. We will process requests within 14 business days.</p>
        </div>

        <div class="legal-section" id="security">
            <h2><span class="sec-num">07</span> Security</h2>
            <p>All data is transmitted over HTTPS (TLS 1.2+). API keys are hashed using bcrypt and never stored in plain text. Database access is restricted to authorised server processes only. We do not store payment credentials.</p>
        </div>

        <div class="legal-section" id="rights">
            <h2><span class="sec-num">08</span> Your Rights</h2>
            <p>You have the right to access, correct, or delete your personal data at any time. To exercise these rights, use our <a href="/contact" style="color:var(--legal-gold)">contact form</a>. We will respond within 14 business days.</p>
        </div>

        <div class="legal-section" id="updates">
            <h2><span class="sec-num">09</span> Policy Updates</h2>
            <p>We may update this policy periodically. The "last updated" date above will reflect changes. Continued use of the Service after changes constitutes acceptance of the updated policy. Material changes will be communicated by email or portal notice.</p>
        </div>

        <div class="legal-section" id="contact">
            <h2><span class="sec-num">10</span> Contact</h2>
            <p>Privacy questions or data requests: <a href="/contact" style="color:var(--legal-gold)">contact form</a>.</p>
        </div>

        <div class="legal-footer">
            <a href="/about">About</a>
            <a href="/terms-of-service">Terms of Service</a>
            <a href="/contact">Contact</a>
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
