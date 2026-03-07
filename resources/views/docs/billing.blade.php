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
        font-size: 0.95rem;
    }

    .docs-table th,
    .docs-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .docs-table th {
        background: var(--bg-card);
        font-weight: 600;
        color: var(--text-primary);
    }

    .docs-table td {
        color: var(--text-secondary);
    }

    .docs-table code {
        background: rgba(0, 0, 0, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-family: 'Monaco', 'Courier New', monospace;
        color: #a0d468;
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
            font-size: 0.85rem;
        }

        .docs-table th,
        .docs-table td {
            padding: 0.5rem;
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
                    model's credit multiplier. The multiplier varies between local and cloud models.
                </p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-label">Formula</div>
                <code>Credits Deducted = (prompt_tokens + completion_tokens) × credit_multiplier</code>
            </div>

            @if(app()->getLocale() === 'ar')
                <h3>معاملات النماذج</h3>
                <p>تتراوح قيمة المعامل بحسب نوع النموذج:</p>
            @else
                <h3>Model Multipliers</h3>
                <p>Multiplier values vary by model type:</p>
            @endif

            <table class="docs-table">
                <thead>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <th>نوع النموذج</th>
                            <th>نطاق المعامل</th>
                        @else
                            <th>Model Type</th>
                            <th>Multiplier Range</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <td>النماذج المحلية</td>
                        @else
                            <td>Local Models</td>
                        @endif
                        <td>0.5× – 1.5×</td>
                    </tr>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <td>النماذج السحابية</td>
                        @else
                            <td>Cloud Models</td>
                        @endif
                        <td>2× – 3.5×</td>
                    </tr>
                </tbody>
            </table>

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
                <div class="docs-code-label">Example</div>
                <code>prompt_tokens:      100
completion_tokens:  200
total_tokens:       300
credit_multiplier:  1.0  (local model)
credits_deducted:   300 × 1.0 = 300 credits

-- Cloud model example (multiplier 2.5×) --
credits_deducted:   300 × 2.5 = 750 credits</code>
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
                <div class="docs-code-label">Usage Field in Response</div>
                <code>"usage": {
  "prompt_tokens": 45,
  "completion_tokens": 128,
  "total_tokens": 173
}</code>
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

            <h3>GET /api/billing/subscription</h3>
            @if(app()->getLocale() === 'ar')
                <p>يُعيد معلومات اشتراكك الحالي، بما يشمل مستوى الخطة، والحالة، وتاريخ الانتهاء، ورصيد الأرصدة.</p>
            @else
                <p>Returns your current subscription details including tier, status, expiry date, and credit balance.</p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-label">Request</div>
                <code>GET https://llm.resayil.io/api/billing/subscription
Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Response</div>
                <code>{
  "tier": "pro",
  "status": "active",
  "expires_at": "2025-06-01T00:00:00Z",
  "credits": {
    "balance": 4250,
    "currency": "credits"
  }
}</code>
            </div>

            <h3>GET /api/billing/topup-packs</h3>
            @if(app()->getLocale() === 'ar')
                <p>يُعيد قائمة بحزم الرصيد المتاحة للشراء مع التفاصيل الخاصة بكل حزمة.</p>
            @else
                <p>Returns the list of available credit top-up packs with their pricing details.</p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-label">Request</div>
                <code>GET https://llm.resayil.io/api/billing/topup-packs
Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Response</div>
                <code>{
  "packs": [
    { "id": "pack_500",  "credits": 500,   "price": 5.00,  "currency": "USD" },
    { "id": "pack_2000", "credits": 2000,  "price": 18.00, "currency": "USD" },
    { "id": "pack_5000", "credits": 5000,  "price": 40.00, "currency": "USD" }
  ]
}</code>
            </div>

            <h3>GET /api/billing/topup-history</h3>
            @if(app()->getLocale() === 'ar')
                <p>
                    يُعيد سجلاً مُرقَّماً بعمليات شراء الرصيد السابقة (20 عملية لكل صفحة).
                    استخدم معامل <code>page</code> للتنقل بين الصفحات.
                </p>
            @else
                <p>
                    Returns a paginated list of past credit top-up purchases (20 per page).
                    Use the <code>page</code> query parameter to navigate between pages.
                </p>
            @endif

            <div class="docs-code-block">
                <div class="docs-code-label">Request</div>
                <code>GET https://llm.resayil.io/api/billing/topup-history?page=1
Authorization: Bearer YOUR_API_KEY</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Response</div>
                <code>{
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
}</code>
            </div>

            <div class="docs-info-box">
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

            <table class="docs-table">
                <thead>
                    <tr>
                        @if(app()->getLocale() === 'ar')
                            <th>المستوى</th>
                            <th>المميزات</th>
                        @else
                            <th>Tier</th>
                            <th>Benefits</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Free</strong></td>
                        @if(app()->getLocale() === 'ar')
                            <td>1,000 رصيد مجاني للبدء، حدود قياسية للطلبات</td>
                        @else
                            <td>1,000 starting credits, standard rate limits</td>
                        @endif
                    </tr>
                    <tr>
                        <td><strong>Starter</strong></td>
                        @if(app()->getLocale() === 'ar')
                            <td>رصيد شهري أعلى، وصول إلى نماذج إضافية</td>
                        @else
                            <td>Higher monthly credits, access to additional models</td>
                        @endif
                    </tr>
                    <tr>
                        <td><strong>Basic</strong></td>
                        @if(app()->getLocale() === 'ar')
                            <td>حدود طلبات محسّنة، أولوية في المعالجة</td>
                        @else
                            <td>Improved rate limits, processing priority</td>
                        @endif
                    </tr>
                    <tr>
                        <td><strong>Pro</strong></td>
                        @if(app()->getLocale() === 'ar')
                            <td>حدود طلبات عالية، وصول كامل إلى جميع النماذج</td>
                        @else
                            <td>High rate limits, full access to all models</td>
                        @endif
                    </tr>
                    <tr>
                        <td><strong>Enterprise</strong></td>
                        @if(app()->getLocale() === 'ar')
                            <td>حدود مخصصة، دعم مخصص، حلول API مؤسسية</td>
                        @else
                            <td>Custom limits, dedicated support, enterprise API solutions</td>
                        @endif
                    </tr>
                </tbody>
            </table>

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

                <h3>خطوات شراء الرصيد</h3>
                <ol style="list-style: decimal; margin-left: 2rem;">
                    <li>سجّل دخولك إلى <a href="{{ url('/dashboard') }}" class="docs-link">لوحة التحكم</a></li>
                    <li>انتقل إلى <strong>الفوترة</strong> &larr; <strong>خطط الاشتراك</strong></li>
                    <li>اختر حزمة الرصيد المناسبة</li>
                    <li>أكمل عملية الدفع عبر MyFatoorah</li>
                    <li>يُضاف الرصيد إلى حسابك فوراً بعد تأكيد الدفع</li>
                </ol>
            @else
                <h2>Purchasing Credits</h2>
                <p>
                    Purchase credits anytime through your dashboard. Payments are processed immediately via the
                    <a href="https://myfatoorah.com" class="docs-link">MyFatoorah</a> payment gateway.
                    Purchased credits never expire.
                </p>

                <h3>How to Top-Up</h3>
                <ol style="list-style: decimal; margin-left: 2rem;">
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

@endsection
