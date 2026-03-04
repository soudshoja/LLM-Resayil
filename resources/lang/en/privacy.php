<?php

return [
    // Page
    'page_title' => 'Privacy Policy',
    'eyebrow' => 'Legal',
    'last_updated_label' => 'Last updated:',
    'last_updated_date' => 'March 2026',
    'read_time' => '~6 min read',
    'brand_name' => 'LLM Resayil',
    'version_label' => 'Version',
    'version_number' => '1.0',

    // Sidebar TOC
    'toc_header' => 'Contents',
    'toc_introduction' => '1. Introduction',
    'toc_data_collected' => '2. Data We Collect',
    'toc_how_used' => '3. How We Use Data',
    'toc_api_content' => '4. API Request Content',
    'toc_third_party' => '5. Third-Party Services',
    'toc_retention' => '6. Data Retention',
    'toc_security' => '7. Security',
    'toc_rights' => '8. Your Rights',
    'toc_updates' => '9. Policy Updates',
    'toc_contact' => '10. Contact',

    // Section 01 - Introduction
    'section_introduction' => 'Introduction',
    'introduction_text' => 'LLM Resayil ("we", "us", "our") is committed to protecting your privacy. This policy explains what data we collect, how we use it, and your rights regarding that data when you use our API and web portal at llm.resayil.io.',

    // Section 02 - Data We Collect
    'section_data_collected' => 'Data We Collect',
    'table_category' => 'Category',
    'table_data' => 'Data',
    'table_purpose' => 'Purpose',
    'category_account' => 'Account',
    'data_account' => 'Name, email, phone number',
    'purpose_account' => 'Registration, OTP verification, support',
    'category_usage_logs' => 'Usage logs',
    'data_usage_logs' => 'Model name, token count, credits deducted, timestamp',
    'purpose_usage_logs' => 'Billing, dashboard, rate limiting',
    'category_payment' => 'Payment',
    'data_payment' => 'Transaction ID, payment status (from MyFatoorah)',
    'purpose_payment' => 'Credit top-up confirmation',
    'category_session' => 'Session',
    'data_session' => 'Session cookies, locale preference',
    'purpose_session' => 'Authentication, UI language',
    'no_tracking' => 'We do <strong style="color:var(--legal-text)">not</strong> collect advertising data, browser fingerprints, or third-party tracking identifiers.',

    // Section 03 - How We Use Your Data
    'section_how_used' => 'How We Use Your Data',
    'use_authenticate' => 'Authenticate your account and verify your phone number via WhatsApp OTP',
    'use_payments' => 'Process payments and top-up credits via MyFatoorah / KNET',
    'use_track_api' => 'Track API usage for billing, rate limits, and dashboard display',
    'use_whatsapp' => 'Send transactional WhatsApp messages (OTP codes, payment confirmations)',
    'use_support' => 'Respond to support enquiries submitted via the contact form',
    'no_sell_data' => '<strong>We do not sell your data.</strong> We do not use your data for advertising, profiling, or any purpose beyond operating the Service.',

    // Section 04 - API Request Content
    'section_api_content' => 'API Request Content',
    'api_content_text_1' => 'We do <strong style="color:var(--legal-text)">not</strong> store the content of your API requests (prompts) or model responses. Usage logs record only metadata — model name, token count, credits deducted, and timestamp.',
    'api_content_text_2' => 'Your prompt data is forwarded directly to the model inference server and is not persisted to any database or log file.',

    // Section 05 - Third-Party Services
    'section_third_party' => 'Third-Party Services',
    'tp_myfatoorah_name' => 'MyFatoorah',
    'tp_myfatoorah_desc' => 'Payment processing via KNET and credit card. Card details are handled entirely by MyFatoorah — we never receive or store them. Subject to <a href="https://www.myfatoorah.com/privacy-policy" target="_blank" style="color:var(--legal-gold)">MyFatoorah\'s privacy policy</a>.',
    'tp_resayil_name' => 'Resayil WhatsApp API',
    'tp_resayil_desc' => 'Used to send OTP codes and account notifications to your registered phone number. Phone numbers are shared with this service only for transactional messaging.',
    'tp_google_fonts_name' => 'Google Fonts',
    'tp_google_fonts_desc' => 'IBM Plex Sans and JetBrains Mono are loaded from Google Fonts CDN on the web portal. Subject to Google\'s privacy policy.',

    // Section 06 - Data Retention
    'section_retention' => 'Data Retention',
    'retention_account' => 'Account data — retained while your account is active',
    'retention_usage' => 'Usage logs — retained for up to 12 months for billing and support',
    'retention_otp' => 'OTP codes — expire after 10 minutes and are deleted after use or expiry',
    'retention_session' => 'Session data — expires after 2 hours of inactivity',
    'retention_deletion' => 'You may request deletion of your account and associated data by contacting us via the contact form. We will process requests within 14 business days.',

    // Section 07 - Security
    'section_security' => 'Security',
    'security_text' => 'All data is transmitted over HTTPS (TLS 1.2+). API keys are hashed using bcrypt and never stored in plain text. Database access is restricted to authorised server processes only. We do not store payment credentials.',

    // Section 08 - Your Rights
    'section_rights' => 'Your Rights',
    'rights_text' => 'You have the right to access, correct, or delete your personal data at any time. To exercise these rights, use our <a href="/contact" style="color:var(--legal-gold)">contact form</a>. We will respond within 14 business days.',

    // Section 09 - Policy Updates
    'section_updates' => 'Policy Updates',
    'updates_text' => 'We may update this policy periodically. The "last updated" date above will reflect changes. Continued use of the Service after changes constitutes acceptance of the updated policy. Material changes will be communicated by email or portal notice.',

    // Section 10 - Contact
    'section_contact' => 'Contact',
    'contact_text' => 'Privacy questions or data requests: <a href="/contact" style="color:var(--legal-gold)">contact form</a>.',

    // Footer
    'footer_about' => 'About',
    'footer_terms' => 'Terms of Service',
    'footer_contact' => 'Contact',
];
