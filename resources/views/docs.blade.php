<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Documentation — LLM Resayil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/monokai-sublime.min.css">
    <style>
        :root {
            --bg-primary: #0f1115;
            --bg-secondary: #0a0d14;
            --bg-card: #13161d;
            --gold: #d4af37;
            --gold-light: #eac558;
            --gold-muted: #8a702a;
            --border: #1e2230;
            --text-primary: #e0e5ec;
            --text-secondary: #a0a8b5;
            --text-muted: #6b7280;
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
            font-family: 'Inter', 'Tajawal', sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gold-light);
        }

        /* Navbar */
        nav {
            position: sticky;
            top: 0;
            height: 64px;
            backdrop-filter: blur(12px);
            background: rgba(15, 17, 21, 0.95);
            border-bottom: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .nav-brand {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--gold);
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--bg-primary);
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
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

        /* Layout */
        .docs-layout {
            display: flex;
            padding-top: 64px;
            min-height: calc(100vh - 64px);
        }

        .docs-sidebar {
            width: 260px;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            background: var(--bg-primary);
            border-right: 1px solid var(--border);
            padding: 2rem 1rem;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 6px;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .sidebar-nav a:hover {
            color: var(--gold);
            background: rgba(212, 175, 55, 0.05);
        }

        .sidebar-nav a.active {
            color: var(--gold);
            border-left-color: var(--gold);
            background: rgba(212, 175, 55, 0.1);
        }

        .docs-content {
            flex: 1;
            max-width: 860px;
            padding: 3rem 2rem;
            padding-right: 3rem;
        }

        /* Sections */
        section {
            margin-bottom: 4rem;
            scroll-margin-top: 80px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--gold);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            margin-top: 2rem;
        }

        h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        /* Code Blocks */
        .code-block-wrapper {
            position: relative;
            margin: 1.5rem 0;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        pre {
            background: var(--bg-card) !important;
            padding: 1.5rem;
            overflow-x: auto;
            border: none !important;
        }

        pre code {
            background: transparent !important;
            border: none;
            color: var(--text-primary);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        code {
            color: var(--gold);
            background: rgba(212, 175, 55, 0.1);
            border-radius: 4px;
            padding: 2px 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        pre code {
            color: inherit;
            background: transparent;
            padding: 0;
        }

        .copy-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(212, 175, 55, 0.2);
            color: var(--gold);
            border: 1px solid var(--gold-muted);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .copy-btn:hover {
            background: rgba(212, 175, 55, 0.3);
            border-color: var(--gold);
        }

        /* Inline Code */
        p code, li code {
            padding: 2px 6px;
        }

        /* Lists */
        ol, ul {
            margin-left: 2rem;
            margin-bottom: 1rem;
        }

        li {
            margin-bottom: 0.75rem;
            color: var(--text-secondary);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: var(--bg-card);
            color: var(--gold);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Callout Boxes */
        .callout {
            background: rgba(212, 175, 55, 0.05);
            border-left: 4px solid var(--gold);
            border-radius: 6px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .callout strong {
            color: var(--gold);
        }

        /* Error Cards */
        .error-card {
            background: var(--bg-card);
            border-left: 4px solid;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .error-card.error-401 {
            border-left-color: #ef4444;
        }

        .error-card.error-402 {
            border-left-color: #f59e0b;
        }

        .error-card.error-429 {
            border-left-color: #f97316;
        }

        .error-card.error-503 {
            border-left-color: #3b82f6;
        }

        .error-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .error-card.error-401 .error-badge {
            color: #ef4444;
        }

        .error-card.error-402 .error-badge {
            color: #f59e0b;
        }

        .error-card.error-429 .error-badge {
            color: #f97316;
        }

        .error-card.error-503 .error-badge {
            color: #3b82f6;
        }

        .error-card h4 {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin: 0.5rem 0 1rem;
        }

        /* Tabs */
        .tab-switcher {
            display: flex;
            gap: 0;
            border-bottom: 2px solid var(--border);
            margin: 2rem 0 1.5rem;
        }

        .tab-btn {
            background: transparent;
            color: var(--text-secondary);
            border: none;
            padding: 1rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: color 0.2s ease;
        }

        .tab-btn:hover {
            color: var(--gold);
        }

        .tab-btn.active {
            color: var(--gold);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gold);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            border-top: 1px solid var(--border);
            margin-top: 4rem;
        }

        footer a {
            color: var(--gold);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Sidebar Toggle (mobile) */
        #sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--gold);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .docs-layout {
                flex-direction: column;
            }

            .docs-sidebar {
                display: none;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                width: 100%;
                height: auto;
                max-height: 400px;
                border-right: none;
                border-bottom: 1px solid var(--border);
                z-index: 999;
            }

            .docs-sidebar.open {
                display: block;
            }

            #sidebar-toggle {
                display: block;
            }

            .docs-content {
                max-width: 100%;
                padding: 2rem 1rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            h2 {
                font-size: 1.4rem;
            }

            nav {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a class="nav-brand" href="/">⚡ LLM Resayil</a>
        <button id="sidebar-toggle">≡</button>
        <div class="nav-links">
            @if(auth()->check())
                <a href="/dashboard" class="nav-link">Dashboard</a>
            @else
                <a href="/login" class="nav-link">Login</a>
                <a href="/register" class="btn-gold">Get Started</a>
            @endif
        </div>
    </nav>

    <!-- Layout -->
    <div class="docs-layout">
        <!-- Sidebar -->
        <aside class="docs-sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#getting-started" class="active">Getting Started</a></li>
                    <li><a href="#authentication">Authentication</a></li>
                    <li><a href="#api-reference">API Reference</a></li>
                    <li><a href="#models">Models</a></li>
                    <li><a href="#billing">Billing & Credits</a></li>
                    <li><a href="#rate-limits">Rate Limits</a></li>
                    <li><a href="#errors">Error Codes</a></li>
                    <li><a href="#examples">Code Examples</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="docs-content">
            <!-- Getting Started -->
            <section id="getting-started">
                <h1>Getting Started</h1>
                <p>LLM Resayil is an OpenAI-compatible API that lets you access 45+ open-source and cloud LLM models with flexible billing. Any OpenAI SDK works seamlessly by overriding the base URL.</p>

                <h2 style="margin-top: 1.5rem;">Setup in 5 Steps</h2>
                <ol>
                    <li><strong>Register</strong> at <a href="/register" style="color: var(--gold); text-decoration: none;">llm.resayil.io/register</a></li>
                    <li><strong>Subscribe</strong> at <a href="/billing/plans" style="color: var(--gold); text-decoration: none;">/billing/plans</a> — choose a plan or start your free 7-day trial</li>
                    <li><strong>Create API Key</strong> at <a href="/api-keys" style="color: var(--gold); text-decoration: none;">/api-keys</a> — click "Create Key" and save it (shown only once)</li>
                    <li><strong>Make your first call:</strong></li>
                </ol>

                <div class="code-block-wrapper">
                    <pre><code class="language-bash">curl https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [{"role": "user", "content": "Hello!"}]
  }'</code></pre>
                </div>

                <ol start="5">
                    <li><strong>Track usage</strong> at <a href="/dashboard" style="color: var(--gold); text-decoration: none;">/dashboard</a></li>
                </ol>
            </section>

            <!-- Authentication -->
            <section id="authentication">
                <h2>Authentication</h2>
                <p>Every API request requires authentication using your API key in the <code>Authorization</code> header.</p>

                <h3>Header Format</h3>
                <div class="code-block-wrapper">
                    <pre><code class="language-bash">Authorization: Bearer YOUR_API_KEY</code></pre>
                </div>

                <h3>Getting Your API Key</h3>
                <p>
                    Visit <a href="/api-keys" style="color: var(--gold); text-decoration: none;">/api-keys</a> to create and manage keys.
                    Your key is shown only once at creation — store it securely.
                </p>

                <h3>Key Limits by Plan</h3>
                <table>
                    <tr>
                        <th>Plan</th>
                        <th>Max API Keys</th>
                    </tr>
                    <tr>
                        <td>Starter</td>
                        <td>1 key</td>
                    </tr>
                    <tr>
                        <td>Basic</td>
                        <td>2 keys</td>
                    </tr>
                    <tr>
                        <td>Pro</td>
                        <td>3 keys</td>
                    </tr>
                    <tr>
                        <td>Enterprise</td>
                        <td>Unlimited</td>
                    </tr>
                </table>

                <h3>Security Best Practices</h3>
                <ul>
                    <li>Never commit API keys to version control — use environment variables</li>
                    <li>Rotate keys regularly</li>
                    <li>Use separate keys per application or environment</li>
                    <li>Revoke keys immediately if compromised</li>
                    <li>Omit keys from error messages and logs</li>
                </ul>

                <div class="callout">
                    <strong>Authentication Error:</strong> Missing or invalid API key returns <code>401 Unauthorized</code>
                </div>
            </section>

            <!-- API Reference -->
            <section id="api-reference">
                <h2>API Reference</h2>
                <p>The base URL for all API requests is:</p>

                <div class="callout">
                    <code>https://llm.resayil.io/api/v1</code>
                </div>

                <h3>POST /api/v1/chat/completions</h3>
                <p>Send messages to a model and get a completion response. Supports streaming via the <code>stream</code> parameter.</p>

                <h4 style="font-size: 1.1rem; margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Request Parameters</h4>
                <table>
                    <tr>
                        <th>Parameter</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td><code>model</code></td>
                        <td>string</td>
                        <td>Yes</td>
                        <td>Model ID from <code>GET /api/v1/models</code></td>
                    </tr>
                    <tr>
                        <td><code>messages</code></td>
                        <td>array</td>
                        <td>Yes</td>
                        <td>Array of message objects: <code>{"role": "user|assistant", "content": "text"}</code></td>
                    </tr>
                    <tr>
                        <td><code>stream</code></td>
                        <td>boolean</td>
                        <td>No</td>
                        <td>Default <code>false</code>. Set to <code>true</code> for Server-Sent Events streaming</td>
                    </tr>
                    <tr>
                        <td><code>temperature</code></td>
                        <td>number</td>
                        <td>No</td>
                        <td>Sampling temperature (0–2), default 1.0</td>
                    </tr>
                    <tr>
                        <td><code>max_tokens</code></td>
                        <td>integer</td>
                        <td>No</td>
                        <td>Maximum tokens to generate</td>
                    </tr>
                </table>

                <h4 style="font-size: 1.1rem; margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Example Request</h4>
                <div class="code-block-wrapper">
                    <pre><code class="language-bash">curl https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {"role": "user", "content": "What is 2 + 2?"}
    ],
    "temperature": 0.7,
    "max_tokens": 100
  }'</code></pre>
                </div>

                <h4 style="font-size: 1.1rem; margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Example Response</h4>
                <div class="code-block-wrapper">
                    <pre><code class="language-json">{
  "id": "chatcmpl-abc123def456",
  "object": "chat.completion",
  "created": 1740000000,
  "model": "llama3.2:3b",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "2 + 2 equals 4."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 24,
    "completion_tokens": 9,
    "total_tokens": 33
  }
}</code></pre>
                </div>

                <h3>GET /api/v1/models</h3>
                <p>List all available models. Returns an array of model objects with metadata.</p>

                <h4 style="font-size: 1.1rem; margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Example Response</h4>
                <div class="code-block-wrapper">
                    <pre><code class="language-json">{
  "object": "list",
  "data": [
    {
      "id": "llama3.2:3b",
      "object": "model",
      "owned_by": "meta",
      "context_window": 8192,
      "credit_cost_multiplier": 1,
      "is_active": true
    },
    {
      "id": "qwen2.5:7b",
      "object": "model",
      "owned_by": "alibaba",
      "context_window": 131072,
      "credit_cost_multiplier": 1,
      "is_active": true
    },
    {
      "id": "claude-3-sonnet-cloud",
      "object": "model",
      "owned_by": "anthropic",
      "context_window": 200000,
      "credit_cost_multiplier": 2,
      "is_active": true
    }
  ]
}</code></pre>
                </div>
            </section>

            <!-- Models -->
            <section id="models">
                <h2>Models</h2>
                <p>Access 45+ LLM models spanning local open-source and cloud providers. Fetch available models using:</p>
                <div class="code-block-wrapper">
                    <pre><code class="language-bash">curl https://llm.resayil.io/api/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
                </div>

                <h3>Model Families</h3>
                <table>
                    <tr>
                        <th>Family</th>
                        <th>Provider</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td><strong>Llama</strong></td>
                        <td>Meta</td>
                        <td>General-purpose chat and reasoning</td>
                    </tr>
                    <tr>
                        <td><strong>Qwen</strong></td>
                        <td>Alibaba</td>
                        <td>Multilingual chat and coding</td>
                    </tr>
                    <tr>
                        <td><strong>Gemma</strong></td>
                        <td>Google</td>
                        <td>Lightweight and efficient</td>
                    </tr>
                    <tr>
                        <td><strong>DeepSeek</strong></td>
                        <td>DeepSeek AI</td>
                        <td>Code and reasoning specialists</td>
                    </tr>
                    <tr>
                        <td><strong>Mistral</strong></td>
                        <td>Mistral AI</td>
                        <td>Fast, European open models</td>
                    </tr>
                    <tr>
                        <td><strong>GLM</strong></td>
                        <td>Zhipu AI</td>
                        <td>Chinese + English bilingual</td>
                    </tr>
                </table>

                <h3>Credit Costs</h3>
                <table>
                    <tr>
                        <th>Model Type</th>
                        <th>Credits per Token</th>
                    </tr>
                    <tr>
                        <td>Local models</td>
                        <td>1 credit</td>
                    </tr>
                    <tr>
                        <td>Cloud models</td>
                        <td>2 credits</td>
                    </tr>
                </table>

                <p>Each API request consumes credits based on total tokens (prompt + completion) multiplied by the model's credit multiplier. Check your model list response for the <code>credit_cost_multiplier</code> field.</p>
            </section>

            <!-- Billing & Credits -->
            <section id="billing">
                <h2>Billing & Credits</h2>
                <p>LLM Resayil uses a credit-based system. Each subscription plan includes monthly credits, and additional credits can be purchased via top-up packs.</p>

                <h3>Credit Costs by Model Type</h3>
                <table>
                    <tr>
                        <th>Model Type</th>
                        <th>Credits per Token</th>
                    </tr>
                    <tr>
                        <td>Local models (Llama, Qwen, etc.)</td>
                        <td>1 credit</td>
                    </tr>
                    <tr>
                        <td>Cloud models (Claude, GPT, etc.)</td>
                        <td>2 credits</td>
                    </tr>
                </table>

                <h3>Monthly Plans</h3>
                <table>
                    <tr>
                        <th>Plan</th>
                        <th>Monthly Price</th>
                        <th>Credits Included</th>
                        <th>Rate Limit</th>
                    </tr>
                    <tr>
                        <td>Starter</td>
                        <td>15 KWD</td>
                        <td>5,000</td>
                        <td>10 req/min</td>
                    </tr>
                    <tr>
                        <td>Basic</td>
                        <td>25 KWD</td>
                        <td>15,000</td>
                        <td>10 req/min</td>
                    </tr>
                    <tr>
                        <td>Pro</td>
                        <td>45 KWD</td>
                        <td>50,000</td>
                        <td>30 req/min</td>
                    </tr>
                    <tr>
                        <td>Enterprise</td>
                        <td>Contact sales</td>
                        <td>Custom</td>
                        <td>60 req/min</td>
                    </tr>
                </table>

                <h3>Free Trial</h3>
                <p>New accounts receive a 7-day free trial with 5,000 trial credits. No payment method required.</p>

                <h3>Credit Top-ups</h3>
                <p>When your monthly credits run out, purchase additional credits:</p>
                <table>
                    <tr>
                        <th>Credits</th>
                        <th>Price</th>
                    </tr>
                    <tr>
                        <td>5,000</td>
                        <td>2 KWD</td>
                    </tr>
                    <tr>
                        <td>15,000</td>
                        <td>5 KWD</td>
                    </tr>
                    <tr>
                        <td>50,000</td>
                        <td>15 KWD</td>
                    </tr>
                </table>

                <div class="callout">
                    <strong>Out of Credits:</strong> API requests return <code>402 Payment Required</code> when your account credits are depleted. Top up at <a href="/billing/plans" style="color: var(--gold); text-decoration: none;">/billing/plans</a> to resume access.
                </div>

                <h3>Monitoring Usage</h3>
                <p>Track your credit balance and usage breakdown in your <a href="/dashboard" style="color: var(--gold); text-decoration: none;">dashboard</a>.</p>
            </section>

            <!-- Rate Limits -->
            <section id="rate-limits">
                <h2>Rate Limits</h2>
                <p>API requests are rate-limited on a per-key basis using a rolling 1-minute window.</p>

                <h3>Rate Limits by Plan</h3>
                <table>
                    <tr>
                        <th>Plan</th>
                        <th>Requests per Minute</th>
                    </tr>
                    <tr>
                        <td>Starter</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Basic</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Pro</td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td>Enterprise</td>
                        <td>60</td>
                    </tr>
                </table>

                <h3>Exceeding Limits</h3>
                <p>When you exceed your rate limit, the API returns a <code>429 Too Many Requests</code> response:</p>
                <div class="code-block-wrapper">
                    <pre><code class="language-json">{
  "error": {
    "code": "429",
    "message": "Rate limit exceeded. Max 10 requests per minute."
  }
}</code></pre>
                </div>

                <h3>Recommended Strategy</h3>
                <p>Implement exponential backoff when receiving <code>429</code> responses:</p>
                <ul>
                    <li>First retry: wait 1 second</li>
                    <li>Second retry: wait 2 seconds</li>
                    <li>Third retry: wait 4 seconds</li>
                    <li>And so on, doubling each time</li>
                </ul>

                <h3>Upgrade Your Plan</h3>
                <p>Need higher limits? <a href="/billing/plans" style="color: var(--gold); text-decoration: none;">upgrade your subscription</a> or contact support for Enterprise tier options.</p>
            </section>

            <!-- Error Codes -->
            <section id="errors">
                <h2>Error Codes</h2>
                <p>All error responses follow this format:</p>
                <div class="code-block-wrapper">
                    <pre><code class="language-json">{
  "error": {
    "code": "ERROR_CODE",
    "message": "Human-readable error message"
  }
}</code></pre>
                </div>

                <h3>Common Errors</h3>

                <div class="error-card error-401">
                    <div class="error-badge">401</div>
                    <h4>Unauthorized</h4>
                    <p><strong>When it occurs:</strong> Missing, invalid, or expired API key.</p>
                    <p><strong>Example response:</strong></p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-json">{
  "error": {
    "code": "unauthorized",
    "message": "Invalid or missing API key"
  }
}</code></pre>
                    </div>
                    <p><strong>Resolution:</strong> Verify your API key at <a href="/api-keys" style="color: var(--gold); text-decoration: none;">/api-keys</a> or generate a new one.</p>
                </div>

                <div class="error-card error-402">
                    <div class="error-badge">402</div>
                    <h4>Payment Required</h4>
                    <p><strong>When it occurs:</strong> Account has insufficient credits to process the request.</p>
                    <p><strong>Example response:</strong></p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-json">{
  "error": {
    "code": "insufficient_credits",
    "message": "Insufficient credits. Please top up your account."
  }
}</code></pre>
                    </div>
                    <p><strong>Resolution:</strong> Top up credits at <a href="/billing/plans" style="color: var(--gold); text-decoration: none;">/billing/plans</a>.</p>
                </div>

                <div class="error-card error-429">
                    <div class="error-badge">429</div>
                    <h4>Too Many Requests</h4>
                    <p><strong>When it occurs:</strong> You've exceeded your plan's rate limit within the current 1-minute window.</p>
                    <p><strong>Example response:</strong></p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-json">{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Rate limit exceeded. Max 10 requests per minute."
  }
}</code></pre>
                    </div>
                    <p><strong>Resolution:</strong> Retry with exponential backoff, or upgrade your plan for higher limits.</p>
                </div>

                <div class="error-card error-503">
                    <div class="error-badge">503</div>
                    <h4>Service Unavailable</h4>
                    <p><strong>When it occurs:</strong> The API or a model service is temporarily unavailable.</p>
                    <p><strong>Example response:</strong></p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-json">{
  "error": {
    "code": "service_unavailable",
    "message": "Model service is temporarily unavailable. Please retry shortly."
  }
}</code></pre>
                    </div>
                    <p><strong>Resolution:</strong> Wait a few moments and retry. Check system status for ongoing incidents.</p>
                </div>
            </section>

            <!-- Code Examples -->
            <section id="examples">
                <h2>Code Examples</h2>
                <p>Below are complete working examples in multiple languages and platforms. Replace <code>YOUR_API_KEY</code> with your actual key.</p>

                <div class="tab-switcher">
                    <button class="tab-btn active" data-tab="curl">cURL</button>
                    <button class="tab-btn" data-tab="python">Python</button>
                    <button class="tab-btn" data-tab="javascript">JavaScript</button>
                    <button class="tab-btn" data-tab="n8n">n8n</button>
                </div>

                <div id="tab-curl" class="tab-panel active">
                    <h3>cURL</h3>
                    <p>Make API calls directly from the command line:</p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-bash">curl https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {"role": "user", "content": "Tell me a joke."}
    ]
  }'</code></pre>
                    </div>
                </div>

                <div id="tab-python" class="tab-panel">
                    <h3>Python</h3>
                    <p>Use the official OpenAI Python library with LLM Resayil:</p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-python"># pip install openai
