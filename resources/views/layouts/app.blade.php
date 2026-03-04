<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LLM Resayil') - LLM Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
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
        nav { background: rgba(10,13,20,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 50; }
        .nav-brand { font-size: 1.25rem; font-weight: 700; background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; }
        .nav-links { display: flex; gap: 1rem; align-items: center; }
        .nav-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; padding: 0.4rem 0.8rem; border-radius: 6px; transition: all 0.2s; }
        .nav-links a:hover { color: var(--text-primary); background: var(--slate); }
        .nav-link-gold { color: var(--gold); }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.875rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; }
        .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(212,175,55,0.3); }
        .btn-outline { border: 1px solid var(--gold-muted); color: var(--gold); background: transparent; }
        .btn-outline:hover { background: rgba(212,175,55,0.1); }
        .btn-danger { background: var(--error); color: white; }
        .btn-danger:hover { opacity: 0.9; }
        main { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .form-input { width: 100%; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; padding: 0.65rem 1rem; color: var(--text-primary); font-size: 0.925rem; transition: border-color 0.2s; }
        .form-input:focus { outline: none; border-color: var(--gold-muted); }
        .alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-success { background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); color: #6ee7b7; }
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .text-gold { color: var(--gold); }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .text-muted { color: var(--text-muted); }
        .text-secondary { color: var(--text-secondary); }
        .font-bold { font-weight: 700; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-6 { margin-top: 1.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }
        .session-message { max-width: 1200px; margin: 1rem auto; padding: 0 2rem; }
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-gold { background: rgba(212,175,55,0.15); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
        .badge-green { background: rgba(5,150,105,0.15); color: #6ee7b7; border: 1px solid rgba(5,150,105,0.3); }
        /* ── Language Switcher ── */
        .lang-switch { position: relative; }
        .lang-switch button { background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.4rem 0.8rem; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; }
        .lang-switch button:hover { color: var(--text-primary); background: var(--slate); }
        .lang-dropdown { position: absolute; top: 100%; right: 0; margin-top: 0.5rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); overflow: hidden; min-width: 160px; z-index: 100; }
        .lang-dropdown.show { display: block; }
        .lang-dropdown button { display: block; width: 100%; padding: 0.6rem 1rem; text-align: left; background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.875rem; transition: all 0.2s; }
        .lang-dropdown button:hover { color: var(--text-primary); background: var(--slate); }
        .lang-dropdown button.active { color: var(--gold); font-weight: 600; }
        .lang-option { display: flex; align-items: center; gap: 0.5rem; }
        @media(max-width: 768px) { .grid-cols-2, .grid-cols-3 { grid-template-columns: 1fr; } main { padding: 1rem; } }
    </style>
    @stack('styles')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Language switcher
            const langSwitch = document.querySelector('.lang-switch');
            const langButton = langSwitch?.querySelector('.lang-btn');
            const langDropdown = langSwitch?.querySelector('.lang-dropdown');
            let currentLocale = '{{ app()->getLocale() }}';

            function toggleLangDropdown() {
                langDropdown.classList.toggle('show');
            }

            function setLanguage(lang) {
                currentLocale = lang;
                // Create hidden form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/language/' + lang;

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                // Add locale hidden input
                const localeInput = document.createElement('input');
                localeInput.type = 'hidden';
                localeInput.name = 'locale';
                localeInput.value = lang;
                form.appendChild(localeInput);

                document.body.appendChild(form);
                form.submit();
            }

            langButton?.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleLangDropdown();
            });

            document.querySelectorAll('.lang-option-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const lang = this.getAttribute('data-lang');
                    setLanguage(lang);
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!langSwitch?.contains(e.target)) {
                    langDropdown.classList.remove('show');
                }
            });
        });
    </script>
</head>
<body>
<nav>
    <a href="/" class="nav-brand">⚡ LLM Resayil</a>
    <div class="nav-links">
        <div class="lang-switch">
            <button class="lang-btn" type="button" aria-label="{{ __('navigation.language') }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span id="current-lang-text">{{ app()->getLocale() === 'ar' ? 'AR' : 'EN' }}</span>
            </button>
            <div class="lang-dropdown">
                <button class="lang-option-btn" data-lang="en">
                    <span class="lang-option">
                        <svg width="16" height="12" viewBox="0 0 640 480">
                            <path fill="#b22234" d="M0 0h640v480H0z"/>
                            <path fill="#fff" d="M0 70h640v40H0zM0 190h640v40H0zM0 310h640v40H0zM0 430h640v40H0z"/>
                            <path fill="#3c3b6e" d="M0 0h266.67v240H0z"/>
                            <g fill="#fff">
                                <circle cx="26.67" cy="24" r="12"/>
                                <circle cx="80" cy="24" r="12"/>
                                <circle cx="133.33" cy="24" r="12"/>
                                <circle cx="186.67" cy="24" r="12"/>
                                <circle cx="240" cy="24" r="12"/>
                            </g>
                        </svg>
                        <span>{{ __('navigation.english') }}</span>
                    </span>
                </button>
                <button class="lang-option-btn" data-lang="ar">
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
            @if(auth()->user()->email === 'admin@llm.resayil.io')
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
            <a href="/register" class="btn btn-gold">{{ __('navigation.get_started') }}</a>
        @endauth
    </div>
</nav>

@if(session('success'))
<div class="session-message session-message-success">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div class="session-message session-message-error">
    <div class="alert alert-error">{{ session('error') }}</div>
</div>
@endif

@yield('content')

@stack('scripts')
</body>
</html>
