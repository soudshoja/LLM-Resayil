@extends('layouts.app')

@section('title', 'Authentication — API Documentation')

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

    /* Simple code block (no header) */
    .docs-code-block-simple {
        background: #0d1017;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin: 1.5rem 0;
        overflow-x: auto;
    }

    .docs-code-block-simple code {
        display: block;
        font-size: 0.82rem;
        line-height: 1.7;
        color: #a0d468;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        white-space: pre;
    }

    /* Info / Warning Boxes */
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

    /* Next/Prev Navigation */
    .docs-nav-footer {
        margin-top: 4rem;
        display: flex;
        justify-content: space-between;
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
        margin-left: auto;
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

    [dir="rtl"] .docs-nav-btn.next {
        margin-left: 0;
        margin-right: auto;
        text-align: left;
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

        .docs-table th,
        .docs-table td {
            padding: 0.6rem 0.75rem;
            font-size: 0.82rem;
        }

        .docs-nav-footer {
            flex-direction: column;
        }

        .docs-nav-btn {
            max-width: 100%;
        }

        .docs-nav-btn.next {
            margin-left: 0;
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
                    @if(app()->getLocale() === 'ar')المصادقة@else Authentication@endif
                </span>
            </div>

            <!-- Title -->
            <h1 class="docs-title">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family: 'Tajawal', sans-serif;" dir="rtl">مصادقة <span>API</span></span>
                @else
                    API <span>Authentication</span>
                @endif
            </h1>
            @if(app()->getLocale() === 'ar')
                <p class="docs-intro" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                    تعرّف على كيفية المصادقة مع واجهة برمجة تطبيقات LLM Resayil باستخدام مفاتيح API ورموز Bearer.
                    افهم أفضل الممارسات لتأمين بيانات اعتمادك وإدارة مفاتيح API الخاصة بك.
                </p>
            @else
                <p class="docs-intro">
                    Learn how to authenticate with the LLM Resayil API using API keys and Bearer tokens.
                    Understand best practices for securing your credentials and managing your API keys.
                </p>
            @endif

            <!-- Section 0: Base URLs -->
            <section class="docs-section" id="base-urls">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">عناوين URL الأساسية</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        تدعم واجهة برمجة التطبيقات ثلاثة عناوين أساسية. اختر ما يناسب حالة الاستخدام لديك:
                    </p>
                @else
                    <h2>Base URLs</h2>
                    <p>
                        The API supports three base URLs. Choose the one that best fits your use case:
                    </p>
                @endif

                <div class="docs-table-wrap">
                    <table class="docs-table">
                        <thead>
                            <tr>
                                @if(app()->getLocale() === 'ar')
                                    <th>العنوان</th>
                                    <th>الاستخدام</th>
                                @else
                                    <th>URL</th>
                                    <th>Use Case</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <code>https://llm.resayil.io/v1/</code>
                                    <span class="url-badge url-badge-primary">Preferred</span>
                                </td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">متوافق مع OpenAI — الأنسب مع مكتبات OpenAI الرسمية وأي أداة تعمل مع OpenAI</td>
                                @else
                                    <td>OpenAI-compatible shorthand — ideal for OpenAI client libraries and any tool that targets the OpenAI API</td>
                                @endif
                            </tr>
                            <tr>
                                <td>
                                    <code>https://llmapi.resayil.io/v1/</code>
                                    <span class="url-badge url-badge-primary" style="background:rgba(34,197,94,0.1);color:#4ade80;border-color:rgba(34,197,94,0.35);">New</span>
                                </td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">نطاق API مخصص — بديل نظيف لمن يفضل نطاقاً منفصلاً لواجهة برمجة التطبيقات</td>
                                @else
                                    <td>Dedicated API hostname — a clean alternative for integrations that prefer a separate API domain</td>
                                @endif
                            </tr>
                            <tr>
                                <td>
                                    <code>https://llm.resayil.io/api/v1/</code>
                                    <span class="url-badge url-badge-alt">Standard</span>
                                </td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">العنوان القياسي — متاح للتوافق مع التكاملات القائمة</td>
                                @else
                                    <td>Standard path — retained for compatibility with existing integrations</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if(app()->getLocale() === 'ar')
                    <div class="docs-info-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>ملاحظة:</strong> جميع العناوين الثلاثة تؤدي نفس الوظيفة وتدعم نفس نقاط النهاية. نوصي باستخدام
                        <code>https://llm.resayil.io/v1/</code> في المشاريع الجديدة لأنه أقصر ومتوافق مباشرةً مع OpenAI SDK.
                        يمكن أيضاً استخدام <code>https://llmapi.resayil.io/v1/</code> كبديل على النطاق المخصص.</p>
                    </div>
                @else
                    <div class="docs-info-box">
                        <p><strong>Note:</strong> All three URLs point to the same API and support identical endpoints. We recommend
                        <code>https://llm.resayil.io/v1/</code> for new projects — it is shorter and drops directly into the OpenAI SDK
                        <code>base_url</code> parameter. <code>https://llmapi.resayil.io/v1/</code> is also available as a dedicated API hostname.</p>
                    </div>
                @endif
            </section>

            <!-- Section 1: API Key Authentication -->
            <section class="docs-section" id="api-key-auth">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">المصادقة بمفتاح API</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        يعتمد LLM Resayil على مصادقة Bearer token باستخدام مفاتيح API. يجب أن يتضمن كل طلب API مفتاحك
                        في ترويسة Authorization. مفاتيح API هي بيانات اعتماد دائمة مرتبطة بحسابك ويمكن إدارتها من لوحة التحكم.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">كيف تعمل مفاتيح API</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        عند إنشاء مفتاح API:
                    </p>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>مرتبط بحسابك:</strong> المفتاح مقيّد بحسابك ويرث طبقة اشتراكك وحدود رصيدك.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>لا تنتهي صلاحيته:</strong> مفاتيح API دائمة حتى تقوم بإلغائها يدوياً.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>يمثّل وصولاً كاملاً:</strong> أي شخص يمتلك مفتاحك يمكنه إجراء طلبات API واستهلاك رصيدك.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>مفاتيح متعددة مسموحة:</strong> يمكنك إنشاء مفاتيح متعددة لتطبيقات أو أعضاء فريق مختلفين.</li>
                    </ul>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">البحث عن المفاتيح وتوليدها</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        لإدارة مفاتيح API الخاصة بك:
                    </p>
                @else
                    <h2>API Key Authentication</h2>
                    <p>
                        LLM Resayil uses Bearer token authentication with API keys. Every API request must include your API key
                        in the Authorization header. API keys are permanent credentials associated with your account and can be
                        managed from your dashboard.
                    </p>

                    <h3>How API Keys Work</h3>
                    <p>
                        When you create an API key:
                    </p>
                    <ul>
                        <li><strong>It's account-specific:</strong> The key is tied to your account and inherits your subscription tier and credit limits.</li>
                        <li><strong>It never expires:</strong> API keys are permanent until you manually revoke them.</li>
                        <li><strong>It represents full access:</strong> Anyone with your key can make API requests and consume your credits.</li>
                        <li><strong>Multiple keys allowed:</strong> You can create multiple keys for different applications or teammates.</li>
                    </ul>

                    <h3>Finding and Generating Keys</h3>
                    <p>
                        To manage your API keys:
                    </p>
                @endif
                <ol style="list-style: decimal; margin-left: 2rem; color: var(--text-secondary); line-height: 1.8;">
                    @if(app()->getLocale() === 'ar')
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">سجّل الدخول إلى <a href="{{ url('/dashboard') }}" class="docs-link">لوحة تحكم LLM Resayil</a></li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">انتقل إلى <strong>API Keys</strong> في الشريط الجانبي الأيسر</li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">انقر على <strong>"Generate New Key"</strong> لإنشاء مفتاح جديد</li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">سيظهر مفتاحك مرة واحدة فقط — انسخه فوراً واحفظه في مكان آمن</li>
                    @else
                        <li style="margin-bottom:0.5rem;">Log in to your <a href="{{ url('/dashboard') }}" class="docs-link">LLM Resayil dashboard</a></li>
                        <li style="margin-bottom:0.5rem;">Navigate to <strong>API Keys</strong> in the left sidebar</li>
                        <li style="margin-bottom:0.5rem;">Click <strong>"Generate New Key"</strong> to create a new key</li>
                        <li style="margin-bottom:0.5rem;">Your key will display once—copy it immediately and store it securely</li>
                    @endif
                </ol>

                @if(app()->getLocale() === 'ar')
                    <div class="docs-warning-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>مهم:</strong> تُعرض مفاتيح API مرة واحدة فقط بعد إنشائها. إذا فقدت مفتاحك، ستحتاج إلى إنشاء مفتاح جديد.
                        احفظ مفاتيحك في موقع آمن مثل مدير كلمات المرور.</p>
                    </div>
                @else
                    <div class="docs-warning-box">
                        <p><strong>Important:</strong> API keys are displayed only once after creation. If you lose your key, you'll need
                        to generate a new one. Save your keys in a secure location like a password manager.</p>
                    </div>
                @endif
            </section>

            <!-- Section 2: Bearer Token Format -->
            <section class="docs-section" id="bearer-format">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">تنسيق ترويسة التفويض</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        يجب أن يتضمن كل طلب إلى واجهة برمجة تطبيقات LLM Resayil ترويسة Authorization تحتوي على مفتاح API بتنسيق Bearer token.
                        يجب تنسيق الترويسة بالضبط كما هو موضح أدناه:
                    </p>
                @else
                    <h2>Authorization Header Format</h2>
                    <p>
                        Every request to the LLM Resayil API must include an Authorization header with your API key in Bearer token format.
                        The header must be formatted exactly as shown below:
                    </p>
                @endif

                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">Authorization Header</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="Authorization: Bearer YOUR_API_KEY">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>Authorization: Bearer YOUR_API_KEY</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        استبدل <strong>YOUR_API_KEY</strong> بمفتاح API الفعلي الخاص بك. يجب تضمين كلمة "Bearer" وهي حساسة لحالة الأحرف.
                        يجب أن تفصل مسافة بين "Bearer" ومفتاحك.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">أمثلة بلغات برمجية مختلفة</h3>
                @else
                    <p>
                        Replace <strong>YOUR_API_KEY</strong> with your actual API key. The word "Bearer" must be included and is case-sensitive.
                        A space must separate "Bearer" and your key.
                    </p>

                    <h3>Examples in Different Languages</h3>
                @endif

                <p style="color:var(--text-secondary); font-size:0.975rem; margin-bottom:0.5rem;">
                    <strong>cURL</strong>
                    @if(app()->getLocale() === 'ar')
                        <span style="font-size:0.82rem;color:var(--text-muted)"> — العنوان الأساسي المفضّل (متوافق مع OpenAI)</span>
                    @else
                        <span style="font-size:0.82rem;color:var(--text-muted);"> — preferred base URL (OpenAI-compatible)</span>
                    @endif
                </p>
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '"'"'{"model": "mistral", "messages": [{"role": "user", "content": "Hello"}]}'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llm.resayil.io/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Hello"}]}'</code></pre>
                </div>

                <p style="color:var(--text-secondary); font-size:0.975rem; margin-bottom:0.5rem;">
                    <strong>cURL</strong>
                    @if(app()->getLocale() === 'ar')
                        <span style="font-size:0.82rem;color:var(--text-muted)"> — العنوان القياسي البديل</span>
                    @else
                        <span style="font-size:0.82rem;color:var(--text-muted);"> — standard alternative base URL</span>
                    @endif
                </p>
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">bash</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '"'"'{"model": "mistral", "messages": [{"role": "user", "content": "Hello"}]}'"'"''>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "mistral", "messages": [{"role": "user", "content": "Hello"}]}'</code></pre>
                </div>

                <p style="color:var(--text-secondary); font-size:0.975rem; margin-bottom:0.5rem;"><strong>JavaScript (fetch):</strong></p>
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">javascript</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="const response = await fetch('https://llm.resayil.io/v1/chat/completions', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    model: 'mistral',
    messages: [{ role: 'user', content: 'Hello' }]
  })
});
const data = await response.json();">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>const response = await fetch('https://llm.resayil.io/v1/chat/completions', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    model: 'mistral',
    messages: [{ role: 'user', content: 'Hello' }]
  })
});
const data = await response.json();</code></pre>
                </div>

                <p style="color:var(--text-secondary); font-size:0.975rem; margin-bottom:0.5rem;"><strong>Python (requests):</strong></p>
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">python</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="import requests

