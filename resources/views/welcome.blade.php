<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() === 'ar' ? 'LLM Resayil — منصة الذكاء الاصطناعي' : 'LLM Resayil — AI Models API Platform' }}</title>
    <meta name="description" content="{{ app()->getLocale() === 'ar' ? 'منصة ذكاء اصطناعي قوية. 46+ نموذج، متوافق مع OpenAI، ادفع فقط مقابل ما تستخدمه. 1,000 رصيد مجاني بدون بطاقة ائتمان.' : 'Powerful AI models API platform. 46+ models, OpenAI-compatible, pay only for what you use. 1,000 free credits — no credit card required.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- SoftwareApplication Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "LLM Resayil",
        "description": "OpenAI-compatible AI models API platform with 46+ models. Pay per token, no subscriptions.",
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Web",
        "url": "https://llm.resayil.io",
        "offers": [{ "@type": "Offer", "priceCurrency": "KWD", "price": "0", "name": "Free Tier" }],
        "aggregateRating": { "@type": "AggregateRating", "ratingValue": "4.8", "ratingCount": "250", "bestRating": "5", "worstRating": "1" }
    }
    </script>

    @if(app()->isProduction())
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M0T3YYQP7X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-M0T3YYQP7X');
    </script>
    @endif

    <!-- Hreflang Tags for Multilingual SEO -->
    <x-hreflang :currentPath="''" :isXDefault="true" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:            #0f1115;
            --bg-secondary:  #0a0d14;
            --bg-card:       #13161d;
            --slate:         #1a1d24;
            --gold:          #d4af37;
            --gold-light:    #eac558;
            --gold-muted:    #8a702a;
            --gold-dim:      rgba(212,175,55,0.10);
            --gold-border:   rgba(212,175,55,0.30);
            --border:        #1e2230;
            --text:          #e0e5ec;
            --text-secondary:#a0a8b5;
            --text-muted:    #6b7280;
            --shadow-gold:   0 0 60px rgba(212,175,55,0.12), 0 4px 32px rgba(0,0,0,0.5);
            --shadow-card:   0 4px 32px rgba(0,0,0,0.35);
            --r-sm: 8px; --r-md: 12px; --r-lg: 20px; --r-xl: 28px;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.65;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        [lang="ar"] body, [dir="rtl"] body { font-family: 'Tajawal', 'Inter', sans-serif; }
        a { color: inherit; text-decoration: none; }
        button { font-family: inherit; cursor: pointer; border: none; background: none; }
        ul { list-style: none; }

        /* Grid overlay */
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
            background-size: 72px 72px;
        }

        /* Layout */
        .container    { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .container-sm { max-width: 820px;  margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .text-center { text-align: center; }

        /* Gradient text */
        .text-grad {
            background: linear-gradient(135deg, var(--gold) 0%, #fff 50%, var(--gold-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .text-grad-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.8rem 1.75rem; border-radius: var(--r-md);
            font-weight: 700; font-size: 0.95rem;
            transition: all 0.2s ease; cursor: pointer; white-space: nowrap;
            position: relative; overflow: hidden;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0f1115;
            box-shadow: 0 0 28px rgba(212,175,55,0.28), 0 4px 16px rgba(0,0,0,0.35);
        }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 0 44px rgba(212,175,55,0.45), 0 8px 28px rgba(0,0,0,0.35); color: #0f1115; }
        .btn-gold::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.15) 100%); pointer-events: none; }
        .btn-lg  { padding: 1.1rem 2.5rem; font-size: 1.05rem; border-radius: var(--r-lg); }
        .btn-ghost {
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { border-color: var(--gold-border); color: var(--gold); background: var(--gold-dim); }
        .btn-outline-gold {
            background: transparent; color: var(--gold);
            border: 1px solid var(--gold-border);
        }
        .btn-outline-gold:hover { background: var(--gold-dim); color: var(--gold); }

        /* Card */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
        }
        .card:hover {
            border-color: var(--gold-border);
            box-shadow: 0 0 28px rgba(212,175,55,0.08), 0 12px 40px rgba(0,0,0,0.3);
            transform: translateY(-3px);
        }
        .card-glow {
            border-color: var(--gold-border);
            box-shadow: var(--shadow-gold);
        }

        /* ──────── NAVBAR ──────── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1.1rem 0;
            transition: all 0.3s;
        }
        .navbar.scrolled {
            background: rgba(10,13,20,0.94);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0.65rem 0;
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; }
        .logo {
            display: flex; align-items: center; gap: 0.65rem;
            font-weight: 800; font-size: 1.2rem; letter-spacing: -0.02em;
        }
        .logo-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--gold); flex-shrink: 0;
            box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 28px rgba(212,175,55,0.4);
            animation: dot-pulse 2.8s ease-in-out infinite;
        }
        @keyframes dot-pulse {
            0%, 100% { box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 28px rgba(212,175,55,0.4); }
            50% { box-shadow: 0 0 22px rgba(212,175,55,1), 0 0 44px rgba(212,175,55,0.6); }
        }
        .nav-links { display: flex; align-items: center; gap: 0.2rem; }
        .nav-links a {
            color: var(--text-secondary); font-size: 0.88rem; font-weight: 500;
            padding: 0.5rem 0.9rem; border-radius: var(--r-sm);
            transition: color 0.2s, background 0.2s;
        }
        .nav-links a:hover { color: var(--text); background: rgba(255,255,255,0.04); }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .lang-switch {
            display: flex; align-items: center;
            border: 1px solid var(--border); border-radius: 8px; overflow: hidden;
        }
        .lang-switch a {
            font-size: 0.78rem; font-weight: 700; padding: 0.4rem 0.75rem;
            transition: all 0.2s; letter-spacing: 0.04em; text-transform: uppercase;
        }
        .lang-switch a.active { color: var(--gold); background: rgba(212,175,55,0.12); }
        .lang-switch a:not(.active) { color: var(--text-muted); }
        .lang-switch a:not(.active):hover { color: var(--text-secondary); background: rgba(255,255,255,0.04); }
        .lang-switch .sep { color: var(--border); font-size: 1rem; line-height: 1; }
        .nav-toggle {
            display: none; flex-direction: column; gap: 5px; cursor: pointer;
            padding: 0.5rem; min-width: 44px; min-height: 44px;
            align-items: center; justify-content: center;
            border-radius: 8px; transition: background 0.2s;
        }
        .nav-toggle:hover { background: rgba(255,255,255,0.05); }
        .nav-toggle span {
            display: block; width: 22px; height: 2px;
            background: var(--text-secondary); border-radius: 2px;
            transition: transform 0.3s, opacity 0.3s; transform-origin: center;
        }
        .nav-toggle.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .nav-toggle.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .nav-toggle.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
        .mobile-nav {
            display: none; position: fixed; inset: 0; z-index: 1001;
            background: rgba(10,13,20,0.97); backdrop-filter: blur(20px);
            flex-direction: column; align-items: center; justify-content: center; gap: 2rem;
        }
        .mobile-nav.open { display: flex; }
        .mobile-nav a { font-size: 1.5rem; font-weight: 700; color: var(--text-secondary); transition: color 0.2s; }
        .mobile-nav a:hover { color: var(--gold); }
        .mnav-close {
            position: absolute; top: 1.25rem; right: 1.25rem; color: var(--text);
            min-width: 44px; min-height: 44px; display: flex; align-items: center; justify-content: center;
            border-radius: 8px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            cursor: pointer; transition: background 0.2s;
        }
        .mnav-close:hover { background: rgba(255,255,255,0.12); }
        .mnav-close svg { width: 22px; height: 22px; }
        .mobile-lang {
            display: flex; align-items: center;
            border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin-top: 0.5rem;
        }
        .mobile-lang a {
            font-size: 0.9rem; font-weight: 700; color: var(--text-muted);
            padding: 0.6rem 1.5rem; min-height: 44px; display: flex; align-items: center;
            transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.04em;
        }
        .mobile-lang a.active { color: var(--gold); background: rgba(212,175,55,0.12); }
        .mobile-lang .sep { color: var(--border); font-size: 1.1rem; }

        /* ──────── SECTIONS ──────── */
        section { position: relative; }
        .sp  { padding: 7rem 0; }
        .sps { padding: 5rem 0; }
        .slabel {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--gold); margin-bottom: 1rem;
        }
        .slabel::before { content: ''; display: block; width: 18px; height: 2px; background: var(--gold); border-radius: 2px; }
        .sh2 {
            font-size: clamp(1.9rem, 4vw, 3rem); font-weight: 800;
            line-height: 1.15; letter-spacing: -0.025em; margin-bottom: 1rem;
        }
        .ssub { font-size: 1.05rem; color: var(--text-secondary); max-width: 560px; line-height: 1.7; }
        .sheader { margin-bottom: 4rem; }

        /* ──────── HERO ──────── */
        .hero {
            min-height: 100vh; display: flex; align-items: center;
            padding: 9rem 0 6rem; position: relative; overflow: hidden;
        }
        .orb { position: absolute; border-radius: 50%; filter: blur(90px); pointer-events: none; animation: orb-drift 10s ease-in-out infinite; }
        .orb-1 { width: 600px; height: 600px; background: radial-gradient(circle, rgba(212,175,55,0.14) 0%, transparent 70%); top: -120px; right: -120px; }
        .orb-2 { width: 450px; height: 450px; background: radial-gradient(circle, rgba(100,60,200,0.16) 0%, transparent 70%); bottom: -100px; left: -80px; animation-delay: -4s; }
        .orb-3 { width: 320px; height: 320px; background: radial-gradient(circle, rgba(212,175,55,0.08) 0%, transparent 70%); top: 45%; left: 28%; animation-delay: -7s; }
        @keyframes orb-drift { 0%,100% { transform: translate(0,0) scale(1); } 33% { transform: translate(24px,-32px) scale(1.06); } 66% { transform: translate(-18px,22px) scale(0.94); } }

        .hero-content { position: relative; z-index: 2; text-align: center; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.22);
            border-radius: 100px; padding: 0.5rem 1.25rem;
            font-size: 0.88rem; font-weight: 600; color: var(--gold);
            margin-bottom: 2.25rem;
            animation: eyebrow-glow 3.5s ease-in-out infinite;
        }
        .hero-eyebrow svg { width: 16px; height: 16px; flex-shrink: 0; }
        @keyframes eyebrow-glow { 0%,100% { box-shadow: none; } 50% { box-shadow: 0 0 22px rgba(212,175,55,0.2); } }

        .hero-h1 {
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            font-weight: 900; line-height: 1.06; letter-spacing: -0.03em;
            margin-bottom: 1.75rem;
        }
        .hero-sub {
            font-size: clamp(1.05rem, 2.5vw, 1.25rem);
            color: var(--text-secondary); max-width: 580px;
            margin: 0 auto 2.75rem; line-height: 1.72;
        }
        .hero-cta { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 3.5rem; }

        /* Trust badges */
        .trust-badges {
            display: flex; align-items: center; justify-content: center;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 3rem;
        }
        .trust-badge {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text-muted);
        }
        .trust-badge svg { width: 14px; height: 14px; color: var(--gold); flex-shrink: 0; }
        .trust-sep { width: 1px; height: 16px; background: var(--border); }

        /* Floating stats pill */
        .stats-pill {
            display: inline-flex; align-items: center; gap: 1.25rem;
            background: rgba(19,22,29,0.88); backdrop-filter: blur(16px);
            border: 1px solid var(--border); border-radius: 100px;
            padding: 0.75rem 2rem;
            animation: pill-float 4.5s ease-in-out infinite;
        }
        @keyframes pill-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .stat-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; font-weight: 500; color: var(--text-secondary); }
        .stat-dot { width: 6px; height: 6px; border-radius: 50%; animation: dot-blink 2.2s ease-in-out infinite; }
        .stat-dot.green  { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.7); }
        .stat-dot.gold   { background: var(--gold); box-shadow: 0 0 8px rgba(212,175,55,0.7); }
        .stat-dot.blue   { background: #38bdf8; box-shadow: 0 0 8px rgba(56,189,248,0.7); }
        @keyframes dot-blink { 0%,100% { opacity:1; } 50% { opacity:0.35; } }
        .stat-sep { width: 1px; height: 14px; background: var(--border); }

        /* ──────── CODE DEMO BLOCK ──────── */
        .code-demo-wrap {
            max-width: 760px; margin: 0 auto;
            background: #0a0c12;
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(212,175,55,0.06), 0 24px 80px rgba(0,0,0,0.6);
            position: relative; z-index: 2;
        }
        .code-topbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.85rem 1.25rem;
            background: rgba(255,255,255,0.025);
            border-bottom: 1px solid var(--border);
        }
        .code-dots { display: flex; gap: 0.45rem; }
        .code-dot { width: 12px; height: 12px; border-radius: 50%; }
        .code-dot.red    { background: #ff5f57; }
        .code-dot.yellow { background: #febc2e; }
        .code-dot.green  { background: #28c840; }
        .code-filename { font-size: 0.78rem; color: var(--text-muted); font-family: 'Courier New', monospace; }
        .code-live-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25);
            border-radius: 100px; padding: 0.2rem 0.65rem;
            font-size: 0.7rem; font-weight: 700; color: #22c55e; text-transform: uppercase; letter-spacing: 0.06em;
        }
        .code-live-badge::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background: #22c55e; animation: dot-blink 1.5s ease-in-out infinite; }
        .code-body {
            padding: 1.5rem 1.75rem;
            font-family: 'Courier New', monospace;
            font-size: 0.83rem; line-height: 1.85;
            overflow-x: auto;
        }
        .code-body .c  { color: var(--text-muted); }
        .code-body .k  { color: #c792ea; }
        .code-body .kw { color: #7dd3fc; }
        .code-body .s  { color: #a5f3a0; }
        .code-body .n  { color: #e0e5ec; }
        .code-body .g  { color: var(--gold); }
        .code-body .num{ color: #fb923c; }

        /* ──────── BENTO GRID ──────── */
        .bento {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
        .bc {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: 2rem;
            transition: all 0.3s;
            position: relative; overflow: hidden;
            cursor: default;
        }
        .bc::before {
            content: ''; position: absolute; inset: 0;
            border-radius: var(--r-lg);
            background: linear-gradient(135deg, var(--gold-dim) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.3s;
        }
        .bc:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 32px rgba(212,175,55,0.10), 0 12px 40px rgba(0,0,0,0.3); }
        .bc:hover::before { opacity: 1; }
        .bc.w2 { grid-column: span 2; }
        .bi {
            width: 48px; height: 48px;
            background: var(--gold-dim); border: 1px solid rgba(212,175,55,0.18);
            border-radius: var(--r-sm);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem; transition: all 0.3s;
        }
        .bc:hover .bi { background: rgba(212,175,55,0.18); box-shadow: 0 0 18px rgba(212,175,55,0.22); }
        .bi svg { width: 22px; height: 22px; color: var(--gold); }
        .bc h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; position: relative; }
        .bc p  { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.65; position: relative; }

        /* ──────── HOW IT WORKS ──────── */
        .steps-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem; position: relative;
        }
        .steps-grid::before {
            content: ''; position: absolute;
            top: 31px; left: calc(16.66% + 1.25rem); right: calc(16.66% + 1.25rem);
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-border), transparent);
            pointer-events: none; z-index: 0;
        }
        .step-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--r-lg); padding: 2.25rem;
            text-align: center; position: relative; z-index: 1;
            transition: all 0.3s;
        }
        .step-card:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 28px rgba(212,175,55,0.08), 0 12px 40px rgba(0,0,0,0.3); }
        .step-num {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0f1115; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 1.25rem;
            margin: 0 auto 1.25rem;
            box-shadow: 0 0 24px rgba(212,175,55,0.35);
        }
        .step-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.6rem; }
        .step-card p  { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.65; }

        /* ──────── MODEL SHOWCASE ──────── */
        .model-scroll-wrap { overflow: hidden; position: relative; }
        .model-scroll-wrap::before,
        .model-scroll-wrap::after {
            content: ''; position: absolute; top: 0; bottom: 0; width: 80px; z-index: 2; pointer-events: none;
        }
        .model-scroll-wrap::before { left: 0; background: linear-gradient(90deg, var(--bg) 0%, transparent 100%); }
        .model-scroll-wrap::after  { right: 0; background: linear-gradient(270deg, var(--bg) 0%, transparent 100%); }
        .model-scroll-track {
            display: flex; gap: 0.75rem;
            animation: scroll-left 40s linear infinite;
            width: max-content;
        }
        @keyframes scroll-left { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .model-pill {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 100px; padding: 0.5rem 1.1rem;
            font-size: 0.8rem; font-weight: 600; color: var(--text-secondary);
            white-space: nowrap; flex-shrink: 0;
            transition: border-color 0.2s, color 0.2s;
        }
        .model-pill:hover { border-color: var(--gold-border); color: var(--gold); }
        .model-pill-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); opacity: 0.6; }

        /* ──────── PRICING TEASER ──────── */
        .p-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .pc {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--r-lg); padding: 2.25rem;
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
            transition: all 0.3s;
        }
        .pc:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 32px rgba(212,175,55,0.10), 0 12px 40px rgba(0,0,0,0.3); }
        .pc.feat {
            border-color: var(--gold-border);
            background: linear-gradient(160deg, var(--bg-card), rgba(212,175,55,0.04));
            box-shadow: 0 0 44px rgba(212,175,55,0.12);
        }
        .pc.feat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .pbadge {
            display: inline-block; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: #0f1115; background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 100px; padding: 0.28rem 0.8rem;
            margin-bottom: 1rem; align-self: flex-start;
        }
        .pname  { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; }
        .pcredits {
            font-size: 2.6rem; font-weight: 900; line-height: 1;
            margin: 0.75rem 0 0.2rem; color: var(--gold);
        }
        .pcredits small { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
        .pprice { font-size: 1.3rem; font-weight: 700; color: var(--text); margin-bottom: 0.3rem; }
        .pper   { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.75rem; }
        .pfeat  { margin-bottom: 1.75rem; flex: 1; }
        .pfeat li {
            display: flex; align-items: flex-start; gap: 0.6rem;
            font-size: 0.88rem; color: var(--text-secondary); padding: 0.35rem 0;
        }
        .pfeat li svg { width: 16px; height: 16px; color: var(--gold); flex-shrink: 0; margin-top: 1px; }

        /* ──────── CTA SECTION ──────── */
        .cta-band {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border-radius: var(--r-xl);
            padding: 4rem 2.5rem;
            text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-band::before {
            content: ''; position: absolute; inset: 0; border-radius: var(--r-xl);
            background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.10) 100%);
            pointer-events: none;
        }
        .cta-band h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; color: #0f1115; margin-bottom: 1rem; letter-spacing: -0.02em; line-height: 1.15; position: relative; }
        .cta-band p  { font-size: 1.05rem; color: rgba(15,17,21,0.72); margin-bottom: 2.25rem; line-height: 1.7; position: relative; }
        .cta-band-btns { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; }
        .btn-dark {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #0f1115; color: var(--gold);
            padding: 0.9rem 2rem; border-radius: var(--r-md);
            font-weight: 700; font-size: 0.95rem;
            transition: all 0.2s; white-space: nowrap;
        }
        .btn-dark:hover { background: #13161d; color: var(--gold); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
        .btn-dark-outline {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: transparent; color: #0f1115;
            border: 2px solid rgba(15,17,21,0.35);
            padding: 0.9rem 2rem; border-radius: var(--r-md);
            font-weight: 700; font-size: 0.95rem;
            transition: all 0.2s; white-space: nowrap;
        }
        .btn-dark-outline:hover { background: rgba(15,17,21,0.10); border-color: rgba(15,17,21,0.6); color: #0f1115; }

        /* ──────── FOOTER ──────── */
        footer { border-top: 1px solid var(--border); padding: 3.5rem 0; }
        .foot-in { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
        .foot-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .foot-links a { font-size: 0.84rem; color: var(--text-muted); transition: color 0.2s; }
        .foot-links a:hover { color: var(--text-secondary); }
        .foot-copy { font-size: 0.8rem; color: var(--text-muted); }

        /* ──────── ANIMATIONS ──────── */
        .fu { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .fu.on { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: 0.10s; }
        .d2 { transition-delay: 0.20s; }
        .d3 { transition-delay: 0.30s; }

        @media (prefers-reduced-motion: reduce) {
            .fu { opacity: 1; transform: none; transition: none; }
            .orb, .stats-pill, .logo-dot, .model-scroll-track { animation: none; }
        }

        /* ──────── RESPONSIVE ──────── */
        @media (max-width: 1024px) {
            .bento { grid-template-columns: repeat(2, 1fr); }
            .bc.w2 { grid-column: span 1; }
            .p-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .nav-links, .nav-actions .btn-ghost, .nav-actions .btn-outline-gold { display: none; }
            .nav-toggle { display: flex; }
            .bento, .steps-grid { grid-template-columns: 1fr; }
            .steps-grid::before { display: none; }
            .p-grid { grid-template-columns: 1fr; max-width: 380px; margin-left: auto; margin-right: auto; }
            .hero { padding: 7.5rem 0 4.5rem; }
            .sp { padding: 5rem 0; }
            .stats-pill { flex-direction: column; gap: 0.75rem; border-radius: var(--r-md); padding: 1rem 1.5rem; }
            .stat-sep { width: 100%; height: 1px; }
            .foot-in { flex-direction: column; text-align: center; }
            .foot-links { justify-content: center; }
            .cta-band { padding: 3rem 1.5rem; }
        }
        @media (max-width: 480px) {
            .hero-h1 { font-size: 2.4rem; }
            .hero-cta { flex-direction: column; align-items: stretch; }
            .hero-cta .btn { justify-content: center; }
            .trust-badges { gap: 0.75rem; }
            .trust-sep { display: none; }
        }
    </style>
</head>
<body>

<!-- Mobile Nav -->
<nav class="mobile-nav" id="mobile-nav" role="dialog" aria-label="{{ app()->getLocale() === 'ar' ? 'قائمة التنقل' : 'Navigation menu' }}" aria-modal="true">
    <button class="mnav-close" id="mnav-close" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close menu' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <a href="#features"   onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
    <a href="#how-it-works" onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'How It Works' }}</a>
    <a href="#pricing"    onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
    <a href="/docs"       onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'التوثيق' : 'Docs' }}</a>
    <a href="/login"      onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}</a>
    <a href="/register"   onclick="closeMnav()" style="color:var(--gold);">{{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start Free' }}</a>
    <div class="mobile-lang" role="group" aria-label="{{ app()->getLocale() === 'ar' ? 'اللغة' : 'Language' }}">
        <a href="/locale/en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}" aria-label="English">EN</a>
        <span class="sep" aria-hidden="true">|</span>
        <a href="/locale/ar" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}" aria-label="العربية">AR</a>
    </div>
</nav>

<!-- Navbar -->
<header class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="logo" aria-label="LLM Resayil home">
                <div class="logo-dot" aria-hidden="true"></div>
                LLM Resayil
            </a>
            <nav class="nav-links" aria-label="{{ app()->getLocale() === 'ar' ? 'التنقل الرئيسي' : 'Main navigation' }}">
                <a href="#features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
                <a href="#how-it-works">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'How It Works' }}</a>
                <a href="#pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
                <a href="/docs">{{ app()->getLocale() === 'ar' ? 'التوثيق' : 'Docs' }}</a>
            </nav>
            <div class="nav-actions">
                <div class="lang-switch" role="group" aria-label="{{ app()->getLocale() === 'ar' ? 'اللغة' : 'Language' }}">
                    <a href="/locale/en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}" aria-label="English">EN</a>
                    <span class="sep" aria-hidden="true">|</span>
                    <a href="/locale/ar" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}" aria-label="العربية">AR</a>
                </div>
                <a href="/login" class="btn btn-ghost" style="padding:.6rem 1.1rem;font-size:.86rem;">{{ app()->getLocale() === 'ar' ? 'دخول' : 'Sign In' }}</a>
                <a href="/register" class="btn btn-gold" style="padding:.6rem 1.25rem;font-size:.86rem;">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start Free' }}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <button class="nav-toggle" id="nav-toggle" aria-label="{{ app()->getLocale() === 'ar' ? 'فتح القائمة' : 'Open menu' }}" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<main>

