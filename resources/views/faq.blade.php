@extends('layouts.app')

@section('title', $pageTitle ?? 'FAQ — LLM Resayil')

@push('styles')
<style>
    /* ─────────────────────────────────────────
       FAQ PAGE — Dark Luxury Redesign
       Design System: bg #0f1115 / gold #d4af37
    ───────────────────────────────────────── */

    .faq-wrap {
        background: #0a0d14;
        min-height: 100vh;
    }

    /* ── Hero ── */
    .faq-hero {
        position: relative;
        padding: 5rem 2rem 4rem;
        text-align: center;
        overflow: hidden;
    }

    .faq-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 55% at 50% -5%, rgba(212,175,55,0.10) 0%, transparent 65%),
            radial-gradient(ellipse 35% 25% at 80% 15%, rgba(212,175,55,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .faq-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(212,175,55,0.07) 1px, transparent 1px);
        background-size: 30px 30px;
        mask-image: radial-gradient(ellipse 65% 55% at center, black, transparent);
        pointer-events: none;
    }

    .faq-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 1rem;
        background: rgba(212,175,55,0.10);
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: 2rem;
        color: #d4af37;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 1;
    }

    .faq-badge svg {
        width: 13px;
        height: 13px;
        opacity: 0.85;
    }

    .faq-hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        line-height: 1.12;
        color: #e0e5ec;
        margin-bottom: 1.25rem;
        position: relative;
        z-index: 1;
    }

    .faq-hero h1 .gold {
        background: linear-gradient(135deg, #d4af37, #eac558);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .faq-hero-lead {
        font-size: 1.05rem;
        color: #a0a8b5;
        line-height: 1.75;
        max-width: 600px;
        margin: 0 auto 2.25rem;
        position: relative;
        z-index: 1;
    }

    .faq-hero-lead a {
        color: #d4af37;
        text-decoration: none;
        border-bottom: 1px solid rgba(212,175,55,0.35);
        transition: border-color 0.2s;
    }

    .faq-hero-lead a:hover {
        border-bottom-color: #d4af37;
    }

    /* ── Search Bar ── */
    .faq-search-wrap {
        max-width: 480px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .faq-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }

    .faq-search-icon svg {
        width: 17px;
        height: 17px;
    }

    #faqSearch {
        width: 100%;
        background: #13161d;
        border: 1px solid #1e2230;
        border-radius: 10px;
        padding: 0.8rem 1rem 0.8rem 2.75rem;
        font-size: 0.95rem;
        color: #e0e5ec;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
        box-sizing: border-box;
    }

    #faqSearch::placeholder {
        color: #6b7280;
    }

    #faqSearch:focus {
        border-color: rgba(212,175,55,0.5);
        box-shadow: 0 0 0 3px rgba(212,175,55,0.08);
    }

    /* ── Category Pills ── */
    .faq-cats {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
        padding: 2.5rem 2rem 0;
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-cat-pill {
        padding: 0.4rem 1rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #1e2230;
        background: #13161d;
        color: #a0a8b5;
        transition: all 0.2s;
        user-select: none;
    }

    .faq-cat-pill:hover {
        border-color: rgba(212,175,55,0.3);
        color: #d4af37;
    }

    .faq-cat-pill.active {
        background: #d4af37;
        border-color: #d4af37;
        color: #0f1115;
    }

    /* ── FAQ Section ── */
    .faq-section {
        max-width: 820px;
        margin: 0 auto;
        padding: 2.5rem 2rem 2rem;
    }

    .faq-category-group {
        margin-bottom: 2.5rem;
    }

    .faq-category-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #d4af37;
        margin-bottom: 1rem;
        padding-left: 0.25rem;
        display: block;
    }

    /* ── Accordion Items ── */
    .faq-item {
        background: #13161d;
        border: 1px solid #1e2230;
        border-left: 3px solid transparent;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .faq-item:hover {
        border-color: rgba(212,175,55,0.25);
        box-shadow: 0 2px 16px rgba(0,0,0,0.3);
    }

    .faq-item.active {
        border-color: rgba(212,175,55,0.35);
        border-left-color: #d4af37;
        box-shadow: 0 4px 24px rgba(0,0,0,0.35);
    }

    .faq-question {
        padding: 1.35rem 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        background: transparent;
        border: none;
        width: 100%;
        text-align: left;
        font-size: 0.975rem;
        font-weight: 600;
        color: #e0e5ec;
        transition: color 0.2s, background 0.2s;
        font-family: inherit;
    }

    .faq-item.active .faq-question {
        color: #d4af37;
        background: rgba(212,175,55,0.04);
    }

    .faq-question:hover {
        color: #d4af37;
        background: rgba(212,175,55,0.03);
    }

    .faq-toggle {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #1a1d24;
        border: 1px solid #1e2230;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease-out, background 0.2s, border-color 0.2s;
    }

    .faq-toggle svg {
        width: 14px;
        height: 14px;
        color: #6b7280;
        transition: color 0.2s;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(180deg);
        background: rgba(212,175,55,0.12);
        border-color: rgba(212,175,55,0.3);
    }

    .faq-item.active .faq-toggle svg {
        color: #d4af37;
    }

    /* ── Accordion Answer — max-height CSS transition ── */
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease-out;
    }

    .faq-item.active .faq-answer {
        max-height: 1200px;
        transition: max-height 0.45s ease-in;
    }

    .faq-answer-inner {
        margin: 0 1.5rem;
        padding: 1rem 0 1.25rem;
        color: #a0a8b5;
        line-height: 1.8;
        font-size: 0.925rem;
        border-top: 1px solid rgba(30,34,48,0.8);
    }

    .faq-item.active .faq-answer-inner {
        border-top-color: rgba(212,175,55,0.1);
    }

    .faq-answer-inner p {
        margin-bottom: 0.75rem;
    }

    .faq-answer-inner p:last-child {
        margin-bottom: 0;
    }

    .faq-answer-inner ul {
        margin: 0.75rem 0;
        padding-left: 1.5rem;
    }

    .faq-answer-inner li {
        margin-bottom: 0.45rem;
    }

    .faq-answer-inner code {
        background: rgba(212,175,55,0.10);
        padding: 0.2rem 0.45rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        color: #eac558;
    }

    .faq-answer-inner a {
        color: #d4af37;
        text-decoration: none;
        border-bottom: 1px solid rgba(212,175,55,0.3);
        transition: border-color 0.2s;
    }

    .faq-answer-inner a:hover {
        border-bottom-color: #d4af37;
    }

    /* ── No-results message ── */
    .faq-no-results {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
        font-size: 0.95rem;
        display: none;
    }

    /* ── Gold divider ── */
    .faq-divider {
        max-width: 820px;
        margin: 0 auto 2.5rem;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(212,175,55,0.2), transparent);
    }

    /* ── CTA Section ── */
    .faq-cta-wrap {
        max-width: 820px;
        margin: 0 auto;
        padding: 0 2rem 5rem;
    }

    .faq-cta {
        background: linear-gradient(135deg, rgba(212,175,55,0.07), rgba(212,175,55,0.02));
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 16px;
        padding: 3rem 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .faq-cta::before {
        content: '';
        position: absolute;
        top: -40%;
        left: 50%;
        transform: translateX(-50%);
        width: 300px;
        height: 200px;
        background: radial-gradient(ellipse, rgba(212,175,55,0.08), transparent 70%);
        pointer-events: none;
    }

    .faq-cta h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #e0e5ec;
        margin-bottom: 0.6rem;
        position: relative;
        z-index: 1;
    }

    .faq-cta p {
        color: #a0a8b5;
        margin-bottom: 1.75rem;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .faq-cta-links {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .faq-cta-link {
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
    }

    .faq-cta-link.primary {
        background: linear-gradient(135deg, #d4af37, #eac558);
        color: #0f1115;
        box-shadow: 0 0 20px rgba(212,175,55,0.2);
    }

    .faq-cta-link.primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 24px rgba(212,175,55,0.3);
        color: #0f1115;
    }

    .faq-cta-link.secondary {
        background: #13161d;
        border: 1px solid rgba(212,175,55,0.3);
        color: #d4af37;
    }

    .faq-cta-link.secondary:hover {
        background: rgba(212,175,55,0.08);
        border-color: #d4af37;
    }

    /* ── Reduced motion ── */
    @media (prefers-reduced-motion: reduce) {
        .faq-answer { transition: none; }
        .faq-toggle { transition: none; }
        .faq-item { transition: none; }
    }

    /* ── Responsive ── */
    @media (max-width: 600px) {
        .faq-hero { padding: 3.5rem 1.5rem 3rem; }
        .faq-section { padding: 2rem 1.25rem; }
        .faq-cta-wrap { padding: 0 1.25rem 4rem; }
        .faq-cta { padding: 2rem 1.5rem; }
        .faq-question { padding: 1.15rem 1.25rem; font-size: 0.925rem; }
        .faq-cta-links { flex-direction: column; }
        .faq-cta-link { justify-content: center; }
        .faq-cats { padding: 2rem 1.25rem 0; }
    }

    /* ── RTL overrides ── */
    [dir="rtl"] .faq-question {
        text-align: right;
    }

    [dir="rtl"] .faq-answer-inner {
        direction: rtl;
        text-align: right;
    }

    [dir="rtl"] .faq-answer-inner ul {
        padding-left: 0;
        padding-right: 1.5rem;
    }

    [dir="rtl"] .faq-item {
        border-left: 1px solid #1e2230;
        border-right: 3px solid transparent;
    }

    [dir="rtl"] .faq-item.active {
        border-right-color: #d4af37;
    }
</style>
@endpush

@section('content')
<div class="faq-wrap">

    {{-- ── Hero ── --}}
    <div class="faq-hero">
        @if(app()->getLocale() === 'ar')
            <span class="faq-badge" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><circle cx="12" cy="17" r=".5" fill="currentColor"/></svg>
                المساعدة والدعم
            </span>
            <h1 dir="rtl" style="font-family:'Tajawal',sans-serif;">الأسئلة <span class="gold">الشائعة</span></h1>
            <p class="faq-hero-lead" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                اعثر على إجابات للأسئلة الشائعة حول واجهة برمجة LLM Resayil والفوترة والنماذج واستكشاف الأخطاء.
                لا تجد ما تبحث عنه؟ <a href="{{ route('contact') }}">تواصل مع فريق الدعم</a>.
            </p>
        @else
            <span class="faq-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><circle cx="12" cy="17" r=".5" fill="currentColor"/></svg>
                Help &amp; Support
            </span>
            <h1>Frequently Asked <span class="gold">Questions</span></h1>
            <p class="faq-hero-lead">
                Find answers to common questions about the LLM Resayil API, billing, models, and troubleshooting.
                Can't find what you need? <a href="{{ route('contact') }}">Contact our support team</a>.
            </p>
        @endif

        {{-- Search input --}}
        <div class="faq-search-wrap">
            <div class="faq-search-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </div>
            @if(app()->getLocale() === 'ar')
                <input id="faqSearch" type="text" placeholder="ابحث في الأسئلة..." dir="rtl"
                    style="padding-left:1rem;padding-right:2.75rem;font-family:'Tajawal',sans-serif;"
                    autocomplete="off" aria-label="ابحث في الأسئلة الشائعة" />
            @else
                <input id="faqSearch" type="text" placeholder="Search questions..."
                    autocomplete="off" aria-label="Search FAQ" />
            @endif
        </div>
    </div>

    {{-- ── Category Pills ── --}}
    <div class="faq-cats">
        @if(app()->getLocale() === 'ar')
            <button class="faq-cat-pill active" data-cat="all" onclick="filterCat('all',this)" style="font-family:'Tajawal',sans-serif;">الكل</button>
            <button class="faq-cat-pill" data-cat="general" onclick="filterCat('general',this)" style="font-family:'Tajawal',sans-serif;">عام</button>
            <button class="faq-cat-pill" data-cat="api" onclick="filterCat('api',this)">API</button>
            <button class="faq-cat-pill" data-cat="billing" onclick="filterCat('billing',this)" style="font-family:'Tajawal',sans-serif;">الفوترة</button>
            <button class="faq-cat-pill" data-cat="models" onclick="filterCat('models',this)" style="font-family:'Tajawal',sans-serif;">النماذج</button>
            <button class="faq-cat-pill" data-cat="troubleshoot" onclick="filterCat('troubleshoot',this)" style="font-family:'Tajawal',sans-serif;">استكشاف الأخطاء</button>
        @else
            <button class="faq-cat-pill active" data-cat="all" onclick="filterCat('all',this)">All</button>
            <button class="faq-cat-pill" data-cat="general" onclick="filterCat('general',this)">General</button>
            <button class="faq-cat-pill" data-cat="api" onclick="filterCat('api',this)">API</button>
            <button class="faq-cat-pill" data-cat="billing" onclick="filterCat('billing',this)">Billing</button>
            <button class="faq-cat-pill" data-cat="models" onclick="filterCat('models',this)">Models</button>
            <button class="faq-cat-pill" data-cat="troubleshoot" onclick="filterCat('troubleshoot',this)">Troubleshooting</button>
        @endif
    </div>

    {{-- ── FAQ List ── --}}
    <div class="faq-section" id="faqList">

        {{-- ─ GENERAL ─ --}}
        <div class="faq-category-group" data-group="general">
            @if(app()->getLocale() === 'ar')
                <span class="faq-category-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">عام</span>
            @else
                <span class="faq-category-label">General</span>
            @endif

            {{-- Q: Get started --}}
            <div class="faq-item" data-cat="general"
                 data-en="how do i get started llm resayil api"
                 data-ar="كيف أبدأ مع واجهة برمجة llm resayil">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف أبدأ مع واجهة برمجة LLM Resayil؟</span>
                    @else
                        <span>How do I get started with the LLM Resayil API?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                البدء سريع وبسيط. أنشئ حساباً مجانياً — ستتلقى 1,000 رصيد فوراً لبدء الاختبار.
                                ثم أنشئ مفتاح API من لوحة التحكم ضمن "مفاتيح API". أدرج هذا المفتاح في كل طلب
                                كـ Bearer token في رأس Authorization. راجع <a href="{{ route('docs.index') }}">توثيق API</a>
                                للحصول على أمثلة بـ Python وJavaScript وcURL.
                            </p>
                        @else
                            <p>
                                Getting started is quick and simple. Create a free account — you'll receive 1,000 free credits immediately.
                                Then generate an API key from your dashboard under "API Keys." Include this key in every request as a
                                Bearer token in the Authorization header. See our <a href="{{ route('docs.index') }}">API documentation</a>
                                for code examples in Python, JavaScript, and cURL.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Migrate from OpenAI --}}
            <div class="faq-item" data-cat="general"
                 data-en="migrate from openai to llm resayil compatible"
                 data-ar="الانتقال من openai إلى llm resayil متوافق">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">هل يمكنني الانتقال من OpenAI إلى LLM Resayil؟</span>
                    @else
                        <span>Can I migrate from OpenAI to LLM Resayil?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                نعم، الانتقال سهل لأن واجهة برمجتنا متوافقة مع OpenAI. غيّر فقط عنوان URL من
                                <code>https://api.openai.com/v1</code> إلى نقطة نهايتنا، واستبدل مفتاح OpenAI
                                بمفتاح LLM Resayil. ستوفر عادةً 70–90% من التكاليف. جرّب مجاناً
                                باستخدام 1,000 رصيد — لا تحتاج إلى بطاقة ائتمانية.
                            </p>
                        @else
                            <p>
                                Yes — our API is OpenAI-compatible, so migration is minimal. Change the endpoint URL from
                                <code>https://api.openai.com/v1</code> to ours, and swap your OpenAI key for your LLM Resayil
                                API key. You'll typically save 70–90% on costs. Try it risk-free with your 1,000 free credits —
                                no credit card required.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: SLA / Uptime --}}
            <div class="faq-item" data-cat="general"
                 data-en="sla api uptime availability reliability"
                 data-ar="اتفاقية مستوى خدمة وقت التشغيل التوافر">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">هل يوجد اتفاقية مستوى خدمة لوقت تشغيل API؟</span>
                    @else
                        <span>Is there an SLA for API uptime?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                يستهدف LLM Resayil وقت تشغيل 99.5% لبنيتنا التحتية. نستخدم خوادم متكررة وتجاوز فشل
                                تلقائي للتعامل مع الانقطاعات. يمكن لعملاء المؤسسات شراء اتفاقيات SLA مخصصة مع دعم
                                ذي أولوية. تواصل مع فريقنا للحصول على خيارات مخصصة.
                            </p>
                        @else
                            <p>
                                LLM Resayil targets 99.5% uptime for our API infrastructure. We run redundant servers with
                                automatic failover to handle disruptions. Enterprise customers can purchase dedicated SLA
                                agreements with guaranteed uptime and priority support. Contact our team for custom SLA options.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ─ API ─ --}}
        <div class="faq-category-group" data-group="api">
            @if(app()->getLocale() === 'ar')
                <span class="faq-category-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">واجهة برمجة التطبيقات</span>
            @else
                <span class="faq-category-label">API</span>
            @endif

            {{-- Q: Authentication --}}
            <div class="faq-item" data-cat="api"
                 data-en="authentication bearer token api key authorization"
                 data-ar="المصادقة bearer token مفتاح api ترخيص">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">ما طريقة المصادقة التي يستخدمها LLM Resayil؟</span>
                    @else
                        <span>What authentication method does LLM Resayil use?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                يستخدم LLM Resayil مصادقة Bearer token. أدرج مفتاحك في رأس <code>Authorization</code>
                                لكل طلب: <code>Authorization: Bearer YOUR_API_KEY</code>. هذا نفس نمط OpenAI.
                                المفاتيح سرية — لا تشاركها علناً. يمكنك إنشاء مفاتيح متعددة لبيئات مختلفة وإلغاؤها في أي وقت.
                            </p>
                        @else
                            <p>
                                LLM Resayil uses Bearer token authentication. Include your API key in the
                                <code>Authorization</code> header of every request:
                                <code>Authorization: Bearer YOUR_API_KEY</code>.
                                This is identical to OpenAI's pattern. Keys are secret — never share them publicly or
                                commit them to source control. Create multiple keys for different environments and
                                revoke them any time.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Rate limits --}}
            <div class="faq-item" data-cat="api"
                 data-en="rate limit requests per minute throttle 429"
                 data-ar="حد المعدل طلبات في الدقيقة 429">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">ما هو حد معدل API؟</span>
                    @else
                        <span>What is the API rate limit?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                حدود المعدل مرتبطة بمستوى الاشتراك: الحساب المجاني 10 طلبات/دقيقة،
                                المستوى الأساسي 100 طلب/دقيقة، المستوى الاحترافي 500 طلب/دقيقة،
                                والمستوى المؤسسي بحدود مخصصة. تجاوز الحد يُرجع رمز الحالة 429.
                                ارقَ مستواك أو تواصل معنا للحصول على خطة مؤسسية بحدود مخصصة.
                            </p>
                        @else
                            <p>
                                Rate limits are tier-based. Free: 10 req/min. Basic: 100 req/min. Pro: 500 req/min.
                                Enterprise: custom. Exceeding your limit returns HTTP 429 (Too Many Requests).
                                Monitor usage in your dashboard, upgrade your tier, or contact us for an enterprise
                                plan with custom thresholds.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Streaming --}}
            <div class="faq-item" data-cat="api"
                 data-en="streaming sse server sent events real time tokens"
                 data-ar="بث sse الوقت الفعلي رموز">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">هل يدعم LLM Resayil استجابات البث المباشر؟</span>
                    @else
                        <span>Does LLM Resayil support streaming responses?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                نعم، ندعم بالكامل استجابات البث عبر Server-Sent Events (SSE). بدلاً من الانتظار
                                للاستجابة الكاملة، يرسل البث الرموز فور توليدها — مما يوفر وقت استجابة مدرك أسرع بكثير.
                                لتفعيل البث، اضبط <code>stream: true</code> في معاملات طلبك. التنسيق متطابق مع
                                بث OpenAI، لذا الكود الحالي سيعمل مع تغييرات بسيطة.
                            </p>
                        @else
                            <p>
                                Yes, we fully support streaming via Server-Sent Events (SSE). Instead of waiting for the
                                full response, tokens are sent as they're generated — providing dramatically faster perceived
                                response times. To enable streaming, set <code>stream: true</code> in your request parameters.
                                The format is identical to OpenAI's streaming, so existing code works with minimal changes.
                                See our <a href="{{ route('docs.index') }}">documentation</a> for examples.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Error handling --}}
            <div class="faq-item" data-cat="api"
                 data-en="error handling http status codes 401 429 400 500"
                 data-ar="معالجة الأخطاء رموز الحالة http">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف أتعامل مع الأخطاء في تطبيقي؟</span>
                    @else
                        <span>How do I handle errors in my application?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                تُرجع واجهة برمجة LLM Resayil رموز حالة HTTP قياسية. 2xx = نجاح،
                                4xx = أخطاء العميل، 5xx = أخطاء الخادم. الأخطاء الشائعة: 401 (مفتاح API غير صالح)،
                                429 (تجاوز حد المعدل)، 400 (معاملات غير صالحة). طبّق معالجة أخطاء مناسبة:
                                استخدم التراجع الأسي لأخطاء 429، وتحقق من صحة المعاملات قبل الإرسال.
                            </p>
                        @else
                            <p>
                                The API returns standard HTTP status codes: 2xx = success, 4xx = client errors,
                                5xx = server errors. Common errors: 401 (invalid API key), 429 (rate limit exceeded),
                                400 (bad parameters). Implement proper handling: use exponential backoff for 429s,
                                validate parameters before sending, log error bodies (they contain descriptive messages).
                                See our <a href="{{ route('docs.index') }}">documentation</a> for the full error code reference.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Optimization --}}
            <div class="faq-item" data-cat="api"
                 data-en="optimize api requests performance cost tokens caching"
                 data-ar="تحسين طلبات api الأداء التكلفة الرموز التخزين المؤقت">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف يمكنني تحسين طلبات API؟</span>
                    @else
                        <span>How can I optimize my API requests?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <ul dir="rtl" style="font-family:'Tajawal',sans-serif;padding-left:0;padding-right:1.5rem;">
                                <li>اكتب مطالبات موجزة — كلمات أقل تعني رموزاً أقل وردوداً أسرع</li>
                                <li>استخدم أصغر نموذج مناسب لمهمتك</li>
                                <li>نفّذ التخزين المؤقت للطلبات الشائعة</li>
                                <li>اضبط <code>max_tokens</code> بشكل مناسب لإنهاء التوليد مبكراً</li>
                                <li>استخدم البث لتحسين الأداء المدرك</li>
                                <li>راقب استخدام الرموز في لوحة التحكم</li>
                            </ul>
                        @else
                            <ul>
                                <li>Write concise prompts — fewer words means fewer tokens and faster responses</li>
                                <li>Use the smallest model that fits your task to save cost and time</li>
                                <li>Cache responses for repeated queries in your application layer</li>
                                <li>Set <code>max_tokens</code> appropriately to stop generation early</li>
                                <li>Enable streaming for better perceived performance in interactive apps</li>
                                <li>Monitor token usage in the dashboard and adjust your strategy accordingly</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ─ BILLING ─ --}}
        <div class="faq-category-group" data-group="billing">
            @if(app()->getLocale() === 'ar')
                <span class="faq-category-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">الفوترة</span>
            @else
                <span class="faq-category-label">Billing</span>
            @endif

            {{-- Q: How billing works --}}
            <div class="faq-item" data-cat="billing"
                 data-en="billing credits pay per token no subscription kwd"
                 data-ar="فوترة رصيد دفع مقابل الاستخدام دون اشتراك دينار كويتي">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف تعمل الفوترة؟</span>
                    @else
                        <span>How does billing work?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                يستخدم LLM Resayil نظام رصيد بالدفع مقابل الاستخدام بدون اشتراكات شهرية. تشتري
                                حزم رصيد تبدأ من 2 دينار كويتي مقابل 5,000 رصيد. عند إرسال طلبات API، تُخصم
                                الرموز من رصيدك في الوقت الفعلي. رموز الإدخال والإخراج تُحسب بشكل منفصل.
                                الأرصدة لا تنتهي صلاحيتها — استخدمها متى شئت.
                            </p>
                        @else
                            <p>
                                LLM Resayil uses a pay-per-token credit system — no monthly subscriptions. Purchase
                                credit packs starting from 2 KWD for 5,000 credits. Tokens are deducted in real-time
                                as you make API calls. Input and output tokens are counted separately at different rates
                                per model. Credits never expire and top-ups are instant. Use the
                                <a href="{{ route('cost-calculator') }}">cost calculator</a> to estimate spending.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Monitor spending --}}
            <div class="faq-item" data-cat="billing"
                 data-en="monitor spending usage dashboard balance real time"
                 data-ar="مراقبة الإنفاق الاستخدام لوحة التحكم الرصيد الوقت الفعلي">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف يمكنني مراقبة إنفاقي؟</span>
                    @else
                        <span>How can I monitor my spending?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                توفر لوحة تحكم LLM Resayil مراقبة إنفاق واستخدام في الوقت الفعلي. تعرض "لوحة
                                التحكم" رصيدك الحالي والمخططات اليومية/الشهرية وتفاصيل التكلفة حسب النموذج.
                                يعرض قسم "الفوترة" سجل المعاملات وعمليات شراء الرصيد. تتضمن استجابات API
                                أعداد الرموز حتى تتمكن من تتبع الإنفاق في سجلاتك الخاصة.
                            </p>
                        @else
                            <p>
                                Your dashboard provides real-time spending and usage monitoring. The Dashboard page shows
                                current credit balance, daily/monthly usage charts, and cost breakdown by model. The Billing
                                section displays transaction history and credit purchases. API responses include token counts
                                so you can track spending in your own logs. Everything updates in real-time as you make calls.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Spending caps --}}
            <div class="faq-item" data-cat="billing"
                 data-en="spending cap usage limit control budget"
                 data-ar="سقف الإنفاق حد الاستخدام التحكم الميزانية">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">هل يمكنني تحديد حدود الاستخدام أو سقف الإنفاق؟</span>
                    @else
                        <span>Can I set usage limits or spending caps?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                نعم، يمكنك التحكم في إنفاقك عبر مستويات الاشتراك — كل مستوى له حدود معدل مدمجة
                                تحد بطبيعتها من الاستهلاك الإجمالي. بالإضافة إلى ذلك، نفّذ ضمانات في كود تطبيقك:
                                خزّن الاستجابات، استخدم نماذج أصغر للمهام البسيطة، وراقب الاستخدام باستمرار.
                                تواصل معنا للحصول على خطط مؤسسية بسقوف إنفاق مخصصة.
                            </p>
                        @else
                            <p>
                                Yes — subscription tier rate limits naturally cap overall consumption. Additionally,
                                implement safeguards in your code: cache responses when possible, use smaller models for
                                simple tasks, and monitor usage continuously. The dashboard shows cost projections to
                                help estimate spending in advance. Contact us for enterprise plans with custom spending caps.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ─ MODELS ─ --}}
        <div class="faq-category-group" data-group="models">
            @if(app()->getLocale() === 'ar')
                <span class="faq-category-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">النماذج</span>
            @else
                <span class="faq-category-label">Models</span>
            @endif

            {{-- Q: Which models --}}
            <div class="faq-item" data-cat="models"
                 data-en="which models available llama mistral neural chat"
                 data-ar="ما النماذج المتاحة llama mistral">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">ما النماذج المتاحة؟</span>
                    @else
                        <span>Which models are available?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                يوفر LLM Resayil الوصول إلى أكثر من 45 نموذج AI قوي، بما في ذلك Meta Llama وMistral
                                وNeuralChat وOrca وغيرها. لكل نموذج قدرات وسرعات وأسعار مختلفة. النماذج الأصغر (7B)
                                أسرع وأرخص — مثالية للمهام البسيطة. النماذج الأكبر (70B+) توفر استنتاجاً أفضل للمهام
                                المعقدة. راجع <a href="{{ route('features') }}">صفحة الميزات</a> للقائمة الكاملة.
                            </p>
                        @else
                            <p>
                                LLM Resayil offers access to 45+ powerful AI models, including Meta Llama, Mistral,
                                NeuralChat, Orca, and more. Each has different capabilities, speeds, and pricing.
                                Smaller models (7B parameters) are faster and cheaper — ideal for simple tasks and
                                high-volume applications. Larger models (70B+) deliver superior reasoning for complex tasks.
                                See our <a href="{{ route('features') }}">Features page</a> for the full list with benchmarks.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Custom / fine-tuned models --}}
            <div class="faq-item" data-cat="models"
                 data-en="custom fine-tuned models enterprise hosting"
                 data-ar="نماذج مخصصة مدربة بدقة مؤسسية استضافة">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">هل يمكنني استخدام نماذج مخصصة أو مدربة بدقة؟</span>
                    @else
                        <span>Can I use custom or fine-tuned models?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                حالياً يوفر LLM Resayil الوصول إلى أكثر من 45 نموذج AI متاح مسبقاً. استضافة
                                النماذج المخصصة وخدمات الضبط الدقيق متاحة عبر عرضنا المؤسسي.
                                <a href="{{ route('contact') }}">تواصل مع فريقنا</a> لمناقشة الخيارات. يجد كثيرون
                                أن هندسة الإشارات الجيدة مع اختيار النموذج المناسب تحل 90% من حالات الاستخدام.
                            </p>
                        @else
                            <p>
                                Currently, LLM Resayil provides access to 45+ pre-trained AI models. Custom model hosting
                                and fine-tuning services are available through our enterprise offering —
                                <a href="{{ route('contact') }}">contact our team</a> to discuss dedicated options.
                                Many users find that careful prompt engineering with the right base model solves
                                90% of use cases without fine-tuning.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ─ TROUBLESHOOTING ─ --}}
        <div class="faq-category-group" data-group="troubleshoot">
            @if(app()->getLocale() === 'ar')
                <span class="faq-category-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">استكشاف الأخطاء</span>
            @else
                <span class="faq-category-label">Troubleshooting</span>
            @endif

            {{-- Q: 401 error --}}
            <div class="faq-item" data-cat="troubleshoot"
                 data-en="401 unauthorized error invalid api key"
                 data-ar="401 خطأ غير مصرح مفتاح api غير صالح">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">لماذا أتلقى خطأ 401 Unauthorized؟</span>
                    @else
                        <span>Why am I getting a 401 Unauthorized error?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                خطأ 401 يعني أن مفتاح API مفقود أو غير صالح أو منتهي الصلاحية. تحقق من صحة
                                الرأس: <code>Authorization: Bearer YOUR_API_KEY</code>. تأكد من نسخ المفتاح كاملاً —
                                حتى حرف واحد مفقود سيتسبب في الفشل. إذا بدا صحيحاً، أنشئ مفتاحاً جديداً من
                                قسم "مفاتيح API" في لوحة التحكم. تأكد أيضاً من استخدام عنوان URL الصحيح لنقطة النهاية.
                            </p>
                        @else
                            <p>
                                A 401 error means your API key is missing, invalid, or expired. Verify the header format:
                                <code>Authorization: Bearer YOUR_API_KEY</code>. Check that you copied the full key —
                                even one missing character causes failure. If the key looks correct, generate a new one
                                from "API Keys" in your dashboard. Also confirm you're using the correct API endpoint URL.
                                See our <a href="{{ route('docs.index') }}">documentation</a> for authentication details.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Q: Slow API --}}
            <div class="faq-item" data-cat="troubleshoot"
                 data-en="api slow performance latency response time"
                 data-ar="api بطيء الأداء زمن الاستجابة">
                <button class="faq-question" onclick="toggleFaq(this)" aria-expanded="false">
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">ما الذي يجب أن أفعله إذا كان API بطيئاً؟</span>
                    @else
                        <span>What should I do if the API is slow?</span>
                    @endif
                    <span class="faq-toggle" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        @if(app()->getLocale() === 'ar')
                            <p dir="rtl" style="font-family:'Tajawal',sans-serif;">يعتمد وقت الاستجابة على حجم النموذج وتعقيد الطلب. إذا واجهت بطئاً:</p>
                            <ul dir="rtl" style="font-family:'Tajawal',sans-serif;padding-left:0;padding-right:1.5rem;">
                                <li>استخدم نموذجاً أصغر وأسرع إذا سمحت الدقة</li>
                                <li>فعّل وضع البث للحصول على وقت استجابة مدرك أسرع</li>
                                <li>حسّن مطالباتك لتكون أكثر إيجازاً</li>
                                <li>تحقق من اقترابك من حد المعدل</li>
                                <li>إذا استمرت المشاكل، <a href="{{ route('contact') }}">تواصل مع الدعم</a> مع تفاصيل التوقيت</li>
                            </ul>
                        @else
                            <p>Response time depends on model size and request complexity. If you experience slowness:</p>
                            <ul>
                                <li>Try a smaller, faster model if accuracy permits</li>
                                <li>Enable streaming mode for faster perceived response times</li>
                                <li>Optimize prompts to be more concise</li>
                                <li>Check if you're near your rate limit — throttled requests are slower</li>
                                <li>If issues persist, <a href="{{ route('contact') }}">contact support</a> with timing details and request IDs</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- No results message --}}
        <div class="faq-no-results" id="faqNoResults">
            @if(app()->getLocale() === 'ar')
                <p style="font-family:'Tajawal',sans-serif;">لم يتم العثور على نتائج. جرّب كلمات بحث مختلفة.</p>
            @else
                <p>No results found. Try different search terms or <a href="{{ route('contact') }}" style="color:#d4af37;">contact support</a>.</p>
            @endif
        </div>
    </div>

    <div class="faq-divider"></div>

    {{-- ── CTA ── --}}
    <div class="faq-cta-wrap">
        <div class="faq-cta">
            @if(app()->getLocale() === 'ar')
                <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">لا تزال لديك أسئلة؟</h3>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">لم تجد الإجابة التي تبحث عنها؟ فريق الدعم لدينا هنا للمساعدة.</p>
                <div class="faq-cta-links">
                    <a href="{{ route('docs.index') }}" class="faq-cta-link secondary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        قراءة التوثيق
                    </a>
                    <a href="{{ route('features') }}" class="faq-cta-link secondary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        استكشاف الميزات
                    </a>
                    <a href="{{ route('contact') }}" class="faq-cta-link primary" style="font-family:'Tajawal',sans-serif;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        تواصل مع الدعم
                    </a>
                </div>
            @else
                <h3>Still Have Questions?</h3>
                <p>Can't find the answer you're looking for? Our support team is ready to help.</p>
                <div class="faq-cta-links">
                    <a href="{{ route('docs.index') }}" class="faq-cta-link secondary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Read Documentation
                    </a>
                    <a href="{{ route('features') }}" class="faq-cta-link secondary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Explore Features
                    </a>
                    <a href="{{ route('contact') }}" class="faq-cta-link primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Contact Support
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Toggle accordion (one open at a time) ── */
    window.toggleFaq = function (btn) {
        var item = btn.closest('.faq-item');
        var isOpen = item.classList.contains('active');

        // Close all
        document.querySelectorAll('.faq-item.active').forEach(function (el) {
            el.classList.remove('active');
            el.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
        });

        // Open clicked if was closed
        if (!isOpen) {
            item.classList.add('active');
            btn.setAttribute('aria-expanded', 'true');
        }
    };

    /* ── Category filter ── */
    window.filterCat = function (cat, pill) {
        document.querySelectorAll('.faq-cat-pill').forEach(function (p) { p.classList.remove('active'); });
        pill.classList.add('active');

        document.querySelectorAll('.faq-category-group').forEach(function (group) {
            var show = cat === 'all' || group.getAttribute('data-group') === cat;
            group.style.display = show ? '' : 'none';
        });

        document.getElementById('faqSearch').value = '';
        showAllItems();
        checkNoResults();
    };

    function showAllItems() {
        document.querySelectorAll('.faq-item').forEach(function (item) { item.style.display = ''; });
    }

    /* ── Search ── */
    var searchInput = document.getElementById('faqSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();

            // Reset category pills
            document.querySelectorAll('.faq-cat-pill').forEach(function (p) { p.classList.remove('active'); });
            var allPill = document.querySelector('.faq-cat-pill[data-cat="all"]');
            if (allPill) allPill.classList.add('active');

            // Show all groups first
            document.querySelectorAll('.faq-category-group').forEach(function (g) { g.style.display = ''; });

            if (!q) {
                showAllItems();
                checkNoResults();
                return;
            }

            document.querySelectorAll('.faq-item').forEach(function (item) {
                var en = (item.getAttribute('data-en') || '').toLowerCase();
                var ar = (item.getAttribute('data-ar') || '').toLowerCase();
                var qText = item.querySelector('.faq-question span') ? item.querySelector('.faq-question span').textContent.toLowerCase() : '';
                var aText = item.querySelector('.faq-answer-inner') ? item.querySelector('.faq-answer-inner').textContent.toLowerCase() : '';
                var hit = (en + ' ' + ar + ' ' + qText + ' ' + aText).indexOf(q) !== -1;
                item.style.display = hit ? '' : 'none';
            });

            // Hide empty groups
            document.querySelectorAll('.faq-category-group').forEach(function (group) {
                var hasVisible = Array.from(group.querySelectorAll('.faq-item')).some(function (i) { return i.style.display !== 'none'; });
                group.style.display = hasVisible ? '' : 'none';
            });

            checkNoResults();
        });
    }

    function checkNoResults() {
        var el = document.getElementById('faqNoResults');
        if (!el) return;
        var any = Array.from(document.querySelectorAll('.faq-item')).some(function (i) { return i.style.display !== 'none'; });
        el.style.display = any ? 'none' : 'block';
    }

    /* ── Auto-expand first item on load ── */
    document.addEventListener('DOMContentLoaded', function () {
        var first = document.querySelector('.faq-item');
        if (first) {
            first.classList.add('active');
            var btn = first.querySelector('.faq-question');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        }
    });
})();
</script>

