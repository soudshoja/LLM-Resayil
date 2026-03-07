@extends('layouts.app')

@section('title', 'Credits System — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page — Credits System ── */
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

    /* ── Hero Stat ── */
    .credits-hero {
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.03) 100%);
        border: 1px solid rgba(212,175,55,0.35);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        text-align: center;
        margin: 1.5rem 0 2rem;
    }

    .credits-hero-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.75rem;
    }

    .credits-hero-number {
        font-size: 4.5rem;
        font-weight: 900;
        color: var(--gold);
        line-height: 1;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }

    .credits-hero-unit {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        margin-left: 0.25rem;
    }

    .credits-hero-sub {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 0.75rem;
    }

    .credits-hero-badge {
        display: inline-block;
        background: rgba(34,197,94,0.15);
        border: 1px solid rgba(34,197,94,0.3);
        color: #22c55e;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.2rem 0.75rem;
        border-radius: 999px;
        margin-top: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ── Formula Block ── */
    .formula-block {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-left: 4px solid var(--gold);
        border-radius: 8px;
        padding: 1.5rem 1.75rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        font-family: 'Monaco','Menlo','Courier New',monospace;
    }

    .formula-part {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .formula-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
    }

    .formula-desc {
        font-size: 0.68rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-family: inherit;
        white-space: nowrap;
    }

    .formula-op { font-size: 1.3rem; font-weight: 300; color: var(--text-muted); padding: 0 0.2rem; align-self: center; }
    .formula-eq { font-size: 1.3rem; font-weight: 700; color: var(--gold); padding: 0 0.2rem; align-self: center; }
    .formula-result .formula-value { color: var(--gold); font-size: 1.2rem; }

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
    .docs-box-warning{ background: rgba(245,158,11,0.08);  border-left: 4px solid #f59e0b; }
    .docs-box-warning .docs-box-icon svg { stroke: #f59e0b; }
    .docs-box-danger { background: rgba(220,38,38,0.08);   border-left: 4px solid #dc2626; }
    .docs-box-danger  .docs-box-icon svg { stroke: #dc2626; }
    .docs-box-note   { background: rgba(59,130,246,0.08);  border-left: 4px solid #3b82f6; }
    .docs-box-note   .docs-box-icon svg { stroke: #3b82f6; }

    /* Link */
    .docs-link { color: var(--gold); text-decoration: none; font-weight: 500; transition: opacity 0.2s; }
    .docs-link:hover { opacity: 0.8; }

    /* Table */
    .docs-table-wrap {
        margin: 1.5rem 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        overflow-x: auto;
    }

    .docs-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .docs-table thead { position: sticky; top: 0; z-index: 1; }

    .docs-table th {
        background: var(--bg-card);
        font-weight: 600;
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 2px solid var(--gold);
        white-space: nowrap;
    }

    .docs-table td {
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }

    .docs-table tbody tr:last-child td { border-bottom: none; }

    /* Color bands for multiplier table */
    .docs-table tbody tr:nth-child(1) td { background: rgba(34,197,94,0.04); }
    .docs-table tbody tr:nth-child(2) td { background: rgba(34,197,94,0.07); }
    .docs-table tbody tr:nth-child(3) td { background: rgba(212,175,55,0.05); }
    .docs-table tbody tr:nth-child(4) td { background: rgba(239,68,68,0.04); }
    .docs-table tbody tr:nth-child(5) td { background: rgba(239,68,68,0.07); }

    .docs-table code {
        background: rgba(0,0,0,0.3);
        padding: 0.2rem 0.45rem;
        border-radius: 3px;
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.82rem;
        color: var(--gold);
    }

    /* Multiplier pills */
    .mult-pill {
        display: inline-block;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
        font-family: 'Monaco','Menlo',monospace;
        white-space: nowrap;
    }

    .mult-05  { background: rgba(34,197,94,0.15);  color: #22c55e; border: 1px solid rgba(34,197,94,0.3);  }
    .mult-1   { background: rgba(34,197,94,0.1);   color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
    .mult-15  { background: rgba(212,175,55,0.12); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
    .mult-2   { background: rgba(239,68,68,0.1);   color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
    .mult-35  { background: rgba(185,28,28,0.15);  color: #f87171; border: 1px solid rgba(185,28,28,0.3);  }

    /* ── 402 Error Block ── */
    .error-402-card {
        background: rgba(220,38,38,0.06);
        border: 1px solid rgba(220,38,38,0.3);
        border-left: 5px solid #dc2626;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin: 1.5rem 0;
    }

    .error-402-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .error-402-badge {
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 1.1rem;
        font-weight: 800;
        color: #f87171;
        background: rgba(220,38,38,0.15);
        border: 1px solid rgba(220,38,38,0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }

    .error-402-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fca5a5;
    }

    .error-402-card p { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.65; margin: 0 0 0.75rem; }
    .error-402-card p:last-child { margin: 0; }

    /* ── Subscription vs Top-Up comparison ── */
    .credits-compare {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin: 1.5rem 0;
    }

    .credits-compare-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .credits-compare-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .credits-compare-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(212,175,55,0.1);
        border: 1px solid rgba(212,175,55,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .credits-compare-icon svg {
        width: 20px; height: 20px;
        stroke: var(--gold); fill: none;
        stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round;
    }

    .credits-compare-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--gold);
    }

    .credits-compare-card p {
        color: var(--text-secondary);
        font-size: 0.87rem;
        line-height: 1.65;
        margin: 0;
    }

    /* ── Balance endpoint block ── */
    .balance-endpoint-block {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .balance-endpoint-method {
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.78rem;
        font-weight: 700;
        background: rgba(34,197,94,0.15);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.3);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .balance-endpoint-url {
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.84rem;
        color: var(--text-secondary);
        flex: 1;
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

        .credits-hero-number { font-size: 3.25rem; }
        .credits-hero-unit { font-size: 1.35rem; }

        .formula-block { padding: 1.25rem 1rem; gap: 0.5rem; }
        .formula-value { font-size: 0.9rem; }
        .formula-op, .formula-eq { font-size: 1.1rem; }

        .credits-compare { grid-template-columns: 1fr; }

        .balance-endpoint-block { flex-direction: column; align-items: flex-start; gap: 0.5rem; }

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

        .docs-table-wrap {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .docs-table th,
        .docs-table td { padding: 0.6rem 0.75rem; font-size: 0.82rem; }
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
                @if(app()->getLocale() === 'ar') نظام الرصيد @else Credits System @endif
            </span>
        </nav>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                نظام <span>الرصيد</span>
            @else
                Credits <span>System</span>
            @endif
        </h1>
        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                دليل شامل لفهم نظام الرصيد في LLM Resayil — كيف يعمل، كيف يُحتسب، وما الذي يحدث عند نفاده.
            </p>
        @else
            <p class="docs-intro">
                A comprehensive guide to understanding the LLM Resayil credits system — how credits work,
                how they are calculated, and what happens when your balance runs out.
            </p>
        @endif

        <!-- Section 1: What Are Credits? -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') ما هو الرصيد؟ @else What Are Credits? @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    الرصيد هو وحدة الدفع في LLM Resayil. بدلًا من الدفع بالدولار مباشرةً مقابل كل طلب، يُخصم رصيد من حسابك
                    بناءً على عدد الرموز (Tokens) التي يستهلكها طلبك والنموذج المستخدَم. هذا النظام يمنحك مرونة كاملة:
                    تدفع فقط مقابل ما تستخدمه، دون رسوم ثابتة أو اشتراكات إلزامية.
                </p>
            @else
                <p>
                    Credits are the unit of payment in LLM Resayil. Rather than paying in dollars directly per request,
                    a credit amount is deducted from your account based on the number of tokens consumed and the model
                    used. This system gives you complete flexibility — you pay only for what you use, with no fixed fees
                    or mandatory subscriptions.
                </p>
            @endif
        </section>

        <!-- Section 2: Free Tier Credits — Hero Stat -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') رصيد الترحيب للمستخدمين الجدد @else Free Credits for New Users @endif
            </h2>

            <!-- Hero Stat -->
            <div class="credits-hero">
                <div class="credits-hero-label">
                    @if(app()->getLocale() === 'ar') رصيد مجاني عند التسجيل @else Free welcome credits on sign-up @endif
                </div>
                <div>
                    <span class="credits-hero-number">1,000</span>
                    <span class="credits-hero-unit">@if(app()->getLocale() === 'ar') رصيدة @else credits @endif</span>
                </div>
                <div class="credits-hero-sub">
                    @if(app()->getLocale() === 'ar')
                        تُضاف تلقائيًا لكل حساب جديد — بدون بطاقة ائتمان
                    @else
                        Automatically added to every new account — no credit card required
                    @endif
                </div>
                <div class="credits-hero-badge">
                    @if(app()->getLocale() === 'ar') لا حاجة لبطاقة ائتمان @else No credit card required @endif
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    عند إنشاء حساب جديد على LLM Resayil، تحصل تلقائيًا على <strong>1,000 رصيدة مجانية</strong> للبدء.
                    هذا الرصيد يكفي لإجراء مئات الطلبات باستخدام النماذج المحلية، ويتيح لك اختبار المنصة واستكشاف النماذج المتاحة
                    قبل اتخاذ أي قرار بالشراء.
                </p>
            @else
                <p>
                    When you create a new LLM Resayil account, you automatically receive <strong>1,000 free credits</strong>
                    to get started. This allowance is sufficient for hundreds of requests with local models, letting you
                    evaluate the platform and explore available models before making any purchase decision.
                </p>
            @endif

            <div class="docs-box docs-box-tip">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>لا حاجة لبطاقة ائتمان:</strong> رصيد البداية متاح فور التسجيل دون الحاجة إلى إدخال أي بيانات دفع.
                    @else
                        <strong>No credit card required:</strong> The welcome credit is available immediately upon registration with no payment details needed.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 3: Credit-Based Billing -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') الفوترة القائمة على الرصيد @else Credit-Based Billing @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يعتمد LLM Resayil نموذج الدفع بحسب الاستخدام (Pay-per-use). الخصم يحدث مباشرةً بعد اكتمال كل طلب
                    بناءً على الصيغة التالية:
                </p>
            @else
                <p>
                    LLM Resayil uses a pay-per-use billing model. Deduction happens immediately after each completed
                    request based on the following formula:
                </p>
            @endif

            <!-- Formula Block -->
            <div class="formula-block">
                <div class="formula-part">
                    <span class="formula-value">(prompt_tokens</span>
                    <span class="formula-desc">@if(app()->getLocale() === 'ar') رموز المدخل @else input tokens @endif</span>
                </div>
                <span class="formula-op">+</span>
                <div class="formula-part">
                    <span class="formula-value">completion_tokens)</span>
                    <span class="formula-desc">@if(app()->getLocale() === 'ar') رموز الإخراج @else output tokens @endif</span>
                </div>
                <span class="formula-op">&times;</span>
                <div class="formula-part">
                    <span class="formula-value">model_multiplier</span>
                    <span class="formula-desc">@if(app()->getLocale() === 'ar') معامل النموذج @else model multiplier @endif</span>
                </div>
                <span class="formula-eq">=</span>
                <div class="formula-part formula-result">
                    <span class="formula-value">credits_deducted</span>
                    <span class="formula-desc">@if(app()->getLocale() === 'ar') الرصيد المخصوم @else deducted @endif</span>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    لا توجد رسوم اشتراك شهري ولا رسوم ثابتة. يظل رصيدك محفوظًا حتى تستخدمه — سواء استغرق أسبوعًا أم شهرًا أم أكثر.
                </p>
            @else
                <p>
                    There are no monthly subscription fees and no standing charges. Your credit balance remains intact
                    until you use it — whether that takes a week, a month, or longer.
                </p>
            @endif
        </section>

        <!-- Section 4: Multiplier Table -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') معاملات الضرب حسب فئة النموذج @else Credit Rates by Model Tier @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يختلف معدل خصم الرصيد بحسب فئة النموذج — النماذج القياسية أو النماذج المتقدمة. اختر النموذج المناسب لحالة استخدامك لتحقيق أقصى استفادة من رصيدك:
                </p>
            @else
                <p>
                    The credit deduction rate varies by model tier — Standard Models or Frontier Models. Choose the right tier for your use case to get the most from your credits:
                </p>
            @endif

            {{-- Standard Models --}}
            <div style="margin: 1.5rem 0 0.6rem; display: flex; align-items: center; gap: 0.65rem;">
                <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="16" cy="16" r="12" stroke="#6b7280" stroke-width="1.5" fill="#6b7280" fill-opacity="0.07"/>
                    <circle cx="16" cy="16" r="3.5" stroke="#6b7280" stroke-width="1.5" fill="#6b7280" fill-opacity="0.15"/>
                    <circle cx="16" cy="16" r="1.2" fill="#6b7280"/>
                </svg>
                <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">
                    @if(app()->getLocale() === 'ar') النماذج القياسية @else Standard Models @endif
                </span>
            </div>
            <div class="docs-table-wrap" style="margin-top: 0.5rem;">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar')نوع النموذج@else Model Type@endif</th>
                            <th>@if(app()->getLocale() === 'ar')معامل الضرب@else Multiplier@endif</th>
                            <th>@if(app()->getLocale() === 'ar')أمثلة@else Examples@endif</th>
                            <th>@if(app()->getLocale() === 'ar')الاستخدام الأمثل@else Best For@endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')خفيف — صغير وسريع@else Lightweight models@endif</td>
                            <td><span class="mult-pill mult-05">0.5&times;</span></td>
                            <td>Phi-3 Mini, TinyLlama</td>
                            <td>@if(app()->getLocale() === 'ar')التصنيف، الملخصات القصيرة@else Classification, short summaries@endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')قياسي — متوازن@else Standard models@endif</td>
                            <td><span class="mult-pill mult-1">1.0&times;</span></td>
                            <td>Mistral 7B, Llama 3 8B</td>
                            <td>@if(app()->getLocale() === 'ar')الدردشة العامة، الكتابة، البرمجة@else General chat, writing, coding@endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')متوسط — كبير الحجم@else Mid-range models@endif</td>
                            <td><span class="mult-pill mult-15">1.5&times;</span></td>
                            <td>Llama 3 70B, Mixtral 8x7B</td>
                            <td>@if(app()->getLocale() === 'ar')الاستدلال المعقد، المحتوى الطويل@else Complex reasoning, long content@endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Frontier Models --}}
            <div style="margin: 1.75rem 0 0.6rem; display: flex; align-items: center; gap: 0.65rem;">
                <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="16" cy="16" r="12" stroke="#ef4444" stroke-width="1.5" fill="#ef4444" fill-opacity="0.08"/>
                    <circle cx="16" cy="16" r="7.5" stroke="#ef4444" stroke-width="1" fill="#ef4444" fill-opacity="0.06" stroke-dasharray="2.5 1.5"/>
                    <path d="M16 9.5l3.5 6.5-3.5 6.5-3.5-6.5L16 9.5z" fill="#ef4444" fill-opacity="0.25" stroke="#ef4444" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M9.5 16l6.5-3.5 6.5 3.5-6.5 3.5L9.5 16z" fill="#ef4444" fill-opacity="0.15" stroke="#ef4444" stroke-width="1.5" stroke-linejoin="round"/>
                    <circle cx="16" cy="16" r="1.75" fill="#ef4444" fill-opacity="0.9"/>
                </svg>
                <span style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">
                    @if(app()->getLocale() === 'ar') النماذج المتقدمة @else Frontier Models @endif
                </span>
            </div>
            <div class="docs-table-wrap" style="margin-top: 0.5rem;">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar')نوع النموذج@else Model Type@endif</th>
                            <th>@if(app()->getLocale() === 'ar')معامل الضرب@else Multiplier@endif</th>
                            <th>@if(app()->getLocale() === 'ar')أمثلة@else Examples@endif</th>
                            <th>@if(app()->getLocale() === 'ar')الاستخدام الأمثل@else Best For@endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')متقدم — متوسط@else Frontier Standard@endif</td>
                            <td><span class="mult-pill mult-2">2.0× – 2.5×</span></td>
                            <td>GPT-4o Mini, Claude Haiku</td>
                            <td>@if(app()->getLocale() === 'ar')مهام تتطلب دقةً عالية@else High-accuracy tasks@endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')متقدم — كبير@else Frontier Large@endif</td>
                            <td><span class="mult-pill mult-35">3.0× – 3.5×</span></td>
                            <td>GPT-4o, Claude 3.5 Sonnet, Gemini Pro</td>
                            <td>@if(app()->getLocale() === 'ar')أصعب المهام، الاستدلال المتعدد@else Hardest tasks, multi-step reasoning@endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    للاطلاع على المعامل الدقيق لكل نموذج، راجع صفحة <a href="{{ route('docs.models') }}" class="docs-link">النماذج المتاحة</a>.
                </p>
            @else
                <p>
                    For the exact multiplier of each individual model, refer to the <a href="{{ route('docs.models') }}" class="docs-link">Available Models</a> page.
                </p>
            @endif
        </section>

        <!-- Section 5: How to Check Balance -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') التحقق من رصيدك @else Checking Your Balance @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>يمكنك الاطلاع على رصيدك الحالي بطريقتين:</p>
            @else
                <p>You can check your current balance in two ways:</p>
            @endif
            <ul>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>لوحة التحكم:</strong> يظهر الرصيد في أعلى الصفحة الرئيسية لحسابك.
                    @else
                        <strong>Dashboard:</strong> Your balance is displayed at the top of your account's main page.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>عبر API:</strong> استخدم نقطة النهاية <code>GET /api/billing/subscription</code> للاستعلام برمجيًا.
                    @else
                        <strong>Via API:</strong> Use the <code>GET /api/billing/subscription</code> endpoint to query programmatically.
                    @endif
                </li>
            </ul>

            <!-- Styled endpoint block -->
            <div class="balance-endpoint-block">
                <span class="balance-endpoint-method">GET</span>
                <span class="balance-endpoint-url">https://llm.resayil.io/api/billing/subscription</span>
            </div>

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON Response</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-balance-resp">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-balance-resp">{
  "tier": "free",
  "status": "active",
  "expires_at": null,
  "credits": 732.00
}</code>
                </div>
            </div>
        </section>

        <!-- Section 6: When Credits Run Out — 402 card -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') ماذا يحدث عند نفاد الرصيد؟ @else What Happens When Credits Run Out @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    عند محاولة إرسال طلب وكان رصيدك غير كافٍ لتغطيته، يرفض الخادم الطلب فورًا ويُعيد خطأ HTTP:
                </p>
            @else
                <p>
                    When you attempt a request and your balance is insufficient to cover it, the server immediately
                    rejects the request and returns an HTTP error:
                </p>
            @endif

            <!-- Red-accented 402 error block -->
            <div class="error-402-card">
                <div class="error-402-card-header">
                    <span class="error-402-badge">402</span>
                    <span class="error-402-title">Payment Required — Insufficient Credits</span>
                </div>
                <p>@if(app()->getLocale() === 'ar') استجابة الخادم عند نفاد الرصيد: @else Server response when balance is exhausted: @endif</p>
                <div class="docs-code-wrap" style="margin: 0.5rem 0 0;">
                    <div class="docs-code-header">
                        <span class="docs-code-lang">HTTP / JSON</span>
                        <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-402-err">
                            <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <div class="docs-code-block">
                        <code id="code-402-err">HTTP/1.1 402 Payment Required

{
  "error": {
    "message": "Insufficient credits. Please top up your balance to continue.",
    "type": "insufficient_credits",
    "code": 402
  }
}</code>
                    </div>
                </div>
            </div>

            <div class="docs-box docs-box-danger">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>تنبيه:</strong> لا يتم تنفيذ أي جزء من الطلب إذا كان الرصيد غير كافٍ — النموذج لا يُشغَّل ولا تُخصم أي رصيدة جزئية.
                    @else
                        <strong>Note:</strong> No part of the request is executed if the balance is insufficient — the model is not invoked and no partial credit is deducted.
                    @endif
                </p>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    لاستئناف الاستخدام، يكفي شراء حزمة شحن عبر صفحة <a href="https://llm.resayil.io/billing/plans" class="docs-link" target="_blank" rel="noopener">الفوترة</a>.
                    يُضاف الرصيد تلقائيًا فور تأكيد الدفع.
                </p>
            @else
                <p>
                    To resume usage, simply purchase a top-up pack through the
                    <a href="https://llm.resayil.io/billing/plans" class="docs-link" target="_blank" rel="noopener">Billing</a> page.
                    Credits are added automatically as soon as payment is confirmed.
                </p>
            @endif
        </section>

        <!-- Section 7: Subscription vs Top-Up Credits -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') رصيد الاشتراك مقابل رصيد الشحن @else Subscription Credits vs Top-Up Credits @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يتضمن حسابك نوعين من الرصيد يعملان معًا بشفافية تامة:
                </p>
            @else
                <p>
                    Your account can hold two types of credits that work together seamlessly:
                </p>
            @endif

            <!-- Two-column comparison cards -->
            <div class="credits-compare">
                <div class="credits-compare-card">
                    <div class="credits-compare-card-header">
                        <div class="credits-compare-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        </div>
                        <div class="credits-compare-title">
                            @if(app()->getLocale() === 'ar') رصيد الاشتراك @else Subscription Credits @endif
                        </div>
                    </div>
                    <p>
                        @if(app()->getLocale() === 'ar')
                            رصيد مُخصَّص ضمن خطة اشتراك دورية. يتجدد عند تجديد الاشتراك. رصيد الخطة المجانية (1,000 رصيدة عند التسجيل) يندرج ضمن هذه الفئة.
                        @else
                            Credits allocated as part of a recurring subscription plan. Renewed when the subscription renews. The free-tier welcome credit (1,000 credits at registration) falls under this type.
                        @endif
                    </p>
                </div>
                <div class="credits-compare-card">
                    <div class="credits-compare-card-header">
                        <div class="credits-compare-icon">
                            <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="credits-compare-title">
                            @if(app()->getLocale() === 'ar') رصيد الشحن الإضافي @else Top-Up Credits @endif
                        </div>
                    </div>
                    <p>
                        @if(app()->getLocale() === 'ar')
                            رصيد مشترى لمرة واحدة يُضاف فوق رصيد الاشتراك. لا ينتهي بانتهاء الاشتراك ولا يتجدد تلقائيًا — يبقى حتى يُستنفَد.
                        @else
                            Credits purchased as a one-time top-up, added on top of subscription credits. They do not expire with the subscription and do not renew automatically — they persist until consumed.
                        @endif
                    </p>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    عند تنفيذ الطلبات، يستخدم النظام رصيد الاشتراك أولًا، ثم ينتقل إلى رصيد الشحن الإضافي عند الحاجة.
                    يُظهر <code>GET /api/billing/subscription</code> إجمالي الرصيد المتاح من كلا المصدرين.
                </p>
            @else
                <p>
                    When executing requests, the system draws from subscription credits first, then moves to top-up
                    credits as needed. The <code>GET /api/billing/subscription</code> endpoint shows the combined
                    available balance from both sources.
                </p>
            @endif

            <div class="docs-box docs-box-note">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>للاطلاع على المزيد:</strong> راجع صفحة
                        <a href="{{ route('docs.topup') }}" class="docs-link">شراء رصيد إضافي</a>
                        لفهم كيفية شراء حزم الشحن وطرق الدفع المتاحة.
                    @else
                        <strong>Learn more:</strong> See the
                        <a href="{{ route('docs.topup') }}" class="docs-link">Top-Up Credits</a> page for details
                        on purchasing credit packs and available payment methods.
                    @endif
                </p>
            </div>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>هل تريد مراقبة استهلاكك بدقة؟</h3>
                <p>تعرّف على كيفية تتبّع الاستخدام وتحسين طلباتك لتوفير الرصيد.</p>
                <a href="{{ route('docs.usage') }}">الانتقال إلى صفحة الاستخدام &rarr;</a>
            @else
                <h3>Want to monitor your consumption precisely?</h3>
                <p>Learn how to track usage in real time and optimize your requests to save credits.</p>
                <a href="{{ route('docs.usage') }}">Go to Usage &amp; Analytics &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Docs', 'url' => route('docs.index')],
    ['name' => 'Credits System', 'url' => route('docs.credits')]
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