<!-- ═══════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════ -->
<section class="hero" aria-labelledby="hero-h1">
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>
    <div class="container">
        <div class="hero-content">

            <div class="hero-eyebrow" role="note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                @if(app()->getLocale() === 'ar')
                    1,000 رصيد مجاني &mdash; بدون بطاقة ائتمان
                @else
                    1,000 Free Credits &mdash; No Credit Card Needed
                @endif
            </div>

            <h1 class="hero-h1" id="hero-h1">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        الوصول إلى<br><span class="text-grad">46+ نموذج ذكاء اصطناعي</span><br>بسعر واحد
                    </span>
                @else
                    Access<br><span class="text-grad">46+ AI Models</span><br>Through One API
                @endif
            </h1>

            <p class="hero-sub">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        منصة API متوافقة مع OpenAI. ادفع فقط مقابل ما تستخدمه — لا اشتراكات، لا رسوم مخفية. الرصيد لا تنتهي صلاحيته أبداً.
                    </span>
                @else
                    OpenAI-compatible API platform. Pay only for what you use — no subscriptions, no hidden fees. Credits never expire.
                @endif
            </p>

            <div class="hero-cta">
                <a href="/register" class="btn btn-gold btn-lg">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Get Started Free' }}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a href="/docs" class="btn btn-outline-gold btn-lg">
                    {{ app()->getLocale() === 'ar' ? 'عرض التوثيق' : 'View Docs' }}
                </a>
            </div>

            <!-- Trust badges -->
            <div class="trust-badges">
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    @if(app()->getLocale() === 'ar')
                        <span style="font-family:'Tajawal',sans-serif;" dir="rtl">46+ نموذج ذكاء اصطناعي</span>
                    @else
                        46+ AI Models
                    @endif
                </div>
                <div class="trust-sep" aria-hidden="true"></div>
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    @if(app()->getLocale() === 'ar')
                        <span style="font-family:'Tajawal',sans-serif;" dir="rtl">متوافق مع OpenAI</span>
                    @else
                        OpenAI-Compatible
                    @endif
                </div>
                <div class="trust-sep" aria-hidden="true"></div>
                <div class="trust-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    @if(app()->getLocale() === 'ar')
                        <span style="font-family:'Tajawal',sans-serif;" dir="rtl">ادفع مقابل ما تستخدمه</span>
                    @else
                        Pay Per Use
                    @endif
                </div>
            </div>

            <!-- Live stats pill -->
            <div class="stats-pill" role="status" aria-label="{{ app()->getLocale() === 'ar' ? 'إحصائيات المنصة' : 'Platform statistics' }}">
                <div class="stat-item"><div class="stat-dot green" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '1,200+ مستخدم متصل' : '1,200+ users online' }}</div>
                <div class="stat-sep" aria-hidden="true"></div>
                <div class="stat-item"><div class="stat-dot gold" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '46+ نموذج' : '46+ models' }}</div>
                <div class="stat-sep" aria-hidden="true"></div>
                <div class="stat-item"><div class="stat-dot blue" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '99.9% وقت التشغيل' : '99.9% uptime' }}</div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     CODE DEMO
