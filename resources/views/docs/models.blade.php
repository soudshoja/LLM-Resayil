@extends('layouts.app')

@section('title', 'Available Models — API Documentation')

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

    /* Category badges */
    .badge {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .badge-chat      { background: rgba(96,165,250,0.15); color: #60a5fa; }
    .badge-code      { background: rgba(52,211,153,0.15); color: #34d399; }
    .badge-vision    { background: rgba(167,139,250,0.15); color: #a78bfa; }
    .badge-embedding { background: rgba(251,191,36,0.15);  color: #fbbf24; }
    .badge-thinking  { background: rgba(251,113,133,0.15); color: #fb7185; }
    .badge-tools     { background: rgba(148,163,184,0.15); color: #94a3b8; }

    /* Multiplier chips */
    .multiplier {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Monaco', 'Courier New', monospace;
        background: rgba(212,175,55,0.12);
        color: var(--gold);
        border: 1px solid rgba(212,175,55,0.25);
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
            font-size: 0.82rem;
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
                يوفر LLM Resayil وصولاً إلى 46 نموذجاً من النماذج اللغوية الكبيرة — 15 نموذجاً محلياً يعمل على بنيتنا التحتية الخاصة، و31 نموذجاً سحابياً عالي الأداء. اختر النموذج المناسب لحالة الاستخدام لديك بناءً على السرعة والدقة ومعامل الاستهلاك.
            @else
                LLM Resayil provides access to 46 large language models — 15 local models running on our own infrastructure and 31 high-performance cloud models. Choose the right model for your use case based on speed, accuracy, and credit multiplier.
            @endif
        </p>

        <!-- Section 1: Credit Multiplier System -->
        <section class="docs-section">
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

            <table class="docs-table">
                <thead>
                    <tr>
                        <th>@if(app()->getLocale() === 'ar') المعامل @else Multiplier @endif</th>
                        <th>@if(app()->getLocale() === 'ar') النوع @else Tier @endif</th>
                        <th>@if(app()->getLocale() === 'ar') الوصف @else Description @endif</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محلي — خفيف @else Local — Lightweight @endif</td>
                        <td>@if(app()->getLocale() === 'ar') نماذج صغيرة وسريعة جداً، مثالية للمهام البسيطة @else Small, ultra-fast models ideal for simple tasks @endif</td>
                    </tr>
                    <tr>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') سحابي — تضمين @else Cloud — Embedding @endif</td>
                        <td>@if(app()->getLocale() === 'ar') نماذج التضمين السحابية خفيفة الاستهلاك @else Lightweight cloud embedding models @endif</td>
                    </tr>
                    <tr>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محلي — متوسط @else Local — Standard @endif</td>
                        <td>@if(app()->getLocale() === 'ar') نماذج محلية متوسطة الحجم وعالية الكفاءة @else Mid-size local models with strong performance @endif</td>
                    </tr>
                    <tr>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') سحابي — متوسط @else Cloud — Mid @endif</td>
                        <td>@if(app()->getLocale() === 'ar') نماذج سحابية متوسطة الحجم متوازنة بين الأداء والتكلفة @else Balanced cloud models for quality and cost @endif</td>
                    </tr>
                    <tr>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') سحابي — كبير @else Cloud — Large @endif</td>
                        <td>@if(app()->getLocale() === 'ar') النماذج الأعلى أداءً والأكبر حجماً @else Highest-performance, largest-scale models @endif</td>
                    </tr>
                </tbody>
            </table>

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

        <!-- Section 2: Local Models -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') النماذج المحلية (15 نموذجاً) @else Local Models (15) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    تعمل هذه النماذج مباشرةً على خوادمنا المخصصة، مما يضمن زمن استجابة منخفضاً وخصوصية تامة للبيانات. المعامل الأساسي هو <strong>1 رصيد لكل رمز</strong> مع تعديلات بحسب حجم النموذج.
                @else
                    These models run directly on our dedicated hardware, ensuring low latency and full data privacy. The base rate is <strong>1 credit per token</strong> adjusted by the model multiplier.
                @endif
            </p>

            <table class="docs-table">
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
                    <tr>
                        <td><code>llama3.2:3b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>3B</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة، سرعة عالية @else General chat, fast @endif</td>
                    </tr>
                    <tr>
                        <td><code>smollm2:135m</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>135M</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') فائق السرعة، المهام الخفيفة @else Ultra-fast, lightweight tasks @endif</td>
                    </tr>
                    <tr>
                        <td><code>phi3.5-mini:3.8b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>3.8B</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') سريع وكفؤ، نافذة سياق 128K @else Fast, efficient, 128K context @endif</td>
                    </tr>
                    <tr>
                        <td><code>starcoder2:3b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>3B</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') توليد الكود، سريع @else Code generation, fast @endif</td>
                    </tr>
                    <tr>
                        <td><code>codellama:7b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>7B</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') برمجة، شرح الكود @else Coding, code explanation @endif</td>
                    </tr>
                    <tr>
                        <td><code>nomic-embed-text</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>110M</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين النصوص @else Text embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>all-minilm</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>23M</td>
                        <td><span class="multiplier">0.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين خفيف الوزن @else Lightweight embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen2.5-coder:14b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>14B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') توليد الكود المتقدم @else Advanced code generation @endif</td>
                    </tr>
                    <tr>
                        <td><code>mistral-small3.2:24b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>24B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة متوازنة، أداء عالٍ @else Balanced chat, high performance @endif</td>
                    </tr>
                    <tr>
                        <td><code>gemma2:9b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>9B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-coder:6.7b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>6.7B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') برمجة ومهام تقنية @else Coding and technical tasks @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3.1:8b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>8B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة متعددة الأغراض @else Versatile general chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>mistral-7b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>7B</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>bge-m3</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>580M</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين متعدد التمثيلات @else Multi-representation embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>snowflake-arctic-embed</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>137M</td>
                        <td><span class="multiplier">1.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين عالي الجودة @else High-quality embeddings @endif</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Section 3: Cloud Models -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') النماذج السحابية (31 نموذجاً) @else Cloud Models (31) @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    النماذج السحابية تُعالَج عبر خوادم GPU متخصصة بعيدة، مما يتيح الوصول إلى نماذج ضخمة بعشرات أو مئات المليارات من المعاملات. تبدأ معاملاتها من <strong>2.5×</strong> وتصل إلى <strong>3.5×</strong>.
                @else
                    Cloud models are processed via remote specialized GPU servers, enabling access to models with tens or hundreds of billions of parameters. Multipliers range from <strong>2.5×</strong> to <strong>3.5×</strong>.
                @endif
            </p>

            <h3>@if(app()->getLocale() === 'ar') نماذج المحادثة والتفكير @else Chat &amp; Thinking Models @endif</h3>
            <table class="docs-table">
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
                    <tr>
                        <td><code>gpt-oss:20b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>20B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General purpose @endif</td>
                    </tr>
                    <tr>
                        <td><code>mistral-large:24b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>24B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عالية الجودة @else High-quality chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>mistral-nemo:12b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>12B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة متوازنة @else Balanced chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen3-30b</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>30B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تفكير واستدلال @else Reasoning and thinking @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3.2:70b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>70B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عالية الأداء @else High-performance chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3.1:70b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>70B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة متقدمة @else Advanced general chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3.1:405b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>405B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') أكبر نماذج Llama @else Largest Llama model @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3-gradient:70b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>70B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') سياق ممتد (262K رمز) @else Extended context (262K tokens) @endif</td>
                    </tr>
                    <tr>
                        <td><code>gemma2:27b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>27B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>mixtral:8x7b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>8×7B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') بنية MoE متوازنة @else Balanced MoE architecture @endif</td>
                    </tr>
                    <tr>
                        <td><code>mixtral:8x22b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>8×22B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') MoE الأداء العالي @else High-performance MoE @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen2:72b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>72B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة متعددة اللغات @else Multilingual chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen2.5:32b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>32B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة عامة @else General chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>yi:34b</code></td>
                        <td><span class="badge badge-chat">chat</span></td>
                        <td>34B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة متعددة اللغات @else Multilingual chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen3.5:397b</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>397B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تفكير، الأكبر حجماً @else Thinking, largest model @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-v3.1:671b</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>671B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تفكير عميق واستدلال @else Deep thinking and reasoning @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-v3.2</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>671B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') أحدث إصدار DeepSeek @else Latest DeepSeek release @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-v2.5</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>236B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تفكير واستدلال MoE @else MoE thinking and reasoning @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-chat:671b</code></td>
                        <td><span class="badge badge-thinking">thinking</span></td>
                        <td>671B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') محادثة وتفكير متقدم @else Advanced chat and reasoning @endif</td>
                    </tr>
                </tbody>
            </table>

            <h3>@if(app()->getLocale() === 'ar') نماذج الرؤية @else Vision Models @endif</h3>
            <table class="docs-table">
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
                    <tr>
                        <td><code>glm-4.7-flash</code></td>
                        <td><span class="badge badge-vision">vision</span></td>
                        <td>12B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') رؤية متعددة الوسائط @else Multimodal vision @endif</td>
                    </tr>
                    <tr>
                        <td><code>llama3.2:11b</code></td>
                        <td><span class="badge badge-vision">vision</span></td>
                        <td>11B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') رؤية ومحادثة @else Vision and chat @endif</td>
                    </tr>
                    <tr>
                        <td><code>Qwen3-VL-32B</code></td>
                        <td><span class="badge badge-vision">vision</span></td>
                        <td>32B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') رؤية متقدمة @else Advanced vision-language @endif</td>
                    </tr>
                    <tr>
                        <td><code>qwen3-vl:235b-instruct</code></td>
                        <td><span class="badge badge-vision">vision</span></td>
                        <td>235B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') رؤية، النموذج الرائد @else Vision, flagship model @endif</td>
                    </tr>
                </tbody>
            </table>

            <h3>@if(app()->getLocale() === 'ar') نماذج البرمجة @else Code Models @endif</h3>
            <table class="docs-table">
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
                    <tr>
                        <td><code>codestral:22b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>22B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') توليد الكود @else Code generation @endif</td>
                    </tr>
                    <tr>
                        <td><code>deepseek-coder:33b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>33B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') برمجة متقدمة @else Advanced coding @endif</td>
                    </tr>
                    <tr>
                        <td><code>devstral-2:123b</code></td>
                        <td><span class="badge badge-code">code</span></td>
                        <td>123B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') برمجة، نموذج كبير @else Coding, large model @endif</td>
                    </tr>
                </tbody>
            </table>

            <h3>@if(app()->getLocale() === 'ar') نماذج الأدوات والتضمين السحابي @else Tools &amp; Cloud Embedding Models @endif</h3>
            <table class="docs-table">
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
                    <tr>
                        <td><code>firefunction-v2:18b</code></td>
                        <td><span class="badge badge-tools">tools</span></td>
                        <td>18B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') استدعاء الدوال @else Function calling @endif</td>
                    </tr>
                    <tr>
                        <td><code>command-r:35b</code></td>
                        <td><span class="badge badge-tools">tools</span></td>
                        <td>35B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') RAG والأدوات @else RAG and tool use @endif</td>
                    </tr>
                    <tr>
                        <td><code>command-r-plus:104b</code></td>
                        <td><span class="badge badge-tools">tools</span></td>
                        <td>104B</td>
                        <td><span class="multiplier">3.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') RAG المتقدم @else Advanced RAG @endif</td>
                    </tr>
                    <tr>
                        <td><code>nomic-embed:27m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>27M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين النصوص @else Text embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>gte-qwen:7m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>7M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين سريع @else Fast embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>nvidia-embed:1b</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>1B</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين NeMo @else NeMo embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>all-minilm-l6:22m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>22M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين خفيف @else Lightweight embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>gte-base:110m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>110M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين قياسي @else Standard embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>bge-small:8m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>8M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين صغير @else Small embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>minilm-l12:39m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>39M</td>
                        <td><span class="multiplier">1.0×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين MiniLM @else MiniLM embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>bge-large:335m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>335M</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين عالي الجودة @else High-quality embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>e5-mistral:7b</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>7B</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين متقدم @else Advanced embeddings @endif</td>
                    </tr>
                    <tr>
                        <td><code>snowflake-arctic-embed-l:335m</code></td>
                        <td><span class="badge badge-embedding">embedding</span></td>
                        <td>335M</td>
                        <td><span class="multiplier">2.5×</span></td>
                        <td>@if(app()->getLocale() === 'ar') تضمين Snowflake الكبير @else Snowflake large embeddings @endif</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Section 4: API Endpoint -->
        <section class="docs-section">
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
                <div class="docs-code-label">Endpoint</div>
                <code>GET https://llm.resayil.io/v1/models</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Request</div>
                <code>curl https://llm.resayil.io/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Response</div>
                <code>{
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
}</code>
            </div>

            <div class="docs-info-box">
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
        <section class="docs-section">
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
                <div class="docs-code-label">Endpoint</div>
                <code>POST https://llm.resayil.io/api/v1/chat/completions</code>
            </div>

            <div class="docs-code-block">
                <div class="docs-code-label">Chat Completion Request</div>
                <code>{
  "model": "mistral-small3.2:24b",
  "messages": [
    {"role": "system", "content": "You are a helpful assistant."},
    {"role": "user", "content": "Explain quantum computing in simple terms."}
  ],
  "temperature": 0.7,
  "top_p": 0.9,
  "max_tokens": 500,
  "stream": false
}</code>
            </div>

            <p>
                @if(app()->getLocale() === 'ar')
                    كل استجابة تتضمن حقل <code>usage</code> يوضح عدد الرموز المستهلكة بدقة:
                @else
                    Every response includes a <code>usage</code> field showing exact token consumption:
                @endif
            </p>

            <div class="docs-code-block">
                <div class="docs-code-label">Usage in Response</div>
                <code>"usage": {
  "prompt_tokens": 15,
  "completion_tokens": 142,
  "total_tokens": 157
}</code>
            </div>
        </section>

        <!-- Section 6: Model Availability -->
        <section class="docs-section">
            <h2>
                @if(app()->getLocale() === 'ar') توفر النماذج وحالتها @else Model Availability &amp; Status @endif
            </h2>

            <h3>@if(app()->getLocale() === 'ar') الوصول الكامل لجميع المستويات @else Full Access Across All Tiers @endif</h3>
            <p>
                @if(app()->getLocale() === 'ar')
                    جميع المستويات يمكنها الوصول إلى جميع النماذج الـ 46 فوراً دون أي قيود. الفارق الوحيد هو رصيدك المتاح.
                @else
                    All subscription tiers have immediate access to all 46 models with no restrictions. The only differentiator is your available credit balance.
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

@endsection