<!-- FAQPage Schema.org Markup -->
<script type="application/ld+json">
@php
$faqs = [
    ['question' => 'How do I get started with the LLM Resayil API?', 'answer' => 'Create a free account to receive 1,000 free credits. Generate an API key from your dashboard and include it as a Bearer token in every request Authorization header.'],
    ['question' => 'What authentication method does LLM Resayil use?', 'answer' => 'Bearer token authentication. Include your key as: Authorization: Bearer YOUR_API_KEY in every request.'],
    ['question' => 'Which models are available?', 'answer' => 'LLM Resayil offers 45+ powerful AI models including Meta Llama, Mistral, NeuralChat, Orca and more.'],
    ['question' => 'What is the API rate limit?', 'answer' => 'Free: 10 req/min. Basic: 100 req/min. Pro: 500 req/min. Enterprise: custom limits.'],
    ['question' => 'How does billing work?', 'answer' => 'Pay-per-token credit system with no monthly subscriptions. Credits start from 2 KWD and never expire.'],
    ['question' => 'Does LLM Resayil support streaming responses?', 'answer' => 'Yes, via Server-Sent Events (SSE). Set stream: true in your request parameters.'],
    ['question' => 'Can I migrate from OpenAI to LLM Resayil?', 'answer' => 'Yes — our API is OpenAI-compatible. Just change the endpoint URL and API key. Save 70-90% on costs.'],
    ['question' => 'Why am I getting a 401 Unauthorized error?', 'answer' => 'Your API key is missing, invalid, or expired. Verify the header: Authorization: Bearer YOUR_API_KEY.'],
];
$schema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(function($item) {
    return ['@type' => 'Question', 'name' => $item['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']]];
}, $faqs)];
@endphp
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush
