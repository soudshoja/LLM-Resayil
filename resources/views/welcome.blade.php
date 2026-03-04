@extends('layouts.app')

@section('title', __('welcome.title'))

@push('styles')
<style>
    body { background: var(--bg-secondary); }

    /* ── Hero ── */
    .hero { text-align: center; padding: 5rem 2rem 3rem; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 500px; background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.1) 0%, transparent 70%); pointer-events: none; }
    .hero::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(ellipse at 20% 60%, rgba(212,175,55,0.04) 0%, transparent 50%); pointer-events: none; }
    .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.25); color: var(--gold); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.75rem; }
    .hero h1 { font-size: 3.5rem; font-weight: 800; line-height: 1.12; margin-bottom: 1.25rem; max-width: 760px; margin-left: auto; margin-right: auto; letter-spacing: -0.02em; }
    .hero h1 .text-gradient { background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-lead { font-size: 1.15rem; color: var(--text-secondary); max-width: 580px; margin: 0 auto 2.25rem; line-height: 1.75; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem; }
    .hero-trust { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2.5rem; }
    .hero-trust-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-muted); }
    .hero-trust-item svg { color: var(--gold); flex-shrink: 0; }

    /* ── Featured Models (slider card) ── */
    .featured-models-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--text-muted); text-align: center; margin-bottom: 0.75rem; }
    .hero-slider-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 0; overflow: hidden; max-width: 820px; margin: 0 auto; position: relative; box-shadow: 0 0 0 1px rgba(212,175,55,0.06), 0 20px 60px rgba(0,0,0,0.35); }
    .hero-slider-container { position: relative; width: 100%; height: 260px; }
    .hero-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.5s ease-in-out; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 2rem; }
    .hero-slide.active { opacity: 1; }
    .hero-slide-content { text-align: center; }
    .hero-slide-logo { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border-radius: 12px; font-size: 0.85rem; font-weight: 800; color: #fff; margin-bottom: 0.85rem; letter-spacing: -0.01em; }
    .slide-logo-meta    { background: linear-gradient(140deg, #0866FF 0%, #3b8fff 100%); }
    .slide-logo-deepseek{ background: linear-gradient(140deg, #3a58e8 0%, #6f88ff 100%); }
    .hero-slide-badge { display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.22); color: var(--gold); padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; margin-bottom: 0.75rem; }
    .hero-slide-model { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.6rem; line-height: 1.2; }
    .hero-slide-model .text-gradient { background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-slide p { font-size: 0.9rem; color: var(--text-secondary); max-width: 420px; margin: 0 auto; line-height: 1.6; }
    .hero-slider-footer { display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 0.9rem 1.5rem; border-top: 1px solid var(--border); background: rgba(0,0,0,0.15); }
    .hero-slider-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(212,175,55,0.3); border: 1.5px solid rgba(212,175,55,0.5); cursor: pointer; transition: all 0.3s; padding: 0; }
    .hero-slider-dot.active { background: var(--gold); border-color: var(--gold); transform: scale(1.3); }
    .hero-slider-dot:hover { background: rgba(212,175,55,0.6); }
    .hero-slider-counter { font-size: 0.72rem; color: var(--text-muted); letter-spacing: 0.08em; }

    /* ── Sections ── */
    .section { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
    .section-title { text-align: center; margin-bottom: 3rem; }
    .section-title h2 { font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem; }
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
    .trial-section-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; }
    .trial-card-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.85rem; }
    .trial-note { font-size: 0.75rem; color: var(--text-muted); text-align: center; margin-top: 0.85rem; }

    /* ── Button sizes ── */
    .btn-lg { padding: 0.75rem 2rem; font-size: 1rem; }
    .btn-xl { padding: 0.85rem 2.5rem; font-size: 1.05rem; }

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

    /* ── Addon box ── */
    .addon-box { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem 1.5rem; margin-top: 2rem; }
    .addon-box h4 { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.75rem; }
    .addon-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.4rem 0; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
    .addon-row:last-child { border-bottom: none; }
    .addon-row span:last-child { color: var(--gold); font-weight: 600; }
    .text-bonus { color: #28a745; font-size: 0.8em; font-weight: 600; }

    /* ── Code block ── */
    .code-block { background: #0a0d14; border: 1px solid var(--border); border-radius: 10px; padding: 1.5rem; overflow-x: auto; font-family: monospace; font-size: 0.85rem; line-height: 1.7; color: #e0e5ec; }
    .code-block .comment { color: #4b5563; }
    .code-block .string { color: #86efac; }
    .code-block .key { color: #93c5fd; }

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
    .ml-footer a { color: var(--gold); font-weight: 600; }
    .ml-footer a:hover { text-decoration: underline; }

    /* ── CTA section ── */
    .cta-section { text-align: center; padding: 5rem 2rem; background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, transparent 100%); border-top: 1px solid var(--border); }
    .cta-title { font-size: 2.25rem; font-weight: 700; margin-bottom: 0.75rem; }
    .cta-subtitle { color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.05rem; }

    /* ── Site footer ── */
    .site-footer { border-top: 1px solid var(--border); padding: 2rem; background: var(--bg-secondary); }
    .site-footer-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .site-footer-brand { font-weight: 700; font-size: 0.95rem; background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .site-footer-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .site-footer-links a { font-size: 0.825rem; color: var(--text-muted); transition: color 0.2s; }
    .site-footer-links a:hover { color: var(--gold); }
    .site-footer-copy { font-size: 0.78rem; color: var(--text-muted); }

    /* ── Dividers ── */
    .divider { border: none; border-top: 1px solid var(--border); margin: 0; }

    /* ── Responsive ── */
    @media(max-width: 900px) {
        .pricing-grid { grid-template-columns: 1fr; }
        .trial-grid { grid-template-columns: 1fr; }
        .ml-grid { grid-template-columns: repeat(2, 1fr); }
        .site-footer-inner { flex-direction: column; text-align: center; }
    }
    @media(max-width: 768px) {
        .hero h1 { font-size: 2.25rem; }
        .steps { grid-template-columns: 1fr; }
        .hero-slider-container { height: 220px; }
        .hero-slide h2, .hero-slide-model { font-size: 1.4rem; }
        .hero-trust { gap: 1rem; }
    }
    @media(max-width: 560px) {
        .ml-grid { grid-template-columns: 1fr; }
        .hero h1 { font-size: 1.875rem; }
        .site-footer-links { justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-slider-dot');
    let currentSlide = 0;
    let autoPlay = null;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (dots[i]) dots[i].classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
                if (dots[i]) dots[i].classList.add('active');
            }
        });
        currentSlide = index;
        const counter = document.getElementById('heroSlideCounter');
        if (counter) counter.textContent = (index + 1) + ' / ' + slides.length;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    function startAutoPlay() {
        autoPlay = setInterval(nextSlide, 4500);
    }

    function stopAutoPlay() {
        if (autoPlay) { clearInterval(autoPlay); autoPlay = null; }
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => { stopAutoPlay(); showSlide(index); startAutoPlay(); });
    });

    document.querySelectorAll('.hero-slider-prev, .hero-slider-next').forEach(btn => {
        btn.addEventListener('click', function() {
            stopAutoPlay();
            this.classList.contains('hero-slider-prev') ? prevSlide() : nextSlide();
            startAutoPlay();
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') { stopAutoPlay(); prevSlide(); startAutoPlay(); }
        if (e.key === 'ArrowRight') { stopAutoPlay(); nextSlide(); startAutoPlay(); }
    });

    // Initialize
    showSlide(0);
    startAutoPlay();

    // Pause on hover
    const sliderCard = document.querySelector('.hero-slider-card');
    sliderCard?.addEventListener('mouseenter', stopAutoPlay);
    sliderCard?.addEventListener('mouseleave', startAutoPlay);
});
</script>
@endpush

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        {{ __('welcome.hero_badge') }}
    </div>
    <h1>{{ __('welcome.hero_headline_before') }} <span class="text-gradient">{{ __('welcome.hero_headline_gold') }}</span> {{ __('welcome.hero_headline_after') }}</h1>
    <p class="hero-lead">{{ __('welcome.hero_subtitle') }}</p>
    <div class="hero-cta">
        <a href="/register" class="btn btn-gold btn-lg">{{ __('welcome.cta_start_free_trial') }}</a>
        <a href="#pricing" class="btn btn-outline btn-lg">{{ __('welcome.cta_view_pricing') }}</a>
    </div>
    <div class="hero-trust">
        <div class="hero-trust-item">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ __('welcome.trust_openai_compatible') }}
        </div>
        <div class="hero-trust-item">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('welcome.trust_pay_per_use') }}
        </div>
        <div class="hero-trust-item">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
            {{ __('welcome.trust_kuwait_based') }}
        </div>
        <div class="hero-trust-item">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ __('welcome.trust_free_trial') }}
        </div>
    </div>

    <!-- Featured Models Slider -->
    <div class="featured-models-label">{{ __('welcome.featured_models_label') }}</div>
    <div class="hero-slider-card">
        <div class="hero-slider-container">
            <!-- Slide 1: Lightweight & Fast -->
            <div class="hero-slide active">
                <div class="hero-slide-content">
                    <div class="hero-slide-logo slide-logo-meta">M</div>
                    <div class="hero-slide-badge">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('welcome.lightweight') }}
                    </div>
                    <div class="hero-slide-model">{{ __('welcome.llama_32_3b_plain') }} <span class="text-gradient">{{ __('welcome.llama_32_3b_highlight') }}</span></div>
                    <p>{{ __('welcome.lightweight_fast') }}</p>
                </div>
            </div>
            <!-- Slide 2: Frontier Model -->
            <div class="hero-slide">
                <div class="hero-slide-content">
                    <div class="hero-slide-logo slide-logo-deepseek">D</div>
                    <div class="hero-slide-badge">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ __('welcome.frontier_model') }}
                    </div>
                    <div class="hero-slide-model">{{ __('welcome.deepseek_v31_671b_plain') }} <span class="text-gradient">{{ __('welcome.deepseek_v31_671b_highlight') }}</span></div>
                    <p>{{ __('welcome.frontier_reasoning') }}</p>
                </div>
            </div>
        </div>
        <!-- Slider Footer with Controls -->
        <div class="hero-slider-footer">
            <button class="hero-slider-dot" aria-label="{{ __('welcome.slide_1_label') }}"></button>
            <button class="hero-slider-dot" aria-label="{{ __('welcome.slide_2_label') }}"></button>
            <span class="hero-slider-counter" id="heroSlideCounter">1 / 2</span>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section">
    <div class="section-title">
        <h2>{{ __('welcome.how_it_works_title') }}</h2>
        <p>{{ __('welcome.how_it_works_subtitle') }}</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <h3>{{ __('welcome.step_1_title') }}</h3>
            <p>{{ __('welcome.step_1_description') }}</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h3>{{ __('welcome.step_2_title') }}</h3>
            <p>{{ __('welcome.step_2_description') }}</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h3>{{ __('welcome.step_3_title') }}</h3>
            <p>{{ __('welcome.step_3_description') }}</p>
        </div>
    </div>
