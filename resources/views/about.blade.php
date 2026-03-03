@extends('layouts.app')

@section('title', 'About')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --about-bg: #090b0f;
        --about-card: #0e1117;
        --about-card-hover: #131720;
        --about-border: #1a1f2e;
        --about-gold: #d4af37;
        --about-gold-glow: rgba(212,175,55,0.15);
        --about-gold-dim: rgba(212,175,55,0.4);
        --about-text: #e2e8f0;
        --about-muted: #64748b;
        --about-secondary: #94a3b8;
    }

    .about-wrap { background: var(--about-bg); font-family: 'IBM Plex Sans', 'Inter', sans-serif; }

    /* ── Hero ── */
    .about-hero {
        position: relative;
        padding: 6rem 2rem 5rem;
        text-align: center;
        overflow: hidden;
    }
    .about-hero::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 80% 50% at 50% -10%, rgba(212,175,55,0.08) 0%, transparent 70%),
            radial-gradient(ellipse 40% 30% at 80% 20%, rgba(212,175,55,0.04) 0%, transparent 60%);
        pointer-events: none;
    }
    .about-hero::after {
        content: '';
        position: absolute; inset: 0;
        background-image: radial-gradient(circle, rgba(212,175,55,0.06) 1px, transparent 1px);
        background-size: 32px 32px;
        mask-image: radial-gradient(ellipse 70% 60% at center, black, transparent);
        pointer-events: none;
    }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem; font-weight: 500;
        color: var(--about-gold); letter-spacing: 0.12em; text-transform: uppercase;
        background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.2);
        padding: 0.35rem 0.85rem; border-radius: 20px; margin-bottom: 1.75rem;
        position: relative; z-index: 1;
    }
    .hero-eyebrow::before { content: '//'; opacity: 0.5; margin-right: 0.15rem; }
    .about-hero h1 {
        font-size: clamp(2rem, 5vw, 3.25rem);
        font-weight: 700; line-height: 1.15;
        color: var(--about-text); margin-bottom: 1.25rem;
        position: relative; z-index: 1;
    }
    .about-hero h1 em {
        font-style: normal;
        background: linear-gradient(135deg, var(--about-gold), #eac558);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .about-hero p {
        font-size: 1.1rem; color: var(--about-secondary); line-height: 1.75;
        max-width: 620px; margin: 0 auto 2.5rem;
        position: relative; z-index: 1;
    }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

    /* ── Stats bar ── */
    .stats-bar {
        display: grid; grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--about-border);
        border-bottom: 1px solid var(--about-border);
    }
    .stat-block {
        padding: 2rem 1.5rem; text-align: center;
        border-right: 1px solid var(--about-border);
        position: relative; overflow: hidden;
        transition: background 0.3s;
    }
    .stat-block:last-child { border-right: none; }
    .stat-block:hover { background: rgba(212,175,55,0.03); cursor: default; }
    .stat-block::after {
        content: '';
        position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--about-gold), transparent);
        transition: width 0.4s ease;
    }
    .stat-block:hover::after { width: 80%; }
    .stat-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2.25rem; font-weight: 600; color: var(--about-gold);
        line-height: 1; margin-bottom: 0.4rem;
    }
    .stat-label { font-size: 0.8rem; color: var(--about-muted); font-weight: 500; letter-spacing: 0.04em; }

    /* ── Section wrapper ── */
    .about-section { max-width: 1100px; margin: 0 auto; padding: 5rem 2rem; }
    .section-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; font-weight: 600; color: var(--about-gold);
        text-transform: uppercase; letter-spacing: 0.15em;
        margin-bottom: 0.75rem; display: block;
    }
    .section-title {
        font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 700;
        color: var(--about-text); margin-bottom: 1rem; line-height: 1.3;
    }
    .section-body { font-size: 1rem; color: var(--about-secondary); line-height: 1.8; max-width: 640px; }

    /* ── Bento grid ── */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: minmax(180px, auto);
        gap: 1px;
        background: var(--about-border);
        border: 1px solid var(--about-border);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 3rem;
    }
    .bento-cell {
        background: var(--about-card);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: background 0.25s, transform 0.2s;
        cursor: default;
    }
    .bento-cell:hover { background: var(--about-card-hover); }
    .bento-cell.wide { grid-column: span 2; }
    .bento-cell.tall { grid-row: span 2; }
    .bento-cell::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.2), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .bento-cell:hover::before { opacity: 1; }
    .bento-icon {
        width: 44px; height: 44px; margin-bottom: 1.25rem;
        background: var(--about-gold-glow); border: 1px solid rgba(212,175,55,0.2);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
    }
    .bento-icon svg { width: 20px; height: 20px; color: var(--about-gold); }
    .bento-title { font-size: 1rem; font-weight: 600; color: var(--about-text); margin-bottom: 0.5rem; }
    .bento-desc { font-size: 0.875rem; color: var(--about-muted); line-height: 1.65; }
    .bento-badge {
        font-family: 'JetBrains Mono', monospace; font-size: 0.7rem;
        background: rgba(212,175,55,0.1); color: var(--about-gold);
        border: 1px solid rgba(212,175,55,0.2); padding: 0.2rem 0.6rem;
        border-radius: 4px; margin-top: 1rem; display: inline-block;
    }
    .bento-big-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 4rem; font-weight: 700;
        background: linear-gradient(135deg, var(--about-gold), rgba(212,175,55,0.3));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        line-height: 1; margin-bottom: 0.5rem;
    }

    /* ── Pricing overview ── */
    .pricing-strip {
        background: var(--about-card);
        border: 1px solid var(--about-border);
        border-radius: 14px; overflow: hidden; margin-top: 3rem;
    }
    .pricing-strip-header {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid var(--about-border);
        display: flex; align-items: center; gap: 0.75rem;
    }
    .pricing-strip-header span {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.875rem; color: var(--about-gold); font-weight: 600;
    }
    .pricing-row {
        display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;
        border-bottom: 1px solid var(--about-border);
    }
    .pricing-row:last-child { border-bottom: none; }
    .pricing-cell {
        padding: 1rem 2rem;
        font-size: 0.875rem; color: var(--about-secondary);
        border-right: 1px solid var(--about-border);
    }
    .pricing-cell:last-child { border-right: none; }
    .pricing-cell.header {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; font-weight: 600; color: var(--about-muted);
        text-transform: uppercase; letter-spacing: 0.08em;
        background: rgba(0,0,0,0.2); padding-top: 0.75rem; padding-bottom: 0.75rem;
    }
    .pricing-cell strong { color: var(--about-gold); font-weight: 600; }

    /* ── Infrastructure ── */
    .infra-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2.5rem; }
    .infra-card {
        background: var(--about-card); border: 1px solid var(--about-border);
        border-radius: 12px; padding: 1.75rem;
        position: relative; overflow: hidden;
    }
    .infra-card::after {
        content: '';
        position: absolute; top: -50%; right: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle at center, rgba(212,175,55,0.04) 0%, transparent 60%);
    }
    .infra-card h3 { font-size: 0.95rem; font-weight: 600; color: var(--about-text); margin-bottom: 0.5rem; }
    .infra-card p { font-size: 0.875rem; color: var(--about-muted); line-height: 1.7; }

    /* ── Terminal block ── */
    .terminal-block {
        background: #05070a;
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 10px; padding: 1.5rem;
        font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;
        line-height: 1.8; margin-top: 1.5rem;
    }
    .t-prompt { color: var(--about-gold); }
    .t-cmd { color: #e2e8f0; }
    .t-out { color: #64748b; }
    .t-str { color: #7dd3a8; }
    .t-num { color: #f6ad55; }

    /* ── CTA strip ── */
    .cta-strip {
        background: linear-gradient(135deg, rgba(212,175,55,0.06), rgba(212,175,55,0.02));
        border-top: 1px solid rgba(212,175,55,0.15);
        border-bottom: 1px solid rgba(212,175,55,0.15);
        padding: 4rem 2rem; text-align: center;
    }
    .cta-strip h2 { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--about-text); }
    .cta-strip p { color: var(--about-secondary); margin-bottom: 2rem; font-size: 1rem; }

    /* ── Buttons ── */
    .btn-primary {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: linear-gradient(135deg, var(--about-gold), #eac558);
        color: #07090c; font-weight: 700; font-size: 0.9rem;
        padding: 0.7rem 1.75rem; border-radius: 8px; text-decoration: none;
        transition: all 0.2s; cursor: pointer; border: none;
        box-shadow: 0 0 20px rgba(212,175,55,0.2);
    }
    .btn-primary:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 4px 24px rgba(212,175,55,0.3); }
    .btn-secondary {
        display: inline-flex; align-items: center; gap: 0.5rem;
        border: 1px solid rgba(212,175,55,0.3); color: var(--about-gold);
        background: transparent; font-weight: 600; font-size: 0.9rem;
        padding: 0.7rem 1.75rem; border-radius: 8px; text-decoration: none;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-secondary:hover { background: rgba(212,175,55,0.08); border-color: var(--about-gold); }

    /* ── Footer nav ── */
    .about-footer-nav {
        max-width: 1100px; margin: 0 auto;
        padding: 1.5rem 2rem;
        display: flex; align-items: center; justify-content: center; gap: 1.5rem;
        border-top: 1px solid var(--about-border);
        flex-wrap: wrap;
    }
    .about-footer-nav a { font-size: 0.825rem; color: var(--about-muted); text-decoration: none; transition: color 0.2s; }
    .about-footer-nav a:hover { color: var(--about-text); }
    .about-footer-nav span { color: var(--about-border); }

    @media (max-width: 900px) {
        .bento-grid { grid-template-columns: 1fr 1fr; }
        .bento-cell.wide { grid-column: span 2; }
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
        .stat-block { border-right: 1px solid var(--about-border); }
        .stat-block:nth-child(2) { border-right: none; }
        .stat-block:nth-child(3) { border-top: 1px solid var(--about-border); border-right: 1px solid var(--about-border); }
        .infra-grid { grid-template-columns: 1fr; }
        .pricing-row { grid-template-columns: 1fr 1fr; }
        .pricing-cell.header:nth-child(3), .pricing-cell.header:nth-child(4),
        .pricing-cell:nth-child(3), .pricing-cell:nth-child(4) { display: none; }
    }
    @media (max-width: 600px) {
        .bento-grid { grid-template-columns: 1fr; }
        .bento-cell.wide { grid-column: span 1; }
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .about-hero { padding: 4rem 1.5rem 3.5rem; }
        .about-section { padding: 3.5rem 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="about-wrap">

    {{-- ── Hero ── --}}
    <div class="about-hero">
        <span class="hero-eyebrow">About LLM Resayil</span>
        <h1>The LLM API Built for<br><em>Developers Who Ship</em></h1>
        <p>45+ open-source and frontier AI models through one OpenAI-compatible gateway. Local GPU inference, cloud proxies, pay-per-token — zero lock-in.</p>
        <div class="hero-cta">
            <a href="/register" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Get Started
            </a>
            <a href="/docs" class="btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Read the Docs
            </a>
        </div>
    </div>

    {{-- ── Stats bar ── --}}
    <div class="stats-bar">
        <div class="stat-block">
            <div class="stat-num">45+</div>
            <div class="stat-label">AI Models</div>
        </div>
        <div class="stat-block">
            <div class="stat-num">1:1</div>
            <div class="stat-label">OpenAI Compatible</div>
        </div>
        <div class="stat-block">
            <div class="stat-num">KWD</div>
            <div class="stat-label">Local Currency</div>
        </div>
        <div class="stat-block">
            <div class="stat-num">0ms</div>
            <div class="stat-label">Setup Time</div>
        </div>
    </div>

    {{-- ── Mission ── --}}
    <div class="about-section">
        <span class="section-label">Our Mission</span>
        <h2 class="section-title">Powerful AI shouldn't require<br>a PhD in infrastructure</h2>
        <p class="section-body">We built LLM Resayil to remove the friction between "I want to use AI" and "my app is live." GPU servers, model weights, provider APIs, billing — we handle it. You focus on building.</p>
    </div>

    {{-- ── Features bento grid ── --}}
    <div style="max-width:1100px;margin:0 auto;padding:0 2rem 5rem">
        <span class="section-label">What We Offer</span>
        <div class="bento-grid">

            {{-- Wide: API compatibility --}}
            <div class="bento-cell wide">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <div class="bento-title">OpenAI-Compatible API</div>
                <div class="bento-desc">Drop-in replacement. Change your base URL, keep your code. Works with any SDK that targets OpenAI's API — Python, Node, curl, n8n, LangChain.</div>
                <div class="terminal-block">
                    <span class="t-prompt">$</span> <span class="t-cmd">curl https://llm.resayil.io/api/v1/chat/completions</span><br>
                    &nbsp;&nbsp;<span class="t-cmd">-H </span><span class="t-str">"Authorization: Bearer $API_KEY"</span><br>
                    &nbsp;&nbsp;<span class="t-cmd">-d </span><span class="t-str">'{"model":"llama3.2:3b","messages":[...]}'</span><br>
                    <span class="t-out"># Returns standard OpenAI-format JSON ✓</span>
                </div>
            </div>

            {{-- 45+ models --}}
            <div class="bento-cell tall">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/>
                    </svg>
                </div>
                <div class="bento-big-num">45+</div>
                <div class="bento-title">AI Models</div>
                <div class="bento-desc">Local GPU: Llama 3.2, Qwen 3, Mistral, Gemma, Phi-4. Cloud proxies: DeepSeek V3.1, GPT-4o, Claude, Gemini, and more.</div>
                <div class="bento-badge">15 local · 30 cloud</div>
            </div>

            {{-- Pay per token --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div class="bento-title">Pay Per Token</div>
                <div class="bento-desc">No subscriptions. Buy credits, use them anytime. Local: 1 cr/token. Cloud: 2 cr/token.</div>
                <div class="bento-badge">No monthly fee</div>
            </div>

            {{-- KNET --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                    </svg>
                </div>
                <div class="bento-title">KNET Payments</div>
                <div class="bento-desc">Secure local KNET payments via MyFatoorah. KWD pricing. No international card needed.</div>
                <div class="bento-badge">Powered by MyFatoorah</div>
            </div>

            {{-- Usage tracking --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                <div class="bento-title">Full Usage Visibility</div>
                <div class="bento-desc">Per-call logs: model, tokens, credits, timing. Weekly summaries in your dashboard.</div>
            </div>

            {{-- Security --}}
            <div class="bento-cell">
                <div class="bento-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="bento-title">Secure by Default</div>
                <div class="bento-desc">API keys hashed at rest. WhatsApp OTP for account verification. Per-key rate limits. HTTPS only.</div>
            </div>

        </div>
    </div>

    {{-- ── Pricing overview ── --}}
    <div style="max-width:1100px;margin:0 auto;padding:0 2rem 5rem">
        <span class="section-label">Pricing</span>
        <h2 class="section-title">Simple credit-based pricing</h2>
        <div class="pricing-strip">
            <div class="pricing-strip-header">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01"/></svg>
                <span>Credit top-ups — unused credits never expire</span>
            </div>
            <div class="pricing-row">
                <div class="pricing-cell header">Credits</div>
                <div class="pricing-cell header">Price (KWD)</div>
                <div class="pricing-cell header">Local use</div>
                <div class="pricing-cell header">Cloud use</div>
            </div>
            <div class="pricing-row">
                <div class="pricing-cell"><strong>5,000</strong> credits</div>
                <div class="pricing-cell">2.000 KWD</div>
                <div class="pricing-cell">5,000 tokens</div>
                <div class="pricing-cell">2,500 tokens</div>
            </div>
            <div class="pricing-row">
                <div class="pricing-cell"><strong>15,000</strong> credits</div>
                <div class="pricing-cell">5.000 KWD</div>
                <div class="pricing-cell">15,000 tokens</div>
                <div class="pricing-cell">7,500 tokens</div>
            </div>
            <div class="pricing-row">
                <div class="pricing-cell"><strong>50,000</strong> credits</div>
                <div class="pricing-cell">15.000 KWD</div>
                <div class="pricing-cell">50,000 tokens</div>
                <div class="pricing-cell">25,000 tokens</div>
            </div>
        </div>
    </div>

    {{-- ── Infrastructure ── --}}
    <div class="about-section" style="padding-top:0">
        <span class="section-label">Infrastructure</span>
        <h2 class="section-title">Built on dedicated GPU hardware</h2>
        <p class="section-body">We run our own GPU server for local model inference, backed by cloud proxy routing for frontier models. Everything is served over HTTPS with per-key authentication.</p>
        <div class="infra-grid">
            <div class="infra-card">
                <h3>Local GPU Server</h3>
                <p>Dedicated GPU hardware running Ollama for fast, private inference on open-source models. Llama, Qwen, Mistral, Phi, Gemma, and more — served from our own hardware.</p>
            </div>
            <div class="infra-card">
                <h3>Cloud Proxy Models</h3>
                <p>Frontier models (DeepSeek V3.1, Qwen 3.5 397B, and others) routed via Ollama cloud proxies. Same API interface — no extra configuration.</p>
            </div>
            <div class="infra-card">
                <h3>Payment Processing</h3>
                <p>All payments handled by MyFatoorah — a licensed Kuwaiti payment gateway. We never store card numbers or KNET credentials.</p>
            </div>
            <div class="infra-card">
                <h3>Account Security</h3>
                <p>Phone verification via WhatsApp OTP. API keys are hashed, never stored in plain text. Rate limiting applied per key, per tier.</p>
            </div>
        </div>
    </div>

    {{-- ── CTA strip ── --}}
    <div class="cta-strip">
        <h2>Ready to build?</h2>
        <p>Create your account, get your API key, and make your first request in under 5 minutes.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="/register" class="btn-primary">Create Free Account</a>
            <a href="/docs" class="btn-secondary">Explore the Docs</a>
        </div>
    </div>

    {{-- ── Footer nav ── --}}
    <nav class="about-footer-nav">
        <a href="/docs">Documentation</a>
        <span>·</span>
        <a href="/billing/plans">Pricing</a>
        <span>·</span>
        <a href="/contact">Contact</a>
        <span>·</span>
        <a href="/terms-of-service">Terms</a>
        <span>·</span>
        <a href="/privacy-policy">Privacy</a>
    </nav>

</div>
@endsection
