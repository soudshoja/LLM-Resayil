@extends('layouts.app')

@section('title', 'Error Codes & Troubleshooting — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page — Error Codes ── */
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

    .docs-breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }

    .docs-breadcrumb a:hover { color: var(--gold); }

    .docs-breadcrumb .bc-sep {
        color: var(--text-muted);
        opacity: 0.4;
        font-size: 0.8rem;
    }

    .docs-breadcrumb .bc-current {
        color: var(--gold);
        font-weight: 500;
    }

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
    .docs-section {
        margin-bottom: 3rem;
    }

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
        margin-top: 1.75rem;
        color: var(--text-primary);
    }

    .docs-section p {
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 1rem;
    }

    .docs-section ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 1rem;
    }

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

    /* Code Blocks */
    .docs-code-wrap {
        position: relative;
        margin: 1.5rem 0;
    }

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
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        white-space: pre;
    }

    .docs-code-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
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
        font-family: 'Monaco', 'Menlo', monospace;
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

    .docs-copy-btn:hover {
        color: var(--gold);
        border-color: var(--gold);
    }

    .docs-copy-btn svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
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

    .docs-box-icon {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        margin-top: 0.15rem;
    }

    .docs-box-icon svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .docs-box p { margin: 0; font-size: 0.92rem; line-height: 1.65; }

    .docs-box-tip    { background: rgba(212, 175, 55, 0.08); border-left: 4px solid var(--gold); }
    .docs-box-tip    .docs-box-icon svg { stroke: var(--gold); }
    .docs-box-warning{ background: rgba(245, 158, 11, 0.08); border-left: 4px solid #f59e0b; }
    .docs-box-warning .docs-box-icon svg { stroke: #f59e0b; }
    .docs-box-danger { background: rgba(220, 38, 38, 0.08);  border-left: 4px solid #dc2626; }
    .docs-box-danger  .docs-box-icon svg { stroke: #dc2626; }
    .docs-box-note   { background: rgba(59, 130, 246, 0.08); border-left: 4px solid #3b82f6; }
    .docs-box-note   .docs-box-icon svg { stroke: #3b82f6; }

    /* Link */
    .docs-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }
    .docs-link:hover { opacity: 0.8; }

    /* Table */
    .docs-table-wrap {
        margin: 1.5rem 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        overflow-x: auto;
    }

    .docs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

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

    /* Quick Reference Table — Quick-ref status table */
    .docs-table .qr-status { font-weight: 700; white-space: nowrap; }

    /* HTTP Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: 'Monaco','Menlo','Courier New',monospace;
        font-size: 0.95rem;
        font-weight: 800;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .status-2xx { background: rgba(34,197,94,0.15);  color: #22c55e; border: 1px solid rgba(34,197,94,0.3);  }
    .status-4xx { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .status-402 { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3);  }
    .status-429 { background: rgba(239,68,68,0.12);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
    .status-5xx { background: rgba(185,28,28,0.18);  color: #fca5a5; border: 1px solid rgba(185,28,28,0.35); }

    /* Severity indicator */
    .severity-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
    }

    .sev-info     { background: rgba(59,130,246,0.15);  color: #60a5fa; }
    .sev-warning  { background: rgba(245,158,11,0.15);  color: #fbbf24; }
    .sev-error    { background: rgba(239,68,68,0.15);   color: #f87171; }
    .sev-critical { background: rgba(185,28,28,0.2);    color: #fca5a5; }

    .sev-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .sev-info     .sev-dot { background: #60a5fa; }
    .sev-warning  .sev-dot { background: #fbbf24; }
    .sev-error    .sev-dot { background: #f87171; }
    .sev-critical .sev-dot { background: #fca5a5; }

    /* Error Code Card */
    .error-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        margin: 2rem 0;
        overflow: hidden;
    }

    .error-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255,255,255,0.02);
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .error-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        flex: 1;
    }

    .error-card-body {
        padding: 1.25rem;
    }

    .error-card-body p {
        color: var(--text-secondary);
        line-height: 1.75;
        margin-bottom: 0.75rem;
        font-size: 0.93rem;
    }

    .error-card-body ul {
        margin-bottom: 0;
    }

    /* Collapsible JSON on mobile */
    .error-json-toggle {
        display: none;
        width: 100%;
        text-align: left;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.6rem 1rem;
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 0;
        font-family: inherit;
        transition: color 0.2s, border-color 0.2s;
    }

    .error-json-toggle:hover { color: var(--gold); border-color: var(--gold); }

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

    /* rel list override */
    .rel-list li { padding-left: 0; }
    .rel-list li:before { display: none; }

    @media (max-width: 768px) {
        .docs-title { font-size: 1.75rem; }
        .docs-section h2 { font-size: 1.35rem; }
        .docs-section h3 { font-size: 1.1rem; }

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

        .error-json-toggle { display: block; }

        .error-json-collapsible { display: none; }
        .error-json-collapsible.open { display: block; }

        .error-card-header { gap: 0.75rem; }
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
                @if(app()->getLocale() === 'ar') التوثيق @else Documentation @endif
            </a>
            <span class="bc-sep">&#8250;</span>
            <span class="bc-current">
                @if(app()->getLocale() === 'ar') رموز الخطأ &amp; استكشاف الأعطال @else Error Codes &amp; Troubleshooting @endif
            </span>
        </nav>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                رموز الخطأ <span>&amp; استكشاف الأعطال</span>
            @else
                Error Codes &amp; <span>Troubleshooting</span>
            @endif
        </h1>
        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                تعرّف على أخطاء API الشائعة وأسبابها وكيفية حلها.
                استخدم هذا الدليل لتصحيح المشكلات وتطبيق معالجة أخطاء فعّالة في تطبيقاتك.
            </p>
        @else
            <p class="docs-intro">
                Learn about common API errors, their causes, and how to resolve them.
                Use this guide to debug issues and implement robust error handling in your applications.
            </p>
        @endif

        <!-- Quick Reference Table -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>مرجع سريع — جميع رموز الخطأ</h2>
            @else
                <h2>Quick Reference — All Error Codes</h2>
            @endif

            <div class="docs-table-wrap">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            @if(app()->getLocale() === 'ar')
                                <th>الاسم</th>
                                <th>الخطورة</th>
                                <th>وصف موجز</th>
                            @else
                                <th>Name</th>
                                <th>Severity</th>
                                <th>One-line description</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="status-badge status-2xx">200</span></td>
                            <td>OK</td>
                            <td><span class="severity-pill sev-info"><span class="sev-dot"></span>Info</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>الطلب ناجح، الاستجابة جاهزة.</td>
                            @else
                                <td>Request succeeded and response is ready.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-4xx">401</span></td>
                            <td>Unauthorized</td>
                            <td><span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>مفتاح API مفقود أو غير صالح أو منتهي الصلاحية.</td>
                            @else
                                <td>Missing, invalid, or expired API key.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-402">402</span></td>
                            <td>Payment Required</td>
                            <td><span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>رصيد غير كافٍ لإتمام الطلب.</td>
                            @else
                                <td>Insufficient credits to complete the request.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-4xx">403</span></td>
                            <td>Forbidden</td>
                            <td><span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>مصادق عليه لكن بدون صلاحيات كافية.</td>
                            @else
                                <td>Authenticated but insufficient permissions.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-4xx">404</span></td>
                            <td>Not Found</td>
                            <td><span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>النموذج المطلوب غير موجود أو مسار API خاطئ.</td>
                            @else
                                <td>Model not found or incorrect API path.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-4xx">422</span></td>
                            <td>Unprocessable Entity</td>
                            <td><span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>فشل التحقق — حقول مفقودة أو قيم غير صالحة.</td>
                            @else
                                <td>Validation failed — missing or invalid fields.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-429">429</span></td>
                            <td>Too Many Requests</td>
                            <td><span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>تجاوز حد معدل الطلبات، انتظر retry_after.</td>
                            @else
                                <td>Rate limit exceeded, wait for retry_after seconds.</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="status-badge status-5xx">500</span></td>
                            <td>Internal Server Error</td>
                            <td><span class="severity-pill sev-critical"><span class="sev-dot"></span>Critical</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>خطأ غير متوقع من جانب الخادم، أعد المحاولة.</td>
                            @else
                                <td>Unexpected server error, retry with backoff.</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 1: Error Response Structure -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>بنية استجابة الخطأ</h2>
                <p>
                    عند مواجهة خطأ، تُعيد واجهة LLM Resayil API استجابة JSON تصف ما حدث.
                    فهم كيفية تحليل هذه الاستجابات ضروري لبناء تطبيقات قوية.
                </p>
                <p>تتبع جميع استجابات الخطأ هذا التنسيق الموحد:</p>
            @else
                <h2>Error Response Structure</h2>
                <p>
                    When the LLM Resayil API encounters an error, it returns a JSON response describing what
                    went wrong. Understanding how to parse these responses is essential for building robust
                    error handling in your applications.
                </p>
                <p>All error responses follow this unified format:</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-err-format">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-err-format">{
  "error": {
    "message": "Human-readable error message",
    "code": 401
  }
}</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <p>
                    تحتوي بعض الأخطاء — كأخطاء التحقق (422) — على حقل إضافي <code>errors</code>
                    يوفر تفاصيل التحقق لكل حقل:
                </p>
            @else
                <p>
                    Certain errors — such as validation errors (422) — include an additional <code>errors</code>
                    object with per-field validation details:
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JSON — 422 Validation</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-422-format">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-422-format">{
  "error": {
    "message": "Validation failed",
    "code": 422
  },
  "errors": {
    "model": ["The model field is required."],
    "messages": ["The messages field must be an array."]
  }
}</code>
                </div>
            </div>
        </section>

        <!-- Section 2: Detailed Error Code Cards -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>أمثلة تفصيلية لاستجابات الأخطاء</h2>
            @else
                <h2>Detailed Error Response Examples</h2>
            @endif

            <!-- 401 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-4xx">401</span>
                    <span class="error-card-title">Unauthorized</span>
                    <span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>مفتاح API مفقود أو غير صالح في رأس Authorization.</p>
                    @else
                        <p>Missing or invalid API key in the Authorization header.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 401</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-401">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-401">HTTP/1.1 401 Unauthorized

{
  "error": {
    "message": "Unauthenticated.",
    "code": 401
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>تأكد من أن مفتاح API صحيح ومنسوخ بدون مسافات إضافية</li>
                            <li>تأكد من أن تنسيق رأس Authorization هو: <code>Authorization: Bearer YOUR_KEY</code></li>
                            <li>أنشئ مفتاحاً جديداً إذا اعتقدت أن المفتاح الحالي قد اختُرق</li>
                            <li>تحقق من أن المفتاح لم يُلغَ من لوحة التحكم</li>
                        </ul>
                    @else
                        <ul>
                            <li>Verify the API key is correct — copy it from the dashboard with no extra spaces</li>
                            <li>Ensure the Authorization header format is: <code>Authorization: Bearer YOUR_KEY</code></li>
                            <li>Generate a new key if you suspect the current one is compromised</li>
                            <li>Confirm the key has not been revoked from the dashboard</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 402 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-402">402</span>
                    <span class="error-card-title">Payment Required</span>
                    <span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>استُنفد رصيد حسابك. يجب إضافة رصيد للمتابعة.</p>
                    @else
                        <p>Your credit balance has been exhausted. You must top up to continue making API calls.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 402</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-402">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-402">HTTP/1.1 402 Payment Required

{
  "error": {
    "message": "Insufficient credits. Please top up your balance to continue.",
    "code": 402
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>انتقل إلى لوحة التحكم واشترِ رصيداً إضافياً عبر <a href="{{ url('/billing/plans') }}" class="docs-link">/billing/plans</a></li>
                            <li>تحقق من رصيدك الحالي عبر نقطة النهاية <code>GET /api/billing/subscription</code></li>
                            <li>فكّر في الترقية إلى خطة اشتراك لتجنب انقطاع الخدمة</li>
                        </ul>
                    @else
                        <ul>
                            <li>Go to the dashboard and purchase additional credits at <a href="{{ url('/billing/plans') }}" class="docs-link">/billing/plans</a></li>
                            <li>Check your current balance via the <code>GET /api/billing/subscription</code> endpoint</li>
                            <li>Consider upgrading to a subscription plan to avoid service interruptions</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 403 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-4xx">403</span>
                    <span class="error-card-title">Forbidden</span>
                    <span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>تمت المصادقة لكن الحساب لا يملك الصلاحيات الكافية للوصول إلى المورد المطلوب.</p>
                    @else
                        <p>Authenticated but the account does not have sufficient permissions to access the requested resource.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 403</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-403">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-403">HTTP/1.1 403 Forbidden

{
  "error": {
    "message": "You do not have permission to access this resource.",
    "code": 403
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>تحقق من حالة حسابك في لوحة التحكم — قد يكون موقوفاً</li>
                            <li>تأكد من أن اشتراكك لا يزال نشطاً</li>
                            <li>تواصل مع الدعم إذا اعتقدت أن هذا خطأ</li>
                        </ul>
                    @else
                        <ul>
                            <li>Check your account status in the dashboard — it may be suspended</li>
                            <li>Confirm your subscription is still active</li>
                            <li>Contact support if you believe this is an error</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 404 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-4xx">404</span>
                    <span class="error-card-title">Not Found</span>
                    <span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>النموذج المطلوب غير موجود، أو مسار API غير صحيح.</p>
                    @else
                        <p>The requested model does not exist, or the API path is incorrect.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 404</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-404">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-404">HTTP/1.1 404 Not Found

{
  "error": {
    "message": "Model not found.",
    "code": 404
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>تحقق من اسم النموذج المستخدم في حقل <code>model</code> بطلبك</li>
                            <li>راجع قائمة النماذج المتاحة في لوحة التحكم</li>
                            <li>تأكد من صحة عنوان URL للطلب</li>
                        </ul>
                    @else
                        <ul>
                            <li>Verify the model name used in the <code>model</code> field of your request</li>
                            <li>Review the list of available models in your dashboard</li>
                            <li>Confirm the request URL is correct</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 422 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-4xx">422</span>
                    <span class="error-card-title">Unprocessable Entity</span>
                    <span class="severity-pill sev-warning"><span class="sev-dot"></span>Warning</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>فشل التحقق من صحة الطلب. يتضمن الرد كائن <code>errors</code> يصف كل حقل به خطأ.</p>
                    @else
                        <p>Request validation failed. The response includes an <code>errors</code> object describing each invalid field.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 422</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-422">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-422">HTTP/1.1 422 Unprocessable Entity

{
  "error": {
    "message": "Validation failed",
    "code": 422
  },
  "errors": {
    "model": ["The model field is required."],
    "messages": ["The messages field must be an array."]
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>افحص كائن <code>errors</code> لتحديد الحقول ذات المشكلات</li>
                            <li>تأكد من تضمين جميع الحقول المطلوبة: <code>model</code> و<code>messages</code></li>
                            <li>تأكد من أن <code>messages</code> مصفوفة من الكائنات التي تحتوي على <code>role</code> و<code>content</code></li>
                            <li>تحقق من صحة بناء JSON قبل الإرسال</li>
                        </ul>
                    @else
                        <ul>
                            <li>Inspect the <code>errors</code> object to identify the problematic fields</li>
                            <li>Ensure all required fields are included: <code>model</code> and <code>messages</code></li>
                            <li>Confirm <code>messages</code> is an array of objects with <code>role</code> and <code>content</code></li>
                            <li>Validate your JSON syntax before sending</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 429 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-429">429</span>
                    <span class="error-card-title">Too Many Requests</span>
                    <span class="severity-pill sev-error"><span class="sev-dot"></span>Error</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>
                            تجاوزت حد معدل الطلبات. تتضمن الاستجابة حقل <code>retry_after</code>
                            يحدد عدد الثواني اللازمة للانتظار.
                        </p>
                    @else
                        <p>
                            You have exceeded your rate limit. The response includes a <code>retry_after</code> field
                            indicating how many seconds to wait before retrying.
                        </p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 429</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-429">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-429">HTTP/1.1 429 Too Many Requests
X-RateLimit-Limit: 20
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1741305600

{
  "error": {
    "message": "Rate limit exceeded",
    "code": 429
  },
  "retry_after": 45
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>انتظر عدد الثواني المحدد في <code>retry_after</code> قبل إعادة المحاولة</li>
                            <li>طبّق انتظاراً تدريجياً أسياً للمحاولات التلقائية</li>
                            <li>تحقق من حدود مستواك وفكّر في الترقية إذا تكررت المشكلة</li>
                            <li>طبّق تقنيناً من جانب العميل للبقاء ضمن حصتك</li>
                        </ul>
                    @else
                        <ul>
                            <li>Wait the number of seconds specified in <code>retry_after</code> before retrying</li>
                            <li>Implement exponential backoff for automatic retries</li>
                            <li>Check your tier's rate limits and consider upgrading if you frequently hit this error</li>
                            <li>Implement client-side rate limiting to stay within your quota</li>
                        </ul>
                    @endif
                </div>
            </div>

            <!-- 500 Card -->
            <div class="error-card">
                <div class="error-card-header">
                    <span class="status-badge status-5xx">500</span>
                    <span class="error-card-title">Internal Server Error</span>
                    <span class="severity-pill sev-critical"><span class="sev-dot"></span>Critical</span>
                </div>
                <div class="error-card-body">
                    @if(app()->getLocale() === 'ar')
                        <p>حدث خطأ غير متوقع من جانب الخادم. هذه الأخطاء نادرة وعادةً مؤقتة.</p>
                    @else
                        <p>An unexpected server-side error occurred. These are rare and usually temporary.</p>
                    @endif

                    <button class="error-json-toggle" onclick="toggleJson(this)">
                        @if(app()->getLocale() === 'ar') عرض مثال JSON ▾ @else Show JSON example ▾ @endif
                    </button>
                    <div class="error-json-collapsible">
                        <div class="docs-code-wrap">
                            <div class="docs-code-header">
                                <span class="docs-code-lang">HTTP / JSON — 500</span>
                                <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-500">
                                    <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Copy
                                </button>
                            </div>
                            <div class="docs-code-block">
                                <code id="code-500">HTTP/1.1 500 Internal Server Error

{
  "error": {
    "message": "An unexpected error occurred. Please try again later.",
    "code": 500
  }
}</code>
                            </div>
                        </div>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <ul>
                            <li>أعد المحاولة بعد بضع ثوانٍ</li>
                            <li>طبّق انتظاراً تدريجياً أسياً للمحاولات التلقائية</li>
                            <li>إذا استمر الخطأ، <a href="{{ route('contact') }}" class="docs-link">تواصل مع الدعم</a></li>
                        </ul>
                    @else
                        <ul>
                            <li>Retry the request after a few seconds</li>
                            <li>Implement exponential backoff for automatic retries</li>
                            <li>If the error persists, <a href="{{ route('contact') }}" class="docs-link">contact support</a></li>
                        </ul>
                    @endif
                </div>
            </div>
        </section>

        <!-- Section 3: Debugging Checklist -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>قائمة التحقق للتصحيح</h2>
                <p>
                    عند مواجهة أي خطأ، اعمل عبر هذه القائمة لتحديد المشكلة وحلها:
                </p>

                <h3>لجميع الأخطاء</h3>
                <ul>
                    <li>تأكد من صحة مفتاح API وتنسيقه الصحيح في رأس Authorization</li>
                    <li>تأكد من استخدام طريقة POST وليس GET</li>
                    <li>تأكد من ضبط رأس Content-Type على <code>application/json</code></li>
                    <li>تحقق من صحة بناء JSON في طلبك</li>
                </ul>

                <h3>لأخطاء 401 و 403</h3>
                <ul>
                    <li>تأكد من أن حسابك نشط وغير موقوف</li>
                    <li>تحقق من أن مفتاح API لم يُلغَ</li>
                    <li>تأكد من أن اشتراكك لا يزال ساري المفعول</li>
                    <li>تحقق من وجود رصيد كافٍ (الخطأ 402)</li>
                </ul>

                <h3>لأخطاء 422</h3>
                <ul>
                    <li>تضمّن جميع الحقول المطلوبة: model و messages</li>
                    <li>تأكد من أن اسم النموذج صالح وموجود في حسابك</li>
                    <li>تأكد من أن messages مصفوفة من الكائنات التي تحتوي على role و content</li>
                    <li>استخدم حقل errors في الاستجابة لتحديد الحقل المشكل</li>
                </ul>

                <h3>لأخطاء 429</h3>
                <ul>
                    <li>راقب رأس <code>X-RateLimit-Remaining</code> في استجاباتك</li>
                    <li>طبّق منطق إعادة المحاولة مع الانتظار التدريجي</li>
                    <li>قلّل تكرار طلباتك</li>
                    <li>فكّر في الترقية إلى مستوى أعلى للحصول على حدود أكبر</li>
                </ul>

                <h3>لأخطاء 500</h3>
                <ul>
                    <li>أعد المحاولة بعد 5–10 ثوانٍ</li>
                    <li>طبّق انتظاراً تدريجياً أسياً لعدة محاولات</li>
                    <li>تواصل مع الدعم إذا استمرت الأخطاء</li>
                </ul>
            @else
                <h2>Debugging Checklist</h2>
                <p>
                    When you encounter an error, work through this checklist to identify and resolve the issue:
                </p>

                <h3>For All Errors</h3>
                <ul>
                    <li>Check your API key is correct and properly formatted in the Authorization header</li>
                    <li>Ensure you are using the POST method, not GET</li>
                    <li>Verify the Content-Type header is set to <code>application/json</code></li>
                    <li>Check that your request JSON is valid with no syntax errors</li>
                </ul>

                <h3>For 401 and 403 Errors</h3>
                <ul>
                    <li>Verify your account is active and not suspended</li>
                    <li>Check that your API key has not been revoked</li>
                    <li>Ensure your subscription tier is active</li>
                    <li>Confirm you have enough credits (see 402)</li>
                </ul>

                <h3>For 422 Errors</h3>
                <ul>
                    <li>Include all required fields: model and messages</li>
                    <li>Verify the model name is valid and available in your account</li>
                    <li>Ensure messages is an array of objects with role and content properties</li>
                    <li>Use the errors object in the response to identify the problematic field</li>
                </ul>

                <h3>For 429 Errors</h3>
                <ul>
                    <li>Check the <code>X-RateLimit-Remaining</code> header in your responses</li>
                    <li>Implement retry logic with exponential backoff</li>
                    <li>Reduce the frequency of your requests</li>
                    <li>Consider upgrading to a higher tier for increased limits</li>
                </ul>

                <h3>For 500 Errors</h3>
                <ul>
                    <li>Retry the request after 5–10 seconds</li>
                    <li>Implement exponential backoff for multiple retries</li>
                    <li>Contact support if errors persist</li>
                </ul>
            @endif
        </section>

        <!-- Section 4: Best Practices -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>أفضل ممارسات معالجة الأخطاء</h2>

                <h3>1. احلل استجابات الأخطاء دائماً</h3>
                <p>
                    لا تكتفِ بالتحقق من رمز الحالة HTTP. حلّل JSON الخاص باستجابة الخطأ
                    لفهم ما حدث وتقديم تغذية راجعة مفيدة للمستخدمين.
                </p>

                <h3>2. طبّق منطق إعادة المحاولة</h3>
                <p>
                    استخدم انتظاراً تدريجياً أسياً مع عشوائية للأخطاء القابلة للمحاولة (429، 500).
                    راجع دليل حدود المعدل للتفاصيل.
                </p>

                <h3>3. سجّل الأخطاء للتصحيح</h3>
                <p>
                    سجّل استجابات الأخطاء كاملةً مع رموز الحالة ورموز الأخطاء ومعرّفات الطلبات.
                    هذا يجعل التصحيح أسرع بكثير.
                </p>

                <h3>4. قدّم رسائل سهلة الفهم للمستخدمين</h3>
                <p>
                    لا تعرض رسائل الخطأ الخام للمستخدمين. حوّل رموز الأخطاء إلى رسائل ودية
                    وقدّم إرشادات عملية.
                </p>

                <h3>5. راقب حدود المعدل بشكل استباقي</h3>
                <p>
                    تابع <code>X-RateLimit-Remaining</code> في الاستجابات وقلّل الطلبات قبل الوصول إلى الحد.
                </p>

                <h3>6. اضبط مهلات انتظار مناسبة</h3>
                <p>
                    اضبط مهلات الطلبات على 30 ثانية على الأقل للسماح للنماذج الأبطأ وزمن الشبكة.
                </p>
            @else
                <h2>Error Handling Best Practices</h2>

                <h3>1. Always Parse Error Responses</h3>
                <p>
                    Do not just check the HTTP status code. Parse the error response JSON to understand what
                    went wrong and provide meaningful feedback to your users.
                </p>

                <h3>2. Implement Retry Logic</h3>
                <p>
                    Use exponential backoff with jitter for retryable errors (429, 500). See the Rate Limits
                    guide for implementation details.
                </p>

                <h3>3. Log Errors for Debugging</h3>
                <p>
                    Log full error responses including status codes, error codes, and request IDs. This makes
                    debugging significantly faster.
                </p>

                <h3>4. Provide User-Friendly Messages</h3>
                <p>
                    Do not expose raw error messages to users. Map error codes to friendly messages and provide
                    actionable guidance.
                </p>

                <h3>5. Monitor Rate Limits Proactively</h3>
                <p>
                    Check <code>X-RateLimit-Remaining</code> in responses and throttle requests before hitting
                    the limit.
                </p>

                <h3>6. Set Appropriate Timeouts</h3>
                <p>
                    Set request timeouts to at least 30 seconds to allow for slower models and network latency.
                </p>
            @endif
        </section>

        <!-- Related Links -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>موارد ذات صلة</h2>
            @else
                <h2>Related Resources</h2>
            @endif
            <ul class="rel-list">
                <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits &amp; Quotas</a>
                    @if(app()->getLocale() === 'ar') — أخطاء حدود المعدل واستجابات 429 @else — Rate limit errors and 429 responses @endif
                </li>
                <li><a href="{{ route('docs.authentication') }}" class="docs-link">Authentication</a>
                    @if(app()->getLocale() === 'ar') — مصادقة مفتاح API وأخطاء 401 @else — API key authentication and 401 errors @endif
                </li>
                <li><a href="{{ route('docs.billing') }}" class="docs-link">Billing &amp; Credits</a>
                    @if(app()->getLocale() === 'ar') — إدارة الرصيد وأخطاء 402 @else — Credit management and 402 errors @endif
                </li>
                <li><a href="{{ route('contact') }}" class="docs-link">Contact Support</a>
                    @if(app()->getLocale() === 'ar') — احصل على مساعدة في الأخطاء المستمرة @else — Get help with persistent errors @endif
                </li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>انتهيت من التوثيق؟</h3>
                <p>أنت جاهز للبدء في البناء باستخدام LLM Resayil.</p>
                <a href="{{ route('register') }}">أنشئ حساباً مجانياً &larr;</a>
            @else
                <h3>Done with documentation?</h3>
                <p>Ready to start building with LLM Resayil.</p>
                <a href="{{ route('register') }}">Create Free Account &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Error Codes & Troubleshooting', 'url' => route('docs.error-codes')]
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

function toggleJson(btn) {
    const panel = btn.nextElementSibling;
    if (!panel) return;
    const isOpen = panel.classList.contains('open');
    panel.classList.toggle('open', !isOpen);
    const isAr = document.documentElement.lang === 'ar' || document.querySelector('html').getAttribute('lang') === 'ar';
    if (!isOpen) {
        btn.innerHTML = isAr ? 'إخفاء JSON ▴' : 'Hide JSON example ▴';
    } else {
        btn.innerHTML = isAr ? 'عرض مثال JSON ▾' : 'Show JSON example ▾';
    }
}
</script>

@endsection