</section>

<hr class="divider">

<!-- Pricing -->
<section class="section" id="pricing">
    <div class="section-title">
        <h2>{{ __('welcome.pricing_title_plain') }} <span class="text-gold">{{ __('welcome.pricing_title_gold') }}</span></h2>
        <p>{{ __('welcome.pricing_subtitle') }}</p>
    </div>

    {{-- Free Trial Box --}}
    <div class="trial-section">
        <div class="trial-badge">{{ __('welcome.free_trial_badge') }}</div>
        <h2 class="trial-section-title">{{ __('welcome.try_before_buy') }}</h2>
        <div class="trial-grid">
            <div class="trial-card">
                <div class="trial-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#28a745"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="trial-card-title">{{ __('welcome.seven_day_trial') }}</h3>
                <ul class="trial-features">
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ __('welcome.full_starter_features') }}</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ __('welcome.one_thousand_credits') }}</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ __('welcome.small_models_only') }}</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ __('welcome.one_free_api_key') }}</li>
                    <li><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ __('welcome.cancel_anytime_trial') }}</li>
                </ul>
            </div>
            <div class="trial-cta-col">
                <div class="trial-after-label">{{ __('welcome.after_trial_label') }}</div>
                <div class="trial-after-plan">{{ __('welcome.auto_bill_to_starter') }}</div>
                <div class="trial-after-details">{{ __('welcome.card_required_for_trial') }}<br>{{ __('welcome.cancel_anytime') }}</div>
                <a href="/register" class="trial-cta-btn">{{ __('welcome.start_free_trial_card_required') }}</a>
            </div>
        </div>
        <p class="trial-footer">{{ __('welcome.payments_secure') }}</p>
    </div>

    <div class="pricing-grid">

        {{-- Starter Tier --}}
        <div class="plan-card">
            <div class="plan-badge">{{ __('welcome.most_popular') }}</div>
            <div class="plan-name">{{ __('welcome.starter_tier') }}</div>
            <div class="plan-price">15 <span>KWD</span></div>
            <div class="plan-billing">{{ __('welcome.per_month') }} &nbsp;&middot;&nbsp; {{ __('welcome.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.one_thousand_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.ten_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.one_free_api_key') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.small_models_only') }}
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-outline">{{ __('welcome.start_monthly_plan') }}</a>
        </div>

        {{-- Basic Tier (featured) --}}
        <div class="plan-card featured">
            <div class="plan-badge">{{ __('welcome.best_value') }}</div>
            <div class="plan-name">{{ __('welcome.basic_tier') }}</div>
            <div class="plan-price">25 <span>KWD</span></div>
            <div class="plan-billing">{{ __('welcome.per_month') }} &nbsp;&middot;&nbsp; {{ __('welcome.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.three_thousand_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.thirty_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.one_free_api_key') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.all_model_sizes') }}
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-gold">{{ __('welcome.start_monthly_plan') }}</a>
        </div>

        {{-- Pro Tier --}}
        <div class="plan-card">
            <div class="plan-name">{{ __('welcome.pro_tier') }}</div>
            <div class="plan-price">45 <span>KWD</span></div>
            <div class="plan-billing">{{ __('welcome.per_month') }} &nbsp;&middot;&nbsp; {{ __('welcome.billed_monthly') }}</div>
            <hr class="plan-divider">
            <ul class="plan-features">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.ten_thousand_credits_month') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.sixty_requests_minute') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.two_free_api_keys') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('welcome.priority_cloud_failover') }}
                </li>
            </ul>
            <a href="/register" class="plan-cta plan-cta-outline">{{ __('welcome.start_monthly_plan') }}</a>
        </div>

    </div>
    <p class="trial-note">{{ __('welcome.card_required_for_trial') }} {{ __('welcome.cancel_anytime') }} {{ __('welcome.payments_secure') }}</p>

    <!-- Credit top-up & addons info -->
    <div class="addon-box">
        <h4>{{ __('welcome.credit_top_ups_title') }}</h4>
        <div class="addon-row"><span>{{ __('welcome.five_hundred_credits') }}</span><span>{{ __('welcome.five_kwd') }}</span></div>
        <div class="addon-row"><span>{{ __('welcome.one_thousand_one_hundred_credits') }}</span><span>{{ __('welcome.ten_kwd') }} <span class="text-bonus">{{ __('welcome.bonus_ten') }}</span></span></div>
        <div class="addon-row"><span>{{ __('welcome.three_thousand_credits') }}</span><span>{{ __('welcome.twenty_five_kwd') }} <span class="text-bonus">{{ __('welcome.bonus_twenty') }}</span></span></div>
        <div class="addon-row"><span>{{ __('welcome.credits_per_one_k_tokens') }}</span><span>{{ __('welcome.local_cloud_pricing') }}</span></div>
    </div>
</section>

<hr class="divider">

<!-- Available Models -->
<section class="section" id="models">
    <div class="section-title">
        <h2>{{ __('welcome.available_models_title') }}</h2>
        <p>{{ __('welcome.available_models_description') }}</p>
    </div>

    <!-- General Chat -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">{{ __('welcome.general_chat_category') }}</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">
            <div class="ml-card">
                <div class="ml-avatar ml-av-meta">M</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.llama_32_3b') }}</div>
                    <div class="ml-company">{{ __('welcome.llama_32_3b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.llama_32_3b_description') }}</div>
                </div>
            </div>
            <div class="ml-card">
                <div class="ml-avatar ml-av-mistral">Mi</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.mistral_small_32_24b') }}</div>
                    <div class="ml-company">{{ __('welcome.mistral_small_32_24b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.mistral_small_32_24b_description') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Code -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">{{ __('welcome.code_category') }}</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">
            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.qwen_25_coder_14b') }}</div>
                    <div class="ml-company">{{ __('welcome.qwen_25_coder_14b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.qwen_25_coder_14b_description') }}</div>
                </div>
            </div>
            <div class="ml-card">
                <div class="ml-avatar ml-av-deepseek">D</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.deepseek_coder_67b') }}</div>
                    <div class="ml-company">{{ __('welcome.deepseek_coder_67b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.deepseek_coder_67b_description') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Multimodal -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">{{ __('welcome.vision_multimodal_category') }}</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">
            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.qwen3_vl_32b') }}</div>
                    <div class="ml-company">{{ __('welcome.qwen3_vl_32b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.qwen3_vl_32b_description') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Frontier -->
    <div class="ml-category-group">
        <div class="ml-category-header">
            <span class="ml-category-diamond">&#9670;</span>
            <span class="ml-category-label">{{ __('welcome.frontier_category') }}</span>
            <span class="ml-category-line"></span>
        </div>
        <div class="ml-grid">
            <div class="ml-card">
                <div class="ml-avatar ml-av-deepseek">D</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.deepseek_v31_671b') }}</div>
                    <div class="ml-company">{{ __('welcome.deepseek_v31_671b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.deepseek_v31_671b_description') }}</div>
                </div>
            </div>
            <div class="ml-card">
                <div class="ml-avatar ml-av-qwen">Q</div>
                <div class="ml-body">
                    <div class="ml-model-name">{{ __('welcome.qwen_35_397b') }}</div>
                    <div class="ml-company">{{ __('welcome.qwen_35_397b_company') }}</div>
                    <div class="ml-tagline">{{ __('welcome.qwen_35_397b_description') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="ml-footer">
        <p>{!! str_replace(':link', '<a href="/dashboard">', __('welcome.model_selection_notice')) !!}</p>
    </div>
</section>

<hr class="divider">

<!-- Code Example -->
<section class="section" id="docs">
    <div class="section-title">
        <h2>{{ __('welcome.drop_in_replacement_title') }}</h2>
        <p>{{ __('welcome.drop_in_replacement_description') }}</p>
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
    <h2 class="cta-title">{{ __('welcome.ready_to_get_started') }}</h2>
    <p class="cta-subtitle">{{ __('welcome.join_developers') }}</p>
    <a href="/register" class="btn btn-gold btn-xl">{{ __('welcome.create_free_account') }}</a>
</section>

<!-- Site Footer -->
<footer class="site-footer">
    <div class="site-footer-inner">
        <span class="site-footer-brand">LLM Resayil</span>
        <nav class="site-footer-links">
            <a href="/docs">{{ __('navigation.docs') }}</a>
            <a href="/credits">{{ __('navigation.credits') }}</a>
            <a href="#pricing">{{ __('welcome.cta_view_pricing') }}</a>
            <a href="/privacy-policy">{{ __('welcome.footer_privacy') }}</a>
            <a href="/terms-of-service">{{ __('welcome.footer_terms') }}</a>
            <a href="/contact">{{ __('welcome.footer_contact') }}</a>
        </nav>
        <span class="site-footer-copy">&copy; {{ date('Y') }} LLM Resayil</span>
    </div>
</footer>

@endsection
