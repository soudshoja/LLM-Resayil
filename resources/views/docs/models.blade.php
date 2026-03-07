@extends('layouts.app')

@section('title', 'Available Models — API Documentation')

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

    /* Section Jump Links */
    .docs-jump-links {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }

    .docs-jump-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-secondary);
        transition: border-color 0.2s, color 0.2s, background 0.2s;
        cursor: pointer;
    }

    .docs-jump-link:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: rgba(212,175,55,0.07);
    }

    .docs-jump-link.active {
        border-color: var(--gold);
        color: var(--gold);
        background: rgba(212,175,55,0.12);
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
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .docs-section h2 .section-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .docs-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 1.75rem 0 0.75rem;
        color: var(--text-primary);
        padding-bottom: 0.4rem;
        border-bottom: 1px solid var(--border);
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

    /* ── Filter / Search Bar ── */
    .model-search-bar {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .model-search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .model-search-input-wrap svg {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: var(--text-muted);
        pointer-events: none;
    }

    .model-search-input {
        width: 100%;
        padding: 0.55rem 0.75rem 0.55rem 2.25rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    .model-search-input::placeholder { color: var(--text-muted); }
    .model-search-input:focus { border-color: var(--gold); }

    .model-filter-btns {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .model-filter-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid var(--border);
        background: transparent;
        cursor: pointer;
        transition: all 0.18s;
        font-family: inherit;
        color: var(--text-secondary);
    }

    .model-filter-btn[data-filter="all"]      { border-color: var(--border); color: var(--text-secondary); }
    .model-filter-btn[data-filter="chat"]     { border-color: rgba(96,165,250,0.4); color: #60a5fa; }
    .model-filter-btn[data-filter="code"]     { border-color: rgba(52,211,153,0.4); color: #34d399; }
    .model-filter-btn[data-filter="vision"]   { border-color: rgba(167,139,250,0.4); color: #a78bfa; }
    .model-filter-btn[data-filter="embedding"]{ border-color: rgba(251,191,36,0.4);  color: #fbbf24; }
    .model-filter-btn[data-filter="thinking"] { border-color: rgba(251,113,133,0.4); color: #fb7185; }
    .model-filter-btn[data-filter="tools"]    { border-color: rgba(148,163,184,0.4); color: #94a3b8; }

    .model-filter-btn.active,
    .model-filter-btn:hover {
        background: rgba(212,175,55,0.1);
        border-color: var(--gold);
        color: var(--gold);
    }

    .model-filter-btn[data-filter="chat"].active,
    .model-filter-btn[data-filter="chat"]:hover     { background: rgba(96,165,250,0.12); border-color: #60a5fa; color: #60a5fa; }
    .model-filter-btn[data-filter="code"].active,
    .model-filter-btn[data-filter="code"]:hover     { background: rgba(52,211,153,0.12); border-color: #34d399; color: #34d399; }
    .model-filter-btn[data-filter="vision"].active,
    .model-filter-btn[data-filter="vision"]:hover   { background: rgba(167,139,250,0.12); border-color: #a78bfa; color: #a78bfa; }
    .model-filter-btn[data-filter="embedding"].active,
    .model-filter-btn[data-filter="embedding"]:hover{ background: rgba(251,191,36,0.12); border-color: #fbbf24; color: #fbbf24; }
    .model-filter-btn[data-filter="thinking"].active,
    .model-filter-btn[data-filter="thinking"]:hover { background: rgba(251,113,133,0.12); border-color: #fb7185; color: #fb7185; }
    .model-filter-btn[data-filter="tools"].active,
    .model-filter-btn[data-filter="tools"]:hover    { background: rgba(148,163,184,0.12); border-color: #94a3b8; color: #94a3b8; }

    .model-count-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        white-space: nowrap;
        align-self: center;
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
    .lang-http    { background: rgba(96,165,250,0.15);  color: #60a5fa; }
    .lang-formula { background: rgba(212,175,55,0.15);  color: var(--gold); }
    .lang-example { background: rgba(167,139,250,0.15); color: #a78bfa; }

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

    .docs-copy-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .docs-copy-btn.copied {
        border-color: #34d399;
        color: #34d399;
    }

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

    /* Info / Warning / Note Boxes */
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

    /* Link */
    .docs-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .docs-link:hover { opacity: 0.8; }

    /* ── Model Tables ── */
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
        min-width: 560px;
    }

    .docs-table thead {
        position: sticky;
        top: 0;
        z-index: 2;
    }

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
        transition: background 0.15s;
        vertical-align: middle;
    }

    .docs-table tbody tr:nth-child(odd) td {
        background: rgba(255,255,255,0.015);
    }

    .docs-table tbody tr:nth-child(even) td {
        background: transparent;
    }

    .docs-table tbody tr:last-child td {
        border-bottom: none;
    }

    .docs-table tbody tr:hover td {
        background: rgba(212,175,55,0.055);
        cursor: default;
    }

    /* Model ID cell with copy-on-hover */
    .model-id-cell {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .model-id-cell code {
        background: rgba(0, 0, 0, 0.25);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Courier New', monospace;
        color: #a0d468;
        font-size: 0.82rem;
        border: 1px solid rgba(255,255,255,0.06);
    }

    .model-copy-btn {
        opacity: 0;
        display: inline-flex;
        align-items: center;
        padding: 0.15rem 0.4rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 4px;
        color: var(--text-muted);
        font-size: 0.68rem;
        cursor: pointer;
        transition: opacity 0.15s, border-color 0.15s, color 0.15s;
        white-space: nowrap;
        font-family: inherit;
    }

    .docs-table tbody tr:hover .model-copy-btn {
        opacity: 1;
    }

    .model-copy-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .model-copy-btn.copied {
        border-color: #34d399;
        color: #34d399;
    }

    /* No results row */
    .no-results-row td {
        text-align: center;
        color: var(--text-muted);
        font-style: italic;
        padding: 2rem !important;
        background: transparent !important;
    }

    /* ── Category Badges ── */
    .badge {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .badge-chat      { background: rgba(96,165,250,0.15);  color: #60a5fa;  border: 1px solid rgba(96,165,250,0.25); }
    .badge-code      { background: rgba(52,211,153,0.15);  color: #34d399;  border: 1px solid rgba(52,211,153,0.25); }
    .badge-vision    { background: rgba(167,139,250,0.15); color: #a78bfa;  border: 1px solid rgba(167,139,250,0.25); }
    .badge-embedding { background: rgba(148,163,184,0.15); color: #94a3b8;  border: 1px solid rgba(148,163,184,0.25); }
    .badge-thinking  { background: rgba(212,175,55,0.15);  color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
    .badge-tools     { background: rgba(251,113,133,0.12); color: #fb7185;  border: 1px solid rgba(251,113,133,0.22); }

    /* ── Multiplier Chips ── */
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

    /* Multiplier reference table — legacy (credit system section) */
    .docs-table.multiplier-ref td:first-child { width: 100px; }

    @media (max-width: 768px) {
        .docs-title { font-size: 1.75rem; }
        .docs-section h2 { font-size: 1.35rem; }
        .docs-jump-links { gap: 0.5rem; }
        .model-search-bar { gap: 0.5rem; }
        .docs-table-wrap {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
    }
</style>
@endpush

@section('content')
<div class="docs-page">
    <div class="docs-content">

        <!-- Breadcrumb -->
        <div class="docs-breadcrumb">
            <a href="{{ route('welcome') }}">
                @if(app()->getLocale() === 'ar') الرئيسية @else Home @endif
            </a>
            <span>&rarr;</span>
            <a href="{{ route('docs.index') }}">
                @if(app()->getLocale() === 'ar') التوثيق @else Documentation @endif
            </a>
            <span>&rarr;</span>
            <span>
                @if(app()->getLocale() === 'ar') النماذج المتاحة @else Available Models @endif
            </span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                النماذج <span>المتاحة</span>
            @else
                Available <span>Models</span>
            @endif
        </h1>

        <p class="docs-intro">
            @if(app()->getLocale() === 'ar')
                يوفر LLM Resayil وصولاً إلى 46 نموذجاً من النماذج اللغوية الكبيرة — 15 نموذجاً محلياً يعمل على بنيتنا التحتية الخاصة، و31 نموذجاً سحابياً عالي الأداء. اختر النموذج المناسب لحالة الاستخدام لديك بناءً على السرعة والدقة ومعامل الاستهلاك.
            @else
                LLM Resayil provides access to 46 large language models — 15 local models running on our own infrastructure and 31 high-performance cloud models. Choose the right model for your use case based on speed, accuracy, and credit multiplier.
            @endif
        </p>

        <!-- Section Jump Links -->
        <div class="docs-jump-links">
            <a href="#section-multipliers" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') نظام المعاملات @else Credit Multipliers @endif
            </a>
            <a href="#section-local" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') النماذج المحلية (15) @else Local Models (15) @endif
            </a>
            <a href="#section-cloud" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') النماذج السحابية (31) @else Cloud Models (31) @endif
            </a>
            <a href="#section-api" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') واجهة API @else Models API @endif
            </a>
            <a href="#section-sending" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') إرسال الطلبات @else Sending Requests @endif
            </a>
        </div>

        <!-- Section 1: Credit Multiplier System -->
        <section class="docs-section" id="section-multipliers">
            <h2>
                @if(app()->getLocale() === 'ar') نظام معامل الأرصدة @else Credit Multiplier System @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    كل طلب يستهلك رصيداً بناءً على عدد الرموز المُعالَجة مضروباً في معامل النموذج. المعامل الأساسي هو <strong>1.0×</strong>، وتتراوح المعاملات بين <strong>0.5×</strong> للنماذج الخفيفة السريعة وحتى <strong>3.5×</strong> للنماذج الضخمة عالية القدرة.
                @else
                    Every request consumes credits equal to the number of tokens processed multiplied by the model's credit multiplier. The base rate is <strong>1.0×</strong>, ranging from <strong>0.5×</strong> for lightweight fast models up to <strong>3.5×</strong> for large, high-capability models.
                @endif
            </p>

            <div class="docs-table-wrap">
                <table class="docs-table multiplier-ref">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') النوع @else Tier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الوصف @else Description @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محلي — خفيف @else Local — Lightweight @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج صغيرة وسريعة جداً، مثالية للمهام البسيطة @else Small, ultra-fast models ideal for simple tasks @endif</td>
                        </tr>
                        <tr>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') سحابي — تضمين @else Cloud — Embedding @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج التضمين السحابية خفيفة الاستهلاك @else Lightweight cloud embedding models @endif</td>
                        </tr>
                        <tr>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محلي — متوسط @else Local — Standard @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج محلية متوسطة الحجم وعالية الكفاءة @else Mid-size local models with strong performance @endif</td>
                        </tr>
                        <tr>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') سحابي — متوسط @else Cloud — Mid @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج سحابية متوسطة الحجم متوازنة بين الأداء والتكلفة @else Balanced cloud models for quality and cost @endif</td>
                        </tr>
                        <tr>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') سحابي — كبير @else Cloud — Large @endif</td>
                            <td>@if(app()->getLocale() === 'ar') النماذج الأعلى أداءً والأكبر حجماً @else Highest-performance, largest-scale models @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="docs-info-box">
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>مثال:</strong> إذا أرسلت طلباً يستهلك 1,000 رمز باستخدام نموذج بمعامل 1.5×، فسيُخصم 1,500 رصيد. يمكنك مراقبة الاستهلاك الدقيق من خلال حقل <code>usage</code> في كل استجابة.
                    @else
                        <strong>Example:</strong> A request consuming 1,000 tokens on a 1.5× model deducts 1,500 credits. Monitor exact consumption via the <code>usage</code> field in every response.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 2: Local Models -->
        <section class="docs-section" id="section-local">
            <h2>
                @if(app()->getLocale() === 'ar') النماذج المحلية (15 نموذجاً) @else Local Models (15) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    تعمل هذه النماذج مباشرةً على خوادمنا المخصصة، مما يضمن زمن استجابة منخفضاً وخصوصية تامة للبيانات. المعامل الأساسي هو <strong>1 رصيد لكل رمز</strong> مع تعديلات بحسب حجم النموذج.
                @else
                    These models run directly on our dedicated hardware, ensuring low latency and full data privacy. The base rate is <strong>1 credit per token</strong> adjusted by the model multiplier.
                @endif
            </p>

            <!-- Filter bar (applies to all model tables) -->
            <div class="model-search-bar">
                <div class="model-search-input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="modelSearchInput" class="model-search-input"
                        placeholder="@if(app()->getLocale() === 'ar') ابحث عن نموذج... @else Filter models by name... @endif"
                        aria-label="Filter models">
                </div>
                <div class="model-filter-btns" role="group" aria-label="Filter by category">
                    <button class="model-filter-btn active" data-filter="all">@if(app()->getLocale() === 'ar') الكل @else All @endif</button>
                    <button class="model-filter-btn" data-filter="chat">Chat</button>
                    <button class="model-filter-btn" data-filter="code">Code</button>
                    <button class="model-filter-btn" data-filter="vision">Vision</button>
                    <button class="model-filter-btn" data-filter="embedding">Embedding</button>
                    <button class="model-filter-btn" data-filter="thinking">Thinking</button>
                    <button class="model-filter-btn" data-filter="tools">Tools</button>
                </div>
                <span class="model-count-label" id="modelCountLabel"></span>
            </div>

            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="localModelsTable">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') معرّف النموذج @else Model ID @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الفئة @else Category @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الحجم @else Size @endif</th>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الاستخدام الأمثل @else Best For @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3.2:3b</code><button class="model-copy-btn" data-model="llama3.2:3b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>3B</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة، سرعة عالية @else General chat, fast @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>smollm2:135m</code><button class="model-copy-btn" data-model="smollm2:135m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>135M</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') فائق السرعة، المهام الخفيفة @else Ultra-fast, lightweight tasks @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>phi3.5-mini:3.8b</code><button class="model-copy-btn" data-model="phi3.5-mini:3.8b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>3.8B</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') سريع وكفؤ، نافذة سياق 128K @else Fast, efficient, 128K context @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>starcoder2:3b</code><button class="model-copy-btn" data-model="starcoder2:3b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>3B</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') توليد الكود، سريع @else Code generation, fast @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>codellama:7b</code><button class="model-copy-btn" data-model="codellama:7b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>7B</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') برمجة، شرح الكود @else Coding, code explanation @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>nomic-embed-text</code><button class="model-copy-btn" data-model="nomic-embed-text" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>110M</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين النصوص @else Text embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>all-minilm</code><button class="model-copy-btn" data-model="all-minilm" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>23M</td>
                            <td><span class="multiplier mult-05">0.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين خفيف الوزن @else Lightweight embeddings @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>qwen2.5-coder:14b</code><button class="model-copy-btn" data-model="qwen2.5-coder:14b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>14B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') توليد الكود المتقدم @else Advanced code generation @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mistral-small3.2:24b</code><button class="model-copy-btn" data-model="mistral-small3.2:24b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>24B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة متوازنة، أداء عالٍ @else Balanced chat, high performance @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>gemma2:9b</code><button class="model-copy-btn" data-model="gemma2:9b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>9B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>deepseek-coder:6.7b</code><button class="model-copy-btn" data-model="deepseek-coder:6.7b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>6.7B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') برمجة ومهام تقنية @else Coding and technical tasks @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3.1:8b</code><button class="model-copy-btn" data-model="llama3.1:8b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>8B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة متعددة الأغراض @else Versatile general chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mistral-7b</code><button class="model-copy-btn" data-model="mistral-7b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>7B</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>bge-m3</code><button class="model-copy-btn" data-model="bge-m3" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>580M</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين متعدد التمثيلات @else Multi-representation embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>snowflake-arctic-embed</code><button class="model-copy-btn" data-model="snowflake-arctic-embed" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>137M</td>
                            <td><span class="multiplier mult-15">1.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين عالي الجودة @else High-quality embeddings @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 3: Cloud Models -->
        <section class="docs-section" id="section-cloud">
            <h2>
                @if(app()->getLocale() === 'ar') النماذج السحابية (31 نموذجاً) @else Cloud Models (31) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    النماذج السحابية تُعالَج عبر خوادم GPU متخصصة بعيدة، مما يتيح الوصول إلى نماذج ضخمة بعشرات أو مئات المليارات من المعاملات. تبدأ معاملاتها من <strong>2.5×</strong> وتصل إلى <strong>3.5×</strong>.
                @else
                    Cloud models are processed via remote specialized GPU servers, enabling access to models with tens or hundreds of billions of parameters. Multipliers range from <strong>2.5×</strong> to <strong>3.5×</strong>.
                @endif
            </p>

            <h3>@if(app()->getLocale() === 'ar') نماذج المحادثة والتفكير @else Chat &amp; Thinking Models @endif</h3>
            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="cloudChatTable">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') معرّف النموذج @else Model ID @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الفئة @else Category @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الحجم @else Size @endif</th>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الاستخدام الأمثل @else Best For @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>gpt-oss:20b</code><button class="model-copy-btn" data-model="gpt-oss:20b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>20B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General purpose @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mistral-large:24b</code><button class="model-copy-btn" data-model="mistral-large:24b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>24B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عالية الجودة @else High-quality chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mistral-nemo:12b</code><button class="model-copy-btn" data-model="mistral-nemo:12b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>12B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة متوازنة @else Balanced chat @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>qwen3-30b</code><button class="model-copy-btn" data-model="qwen3-30b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>30B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تفكير واستدلال @else Reasoning and thinking @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3.2:70b</code><button class="model-copy-btn" data-model="llama3.2:70b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>70B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عالية الأداء @else High-performance chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3.1:70b</code><button class="model-copy-btn" data-model="llama3.1:70b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>70B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة متقدمة @else Advanced general chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3.1:405b</code><button class="model-copy-btn" data-model="llama3.1:405b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>405B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') أكبر نماذج Llama @else Largest Llama model @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>llama3-gradient:70b</code><button class="model-copy-btn" data-model="llama3-gradient:70b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>70B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') سياق ممتد (262K رمز) @else Extended context (262K tokens) @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>gemma2:27b</code><button class="model-copy-btn" data-model="gemma2:27b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>27B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mixtral:8x7b</code><button class="model-copy-btn" data-model="mixtral:8x7b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>8×7B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') بنية MoE متوازنة @else Balanced MoE architecture @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>mixtral:8x22b</code><button class="model-copy-btn" data-model="mixtral:8x22b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>8×22B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') MoE الأداء العالي @else High-performance MoE @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>qwen2:72b</code><button class="model-copy-btn" data-model="qwen2:72b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>72B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة متعددة اللغات @else Multilingual chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>qwen2.5:32b</code><button class="model-copy-btn" data-model="qwen2.5:32b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>32B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                        </tr>
                        <tr data-category="chat">
                            <td><div class="model-id-cell"><code>yi:34b</code><button class="model-copy-btn" data-model="yi:34b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-chat">chat</span></td>
                            <td>34B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة متعددة اللغات @else Multilingual chat @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>qwen3.5:397b</code><button class="model-copy-btn" data-model="qwen3.5:397b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>397B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تفكير، الأكبر حجماً @else Thinking, largest model @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>deepseek-v3.1:671b</code><button class="model-copy-btn" data-model="deepseek-v3.1:671b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>671B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تفكير عميق واستدلال @else Deep thinking and reasoning @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>deepseek-v3.2</code><button class="model-copy-btn" data-model="deepseek-v3.2" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>671B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') أحدث إصدار DeepSeek @else Latest DeepSeek release @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>deepseek-v2.5</code><button class="model-copy-btn" data-model="deepseek-v2.5" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>236B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تفكير واستدلال MoE @else MoE thinking and reasoning @endif</td>
                        </tr>
                        <tr data-category="thinking">
                            <td><div class="model-id-cell"><code>deepseek-chat:671b</code><button class="model-copy-btn" data-model="deepseek-chat:671b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-thinking">thinking</span></td>
                            <td>671B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') محادثة وتفكير متقدم @else Advanced chat and reasoning @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3>@if(app()->getLocale() === 'ar') نماذج الرؤية @else Vision Models @endif</h3>
            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="cloudVisionTable">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') معرّف النموذج @else Model ID @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الفئة @else Category @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الحجم @else Size @endif</th>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الاستخدام الأمثل @else Best For @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-category="vision">
                            <td><div class="model-id-cell"><code>glm-4.7-flash</code><button class="model-copy-btn" data-model="glm-4.7-flash" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-vision">vision</span></td>
                            <td>12B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') رؤية متعددة الوسائط @else Multimodal vision @endif</td>
                        </tr>
                        <tr data-category="vision">
                            <td><div class="model-id-cell"><code>llama3.2:11b</code><button class="model-copy-btn" data-model="llama3.2:11b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-vision">vision</span></td>
                            <td>11B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') رؤية ومحادثة @else Vision and chat @endif</td>
                        </tr>
                        <tr data-category="vision">
                            <td><div class="model-id-cell"><code>Qwen3-VL-32B</code><button class="model-copy-btn" data-model="Qwen3-VL-32B" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-vision">vision</span></td>
                            <td>32B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') رؤية متقدمة @else Advanced vision-language @endif</td>
                        </tr>
                        <tr data-category="vision">
                            <td><div class="model-id-cell"><code>qwen3-vl:235b-instruct</code><button class="model-copy-btn" data-model="qwen3-vl:235b-instruct" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-vision">vision</span></td>
                            <td>235B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') رؤية، النموذج الرائد @else Vision, flagship model @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3>@if(app()->getLocale() === 'ar') نماذج البرمجة @else Code Models @endif</h3>
            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="cloudCodeTable">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') معرّف النموذج @else Model ID @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الفئة @else Category @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الحجم @else Size @endif</th>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الاستخدام الأمثل @else Best For @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>codestral:22b</code><button class="model-copy-btn" data-model="codestral:22b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>22B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') توليد الكود @else Code generation @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>deepseek-coder:33b</code><button class="model-copy-btn" data-model="deepseek-coder:33b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>33B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') برمجة متقدمة @else Advanced coding @endif</td>
                        </tr>
                        <tr data-category="code">
                            <td><div class="model-id-cell"><code>devstral-2:123b</code><button class="model-copy-btn" data-model="devstral-2:123b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-code">code</span></td>
                            <td>123B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') برمجة، نموذج كبير @else Coding, large model @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3>@if(app()->getLocale() === 'ar') نماذج الأدوات والتضمين السحابي @else Tools &amp; Cloud Embedding Models @endif</h3>
            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="cloudToolsTable">
                    <thead>
                        <tr>
                            <th>@if(app()->getLocale() === 'ar') معرّف النموذج @else Model ID @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الفئة @else Category @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الحجم @else Size @endif</th>
                            <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                            <th>@if(app()->getLocale() === 'ar') الاستخدام الأمثل @else Best For @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-category="tools">
                            <td><div class="model-id-cell"><code>firefunction-v2:18b</code><button class="model-copy-btn" data-model="firefunction-v2:18b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-tools">tools</span></td>
                            <td>18B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') استدعاء الدوال @else Function calling @endif</td>
                        </tr>
                        <tr data-category="tools">
                            <td><div class="model-id-cell"><code>command-r:35b</code><button class="model-copy-btn" data-model="command-r:35b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-tools">tools</span></td>
                            <td>35B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') RAG والأدوات @else RAG and tool use @endif</td>
                        </tr>
                        <tr data-category="tools">
                            <td><div class="model-id-cell"><code>command-r-plus:104b</code><button class="model-copy-btn" data-model="command-r-plus:104b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-tools">tools</span></td>
                            <td>104B</td>
                            <td><span class="multiplier mult-35">3.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') RAG المتقدم @else Advanced RAG @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>nomic-embed:27m</code><button class="model-copy-btn" data-model="nomic-embed:27m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>27M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين النصوص @else Text embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>gte-qwen:7m</code><button class="model-copy-btn" data-model="gte-qwen:7m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>7M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين سريع @else Fast embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>nvidia-embed:1b</code><button class="model-copy-btn" data-model="nvidia-embed:1b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>1B</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين NeMo @else NeMo embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>all-minilm-l6:22m</code><button class="model-copy-btn" data-model="all-minilm-l6:22m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>22M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين خفيف @else Lightweight embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>gte-base:110m</code><button class="model-copy-btn" data-model="gte-base:110m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>110M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين قياسي @else Standard embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>bge-small:8m</code><button class="model-copy-btn" data-model="bge-small:8m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>8M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين صغير @else Small embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>minilm-l12:39m</code><button class="model-copy-btn" data-model="minilm-l12:39m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>39M</td>
                            <td><span class="multiplier mult-10">1.0×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين MiniLM @else MiniLM embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>bge-large:335m</code><button class="model-copy-btn" data-model="bge-large:335m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>335M</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين عالي الجودة @else High-quality embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>e5-mistral:7b</code><button class="model-copy-btn" data-model="e5-mistral:7b" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>7B</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين متقدم @else Advanced embeddings @endif</td>
                        </tr>
                        <tr data-category="embedding">
                            <td><div class="model-id-cell"><code>snowflake-arctic-embed-l:335m</code><button class="model-copy-btn" data-model="snowflake-arctic-embed-l:335m" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-embedding">embedding</span></td>
                            <td>335M</td>
                            <td><span class="multiplier mult-25">2.5×</span></td>
                            <td>@if(app()->getLocale() === 'ar') تضمين Snowflake الكبير @else Snowflake large embeddings @endif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 4: API Endpoint -->
        <section class="docs-section" id="section-api">
            <h2>
                @if(app()->getLocale() === 'ar') واجهة برمجة النماذج @else Models API Endpoint @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    يمكنك الحصول على القائمة الكاملة للنماذج المتاحة عبر نقطة النهاية التالية المتوافقة مع OpenAI:
                @else
                    Retrieve the full list of available models via the following OpenAI-compatible endpoint:
                @endif
            </p>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-http">GET</span>
                    <button class="docs-copy-btn" data-copy="GET https://llm.resayil.io/v1/models" aria-label="Copy endpoint">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>GET https://llm.resayil.io/v1/models</code></pre>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-bash">bash</span>
                    <button class="docs-copy-btn" data-copy='curl https://llm.resayil.io/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"' aria-label="Copy code">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>curl https://llm.resayil.io/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-json">json</span>
                    <button class="docs-copy-btn" aria-label="Copy response">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>{
  "object": "list",
  "data": [
    {
      "id": "llama3.2:3b",
      "object": "model",
      "created": 1700000000,
      "owned_by": "llm-resayil"
    },
    {
      "id": "qwen3.5:397b",
      "object": "model",
      "created": 1700000000,
      "owned_by": "llm-resayil"
    }
  ]
}</code></pre>
            </div>

            <div class="docs-info-box note">
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>ملاحظة:</strong> يمكن أيضاً الوصول إلى هذه النقطة عبر المسار البديل <code>GET /api/v1/models</code>. كلا المسارَين يعيدان نفس القائمة.
                    @else
                        <strong>Note:</strong> The endpoint is also accessible at the alternative path <code>GET /api/v1/models</code>. Both paths return the same list.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 5: Sending Requests -->
        <section class="docs-section" id="section-sending">
            <h2>
                @if(app()->getLocale() === 'ar') إرسال الطلبات @else Sending Requests @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    جميع النماذج تستخدم نقطة نهاية واحدة متوافقة مع OpenAI. ما عليك سوى تغيير حقل <code>model</code> لاستخدام نموذج مختلف:
                @else
                    All models share a single OpenAI-compatible endpoint. Simply change the <code>model</code> field to switch models:
                @endif
            </p>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-http">POST</span>
                    <button class="docs-copy-btn" data-copy="POST https://llm.resayil.io/api/v1/chat/completions" aria-label="Copy endpoint">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>POST https://llm.resayil.io/api/v1/chat/completions</code></pre>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-json">json</span>
                    <button class="docs-copy-btn" aria-label="Copy request body">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>{
  "model": "mistral-small3.2:24b",
  "messages": [
    {"role": "system", "content": "You are a helpful assistant."},
    {"role": "user", "content": "Explain quantum computing in simple terms."}
  ],
  "temperature": 0.7,
  "top_p": 0.9,
  "max_tokens": 500,
  "stream": false
}</code></pre>
            </div>

            <p>
                @if(app()->getLocale() === 'ar')
                    كل استجابة تتضمن حقل <code>usage</code> يوضح عدد الرموز المستهلكة بدقة:
                @else
                    Every response includes a <code>usage</code> field showing exact token consumption:
                @endif
            </p>

            <div class="docs-code-block">
                <div class="docs-code-block-header">
                    <span class="docs-code-lang lang-json">json</span>
                    <button class="docs-copy-btn" aria-label="Copy usage response">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <pre><code>"usage": {
  "prompt_tokens": 15,
  "completion_tokens": 142,
  "total_tokens": 157
}</code></pre>
            </div>
        </section>

        <!-- Section 6: Model Availability -->
        <section class="docs-section" id="section-availability">
            <h2>
                @if(app()->getLocale() === 'ar') توفر النماذج وحالتها @else Model Availability &amp; Status @endif
            </h2>

            <h3>@if(app()->getLocale() === 'ar') الوصول الكامل لجميع المستويات @else Full Access Across All Tiers @endif</h3>
            <p>
                @if(app()->getLocale() === 'ar')
                    جميع المستويات يمكنها الوصول إلى جميع النماذج الـ 46 فوراً دون أي قيود. الفارق الوحيد هو رصيدك المتاح.
                @else
                    All subscription tiers have immediate access to all 46 models with no restrictions. The only differentiator is your available credit balance.
                @endif
            </p>

            <h3>@if(app()->getLocale() === 'ar') تحديثات النماذج @else Model Updates @endif</h3>
            <p>
                @if(app()->getLocale() === 'ar')
                    نحرص على تحديث كتالوج النماذج باستمرار لإضافة أحدث وأقوى النماذج المتاحة. عند إضافة نماذج جديدة تظهر فوراً في نتائج <code>GET /v1/models</code> وتصبح جاهزة للاستخدام.
                @else
                    We continuously update the model catalog to include the latest and most capable models. New models appear immediately in <code>GET /v1/models</code> results and are ready to use.
                @endif
            </p>

            <h3>@if(app()->getLocale() === 'ar') إيقاف النماذج @else Deprecations @endif</h3>
            <p>
                @if(app()->getLocale() === 'ar')
                    في حال إيقاف أي نموذج، سيتم إشعارك بمهلة لا تقل عن 30 يوماً مع توجيهات الانتقال إلى البديل. ستصل الإشعارات عبر البريد الإلكتروني ولوحة التحكم.
                @else
                    If a model is deprecated, at least 30 days notice is provided along with migration guidance. Notifications are sent via email and dashboard alerts.
                @endif
            </p>
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') موارد ذات صلة @else Related Resources @endif
            </h2>
            <ul>
                <li><a href="{{ route('docs.getting-started') }}" class="docs-link">
                    @if(app()->getLocale() === 'ar') البدء السريع @else Getting Started @endif
                </a> — @if(app()->getLocale() === 'ar') أول طلب API لك @else Your first API request @endif</li>
                <li><a href="{{ route('docs.billing') }}" class="docs-link">
                    @if(app()->getLocale() === 'ar') الفواتير والأرصدة @else Billing &amp; Credits @endif
                </a> — @if(app()->getLocale() === 'ar') معدلات استهلاك الأرصدة @else Credit consumption rates @endif</li>
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">
                    @if(app()->getLocale() === 'ar') حدود المعدل @else Rate Limits @endif
                </a> — @if(app()->getLocale() === 'ar') حصص الطلبات لكل مستوى @else Request quotas per tier @endif</li>
                <li><a href="{{ route('pricing') }}" class="docs-link">
                    @if(app()->getLocale() === 'ar') الأسعار @else Pricing @endif
                </a> — @if(app()->getLocale() === 'ar') مستويات الاشتراك والتكاليف @else Subscription tiers and costs @endif</li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            <h3>
                @if(app()->getLocale() === 'ar') هل أنت مستعد للبدء؟ @else Ready to start building? @endif
            </h3>
            <p>
                @if(app()->getLocale() === 'ar')
                    تعرّف على نظام الأرصدة والفواتير لفهم التكاليف.
                @else
                    Learn about the credit system and billing to understand costs.
                @endif
            </p>
            <a href="{{ route('docs.billing') }}">
                @if(app()->getLocale() === 'ar') الانتقال إلى الفواتير والأرصدة &larr; @else Go to Billing &amp; Credits &rarr; @endif
            </a>
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Available Models', 'url' => route('docs.models')]
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
            // fallback
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
                // grab from sibling pre > code
                var block = btn.closest('.docs-code-block');
                var code = block ? block.querySelector('code') : null;
                text = code ? code.textContent : '';
            }
            copyText(text, btn);
        });
    });

    // Model ID copy buttons
    document.querySelectorAll('.model-copy-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            copyText(btn.dataset.model, btn);
        });
    });

    // ── Model filter / search ─────────────────────────────────────────────
    var searchInput   = document.getElementById('modelSearchInput');
    var filterBtns    = document.querySelectorAll('.model-filter-btn');
    var countLabel    = document.getElementById('modelCountLabel');
    var modelTables   = document.querySelectorAll('.model-table');
    var activeFilter  = 'all';

    function applyFilter() {
        var query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        var visible = 0;

        modelTables.forEach(function (table) {
            var rows = table.querySelectorAll('tbody tr:not(.no-results-row)');
            var tableVisible = 0;

            rows.forEach(function (row) {
                var cat  = (row.dataset.category || '').toLowerCase();
                var text = row.textContent.toLowerCase();
                var matchCat  = (activeFilter === 'all') || (cat === activeFilter);
                var matchText = !query || text.includes(query);

                if (matchCat && matchText) {
                    row.style.display = '';
                    tableVisible++;
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide no-results row per table
            var noRes = table.querySelector('.no-results-row');
            if (tableVisible === 0) {
                if (!noRes) {
                    var tr = document.createElement('tr');
                    tr.className = 'no-results-row';
                    var td = document.createElement('td');
                    td.colSpan = 5;
                    td.textContent = 'No models match your filter.';
                    tr.appendChild(td);
                    table.querySelector('tbody').appendChild(tr);
                } else {
                    noRes.style.display = '';
                }
            } else {
                if (noRes) noRes.style.display = 'none';
            }
        });

        if (countLabel) {
            countLabel.textContent = visible + ' model' + (visible !== 1 ? 's' : '');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            applyFilter();
        });
    });

    // Initial count
    applyFilter();

    // ── Smooth scroll for jump links ──────────────────────────────────────
    document.querySelectorAll('.docs-jump-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var href = link.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                var target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
}());
</script>
@endpush

@endsection
