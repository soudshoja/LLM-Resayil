<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How Credits Work — LLM Resayil</title>
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
            --green-dim: rgba(34,197,94,0.12);
            --red: #ef4444;
            --red-dim: rgba(239,68,68,0.12);
            --indigo: #6366f1;
            --indigo-dim: rgba(99,102,241,0.12);
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

        .btn-outline {
            background: transparent;
            color: var(--gold);
            padding: 0.65rem 1.5rem;
            border: 1px solid var(--gold-border);
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-outline:hover {
            background: var(--gold-dim);
            border-color: var(--gold);
        }

        /* Hero Section */
        .hero {
            padding: 5rem 2rem 3rem 2rem;
            margin-top: 70px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            border-radius: 2rem;
            color: var(--gold);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        .hero h1 span {
            color: var(--gold);
        }

        .hero-lead {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Section Layout */
        .section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-label {
            color: var(--gold);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            line-height: 1.1;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        /* Why Credits Section */
        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 968px) {
            .comparison-grid {
                grid-template-columns: 1fr;
            }
        }

        .comparison-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 2rem;
            border-top: 3px solid;
        }

        .comparison-card.bad {
            border-top-color: var(--red);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .comparison-card.good {
            border-top-color: var(--green);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .comparison-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }

        .card-badge {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .card-badge.bad {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
        }

        .card-badge.good {
            background: rgba(34, 197, 94, 0.15);
            color: var(--green);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .code-box {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            color: var(--text-secondary);
            overflow-x: auto;
        }

        .code-box-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .code-line {
            line-height: 1.6;
        }

        .code-line.highlight {
            color: var(--gold);
        }

        .comparison-card.bad .code-line.highlight {
            color: var(--red);
        }

        .comparison-card.good .code-line.highlight {
            color: var(--green);
        }

        .bullet-list {
            list-style: none;
        }

        .bullet-list li {
            padding: 0.75rem 0;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .bullet-icon {
            width: 1.2rem;
            height: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
        }

        .bad .bullet-icon {
            color: var(--red);
        }

        .good .bullet-icon {
            color: var(--green);
        }

        /* Billing Flow Section */
        .steps-flow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            position: relative;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .steps-flow {
                flex-direction: column;
                gap: 2rem;
            }
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-num {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-secondary);
            margin: 0 auto 1rem;
            transition: all 0.4s ease;
        }

        .step-label {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .step-desc {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .steps-flow::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 50px;
            right: 50px;
            height: 2px;
            background: var(--border);
            z-index: -1;
        }

        @media (max-width: 768px) {
            .steps-flow::before {
                display: none;
            }
        }

        .tip-box {
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .tip-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            flex-shrink: 0;
            font-weight: 700;
        }

        .tip-text {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Worked Example Section */
        .example-prompt {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .example-prompt-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: var(--gold);
            font-size: 1.2rem;
        }

        .example-prompt-text {
            flex: 1;
        }

        .example-prompt-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .example-prompt-detail {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .example-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 968px) {
            .example-comparison {
                grid-template-columns: 1fr;
            }
        }

        .example-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .example-card-tag {
            display: inline-block;
            padding: 0.3rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .example-card.complex .example-card-tag {
            background: rgba(239, 68, 68, 0.2);
            color: var(--red);
        }

        .example-card.simple .example-card-tag {
            background: rgba(34, 197, 94, 0.2);
            color: var(--green);
        }

        .example-card-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .example-breakdown {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .example-breakdown .highlight {
            font-weight: 700;
        }

        .example-card.complex .example-breakdown .highlight {
            color: var(--red);
        }

        .example-card.simple .example-breakdown .highlight {
            color: var(--green);
        }

        .example-note {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-style: italic;
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        /* Rates Section */
        .rates-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .rates-table th {
            background: var(--bg-secondary);
            padding: 1.25rem;
            text-align: left;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .rates-table td {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        .rates-table tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .tier-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .tier-badge.standard {
            background: rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }

        .tier-badge.premium {
            background: var(--gold-dim);
            color: var(--gold);
        }

        /* Top-Up Section */
        .topup-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .topup-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .topup-card:hover {
            border-color: var(--gold-border);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.1);
        }

        .topup-ribbon {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--gold), #ffd700);
            color: var(--bg);
            padding: 0.4rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .topup-card.featured {
            border-color: var(--gold-border);
            background: linear-gradient(135deg, var(--bg-card), rgba(212, 175, 55, 0.05));
        }

        .topup-credits {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .topup-credits-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .topup-price {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .topup-per-credit {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .topup-card .btn-gold {
            width: 100%;
            padding: 0.75rem 1.5rem;
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
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #ffd700;
        }

        /* Utilities */
        .text-gold {
            color: var(--gold);
        }

        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">LLM Resayil</div>
        <div class="navbar-nav">
            <a href="/docs" class="navbar-link">← Back to Docs</a>
            @if(auth()->check())
                <a href="/dashboard" class="navbar-link">Dashboard</a>
            @else
                <a href="/login" class="navbar-link">Login</a>
                <a href="/register" class="btn-gold">Get Started</a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="badge">Credits System</div>
        <h1>Simple, Predictable <span class="text-gold">Credits</span></h1>
        <p class="hero-lead">No complex token math, no surprise USD bills, no per-model pricing puzzles. Buy credits in KWD, spend them as you build. Your balance stays clear.</p>
        <div class="hero-buttons">
            <a href="#topup" class="btn-gold">View Top-Up Packs</a>
            <a href="/docs" class="btn-outline">API Documentation</a>
        </div>
    </section>

    <!-- Why Credits Section -->
    <section class="section">
        <div class="section-label">Pricing Model</div>
        <h2 class="section-title">One system. Zero confusion.</h2>
        <p class="section-subtitle">Other LLM providers charge per-model in USD with separate input/output rates. We simplified it.</p>

        <div class="comparison-grid">
            <!-- Bad Card -->
            <div class="comparison-card bad">
                <div class="card-badge bad">✗ Token-based pricing (other providers)</div>
                <h3 class="card-title">Complex & unpredictable</h3>
                <p class="card-subtitle">Different rates per model. Input tokens priced separately from output tokens. Prices in USD subject to exchange rates.</p>

                <div class="code-box">
                    <div class="code-box-label">Example: GPT-4o, 1 request</div>
                    <div class="code-line">Input tokens:   30 × $0.0000025 = $0.000075</div>
                    <div class="code-line">Output tokens: 150 × $0.0000100 = $0.001500</div>
                    <div class="code-line">FX (KWD/USD):              0.307</div>
                    <div class="code-line">Total:                   ~$0.001575 <span class="highlight">(red)</span></div>
                </div>

                <ul class="bullet-list">
                    <li><span class="bullet-icon">✗</span> Different price per model</li>
                    <li><span class="bullet-icon">✗</span> Separate input & output rates</li>
                    <li><span class="bullet-icon">✗</span> USD prices subject to FX</li>
                    <li><span class="bullet-icon">✗</span> Hard to predict monthly spend</li>
                    <li><span class="bullet-icon">✗</span> Varies with each model update</li>
                </ul>
            </div>

            <!-- Good Card -->
            <div class="comparison-card good">
                <div class="card-badge good">✓ Credit-based pricing (Resayil)</div>
                <h3 class="card-title">Simple & transparent</h3>
                <p class="card-subtitle">One credit = one output token (Standard) or two credits (Premium). Buy packs in KWD. No FX, no surprises.</p>

                <div class="code-box">
                    <div class="code-box-label">Same Request: Standard Model, 1 request</div>
                    <div class="code-line">Output tokens:     150</div>
                    <div class="code-line">Rate:      × 1 credit</div>
                    <div class="code-line">Credits used:      <span class="highlight">150</span></div>
                    <div class="code-line">Balance left: 4,850 of 5,000</div>
                    <div class="code-line">Total:         <span class="highlight">150 credits</span></div>
                </div>

                <ul class="bullet-list">
                    <li><span class="bullet-icon">✓</span> Same rate for all Standard models</li>
                    <li><span class="bullet-icon">✓</span> Only output tokens count</li>
                    <li><span class="bullet-icon">✓</span> Priced in KWD — no FX</li>
                    <li><span class="bullet-icon">✓</span> Clear balance always visible</li>
                    <li><span class="bullet-icon">✓</span> Credits never expire</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Billing Flow Section -->
    <section class="section" id="flow">
        <div class="section-label">Billing Flow</div>
        <h2 class="section-title">How a request is charged</h2>
        <p class="section-subtitle">Every API call follows the same 5-step flow.</p>

        <div class="steps-flow">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-label">Make Request</div>
                <div class="step-desc">Call /api/v1/chat/completions</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-label">Model Responds</div>
                <div class="step-desc">Response generated</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-label">Tokens Counted</div>
                <div class="step-desc">Output tokens measured</div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-label">Credits Deducted</div>
                <div class="step-desc">From your balance</div>
            </div>
            <div class="step">
                <div class="step-num">5</div>
                <div class="step-label">Balance Updated</div>
                <div class="step-desc">Visible on dashboard</div>
            </div>
        </div>

        <div class="tip-box">
            <div class="tip-icon">ℹ</div>
            <div class="tip-text">Only output tokens count toward credits. Input tokens (your prompt) are not charged — so longer system prompts don't cost extra.</div>
        </div>
    </section>

    <!-- Worked Example Section -->
    <section class="section" id="example">
        <div class="section-label">Real Example</div>
        <h2 class="section-title">You ask: <span class="text-gold">"Write a 100-word email"</span></h2>
        <p class="section-subtitle">See exactly how the same request is billed under each system.</p>

        <div class="example-prompt">
            <div class="example-prompt-icon">📄</div>
            <div class="example-prompt-text">
                <div class="example-prompt-title">Write a professional 100-word email introducing our product.</div>
                <div class="example-prompt-detail">~30 input tokens, ~150 output tokens generated</div>
            </div>
        </div>

        <div class="example-comparison">
            <div class="example-card complex">
                <div class="example-card-tag">Complex</div>
                <div class="example-card-title">Token-based (e.g. GPT-4o)</div>
                <div class="example-breakdown">
                    Input: 30 tokens × $0.0000025 = $0.000075
                    Output: 150 tokens × $0.000010 = $0.001500
                    FX rate: 0.307 KWD/USD
                    Conversion fee: ~included
                    Total: ~$0.001575 <span class="highlight">USD</span>
                </div>
                <div class="example-note">Varies by model, by rate changes, by FX</div>
            </div>

            <div class="example-card simple">
                <div class="example-card-tag">Simple</div>
                <div class="example-card-title">Credit-based (Resayil Standard)</div>
                <div class="example-breakdown">
                    Output tokens: 150
                    Rate: 1 credit / token
                    Credits used: <span class="highlight">150</span>
                    Pack: 5,000 credits (2 KWD)
                    Total: <span class="highlight">150 credits</span>
                </div>
                <div class="example-note">Always this rate. No surprises.</div>
            </div>
        </div>
    </section>

    <!-- Rates Section -->
    <section class="section" id="rates">
        <div class="section-label">Rates</div>
        <h2 class="section-title">Credit rates by model tier</h2>
        <p class="section-subtitle">All models fall into one of two tiers. The /api/v1/models endpoint shows which tier each model is.</p>

        <table class="rates-table">
            <thead>
                <tr>
                    <th>Model Tier</th>
                    <th>Credits/Output Token</th>
                    <th>Example Models</th>
                    <th>Best For</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="tier-badge standard">Standard</span></td>
                    <td><strong>1 credit</strong></td>
                    <td>Llama, Qwen, Gemma, Mistral</td>
                    <td>General chat, coding, summarization</td>
                </tr>
                <tr>
                    <td><span class="tier-badge premium">Premium</span></td>
                    <td><strong>2 credits</strong></td>
                    <td>DeepSeek, large reasoning models</td>
                    <td>Complex reasoning, long context, advanced tasks</td>
                </tr>
            </tbody>
        </table>

        <div class="tip-box">
            <div class="tip-icon">💡</div>
            <div class="tip-text">Not sure which tier a model is? Call <strong>GET /api/v1/models</strong> — each model includes a <strong>credit_multiplier</strong> field: 1 for Standard, 2 for Premium.</div>
        </div>
    </section>

    <!-- Top-Up Section -->
    <section class="section" id="topup">
        <div class="section-label">Top-Up Packs</div>
        <h2 class="section-title">Buy credits, spend as you go</h2>
        <p class="section-subtitle">Credits never expire and are separate from your subscription. Top up anytime.</p>

        <div class="topup-grid">
            <!-- Pack 1 -->
            <div class="topup-card">
                <div class="topup-credits topup-credits-5k">5,000</div>
                <div class="topup-credits-label">credits</div>
                <div class="topup-price">2<span style="font-size: 1.5rem;"> KWD</span></div>
                <div class="topup-per-credit">0.0004 KWD per credit</div>
                <a href="/billing/plans" class="btn-gold">Top Up</a>
            </div>

            <!-- Pack 2 (Featured) -->
            <div class="topup-card featured">
                <div class="topup-ribbon">Best Value</div>
                <div class="topup-credits topup-credits-50k">50,000</div>
                <div class="topup-credits-label">credits</div>
                <div class="topup-price">15<span style="font-size: 1.5rem;"> KWD</span></div>
                <div class="topup-per-credit">0.0003 KWD per credit</div>
                <a href="/billing/plans" class="btn-gold">Top Up</a>
            </div>

            <!-- Pack 3 -->
            <div class="topup-card">
                <div class="topup-credits topup-credits-15k">15,000</div>
                <div class="topup-credits-label">credits</div>
                <div class="topup-price">5<span style="font-size: 1.5rem;"> KWD</span></div>
                <div class="topup-per-credit">0.00033 KWD per credit</div>
                <a href="/billing/plans" class="btn-gold">Top Up</a>
            </div>
        </div>

        <div class="tip-box">
            <div class="tip-icon">💳</div>
            <div class="tip-text">Available at <a href="/billing/plans" style="color: var(--gold);">Billing & Plans</a>. Payment via KNET or international credit card.</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>LLM Resayil · Credits Documentation · <a href="/docs">Back to Docs</a></p>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Inject CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            .step-num.visible {
                background: var(--gold);
                color: var(--bg);
                border-color: var(--gold);
            }
            .comparison-card.animate-in {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        document.head.appendChild(style);

        // Step numbers animation
        const stepsFlowObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const steps = entry.target.querySelectorAll('.step-num');
                    steps.forEach((step, index) => {
                        setTimeout(() => {
                            step.classList.add('visible');
                        }, index * 150);
                    });
                    stepsFlowObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const stepsFlow = document.querySelector('.steps-flow');
        if (stepsFlow) {
            stepsFlowObserver.observe(stepsFlow);
        }

        // Comparison cards animation
        const cardsObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const cards = document.querySelectorAll('.comparison-card');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.classList.add('animate-in');
                        }, index * 200);
                    });
                    cardsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        const comparisonGrid = document.querySelector('.comparison-grid');
        if (comparisonGrid) {
            cardsObserver.observe(comparisonGrid);
        }

        // Counter animation for topup credits
        function easeOutQuad(t) {
            return t * (2 - t);
        }

        function animateCounter(element, finalValue, duration = 800) {
            const startValue = 0;
            const startTime = performance.now();

            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = easeOutQuad(progress);
                const current = Math.floor(startValue + (finalValue - startValue) * eased);

                element.textContent = current.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };

            requestAnimationFrame(animate);
        }

        const topupObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const creditElements = [
                        { el: document.querySelector('.topup-credits-5k'), val: 5000 },
                        { el: document.querySelector('.topup-credits-15k'), val: 15000 },
                        { el: document.querySelector('.topup-credits-50k'), val: 50000 }
                    ];

                    creditElements.forEach(item => {
                        if (item.el && item.el.textContent === '0') {
                            animateCounter(item.el, item.val);
                        }
                    });

                    topupObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        const topupSection = document.querySelector('#topup');
        if (topupSection) {
            topupObserver.observe(topupSection);
        }
    </script>
</body>
</html>