response = requests.post(
  'https://llm.resayil.io/v1/chat/completions',
  headers={
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  json={
    'model': 'mistral',
    'messages': [{'role': 'user', 'content': 'Hello'}]
  }
)
data = response.json()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>import requests

response = requests.post(
  'https://llm.resayil.io/v1/chat/completions',
  headers={
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  },
  json={
    'model': 'mistral',
    'messages': [{'role': 'user', 'content': 'Hello'}]
  }
)
data = response.json()</code></pre>
                </div>
            </section>

            <!-- Section 3: API Key Lifecycle -->
            <section class="docs-section" id="key-lifecycle">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">دورة حياة مفتاح API</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        إن فهم كيفية إدارة مفاتيح API أمر بالغ الأهمية للأمان واستمرارية العمليات.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">توليد المفاتيح</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">عند توليد مفتاح API جديد:</p>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span>ستشاهد المفتاح مرة واحدة فقط على شاشة التأكيد</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span>انسخه واحفظه فوراً — لن تراه مجدداً</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span>المفتاح نشط فوراً وجاهز للاستخدام</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span>يمكن أن تكون عدة مفاتيح نشطة في وقت واحد</li>
                    </ul>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">أفضل الممارسات لإدارة المفاتيح</h3>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>لا تضعها في نظام التحكم بالإصدار:</strong> خزّن المفاتيح في متغيرات البيئة أو أدوات إدارة الأسرار، وليس في الكود أو ملفات .env المحفوظة في git.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>استخدم مفاتيح مختلفة لتطبيقات مختلفة:</strong> أنشئ مفاتيح منفصلة لبيئات التطوير والاختبار والإنتاج.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>دوّر المفاتيح بانتظام:</strong> حتى لو لم تتعرض للاختراق، قم بتدوير المفاتيح دورياً للحفاظ على الأمان.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>استخدم الصلاحيات الأدنى:</strong> إذا أتاحت منصتك ذلك، استخدم مفاتيح ذات وصول محدود بدلاً من الوصول الكامل للحساب.</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><strong>راقب استخدام المفاتيح:</strong> راجع بانتظام المفاتيح النشطة وألغِ غير المستخدمة منها.</li>
                    </ul>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">إلغاء المفاتيح</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        إذا كنت تشك في تعرّض مفتاح للاختراق أو لم تعد بحاجة إليه:
                    </p>
                @else
                    <h2>API Key Lifecycle</h2>
                    <p>
                        Understanding how to manage your API keys is crucial for security and operational continuity.
                    </p>

                    <h3>Key Generation</h3>
                    <p>When you generate a new API key:</p>
                    <ul>
                        <li>You'll see the key once on the confirmation screen</li>
                        <li>Copy and save it immediately—you won't see it again</li>
                        <li>The key is immediately active and ready to use</li>
                        <li>Multiple keys can be active simultaneously</li>
                    </ul>

                    <h3>Best Practices for Key Management</h3>
                    <ul>
                        <li><strong>Never commit to version control:</strong> Store keys in environment variables or secret management tools, never in code or .env files checked into git.</li>
                        <li><strong>Use different keys for different apps:</strong> Create separate keys for development, staging, and production environments.</li>
                        <li><strong>Rotate keys regularly:</strong> Even if not compromised, rotate keys periodically for security hygiene.</li>
                        <li><strong>Use minimal permissions:</strong> If your platform supports it, use keys with restricted access rather than full account access.</li>
                        <li><strong>Monitor key usage:</strong> Regularly review which keys are active and revoke unused ones.</li>
                    </ul>

                    <h3>Revoking Keys</h3>
                    <p>
                        If you suspect a key has been compromised or you no longer need it:
                    </p>
                @endif
                <ol style="list-style: decimal; margin-left: 2rem; color: var(--text-secondary); line-height: 1.8;">
                    @if(app()->getLocale() === 'ar')
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">انتقل إلى <strong>API Keys</strong> في لوحة التحكم</li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">انقر على <strong>"Revoke"</strong> بجانب المفتاح الذي تريد تعطيله</li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">أكّد الإلغاء</li>
                        <li dir="rtl" style="font-family: 'Tajawal', sans-serif; margin-bottom:0.5rem;">يتم تعطيل المفتاح فوراً ولا يمكن استخدامه لطلبات جديدة</li>
                    @else
                        <li style="margin-bottom:0.5rem;">Go to <strong>API Keys</strong> in your dashboard</li>
                        <li style="margin-bottom:0.5rem;">Click <strong>"Revoke"</strong> next to the key you want to disable</li>
                        <li style="margin-bottom:0.5rem;">Confirm the revocation</li>
                        <li style="margin-bottom:0.5rem;">The key is immediately deactivated and cannot be used for new requests</li>
                    @endif
                </ol>

                @if(app()->getLocale() === 'ar')
                    <div class="docs-warning-box" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        <p><strong>بعد الإلغاء:</strong> أي طلبات تستخدم مفتاحاً ملغى ستُرجع خطأ <strong>401 Unauthorized</strong>.
                        إذا كنت تستخدم هذا المفتاح في الإنتاج، حدّث تطبيقك ليستخدم مفتاحاً جديداً قبل إلغاء المفتاح القديم.</p>
                    </div>
                @else
                    <div class="docs-warning-box">
                        <p><strong>After Revocation:</strong> Any requests using a revoked key will return a <strong>401 Unauthorized</strong> error.
                        If you're using this key in production, update your application to use a new key before revoking the old one.</p>
                    </div>
                @endif
            </section>

            <!-- Section 4: Error Handling -->
            <section class="docs-section" id="error-handling">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">معالجة أخطاء المصادقة</h2>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        إذا فشلت عملية المصادقة، ستتلقى استجابة خطأ. إليك كيفية تشخيص مشكلات المصادقة الشائعة وإصلاحها:
                    </p>
                @else
                    <h2>Authentication Error Handling</h2>
                    <p>
                        If your authentication fails, you'll receive an error response. Here's how to diagnose and fix common auth issues:
                    </p>
                @endif

                <div class="docs-table-wrap">
                    <table class="docs-table">
                        <thead>
                            <tr>
                                @if(app()->getLocale() === 'ar')
                                    <th>رمز الحالة</th>
                                    <th>الخطأ</th>
                                    <th>السبب</th>
                                    <th>الحل</th>
                                @else
                                    <th>Status</th>
                                    <th>Error</th>
                                    <th>Cause</th>
                                    <th>Solution</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>401</code></td>
                                <td>Unauthorized</td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">مفتاح API مفقود أو غير صالح أو بتنسيق خاطئ</td>
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">تحقق من وجود ترويسة Authorization وتنسيقها الصحيح. تحقق من أن مفتاح API لم يُلغَ.</td>
                                @else
                                    <td>Missing, invalid, or malformed API key</td>
                                    <td>Check that Authorization header is present and formatted correctly. Verify your API key hasn't been revoked.</td>
                                @endif
                            </tr>
                            <tr>
                                <td><code>401</code></td>
                                <td>Invalid API Key</td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">المفتاح المقدَّم غير موجود أو غير صالح</td>
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">أنشئ مفتاحاً جديداً من لوحة التحكم وحدّث تطبيقك.</td>
                                @else
                                    <td>The provided key doesn't exist or is invalid</td>
                                    <td>Generate a new key from your dashboard and update your application.</td>
                                @endif
                            </tr>
                            <tr>
                                <td><code>401</code></td>
                                <td>Key Revoked</td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">تم إلغاء مفتاح API</td>
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">أنشئ مفتاحاً جديداً ونشره في تطبيقك.</td>
                                @else
                                    <td>The API key has been revoked</td>
                                    <td>Generate a new key and deploy it to your application.</td>
                                @endif
                            </tr>
                            <tr>
                                <td><code>403</code></td>
                                <td>Forbidden</td>
                                @if(app()->getLocale() === 'ar')
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">تمت المصادقة لكن غير مصرّح (حساب معلّق أو مشكلة في الطبقة)</td>
                                    <td dir="rtl" style="font-family: 'Tajawal', sans-serif;">تحقق من حالة حسابك وطبقة اشتراكك في لوحة التحكم.</td>
                                @else
                                    <td>Authenticated but not authorized (account suspended or tier issue)</td>
                                    <td>Check your account status and subscription tier in the dashboard.</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">مثال على استجابة 401 Unauthorized</h3>
                @else
                    <h3>401 Unauthorized Response Example</h3>
                @endif
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">json — Error Response</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code='{
  "error": {
    "code": "invalid_api_key",
    "message": "Invalid API key provided.",
    "type": "authentication_error"
  }
}'>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>{
  "error": {
    "code": "invalid_api_key",
    "message": "Invalid API key provided.",
    "type": "authentication_error"
  }
}</code></pre>
                </div>
            </section>

            <!-- Section 5: Security Best Practices -->
            <section class="docs-section" id="security">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">أفضل الممارسات الأمنية</h2>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">1. متغيرات البيئة</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        احرص دائماً على تحميل مفتاح API من متغيرات البيئة، وليس من سلاسل مشفّرة أو ملفات ضبط:
                    </p>
                @else
                    <h2>Security Best Practices</h2>

                    <h3>1. Environment Variables</h3>
                    <p>
                        Always load your API key from environment variables, not from hardcoded strings or config files:
                    </p>
                @endif
                <div class="docs-code-block">
                    <div class="docs-code-block-header">
                        <span class="docs-code-label">python</span>
                        <button class="copy-btn" onclick="copyCode(this)" data-code="import os
