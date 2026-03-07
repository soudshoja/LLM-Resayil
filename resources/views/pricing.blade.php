@extends('layouts.app')

@section('title', $pageTitle ?? 'Pricing — LLM Resayil')

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   PRICING PAGE — COMPLETE REDESIGN
   Dark Luxury · Gold Accents · Premium SaaS
══════════════════════════════════════════════════ */

/* ── Layout shell ── */
.pricing-page {
    background: var(--bg-secondary);
    min-height: 100vh;
}

/* ── Shared typography helpers ── */
.pr-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: var(--gold); margin-bottom: 1rem;
}
.pr-label::before {
    content: ''; display: block; width: 18px; height: 2px;
    background: var(--gold); border-radius: 2px;
}
.pr-h2 {
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 800; line-height: 1.15;
    letter-spacing: -0.025em; margin-bottom: 0.85rem;
}
.pr-sub {
    font-size: 1rem; color: var(--text-secondary);
    line-height: 1.7; max-width: 580px;
}
.pr-section {
    max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 5rem;
}
.pr-center { text-align: center; }
.pr-center .pr-sub { margin-left: auto; margin-right: auto; }

/* ── Scroll-reveal ── */
.fu { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fu.on { opacity: 1; transform: none; }
.d1 { transition-delay: 0.10s; }
.d2 { transition-delay: 0.20s; }
.d3 { transition-delay: 0.30s; }
@media (prefers-reduced-motion: reduce) { .fu { opacity:1; transform:none; transition:none; } }

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.pr-hero {
    position: relative; overflow: hidden;
    padding: 5.5rem 1.5rem 4.5rem;
    text-align: center;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(212,175,55,0.08) 0%, transparent 70%);
}
.pr-hero::after {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,0.016) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,0.016) 1px, transparent 1px);
    background-size: 72px 72px;
}
.pr-hero-inner { max-width: 760px; margin: 0 auto; position: relative; z-index: 1; }
.pr-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.22);
    border-radius: 100px; padding: 0.45rem 1.1rem;
    font-size: 0.82rem; font-weight: 600; color: var(--gold);
    margin-bottom: 1.75rem;
}
.pr-eyebrow svg { width: 14px; height: 14px; }
.pr-hero-h1 {
    font-size: clamp(2.4rem, 6vw, 4rem);
    font-weight: 900; line-height: 1.08; letter-spacing: -0.03em;
    margin-bottom: 1.25rem;
}
.pr-hero-h1 span {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.pr-hero-lead {
    font-size: 1.1rem; color: var(--text-secondary); line-height: 1.72; margin-bottom: 2.25rem;
}
.pr-free-notice {
    display: inline-flex; align-items: center; gap: 0.6rem;
    background: rgba(5,150,105,0.10); border: 1px solid rgba(5,150,105,0.25);
    color: #6ee7b7; border-radius: 10px;
    padding: 0.65rem 1.25rem; font-size: 0.88rem; font-weight: 600;
}
.pr-free-notice svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ═══════════════════════════════════════
   CREDIT PACKS
═══════════════════════════════════════ */
.packs-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem; margin-bottom: 2rem;
}
@media (max-width: 860px) { .packs-grid { grid-template-columns: 1fr; max-width: 400px; margin-left: auto; margin-right: auto; } }

.pack-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px; padding: 2.25rem;
    position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
    cursor: default;
}
.pack-card:hover {
    border-color: rgba(212,175,55,0.35);
    box-shadow: 0 0 32px rgba(212,175,55,0.08), 0 16px 48px rgba(0,0,0,0.3);
    transform: translateY(-4px);
}
.pack-card.featured {
    border-color: rgba(212,175,55,0.45);
    background: linear-gradient(160deg, var(--bg-card), rgba(212,175,55,0.05));
    box-shadow: 0 0 48px rgba(212,175,55,0.12), 0 16px 48px rgba(0,0,0,0.3);
}
.pack-card.featured::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.pack-card.featured:hover { transform: translateY(-6px); box-shadow: 0 0 60px rgba(212,175,55,0.18), 0 20px 56px rgba(0,0,0,0.35); }

