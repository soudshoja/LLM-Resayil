@extends('layouts.app')

@section('title', 'Privacy Policy')

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
    <h1>Privacy Policy</h1>
    <div class="legal-meta">Last updated: March 2026 &nbsp;·&nbsp; LLM Resayil</div>

    <div class="legal-section">
        <h2>1. Introduction</h2>
        <p>LLM Resayil ("we", "us", "our") is committed to protecting your privacy. This policy explains what data we collect, how we use it, and your rights regarding that data when you use our API and web portal.</p>
    </div>

    <div class="legal-section">
        <h2>2. Data We Collect</h2>
        <p><strong style="color:var(--text-primary)">Account data:</strong> When you register, we collect your name, email address, and phone number (for WhatsApp OTP verification).</p>
        <p><strong style="color:var(--text-primary)">Usage data:</strong> Every API call generates a usage log containing the model used, token count, credits deducted, and timestamp. No message content is stored.</p>
        <p><strong style="color:var(--text-primary)">Payment data:</strong> Payments are processed by MyFatoorah. We receive a transaction ID and payment status. We do not store card numbers or KNET credentials.</p>
        <p><strong style="color:var(--text-primary)">Session data:</strong> Standard session cookies are used to keep you logged in. We do not use tracking or advertising cookies.</p>
    </div>

    <div class="legal-section">
        <h2>3. How We Use Your Data</h2>
        <ul>
            <li>To authenticate your account and verify your phone number via WhatsApp OTP</li>
            <li>To process payments and top-up credits via MyFatoorah/KNET</li>
            <li>To track API usage and enforce rate limits and credit balances</li>
            <li>To send transactional WhatsApp messages (OTP codes, payment confirmations)</li>
            <li>To respond to support enquiries submitted via the contact form</li>
        </ul>
        <p>We do not sell your data to third parties. We do not use your data for advertising.</p>
    </div>

    <div class="legal-section">
        <h2>4. API Request Content</h2>
        <p>We do <strong style="color:var(--text-primary)">not</strong> store the contents of your API requests (prompts) or model responses. Usage logs record only metadata (model name, token count, credits used, timestamp). Your prompt data is forwarded directly to the model inference server and not persisted.</p>
    </div>

    <div class="legal-section">
        <h2>5. Third-Party Services</h2>
        <ul>
            <li><strong style="color:var(--text-primary)">MyFatoorah:</strong> Payment processing (KNET / credit card). Subject to MyFatoorah's privacy policy.</li>
            <li><strong style="color:var(--text-primary)">Resayil WhatsApp API:</strong> Used to send OTP codes and notifications to your registered phone number.</li>
            <li><strong style="color:var(--text-primary)">Google Fonts:</strong> Loaded on the web portal for typography. Subject to Google's privacy policy.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>6. Data Retention</h2>
        <p>Account data is retained for as long as your account is active. Usage logs are retained for up to 12 months for billing and support purposes. OTP codes expire after 10 minutes and are deleted after use.</p>
        <p>You may request deletion of your account and associated data by contacting us.</p>
    </div>

    <div class="legal-section">
        <h2>7. Security</h2>
        <p>All data is transmitted over HTTPS. API keys are hashed and never stored in plain text. Access to production databases is restricted to authorised personnel only.</p>
    </div>

    <div class="legal-section">
        <h2>8. Your Rights</h2>
        <p>You have the right to access, correct, or delete your personal data. To exercise these rights, please contact us via the <a href="/contact" style="color:var(--gold)">contact form</a>. We will respond within 14 business days.</p>
    </div>

    <div class="legal-section">
        <h2>9. Changes to This Policy</h2>
        <p>We may update this policy periodically. The "last updated" date at the top will reflect changes. Continued use of the Service after changes constitutes acceptance of the updated policy.</p>
    </div>

    <div class="legal-section">
        <h2>10. Contact</h2>
        <p>Privacy questions or data requests: <a href="/contact" style="color:var(--gold)">contact form</a>.</p>
    </div>

    <div class="legal-footer">
        <p class="text-muted text-sm">
            <a href="/about" style="color:var(--text-muted)">About</a> ·
            <a href="/terms-of-service" style="color:var(--text-muted)">Terms of Service</a> ·
            <a href="/contact" style="color:var(--text-muted)">Contact</a>
        </p>
    </div>
</div>
@endsection
