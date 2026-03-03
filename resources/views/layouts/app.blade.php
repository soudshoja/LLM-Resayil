<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('navigation.portal_title'))</title>
    {{-- Open Graph / Social --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', __('navigation.brand')) — AI API for Developers">
    <meta property="og:description" content="@yield('og_description', __('navigation.og_description_default'))">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta property="og:site_name" content="{{ __('navigation.brand') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('navigation.brand'))">
    <meta name="twitter:description" content="@yield('og_description', __('navigation.og_description_short'))">
    <meta name="twitter:image" content="{{ asset('og-image.png') }}">
    <meta name="description" content="@yield('meta_description', __('navigation.meta_description_default'))">
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
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-gold { background: rgba(212,175,55,0.15); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
        .badge-green { background: rgba(5,150,105,0.15); color: #6ee7b7; border: 1px solid rgba(5,150,105,0.3); }
        @media(max-width: 768px) { .grid-cols-2, .grid-cols-3 { grid-template-columns: 1fr; } main { padding: 1rem; } }
        /* Locale switcher */
        .locale-switcher { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; margin-left: 0.5rem; }
        .locale-switcher a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .locale-switcher a:hover { color: var(--text-primary); }
        .locale-switcher a.active { color: var(--gold); font-weight: 600; }
        .locale-switcher span { color: var(--border); }
        /* RTL support */
        [dir="rtl"] body { font-family: 'Tajawal', 'Inter', sans-serif; }
        [dir="rtl"] .nav-links { flex-direction: row-reverse; }
        [dir="rtl"] .sidebar { right: 0; left: auto; }
        [dir="rtl"] .card { text-align: right; }
        [dir="rtl"] .locale-switcher { margin-left: 0; margin-right: 0.5rem; order: -1; }
        [dir="rtl"] .hero-content { text-align: right; }
        [dir="rtl"] .code-block { direction: ltr; } /* keep code LTR */
        [dir="rtl"] .stat-card { text-align: right; }
        [dir="rtl"] .feature-item { flex-direction: row-reverse; }
        [dir="rtl"] .btn-group { flex-direction: row-reverse; }
        [dir="rtl"] .input-group .input-label { text-align: right; }
        [dir="rtl"] .form-label { text-align: right; }
        [dir="rtl"] .alert { text-align: right; }
        [dir="rtl"] .nav-brand { margin-left: auto; margin-right: 0; }
    </style>
    @stack('styles')
</head>
<body dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<nav>
    <a href="/" class="nav-brand">⚡ {{ __('navigation.brand') }}</a>
    <div class="nav-links">
        <!-- Language Switcher -->
        <div class="locale-switcher">
            <a href="{{ route('locale', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            <span>|</span>
            <a href="{{ route('locale', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}">ع</a>
        </div>
        @auth
            <a href="/dashboard">{{ __('navigation.dashboard') }}</a>
            <a href="/api-keys">{{ __('navigation.api_keys') }}</a>
            <a href="/billing/plans" style="color:var(--gold)">{{ __('navigation.billing') }}</a>
            <a href="/docs">{{ __('navigation.docs') }}</a>
            <a href="/credits">{{ __('navigation.credits') }}</a>
            <a href="/contact">{{ __('navigation.contact') }}</a>
            <a href="/billing/payment-methods">{{ __('navigation.payment_methods') }}</a>
            @if(auth()->user()->subscription_tier === 'enterprise')
            <a href="/teams">{{ __('navigation.team') }}</a>
            @endif
            @if(auth()->user()->email === 'admin@llm.resayil.io')
            <a href="/admin" style="color:var(--gold)">{{ __('navigation.admin') }}</a>
            <a href="/admin/monitoring" style="color:var(--gold)">{{ __('navigation.monitor') }}</a>
            <a href="/admin/models" style="color:var(--gold)">{{ __('navigation.models') }}</a>
            @endif
            <a href="/profile">{{ __('navigation.profile') }}</a>
            <form method="POST" action="/logout" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-outline" style="padding:0.4rem 0.8rem">{{ __('navigation.logout') }}</button>
            </form>
        @else
            <a href="/login">{{ __('navigation.login') }}</a>
            <a href="/register" class="btn btn-gold">{{ __('navigation.get_started') }}</a>
        @endauth
    </div>
</nav>

@if(session('success'))
<div style="max-width:1200px;margin:1rem auto;padding:0 2rem">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif
@if(session('error'))
<div style="max-width:1200px;margin:1rem auto;padding:0 2rem">
    <div class="alert alert-error">{{ session('error') }}</div>
</div>
@endif

@yield('content')

@stack('scripts')
</body>
</html>