.pack-ribbon {
    position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0a0d14; padding: 0.3rem 1rem;
    border-radius: 100px; font-size: 0.7rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.07em; white-space: nowrap;
}
.pack-name {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: var(--text-muted); margin-bottom: 1.25rem;
}
.pack-credits {
    font-size: 3rem; font-weight: 900; color: var(--gold);
    line-height: 1; margin-bottom: 0.2rem;
}
.pack-credits-label { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem; }
.pack-price {
    font-size: 2rem; font-weight: 800; color: var(--text-primary);
    line-height: 1; margin-bottom: 0.3rem;
}
.pack-price span { font-size: 1.1rem; font-weight: 500; color: var(--text-secondary); }
.pack-per-credit { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 2rem; flex: 1; }
.pack-cta {
    display: flex; align-items: center; justify-content: center;
    width: 100%; padding: 0.9rem 1.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0a0d14; border-radius: 10px;
    font-weight: 700; font-size: 0.9rem; text-decoration: none;
    transition: opacity 0.2s, transform 0.2s;
    border: none; cursor: pointer;
}
.pack-cta:hover { opacity: 0.88; transform: translateY(-1px); color: #0a0d14; }
.pack-cta-ghost {
    display: flex; align-items: center; justify-content: center;
    width: 100%; padding: 0.9rem 1.5rem;
    background: transparent;
    color: var(--gold); border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; transition: background 0.2s, border-color 0.2s;
    cursor: pointer;
}
.pack-cta-ghost:hover { background: rgba(212,175,55,0.08); border-color: var(--gold); color: var(--gold); }
.pack-features { list-style: none; margin-top: 1.5rem; }
.pack-features li {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.86rem; color: var(--text-secondary); padding: 0.3rem 0;
}
.pack-features .chk { color: var(--gold); font-weight: 700; flex-shrink: 0; line-height: 1; }

/* ═══════════════════════════════════════
   FREE TIER CARD
═══════════════════════════════════════ */
.free-tier-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px; padding: 2.5rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 2rem; margin-top: 3rem; flex-wrap: wrap;
    position: relative; overflow: hidden;
}
.free-tier-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(5,150,105,0.4), transparent);
}
.free-tier-icon {
    width: 52px; height: 52px; border-radius: 12px; flex-shrink: 0;
    background: rgba(5,150,105,0.12); border: 1px solid rgba(5,150,105,0.25);
    display: flex; align-items: center; justify-content: center;
}
.free-tier-icon svg { width: 22px; height: 22px; color: #6ee7b7; }
.free-tier-body { flex: 1; }
.free-tier-body h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; }
.free-tier-body p { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.65; max-width: 520px; }
.btn-free {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0a0d14; padding: 0.85rem 1.75rem;
    border-radius: 10px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; white-space: nowrap;
    transition: opacity 0.2s, transform 0.2s;
}
.btn-free:hover { opacity: 0.88; transform: translateY(-1px); color: #0a0d14; }

/* ═══════════════════════════════════════
   HOW CREDITS WORK
═══════════════════════════════════════ */
.how-credits-flow {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 1px; background: var(--border);
    border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    margin-top: 2.5rem;
}
@media (max-width: 860px) { .how-credits-flow { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px)  { .how-credits-flow { grid-template-columns: 1fr; } }

.hcf-step {
    background: var(--bg-card); padding: 2rem 1.75rem;
    position: relative;
}
.hcf-num {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0a0d14; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 0.95rem;
    margin-bottom: 1rem;
    box-shadow: 0 0 18px rgba(212,175,55,0.3);
}
.hcf-step h4 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; }
.hcf-step p  { font-size: 0.84rem; color: var(--text-secondary); line-height: 1.65; }

/* ═══════════════════════════════════════
   MODEL TIERS TABLE
═══════════════════════════════════════ */
.tiers-table-wrap {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden; margin-top: 2.5rem;
}
.tiers-table {
    width: 100%; border-collapse: collapse;
}
.tiers-table thead tr {
    background: rgba(0,0,0,0.25); border-bottom: 1px solid var(--border);
}
.tiers-table th {
    padding: 1rem 1.25rem; text-align: left;
    font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted);
}
.tiers-table td {
    padding: 1.1rem 1.25rem; font-size: 0.9rem; color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}
