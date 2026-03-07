@extends('layouts.app')

@section('title', 'Billing & Credits — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page Styles ── */
    .docs-page {
        background: var(--bg-secondary);
        padding: 3rem 2rem;
        min-height: 100vh;
    }

    .docs-content {
        max-width: 960px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .docs-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .docs-breadcrumb a {
        color: var(--gold);
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-breadcrumb a:hover { opacity: 0.8; }
    .docs-breadcrumb span { color: var(--text-secondary); }

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
        margin-bottom: 2.5rem;
        line-height: 1.8;
        max-width: 72ch;
    }

    /* Sections */
    .docs-section {
        margin-bottom: 3.5rem;
    }

    .docs-section h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .docs-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 1.5rem 0 0.75rem;
        color: var(--text-primary);
    }

    .docs-section p {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .docs-section ul {
        list-style: none;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .docs-section li {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 0.75rem;
        position: relative;
        padding-left: 1rem;
    }

    .docs-section li:before {
        content: "▸";
        color: var(--gold);
        position: absolute;
        left: 0;
    }

    .docs-section strong { color: var(--text-primary); }

    /* ── Credit Formula Block ── */
    .formula-block {
        background: linear-gradient(135deg, rgba(212,175,55,0.06) 0%, rgba(212,175,55,0.02) 100%);
        border: 1px solid rgba(212,175,55,0.3);
        border-radius: 12px;
        padding: 2rem 2rem 1.5rem;
        margin: 1.75rem 0;
        text-align: center;
    }

    .formula-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gold);
        margin-bottom: 1rem;
        display: block;
    }

    .formula-equation {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-bottom: 1rem;
    }

    .formula-var {
        display: inline-block;
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        font-weight: 600;
        background: rgba(96,165,250,0.12);
        color: #60a5fa;
        border: 1px solid rgba(96,165,250,0.25);
    }

    .formula-var.out {
        background: rgba(212,175,55,0.14);
        color: var(--gold);
        border-color: rgba(212,175,55,0.3);
        font-size: 1rem;
    }

    .formula-op {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-muted);
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .formula-paren {
        font-size: 1.4rem;
        color: var(--text-muted);
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .formula-note {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-style: italic;
    }

    /* ── Code Blocks ── */
    .docs-code-block {
        background: #0d1017;
        border: 1px solid var(--border);
        border-radius: 8px;
        margin: 1.5rem 0;
        overflow: hidden;
        position: relative;
    }

    .docs-code-block-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.03);
        border-bottom: 1px solid var(--border);
    }

    .docs-code-lang {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .lang-bash    { background: rgba(52,211,153,0.15); color: #34d399; }
    .lang-json    { background: rgba(251,191,36,0.15);  color: #fbbf24; }
    .lang-get     { background: rgba(52,211,153,0.15);  color: #34d399; }
    .lang-post    { background: rgba(96,165,250,0.15);  color: #60a5fa; }
    .lang-example { background: rgba(167,139,250,0.15); color: #a78bfa; }
    .lang-formula { background: rgba(212,175,55,0.15);  color: var(--gold); }

    .docs-copy-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.6rem;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 5px;
        color: var(--text-muted);
        font-size: 0.72rem;
        cursor: pointer;
        transition: all 0.18s;
        font-family: inherit;
    }

    .docs-copy-btn:hover { border-color: var(--gold); color: var(--gold); }
    .docs-copy-btn.copied { border-color: #34d399; color: #34d399; }

    .docs-code-block pre {
        margin: 0;
        padding: 1.25rem;
        overflow-x: auto;
    }

    .docs-code-block code {
        display: block;
        font-size: 0.85rem;
        line-height: 1.65;
        color: #a0d468;
        font-family: 'Monaco', 'Courier New', monospace;
        white-space: pre;
    }

    /* ── API Endpoint Blocks ── */
    .api-endpoint-block {
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        margin: 1.5rem 0;
        background: var(--bg-card);
    }

    .api-endpoint-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.25rem;
        cursor: pointer;
        user-select: none;
        transition: background 0.18s;
    }

    .api-endpoint-header:hover {
        background: rgba(255,255,255,0.03);
    }

    .method-badge {
        display: inline-block;
        padding: 0.2rem 0.65rem;
        border-radius: 5px;
        font-size: 0.72rem;
        font-weight: 800;
        font-family: 'Monaco', 'Courier New', monospace;
        letter-spacing: 0.04em;
        flex-shrink: 0;
    }

    .method-get  { background: rgba(52,211,153,0.15); color: #34d399; border: 1px solid rgba(52,211,153,0.3); }
    .method-post { background: rgba(96,165,250,0.15); color: #60a5fa; border: 1px solid rgba(96,165,250,0.3); }

    .api-endpoint-path {
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.88rem;
        color: var(--text-primary);
        flex: 1;
    }

    .api-endpoint-toggle {
        font-size: 0.75rem;
        color: var(--text-muted);
        transition: transform 0.2s;
        flex-shrink: 0;
    }

    .api-endpoint-header.open .api-endpoint-toggle {
        transform: rotate(180deg);
    }

    .api-endpoint-body {
        display: none;
        border-top: 1px solid var(--border);
    }

    .api-endpoint-body.open {
        display: block;
    }

    .api-endpoint-desc {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.7;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,0.015);
    }

    .api-endpoint-body .docs-code-block {
        margin: 0;
        border-radius: 0;
        border: none;
        border-bottom: 1px solid var(--border);
    }

    .api-endpoint-body .docs-code-block:last-child {
        border-bottom: none;
    }

    /* ── Info / Warning / Note Boxes ── */
    .docs-info-box {
        background: rgba(212, 175, 55, 0.08);
        border-left: 4px solid var(--gold);
        border-radius: 0 6px 6px 0;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-info-box.note {
        background: rgba(96,165,250,0.07);
        border-left-color: #60a5fa;
    }

    .docs-info-box.warning {
        background: rgba(251,113,133,0.07);
        border-left-color: #fb7185;
    }

    .docs-info-box p { margin: 0; color: var(--text-secondary); }

    /* ── Top-Up Callout ── */
    .topup-callout {
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.04) 100%);
        border: 1px solid rgba(212,175,55,0.35);
        border-radius: 12px;
        padding: 1.75rem 2rem;
        margin: 1.75rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .topup-callout-content h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 0.4rem;
    }

    .topup-callout-content p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.6;
    }

    .topup-callout-btn {
        display: inline-block;
        padding: 0.65rem 1.5rem;
        background: var(--gold);
        color: #0f1115;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: opacity 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .topup-callout-btn:hover { opacity: 0.85; }

    /* ── Subscription Tier Cards ── */
    .tier-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .tier-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.4rem 1.2rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        position: relative;
    }

    .tier-card:hover {
        border-color: rgba(212,175,55,0.35);
        box-shadow: 0 4px 24px rgba(0,0,0,0.25);
    }

    .tier-card.recommended {
        border-color: var(--gold);
        box-shadow: 0 0 0 1px rgba(212,175,55,0.25), 0 6px 28px rgba(212,175,55,0.08);
    }

    .tier-card-badge {
        position: absolute;
        top: -11px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--gold);
        color: #0f1115;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        white-space: nowrap;
    }

    .tier-svg-icon { flex-shrink: 0; vertical-align: middle; }
    .tier-card-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tier-card.recommended .tier-card-name {
        color: var(--gold);
    }

    .tier-card-divider {
        height: 1px;
        background: var(--border);
        margin: 0.25rem 0;
    }

    .tier-card-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .tier-card-label {
        font-size: 0.72rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        flex-shrink: 0;
    }

    .tier-card-value {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
        text-align: right;
    }

    .tier-card-value.highlight {
        color: var(--gold);
        font-weight: 700;
    }

    /* ── Multiplier table (billing page) ── */
    .docs-table-wrap {
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
        margin: 1.25rem 0;
    }

    .docs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .docs-table thead { position: sticky; top: 0; z-index: 2; }

    .docs-table th {
        background: var(--bg-card);
        font-weight: 600;
        color: var(--text-primary);
        padding: 0.8rem 0.9rem;
        text-align: left;
        border-bottom: 2px solid var(--gold);
        white-space: nowrap;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .docs-table td {
        padding: 0.7rem 0.9rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .docs-table tbody tr:nth-child(odd) td  { background: rgba(255,255,255,0.015); }
    .docs-table tbody tr:nth-child(even) td { background: transparent; }
    .docs-table tbody tr:last-child td      { border-bottom: none; }
    .docs-table tbody tr:hover td           { background: rgba(212,175,55,0.045); }

    .docs-table code {
        background: rgba(0, 0, 0, 0.25);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Courier New', monospace;
        color: #a0d468;
        font-size: 0.82rem;
        border: 1px solid rgba(255,255,255,0.06);
    }

    /* ── Multiplier Chips (consistent with models page) ── */
    .multiplier {
        display: inline-block;
        padding: 0.18rem 0.55rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Monaco', 'Courier New', monospace;
        white-space: nowrap;
    }

    .mult-05  { background: rgba(52,211,153,0.15);  color: #34d399;  border: 1px solid rgba(52,211,153,0.3); }
    .mult-10  { background: rgba(96,165,250,0.15);  color: #60a5fa;  border: 1px solid rgba(96,165,250,0.3); }
    .mult-15  { background: rgba(251,191,36,0.15);  color: #fbbf24;  border: 1px solid rgba(251,191,36,0.3); }
    .mult-25  { background: rgba(251,146,60,0.15);  color: #fb923c;  border: 1px solid rgba(251,146,60,0.3); }
    .mult-35  { background: rgba(251,113,133,0.15); color: #fb7185;  border: 1px solid rgba(251,113,133,0.3); }

    /* ── Link ── */
    .docs-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .docs-link:hover { opacity: 0.8; }

    /* ── Next Section Link ── */
    .docs-next-section {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-section h3 { margin-bottom: 0.5rem; }

    .docs-next-section p {
        color: var(--text-secondary);
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }

    .docs-next-section a {
        display: inline-block;
        padding: 0.75rem 1.75rem;
        background: var(--gold);
        color: #0f1115;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        transition: opacity 0.2s;
        font-size: 0.95rem;
    }

    .docs-next-section a:hover { opacity: 0.85; }

    @media (max-width: 768px) {
        .docs-title { font-size: 1.75rem; }
        .docs-section h2 { font-size: 1.35rem; }

        .formula-equation { gap: 0.4rem; }
        .formula-var { font-size: 0.8rem; padding: 0.35rem 0.6rem; }

        .topup-callout { flex-direction: column; text-align: center; }
        .topup-callout-btn { width: 100%; text-align: center; }

        .tier-cards-grid { grid-template-columns: 1fr 1fr; }

        .docs-table-wrap {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .api-endpoint-path { font-size: 0.78rem; }
    }

    @media (max-width: 480px) {
        .tier-cards-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="docs-page">
    <div class="docs-content">

        <!-- Breadcrumb -->
        <div class="docs-breadcrumb">
            <a href="{{ route('welcome') }}">Home</a>
            <span>&rarr;</span>
            <a href="{{ route('docs.index') }}">Documentation</a>
            <span>&rarr;</span>
            <span>Billing & Credits</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Billing & <span>Credits</span></h1>
        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                تعرّف على كيفية عمل نظام الرصيد في LLM Resayil، وطريقة احتساب استهلاك الرموز،
                وكيفية إدارة رصيد حسابك وتكاليفك.
            </p>
        @else
            <p class="docs-intro">
                Understand how LLM Resayil's credit system works, how we calculate token consumption,
                and how to manage your account balance and costs.
            </p>
        @endif

        <!-- Section 1: How Credits Work -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>كيف يعمل نظام الرصيد</h2>
                <p>
                    يعتمد LLM Resayil على نظام فوترة قائم على الرصيد. تُخصم الأرصدة تلقائياً من حسابك
                    بناءً على عدد الرموز المستهلكة في كل طلب API. هذا النموذج يعني أنك لا تدفع إلا مقابل ما تستخدمه فعلاً.
                </p>

                <h3>ما هي الأرصدة؟</h3>
                <p>
                    الأرصدة هي وحدة الحساب الأساسية. عند إرسال طلب API، يُطبَّق معامل ضرب على مجموع الرموز
                    (رموز المدخلات + رموز المخرجات) لتحديد عدد الأرصدة المخصومة.
                </p>

                <h3>الرصيد الابتدائي</h3>
                <p>
                    تحصل الحسابات الجديدة على <strong>1,000 رصيد مجاني</strong> للبدء. استخدمها لاستكشاف
                    الواجهة البرمجية واختبار النماذج المختلفة. لا يُشترط وجود بطاقة ائتمانية للمستوى المجاني.
                </p>
            @else
                <h2>How Credits Work</h2>
                <p>
                    LLM Resayil uses a credit-based billing system. Credits are automatically deducted from
                    your account based on the number of tokens consumed per API request. This pay-per-token
                    model means you only pay for what you actually use.
                </p>

                <h3>What Are Credits?</h3>
                <p>
                    Credits are our standard unit of account. When you make an API request, a credit multiplier
                    is applied to the total token count (prompt tokens + completion tokens) to determine how many
                    credits are deducted from your balance.
                </p>

                <h3>Starting Balance</h3>
                <p>
                    New accounts receive <strong>1,000 free credits</strong> to get started. Use these to explore
                    the API and test different models. No credit card is required for the free tier. When your
                    free credits are exhausted, you'll need to purchase more to continue using the API.
                </p>
            @endif

            <div class="docs-info-box">
                @if(app()->getLocale() === 'ar')
                    <p><strong>صلاحية الرصيد المجاني:</strong> الأرصدة المجانية صالحة لمدة 30 يوماً من تاريخ إنشاء الحساب.
                    بعد انتهاء هذه المدة تنتهي صلاحيتها. أما الأرصدة المشتراة فلا تنتهي صلاحيتها وتبقى سارية إلى أجل غير مسمى.</p>
                @else
                    <p><strong>Free Credits Validity:</strong> Free credits are valid for 30 days from account creation.
                    After 30 days, your free credits expire. Purchased credits do not expire and remain valid indefinitely.</p>
                @endif
            </div>
        </section>

        <!-- Section 2: Credit Calculation Formula -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>صيغة احتساب الرصيد</h2>
                <p>
                    يُحسب الرصيد المخصوم لكل طلب بضرب مجموع الرموز في معامل النموذج المستخدم.
                    يختلف هذا المعامل بين النماذج المحلية والنماذج السحابية.
                </p>
            @else
                <h2>Credit Calculation Formula</h2>
                <p>
                    Credits deducted per request are calculated by multiplying the total token count by the
                    model's credit multiplier. The multiplier varies by model tier — Standard or Frontier.
                </p>
            @endif

            <!-- Visual formula equation block -->
            <div class="formula-block">
                <span class="formula-label">
                    @if(app()->getLocale() === 'ar') معادلة الاحتساب @else Calculation Formula @endif
                </span>
                <div class="formula-equation" dir="ltr">
                    <span class="formula-var out">credits_deducted</span>
                    <span class="formula-op">=</span>
                    <span class="formula-paren">(</span>
                    <span class="formula-var">prompt_tokens</span>
                    <span class="formula-op">+</span>
                    <span class="formula-var">completion_tokens</span>
                    <span class="formula-paren">)</span>
                    <span class="formula-op">&times;</span>
                    <span class="formula-var">credit_multiplier</span>
                </div>
                <p class="formula-note">
                    @if(app()->getLocale() === 'ar')
                        تُحتسب رموز المدخلات والمخرجات معاً، ثم تُضرب في معامل النموذج.
                    @else
                        Prompt and completion tokens are summed, then multiplied by the model's credit multiplier.
                    @endif
                </p>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>معاملات النماذج حسب الفئة</h3>
                <p>تتراوح قيمة المعامل بحسب فئة النموذج — النماذج القياسية أو النماذج المتقدمة:</p>
            @else
                <h3>Credit Rates by Model Tier</h3>
                <p>Multiplier values are organized by model tier — Standard Models or Frontier Models:</p>
            @endif

            {{-- Standard Models tier --}}
            <div style="margin: 1.5rem 0 0.75rem; display: flex; align-items: center; gap: 0.6rem;">
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
                            @if(app()->getLocale() === 'ar')
                                <th>نوع النموذج</th>
                                <th>المعامل</th>
                                <th>الاستخدام الأمثل</th>
                            @else
                                <th>Model Type</th>
                                <th>Multiplier</th>
                                <th>Best For</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar') خفيف — صغير وسريع @else Lightweight — small &amp; fast @endif</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') التصنيف، الملخصات القصيرة @else Classification, short summaries @endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar') قياسي — متوازن @else Standard — balanced @endif</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') الدردشة العامة، الكتابة، البرمجة @else General chat, writing, coding @endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar') متوسط — كبير الحجم @else Mid-range — larger models @endif</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') الاستدلال المعقد، المحتوى الطويل @else Complex reasoning, long content @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Frontier Models tier --}}
            <div style="margin: 1.75rem 0 0.75rem; display: flex; align-items: center; gap: 0.6rem;">
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
                            @if(app()->getLocale() === 'ar')
                                <th>نوع النموذج</th>
                                <th>المعامل</th>
                                <th>الاستخدام الأمثل</th>
                            @else
                                <th>Model Type</th>
                                <th>Multiplier</th>
                                <th>Best For</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar') متقدم — متوسط @else Frontier Standard @endif</td>
                            <td><span class="multiplier mult-25">2.0× – 2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') مهام تتطلب دقةً عالية @else High-accuracy tasks @endif</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar') متقدم — كبير @else Frontier Large @endif</td>
                            <td><span class="multiplier mult-35">3.0× – 3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') أصعب المهام، الاستدلال المتعدد @else Hardest tasks, multi-step reasoning @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>مثال على الاحتساب</h3>
                <p>
                    لنفترض أنك أرسلت طلباً يحتوي على 100 رمز مدخل واستجاب النموذج بـ 200 رمز،
                    باستخدام نموذج محلي بمعامل 1.0:
                </p>
            @else
                <h3>Example Calculation</h3>
                <p>
                    Suppose you send a request with 100 prompt tokens and the model responds with 200 completion
                    tokens, using a local model with a 1.0× multiplier:
                </p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-example">example</span>
                    <button class="docs-copy-btn" aria-label="Copy example">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>prompt_tokens:      100
completion_tokens:  200
total_tokens:       300
credit_multiplier:  1.0  (local model)
credits_deducted:   300 × 1.0 = 300 credits

-- Cloud model example (multiplier 2.5×) --
credits_deducted:   300 × 2.5 = 750 credits</code></pre>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    يمكنك مراجعة <a href="{{ url('/dashboard') }}" class="docs-link">لوحة التحكم</a>
                    للاطلاع على معامل كل نموذج بشكل محدد.
                </p>
            @else
                <p>
                    Check your <a href="{{ url('/dashboard') }}" class="docs-link">dashboard</a> for the
                    exact multiplier applied to each model.
                </p>
            @endif
        </section>

        <!-- Section 3: Token Consumption -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>استهلاك الرموز</h2>
                <p>
                    يعدّ فهم طريقة احتساب الرموز أمراً ضرورياً لإدارة التكاليف. تُحتسب نوعان من الرموز في كل طلب:
                    رموز المدخلات (الطلب) ورموز المخرجات (الاستجابة).
                </p>

                <h3>رموز المدخلات (Prompt Tokens)</h3>
                <p>
                    هي الرموز الموجودة في رسالة طلبك، بما يشمل أي موجّهات نظام، وسجل المحادثة، واستفسار المستخدم.
                    كلما طالت المحادثة وزاد السياق، ارتفع عدد رموز المدخلات وزادت التكلفة.
                </p>

                <h3>رموز المخرجات (Completion Tokens)</h3>
                <p>
                    هي الرموز التي يولّدها النموذج في استجابته. تُحدّد معلمة <code>max_tokens</code>
                    في طلبك الحد الأقصى لعدد رموز المخرجات الممكن توليدها.
                </p>

                <h3>معلومات الاستخدام في الاستجابة</h3>
                <p>
                    تتضمن كل استجابة API معلومات تفصيلية عن الرموز المستهلكة:
                </p>
            @else
                <h2>Token Consumption</h2>
                <p>
                    Understanding how tokens are counted is essential for budgeting. Two types of tokens are
                    counted per request: tokens in your input (prompt) and tokens in the model's output (completion).
                </p>

                <h3>Prompt Tokens</h3>
                <p>
                    Prompt tokens are the tokens in your request message, including any system prompts, message
                    history, and the user's query. Longer conversations and more context mean more prompt tokens
                    and higher costs.
                </p>

                <h3>Completion Tokens</h3>
                <p>
                    Completion tokens are the tokens the model generates in its response. The <code>max_tokens</code>
                    parameter in your request limits the maximum number of completion tokens that can be generated.
                </p>

                <h3>Usage Information in Responses</h3>
                <p>
                    Every API response includes detailed token usage information:
                </p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-json">json</span>
                    <button class="docs-copy-btn" aria-label="Copy usage field">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>"usage": {
  "prompt_tokens": 45,
  "completion_tokens": 128,
  "total_tokens": 173
}</code></pre>
            </div>
        </section>

        <!-- Section 4: Billing API Endpoints -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>نقاط نهاية API للفوترة</h2>
                <p>
                    يوفّر LLM Resayil نقاط نهاية API للاطلاع على بيانات الاشتراك والرصيد وسجل عمليات الشراء.
                    تتطلب جميع هذه النقاط مصادقة عبر مفتاح API.
                </p>
            @else
                <h2>Billing API Endpoints</h2>
                <p>
                    LLM Resayil provides API endpoints for querying subscription data, credit balance, and
                    purchase history. All billing endpoints require API key authentication.
                </p>
            @endif

            <!-- GET /api/billing/subscription -->
            <div class="api-endpoint-block">
                <div class="api-endpoint-header" role="button" tabindex="0" aria-expanded="false">
                    <span class="method-badge method-get">GET</span>
                    <span class="api-endpoint-path">/api/billing/subscription</span>
                    <span class="api-endpoint-toggle">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </div>
                <div class="api-endpoint-body">
                    <div class="api-endpoint-desc">
                        @if(app()->getLocale() === 'ar')
                            يُعيد معلومات اشتراكك الحالي، بما يشمل مستوى الخطة، والحالة، وتاريخ الانتهاء، ورصيد الأرصدة.
                        @else
                            Returns your current subscription details including tier, status, expiry date, and credit balance.
                        @endif
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-bash">Request</span>
                            <button class="docs-copy-btn" aria-label="Copy request">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>GET https://llm.resayil.io/api/billing/subscription
Authorization: Bearer YOUR_API_KEY</code></pre>
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-json">Response</span>
                            <button class="docs-copy-btn" aria-label="Copy response">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>{
  "tier": "pro",
  "status": "active",
  "expires_at": "2025-06-01T00:00:00Z",
  "credits": {
    "balance": 4250,
    "currency": "credits"
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /api/billing/topup-packs -->
            <div class="api-endpoint-block">
                <div class="api-endpoint-header" role="button" tabindex="0" aria-expanded="false">
                    <span class="method-badge method-get">GET</span>
                    <span class="api-endpoint-path">/api/billing/topup-packs</span>
                    <span class="api-endpoint-toggle">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </div>
                <div class="api-endpoint-body">
                    <div class="api-endpoint-desc">
                        @if(app()->getLocale() === 'ar')
                            يُعيد قائمة بحزم الرصيد المتاحة للشراء مع التفاصيل الخاصة بكل حزمة.
                        @else
                            Returns the list of available credit top-up packs with their pricing details.
                        @endif
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-bash">Request</span>
                            <button class="docs-copy-btn" aria-label="Copy request">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>GET https://llm.resayil.io/api/billing/topup-packs
Authorization: Bearer YOUR_API_KEY</code></pre>
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-json">Response</span>
                            <button class="docs-copy-btn" aria-label="Copy response">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>{
  "packs": [
    { "id": "pack_500",  "credits": 500,   "price": 5.00,  "currency": "USD" },
    { "id": "pack_2000", "credits": 2000,  "price": 18.00, "currency": "USD" },
    { "id": "pack_5000", "credits": 5000,  "price": 40.00, "currency": "USD" }
  ]
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /api/billing/topup-history -->
            <div class="api-endpoint-block">
                <div class="api-endpoint-header" role="button" tabindex="0" aria-expanded="false">
                    <span class="method-badge method-get">GET</span>
                    <span class="api-endpoint-path">/api/billing/topup-history</span>
                    <span class="api-endpoint-toggle">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </div>
                <div class="api-endpoint-body">
                    <div class="api-endpoint-desc">
                        @if(app()->getLocale() === 'ar')
                            يُعيد سجلاً مُرقَّماً بعمليات شراء الرصيد السابقة (20 عملية لكل صفحة).
                            استخدم معامل <code>page</code> للتنقل بين الصفحات.
                        @else
                            Returns a paginated list of past credit top-up purchases (20 per page).
                            Use the <code>page</code> query parameter to navigate between pages.
                        @endif
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-bash">Request</span>
                            <button class="docs-copy-btn" aria-label="Copy request">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>GET https://llm.resayil.io/api/billing/topup-history?page=1
Authorization: Bearer YOUR_API_KEY</code></pre>
                    </div>
                    <div class="docs-code-block">
                        <div class="docs-code-block-header">
                            <span class="docs-code-lang lang-json">Response</span>
                            <button class="docs-copy-btn" aria-label="Copy response">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                Copy
                            </button>
                        </div>
                        <pre><code>{
  "data": [
    {
      "id": "txn_abc123",
      "credits": 2000,
      "amount": 18.00,
      "currency": "USD",
      "status": "completed",
      "created_at": "2025-03-01T10:22:00Z"
    }
  ],
  "current_page": 1,
  "per_page": 20,
  "total": 5
}</code></pre>
                    </div>
                </div>
            </div>

            <div class="docs-info-box note">
                @if(app()->getLocale() === 'ar')
                    <p><strong>ملاحظة:</strong> عمليات شراء الرصيد تتم حصراً عبر واجهة الويب على
                    <a href="{{ url('/billing/plans') }}" class="docs-link">/billing/plans</a>.
                    لا تتوفر نقطة نهاية API لإتمام عمليات الشراء مباشرةً — يُعالَج الدفع عبر بوابة MyFatoorah.</p>
                @else
                    <p><strong>Note:</strong> Top-up purchases must be completed through the web UI at
                    <a href="{{ url('/billing/plans') }}" class="docs-link">/billing/plans</a>.
                    There is no API endpoint for initiating purchases — payment is processed via the MyFatoorah gateway.</p>
                @endif
            </div>
        </section>

        <!-- Section 5: Subscription Tiers -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>مستويات الاشتراك</h2>
                <p>
                    يقدّم LLM Resayil مستويات اشتراك متعددة تتيح حدوداً مختلفة للطلبات وإمكانية الوصول إلى النماذج.
                </p>
            @else
                <h2>Subscription Tiers</h2>
                <p>
                    LLM Resayil offers multiple subscription tiers with different request limits and model access.
                </p>
            @endif

            <div class="tier-cards-grid">
                <!-- Free -->
                <div class="tier-card">
                    <div class="tier-card-name"><x-tier-icon tier="free" :size="20" />Free</div>
                    <div class="tier-card-divider"></div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الرصيد @else Credits @endif
                        </span>
                        <span class="tier-card-value">1,000</span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الحد @else Limits @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') قياسية @else Standard @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') السعر @else Price @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') مجاني @else Free @endif
                        </span>
                    </div>
                </div>

                <!-- Starter -->
                <div class="tier-card">
                    <div class="tier-card-name"><x-tier-icon tier="starter" :size="20" />Starter</div>
                    <div class="tier-card-divider"></div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الرصيد @else Credits @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') شهري أعلى @else Higher monthly @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الحد @else Limits @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') موسّعة @else Expanded @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') النماذج @else Models @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') إضافية @else Additional @endif
                        </span>
                    </div>
                </div>

                <!-- Basic -->
                <div class="tier-card">
                    <div class="tier-card-name"><x-tier-icon tier="basic" :size="20" />Basic</div>
                    <div class="tier-card-divider"></div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الحد @else Limits @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') محسّنة @else Improved @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') المعالجة @else Processing @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') أولوية @else Priority @endif
                        </span>
                    </div>
                </div>

                <!-- Pro (recommended) -->
                <div class="tier-card recommended">
                    <div class="tier-card-badge">
                        @if(app()->getLocale() === 'ar') موصى به @else Recommended @endif
                    </div>
                    <div class="tier-card-name"><x-tier-icon tier="pro" :size="20" />Pro</div>
                    <div class="tier-card-divider"></div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الحد @else Limits @endif
                        </span>
                        <span class="tier-card-value highlight">
                            @if(app()->getLocale() === 'ar') عالية @else High @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') النماذج @else Models @endif
                        </span>
                        <span class="tier-card-value highlight">
                            @if(app()->getLocale() === 'ar') كاملة @else Full access @endif
                        </span>
                    </div>
                </div>

                <!-- Enterprise -->
                <div class="tier-card">
                    <div class="tier-card-name"><x-tier-icon tier="enterprise" :size="20" />Enterprise</div>
                    <div class="tier-card-divider"></div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الحد @else Limits @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') مخصصة @else Custom @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">
                            @if(app()->getLocale() === 'ar') الدعم @else Support @endif
                        </span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') مخصص @else Dedicated @endif
                        </span>
                    </div>
                    <div class="tier-card-row">
                        <span class="tier-card-label">API</span>
                        <span class="tier-card-value">
                            @if(app()->getLocale() === 'ar') مؤسسي @else Enterprise @endif
                        </span>
                    </div>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    اطّلع على صفحة <a href="{{ route('pricing') }}" class="docs-link">الأسعار</a>
                    للحصول على التفاصيل الكاملة حول كل مستوى.
                </p>
            @else
                <p>
                    See the <a href="{{ route('pricing') }}" class="docs-link">Pricing</a> page for full
                    details on each tier.
                </p>
            @endif
        </section>

        <!-- Section 6: Top-Up & How to Purchase -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>شراء الرصيد</h2>
                <p>
                    يمكنك شراء الرصيد في أي وقت عبر لوحة التحكم. تُعالَج المدفوعات فوراً
                    عبر بوابة <a href="https://myfatoorah.com" class="docs-link">MyFatoorah</a>.
                    الرصيد المشترى لا تنتهي صلاحيته.
                </p>
            @else
                <h2>Purchasing Credits</h2>
                <p>
                    Purchase credits anytime through your dashboard. Payments are processed immediately via the
                    <a href="https://myfatoorah.com" class="docs-link">MyFatoorah</a> payment gateway.
                    Purchased credits never expire.
                </p>
            @endif

            <!-- Top-up callout -->
            <div class="topup-callout">
                <div class="topup-callout-content">
                    <h4>
                        @if(app()->getLocale() === 'ar') شحن الرصيد الآن @else Top Up Your Credits @endif
                    </h4>
                    <p>
                        @if(app()->getLocale() === 'ar')
                            أضف رصيداً لحسابك عبر بوابة MyFatoorah. الرصيد المشترى لا ينتهي أبداً.
                        @else
                            Add credits to your account instantly via MyFatoorah. Purchased credits never expire.
                        @endif
                    </p>
                </div>
                <a href="{{ url('/billing/plans') }}" class="topup-callout-btn">
                    @if(app()->getLocale() === 'ar') اشترِ الآن &larr; @else View Plans &rarr; @endif
                </a>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>خطوات شراء الرصيد</h3>
                <ol style="list-style: decimal; margin-left: 2rem; color: var(--text-secondary); line-height: 2;">
                    <li>سجّل دخولك إلى <a href="{{ url('/dashboard') }}" class="docs-link">لوحة التحكم</a></li>
                    <li>انتقل إلى <strong>الفوترة</strong> &larr; <strong>خطط الاشتراك</strong></li>
                    <li>اختر حزمة الرصيد المناسبة</li>
                    <li>أكمل عملية الدفع عبر MyFatoorah</li>
                    <li>يُضاف الرصيد إلى حسابك فوراً بعد تأكيد الدفع</li>
                </ol>
            @else
                <h3>How to Top-Up</h3>
                <ol style="list-style: decimal; margin-left: 2rem; color: var(--text-secondary); line-height: 2;">
                    <li>Log in to your <a href="{{ url('/dashboard') }}" class="docs-link">LLM Resayil dashboard</a></li>
                    <li>Go to <strong>Billing</strong> &rarr; <strong>Plans</strong></li>
                    <li>Select the credit pack you want to purchase</li>
                    <li>Complete payment via MyFatoorah</li>
                    <li>Credits are added to your account immediately after payment confirmation</li>
                </ol>
            @endif

            <div class="docs-info-box">
                @if(app()->getLocale() === 'ar')
                    <p><strong>طرق الدفع:</strong> تقبل MyFatoorah طرق دفع متعددة تشمل بطاقات الائتمان والخصم
                    وخيارات الدفع المحلية حسب منطقتك. يُرجى زيارة موقعها للاطلاع على القائمة الكاملة.</p>
                @else
                    <p><strong>Payment Methods:</strong> MyFatoorah accepts multiple payment methods including
                    credit cards, debit cards, and local payment options depending on your region. See their
                    website for full details.</p>
                @endif
            </div>
        </section>

        <!-- Section 7: Viewing Usage -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>متابعة الاستخدام</h2>
                <p>
                    راقب استهلاك API ورصيدك من لوحة التحكم:
                </p>
                <ul>
                    <li><strong>رصيد الأرصدة الحالي:</strong> يعرض الأرصدة المتبقية في حسابك</li>
                    <li><strong>استخدام هذا الشهر:</strong> إجمالي الرموز والأرصدة المستهلكة في الفترة الحالية</li>
                    <li><strong>تفصيل التكلفة حسب النموذج:</strong> اعرف أي النماذج يستهلك أكثر الأرصدة</li>
                    <li><strong>سجل استدعاءات API:</strong> استعرض سجلات المعاملات وأنماط الاستخدام</li>
                </ul>
            @else
                <h2>Viewing Your Usage</h2>
                <p>
                    Track your API usage and credit consumption from your account dashboard:
                </p>
                <ul>
                    <li><strong>Current Credit Balance:</strong> Shows available credits remaining in your account</li>
                    <li><strong>Usage This Month:</strong> Total tokens and credits consumed in the current billing period</li>
                    <li><strong>Cost Breakdown by Model:</strong> See which models are consuming the most credits</li>
                    <li><strong>API Call History:</strong> View transaction logs and usage patterns</li>
                </ul>
            @endif
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>موارد ذات صلة</h2>
            @else
                <h2>Related Resources</h2>
            @endif
            <ul>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits & Quotas</a>
                    @if(app()->getLocale() === 'ar') — حدود الطلبات حسب المستوى @else — Request limits by tier @endif
                </li>
                <li><a href="{{ route('docs.models') }}" class="docs-link">Available Models</a>
                    @if(app()->getLocale() === 'ar') — يختلف استهلاك الرموز من نموذج لآخر @else — Token consumption varies by model @endif
                </li>
                <li><a href="{{ route('pricing') }}" class="docs-link">Pricing</a>
                    @if(app()->getLocale() === 'ar') — خطط الاشتراك والأسعار @else — Subscription plans and rates @endif
                </li>
                <li><a href="{{ url('/dashboard') }}" class="docs-link">Dashboard</a>
                    @if(app()->getLocale() === 'ar') — استعرض استخدامك ورصيد حسابك @else — View your usage and account balance @endif
                </li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>هل تريد معرفة المزيد؟</h3>
                <p>تعرّف على حدود الطلبات وإدارة الحصص.</p>
                <a href="{{ route('docs.rate-limits') }}">انتقل إلى حدود المعدل والحصص &larr;</a>
            @else
                <h3>Ready to learn more?</h3>
                <p>Understand request rate limits and quota management.</p>
                <a href="{{ route('docs.rate-limits') }}">Go to Rate Limits & Quotas &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Billing & Credits', 'url' => route('docs.billing')]
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

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Copy-to-clipboard helper ──────────────────────────────────────────
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(function () {
            btn.classList.add('copied');
            var orig = btn.innerHTML;
            btn.innerHTML = btn.innerHTML.replace('Copy', 'Copied!');
            setTimeout(function () {
                btn.classList.remove('copied');
                btn.innerHTML = orig;
            }, 1800);
        }).catch(function () {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        });
    }

    // Code block copy buttons
    document.querySelectorAll('.docs-copy-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.dataset.copy;
            if (!text) {
                var block = btn.closest('.docs-code-block');
                var code = block ? block.querySelector('code') : null;
                text = code ? code.textContent : '';
            }
            copyText(text, btn);
        });
    });

    // ── Collapsible API endpoint blocks ──────────────────────────────────
    document.querySelectorAll('.api-endpoint-header').forEach(function (header) {
        header.addEventListener('click', function () {
            var body = header.nextElementSibling;
            var isOpen = body && body.classList.contains('open');

            if (body) {
                body.classList.toggle('open', !isOpen);
            }
            header.classList.toggle('open', !isOpen);
            header.setAttribute('aria-expanded', String(!isOpen));
        });

        header.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                header.click();
            }
        });
    });
}());
</script>
@endpush

@endsection
