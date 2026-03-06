<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? (@yield('title', 'LLM Resayil')) }}</title>
    <meta name="description" content="{{ $pageDescription ?? 'Affordable OpenAI-compatible LLM API with 45+ models. Pay-per-token pricing, free tier with 1,000 credits.' }}">
    <meta name="keywords" content="{{ $pageKeywords ?? 'llm api, openai alternative, ai api' }}">
    <meta property="og:title" content="{{ $pageTitle ?? (@yield('title', 'LLM Resayil')) }}">
    <meta property="og:description" content="{{ $pageDescription ?? '' }}">
    <meta property="og:image" content="{{ $ogImage ?? 'https://llm.resayil.io/og-images/og-default.png' }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? (@yield('title', 'LLM Resayil')) }}">
    <meta name="twitter:description" content="{{ $pageDescription ?? '' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? 'https://llm.resayil.io/og-images/og-default.png' }}">
    <link rel="canonical" href="{{ url(request()->getPathInfo()) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        a { text-decoration: none; color: inherit; }
        :root {
            --bg-primary: #0f1115;
            --bg-secondary: #0a0d14;
            --bg-card: #13161d;
            --gold: #d4af37;
            --gold-light: #eac558;
            --gold-muted: #8a702a;
            --slate: #1a1d24;
            --text-primary: #e0e5ec;
            --text-secondary: #a0a8b5;
            --text-muted: #6b7280;
            --success: #059669;
            --error: #ef4444;
            --warning: #f59e0b;
            --border: #1e2230;
        }
        body { background: var(--bg-secondary); color: var(--text-primary); font-family: 'Inter', sans-serif; min-height: 100vh; }
        [lang="ar"] body, [dir="rtl"] body { font-family: 'Tajawal', 'Inter', sans-serif; }

        /* ── Navbar ── */
        nav { background: rgba(10,13,20,0.97); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100; }
        .nav-brand { font-size: 1.15rem; font-weight: 700; background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -0.01em; }
        .nav-links { display: flex; gap: 0.25rem; align-items: center; }
        .nav-links a { color: var(--text-secondary); font-size: 0.875rem; padding: 0.4rem 0.7rem; border-radius: 6px; transition: color 0.2s, background 0.2s; white-space: nowrap; }
        .nav-links a:hover { color: var(--text-primary); background: var(--slate); }
        .nav-links a.nav-active { color: var(--text-primary); background: var(--slate); }
        .nav-link-gold { color: var(--gold) !important; }
        .nav-link-gold:hover { color: var(--gold-light) !important; background: rgba(212,175,55,0.08) !important; }

        /* ── Mobile hamburger ── */
        .nav-hamburger { display: none; background: transparent; border: none; cursor: pointer; padding: 0.4rem; color: var(--text-secondary); transition: color 0.2s; }
        .nav-hamburger:hover { color: var(--text-primary); }
        .nav-hamburger svg { display: block; }
        @media(max-width: 900px) {
            .nav-links { display: none; position: fixed; top: 64px; left: 0; right: 0; background: rgba(10,13,20,0.98); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); flex-direction: column; align-items: flex-start; padding: 1rem 1.5rem; gap: 0.25rem; z-index: 99; }
            .nav-links.nav-open { display: flex; }
            .nav-links a { width: 100%; padding: 0.6rem 0.75rem; font-size: 0.95rem; }
            .nav-hamburger { display: flex; align-items: center; gap: 0.5rem; }
            .nav-hamburger-lang { display: none; }
        }

        /* ── Buttons ── */
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.875rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; gap: 0.4rem; }
        .btn-gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
        .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(212,175,55,0.3); color: #0a0d14; }
        /* Nav Get Started — outlined style so gold text is visible on dark navbar */
        .nav-links .btn-gold { background: transparent; border: 1px solid var(--gold); color: var(--gold); }
        .nav-links .btn-gold:hover { background: rgba(212,175,55,0.1); color: var(--gold); transform: none; box-shadow: none; opacity: 1; }
        .btn-outline { border: 1px solid var(--gold-muted); color: var(--gold); background: transparent; }
        .btn-outline:hover { background: rgba(212,175,55,0.1); color: var(--gold); }
        .btn-danger { background: var(--error); color: white; }
        .btn-danger:hover { opacity: 0.9; }

        /* ── Layout ── */
        main { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-input { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; padding: 0.65rem 1rem; color: var(--text-primary); font-size: 0.925rem; transition: border-color 0.2s; font-family: inherit; }
        .form-input:focus { outline: none; border-color: var(--gold-muted); }

        /* ── Alerts ── */
        .alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-success { background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); color: #6ee7b7; }

        /* ── Grid helpers ── */
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }

        /* ── Text utilities ── */
        .text-gold { color: var(--gold); }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .text-muted { color: var(--text-muted); }
        .text-secondary { color: var(--text-secondary); }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .hidden { display: none !important; }
        .ml-1 { margin-left: 0.25rem; }

        /* ── Spacing ── */
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-6 { margin-top: 1.5rem; }

        /* ── Flex ── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }

        /* ── Session messages ── */
        .session-message { max-width: 1200px; margin: 1rem auto; padding: 0 2rem; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-gold { background: rgba(212,175,55,0.15); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
        .badge-green { background: rgba(5,150,105,0.15); color: #6ee7b7; border: 1px solid rgba(5,150,105,0.3); }

        /* ── Language Switcher ── */
        .lang-switch { position: relative; }
        .lang-switch button { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.4rem 0.7rem; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center; gap: 0.4rem; font-size: 0.875rem; font-family: inherit; }
        .lang-switch button:hover { color: var(--text-primary); background: var(--slate); }
        .lang-dropdown { display: none; position: absolute; top: calc(100% + 0.5rem); right: 0; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.4); overflow: hidden; min-width: 160px; z-index: 200; }
        [dir="rtl"] .lang-dropdown { right: auto; left: 0; }
        .lang-dropdown.show { display: block; }
        .lang-dropdown button { display: flex; width: 100%; padding: 0.65rem 1rem; background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.875rem; transition: all 0.2s; font-family: inherit; }
        .lang-dropdown button:hover { color: var(--text-primary); background: var(--slate); }
        .lang-dropdown button.active { color: var(--gold); font-weight: 600; }
        .lang-option { display: flex; align-items: center; gap: 0.5rem; }

        /* ── Responsive ── */
        @media(max-width: 768px) {
            .grid-cols-2, .grid-cols-3 { grid-template-columns: 1fr; }
            main { padding: 1rem; }
        }
    </style>
    @stack('styles')
    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "LLM Resayil",
        "url": "https://llm.resayil.io",
        "logo": "https://llm.resayil.io/logo.png",
        "description": "Powerful AI assistant platform with 50+ models. Write faster, answer anything, and get results instantly. 1,000 free credits—no credit card required.",
        "foundingDate": "2024-01-01",
        "headquarters": {
            "@type": "Place",
            "address": {
                "@type": "PostalAddress",
                "addressCountry": "KW"
            }
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Support",
            "email": "support@resayil.io",
            "availableLanguage": ["en", "ar"]
        },
        "areaServed": {
            "@type": "Country",
            "name": "Worldwide"
        },
        "sameAs": [
            "https://twitter.com/LLMResayil",
            "https://facebook.com/LLMResayil"
        ]
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

      // PHASE 10 FINDING #6: Internal Link Click Tracking
      document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href^="/"]');
        if (link && link.href) {
          gtag('event', 'internal_link_click', {
            'link_destination': link.href,
            'link_text': link.textContent.trim(),
            'page_source': window.location.pathname
          });
        }
      });
    </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Language switcher
            const langSwitch = document.querySelector('.lang-switch');
            const langButton = langSwitch?.querySelector('.lang-btn');
            const langDropdown = langSwitch?.querySelector('.lang-dropdown');

            langButton?.addEventListener('click', function(e) {
                e.stopPropagation();
                langDropdown.classList.toggle('show');
            });

            document.querySelectorAll('.lang-option-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const lang = this.getAttribute('data-lang');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    try {
                        await fetch('/locale/ajax/' + lang, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                        });
                    } catch (e) {}
                    location.reload();
                });
            });

            document.addEventListener('click', function(e) {
                if (!langSwitch?.contains(e.target)) {
                    langDropdown?.classList.remove('show');
                }
            });

            // Mobile hamburger menu
            const hamburger = document.getElementById('nav-hamburger');
            const navLinks = document.querySelector('.nav-links');
            hamburger?.addEventListener('click', function() {
                navLinks.classList.toggle('nav-open');
                const isOpen = navLinks.classList.contains('nav-open');
                hamburger.setAttribute('aria-expanded', isOpen);
                hamburger.querySelector('.ham-icon-open').style.display = isOpen ? 'none' : 'block';
                hamburger.querySelector('.ham-icon-close').style.display = isOpen ? 'block' : 'none';
            });

            // Close mobile menu when a link is clicked
            navLinks?.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.classList.remove('nav-open');
                });
            });

            // Active nav link highlighting
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-links a').forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '/' && currentPath.startsWith(href)) {
                    link.classList.add('nav-active');
                } else if (href === '/' && currentPath === '/') {
                    link.classList.add('nav-active');
                }
            });
        });
    </script>
