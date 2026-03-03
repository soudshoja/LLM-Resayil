@extends('layouts.app')

@section('title', 'Terms of Service')

@push('styles')
<style>
    .legal-wrap { max-width: 820px; margin: 0 auto; padding: 3rem 2rem 4rem; }
    .legal-wrap h1 { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .legal-meta { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
    .legal-section { margin-bottom: 2.5rem; }
    .legal-section h2 { font-size: 1.1rem; font-weight: 700; color: var(--gold); margin-bottom: 0.75rem; padding-left: 0.75rem; border-left: 3px solid rgba(212,175,55,0.4); }
    .legal-section p { color: var(--text-secondary); line-height: 1.8; margin-bottom: 0.75rem; font-size: 0.95rem; }
    .legal-section ul { color: var(--text-secondary); line-height: 1.8; margin: 0.5rem 0 0.75rem 1.5rem; font-size: 0.95rem; }
    .legal-section ul li { margin-bottom: 0.35rem; }
    .legal-footer { border-top: 1px solid var(--border); padding-top: 1.5rem; text-align: center; }
</style>
@endpush

@section('content')
<div class="legal-wrap">
    <h1>Terms of Service</h1>
    <div class="legal-meta">Last updated: March 2026 &nbsp;·&nbsp; LLM Resayil</div>

    <div class="legal-section">
        <h2>1. Acceptance of Terms</h2>
        <p>By creating an account or using the LLM Resayil API or web portal ("Service"), you agree to be bound by these Terms of Service. If you do not agree, do not use the Service.</p>
    </div>

    <div class="legal-section">
        <h2>2. Description of Service</h2>
        <p>LLM Resayil provides access to large language models (LLMs) via an OpenAI-compatible REST API. The Service includes:</p>
        <ul>
            <li>API access to 45+ AI models (local GPU inference and cloud proxies)</li>
            <li>A web portal for managing API keys, credits, and usage</li>
            <li>Credit-based billing processed via MyFatoorah (KNET / credit card)</li>
            <li>Account verification via WhatsApp OTP</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>3. Account Registration</h2>
        <p>You must provide a valid phone number for WhatsApp OTP verification to create an account. You are responsible for maintaining the confidentiality of your API keys and account credentials. You must notify us immediately of any unauthorised use.</p>
    </div>

    <div class="legal-section">
        <h2>4. Credits and Payments</h2>
        <p>Access to the API is credit-based. Credits are purchased in KWD via KNET or credit card through MyFatoorah. Current pricing:</p>
        <ul>
            <li>5,000 credits — 2.000 KWD</li>
            <li>15,000 credits — 5.000 KWD</li>
            <li>50,000 credits — 15.000 KWD</li>
        </ul>
        <p>Credits are non-refundable once used. Unused credits do not expire. Prices are subject to change with 14 days notice.</p>
    </div>

    <div class="legal-section">
        <h2>5. API Usage and Rate Limits</h2>
        <p>API usage is subject to per-minute rate limits based on your account tier. Attempting to circumvent rate limits or abuse the Service (e.g., automated scraping, DoS attacks, or reselling access without authorisation) will result in immediate account suspension.</p>
        <p>Credit multipliers: Local models consume 1 credit per token. Cloud models consume 2 credits per token.</p>
    </div>

    <div class="legal-section">
        <h2>6. Prohibited Uses</h2>
        <p>You must not use the Service to:</p>
        <ul>
            <li>Generate illegal, harmful, or deceptive content</li>
            <li>Violate any applicable law or regulation</li>
            <li>Infringe intellectual property rights</li>
            <li>Attempt to reverse-engineer, probe, or exploit the Service infrastructure</li>
            <li>Resell or sublicense API access without written permission</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>7. Service Availability</h2>
        <p>We strive for high availability but do not guarantee uninterrupted access. The Service is provided "as is" without warranties of any kind. We are not liable for losses resulting from downtime, model output errors, or data loss.</p>
    </div>

    <div class="legal-section">
        <h2>8. Termination</h2>
        <p>We reserve the right to suspend or terminate accounts that violate these terms, at our sole discretion, with or without notice. You may close your account at any time by contacting us. Unused credits are non-refundable upon termination.</p>
    </div>

    <div class="legal-section">
        <h2>9. Changes to Terms</h2>
        <p>We may update these terms at any time. Continued use of the Service after changes constitutes acceptance of the new terms. Material changes will be communicated via email or a notice on the portal.</p>
    </div>

    <div class="legal-section">
        <h2>10. Contact</h2>
        <p>For questions about these terms, please use our <a href="/contact" style="color:var(--gold)">contact form</a>.</p>
    </div>

    <div class="legal-footer">
        <p class="text-muted text-sm">
            <a href="/about" style="color:var(--text-muted)">About</a> ·
            <a href="/privacy-policy" style="color:var(--text-muted)">Privacy Policy</a> ·
            <a href="/contact" style="color:var(--text-muted)">Contact</a>
        </p>
    </div>
</div>
@endsection