════════════════════════════════════════════════════════ -->
<section class="sps" aria-labelledby="code-h2">
    <div class="container">
        <div class="sheader text-center fu">
            <div class="slabel">{{ app()->getLocale() === 'ar' ? 'تكامل فوري' : 'Instant Integration' }}</div>
            <h2 class="sh2" id="code-h2">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">تبديل مزود واحد. <span class="text-grad-gold">صفر تعديل</span> على الكود.</span>
                @else
                    Change one line. <span class="text-grad-gold">Zero rewrites.</span>
                @endif
            </h2>
            <p class="ssub fu d1" style="margin:0 auto;">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">يتبع الـ API تنسيق OpenAI بالكامل. فقط غيّر الـ base URL ومفتاح API وانتهيت.</span>
                @else
                    The API follows the OpenAI format exactly. Just change the base URL and API key — done.
                @endif
            </p>
        </div>
        <div class="fu d2">
            <div class="code-demo-wrap">
                <div class="code-topbar">
                    <div class="code-dots" aria-hidden="true">
                        <div class="code-dot red"></div>
                        <div class="code-dot yellow"></div>
                        <div class="code-dot green"></div>
                    </div>
                    <span class="code-filename">api_demo.py</span>
                    <div class="code-live-badge">{{ app()->getLocale() === 'ar' ? 'مباشر' : 'Live' }}</div>
                </div>
                <div class="code-body" role="region" aria-label="{{ app()->getLocale() === 'ar' ? 'مثال كود' : 'Code example' }}">
