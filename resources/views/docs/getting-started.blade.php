@extends('layouts.app')

@section('title', 'Getting Started — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page Styles ── */
    .docs-page {
        background: var(--bg-secondary);
        padding: 3rem 2rem 5rem;
        min-height: 100vh;
    }

    /* Two-column layout: content + TOC sidebar */
    .docs-layout {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 220px;
        gap: 3rem;
        align-items: start;
    }

    .docs-content {
        min-width: 0;
    }

    /* Sticky TOC sidebar */
    .docs-toc {
        position: sticky;
        top: 5rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-left: 3px solid var(--gold);
        border-radius: 10px;
        padding: 1.25rem;
    }

    .docs-toc-title {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.875rem;
    }

    .docs-toc ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .docs-toc li {
        margin-bottom: 0.1rem;
    }

    .docs-toc a {
        display: block;
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-decoration: none;
        padding: 0.3rem 0.5rem;
        border-radius: 5px;
        transition: color 0.15s, background 0.15s;
        line-height: 1.4;
    }

    .docs-toc a:hover {
        color: var(--gold);
        background: rgba(212, 175, 55, 0.07);
    }

    .docs-toc a.active {
        color: var(--gold);
        background: rgba(212, 175, 55, 0.1);
        font-weight: 600;
    }

    /* Breadcrumb */
    .docs-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.875rem;
        margin-bottom: 2.5rem;
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
        opacity: 0.45;
        font-size: 0.8rem;
        margin: 0 0.15rem;
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
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: var(--text-primary);
    }

    .docs-title span {
        color: var(--gold);
    }

    .docs-intro {
        font-size: 1.05rem;
        color: var(--text-secondary);
        margin-bottom: 3rem;
        line-height: 1.8;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border);
    }

    /* Sections */
    .docs-section {
        margin-bottom: 3.5rem;
        scroll-margin-top: 5rem;
    }

    .docs-section h2 {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
        margin-top: 0;
        color: var(--text-primary);
        padding-bottom: 0.6rem;
        border-bottom: 1px solid var(--border);
    }

    .docs-section h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        margin-top: 1.75rem;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .docs-section p {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 0.975rem;
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
        font-size: 0.975rem;
    }

    .docs-section li:before {
        content: "▸";
        color: var(--gold);
        position: absolute;
        left: 0;
    }

    .docs-section strong {
        color: var(--text-primary);
    }

    /* Inline code */
    .docs-section p code,
    .docs-section li code,
    .docs-section td code {
        background: rgba(0,0,0,0.3);
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.82em;
        padding: 0.12rem 0.42rem;
        border-radius: 4px;
        border: 1px solid rgba(255,255,255,0.06);
    }

    /* Code Blocks */
    .docs-code-block {
        background: #0d1017;
        border: 1px solid var(--border);
        border-radius: 10px;
        margin: 1.5rem 0;
        overflow: hidden;
    }

    .docs-code-block-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 1rem;
        background: rgba(255,255,255,0.025);
        border-bottom: 1px solid var(--border);
    }

    .docs-code-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        font-weight: 600;
    }

    .copy-btn {
        background: none;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 5px;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 0.7rem;
        padding: 0.2rem 0.55rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: border-color 0.2s, color 0.2s;
        font-family: inherit;
    }

    .copy-btn:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .copy-btn svg {
        width: 12px;
        height: 12px;
    }

    .docs-code-block pre {
        margin: 0;
        padding: 1.25rem 1.5rem;
        overflow-x: auto;
    }

    .docs-code-block code {
        display: block;
        font-size: 0.82rem;
        line-height: 1.7;
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        white-space: pre;
    }

    /* Info / Tip Boxes */
    .docs-info-box {
        background: rgba(212, 175, 55, 0.07);
        border-left: 4px solid var(--gold);
        border-radius: 0 6px 6px 0;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-info-box p {
        margin: 0;
        font-size: 0.92rem;
        color: var(--text-secondary);
    }

    .docs-warning-box {
        background: rgba(239, 68, 68, 0.07);
        border-left: 4px solid #ef4444;
        border-radius: 0 6px 6px 0;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-warning-box p {
        margin: 0;
        font-size: 0.92rem;
        color: var(--text-secondary);
    }

    .docs-warning-box strong {
        color: #f87171;
    }

    /* Link */
    .docs-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .docs-link:hover {
        opacity: 0.8;
    }

    /* Table */
    .docs-table-wrap {
        margin: 1.5rem 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .docs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .docs-table thead {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .docs-table th {
        background: var(--bg-card);
        font-weight: 600;
        color: var(--text-primary);
        padding: 0.8rem 1rem;
        text-align: left;
        border-bottom: 2px solid var(--gold);
        font-size: 0.82rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .docs-table td {
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .docs-table tbody tr:nth-child(even) td {
        background: rgba(255,255,255,0.018);
    }

    .docs-table tbody tr:last-child td {
        border-bottom: none;
    }

    .docs-table tbody tr:hover td {
        background: rgba(212, 175, 55, 0.04);
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
        white-space: nowrap;
    }

    .url-badge-primary {
        background: rgba(212, 175, 55, 0.15);
        color: var(--gold);
        border: 1px solid rgba(212, 175, 55, 0.4);
    }

    .url-badge-new {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.35);
    }

    .url-badge-alt {
        background: rgba(148, 163, 184, 0.1);
        color: var(--text-muted);
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    /* Step indicator */
    .docs-step {
        display: flex;
        gap: 1.25rem;
        margin-bottom: 2rem;
        align-items: flex-start;
    }

    .docs-step-num {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(212, 175, 55, 0.12);
        border: 1.5px solid rgba(212, 175, 55, 0.4);
        color: var(--gold);
        font-size: 0.82rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.15rem;
    }

    .docs-step-body h3 {
        margin-top: 0;
        margin-bottom: 0.5rem;
    }

    /* Next/Prev Navigation */
    .docs-nav-footer {
        margin-top: 4rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border);
    }

    .docs-nav-btn {
        display: flex;
        flex-direction: column;
        padding: 1rem 1.5rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        text-decoration: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        min-width: 180px;
        max-width: 260px;
    }

    .docs-nav-btn:hover {
        border-color: rgba(212, 175, 55, 0.5);
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }

    .docs-nav-btn.next {
        text-align: right;
    }

    .docs-nav-btn .nav-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .docs-nav-btn.next .nav-label {
        justify-content: flex-end;
    }

    .docs-nav-btn .nav-label svg {
        width: 13px;
        height: 13px;
    }

    .docs-nav-btn .nav-title {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--gold);
    }

    /* Arabic RTL */
    [dir="rtl"] .docs-breadcrumb {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .docs-layout {
        grid-template-columns: 220px 1fr;
    }

    [dir="rtl"] .docs-toc {
        border-left: 1px solid var(--border);
        border-right: 3px solid var(--gold);
        order: -1;
    }

    [dir="rtl"] .docs-section ul {
        padding-left: 0;
        padding-right: 1.5rem;
    }

    [dir="rtl"] .docs-section li {
        padding-left: 0;
        padding-right: 1rem;
    }

    [dir="rtl"] .docs-section li:before {
        left: auto;
        right: 0;
    }

    [dir="rtl"] .docs-info-box,
    [dir="rtl"] .docs-warning-box {
        border-left: none;
        border-right: 4px solid var(--gold);
        border-radius: 6px 0 0 6px;
    }

    [dir="rtl"] .docs-warning-box {
        border-right-color: #ef4444;
    }

    [dir="rtl"] .docs-table th,
    [dir="rtl"] .docs-table td {
        text-align: right;
    }

    [dir="rtl"] .docs-step {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .docs-nav-footer {
        justify-content: flex-start;
    }

    [dir="rtl"] .docs-nav-btn.next {
        text-align: left;
    }

    [dir="rtl"] .docs-nav-btn.next .nav-label {
        justify-content: flex-start;
    }

    @media (max-width: 900px) {
        .docs-layout {
            grid-template-columns: 1fr;
        }

        .docs-toc {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .docs-title {
            font-size: 1.75rem;
            letter-spacing: -0.02em;
        }

        .docs-section h2 {
            font-size: 1.35rem;
        }

        .docs-code-block {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-nav-footer {
            flex-direction: column;
        }

        .docs-nav-btn {
            max-width: 100%;
        }

        .docs-nav-btn.next {
            text-align: left;
        }

        .docs-nav-btn.next .nav-label {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="docs-page">
    <div class="docs-layout">
        <!-- Main Content -->
        <div class="docs-content">

            <!-- Breadcrumb -->
            <div class="docs-breadcrumb">
                <a href="{{ route('welcome') }}">
                    @if(app()->getLocale() === 'ar')الرئيسية@else Home@endif
                </a>
                <span class="bc-sep">&#8250;</span>
                <a href="{{ route('docs.index') }}">
                    @if(app()->getLocale() === 'ar')التوثيق@else Documentation@endif
                </a>
                <span class="bc-sep">&#8250;</span>
                <span class="bc-current">
                    @if(app()->getLocale() === 'ar')البدء السريع@else Getting Started@endif
                </span>
            </div>

            <!-- Title -->
            <h1 class="docs-title">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family: 'Tajawal', sans-serif;">البدء <span>السريع</span></span>
                @else
                    Getting <span>Started</span>
                @endif
            </h1>
            @if(app()->getLocale() === 'ar')
                <p class="docs-intro" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    تعلّم أساسيات LLM Resayil في دقائق معدودة. سيرشدك هذا الدليل خلال التسجيل،
                    والحصول على مفتاح API، وإرسال أول طلب API.
                </p>
            @else
                <p class="docs-intro">
                    Learn the basics of LLM Resayil in just a few minutes. This guide will walk you through registration,
                    obtaining your API key, and making your first API request.
                </p>
            @endif

            <!-- Section 1: What is LLM Resayil? -->
            <section class="docs-section" id="what-is">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">ما هو LLM Resayil؟</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        LLM Resayil هي واجهة برمجة تطبيقات متوافقة مع OpenAI توفر وصولاً إلى أكثر من 45 نموذجاً لغوياً كبيراً. سواء كنت بحاجة إلى
                        استدلال سريع مع Mistral، أو تفكير قوي مع Llama 2، أو نماذج متخصصة لمهام محددة،
                        يتيح لك LLM Resayil الوصول إليها جميعاً بواجهة برمجة تطبيقات موحّدة. يعني نظام الدفع لكل رمز أنك تدفع فقط مقابل ما تستخدمه —
                        بلا اشتراكات شهرية، بلا رسوم خفية. ابدأ بـ 1,000 رصيد مجاني وتوسّع حسب احتياجاتك.
                    </p>
                @else
                    <h2>What is LLM Resayil?</h2>
                    <p>
                        LLM Resayil is an OpenAI-compatible API that provides access to 45+ large language models. Whether you need
                        fast inference with Mistral, powerful reasoning with Llama 2, or specialized models for specific tasks,
                        LLM Resayil lets you access them all with a single, unified API. Our pay-per-token pricing means you only
                        pay for what you use—no monthly subscriptions, no hidden fees. Start with 1,000 free credits and scale up
                        as your needs grow.
                    </p>
                @endif
            </section>

            <!-- Section 1b: Base URLs -->
            <section class="docs-section" id="base-urls">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">عناوين URL الأساسية</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        تدعم واجهة البرمجة ثلاثة عناوين أساسية. يمكنك استخدام أي منها — جميعها تؤدي نفس الوظيفة
                        وتدعم نفس نقاط النهاية:
                    </p>
                @else
                    <h2>Base URLs</h2>
                    <p>
                        The API supports three base URLs. You can use any of them — they all serve the same endpoints
                        and behave identically:
                    </p>
                @endif

                <div class="docs-table-wrap">
                    <table class="docs-table">
                        <thead>
                            <tr>
                                <th>@if(app()->getLocale() === 'ar')العنوان@else URL@endif</th>
                                <th>@if(app()->getLocale() === 'ar')الاستخدام@else Use Case@endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <code>https://llm.resayil.io/v1/</code>
                                    <span class="url-badge url-badge-primary">Preferred</span>
                                </td>
                                <td>
                                    @if(app()->getLocale() === 'ar')
                                        <span dir="rtl" style="font-family: 'Tajawal', sans-serif;">متوافق مع OpenAI — الأنسب مع مكتبات openai الرسمية</span>
                                    @else
                                        OpenAI-compatible shorthand — ideal for the official <code>openai</code> client library
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
                                        <span dir="rtl" style="font-family: 'Tajawal', sans-serif;">نطاق API مخصص — بديل نظيف للتكاملات التي تفضل نطاقاً منفصلاً</span>
                                    @else
                                        Dedicated API hostname — clean alternative for integrations that prefer a separate API domain
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <code>https://llm.resayil.io/api/v1/</code>
                                    <span class="url-badge url-badge-alt">Standard</span>
                                </td>
                                <td>
                                    @if(app()->getLocale() === 'ar')
                                        <span dir="rtl" style="font-family: 'Tajawal', sans-serif;">العنوان القياسي — متاح للتوافق مع التكاملات القائمة</span>
                                    @else
                                        Standard path — retained for compatibility with existing integrations
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if(app()->getLocale() === 'ar')
                    <div class="docs-info-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>نصيحة:</strong> إذا كنت تستخدم مكتبة <code>openai</code> الرسمية للـ Python أو JavaScript، فقط عيّن
                        <code>base_url='https://llm.resayil.io/v1'</code> وستعمل جميع الاستدعاءات تلقائياً. يمكنك أيضاً
                        استخدام <code>https://llmapi.resayil.io/v1</code> كبديل على النطاق المخصص.</p>
                    </div>
                @else
                    <div class="docs-info-box">
                        <p><strong>Tip:</strong> If you are using the official Python or JavaScript <code>openai</code> library, simply set
                        <code>base_url='https://llm.resayil.io/v1'</code> and all calls work automatically. You can also use
                        <code>https://llmapi.resayil.io/v1</code> as an alternative on the dedicated API hostname.</p>
                    </div>
                @endif
            </section>

            <!-- Section 2: Getting Your API Key -->
            <section class="docs-section" id="api-key">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">الحصول على مفتاح API</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        لاستخدام واجهة برمجة تطبيقات LLM Resayil، ستحتاج إلى مفتاح API. إليك كيفية الحصول عليه في ثلاث خطوات بسيطة:
                    </p>

                    <div class="docs-step">
                        <div class="docs-step-num">1</div>
                        <div class="docs-step-body">
                            <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">التسجيل أو تسجيل الدخول</h3>
                            <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                                قم بزيارة <a href="{{ route('register') }}" class="docs-link">https://llm.resayil.io/register</a> لإنشاء
                                حساب مجاني. إذا كان لديك حساب بالفعل، فقط <a href="{{ route('login') }}" class="docs-link">سجّل دخولك</a>.
                                يستغرق التسجيل أقل من دقيقتين ويأتي مع 1,000 رصيد مجاني للبدء.
                            </p>
                        </div>
                    </div>

                    <div class="docs-step">
                        <div class="docs-step-num">2</div>
                        <div class="docs-step-body">
                            <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">الانتقال إلى مفاتيح API</h3>
                            <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                                بعد تسجيل الدخول، انتقل إلى لوحة التحكم وانقر على <strong>"API Keys"</strong> في الشريط الجانبي الأيسر. تعرض هذه الصفحة
                                جميع مفاتيح API النشطة لديك وتتيح لك إدارتها.
                            </p>
                        </div>
                    </div>

                    <div class="docs-step">
                        <div class="docs-step-num">3</div>
                        <div class="docs-step-body">
                            <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">نسخ مفتاح API</h3>
                            <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                                انقر على زر <strong>"Generate New Key"</strong> لإنشاء مفتاح API جديد. سيُعرض مفتاحك مرة واحدة — انسخه
                                فوراً واحفظه في مكان آمن. ستستخدم هذا المفتاح لمصادقة جميع طلبات API. لا تشارك مفتاح API علناً أو تضعه في نظام التحكم بالإصدار.
                            </p>
                        </div>
                    </div>
                @else
                    <h2>Getting Your API Key</h2>
                    <p>
                        To use the LLM Resayil API, you'll need an API key. Here's how to get one in three simple steps:
                    </p>

                    <div class="docs-step">
                        <div class="docs-step-num">1</div>
                        <div class="docs-step-body">
                            <h3>Step 1: Register or Log In</h3>
                            <p>
                                Visit <a href="{{ route('register') }}" class="docs-link">https://llm.resayil.io/register</a> to create
                                a free account. If you already have an account, simply <a href="{{ route('login') }}" class="docs-link">log in</a>.
                                Registration takes less than two minutes and comes with 1,000 free credits to get started.
                            </p>
                        </div>
                    </div>

                    <div class="docs-step">
                        <div class="docs-step-num">2</div>
                        <div class="docs-step-body">
                            <h3>Step 2: Navigate to API Keys</h3>
                            <p>
                                After logging in, go to your dashboard and click on <strong>"API Keys"</strong> in the left sidebar. This page
                                shows all your active API keys and allows you to manage them.
                            </p>
                        </div>
                    </div>

                    <div class="docs-step">
                        <div class="docs-step-num">3</div>
                        <div class="docs-step-body">
                            <h3>Step 3: Copy Your API Key</h3>
                            <p>
                                Click the <strong>"Generate New Key"</strong> button to create a new API key. Your key will be displayed once—copy it
                                immediately and store it somewhere safe. You'll use this key to authenticate all your API requests. Never share your
                                API key publicly or commit it to version control.
                            </p>
                        </div>
                    </div>
                @endif

                @if(app()->getLocale() === 'ar')
                    <div class="docs-warning-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>نصيحة أمنية:</strong> عامل مفتاح API كلمة مرور. خزّنه في متغيرات البيئة، وليس في الكود.
                        إذا كشفت مفتاحك عن طريق الخطأ، ألغِه فوراً من صفحة API Keys وأنشئ مفتاحاً جديداً.</p>
                    </div>
                @else
                    <div class="docs-warning-box">
                        <p><strong>Security Tip:</strong> Treat your API key like a password. Store it in environment variables, not in code.
                        If you accidentally expose your key, revoke it immediately from the API Keys page and generate a new one.</p>
                    </div>
                @endif
            </section>

            <!-- Section 3: Your First Request -->
            <section class="docs-section" id="first-request">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">أول طلب لك</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        الآن بعد أن أصبح لديك مفتاح API، لنُرسل أول طلب API. تستخدم واجهة برمجة LLM Resayil نفس تنسيق
                        نقطة نهاية Chat Completions من OpenAI، لذا إذا سبق لك استخدام OpenAI، ستجد كل شيء مألوفاً.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">فهم ترويسة التفويض</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        يجب أن يتضمن كل طلب API ترويسة <strong>Authorization</strong> تحتوي على مفتاح API بالتنسيق التالي:
                    </p>
                @else
                    <h2>Your First Request</h2>
                    <p>
                        Now that you have an API key, let's make your first API request. The LLM Resayil API uses the same format as OpenAI's
                        Chat Completions endpoint, so if you've used OpenAI before, you'll feel right at home.
                    </p>

                    <h3>Understanding the Authorization Header</h3>
                    <p>
                        Every API request must include an <strong>Authorization</strong> header with your API key in the following format:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">Authorization Header Format</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="Authorization: Bearer YOUR_API_KEY">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>Authorization: Bearer YOUR_API_KEY</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        استبدل <strong>YOUR_API_KEY</strong> بمفتاح API الفعلي الذي أنشأته في الخطوة السابقة.
                        كلمة "Bearer" مطلوبة وحساسة لحالة الأحرف.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">إرسال طلب Chat Completion</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        إليك مثال كامل لإرسال طلب chat completion باستخدام cURL. انسخه واستبدل
                        <strong>YOUR_API_KEY</strong> بمفتاحك الفعلي:
                    </p>
                @else
                    <p>
                        Replace <strong>YOUR_API_KEY</strong> with the actual API key you generated in the previous step.
                        The word "Bearer" is required and case-sensitive.
                    </p>

                    <h3>Making a Chat Completion Request</h3>
                    <p>
                        Here's a complete example of making a chat completion request using cURL. Copy this and replace
                        <strong>YOUR_API_KEY</strong> with your actual key:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash — Preferred (/v1/ shorthand)</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '"'"'{
    "model": "mistral",
    "messages": [
      {
        "role": "user",
        "content": "Hello! What is your name?"
      }
    ],
    "max_tokens": 100
  }'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "mistral",
    "messages": [
      {
        "role": "user",
        "content": "Hello! What is your name?"
      }
    ],
    "max_tokens": 100
  }'</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">يمكنك أيضاً استخدام النطاق المخصص أو العنوان القياسي البديل:</p>
                @else
                    <p>You can also use the dedicated API hostname or the standard alternative path:</p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash — Dedicated API hostname</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llmapi.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '"'"'{"model": "mistral", "messages": [{"role": "user", "content": "Hello! What is your name?"}], "max_tokens": 100}'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llmapi.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Hello! What is your name?"}], "max_tokens": 100}'</code></pre>
                </div>

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash — Standard path (alternative)</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '"'"'{"model": "mistral", "messages": [{"role": "user", "content": "Hello! What is your name?"}], "max_tokens": 100}'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Hello! What is your name?"}], "max_tokens": 100}'</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">استخدام مكتبة OpenAI الرسمية (Python)</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        بما أن واجهة LLM Resayil متوافقة مع OpenAI، يمكنك استخدام مكتبة <code>openai</code> الرسمية مع تعديل
                        <code>base_url</code> فقط:
                    </p>
                @else
                    <h3>Using the Official OpenAI Library (Python)</h3>
                    <p>
                        Because LLM Resayil is OpenAI-compatible, you can use the official <code>openai</code> library by simply
                        changing the <code>base_url</code>:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">python — openai library</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="import os
from openai import OpenAI

client = OpenAI(
    api_key=os.getenv('LLM_RESAYIL_API_KEY'),
    base_url='https://llm.resayil.io/v1'  # or https://llmapi.resayil.io/v1
)

response = client.chat.completions.create(
    model='mistral',
    messages=[{'role': 'user', 'content': 'Hello! What is your name?'}],
    max_tokens=100
)
print(response.choices[0].message.content)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>import os
from openai import OpenAI

client = OpenAI(
    api_key=os.getenv('LLM_RESAYIL_API_KEY'),
    base_url='https://llm.resayil.io/v1'  # or https://llmapi.resayil.io/v1
)

response = client.chat.completions.create(
    model='mistral',
    messages=[{'role': 'user', 'content': 'Hello! What is your name?'}],
    max_tokens=100
)
print(response.choices[0].message.content)</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">شرح معاملات الطلب</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">إليك ما يعنيه كل معامل في الطلب:</p>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>model:</strong> اسم النموذج المراد استخدامه (مثلاً "mistral"، "llama2"، "neural-chat"). راجع <a href="{{ route('docs.models') }}" class="docs-link">دليل النماذج</a> للخيارات المتاحة.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>messages:</strong> مصفوفة من كائنات الرسائل مع "role" (user، assistant، أو system) و"content" (النص).</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>max_tokens:</strong> الحد الأقصى للرموز التي ينبغي للنموذج توليدها في استجابته.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>temperature (اختياري):</strong> يتحكم في العشوائية. القيم المنخفضة (0.1) تجعل الاستجابات أكثر حتمية؛ القيم الأعلى (0.9) تجعلها أكثر إبداعاً.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>top_p (اختياري):</strong> يتحكم في التنوع عبر أخذ عينات النواة. القيمة المعتادة هي 0.9.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>stream (اختياري):</strong> اضبطه على <code>true</code> لتمكين استجابات البث عبر SSE — مدعوم بالكامل.</li>
                    </ul>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">فهم الاستجابة</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        عند نجاح طلبك، ستتلقى استجابة JSON. إليك شكل استجابة نموذجية:
                    </p>
                @else
                    <h3>Request Parameters Explained</h3>
                    <p>Here's what each parameter in the request means:</p>
                    <ul>
                        <li><strong>model:</strong> The name of the model to use (e.g., "mistral", "llama2", "neural-chat"). See our <a href="{{ route('docs.models') }}" class="docs-link">Models guide</a> for available options.</li>
                        <li><strong>messages:</strong> An array of message objects with "role" (user, assistant, or system) and "content" (the text).</li>
                        <li><strong>max_tokens:</strong> The maximum number of tokens the model should generate in its response.</li>
                        <li><strong>temperature (optional):</strong> Controls randomness. Lower values (0.1) make responses more deterministic; higher values (0.9) make them more creative.</li>
                        <li><strong>top_p (optional):</strong> Controls diversity via nucleus sampling. Typical value is 0.9.</li>
                        <li><strong>stream (optional):</strong> Set to <code>true</code> to enable streaming responses via SSE — fully supported.</li>
                    </ul>

                    <h3>Understanding the Response</h3>
                    <p>
                        When your request is successful, you'll receive a JSON response. Here's what a typical response looks like:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">json — Example Response</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='{
  "id": "chatcmpl-123456",
  "object": "chat.completion",
  "created": 1234567890,
  "model": "mistral",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "My name is Mistral. I am an AI assistant..."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 10,
    "completion_tokens": 25,
    "total_tokens": 35
  }
}'>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>{
  "id": "chatcmpl-123456",
  "object": "chat.completion",
  "created": 1234567890,
  "model": "mistral",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "My name is Mistral. I am an AI assistant..."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 10,
    "completion_tokens": 25,
    "total_tokens": 35
  }
}</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">الحقول الرئيسية في الاستجابة:</p>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>choices:</strong> مصفوفة تحتوي على استجابة النموذج. الاختيار الأول (index 0) يحتوي على الرسالة الفعلية.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>message.content:</strong> نص الاستجابة الفعلي من النموذج.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>usage:</strong> تفصيل استهلاك الرموز. استخدمه لتقدير التكاليف.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>finish_reason:</strong> سبب توقف النموذج (عادةً "stop" عند الاكتمال الناجح).</li>
                    </ul>
                @else
                    <p>Key fields in the response:</p>
                    <ul>
                        <li><strong>choices:</strong> An array containing the model's response. The first choice (index 0) contains the actual message.</li>
                        <li><strong>message.content:</strong> The actual text response from the model.</li>
                        <li><strong>usage:</strong> Token consumption breakdown. Use this to estimate costs.</li>
                        <li><strong>finish_reason:</strong> Why the model stopped (usually "stop" for successful completion).</li>
                    </ul>
                @endif
            </section>

            <!-- Section 4: Streaming -->
            <section class="docs-section" id="streaming">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">البث (Streaming)</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        تدعم واجهة البرمجة البث عبر Server-Sent Events (SSE) بشكل كامل. أضف <code>"stream": true</code> إلى طلبك
                        لتلقي الرموز فور توليدها، مما يتيح تجربة مستخدم أكثر استجابة:
                    </p>
                @else
                    <h2>Streaming</h2>
                    <p>
                        The API supports streaming via Server-Sent Events (SSE) and is fully working. Add <code>"stream": true</code> to your request
                        to receive tokens as they are generated, enabling a more responsive user experience:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash — Streaming Example</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: text/event-stream" \
  -d '"'"'{"model": "mistral", "messages": [{"role": "user", "content": "Tell me a short story."}], "stream": true}'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: text/event-stream" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Tell me a short story."}], "stream": true}'</code></pre>
                </div>

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">python — Streaming with openai library</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="import os
from openai import OpenAI