</head>
<body>
<nav>
    <a href="/" class="nav-brand">LLM Resayil</a>

    <!-- Mobile hamburger -->
    <button id="nav-hamburger" class="nav-hamburger" aria-label="Toggle navigation" aria-expanded="false">
        <svg class="ham-icon-open" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg class="ham-icon-close" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <div class="nav-links">
        <!-- Language Switcher -->
        <div class="lang-switch">
            <button class="lang-btn" type="button" aria-label="{{ __('navigation.language') }}">
                <span style="font-weight:600;letter-spacing:0.03em">{{ app()->getLocale() === 'ar' ? 'AR' : 'EN' }}</span>
                <span style="color:var(--border);margin:0 1px">|</span>
                <span style="color:var(--text-muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}</span>
            </button>
            <div class="lang-dropdown">
                <button class="lang-option-btn{{ app()->getLocale() === 'en' ? ' active' : '' }}" data-lang="en">
                    <span class="lang-option">
                        <svg width="16" height="12" viewBox="0 0 640 480">
                            <path fill="#b22234" d="M0 0h640v480H0z"/>
                            <path fill="#fff" d="M0 70h640v40H0zM0 190h640v40H0zM0 310h640v40H0zM0 430h640v40H0z"/>
                            <path fill="#3c3b6e" d="M0 0h266.67v240H0z"/>
                            <g fill="#fff"><circle cx="26.67" cy="24" r="12"/><circle cx="80" cy="24" r="12"/><circle cx="133.33" cy="24" r="12"/><circle cx="186.67" cy="24" r="12"/><circle cx="240" cy="24" r="12"/></g>
                        </svg>
                        <span>{{ __('navigation.english') }}</span>
                    </span>
                </button>
                <button class="lang-option-btn{{ app()->getLocale() === 'ar' ? ' active' : '' }}" data-lang="ar">
                    <span class="lang-option">
                        <svg width="16" height="12" viewBox="0 0 640 480">
                            <path fill="#007a3d" d="M0 0h640v160H0z"/>
                            <path fill="#fff" d="M0 160h640v160H0z"/>
                            <path fill="#ff0000" d="M0 320h640v160H0z"/>
                            <path fill="#000" d="M0 0l213.33 160L0 320V0z"/>
                        </svg>
                        <span>{{ __('navigation.arabic') }}</span>
                    </span>
                </button>
            </div>
        </div>

        @auth
            <a href="/dashboard">{{ __('navigation.dashboard') }}</a>
            <a href="/api-keys">{{ __('navigation.api_keys') }}</a>
            <a href="/billing/plans" class="nav-link-gold">{{ __('navigation.billing') }}</a>
            <a href="/docs">{{ __('navigation.docs') }}</a>
            <a href="/credits">{{ __('navigation.credits') }}</a>
            <a href="/billing/payment-methods">{{ __('navigation.payment_methods') }}</a>
            @if(auth()->user()->subscription_tier === 'enterprise')
            <a href="/teams">{{ __('navigation.team') }}</a>
            @endif
            @if(auth()->user()->isAdmin())
            <a href="/admin" class="nav-link-gold">{{ __('navigation.admin') }}</a>
            <a href="/admin/monitoring" class="nav-link-gold">{{ __('navigation.monitor') }}</a>
            <a href="/admin/models" class="nav-link-gold">{{ __('navigation.models') }}</a>
            @endif
            <a href="/profile">{{ __('navigation.profile') }}</a>
            <form method="POST" action="/logout" class="nav-logout-form">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">{{ __('navigation.logout') }}</button>
            </form>
        @else
            <a href="/login">{{ __('navigation.login') }}</a>
            <a href="/register" class="btn btn-gold btn-sm">{{ __('navigation.get_started') }}</a>
        @endauth
    </div>