<span class="c"># {{ app()->getLocale() === 'ar' ? 'استبدل مزودك الحالي ببضع أسطر فقط' : 'Drop-in replacement — works with your existing code' }}</span>
<span class="k">from</span> <span class="n">openai</span> <span class="k">import</span> <span class="n">OpenAI</span>

<span class="n">client</span> = <span class="n">OpenAI</span>(
    <span class="kw">base_url</span>=<span class="g">"https://llm.resayil.io/api/v1"</span>,
    <span class="kw">api_key</span>=<span class="s">"your-api-key"</span>,
)

<span class="n">response</span> = <span class="n">client</span>.<span class="n">chat</span>.<span class="n">completions</span>.<span class="n">create</span>(
    <span class="kw">model</span>=<span class="s">"llama3.1:70b"</span>,      <span class="c"># {{ app()->getLocale() === 'ar' ? 'اختر من 46+ نموذج' : 'choose from 46+ models' }}</span>
    <span class="kw">messages</span>=[{
        <span class="s">"role"</span>: <span class="s">"user"</span>,
        <span class="s">"content"</span>: <span class="s">"{{ app()->getLocale() === 'ar' ? 'مرحباً!' : 'Hello from LLM Resayil!' }}"</span>
    }],
    <span class="kw">stream</span>=<span class="num">True</span>       <span class="c"># {{ app()->getLocale() === 'ar' ? 'البث مدعوم' : 'streaming supported' }}</span>
)