from openai import OpenAI

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[
        {"role": "user", "content": "Tell me a joke."}
    ]
)

print(response.choices[0].message.content)</code></pre>
                    </div>
                </div>

                <div id="tab-javascript" class="tab-panel">
                    <h3>JavaScript</h3>
                    <p>Use the official OpenAI JavaScript library:</p>
                    <div class="code-block-wrapper">
                        <pre><code class="language-javascript">// npm install openai
import OpenAI from 'openai';

const client = new OpenAI({
  apiKey: 'YOUR_API_KEY',
  baseURL: 'https://llm.resayil.io/api/v1',
});

const completion = await client.chat.completions.create({
  model: 'llama3.2:3b',
  messages: [
    { role: 'user', content: 'Tell me a joke.' },
  ],
});

console.log(completion.choices[0].message.content);</code></pre>
                    </div>
                </div>

                <div id="tab-n8n" class="tab-panel">
                    <h3>n8n Workflow Integration</h3>
                    <p>Integrate LLM Resayil into n8n workflows using the HTTP Request node:</p>
                    <ol>
                        <li>Add an <strong>HTTP Request</strong> node to your workflow</li>
                        <li>Set <strong>Method</strong> to <code>POST</code></li>
                        <li>Set <strong>URL</strong> to <code>https://llm.resayil.io/api/v1/chat/completions</code></li>
                        <li>Go to <strong>Authentication</strong> tab, select <strong>Generic Credential Type</strong></li>
                        <li>Choose <strong>Header Auth</strong>:
                            <ul>
                                <li>Name: <code>Authorization</code></li>
                                <li>Value: <code>Bearer YOUR_API_KEY</code></li>
                            </ul>
                        </li>
                        <li>In the <strong>Body</strong> tab, set <strong>Content Type</strong> to <code>JSON</code></li>
                        <li>Paste the request body:</li>
                    </ol>

                    <div class="code-block-wrapper">
                        <pre><code class="language-json">@verbatim
{
  "model": "llama3.2:3b",
  "messages": [
    {
      "role": "user",
      "content": "{{ $json.input }}"
    }
  ]
}
@endverbatim</code></pre>
                    </div>

                    <ol start="8">
                        <li>To extract the response, reference:
                            <div class="code-block-wrapper">
                                <pre><code>@verbatim{{ $json.choices[0].message.content }}@endverbatim</code></pre>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>

            <!-- Footer -->
            <footer>
                <p>LLM Resayil API Docs · v1 · <a href="https://llm.resayil.io">llm.resayil.io</a></p>
            </footer>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        // 1. Initialize highlight.js
        hljs.highlightAll();

        // 2. Copy buttons for code blocks
        document.querySelectorAll('.code-block-wrapper').forEach(wrapper => {
            const btn = document.createElement('button');
            btn.className = 'copy-btn';
            btn.textContent = 'Copy';
            btn.onclick = () => {
                const code = wrapper.querySelector('code');
                navigator.clipboard.writeText(code.innerText).then(() => {
                    btn.textContent = 'Copied!';
                    setTimeout(() => btn.textContent = 'Copy', 2000);
                });
            };
            wrapper.appendChild(btn);
        });

        // 3. Scroll spy with IntersectionObserver
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.sidebar-nav a');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    const active = document.querySelector(`.sidebar-nav a[href="#${entry.target.id}"]`);
                    if (active) active.classList.add('active');
                }
            });
        }, { threshold: 0.3 });
        sections.forEach(s => observer.observe(s));

        // 4. Tab switcher
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('tab-' + tab).classList.add('active');
            });
        });

        // 5. Mobile sidebar toggle
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.docs-sidebar');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
        }
    </script>
</body>
</html>