client = OpenAI(
    api_key=os.getenv('LLM_RESAYIL_API_KEY'),
    base_url='https://llm.resayil.io/v1'
)

stream = client.chat.completions.create(
    model='mistral',
    messages=[{'role': 'user', 'content': 'Tell me a short story.'}],
    stream=True
)

for chunk in stream:
    if chunk.choices[0].delta.content:
        print(chunk.choices[0].delta.content, end='', flush=True)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>import os
from openai import OpenAI

client = OpenAI(
    api_key=os.getenv('LLM_RESAYIL_API_KEY'),
    base_url='https://llm.resayil.io/v1'
)

stream = client.chat.completions.create(
    model='mistral',
    messages=[{'role': 'user', 'content': 'Tell me a short story.'}],
    stream=True
)

for chunk in stream:
    if chunk.choices[0].delta.content:
        print(chunk.choices[0].delta.content, end='', flush=True)</code></pre>
                </div>
            </section>

            <!-- Section 5: What's Next? -->
            <section class="docs-section" id="whats-next">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">ما التالي؟</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        تهانينا على إرسال أول طلب API! إليك بعض الخطوات التالية المقترحة للمتابعة في البناء:
                    </p>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>استكشاف النماذج:</strong> اذهب إلى دليل <a href="{{ route('docs.models') }}" class="docs-link">النماذج المتاحة</a> للتعرف على جميع النماذج الـ 45+ وإمكانياتها.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>تعلّم المصادقة:</strong> اقرأ دليل <a href="{{ route('docs.authentication') }}" class="docs-link">المصادقة</a> لأفضل الممارسات في إدارة مفاتيح API بأمان.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>فهم الفوترة:</strong> زر صفحة <a href="{{ route('docs.billing') }}" class="docs-link">الفوترة والرصيد</a> لفهم استهلاك الرموز والتسعير.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>معالجة الأخطاء:</strong> راجع دليل <a href="{{ route('docs.error-codes') }}" class="docs-link">رموز الأخطاء</a> لتعلّم كيفية التعامل مع المشكلات الشائعة.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>حدود المعدّل:</strong> تعرّف على حدود المعدّل وكيفية تطبيق استراتيجيات التراجع في دليل <a href="{{ route('docs.rate-limits') }}" class="docs-link">حدود المعدّل</a>.</li>
                    </ul>
                @else
                    <h2>What's Next?</h2>
                    <p>
                        Congratulations on making your first API request! Here are some suggested next steps to continue building:
                    </p>
                    <ul>
                        <li><strong>Explore Models:</strong> Head to the <a href="{{ route('docs.models') }}" class="docs-link">Available Models</a> guide to learn about all 45+ models and their capabilities.</li>
                        <li><strong>Learn Authentication:</strong> Read the <a href="{{ route('docs.authentication') }}" class="docs-link">Authentication</a> guide for best practices on managing API keys securely.</li>
                        <li><strong>Understand Billing:</strong> Visit the <a href="{{ route('docs.billing') }}" class="docs-link">Billing & Credits</a> page to understand token consumption and pricing.</li>
                        <li><strong>Handle Errors:</strong> Check out the <a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> guide to learn how to handle common issues.</li>
                        <li><strong>Rate Limits:</strong> Learn about rate limits and how to implement backoff strategies in the <a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> guide.</li>
                    </ul>
                @endif
            </section>

            <!-- Common Issues -->
            <section class="docs-section" id="common-issues">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">المشكلات الشائعة</h2>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">خطأ 401 Unauthorized</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        يعني هذا الخطأ أن مفتاح API مفقود أو غير صالح أو بتنسيق خاطئ في ترويسة Authorization.
                        تحقق مرتين من استخدامك للمفتاح الصحيح وأنه مسبوق بـ "Bearer ".
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">خطأ 429 Too Many Requests</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        تجاوزت حد المعدّل للنافذة الزمنية الحالية. انتظر لحظة قبل المحاولة مجدداً، أو ارقِّ إلى
                        طبقة اشتراك أعلى لحدود أكبر. راجع دليل <a href="{{ route('docs.rate-limits') }}" class="docs-link">حدود المعدّل</a> للتفاصيل.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">انتهاء مهلة الاتصال</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        إذا انتهت مهلة طلبك، أعد المحاولة بقيمة مهلة أطول. يمكن أن تستغرق الاتصالات الباردة بواجهة البرمجة 1-3 ثواني.
                        بعد الاتصال، عادةً ما تكون الطلبات اللاحقة أسرع بكثير.
                    </p>
                @else
                    <h2>Common Issues</h2>

                    <h3>401 Unauthorized Error</h3>
                    <p>
                        This error means your API key is missing, invalid, or incorrectly formatted in the Authorization header.
                        Double-check that you're using the correct key and that it's prefixed with "Bearer ".
                    </p>

                    <h3>429 Too Many Requests</h3>
                    <p>
                        You've exceeded your rate limit for the current time window. Wait a moment before retrying, or upgrade your
                        subscription tier for higher limits. See the <a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> guide for details.
                    </p>

                    <h3>Connection Timeout</h3>
                    <p>
                        If your request times out, try again with a longer timeout value. Cold connections to our API can take 1-3 seconds.
                        Once connected, subsequent requests are typically much faster.
                    </p>
                @endif

                @if(app()->getLocale() === 'ar')
                    <div class="docs-info-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>تحتاج مساعدة؟</strong> إذا كنت عالقاً، <a href="{{ route('contact') }}" class="docs-link">تواصل مع فريق الدعم</a>
                        أو زر دليل <a href="{{ route('docs.error-codes') }}" class="docs-link">رموز الأخطاء</a> للمزيد من نصائح استكشاف الأخطاء.</p>
                    </div>
                @else
                    <div class="docs-info-box">
                        <p><strong>Need Help?</strong> If you're stuck, <a href="{{ route('contact') }}" class="docs-link">contact our support team</a>
                        or visit the <a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> guide for more troubleshooting tips.</p>
                    </div>
                @endif
            </section>

            <!-- Next Navigation -->
            <div class="docs-nav-footer">
                <a href="{{ route('docs.authentication') }}" class="docs-nav-btn next">
                    <span class="nav-label">
                        @if(app()->getLocale() === 'ar')التالي@else Next@endif
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <span class="nav-title">
                        @if(app()->getLocale() === 'ar')دليل المصادقة@else Authentication Guide@endif
                    </span>
                </a>
            </div>

        </div>

        <!-- TOC Sidebar -->
        <aside class="docs-toc" aria-label="On this page">
            <div class="docs-toc-title">
                @if(app()->getLocale() === 'ar')على هذه الصفحة@else On this page@endif
            </div>
            <ul>
                <li><a href="#what-is">@if(app()->getLocale() === 'ar')ما هو LLM Resayil@else What is LLM Resayil@endif</a></li>
                <li><a href="#base-urls">@if(app()->getLocale() === 'ar')عناوين URL الأساسية@else Base URLs@endif</a></li>
                <li><a href="#api-key">@if(app()->getLocale() === 'ar')الحصول على مفتاح API@else Getting Your API Key@endif</a></li>
                <li><a href="#first-request">@if(app()->getLocale() === 'ar')أول طلب@else Your First Request@endif</a></li>
                <li><a href="#streaming">@if(app()->getLocale() === 'ar')البث@else Streaming@endif</a></li>
                <li><a href="#whats-next">@if(app()->getLocale() === 'ar')ما التالي@else What's Next@endif</a></li>
                <li><a href="#common-issues">@if(app()->getLocale() === 'ar')المشكلات الشائعة@else Common Issues@endif</a></li>
            </ul>
        </aside>

    </div>
</div>

<script>
// Copy button handler
function copyCode(btn) {
    var text = btn.dataset.code || '';
    navigator.clipboard.writeText(text).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
        btn.style.color = '#4ade80';
        btn.style.borderColor = '#4ade80';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('.docs-toc a').forEach(function(link) {
    link.addEventListener('click', function(e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Active TOC link on scroll
(function() {
    var sections = document.querySelectorAll('.docs-section[id]');
    var tocLinks = document.querySelectorAll('.docs-toc a');

    function onScroll() {
        var scrollY = window.scrollY + 120;
        var current = '';
        sections.forEach(function(section) {
            if (section.offsetTop <= scrollY) {
                current = section.id;
            }
        });
        tocLinks.forEach(function(link) {
            link.classList.toggle('active', link.getAttribute('href') === '#' + current);
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
</script>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Getting Started', 'url' => route('docs.getting-started')]
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