<span class="c"># {{ app()->getLocale() === 'ar' ? 'رصيد واحد = رمز ناتج واحد. رموز الإدخال مجانية تماماً.' : '1 credit = 1 output token. Input tokens are always free.' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FEATURES BENTO GRID
════════════════════════════════════════════════════════ -->
<section class="sp" id="features" aria-labelledby="feat-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'ما يمكنك فعله' : 'Platform Capabilities' }}</div>
            <h2 class="sh2 fu d1" id="feat-h2">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">كل ما تحتاجه <span class="text-grad-gold">في منصة واحدة</span></span>
                @else
                    Everything you need <span class="text-grad-gold">in one platform</span>
                @endif
            </h2>
            <p class="ssub fu d2" style="margin:0 auto;">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">بنية تحتية مصممة للمطورين الذين يريدون نتائج فورية بدون تعقيد.</span>
                @else
                    Infrastructure designed for developers who want results without complexity.
                @endif
            </p>
        </div>

        <div class="bento">
            <!-- Wide card: 46+ models -->
            <div class="bc w2 fu" style="background: linear-gradient(140deg, var(--bg-card), rgba(212,175,55,0.04));">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                </div>
                <h3>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;"><span class="text-grad-gold">46+ نموذج ذكاء اصطناعي</span> في مكان واحد</span>
                    @else
                        <span class="text-grad-gold">46+ AI Models</span> — One Endpoint
                    @endif
                </h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">Llama، Qwen، Gemma، Mistral، DeepSeek، Command R والمزيد. نماذج للمحادثة، البرمجة، الاستدلال، واللغات المتعددة — كلها تحت مفتاح API واحد.</span>
                    @else
                        Llama, Qwen, Gemma, Mistral, DeepSeek, Command R and more. Models for chat, coding, reasoning, and multilingual tasks — all under a single API key.
                    @endif
                </p>
            </div>

            <!-- OpenAI compatible -->
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'متوافق مع OpenAI' : 'OpenAI-Compatible' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">نفس تنسيق الـ API، نفس الـ SDK، نفس البث. غيّر base URL ومفتاح API فقط.</span>
                    @else
                        Same API format, same SDK, same streaming. Change base URL and API key only — your existing code works immediately.
                    @endif
                </p>
            </div>

            <!-- Pay per use -->
            <div class="bc fu">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'ادفع مقابل ما تستخدمه' : 'Pay Per Use' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد واحد = رمز ناتج واحد. رموز الإدخال مجانية. الرصيد لا تنتهي صلاحيته أبداً.</span>
                    @else
                        1 credit = 1 output token. Input tokens are free. Credits never expire. No monthly minimums.
                    @endif
                </p>
            </div>

            <!-- API keys -->
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'مفاتيح API متعددة' : 'Multiple API Keys' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">أنشئ مفاتيح غير محدودة تشترك في نفس رصيد الاعتمادات. مثالية للفرق والمشاريع المتعددة.</span>
                    @else
                        Create unlimited API keys that share one credit balance. Perfect for teams, apps, and multiple projects.
                    @endif
                </p>
            </div>

            <!-- Usage dashboard -->
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'لوحة تحكم الاستخدام' : 'Usage Dashboard' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد فعلي، سجل الاستخدام، وتفاصيل تكلفة كل طلب في الوقت الفعلي.</span>
                    @else
                        Real-time balance, usage history, and per-request cost breakdown visible in your dashboard.
                    @endif
                </p>
            </div>

            <!-- Security -->
            <div class="bc fu">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'خاص وآمن' : 'Private &amp; Secure' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">محادثاتك وبياناتك تبقى آمنة. لا نتدرب على بياناتك.</span>
                    @else
                        Your conversations and data stay safe. We do not train on your data. Privacy by default.
                    @endif
                </p>
            </div>

            <!-- Wide card: Arabic/bilingual -->
            <div class="bc w2 fu d1">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'واجهة عربية وإنجليزية كاملة' : 'Full Arabic &amp; English Interface' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">لوحة التحكم، الفواتير، التوثيق — كل شيء متاح بالعربية مع دعم RTL كامل. مصممة للمطورين في الكويت والمنطقة.</span>
                    @else
                        Dashboard, billing, documentation — all available in Arabic with full RTL support. Designed for developers in Kuwait and the region.
                    @endif
                </p>
            </div>

            <!-- Streaming -->
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'البث المباشر' : 'Streaming Support' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">البث المباشر للردود مدعوم عبر SSE، تماماً كما في OpenAI. مثالي لتطبيقات الدردشة.</span>
                    @else
                        Server-sent events streaming for real-time responses, identical to OpenAI. Perfect for chat applications.
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════════ -->
<section class="sp" id="how-it-works" aria-labelledby="hiw-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'عملية بسيطة' : 'Simple Process' }}</div>
            <h2 class="sh2 fu d1" id="hiw-h2">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">تشغيل وتشغيل في <span class="text-grad-gold">أقل من 5 دقائق</span></span>
                @else
                    Up and running in <span class="text-grad-gold">under 5 minutes</span>
                @endif
            </h2>
            <p class="ssub fu d2" style="margin:0 auto;">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">لا إعداد معقد، لا بطاقة ائتمان. ثلاث خطوات وأنت تبني بالفعل.</span>
                @else
                    No complicated setup, no credit card. Three steps and you are already building.
                @endif
            </p>
        </div>
        <div class="steps-grid">
            <div class="step-card fu">
                <div class="step-num">1</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Create Account' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">سجّل ببريدك الإلكتروني. لا بطاقة ائتمان مطلوبة. تحصل فوراً على 1,000 رصيد مجاني.</span>
                    @else
                        Sign up with your email. No credit card required. Get 1,000 free credits instantly upon registration.
                    @endif
                </p>
            </div>
            <div class="step-card fu d1">
                <div class="step-num">2</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'أنشئ مفتاح API' : 'Create API Key' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">أنشئ مفتاح API من لوحة التحكم. أضف الـ base URL ومفتاح API إلى أي SDK متوافق مع OpenAI.</span>
                    @else
                        Generate an API key from your dashboard. Set the base URL and API key in any OpenAI-compatible SDK.
                    @endif
                </p>
            </div>
            <div class="step-card fu d2">
                <div class="step-num">3</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'ابدأ البناء' : 'Start Building' }}</h3>
                <p>
                    @if(app()->getLocale() === 'ar')
                        <span dir="rtl" style="font-family:'Tajawal',sans-serif;">اختر من 46+ نموذج واستدعِ الـ API. تُخصم الرصيد مقابل الرموز الناتجة فقط — الإدخال مجاني.</span>
                    @else
                        Choose from 46+ models and make your first API call. Credits deduct on output tokens only — input is always free.
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     MODEL SHOWCASE
════════════════════════════════════════════════════════ -->
<section class="sps" aria-label="{{ app()->getLocale() === 'ar' ? 'النماذج المتاحة' : 'Available models' }}">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'مكتبة النماذج' : 'Model Library' }}</div>
            <h2 class="sh2 fu d1">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">اختر النموذج المناسب <span class="text-grad-gold">لكل مهمة</span></span>
                @else
                    Pick the right model <span class="text-grad-gold">for every task</span>
                @endif
            </h2>
        </div>
    </div>
    <div class="model-scroll-wrap fu d2" aria-hidden="true">
        <div class="model-scroll-track">
            @php
            $models = [
                'Llama 3.1 8B','Llama 3.1 70B','Llama 3.3 70B',
                'Qwen 2.5 7B','Qwen 2.5 72B','Qwen 2.5 Coder',
                'DeepSeek R1','DeepSeek V3','DeepSeek Coder',
                'Gemma 2 9B','Gemma 2 27B','Gemma 3 12B',
                'Mistral 7B','Mistral Nemo','Mixtral 8x7B',
                'Command R','Aya Expanse','Phi-4',
                'Yi 34B','Falcon 40B','Nous Hermes',
                'CodeLlama','Qwen QwQ','Solar Pro',
            ];
            @endphp
            @foreach(array_merge($models, $models) as $model)
                <div class="model-pill"><div class="model-pill-dot" aria-hidden="true"></div>{{ $model }}</div>
            @endforeach
        </div>
    </div>
    <div class="container" style="margin-top:1.5rem; text-align:center;">
        <a href="/features" style="color:var(--gold);font-size:0.9rem;font-weight:600;">
            @if(app()->getLocale() === 'ar')
                <span dir="rtl" style="font-family:'Tajawal',sans-serif;">عرض جميع النماذج &larr;</span>
            @else
                Browse all 46+ models &rarr;
            @endif
        </a>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     PRICING TEASER
