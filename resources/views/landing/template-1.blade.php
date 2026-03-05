<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LLM Resayil — The fastest OpenAI-compatible LLM API in the Middle East. Pay per use, no subscriptions, Arabic-first, GPU-powered.">
    <title>LLM Resayil — The Fastest LLM API in the Middle East</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0f1115;
            --bg-card: #13161d;
            --bg-secondary: #1a1e27;
            --gold: #d4af37;
            --gold-dim: rgba(212, 175, 55, 0.15);
            --gold-glow: rgba(212, 175, 55, 0.25);
            --border: rgba(255, 255, 255, 0.08);
            --border-gold: rgba(212, 175, 55, 0.35);
            --text: #f0f0f0;
            --text-muted: #6b7280;
            --text-secondary: #9ca3af;
            --radius: 12px;
            --radius-lg: 18px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ─── UTILITIES ────────────────────────────────────────── */

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .gold { color: var(--gold); }

        .fade-in {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-delay-1 { transition-delay: 0.1s; }
        .fade-in-delay-2 { transition-delay: 0.2s; }
        .fade-in-delay-3 { transition-delay: 0.3s; }
        .fade-in-delay-4 { transition-delay: 0.4s; }
        .fade-in-delay-5 { transition-delay: 0.5s; }

        /* ─── NAVBAR ───────────────────────────────────────────── */

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            background: rgba(15, 17, 21, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(15, 17, 21, 0.97);
            box-shadow: 0 1px 0 var(--border);
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gold);
            text-decoration: none;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-logo svg {
            flex-shrink: 0;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
        }

        .navbar-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .navbar-links a:hover {
            color: var(--text);
        }

        .navbar-cta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-gold {
            background: var(--gold);
            color: #0f1115;
        }

        .btn-gold:hover {
            background: #c9a227;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(212, 175, 55, 0.35);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            color: var(--text);
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.04);
        }

        .btn-hero-primary {
            background: var(--gold);
            color: #0f1115;
            padding: 16px 36px;
            font-size: 1.05rem;
            border-radius: 10px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-hero-primary:hover {
            background: #c9a227;
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(212, 175, 55, 0.4);
        }

        .btn-hero-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            padding: 16px 36px;
            font-size: 1.05rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-hero-outline:hover {
            border-color: var(--border-gold);
            color: var(--gold);
            background: var(--gold-dim);
        }

        /* ─── HERO ─────────────────────────────────────────────── */

        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 24px 80px;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .hero-bg-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 900px;
            height: 900px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.09) 0%, rgba(212, 175, 55, 0.04) 40%, transparent 70%);
            animation: pulse-bg 6s ease-in-out infinite;
        }

        .hero-bg-pulse-2 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1400px;
            height: 1400px;
            border-radius: 50%;
            background: radial-gradient(circle, transparent 30%, rgba(212, 175, 55, 0.025) 60%, transparent 75%);
            animation: pulse-bg 8s ease-in-out infinite reverse;
        }

        .hero-grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
        }

        @keyframes pulse-bg {
            0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.08); }
        }

        .hero-badge {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px 6px 8px;
            background: var(--gold-dim);
            border: 1px solid var(--border-gold);
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gold);
            margin-bottom: 32px;
            letter-spacing: 0.02em;
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-headline {
            position: relative;
            z-index: 1;
            font-size: clamp(2.8rem, 7vw, 5.2rem);
            font-weight: 900;
            line-height: 1.06;
            letter-spacing: -0.03em;
            margin-bottom: 24px;
            max-width: 900px;
        }

        .hero-headline-gradient {
            background: linear-gradient(135deg, #fff 30%, #d4af37 70%, #f0d060 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subheadline {
            position: relative;
            z-index: 1;
            font-size: clamp(1rem, 2.2vw, 1.25rem);
            color: var(--text-secondary);
            max-width: 560px;
            margin: 0 auto 40px;
            font-weight: 400;
            line-height: 1.65;
        }

        .hero-ctas {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 56px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .hero-code-block {
            position: relative;
            z-index: 1;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 24px;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 640px;
            width: 100%;
            text-align: left;
            margin: 0 auto 48px;
            overflow: hidden;
        }

        .hero-code-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), transparent);
            border-radius: 12px 12px 0 0;
        }

        .hero-code-dots {
            display: flex;
            gap: 6px;
            margin-bottom: 12px;
        }

        .hero-code-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-red { background: #ff5f57; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #28c840; }

        #typing-area {
            display: flex;
            align-items: center;
            min-height: 2.8em;
            flex-wrap: wrap;
            gap: 0;
        }

        #typing-output {
            color: var(--text-secondary);
        }

        .cursor-blink {
            display: inline-block;
            width: 2px;
            height: 1em;
            background: var(--gold);
            animation: cursor-blink 0.9s step-end infinite;
            vertical-align: text-bottom;
            margin-left: 1px;
        }

        @keyframes cursor-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .trust-bar {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .trust-check {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .trust-check svg {
            width: 9px;
            height: 9px;
            color: var(--gold);
        }

        /* ─── SECTION COMMON ───────────────────────────────────── */

        section {
            padding: 100px 0;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 16px;
            display: block;
        }

        .section-title {
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 800;
            letter-spacing: -0.025em;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 520px;
            line-height: 1.65;
        }

        /* ─── FEATURES ─────────────────────────────────────────── */

        .features {
            background: linear-gradient(180deg, var(--bg) 0%, #0c0e12 100%);
        }

        .features-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .features-header .section-subtitle {
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px 28px;
            transition: border-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-glow), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--border-gold);
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(212, 175, 55, 0.08);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--gold-dim);
            border: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--gold);
        }

        .feature-icon svg {
            width: 22px;
            height: 22px;
        }

        .feature-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text);
        }

        .feature-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* ─── CODE DEMO ────────────────────────────────────────── */

        .code-demo {
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }

        .code-demo::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -200px;
            transform: translateY(-50%);
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .code-demo-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
        }

        .code-demo-text .section-subtitle {
            margin-bottom: 36px;
        }

        .code-demo-benefit {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
        }

        .benefit-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--gold-dim);
            border: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
            color: var(--gold);
        }

        .benefit-icon svg {
            width: 15px;
            height: 15px;
        }

        .benefit-text strong {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .benefit-text span {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .code-window {
            background: #0a0c10;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.5);
        }

        .code-window-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: #0c0f14;
        }

        .code-window-dots {
            display: flex;
            gap: 6px;
        }

        .code-window-dots span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .code-window-title {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-family: 'SF Mono', 'Fira Code', monospace;
            margin-left: 8px;
        }

        .code-window-body {
            padding: 28px 24px;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.845rem;
            line-height: 1.85;
        }

        .code-line {
            display: block;
        }

        .code-line-empty {
            display: block;
            height: 0.85em;
        }

        .syn-comment { color: #4b5563; font-style: italic; }
        .syn-keyword { color: #c084fc; }
        .syn-func { color: #60a5fa; }
        .syn-string { color: #6ee7b7; }
        .syn-param { color: #93c5fd; }
        .syn-url { color: #d4af37; }
        .syn-operator { color: #9ca3af; }

        .code-changed-line {
            background: rgba(212, 175, 55, 0.06);
            border-left: 2px solid var(--gold);
            padding-left: 10px;
            margin-left: -2px;
            display: block;
        }

        /* ─── PRICING ──────────────────────────────────────────── */

        .pricing {
            background: linear-gradient(180deg, #0c0e12 0%, var(--bg) 100%);
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .pricing-header .section-subtitle {
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            align-items: start;
        }

        .pricing-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            position: relative;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .pricing-card.popular {
            border-color: var(--border-gold);
            background: linear-gradient(180deg, rgba(212, 175, 55, 0.04) 0%, var(--bg-card) 100%);
            transform: scale(1.04);
        }

        .pricing-card.popular:hover {
            transform: scale(1.04) translateY(-4px);
        }

        .pricing-card:not(.popular):hover {
            border-color: var(--border-gold);
            transform: translateY(-4px);
        }

        .popular-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gold);
            color: #0f1115;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 16px;
            border-radius: 100px;
            white-space: nowrap;
        }

        .pricing-tier {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .popular .pricing-tier {
            color: var(--gold);
        }

        .pricing-credits {
            font-size: 2.6rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 4px;
            color: var(--text);
        }

        .pricing-credits-unit {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-left: 4px;
        }

        .pricing-price {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-bottom: 28px;
            padding-bottom: 28px;
            border-bottom: 1px solid var(--border);
        }

        .pricing-price strong {
            color: var(--gold);
            font-weight: 700;
            font-size: 1.05rem;
        }

        .pricing-features-list {
            list-style: none;
            margin-bottom: 32px;
        }

        .pricing-features-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .pricing-features-list li svg {
            width: 15px;
            height: 15px;
            color: var(--gold);
            flex-shrink: 0;
        }

        .pricing-cta {
            width: 100%;
            text-align: center;
            padding: 13px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
        }

        .pricing-cta-gold {
            background: var(--gold);
            color: #0f1115;
        }

        .pricing-cta-gold:hover {
            background: #c9a227;
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.35);
        }

        .pricing-cta-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .pricing-cta-outline:hover {
            border-color: rgba(255,255,255,0.15);
            color: var(--text);
            background: rgba(255,255,255,0.03);
        }

        .pricing-free-cta {
            text-align: center;
            margin-top: 48px;
        }

        .pricing-free-cta a {
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            border-bottom: 1px solid transparent;
            transition: border-color 0.2s;
        }

        .pricing-free-cta a:hover {
            border-color: var(--gold);
        }

        .pricing-free-cta p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* ─── STATS ────────────────────────────────────────────── */

        .stats {
            padding: 80px 0;
            background: var(--bg);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .stat-item {
            background: var(--bg-card);
            padding: 40px 32px;
            text-align: center;
            transition: background 0.2s;
        }

        .stat-item:hover {
            background: var(--bg-secondary);
        }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─── FINAL CTA ────────────────────────────────────────── */

        .final-cta {
            padding: 120px 0;
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }

        .final-cta-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 50% 50%,
                    rgba(212, 175, 55, 0.09) 0%,
                    rgba(212, 175, 55, 0.04) 40%,
                    transparent 70%);
        }

        .final-cta-border-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-glow), transparent);
        }

        .final-cta-border-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-glow), transparent);
        }

        .final-cta-inner {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .final-cta-title {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 20px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .final-cta-sub {
            font-size: 1.05rem;
            color: var(--text-secondary);
            margin-bottom: 48px;
            max-width: 440px;
            margin-left: auto;
            margin-right: auto;
        }

        .final-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--gold);
            color: #0f1115;
            padding: 18px 48px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            letter-spacing: -0.01em;
        }

        .final-cta-btn:hover {
            background: #c9a227;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(212, 175, 55, 0.45);
        }

        .final-cta-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.2s;
        }

        .final-cta-btn:hover svg {
            transform: translateX(3px);
        }

        .final-cta-note {
            margin-top: 20px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        /* ─── FOOTER ───────────────────────────────────────────── */

        .footer {
            background: #0a0c10;
            border-top: 1px solid var(--border);
            padding: 60px 0 40px;
        }

        .footer-inner {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 52px;
        }

        .footer-brand-logo {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--gold);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .footer-brand-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
            max-width: 260px;
            margin-bottom: 24px;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social-link {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .footer-social-link:hover {
            border-color: var(--border-gold);
            color: var(--gold);
            background: var(--gold-dim);
        }

        .footer-social-link svg {
            width: 15px;
            height: 15px;
        }

        .footer-col-title {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 18px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 11px;
        }

        .footer-links a {
            font-size: 0.875rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--text);
        }

        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-copyright {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .footer-legal {
            display: flex;
            gap: 24px;
        }

        .footer-legal a {
            font-size: 0.82rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-legal a:hover {
            color: var(--text);
        }

        /* ─── RESPONSIVE ───────────────────────────────────────── */

        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 420px;
                margin: 0 auto;
            }

            .pricing-card.popular {
                transform: none;
            }

            .pricing-card.popular:hover {
                transform: translateY(-4px);
            }

            .code-demo-inner {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-inner {
                grid-template-columns: 1fr 1fr;
                gap: 36px;
            }
        }

        @media (max-width: 768px) {
            .navbar-links {
                display: none;
            }

            .hero-headline {
                font-size: clamp(2.2rem, 10vw, 3.5rem);
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-inner {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-ctas {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-hero-primary,
            .btn-hero-outline {
                text-align: center;
                justify-content: center;
            }

            section {
                padding: 72px 0;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-code-block {
                font-size: 0.75rem;
                padding: 14px 16px;
            }

            .trust-bar {
                gap: 16px;
            }
        }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M0T3YYQP7X"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-M0T3YYQP7X');
    </script>
</head>
<body>

    <!-- ─────────────── NAVBAR ─────────────────────────────── -->
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
                LLM Resayil
            </a>

            <ul class="navbar-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="/docs">Docs</a></li>
            </ul>

            <div class="navbar-cta">
                <a href="/login" class="btn btn-outline">Sign In</a>
                <a href="/register" class="btn btn-gold">Get Started Free</a>
            </div>
        </div>
    </nav>

    <!-- ─────────────── HERO ──────────────────────────────── -->
    <section class="hero" id="home">
        <div class="hero-bg">
            <div class="hero-grid-lines"></div>
            <div class="hero-bg-pulse"></div>
            <div class="hero-bg-pulse-2"></div>
        </div>

        <div class="hero-badge fade-in visible">
            <span class="hero-badge-dot"></span>
            Now live — GPU-powered inference in Kuwait
        </div>

        <h1 class="hero-headline fade-in visible">
            <span class="hero-headline-gradient">The Fastest LLM API<br>in the Middle East</span>
        </h1>

        <p class="hero-subheadline fade-in visible fade-in-delay-1">
            OpenAI-compatible. Pay per use. No subscriptions.<br>
            Drop in your existing code and start building.
        </p>

        <div class="hero-ctas fade-in visible fade-in-delay-2">
            <a href="/register" class="btn-hero-primary">
                Start Building Free
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="/docs" class="btn-hero-outline">View Docs</a>
        </div>

        <div class="hero-code-block fade-in fade-in-delay-3">
            <div class="hero-code-dots">
                <span class="dot-red"></span>
                <span class="dot-yellow"></span>
                <span class="dot-green"></span>
            </div>
            <div id="typing-area">
                <span id="typing-output"></span><span class="cursor-blink" id="cursor"></span>
            </div>
        </div>

        <div class="trust-bar fade-in fade-in-delay-4">
            <div class="trust-item">
                <span class="trust-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                OpenAI Compatible
            </div>
            <div class="trust-item">
                <span class="trust-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                Arabic Language
            </div>
            <div class="trust-item">
                <span class="trust-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                No Monthly Fees
            </div>
            <div class="trust-item">
                <span class="trust-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                GPU-Powered
            </div>
        </div>
    </section>

    <!-- ─────────────── FEATURES ──────────────────────────── -->
    <section class="features" id="features">
        <div class="container">
            <div class="features-header">
                <span class="section-label fade-in">Why Resayil</span>
                <h2 class="section-title fade-in">Everything you need to ship faster</h2>
                <p class="section-subtitle fade-in fade-in-delay-1">Built for developers who want production-grade LLM infrastructure without the complexity or the subscription lock-in.</p>
            </div>

            <div class="features-grid">

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </div>
                    <div class="feature-title">Drop-in OpenAI Replacement</div>
                    <div class="feature-desc">Change one line of code. Your existing OpenAI SDK, libraries, and integrations work without modification.</div>
                </div>

                <div class="feature-card fade-in fade-in-delay-1">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                        </svg>
                    </div>
                    <div class="feature-title">Pay Per Use</div>
                    <div class="feature-desc">Credits-based billing. Only pay for tokens you consume. Credits never expire. No idle costs, no surprises.</div>
                </div>

                <div class="feature-card fade-in fade-in-delay-2">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                        </svg>
                    </div>
                    <div class="feature-title">Arabic First</div>
                    <div class="feature-desc">Native Arabic language understanding and generation. Models fine-tuned for Gulf dialect, MSA, and code-switching.</div>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                    </div>
                    <div class="feature-title">Fast Inference</div>
                    <div class="feature-desc">Dedicated GPU servers with sub-500ms response times. Built for real-time applications and latency-sensitive workloads.</div>
                </div>

                <div class="feature-card fade-in fade-in-delay-1">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <div class="feature-title">50+ Models</div>
                    <div class="feature-desc">GPT-4 class models, code generation, embeddings, and specialized Arabic models — all under one unified API.</div>
                </div>

                <div class="feature-card fade-in fade-in-delay-2">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="feature-title">Secure by Default</div>
                    <div class="feature-desc">API key authentication with per-key rate limiting and usage tracking. Your data stays in the region.</div>
                </div>

            </div>
        </div>
    </section>

    <!-- ─────────────── CODE DEMO ─────────────────────────── -->
    <section class="code-demo">
        <div class="container">
            <div class="code-demo-inner">

                <div class="code-demo-text fade-in">
                    <span class="section-label">Integration</span>
                    <h2 class="section-title">One line.<br>Zero migration cost.</h2>
                    <p class="section-subtitle" style="margin-bottom: 36px;">
                        Works with every OpenAI SDK, LangChain, LlamaIndex, AutoGen, and every library that supports a custom base URL.
                    </p>

                    <div class="code-demo-benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="benefit-text">
                            <strong>No SDK changes</strong>
                            <span>Use the official openai Python or Node.js package as-is</span>
                        </div>
                    </div>

                    <div class="code-demo-benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="benefit-text">
                            <strong>Streaming supported</strong>
                            <span>Full SSE streaming for real-time completions</span>
                        </div>
                    </div>

                    <div class="code-demo-benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="benefit-text">
                            <strong>Lower latency</strong>
                            <span>Servers in Kuwait — drastically lower RTT for GCC users</span>
                        </div>
                    </div>
                </div>

                <div class="code-window fade-in fade-in-delay-2">
                    <div class="code-window-header">
                        <div class="code-window-dots">
                            <span class="dot-red"></span>
                            <span class="dot-yellow"></span>
                            <span class="dot-green"></span>
                        </div>
                        <span class="code-window-title">migration.py</span>
                    </div>
                    <div class="code-window-body">
                        <span class="code-line"><span class="syn-comment"># Before — standard OpenAI</span></span>
                        <span class="code-line"><span class="syn-keyword">from</span> openai <span class="syn-keyword">import</span> <span class="syn-func">OpenAI</span></span>
                        <span class="code-line-empty"></span>
                        <span class="code-line"><span class="syn-operator">client</span> = <span class="syn-func">OpenAI</span>(</span>
                        <span class="code-line">    <span class="syn-param">api_key</span>=<span class="syn-string">"sk-..."</span></span>
                        <span class="code-line">)</span>
                        <span class="code-line-empty"></span>
                        <span class="code-line-empty"></span>
                        <span class="code-line"><span class="syn-comment"># After — LLM Resayil (one line changed)</span></span>
                        <span class="code-line"><span class="syn-keyword">from</span> openai <span class="syn-keyword">import</span> <span class="syn-func">OpenAI</span></span>
                        <span class="code-line-empty"></span>
                        <span class="code-line"><span class="syn-operator">client</span> = <span class="syn-func">OpenAI</span>(</span>
                        <span class="code-line">    <span class="syn-param">api_key</span>=<span class="syn-string">"your-resayil-key"</span>,</span>
                        <span class="code-changed-line">    <span class="syn-param">base_url</span>=<span class="syn-url">"https://llm.resayil.io/api/v1"</span></span>
                        <span class="code-line">)</span>
                        <span class="code-line-empty"></span>
                        <span class="code-line"><span class="syn-comment"># Everything else stays exactly the same</span></span>
                        <span class="code-line"><span class="syn-operator">response</span> = client.chat.completions.<span class="syn-func">create</span>(</span>
                        <span class="code-line">    <span class="syn-param">model</span>=<span class="syn-string">"llama3.2"</span>,</span>
                        <span class="code-line">    <span class="syn-param">messages</span>=[{<span class="syn-string">"role"</span>: <span class="syn-string">"user"</span>, <span class="syn-string">"content"</span>: <span class="syn-string">"مرحبا"</span>}]</span>
                        <span class="code-line">)</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ─────────────── PRICING ───────────────────────────── -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="pricing-header">
                <span class="section-label fade-in">Pricing</span>
                <h2 class="section-title fade-in">Simple, transparent credits</h2>
                <p class="section-subtitle fade-in fade-in-delay-1">Buy credits once, use them whenever. No expiry. No monthly commitment. Scale as you grow.</p>
            </div>

            <div class="pricing-grid">

                <div class="pricing-card fade-in">
                    <div class="pricing-tier">Starter</div>
                    <div class="pricing-credits">5,000 <span class="pricing-credits-unit">credits</span></div>
                    <div class="pricing-price">
                        <strong>2 KWD</strong> &mdash; one-time top-up
                    </div>
                    <ul class="pricing-features-list">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            All models included
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Credits never expire
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            API key access
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Community support
                        </li>
                    </ul>
                    <a href="/register" class="pricing-cta pricing-cta-outline">Get Started</a>
                </div>

                <div class="pricing-card popular fade-in fade-in-delay-1">
                    <span class="popular-badge">Most Popular</span>
                    <div class="pricing-tier">Pro</div>
                    <div class="pricing-credits">15,000 <span class="pricing-credits-unit">credits</span></div>
                    <div class="pricing-price">
                        <strong>5 KWD</strong> &mdash; one-time top-up
                    </div>
                    <ul class="pricing-features-list">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            All models included
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Credits never expire
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Priority API access
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Usage analytics
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Email support
                        </li>
                    </ul>
                    <a href="/register" class="pricing-cta pricing-cta-gold">Get Started</a>
                </div>

                <div class="pricing-card fade-in fade-in-delay-2">
                    <div class="pricing-tier">Enterprise</div>
                    <div class="pricing-credits">50,000 <span class="pricing-credits-unit">credits</span></div>
                    <div class="pricing-price">
                        <strong>15 KWD</strong> &mdash; one-time top-up
                    </div>
                    <ul class="pricing-features-list">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            All models included
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Credits never expire
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Dedicated rate limits
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Advanced analytics
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Priority support
                        </li>
                    </ul>
                    <a href="/register" class="pricing-cta pricing-cta-outline">Get Started</a>
                </div>

            </div>

            <div class="pricing-free-cta fade-in">
                <p>New accounts receive 1,000 free credits to start.</p>
                <a href="/register">Start with 1,000 free credits &rarr;</a>
            </div>
        </div>
    </section>

    <!-- ─────────────── STATS ──────────────────────────────── -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid fade-in">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">API Calls Served</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime SLA</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">&lt;500ms</div>
                    <div class="stat-label">Avg Response Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Available Models</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─────────────── FINAL CTA ─────────────────────────── -->
    <section class="final-cta">
        <div class="final-cta-bg"></div>
        <div class="final-cta-border-top"></div>
        <div class="final-cta-border-bottom"></div>
        <div class="container">
            <div class="final-cta-inner fade-in">
                <h2 class="final-cta-title">
                    Ready to build with the best<br>
                    <span class="gold">LLM API in Kuwait?</span>
                </h2>
                <p class="final-cta-sub">
                    Join developers who are already building faster with regional infrastructure and OpenAI compatibility.
                </p>
                <a href="/register" class="final-cta-btn">
                    Create Free Account
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <p class="final-cta-note">No credit card required. 1,000 free credits on signup.</p>
            </div>
        </div>
    </section>

    <!-- ─────────────── FOOTER ────────────────────────────── -->
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">

                <div>
                    <a href="/" class="footer-brand-logo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                        LLM Resayil
                    </a>
                    <p class="footer-brand-desc">
                        The fastest OpenAI-compatible LLM API built for the Middle East. GPU-powered, Arabic-first, pay per use.
                    </p>
                    <div class="footer-social">
                        <a href="https://github.com/soudshoja/LLM-Resayil" class="footer-social-link" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="footer-social-link" target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="footer-col-title">Product</div>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="/docs">Documentation</a></li>
                        <li><a href="/register">Get Started</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Company</div>
                    <ul class="footer-links">
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="https://llm.resayil.io" target="_blank" rel="noopener noreferrer">Status</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Legal</div>
                    <ul class="footer-links">
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-of-service">Terms of Service</a></li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                <p class="footer-copyright">&copy; 2026 LLM Resayil. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="/privacy-policy">Privacy</a>
                    <a href="/terms-of-service">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ─────────────── SCRIPTS ───────────────────────────── -->
    <script>
        // ─── Navbar scroll effect ──────────────────────────
        (function () {
            var navbar = document.getElementById('navbar');
            function onScroll() {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
        })();

        // ─── Intersection Observer — fade-in on scroll ─────
        (function () {
            var elements = document.querySelectorAll('.fade-in:not(.visible)');
            if (!elements.length) return;

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            elements.forEach(function (el) {
                observer.observe(el);
            });
        })();

        // ─── Typing animation in hero code block ──────────
        (function () {
            var outputEl = document.getElementById('typing-output');
            if (!outputEl) return;

            // Lines: [text, colorClass]
            var lines = [
                ['# Drop-in OpenAI replacement', 'syn-comment'],
                ['openai.api_base = "https://llm.resayil.io/api/v1"', 'syn-url'],
            ];

            var lineIndex = 0;
            var charIndex = 0;
            var typingSpeed = 40;
            var pauseBetweenLines = 860;
            var pauseAfterAll = 2600;

            // Holds references to the created span elements
            var lineSpans = [];

            function reset() {
                // Remove all children safely
                while (outputEl.firstChild) {
                    outputEl.removeChild(outputEl.firstChild);
                }
                lineSpans = [];
                lineIndex = 0;
                charIndex = 0;
            }

            function typeChar() {
                if (lineIndex >= lines.length) {
                    setTimeout(function () {
                        reset();
                        typeChar();
                    }, pauseAfterAll);
                    return;
                }

                var lineData = lines[lineIndex];
                var text = lineData[0];
                var colorClass = lineData[1];

                // Create the span element for a new line
                if (charIndex === 0) {
                    if (lineIndex > 0) {
                        // Add a line break before second+ lines
                        outputEl.appendChild(document.createElement('br'));
                    }
                    var span = document.createElement('span');
                    span.className = colorClass;
                    outputEl.appendChild(span);
                    lineSpans[lineIndex] = span;
                }

                var currentSpan = lineSpans[lineIndex];
                if (charIndex < text.length) {
                    currentSpan.textContent += text[charIndex];
                    charIndex++;
                    var jitter = Math.random() * 18 - 9;
                    setTimeout(typeChar, typingSpeed + jitter);
                } else {
                    lineIndex++;
                    charIndex = 0;
                    setTimeout(typeChar, pauseBetweenLines);
                }
            }

            setTimeout(typeChar, 700);
        })();
    </script>

</body>
</html>
