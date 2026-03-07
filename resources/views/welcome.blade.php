<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() === 'ar' ? 'LLM Resayil — منصة المساعد الذكي' : 'LLM Resayil — AI Assistant Platform' }}</title>
    <meta name="description" content="{{ app()->getLocale() === 'ar' ? 'منصة ذكاء اصطناعي قوية. اكتب أسرع، أجب على أي سؤال، واحصل على النتائج فوراً. 1,000 رصيد مجاني — بدون بطاقة ائتمان.' : 'Powerful AI assistant platform. Write faster, answer anything, and get results instantly. 1,000 free credits — no credit card required.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- SoftwareApplication Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "LLM Resayil",
        "description": "Powerful AI assistant platform with 50+ models. Write faster, answer anything, and get results instantly.",
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Web",
        "url": "https://llm.resayil.io",
        "image": "https://llm.resayil.io/og-image.png",
        "offers": [
            {
                "@type": "Offer",
                "priceCurrency": "KWD",
                "price": "0",
                "name": "Free Trial",
                "description": "Start free with 1,000 credits. No credit card required. Valid for 7 days."
            },
            {
                "@type": "Offer",
                "priceCurrency": "KWD",
                "price": "15",
                "pricingModel": "Subscription",
                "name": "Starter Plan",
                "description": "1,000 credits per month. 10 requests per minute."
            },
            {
                "@type": "Offer",
                "priceCurrency": "KWD",
                "price": "25",
                "pricingModel": "Subscription",
                "name": "Basic Plan",
                "description": "3,000 credits per month. 30 requests per minute. All model sizes."
            },
            {
                "@type": "Offer",
                "priceCurrency": "KWD",
                "price": "45",
                "pricingModel": "Subscription",
                "name": "Pro Plan",
                "description": "10,000 credits per month. 60 requests per minute. Priority cloud failover."
            }
        ],
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "ratingCount": "250",
            "bestRating": "5",
            "worstRating": "1"
        },
        "featureList": [
            "50+ AI Models",
            "Write Faster",
            "Answer Anything",
            "Multiple Languages",
            "Available 24/7",
            "Private & Secure",
            "OpenAI Compatible API"
        ],
        "downloadUrl": "https://llm.resayil.io/register",
        "screenshot": "https://llm.resayil.io/screenshot.png"
    }
    </script>

    @if(app()->isProduction())
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M0T3YYQP7X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-M0T3YYQP7X');
    </script>
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:             #0f1115;
            --bg-card:        rgba(19,22,29,0.82);
            --gold:           #d4af37;
            --gold-light:     #e8cc5a;
            --gold-dim:       rgba(212,175,55,0.12);
            --gold-border:    rgba(212,175,55,0.35);
            --purple:         #7c3aed;
            --border:         rgba(255,255,255,0.07);
            --text:           #f0f2f7;
            --text-secondary: #a1aab8;
            --text-muted:     #6b7280;
            --red:            #ef4444;
            --red-dim:        rgba(239,68,68,0.12);
            --font:           'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --shadow-gold:    0 0 40px rgba(212,175,55,0.15), 0 4px 24px rgba(0,0,0,0.4);
            --shadow-card:    0 4px 32px rgba(0,0,0,0.3);
            --r-sm: 8px; --r-md: 12px; --r-lg: 20px; --r-xl: 28px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.65; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        [dir="rtl"] body, [lang="ar"] body { font-family: 'Tajawal', 'Inter', sans-serif; }
        a { color: inherit; text-decoration: none; }
        button { font-family: var(--font); cursor: pointer; border: none; background: none; }
        ul { list-style: none; }
        input { font-family: var(--font); }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image: linear-gradient(rgba(255,255,255,0.022) 1px,transparent 1px), linear-gradient(90deg,rgba(255,255,255,0.022) 1px,transparent 1px);
            background-size: 64px 64px;
        }
        .container    { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .container-sm { max-width: 800px;  margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .text-center { text-align: center; }
        .text-gradient {
            background: linear-gradient(135deg, var(--gold) 0%, #fff 55%, var(--gold-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .glass {
            background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border); border-radius: var(--r-lg); box-shadow: var(--shadow-card);
        }
        .glass-gold {
            background: var(--bg-card); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--gold-border); border-radius: var(--r-xl); box-shadow: var(--shadow-gold);
        }
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; border-radius: var(--r-md);
            font-weight: 600; font-size: 0.95rem;
            transition: all 0.2s ease; cursor: pointer; white-space: nowrap;
            position: relative; overflow: hidden;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0f1115;
            box-shadow: 0 0 24px rgba(212,175,55,0.30), 0 4px 16px rgba(0,0,0,0.3);
        }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(212,175,55,0.50), 0 8px 24px rgba(0,0,0,0.3); color: #0f1115; }
        .btn-gold::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg,transparent 40%,rgba(255,255,255,0.18) 100%); pointer-events: none; }
        .btn-lg  { padding: 1.1rem 2.4rem; font-size: 1.1rem; }
        .btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-ghost:hover { border-color: var(--gold-border); color: var(--gold); background: var(--gold-dim); }
        .btn-outline-gold { background: transparent; color: var(--gold); border: 1px solid var(--gold-border); }
        .btn-outline-gold:hover { background: var(--gold-dim); color: var(--gold); }

        /* NAVBAR */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 1rem 0; transition: all 0.3s; }
        .navbar.scrolled { background: rgba(15,17,21,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 0.65rem 0; }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; }
        .logo { display: flex; align-items: center; gap: 0.6rem; font-weight: 800; font-size: 1.2rem; letter-spacing: -0.02em; }
        .logo-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--gold); flex-shrink: 0; box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 24px rgba(212,175,55,0.4); animation: pulse-dot 2.5s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 24px rgba(212,175,55,0.4); } 50% { box-shadow: 0 0 20px rgba(212,175,55,1), 0 0 40px rgba(212,175,55,0.6); } }
        .nav-links { display: flex; align-items: center; gap: 0.2rem; }
        .nav-links a { color: var(--text-secondary); font-size: 0.9rem; font-weight: 500; padding: 0.5rem 0.9rem; border-radius: var(--r-sm); transition: all 0.2s; }
        .nav-links a:hover { color: var(--text); background: rgba(255,255,255,0.04); }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .nav-toggle { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 0.5rem; min-width: 44px; min-height: 44px; align-items: center; justify-content: center; border-radius: 8px; transition: background 0.2s; }
        .nav-toggle:hover { background: rgba(255,255,255,0.06); }
        .nav-toggle span { display: block; width: 22px; height: 2px; background: var(--text-secondary); border-radius: 2px; transition: transform 0.3s ease, opacity 0.3s ease; transform-origin: center; }
        .nav-toggle.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .nav-toggle.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .nav-toggle.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
        /* Mobile nav: z-index 1001 — ABOVE navbar (1000) so close button is always reachable */
        .mobile-nav { display: none; position: fixed; inset: 0; z-index: 1001; background: rgba(15,17,21,0.97); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); flex-direction: column; align-items: center; justify-content: center; gap: 2rem; }
        .mobile-nav.open { display: flex; }
        .mobile-nav a { font-size: 1.5rem; font-weight: 700; color: var(--text-secondary); transition: color 0.2s; }
        .mobile-nav a:hover { color: var(--gold); }
        .mobile-nav-close { position: absolute; top: 1rem; right: 1rem; color: #fff; cursor: pointer; padding: 0; min-width: 44px; min-height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); transition: background 0.2s; }
        .mobile-nav-close:hover { background: rgba(255,255,255,0.12); }
        .mobile-nav-close svg { width: 22px; height: 22px; }
        .mobile-lang-switch { display: flex; align-items: center; gap: 0; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin-top: 0.5rem; }
        .mobile-lang-switch a { font-size: 0.95rem; font-weight: 600; color: var(--text-muted); padding: 0.6rem 1.4rem; min-height: 44px; display: flex; align-items: center; transition: all 0.2s; letter-spacing: 0.05em; text-transform: uppercase; }
        .mobile-lang-switch a:hover { color: var(--gold); background: rgba(212,175,55,0.08); }
        .mobile-lang-switch a.active { color: var(--gold); background: rgba(212,175,55,0.12); }
        .mobile-lang-switch .sep { color: var(--border); font-size: 1.2rem; }

        /* HERO */
        .hero { min-height: 100vh; display: flex; align-items: center; padding: 8rem 0 6rem; position: relative; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; animation: orb-float 8s ease-in-out infinite; }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(212,175,55,0.18) 0%,transparent 70%); top: -100px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(124,58,237,0.20) 0%,transparent 70%); bottom: -80px; left: -80px; animation-delay: -3s; }
        .orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(212,175,55,0.10) 0%,transparent 70%); top: 40%; left: 30%; animation-delay: -5s; }
        @keyframes orb-float { 0%,100% { transform: translate(0,0) scale(1); } 33% { transform: translate(20px,-30px) scale(1.05); } 66% { transform: translate(-15px,20px) scale(0.95); } }
        .hero-content { position: relative; z-index: 2; text-align: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(212,175,55,0.10); border: 1px solid rgba(212,175,55,0.25); border-radius: 100px; padding: 0.45rem 1.1rem; font-size: 0.9rem; font-weight: 600; color: var(--gold); margin-bottom: 2rem; animation: badge-glow 3s ease-in-out infinite; }
        .hero-badge svg { width: 18px; height: 18px; flex-shrink: 0; }
        @keyframes badge-glow { 0%,100% { box-shadow: none; } 50% { box-shadow: 0 0 20px rgba(212,175,55,0.22); } }
        .hero-h1 { font-size: clamp(2.5rem,6vw,4.5rem); font-weight: 900; line-height: 1.08; letter-spacing: -0.03em; margin-bottom: 1.5rem; }
        .hero-sub { font-size: clamp(1.05rem,2.5vw,1.3rem); color: var(--text-secondary); max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
        .hero-cta-group { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .hero-trust { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 3rem; }
        .hero-trust strong { color: var(--text-secondary); }
        .stats-card { display: inline-flex; align-items: center; gap: 1.5rem; background: rgba(19,22,29,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: 100px; padding: 0.7rem 1.8rem; animation: stats-float 4s ease-in-out infinite; }
        @keyframes stats-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .h-stat { display: flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); }
        .sdot { width: 6px; height: 6px; border-radius: 50%; animation: sdot-pulse 2s ease-in-out infinite; }
        .sdot.g { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.7); }
        .sdot.y { background: var(--gold); box-shadow: 0 0 8px rgba(212,175,55,0.7); }
        .sdot.b { background: #38bdf8; box-shadow: 0 0 8px rgba(56,189,248,0.7); }
        @keyframes sdot-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .ssep { width: 1px; height: 16px; background: var(--border); }

        /* SECTIONS */
        section { position: relative; }
        .sp  { padding: 7rem 0; }
        .sps { padding: 5rem 0; }
        .slabel { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--gold); margin-bottom: 1rem; }
        .slabel::before { content: ''; display: block; width: 20px; height: 2px; background: var(--gold); border-radius: 2px; }
        .sh2 { font-size: clamp(1.9rem,4vw,3rem); font-weight: 800; line-height: 1.15; letter-spacing: -0.025em; margin-bottom: 1rem; }
        .ssub { font-size: 1.05rem; color: var(--text-secondary); max-width: 560px; line-height: 1.7; }
        .sheader { margin-bottom: 4rem; }

        /* BENTO */
        .bento { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }
        .bc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
        .bc::before { content: ''; position: absolute; inset: 0; border-radius: var(--r-lg); background: linear-gradient(135deg, var(--gold-dim) 0%, transparent 60%); opacity: 0; transition: opacity 0.3s; }
        .bc:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 30px rgba(212,175,55,0.10), 0 12px 40px rgba(0,0,0,0.3); }
        .bc:hover::before { opacity: 1; }
        .bc.w2 { grid-column: span 2; }
        .bi { width: 48px; height: 48px; background: var(--gold-dim); border: 1px solid rgba(212,175,55,0.20); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; transition: all 0.3s; }
        .bc:hover .bi { background: rgba(212,175,55,0.20); box-shadow: 0 0 16px rgba(212,175,55,0.25); }
        .bi svg { width: 24px; height: 24px; color: var(--gold); }
        .bc h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; position: relative; }
        .bc p  { font-size: 0.92rem; color: var(--text-secondary); line-height: 1.65; position: relative; }

        /* HOW IT WORKS */
        .steps-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; position: relative; }
        .steps-grid::before { content: ''; position: absolute; top: 30px; left: calc(16.66% + 1.25rem); right: calc(16.66% + 1.25rem); height: 1px; background: linear-gradient(90deg, transparent, var(--gold-border), transparent); pointer-events: none; z-index: 0; }
        .step-card { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem; text-align: center; position: relative; z-index: 1; transition: all 0.3s; }
        .step-card:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 24px rgba(212,175,55,0.08), 0 12px 40px rgba(0,0,0,0.3); }
        .step-num { width: 52px; height: 52px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0f1115; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.3rem; margin: 0 auto 1.25rem; box-shadow: 0 0 20px rgba(212,175,55,0.35); }
        .step-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
        .step-card p { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.65; }

        /* TESTIMONIALS */
        .t-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
        .tc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 1.75rem; transition: all 0.3s; }
        .tc:hover { border-color: var(--gold-border); transform: translateY(-3px); box-shadow: 0 0 24px rgba(212,175,55,0.08); }
        .tstars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
        .tstars svg { width: 16px; height: 16px; color: var(--gold); }
        .tq { font-size: 0.95rem; color: var(--text-secondary); line-height: 1.75; margin-bottom: 1.25rem; font-style: italic; }
        .ta { display: flex; align-items: center; gap: 0.75rem; }
        .tav { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; }
        .av-p { background: rgba(124,58,237,0.25); color: #a78bfa; border: 1px solid rgba(124,58,237,0.35); }
        .av-g { background: rgba(212,175,55,0.20); color: var(--gold); border: 1px solid var(--gold-border); }
        .av-b { background: rgba(56,189,248,0.20); color: #38bdf8; border: 1px solid rgba(56,189,248,0.35); }
        .tn { font-weight: 700; font-size: 0.9rem; }
        .tr { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }

        /* PRICING */
        .p-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.25rem; }
        .pc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; display: flex; flex-direction: column; }
        .pc:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 30px rgba(212,175,55,0.10), 0 12px 40px rgba(0,0,0,0.3); }
        .pc.feat { border-color: var(--gold-border); box-shadow: 0 0 40px rgba(212,175,55,0.12); }
        .pc.feat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg,transparent,var(--gold),transparent); }
        .pbadge { display: inline-block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #0f1115; background: linear-gradient(135deg,var(--gold),var(--gold-light)); border-radius: 100px; padding: 0.25rem 0.75rem; margin-bottom: 1rem; }
        .pname  { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.25rem; }
        .pprice { font-size: 2.4rem; font-weight: 900; line-height: 1; margin: 0.75rem 0 0.25rem; color: var(--gold); }
        .pprice small { font-size: 0.9rem; font-weight: 400; color: var(--text-muted); }
        .pdesc  { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem; }
        .pfeat  { margin-bottom: 1.75rem; flex: 1; }
        .pfeat li { display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.88rem; color: var(--text-secondary); padding: 0.35rem 0; }
        .pfeat li svg { width: 16px; height: 16px; color: var(--gold); flex-shrink: 0; margin-top: 1px; }

        /* FOOTER */
        footer { border-top: 1px solid var(--border); padding: 3rem 0; }
        .foot-in { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
        .foot-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .foot-links a { font-size: 0.85rem; color: var(--text-muted); transition: color 0.2s; }
        .foot-links a:hover { color: var(--text-secondary); }
        .foot-copy { font-size: 0.82rem; color: var(--text-muted); }

        /* ANIMATIONS */
        .fu { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .fu.on { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: 0.10s; }
        .d2 { transition-delay: 0.20s; }
        .d3 { transition-delay: 0.30s; }

        @media (prefers-reduced-motion: reduce) {
            .fu { opacity: 1; transform: none; transition: none; }
            .orb, .stats-card, .logo-dot { animation: none; }
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .bento { grid-template-columns: repeat(2,1fr); }
            .bc.w2 { grid-column: span 1; }
        }
        @media (max-width: 768px) {
            .nav-links, .nav-actions .btn-ghost, .nav-actions .btn-outline-gold { display: none; }
            .nav-toggle { display: flex; }
            .bento, .t-grid, .steps-grid { grid-template-columns: 1fr; }
            .p-grid { grid-template-columns: repeat(2,1fr); }
            .steps-grid::before { display: none; }
            .hero { padding: 7rem 0 4rem; }
            .sp { padding: 5rem 0; }
            .stats-card { flex-direction: column; gap: 0.75rem; border-radius: var(--r-md); padding: 1rem 1.5rem; }
            .ssep { width: 100%; height: 1px; }
            .foot-in { flex-direction: column; text-align: center; }
            .foot-links { justify-content: center; }
        }
        @media (max-width: 480px) {
            .hero-h1 { font-size: 2.2rem; }
            .hero-cta-group { flex-direction: column; align-items: stretch; }
            .hero-cta-group .btn { justify-content: center; }
        }
    </style>
    <!-- Hreflang Tags for Multilingual SEO -->
    <x-hreflang :currentPath="''" :isXDefault="true" />
</head>
<body>

<nav class="mobile-nav" id="mobile-nav" role="dialog" aria-label="Navigation menu" aria-modal="true">
    <button class="mobile-nav-close" id="mnav-close" aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <a href="#features" onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
    <a href="#how-it-works" onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'How It Works' }}</a>
    <a href="#pricing" onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
    <a href="/login" onclick="closeMnav()">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}</a>
    <a href="/register" onclick="closeMnav()" style="color:var(--gold);">{{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start Free' }}</a>
    <div class="mobile-lang-switch" role="group" aria-label="Language switcher">
        <a href="/locale/en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}" aria-label="Switch to English">EN</a>
        <span class="sep" aria-hidden="true">|</span>
        <a href="/locale/ar" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}" aria-label="التبديل إلى العربية">AR</a>
    </div>
</nav>

<header class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="logo" aria-label="LLM Resayil home">
                <div class="logo-dot" aria-hidden="true"></div>
                LLM Resayil
            </a>
            <nav class="nav-links" aria-label="Main navigation">
                <a href="#features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
                <a href="#how-it-works">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'How It Works' }}</a>
                <a href="#pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
            </nav>
            <div class="nav-actions">
                <div style="display:flex;align-items:center;gap:0;border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                    <a href="/locale/en" style="font-size:0.8rem;font-weight:600;padding:0.4rem 0.7rem;color:{{ app()->getLocale()==='en' ? 'var(--gold)' : 'var(--text-muted)' }};background:{{ app()->getLocale()==='en' ? 'rgba(212,175,55,0.12)' : 'transparent' }};transition:all 0.2s;" aria-label="English">EN</a>
                    <span style="color:var(--border);font-size:1rem;" aria-hidden="true">|</span>
                    <a href="/locale/ar" style="font-size:0.8rem;font-weight:600;padding:0.4rem 0.7rem;color:{{ app()->getLocale()==='ar' ? 'var(--gold)' : 'var(--text-muted)' }};background:{{ app()->getLocale()==='ar' ? 'rgba(212,175,55,0.12)' : 'transparent' }};transition:all 0.2s;" aria-label="العربية">AR</a>
                </div>
                <a href="/login" class="btn btn-ghost" style="padding:.6rem 1.1rem;font-size:.88rem;">{{ app()->getLocale() === 'ar' ? 'دخول' : 'Sign In' }}</a>
                <a href="/register" class="btn btn-gold" style="padding:.6rem 1.2rem;font-size:.88rem;">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start Free' }}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<main>

