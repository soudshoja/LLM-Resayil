@extends('layouts.app')

@section('title', $pageTitle ?? 'Pricing Plans — LLM Resayil')

@push('styles')
<style>
    /* ── Pricing Page Styles ── */
    .pricing-wrap {
        background: var(--bg-secondary);
    }

    /* Hero */
    .pricing-hero {
        padding: 4rem 2rem 3rem;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .pricing-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.3);
        border-radius: 2rem;
        color: var(--gold);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 1.5rem;
    }

    .pricing-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.25rem;
    }

    .pricing-hero h1 span {
        color: var(--gold);
    }

    .pricing-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 2.5rem;
    }

    @media (max-width: 600px) {
        .pricing-hero h1 { font-size: 2rem; }
    }

    /* Free tier notice */
    .free-notice {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(5,150,105,0.1);
        border: 1px solid rgba(5,150,105,0.3);
        color: #6ee7b7;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 3rem;
    }

    /* Plans section */
    .pricing-section {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
    }

    .pricing-section-label {
        color: var(--gold);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.75rem;
        text-align: center;
    }

    .pricing-section-title {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 0.75rem;
    }

    .pricing-section-subtitle {
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 2.5rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Top-up cards */
    .topup-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 800px) {
        .topup-grid { grid-template-columns: 1fr; max-width: 380px; margin-left: auto; margin-right: auto; }
    }

    .topup-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }

    .topup-card:hover {
        border-color: rgba(212,175,55,0.4);
        box-shadow: 0 8px 28px rgba(212,175,55,0.1);
        transform: translateY(-3px);
    }

    .topup-card.featured {
        border-color: rgba(212,175,55,0.45);
        background: linear-gradient(160deg, var(--bg-card), rgba(212,175,55,0.04));
    }

    .topup-ribbon {
        position: absolute;
        top: -13px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--gold), #eac558);
        color: #0a0d14;
        padding: 0.35rem 1rem;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
    }

    .topup-credits-amount {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--gold);
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .topup-credits-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 1.75rem;
    }

    .topup-price {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
    }

    .topup-price span {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .topup-per-credit {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 1.75rem;
    }

    .topup-cta {
        display: block;
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--gold), #eac558);
        color: #0a0d14;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        text-align: center;
        transition: opacity 0.2s, transform 0.2s;
    }

    .topup-cta:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: #0a0d14;
    }

    .topup-features {
        list-style: none;
        margin-top: 1.5rem;
        text-align: left;
    }

    .topup-features li {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        padding: 0.35rem 0;
    }

    .topup-features .check {
        color: var(--gold);
        font-weight: 700;
        flex-shrink: 0;
    }

    /* Free tier section */
    .free-tier-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .free-tier-left h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .free-tier-left p {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        max-width: 500px;
    }

    /* How credits work */
    .how-credits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .how-credits-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .how-credits-item .icon {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .how-credits-item h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .how-credits-item p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    /* FAQ section */
    .faq-section {
        max-width: 750px;
        margin: 3rem auto 0;
        padding: 0 2rem;
    }

    .faq-item {
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 0;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-q {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .faq-a {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.7;
    }

    .faq-a a {
        color: var(--gold);
    }

    .faq-a a:hover {
        text-decoration: underline;
    }

    /* CTA section */
    .pricing-cta-section {
        text-align: center;
        padding: 3rem 2rem 4rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .pricing-cta-section h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .pricing-cta-section p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .pricing-cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-outline-gold {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 1.5rem;
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 8px;
        color: var(--gold);
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-outline-gold:hover {
        background: rgba(212,175,55,0.08);
        border-color: var(--gold);
        color: var(--gold);
    }
</style>
@endpush

@section('content')
<div class="pricing-wrap">

    <!-- Hero -->
    <div class="pricing-hero">
        @if(app()->getLocale() === 'ar')
            <div class="pricing-badge" dir="rtl" style="font-family: 'Tajawal', sans-serif;">أسعار بسيطة وشفافة</div>
            <h1 dir="rtl" style="font-family: 'Tajawal', sans-serif;">ادفع فقط مقابل <span>ما تستخدمه</span></h1>
            <p class="pricing-hero-lead" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                لا اشتراكات، لا رسوم مقاعد، لا فواتير مفاجئة. اشترِ رصيداً، استخدم الـ API، وادفع مقابل كل رمز ناتج فقط.
                الرصيد لا تنتهي صلاحيته.
            </p>
            <div class="free-notice" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                ابدأ مجاناً — 1,000 رصيد عند التسجيل. لا حاجة لبطاقة ائتمان.
            </div>
        @else
            <div class="pricing-badge">Simple Pricing</div>
            <h1>Pay Only for What You <span>Use</span></h1>
            <p class="pricing-hero-lead">
                No subscriptions, no seat fees, no surprise bills. Buy credits, use the API, pay per output token.
                Credits never expire.
            </p>
            <div class="free-notice">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Start free — 1,000 credits on registration. No credit card required.
            </div>
        @endif
    </div>

    <!-- Top-Up Packs -->
    <section class="pricing-section">
        @if(app()->getLocale() === 'ar')
            <div class="pricing-section-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">باقات الرصيد</div>
            <h2 class="pricing-section-title" dir="rtl" style="font-family: 'Tajawal', sans-serif;">اختر باقتك</h2>
            <p class="pricing-section-subtitle" dir="rtl" style="font-family: 'Tajawal', sans-serif;">جميع الباقات تتيح الوصول إلى أكثر من 45 نموذجاً. الرصيد مجمّع ومشترك بين جميع مفاتيح API الخاصة بك.</p>
        @else
            <div class="pricing-section-label">Credit Packs</div>
            <h2 class="pricing-section-title">Choose Your Pack</h2>
            <p class="pricing-section-subtitle">All packs include access to all 45+ models. Credits are pooled and shared across all your API keys.</p>
        @endif

        <div class="topup-grid">
            <!-- Pack 1 -->
            <div class="topup-card">
                <div class="topup-credits-amount">5,000</div>
                @if(app()->getLocale() === 'ar')
                    <div class="topup-credits-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">رصيد</div>
                    <div class="topup-price">2 <span>KWD</span></div>
                    <div class="topup-per-credit" dir="rtl" style="font-family: 'Tajawal', sans-serif;">0.0004 د.ك لكل رصيد</div>
                    <a href="/register" class="topup-cta" style="font-family: 'Tajawal', sans-serif;">ابدأ الآن</a>
                    <ul class="topup-features" dir="rtl" style="font-family: 'Tajawal', sans-serif; text-align: right;">
                        <li><span class="check">✓</span> 5,000 رمز ناتج من API</li>
                        <li><span class="check">✓</span> جميع النماذج 45+ مشمولة</li>
                        <li><span class="check">✓</span> الرصيد لا تنتهي صلاحيته</li>
                    </ul>
                @else
                    <div class="topup-credits-label">Credits</div>
                    <div class="topup-price">2 <span>KWD</span></div>
                    <div class="topup-per-credit">0.0004 KWD per credit</div>
                    <a href="/register" class="topup-cta">Get Started</a>
                    <ul class="topup-features">
                        <li><span class="check">✓</span> 5,000 API output tokens</li>
                        <li><span class="check">✓</span> All 45+ models included</li>
                        <li><span class="check">✓</span> Credits never expire</li>
                    </ul>
                @endif
            </div>

            <!-- Pack 2 (Featured) -->
            <div class="topup-card featured">
                @if(app()->getLocale() === 'ar')
                    <div class="topup-ribbon" style="font-family: 'Tajawal', sans-serif;">أفضل قيمة</div>
                    <div class="topup-credits-amount">15,000</div>
                    <div class="topup-credits-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">رصيد</div>
                    <div class="topup-price">5 <span>KWD</span></div>
                    <div class="topup-per-credit" dir="rtl" style="font-family: 'Tajawal', sans-serif;">0.00033 د.ك لكل رصيد</div>
                    <a href="/register" class="topup-cta" style="font-family: 'Tajawal', sans-serif;">ابدأ الآن</a>
                    <ul class="topup-features" dir="rtl" style="font-family: 'Tajawal', sans-serif; text-align: right;">
                        <li><span class="check">✓</span> 15,000 رمز ناتج من API</li>
                        <li><span class="check">✓</span> جميع النماذج 45+ مشمولة</li>
                        <li><span class="check">✓</span> الرصيد لا تنتهي صلاحيته</li>
                        <li><span class="check">✓</span> أرخص بنسبة 17% لكل رصيد</li>
                    </ul>
                @else
                    <div class="topup-ribbon">Best Value</div>
                    <div class="topup-credits-amount">15,000</div>
                    <div class="topup-credits-label">Credits</div>
                    <div class="topup-price">5 <span>KWD</span></div>
                    <div class="topup-per-credit">0.00033 KWD per credit</div>
                    <a href="/register" class="topup-cta">Get Started</a>
                    <ul class="topup-features">
                        <li><span class="check">✓</span> 15,000 API output tokens</li>
                        <li><span class="check">✓</span> All 45+ models included</li>
                        <li><span class="check">✓</span> Credits never expire</li>
                        <li><span class="check">✓</span> 17% cheaper per credit</li>
                    </ul>
                @endif
            </div>

            <!-- Pack 3 -->
            <div class="topup-card">
                <div class="topup-credits-amount">50,000</div>
                @if(app()->getLocale() === 'ar')
                    <div class="topup-credits-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">رصيد</div>
                    <div class="topup-price">15 <span>KWD</span></div>
                    <div class="topup-per-credit" dir="rtl" style="font-family: 'Tajawal', sans-serif;">0.0003 د.ك لكل رصيد</div>
                    <a href="/register" class="topup-cta" style="font-family: 'Tajawal', sans-serif;">ابدأ الآن</a>
                    <ul class="topup-features" dir="rtl" style="font-family: 'Tajawal', sans-serif; text-align: right;">
                        <li><span class="check">✓</span> 50,000 رمز ناتج من API</li>
                        <li><span class="check">✓</span> جميع النماذج 45+ مشمولة</li>
                        <li><span class="check">✓</span> الرصيد لا تنتهي صلاحيته</li>
                        <li><span class="check">✓</span> أرخص بنسبة 25% لكل رصيد</li>
                    </ul>
                @else
                    <div class="topup-credits-label">Credits</div>
                    <div class="topup-price">15 <span>KWD</span></div>
                    <div class="topup-per-credit">0.0003 KWD per credit</div>
                    <a href="/register" class="topup-cta">Get Started</a>
                    <ul class="topup-features">
                        <li><span class="check">✓</span> 50,000 API output tokens</li>
                        <li><span class="check">✓</span> All 45+ models included</li>
                        <li><span class="check">✓</span> Credits never expire</li>
                        <li><span class="check">✓</span> 25% cheaper per credit</li>
                    </ul>
                @endif
            </div>
        </div>

        <!-- Free tier -->
        <div class="free-tier-card">
            @if(app()->getLocale() === 'ar')
                <div class="free-tier-left">
                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">الفئة المجانية — 1,000 رصيد عند التسجيل</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">كل حساب جديد يحصل على 1,000 رصيد مجاني — دون الحاجة لبطاقة ائتمان. يكفي ذلك لاختبار جميع نماذجنا وتجربة الـ API وتقييم ما إذا كان LLM Resayil يناسب احتياجاتك.</p>
                </div>
                <a href="/register" class="btn btn-gold" style="font-family: 'Tajawal', sans-serif;">إنشاء حساب مجاني</a>
            @else
                <div class="free-tier-left">
                    <h3>Free Tier — 1,000 Credits on Signup</h3>
                    <p>Every new account receives 1,000 free credits — no credit card required. That's enough to test all our models, try the API, and evaluate whether LLM Resayil fits your use case.</p>
                </div>
                <a href="/register" class="btn btn-gold">Create Free Account</a>
            @endif
        </div>
    </section>

    <!-- How Credits Work -->
    <section class="pricing-section" style="padding-top: 2rem;">
        @if(app()->getLocale() === 'ar')
            <div class="pricing-section-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">نموذج الفوترة</div>
            <h2 class="pricing-section-title" dir="rtl" style="font-family: 'Tajawal', sans-serif;">كيف يعمل نظام الرصيد</h2>
            <p class="pricing-section-subtitle" dir="rtl" style="font-family: 'Tajawal', sans-serif;">رصيد واحد = رمز ناتج واحد. لا رسوم على رموز الإدخال، لا رسوم مقاعد، لا تعقيد.</p>
        @else
            <div class="pricing-section-label">Billing Model</div>
            <h2 class="pricing-section-title">How Credits Work</h2>
            <p class="pricing-section-subtitle">One credit = one output token. No input token charges, no seat fees, no complexity.</p>
        @endif

        <div class="how-credits-grid">
            @if(app()->getLocale() === 'ar')
                <div class="how-credits-item" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    <div class="icon">1</div>
                    <h4>اشترِ رصيداً</h4>
                    <p>اختر باقة الرصيد المناسبة. تُعالج المدفوعات عبر MyFatoorah بالدينار الكويتي (KWD).</p>
                </div>
                <div class="how-credits-item" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    <div class="icon">2</div>
                    <h4>استدعِ الـ API</h4>
                    <p>استخدم مفتاح API الخاص بك مع أي SDK متوافق مع OpenAI أو عميل HTTP. نفس الواجهة، تكلفة أقل.</p>
                </div>
                <div class="how-credits-item" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    <div class="icon">3</div>
                    <h4>خصم الرصيد</h4>
                    <p>بعد كل استجابة، يُحسب عدد الرموز الناتجة ويُخصم الرصيد المقابل من رصيدك.</p>
                </div>
                <div class="how-credits-item" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    <div class="icon">4</div>
                    <h4>تتبّع الاستخدام</h4>
                    <p>تعرض لوحة التحكم الرصيد الفعلي، وسجل الاستخدام، وتفاصيل تكلفة كل طلب.</p>
                </div>
            @else
                <div class="how-credits-item">
                    <div class="icon">1</div>
                    <h4>Buy Credits</h4>
                    <p>Choose a credit pack. Payment is processed via MyFatoorah in Kuwaiti Dinar (KWD).</p>
                </div>
                <div class="how-credits-item">
                    <div class="icon">2</div>
                    <h4>Call the API</h4>
                    <p>Use your API key with any OpenAI-compatible SDK or HTTP client. Same interface, lower cost.</p>
                </div>
                <div class="how-credits-item">
                    <div class="icon">3</div>
                    <h4>Credits Deducted</h4>
                    <p>After each response, output tokens are counted and credits are deducted from your balance.</p>
                </div>
                <div class="how-credits-item">
                    <div class="icon">4</div>
                    <h4>Track Usage</h4>
                    <p>Your dashboard shows real-time balance, usage history, and per-request cost breakdown.</p>
                </div>
            @endif
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            @if(app()->getLocale() === 'ar')
                <a href="/credits" style="color: var(--gold); font-size: 0.9rem; font-weight: 600; font-family: 'Tajawal', sans-serif;" dir="rtl">اقرأ الدليل الكامل للرصيد &larr;</a>
            @else
                <a href="/credits" style="color: var(--gold); font-size: 0.9rem; font-weight: 600;">Read the full credits guide &rarr;</a>
            @endif
        </div>
    </section>

    <!-- Model Tiers -->
    <section class="pricing-section" style="padding-top: 1rem;">
        @if(app()->getLocale() === 'ar')
            <div class="pricing-section-label" dir="rtl" style="font-family: 'Tajawal', sans-serif;">فئات النماذج</div>
            <h2 class="pricing-section-title" dir="rtl" style="font-family: 'Tajawal', sans-serif;">معدلات الرصيد حسب النموذج</h2>
            <p class="pricing-section-subtitle" dir="rtl" style="font-family: 'Tajawal', sans-serif;">النماذج القياسية تكلّف رصيداً واحداً لكل رمز ناتج. نماذج الاستدلال المتقدمة تكلّف رصيدين.</p>
        @else
            <div class="pricing-section-label">Model Tiers</div>
            <h2 class="pricing-section-title">Credit Rates by Model</h2>
            <p class="pricing-section-subtitle">Standard models cost 1 credit per output token. Premium reasoning models cost 2.</p>
        @endif

        <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.2); border-bottom: 1px solid var(--border);">
                        @if(app()->getLocale() === 'ar')
                            <th style="padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-family: 'Tajawal', sans-serif;">الفئة</th>
                            <th style="padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-family: 'Tajawal', sans-serif;">الرصيد / الرمز</th>
                            <th style="padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-family: 'Tajawal', sans-serif;">أمثلة على النماذج</th>
                            <th style="padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-family: 'Tajawal', sans-serif;">الأنسب لـ</th>
                        @else
                            <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Tier</th>
                            <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Credits / Token</th>
                            <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Example Models</th>
                            <th style="padding: 1rem 1.25rem; text-align: left; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Best For</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1.25rem; font-size: 0.9rem;">
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: rgba(99,102,241,0.15); color: #818cf8; border-radius: 4px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                @if(app()->getLocale() === 'ar')قياسي@else Standard@endif
                            </span>
                        </td>
                        @if(app()->getLocale() === 'ar')
                            <td style="padding: 1.25rem; font-weight: 700; font-size: 0.95rem; font-family: 'Tajawal', sans-serif; text-align: right;">رصيد واحد</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem; text-align: right;">Llama, Qwen, Gemma, Mistral</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem; font-family: 'Tajawal', sans-serif; text-align: right;">المحادثة، البرمجة، التلخيص</td>
                        @else
                            <td style="padding: 1.25rem; font-weight: 700; font-size: 0.95rem;">1 credit</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem;">Llama, Qwen, Gemma, Mistral</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem;">Chat, coding, summarization</td>
                        @endif
                    </tr>
                    <tr>
                        <td style="padding: 1.25rem; font-size: 0.9rem;">
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: rgba(212,175,55,0.12); color: var(--gold); border-radius: 4px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                @if(app()->getLocale() === 'ar')متقدم@else Premium@endif
                            </span>
                        </td>
                        @if(app()->getLocale() === 'ar')
                            <td style="padding: 1.25rem; font-weight: 700; font-size: 0.95rem; font-family: 'Tajawal', sans-serif; text-align: right;">رصيدان</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem; text-align: right;">DeepSeek, نماذج الاستدلال الكبيرة</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem; font-family: 'Tajawal', sans-serif; text-align: right;">الاستدلال المعقد، التحليل</td>
                        @else
                            <td style="padding: 1.25rem; font-weight: 700; font-size: 0.95rem;">2 credits</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem;">DeepSeek, large reasoning models</td>
                            <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.9rem;">Complex reasoning, analysis</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- FAQ -->
    <div class="faq-section">
        @if(app()->getLocale() === 'ar')
            <div class="pricing-section-label" style="text-align: center; font-family: 'Tajawal', sans-serif;" dir="rtl">الأسئلة الشائعة</div>
            <h2 class="pricing-section-title" style="text-align: center; font-size: 1.75rem; font-family: 'Tajawal', sans-serif;" dir="rtl">أسئلة شائعة</h2>

            <div class="faq-item" dir="rtl">
                <div class="faq-q" style="font-family: 'Tajawal', sans-serif;">هل تنتهي صلاحية الرصيد؟</div>
                <div class="faq-a" style="font-family: 'Tajawal', sans-serif;">لا. الرصيد لا تنتهي صلاحيته أبداً. اشترِ مرة واحدة واستخدمه متى تشاء.</div>
            </div>
            <div class="faq-item" dir="rtl">
                <div class="faq-q" style="font-family: 'Tajawal', sans-serif;">هل تُحسب رموز الإدخال؟</div>
                <div class="faq-a" style="font-family: 'Tajawal', sans-serif;">لا. يُحسب الرصيد على رموز الإخراج فقط. مطالباتك ونافذة السياق مجانية تماماً.</div>
            </div>
            <div class="faq-item" dir="rtl">
                <div class="faq-q" style="font-family: 'Tajawal', sans-serif;">ما العملة المستخدمة؟</div>
                <div class="faq-a" style="font-family: 'Tajawal', sans-serif;">جميع الأسعار بالدينار الكويتي (KWD). تُعالج المدفوعات بأمان عبر MyFatoorah.</div>
            </div>
            <div class="faq-item" dir="rtl">
                <div class="faq-q" style="font-family: 'Tajawal', sans-serif;">هل يمكنني استخدام مفاتيح API متعددة من نفس الرصيد؟</div>
                <div class="faq-a" style="font-family: 'Tajawal', sans-serif;">نعم. جميع مفاتيح API في حسابك تشترك في نفس رصيد الاعتمادات. أنشئ عدداً غير محدود من المفاتيح حسب حاجتك.</div>
            </div>
            <div class="faq-item" dir="rtl">
                <div class="faq-q" style="font-family: 'Tajawal', sans-serif;">هل الـ API متوافق مع OpenAI؟</div>
                <div class="faq-a" style="font-family: 'Tajawal', sans-serif;">نعم. يتبع الـ API تنسيق OpenAI. أضفه إلى كودك الحالي بتغيير رابط الـ base URL ومفتاح API فقط. راجع <a href="/docs">التوثيق</a> للمزيد من الأمثلة.</div>
            </div>
        @else
            <div class="pricing-section-label" style="text-align: center;">FAQ</div>
            <h2 class="pricing-section-title" style="text-align: center; font-size: 1.75rem;">Common Questions</h2>

            <div class="faq-item">
                <div class="faq-q">Do credits expire?</div>
                <div class="faq-a">No. Credits never expire. Buy once, use whenever you need.</div>
            </div>
            <div class="faq-item">
                <div class="faq-q">Are input tokens charged?</div>
                <div class="faq-a">No. Only output tokens are charged. Your prompts and context window are free.</div>
            </div>
            <div class="faq-item">
                <div class="faq-q">What currency is used?</div>
                <div class="faq-a">All pricing is in Kuwaiti Dinar (KWD). Payments are processed securely via MyFatoorah.</div>
            </div>
            <div class="faq-item">
                <div class="faq-q">Can I use multiple API keys from one balance?</div>
                <div class="faq-a">Yes. All API keys on your account share the same credit balance. Create as many keys as you need.</div>
            </div>
            <div class="faq-item">
                <div class="faq-q">Is this OpenAI compatible?</div>
                <div class="faq-a">Yes. The API follows the OpenAI format. Drop in your existing code by changing the base URL and API key. See <a href="/docs">the documentation</a> for examples.</div>
            </div>
        @endif
    </div>

    <!-- CTA -->
    <div class="pricing-cta-section">
        @if(app()->getLocale() === 'ar')
            <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">ابدأ البناء اليوم</h2>
            <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">سجّل مجاناً، احصل على 1,000 رصيد فوراً، وأجرِ أول استدعاء API في أقل من 5 دقائق.</p>
            <div class="pricing-cta-buttons">
                <a href="/register" class="btn btn-gold" style="font-family: 'Tajawal', sans-serif;">ابدأ مجاناً</a>
                <a href="/docs" class="btn-outline-gold" style="font-family: 'Tajawal', sans-serif;">عرض توثيق الـ API</a>
            </div>
        @else
            <h2>Start Building Today</h2>
            <p>Register for free, get 1,000 credits instantly, and connect your first API call in under 5 minutes.</p>
            <div class="pricing-cta-buttons">
                <a href="/register" class="btn btn-gold">Get Started Free</a>
                <a href="/docs" class="btn-outline-gold">View API Docs</a>
            </div>
        @endif
    </div>

</div>
@endsection
