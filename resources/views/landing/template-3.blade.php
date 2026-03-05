<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LLM Resayil — Your AI Infrastructure. Supercharged.</title>
    <meta name="description" content="OpenAI-compatible LLM API with pay-per-use pricing. 50+ AI models. Built for developers in the Arab world.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ─────────────────────────────────────────────
           RESET & BASE
        ───────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:           #0f1115;
            --bg-card:      rgba(19,22,29,0.82);
            --bg-card-solid:#13161d;
            --gold:         #d4af37;
            --gold-light:   #e8cc5a;
            --gold-dim:     rgba(212,175,55,0.15);
            --gold-glow:    rgba(212,175,55,0.25);
            --gold-border:  rgba(212,175,55,0.3);
            --purple:       #7c3aed;
            --purple-dim:   rgba(124,58,237,0.15);
            --purple-glow:  rgba(124,58,237,0.3);
            --border:       rgba(255,255,255,0.07);
            --border-hover: rgba(212,175,55,0.35);
            --text:         #f0f2f7;
            --text-secondary:#a1aab8;
            --text-muted:   #6b7280;
            --font:         'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font);
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        button { font-family: var(--font); cursor: pointer; border: none; background: none; }
        ul, ol { list-style: none; }
        img { max-width: 100%; display: block; }

        /* ─────────────────────────────────────────────
           GRID BACKGROUND PATTERN
        ───────────────────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ─────────────────────────────────────────────
           UTILITIES
        ───────────────────────────────────────────── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .text-gold { color: var(--gold); }
        .text-muted { color: var(--text-muted); }
        .text-gradient {
            background: linear-gradient(135deg, var(--gold) 0%, #fff 60%, var(--gold-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ─────────────────────────────────────────────
           GLASS MIXIN — shared base
        ───────────────────────────────────────────── */
        .glass {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
        }

        /* ─────────────────────────────────────────────
           BUTTONS
        ───────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.6rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0a0c12;
            box-shadow: 0 0 24px var(--gold-glow), 0 4px 16px rgba(0,0,0,0.3);
        }
        .btn-gold:hover {
            box-shadow: 0 0 40px rgba(212,175,55,0.45), 0 6px 24px rgba(0,0,0,0.4);
            transform: translateY(-2px);
            color: #0a0c12;
        }
        .btn-gold::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.18) 100%);
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border-hover);
            color: var(--text-secondary);
        }
        .btn-ghost:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-dim);
        }
        .btn-lg { padding: 0.9rem 2rem; font-size: 1.05rem; border-radius: 12px; }
        .btn-xl { padding: 1.1rem 2.8rem; font-size: 1.15rem; border-radius: 14px; }

        /* ─────────────────────────────────────────────
           NAVBAR
        ───────────────────────────────────────────── */
        .navbar-wrap {
            position: fixed;
            top: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 3rem);
            max-width: 1100px;
            z-index: 1000;
        }
        .navbar {
            background: rgba(15,17,21,0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 30px rgba(0,0,0,0.4), 0 0 0 1px rgba(212,175,55,0.04);
            transition: background 0.3s;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
        }
        .logo-dot {
            width: 9px;
            height: 9px;
            background: var(--gold);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--gold), 0 0 20px var(--gold-glow);
            animation: pulse-dot 2.5s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 8px var(--gold), 0 0 16px var(--gold-glow); }
            50%       { box-shadow: 0 0 14px var(--gold), 0 0 28px rgba(212,175,55,0.5); }
        }
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .navbar-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: color 0.2s;
        }
        .navbar-links a:hover { color: var(--text); }
        .navbar-cta { display: flex; align-items: center; gap: 0.75rem; }
        .navbar-cta .btn-ghost { padding: 0.55rem 1.2rem; font-size: 0.85rem; }
        .navbar-cta .btn-gold  { padding: 0.55rem 1.25rem; font-size: 0.85rem; border-radius: 9px; }

        /* ─────────────────────────────────────────────
           HERO
        ───────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10rem 1.5rem 5rem;
            position: relative;
            overflow: hidden;
        }

        /* Background orbs */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
        }
        .hero-orb-gold {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(212,175,55,0.18) 0%, transparent 70%);
            top: -100px;
            left: -150px;
            animation: float-orb 8s ease-in-out infinite;
        }
        .hero-orb-purple {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(124,58,237,0.2) 0%, transparent 70%);
            top: 50px;
            right: -100px;
            animation: float-orb 10s ease-in-out infinite reverse;
        }
        .hero-orb-gold-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
            bottom: 100px;
            right: 200px;
            animation: float-orb 12s ease-in-out infinite;
        }
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -40px) scale(1.05); }
            66%       { transform: translate(-20px, 30px) scale(0.96); }
        }

        /* Hero content */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(212,175,55,0.1);
            border: 1px solid rgba(212,175,55,0.3);
            color: var(--gold);
            padding: 0.45rem 1.1rem;
            border-radius: 100px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 2rem;
            animation: fade-up 0.7s ease both;
            animation-delay: 0.1s;
        }
        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 5.5rem);
            font-weight: 900;
            line-height: 1.07;
            letter-spacing: -0.035em;
            max-width: 900px;
            margin: 0 auto 1.5rem;
            animation: fade-up 0.7s ease both;
            animation-delay: 0.2s;
        }
        .hero-sub {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: var(--text-secondary);
            max-width: 580px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
            animation: fade-up 0.7s ease both;
            animation-delay: 0.3s;
        }
        .hero-cta-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
            animation: fade-up 0.7s ease both;
            animation-delay: 0.4s;
        }

        /* Stats card */
        .hero-stats-card {
            display: inline-flex;
            align-items: center;
            gap: 0;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.35), 0 0 0 1px rgba(212,175,55,0.06);
            animation: fade-up 0.7s ease both, float-card 6s ease-in-out 1s infinite;
            animation-delay: 0.55s, 1s;
        }
        @keyframes float-card {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-6px); }
        }
        .hero-stat {
            padding: 1.1rem 1.8rem;
            text-align: center;
            position: relative;
        }
        .hero-stat + .hero-stat::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background: var(--border);
        }
        .hero-stat-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .hero-stat-label {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ─────────────────────────────────────────────
           FADE-UP ANIMATION
        ───────────────────────────────────────────── */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─────────────────────────────────────────────
           TECH BAR / MARQUEE
        ───────────────────────────────────────────── */
        .tech-bar {
            padding: 1.5rem 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: rgba(19,22,29,0.4);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .tech-bar::before,
        .tech-bar::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 140px;
            z-index: 2;
            pointer-events: none;
        }
        .tech-bar::before { left: 0;  background: linear-gradient(to right, var(--bg), transparent); }
        .tech-bar::after  { right: 0; background: linear-gradient(to left,  var(--bg), transparent); }
        .marquee-track {
            display: flex;
            gap: 0;
            width: max-content;
            animation: marquee 28s linear infinite;
        }
        .tech-bar:hover .marquee-track { animation-play-state: paused; }
        @keyframes marquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0 2.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
            transition: color 0.2s;
        }
        .marquee-dot {
            width: 5px;
            height: 5px;
            background: var(--gold);
            border-radius: 50%;
            opacity: 0.5;
        }

        /* ─────────────────────────────────────────────
           SECTION BASE
        ───────────────────────────────────────────── */
        .section {
            padding: 6rem 1.5rem;
            position: relative;
            z-index: 1;
        }
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-label::before {
            content: '';
            display: block;
            width: 20px;
            height: 1px;
            background: var(--gold);
        }
        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 1rem;
        }
        .section-sub {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 560px;
            line-height: 1.75;
        }
        .section-header { margin-bottom: 3.5rem; }
        .section-header.center { text-align: center; }
        .section-header.center .section-label { justify-content: center; }
        .section-header.center .section-label::before { display: none; }
        .section-header.center .section-sub { margin: 0 auto; }

        /* ─────────────────────────────────────────────
           BENTO FEATURES GRID
        ───────────────────────────────────────────── */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-template-rows: auto;
            gap: 1.25rem;
        }

        /* Card base */
        .bento-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.3s;
        }
        .bento-card:hover {
            border-color: var(--border-hover);
            box-shadow: 0 0 0 1px rgba(212,175,55,0.12), 0 16px 48px rgba(0,0,0,0.4);
            transform: translateY(-3px);
        }
        .bento-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(212,175,55,0.03) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Grid placement */
        .bento-span-3 { grid-column: span 3; }
        .bento-span-2 { grid-column: span 2; }
        .bento-span-full { grid-column: span 6; }
        .bento-span-1 { grid-column: span 1; }
        .bento-span-4 { grid-column: span 4; }

        /* Card label */
        .bento-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.6rem;
        }
        .bento-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
            line-height: 1.3;
        }
        .bento-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.65;
        }

        /* Icon circle */
        .bento-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        .bento-icon-gold   { background: rgba(212,175,55,0.15); color: var(--gold); }
        .bento-icon-purple { background: rgba(124,58,237,0.15); color: #a78bfa; }
        .bento-icon-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .bento-icon-green  { background: rgba(34,197,94,0.15);  color: #4ade80; }

        /* Code snippet in bento */
        .bento-code {
            background: rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            line-height: 1.8;
            color: #c9d1d9;
            margin-top: 1.25rem;
            overflow-x: auto;
        }
        .code-comment   { color: #4b5563; }
        .code-string    { color: #86efac; }
        .code-key       { color: #93c5fd; }
        .code-changed   { color: var(--gold); font-weight: 700; }
        .code-diff-add  { color: #86efac; }
        .code-diff-line { display: flex; gap: 0.5rem; align-items: baseline; }
        .code-diff-mark { color: var(--gold); font-weight: 700; width: 14px; flex-shrink: 0; }

        /* Big number stat */
        .bento-big-num {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            margin: 0.5rem 0 0.25rem;
        }
        .bento-big-num.gold { color: var(--gold); text-shadow: 0 0 30px var(--gold-glow); }

        /* Credit counter animation */
        .credit-counter {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .credit-bar {
            flex: 1;
            height: 6px;
            background: rgba(255,255,255,0.07);
            border-radius: 3px;
            overflow: hidden;
        }
        .credit-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 3px;
            width: 68%;
            animation: bar-grow 2s ease-out both;
            animation-delay: 1s;
        }
        @keyframes bar-grow {
            from { width: 0; }
            to   { width: 68%; }
        }
        .credit-badge {
            background: rgba(212,175,55,0.12);
            border: 1px solid rgba(212,175,55,0.25);
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            white-space: nowrap;
        }

        /* Arabic text display */
        .bento-arabic {
            direction: rtl;
            text-align: right;
            font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.7;
            margin-top: 0.75rem;
            background: linear-gradient(135deg, rgba(212,175,55,0.1), rgba(124,58,237,0.1));
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }
        .bento-arabic-sub {
            direction: rtl;
            text-align: right;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.4rem;
        }

        /* Mini model cards inside bento */
        .mini-model-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
            margin-top: 1.25rem;
        }
        .mini-model-chip {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.55rem 0.7rem;
            font-size: 0.73rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-align: center;
            transition: all 0.2s;
        }
        .mini-model-chip:hover {
            border-color: var(--border-hover);
            color: var(--gold);
            background: var(--gold-dim);
        }

        /* Glow ring on fast card */
        .glow-ring {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212,175,55,0.12) 0%, transparent 65%);
            pointer-events: none;
        }

        /* ─────────────────────────────────────────────
           PRICING
        ───────────────────────────────────────────── */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            align-items: stretch;
        }
        .pricing-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 2.25rem 2rem;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: border-color 0.35s, box-shadow 0.35s, transform 0.3s;
        }
        .pricing-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-4px);
        }

        /* Popular card with shimmer */
        .pricing-card.popular {
            border-color: var(--gold-border);
            box-shadow: 0 0 0 1px rgba(212,175,55,0.15), 0 12px 60px rgba(212,175,55,0.12);
            overflow: hidden;
        }
        .pricing-card.popular::after {
            content: '';
            position: absolute;
            top: -100%;
            left: -60%;
            width: 60%;
            height: 300%;
            background: linear-gradient(
                115deg,
                transparent 30%,
                rgba(212,175,55,0.08) 50%,
                transparent 70%
            );
            animation: shimmer 3.5s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%   { transform: translateX(-100%) rotate(0deg); }
            100% { transform: translateX(400%) rotate(0deg); }
        }

        .pricing-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0a0c12;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.3rem 1rem;
            border-radius: 100px;
            white-space: nowrap;
        }
        .pricing-tier {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.85rem;
        }
        .pricing-price {
            font-size: 3.25rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .pricing-price sup {
            font-size: 1.5rem;
            font-weight: 700;
            vertical-align: super;
        }
        .pricing-period {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }
        .pricing-credits {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gold-dim);
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 8px;
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 1.75rem;
        }
        .pricing-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        .pricing-features {
            flex: 1;
            margin-bottom: 2rem;
        }
        .pricing-feature {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            padding: 0.45rem 0;
        }
        .pricing-feature svg { flex-shrink: 0; color: var(--gold); }
        .pricing-feature.muted svg { color: var(--text-muted); }
        .pricing-feature.muted { color: var(--text-muted); }
        .pricing-cta {
            display: block;
            width: 100%;
            padding: 0.85rem 1.5rem;
            border-radius: 11px;
            font-weight: 700;
            font-size: 0.9rem;
            text-align: center;
            transition: all 0.25s;
        }
        .pricing-cta-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0a0c12;
            box-shadow: 0 4px 20px var(--gold-glow);
        }
        .pricing-cta-gold:hover {
            box-shadow: 0 6px 30px rgba(212,175,55,0.4);
            transform: translateY(-1px);
            color: #0a0c12;
        }
        .pricing-cta-outline {
            border: 1px solid var(--border-hover);
            color: var(--gold);
            background: transparent;
        }
        .pricing-cta-outline:hover {
            background: var(--gold-dim);
        }

        /* ─────────────────────────────────────────────
           TESTIMONIALS
        ───────────────────────────────────────────── */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .testimonial-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            transition: border-color 0.3s, transform 0.3s;
        }
        .testimonial-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-3px);
        }
        .testimonial-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1.1rem;
            color: var(--gold);
        }
        .testimonial-text {
            font-size: 0.93rem;
            color: var(--text-secondary);
            line-height: 1.75;
            margin-bottom: 1.5rem;
            font-style: italic;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .testimonial-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .av-gold   { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0c12; }
        .av-purple { background: linear-gradient(135deg, var(--purple), #9d5cf6); color: #fff; }
        .av-blue   { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #fff; }
        .testimonial-name {
            font-weight: 700;
            font-size: 0.9rem;
        }
        .testimonial-role {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* ─────────────────────────────────────────────
           ARABIC SHOWCASE SECTION
        ───────────────────────────────────────────── */
        .arabic-section {
            background: rgba(19,22,29,0.5);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        .arabic-section::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -150px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(124,58,237,0.1) 0%, transparent 65%);
            pointer-events: none;
        }
        .arabic-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .arabic-left-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--purple);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .arabic-headline {
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .arabic-arabic-headline {
            direction: rtl;
            text-align: right;
            font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
            font-size: clamp(1.5rem, 3.5vw, 2.2rem);
            font-weight: 800;
            color: var(--gold);
            line-height: 1.5;
            margin-bottom: 1.25rem;
            text-shadow: 0 0 30px var(--gold-glow);
        }
        .arabic-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1.5rem;
        }
        .arabic-tag {
            background: rgba(124,58,237,0.12);
            border: 1px solid rgba(124,58,237,0.25);
            color: #a78bfa;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
        }
        .arabic-code-panel {
            background: rgba(0,0,0,0.5);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .arabic-code-topbar {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        .code-dot { width: 10px; height: 10px; border-radius: 50%; }
        .code-dot-red    { background: #ff5f57; }
        .code-dot-yellow { background: #febc2e; }
        .code-dot-green  { background: #28c840; }
        .arabic-code-topbar-label {
            margin-left: auto;
            font-size: 0.72rem;
            color: var(--text-muted);
            font-family: monospace;
        }
        .arabic-code-body {
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            line-height: 1.85;
            color: #c9d1d9;
        }
        .json-key    { color: #79c0ff; }
        .json-string { color: #a5d6a7; }
        .json-num    { color: #f8a261; }
        .json-bool   { color: #d2a8ff; }
        .json-ar     { color: var(--gold); direction: rtl; unicode-bidi: embed; }

        /* ─────────────────────────────────────────────
           FINAL CTA SECTION
        ───────────────────────────────────────────── */
        .final-cta-section {
            padding: 7rem 1.5rem;
            text-align: center;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        .final-cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 80%, rgba(212,175,55,0.07) 0%, transparent 65%);
            pointer-events: none;
        }
        .final-cta-box {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            padding: 4rem 3rem;
            border-radius: 28px;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        /* Animated gradient border */
        .final-cta-box::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 30px;
            background: linear-gradient(
                90deg,
                var(--gold),
                var(--purple),
                #3b82f6,
                var(--gold)
            );
            background-size: 300% 100%;
            animation: border-flow 4s linear infinite;
            z-index: -1;
        }
        .final-cta-box::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            background: var(--bg-card);
            z-index: -1;
        }
        @keyframes border-flow {
            0%   { background-position: 0% 50%; }
            100% { background-position: 300% 50%; }
        }
        .final-cta-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.25rem;
        }
        .final-cta-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 1rem;
        }
        .final-cta-sub {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 2.25rem;
            line-height: 1.7;
        }
        .final-cta-perks {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.25rem;
        }
        .final-perk {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            color: var(--text-muted);
        }
        .final-perk svg { color: var(--gold); }

        /* ─────────────────────────────────────────────
           FOOTER
        ───────────────────────────────────────────── */
        .site-footer {
            border-top: 1px solid var(--border);
            padding: 3rem 1.5rem 2rem;
            position: relative;
            z-index: 1;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-top {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid var(--border);
        }
        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
            margin-bottom: 0.85rem;
        }
        .footer-brand-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 240px;
            margin-bottom: 1.25rem;
        }
        .footer-socials {
            display: flex;
            gap: 0.75rem;
        }
        .footer-social-link {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .footer-social-link:hover {
            border-color: var(--gold-border);
            color: var(--gold);
            background: var(--gold-dim);
        }
        .footer-col-title {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text);
            margin-bottom: 1.1rem;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }
        .footer-links a {
            font-size: 0.85rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--gold); }
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-copy {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }
        .footer-legal a {
            font-size: 0.8rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }
        .footer-legal a:hover { color: var(--gold); }

        /* ─────────────────────────────────────────────
           RESPONSIVE
        ───────────────────────────────────────────── */
        @media (max-width: 1100px) {
            .bento-span-3 { grid-column: span 6; }
            .bento-span-4 { grid-column: span 6; }
            .bento-span-2 { grid-column: span 3; }
            .bento-span-1 { grid-column: span 3; }
            .mini-model-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .navbar-links { display: none; }
            .pricing-grid { grid-template-columns: 1fr; max-width: 420px; margin: 0 auto; }
            .testimonials-grid { grid-template-columns: 1fr; max-width: 500px; margin: 0 auto; }
            .arabic-split { grid-template-columns: 1fr; gap: 2.5rem; }
            .footer-top { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        @media (max-width: 700px) {
            .bento-span-2 { grid-column: span 6; }
            .bento-span-1 { grid-column: span 6; }
            .bento-grid { gap: 0.85rem; }
            .hero { padding: 9rem 1rem 4rem; }
            .hero-stats-card { flex-direction: column; }
            .hero-stat + .hero-stat::before { width: 60%; height: 1px; left: 20%; top: 0; }
            .final-cta-box { padding: 2.5rem 1.5rem; }
            .footer-top { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .footer-legal { justify-content: center; }
            .section { padding: 4rem 1rem; }
            .mini-model-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .hero-cta-row { flex-direction: column; align-items: center; }
            .btn-lg, .btn-xl { width: 100%; justify-content: center; }
            .navbar-wrap { width: calc(100% - 1.5rem); top: 0.75rem; }
            .navbar { border-radius: 12px; padding: 0.6rem 1rem; }
            .navbar-cta .btn-ghost { display: none; }
        }
    </style>
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
</head>
<body>

<!-- ═══════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════ -->
<div class="navbar-wrap">
    <nav class="navbar">
        <a href="/" class="navbar-logo" style="color:var(--text);">
            <div class="logo-dot"></div>
            LLM Resayil
        </a>
        <div class="navbar-links">
            <a href="#features">Features</a>
            <a href="#models">Models</a>
            <a href="#pricing">Pricing</a>
            <a href="/docs">Docs</a>
        </div>
        <div class="navbar-cta">
            <a href="/login" class="btn btn-ghost">Sign In</a>
            <a href="/register" class="btn btn-gold">Start Free
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </nav>
</div>

<!-- ═══════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════ -->
<section class="hero">
    <!-- Glowing orbs -->
    <div class="hero-orb hero-orb-gold"></div>
    <div class="hero-orb hero-orb-purple"></div>
    <div class="hero-orb hero-orb-gold-2"></div>

    <div class="container" style="display:flex;flex-direction:column;align-items:center;">
        <!-- Badge -->
        <div class="hero-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            Now with 50+ AI Models
        </div>

        <!-- Headline -->
        <h1>Your AI Infrastructure.<br><span class="text-gradient">Supercharged.</span></h1>

        <!-- Subheadline -->
        <p class="hero-sub">
            OpenAI-compatible LLM API with pay-per-use pricing.<br>
            Built for developers in the Arab world.
        </p>

        <!-- CTAs -->
        <div class="hero-cta-row">
            <a href="/register" class="btn btn-gold btn-lg">
                Get Started — It's Free
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#models" class="btn btn-ghost btn-lg">
                See the Models
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>

        <!-- Floating stats card -->
        <div class="hero-stats-card">
            <div class="hero-stat">
                <div class="hero-stat-value">1,247</div>
                <div class="hero-stat-label">Requests / min</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">47ms</div>
                <div class="hero-stat-label">Avg latency</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">99.9%</div>
                <div class="hero-stat-label">Uptime SLA</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">50+</div>
                <div class="hero-stat-label">AI Models</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     TECH BAR / MARQUEE
═══════════════════════════════════════════════════ -->
<div class="tech-bar">
    <div class="marquee-track">
        <!-- First copy -->
        <div class="marquee-item"><span class="marquee-dot"></span>OpenAI SDK</div>
        <div class="marquee-item"><span class="marquee-dot"></span>LangChain</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Python</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Node.js</div>
        <div class="marquee-item"><span class="marquee-dot"></span>LlamaIndex</div>
        <div class="marquee-item"><span class="marquee-dot"></span>AutoGen</div>
        <div class="marquee-item"><span class="marquee-dot"></span>REST API</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Streaming SSE</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Function Calling</div>
        <div class="marquee-item"><span class="marquee-dot"></span>CrewAI</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Vercel AI SDK</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Spring AI</div>
        <!-- Second copy (mirror for seamless loop) -->
        <div class="marquee-item"><span class="marquee-dot"></span>OpenAI SDK</div>
        <div class="marquee-item"><span class="marquee-dot"></span>LangChain</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Python</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Node.js</div>
        <div class="marquee-item"><span class="marquee-dot"></span>LlamaIndex</div>
        <div class="marquee-item"><span class="marquee-dot"></span>AutoGen</div>
        <div class="marquee-item"><span class="marquee-dot"></span>REST API</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Streaming SSE</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Function Calling</div>
        <div class="marquee-item"><span class="marquee-dot"></span>CrewAI</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Vercel AI SDK</div>
        <div class="marquee-item"><span class="marquee-dot"></span>Spring AI</div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     FEATURES — BENTO GRID
═══════════════════════════════════════════════════ -->
<section class="section" id="features">
    <div class="container">
        <div class="section-header reveal">
            <div class="section-label">Why Resayil</div>
            <h2 class="section-title">Everything you need.<br>Nothing you don't.</h2>
            <p class="section-sub">Drop-in OpenAI replacement with local GPU power, pay-per-use credits, and native Arabic support.</p>
        </div>

        <div class="bento-grid">

            <!-- Card 1: Drop-in replacement (span 3) -->
            <div class="bento-card bento-span-3 reveal">
                <div class="bento-icon bento-icon-gold">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="bento-label">Drop-in Replacement</div>
                <div class="bento-title">One line change. That's it.</div>
                <div class="bento-desc">Switch from OpenAI to Resayil by changing a single URL. Every SDK, every library — works instantly.</div>
                <div class="bento-code">
<div class="code-diff-line"><span class="code-diff-mark" style="color:#ef4444;">-</span><span><span class="code-key">base_url</span>=<span class="code-string" style="text-decoration:line-through;opacity:0.5;">"https://api.openai.com/v1"</span></span></div>
<div class="code-diff-line"><span class="code-diff-mark code-diff-add">+</span><span><span class="code-key">base_url</span>=<span class="code-changed">"https://llm.resayil.io/api/v1"</span></span></div>
<div class="code-diff-line"><span class="code-diff-mark" style="color:transparent;">·</span><span><span class="code-key">api_key</span>=<span class="code-string">"sk-resayil-your-api-key"</span></span></div>
                </div>
            </div>

            <!-- Card 2: Pay per use (span 3) -->
            <div class="bento-card bento-span-3 reveal" style="transition-delay:0.1s;">
                <div class="bento-icon bento-icon-green">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="bento-label">Transparent Pricing</div>
                <div class="bento-title">Pay per token.<br>No surprises.</div>
                <div class="bento-desc">Credits-based system. Know your costs upfront. Start with 1,000 free credits — no card required.</div>
                <div class="credit-counter">
                    <div style="font-size:0.82rem;color:var(--text-muted);white-space:nowrap;">680 / 1000 credits</div>
                    <div class="credit-bar"><div class="credit-bar-fill"></div></div>
                    <div class="credit-badge">68%</div>
                </div>
                <div style="margin-top:1rem;display:flex;gap:0.5rem;flex-wrap:wrap;">
                    <span style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#4ade80;font-size:0.75rem;font-weight:600;padding:0.3rem 0.7rem;border-radius:6px;">No credit card</span>
                    <span style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);color:#60a5fa;font-size:0.75rem;font-weight:600;padding:0.3rem 0.7rem;border-radius:6px;">Cancel anytime</span>
                    <span style="background:var(--gold-dim);border:1px solid rgba(212,175,55,0.2);color:var(--gold);font-size:0.75rem;font-weight:600;padding:0.3rem 0.7rem;border-radius:6px;">Top up anytime</span>
                </div>
            </div>

            <!-- Card 3: Fast (span 1 — tall number) -->
            <div class="bento-card bento-span-2 reveal" style="transition-delay:0.15s;min-height:220px;">
                <div class="glow-ring"></div>
                <div class="bento-icon bento-icon-gold">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <div class="bento-label">Speed</div>
                <div class="bento-big-num gold">&lt;500<span style="font-size:1.5rem;">ms</span></div>
                <div class="bento-desc" style="margin-top:0.5rem;">Average time-to-first-token. Streaming always on.</div>
            </div>

            <!-- Card 4: Secure (span 1) -->
            <div class="bento-card bento-span-2 reveal" style="transition-delay:0.2s;min-height:220px;">
                <div class="bento-icon bento-icon-purple">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div class="bento-label">Security</div>
                <div class="bento-title">API keys.<br>Scoped access.</div>
                <div class="bento-desc">Generate multiple API keys per account. Revoke instantly. Your data stays yours.</div>
            </div>

            <!-- Card 5: Arabic (span 2) -->
            <div class="bento-card bento-span-2 reveal" style="transition-delay:0.25s;">
                <div class="bento-icon bento-icon-purple">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                </div>
                <div class="bento-label">Arabic Native</div>
                <div class="bento-title">Built for Arabic.</div>
                <div class="bento-arabic">مرحبا! كيف يمكنني مساعدتك اليوم؟</div>
                <div class="bento-arabic-sub">Native RTL support. Arabic-first models.</div>
            </div>

            <!-- Card 6: 50+ Models (span 4) -->
            <div class="bento-card bento-span-4 reveal" style="transition-delay:0.3s;" id="models">
                <div class="bento-label">Model Library</div>
                <div class="bento-title">50+ Models. One API.</div>
                <div class="bento-desc">General chat, code generation, vision, reasoning — access all categories through one unified endpoint.</div>
                <div class="mini-model-grid" style="grid-template-columns:repeat(4,1fr);">
                    <div class="mini-model-chip">Llama 3.2</div>
                    <div class="mini-model-chip">DeepSeek V3</div>
                    <div class="mini-model-chip">Mistral 24B</div>
                    <div class="mini-model-chip">Qwen 2.5</div>
                    <div class="mini-model-chip">Gemma 3</div>
                    <div class="mini-model-chip">Phi-4</div>
                    <div class="mini-model-chip">Codestral</div>
                    <div class="mini-model-chip">+43 more</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     PRICING
═══════════════════════════════════════════════════ -->
<section class="section" id="pricing" style="background:rgba(19,22,29,0.3);border-top:1px solid var(--border);">
    <div class="container">
        <div class="section-header center reveal">
            <div class="section-label">Pricing</div>
            <h2 class="section-title">Simple, transparent <span class="text-gradient-gold">pricing</span></h2>
            <p class="section-sub">Start free. Scale as you grow. No hidden fees, no locked-in commitments.</p>
        </div>

        <div class="pricing-grid reveal">

            <!-- Starter -->
            <div class="pricing-card">
                <div class="pricing-tier">Starter</div>
                <div class="pricing-price"><sup>KWD</sup>0</div>
                <div class="pricing-period">Free trial — 7 days</div>
                <div class="pricing-credits">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    1,000 credits included
                </div>
                <hr class="pricing-divider">
                <div class="pricing-features">
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Full API access
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        1 API key
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        10 requests / minute
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Compact models
                    </div>
                    <div class="pricing-feature muted">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Frontier models
                    </div>
                </div>
                <a href="/register" class="pricing-cta pricing-cta-outline">Start Free Trial</a>
            </div>

            <!-- Basic — Most Popular -->
            <div class="pricing-card popular">
                <div class="pricing-badge">Most Popular</div>
                <div class="pricing-tier">Basic</div>
                <div class="pricing-price"><sup>KWD</sup>2<span style="font-size:1.5rem;font-weight:600;color:var(--text-muted);">/mo</span></div>
                <div class="pricing-period">Billed monthly</div>
                <div class="pricing-credits">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    5,000 credits / month
                </div>
                <hr class="pricing-divider">
                <div class="pricing-features">
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Full API access
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        2 API keys
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        30 requests / minute
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        All model sizes
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Credit top-ups available
                    </div>
                </div>
                <a href="/register" class="pricing-cta pricing-cta-gold">Start Free Trial</a>
            </div>

            <!-- Pro -->
            <div class="pricing-card">
                <div class="pricing-tier">Pro</div>
                <div class="pricing-price"><sup>KWD</sup>5<span style="font-size:1.5rem;font-weight:600;color:var(--text-muted);">/mo</span></div>
                <div class="pricing-period">Billed monthly</div>
                <div class="pricing-credits">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    15,000 credits / month
                </div>
                <hr class="pricing-divider">
                <div class="pricing-features">
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Everything in Basic
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        5 API keys
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        60 requests / minute
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Priority access
                    </div>
                    <div class="pricing-feature">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Usage analytics
                    </div>
                </div>
                <a href="/register" class="pricing-cta pricing-cta-outline">Start Free Trial</a>
            </div>

        </div>

        <p style="text-align:center;font-size:0.8rem;color:var(--text-muted);margin-top:1.75rem;">
            Start with a free trial — no credit card required. Add credits anytime via MyFatoorah.
        </p>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     TESTIMONIALS
═══════════════════════════════════════════════════ -->
<section class="section">
    <div class="container">
        <div class="section-header center reveal">
            <div class="section-label">Social Proof</div>
            <h2 class="section-title">Developers <span class="text-gradient-gold">love it</span></h2>
            <p class="section-sub">Teams across the Arab world are building production apps on Resayil today.</p>
        </div>

        <div class="testimonials-grid">

            <div class="testimonial-card reveal">
                <div class="testimonial-stars">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <p class="testimonial-text">"Switched from OpenAI to Resayil in literally 5 minutes. Changed one line of code — everything else just worked. The pay-per-use model saves us hundreds compared to a fixed monthly plan."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-gold">AR</div>
                    <div>
                        <div class="testimonial-name">Ahmed Al-Rashid</div>
                        <div class="testimonial-role">Backend Engineer, Riyadh</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card reveal" style="transition-delay:0.1s;">
                <div class="testimonial-stars">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <p class="testimonial-text">"The Arabic language support is genuinely good. I'm building an Arabic customer service chatbot and the response quality in Arabic is impressive. Finally an LLM API built for our region."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-purple">SH</div>
                    <div>
                        <div class="testimonial-name">Sara Hassan</div>
                        <div class="testimonial-role">AI Developer, Kuwait City</div>
                    </div>
                </div>
            </div>

            <div class="testimonial-card reveal" style="transition-delay:0.2s;">
                <div class="testimonial-stars">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <p class="testimonial-text">"Our LangChain pipeline worked the moment we updated the base URL. The streaming latency is excellent and costs are predictable. We cut our AI budget by 60% in the first month."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-blue">KA</div>
                    <div>
                        <div class="testimonial-name">Khalid Al-Anezi</div>
                        <div class="testimonial-role">CTO, Dubai Startup</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     ARABIC SHOWCASE
═══════════════════════════════════════════════════ -->
<section class="section arabic-section">
    <div class="container">
        <div class="arabic-split">

            <!-- Left: Copy -->
            <div class="reveal">
                <div class="arabic-left-label">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    Arabic-First
                </div>
                <div class="arabic-headline">Built for<br><span class="text-gradient-gold">Arabic Developers</span></div>
                <div class="arabic-arabic-headline">مرحبا بك في مستقبل<br>الذكاء الاصطناعي</div>
                <p style="font-size:0.95rem;color:var(--text-secondary);line-height:1.75;margin-bottom:1.25rem;">
                    Our platform was designed from the ground up with Arabic developers in mind. Native RTL rendering, Arabic-capable models, and billing in KWD.
                </p>
                <div class="arabic-tags">
                    <span class="arabic-tag">RTL Support</span>
                    <span class="arabic-tag">Arabic Models</span>
                    <span class="arabic-tag">KWD Billing</span>
                    <span class="arabic-tag">Kuwait-Based</span>
                    <span class="arabic-tag">Arabic UI</span>
                </div>
            </div>

            <!-- Right: Code panel -->
            <div class="arabic-code-panel reveal" style="transition-delay:0.15s;">
                <div class="arabic-code-topbar">
                    <span class="code-dot code-dot-red"></span>
                    <span class="code-dot code-dot-yellow"></span>
                    <span class="code-dot code-dot-green"></span>
                    <span class="arabic-code-topbar-label">response.json</span>
                </div>
                <div class="arabic-code-body">
<span style="color:var(--text-muted);">{</span><br>
&nbsp;&nbsp;<span class="json-key">"id"</span>: <span class="json-string">"chatcmpl-r7xa9"</span>,<br>
&nbsp;&nbsp;<span class="json-key">"model"</span>: <span class="json-string">"qwen2.5:7b"</span>,<br>
&nbsp;&nbsp;<span class="json-key">"choices"</span>: [<span style="color:var(--text-muted);">{</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"message"</span>: <span style="color:var(--text-muted);">{</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"role"</span>: <span class="json-string">"assistant"</span>,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"content"</span>: <span class="json-ar">"مرحباً! يمكنني مساعدتك في تطوير تطبيقات الذكاء الاصطناعي."</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:var(--text-muted);">}</span><br>
&nbsp;&nbsp;<span style="color:var(--text-muted);">}]</span>,<br>
&nbsp;&nbsp;<span class="json-key">"usage"</span>: <span style="color:var(--text-muted);">{</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"prompt_tokens"</span>: <span class="json-num">28</span>,<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"completion_tokens"</span>: <span class="json-num">19</span>,<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="json-key">"total_tokens"</span>: <span class="json-num">47</span><br>
&nbsp;&nbsp;<span style="color:var(--text-muted);">}</span><br>
<span style="color:var(--text-muted);">}</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     FINAL CTA
═══════════════════════════════════════════════════ -->
<section class="final-cta-section">
    <div class="final-cta-box reveal">
        <div class="final-cta-eyebrow">Join the revolution</div>
        <h2 class="final-cta-title">Join the AI revolution<br>in Kuwait.</h2>
        <p class="final-cta-sub">
            Start with 1,000 free credits. No credit card required. Cancel anytime.<br>
            Your entire AI stack, from development to production.
        </p>
        <a href="/register" class="btn btn-gold btn-xl" style="margin-bottom:1.75rem;">
            Create Your Free Account
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <div class="final-cta-perks">
            <div class="final-perk">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                1,000 free credits
            </div>
            <div class="final-perk">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                No credit card
            </div>
            <div class="final-perk">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Cancel anytime
            </div>
            <div class="final-perk">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Live in 2 minutes
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-top">

            <!-- Brand col -->
            <div>
                <div class="footer-brand-logo">
                    <div class="logo-dot"></div>
                    <span style="background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">LLM Resayil</span>
                </div>
                <p class="footer-brand-desc">OpenAI-compatible LLM API with pay-per-use pricing. Built for developers in the Arab world.</p>
                <div class="footer-socials">
                    <!-- GitHub -->
                    <a href="#" class="footer-social-link" aria-label="GitHub">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                    </a>
                    <!-- Twitter/X -->
                    <a href="#" class="footer-social-link" aria-label="Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <!-- LinkedIn -->
                    <a href="#" class="footer-social-link" aria-label="LinkedIn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Product col -->
            <div>
                <div class="footer-col-title">Product</div>
                <div class="footer-links">
                    <a href="#features">Features</a>
                    <a href="#models">Models</a>
                    <a href="#pricing">Pricing</a>
                    <a href="/credits">Credits</a>
                </div>
            </div>

            <!-- Developers col -->
            <div>
                <div class="footer-col-title">Developers</div>
                <div class="footer-links">
                    <a href="/docs">Documentation</a>
                    <a href="/docs">API Reference</a>
                    <a href="/docs">SDK Guide</a>
                    <a href="/register">Get API Key</a>
                </div>
            </div>

            <!-- Company col -->
            <div>
                <div class="footer-col-title">Company</div>
                <div class="footer-links">
                    <a href="/about">About</a>
                    <a href="/contact">Contact</a>
                    <a href="/privacy-policy">Privacy Policy</a>
                    <a href="/terms-of-service">Terms of Service</a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <span class="footer-copy">&copy; {{ date('Y') }} LLM Resayil. All rights reserved.</span>
            <div class="footer-legal">
                <a href="/privacy-policy">Privacy</a>
                <a href="/terms-of-service">Terms</a>
                <a href="/contact">Contact</a>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════ -->
<script>
(function() {
    /* ── Scroll reveal ── */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(function(el) { observer.observe(el); });
    } else {
        revealEls.forEach(function(el) { el.classList.add('visible'); });
    }

    /* ── Navbar transparency on scroll ── */
    var navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 60) {
            navbar.style.background = 'rgba(15,17,21,0.95)';
        } else {
            navbar.style.background = 'rgba(15,17,21,0.75)';
        }
    }, { passive: true });

    /* ── Animated credit counter in hero stats ── */
    function animateCounter(el, target, duration, suffix) {
        var start = 0;
        var step = (target / duration) * 16;
        var interval = setInterval(function() {
            start += step;
            if (start >= target) {
                el.textContent = target.toLocaleString() + suffix;
                clearInterval(interval);
            } else {
                el.textContent = Math.floor(start).toLocaleString() + suffix;
            }
        }, 16);
    }

    /* Run counters when hero stats card enters view */
    var statsCard = document.querySelector('.hero-stats-card');
    if (statsCard && 'IntersectionObserver' in window) {
        var statsObserver = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting) {
                var values = statsCard.querySelectorAll('.hero-stat-value');
                var targets = [1247, 47, 99.9, 50];
                var suffixes = ['', 'ms', '%', '+'];
                values.forEach(function(el, i) {
                    if (i === 2) {
                        /* uptime: static */
                        setTimeout(function() { el.textContent = '99.9%'; }, 600);
                    } else if (i === 3) {
                        setTimeout(function() { el.textContent = '50+'; }, 800);
                    } else {
                        animateCounter(el, targets[i], 900, suffixes[i]);
                    }
                });
                statsObserver.unobserve(statsCard);
            }
        }, { threshold: 0.5 });
        statsObserver.observe(statsCard);
    }
})();
</script>

</body>
</html>