.tiers-table tbody tr:last-child td { border-bottom: none; }
.tiers-table tbody tr:hover td { background: rgba(255,255,255,0.01); }
.tier-badge {
    display: inline-block; padding: 0.28rem 0.8rem;
    border-radius: 6px; font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.tier-badge.standard { background: rgba(99,102,241,0.14); color: #818cf8; }
.tier-badge.premium  { background: rgba(212,175,55,0.12); color: var(--gold); }

/* ═══════════════════════════════════════
   FAQ ACCORDION
═══════════════════════════════════════ */
.faq-list { max-width: 740px; margin: 2.5rem auto 0; }
.faq-item {
    border-bottom: 1px solid var(--border);
}
.faq-item:last-child { border-bottom: none; }
.faq-trigger {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 1.25rem 0; cursor: pointer;
    background: none; border: none; text-align: left;
    gap: 1rem;
}
[dir="rtl"] .faq-trigger { text-align: right; }
.faq-q {
    font-size: 0.95rem; font-weight: 600;
    color: var(--text-primary); line-height: 1.5;
    transition: color 0.2s;
}
.faq-trigger:hover .faq-q { color: var(--gold); }
.faq-icon {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.2);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: background 0.2s, transform 0.3s;
}
.faq-icon svg { width: 12px; height: 12px; color: var(--gold); transition: transform 0.3s; }
.faq-item.open .faq-icon { background: rgba(212,175,55,0.16); }
.faq-item.open .faq-icon svg { transform: rotate(45deg); }
.faq-body { display: none; padding: 0 0 1.25rem; }
.faq-body.open { display: block; }
.faq-body p {
    font-size: 0.9rem; color: var(--text-secondary); line-height: 1.75;
}
.faq-body a { color: var(--gold); }
.faq-body a:hover { text-decoration: underline; }

/* ═══════════════════════════════════════
   FINAL CTA BAND
═══════════════════════════════════════ */
.pr-cta-band {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    border-radius: 24px; padding: 4.5rem 2.5rem;
    text-align: center; position: relative; overflow: hidden;
    margin-bottom: 2rem;
}
.pr-cta-band::before {
    content: ''; position: absolute; inset: 0; border-radius: 24px;
    background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.12) 100%);
    pointer-events: none;
}
.pr-cta-band h2 {
    font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 900;
    color: #0a0d14; letter-spacing: -0.02em; line-height: 1.15;
    margin-bottom: 1rem; position: relative;
}
.pr-cta-band p {
    font-size: 1rem; color: rgba(10,13,20,0.7);
    margin-bottom: 2.25rem; line-height: 1.7; position: relative;
}
.pr-cta-btns { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; }
.btn-cta-dark {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: #0a0d14; color: var(--gold);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-dark:hover { background: #13161d; color: var(--gold); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
.btn-cta-outline {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: transparent; color: #0a0d14;
    border: 2px solid rgba(10,13,20,0.3);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-outline:hover { background: rgba(10,13,20,0.08); border-color: rgba(10,13,20,0.5); color: #0a0d14; }

/* ── RTL overrides ── */
[dir="rtl"] .pr-label::before { display: none; }
[dir="rtl"] .pr-label::after  { content: ''; display: block; width: 18px; height: 2px; background: var(--gold); border-radius: 2px; }
[dir="rtl"] .pack-features li { flex-direction: row-reverse; }
[dir="rtl"] .free-tier-card { flex-direction: row-reverse; }
[dir="rtl"] .tiers-table th,
[dir="rtl"] .tiers-table td { text-align: right; }
</style>
@endpush

@section('content')
<div class="pricing-page">

    <!-- ═══════════════════════════════════════
         HERO
    ═══════════════════════════════════════ -->
    <div class="pr-hero">
        <div class="pr-hero-inner">
            @if(app()->getLocale() === 'ar')
                <div class="pr-eyebrow" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    أسعار بسيطة وشفافة
                </div>
                <h1 class="pr-hero-h1" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    ادفع فقط مقابل <span>ما تستخدمه</span>
                </h1>
                <p class="pr-hero-lead" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    لا اشتراكات، لا رسوم مقاعد، لا فواتير مفاجئة. اشترِ رصيداً، استخدم الـ API، وادفع مقابل كل رمز ناتج فقط. الرصيد لا تنتهي صلاحيته أبداً.
                </p>
                <div class="pr-free-notice" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    ابدأ مجاناً — 1,000 رصيد عند التسجيل. لا حاجة لبطاقة ائتمان.
                </div>
            @else
                <div class="pr-eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Simple, Transparent Pricing
                </div>
                <h1 class="pr-hero-h1">
                    Pay Only for What You <span>Use</span>
                </h1>
                <p class="pr-hero-lead">
                    No subscriptions, no seat fees, no surprise bills. Buy credits, use the API, pay per output token only. Credits never expire.
                </p>
                <div class="pr-free-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Start free — 1,000 credits on registration. No credit card required.
                </div>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         CREDIT PACKS
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:4rem;">
        <div class="pr-center" style="margin-bottom:3rem;">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">باقات الرصيد</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">اختر باقتك</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">جميع الباقات تتيح الوصول إلى أكثر من 46 نموذجاً. الرصيد مجمّع ومشترك بين جميع مفاتيح API الخاصة بك.</p>
            @else
                <div class="pr-label fu" style="justify-content:center;">Credit Packs</div>
                <h2 class="pr-h2 fu d1">Choose Your Pack</h2>
                <p class="pr-sub fu d2" style="margin:0 auto;">All packs include access to all 46+ models. Credits are pooled and shared across all your API keys.</p>
            @endif
        </div>

        <div class="packs-grid">
            <!-- Pack 1: Starter -->
            <div class="pack-card fu">
                @if(app()->getLocale() === 'ar')
                    <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">الباقة الأساسية</div>
                    <div class="pack-credits">5,000</div>
                    <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                    <div class="pack-price">2 <span>د.ك</span></div>
                    <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.0004 د.ك لكل رصيد</div>
                    <a href="/register" class="pack-cta-ghost" style="font-family:'Tajawal',sans-serif;">ابدأ الآن</a>
                    <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        <li><span class="chk">✓</span> 5,000 رمز ناتج من API</li>
                        <li><span class="chk">✓</span> جميع النماذج 46+ مشمولة</li>
                        <li><span class="chk">✓</span> الرصيد لا تنتهي صلاحيته</li>
                    </ul>
                @else
                    <div class="pack-name">Starter</div>
                    <div class="pack-credits">5,000</div>
                    <div class="pack-credits-label">Credits</div>
                    <div class="pack-price">2 <span>KWD</span></div>
                    <div class="pack-per-credit">0.0004 KWD per credit</div>
                    <a href="/register" class="pack-cta-ghost">Get Started</a>
                    <ul class="pack-features">
                        <li><span class="chk">✓</span> 5,000 API output tokens</li>
                        <li><span class="chk">✓</span> All 46+ models included</li>
                        <li><span class="chk">✓</span> Credits never expire</li>
                    </ul>
                @endif
            </div>

            <!-- Pack 2: Featured -->
            <div class="pack-card featured fu d1">
                @if(app()->getLocale() === 'ar')
                    <div class="pack-ribbon" style="font-family:'Tajawal',sans-serif;">أفضل قيمة</div>
                    <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif; margin-top:0.75rem;">باقة النمو</div>
                    <div class="pack-credits">15,000</div>
                    <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                    <div class="pack-price">5 <span>د.ك</span></div>
                    <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.00033 د.ك لكل رصيد · أرخص 17%</div>
                    <a href="/register" class="pack-cta" style="font-family:'Tajawal',sans-serif;">ابدأ الآن</a>
                    <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        <li><span class="chk">✓</span> 15,000 رمز ناتج من API</li>
                        <li><span class="chk">✓</span> جميع النماذج 46+ مشمولة</li>
                        <li><span class="chk">✓</span> الرصيد لا تنتهي صلاحيته</li>
                        <li><span class="chk">✓</span> أرخص 17% لكل رصيد</li>
                    </ul>
                @else
                    <div class="pack-ribbon">Best Value</div>
                    <div class="pack-name" style="margin-top:0.75rem;">Growth</div>
                    <div class="pack-credits">15,000</div>
                    <div class="pack-credits-label">Credits</div>
                    <div class="pack-price">5 <span>KWD</span></div>
                    <div class="pack-per-credit">0.00033 KWD per credit · 17% cheaper</div>
                    <a href="/register" class="pack-cta">Get Started</a>
                    <ul class="pack-features">
                        <li><span class="chk">✓</span> 15,000 API output tokens</li>
                        <li><span class="chk">✓</span> All 46+ models included</li>
                        <li><span class="chk">✓</span> Credits never expire</li>
                        <li><span class="chk">✓</span> 17% cheaper per credit</li>
                    </ul>
                @endif
            </div>

            <!-- Pack 3: Pro -->
            <div class="pack-card fu d2">
                @if(app()->getLocale() === 'ar')
                    <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">الباقة الاحترافية</div>
                    <div class="pack-credits">50,000</div>
                    <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                    <div class="pack-price">15 <span>د.ك</span></div>
                    <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.0003 د.ك لكل رصيد · أرخص 25%</div>
                    <a href="/register" class="pack-cta-ghost" style="font-family:'Tajawal',sans-serif;">ابدأ الآن</a>
                    <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        <li><span class="chk">✓</span> 50,000 رمز ناتج من API</li>
                        <li><span class="chk">✓</span> جميع النماذج 46+ مشمولة</li>
                        <li><span class="chk">✓</span> الرصيد لا تنتهي صلاحيته</li>
                        <li><span class="chk">✓</span> أرخص 25% لكل رصيد</li>
                    </ul>
                @else
                    <div class="pack-name">Pro</div>
                    <div class="pack-credits">50,000</div>
                    <div class="pack-credits-label">Credits</div>
                    <div class="pack-price">15 <span>KWD</span></div>
                    <div class="pack-per-credit">0.0003 KWD per credit · 25% cheaper</div>
                    <a href="/register" class="pack-cta-ghost">Get Started</a>
                    <ul class="pack-features">
                        <li><span class="chk">✓</span> 50,000 API output tokens</li>
                        <li><span class="chk">✓</span> All 46+ models included</li>
                        <li><span class="chk">✓</span> Credits never expire</li>
                        <li><span class="chk">✓</span> 25% cheaper per credit</li>
                    </ul>
                @endif
            </div>
        </div>

        <!-- Free Tier -->
        <div class="free-tier-card fu">
            <div class="free-tier-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="free-tier-body">
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">الفئة المجانية — 1,000 رصيد عند التسجيل</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">كل حساب جديد يحصل على 1,000 رصيد مجاني — دون الحاجة لبطاقة ائتمان. يكفي لاختبار جميع نماذجنا وتجربة الـ API وتقييم ما إذا كان LLM Resayil يناسب احتياجاتك.</p>
                @else
                    <h3>Free Tier — 1,000 Credits on Signup</h3>
                    <p>Every new account receives 1,000 free credits — no credit card required. That is enough to test all models, try the API, and evaluate whether LLM Resayil fits your use case.</p>
                @endif
            </div>
            @if(app()->getLocale() === 'ar')
                <a href="/register" class="btn-free" style="font-family:'Tajawal',sans-serif;">
                    إنشاء حساب مجاني
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </a>
            @else
                <a href="/register" class="btn-free">
                    Create Free Account
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         HOW CREDITS WORK
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">نموذج الفوترة</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف يعمل نظام الرصيد</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">رصيد واحد = رمز ناتج واحد. لا رسوم على رموز الإدخال، لا رسوم مقاعد، لا تعقيد.</p>
            @else
                <div class="pr-label" style="justify-content:center;">Billing Model</div>
                <h2 class="pr-h2">How Credits Work</h2>
                <p class="pr-sub" style="margin:0 auto;">One credit = one output token. No input token charges, no seat fees, no complexity.</p>
            @endif
        </div>

        <div class="how-credits-flow fu d1">
            @if(app()->getLocale() === 'ar')
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">١</div>
                    <h4>اشترِ رصيداً</h4>
                    <p>اختر باقة الرصيد المناسبة. تُعالج المدفوعات عبر MyFatoorah بالدينار الكويتي (KWD).</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٢</div>
                    <h4>استدعِ الـ API</h4>
                    <p>استخدم مفتاح API الخاص بك مع أي SDK متوافق مع OpenAI أو عميل HTTP.</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٣</div>
                    <h4>خصم الرصيد</h4>
                    <p>بعد كل استجابة، يُحسب عدد الرموز الناتجة ويُخصم الرصيد المقابل تلقائياً.</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٤</div>
                    <h4>تتبّع الاستخدام</h4>
                    <p>تعرض لوحة التحكم الرصيد الفعلي، وسجل الاستخدام، وتفاصيل تكلفة كل طلب.</p>
                </div>
            @else
                <div class="hcf-step">
                    <div class="hcf-num">1</div>
                    <h4>Buy Credits</h4>
                    <p>Choose a credit pack. Payment processed via MyFatoorah in Kuwaiti Dinar (KWD).</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">2</div>
                    <h4>Call the API</h4>
                    <p>Use your API key with any OpenAI-compatible SDK or HTTP client. Same interface, lower cost.</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">3</div>
                    <h4>Credits Deducted</h4>
                    <p>After each response, output tokens are counted and credits deducted from your balance automatically.</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">4</div>
                    <h4>Track Usage</h4>
                    <p>Your dashboard shows real-time balance, usage history, and per-request cost breakdown.</p>
                </div>
            @endif
        </div>

        <div style="margin-top:1.5rem; text-align:center;">
            @if(app()->getLocale() === 'ar')
                <a href="/credits" style="color:var(--gold); font-size:0.88rem; font-weight:600; font-family:'Tajawal',sans-serif;" dir="rtl">اقرأ الدليل الكامل للرصيد &larr;</a>
            @else
                <a href="/credits" style="color:var(--gold); font-size:0.88rem; font-weight:600;">Read the full credits guide &rarr;</a>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         MODEL TIERS TABLE
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">فئات النماذج</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">معدلات الرصيد حسب النموذج</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">النماذج القياسية تكلّف رصيداً واحداً لكل رمز ناتج. نماذج الاستدلال المتقدمة تكلّف رصيدين.</p>
            @else
                <div class="pr-label" style="justify-content:center;">Model Tiers</div>
                <h2 class="pr-h2">Credit Rates by Model</h2>
                <p class="pr-sub" style="margin:0 auto;">Standard models cost 1 credit per output token. Premium reasoning models cost 2 credits.</p>
            @endif
        </div>

        <div class="tiers-table-wrap fu d1">
            <table class="tiers-table">
                <thead>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <th style="font-family:'Tajawal',sans-serif;">الفئة</th>
                            <th style="font-family:'Tajawal',sans-serif;">الرصيد / الرمز</th>
                            <th style="font-family:'Tajawal',sans-serif;">أمثلة على النماذج</th>
                            <th style="font-family:'Tajawal',sans-serif;">الأنسب لـ</th>
                        @else
                            <th>Tier</th>
                            <th>Credits / Token</th>
                            <th>Example Models</th>
                            <th>Best For</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="tier-badge standard">
                                @if(app()->getLocale() === 'ar') قياسي @else Standard @endif
                            </span>
                        </td>
                        @if(app()->getLocale() === 'ar')
                            <td style="font-weight:700; color:var(--text-primary); font-family:'Tajawal',sans-serif;">رصيد واحد</td>
                            <td>Llama, Qwen, Gemma, Mistral, Command R</td>
                            <td style="font-family:'Tajawal',sans-serif;">المحادثة، البرمجة، التلخيص</td>
                        @else
                            <td style="font-weight:700; color:var(--text-primary);">1 credit</td>
                            <td>Llama, Qwen, Gemma, Mistral, Command R</td>
                            <td>Chat, coding, summarization</td>
                        @endif
                    </tr>
                    <tr>
                        <td>
                            <span class="tier-badge premium">
                                @if(app()->getLocale() === 'ar') متقدم @else Premium @endif
                            </span>
                        </td>
                        @if(app()->getLocale() === 'ar')
                            <td style="font-weight:700; color:var(--text-primary); font-family:'Tajawal',sans-serif;">رصيدان</td>
                            <td>DeepSeek R1, DeepSeek V3, Qwen QwQ</td>
                            <td style="font-family:'Tajawal',sans-serif;">الاستدلال المعقد، التحليل العميق</td>
                        @else
                            <td style="font-weight:700; color:var(--text-primary);">2 credits</td>
                            <td>DeepSeek R1, DeepSeek V3, Qwen QwQ</td>
                            <td>Complex reasoning, deep analysis</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         FAQ ACCORDION
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">الأسئلة الشائعة</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">أسئلة شائعة</h2>
            @else
                <div class="pr-label" style="justify-content:center;">FAQ</div>
                <h2 class="pr-h2">Common Questions</h2>
            @endif
        </div>

        <div class="faq-list">
            @if(app()->getLocale() === 'ar')
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل تنتهي صلاحية الرصيد؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">لا. الرصيد لا تنتهي صلاحيته أبداً. اشترِ مرة واحدة واستخدمه متى تشاء — لا يوجد أي ضغط زمني.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل تُحسب رموز الإدخال؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">لا. يُحسب الرصيد على رموز الإخراج فقط. مطالباتك ونافذة السياق مجانية تماماً.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">ما العملة المستخدمة؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">جميع الأسعار بالدينار الكويتي (KWD). تُعالج المدفوعات بأمان عبر MyFatoorah.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل يمكنني استخدام مفاتيح API متعددة من نفس الرصيد؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">نعم. جميع مفاتيح API في حسابك تشترك في نفس رصيد الاعتمادات. أنشئ عدداً غير محدود من المفاتيح حسب حاجتك.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل الـ API متوافق مع OpenAI؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">نعم. يتبع الـ API تنسيق OpenAI. أضفه إلى كودك الحالي بتغيير رابط الـ base URL ومفتاح API فقط. راجع <a href="/docs">التوثيق</a> للمزيد من الأمثلة.</p></div>
                </div>
            @else
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Do credits expire?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>No. Credits never expire. Buy once, use whenever you need — no time pressure whatsoever.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Are input tokens charged?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>No. Only output tokens are charged. Your prompts and context window are completely free.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">What currency is used?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>All pricing is in Kuwaiti Dinar (KWD). Payments are processed securely via MyFatoorah.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Can I use multiple API keys from one balance?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>Yes. All API keys on your account share the same credit balance. Create as many keys as you need for different projects or applications.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Is this OpenAI compatible?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>Yes. The API follows the OpenAI format exactly. Drop into your existing code by changing the base URL and API key only. See <a href="/docs">the documentation</a> for examples.</p></div>
                </div>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         FINAL CTA BAND
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem; padding-bottom:4rem;">
        <div class="pr-cta-band fu">
            @if(app()->getLocale() === 'ar')
                <h2 dir="rtl" style="font-family:'Tajawal',sans-serif;">ابدأ البناء اليوم</h2>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">سجّل مجاناً، احصل على 1,000 رصيد فوراً، وأجرِ أول استدعاء API في أقل من 5 دقائق.</p>
                <div class="pr-cta-btns">
                    <a href="/register" class="btn-cta-dark" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        ابدأ مجاناً
                    </a>
                    <a href="/docs" class="btn-cta-outline" style="font-family:'Tajawal',sans-serif;">عرض توثيق الـ API</a>
                </div>
            @else
                <h2>Start Building Today</h2>
                <p>Register for free, get 1,000 credits instantly, and connect your first API call in under 5 minutes.</p>
                <div class="pr-cta-btns">
                    <a href="/register" class="btn-cta-dark">
                        Get Started Free
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <a href="/docs" class="btn-cta-outline">View API Docs</a>
                </div>
            @endif
        </div>
    </section>

</div>

<script>
(function () {
    'use strict';

    /* FAQ accordion */
    document.querySelectorAll('.faq-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var body = item.querySelector('.faq-body');
            var isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-item.open').forEach(function (el) {
                el.classList.remove('open');
                el.querySelector('.faq-body').classList.remove('open');
                el.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                item.classList.add('open');
                body.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* Scroll-reveal */
    var fus = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('on'); io.unobserve(en.target); }
            });
        }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
        fus.forEach(function (el) { io.observe(el); });
    } else {
        fus.forEach(function (el) { el.classList.add('on'); });
    }
})();
</script>
@endsection
