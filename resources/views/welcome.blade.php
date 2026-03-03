@extends('layouts.app')

@section('title', 'LLM Resayil - OpenAI-Compatible LLM API')

@push('styles')
<style>
    body { background: var(--bg-secondary); }
    .hero { text-align: center; padding: 6rem 2rem 4rem; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 400px; background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.08) 0%, transparent 70%); pointer-events: none; }
    .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.25); color: var(--gold); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
    .hero h1 { font-size: 3rem; font-weight: 700; line-height: 1.15; margin-bottom: 1.25rem; max-width: 700px; margin-left: auto; margin-right: auto; }
    .hero h1 span { background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .hero p { font-size: 1.125rem; color: var(--text-secondary); max-width: 560px; margin: 0 auto 2rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .section { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
    .section-title { text-align: center; margin-bottom: 3rem; }
    .section-title h2 { font-size: 1.875rem; font-weight: 700; margin-bottom: 0.75rem; }
    .section-title p { color: var(--text-secondary); font-size: 1rem; max-width: 500px; margin: 0 auto; }
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .step { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; text-align: center; position: relative; }
    .step-num { width: 44px; height: 44px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; margin: 0 auto 1rem; }
    .step h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .step p { color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6; }
    /* ── Pricing cards (matches billing/plans style) ── */
    .pricing-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
    .plan-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 2rem; display: flex; flex-direction: column; position: relative; transition: border-color 0.2s, transform 0.2s; }
    .plan-card:hover { border-color: rgba(212,175,55,0.4); transform: translateY(-2px); }
    .plan-card.featured { border-color: rgba(212,175,55,0.4); box-shadow: 0 0 0 1px rgba(212,175,55,0.2), 0 8px 32px rgba(212,175,55,0.08); }
    .plan-card.trial-card { border: 2px dashed rgba(212,175,55,0.45); background: linear-gradient(160deg, rgba(212,175,55,0.04) 0%, var(--bg-card) 70%); }
    .plan-card.trial-card:hover { border-color: rgba(212,175,55,0.7); }
    .plan-badge { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .plan-badge-trial { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #28a745, #20c997); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .plan-name { font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); margin-bottom: 0.75rem; }
    .plan-price { font-size: 2.5rem; font-weight: 700; color: var(--gold); line-height: 1; margin-bottom: 0.25rem; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--text-secondary); }
    .plan-price-free { font-size: 2rem; font-weight: 700; color: #28a745; line-height: 1; margin-bottom: 0.25rem; }
    .plan-billing { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; }
    .plan-divider { border: none; border-top: 1px solid var(--border); margin-bottom: 1.25rem; }
    .plan-features { list-style: none; flex: 1; margin-bottom: 1.75rem; padding: 0; }
    .plan-features li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; color: var(--text-secondary); padding: 0.35rem 0; }
    .plan-features li svg { flex-shrink: 0; color: var(--gold); }
    .plan-features li svg.green { color: #28a745; }
    .plan-cta { display: block; width: 100%; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; text-align: center; text-decoration: none; }
    .plan-cta-gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .plan-cta-gold:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(212,175,55,0.3); color: #0a0d14; }
    .plan-cta-outline { background: transparent; border: 1px solid rgba(212,175,55,0.4); color: var(--gold); }
    .plan-cta-outline:hover { background: rgba(212,175,55,0.1); color: var(--gold); }
    .plan-cta-green { background: linear-gradient(135deg, #28a745, #20c997); color: #fff; }
    .plan-cta-green:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(40,167,69,0.3); color: #fff; }
    .trial-note { font-size: 0.75rem; color: var(--text-muted); text-align: center; margin-top: 1.25rem; }
    .addon-box { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem 1.5rem; margin-top: 2rem; }
    .addon-box h4 { font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }
    .addon-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.35rem 0; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
    .addon-row:last-child { border-bottom: none; }
    .addon-row span:last-child { color: var(--gold); font-weight: 600; }
    .code-block { background: #0a0d14; border: 1px solid var(--border); border-radius: 10px; padding: 1.5rem; overflow-x: auto; font-family: monospace; font-size: 0.85rem; line-height: 1.7; color: #e0e5ec; }
    .code-block .comment { color: #4b5563; }
    .code-block .string { color: #86efac; }
    .code-block .key { color: #93c5fd; }
    .models-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .model-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; }
    .model-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; }
    .model-meta { font-size: 0.75rem; color: var(--text-muted); }
    .divider { border: none; border-top: 1px solid var(--border); margin: 0; }

    /* ── Model List (ml-) premium redesign ── */
    .ml-category-group { margin-bottom: 2.75rem; }
    .ml-category-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
    .ml-category-diamond { color: var(--gold); font-size: 0.65rem; flex-shrink: 0; line-height: 1; }
    .ml-category-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold); white-space: nowrap; }
    .ml-category-line { flex: 1; height: 1px; background: linear-gradient(to right, rgba(212,175,55,0.4), transparent); }
    .ml-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .ml-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 1.2rem 1.35rem; display: flex; align-items: flex-start; gap: 1rem; transition: border-color 0.22s, box-shadow 0.22s; cursor: default; }
    .ml-card:hover { border-color: rgba(212,175,55,0.5); box-shadow: 0 0 0 1px rgba(212,175,55,0.13), 0 8px 28px rgba(0,0,0,0.38); }
    .ml-avatar { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; font-weight: 800; color: #fff; flex-shrink: 0; letter-spacing: -0.01em; font-family: 'Inter', sans-serif; }
    .ml-av-meta    { background: linear-gradient(140deg, #0866FF 0%, #3b8fff 100%); }
    .ml-av-mistral { background: linear-gradient(140deg, #e56000 0%, #ff8c2e 100%); }
    .ml-av-qwen    { background: linear-gradient(140deg, #e05c00 0%, #ff8533 100%); }
    .ml-av-deepseek{ background: linear-gradient(140deg, #3a58e8 0%, #6f88ff 100%); }
    .ml-body { flex: 1; min-width: 0; }
    .ml-model-name { font-size: 0.875rem; font-weight: 700; color: #dde2ea; line-height: 1.3; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ml-company { font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.4rem; line-height: 1; }
    .ml-tagline { font-size: 0.76rem; color: var(--text-secondary); line-height: 1.55; }
    .ml-footer { text-align: center; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border); }
    .ml-footer p { color: var(--text-muted); font-size: 0.875rem; }
    .ml-footer a { color: var(--gold); text-decoration: none; font-weight: 600; }
    .ml-footer a:hover { text-decoration: underline; }
    @media(max-width: 900px) { .ml-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 560px) { .ml-grid { grid-template-columns: 1fr; } }
    .cta-section { text-align: center; padding: 5rem 2rem; background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, transparent 100%); border-top: 1px solid var(--border); }
    @media(max-width: 1100px) { .pricing-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 768px) { .hero h1 { font-size: 2rem; } .steps, .pricing-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">✦ OpenAI-Compatible API Gateway</div>
    <h1>Powerful LLMs, <span>Pay As You Go</span></h1>
    <p>Access state-of-the-art language models via an OpenAI-compatible API. Credit-based billing, automatic cloud failover, and enterprise team management — all in Kuwait Dinar.</p>
    <div class="hero-cta">
        <a href="/register" class="btn btn-gold" style="padding:0.75rem 2rem;font-size:1rem">Start Free Trial</a>
        <a href="#pricing" class="btn btn-outline" style="padding:0.75rem 2rem;font-size:1rem">View Pricing</a>
    </div>
</section>

<!-- How It Works -->
<section class="section">
    <div class="section-title">
        <h2>How It Works</h2>
        <p>Three simple steps to access powerful AI models</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <h3>Register & Top Up</h3>
            <p>Create an account, choose a subscription tier, and top up with credits via KNET or credit card.</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h3>Get Your API Key</h3>
            <p>Generate an API key from your dashboard. Use it exactly like you would with OpenAI's API.</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h3>Make API Calls</h3>
            <p>Point your app to our endpoint. Local GPU processing, cloud failover when needed — all automatic.</p>
        </div>
    </div>
</section>

<hr class="divider">

<!-- Pricing -->
<section class="section" id="pricing">
    <div class="section-title">
        <h2>Simple, Transparent Pricing</h2>
        <p>All prices in Kuwaiti Dinar. Billed monthly. No hidden fees.</p>
    </div>

    <div class="pricing-grid">

        <!-- Free Trial -->
        <div class="plan-card trial-card">
            <div class="plan-badge-trial">Free Trial</div>
            <div class="plan-name">Trial</div>
            <div class="plan-price-free">FREE</div>
            <div class="plan-billing">7-Day Free Trial &mdash; then 15 KWD/mo</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="green"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Full Starter features
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="green"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1,000 credits included
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="green"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All 45 models (local + cloud)
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="green"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="green"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Cancel anytime during trial
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-green">Start Free Trial</a>
        </div>

        <!-- Starter -->
        <div class="plan-card">
            <div class="plan-name">Starter</div>
            <div class="plan-price">15 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All 45 models (local + cloud)
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Extra keys: +5 KWD (2nd), +10 KWD (3rd)
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-outline">Get Started</a>
        </div>

        <!-- Basic (Most Popular) -->
        <div class="plan-card featured">
            <div class="plan-badge">Most Popular</div>
            <div class="plan-name">Basic</div>
            <div class="plan-price">25 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    3,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All 45 models (local + cloud)
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    30 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Extra keys: +3 KWD (2nd), +7 KWD (3rd)
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-gold">Get Started</a>
        </div>

        <!-- Pro (Best Value) -->
        <div class="plan-card">
            <div class="plan-badge" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;">Best Value</div>
            <div class="plan-name">Pro</div>
            <div class="plan-price">45 <span>KWD</span></div>
            <div class="plan-billing">per month &nbsp;&middot;&nbsp; billed monthly</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    10,000 credits / month
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All 45 models (local + cloud)
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    60 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    2 free API keys
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Priority cloud queue
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Extra keys: +2 KWD (3rd), +5 KWD (4th)
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-outline">Get Started</a>
        </div>

    </div>
    <p class="trial-note">Card required for trial. Cancel anytime. Payments processed securely via KNET / credit card.</p>

    <!-- Credit top-up & addons info -->
    <div class="addon-box">
        <h4>Credit Top-Ups &amp; Add-Ons</h4>
        <div class="addon-row"><span>500 extra credits</span><span>5 KWD</span></div>
        <div class="addon-row"><span>1,100 extra credits</span><span>10 KWD <span style="color:#28a745;font-size:0.8em;font-weight:600">(+10% bonus)</span></span></div>
        <div class="addon-row"><span>3,000 extra credits</span><span>25 KWD <span style="color:#28a745;font-size:0.8em;font-weight:600">(+20% bonus)</span></span></div>
        <div class="addon-row"><span>Credits per 1k tokens</span><span>0.5–3 (local) · 1–3.5 (cloud)</span></div>
    </div>
</section>

<hr class="divider">

<!-- Available Models -->
<section class="section" id="models">
    <div class="section-title">
        <h2>Available Models</h2>
        <p>45+ models from the world's leading AI labs. Access all from a single API.</p>
    </div>

    <!-- General Chat -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">General Chat</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">

            <div class="ml-card">
                <div class="ml-avatar ml-av-meta">M</div>
                <div class="ml-body">
                    <div class="ml-model-name">Llama 3.2 3B</div>
                    <div class="ml-company">Meta &middot; 3B</div>
                    <div class="ml-tagline">Lightweight and blazing fast for everyday tasks</div>
                </div>
            </div>

            <div class="ml-card">
                <div class="ml-avatar ml-av-mistral">Mi</div>
                <div class="ml-body">
                    <div class="ml-model-name">Mistral Small 3.2 24B</div>
                    <div class="ml-company">Mistral AI &middot; 24B</div>
                    <div class="ml-tagline">Balanced quality and speed for complex chat</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Code -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">Code</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">

            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">Qwen 2.5 Coder 14B</div>
                    <div class="ml-company">Alibaba / Qwen &middot; 14B</div>
                    <div class="ml-tagline">Code specialist with deep multi-language support</div>
                </div>
            </div>

            <div class="ml-card">
                <div class="ml-avatar ml-av-deepseek">D</div>
                <div class="ml-body">
                    <div class="ml-model-name">DeepSeek Coder 6.7B</div>
                    <div class="ml-company">DeepSeek &middot; 6.7B</div>
                    <div class="ml-tagline">Fast, accurate code generation and completion</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Vision & Multimodal -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">Vision &amp; Multimodal</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">

            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">Qwen3-VL 32B</div>
                    <div class="ml-company">Alibaba / Qwen &middot; 32B</div>
                    <div class="ml-tagline">Understand images and documents alongside text</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Frontier -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">Frontier</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">

            <div class="ml-card">
                <div class="ml-avatar ml-av-deepseek">D</div>
                <div class="ml-body">
                    <div class="ml-model-name">DeepSeek V3.1 671B</div>
                    <div class="ml-company">DeepSeek &middot; 671B</div>
                    <div class="ml-tagline">Frontier-class reasoning at scale</div>
                </div>
            </div>

            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">Qwen 3.5 397B</div>
                    <div class="ml-company">Alibaba / Qwen &middot; 397B MoE</div>
                    <div class="ml-tagline">The largest model available on the platform</div>
                </div>
            </div>

        </div>
    </div>

    <div class="ml-footer">
        <p>Showing a curated selection &mdash; <a href="/dashboard">explore all 45+ models</a> after signing in.</p>
    </div>
</section>

<hr class="divider">

<!-- Code Example -->
<section class="section" id="docs">
    <div class="section-title">
        <h2>Drop-In Replacement</h2>
        <p>Works with any OpenAI-compatible SDK. Just change the base URL and API key.</p>
    </div>
    <div class="code-block">
<span class="comment"># Python example using openai SDK</span>
from openai import OpenAI

client = OpenAI(
    <span class="key">api_key</span>=<span class="string">"sk-resayil-your-key-here"</span>,
    <span class="key">base_url</span>=<span class="string">"https://llm.resayil.io/api/v1"</span>
)

response = client.chat.completions.create(
    <span class="key">model</span>=<span class="string">"qwen2.5:7b"</span>,
    <span class="key">messages</span>=[{<span class="string">"role"</span>: <span class="string">"user"</span>, <span class="string">"content"</span>: <span class="string">"Hello!"</span>}]
)
print(response.choices[0].message.content)
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2 style="font-size:2rem;font-weight:700;margin-bottom:0.75rem">Ready to get started?</h2>
    <p style="color:var(--text-secondary);margin-bottom:2rem">Join developers already using LLM Resayil. Pay with KNET or credit card.</p>
    <a href="/register" class="btn btn-gold" style="padding:0.85rem 2.5rem;font-size:1.05rem">Create Free Account</a>
</section>

@endsection