<!-- HERO -->
<section class="hero" aria-labelledby="hero-h1">
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge" role="note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                {{ app()->getLocale() === 'ar' ? '1000 رصيد مجاني &mdash; بدون بطاقة ائتمان' : '1,000 Free Credits &mdash; No Credit Card Needed' }}
            </div>
            <h1 class="hero-h1" id="hero-h1">
                {{ app()->getLocale() === 'ar' ? 'مساعدك الذكي' : 'Your Personal' }}<br>
                <span class="text-gradient">{{ app()->getLocale() === 'ar' ? 'الشخصي' : 'AI Assistant' }}</span>
            </h1>
            <p class="hero-sub">
                {{ app()->getLocale() === 'ar' ? 'اكتب وفكّر وأنجز أسرع من أي وقت مضى.' : 'Write faster, think smarter, and get answers instantly.' }}<br>
                {!! app()->getLocale() === 'ar' ? 'احصل على <strong>أكثر من 50 نموذج ذكاء اصطناعي</strong> على منصة واحدة.' : 'Access <strong>50+ powerful AI models</strong> on a single platform.' !!}
            </p>
            <div class="hero-cta-group">
                <a href="/register" class="btn btn-gold btn-lg">
                    {{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start Free' }}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a href="#features" class="btn btn-ghost btn-lg">{{ app()->getLocale() === 'ar' ? 'شاهد المميزات' : 'See Features' }}</a>
            </div>
            <p class="hero-trust"><strong>{{ app()->getLocale() === 'ar' ? 'موثوق من قبل المستخدمين حول العالم' : 'Trusted by users worldwide' }}</strong> &mdash; {{ app()->getLocale() === 'ar' ? 'يوفّرون ساعات يوميًا' : 'saving hours every day' }}</p>
            <div class="stats-card" role="status" aria-label="{{ app()->getLocale() === 'ar' ? 'إحصائيات المنصة المباشرة' : 'Live platform statistics' }}">
                <div class="h-stat"><div class="sdot g" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '1,200+ مستخدم متصل' : '1,200+ users online' }}</div>
                <div class="ssep" aria-hidden="true"></div>
                <div class="h-stat"><div class="sdot y" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '50+ نموذج ذكاء اصطناعي' : '50+ AI models' }}</div>
                <div class="ssep" aria-hidden="true"></div>
                <div class="h-stat"><div class="sdot b" aria-hidden="true"></div>{{ app()->getLocale() === 'ar' ? '99.9% وقت التشغيل' : '99.9% uptime' }}</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="sp" id="features" aria-labelledby="feat-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'ما يمكنك فعله' : 'What you can do' }}</div>
            <h2 class="sh2 fu d1" id="feat-h2">{!! app()->getLocale() === 'ar' ? 'مهامك اليومية، منجزة <span class="text-gradient-gold">أسرع 10 مرات</span>' : 'Everyday tasks, done <span class="text-gradient-gold">10x faster</span>' !!}</h2>
            <p class="ssub fu d2" style="margin:0 auto;">{{ app()->getLocale() === 'ar' ? 'لا حاجة لخبرة تقنية. فقط اكتب ما تحتاجه واحصل على النتائج فوراً.' : 'No technical knowledge needed. Just type what you need and get results instantly.' }}</p>
        </div>
        <div class="bento">
            <div class="bc fu">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'اكتب بسرعة' : 'Write Faster' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'رسائل بريد إلكترونية، تقارير، منشورات وسائط اجتماعية، مقالات &mdash; توليد محتوى محترف في ثوان. لا تحدق في صفحة فارغة مرة أخرى.' : 'Emails, reports, social posts, articles &mdash; generate polished content in seconds. Never stare at a blank page again.' }}</p>
            </div>
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'أجب على أي سؤال' : 'Answer Anything' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'ابحث عن المواضيع، احصل على تحليل فوري، وجد إجابات للأسئلة المعقدة دون قضاء ساعات في البحث.' : 'Research topics, get instant analysis, and find answers to complex questions without spending hours searching.' }}</p>
            </div>
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'لغات متعددة' : 'Multiple Languages' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'اعمل باللغة التي تشعر بالارتياح معها. نماذجنا تدعم العشرات من اللغات بدقة عالية.' : 'Work in the language you are most comfortable with. Our models support dozens of languages with high accuracy.' }}</p>
            </div>
            <div class="bc w2 fu">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'متاح 24/7 &mdash; دائماً فوري' : 'Available 24/7 &mdash; Always Instant' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'بدون انتظار، بدون مواعيد، بدون تأخير. مساعدك الذكي جاهز في اللحظة التي تحتاجه فيها، في أي وقت من الليل والنهار. سواء كان الساعة 3 صباحاً أو أضخم يوم عمل، نحن دائماً متصلين.' : 'No waiting, no appointments, no delays. Your AI assistant is ready the moment you need it, any time of day or night. Whether it is 3am or your busiest workday, we are always on.' }}</p>
            </div>
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? 'خاص وآمن' : 'Private &amp; Secure' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'محادثاتك وبياناتك تبقى آمنة. مبني مع الخصوصية كإعداد افتراضي &mdash; لا نقوم بتدريب نماذجنا على بياناتك.' : 'Your conversations and data stay safe. Built with privacy as the default &mdash; we do not train on your data.' }}</p>
            </div>
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                </div>
                <h3>{{ app()->getLocale() === 'ar' ? '50+ نموذج ذكاء اصطناعي' : '50+ AI Models' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'اختر أفضل ذكاء اصطناعي لكل مهمة. الكتابة الإبداعية، تحليل البيانات، مساعدة الترميز &mdash; هناك نموذج لكل شيء.' : 'Choose the best AI for each task. Creative writing, data analysis, coding help &mdash; there is a model for everything.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="sp" id="how-it-works" aria-labelledby="hiw-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'عملية بسيطة' : 'Simple Process' }}</div>
            <h2 class="sh2 fu d1" id="hiw-h2">{!! app()->getLocale() === 'ar' ? 'تشغيل وتشغيل في <span class="text-gradient-gold">30 ثانية</span>' : 'Up and running in <span class="text-gradient-gold">30 seconds</span>' !!}</h2>
            <p class="ssub fu d2" style="margin:0 auto;">{{ app()->getLocale() === 'ar' ? 'لا إعدادات تقنية، لا إعداد معقد. ثلاث خطوات بسيطة وأنت جاهز.' : 'No technical setup, no complicated onboarding. Three simple steps and you are ready.' }}</p>
        </div>
        <div class="steps-grid">
            <div class="step-card fu">
                <div class="step-num">1</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Create Account' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'اشترك في 30 ثانية ببريدك الإلكتروني. لا توجد بطاقة ائتمان مطلوبة لبدء تجربتك المجانية بـ 1000 رصيد.' : 'Sign up in 30 seconds with your email. No credit card required to start your free trial with 1,000 credits.' }}</p>
            </div>
            <div class="step-card fu d1">
                <div class="step-num">2</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'ابدأ المحادثة' : 'Start Chatting' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'اكتب سؤالك أو مهمتك أو فكرتك. اختر من 50+ نموذج ذكاء اصطناعي أو اترك النظام يختار الأفضل لك.' : 'Type your question, task, or idea. Pick from 50+ AI models or let the system choose the best one for you.' }}</p>
            </div>
            <div class="step-card fu d2">
                <div class="step-num">3</div>
                <h3>{{ app()->getLocale() === 'ar' ? 'احصل على النتائج' : 'Get Results' }}</h3>
                <p>{{ app()->getLocale() === 'ar' ? 'احصل على ردود فورية عالية الجودة. صقّل ونسّق واستخدم النتائج بالطريقة التي تحتاجها.' : 'Receive instant, high-quality responses. Refine, export, and use your results however you need them.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="sp" aria-labelledby="test-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'مستخدمون حقيقيون، نتائج حقيقية' : 'Real Users, Real Results' }}</div>
            @if(app()->getLocale() === 'ar')
            <h2 class="sh2 fu d1" id="test-h2">الناس يوفرون <span class="text-gradient-gold">ساعات كل يوم</span></h2>
            @else
            <h2 class="sh2 fu d1" id="test-h2">People are saving <span class="text-gradient-gold">hours every day</span></h2>
            @endif
        </div>
        <div class="t-grid">
            <div class="tc fu">
                <div class="tstars" aria-label="5 stars">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                <p class="tq">"أكتب جميع عروضي التجارية ورسائل بريدي الإلكتروني في دقائق الآن. إنه يفهم بالضبط ما أحتاجه والنبرة احترافية دائماً. لقد غيّر تماماً طريقة عملي."</p>
                <div class="ta">
                    <div class="tav av-p" aria-hidden="true">س</div>
                    <div>
                        <div class="tn">سارة ميتشل</div>
                        <div class="tr">مستشارة عمل حرة</div>
                    </div>
                </div>
                @else
                <p class="tq">"I write all my business proposals and client emails in minutes now. It understands exactly what I need and the tone is always professional. It has completely changed how I work."</p>
                <div class="ta">
                    <div class="tav av-p" aria-hidden="true">S</div>
                    <div>
                        <div class="tn">Sarah Mitchell</div>
                        <div class="tr">Freelance Consultant</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="tc fu d1">
                <div class="tstars" aria-label="5 stars">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                <p class="tq">"فريقي يستخدمه للبحث والتحليل كل يوم. جودة الردود استثنائية. لقد جعل الجميع أكثر إنتاجية بشكل كبير وقفزت جودة إنتاجنا."</p>
                <div class="ta">
                    <div class="tav av-g" aria-hidden="true">ج</div>
                    <div>
                        <div class="tn">جيمس ثورنتون</div>
                        <div class="tr">مدير منتج، شركة تقنية</div>
                    </div>
                </div>
                @else
                <p class="tq">"My team uses it for research and analysis every day. The quality of responses is remarkable. It has made everyone significantly more productive and our output quality has jumped."</p>
                <div class="ta">
                    <div class="tav av-g" aria-hidden="true">J</div>
                    <div>
                        <div class="tn">James Thornton</div>
                        <div class="tr">Product Manager, Tech Startup</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="tc fu d2">
                <div class="tstars" aria-label="5 stars">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                @if(app()->getLocale() === 'ar')
                <p class="tq">"وفّرت لي 3 ساعات يومياً على محتوى الإنشاء. كنت أعاني من كتابة التسميات والمقالات. الآن أصف ما أريده ويتم إنجازه في ثوانٍ. يستحق فعلاً."</p>
                <div class="ta">
                    <div class="tav av-b" aria-hidden="true">ن</div>
                    <div>
                        <div class="tn">نينا كوالسكي</div>
                        <div class="tr">منشئة محتوى ومدونة</div>
                    </div>
                </div>
                @else
                <p class="tq">"Saved me 3 hours every single day on content creation. I used to struggle with captions and articles. Now I describe what I want and it is done in seconds. Absolutely worth it."</p>
                <div class="ta">
                    <div class="tav av-b" aria-hidden="true">N</div>
                    <div>
                        <div class="tn">Nina Kowalski</div>
                        <div class="tr">Content Creator &amp; Blogger</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="sp" id="pricing" aria-labelledby="price-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</div>
            <h2 class="sh2 fu d1" id="price-h2">{!! app()->getLocale() === 'ar' ? 'أسعار بسيطة. <span class="text-gradient-gold">بدون مفاجآت.</span>' : 'Simple Pricing. <span class="text-gradient-gold">No Surprises.</span>' !!}</h2>
            <p class="ssub fu d2" style="margin:0 auto;">{{ app()->getLocale() === 'ar' ? 'ابدأ مجاناً، قم بالترقية عندما تحتاج المزيد. ألغ في أي وقت.' : 'Start free, upgrade only when you need more. Cancel anytime.' }}</p>
        </div>
        <div class="p-grid">
            <div class="pc fu">
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'تجربة مجانية' : 'Free Trial' }}</div>
                <div class="pprice">{{ app()->getLocale() === 'ar' ? 'مجاني' : 'FREE' }}<small>{{ app()->getLocale() === 'ar' ? ' / 7 أيام' : ' / 7 days' }}</small></div>
                <p class="pdesc">{{ app()->getLocale() === 'ar' ? 'كل ما تحتاجه لتجربة الذكاء الاصطناعي' : 'Everything you need to experience AI' }}</p>
                <ul class="pfeat">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? '1000 رصيد مجاني' : '1,000 free credits' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? '~500 ردود ذكاء اصطناعي' : '~500 AI responses' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'لا توجد بطاقة ائتمان مطلوبة' : 'No credit card required' }}
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'نماذج ذكاء اصطناعي متعددة' : 'Multiple AI models' }}
                    </li>
                </ul>
                <a href="/register" class="btn btn-outline-gold" style="width:100%;justify-content:center;padding:.85rem;">{{ app()->getLocale() === 'ar' ? 'ابدأ التجربة المجانية' : 'Start Free Trial' }}</a>
            </div>
            <div class="pc fu d1">
                <div class="pbadge">{{ app()->getLocale() === 'ar' ? 'الأكثر شيوعاً' : 'Most Popular' }}</div>
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'البداية' : 'Starter' }}</div>
                <div class="pprice">15 <small>{{ app()->getLocale() === 'ar' ? 'د.ك / شهر' : 'KWD / mo' }}</small></div>
                <p class="pdesc">{{ app()->getLocale() === 'ar' ? 'رائع للاستخدام الشخصي اليومي' : 'Great for daily personal use' }}</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '1000 رصيد / الشهر' : '1,000 credits / month' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '10 طلبات / دقيقة' : '10 requests / minute' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'مفتاح API واحد مجاني' : '1 free API key' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'نماذج صغيرة فقط' : 'Small models only' }}</li>
                </ul>
                <a href="/billing/plans" class="btn btn-outline-gold" style="width:100%;justify-content:center;padding:.85rem;">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            </div>
            <div class="pc feat fu d2">
                <div class="pbadge">{{ app()->getLocale() === 'ar' ? 'أفضل قيمة' : 'Best Value' }}</div>
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'أساسي' : 'Basic' }}</div>
                <div class="pprice">25 <small>{{ app()->getLocale() === 'ar' ? 'د.ك / شهر' : 'KWD / mo' }}</small></div>
                <p class="pdesc">{{ app()->getLocale() === 'ar' ? 'قوة أكثر، جميع أحجام النماذج' : 'More power, all model sizes' }}</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '3000 رصيد / الشهر' : '3,000 credits / month' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '30 طلب / دقيقة' : '30 requests / minute' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'مفتاح API واحد مجاني' : '1 free API key' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'جميع أحجام النماذج' : 'All model sizes' }}</li>
                </ul>
                <a href="/billing/plans" class="btn btn-gold" style="width:100%;justify-content:center;padding:.85rem;">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            </div>
            <div class="pc fu d3">
                <div class="pname">{{ app()->getLocale() === 'ar' ? 'احترافي' : 'Pro' }}</div>
                <div class="pprice">45 <small>{{ app()->getLocale() === 'ar' ? 'د.ك / شهر' : 'KWD / mo' }}</small></div>
                <p class="pdesc">{{ app()->getLocale() === 'ar' ? 'للمستخدمين المتقدمين والفريق' : 'For power users &amp; teams' }}</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '10000 رصيد / الشهر' : '10,000 credits / month' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? '60 طلب / دقيقة' : '60 requests / minute' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'مفتاحا API مجانيان' : '2 free API keys' }}</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>{{ app()->getLocale() === 'ar' ? 'أولوية نسخ احتياطي سحابي' : 'Priority cloud failover' }}</li>
                </ul>
                <a href="/billing/plans" class="btn btn-ghost" style="width:100%;justify-content:center;padding:.85rem;">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            </div>
        </div>
    </div>
