@extends('layouts.app')

@section('title', $pageTitle ?? 'FAQ — LLM Resayil')

@push('styles')
<style>
    /* ── FAQ Page Styles ── */
    .faq-wrap {
        background: var(--bg-secondary);
        min-height: 100vh;
    }

    /* Hero */
    .faq-hero {
        padding: 4rem 2rem 3rem;
        text-align: center;
        max-width: 820px;
        margin: 0 auto;
    }

    .faq-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(212,175,55,0.12);
        border: 1px solid rgba(212,175,55,0.3);
        border-radius: 2rem;
        color: var(--gold);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 1.5rem;
    }

    .faq-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.25rem;
    }

    .faq-hero h1 span {
        color: var(--gold);
    }

    .faq-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    @media (max-width: 600px) {
        .faq-hero h1 { font-size: 2rem; }
    }

    /* FAQ Section */
    .faq-section {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
    }

    .faq-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: border-color 0.2s;
    }

    .faq-item:hover {
        border-color: rgba(212,175,55,0.3);
    }

    .faq-question {
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        background: var(--bg-card);
        border: none;
        width: 100%;
        text-align: left;
        font-size: 1rem;
        font-weight: 600;
        color: var(--gold);
        transition: background 0.2s;
    }

    [dir="rtl"] .faq-question {
        text-align: right;
    }

    .faq-question:hover {
        background: rgba(212,175,55,0.05);
    }

    .faq-toggle {
        font-size: 1.2rem;
        transition: transform 0.3s;
        flex-shrink: 0;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(180deg);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .faq-item.active .faq-answer {
        max-height: 2000px;
    }

    .faq-answer-content {
        padding: 0 1.5rem 1.5rem;
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.95rem;
    }

    .faq-answer-content p {
        margin-bottom: 0.75rem;
    }

    .faq-answer-content p:last-child {
        margin-bottom: 0;
    }

    /* CTA */
    .faq-cta {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
    }

    .faq-cta h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .faq-cta p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .faq-cta-links {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .faq-cta-link {
        display: inline-flex;
        align-items: center;
        padding: 0.65rem 1.5rem;
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 8px;
        color: var(--gold);
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .faq-cta-link:hover {
        background: var(--gold);
        color: var(--bg-primary);
    }

    @media (max-width: 600px) {
        .faq-section { padding: 0 1.5rem 3rem; }
        .faq-question { padding: 1.25rem; font-size: 0.95rem; }
        .faq-cta-links { flex-direction: column; }
        .faq-cta-link { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="faq-wrap">
    <!-- Hero -->
    <div class="faq-hero">
        @if(app()->getLocale() === 'ar')
            <span class="faq-badge" style="font-family:'Tajawal',sans-serif;">مساعدة ودعم</span>
            <h1 style="font-family:'Tajawal',sans-serif;" dir="rtl">الأسئلة <span>الشائعة</span></h1>
            <p class="faq-hero-lead" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                اعثر على إجابات للأسئلة الشائعة حول واجهة برمجة تطبيقات LLM Resayil، الفوترة، الميزات، واستكشاف الأخطاء. لم تجد ما تبحث عنه؟ <a href="{{ route('contact') }}" style="color: var(--gold); border-bottom: 1px solid rgba(212,175,55,0.3);">تواصل مع فريق الدعم</a>.
            </p>
        @else
            <span class="faq-badge">Help &amp; Support</span>
            <h1>Frequently Asked <span>Questions</span></h1>
            <p class="faq-hero-lead">
                Find answers to common questions about LLM Resayil API, billing, features, and troubleshooting. Can't find what you're looking for? <a href="{{ route('contact') }}" style="color: var(--gold); border-bottom: 1px solid rgba(212,175,55,0.3);">Contact our support team</a>.
            </p>
        @endif
    </div>

    <!-- FAQ Items -->
    <div class="faq-section">

        {{-- Q1 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">كيف أبدأ باستخدام واجهة برمجة تطبيقات LLM Resayil؟</span>
                @else
                    How do I get started with the LLM Resayil API?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            البدء مع LLM Resayil سريع وبسيط. أولاً، أنشئ حساباً مجانياً في <a href="{{ route('register') }}">LLM Resayil</a>. ستحصل فوراً على 1,000 رصيد مجاني لبدء الاختبار. ثم أنشئ مفتاح API من لوحة التحكم تحت قسم "مفاتيح API". يجب تضمين مفتاح API في كل طلب كـ Bearer Token في ترويسة Authorization. يمكنك الاطلاع على <a href="{{ route('docs.index') }}">توثيق API</a> للعثور على أمثلة برمجية بلغات Python وJavaScript وcURL. ابدأ بطلب إكمال محادثة بسيط لاختبار إعدادك، وستكون جاهزاً لدمج LLM Resayil في تطبيقك.
                        </p>
                    @else
                        <p>Getting started with LLM Resayil is quick and simple. First, create a free account at <a href="{{ route('register') }}">LLM Resayil</a>. You'll receive 1,000 free credits immediately to start testing. Then, generate an API key from your dashboard under "API Keys" section. Your API key should be included in every request as a Bearer token in the Authorization header. You can use our <a href="{{ route('docs.index') }}">API documentation</a> to find code examples for Python, JavaScript, and cURL. Start by making a simple chat completion request to test your setup, and you're ready to integrate LLM Resayil into your application.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q2 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">ما طريقة المصادقة التي يستخدمها LLM Resayil؟</span>
                @else
                    What authentication method does LLM Resayil use?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يستخدم LLM Resayil مصادقة Bearer Token عبر مفاتيح API. عند إنشاء مفتاح API في لوحة التحكم، ستحصل على سلسلة رمزية طويلة. أدرج هذا الرمز في ترويسة <code>Authorization</code> لكل طلب API بالصيغة: <code>Authorization: Bearer YOUR_API_KEY</code>. هذا النمط نفسه الذي تستخدمه OpenAI، لذا إن كنت قد استخدمت واجهتها من قبل، ستجد المصادقة مألوفة. مفاتيح API سرية — لا تشاركها علناً أو تودعها في نظام التحكم بالإصدارات. يمكنك إنشاء مفاتيح متعددة في لوحة التحكم لتطبيقات أو بيئات مختلفة، وإلغاؤها في أي وقت عند الحاجة.
                        </p>
                    @else
                        <p>LLM Resayil uses Bearer token authentication with API keys. When you create an API key in your dashboard, you'll get a long token string. Include this token in the <code>Authorization</code> header of every API request with the format: <code>Authorization: Bearer YOUR_API_KEY</code>. This is the same pattern used by OpenAI, so if you've used their API before, the authentication will be familiar. API keys are secret—never share them publicly or commit them to version control. You can create multiple API keys in your dashboard for different applications or environments, and revoke them at any time if compromised.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q3 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">ما النماذج المتاحة؟</span>
                @else
                    Which models are available?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يتيح LLM Resayil الوصول إلى أكثر من 45 نموذجاً لغوياً متقدماً. ندعم نماذج شهيرة تشمل Meta Llama 2 (7B، 13B، 70B) وMistral (7B، Instruct، Mixtral) وNeural Chat وOrca وغيرها الكثير. لكل نموذج قدرات وسرعات وأسعار مختلفة. النماذج الأصغر (7 مليار معامل) أسرع وأرخص، وهي مثالية للمهام البسيطة والتطبيقات عالية الحجم. أما النماذج الأكبر (70B) فتوفر تفكيراً وفهماً أفضل للمهام المعقدة. يمكنك الاطلاع على جميع النماذج المتاحة في <a href="{{ route('features') }}">صفحة الميزات</a> أو في لوحة التحكم تحت قسم "النماذج". نضيف نماذج جديدة بانتظام بناءً على طلبات المجتمع والأبحاث الحديثة.
                        </p>
                    @else
                        <p>LLM Resayil offers access to 45+ powerful language models. We support popular models including Meta Llama 2 (7B, 13B, 70B), Mistral (7B, Instruct, Mixtral), Neural Chat, Orca, and many more. Each model has different capabilities, speeds, and pricing. The smaller models (7B parameters) are faster and cheaper, ideal for simple tasks and high-volume applications. Larger models (70B) offer better reasoning and understanding for complex tasks. You can see all available models on our <a href="{{ route('features') }}">Features page</a> or in your dashboard under "Models." We regularly add new models based on community requests and emerging research. Visit our <a href="{{ route('docs.index') }}">API documentation</a> for a complete list with performance metrics.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q4 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">ما حد معدل الطلبات (Rate Limit) في API؟</span>
                @else
                    What is the API rate limit?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            تعتمد حدود المعدل في LLM Resayil على طبقة الاشتراك. يمكن لحسابات الطبقة المجانية إجراء حتى 10 طلبات في الدقيقة. يحصل مستخدمو الطبقة الأساسية على 100 طلب في الدقيقة. تتيح الطبقة الاحترافية 500 طلب في الدقيقة. أما طبقة المؤسسات فلها حدود مخصصة. صُممت هذه الحدود لمنع الإساءة مع السماح بالتطبيقات ذات الحجم العالي. إذا تجاوزت حد طبقتك، ستتلقى الطلبات رمز الحالة 429 (Too Many Requests). يمكنك مراقبة استخدامك الحالي في لوحة التحكم. إذا كنت بحاجة إلى حد أعلى، قم بترقية اشتراكك أو <a href="{{ route('contact') }}">تواصل معنا</a> للحصول على خطة مؤسسية.
                        </p>
                    @else
                        <p>Rate limits on LLM Resayil are subscription-based. Free tier accounts can make up to 10 requests per minute. Basic tier users get 100 requests per minute. Pro tier enables 500 requests per minute. Admin/Enterprise tier has custom limits. These limits are designed to prevent abuse while allowing legitimate high-volume applications. If you exceed your tier's limit, requests will receive a 429 (Too Many Requests) status code. You can monitor your current usage in the dashboard. If you need a higher rate limit, upgrade your subscription tier or contact us for an enterprise plan with custom limits tailored to your needs.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q5 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">كيف أتعامل مع الأخطاء في تطبيقي؟</span>
                @else
                    How do I handle errors in my application?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            تُعيد واجهة LLM Resayil رموز حالة HTTP قياسية ورسائل خطأ. تحقق دائماً من رمز الحالة في استجابتك: 2xx (200-299) يعني نجاحاً، 4xx (400-499) يشير إلى أخطاء من طرف العميل، 5xx (500-599) يشير إلى أخطاء الخادم. الأخطاء الشائعة تشمل: 401 Unauthorized (مفتاح API غير صالح)، 429 Too Many Requests (تجاوز حد المعدل)، و400 Bad Request (معاملات غير صالحة). تتضمن استجابات الخطأ جسماً بصيغة JSON يشرح المشكلة. نفّذ معالجة أخطاء مناسبة في كودك: تحقق من المعاملات قبل إرسال الطلبات، تعامل مع أخطاء حد المعدل بالتراجع الأسي، وسجّل الأخطاء للتشخيص. للمشاكل المستمرة، راجع حالة النظام و<a href="{{ route('contact') }}">تواصل مع الدعم</a>.
                        </p>
                    @else
                        <p>The LLM Resayil API returns standard HTTP status codes and error messages. Always check the status code in your response: 2xx (200-299) means success, 4xx (400-499) indicates client errors, 5xx (500-599) indicates server errors. Common errors include 401 Unauthorized (invalid API key), 429 Too Many Requests (rate limit exceeded), and 400 Bad Request (invalid parameters). Error responses include a JSON body with an error message explaining what went wrong. Implement proper error handling in your code: validate parameters before sending requests, handle rate limit errors with exponential backoff, and log errors for debugging. For persistent issues, check our system status and <a href="{{ route('contact') }}">contact support</a>. See the <a href="{{ route('docs.index') }}">documentation</a> for detailed error codes and recovery strategies.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q6 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">كيف تعمل الفوترة؟</span>
                @else
                    How does billing work?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يعتمد LLM Resayil نظام رصيد بالدفع مقابل كل رمز (token) دون اشتراكات شهرية. تشتري حزم رصيد تبدأ من 2 دينار كويتي فقط مقابل 5,000 رصيد. عند إجراء طلبات API، تُخصم الرموز من رصيدك فورياً. تُحسب رموز الإدخال (طلبك) ورموز الإخراج (استجابة النموذج) بشكل منفصل بمعدلات مختلفة لكل نموذج. النماذج الأصغر أرخص لكل رمز، بينما النماذج الأكبر أعلى تكلفة لكنها أفضل جودة. الأرصدة لا تنتهي صلاحيتها — استخدمها متى شئت. يمكنك شراء الرصيد في أي وقت عبر لوحة الفوترة، والشحن فوري. راجع <a href="{{ route('cost-calculator') }}">حاسبة التكلفة</a> لتقدير إنفاقك.
                        </p>
                    @else
                        <p>LLM Resayil uses a pay-per-token credit system with no monthly subscriptions. You purchase credit packs that start from as low as 2 KWD for 5,000 credits. When you make API requests, tokens are deducted from your balance in real-time. Input tokens (your request) and output tokens (the model's response) are counted separately, with different rates per model. Smaller models are cheaper per token, while larger models cost more but provide better quality. Credits don't expire—use them whenever you want. You can purchase credits anytime through your billing dashboard, and the top-up is instant. See the <a href="{{ route('cost-calculator') }}">cost calculator</a> to estimate your spending, or check your billing page for detailed pricing by model.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q7 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">كيف يمكنني مراقبة إنفاقي؟</span>
                @else
                    How can I monitor my spending?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            توفر لوحة تحكم LLM Resayil مراقبة الإنفاق والاستخدام في الوقت الفعلي. تعرض صفحة "لوحة التحكم" رصيدك الحالي، مخططات الاستخدام اليومي والشهري، وتفاصيل التكلفة لكل نموذج. يمكنك رؤية عدد الرموز التي استخدمتها وتكلفة كل نموذج بدقة. يعرض قسم "الفوترة" سجل معاملاتك ومشتريات الرصيد واتجاهات الاستخدام. تتضمن استجابات API أعداد الرموز لتتبع الإنفاق في سجلاتك الخاصة. تتحدث لوحة التحكم في الوقت الفعلي مع كل طلب API تُجريه.
                        </p>
                    @else
                        <p>Your LLM Resayil dashboard provides real-time spending and usage monitoring. The "Dashboard" page shows your current credit balance, daily/monthly usage charts, and cost breakdown by model. You can see exactly how many tokens you've used and how much each model has cost. The "Billing" section displays your transaction history, credit purchases, and usage trends. API responses include token counts so you can track spending in your own logs. You can set spending alerts if you reach certain thresholds by upgrading to a higher subscription tier. The dashboard updates in real-time as you make API calls, so you can always see your current usage and budget status.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q8 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">هل يمكنني تحديد حدود للاستخدام أو الإنفاق؟</span>
                @else
                    Can I set usage limits or spending caps?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            نعم، يمكنك التحكم في إنفاقك عبر طبقات الاشتراك. لكل طبقة حدود معدل مدمجة (طلبات في الدقيقة) تحدّ طبيعياً من إجمالي استهلاكك. مثلاً، حد الطبقة المجانية (10 طلبات/دقيقة) يمنع التكاليف المتصاعدة. إذا كنت بحاجة إلى ضوابط إنفاق أكثر صرامة، قم بترقية طبقتك أو <a href="{{ route('contact') }}">تواصل مع فريق المبيعات</a> للحصول على خطة مؤسسية بحدود إنفاق مخصصة. يجب عليك أيضاً تطبيق ضمانات في كود تطبيقك: التحقق من المدخلات، تخزين الاستجابات مؤقتاً حين أمكن، استخدام النماذج الأصغر للمهام البسيطة، ومراقبة استخدام API باستمرار.
                        </p>
                    @else
                        <p>Yes, you can control your spending through subscription tiers. Each subscription tier has built-in rate limits (requests per minute), which naturally limits your overall consumption. For example, the Free tier's 10 requests/minute limit prevents runaway costs. If you need stricter spending controls, upgrade to a higher tier or contact our sales team for an enterprise plan with custom spending caps. You should also implement safeguards in your application code: validate inputs, cache responses when possible, use smaller models for simple tasks, and monitor API usage continuously. The dashboard shows detailed cost projections, so you can estimate your spending before it happens.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q9 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">هل يدعم LLM Resayil الاستجابات المتدفقة (Streaming)؟</span>
                @else
                    Does LLM Resayil support streaming responses?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            نعم، يدعم LLM Resayil الاستجابات المتدفقة بالكامل باستخدام Server-Sent Events (SSE). بدلاً من انتظار الاستجابة الكاملة، يُرسل التدفق الرموز إلى عميلك فور توليدها من النموذج، مما يوفر زمن استجابة أسرع بشكل ملحوظ. هذا مفيد بشكل خاص لتطبيقات الدردشة وتوليد المحتوى الطويل. لاستخدام التدفق، اضبط <code>stream: true</code> في معاملات طلب API. ستكون الاستجابة سلسلة من سطور <code>data: {...}</code>، كل منها يحتوي على رمز واحد أو أكثر. هذا مطابق لتنسيق التدفق المستخدم في OpenAI، لذا إذا كان لديك كود تدفق OpenAI موجود، سيعمل مع LLM Resayil بتغييرات طفيفة. راجع <a href="{{ route('docs.index') }}">التوثيق</a> للأمثلة البرمجية.
                        </p>
                    @else
                        <p>Yes, LLM Resayil fully supports streaming responses using Server-Sent Events (SSE). Instead of waiting for the entire response, streaming sends tokens to your client as they're generated by the model, providing a much faster perceived response time. This is especially useful for chat applications and long-form content generation. To use streaming, set <code>stream: true</code> in your API request parameters. The response will be a stream of <code>data: {...}</code> lines, each containing one or more tokens. This is identical to the streaming format used by OpenAI, so if you have existing OpenAI streaming code, it will work with LLM Resayil with minimal changes. See code examples in our <a href="{{ route('docs.index') }}">documentation</a>.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q10 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">هل يمكنني استخدام نماذج مخصصة أو نماذج مضبوطة؟</span>
                @else
                    Can I use custom models or fine-tuned models?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يوفر LLM Resayil حالياً الوصول إلى أكثر من 45 نموذجاً مفتوح المصدر مدرباً مسبقاً. تتوفر خدمات استضافة النماذج المخصصة والضبط الدقيق من خلال عرضنا للمؤسسات. إذا كنت بحاجة إلى ضبط نموذج أو استضافة نموذج مخصص، <a href="{{ route('contact') }}">تواصل مع فريق المؤسسات</a> لمناقشة خيارات الخادم المخصص. يجد كثير من المستخدمين أن الهندسة الدقيقة للمحفزات (Prompt Engineering) واختيار النموذج الأساسي الصحيح من كتالوجنا يحل 90% من حالات الاستخدام دون الحاجة إلى الضبط الدقيق.
                        </p>
                    @else
                        <p>Currently, LLM Resayil provides access to 45+ pre-trained open-source and cloud proxy models. Custom model hosting and fine-tuning services are available through our enterprise offering. If you need to fine-tune a model or host a custom model, <a href="{{ route('contact') }}">contact our enterprise team</a> to discuss dedicated server options. Many users find that careful prompt engineering and selecting the right base model from our catalog solves 90% of use cases without needing fine-tuning. Start with our available models and let us know if your requirements evolve.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q11 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">هل هناك اتفاقية مستوى خدمة (SLA) لوقت تشغيل API؟</span>
                @else
                    Is there an SLA for API uptime?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يستهدف LLM Resayil 99.5% وقت تشغيل لبنية API التحتية. نعمل بخوادم متكررة ولدينا تحويل تلقائي للمسارات لمعالجة انقطاعات الخدمة. يمكن لعملاء المؤسسات شراء اتفاقيات SLA مخصصة تضمن وقت التشغيل وتوفر دعماً ذا أولوية. تستخدم واجهة API مقاربة هجينة: إذا تجاوزت الطاقة الاستيعابية المحلية، تتحول الطلبات تلقائياً إلى نماذج سحابية لضمان استمرارية الخدمة. يمكنك التحقق من حالة النظام وسجل الحوادث في صفحة الحالة لدينا.
                        </p>
                    @else
                        <p>LLM Resayil targets 99.5% uptime for our API infrastructure. We run redundant servers and have automatic failover in place to handle service disruptions. Enterprise customers can purchase dedicated SLA agreements with guaranteed uptime and priority support. Our API uses a hybrid approach: if local capacity is exceeded, requests automatically failover to cloud models to ensure service continuity. You can check our system status and incident history on our status page. For production applications requiring higher uptime guarantees, contact our enterprise team for custom SLA options.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q12 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">لماذا أحصل على خطأ 401 Unauthorized؟</span>
                @else
                    Why am I getting a 401 Unauthorized error?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            خطأ 401 Unauthorized يعني أن مفتاح API مفقود أو غير صالح أو منتهي الصلاحية. أولاً، تحقق من تضمين مفتاح API في ترويسة طلبك: <code>Authorization: Bearer YOUR_API_KEY</code>. تأكد من نسخ مفتاح API بالكامل بشكل صحيح — حتى حرف واحد مفقود سيؤدي إلى الفشل. إذا بدا المفتاح صحيحاً، أنشئ مفتاحاً جديداً في لوحة التحكم: اذهب إلى "مفاتيح API"، انقر على زر الإنشاء، انسخ المفتاح الجديد، واختبره. إذا قمت بإلغاء مفتاح قديم، يجب استخدام الجديد. تأكد من أن مفتاح API لم يُعاد إنشاؤه في جلسة أخرى. راجع <a href="{{ route('docs.index') }}">التوثيق</a> للتفاصيل الكاملة.
                        </p>
                    @else
                        <p>A 401 Unauthorized error means your API key is missing, invalid, or expired. First, verify you're including the API key in your request header: <code>Authorization: Bearer YOUR_API_KEY</code>. Check that you copied the entire API key correctly—even one missing character will fail. If your API key seems correct, generate a new one in your dashboard: go to "API Keys," click the create button, copy the new key, and test it. If you revoked an old key, you must use the new one. Make sure your API key hasn't been regenerated in another session. If problems persist, check that you're using the correct API endpoint URL. See the <a href="{{ route('docs.index') }}">documentation</a> for complete authentication details.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q13 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">ماذا أفعل إذا كان API بطيئاً؟</span>
                @else
                    What should I do if the API is slow?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            يعتمد وقت استجابة API على عدة عوامل: حجم النموذج (النماذج الأصغر أسرع)، تعقيد الطلب، وحمل الخادم. إذا كنت تعاني من استجابات بطيئة، جرّب الخطوات التالية: (1) استخدم نموذجاً أصغر وأسرع إذا سمحت الدقة بذلك. (2) استخدم وضع التدفق (Streaming) للحصول على استجابة أسرع بالإدراك. (3) حسّن محفزاتك لتكون أكثر إيجازاً. (4) تحقق من اتصالك بالشبكة وزمن الوصول إلى خوادمنا. (5) راجع لوحة التحكم للتأكد من عدم اقترابك من حد المعدل. (6) راقب صفحة حالة الخدمة للحوادث المحتملة. إذا استمرت المشاكل، <a href="{{ route('contact') }}">تواصل مع الدعم</a> مع تفاصيل التوقيت ومعرّفات طلباتك.
                        </p>
                    @else
                        <p>API response time depends on several factors: model size (smaller models are faster), request complexity, and server load. If you're experiencing slow responses, try these steps: (1) Use a smaller, faster model if accuracy allows—check response times on the Features page. (2) Use streaming mode to get faster perceived responses. (3) Optimize your prompts to be more concise. (4) Check your network connection and ping latency to our servers. (5) Check the dashboard to see if you're near your rate limit—rate-limited requests will be slow. (6) Monitor our status page for service incidents. If problems persist after these steps, <a href="{{ route('contact') }}">contact support</a> with timing details and your request IDs for investigation.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q14 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">كيف يمكنني تحسين طلبات API الخاصة بي؟</span>
                @else
                    How can I optimize my API requests?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            تحسين استخدام API يوفر الوقت والمال. إليك الاستراتيجيات الرئيسية: (1) اكتب محفزات واضحة وموجزة — كلمات أقل تعني رموز إدخال أقل واستجابات أسرع. (2) استخدم أصغر نموذج يناسب مهمتك — Llama 7B كافٍ غالباً للمهام البسيطة وأقل تكلفة. (3) نفّذ التخزين المؤقت للطلبات الشائعة باستخدام طبقة كاش التطبيق. (4) استخدم المعالجة الدفعية في أوقات الذروة المنخفضة للطلبات غير العاجلة. (5) اضبط معاملات temperature وmax_tokens بشكل مناسب. (6) استخدم System Prompts بفعالية لتوجيه سلوك النموذج. (7) راقب استخدام الرموز في لوحة التحكم وعدّل أسلوبك حسب الحاجة. (8) فكّر في استخدام التدفق لأداء أفضل. راجع <a href="{{ route('docs.index') }}">التوثيق</a> للأمثلة التفصيلية.
                        </p>
                    @else
                        <p>Optimizing API usage saves both time and money. Here are key strategies: (1) Write clear, concise prompts—fewer words means fewer input tokens and faster responses. (2) Use the smallest model that works for your task—Llama 7B is often sufficient for simple tasks and costs less. (3) Implement caching for common requests using your application's cache layer. (4) Use batch processing during off-peak hours for non-urgent requests. (5) Set appropriate temperature and max_tokens parameters—lower max_tokens ends generation earlier. (6) Use system prompts effectively to guide the model's behavior. (7) Monitor your token usage in the dashboard and adjust your approach as needed. (8) Consider using streaming for better perceived performance. See our <a href="{{ route('docs.index') }}">documentation</a> for detailed optimization examples.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Q15 --}}
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                @if(app()->getLocale() === 'ar')
                    <span style="font-family:'Tajawal',sans-serif;">هل يمكنني الانتقال من OpenAI إلى LLM Resayil؟</span>
                @else
                    Can I migrate from OpenAI to LLM Resayil?
                @endif
                <span class="faq-toggle">▼</span>
            </button>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    @if(app()->getLocale() === 'ar')
                        <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            نعم، الانتقال من OpenAI إلى LLM Resayil بسيط لأن واجهتنا متوافقة مع OpenAI. معظم الكود الموجود لديك سيعمل بتغييرات طفيفة. تحتاج فقط إلى تغيير عنوان URL لنقطة النهاية من <code>https://api.openai.com/v1</code> إلى نقطتنا، واستبدال مفتاح OpenAI بمفتاح LLM Resayil API الخاص بك. قد تختلف أسماء النماذج — مثلاً، "gpt-3.5-turbo" من OpenAI يصبح "mistral" أو "neural-chat" في LLM Resayil. تعرض <a href="{{ route('comparison') }}">صفحة المقارنة</a> فروق التكلفة والسرعة. ستوفر عادةً 70-90% من التكاليف مع الحصول على جودة استجابة مماثلة أو أفضل في كثير من حالات الاستخدام. جرّبه بلا مخاطرة مع 1,000 رصيد مجاني.
                        </p>
                    @else
                        <p>Yes, migrating from OpenAI to LLM Resayil is straightforward because our API is OpenAI-compatible. Most of your existing code will work with minimal changes. You only need to change the API endpoint URL from <code>https://api.openai.com/v1</code> to our endpoint, and swap your OpenAI key for your LLM Resayil API key. Model names may differ—for example, OpenAI's "gpt-3.5-turbo" becomes "mistral" or "neural-chat" on LLM Resayil. Our <a href="{{ route('comparison') }}">comparison page</a> shows cost and speed differences. You'll typically save 70-90% on costs while getting similar or better response quality for many use cases. Try it risk-free with your 1,000 free credits, and reach out if you need help with the migration.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- CTA Section -->
    <div class="faq-section">
        <div class="faq-cta">
            @if(app()->getLocale() === 'ar')
                <h3 style="font-family:'Tajawal',sans-serif;" dir="rtl">هل لا تزال لديك أسئلة؟</h3>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">لم تجد الإجابة التي تبحث عنها؟ فريق الدعم لدينا هنا للمساعدة.</p>
                <div class="faq-cta-links">
                    <a href="{{ route('docs.index') }}" class="faq-cta-link" style="font-family:'Tajawal',sans-serif;">اقرأ التوثيق</a>
                    <a href="{{ route('features') }}" class="faq-cta-link" style="font-family:'Tajawal',sans-serif;">استكشف الميزات</a>
                    <a href="{{ route('contact') }}" class="faq-cta-link" style="font-family:'Tajawal',sans-serif;">تواصل مع الدعم</a>
                </div>
            @else
                <h3>Still Have Questions?</h3>
                <p>Can't find the answer you're looking for? Our support team is here to help.</p>
                <div class="faq-cta-links">
                    <a href="{{ route('docs.index') }}" class="faq-cta-link">Read Documentation</a>
                    <a href="{{ route('features') }}" class="faq-cta-link">Explore Features</a>
                    <a href="{{ route('contact') }}" class="faq-cta-link">Contact Support</a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFaq(button) {
        const item = button.parentElement;
        item.classList.toggle('active');
    }

    // Auto-expand first item for better UX
    document.addEventListener('DOMContentLoaded', function() {
        const firstItem = document.querySelector('.faq-item');
        if (firstItem) {
            firstItem.classList.add('active');
        }
    });
</script>

<!-- FAQPage Schema Markup -->
<script type="application/ld+json">
@php
$faqs = [
    [
        'question' => 'How do I get started with the LLM Resayil API?',
        'answer' => 'Getting started with LLM Resayil is quick and simple. First, create a free account at LLM Resayil. You will receive 1,000 free credits immediately to start testing. Then, generate an API key from your dashboard under the "API Keys" section. Your API key should be included in every request as a Bearer token in the Authorization header. You can use our API documentation to find code examples for Python, JavaScript, and cURL. Start by making a simple chat completion request to test your setup, and you are ready to integrate LLM Resayil into your application.'
    ],
    [
        'question' => 'What authentication method does LLM Resayil use?',
        'answer' => 'LLM Resayil uses Bearer token authentication with API keys. When you create an API key in your dashboard, you will get a long token string. Include this token in the Authorization header of every API request with the format: Authorization: Bearer YOUR_API_KEY. This is the same pattern used by OpenAI, so if you have used their API before, the authentication will be familiar. API keys are secret—never share them publicly or commit them to version control. You can create multiple API keys in your dashboard for different applications or environments, and revoke them at any time if compromised.'
    ],
    [
        'question' => 'Which models are available?',
        'answer' => 'LLM Resayil offers access to 45+ powerful language models. We support popular models including Meta Llama 2 (7B, 13B, 70B), Mistral (7B, Instruct, Mixtral), Neural Chat, Orca, and many more. Each model has different capabilities, speeds, and pricing. The smaller models (7B parameters) are faster and cheaper, ideal for simple tasks and high-volume applications. Larger models (70B) offer better reasoning and understanding for complex tasks. You can see all available models on our Features page or in your dashboard under "Models." We regularly add new models based on community requests and emerging research.'
    ],
    [
        'question' => 'What is the API rate limit?',
        'answer' => 'Rate limits on LLM Resayil are subscription-based. Free tier accounts can make up to 10 requests per minute. Basic tier users get 100 requests per minute. Pro tier enables 500 requests per minute. Admin/Enterprise tier has custom limits. These limits are designed to prevent abuse while allowing legitimate high-volume applications. If you exceed your tier limit, requests will receive a 429 (Too Many Requests) status code. You can monitor your current usage in the dashboard. If you need a higher rate limit, upgrade your subscription tier or contact us for an enterprise plan with custom limits tailored to your needs.'
    ],
    [
        'question' => 'How do I handle errors in my application?',
        'answer' => 'The LLM Resayil API returns standard HTTP status codes and error messages. Always check the status code in your response: 2xx (200-299) means success, 4xx (400-499) indicates client errors, 5xx (500-599) indicates server errors. Common errors include 401 Unauthorized (invalid API key), 429 Too Many Requests (rate limit exceeded), and 400 Bad Request (invalid parameters). Error responses include a JSON body with an error message explaining what went wrong. Implement proper error handling in your code: validate parameters before sending requests, handle rate limit errors with exponential backoff, and log errors for debugging.'
    ],
    [
        'question' => 'How does billing work?',
        'answer' => 'LLM Resayil uses a pay-per-token credit system with no monthly subscriptions. You purchase credit packs that start from as low as 2 KWD for 5,000 credits. When you make API requests, tokens are deducted from your balance in real-time. Input tokens (your request) and output tokens (the model response) are counted separately, with different rates per model. Smaller models are cheaper per token, while larger models cost more but provide better quality. Credits do not expire—use them whenever you want. You can purchase credits anytime through your billing dashboard, and the top-up is instant.'
    ],
    [
        'question' => 'How can I monitor my spending?',
        'answer' => 'Your LLM Resayil dashboard provides real-time spending and usage monitoring. The Dashboard page shows your current credit balance, daily/monthly usage charts, and cost breakdown by model. You can see exactly how many tokens you have used and how much each model has cost. The Billing section displays your transaction history, credit purchases, and usage trends. API responses include token counts so you can track spending in your own logs. The dashboard updates in real-time as you make API calls, so you can always see your current usage and budget status.'
    ],
    [
        'question' => 'Can I set usage limits or spending caps?',
        'answer' => 'Yes, you can control your spending through subscription tiers. Each subscription tier has built-in rate limits (requests per minute), which naturally limits your overall consumption. For example, the Free tier 10 requests/minute limit prevents runaway costs. If you need stricter spending controls, upgrade to a higher tier or contact our sales team for an enterprise plan with custom spending caps. You should also implement safeguards in your application code: validate inputs, cache responses when possible, use smaller models for simple tasks, and monitor API usage continuously.'
    ],
    [
        'question' => 'Does LLM Resayil support streaming responses?',
        'answer' => 'Yes, LLM Resayil fully supports streaming responses using Server-Sent Events (SSE). Instead of waiting for the entire response, streaming sends tokens to your client as they are generated by the model, providing a much faster perceived response time. This is especially useful for chat applications and long-form content generation. To use streaming, set stream: true in your API request parameters. The response will be a stream of data lines, each containing one or more tokens. This is identical to the streaming format used by OpenAI, so if you have existing OpenAI streaming code, it will work with LLM Resayil with minimal changes.'
    ],
    [
        'question' => 'Can I use custom models or fine-tuned models?',
        'answer' => 'Currently, LLM Resayil provides access to 45+ pre-trained open-source models. Custom model hosting and fine-tuning services are available through our enterprise offering. If you need to fine-tune a model or host a custom model, contact our enterprise team to discuss dedicated server options. Many users find that careful prompt engineering and selecting the right base model from our catalog solves 90% of use cases without needing fine-tuning. Start with our available models and let us know if your requirements evolve.'
    ],
    [
        'question' => 'Is there an SLA for API uptime?',
        'answer' => 'LLM Resayil targets 99.5% uptime for our API infrastructure. We run redundant servers and have automatic failover in place to handle service disruptions. Enterprise customers can purchase dedicated SLA agreements with guaranteed uptime and priority support. Our API uses a hybrid approach: if local capacity is exceeded, requests automatically failover to cloud models to ensure service continuity. You can check our system status and incident history on our status page. For production applications requiring higher uptime guarantees, contact our enterprise team for custom SLA options.'
    ],
    [
        'question' => 'Why am I getting a 401 Unauthorized error?',
        'answer' => 'A 401 Unauthorized error means your API key is missing, invalid, or expired. First, verify you are including the API key in your request header: Authorization: Bearer YOUR_API_KEY. Check that you copied the entire API key correctly—even one missing character will fail. If your API key seems correct, generate a new one in your dashboard: go to API Keys, click the create button, copy the new key, and test it. If you revoked an old key, you must use the new one. Make sure your API key has not been regenerated in another session. If problems persist, check that you are using the correct API endpoint URL.'
    ],
    [
        'question' => 'What should I do if the API is slow?',
        'answer' => 'API response time depends on several factors: model size (smaller models are faster), request complexity, and server load. If you are experiencing slow responses, try these steps: (1) Use a smaller, faster model if accuracy allows. (2) Use streaming mode to get faster perceived responses. (3) Optimize your prompts to be more concise. (4) Check your network connection and ping latency to our servers. (5) Check the dashboard to see if you are near your rate limit. (6) Monitor our status page for service incidents. If problems persist, contact support with timing details and your request IDs for investigation.'
    ],
    [
        'question' => 'How can I optimize my API requests?',
        'answer' => 'Optimizing API usage saves both time and money. Here are key strategies: (1) Write clear, concise prompts—fewer words means fewer input tokens and faster responses. (2) Use the smallest model that works for your task—Llama 7B is often sufficient for simple tasks and costs less. (3) Implement caching for common requests using your application cache layer. (4) Use batch processing during off-peak hours for non-urgent requests. (5) Set appropriate temperature and max_tokens parameters—lower max_tokens ends generation earlier. (6) Use system prompts effectively to guide the model behavior. (7) Monitor your token usage in the dashboard and adjust your approach as needed. (8) Consider using streaming for better perceived performance.'
    ],
    [
        'question' => 'Can I migrate from OpenAI to LLM Resayil?',
        'answer' => 'Yes, migrating from OpenAI to LLM Resayil is straightforward because our API is OpenAI-compatible. Most of your existing code will work with minimal changes. You only need to change the API endpoint URL from https://api.openai.com/v1 to our endpoint, and swap your OpenAI key for your LLM Resayil API key. Model names may differ—for example, OpenAI gpt-3.5-turbo becomes mistral or neural-chat on LLM Resayil. Our comparison page shows cost and speed differences. You will typically save 70-90% on costs while getting similar or better response quality for many use cases. Try it risk-free with your 1,000 free credits.'
    ],
];

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(function($item) {
        return [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['answer']
            ]
        ];
    }, $faqs)
];
@endphp
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@endsection
