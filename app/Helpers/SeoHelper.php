<?php

namespace App\Helpers;

class SeoHelper
{
    private static $metadata = [
        'welcome' => [
            'title' => 'LLM Resayil — OpenAI Alternative API',
            'description' => 'LLM Resayil: Affordable OpenAI-compatible API. 45+ models, pay-per-token. 10x cheaper than OpenAI. Start free with 1,000 credits.',
            'keywords' => 'openai alternative, cheap llm api, affordable ai, llm api, open source models',
            'ogImage' => 'https://llm.resayil.io/og-images/og-home.png',
            'ogType' => 'website',
        ],
        'docs' => [
            'title' => 'API Documentation — LLM Resayil',
            'description' => 'LLM Resayil API Reference: OpenAI-Compatible REST API. Authentication, models, code examples (Python, JS, cURL), rate limits, webhooks.',
            'keywords' => 'openai compatible api, llm api documentation, rest api, api reference',
            'ogImage' => 'https://llm.resayil.io/og-images/og-docs.png',
            'ogType' => 'website',
        ],
        'billing.plans' => [
            'title' => 'Pricing Plans — LLM Resayil',
            'description' => 'LLM Resayil Pricing: Pay-per-token billing. No monthly subscriptions. Free tier (1,000 credits). Plans from 2 KWD. Calculator included.',
            'keywords' => 'llm api pricing, pay per token, affordable pricing, credit plans',
            'ogImage' => 'https://llm.resayil.io/og-images/og-pricing.png',
            'ogType' => 'website',
        ],
        'credits' => [
            'title' => 'Credits System — LLM Resayil',
            'description' => 'Understand LLM Resayil credits system. How tokens are counted, pricing per model, credit expiration, and usage tracking.',
            'keywords' => 'credit system, token counting, usage tracking, billing',
            'ogImage' => 'https://llm.resayil.io/og-images/og-credits.png',
            'ogType' => 'website',
        ],
        'api-keys' => [
            'title' => 'API Keys — LLM Resayil',
            'description' => 'Manage your LLM Resayil API keys. Create, revoke, and monitor API key usage for your applications.',
            'keywords' => 'api keys, key management, authentication',
            'ogImage' => 'https://llm.resayil.io/og-images/og-api-keys.png',
            'ogType' => 'website',
        ],
        'dashboard' => [
            'title' => 'Dashboard — LLM Resayil',
            'description' => 'LLM Resayil Dashboard. Monitor API usage, check credit balance, view costs, and manage your account.',
            'keywords' => 'dashboard, usage analytics, cost tracking',
            'ogImage' => 'https://llm.resayil.io/og-images/og-dashboard.png',
            'ogType' => 'website',
        ],
        'profile' => [
            'title' => 'Profile Settings — LLM Resayil',
            'description' => 'Update your LLM Resayil profile. Manage personal information, phone number, and notification preferences.',
            'keywords' => 'profile settings, account management',
            'ogImage' => 'https://llm.resayil.io/og-images/og-profile.png',
            'ogType' => 'website',
        ],
        'contact' => [
            'title' => 'Contact Us — LLM Resayil',
            'description' => 'Get in touch with LLM Resayil support. Have questions? We\'re here to help with integration and general inquiries.',
            'keywords' => 'contact support, customer support, help',
            'ogImage' => 'https://llm.resayil.io/og-images/og-contact.png',
            'ogType' => 'website',
        ],
        'about' => [
            'title' => 'About Us — LLM Resayil',
            'description' => 'About LLM Resayil: Our mission is to provide affordable, accessible AI APIs for developers worldwide.',
            'keywords' => 'about company, company mission, ai provider',
            'ogImage' => 'https://llm.resayil.io/og-images/og-about.png',
            'ogType' => 'website',
        ],
        'login' => [
            'title' => 'Login — LLM Resayil',
            'description' => 'Log in to your LLM Resayil account. Access your API keys, usage analytics, and billing.',
            'keywords' => 'login, sign in, authentication',
            'ogImage' => 'https://llm.resayil.io/og-images/og-login.png',
            'ogType' => 'website',
        ],
        'register' => [
            'title' => 'Create Account — LLM Resayil',
            'description' => 'Sign up for LLM Resayil. Get 1,000 free credits to start building with AI. No credit card required.',
            'keywords' => 'signup, register, create account, free trial',
            'ogImage' => 'https://llm.resayil.io/og-images/og-register.png',
            'ogType' => 'website',
        ],
        'billing.payment-methods' => [
            'title' => 'Payment Methods — LLM Resayil',
            'description' => 'Manage your payment methods on LLM Resayil. Add, update, or remove payment cards securely.',
            'keywords' => 'payment methods, billing, payment management',
            'ogImage' => 'https://llm.resayil.io/og-images/og-payment-methods.png',
            'ogType' => 'website',
        ],
        'privacy-policy' => [
            'title' => 'Privacy Policy — LLM Resayil',
            'description' => 'LLM Resayil Privacy Policy. How we collect, use, and protect your data.',
            'keywords' => 'privacy policy, data protection',
            'ogImage' => 'https://llm.resayil.io/og-images/og-privacy.png',
            'ogType' => 'website',
        ],
        'terms-of-service' => [
            'title' => 'Terms of Service — LLM Resayil',
            'description' => 'LLM Resayil Terms of Service. Read our terms and conditions for using the platform.',
            'keywords' => 'terms of service, legal terms',
            'ogImage' => 'https://llm.resayil.io/og-images/og-terms.png',
            'ogType' => 'website',
        ],
        'admin.dashboard' => [
            'title' => 'Admin Dashboard — LLM Resayil',
            'description' => 'Admin Dashboard: Manage users, API keys, models, and system configuration.',
            'keywords' => 'admin panel, administration',
            'ogImage' => 'https://llm.resayil.io/og-images/og-admin.png',
            'ogType' => 'website',
        ],
        'teams.index' => [
            'title' => 'Teams — LLM Resayil',
            'description' => 'Manage team members and permissions for your LLM Resayil Enterprise account.',
            'keywords' => 'team management, collaboration, enterprise',
            'ogImage' => 'https://llm.resayil.io/og-images/og-teams.png',
            'ogType' => 'website',
        ],
        'comparison' => [
            'title' => 'LLM Resayil vs Competitors — Cost & Speed Comparison',
            'description' => 'LLM Resayil vs. OpenRouter: Cost comparison, speed benchmarks, model availability. Which API is cheaper? Full breakdown with calculator.',
            'keywords' => 'openrouter alternative, api comparison, cost comparison',
            'ogImage' => 'https://llm.resayil.io/og-images/og-comparison.png',
            'ogType' => 'website',
        ],
        'alternatives' => [
            'title' => 'OpenAI Alternatives — LLM Resayil',
            'description' => 'Explore OpenAI alternatives. Compare features, pricing, and model availability. Find the best LLM API for your use case.',
            'keywords' => 'openai alternatives, gpt alternatives, llm alternatives',
            'ogImage' => 'https://llm.resayil.io/og-images/og-alternatives.png',
            'ogType' => 'website',
        ],
        'cost-calculator' => [
            'title' => 'LLM Cost Calculator — Compare Pricing',
            'description' => 'Calculate your LLM API costs. Compare pricing across OpenAI, OpenRouter, and LLM Resayil. Find the cheapest option.',
            'keywords' => 'cost calculator, pricing calculator, price comparison',
            'ogImage' => 'https://llm.resayil.io/og-images/og-calculator.png',
            'ogType' => 'website',
        ],
        'blog' => [
            'title' => 'Blog — LLM Resayil',
            'description' => 'Stay updated with LLM Resayil blog. AI insights, API best practices, and feature announcements.',
            'keywords' => 'blog, news, articles, ai insights',
            'ogImage' => 'https://llm.resayil.io/og-images/og-blog.png',
            'ogType' => 'website',
        ],
        'landing.3' => [
            'title' => 'LLM Resayil — Affordable AI API Platform',
            'description' => 'LLM Resayil: Affordable OpenAI-compatible API. 45+ models, pay-per-token. 10x cheaper than OpenAI. Start free with 1,000 credits.',
            'keywords' => 'openai alternative, cheap llm api, affordable ai',
            'ogImage' => 'https://llm.resayil.io/og-images/og-landing.png',
            'ogType' => 'website',
        ],
        'dedicated-server' => [
            'title' => 'Resayil LLM + Dedicated Server Hosting: Enterprise Infrastructure',
            'description' => 'Dedicated server hosting with Resayil LLM API. Full control + simplicity. For enterprises, compliance, and high-volume workloads.',
            'keywords' => 'dedicated server, enterprise llm, self-hosted llm, infrastructure control, compliance, data privacy',
            'ogImage' => 'https://llm.resayil.io/og-images/og-dedicated-server.png',
            'ogType' => 'website',
        ],
        'pricing' => [
            'title' => 'Pricing — LLM Resayil | Pay-Per-Token, No Subscriptions',
            'description' => 'LLM Resayil pricing: buy credit packs from 2 KWD. No subscriptions, no seat fees. 5,000 credits/2 KWD, 15,000/5 KWD, 50,000/15 KWD. Start free.',
            'keywords' => 'llm api pricing, pay per token, credit packs, affordable ai pricing, kwd pricing',
            'ogImage' => 'https://llm.resayil.io/og-images/og-pricing.png',
            'ogType' => 'website',
        ],
        'features' => [
            'title' => 'Features & Models — LLM Resayil | 45+ LLMs, OpenAI Compatible',
            'description' => 'LLM Resayil features: 45+ models, OpenAI-compatible API, pay-per-token credits, multiple API keys, usage dashboard, Arabic UI. No monthly fees.',
            'keywords' => 'llm features, available models, openai compatible, api features, arabic llm',
            'ogImage' => 'https://llm.resayil.io/og-images/og-features.png',
            'ogType' => 'website',
        ],
    ];