</section>

<!-- CLUSTER 1: COST/ROI — Show calculator and comparison links -->
<section style="padding: 2rem 2rem; background: rgba(212,175,55,0.02); border-top: 1px solid rgba(212,175,55,0.1); margin: 3rem 0;">
    <div class="container" style="text-align: center;">
        <p style="color: var(--text-secondary); font-size: 0.95rem; margin: 0;">
            {!! app()->getLocale() === 'ar' ? 'هل تحتاج مساعدة في الاختيار؟ <a href="/cost-calculator" style="color: var(--gold); font-weight: 600; text-decoration: none;">جرب حاسبة التكاليف</a> لـ <a href="/cost-calculator" style="color: var(--gold); font-weight: 600; text-decoration: none;">رؤية مدخراتك</a>، أو <a href="/comparison" style="color: var(--gold); font-weight: 600; text-decoration: none;">قارن مع OpenRouter</a>.' : 'Need help choosing? <a href="/cost-calculator" style="color: var(--gold); font-weight: 600; text-decoration: none;">Try our cost calculator</a> to <a href="/cost-calculator" style="color: var(--gold); font-weight: 600; text-decoration: none;">see your savings</a>, or <a href="/comparison" style="color: var(--gold); font-weight: 600; text-decoration: none;">compare with OpenRouter</a>.' !!}
        </p>
    </div>
