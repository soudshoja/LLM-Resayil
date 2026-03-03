@extends('layouts.app')

@section('title', __('welcome.title'))

@push('styles')
{{-- JSON-LD Structured Data --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "LLM Resayil",
  "url": "https://llm.resayil.io",
  "description": "OpenAI-compatible LLM API with 45+ models. Pay per token. KNET payments accepted.",
  "logo": "https://llm.resayil.io/favicon.ico",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer support",
    "url": "https://llm.resayil.io/contact"
  },
  "sameAs": []
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "LLM Resayil API",
  "applicationCategory": "DeveloperApplication",
  "operatingSystem": "Any",
  "description": "OpenAI-compatible REST API providing access to 45+ AI models including Llama, DeepSeek, Qwen. Local GPU inference and cloud proxies. Pay-per-token pricing. KNET payments.",
  "url": "https://llm.resayil.io",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "KWD",
    "description": "Pay per token. Start with 5,000 credits for 2 KWD."
  },
  "featureList": ["OpenAI-compatible API", "45+ AI models", "Local GPU inference", "KNET payments", "Pay per token"]
}
</script>
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
    /* ── Hero Model Carousel ── */
    .hmc-section { padding: 4rem 2rem 3rem; max-width: 1200px; margin: 0 auto; }
    .hmc-header { text-align: center; margin-bottom: 2.75rem; }
    .hmc-header h2 { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.6rem; letter-spacing: -0.01em; }
    .hmc-header h2 em { font-style: normal; background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .hmc-header-underline { width: 56px; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); border-radius: 2px; margin: 0 auto; }
    /* Outer track */
    .hmc-track-outer { position: relative; overflow: hidden; }
    /* Slides container — no gap so translateX(-N*100%) aligns exactly */
    .hmc-track { display: flex; gap: 0; transition: transform 0.55s cubic-bezier(0.4,0,0.2,1); will-change: transform; }
    @media (prefers-reduced-motion: reduce) { .hmc-track { transition: none; } }
    /* Individual slide — glassmorphism card */
    .hmc-slide { flex: 0 0 100%; min-width: 0; padding: 0 0.25rem; }
    .hmc-card { background: linear-gradient(145deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%); border: 1px solid rgba(212,175,55,0.22); border-radius: 20px; padding: 2.5rem 2.75rem; display: flex; align-items: center; gap: 2.75rem; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); box-shadow: 0 8px 40px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.06); position: relative; overflow: hidden; transition: border-color 0.3s; }
    .hmc-card::before { content: ''; position: absolute; top: 0; right: 0; width: 280px; height: 280px; background: radial-gradient(circle at 100% 0%, rgba(212,175,55,0.07) 0%, transparent 65%); pointer-events: none; }
    .hmc-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 200px; height: 200px; background: radial-gradient(circle at 0% 100%, rgba(212,175,55,0.04) 0%, transparent 65%); pointer-events: none; }
    /* Icon panel */
    .hmc-icon-panel { flex-shrink: 0; width: 112px; height: 112px; border-radius: 18px; background: linear-gradient(140deg, rgba(212,175,55,0.12) 0%, rgba(212,175,55,0.04) 100%); border: 1px solid rgba(212,175,55,0.28); display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; }
    .hmc-icon-panel svg { width: 52px; height: 52px; color: var(--gold); filter: drop-shadow(0 0 14px rgba(212,175,55,0.45)); }
    /* Text body */
    .hmc-body { flex: 1; min-width: 0; position: relative; z-index: 1; }
    .hmc-badges { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; }
    .hmc-badge { display: inline-flex; align-items: center; padding: 0.22rem 0.7rem; border-radius: 20px; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
    .hmc-badge-local { background: rgba(5,150,105,0.15); color: #6ee7b7; border: 1px solid rgba(5,150,105,0.35); }
    .hmc-badge-cloud { background: rgba(99,102,241,0.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.35); }
    .hmc-badge-api { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.35); }
    .hmc-badge-all { background: rgba(212,175,55,0.13); color: var(--gold); border: 1px solid rgba(212,175,55,0.32); }
    .hmc-credit-pill { display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.2); border-radius: 20px; padding: 0.22rem 0.75rem; font-size: 0.7rem; font-weight: 600; color: var(--gold-light); }
    .hmc-credit-pill svg { width: 11px; height: 11px; opacity: 0.8; }
    .hmc-model-name { font-size: 1.85rem; font-weight: 800; color: #f0f4ff; line-height: 1.2; margin-bottom: 0.6rem; letter-spacing: -0.02em; }
    .hmc-stat { font-size: 1rem; font-weight: 600; color: var(--gold); margin-bottom: 0.5rem; }
    .hmc-desc { font-size: 0.935rem; color: var(--text-secondary); line-height: 1.65; max-width: 520px; }
    /* Controls bar */
    .hmc-controls { display: flex; align-items: center; justify-content: center; gap: 1.5rem; margin-top: 2rem; }
    .hmc-arrow { width: 44px; height: 44px; border-radius: 50%; background: transparent; border: 1.5px solid rgba(212,175,55,0.4); color: var(--gold); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s, border-color 0.2s, box-shadow 0.2s; flex-shrink: 0; }
    .hmc-arrow:hover { background: rgba(212,175,55,0.12); border-color: var(--gold); box-shadow: 0 0 18px rgba(212,175,55,0.2); }
    .hmc-arrow svg { width: 18px; height: 18px; }
    .hmc-dots { display: flex; gap: 0.5rem; align-items: center; }
    .hmc-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(212,175,55,0.2); cursor: pointer; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); border: none; padding: 0; }
    .hmc-dot.active { background: var(--gold); width: 26px; border-radius: 4px; }
    /* Progress bar */
    .hmc-progress { position: absolute; bottom: 0; left: 0; height: 2px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); border-radius: 1px; transition: width 0.1s linear; }
    /* Responsive */
    @media(max-width: 768px) {
        .hmc-card { flex-direction: column; align-items: flex-start; gap: 1.5rem; padding: 1.75rem; }
        .hmc-icon-panel { width: 72px; height: 72px; border-radius: 14px; }
        .hmc-icon-panel svg { width: 34px; height: 34px; }
        .hmc-model-name { font-size: 1.35rem; }
        .hmc-stat { font-size: 0.9rem; }
        .hmc-desc { font-size: 0.875rem; }
        .hmc-header h2 { font-size: 1.35rem; }
    }
    @media(max-width: 480px) {
        .hmc-section { padding: 2.5rem 1rem 2rem; }
        .hmc-card { padding: 1.25rem; }
        .hmc-model-name { font-size: 1.15rem; }
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
    .site-footer {
        background: var(--bg-card, #13161d);
        border-top: 1px solid var(--border, #1e2330);
        padding: 2rem 1.5rem;
        margin-top: 4rem;
        text-align: center;
    }
    .footer-inner { max-width: 900px; margin: 0 auto; }
    .footer-links {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem 1rem;
        margin-bottom: 1rem;
    }
    .footer-links a {
        color: var(--text-muted, #888);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s;
    }
    .footer-links a:hover { color: var(--gold, #d4af37); }
    .footer-sep { color: var(--border, #333); font-size: 0.75rem; }
    .footer-copy { color: var(--text-muted, #888); font-size: 0.8rem; margin: 0; }
    [dir="rtl"] .footer-links { flex-direction: row-reverse; }
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

<!-- Model Carousel -->
<section class="hmc-section" aria-label="Model showcase carousel">
    <div class="hmc-header">
        <h2>Explore Our <em>Model Lineup</em></h2>
        <div class="hmc-header-underline"></div>
    </div>

    <div class="hmc-track-outer" id="hmcTrackOuter">
        <div class="hmc-track" id="hmcTrack" role="list">

            <!-- Slide 1: Llama 3.2 3B -->
            <div class="hmc-slide" role="listitem" aria-label="Llama 3.2 3B — fastest local model">
                <div class="hmc-card">
                    <div class="hmc-icon-panel" aria-hidden="true">
                        <!-- Lightning bolt SVG — speed -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </div>
                    <div class="hmc-body">
                        <div class="hmc-badges">
                            <span class="hmc-badge hmc-badge-local">Local GPU</span>
                            <span class="hmc-credit-pill">
                                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M9 9h4.5a1.5 1.5 0 010 3H9m0 0h4.5a1.5 1.5 0 010 3H9" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                1 credit / token
                            </span>
                        </div>
                        <div class="hmc-model-name">Llama 3.2 3B</div>
                        <div class="hmc-stat">Fastest local model on the platform</div>
                        <div class="hmc-desc">Meta's lightweight powerhouse — blazing response times for everyday tasks, chatbots, and high-throughput workloads. Runs entirely on local GPU hardware.</div>
                    </div>
                    <div class="hmc-progress" id="hmcProgress" style="width:0%"></div>
                </div>
            </div>

            <!-- Slide 2: DeepSeek V3.1 671B -->
            <div class="hmc-slide" role="listitem" aria-label="DeepSeek V3.1 671B — frontier reasoning">
                <div class="hmc-card">
                    <div class="hmc-icon-panel" aria-hidden="true">
                        <!-- Neural network / brain SVG -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="4" r="2"/>
                            <circle cx="4" cy="12" r="2"/>
                            <circle cx="20" cy="12" r="2"/>
                            <circle cx="8" cy="20" r="2"/>
                            <circle cx="16" cy="20" r="2"/>
                            <path d="M12 6v4m0 0l-6.5 4m6.5-4l6.5 4M5.5 13.5l2 5m9-5l-2 5"/>
                            <circle cx="12" cy="10" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="hmc-body">
                        <div class="hmc-badges">
                            <span class="hmc-badge hmc-badge-cloud">Cloud Proxy</span>
                            <span class="hmc-credit-pill">
                                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M9 9h4.5a1.5 1.5 0 010 3H9m0 0h4.5a1.5 1.5 0 010 3H9" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                2 credits / token
                            </span>
                        </div>
                        <div class="hmc-model-name">DeepSeek V3.1 671B</div>
                        <div class="hmc-stat">Frontier reasoning at scale</div>
                        <div class="hmc-desc">671 billion parameters of frontier-class intelligence. Built for complex reasoning, deep analysis, and multi-step problem solving. Cloud-proxied with automatic failover.</div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Qwen 3.5 397B -->
            <div class="hmc-slide" role="listitem" aria-label="Qwen 3.5 397B — largest MoE, multilingual">
                <div class="hmc-card">
                    <div class="hmc-icon-panel" aria-hidden="true">
                        <!-- Crystal / MoE lattice SVG -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12,2 20,7 20,17 12,22 4,17 4,7"/>
                            <polygon points="12,7 16,9.5 16,14.5 12,17 8,14.5 8,9.5"/>
                            <line x1="12" y1="2" x2="12" y2="7"/>
                            <line x1="20" y1="7" x2="16" y2="9.5"/>
                            <line x1="20" y1="17" x2="16" y2="14.5"/>
                            <line x1="12" y1="22" x2="12" y2="17"/>
                            <line x1="4" y1="17" x2="8" y2="14.5"/>
                            <line x1="4" y1="7" x2="8" y2="9.5"/>
                        </svg>
                    </div>
                    <div class="hmc-body">
                        <div class="hmc-badges">
                            <span class="hmc-badge hmc-badge-cloud">Cloud Proxy</span>
                            <span class="hmc-credit-pill">
                                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M9 9h4.5a1.5 1.5 0 010 3H9m0 0h4.5a1.5 1.5 0 010 3H9" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
                                2 credits / token
                            </span>
                        </div>
                        <div class="hmc-model-name">Qwen 3.5 397B</div>
                        <div class="hmc-stat">Largest MoE · true multilingual intelligence</div>
                        <div class="hmc-desc">Alibaba's Mixture-of-Experts flagship. 397 billion parameters with native Arabic, English, Chinese support and deep multilingual coverage across 29 languages.</div>
                    </div>
                </div>
            </div>

            <!-- Slide 4: OpenAI-Compatible API -->
            <div class="hmc-slide" role="listitem" aria-label="OpenAI-Compatible API — drop-in replacement">
                <div class="hmc-card">
                    <div class="hmc-icon-panel" aria-hidden="true">
                        <!-- Plug / connector SVG -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="7" y="2" width="10" height="8" rx="2"/>
                            <path d="M9 2V0M15 2V0"/>
                            <path d="M12 10v4"/>
                            <path d="M9 14h6a3 3 0 010 6H9a3 3 0 010-6z"/>
                            <path d="M12 20v2"/>
                        </svg>
                    </div>
                    <div class="hmc-body">
                        <div class="hmc-badges">
                            <span class="hmc-badge hmc-badge-api">OpenAI SDK</span>
                            <span class="hmc-credit-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                Zero code changes
                            </span>
                        </div>
                        <div class="hmc-model-name">OpenAI-Compatible API</div>
                        <div class="hmc-stat">Drop-in replacement — change only the base URL</div>
                        <div class="hmc-desc">Works with the official OpenAI Python SDK, Node.js SDK, LangChain, LlamaIndex, and any HTTP client. Point <code style="color:var(--gold);font-size:0.85em">base_url</code> to our endpoint and you're done.</div>
                    </div>
                </div>
            </div>

            <!-- Slide 5: 45+ Models -->
            <div class="hmc-slide" role="listitem" aria-label="45+ models — one API, local and cloud">
                <div class="hmc-card">
                    <div class="hmc-icon-panel" aria-hidden="true">
                        <!-- Grid / catalog SVG -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </div>
                    <div class="hmc-body">
                        <div class="hmc-badges">
                            <span class="hmc-badge hmc-badge-all">All Tiers</span>
                            <span class="hmc-credit-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Pay per token
                            </span>
                        </div>
                        <div class="hmc-model-name">45+ Models</div>
                        <div class="hmc-stat">One API · Local GPU + Cloud Proxy · All tiers</div>
                        <div class="hmc-desc">From 3B lightweight models to 671B frontier giants. Local GPU inference with automatic cloud failover. All models accessible on every subscription tier — no gating.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Carousel controls -->
    <div class="hmc-controls">
        <button class="hmc-arrow" id="hmcPrev" aria-label="Previous model">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="hmc-dots" id="hmcDots" role="tablist" aria-label="Carousel navigation"></div>
        <button class="hmc-arrow" id="hmcNext" aria-label="Next model">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>
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
            <a href="/register" class="plan-cta plan-cta-outline">Start Monthly Plan</a>
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
                    Priority support
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

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-links">
            <a href="{{ route('about') }}">{{ __('navigation.about') }}</a>
            <span class="footer-sep">·</span>
            <a href="{{ route('privacy-policy') }}">{{ __('welcome.privacy_policy') }}</a>
            <span class="footer-sep">·</span>
            <a href="{{ route('terms-of-service') }}">{{ __('welcome.terms_of_service') }}</a>
            <span class="footer-sep">·</span>
            <a href="/docs">{{ __('navigation.docs') }}</a>
            <span class="footer-sep">·</span>
            <a href="/contact">{{ __('welcome.contact') }}</a>
        </div>
        <p class="footer-copy">© {{ date('Y') }} LLM Resayil. {{ __('welcome.all_rights_reserved') }}</p>
    </div>
</footer>

@endsection

@push('scripts')
<script>
// ── Model Carousel ──────────────────────────────────────────────────────────
(() => {
    const track      = document.getElementById('hmcTrack');
    const outer      = document.getElementById('hmcTrackOuter');
    const dotsWrap   = document.getElementById('hmcDots');
    const prevBtn    = document.getElementById('hmcPrev');
    const nextBtn    = document.getElementById('hmcNext');
    const progressEl = document.getElementById('hmcProgress');

    if (!track) return;

    const slides       = track.querySelectorAll('.hmc-slide');
    const total        = slides.length;
    const INTERVAL_MS  = 4500;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let current   = 0;
    let timer     = null;
    let progTimer = null;
    let progStart = null;
    let touchX0   = 0;

    // ── Build dots ──────────────────────────────────────────────────────────
    slides.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = 'hmc-dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('role', 'tab');
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.addEventListener('click', () => { goTo(i); pauseAutoplay(); });
        dotsWrap.appendChild(dot);
    });
    const dots = dotsWrap.querySelectorAll('.hmc-dot');

    // ── Core go-to ──────────────────────────────────────────────────────────
    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
        resetProgress();
    }

    // ── Progress bar ────────────────────────────────────────────────────────
    function resetProgress() {
        if (!progressEl || reducedMotion) return;
        cancelAnimationFrame(progTimer);
        progressEl.style.transition = 'none';
        progressEl.style.width = '0%';
        progStart = performance.now();
        animProgress();
    }

    function animProgress(ts) {
        if (!progStart) progStart = ts;
        const elapsed = ts - progStart;
        const pct = Math.min((elapsed / INTERVAL_MS) * 100, 100);
        progressEl.style.width = pct + '%';
        if (pct < 100) progTimer = requestAnimationFrame(animProgress);
    }

    // ── Autoplay ────────────────────────────────────────────────────────────
    function startAutoplay() {
        if (reducedMotion) return;
        timer = setInterval(() => goTo(current + 1), INTERVAL_MS);
        resetProgress();
    }

    function pauseAutoplay() {
        clearInterval(timer);
        timer = null;
        cancelAnimationFrame(progTimer);
        if (progressEl) progressEl.style.width = '0%';
    }

    function resumeAutoplay() {
        if (reducedMotion || timer) return;
        startAutoplay();
    }

    // ── Arrow buttons ───────────────────────────────────────────────────────
    prevBtn.addEventListener('click', () => { goTo(current - 1); pauseAutoplay(); });
    nextBtn.addEventListener('click', () => { goTo(current + 1); pauseAutoplay(); });

    // ── Pause on hover ──────────────────────────────────────────────────────
    outer.addEventListener('mouseenter', pauseAutoplay);
    outer.addEventListener('mouseleave', resumeAutoplay);

    // ── Touch / swipe ───────────────────────────────────────────────────────
    outer.addEventListener('touchstart', (e) => {
        touchX0 = e.changedTouches[0].clientX;
    }, { passive: true });

    outer.addEventListener('touchend', (e) => {
        const delta = e.changedTouches[0].clientX - touchX0;
        if (Math.abs(delta) < 40) return;
        goTo(delta < 0 ? current + 1 : current - 1);
        pauseAutoplay();
    }, { passive: true });

    // ── Keyboard accessibility ──────────────────────────────────────────────
    outer.setAttribute('tabindex', '0');
    outer.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft')  { goTo(current - 1); pauseAutoplay(); }
        if (e.key === 'ArrowRight') { goTo(current + 1); pauseAutoplay(); }
    });

    // ── Init ────────────────────────────────────────────────────────────────
    goTo(0);
    startAutoplay();
})();
</script>
@endpush
