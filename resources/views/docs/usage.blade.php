@extends('layouts.app')

@section('title', 'Usage & Analytics — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page — Usage & Analytics ── */
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

    /* ── Credit Balance Widget ── */
    .balance-widget {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.75rem 2rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .balance-widget-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .balance-widget-icon svg {
        width: 26px;
        height: 26px;
        stroke: var(--gold);
        fill: none;
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .balance-widget-body { flex: 1; min-width: 160px; }

    .balance-widget-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.25rem;
    }

    .balance-widget-amount {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--gold);
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .balance-widget-sub {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }

    .balance-widget-endpoint {
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.8rem;
        color: var(--text-secondary);
        background: rgba(0,0,0,0.25);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.5rem 0.9rem;
        white-space: nowrap;
        flex-shrink: 0;
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
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
    }

    .formula-desc {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-family: inherit;
        white-space: nowrap;
    }

    .formula-op {
        font-size: 1.5rem;
        font-weight: 300;
        color: var(--text-muted);
        padding: 0 0.25rem;
        align-self: center;
    }

    .formula-eq {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gold);
        padding: 0 0.25rem;
        align-self: center;
    }

    .formula-result .formula-value {
        color: var(--gold);
        font-size: 1.4rem;
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
    .docs-code-block code .hl-key  { color: #79b8ff; }
    .docs-code-block code .hl-str  { color: #f97583; }
    .docs-code-block code .hl-num  { color: #ffab70; }

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

    /* Info/Tip Boxes */
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
    .docs-table tbody tr:nth-child(even) td { background: rgba(255,255,255,0.015); }

    .docs-table code {
        background: rgba(0,0,0,0.3);
        padding: 0.2rem 0.45rem;
        border-radius: 3px;
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.82rem;
        color: var(--gold);
    }

    /* Multiplier Pills */
    .mult-pill {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
        font-family: 'Monaco','Menlo',monospace;
        white-space: nowrap;
    }

    .mult-low  { background: rgba(34,197,94,0.15);  color: #22c55e; border: 1px solid rgba(34,197,94,0.3);  }
    .mult-mid  { background: rgba(212,175,55,0.15); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
    .mult-high { background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }

    /* Tips numbered list */
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
        counter-reset: tip-counter;
    }

    .tips-list li {
        counter-increment: tip-counter;
        padding: 1rem 1.25rem 1rem 3.5rem;
        position: relative;
        margin-bottom: 0.75rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-left: 4px solid var(--gold);
        border-radius: 6px;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .tips-list li:before {
        content: counter(tip-counter);
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.5rem;
        height: 1.5rem;
        background: rgba(212,175,55,0.15);
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gold);
        line-height: 1.5rem;
        text-align: center;
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

        .balance-widget { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .balance-widget-endpoint { white-space: normal; font-size: 0.75rem; }

        .formula-block { padding: 1.25rem 1rem; gap: 0.5rem; }
        .formula-value { font-size: 1.05rem; }
        .formula-op, .formula-eq { font-size: 1.2rem; }

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

        .tips-list li { padding-left: 3rem; }
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
                @if(app()->getLocale() === 'ar') الاستخدام &amp; الإحصائيات @else Usage &amp; Analytics @endif
            </span>
        </nav>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                الاستخدام <span>&amp; الإحصائيات</span>
            @else
                Usage <span>&amp; Analytics</span>
            @endif
        </h1>
        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                تعرّف على كيفية تتبّع استهلاكك من الرصيد، وفهم آلية احتساب الرموز (Tokens)، وتحسين طلباتك لخفض التكلفة.
            </p>
        @else
            <p class="docs-intro">
                Learn how to monitor your credit consumption, understand how tokens are counted, and optimize
                your requests to get the most out of your balance.
            </p>
        @endif

        <!-- Section 1: Check Your Balance -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    التحقق من رصيدك الحالي
                @else
                    Checking Your Current Balance
                @endif
            </h2>

            <!-- Balance Widget -->
            <div class="balance-widget">
                <div class="balance-widget-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div class="balance-widget-body">
                    <div class="balance-widget-label">
                        @if(app()->getLocale() === 'ar') الرصيد المتاح @else Available Balance @endif
                    </div>
                    <div class="balance-widget-amount">842.50</div>
                    <div class="balance-widget-sub">
                        @if(app()->getLocale() === 'ar') رصيدة — تُحدَّث فور كل طلب @else credits — updated after every request @endif
                    </div>
                </div>
                <div class="balance-widget-endpoint">GET /api/billing/subscription</div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    يمكنك الاستعلام عن رصيدك الحالي برمجيًا في أي وقت عبر نقطة النهاية التالية:
                </p>
            @else
                <p>
                    You can programmatically query your current credit balance at any time using the following endpoint:
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">HTTP Request</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-balance-req">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-balance-req">GET https://llm.resayil.io/api/billing/subscription
Authorization: Bearer YOUR_API_KEY</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>يعيد الطلب الناجح استجابةً بتنسيق JSON تحتوي على تفاصيل الاشتراك وإجمالي الرصيد المتبقي:</p>
            @else
                <p>A successful request returns a JSON response containing your subscription details and remaining credit balance:</p>
            @endif

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
  "credits": 842.50
}</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>حقل <strong>credits</strong> يمثّل رصيدك المتاح حاليًا. تُخصم الرصيدة مع كل طلب بحسب عدد الرموز والنموذج المستخدَم.</p>
            @else
                <p>The <strong>credits</strong> field represents your currently available balance. Credits are deducted with each request based on token count and the model used.</p>
            @endif
        </section>

        <!-- Section 2: How Tokens Are Counted -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    كيف تُحتسب الرموز (Tokens)؟
                @else
                    How Tokens Are Counted
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يقيس النظام استهلاكك بالرموز (Tokens). الرمز الواحد يعادل تقريبًا أربعة أحرف باللغة الإنجليزية، أو كلمة إلى كلمة ونصف.
                    كل طلب يحتسب نوعين من الرموز:
                </p>
            @else
                <p>
                    The system measures your consumption in tokens. One token is approximately four characters in English,
                    or roughly three-quarters of a word. Every request counts two types of tokens:
                </p>
            @endif
            <ul>
                <li>
                    <strong>prompt_tokens</strong>
                    @if(app()->getLocale() === 'ar')
                        — رموز الرسالة المُرسَلة إلى النموذج (بما في ذلك رسائل النظام والسياق السابق).
                    @else
                        — Tokens in the message(s) sent to the model, including system prompts and conversation history.
                    @endif
                </li>
                <li>
                    <strong>completion_tokens</strong>
                    @if(app()->getLocale() === 'ar')
                        — رموز الاستجابة التي ولّدها النموذج.
                    @else
                        — Tokens in the response generated by the model.
                    @endif
                </li>
                <li>
                    <strong>total_tokens</strong>
                    @if(app()->getLocale() === 'ar')
                        — المجموع: prompt_tokens + completion_tokens. هذا هو الرقم المستخدَم في احتساب الخصم.
                    @else
                        — The sum of prompt_tokens + completion_tokens. This is the figure used for credit deduction.
                    @endif
                </li>
            </ul>

            @if(app()->getLocale() === 'ar')
                <p>تظهر هذه القيم في حقل <strong>usage</strong> ضمن كل استجابة:</p>
            @else
                <p>These values appear in the <strong>usage</strong> field of every response:</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON — usage field</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-usage-field">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-usage-field">{
  "usage": {
    "prompt_tokens": 42,
    "completion_tokens": 118,
    "total_tokens": 160
  }
}</code>
                </div>
            </div>
        </section>

        <!-- Section 3: Credit Deduction Formula -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    صيغة خصم الرصيد
                @else
                    Credit Deduction Formula
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    لا تُخصم الرموز بمعدل ثابت لجميع النماذج. يعتمد النظام على معامل ضرب خاص بكل نموذج يعكس تكلفته التشغيلية:
                </p>
            @else
                <p>
                    Tokens are not deducted at a flat rate across all models. The system applies a per-model multiplier
                    that reflects its operational cost:
                </p>
            @endif

            <!-- Styled Formula Block -->
            <div class="formula-block">
                <div class="formula-part">
                    <span class="formula-value">total_tokens</span>
                    <span class="formula-desc">
                        @if(app()->getLocale() === 'ar') إجمالي الرموز @else token count @endif
                    </span>
                </div>
                <span class="formula-op">&times;</span>
                <div class="formula-part">
                    <span class="formula-value">model_multiplier</span>
                    <span class="formula-desc">
                        @if(app()->getLocale() === 'ar') معامل النموذج @else model multiplier @endif
                    </span>
                </div>
                <span class="formula-eq">=</span>
                <div class="formula-part formula-result">
                    <span class="formula-value">credits_deducted</span>
                    <span class="formula-desc">
                        @if(app()->getLocale() === 'ar') الرصيد المخصوم @else credits deducted @endif
                    </span>
                </div>
            </div>

            <div class="docs-table-wrap">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar')نوع النموذج@else Model Type@endif</th>
                            <th>@if(app()->getLocale() === 'ar')معامل الضرب@else Multiplier Range@endif</th>
                            <th>@if(app()->getLocale() === 'ar')أمثلة@else Examples@endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')نماذج محلية (Local)@else Local models@endif</td>
                            <td><span class="mult-pill mult-low">0.5&times; – 1.5&times;</span></td>
                            <td>Mistral, Llama 3, Neural Chat</td>
                        </tr>
                        <tr>
                            <td>@if(app()->getLocale() === 'ar')نماذج سحابية (Cloud)@else Cloud proxy models@endif</td>
                            <td><span class="mult-pill mult-high">2&times; – 3.5&times;</span></td>
                            <td>GPT-4o, Claude 3.5, Gemini Pro</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    <strong>مثال:</strong> إذا أرسلت طلبًا بـ 200 رمز إجماليًا باستخدام نموذج محلي بمعامل 1×، يُخصم 200 رصيدة.
                    نفس الطلب عبر نموذج سحابي بمعامل 3×، يُخصم 600 رصيدة.
                </p>
            @else
                <p>
                    <strong>Example:</strong> A request with 200 total tokens using a local model at 1&times; multiplier deducts
                    200 credits. The same request through a cloud model at 3&times; deducts 600 credits.
                </p>
            @endif

            <div class="docs-box docs-box-note">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>ملاحظة:</strong> يمكنك الاطلاع على معامل الضرب الدقيق لكل نموذج في صفحة <a href="{{ route('docs.models') }}" class="docs-link">النماذج المتاحة</a>.
                    @else
                        <strong>Note:</strong> You can find the exact multiplier for each model on the <a href="{{ route('docs.models') }}" class="docs-link">Available Models</a> page.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 4: Streaming vs Non-Streaming -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    البث الفوري مقابل الاستجابة الكاملة
                @else
                    Streaming vs Non-Streaming Token Counting
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يدعم LLM Resayil وضعَي الاستجابة: الكاملة (non-streaming) والبث الفوري (streaming عبر <strong>stream: true</strong>).
                    آلية احتساب الرموز متطابقة في كلا الوضعين — الفارق يكمن في طريقة استلام البيانات فحسب:
                </p>
            @else
                <p>
                    LLM Resayil supports both full-response (non-streaming) and real-time streaming (<strong>stream: true</strong>) modes.
                    Token counting is identical in both modes — the only difference is how you receive the data:
                </p>
            @endif
            <ul>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>غير بث (Non-streaming):</strong> تصل الاستجابة كاملةً في رسالة واحدة، وتتضمّن حقل <strong>usage</strong> مباشرةً.
                    @else
                        <strong>Non-streaming:</strong> The full response arrives in a single message and always includes the <strong>usage</strong> field.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>بث فوري (Streaming):</strong> تصل الاستجابة على شكل أجزاء (chunks) عبر Server-Sent Events. حقل <strong>usage</strong> يظهر في الجزء الأخير ([DONE]).
                    @else
                        <strong>Streaming:</strong> The response arrives as incremental chunks via Server-Sent Events. The <strong>usage</strong> field appears in the final chunk before the <code>[DONE]</code> signal.
                    @endif
                </li>
            </ul>
            @if(app()->getLocale() === 'ar')
                <p>
                    الخصم من الرصيد يتمّ بعد اكتمال الاستجابة بالكامل في كلتا الحالتين. لا يتأثر إجمالي الرموز المحتسبة بطريقة الاستلام.
                </p>
            @else
                <p>
                    Credit deduction occurs after the full response is complete in both cases. The total token count
                    is not affected by the delivery method.
                </p>
            @endif
        </section>

        <!-- Section 5: API Key Last Used -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    تتبُّع آخر استخدام لمفتاح API
                @else
                    API Key Last-Used Tracking
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يحتفظ النظام بطابع زمني (<strong>last_used_at</strong>) لكل مفتاح API، يُحدَّث بدقة كل دقيقة لتقليل الضغط على قاعدة البيانات.
                    يمكنك مشاهدة هذه القيمة في صفحة <strong>مفاتيح API</strong> في لوحة التحكم.
                </p>
                <p>
                    يُفيد هذا المؤشر في رصد النشاط غير المعتاد أو التحقق من أن التطبيق يستخدم المفتاح الصحيح. إن رأيت مفتاحًا لم يُستخدَم منذ فترة طويلة، يُنصح بإلغائه وإنشاء مفتاح جديد.
                </p>
            @else
                <p>
                    The system maintains a <strong>last_used_at</strong> timestamp for each API key, updated with per-minute
                    granularity to minimise database write pressure. You can see this value on the <strong>API Keys</strong>
                    page in your dashboard.
                </p>
                <p>
                    This indicator is useful for spotting unusual activity or verifying that your application is using
                    the correct key. If you see a key that has not been used in a long time, consider revoking it and
                    generating a fresh one.
                </p>
            @endif
        </section>

        <!-- Section 6: Optimization Tips -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    نصائح لتحسين استهلاك الرصيد
                @else
                    Tips for Optimizing Credit Usage
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>اتبع هذه الممارسات لخفض تكلفة طلباتك دون التضحية بالجودة:</p>
            @else
                <p>Follow these practices to reduce your per-request cost without sacrificing quality:</p>
            @endif

            <ol class="tips-list">
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>اختصر رسائل النظام:</strong> رسائل النظام الطويلة تُحتسب في كل طلب. احرص على إيجازها مع الحفاظ على وضوحها.
                    @else
                        <strong>Keep system prompts concise:</strong> System messages are counted in every request. Write them clearly but briefly.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>استخدم نماذج محلية للمهام البسيطة:</strong> النماذج المحلية بمعامل 0.5×–1.5× مناسبة لمعظم المهام العامة وتكلفتها أقل بكثير.
                    @else
                        <strong>Use local models for simple tasks:</strong> Local models with a 0.5&times;–1.5&times; multiplier are well-suited for most general tasks and cost significantly less than cloud models.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>حدّ من max_tokens:</strong> ضبط قيمة <strong>max_tokens</strong> يمنع النموذج من توليد استجابات أطول من اللازم.
                    @else
                        <strong>Limit max_tokens:</strong> Setting a sensible <strong>max_tokens</strong> value prevents the model from generating unnecessarily long responses.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>قلّل من سياق المحادثة:</strong> كلما أرسلت رسائل تاريخية أكثر في كل طلب، ارتفعت تكلفة prompt_tokens. احذف الرسائل القديمة غير الضرورية.
                    @else
                        <strong>Trim conversation history:</strong> The more historical messages you include in each request, the higher your prompt_tokens cost. Remove older turns that are no longer relevant.
                    @endif
                </li>
                <li>
                    @if(app()->getLocale() === 'ar')
                        <strong>احجز النماذج السحابية للمهام المعقدة:</strong> استخدم نماذج GPT-4o أو Claude فقط عندما تتطلب المهمة قدرات استدلال متقدمة.
                    @else
                        <strong>Reserve cloud models for complex tasks:</strong> Use GPT-4o or Claude-class models only when the task genuinely demands advanced reasoning capabilities.
                    @endif
                </li>
            </ol>

            <div class="docs-box docs-box-tip">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>نصيحة:</strong> جرّب نماذج مختلفة على مهامك وقارن جودة الاستجابة بالتكلفة. في كثير من الحالات، يؤدي نموذج محلي بسرعة أعلى وتكلفة أقل نفس المهمة بشكل ممتاز.
                    @else
                        <strong>Tip:</strong> Benchmark different models against your tasks and compare response quality to cost. In many cases a faster, cheaper local model performs the job just as well.
                    @endif
                </p>
            </div>
        </section>

        <!-- Dashboard Usage History CTA -->
        @auth
        <div class="docs-box docs-box-tip" style="margin-bottom: 2rem;">
            <div class="docs-box-icon">
                <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <p>
                @if(app()->getLocale() === 'ar')
                    <strong>سجل استخدامك الكامل متاح في لوحة التحكم.</strong>
                    يمكنك مشاهدة جميع طلبات API الأخيرة — مع تفاصيل الرموز والرصيد المخصوم والنموذج المستخدَم — في
                    <a href="{{ url('/dashboard#usage') }}" class="docs-link">لوحة التحكم ← قسم سجل الاستخدام</a>.
                @else
                    <strong>Your full usage history is available on the dashboard.</strong>
                    View all recent API calls — with token breakdown, credits deducted, and model used — in the
                    <a href="{{ url('/dashboard#usage') }}" class="docs-link">Dashboard &rarr; Usage History</a> section.
                @endif
            </p>
        </div>
        @endauth

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>هل تحتاج إلى رصيد إضافي؟</h3>
                <p>تعرّف على طريقة شراء حزم الرصيد الإضافية (Top-Up).</p>
                <a href="{{ route('docs.topup') }}">الانتقال إلى صفحة الشحن &rarr;</a>
            @else
                <h3>Need more credits?</h3>
                <p>Learn how to purchase additional top-up credit packs.</p>
                <a href="{{ route('docs.topup') }}">Go to Top-Up Credits &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Docs', 'url' => route('docs.index')],
    ['name' => 'Usage & Analytics', 'url' => route('docs.usage')]
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