</section>

<!-- FINAL CTA -->
<section class="sps" aria-labelledby="cta-h2">
    <div class="container">
        <div class="glass-gold fu" style="padding:3.5rem 2.5rem;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:radial-gradient(circle,rgba(212,175,55,.10) 0%,transparent 70%);border-radius:50%;pointer-events:none;" aria-hidden="true"></div>
            <div style="position:absolute;bottom:-40px;left:-40px;width:160px;height:160px;background:radial-gradient(circle,rgba(124,58,237,.08) 0%,transparent 70%);border-radius:50%;pointer-events:none;" aria-hidden="true"></div>
            <div class="slabel" style="justify-content:center;margin-bottom:.75rem;">{{ app()->getLocale() === 'ar' ? 'هل أنت مستعد للبدء؟' : 'Ready to start?' }}</div>
            <h2 class="sh2" id="cta-h2" style="margin-bottom:.75rem;">{!! app()->getLocale() === 'ar' ? 'ابدأ تجربتك المجانية <span class="text-gradient-gold">اليوم</span>' : 'Start Your Free Trial <span class="text-gradient-gold">Today</span>' !!}</h2>
            <p style="color:var(--text-secondary);margin-bottom:2rem;font-size:1.05rem;">{{ app()->getLocale() === 'ar' ? '7 أيام مجاني. 1000 رصيد. لا توجد بطاقة ائتمان مطلوبة.' : '7 days free. 1,000 credits. No credit card required.' }}</p>
            <a href="/register" class="btn btn-gold btn-lg">
                {{ app()->getLocale() === 'ar' ? 'إنشاء حساب مجاني' : 'Create Free Account' }}
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            <p style="margin-top:1rem;font-size:.84rem;color:var(--text-muted);">{{ app()->getLocale() === 'ar' ? 'ألغ في أي وقت. بدون التزام.' : 'Cancel anytime. No commitment.' }}</p>
        </div>
    </div>
