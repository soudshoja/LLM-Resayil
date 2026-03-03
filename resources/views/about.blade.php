@extends('layouts.app')

@section('title', 'About')

@push('styles')
<style>
    .page-hero { text-align: center; padding: 4rem 2rem 3rem; max-width: 760px; margin: 0 auto; }
    .page-hero h1 { font-size: 2.25rem; font-weight: 700; margin-bottom: 1rem; }
    .page-hero p { font-size: 1.1rem; color: var(--text-secondary); line-height: 1.7; }
    .section { max-width: 900px; margin: 0 auto 3rem; padding: 0 2rem; }
    .section h2 { font-size: 1.25rem; font-weight: 700; color: var(--gold); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); }
    .section p { color: var(--text-secondary); line-height: 1.8; margin-bottom: 0.75rem; }
    .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.5rem; }
    .feature-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .feature-card .icon { font-size: 1.75rem; margin-bottom: 0.75rem; }
    .feature-card h3 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.4rem; }
    .feature-card p { font-size: 0.85rem; color: var(--text-muted); line-height: 1.6; margin: 0; }
    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1.5rem; }
    .stat-item { text-align: center; padding: 1.25rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; }
    .stat-item .num { font-size: 2rem; font-weight: 700; color: var(--gold); }
    .stat-item .lbl { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }
    .cta-section { text-align: center; padding: 3rem 2rem; background: var(--bg-card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); margin-bottom: 2rem; }
    .cta-section h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; }
    .cta-section p { color: var(--text-secondary); margin-bottom: 1.5rem; }
    @media(max-width: 768px) { .feature-grid, .stat-row { grid-template-columns: 1fr 1fr; } .page-hero h1 { font-size: 1.75rem; } }
    @media(max-width: 480px) { .feature-grid, .stat-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<div class="page-hero">
    <h1>The LLM API Built for <span style="color:var(--gold)">Developers</span></h1>
    <p>LLM Resayil gives you instant access to 45+ open-source and frontier AI models — local GPU inference and cloud proxies — through a single, OpenAI-compatible API. Pay only for what you use.</p>
</div>

<div class="section">
    <h2>Our Mission</h2>
    <p>We believe powerful AI should be accessible and affordable. LLM Resayil removes the complexity of running your own GPU infrastructure, managing model weights, and navigating multiple provider APIs.</p>
    <p>Whether you're a solo developer prototyping an idea or a team building production applications, we give you a unified gateway to the best open-source models — with transparent, credit-based pricing and no subscriptions required.</p>
</div>

<div class="section">
    <h2>What We Offer</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <div class="icon">⚡</div>
            <h3>45+ AI Models</h3>
            <p>Local GPU models for speed, cloud proxies for frontier capabilities. Llama, Qwen, DeepSeek, Mistral, and more.</p>
        </div>
        <div class="feature-card">
            <div class="icon">🔌</div>
            <h3>OpenAI-Compatible</h3>
            <p>Drop-in replacement for OpenAI's API. Zero code changes — just swap the base URL and API key.</p>
        </div>
        <div class="feature-card">
            <div class="icon">💳</div>
            <h3>Pay Per Token</h3>
            <p>No monthly subscriptions. Buy credits, use them when you need them. Local: 1 credit/token. Cloud: 2 credits/token.</p>
        </div>
        <div class="feature-card">
            <div class="icon">🔒</div>
            <h3>Secure & Private</h3>
            <p>API key authentication, per-key rate limits, and WhatsApp OTP verification for your account.</p>
        </div>
        <div class="feature-card">
            <div class="icon">📊</div>
            <h3>Usage Tracking</h3>
            <p>Full visibility into every API call — tokens used, credits deducted, model used, and timing.</p>
        </div>
        <div class="feature-card">
            <div class="icon">🌐</div>
            <h3>KNET Payments</h3>
            <p>Secure local KNET payments via MyFatoorah. No international card needed. KWD pricing.</p>
        </div>
    </div>
</div>

<div class="section">
    <h2>By the Numbers</h2>
    <div class="stat-row">
        <div class="stat-item">
            <div class="num">45+</div>
            <div class="lbl">AI Models</div>
        </div>
        <div class="stat-item">
            <div class="num">1:1</div>
            <div class="lbl">API Compatibility</div>
        </div>
        <div class="stat-item">
            <div class="num">3</div>
            <div class="lbl">Credit Tiers</div>
        </div>
        <div class="stat-item">
            <div class="num">KWD</div>
            <div class="lbl">Local Currency</div>
        </div>
    </div>
</div>

<div class="section">
    <h2>Infrastructure</h2>
    <p>Our platform runs on a dedicated GPU server providing low-latency inference for local models, backed by cloud proxy routing for frontier models through our Ollama gateway. All traffic is served over HTTPS with API key authentication.</p>
    <p>The web portal is built on Laravel and hosted on a managed cPanel environment. Payments are processed entirely by MyFatoorah — we never store card details.</p>
</div>

<div class="cta-section">
    <h2>Ready to get started?</h2>
    <p>Create your account, get your API key, and make your first request in minutes.</p>
    <a href="/register" class="btn btn-gold" style="margin-right:0.75rem">Get Started Free</a>
    <a href="/docs" class="btn btn-outline">Read the Docs</a>
</div>

<div style="max-width:900px;margin:0 auto 2rem;padding:0 2rem;text-align:center">
    <p class="text-muted text-sm">
        Questions? <a href="/contact" style="color:var(--gold)">Contact us</a> ·
        <a href="/terms-of-service" style="color:var(--text-muted)">Terms of Service</a> ·
        <a href="/privacy-policy" style="color:var(--text-muted)">Privacy Policy</a>
    </p>
</div>

@endsection
