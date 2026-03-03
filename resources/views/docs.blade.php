<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation — LLM Resayil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1115;
            --bg-card: #13161d;
            --bg-secondary: #0a0c12;
            --gold: #d4af37;
            --gold-dim: rgba(212,175,55,0.15);
            --gold-border: rgba(212,175,55,0.3);
            --border: #1e2230;
            --text: #e0e5ec;
            --text-muted: #6b7280;
            --text-secondary: #a0a8b5;
            --green: #22c55e;
            --red: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(15, 17, 21, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--gold), #ffd700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .navbar-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .navbar-link:hover {
            color: var(--gold);
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold), #ffd700);
            color: var(--bg);
            padding: 0.65rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 8rem 2rem 4rem 2rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .hero h1 span {
            color: var(--gold);
        }

        .hero-lead {
            font-size: 1.15rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        /* Docs List Section */
        .docs-section {
            padding: 3rem 2rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .doc-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .doc-card:hover {
            border-color: var(--gold-border);
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(212, 175, 55, 0.1);
        }

        .doc-date {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .doc-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text);
        }

        .doc-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
            flex: 1;
        }

        .doc-tag {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            background: var(--gold-dim);
            color: var(--gold);
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 1rem;
            align-self: flex-start;
        }

        .doc-link-arrow {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.85rem;
            color: var(--gold);
            margin-top: auto;
        }

        .doc-link-arrow svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .doc-card:hover .doc-link-arrow svg {
            transform: translateX(4px);
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--gold);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">LLM Resayil</div>
        <div class="navbar-nav">
            <a href="/" class="navbar-link">Home</a>
            <a href="/dashboard" class="navbar-link">Dashboard</a>
            @if(auth()->check())
                <a href="/credits" class="navbar-link">How Credits Work</a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Documentation</h1>
        <p class="hero-lead">Everything you need to know about using the LLM Resayil API, pricing, billing, and features.</p>
        <div>
            <a href="/dashboard" class="btn-gold">Go to Dashboard</a>
        </div>
    </section>

    <!-- Documentation List -->
    <section class="docs-section">
        <div class="docs-grid">
            <!-- Billing & Plans -->
            <a href="/docs/plans/2026-03-02-billing-admin-enhancements.md" class="doc-card" style="pointer-events: auto;">
                <span class="doc-date">March 2, 2026</span>
                <h3 class="doc-title">Billing & Subscription Plans</h3>
                <p class="doc-description">Complete guide to subscription tiers (Starter, Basic, Pro), credit top-ups, trial period, and auto-billing with MyFatoorah payment gateway.</p>
                <span class="doc-tag">Billing</span>
                <div class="doc-link-arrow">Read more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
            </a>

            <!-- Model Catalog -->
            <a href="/docs/plans/2026-03-02-model-catalog-admin-panel.md" class="doc-card" style="pointer-events: auto;">
                <span class="doc-date">March 2, 2026</span>
                <h3 class="doc-title">Model Catalog & Admin Panel</h3>
                <p class="doc-description">Access 45+ LLM models (local and cloud), detailed model metadata, search and filter options, and admin model management.</p>
                <span class="doc-tag">API</span>
                <div class="doc-link-arrow">Read more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
            </a>

            <!-- Recurring Payments & WhatsApp -->
            <a href="/docs/plans/2026-03-02-billing-recurring-whatsapp.md" class="doc-card" style="pointer-events: auto;">
                <span class="doc-date">March 2, 2026</span>
                <h3 class="doc-title">Recurring Payments & WhatsApp Timeline</h3>
                <p class="doc-description">MyFatoorah recurring payment gateway setup, WhatsApp notification timeline (Day 1, Day 6, Day 7), and trial automation.</p>
                <span class="doc-tag">Billing</span>
                <div class="doc-link-arrow">Read more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
            </a>
        </div>

        <div style="margin-top: 3rem; padding: 2rem; background: var(--bg-card); border-radius: 12px; text-align: center;">
            <h2 style="color: var(--text); margin-bottom: 1rem;">Need More Help?</h2>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Check out our API reference and frequently asked questions.</p>
            <a href="/credits" class="btn-gold">How Credits Work</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>LLM Resayil Documentation &bull; <a href="/">Home</a> &bull; <a href="/dashboard">Dashboard</a></p>
    </footer>
</body>
</html>