</section>

</main>

<footer role="contentinfo">
    <div class="container">
        <div class="foot-in">
            <a href="/" class="logo" aria-label="LLM Resayil home">
                <div class="logo-dot" aria-hidden="true"></div>
                LLM Resayil
            </a>
            <nav class="foot-links" aria-label="Footer navigation">
                <a href="#features">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</a>
                <a href="#pricing">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
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

    /* NAVBAR SCROLL */
    var navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function () {
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });

    /* MOBILE NAV */
    var mobileNav   = document.getElementById('mobile-nav');
    var navToggle   = document.getElementById('nav-toggle');
    var mnavClose   = document.getElementById('mnav-close');

    function openMnav() {
        mobileNav.classList.add('open');
        navToggle.classList.add('open');
        navToggle.setAttribute('aria-expanded', 'true');
        navToggle.setAttribute('aria-label', 'Close menu');
        document.body.style.overflow = 'hidden';
    }
    function closeMnav() {
        mobileNav.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-label', 'Open menu');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    window.closeMnav = closeMnav;

    navToggle.addEventListener('click', openMnav);
    mnavClose.addEventListener('click', closeMnav);
    mobileNav.addEventListener('click', function (e) {
        if (e.target === mobileNav) closeMnav();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMnav();
    });

    /* SCROLL ANIMATIONS */
    var fus = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    en.target.classList.add('on');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        fus.forEach(function (el) { io.observe(el); });
    } else {
        fus.forEach(function (el) { el.classList.add('on'); });
    }

})();
</script>

</body>
</html>
