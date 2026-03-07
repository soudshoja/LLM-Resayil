@extends('layouts.app')

@section('title', 'Rate Limits & Quotas — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page — Rate Limits ── */
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

    .docs-breadcrumb a:hover {
        color: var(--gold);
    }

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

    .docs-title span {
        color: var(--gold);
    }

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
        margin-top: 1.5rem;
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

    .docs-section strong {
        color: var(--text-primary);
    }

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

    /* Code block highlighted value */
    .docs-code-block code .hl-gold {
        color: var(--gold);
        font-weight: 600;
    }

    .docs-code-block code .hl-key {
        color: #79b8ff;
    }

    .docs-code-block code .hl-str {
        color: #f97583;
    }

    .docs-code-block code .hl-num {
        color: #ffab70;
    }

    /* Code block header bar */
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

    .docs-box p {
        margin: 0;
        font-size: 0.92rem;
        line-height: 1.65;
    }

    .docs-box-tip {
        background: rgba(212, 175, 55, 0.08);
        border-left: 4px solid var(--gold);
    }

    .docs-box-tip .docs-box-icon svg { stroke: var(--gold); }

    .docs-box-warning {
        background: rgba(245, 158, 11, 0.08);
        border-left: 4px solid #f59e0b;
    }

    .docs-box-warning .docs-box-icon svg { stroke: #f59e0b; }

    .docs-box-danger {
        background: rgba(220, 38, 38, 0.08);
        border-left: 4px solid #dc2626;
    }

    .docs-box-danger .docs-box-icon svg { stroke: #dc2626; }

    .docs-box-note {
        background: rgba(59, 130, 246, 0.08);
        border-left: 4px solid #3b82f6;
    }

    .docs-box-note .docs-box-icon svg { stroke: #3b82f6; }

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

    .docs-table thead {
        position: sticky;
        top: 0;
        z-index: 1;
    }

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
    }

    .docs-table tbody tr:last-child td {
        border-bottom: none;
    }

    .docs-table tbody tr:nth-child(even) td {
        background: rgba(255,255,255,0.015);
    }

    .docs-table code {
        background: rgba(0, 0, 0, 0.3);
        padding: 0.2rem 0.45rem;
        border-radius: 3px;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.82rem;
        color: var(--gold);
    }

    /* Header badge (monospace gold) */
    .header-badge {
        display: inline-block;
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: var(--gold);
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }

    /* Tier badge */
    .tier-badge {
        display: inline-block;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        letter-spacing: 0.03em;
    }

    .tier-free    { background: rgba(100,116,139,0.2); color: #94a3b8; }
    .tier-starter { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .tier-basic   { background: rgba(16,185,129,0.15); color: #34d399; }
    .tier-pro     { background: rgba(212,175,55,0.15);  color: var(--gold); }
    .tier-ent     { background: rgba(168,85,247,0.15);  color: #c084fc; }

    /* Related Resources list */
    .docs-section .rel-list {
        padding-left: 0;
    }

    .docs-section .rel-list li {
        padding-left: 0;
    }

    .docs-section .rel-list li:before {
        display: none;
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

    .docs-next-section a:hover {
        opacity: 0.88;
    }

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

        .docs-code-header {
            border-radius: 0;
        }

        .docs-table-wrap {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .docs-table th,
        .docs-table td {
            padding: 0.6rem 0.75rem;
            font-size: 0.82rem;
        }
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
                @if(app()->getLocale() === 'ar') حدود المعدل والحصص @else Rate Limits &amp; Quotas @endif
            </span>
        </nav>

        <!-- Title -->
        <h1 class="docs-title">
            @if(app()->getLocale() === 'ar')
                حدود المعدل <span>&amp; الحصص</span>
            @else
                Rate Limits &amp; <span>Quotas</span>
            @endif
        </h1>

        @if(app()->getLocale() === 'ar')
            <p class="docs-intro">
                تعرّف على كيفية تطبيق LLM Resayil لحدود معدل الطلبات والحصص لضمان استخدام عادل واستقرار الخدمة.
                تعلّم كيف تتعامل مع استجابات تجاوز الحد وتطبّق استراتيجيات الانتظار التدريجي.
            </p>
        @else
            <p class="docs-intro">
                Understand how LLM Resayil enforces rate limits and quotas to ensure fair usage and service stability.
                Learn how to handle rate limit responses and implement backoff strategies.
            </p>
        @endif

        <!-- Section 1: Rate Limiting Overview -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>نظرة عامة على حدود المعدل</h2>
                <p>
                    يُطبّق LLM Resayil حدوداً للمعدل لمنع الإساءة وضمان وصول عادل لجميع المستخدمين.
                    تُطبَّق هذه الحدود على مستوى معرّف المستخدم في الدقيقة عبر Laravel RateLimiter.
                </p>

                <h3>لماذا حدود المعدل؟</h3>
            @else
                <h2>Rate Limiting Overview</h2>
                <p>
                    LLM Resayil applies rate limits to prevent abuse and ensure fair access for all users.
                    Limits are enforced per user ID per minute via Laravel RateLimiter.
                </p>

                <h3>Why Rate Limits?</h3>
            @endif
            <ul>
                @if(app()->getLocale() === 'ar')
                    <li><strong>الاستخدام العادل:</strong> يمنع أي حساب منفرد من احتكار الموارد</li>
                    <li><strong>استقرار الخدمة:</strong> يحمي البنية التحتية من الإرهاق</li>
                    <li><strong>التحكم في التكاليف:</strong> يساعدك على تجنب استهلاك رصيد زائد عن غير قصد</li>
                    <li><strong>منع الإساءة:</strong> يحدّ من أنماط الاستخدام الضارة</li>
                @else
                    <li><strong>Fair Usage:</strong> Prevents any single account from monopolizing resources</li>
                    <li><strong>Service Stability:</strong> Protects the infrastructure from being overwhelmed</li>
                    <li><strong>Cost Control:</strong> Helps you avoid accidentally consuming excessive credits</li>
                    <li><strong>Spam Prevention:</strong> Reduces abuse and malicious usage patterns</li>
                @endif
            </ul>

            @if(app()->getLocale() === 'ar')
                <h3>كيف تُطبَّق الحدود</h3>
                <p>
                    تُحسب الحدود بتوقيت UTC. تتجدد حصتك في بداية كل دقيقة. عند تجاوز الحد،
                    ستتلقى استجابة <strong>429 Too Many Requests</strong> وعليك الانتظار حتى تتجدد الحصة قبل إعادة المحاولة.
                </p>
            @else
                <h3>How Limits Are Applied</h3>
                <p>
                    Limits are calculated in UTC time. Your quota resets at the beginning of each minute.
                    When you exceed a limit, you'll receive a <strong>429 Too Many Requests</strong> response
                    and must wait for the quota to reset before retrying.
                </p>
            @endif

            <div class="docs-box docs-box-tip">
                <div class="docs-box-icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <strong>ملاحظة:</strong> يتجاوز المستخدمون ذوو صلاحية المدير (Admin) حدود المعدل تلقائياً.
                        تواصل مع الدعم إذا كنت بحاجة إلى حدود أعلى لحالات الاستخدام المشروعة.
                    @else
                        <strong>Note:</strong> Admin users automatically bypass rate limits. Contact support
                        if you need higher limits for legitimate use cases.
                    @endif
                </p>
            </div>
        </section>

        <!-- Section 2: Tier-Based Limits -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>حدود المعدل حسب مستوى الاشتراك</h2>
                <p>
                    تعتمد حدود معدل الطلبات على مستوى اشتراكك. فيما يلي ملخص لحدود كل مستوى:
                </p>
            @else
                <h2>Tier-Based Rate Limits</h2>
                <p>
                    Your rate limits depend on your subscription tier. Here is a breakdown of limits for each tier:
                </p>
            @endif

            <div class="docs-table-wrap">
                <table class="docs-table">
                    <thead>
                        <tr>
                            @if(app()->getLocale() === 'ar')
                                <th>المستوى</th>
                                <th>طلبات/دقيقة</th>
                                <th>طلبات/يوم</th>
                                <th>أقصى رموز/طلب</th>
                            @else
                                <th>Tier</th>
                                <th>Requests/Min</th>
                                <th>Requests/Day</th>
                                <th>Max Tokens/Request</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="tier-badge tier-basic">Basic</span></td>
                            <td>10</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td><span class="tier-badge tier-pro">Pro</span></td>
                            <td>30</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td><span class="tier-badge tier-ent">Enterprise</span></td>
                            <td>60</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 3: Rate Limit Response Format -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>التعامل مع استجابات تجاوز الحد</h2>
                <p>
                    عند تجاوز حد المعدل، ستتلقى استجابة HTTP برمز الحالة <strong>429 Too Many Requests</strong>.
                </p>

                <h3>تنسيق استجابة 429</h3>
                <p>تتضمن الاستجابة حقل <code>retry_after</code> الذي يحدد عدد الثواني اللازمة للانتظار قبل إعادة المحاولة:</p>
            @else
                <h2>Handling Rate Limit Responses</h2>
                <p>
                    When you exceed your rate limit, you'll receive an HTTP <strong>429 Too Many Requests</strong> response.
                </p>

                <h3>429 Response Format</h3>
                <p>The response includes a <code>retry_after</code> field indicating how many seconds to wait before retrying:</p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">HTTP / JSON</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-429-resp">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-429-resp">HTTP/1.1 429 Too Many Requests
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

            @if(app()->getLocale() === 'ar')
                <h3>رؤوس HTTP لحدود المعدل</h3>
                <p>
                    تحقق من رؤوس HTTP هذه في كل استجابة لمراقبة استخدامك وتجنب الوصول إلى الحد:
                </p>
            @else
                <h3>Rate Limit Response Headers</h3>
                <p>
                    These HTTP headers are returned on every response so you can monitor usage and avoid hitting
                    the limit proactively:
                </p>
            @endif

            <div class="docs-table-wrap">
                <table class="docs-table">
                    <thead>
                        <tr>
                            <th>Header</th>
                            @if(app()->getLocale() === 'ar')
                                <th>الوصف</th>
                            @else
                                <th>Description</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="header-badge">X-RateLimit-Limit</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>الحد الأقصى للطلبات في الدقيقة لمستواك (مثلاً: 20)</td>
                            @else
                                <td>Your maximum requests allowed per minute (e.g., 20)</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="header-badge">X-RateLimit-Remaining</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>عدد الطلبات المتبقية في الدقيقة الحالية</td>
                            @else
                                <td>Requests remaining in the current minute window</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="header-badge">X-RateLimit-Reset</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>طابع زمني Unix يحدد موعد تجديد الحصة</td>
                            @else
                                <td>Unix timestamp when the quota window resets</td>
                            @endif
                        </tr>
                        <tr>
                            <td><span class="header-badge">retry_after</span></td>
                            @if(app()->getLocale() === 'ar')
                                <td>عدد الثواني التي يجب الانتظارها قبل إعادة المحاولة (في جسم الاستجابة)</td>
                            @else
                                <td>Seconds to wait before retrying (in the JSON response body)</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>مراقبة الحصة المتبقية</h3>
                <p>
                    راقب الرأس <code>X-RateLimit-Remaining</code> في كل استجابة لتنفيذ تقنين استباقي من جانب العميل:
                </p>
            @else
                <h3>Monitoring Remaining Quota</h3>
                <p>
                    Monitor <code>X-RateLimit-Remaining</code> on every response to implement proactive client-side throttling:
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JavaScript</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-js-monitor">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-js-monitor">const response = await fetch(apiUrl, options);
const remaining = parseInt(response.headers.get('X-RateLimit-Remaining'), 10);
const limit = parseInt(response.headers.get('X-RateLimit-Limit'), 10);

if (remaining &lt; limit * 0.2) {
  console.warn(`Only ${remaining} requests remaining in this window!`);
}</code>
                </div>
            </div>
        </section>

        <!-- Section 4: Retry-After Guidance -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>إعادة المحاولة بعد تجاوز الحد</h2>
                <p>
                    عند تلقّي استجابة 429، استخدم قيمة <code>retry_after</code> الموجودة في جسم JSON
                    لتحديد مدة الانتظار. لا تُعيد المحاولة فوراً — انتظر المدة المحددة على الأقل.
                </p>
            @else
                <h2>Retry-After Guidance</h2>
                <p>
                    When you receive a 429 response, use the <code>retry_after</code> value from the JSON body
                    to determine how long to wait. Never retry immediately — always wait at least the specified
                    number of seconds.
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">Python</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-py-retry">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-py-retry">import time
import requests

def call_with_retry(url, payload, headers, max_retries=5):
    for attempt in range(max_retries):
        response = requests.post(url, json=payload, headers=headers)

        if response.status_code == 200:
            return response.json()

        if response.status_code == 429:
            data = response.json()
            retry_after = data.get("retry_after", 60)
            print(f"Rate limited. Waiting {retry_after}s (attempt {attempt + 1})")
            time.sleep(retry_after)
            continue

        response.raise_for_status()

    raise Exception("Max retries exceeded")</code>
                </div>
            </div>
        </section>

        <!-- Section 5: Implementing Backoff -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>استراتيجيات الانتظار التدريجي</h2>
                <p>
                    عند تجاوز حد المعدل، نفّذ انتظاراً تدريجياً أسياً مع عشوائية لإعادة الطلبات بشكل موثوق.
                    هذا الأسلوب أكثر موثوقية من إعادة المحاولة الفورية ويساعد في توزيع الحمل.
                </p>

                <h3>الانتظار الأسي مع العشوائية</h3>
                <p>
                    الأسلوب الموصى به هو الانتظار الأسي مع عشوائية لتجنب مشكلة "القطيع الرعدي":
                </p>
            @else
                <h2>Implementing Backoff Strategies</h2>
                <p>
                    When rate limited, implement exponential backoff with jitter to gracefully retry requests.
                    This is more reliable than immediate retries and helps distribute load across clients.
                </p>

                <h3>Exponential Backoff with Jitter</h3>
                <p>
                    The recommended approach uses exponential backoff with jitter to avoid thundering herd problems:
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">Python</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-py-backoff">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-py-backoff">import time
import random

def make_api_call_with_backoff(api_url, data, max_retries=5):
    for attempt in range(max_retries):
        try:
            response = requests.post(api_url, json=data, timeout=10)
            if response.status_code == 200:
                return response.json()
            elif response.status_code == 429:
                # Use retry_after from body if available, else exponential backoff
                body = response.json()
                wait_time = body.get("retry_after") or (2 ** attempt) + random.uniform(0, 1)
                print(f"Rate limited. Waiting {wait_time:.1f} seconds...")
                time.sleep(wait_time)
            else:
                response.raise_for_status()
        except Exception as e:
            if attempt &lt; max_retries - 1:
                wait_time = (2 ** attempt) + random.uniform(0, 1)
                time.sleep(wait_time)
            else:
                raise
    raise Exception("Max retries exceeded")</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>تفاصيل استراتيجية الانتظار</h3>
            @else
                <h3>Backoff Strategy Details</h3>
            @endif
            <ul>
                @if(app()->getLocale() === 'ar')
                    <li><strong>ابدأ بقيم صغيرة:</strong> انتظر ثانية، ثم ثانيتين، ثم أربعاً، وهكذا تصاعدياً</li>
                    <li><strong>أضف عشوائية:</strong> أضف 0–1 ثانية عشوائية لمنع تزامن الطلبات من عملاء متعددين</li>
                    <li><strong>حدد وقتاً أقصى:</strong> لا تتجاوز 60 ثانية انتظاراً لتجنب التأخيرات المفرطة</li>
                    <li><strong>حدد عدد المحاولات:</strong> ضع حداً أقصى لعدد المحاولات (عادةً 5–10)</li>
                @else
                    <li><strong>Start Small:</strong> Begin with 1 second, then 2s, 4s, 8s, etc.</li>
                    <li><strong>Add Jitter:</strong> Add random 0–1 second to prevent synchronized retries from multiple clients</li>
                    <li><strong>Cap Max Wait:</strong> Do not exceed 60 seconds to avoid indefinite delays</li>
                    <li><strong>Set Retry Limit:</strong> Set a maximum retry count (typically 5–10 attempts)</li>
                @endif
            </ul>
        </section>

        <!-- Section 6: Best Practices -->
        <section class="docs-section">
            @if(app()->getLocale() === 'ar')
                <h2>أفضل الممارسات لإدارة حدود المعدل</h2>

                <h3>1. دمج الطلبات</h3>
                <p>
                    ادمج عدة طلبات في استدعاء API واحد كلما أمكن. هذا يُعدّ طلباً واحداً نحو حصتك
                    مع معالجة بيانات أكثر.
                </p>

                <h3>2. تطبيق تقنين من جانب العميل</h3>
                <p>
                    لا تعتمد فقط على تقنين الخادم. نفّذ تقنيناً من جانب العميل للبقاء ضمن 80% من حصتك:
                </p>
            @else
                <h2>Best Practices for Rate Limit Management</h2>

                <h3>1. Batch Requests</h3>
                <p>
                    Combine multiple requests into a single API call when possible. This counts as one request
                    toward your quota while processing more data.
                </p>

                <h3>2. Implement Client-Side Rate Limiting</h3>
                <p>
                    Do not rely solely on server-side limiting. Implement client-side throttling to stay below
                    80% of your quota:
                </p>
            @endif

            <div class="docs-code-wrap">
                <div class="docs-code-header">
                    <span class="docs-code-lang">JavaScript</span>
                    <button class="docs-copy-btn" onclick="docsCopy(this)" data-target="code-js-throttle">
                        <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                </div>
                <div class="docs-code-block">
                    <code id="code-js-throttle">// Limit to 80% of max requests to stay safe
const MAX_SAFE_RATE = 0.8;
const maxRequestsPerMinute = 20; // Your tier limit
const safeRate = maxRequestsPerMinute * MAX_SAFE_RATE; // 16 req/min
const delayBetweenRequests = 60000 / safeRate; // ~3750ms</code>
                </div>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>3. تخزين الاستجابات مؤقتاً</h3>
                <p>
                    خزّن استجابات API مؤقتاً لتجنب تكرار الطلبات على نفس الاستفسارات.
                    هذا يُقلل استخدام API بشكل كبير ويبقيك ضمن حدود المعدل.
                </p>

                <h3>4. توزيع الأعمال الكثيفة</h3>
                <p>
                    عند الحاجة لمعالجة كميات كبيرة من البيانات، وزّع الطلبات على مدار الوقت بدلاً من
                    إرسالها دفعة واحدة. هذا يمنع انتهاك حدود الدفعات مع الحفاظ على معدل إنتاج ثابت.
                </p>

                <h3>5. المراقبة والتنبيه</h3>
                <p>
                    أعدّ تنبيهات في تطبيقك عندما ينخفض <code>X-RateLimit-Remaining</code>
                    إلى أقل من 20% من حدك. يمنحك هذا إنذاراً مبكراً لاتخاذ الإجراء قبل ظهور أخطاء 429.
                </p>

                <h3>6. الترقية عند الحاجة</h3>
                <p>
                    إذا كان تطبيقك يحتاج فعلاً إلى حدود أعلى، رقّ مستوى اشتراكك أو تواصل مع الدعم
                    للحلول المؤسسية. من الأفضل الاستباقية على تجربة تدهور الخدمة.
                </p>
            @else
                <h3>3. Cache Responses When Possible</h3>
                <p>
                    Cache API responses to avoid repeated requests for the same queries. This dramatically
                    reduces API usage and keeps you well within rate limits.
                </p>

                <h3>4. Stagger High-Volume Work</h3>
                <p>
                    Spread requests over time rather than sending them all at once. This prevents burst limit
                    violations while maintaining consistent throughput.
                </p>

                <h3>5. Monitor and Alert</h3>
                <p>
                    Set up alerts when <code>X-RateLimit-Remaining</code> drops below 20% of your limit.
                    This gives you early warning to take action before you start receiving 429 errors.
                </p>

                <h3>6. Upgrade When Needed</h3>
                <p>
                    If your application legitimately needs higher rate limits, upgrade your subscription tier
                    or contact support about enterprise options.
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
                <li><a href="{{ route('docs.billing') }}" class="docs-link">Billing &amp; Credits</a>
                    @if(app()->getLocale() === 'ar') — استهلاك الرموز والتكاليف @else — Token consumption and costs @endif
                </li>
                <li><a href="{{ route('docs.error-codes') }}" class="docs-link">Error Codes</a>
                    @if(app()->getLocale() === 'ar') — فهم رموز حالة HTTP @else — Understanding HTTP status codes @endif
                </li>
                <li><a href="{{ route('pricing') }}" class="docs-link">Pricing</a>
                    @if(app()->getLocale() === 'ar') — مستويات الاشتراك والأسعار @else — Subscription tiers and rates @endif
                </li>
            </ul>
        </section>

        <!-- Next Section Link -->
        <div class="docs-next-section">
            @if(app()->getLocale() === 'ar')
                <h3>هل تحتاج مزيداً من المساعدة؟</h3>
                <p>تعلّم كيف تتعامل مع أخطاء API الشائعة.</p>
                <a href="{{ route('docs.error-codes') }}">انتقل إلى رموز الخطأ واستكشاف الأعطال &larr;</a>
            @else
                <h3>Need more help?</h3>
                <p>Learn about common errors and how to troubleshoot them.</p>
                <a href="{{ route('docs.error-codes') }}">Go to Error Codes &amp; Troubleshooting &rarr;</a>
            @endif
        </div>

    </div>
</div>

<!-- JSON-LD Breadcrumb Schema -->
@php
  $breadcrumbs = [
    ['name' => 'Home', 'url' => route('welcome')],
    ['name' => 'Documentation', 'url' => route('docs.index')],
    ['name' => 'Rate Limits & Quotas', 'url' => route('docs.rate-limits')]
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
