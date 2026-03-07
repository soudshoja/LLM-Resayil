@extends('layouts.app')

@section('title', 'API Documentation — LLM Resayil')

@push('styles')
<style>
    /* ── Documentation Landing Page ── */
    .docs-wrap {
        background: var(--bg-secondary);
        padding: 3rem 2rem 5rem;
    }

    /* Breadcrumb Navigation */
    .docs-breadcrumb {
        max-width: 1200px;
        margin: 0 auto 2.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .docs-breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }

    .docs-breadcrumb a:hover {
        color: var(--gold);
    }

    .docs-breadcrumb .bc-sep {
        color: var(--text-muted);
        opacity: 0.45;
        font-size: 0.8rem;
        margin: 0 0.15rem;
    }

    .docs-breadcrumb .bc-current {
        color: var(--gold);
        font-weight: 500;
    }

    /* Hero Section */
    .docs-hero {
        max-width: 900px;
        margin: 0 auto 4rem;
        text-align: center;
    }

    .docs-hero h1 {
        font-size: 2.75rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: var(--text-primary);
    }

    .docs-hero h1 span {
        color: var(--gold);
    }

    .docs-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        max-width: 680px;
        margin: 0 auto 2rem;
        line-height: 1.75;
    }

    /* Quick Example Box */
    .docs-quick-example {
        background: #0d1017;
        border: 1px solid var(--border);
        border-radius: 12px;
        margin: 2rem auto 0;
        max-width: 620px;
        text-align: left;
        position: relative;
        overflow: hidden;
    }

    .docs-quick-example-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,0.03);
    }

    .docs-quick-example-header .lang-badge {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        background: rgba(255,255,255,0.06);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
    }

    .docs-quick-example-header .copy-btn {
        background: none;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 5px;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 0.72rem;
        padding: 0.25rem 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: border-color 0.2s, color 0.2s;
    }

    .docs-quick-example-header .copy-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .docs-quick-example-header .copy-btn svg {
        width: 13px;
        height: 13px;
    }

    .docs-quick-example pre {
        margin: 0;
        padding: 1.25rem 1.5rem;
        overflow-x: auto;
        font-size: 0.8rem;
        line-height: 1.65;
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
    }

    .docs-quick-example p.example-note {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin: 0;
        padding: 0.6rem 1.5rem 0.9rem;
        border-top: 1px solid var(--border);
        background: rgba(255,255,255,0.02);
    }

    .docs-quick-example p.example-note code {
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.75rem;
    }

    /* Base URLs Box */
    .docs-base-urls {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-top: 3px solid var(--gold);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin: 2rem auto 0;
        max-width: 700px;
        text-align: left;
    }

    .docs-base-urls h3 {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 1rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .docs-base-urls table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .docs-base-urls th {
        color: var(--text-muted);
        font-weight: 600;
        padding: 0.4rem 0.75rem 0.6rem 0;
        border-bottom: 1px solid var(--gold);
        text-align: left;
        font-size: 0.8rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .docs-base-urls td {
        padding: 0.6rem 0.75rem 0.6rem 0;
        color: var(--text-secondary);
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }

    .docs-base-urls tbody tr:nth-child(even) td {
        background: rgba(255,255,255,0.015);
    }

    .docs-base-urls tbody tr:last-child td {
        border-bottom: none;
    }

    .docs-base-urls td code {
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.78rem;
        background: rgba(0,0,0,0.35);
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
    }

    .docs-base-urls .url-note {
        margin-top: 1rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.65;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border);
    }

    .docs-base-urls .url-note code {
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.75rem;
        background: rgba(0,0,0,0.25);
        padding: 0.1rem 0.35rem;
        border-radius: 3px;
    }

    /* Documentation Grid Section */
    .docs-section {
        max-width: 1200px;
        margin: 4rem auto 0;
    }

    .docs-section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .docs-section h2 {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--text-primary);
        margin: 0;
    }

    .docs-section-header::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .docs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-left: 3px solid transparent;
        border-radius: 12px;
        padding: 1.75rem 2rem;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
        position: relative;
    }

    .doc-card:hover {
        border-left-color: var(--gold);
        border-top-color: transparent;
        border-right-color: rgba(212, 175, 55, 0.2);
        border-bottom-color: rgba(212, 175, 55, 0.2);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.3), 0 0 0 0 rgba(212, 175, 55, 0.1);
    }

    .doc-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid rgba(212, 175, 55, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: var(--gold);
        flex-shrink: 0;
    }

    .doc-card-icon svg {
        width: 18px;
        height: 18px;
    }

    .doc-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .doc-card p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.65;
        flex: 1;
        margin-bottom: 1.25rem;
    }

    .doc-card .doc-link-arrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--gold);
        font-weight: 600;
        font-size: 0.85rem;
        transition: gap 0.2s;
    }

    .doc-card:hover .doc-link-arrow {
        gap: 0.65rem;
    }

    .doc-card .doc-link-arrow svg {
        width: 15px;
        height: 15px;
        transition: transform 0.2s;
    }

    .doc-card:hover .doc-link-arrow svg {
        transform: translateX(3px);
    }

    /* Help / Next Steps Section */
    .docs-next-steps {
        max-width: 1200px;
        margin: 4rem auto 0;
        padding: 3rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-top: 3px solid var(--gold);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-steps h2 {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .docs-next-steps p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.75rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .docs-next-steps .cta-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .docs-next-steps a.btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        background: var(--gold);
        color: #0f1115;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: filter 0.2s, transform 0.15s;
        text-decoration: none;
    }

    .docs-next-steps a.btn-primary:hover {
        filter: brightness(1.12);
        transform: translateY(-1px);
    }

    .docs-next-steps a.btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        background: transparent;
        color: var(--gold);
        border: 1.5px solid var(--gold);
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
    }

    .docs-next-steps a.btn-secondary:hover {
        background: rgba(212, 175, 55, 0.1);
    }

    .docs-footer-links {
        margin-top: 1.75rem;
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.8;
    }

    .docs-footer-links a {
        color: var(--gold);
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-footer-links a:hover {
        opacity: 0.8;
    }

    /* Badge pills */
    .url-badge {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.4rem;
        vertical-align: middle;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .url-badge-preferred {
        background: rgba(212, 175, 55, 0.15);
        color: var(--gold);
        border: 1px solid rgba(212, 175, 55, 0.4);
    }

    .url-badge-new {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.35);
    }

    /* Arabic RTL support */
    [dir="rtl"] .docs-breadcrumb {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .docs-base-urls th,
    [dir="rtl"] .docs-base-urls td {
        text-align: right;
        padding: 0.6rem 0 0.6rem 0.75rem;
    }

    [dir="rtl"] .doc-card {
        border-left: 1px solid var(--border);
        border-right: 3px solid transparent;
    }

    [dir="rtl"] .doc-card:hover {
        border-right-color: var(--gold);
        border-left-color: rgba(212, 175, 55, 0.2);
    }

    [dir="rtl"] .doc-card .doc-link-arrow {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .doc-card .doc-link-arrow svg {
        transform: scaleX(-1);
    }

    [dir="rtl"] .doc-card:hover .doc-link-arrow svg {
        transform: scaleX(-1) translateX(3px);
    }

    [dir="rtl"] .docs-section-header::after {
        display: none;
    }

    [dir="rtl"] .docs-section-header::before {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    @media (max-width: 768px) {
        .docs-hero h1 {
            font-size: 1.9rem;
            letter-spacing: -0.02em;
        }

        .docs-hero-lead {
            font-size: 1rem;
        }

        .docs-quick-example {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-base-urls {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-next-steps {
            padding: 2rem 1.5rem;
        }

        .docs-next-steps .cta-group {
            flex-direction: column;
            align-items: stretch;
        }

        .docs-next-steps a.btn-primary,
        .docs-next-steps a.btn-secondary {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="docs-wrap">

    <!-- Breadcrumb Navigation -->
    <div class="docs-breadcrumb">
        <a href="{{ route('welcome') }}">
            @if(app()->getLocale() === 'ar')
                الرئيسية
            @else
                Home
            @endif
        </a>
        <span class="bc-sep">&#8250;</span>
        <span class="bc-current">
            @if(app()->getLocale() === 'ar')
                التوثيق
            @else
                Documentation
            @endif
        </span>
    </div>

    <!-- Hero Section -->
    <div class="docs-hero">
        @if(app()->getLocale() === 'ar')
            <h1 dir="rtl" style="font-family: 'Tajawal', sans-serif;">توثيق <span>واجهة برمجة التطبيقات</span></h1>
            <p class="docs-hero-lead" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                تعلّم كيفية دمج LLM Resayil في تطبيقاتك. واجهة برمجة التطبيقات لدينا متوافقة مع OpenAI، وتدعم أكثر من 45 نموذجًا، وتعتمد على نظام دفع حسب الاستخدام. ابدأ في دقائق معدودة بمساعدة توثيق واضح وأمثلة برمجية جاهزة.
            </p>
        @else
            <h1>API <span>Documentation</span></h1>
            <p class="docs-hero-lead">
                Learn how to integrate LLM Resayil into your applications. Our API is OpenAI-compatible, supports 45+ models,
                and offers pay-per-token pricing. Get started in minutes with clear documentation and code examples.
            </p>
        @endif

        <!-- Quick Start Base URLs -->
        <div class="docs-base-urls" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
            @if(app()->getLocale() === 'ar')
                <h3>عناوين URL الأساسية للبدء السريع</h3>
            @else
                <h3>Quick Start Base URLs</h3>
            @endif
            <table>
                <thead>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <th>العنوان</th>
                            <th>الوصف</th>
                        @else
                            <th>URL</th>
                            <th>Description</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <code>https://llm.resayil.io/v1/</code>
                            <span class="url-badge url-badge-preferred">Preferred</span>
                        </td>
                        <td>
                            @if(app()->getLocale() === 'ar')
                                الاختصار الأساسي — متوافق مع OpenAI
                            @else
                                Primary shorthand — OpenAI-compatible
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <code>https://llmapi.resayil.io/v1/</code>
                            <span class="url-badge url-badge-new">New</span>
                        </td>
                        <td>
                            @if(app()->getLocale() === 'ar')
                                نطاق بديل — يعمل بنفس الطريقة
                            @else
                                Alternate domain — works identically
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><code>https://llm.resayil.io/api/v1/</code></td>
                        <td>
                            @if(app()->getLocale() === 'ar')
                                المسار الكامل — للتوافق مع الإصدارات القديمة
                            @else
                                Full path — legacy compatibility
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="url-note">
                @if(app()->getLocale() === 'ar')
                    جميع العناوين الثلاثة تعمل بشكل صحيح. يُنصح باستخدام <code>https://llm.resayil.io/v1/</code> أو <code>https://llmapi.resayil.io/v1/</code> للحصول على أقصر مسار متوافق مع OpenAI.
                @else
                    All three URLs work correctly. We recommend <code>https://llm.resayil.io/v1/</code> or <code>https://llmapi.resayil.io/v1/</code> for the shortest OpenAI-compatible base path.
                @endif
            </p>
        </div>

        <!-- Quick Start Example -->
        <div class="docs-quick-example">
            <div class="docs-quick-example-header">
                <span class="lang-badge">bash</span>
                <button class="copy-btn" onclick="copyCode(this)" data-code="curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H &quot;Authorization: Bearer YOUR_API_KEY&quot; \
  -H &quot;Content-Type: application/json&quot; \
  -d '{
    &quot;model&quot;: &quot;mistral&quot;,
    &quot;messages&quot;: [{&quot;role&quot;: &quot;user&quot;, &quot;content&quot;: &quot;Hello!&quot;}],
    &quot;max_tokens&quot;: 100
  }'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                    Copy
                </button>
            </div>
            <pre><code>curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "mistral",
    "messages": [{"role": "user", "content": "Hello!"}],
    "max_tokens": 100
  }'</code></pre>
            <p class="example-note">
                @if(app()->getLocale() === 'ar')
                    يمكنك أيضًا استخدام <code>https://llmapi.resayil.io/v1/chat/completions</code> أو <code>/api/v1/chat/completions</code> — كلاهما يؤدي نفس النتيجة.
                @else
                    You can also use <code>https://llmapi.resayil.io/v1/chat/completions</code> or <code>/api/v1/chat/completions</code> — both resolve identically.
                @endif
            </p>
        </div>
    </div>

    <!-- Documentation Sections -->
    <section class="docs-section">
        <div class="docs-section-header">
            <h2>
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family: 'Tajawal', sans-serif;">ابدأ الآن</span>
                @else
                    Get Started
                @endif
            </h2>
        </div>

        <div class="docs-grid">
            <!-- Getting Started -->
            <a href="{{ route('docs.getting-started') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>البدء السريع</h3>
                    <p>
                        تعلّم الأساسيات. اكتشف كيفية التسجيل والحصول على مفتاح واجهة البرمجة وإرسال أول طلب إلى LLM Resayil API.
                    </p>
                @else
                    <h3>Getting Started</h3>
                    <p>
                        Learn the basics. Discover how to register, obtain your API key, and make your first request to the LLM Resayil API.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Authentication -->
            <a href="{{ route('docs.authentication') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>المصادقة</h3>
                    <p>
                        تعرّف على مصادقة مفاتيح API وصيغة Bearer token وكيفية إدارة بيانات اعتمادك بأمان.
                    </p>
                @else
                    <h3>Authentication</h3>
                    <p>
                        Understand API key authentication, Bearer token format, and how to securely manage your credentials.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Available Models -->
            <a href="{{ route('docs.models') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>النماذج المتاحة</h3>
                    <p>
                        استكشف أكثر من 45 نموذجًا متاحًا بما في ذلك Mistral و Llama 2 و Neural Chat وغيرها. تعرّف على قدراتها واستخداماتها.
                    </p>
                @else
                    <h3>Available Models</h3>
                    <p>
                        Explore our 45+ available models including Mistral, Llama 2, Neural Chat, and more. Learn their capabilities.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Billing & Credits -->
            <a href="{{ route('docs.billing') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>الفواتير والرصيد</h3>
                    <p>
                        تعرّف على آلية نظام الرصيد ومعدلات استهلاك الرموز (Tokens) وكيفية إدارة رصيد حسابك.
                    </p>
                @else
                    <h3>Billing & Credits</h3>
                    <p>
                        Learn how our credit system works, token consumption rates, and how to manage your account balance.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Rate Limits -->
            <a href="{{ route('docs.rate-limits') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>حدود الطلبات والحصص</h3>
                    <p>
                        افهم حدود الطلبات وجداول تجديد الحصص وأفضل الممارسات للتعامل مع استجابات تجاوز الحد.
                    </p>
                @else
                    <h3>Rate Limits & Quotas</h3>
                    <p>
                        Understand request limits, quota resets, and best practices for handling rate limit responses.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Error Codes -->
            <a href="{{ route('docs.error-codes') }}" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>رموز الخطأ واستكشاف المشكلات</h3>
                    <p>
                        دليل مرجعي للأخطاء الشائعة ورموز الحالة (Status Codes) وكيفية تشخيص مشكلات API وحلّها.
                    </p>
                @else
                    <h3>Error Codes & Troubleshooting</h3>
                    <p>
                        Reference guide for common errors, status codes, and how to debug and resolve API issues.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Usage & Analytics -->
            <a href="/docs/usage" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>الاستخدام والتحليلات</h3>
                    <p>
                        تتبّع استخدامك لواجهة البرمجة، واستهلاك الرموز (Tokens)، وسجل طلباتك بشكل تفصيلي.
                    </p>
                @else
                    <h3>Usage & Analytics</h3>
                    <p>
                        Track your API usage, token consumption, and request history with detailed analytics and reporting.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Top-Up Credits -->
            <a href="/docs/topup" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>شحن الرصيد</h3>
                    <p>
                        تعلّم كيفية شراء رصيد إضافي وإدارة رصيد حسابك وطرق الدفع المتاحة.
                    </p>
                @else
                    <h3>Top-Up Credits</h3>
                    <p>
                        How to purchase additional credits, manage your balance, and understand available payment methods.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Credits System -->
            <a href="/docs/credits" class="doc-card" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
                <div class="doc-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                    <h3>نظام الرصيد</h3>
                    <p>
                        دليل شامل حول آلية عمل الرصيد، وجدول الأسعار، ومعدلات الاستهلاك لكل نموذج.
                    </p>
                @else
                    <h3>Credits System</h3>
                    <p>
                        Complete guide to how credits work, pricing tiers, and per-model token consumption rates.
                    </p>
                @endif
                <div class="doc-link-arrow">
                    @if(app()->getLocale() === 'ar')
                        اقرأ الدليل
                    @else
                        Read Guide
                    @endif
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </section>

    <!-- Next Steps -->
    <div class="docs-next-steps" @if(app()->getLocale() === 'ar') dir="rtl" style="font-family: 'Tajawal', sans-serif;" @endif>
        @if(app()->getLocale() === 'ar')
            <h2>هل أنت مستعد للبناء؟</h2>
            <p>اختر خطوتك التالية أدناه للبدء مع LLM Resayil.</p>
            <div class="cta-group">
                <a href="{{ route('docs.getting-started') }}" class="btn-primary">ابدأ مع دليل البدء السريع</a>
                <a href="{{ route('register') }}" class="btn-secondary">أنشئ حسابًا مجانيًا</a>
            </div>
            <div class="docs-footer-links">
                <p>
                    هل تحتاج إلى مساعدة؟ زر صفحة <a href="{{ route('contact') }}">التواصل</a>،
                    أو اطّلع على <a href="{{ route('welcome') }}">المميزات</a>،
                    أو عرض <a href="{{ route('pricing') }}">خطط الأسعار</a>.
                </p>
            </div>
        @else
            <h2>Ready to Build?</h2>
            <p>Choose your next step below to get started with LLM Resayil.</p>
            <div class="cta-group">
                <a href="{{ route('docs.getting-started') }}" class="btn-primary">Start with Getting Started</a>
                <a href="{{ route('register') }}" class="btn-secondary">Create Free Account</a>
            </div>
            <div class="docs-footer-links">
                <p>
                    Need help? Visit our <a href="{{ route('contact') }}">Contact page</a>,
                    check out <a href="{{ route('welcome') }}">features</a>,
                    or view <a href="{{ route('pricing') }}">pricing plans</a>.
                </p>
            </div>
        @endif
    </div>

</div>

<script>
function copyCode(btn) {
    var text = btn.dataset.code;
    var decoded = text.replace(/&quot;/g, '"').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    navigator.clipboard.writeText(decoded).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
        btn.style.color = '#4ade80';
        btn.style.borderColor = '#4ade80';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    });
}
</script>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')]
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

@endsection
