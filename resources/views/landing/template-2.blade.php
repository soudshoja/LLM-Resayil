<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="OpenAI-compatible LLM API — 10x cheaper than GPT-4o. Same API format, dedicated GPUs in Kuwait. Arabic-native. Pay per use.">
    <title>LLM Resayil — OpenAI-Compatible API for Developers</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #0f1115;
            color: #e8eaed;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        a { color: inherit; text-decoration: none; }
        button { cursor: pointer; font-family: inherit; }
        ul, ol { list-style: none; }
        img { display: block; max-width: 100%; }

        /* ── CSS Variables ── */
        :root {
            --gold: #d4af37;
            --gold-light: #e8cc6a;
            --gold-dim: rgba(212, 175, 55, 0.12);
            --gold-border: rgba(212, 175, 55, 0.28);
            --bg: #0f1115;
            --bg-card: #13161d;
            --bg-secondary: #0c0e13;
            --border: rgba(255, 255, 255, 0.07);
            --border-strong: rgba(255, 255, 255, 0.12);
            --text-muted: #6b7280;
            --text-secondary: #9ca3af;
            --mono: 'JetBrains Mono', 'Fira Code', monospace;
            --radius: 10px;
            --radius-lg: 16px;
        }

        /* ── Layout helpers ── */
        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .container-wide {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ── Shared button styles ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1.4rem;
            border-radius: var(--radius);
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        .btn-gold {
            background: var(--gold);
            color: #0a0c10;
        }
        .btn-gold:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.28);
        }
        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-strong);
        }
        .btn-ghost:hover {
            color: #e8eaed;
            border-color: rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.04);
        }
        .btn-lg {
            padding: 0.85rem 1.85rem;
            font-size: 1rem;
        }

        /* ── Section label ── */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.85rem;
        }
        .section-label::before {
            content: '';
            display: block;
            width: 18px;
            height: 1.5px;
            background: var(--gold);
        }

        /* ═══════════════════════════════════
           NAVBAR
        ═══════════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(15, 17, 21, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
        }
        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 62px;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.05rem;
            font-weight: 800;
            color: #e8eaed;
            letter-spacing: -0.02em;
        }
        .nav-logo-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-api-badge {
            display: inline-block;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            padding: 0.4rem 0.85rem;
            border-radius: var(--radius);
            transition: color 0.15s, background 0.15s;
        }
        .nav-links a:hover {
            color: #e8eaed;
            background: rgba(255, 255, 255, 0.05);
        }
        .nav-status-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            margin-right: 0.35rem;
            animation: pulse-green 2.4s ease-in-out infinite;
        }
        @keyframes pulse-green {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.45; }
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .nav-signin {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: color 0.15s;
        }
        .nav-signin:hover { color: #e8eaed; }
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 4.5px;
            background: none;
            border: none;
            padding: 6px;
            border-radius: var(--radius);
        }
        .nav-hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--text-secondary);
            border-radius: 2px;
            transition: all 0.2s;
        }

        /* ═══════════════════════════════════
           HERO
        ═══════════════════════════════════ */
        .hero {
            padding: 6rem 1.5rem 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -120px;
            left: 50%;
            transform: translateX(-60%);
            width: 900px;
            height: 700px;
            background: radial-gradient(ellipse, rgba(212, 175, 55, 0.07) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            right: -200px;
            width: 600px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(99, 102, 241, 0.05) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1180px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .hero-left {}
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            color: var(--gold);
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .hero-h1 {
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
            color: #f3f4f6;
        }
        .hero-h1 .gold-text {
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1.05rem;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 480px;
        }
        .hero-cta-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.75rem;
        }
        .btn-docs-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-secondary);
            transition: color 0.15s;
        }
        .btn-docs-link:hover { color: #e8eaed; }
        .btn-docs-link svg { transition: transform 0.15s; }
        .btn-docs-link:hover svg { transform: translateX(3px); }
        .hero-social-proof {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .hero-avatars {
            display: flex;
        }
        .hero-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 700;
            color: #0a0c10;
            margin-left: -7px;
        }
        .hero-avatar:first-child { margin-left: 0; }
        .av1 { background: #818cf8; }
        .av2 { background: #34d399; }
        .av3 { background: #f472b6; }
        .av4 { background: #fb923c; }

        /* ── Terminal Window ── */
        .hero-right {}
        .terminal-window {
            background: #0a0c10;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(212, 175, 55, 0.06), 0 32px 80px rgba(0, 0, 0, 0.55);
        }
        .terminal-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.7rem 1rem;
            background: #111318;
            border-bottom: 1px solid var(--border);
        }
        .terminal-dots {
            display: flex;
            gap: 6px;
        }
        .terminal-dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }
        .td-red   { background: #ff5f57; }
        .td-yellow{ background: #ffbd2e; }
        .td-green { background: #28c840; }
        .terminal-title {
            font-family: var(--mono);
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }
        .terminal-badge {
            font-size: 0.65rem;
            font-weight: 600;
            color: #22c55e;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.22);
            padding: 0.15rem 0.55rem;
            border-radius: 4px;
            letter-spacing: 0.06em;
        }

        /* Tab switcher */
        .terminal-tabs {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
            background: #0e1016;
        }
        .terminal-tab {
            font-family: var(--mono);
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            padding: 0.55rem 1rem;
            border: none;
            background: none;
            border-bottom: 2px solid transparent;
            transition: color 0.15s, border-color 0.15s;
        }
        .terminal-tab:hover { color: var(--text-secondary); }
        .terminal-tab.active {
            color: var(--gold);
            border-bottom-color: var(--gold);
            background: rgba(212, 175, 55, 0.04);
        }

        /* Code panels */
        .terminal-body {
            padding: 1.25rem 1.5rem 1.5rem;
            min-height: 230px;
            position: relative;
        }
        .code-panel {
            display: none;
        }
        .code-panel.active {
            display: block;
        }
        .code-panel pre {
            font-family: var(--mono);
            font-size: 0.8rem;
            line-height: 1.75;
            color: #cdd6f4;
            white-space: pre;
            overflow-x: auto;
        }
        .tok-cmd    { color: #89b4fa; }
        .tok-url    { color: #a6e3a1; }
        .tok-flag   { color: #cba6f7; }
        .tok-str    { color: #f38ba8; }
        .tok-key    { color: #89dceb; }
        .tok-val    { color: #a6e3a1; }
        .tok-num    { color: #fab387; }
        .tok-muted  { color: #585b70; }
        .tok-gold   { color: var(--gold); }
        .cursor-blink {
            display: inline-block;
            width: 8px;
            height: 14px;
            background: var(--gold);
            vertical-align: middle;
            border-radius: 1px;
            animation: blink 1.1s step-end infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* ── Response preview ── */
        .terminal-response {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background: rgba(212, 175, 55, 0.04);
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-radius: 8px;
        }
        .terminal-response-label {
            font-family: var(--mono);
            font-size: 0.68rem;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 0.4rem;
            letter-spacing: 0.06em;
        }
        .terminal-response pre {
            font-family: var(--mono);
            font-size: 0.75rem;
            color: #cdd6f4;
            line-height: 1.7;
        }

        /* ═══════════════════════════════════
           TRUST BAR
        ═══════════════════════════════════ */
        .trust-bar {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.015);
            padding: 1.1rem 1.5rem;
        }
        .trust-bar-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            flex-wrap: wrap;
            max-width: 1180px;
            margin: 0 auto;
        }
        .trust-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-right: 0.75rem;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-secondary);
            padding: 0.3rem 0.8rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg-card);
        }
        .trust-sep {
            color: var(--border-strong);
            font-size: 1rem;
        }

        /* ═══════════════════════════════════
           STATS ROW
        ═══════════════════════════════════ */
        .stats-section {
            padding: 4rem 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            max-width: 1180px;
            margin: 0 auto;
        }
        .stat-item {
            background: var(--bg-card);
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
        }
        .stat-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .stat-item:hover::after { opacity: 1; }
        .stat-number {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: #f3f4f6;
            line-height: 1;
            margin-bottom: 0.5rem;
            font-variant-numeric: tabular-nums;
        }
        .stat-number .gold-text {
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ═══════════════════════════════════
           PRICING COMPARISON
        ═══════════════════════════════════ */
        .pricing-section {
            padding: 5rem 1.5rem;
        }
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            max-width: 640px;
            margin-left: auto;
            margin-right: auto;
        }
        .section-header h2 {
            font-size: clamp(1.75rem, 3vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #f3f4f6;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }
        .section-header p {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .comparison-table-wrap {
            max-width: 820px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }
        .comparison-table thead tr {
            background: #0e1016;
            border-bottom: 1px solid var(--border);
        }
        .comparison-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .comparison-table th.col-feature { width: 42%; }
        .comparison-table th.col-competitor {
            width: 29%;
            color: var(--text-muted);
        }
        .comparison-table th.col-us {
            width: 29%;
            color: var(--gold);
        }
        .comparison-table th.col-us-label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .comparison-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .comparison-table tbody tr:last-child { border-bottom: none; }
        .comparison-table tbody tr:hover { background: rgba(255,255,255,0.02); }
        .comparison-table tbody tr.highlight-row {
            background: rgba(212, 175, 55, 0.03);
        }
        .comparison-table td {
            padding: 1.05rem 1.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            vertical-align: middle;
        }
        .comparison-table td.feat-name {
            font-weight: 600;
            color: #d1d5db;
        }
        .feat-desc {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }
        .cell-bad {
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .cell-good {
            color: #e8eaed;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cell-good .check-icon {
            color: var(--gold);
            flex-shrink: 0;
        }
        .price-cell-bad {
            font-family: var(--mono);
            font-size: 1rem;
            color: #6b7280;
            font-weight: 600;
        }
        .price-cell-good {
            font-family: var(--mono);
            font-size: 1rem;
            color: var(--gold);
            font-weight: 700;
        }
        .comparison-cta-row {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: rgba(212, 175, 55, 0.03);
            border-top: 1px solid var(--border);
            gap: 1rem;
            flex-wrap: wrap;
        }
        .savings-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--gold);
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
        }

        /* ═══════════════════════════════════
           MODELS GRID
        ═══════════════════════════════════ */
        .models-section {
            padding: 5rem 1.5rem;
            background: rgba(255,255,255,0.012);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .models-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            max-width: 1180px;
            margin: 0 auto 2rem;
        }
        .model-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .model-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .model-card:hover {
            border-color: rgba(212, 175, 55, 0.22);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
        }
        .model-card:hover::before { opacity: 1; }
        .model-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .model-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mi-llama    { background: linear-gradient(135deg, #f97316, #ea580c); }
        .mi-deepseek { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .mi-qwen     { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .mi-mistral  { background: linear-gradient(135deg, #ec4899, #db2777); }
        .mi-code     { background: linear-gradient(135deg, #10b981, #059669); }
        .mi-gemma    { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .model-badge {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.25rem 0.6rem;
            border-radius: 5px;
        }
        .badge-chat    { background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.25); }
        .badge-code    { background: rgba(16, 185, 129, 0.12); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.22); }
        .badge-think   { background: rgba(245, 158, 11, 0.12); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.22); }
        .badge-multi   { background: rgba(236, 72, 153, 0.12); color: #f9a8d4; border: 1px solid rgba(236, 72, 153, 0.22); }
        .model-name {
            font-size: 1rem;
            font-weight: 700;
            color: #e8eaed;
            margin-bottom: 0.3rem;
            font-family: var(--mono);
        }
        .model-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .model-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .model-meta-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.73rem;
            color: var(--text-muted);
        }
        .models-footer {
            text-align: center;
        }
        .models-view-all {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gold);
            border: 1px solid var(--gold-border);
            background: var(--gold-dim);
            padding: 0.6rem 1.4rem;
            border-radius: var(--radius);
            transition: background 0.15s, box-shadow 0.15s;
        }
        .models-view-all:hover {
            background: rgba(212, 175, 55, 0.18);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.12);
        }

        /* ═══════════════════════════════════
           HOW IT WORKS
        ═══════════════════════════════════ */
        .how-section {
            padding: 5rem 1.5rem;
        }
        .how-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            max-width: 1180px;
            margin: 0 auto;
            position: relative;
        }
        .how-steps::before {
            content: '';
            position: absolute;
            top: 30px;
            left: calc(16.66% + 40px);
            right: calc(16.66% + 40px);
            height: 1px;
            background: linear-gradient(90deg, var(--gold-border), rgba(212, 175, 55, 0.12), var(--gold-border));
            z-index: 0;
        }
        .how-step {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem 1.75rem;
            position: relative;
            z-index: 1;
            transition: border-color 0.2s;
        }
        .how-step:hover { border-color: rgba(212, 175, 55, 0.2); }
        .how-step-num {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #0a0c10;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            margin-bottom: 1.25rem;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.25);
        }
        .how-step h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #e8eaed;
            margin-bottom: 0.6rem;
        }
        .how-step p {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.65;
        }
        .how-step-time {
            display: inline-block;
            margin-top: 1rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gold);
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
        }
        .how-step-code {
            margin-top: 1rem;
            padding: 0.65rem 0.85rem;
            background: #0a0c10;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: var(--mono);
            font-size: 0.75rem;
            color: #a6e3a1;
            line-height: 1.6;
            overflow-x: auto;
        }
        .how-step-code .tok-flag { color: #cba6f7; }
        .how-step-code .tok-url { color: var(--gold); }

        /* ═══════════════════════════════════
           FEATURES GRID
        ═══════════════════════════════════ */
        .features-section {
            padding: 5rem 1.5rem;
            background: rgba(255,255,255,0.012);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            max-width: 1180px;
            margin: 0 auto;
        }
        .feature-cell {
            background: var(--bg-card);
            padding: 1.75rem;
            transition: background 0.15s;
        }
        .feature-cell:hover { background: #161920; }
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--gold);
        }
        .feature-cell h3 {
            font-size: 0.925rem;
            font-weight: 700;
            color: #e8eaed;
            margin-bottom: 0.45rem;
        }
        .feature-cell p {
            font-size: 0.82rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ═══════════════════════════════════
           FINAL CTA
        ═══════════════════════════════════ */
        .cta-section {
            padding: 6rem 1.5rem;
        }
        .cta-box {
            max-width: 680px;
            margin: 0 auto;
            text-align: center;
            padding: 3.5rem 3rem;
            border-radius: 20px;
            position: relative;
            background: var(--bg-card);
        }
        .cta-box::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 21px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.55), rgba(212, 175, 55, 0.08) 40%, rgba(99, 102, 241, 0.15) 70%, rgba(212, 175, 55, 0.35));
            z-index: -1;
            animation: border-spin 5s linear infinite;
            background-size: 200% 200%;
        }
        @keyframes border-spin {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .cta-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
        }
        .cta-box h2 {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 900;
            letter-spacing: -0.025em;
            color: #f3f4f6;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .cta-box p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 460px;
            margin-left: auto;
            margin-right: auto;
        }
        .cta-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .cta-no-card {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* ═══════════════════════════════════
           FOOTER
        ═══════════════════════════════════ */
        .footer {
            border-top: 1px solid var(--border);
            padding: 2.5rem 1.5rem;
            background: var(--bg-secondary);
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1180px;
            margin: 0 auto;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-secondary);
        }
        .footer-logo-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
        }
        .footer-copy {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        .footer-links a {
            font-size: 0.8rem;
            color: var(--text-muted);
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
        }
        .footer-links a:hover {
            color: var(--text-secondary);
            background: rgba(255,255,255,0.04);
        }
        .footer-status {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: #22c55e;
            font-weight: 500;
        }

        /* ═══════════════════════════════════
           MOBILE RESPONSIVE
        ═══════════════════════════════════ */
        @media (max-width: 960px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
            .hero-sub { max-width: 100%; }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .models-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .how-steps {
                grid-template-columns: 1fr;
            }
            .how-steps::before { display: none; }
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .comparison-table th,
            .comparison-table td {
                padding: 0.85rem 1rem;
                font-size: 0.8rem;
            }
        }
        @media (max-width: 680px) {
            .hero { padding: 4rem 1.25rem 3rem; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-signin { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .models-grid { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .cta-box { padding: 2.25rem 1.5rem; }
            .comparison-table-wrap { overflow-x: auto; }
            .comparison-table { min-width: 560px; }
            .footer-inner { flex-direction: column; text-align: center; }
            .footer-links { justify-content: center; }
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.18); }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════
     NAVBAR
════════════════════════════════════════ -->
<header class="navbar">
    <div class="container">
        <nav class="navbar-inner">
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M3 4h10M3 8h7M3 12h10" stroke="#0a0c10" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="13" cy="8" r="2" fill="#0a0c10"/>
                    </svg>
                </div>
                LLM Resayil
                <span class="nav-api-badge">API</span>
            </a>

            <div class="nav-links">
                <a href="/docs">API Docs</a>
                <a href="/models">Models</a>
                <a href="/billing/plans">Pricing</a>
                <a href="#">
                    <span class="nav-status-dot"></span>Status
                </a>
            </div>

            <div class="nav-actions">
                <a href="/login" class="nav-signin">Sign In</a>
                <a href="/register" class="btn btn-gold">
                    Get API Key
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <button class="nav-hamburger" aria-label="Menu" onclick="this.closest('header').querySelector('.nav-links').style.display='flex';this.style.display='none'">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </nav>
    </div>
</header>

<!-- ═══════════════════════════════════════
     HERO — SPLIT LAYOUT
════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-grid">
        <!-- Left: Copy -->
        <div class="hero-left">
            <div class="hero-badge">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M4.5 7l2 2 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                OpenAI-Compatible API
            </div>

            <h1 class="hero-h1">
                Build AI Apps<br>
                Without the <span class="gold-text">OpenAI<br>Price Tag</span>
            </h1>

            <p class="hero-sub">
                Same API format. 10x cheaper. Powered by dedicated GPUs in Kuwait — built for developers in the Middle East and beyond.
            </p>

            <div class="hero-cta-row">
                <a href="/register" class="btn btn-gold btn-lg">
                    Get Your Free API Key
                </a>
                <a href="/docs" class="btn-docs-link">
                    Read the Docs
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                        <path d="M3 7.5h9M7.5 3l4.5 4.5L7.5 12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="hero-social-proof">
                <div class="hero-avatars">
                    <div class="hero-avatar av1">AK</div>
                    <div class="hero-avatar av2">MS</div>
                    <div class="hero-avatar av3">YA</div>
                    <div class="hero-avatar av4">RM</div>
                </div>
                <span>Join developers building with LLM Resayil</span>
            </div>
        </div>

        <!-- Right: Terminal -->
        <div class="hero-right">
            <div class="terminal-window">
                <div class="terminal-bar">
                    <div class="terminal-dots">
                        <div class="terminal-dot td-red"></div>
                        <div class="terminal-dot td-yellow"></div>
                        <div class="terminal-dot td-green"></div>
                    </div>
                    <span class="terminal-title">terminal</span>
                    <span class="terminal-badge">LIVE</span>
                </div>

                <div class="terminal-tabs">
                    <button class="terminal-tab active" data-tab="curl">cURL</button>
                    <button class="terminal-tab" data-tab="python">Python</button>
                    <button class="terminal-tab" data-tab="node">Node.js</button>
                </div>

                <div class="terminal-body">
                    <!-- cURL panel -->
                    <div class="code-panel active" id="panel-curl">
                        <pre><span class="tok-cmd">curl</span> <span class="tok-url">https://llm.resayil.io/api/v1/chat/completions</span> \
  <span class="tok-flag">-H</span> <span class="tok-str">"Authorization: Bearer $API_KEY"</span> \
  <span class="tok-flag">-H</span> <span class="tok-str">"Content-Type: application/json"</span> \
  <span class="tok-flag">-d</span> <span class="tok-str">'{
    <span class="tok-key">"model"</span>: <span class="tok-val">"llama3.2"</span>,
    <span class="tok-key">"messages"</span>: [{
      <span class="tok-key">"role"</span>: <span class="tok-val">"user"</span>,
      <span class="tok-key">"content"</span>: <span class="tok-val">"Hello!"</span>
    }]
  }'</span> <span class="cursor-blink"></span></pre>

                        <div class="terminal-response">
                            <div class="terminal-response-label">// Response (48ms)</div>
                            <pre>{
  <span class="tok-key">"choices"</span>: [{
    <span class="tok-key">"message"</span>: {
      <span class="tok-key">"role"</span>: <span class="tok-val">"assistant"</span>,
      <span class="tok-key">"content"</span>: <span class="tok-val">"Hello! How can I help?"</span>
    }
  }],
  <span class="tok-key">"usage"</span>: { <span class="tok-key">"total_tokens"</span>: <span class="tok-num">23</span> }
}</pre>
                        </div>
                    </div>

                    <!-- Python panel -->
                    <div class="code-panel" id="panel-python">
                        <pre><span class="tok-muted"># pip install openai</span>
<span class="tok-cmd">from</span> openai <span class="tok-cmd">import</span> OpenAI

client = OpenAI(
    <span class="tok-key">api_key</span>=<span class="tok-str">"your-api-key"</span>,
    <span class="tok-key">base_url</span>=<span class="tok-gold">"https://llm.resayil.io/api/v1"</span>
)

response = client.chat.completions.create(
    <span class="tok-key">model</span>=<span class="tok-str">"llama3.2"</span>,
    <span class="tok-key">messages</span>=[{
        <span class="tok-str">"role"</span>: <span class="tok-str">"user"</span>,
        <span class="tok-str">"content"</span>: <span class="tok-str">"Hello!"</span>
    }]
)
<span class="tok-cmd">print</span>(response.choices[<span class="tok-num">0</span>].message.content) <span class="cursor-blink"></span></pre>
                    </div>

                    <!-- Node.js panel -->
                    <div class="code-panel" id="panel-node">
                        <pre><span class="tok-muted">// npm install openai</span>
<span class="tok-cmd">import</span> OpenAI <span class="tok-cmd">from</span> <span class="tok-str">'openai'</span>;

<span class="tok-cmd">const</span> client = <span class="tok-cmd">new</span> OpenAI({
  apiKey: <span class="tok-str">'your-api-key'</span>,
  baseURL: <span class="tok-gold">'https://llm.resayil.io/api/v1'</span>
});

<span class="tok-cmd">const</span> res = <span class="tok-cmd">await</span> client.chat.completions.create({
  model: <span class="tok-str">'llama3.2'</span>,
  messages: [{ role: <span class="tok-str">'user'</span>, content: <span class="tok-str">'Hello!'</span> }]
});

console.log(res.choices[<span class="tok-num">0</span>].message.content); <span class="cursor-blink"></span></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     TRUST BAR
════════════════════════════════════════ -->
<div class="trust-bar">
    <div class="trust-bar-inner">
        <span class="trust-label">Compatible with</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5.5" stroke="#9ca3af" stroke-width="1.5"/><path d="M4 7l2 2 4-4" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            OpenAI SDK
        </span>
        <span class="trust-sep">/</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="2" y="2" width="10" height="10" rx="2" stroke="#9ca3af" stroke-width="1.5"/><path d="M5 7h4M7 5v4" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round"/></svg>
            LangChain
        </span>
        <span class="trust-sep">/</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2L12 11H2L7 2z" stroke="#9ca3af" stroke-width="1.5" stroke-linejoin="round"/></svg>
            LlamaIndex
        </span>
        <span class="trust-sep">/</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="4" cy="7" r="2" stroke="#9ca3af" stroke-width="1.5"/><circle cx="10" cy="4" r="2" stroke="#9ca3af" stroke-width="1.5"/><circle cx="10" cy="10" r="2" stroke="#9ca3af" stroke-width="1.5"/><path d="M6 6.5L8.5 5M6 7.5L8.5 9" stroke="#9ca3af" stroke-width="1.25"/></svg>
            AutoGen
        </span>
        <span class="trust-sep">/</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 4h10v7a1 1 0 01-1 1H3a1 1 0 01-1-1V4z" stroke="#9ca3af" stroke-width="1.5"/><path d="M5 4V3a2 2 0 014 0v1" stroke="#9ca3af" stroke-width="1.5"/></svg>
            LiteLLM
        </span>
        <span class="trust-sep">/</span>
        <span class="trust-item">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="2" y="5" width="10" height="7" rx="1.5" stroke="#9ca3af" stroke-width="1.5"/><path d="M5 5V4a2 2 0 014 0v1" stroke="#9ca3af" stroke-width="1.5"/></svg>
            CrewAI
        </span>
    </div>
</div>

<!-- ═══════════════════════════════════════
     STATS ROW
════════════════════════════════════════ -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">
                <span class="gold-text" data-count="50" data-suffix="+">50+</span>
            </div>
            <div class="stat-label">Available Models</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">
                <span class="gold-text" data-count="10" data-prefix="" data-suffix="x">10x</span>
            </div>
            <div class="stat-label">Cheaper than GPT-4o</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">
                <span class="gold-text" data-count="99.9" data-suffix="%">99.9%</span>
            </div>
            <div class="stat-label">Uptime SLA</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">
                <span class="gold-text" data-count="0" data-suffix="ms">~48ms</span>
            </div>
            <div class="stat-label">Median TTFT</div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     PRICING COMPARISON
════════════════════════════════════════ -->
<section class="pricing-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Pricing</div>
            <h2>Why pay more for the same results?</h2>
            <p>Drop-in replacement for the OpenAI API. Change one line of code, cut your AI bill by 90%.</p>
        </div>

        <div class="comparison-table-wrap">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="col-feature">Feature</th>
                        <th class="col-competitor">OpenAI GPT-4o</th>
                        <th class="col-us">
                            <span class="col-us-label">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2l1.4 2.9L12 5.4l-2.5 2.4.6 3.4L7 9.6l-3.1 1.6.6-3.4L2 5.4l3.6-.5L7 2z" fill="#d4af37"/></svg>
                                LLM Resayil
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="highlight-row">
                        <td class="feat-name">
                            Price per 1M tokens
                            <span class="feat-desc">Input tokens, standard tier</span>
                        </td>
                        <td><span class="price-cell-bad">$5.00</span></td>
                        <td><span class="price-cell-good">$0.50</span></td>
                    </tr>
                    <tr>
                        <td class="feat-name">
                            Monthly minimum
                            <span class="feat-desc">Minimum spend to access API</span>
                        </td>
                        <td><span class="cell-bad">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 3l8 8M11 3l-8 8" stroke="#6b7280" stroke-width="1.6" stroke-linecap="round"/></svg>
                            $20 / month
                        </span></td>
                        <td><span class="cell-good">
                            <svg class="check-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5l3 3 6-6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            $0 minimum
                        </span></td>
                    </tr>
                    <tr>
                        <td class="feat-name">
                            Arabic language support
                            <span class="feat-desc">Native tokenization and tuning</span>
                        </td>
                        <td><span class="cell-bad">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 3v8M3 7h8" stroke="#6b7280" stroke-width="1.6" stroke-linecap="round" opacity="0.5"/></svg>
                            Limited
                        </span></td>
                        <td><span class="cell-good">
                            <svg class="check-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5l3 3 6-6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Native
                        </span></td>
                    </tr>
                    <tr>
                        <td class="feat-name">
                            Data residency
                            <span class="feat-desc">Where your requests are processed</span>
                        </td>
                        <td><span class="cell-bad">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5" stroke="#6b7280" stroke-width="1.5"/><path d="M7 4v3l2 1.5" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round"/></svg>
                            US-only
                        </span></td>
                        <td><span class="cell-good">
                            <svg class="check-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5l3 3 6-6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Kuwait / ME
                        </span></td>
                    </tr>
                    <tr>
                        <td class="feat-name">
                            Credits expire
                            <span class="feat-desc">Paid credits rollover policy</span>
                        </td>
                        <td><span class="cell-bad">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 3l8 8M11 3l-8 8" stroke="#6b7280" stroke-width="1.6" stroke-linecap="round"/></svg>
                            Monthly reset
                        </span></td>
                        <td><span class="cell-good">
                            <svg class="check-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5l3 3 6-6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Never expire
                        </span></td>
                    </tr>
                    <tr>
                        <td class="feat-name">
                            API format
                            <span class="feat-desc">SDK / client compatibility</span>
                        </td>
                        <td><span class="cell-bad">Proprietary</span></td>
                        <td><span class="cell-good">
                            <svg class="check-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5l3 3 6-6" stroke="#d4af37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            OpenAI-compatible
                        </span></td>
                    </tr>
                </tbody>
            </table>

            <div class="comparison-cta-row">
                <div class="savings-badge">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1l1.8 3.7L13 5.4 10 8.3l.7 4.2L7 10.3l-3.7 2.2L4 8.3 1 5.4l4.2-.7L7 1z" fill="#d4af37"/></svg>
                    Save up to 90% on your AI spend
                </div>
                <a href="/register" class="btn btn-gold">
                    Switch Today
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     MODELS SHOWCASE
════════════════════════════════════════ -->
<section class="models-section">
    <div class="container">
        <div class="section-header" style="margin-bottom:2.5rem;">
            <div class="section-label">Model Library</div>
            <h2>50+ models. One API.</h2>
            <p>From fast chat to deep reasoning and code generation — every model is accessible with the same OpenAI-compatible endpoint.</p>
        </div>

        <div class="models-grid">
            <!-- Llama 3.2 -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-llama">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M6 4c0 3.3 2.7 6 6 6" stroke="white" stroke-width="2" stroke-linecap="round"/><path d="M10 10c0 3.3-2.7 6-6 6" stroke="white" stroke-width="2" stroke-linecap="round"/><circle cx="14" cy="4" r="2" fill="white"/><circle cx="6" cy="16" r="2" fill="white"/></svg>
                    </div>
                    <span class="model-badge badge-chat">Chat</span>
                </div>
                <div class="model-name">llama3.2</div>
                <div class="model-desc">Meta's flagship open model — fast, multilingual, instruction-tuned for general use.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        128k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>

            <!-- DeepSeek -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-deepseek">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="7" stroke="white" stroke-width="2"/><path d="M7 10l2 2 4-4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <span class="model-badge badge-think">Thinking</span>
                </div>
                <div class="model-name">deepseek-r1</div>
                <div class="model-desc">Chain-of-thought reasoning model. Exceptional at math, science, and logic problems.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        64k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>

            <!-- Qwen -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-qwen">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><rect x="3" y="3" width="14" height="14" rx="4" stroke="white" stroke-width="2"/><path d="M7 10h6M10 7v6" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </div>
                    <span class="model-badge badge-chat">Chat</span>
                </div>
                <div class="model-name">qwen2.5</div>
                <div class="model-desc">Alibaba's powerful multilingual model with strong Arabic and code performance.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        32k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>

            <!-- Mistral -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-mistral">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4 5h5v5H4zM11 5h5v5h-5zM4 11h5v5H4zM11 11h5v5h-5z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </div>
                    <span class="model-badge badge-chat">Chat</span>
                </div>
                <div class="model-name">mistral-7b</div>
                <div class="model-desc">Highly efficient 7B model. Best throughput-to-quality ratio for high-volume workloads.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        32k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>

            <!-- Codestral -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-code">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M6 7L3 10l3 3M14 7l3 3-3 3M11 5l-2 10" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <span class="model-badge badge-code">Code</span>
                </div>
                <div class="model-name">codestral</div>
                <div class="model-desc">State-of-the-art code generation model. 80+ programming languages, fill-in-middle support.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        32k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>

            <!-- Gemma -->
            <div class="model-card">
                <div class="model-card-top">
                    <div class="model-icon mi-gemma">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="4" stroke="white" stroke-width="2"/><circle cx="10" cy="10" r="8" stroke="white" stroke-width="1.5" stroke-dasharray="3 3"/></svg>
                    </div>
                    <span class="model-badge badge-multi">Multimodal</span>
                </div>
                <div class="model-name">gemma3</div>
                <div class="model-desc">Google's lightweight, efficient model family. Ideal for edge-ready and low-latency use cases.</div>
                <div class="model-meta">
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><rect x="1" y="1" width="10" height="10" rx="2" stroke="#6b7280" stroke-width="1.2"/><path d="M4 6h4M6 4v4" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        128k ctx
                    </div>
                    <div class="model-meta-item">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="#6b7280" stroke-width="1.2" stroke-linecap="round"/></svg>
                        Streaming
                    </div>
                </div>
            </div>
        </div>

        <div class="models-footer">
            <a href="/models" class="models-view-all">
                View all 50+ models
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════ -->
<section class="how-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Get Started</div>
            <h2>Three steps. Five minutes.</h2>
            <p>If you already use the OpenAI SDK, you are 90% done.</p>
        </div>

        <div class="how-steps">
            <div class="how-step">
                <div class="how-step-num">1</div>
                <h3>Create account &amp; get API key</h3>
                <p>Sign up with your email. No credit card required. Your API key is generated instantly.</p>
                <span class="how-step-time">30 seconds</span>
            </div>

            <div class="how-step">
                <div class="how-step-num">2</div>
                <h3>Point your SDK to our endpoint</h3>
                <p>One line change. The rest of your code stays identical — models, streaming, all of it.</p>
                <div class="how-step-code"><span class="tok-flag">base_url</span>=<span class="tok-url">"https://llm.resayil.io/api/v1"</span></div>
            </div>

            <div class="how-step">
                <div class="how-step-num">3</div>
                <h3>Start building — first 1,000 tokens free</h3>
                <p>Every new account gets free credits. No expiry. Pay only when you scale beyond that.</p>
                <span class="how-step-time">Free to start</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     FEATURE HIGHLIGHTS
════════════════════════════════════════ -->
<section class="features-section">
    <div class="container">
        <div class="section-header" style="margin-bottom:2.5rem;">
            <div class="section-label">Platform</div>
            <h2>Everything you need to ship.</h2>
            <p>Production-grade infrastructure, developer-friendly tooling, and zero lock-in.</p>
        </div>

        <div class="features-grid">
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9h12M9 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 3l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.4"/></svg>
                </div>
                <h3>Real-time streaming responses</h3>
                <p>Server-sent events (SSE) out of the box. Same <code style="font-family:var(--mono);font-size:0.78em;color:#a5b4fc;">stream: true</code> parameter as OpenAI.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><rect x="2" y="5" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M6 5V4a3 3 0 016 0v1" stroke="currentColor" stroke-width="1.8"/><circle cx="9" cy="10" r="1.5" fill="currentColor"/></svg>
                </div>
                <h3>Pay-per-use, no subscriptions</h3>
                <p>Buy credits when you need them. No monthly commitments. No unused credit waste.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 2l2 4.5L16 7l-3.5 3.5.8 5L9 13l-4.3 2.5.8-5L2 7l5-.5L9 2z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                </div>
                <h3>99.9% uptime SLA</h3>
                <p>Dedicated GPU infrastructure with automatic failover. Status page with real-time metrics.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M9 5v4l2.5 2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h3>Arabic &amp; English native support</h3>
                <p>Models tuned for Arabic tokenization and RTL text. Built for MENA developers from day one.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 6l6-4 6 4v7a1 1 0 01-1 1H4a1 1 0 01-1-1V6z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M7 13V9h4v4" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </div>
                <h3>Usage dashboard &amp; analytics</h3>
                <p>Per-model cost breakdown, token usage over time, and savings vs GPT-4o — live in your dashboard.</p>
            </div>
            <div class="feature-cell">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M15 4H3a1 1 0 00-1 1v9a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1z" stroke="currentColor" stroke-width="1.8"/><path d="M5 8h2M5 11h6M10 8h3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                </div>
                <h3>Multiple API keys per account</h3>
                <p>Isolate traffic by project or team. Revoke individual keys without disrupting others.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     FINAL CTA
════════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <div class="cta-tag">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1l1.2 2.5L10 4 8 6l.5 3L6 7.8 3.5 9 4 6 2 4l2.8-.5L6 1z" fill="#d4af37"/></svg>
                Free tier available
            </div>
            <h2>Start with 1,000 free credits.<br>No credit card required.</h2>
            <p>Get your API key in 30 seconds. Drop it into your existing OpenAI integration and you're live — on dedicated Middle East infrastructure.</p>
            <div class="cta-actions">
                <a href="/register" class="btn btn-gold btn-lg" style="font-size:1.05rem;padding:1rem 2.5rem;">
                    Create Free Account
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2 8h12M8 2l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <div class="cta-no-card">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M4 7l2.5 2.5L10 4" stroke="#6b7280" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    No credit card &nbsp;&bull;&nbsp; Instant API key &nbsp;&bull;&nbsp; Credits never expire
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
<footer class="footer">
    <div class="footer-inner">
        <div>
            <div class="footer-logo">
                <div class="footer-logo-dot"></div>
                LLM Resayil
            </div>
            <div class="footer-copy">OpenAI-compatible API for the Middle East. &copy; {{ date('Y') }} Resayil.</div>
        </div>

        <nav class="footer-links">
            <a href="/docs">Docs</a>
            <a href="/docs#api-reference">API Reference</a>
            <a href="/models">Models</a>
            <a href="/billing/plans">Pricing</a>
            <a href="#">Status</a>
            <a href="/contact">Contact</a>
            <a href="/privacy-policy">Privacy</a>
            <a href="/terms-of-service">Terms</a>
        </nav>

        <div class="footer-status">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="#22c55e"/></svg>
            All systems operational
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
(function () {
    'use strict';

    /* ── Tab Switcher ── */
    document.querySelectorAll('.terminal-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var tabId = this.dataset.tab;

            // Deactivate all tabs and panels
            document.querySelectorAll('.terminal-tab').forEach(function (t) {
                t.classList.remove('active');
            });
            document.querySelectorAll('.code-panel').forEach(function (p) {
                p.classList.remove('active');
            });

            // Activate clicked tab and matching panel
            this.classList.add('active');
            var panel = document.getElementById('panel-' + tabId);
            if (panel) panel.classList.add('active');
        });
    });

    /* ── Animated stat counters ── */
    function animateCounter(el) {
        var target = parseFloat(el.dataset.count || el.textContent);
        var suffix = el.dataset.suffix || '';
        var prefix = el.dataset.prefix || '';
        var isDecimal = String(target).indexOf('.') !== -1;
        var duration = 1400;
        var start = null;

        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            // Ease out cubic
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = target * eased;
            el.textContent = prefix + (isDecimal ? current.toFixed(1) : Math.floor(current)) + suffix;
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = prefix + target + suffix;
        }

        requestAnimationFrame(step);
    }

    /* ── Intersection Observer for counters ── */
    var counters = document.querySelectorAll('[data-count]');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(function (el) {
            observer.observe(el);
        });
    }

    /* ── Scroll-triggered section reveal ── */
    var style = document.createElement('style');
    style.textContent = '.reveal { opacity: 0; transform: translateY(22px); transition: opacity 0.55s ease, transform 0.55s ease; } .reveal.visible { opacity: 1; transform: none; }';
    document.head.appendChild(style);

    var revealTargets = document.querySelectorAll('.model-card, .how-step, .feature-cell, .stat-item');
    revealTargets.forEach(function (el) { el.classList.add('reveal'); });

    if ('IntersectionObserver' in window) {
        var revealObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealTargets.forEach(function (el) { revealObs.observe(el); });
    } else {
        revealTargets.forEach(function (el) { el.classList.add('visible'); });
    }

    /* ── Stagger reveal delay for grids ── */
    document.querySelectorAll('.models-grid .model-card, .features-grid .feature-cell, .how-steps .how-step').forEach(function (el, i) {
        el.style.transitionDelay = (i * 60) + 'ms';
    });

})();
</script>

</body>
</html>