════════════════════════════════════════════════════════ -->
<section class="sp" id="pricing" aria-labelledby="price-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'باقات الرصيد' : 'Credit Packs' }}</div>
            <h2 class="sh2 fu d1" id="price-h2">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">أسعار بسيطة. <span class="text-grad-gold">بدون مفاجآت.</span></span>
                @else
                    Simple pricing. <span class="text-grad-gold">No surprises.</span>
                @endif
            </h2>
            <p class="ssub fu d2" style="margin:0 auto;">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">اشترِ رصيداً، استخدمه وقتما تريد. الرصيد لا تنتهي صلاحيته أبداً.</span>
                @else
                    Buy credits, use them whenever. No subscriptions, no seat fees. Credits never expire.
                @endif
            </p>
        </div>

        <div class="p-grid">
            <!-- Pack 1 -->
            <div class="pc fu">
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'الباقة الأساسية' : 'Starter Pack' }}</div>
                <div class="pcredits">5,000 <small>{{ app()->getLocale() === 'ar' ? 'رصيد' : 'credits' }}</small></div>
                <div class="pprice">2 {{ app()->getLocale() === 'ar' ? 'د.ك' : 'KWD' }}</div>
                <div class="pper">{{ app()->getLocale() === 'ar' ? '0.0004 د.ك / رصيد' : '0.0004 KWD per credit' }}</div>
                <ul class="pfeat">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? '5,000 رمز ناتج من API' : '5,000 API output tokens' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'جميع النماذج 46+ مشمولة' : 'All 46+ models included' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'الرصيد لا تنتهي صلاحيته' : 'Credits never expire' }}
                    </li>
                </ul>
                <a href="/register" class="btn btn-outline-gold" style="width:100%;justify-content:center;padding:.85rem;">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
                </a>
            </div>

            <!-- Pack 2: Featured -->
            <div class="pc feat fu d1">
                <div class="pbadge">{{ app()->getLocale() === 'ar' ? 'الأكثر شيوعاً' : 'Most Popular' }}</div>
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'الباقة المتوسطة' : 'Growth Pack' }}</div>
                <div class="pcredits">15,000 <small>{{ app()->getLocale() === 'ar' ? 'رصيد' : 'credits' }}</small></div>
                <div class="pprice">5 {{ app()->getLocale() === 'ar' ? 'د.ك' : 'KWD' }}</div>
                <div class="pper">{{ app()->getLocale() === 'ar' ? '0.00033 د.ك / رصيد — أرخص 17%' : '0.00033 KWD per credit — 17% cheaper' }}</div>
                <ul class="pfeat">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? '15,000 رمز ناتج من API' : '15,000 API output tokens' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'جميع النماذج 46+ مشمولة' : 'All 46+ models included' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'الرصيد لا تنتهي صلاحيته' : 'Credits never expire' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'أفضل قيمة للمال' : 'Best value per credit' }}
                    </li>
                </ul>
                <a href="/register" class="btn btn-gold" style="width:100%;justify-content:center;padding:.85rem;color:#0f1115;">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
                </a>
            </div>

            <!-- Pack 3 -->
            <div class="pc fu d2">
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'الباقة الكبيرة' : 'Pro Pack' }}</div>
                <div class="pcredits">50,000 <small>{{ app()->getLocale() === 'ar' ? 'رصيد' : 'credits' }}</small></div>
                <div class="pprice">15 {{ app()->getLocale() === 'ar' ? 'د.ك' : 'KWD' }}</div>
                <div class="pper">{{ app()->getLocale() === 'ar' ? '0.0003 د.ك / رصيد — أرخص 25%' : '0.0003 KWD per credit — 25% cheaper' }}</div>
                <ul class="pfeat">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? '50,000 رمز ناتج من API' : '50,000 API output tokens' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'جميع النماذج 46+ مشمولة' : 'All 46+ models included' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'الرصيد لا تنتهي صلاحيته' : 'Credits never expire' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'للفرق والمشاريع الكبيرة' : 'Ideal for teams and larger projects' }}
                    </li>
                </ul>
                <a href="/register" class="btn btn-outline-gold" style="width:100%;justify-content:center;padding:.85rem;">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
                </a>
            </div>
        </div>

        <div style="margin-top:1.5rem;text-align:center;">
            <a href="/pricing" style="color:var(--gold);font-size:0.9rem;font-weight:600;">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">عرض جميع الأسعار &larr;</span>
                @else
                    View full pricing details &rarr;
                @endif
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FINAL CTA BAND
════════════════════════════════════════════════════════ -->
<section class="sps" aria-labelledby="cta-h2">
    <div class="container">
        <div class="cta-band fu">
            <h2 id="cta-h2">
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">ابدأ تجربتك المجانية اليوم</span>
                @else
                    Start building with AI models today
                @endif
            </h2>
            <p>
                @if(app()->getLocale() === 'ar')
                    <span dir="rtl" style="font-family:'Tajawal',sans-serif;">سجّل مجاناً، احصل على 1,000 رصيد فوراً، وأجرِ أول استدعاء API في أقل من 5 دقائق. لا بطاقة ائتمان مطلوبة.</span>
                @else
                    Register free, get 1,000 credits instantly, and make your first API call in under 5 minutes. No credit card required.
                @endif
            </p>
            <div class="cta-band-btns">
                <a href="/register" class="btn-dark" style="cursor:pointer;">
                    {{ app()->getLocale() === 'ar' ? 'إنشاء حساب مجاني' : 'Create Free Account' }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a href="/docs" class="btn-dark-outline" style="cursor:pointer;">
                    {{ app()->getLocale() === 'ar' ? 'عرض التوثيق' : 'Read the Docs' }}
                </a>
            </div>
        </div>
    </div>
</section>

</main>

<!-- ═══════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════ -->
<footer role="contentinfo">
    <div class="container">
        <div class="foot-in">
            <a href="/" class="logo" aria-label="LLM Resayil home">
                <div class="logo-dot" aria-hidden="true"></div>
                LLM Resayil
            </a>
            <nav class="foot-links" aria-label="{{ app()->getLocale() === 'ar' ? 'روابط التذييل' : 'Footer navigation' }}">
                <a href="/features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
                <a href="/pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
                <a href="/docs">{{ app()->getLocale() === 'ar' ? 'التوثيق' : 'Docs' }}</a>
                <a href="/comparison">{{ app()->getLocale() === 'ar' ? 'قارن APIs' : 'Compare APIs' }}</a>
                <a href="/cost-calculator">{{ app()->getLocale() === 'ar' ? 'حاسبة التكاليف' : 'Cost Calculator' }}</a>
                <a href="/login">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}</a>
                <a href="/terms-of-service">{{ app()->getLocale() === 'ar' ? 'الشروط' : 'Terms' }}</a>
                <a href="/privacy-policy">{{ app()->getLocale() === 'ar' ? 'الخصوصية' : 'Privacy' }}</a>
            </nav>
            <p class="foot-copy">&copy; {{ date('Y') }} LLM Resayil. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</p>
        </div>
    </div>