from openai import OpenAI

api_key = os.getenv('LLM_RESAYIL_API_KEY')

# Preferred: OpenAI-compatible shorthand
client = OpenAI(api_key=api_key, base_url='https://llm.resayil.io/v1')

# Alternative: dedicated API hostname
# client = OpenAI(api_key=api_key, base_url='https://llmapi.resayil.io/v1')

# Legacy: standard path (still supported)
# client = OpenAI(api_key=api_key, base_url='https://llm.resayil.io/api/v1')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            Copy
                        </button>
                    </div>
                    <pre><code>import os
from openai import OpenAI

api_key = os.getenv('LLM_RESAYIL_API_KEY')

# Preferred: OpenAI-compatible shorthand
client = OpenAI(api_key=api_key, base_url='https://llm.resayil.io/v1')

# Alternative: dedicated API hostname
# client = OpenAI(api_key=api_key, base_url='https://llmapi.resayil.io/v1')

# Legacy: standard path (still supported)
# client = OpenAI(api_key=api_key, base_url='https://llm.resayil.io/api/v1')</code></pre>
                </div>

                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">2. HTTPS فقط</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        استخدم HTTPS دائماً عند التواصل مع واجهة برمجة تطبيقات LLM Resayil. لا ترسل مفاتيح API أبداً عبر اتصالات HTTP غير مشفّرة.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">3. ترويسات طلب المصادقة</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        استخدم طريقة ترويسة Authorization الموضحة أعلاه. لا تمرر مفتاح API كمعامل استعلام.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">4. التحكم في الوصول</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        حدّد أعضاء الفريق الذين يمكنهم الوصول إلى مفاتيح API. وفّر مفاتيح مختلفة لتطبيقات وأعضاء فريق مختلفين
                        لتقليل الأثر في حالة اختراق أحد المفاتيح.
                    </p>

                    <h3 dir="rtl" style="font-family: 'Tajawal', sans-serif;">5. المراقبة والتنبيهات</h3>
                    <p dir="rtl" style="font-family: 'Tajawal', sans-serif;">
                        راجع لوحة التحكم بانتظام بحثاً عن نشاط مريب. إذا لاحظت طلبات من عناوين IP غير متوقعة
                        أو أنماط حركة غير معتادة، ألغِ المفتاح المعني فوراً.
                    </p>
                @else
                    <h3>2. HTTPS Only</h3>
                    <p>
                        Always use HTTPS when communicating with the LLM Resayil API. Never send API keys over unencrypted HTTP connections.
                    </p>

                    <h3>3. Request Authentication Headers</h3>
                    <p>
                        Use the Authorization header method shown above. Do not pass your API key as a query parameter.
                    </p>

                    <h3>4. Access Control</h3>
                    <p>
                        Limit which team members have access to your API keys. Provide different keys for different applications
                        and teammates to minimize blast radius if a key is compromised.
                    </p>

                    <h3>5. Monitoring and Alerts</h3>
                    <p>
                        Regularly review your dashboard for suspicious activity. If you notice requests from unexpected IP addresses
                        or unusual traffic patterns, revoke the relevant key immediately.
                    </p>
                @endif
            </section>

            <!-- Related Links -->
            <section class="docs-section" id="related">
                @if(app()->getLocale() === 'ar')
                    <h2 dir="rtl" style="font-family: 'Tajawal', sans-serif;">موارد ذات صلة</h2>
                    <ul dir="rtl" style="font-family: 'Tajawal', sans-serif; padding-left:0; padding-right:1.5rem;">
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><a href="{{ route('docs.getting-started') }}" class="docs-link">البدء السريع</a> — أول طلب API لك</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><a href="{{ route('docs.error-codes') }}" class="docs-link">رموز الأخطاء</a> — استكشاف مشكلات المصادقة وإصلاحها</li>
                        <li style="padding-left:0; padding-right:1rem;"><span style="position:absolute;right:0;color:var(--gold);">▸</span><a href="{{ route('docs.rate-limits') }}" class="docs-link">حدود المعدّل</a> — حصص الطلبات والقيود</li>
                    </ul>
                @else
                    <h2>Related Resources</h2>
                    <ul>
                        <li><a href="{{ route('docs.getting-started') }}" class="docs-link">Getting Started</a> — Your first API request</li>
                        <li><a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a> — Troubleshooting authentication issues</li>
                        <li><a href="{{ route('docs.rate-limits') }}" class="docs-link">Rate Limits</a> — Request quotas and limits</li>
                    </ul>
                @endif
            </section>

            <!-- Next/Prev Navigation -->
            <div class="docs-nav-footer">
                <a href="{{ route('docs.getting-started') }}" class="docs-nav-btn prev">
                    <span class="nav-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        @if(app()->getLocale() === 'ar')السابق@else Previous@endif
                    </span>
                    <span class="nav-title">
                        @if(app()->getLocale() === 'ar')البدء السريع@else Getting Started@endif
                    </span>
                </a>
                <a href="{{ route('docs.models') }}" class="docs-nav-btn next">
                    <span class="nav-label">
                        @if(app()->getLocale() === 'ar')التالي@else Next@endif
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <span class="nav-title">
                        @if(app()->getLocale() === 'ar')النماذج المتاحة@else Available Models@endif
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
                <li><a href="#base-urls">@if(app()->getLocale() === 'ar')عناوين URL الأساسية@else Base URLs@endif</a></li>
                <li><a href="#api-key-auth">@if(app()->getLocale() === 'ar')المصادقة بمفتاح API@else API Key Auth@endif</a></li>
                <li><a href="#bearer-format">@if(app()->getLocale() === 'ar')تنسيق ترويسة التفويض@else Bearer Format@endif</a></li>
                <li><a href="#key-lifecycle">@if(app()->getLocale() === 'ar')دورة حياة المفتاح@else Key Lifecycle@endif</a></li>
                <li><a href="#error-handling">@if(app()->getLocale() === 'ar')معالجة الأخطاء@else Error Handling@endif</a></li>
                <li><a href="#security">@if(app()->getLocale() === 'ar')أفضل الممارسات@else Security Practices@endif</a></li>
                <li><a href="#related">@if(app()->getLocale() === 'ar')موارد ذات صلة@else Related Resources@endif</a></li>
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
    ['name' => 'Authentication', 'url' => route('docs.authentication')]
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