</nav>

@if(session('success'))
<div class="session-message">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div class="session-message">
    <div class="alert alert-error">{{ session('error') }}</div>
</div>
@endif

@yield('content')

<!-- GLOBAL FOOTER LINKS (All Pages) — CLUSTERS 1,2,3 -->
<footer style="border-top: 1px solid var(--border); background: rgba(0,0,0,0.3); padding: 3rem 2rem; margin-top: 4rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
        <!-- CLUSTER 1: COST/ROI -->
        <div>
            <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">Pricing & Savings</p>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 0.5rem;"><a href="/pricing" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Pricing Plans</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/cost-calculator" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Cost Calculator</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/comparison" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Compare OpenRouter</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/alternatives" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">LLM Alternatives</a></li>
            </ul>
        </div>
        <!-- CLUSTER 2: INTEGRATION/API -->
        <div>
            <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">Developer Tools</p>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 0.5rem;"><a href="/docs" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">API Documentation</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/features" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Available Models</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/api-keys" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">API Keys</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/credits" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">How Credits Work</a></li>
            </ul>
        </div>
        <!-- CLUSTER 3: EDUCATION -->
        <div>
            <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;">Learn & Compare</p>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 0.5rem;"><a href="/about" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">About Us</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/contact" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Contact Support</a></li>
                <li style="margin-bottom: 0.5rem;"><a href="/privacy-policy" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;">Privacy</a></li>
            </ul>
        </div>
    </div>
    <div style="max-width: 1200px; margin: 2rem auto 0; padding-top: 2rem; border-top: 1px solid var(--border); text-align: center; color: var(--text-muted); font-size: 0.85rem;">
        <p>LLM Resayil &copy; 2026. Affordable, OpenAI-compatible LLM API.</p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
