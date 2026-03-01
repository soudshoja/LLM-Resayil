@extends('layouts.app')

@section('title', 'LLM Resayil - OpenAI-Compatible LLM API')

@push('styles')
<style>
    body { background: var(--bg-secondary); }
    .hero { text-align: center; padding: 6rem 2rem 4rem; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 400px; background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.08) 0%, transparent 70%); pointer-events: none; }
    .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.25); color: var(--gold); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; }
    .hero h1 { font-size: 3rem; font-weight: 700; line-height: 1.15; margin-bottom: 1.25rem; max-width: 700px; margin-left: auto; margin-right: auto; }
    .hero h1 span { background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .hero p { font-size: 1.125rem; color: var(--text-secondary); max-width: 560px; margin: 0 auto 2rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .section { padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; }
    .section-title { text-align: center; margin-bottom: 3rem; }
    .section-title h2 { font-size: 1.875rem; font-weight: 700; margin-bottom: 0.75rem; }
    .section-title p { color: var(--text-secondary); font-size: 1rem; max-width: 500px; margin: 0 auto; }
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .step { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; text-align: center; position: relative; }
    .step-num { width: 44px; height: 44px; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; margin: 0 auto 1rem; }
    .step h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .step p { color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6; }
    .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .pricing-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; position: relative; transition: transform 0.2s, box-shadow 0.2s; }
    .pricing-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
    .pricing-card.featured { border-color: rgba(212,175,55,0.4); background: linear-gradient(160deg, rgba(212,175,55,0.06) 0%, var(--bg-card) 60%); }
    .pricing-card.featured::before { content: 'Most Popular'; position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0a0d14; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .plan-name { font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .plan-price { font-size: 2.5rem; font-weight: 700; color: var(--gold); margin-bottom: 0.25rem; }
    .plan-price span { font-size: 1rem; color: var(--text-secondary); font-weight: 400; }
    .plan-features { list-style: none; margin: 1.25rem 0 1.75rem; display: flex; flex-direction: column; gap: 0.6rem; }
    .plan-features li { font-size: 0.875rem; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem; }
    .plan-features li::before { content: '✓'; color: var(--gold); font-weight: 700; }
    .code-block { background: #0a0d14; border: 1px solid var(--border); border-radius: 10px; padding: 1.5rem; overflow-x: auto; font-family: monospace; font-size: 0.85rem; line-height: 1.7; color: #e0e5ec; }
    .code-block .comment { color: #4b5563; }
    .code-block .string { color: #86efac; }
    .code-block .key { color: #93c5fd; }
    .models-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .model-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; }
    .model-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; }
    .model-meta { font-size: 0.75rem; color: var(--text-muted); }
    .divider { border: none; border-top: 1px solid var(--border); margin: 0; }
    .cta-section { text-align: center; padding: 5rem 2rem; background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, transparent 100%); border-top: 1px solid var(--border); }
    @media(max-width: 768px) { .hero h1 { font-size: 2rem; } .steps, .pricing-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">✦ OpenAI-Compatible API Gateway</div>
    <h1>Powerful LLMs, <span>Pay As You Go</span></h1>
    <p>Access state-of-the-art language models via an OpenAI-compatible API. Credit-based billing, automatic cloud failover, and enterprise team management — all in Kuwait Dinar.</p>
    <div class="hero-cta">
        <a href="/register" class="btn btn-gold" style="padding:0.75rem 2rem;font-size:1rem">Start Free Trial</a>
        <a href="#pricing" class="btn btn-outline" style="padding:0.75rem 2rem;font-size:1rem">View Pricing</a>
    </div>
</section>

<!-- How It Works -->
<section class="section">
    <div class="section-title">
        <h2>How It Works</h2>
        <p>Three simple steps to access powerful AI models</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <h3>Register & Top Up</h3>
            <p>Create an account, choose a subscription tier, and top up with credits via MyFatoorah (KNET/card).</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h3>Get Your API Key</h3>
            <p>Generate an API key from your dashboard. Use it exactly like you would with OpenAI's API.</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h3>Make API Calls</h3>
            <p>Point your app to our endpoint. Local GPU processing, cloud failover when needed — all automatic.</p>
        </div>
    </div>
</section>

<hr class="divider">

<!-- Pricing -->
<section class="section" id="pricing">
    <div class="section-title">
        <h2>Simple, Transparent Pricing</h2>
        <p>All prices in Kuwaiti Dinar. Credits roll over month-to-month.</p>
    </div>
    <div class="pricing-grid">
        <div class="pricing-card">
            <div class="plan-name">Basic</div>
            <div class="plan-price">10 <span>KWD/mo</span></div>
            <ul class="plan-features">
                <li>15,000 credits/month</li>
                <li>Small & medium models</li>
                <li>10 requests/minute</li>
                <li>Standard queue priority</li>
                <li>API key management</li>
            </ul>
            <a href="/register" class="btn btn-outline" style="width:100%;justify-content:center">Get Started</a>
        </div>
        <div class="pricing-card featured">
            <div class="plan-name">Pro</div>
            <div class="plan-price">50 <span>KWD/mo</span></div>
            <ul class="plan-features">
                <li>75,000 credits/month</li>
                <li>All models + cloud access</li>
                <li>30 requests/minute</li>
                <li>Priority queue</li>
                <li>Cloud failover (2x credits)</li>
            </ul>
            <a href="/register" class="btn btn-gold" style="width:100%;justify-content:center">Get Started</a>
        </div>
        <div class="pricing-card">
            <div class="plan-name">Enterprise</div>
            <div class="plan-price">200 <span>KWD/mo</span></div>
            <ul class="plan-features">
                <li>300,000 credits/month</li>
                <li>All models, highest priority</li>
                <li>60 requests/minute</li>
                <li>Team management (10 seats)</li>
                <li>Dedicated support</li>
            </ul>
            <a href="/register" class="btn btn-outline" style="width:100%;justify-content:center">Contact Sales</a>
        </div>
    </div>
</section>

<hr class="divider">

<!-- Available Models -->
<section class="section" id="models">
    <div class="section-title">
        <h2>Available Models</h2>
        <p>Local GPU-accelerated models with automatic cloud failover</p>
    </div>
    <div class="models-grid">
        <div class="model-card">
            <div class="model-name">llama3.2:3b</div>
            <div class="model-meta">Small · Local GPU</div>
        </div>
        <div class="model-card">
            <div class="model-name">qwen2.5:7b</div>
            <div class="model-meta">Medium · Local GPU</div>
        </div>
        <div class="model-card">
            <div class="model-name">mistral:7b</div>
            <div class="model-meta">Medium · Local GPU</div>
        </div>
        <div class="model-card">
            <div class="model-name">llama3.1:70b</div>
            <div class="model-meta">Large · Cloud (Pro+)</div>
        </div>
        <div class="model-card">
            <div class="model-name">qwen2.5:72b</div>
            <div class="model-meta">Large · Cloud (Pro+)</div>
        </div>
        <div class="model-card">
            <div class="model-name">deepseek-r1:70b</div>
            <div class="model-meta">Large · Cloud (Pro+)</div>
        </div>
    </div>
</section>

<hr class="divider">

<!-- Code Example -->
<section class="section" id="docs">
    <div class="section-title">
        <h2>Drop-In Replacement</h2>
        <p>Works with any OpenAI-compatible SDK. Just change the base URL and API key.</p>
    </div>
    <div class="code-block">
<span class="comment"># Python example using openai SDK</span>
from openai import OpenAI

client = OpenAI(
    <span class="key">api_key</span>=<span class="string">"sk-resayil-your-key-here"</span>,
    <span class="key">base_url</span>=<span class="string">"https://llm.resayil.io/v1"</span>
)

response = client.chat.completions.create(
    <span class="key">model</span>=<span class="string">"qwen2.5:7b"</span>,
    <span class="key">messages</span>=[{<span class="string">"role"</span>: <span class="string">"user"</span>, <span class="string">"content"</span>: <span class="string">"Hello!"</span>}]
)
print(response.choices[0].message.content)
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2 style="font-size:2rem;font-weight:700;margin-bottom:0.75rem">Ready to get started?</h2>
    <p style="color:var(--text-secondary);margin-bottom:2rem">Join developers already using LLM Resayil. Pay with KNET or credit card.</p>
    <a href="/register" class="btn btn-gold" style="padding:0.85rem 2.5rem;font-size:1.05rem">Create Free Account</a>
</section>

@endsection