    /**
     * Get metadata for a page
     *
     * @param string $page
     * @return array
     */
    public static function getPageMeta($page)
    {
        return self::$metadata[$page] ?? [
            'title' => 'LLM Resayil',
            'description' => 'Affordable OpenAI-compatible LLM API with 45+ models.',
            'keywords' => 'llm api, openai alternative, ai api',
            'ogImage' => 'https://llm.resayil.io/og-images/og-default.png',
            'ogType' => 'website',
        ];
    }

    /**
     * Get page title
     *
     * @param string $page
     * @return string
     */
    public static function getTitle($page)
    {
        $meta = self::getPageMeta($page);
        return $meta['title'] ?? 'LLM Resayil';
    }

    /**
     * Get page description
     *
     * @param string $page
     * @return string
     */
    public static function getDescription($page)
    {
        $meta = self::getPageMeta($page);
        return $meta['description'] ?? '';
    }

    /**
     * Get OG image URL
     *
     * @param string $page
     * @return string
     */
    public static function getOgImage($page)
    {
        $meta = self::getPageMeta($page);
        return $meta['ogImage'] ?? 'https://llm.resayil.io/og-images/og-default.png';
    }

    /**
     * Get keywords
     *
     * @param string $page
     * @return string
     */
    public static function getKeywords($page)
    {
        $meta = self::getPageMeta($page);
        return $meta['keywords'] ?? '';
    }

    /**
     * Get OG type
     *
     * @param string $page
     * @return string
     */
    public static function getOgType($page)
    {
        $meta = self::getPageMeta($page);
        return $meta['ogType'] ?? 'website';
    }
}