</footer>

<script>
(function () {
    'use strict';

    /* Navbar scroll */
    var navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function () {
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });

    /* Mobile nav */
    var mobileNav = document.getElementById('mobile-nav');
    var navToggle = document.getElementById('nav-toggle');
    var mnavClose = document.getElementById('mnav-close');

    function openMnav() {
        mobileNav.classList.add('open');
        navToggle.classList.add('open');
        navToggle.setAttribute('aria-expanded', 'true');
        navToggle.setAttribute('aria-label', '{{ app()->getLocale() === "ar" ? "إغلاق القائمة" : "Close menu" }}');
        document.body.style.overflow = 'hidden';
    }
    function closeMnav() {
        mobileNav.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-label', '{{ app()->getLocale() === "ar" ? "فتح القائمة" : "Open menu" }}');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    window.closeMnav = closeMnav;

    navToggle.addEventListener('click', openMnav);
    mnavClose.addEventListener('click', closeMnav);
    mobileNav.addEventListener('click', function (e) { if (e.target === mobileNav) closeMnav(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMnav(); });

    /* Scroll-reveal animations */
    var fus = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('on'); io.unobserve(en.target); }
            });
        }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
        fus.forEach(function (el) { io.observe(el); });
    } else {
        fus.forEach(function (el) { el.classList.add('on'); });
    }

})();
</script>

</body>
</html>
