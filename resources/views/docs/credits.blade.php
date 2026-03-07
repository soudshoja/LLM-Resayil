@extends('layouts.app')

@section('title', 'Credits System — API Documentation')

@push('styles')
<style>
    /* ── Documentation Page Styles ── */
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
        gap: 0.5rem;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .docs-breadcrumb a {
        color: var(--gold);
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-breadcrumb a:hover {
        opacity: 0.8;
    }

    .docs-breadcrumb span {
        color: var(--text-secondary);
    }

    /* Page Title */
    .docs-title {
        font-size: 2rem;
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
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    /* Sections */
    .docs-section {
        margin-bottom: 3rem;
    }

    .docs-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .docs-section h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
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

    .docs-section strong {
        color: var(--text-primary);
    }

    /* Code Blocks */
    .docs-code-block {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        overflow-x: auto;
    }

    .docs-code-block code {
        display: block;
        font-size: 0.85rem;
        line-height: 1.6;
        color: #a0d468;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .docs-code-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Info Box */
    .docs-info-box {
        background: rgba(212, 175, 55, 0.08);
        border-left: 4px solid var(--gold);
        border-radius: 4px;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-info-box p {
        margin: 0;
    }

    /* Warning Box */
    .docs-warning-box {
        background: rgba(220, 60, 60, 0.07);
        border-left: 4px solid #dc3545;
        border-radius: 4px;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
    }

    .docs-warning-box p {
        margin: 0;
        color: var(--text-secondary);
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
    .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9rem;
    }

    .docs-table th {
        background: rgba(212, 175, 55, 0.1);
        color: var(--gold);
        font-weight: 600;
        padding: 0.75rem 1rem;
        text-align: left;
        border: 1px solid var(--border);
    }

    .docs-table td {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        color: var(--text-secondary);
    }

    .docs-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Comparison Grid */
    .credits-compare {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin: 1.5rem 0;
    }

    .credits-compare-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
    }

    .credits-compare-card h4 {
        color: var(--gold);
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .credits-compare-card p {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Next Section Link */
    .docs-next-section {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-next-section h3 {
        margin-bottom: 1rem;
    }

    .docs-next-section a {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: var(--gold);
        color: var(--bg-primary);
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .docs-next-section a:hover {
        opacity: 0.85;
    }

    @media (max-width: 768px) {
        .docs-title {
            font-size: 1.5rem;
        }

        .docs-section h2 {
            font-size: 1.25rem;
        }

        .docs-code-block {
            margin-left: -2rem;
            margin-right: -2rem;
            border-radius: 0;
        }

        .docs-table {
            font-size: 0.8rem;
        }

        .credits-compare {
            grid-template-columns: 1fr;
        }
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
            <a href="{{ route('docs.index') }}">Docs</a>
            <span>&rarr;</span>
            <span>Credits System</span>
        </div>

        <!-- Title -->
        <h1 class="docs-title">Credits <span>System</span></h1>
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
                @if(app()->getLocale() === 'ar')
                    ما هو الرصيد؟
                @else
                    What Are Credits?
                @endif
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

        <!-- Section 2: Free Tier Credits -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    رصيد الترحيب للمستخدمين الجدد
                @else
                    Free Credits for New Users
                @endif
            </h2>
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

            <div class="docs-info-box">
                @if(app()->getLocale() === 'ar')
                    <p><strong>لا حاجة لبطاقة ائتمان:</strong> رصيد البداية متاح فور التسجيل دون الحاجة إلى إدخال أي بيانات دفع.</p>
                @else
                    <p><strong>No credit card required:</strong> The welcome credit is available immediately upon registration with no payment details needed.</p>
                @endif
            </div>
        </section>

        <!-- Section 3: Credit-Based Billing -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    الفوترة القائمة على الرصيد
                @else
                    Credit-Based Billing
                @endif
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

            <div class="docs-code-block">
                <div class="docs-code-label">Deduction Formula</div>
                <code>credits_deducted = (prompt_tokens + completion_tokens) × model_multiplier</code>
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
                @if(app()->getLocale() === 'ar')
                    معاملات الضرب حسب نوع النموذج
                @else
                    Credit Multipliers by Model Type
                @endif
            </h2>
            @if(app()->getLocale() === 'ar')
                <p>
                    يختلف معدل خصم الرصيد بحسب نوع النموذج. النماذج المحلية التي تعمل على خوادمنا المخصصة أقل تكلفةً،
                    بينما تحمل النماذج السحابية تكاليف تشغيل أعلى تنعكس على معامل ضربها:
                </p>
            @else
                <p>
                    The credit deduction rate varies by model type. Local models running on our dedicated infrastructure
                    are less expensive, while cloud-proxied models carry higher operational costs reflected in their multiplier:
                </p>
            @endif

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>
                            @if(app()->getLocale() === 'ar')نوع النموذج@else Model Type@endif
                        </th>
                        <th>
                            @if(app()->getLocale() === 'ar')معامل الضرب@else Multiplier Range@endif
                        </th>
                        <th>
                            @if(app()->getLocale() === 'ar')أمثلة على النماذج@else Example Models@endif
                        </th>
                        <th>
                            @if(app()->getLocale() === 'ar')الاستخدام الأمثل@else Best Used For@endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if(app()->getLocale() === 'ar')نماذج محلية خفيفة@else Light local models@endif
                        </td>
                        <td>0.5×</td>
                        <td>Phi-3 Mini, TinyLlama</td>
                        <td>
                            @if(app()->getLocale() === 'ar')التصنيف، الملخصات القصيرة@else Classification, short summaries@endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if(app()->getLocale() === 'ar')نماذج محلية قياسية@else Standard local models@endif
                        </td>
                        <td>1×</td>
                        <td>Mistral 7B, Llama 3 8B, Neural Chat</td>
                        <td>
                            @if(app()->getLocale() === 'ar')الدردشة العامة، الكتابة، البرمجة@else General chat, writing, coding@endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if(app()->getLocale() === 'ar')نماذج محلية كبيرة@else Large local models@endif
                        </td>
                        <td>1.5×</td>
                        <td>Llama 3 70B, Mixtral 8x7B</td>
                        <td>
                            @if(app()->getLocale() === 'ar')الاستدلال المعقد، المحتوى الطويل@else Complex reasoning, long content@endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if(app()->getLocale() === 'ar')نماذج سحابية متوسطة@else Mid-tier cloud models@endif
                        </td>
                        <td>2× – 2.5×</td>
                        <td>GPT-4o Mini, Claude Haiku</td>
                        <td>
                            @if(app()->getLocale() === 'ar')مهام تتطلب دقةً عالية@else Tasks requiring high accuracy@endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if(app()->getLocale() === 'ar')نماذج سحابية متقدمة@else Premium cloud models@endif
                        </td>
                        <td>3× – 3.5×</td>
                        <td>GPT-4o, Claude 3.5 Sonnet, Gemini Pro</td>
                        <td>
                            @if(app()->getLocale() === 'ar')أصعب المهام، الاستدلال المتعدد الخطوات@else Hardest tasks, multi-step reasoning@endif
                        </td>
                    </tr>
                </tbody>
            </table>

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
                @if(app()->getLocale() === 'ar')
                    التحقق من رصيدك
                @else
                    Checking Your Balance
                @endif
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

            <div class="docs-code-block">
                <div class="docs-code-label">API Request</div>
                <code>GET https://llm.resayil.io/api/billing/subscription
Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Response (credits field)</div>
                <code>{
  "tier": "free",
  "status": "active",
  "expires_at": null,
  "credits": 732.00
}</code>
            </div>
        </section>

        <!-- Section 6: When Credits Run Out -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar')
                    ماذا يحدث عند نفاد الرصيد؟
                @else
                    What Happens When Credits Run Out
                @endif
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

            <div class="docs-code-block">
                <div class="docs-code-label">Error Response — 402 Payment Required</div>
                <code>HTTP/1.1 402 Payment Required

{
  "error": {
    "message": "Insufficient credits. Please top up your balance to continue.",
    "type": "insufficient_credits",
    "code": 402
  }
}</code>
            </div>

            <div class="docs-warning-box">
                @if(app()->getLocale() === 'ar')
                    <p><strong>تنبيه:</strong> لا يتم تنفيذ أي جزء من الطلب إذا كان الرصيد غير كافٍ — النموذج لا يُشغَّل ولا تُخصم أي رصيدة جزئية.</p>
                @else
                    <p><strong>Note:</strong> No part of the request is executed if the balance is insufficient — the model is not invoked and no partial credit is deducted.</p>
                @endif
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
                @if(app()->getLocale() === 'ar')
                    رصيد الاشتراك مقابل رصيد الشحن
                @else
                    Subscription Credits vs Top-Up Credits
                @endif
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

            <div class="credits-compare">
                <div class="credits-compare-card">
                    <h4>
                        @if(app()->getLocale() === 'ar')رصيد الاشتراك@else Subscription Credits@endif
                    </h4>
                    <p>
                        @if(app()->getLocale() === 'ar')
                            رصيد مُخصَّص ضمن خطة اشتراك دورية. يتجدد عند تجديد الاشتراك. رصيد الخطة المجانية (1,000 رصيدة عند التسجيل) يندرج ضمن هذه الفئة.
                        @else
                            Credits allocated as part of a recurring subscription plan. Renewed when the subscription renews. The free-tier welcome credit (1,000 credits at registration) falls under this type.
                        @endif
                    </p>
                </div>
                <div class="credits-compare-card">
                    <h4>
                        @if(app()->getLocale() === 'ar')رصيد الشحن الإضافي@else Top-Up Credits@endif
                    </h4>
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

            <div class="docs-info-box">
                @if(app()->getLocale() === 'ar')
                    <p>
                        <strong>للاطلاع على المزيد:</strong> راجع صفحة
                        <a href="{{ route('docs.topup') }}" class="docs-link">شراء رصيد إضافي</a>
                        لفهم كيفية شراء حزم الشحن وطرق الدفع المتاحة.
                    </p>
                @else
                    <p>
                        <strong>Learn more:</strong> See the
                        <a href="{{ route('docs.topup') }}" class="docs-link">Top-Up Credits</a> page for details
                        on purchasing credit packs and available payment methods.
                    </p>
                @endif
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

@endsection
