@extends('layouts.app')

@section('title', 'Top-Up Credits — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page — Top-Up Credits ── */
    .docs-page {
        background: var(--bg-secondary);
        padding: 3rem 2rem;
        min-height: 100vh;
    }

    .docs-content {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .docs-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .docs-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
    .docs-breadcrumb a:hover { color: var(--gold); }
    .docs-breadcrumb .bc-sep { color: var(--text-muted); opacity: 0.4; font-size: 0.8rem; }
    .docs-breadcrumb .bc-current { color: var(--gold); font-weight: 500; }

    /* Page Title */
    .docs-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: var(--text-primary);
    }

    .docs-title span { color: var(--gold); }

    .docs-intro {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 3rem;
        line-height: 1.75;
        max-width: 72ch;
    }

    /* Sections */
    .docs-section { margin-bottom: 3rem; }

    .docs-section h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }

    .docs-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        margin-top: 1.5rem;
        color: var(--text-primary);
    }

    .docs-section p { color: var(--text-secondary); line-height: 1.75; margin-bottom: 1rem; }

    .docs-section ul { list-style: none; padding-left: 0; margin-bottom: 1rem; }

    .docs-section li {
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 0.75rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .docs-section li:before {
        content: "▸";
        color: var(--gold);
        position: absolute;
        left: 0;
        font-size: 0.8rem;
        top: 0.3rem;
    }

    .docs-section strong { color: var(--text-primary); }

    /* ── Pack Grid ── */
    .pack-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .pack-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem 1.25rem;
        text-align: center;
        position: relative;
        transition: border-color 0.2s;
    }

    .pack-card:hover { border-color: rgba(212,175,55,0.4); }

    .pack-card.popular {
        border-color: var(--gold);
        background: linear-gradient(135deg, rgba(212,175,55,0.07) 0%, var(--bg-card) 100%);
    }

    .pack-popular-badge {
        position: absolute;
        top: -0.65rem;
        left: 50%;
        transform: translateX(-50%);
        background: var(--gold);
        color: #0f1115;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.2rem 0.7rem;
        border-radius: 999px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .pack-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.75rem;
    }

    .pack-price {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .pack-currency {
        font-size: 1rem;
        font-weight: 500;
        vertical-align: super;
        color: var(--text-secondary);
    }

    .pack-credits {
        font-size: 0.88rem;
        color: var(--gold);
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .pack-credits span {
        font-family: 'Monaco','Menlo',monospace;
    }

    /* ── Steps Flow ── */
    .steps-flow {
        position: relative;
        padding: 0.5rem 0;
        margin: 1.5rem 0;
    }

    .steps-flow::before {
        content: '';
        position: absolute;
        left: 1.35rem;
        top: 2rem;
        bottom: 2rem;
        width: 2px;
        background: linear-gradient(to bottom, var(--gold) 0%, rgba(212,175,55,0.2) 100%);
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        padding: 0.75rem 0;
        position: relative;
    }

    .step-num {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 50%;
        background: rgba(212,175,55,0.12);
        border: 2px solid var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--gold);
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        background-color: var(--bg-secondary);
    }

    .step-body { padding-top: 0.4rem; flex: 1; }

    .step-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .step-desc {
        font-size: 0.87rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* Code Blocks */
    .docs-code-wrap { position: relative; margin: 1.5rem 0; }

    .docs-code-block {
        background: #0d1017;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.5rem;
        padding-top: 2.75rem;
        overflow-x: auto;
        direction: ltr;
    }

    .docs-code-block code {
        display: block;
        font-size: 0.84rem;
        line-height: 1.65;
        color: #a0d468;
        font-family: 'Monaco','Menlo','Courier New',monospace;
        white-space: pre;
    }

    .docs-code-block code .hl-gold { color: var(--gold); font-weight: 600; }

    .docs-code-header {
        position: absolute;
        top: 0; left: 0; right: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.03);
        border-bottom: 1px solid var(--border);
        border-radius: 8px 8px 0 0;
    }

    .docs-code-lang {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-family: 'Monaco','Menlo',monospace;
    }

    .docs-copy-btn {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--text-muted);
        background: none;
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 0.2rem 0.6rem;
        cursor: pointer;
        transition: color 0.2s, border-color 0.2s;
        font-family: inherit;
    }

    .docs-copy-btn:hover { color: var(--gold); border-color: var(--gold); }

    .docs-copy-btn svg {
        width: 12px; height: 12px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    /* Info / Warning / Danger boxes */
    .docs-box {
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .docs-box-icon { flex-shrink: 0; width: 18px; height: 18px; margin-top: 0.15rem; }

    .docs-box-icon svg {
        width: 18px; height: 18px;
        fill: none; stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
    }

    .docs-box p { margin: 0; font-size: 0.92rem; line-height: 1.65; }

    .docs-box-tip    { background: rgba(212,175,55,0.08);  border-left: 4px solid var(--gold); }
    .docs-box-tip    .docs-box-icon svg { stroke: var(--gold); }
    .docs-box-warning{ background: rgba(245,158,11,0.1);   border-left: 4px solid #f59e0b; }
    .docs-box-warning .docs-box-icon svg { stroke: #f59e0b; }
    .docs-box-danger { background: rgba(220,38,38,0.08);   border-left: 4px solid #dc2626; }
    .docs-box-danger  .docs-box-icon svg { stroke: #dc2626; }
    .docs-box-note   { background: rgba(59,130,246,0.08);  border-left: 4px solid #3b82f6; }
    .docs-box-note   .docs-box-icon svg { stroke: #3b82f6; }

    /* Link */
    .docs-link { color: var(--gold); text-decoration: none; font-weight: 500; transition: opacity 0.2s; }
    .docs-link:hover { opacity: 0.8; }

    /* CTA Button */
    .docs-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.75rem 1.75rem;
        background: var(--gold);
        color: #0f1115;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        transition: opacity 0.2s;
        margin-top: 0.75rem;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .docs-cta-btn:hover { opacity: 0.88; }

    .docs-cta-btn svg {
        width: 16px; height: 16px;
        stroke: currentColor; fill: none;
        stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
    }

    /* Next Section CTA */
    .docs-next-section {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-section h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .docs-next-section p {
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        font-size: 0.92rem;
    }

    .docs-next-section a {
        display: inline-block;
        padding: 0.7rem 1.5rem;
        background: var(--gold);
        color: #0f1115;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        transition: opacity 0.2s;
        font-size: 0.9rem;
    }

    .docs-next-section a:hover { opacity: 0.88; }

    @media (max-width: 768px) {
        .docs-title { font-size: 1.75rem; }
        .docs-section h2 { font-size: 1.35rem; }
        .docs-section h3 { font-size: 1.1rem; }

        .pack-grid { grid-template-columns: 1fr; max-width: 320px; margin-left: auto; margin-right: auto; }

        .steps-flow::before { left: 1.25rem; }

        .docs-code-block {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-code-wrap {
            margin-left: -2rem;
            margin-right: -2rem;
        }

        .docs-code-header { border-radius: 0; }
    }
</style>
@endpush

@section('content')
<div class="docs-page">
    <div class="docs-content">

        <!-- Breadcrumb -->
        <nav class="docs-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('welcome') }}">
                @if(app()->getLocale() === 'ar') الرئيسية @else Home @endif
            </a>
            <span class="bc-sep">&#8250;</span>
            <a href="{{ route('docs.index') }}">
                @if(app()->getLocale() === 'ar') التوثيق @else Docs @endif
            </a>
            <span class="bc-sep">&#8250;</span>
            <span class="bc-current">
                @if(app()->getLocale() === 'ar') شحن الرصيد @else Top-Up Credits @endif
            </span>
        </nav>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                شحن <span>الرصيد</span>
            @else
                Top-Up <span>Credits</span>
            @endif
        </h1>
        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                الشحن الفوري هو وسيلة لشراء رصيد إضافي لمرة واحدة، دون الحاجة إلى اشتراك شهري.
                تعرّف على الحزم المتاحة، وطريقة الدفع، وكيف يُضاف الرصيد تلقائيًا إلى حسابك.
            </p>
        @else
            <p class="docs-intro">
                Top-ups are one-time credit purchases that let you add balance to your account whenever you need it,
                with no recurring subscription required. Learn about available packs, payment methods, and how
                credits are applied automatically after payment.
            </p>
        @endif

        <!-- Section 1: What Are Top-Ups? -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') ما هي حزم الشحن؟ @else What Are Top-Up Packs? @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    حزمة الشحن هي عملية شراء فورية لكمية محددة من الرصيد مقابل سعر ثابت. لا تنتهي صلاحية الرصيد المشترى
                    بمرور الوقت — يظل متاحًا حتى تستهلكه بالكامل. هذا النظام مناسب تمامًا للمطوّرين والشركات التي تفضّل
                    الدفع مسبقًا وتجنّب الرسوم الدورية.
                </p>
            @else
                <p>
                    A top-up pack is a one-time purchase of a fixed credit amount at a fixed price. Purchased credits
                    do not expire over time — they remain available until fully consumed. This model is ideal for
                    developers and businesses who prefer pre-paid billing without recurring charges.
                </p>
            @endif

            <!-- Pack Grid -->
            <div class="pack-grid">
                <div class="pack-card">
                    <div class="pack-name">Starter</div>
                    <div class="pack-price"><span class="pack-currency">$</span>5</div>
                    <div class="pack-credits"><span>5,000</span> @if(app()->getLocale() === 'ar') رصيدة @else credits @endif</div>
                </div>
                <div class="pack-card popular">
                    <div class="pack-popular-badge">@if(app()->getLocale() === 'ar') الأكثر شيوعاً @else Most Popular @endif</div>
                    <div class="pack-name">Pro</div>
                    <div class="pack-price"><span class="pack-currency">$</span>20</div>
                    <div class="pack-credits"><span>25,000</span> @if(app()->getLocale() === 'ar') رصيدة @else credits @endif</div>
                </div>
                <div class="pack-card">
                    <div class="pack-name">Business</div>
                    <div class="pack-price"><span class="pack-currency">$</span>50</div>
                    <div class="pack-credits"><span>70,000</span> @if(app()->getLocale() === 'ar') رصيدة @else credits @endif</div>
                </div>
            </div>
        </section>

        <!-- Section 2: Viewing Available Packs via API -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') استعراض الحزم المتاحة عبر API @else Viewing Available Packs via API @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>يمكنك استرداد قائمة الحزم المتاحة حاليًا مع أسعارها وكميات الرصيد عبر نقطة النهاية التالية:</p>
            @else
                <p>You can retrieve the list of currently available packs along with their prices and credit amounts via the following endpoint:</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">HTTP Request</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-packs-req">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-packs-req">GET https://llm.resayil.io/api/billing/topup-packs
Authorization: Bearer YOUR_API_KEY</code>
                </div>
            </div>

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON Response</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-packs-resp">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-packs-resp">{
  "packs": [
    {
      "id": "pack_starter",
      "name": "Starter Pack",
      "price": 5.00,
      "currency": "USD",
      "credits": 5000,
      "popular": false
    },
    {
      "id": "pack_pro",
      "name": "Pro Pack",
      "price": 20.00,
      "currency": "USD",
      "credits": 25000,
      "popular": true
    },
    {
      "id": "pack_business",
      "name": "Business Pack",
      "price": 50.00,
      "currency": "USD",
      "credits": 70000,
      "popular": false
    }
  ]
}</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    حقل <strong>credits</strong> يمثّل عدد وحدات الرصيد التي ستُضاف إلى حسابك فور إتمام الدفع.
                    حقل <strong>popular</strong> يشير إلى الحزمة الأكثر اختيارًا من المستخدمين.
                </p>
            @else
                <p>
                    The <strong>credits</strong> field represents the number of credit units added to your account upon
                    successful payment. The <strong>popular</strong> flag indicates the most commonly chosen pack.
                </p>
            @endif
        </section>

        <!-- Section 3: How to Purchase -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') كيفية الشراء @else How to Purchase @endif
            </h2>

            <!-- Warning box: purchase via web UI only -->
            <div class="docs-box docs-box-warning">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>تنبيه:</strong> لا تتوفر حاليًا نقطة نهاية API لإتمام الشراء مباشرةً — يجب إتمام عملية الدفع عبر واجهة الويب فقط.
                    @else
                        <strong>Important:</strong> There is currently no API endpoint for completing a purchase directly — payment must be initiated through the web UI only.
                    @endif
                </p>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>خطوات الشراء:</p>
            @else
                <p>Follow these steps to purchase a top-up pack:</p>
            @endif

            <!-- Numbered steps with connecting line -->
            <div class="steps-flow">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <div class="step-title">
                            @if(app()->getLocale() === 'ar') تسجيل الدخول @else Log in to your account @endif
                        </div>
                        <div class="step-desc">
                            @if(app()->getLocale() === 'ar')
                                سجّل الدخول إلى حسابك على <a href="https://llm.resayil.io" class="docs-link">llm.resayil.io</a>.
                            @else
                                Log in to your account at <a href="https://llm.resayil.io" class="docs-link">llm.resayil.io</a>.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <div class="step-title">
                            @if(app()->getLocale() === 'ar') انتقل إلى الفوترة @else Navigate to Billing @endif
                        </div>
                        <div class="step-desc">
                            @if(app()->getLocale() === 'ar')
                                انتقل إلى <strong>Billing &rarr; Plans</strong> من القائمة الجانبية.
                            @else
                                Navigate to <strong>Billing &rarr; Plans</strong> in the sidebar.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <div class="step-title">
                            @if(app()->getLocale() === 'ar') اختر الحزمة @else Select your pack @endif
                        </div>
                        <div class="step-desc">
                            @if(app()->getLocale() === 'ar')
                                اختر الحزمة المناسبة وانقر على <strong>Buy Now</strong>.
                            @else
                                Select your desired pack and click <strong>Buy Now</strong>.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-body">
                        <div class="step-title">
                            @if(app()->getLocale() === 'ar') أكمل الدفع @else Complete payment @endif
                        </div>
                        <div class="step-desc">
                            @if(app()->getLocale() === 'ar')
                                أكمل عملية الدفع عبر بوابة MyFatoorah الآمنة. يُضاف الرصيد تلقائيًا فور تأكيد الدفع.
                            @else
                                Complete the payment through the secure MyFatoorah gateway. Credits are added to your account automatically as soon as payment is confirmed.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <a href="https://llm.resayil.io/billing/plans" class="docs-cta-btn" target="_blank" rel="noopener">
                @if(app()->getLocale() === 'ar')
                    الانتقال إلى صفحة الفوترة &rarr;
                @else
                    Go to Billing &rarr;
                @endif
                <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </section>

        <!-- Section 4: Payment Methods -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') وسائل الدفع المقبولة @else Accepted Payment Methods @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    تتم المدفوعات عبر بوابة <strong>MyFatoorah</strong>، وهي بوابة دفع إلكتروني آمنة ومرخّصة. تشمل وسائل الدفع المدعومة:
                </p>
            @else
                <p>
                    Payments are processed through <strong>MyFatoorah</strong>, a certified and secure payment gateway.
                    Supported payment methods include:
                </p>
            @endif
            <ul>
                <li>
                    @if(app()->getLocale() === 'ar')
                        بطاقات الائتمان والخصم الدولية (Visa، Mastercard)
                    @else
                        International credit and debit cards (Visa, Mastercard)
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        STC Pay (المملكة العربية السعودية)
                    @else
                        STC Pay (Saudi Arabia)
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        وسائل دفع محلية إضافية بحسب بلدك (تظهر تلقائيًا عند الدفع)
                    @else
                        Additional local payment methods depending on your country (displayed automatically at checkout)
                    @endif
                </li>
            </ul>

            <div class="docs-box docs-box-tip">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>الأمان:</strong> لا يتم تخزين أي بيانات بطاقة على خوادم LLM Resayil. تتولى MyFatoorah معالجة جميع بيانات الدفع بشكل آمن وفق معايير PCI-DSS.
                    @else
                        <strong>Security:</strong> No card data is stored on LLM Resayil servers. MyFatoorah processes all payment data securely in accordance with PCI-DSS standards.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 5: How Credits Are Applied -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') كيف يُضاف الرصيد بعد الدفع؟ @else How Credits Are Applied After Payment @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يعتمد النظام على آلية <strong>Webhook</strong> من MyFatoorah لاستقبال تأكيدات الدفع الفوري. عند نجاح المعاملة:
                </p>
            @else
                <p>
                    The system relies on a <strong>webhook</strong> from MyFatoorah to receive instant payment confirmations.
                    Upon a successful transaction:
                </p>
            @endif
            <ul>
                <li>
                    @if(app()->getLocale() === 'ar')
                        يستقبل الخادم إشعار الدفع من MyFatoorah خلال ثوانٍ.
                    @else
                        The server receives the payment notification from MyFatoorah within seconds.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        يُضاف الرصيد المقابل للحزمة المشتراة إلى رصيدك الحالي فورًا.
                    @else
                        The credit amount for the purchased pack is immediately added to your existing balance.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        تُسجَّل المعاملة في سجل المشتريات ويمكن الاطلاع عليها عبر API أو لوحة التحكم.
                    @else
                        The transaction is recorded in your purchase history and viewable via API or dashboard.
                    @endif
                </li>
            </ul>

            <div class="docs-box docs-box-warning">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>تأخير في الإضافة؟</strong> في الحالات النادرة التي يتأخر فيها الإشعار، انتظر بضع دقائق ثم حدّث الصفحة. إذا استمر التأخر لأكثر من 15 دقيقة، <a href="{{ route('contact') }}" class="docs-link">تواصل مع الدعم</a> مع رقم المعاملة.
                    @else
                        <strong>Delayed credit?</strong> In the rare case of a delayed notification, wait a few minutes and refresh. If credits have not appeared after 15 minutes, <a href="{{ route('contact') }}" class="docs-link">contact support</a> with your transaction reference number.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 6: View Purchase History -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') استعراض سجل المشتريات عبر API @else Viewing Purchase History via API @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>يمكنك الاطلاع على سجل عمليات الشحن السابقة عبر نقطة النهاية التالية (20 سجلًا في كل صفحة):</p>
            @else
                <p>You can retrieve your past top-up transactions via the following endpoint (20 records per page):</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">HTTP Request</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-history-req">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-history-req">GET https://llm.resayil.io/api/billing/topup-history
Authorization: Bearer YOUR_API_KEY</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>للتنقل بين الصفحات، أضف المعامل <strong>?page=2</strong> إلى الرابط.</p>
            @else
                <p>To paginate, append <strong>?page=2</strong> (and so on) to the URL.</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON Response</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-history-resp">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-history-resp">{
  "data": [
    {
      "id": "txn_abc123",
      "pack_name": "Pro Pack",
      "credits": 25000,
      "amount_paid": 20.00,
      "currency": "USD",
      "status": "completed",
      "created_at": "2025-11-14T09:31:00Z"
    },
    {
      "id": "txn_xyz789",
      "pack_name": "Starter Pack",
      "credits": 5000,
      "amount_paid": 5.00,
      "currency": "USD",
      "status": "completed",
      "created_at": "2025-10-02T14:17:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 2
  }
}</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    حقل <strong>status</strong> يمكن أن يكون <strong>completed</strong> (مكتمل) أو <strong>pending</strong> (قيد الانتظار) أو <strong>failed</strong> (فشل).
                    استعلم عن المعاملات بحالة <em>pending</em> إذا كنت تتوقع رصيدًا لم يظهر بعد.
                </p>
            @else
                <p>
                    The <strong>status</strong> field may be <strong>completed</strong>, <strong>pending</strong>, or <strong>failed</strong>.
                    Query transactions with a <em>pending</em> status if you are expecting credits that have not yet appeared.
                </p>
            @endif
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>هل تريد فهم نظام الرصيد بالكامل؟</h3>
                <p>اطّلع على الشرح الشامل لنظام الرصيد ومعاملات الضرب حسب النموذج.</p>
                <a href="{{ route('docs.credits') }}">الانتقال إلى نظام الرصيد &rarr;</a>
            @else
                <h3>Want to understand the full credits system?</h3>
                <p>Read the comprehensive guide to how credits work and per-model multipliers.</p>
                <a href="{{ route('docs.credits') }}">Go to Credits System &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Docs', 'url' => route('docs.index')],
    ['name' => 'Top-Up Credits', 'url' => route('docs.topup')]
  ];

  $schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_map(function($item, $key) {
      return [
        '@type' => 'ListItem',
        'position' => $key + 1,
        'name' => $item['name'],
        'item' => $item['url']
      ];
    }, $breadcrumbs, array_keys($breadcrumbs))
  ];
@endphp

<script type="application/ld+json">
  @json($schema)
</script>

<script>
function docsCopy(btn) {
    const targetId = btn.getAttribute('data-target');
    const el = document.getElementById(targetId);
    if (!el) return;
    const text = el.innerText || el.textContent;
    navigator.clipboard.writeText(text).then(function() {
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" style="width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round"><polyline points="20 6 9 17 4 12"/></svg> Copied';
        btn.style.color = 'var(--gold)';
        btn.style.borderColor = 'var(--gold)';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    });
}
</script>

@endsection
