@extends('layouts.app')

@section('title', 'Available Models — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page Styles ── */
    .mult-svg-icon { flex-shrink: 0; vertical-align: middle; }
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
                يوفر LLM Resayil وصولاً إلى {{ $models->count() }} نموذجاً من النماذج اللغوية الكبيرة — {{ $localModels->count() }} نموذجاً قياسياً و{{ $cloudModels->count() }} نموذجاً متقدماً عالي الأداء. اختر النموذج المناسب لحالة الاستخدام لديك بناءً على السرعة والدقة ومعامل الاستهلاك.
            @else
                LLM Resayil provides access to {{ $models->count() }} large language models — {{ $localModels->count() }} standard models and {{ $cloudModels->count() }} high-performance frontier models. Choose the right model for your use case based on speed, accuracy, and credit multiplier.
            @endif
        </p>

        <!-- Section Jump Links -->
        <div class="docs-jump-links">
            <a href="#section-multipliers" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') نظام المعاملات @else Credit Multipliers @endif
            </a>
            <a href="#section-local" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') النماذج القياسية ({{ $localModels->count() }}) @else Standard Models ({{ $localModels->count() }}) @endif
            </a>
            <a href="#section-cloud" class="docs-jump-link">
                @if(app()->getLocale() === 'ar') النماذج المتقدمة ({{ $cloudModels->count() }}) @else Frontier Models ({{ $cloudModels->count() }}) @endif
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
                            <td><div style="display:inline-flex;align-items:center;gap:0.5rem;"><x-multiplier-icon multiplier="0.5" :size="18" /><span class="multiplier mult-05">0.5×</span></div></td>
                            <td>@if(app()->getLocale() === 'ar') قياسي — خفيف @else Standard — Lightweight @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج صغيرة وسريعة جداً، مثالية للمهام البسيطة @else Small, ultra-fast models ideal for simple tasks @endif</td>
                        </tr>
                        <tr>
                            <td><div style="display:inline-flex;align-items:center;gap:0.5rem;"><x-multiplier-icon multiplier="1.0" :size="18" /><span class="multiplier mult-10">1.0×</span></div></td>
                            <td>@if(app()->getLocale() === 'ar') متقدم — تضمين @else Frontier — Embedding @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج التضمين خفيفة الاستهلاك @else Lightweight embedding models @endif</td>
                        </tr>
                        <tr>
                            <td><div style="display:inline-flex;align-items:center;gap:0.5rem;"><x-multiplier-icon multiplier="1.5" :size="18" /><span class="multiplier mult-15">1.5×</span></div></td>
                            <td>@if(app()->getLocale() === 'ar') قياسي — متوسط @else Standard — Mid @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج متوسطة الحجم وعالية الكفاءة @else Mid-size models with strong performance @endif</td>
                        </tr>
                        <tr>
                            <td><div style="display:inline-flex;align-items:center;gap:0.5rem;"><x-multiplier-icon multiplier="2.5" :size="18" /><span class="multiplier mult-25">2.5×</span></div></td>
                            <td>@if(app()->getLocale() === 'ar') متقدم — متوسط @else Frontier — Mid @endif</td>
                            <td>@if(app()->getLocale() === 'ar') نماذج متقدمة متوازنة بين الأداء والتكلفة @else Balanced frontier models for quality and cost @endif</td>
                        </tr>
                        <tr>
                            <td><div style="display:inline-flex;align-items:center;gap:0.5rem;"><x-multiplier-icon multiplier="3.5" :size="18" /><span class="multiplier mult-35">3.5×</span></div></td>
                            <td>@if(app()->getLocale() === 'ar') متقدم — كبير @else Frontier — Large @endif</td>
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

        <!-- Section 2: Standard Models -->
        <section class="docs-section" id="section-local">
            <h2>
                @if(app()->getLocale() === 'ar') النماذج القياسية ({{ $localModels->count() }} نموذجاً) @else Standard Models ({{ $localModels->count() }}) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    نماذج مُحسَّنة للأداء السريع والاستخدام الفعّال للأرصدة. المعامل الأساسي هو <strong>1 رصيد لكل رمز</strong> مع تعديلات بحسب حجم النموذج.
                @else
                    Models optimized for fast performance and efficient credit usage. The base rate is <strong>1 credit per token</strong> adjusted by the model multiplier.
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
                        @foreach($localModels as $modelId => $model)
                        @php
                            $cat = $model['category'] ?? 'chat';
                            $mult = number_format($model['credit_multiplier'] ?? 1.0, 1);
                            $multClass = 'mult-' . str_replace('.', '', $mult);
                            $descEn = $model['description'] ?? '';
                            $descArMap = [
                                'General chat, fast' => 'محادثة عامة، سريع',
                                'Ultra-fast, lightweight' => 'فائق السرعة، خفيف الوزن',
                                'Code generation' => 'توليد الأكواد',
                                'General chat, balanced' => 'محادثة عامة، متوازن',
                                'Fast, efficient' => 'سريع وفعّال',
                                'General chat' => 'محادثة عامة',
                                'Code' => 'برمجة',
                                'Embeddings' => 'تضمينات',
                                'Vision' => 'رؤية',
                                'Thinking/reasoning' => 'تفكير واستدلال',
                                'Thinking, largest' => 'تفكير - الأكبر',
                                'Code, large' => 'برمجة - كبير',
                                'General, large' => 'عام - كبير',
                                'Thinking' => 'تفكير',
                                'General' => 'عام',
                                'Lightweight 3B parameter model for fast inference' => 'نموذج خفيف بـ 3 مليار معامل للاستدلال السريع',
                                'Tiny 135M parameter model for quick tasks' => 'نموذج صغير جداً بـ 135 مليون معامل للمهام السريعة',
                                'Code-focused model with 14B parameters' => 'نموذج متخصص في البرمجة بـ 14 مليار معامل',
                                'High-performance 24B parameter model' => 'نموذج عالي الأداء بـ 24 مليار معامل',
                                'Small model with large context window' => 'نموذج صغير بنافذة سياق كبيرة',
                                'Google Gemma 2 with 9B parameters' => 'جوجل جيما 2 بـ 9 مليار معامل',
                                'Code-focused model with 6.7B parameters' => 'نموذج متخصص في البرمجة بـ 6.7 مليار معامل',
                                'Versatile 8B parameter model' => 'نموذج متعدد الاستخدامات بـ 8 مليار معامل',
                                'Text embedding model' => 'نموذج تضمين النصوص',
                                'Dense, sparse, and multi-quantizer embeddings' => 'تضمينات كثيفة وخفيفة ومتعددة المحولات',
                                'Lightweight embedding model' => 'نموذج تضمين خفيف الوزن',
                                'High-quality embedding model' => 'نموذج تضمين عالي الجودة',
                                'Original Mistral with 7B parameters' => 'ميسترال الأصلي بـ 7 مليار معامل',
                                'Code generation model' => 'نموذج توليد الأكواد',
                                'Code-specific Llama model' => 'نموذج لاما متخصص في البرمجة',
                            ];
                            $descAr = $descArMap[$descEn] ?? $descEn;
                        @endphp
                        <tr data-category="{{ $cat }}">
                            <td><div class="model-id-cell"><code>{{ $modelId }}</code><button class="model-copy-btn" data-model="{{ $modelId }}" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-{{ $cat }}">{{ $cat }}</span></td>
                            <td>{{ $model['params'] ?? '—' }}</td>
                            <td>
                              <div style="display:inline-flex;align-items:center;gap:0.4rem;">
                                <x-multiplier-icon :multiplier="$mult" :size="16" />
                                <span class="multiplier {{ $multClass }}">{{ $mult }}×</span>
                              </div>
                            </td>
                            <td>@if(app()->getLocale() === 'ar')<span dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ $descAr }}</span>@else{{ $descEn }}@endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 3: Frontier Models -->
        <section class="docs-section" id="section-cloud">
            <h2>
                @if(app()->getLocale() === 'ar') نماذج الحدود ({{ $cloudModels->count() }} نموذجاً) @else Frontier Models ({{ $cloudModels->count() }}) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    النماذج المتقدمة تتيح الوصول إلى بعض أقوى نماذج الذكاء الاصطناعي المتاحة، بعشرات أو مئات المليارات من المعاملات. تبدأ معاملاتها من <strong>2.5×</strong> وتصل إلى <strong>3.5×</strong>.
                @else
                    Frontier models provide access to some of the most powerful AI models available, with tens or hundreds of billions of parameters. Multipliers range from <strong>2.5×</strong> to <strong>3.5×</strong>.
                @endif
            </p>

            <div class="docs-table-wrap">
                <table class="docs-table model-table" id="cloudModelsTable">
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
                        @foreach($cloudModels as $modelId => $model)
                        @php
                            $cat = $model['category'] ?? 'chat';
                            $mult = number_format($model['credit_multiplier'] ?? 2.0, 1);
                            $multClass = 'mult-' . str_replace('.', '', $mult);
                            $descEn = $model['description'] ?? '';
                            $descArMap = [
                                'General chat, fast' => 'محادثة عامة، سريع',
                                'Ultra-fast, lightweight' => 'فائق السرعة، خفيف الوزن',
                                'Code generation' => 'توليد الأكواد',
                                'General chat, balanced' => 'محادثة عامة، متوازن',
                                'Fast, efficient' => 'سريع وفعّال',
                                'General chat' => 'محادثة عامة',
                                'Code' => 'برمجة',
                                'Embeddings' => 'تضمينات',
                                'Vision' => 'رؤية',
                                'Thinking/reasoning' => 'تفكير واستدلال',
                                'Thinking, largest' => 'تفكير - الأكبر',
                                'Code, large' => 'برمجة - كبير',
                                'General, large' => 'عام - كبير',
                                'Thinking' => 'تفكير',
                                'General' => 'عام',
                                'Zhipu AI multimodal model' => 'نموذج متعدد الوسائط من Zhipu AI',
                                'Qwen 3 with 30B parameters' => 'كيوين 3 بـ 30 مليار معامل',
                                'OpenAI open source model' => 'نموذج أوبن سورس من OpenAI',
                                'Qwen3 Vision-Language model' => 'نموذج رؤية ولغة Qwen3',
                                'Qwen3 Vision-Language 235B flagship multimodal model' => 'نموذج Qwen3 متعدد الوسائط الرائد بـ 235 مليار معامل',
                                'Qwen 3.5 with 397B parameters (MoE)' => 'كيوين 3.5 بـ 397 مليار معامل (MoE)',
                                'Mistral Devstral 2 with 123B parameters' => 'Devstral 2 من ميسترال بـ 123 مليار معامل',
                                'DeepSeek V3.1 with 671B parameters' => 'ديب سيك V3.1 بـ 671 مليار معامل',
                                'DeepSeek V3.2 latest version' => 'ديب سيك V3.2 الإصدار الأحدث',
                                'Llama 3.2 with 11B parameters' => 'لاما 3.2 بـ 11 مليار معامل',
                                'Llama 3.2 with 70B parameters' => 'لاما 3.2 بـ 70 مليار معامل',
                                'Llama 3.1 with 70B parameters' => 'لاما 3.1 بـ 70 مليار معامل',
                                'Llama 3.1 with 405B parameters (MoE)' => 'لاما 3.1 بـ 405 مليار معامل (MoE)',
                                'Google Gemma 2 with 27B parameters' => 'جوجل جيما 2 بـ 27 مليار معامل',
                                'Mistral Mixtral 8x7B MoE' => 'ميسترال Mixtral 8x7B MoE',
                                'Mistral Mixtral 8x22B MoE' => 'ميسترال Mixtral 8x22B MoE',
                                'Mistral Large with 24B parameters' => 'ميسترال Large بـ 24 مليار معامل',
                                'Mistral Nemo with 12B parameters' => 'ميسترال Nemo بـ 12 مليار معامل',
                                'Mistral Codestral for code generation' => 'Codestral من ميسترال لتوليد الأكواد',
                                'DeepSeek Coder with 33B parameters' => 'ديب سيك Coder بـ 33 مليار معامل',
                                'DeepSeek Chat with 671B parameters' => 'ديب سيك Chat بـ 671 مليار معامل',
                                'Qwen 2 with 72B parameters' => 'كيوين 2 بـ 72 مليار معامل',
                                'Qwen 2.5 with 32B parameters' => 'كيوين 2.5 بـ 32 مليار معامل',
                                '01.AI Yi with 34B parameters' => '01.AI Yi بـ 34 مليار معامل',
                                'DeepSeek V2.5 with MoE architecture' => 'ديب سيك V2.5 بمعمارية MoE',
                                'Llama 3 with extended context' => 'لاما 3 بسياق موسّع',
                                'Cohere Command R for RAG' => 'Command R من Cohere لـ RAG',
                                'Cohere Command R+ for advanced RAG' => 'Command R+ من Cohere لـ RAG المتقدم',
                                'Fireworks FireFunction for function calling' => 'FireFunction من Fireworks لاستدعاء الدوال',
                                'Nomic Embed for text embeddings' => 'Nomic Embed لتضمين النصوص',
                                'GTE Qwen embedding model' => 'نموذج تضمين GTE Qwen',
                                'BGE Large embedding model' => 'نموذج تضمين BGE Large',
                                'E5 Mistral embedding model' => 'نموذج تضمين E5 Mistral',
                                'NVIDIA NeMo Embedding model' => 'نموذج تضمين NVIDIA NeMo',
                                'All-MiniLM L6 v2 embedding model' => 'نموذج تضمين All-MiniLM L6 v2',
                                'GTE Base embedding model' => 'نموذج تضمين GTE Base',
                                'Snowflake Arctic Embed Large' => 'Snowflake Arctic Embed الكبير',
                                'BGE Small embedding model' => 'نموذج تضمين BGE Small',
                                'MiniLM L12 embedding model' => 'نموذج تضمين MiniLM L12',
                            ];
                            $descAr = $descArMap[$descEn] ?? $descEn;
                        @endphp
                        <tr data-category="{{ $cat }}">
                            <td><div class="model-id-cell"><code>{{ $modelId }}</code><button class="model-copy-btn" data-model="{{ $modelId }}" aria-label="Copy model ID">Copy</button></div></td>
                            <td><span class="badge badge-{{ $cat }}">{{ $cat }}</span></td>
                            <td>{{ $model['params'] ?? '—' }}</td>
                            <td>
                              <div style="display:inline-flex;align-items:center;gap:0.4rem;">
                                <x-multiplier-icon :multiplier="$mult" :size="16" />
                                <span class="multiplier {{ $multClass }}">{{ $mult }}×</span>
                              </div>
                            </td>
                            <td>@if(app()->getLocale() === 'ar')<span dir="rtl" style="font-family:'Tajawal',sans-serif;">{{ $descAr }}</span>@else{{ $descEn }}@endif</td>
                        </tr>
                        @endforeach
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
                    جميع المستويات يمكنها الوصول إلى جميع النماذج الـ {{ $models->count() }} فوراً دون أي قيود. الفارق الوحيد هو رصيدك المتاح.
                @else
                    All subscription tiers have immediate access to all {{ $models->count() }} models with no restrictions. The only differentiator is your available credit balance.
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
