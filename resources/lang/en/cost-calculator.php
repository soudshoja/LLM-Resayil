<?php

return [
    // Page title
    'title' => 'LLM Cost Calculator — Compare API Pricing',

    // Hero
    'hero_badge' => 'Live Price Comparison',
    'hero_title' => 'LLM API',
    'hero_title_highlight' => 'Cost Calculator',
    'hero_description' => 'See exactly how much you\'ll save with LLM Resayil. Compare real-time pricing against OpenAI and OpenRouter — no sign-up required.',
    'hero_stat_openai' => 'Cheaper than OpenAI',
    'hero_stat_openrouter' => 'Cheaper than OpenRouter',
    'hero_stat_minimum' => 'Monthly Minimum',

    // Calculator Inputs
    'card_configure_title' => 'Configure Your Usage',
    'label_monthly_tokens' => 'Monthly Token Usage',
    'tokens_per_month' => 'tokens / month',
    'slider_hint' => 'Drag or use arrow keys (1M step) — Page Up/Down for 10M steps',
    'label_enter_directly' => 'Or enter token count directly:',
    'label_model_tier' => 'Model Tier',
    'model_small' => 'Small — e.g. Mistral 7B',
    'model_medium' => 'Medium — e.g. Llama 70B',
    'model_large' => 'Large — e.g. GPT-4 Equivalent',
    'model_tier_hint' => 'Larger tiers cost more per token across all providers',
    'label_workload_type' => 'Workload Type',
    'workload_production' => 'Production',
    'workload_development' => 'Development',
    'workload_batch' => 'Batch Processing',
    'btn_calculate' => 'Calculate My Savings',

    // Results
    'card_results_title' => 'Monthly Cost Comparison',
    'badge_best_value' => 'Best Value',
    'label_our_platform' => 'Our platform',
    'label_openai_sub' => 'GPT-4 API',
    'label_openrouter_sub' => 'Aggregated routing',
    'savings_label' => 'Your monthly savings vs OpenAI',
    'savings_sub' => 'saved every month',
    'vs_openai' => 'vs OpenAI',
    'vs_openrouter' => 'vs OpenRouter',

    // Pricing Methodology
    'pricing_method_title' => 'How We Calculate Your Costs',
    'pricing_resayil' => 'LLM Resayil: $0.001 / 1K tokens',
    'pricing_openai' => 'OpenAI: $0.015 / 1K tokens',
    'pricing_openrouter' => 'OpenRouter: $0.008 / 1K tokens',
    'pricing_note' => 'Calculations use current market rates and are updated regularly. Actual costs may vary by model selection, additional features, and volume agreements. All figures assume standard pricing without custom contracts.',
    'pricing_links' => 'See a :comparison_link, or explore :alternatives_link.',
    'pricing_comparison_link' => 'detailed comparison with OpenRouter',
    'pricing_alternatives_link' => 'alternative LLM APIs',

    // FAQ
    'faq_title' => 'Frequently Asked Questions',
    'faq_subtitle' => 'Everything you need to know about our pricing and this calculator.',
    'faq_q1' => 'How accurate is this calculator?',
    'faq_a1' => 'Our calculator uses current market pricing rates and is updated regularly. Results are accurate for estimation purposes. For production environments with volume discounts or custom agreements, please contact our sales team for a personalized quote.',
    'faq_q2' => 'Why is LLM Resayil cheaper?',
    'faq_a2' => 'We optimize infrastructure costs and pass savings to users. Our pay-per-token model eliminates monthly minimums. No hidden fees or overages. Plus, access to open-source models with commercial licenses removes vendor lock-in premiums charged by competitors.',
    'faq_q3' => 'Can I use this for production estimates?',
    'faq_a3' => 'Yes, this calculator is designed for production cost estimates. All pricing is based on current published rates. For guaranteed pricing, SLAs, or enterprise agreements, contact our sales team at support@resayil.io with your usage profile.',
    'faq_q4' => 'Do pricing tiers affect the calculation?',
    'faq_a4' => 'Model tier selection affects pricing rates. Larger models (like GPT-4 equivalents) are more expensive per token than smaller models (like Mistral 7B). The calculator uses representative pricing for each tier. See our detailed pricing page for model-specific rates.',
    'faq_q5' => 'Are there volume discounts?',
    'faq_a5' => 'Yes! Enterprise customers with high monthly volumes qualify for volume discounts. Contact our sales team to discuss your specific use case and get a custom pricing proposal tailored to your needs.',
    'faq_q6' => 'How often do prices change?',
    'faq_a6' => 'We update pricing quarterly to reflect market conditions. Existing users are grandfathered into their current rates for 12 months. Price increases (if any) are announced 30 days in advance via email and dashboard notifications.',

    // CTA
    'cta_title' => 'Ready to Start Saving?',
    'cta_description' => 'Join thousands of developers who\'ve switched to LLM Resayil and dramatically cut their API costs.',
    'cta_primary' => 'Start Free — 1,000 Credits',
    'cta_secondary' => 'View Pricing Plans',
    'cta_footer_note' => 'Check our :pricing_link, or see how we :comparison_link.',
    'cta_pricing_link' => 'detailed pricing page',
    'cta_comparison_link' => 'compare to competitors',
];
