@extends('layouts.app')

@section('title', __('welcome.title'))

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
    .hero-subcta { margin-top: 1.5rem; display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; font-size: 0.85rem; color: var(--text-secondary); }
    .hero-subcta a { color: var(--gold); text-decoration: none; transition: all 0.2s; }
    .hero-subcta a:hover { color: var(--gold-light); }
    .hero-subcta span { opacity: 0.4; }
    /* ── Hero Slider ── */
    .hero-slider-section { padding: 3rem 2rem; max-width: 1200px; margin: 0 auto; }
    .hero-slider-wrapper { position: relative; background: var(--bg-card, #13161d); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; overflow: hidden; }
    .hero-slider-container { position: relative; width: 100%; height: 280px; overflow: hidden; }
    .hero-slider { display: flex; position: relative; width: 100%; height: 100%; }
    .hero-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem; opacity: 0; transition: opacity 0.5s ease; }
    .hero-slide.active { opacity: 1; }
    .hero-slide-emoji { font-size: 3rem; margin-bottom: 1rem; }
    .hero-slide-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--gold, #d4af37); }
    .hero-slide-desc { font-size: 1rem; color: var(--text-secondary); max-width: 500px; }
    .hero-slider-controls { display: flex; align-items: center; justify-content: center; gap: 2rem; margin-top: 2rem; }
    .hero-slider-dots { display: flex; gap: 0.5rem; }
    .hero-slider-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(212,175,55,0.2); cursor: pointer; transition: all 0.3s; }
    .hero-slider-dot.active { background: var(--gold, #d4af37); width: 28px; border-radius: 5px; }
    .hero-slider-btn { width: 40px; height: 40px; border-radius: 50%; background: transparent; border: 2px solid var(--gold, #d4af37); color: var(--gold, #d4af37); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .hero-slider-btn:hover { background: rgba(212,175,55,0.1); }
    @media(max-width: 768px) {
        .hero-slider-wrapper { padding: 1.5rem; }
        .hero-slider-container { height: 200px; }
        .hero-slide { padding: 1.5rem; }
        .hero-slide-emoji { font-size: 2rem; }
        .hero-slide-title { font-size: 1.25rem; }
        .hero-slide-desc { font-size: 0.9rem; }
        .hero-slider-controls { gap: 1.5rem; }
    }
    .section { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
    .section-title { text-align: center; margin-bottom: 3rem; }
    .section-title h2 { font-size: 1.875rem; font-weight: 700; margin-bottom: 0.75rem; }
    .section-title p { color: var(--text-secondary); font-size: 1rem; max-width: 500px; margin: 0 auto; }
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .step { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; text-align: center; position: relative; }
    .step-num { width: 44px; height: 44px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; margin: 0 auto 1rem; }
    .step h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .step p { color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6; }
    /* ── Trial section ── */
    .trial-section { margin-bottom: 2.5rem; padding: 2rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; position: relative; overflow: hidden; }
    .trial-section::before { content: ''; position: absolute; top: 0; right: 0; width: 340px; height: 100%; background: radial-gradient(ellipse at 100% 50%, rgba(40,167,69,0.07) 0%, transparent 70%); pointer-events: none; }
    .trial-badge { display: inline-block; background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.85rem; }
    .trial-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
    .trial-card { background: var(--bg-secondary); border: 2px dashed rgba(40,167,69,0.3); border-radius: 12px; padding: 1.5rem; }
    .trial-icon { font-size: 1.75rem; margin-bottom: 0.6rem; }
    .trial-features { list-style: none; padding: 0; margin: 0; }
    .trial-features li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; color: var(--text-secondary); padding: 0.3rem 0; }
    .trial-features li svg { flex-shrink: 0; color: #28a745; }
    .trial-cta-col { display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 0.85rem; }
    .trial-after-label { font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }
    .trial-after-plan { font-size: 1.35rem; font-weight: 700; color: var(--gold); }
    .trial-after-details { font-size: 0.78rem; color: var(--text-muted); line-height: 1.65; }
    .trial-cta-btn { display: block; width: 100%; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; text-align: center; text-decoration: none; background: linear-gradient(135deg, #28a745, #20c997); color: #fff; border: none; transition: all 0.2s; cursor: pointer; }
    .trial-cta-btn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(40,167,69,0.4); color: #fff; }
    .trial-footer { font-size: 0.72rem; color: var(--text-muted); text-align: center; margin-top: 1.25rem; }
    /* ── Pricing cards ── */
    .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .plan-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 2rem; display: flex; flex-direction: column; position: relative; transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s; }
    .plan-card:hover { border-color: var(--gold-muted); transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.28); }
    .plan-card.featured { border-color: var(--gold-muted); box-shadow: 0 0 0 1px rgba(212,175,55,0.2), 0 8px 32px rgba(212,175,55,0.1); }
    .plan-badge { position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.85rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .plan-name { font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-secondary); margin-bottom: 0.75rem; }
    .plan-price { font-size: 2.75rem; font-weight: 800; color: var(--gold); line-height: 1; margin-bottom: 0.2rem; letter-spacing: -0.02em; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--text-secondary); }
    .plan-billing { font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1.5rem; }
    .plan-divider { border: none; border-top: 1px solid var(--border); margin-bottom: 1.25rem; }
    .plan-features { list-style: none; flex: 1; margin-bottom: 1.75rem; padding: 0; }
    .plan-features li { display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; color: var(--text-secondary); padding: 0.4rem 0; }
    .plan-features li svg { flex-shrink: 0; color: var(--gold); }
    .plan-cta { display: block; width: 100%; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; text-align: center; text-decoration: none; }
    .plan-cta-gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .plan-cta-gold:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(212,175,55,0.35); color: #0a0d14; }
    .plan-cta-outline { background: transparent; border: 1px solid var(--gold-muted); color: var(--gold); }
    .plan-cta-outline:hover { background: rgba(212,175,55,0.08); color: var(--gold); }
    .addon-box { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem 1.5rem; margin-top: 2rem; }
    .addon-box h4 { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.75rem; }
    .addon-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.4rem 0; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
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
    .contact-form-section { padding: 5rem 2rem; max-width: 1200px; margin: 0 auto; }
    .contact-container { display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem; align-items: center; }
    .contact-info h2 { font-size: 2.25rem; font-weight: 700; margin-bottom: 1.25rem; }
    .contact-info p { color: var(--text-secondary); font-size: 1rem; line-height: 1.7; margin-bottom: 2rem; }
    .contact-info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
    .contact-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .contact-icon.email { background: rgba(212,175,55,0.1); color: var(--gold); }
    .contact-icon.phone { background: rgba(5,150,105,0.1); color: #6ee7b7; }
    .contact-icon.message { background: rgba(59,130,246,0.1); color: #60a5fa; }
    .contact-info-item strong { color: var(--text-primary); font-weight: 600; }
    .contact-info-item span { color: var(--text-secondary); font-size: 0.925rem; }
    .contact-form-wrapper { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; }
    .contact-form-wrapper h3 { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.75rem; color: var(--text-primary); }
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
    .form-input { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1rem; color: var(--text-primary); font-size: 0.925rem; transition: border-color 0.2s; }
    .form-input:focus { outline: none; border-color: var(--gold-muted); }
    .form-input::placeholder { color: var(--text-muted); }
    .form-textarea { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1rem; color: var(--text-primary); font-size: 0.925rem; min-height: 140px; resize: vertical; font-family: 'Inter', sans-serif; }
    .form-textarea:focus { outline: none; border-color: var(--gold-muted); }
    .btn-submit { display: block; width: 100%; padding: 0.9rem 1.5rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; text-align: center; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212,175,55,0.35); color: #0a0d14; }
    .form-success { display: none; padding: 1.25rem; background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); border-radius: 12px; color: #6ee7b7; text-align: center; margin-bottom: 1.5rem; }
    @media(max-width: 900px) { .contact-container { grid-template-columns: 1fr; } }
    @media(max-width: 768px) { .hero h1 { font-size: 2rem; } .steps { grid-template-columns: 1fr; } .contact-form-wrapper { padding: 1.75rem; } }
</style>
@endpush

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">✦ {{ __('welcome.openai_compatible') }}</div>
    <h1>{!! __('welcome.hero_title') !!}</h1>
    <p>{{ __('welcome.hero_subtitle') }}</p>
    <div class="hero-cta">
        <a href="/register" class="btn btn-gold" style="padding:0.75rem 2rem;font-size:1rem">{{ __('welcome.get_started') }}</a>
        <a href="#pricing" class="btn btn-outline" style="padding:0.75rem 2rem;font-size:1rem">{{ __('welcome.view_plans') }}</a>
        <a href="/docs" class="btn btn-outline" style="padding:0.75rem 2rem;font-size:1rem">{{ __('welcome.documentation') }}</a>
    </div>
    <div class="hero-subcta">
        <a href="/credits">{{ __('welcome.how_credits_work') }}</a>
        <span>|</span>
        <a href="/docs">{{ __('welcome.api_docs') }}</a>
        <span>|</span>
        <a href="/billing/plans">{{ __('welcome.pricing_details') }}</a>
    </div>
</section>

<!-- Hero Slider -->
<section class="hero-slider-section">
    <div class="hero-slider-wrapper">
        <div class="hero-slider-container">
            <div class="hero-slider">
                <div class="hero-slide active">
                    <div class="hero-slide-emoji">⚡</div>
                    <div class="hero-slide-title">Llama 3.2 3B</div>
                    <div class="hero-slide-desc">Fastest for everyday tasks · 1 credit/token</div>
                </div>
                <div class="hero-slide">
                    <div class="hero-slide-emoji">🧠</div>
                    <div class="hero-slide-title">DeepSeek V3.1 671B</div>
                    <div class="hero-slide-desc">Frontier reasoning at your fingertips · 2 credits/token</div>
                </div>
                <div class="hero-slide">
                    <div class="hero-slide-emoji">💬</div>
                    <div class="hero-slide-title">Qwen 3.5 397B</div>
                    <div class="hero-slide-desc">Largest MoE available · multilingual · 2 credits/token</div>
                </div>
                <div class="hero-slide">
                    <div class="hero-slide-emoji">🔌</div>
                    <div class="hero-slide-title">OpenAI-Compatible API</div>
                    <div class="hero-slide-desc">Drop-in replacement · zero code changes</div>
                </div>
                <div class="hero-slide">
                    <div class="hero-slide-emoji">📊</div>
                    <div class="hero-slide-title">45+ Models</div>
                    <div class="hero-slide-desc">One API. Local + Cloud. Pay per token.</div>
                </div>
            </div>
        </div>
        <div class="hero-slider-controls">
            <button class="hero-slider-btn" id="heroSliderPrev" aria-label="Previous slide">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="hero-slider-dots" id="heroSliderDots"></div>
            <button class="hero-slider-btn" id="heroSliderNext" aria-label="Next slide">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
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
        <h2>Simple, <span class="text-gold">Transparent</span> Pricing</h2>
        <p>All prices in Kuwaiti Dinar. Billed monthly. No hidden fees.</p>
    </div>

    {{-- Free Trial Box --}}
    <div class="trial-section">
        <div class="trial-badge">Free Trial</div>
        <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:1.5rem;">Try Before You Buy</h2>
        <div class="trial-grid">
            <div class="trial-card">
                <div class="trial-icon">⚡</div>
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.85rem;">7-Day Free Trial</h3>
                <ul class="trial-features">
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Full Starter tier features</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>1,000 credits included</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Small models (3–14B)</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>1 free API key</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Cancel anytime during trial</li>
                </ul>
            </div>
            <div class="trial-cta-col">
                <div class="trial-after-label">After Trial</div>
                <div class="trial-after-plan">Auto-bill to Starter</div>
                <div class="trial-after-details">15 KWD / month<br>Credit card required<br>Cancel anytime</div>
                <a href="/register" class="trial-cta-btn">Start Free Trial — Card Required</a>
            </div>
        </div>
        <p class="trial-footer">⚠️ Payments processed securely via KNET / credit card. Cancel anytime during the trial period.</p>
    </div>

    <div class="pricing-grid">

        {{-- Starter Tier --}}
        <div class="plan-card">
            <div class="plan-badge">Most Popular</div>
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
                    10 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Small models only
                </li>
            </ul>
            <div class="plan-cta-row">
                <a href="/register" class="plan-cta plan-cta-outline">Start Monthly Plan</a>
                <a href="/register" class="plan-cta plan-cta-outline">Start Free Trial</a>
            </div>
        </div>

        {{-- Basic Tier (featured) --}}
        <div class="plan-card featured">
            <div class="plan-badge">Best Value</div>
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
                    30 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    1 free API key
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All model sizes
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-gold">Start Monthly Plan</a>
        </div>

        {{-- Pro Tier --}}
        <div class="plan-card">
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
                    60 requests / minute
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    2 free API keys
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Priority cloud failover
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-outline">Start Monthly Plan</a>
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

<!-- Contact Us -->
<section class="contact-form-section" id="contact">
    <div class="section-title">
        <h2 style="color:var(--text-primary)">Need Help?</h2>
        <p>Have questions? Our team is here to assist you with integration, pricing, or technical support.</p>
    </div>
    <div class="contact-container">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p>Fill out the form and we'll respond to your email at <strong style="color:var(--gold)">soud@alphia.net</strong> within 24 hours.</p>

            <div class="contact-info-item">
                <div class="contact-icon email">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <strong>Email</strong>
                    <br>
                    <span>Use the contact form below</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon phone">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <strong>Phone</strong>
                    <br>
                    <span>Available on request</span>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="contact-icon message">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <strong>Support</strong>
                    <br>
                    <span>We respond within 24 hours</span>
                </div>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <h3>Send us a Message</h3>
            <div id="contact-form-success" class="form-success">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:block;margin:0 auto 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <strong>Message sent!</strong> We'll get back to you at soud@alphia.net within 24 hours.
            </div>
            <form id="contactForm" method="POST" action="/contact">
                @csrf
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-input" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="john@example.com" required>
                </div>
                <div class="form-group">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" class="form-input" placeholder="+965 1234 5678" required>
                </div>
                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-textarea" placeholder="How can we help you?" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2 style="font-size:2rem;font-weight:700;margin-bottom:0.75rem">Ready to get started?</h2>
    <p style="color:var(--text-secondary);margin-bottom:2rem">Join developers already using LLM Resayil. Pay with KNET or credit card.</p>
    <a href="/register" class="btn btn-gold" style="padding:0.85rem 2.5rem;font-size:1.05rem">Create Free Account</a>
</section>

@endsection

@push('scripts')
<script>
// Hero Slider
(() => {
    const slides = document.querySelectorAll('.hero-slide');
    const sliderContainer = document.querySelector('.hero-slider-container');
    const dotsContainer = document.getElementById('heroSliderDots');
    const prevBtn = document.getElementById('heroSliderPrev');
    const nextBtn = document.getElementById('heroSliderNext');

    let currentSlide = 0;
    let autoplayInterval = null;
    let isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Initialize dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.className = `hero-slider-dot ${index === 0 ? 'active' : ''}`;
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.hero-slider-dot');

    function goToSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        currentSlide = index;
        resetAutoplay();
    }

    function nextSlide() {
        goToSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        goToSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    function startAutoplay() {
        if (isReducedMotion) return;
        autoplayInterval = setInterval(nextSlide, 4000);
    }

    function stopAutoplay() {
        if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
        }
    }

    function resetAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    // Event listeners
    prevBtn.addEventListener('click', () => {
        prevSlide();
        stopAutoplay();
    });
    nextBtn.addEventListener('click', () => {
        nextSlide();
        stopAutoplay();
    });

    // Pause on hover
    sliderContainer.addEventListener('mouseenter', stopAutoplay);
    sliderContainer.addEventListener('mouseleave', resetAutoplay);

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    sliderContainer.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            nextSlide();
        } else if (touchEndX - touchStartX > 50) {
            prevSlide();
        }
    });

    // Start autoplay on load
    startAutoplay();
})();
</script>
@endpush
