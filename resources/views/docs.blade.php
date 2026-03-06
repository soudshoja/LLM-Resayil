@extends('layouts.app')

@section('title', $pageTitle ?? 'API Documentation — LLM Resayil')

@push('styles')
<style>
    /* ── Docs Page Styles ── */
    .docs-wrap {
        background: var(--bg-secondary);
    }

    /* Hero Section */
    .docs-hero {
        padding: 4rem 2rem 3rem;
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }

    .docs-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.1;
    }

    .docs-hero h1 span {
        color: var(--gold);
    }

    .docs-hero-lead {
        font-size: 1.1rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
    }

    @media (max-width: 600px) {
        .docs-hero h1 { font-size: 2rem; }
    }

    /* Documentation List Section */
    .docs-section {
        padding: 0 2rem 4rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .docs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .doc-card:hover {
        border-color: rgba(212,175,55,0.3);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(212,175,55,0.1);
    }

    .doc-date {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .doc-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .doc-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        flex: 1;
    }

    .doc-tag {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        background: rgba(212,175,55,0.12);
        color: var(--gold);
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 1rem;
        align-self: flex-start;
    }

    .doc-link-arrow {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.85rem;
        color: var(--gold);
        margin-top: auto;
        padding-top: 1rem;
    }

    .doc-link-arrow svg {
        width: 16px;
        height: 16px;
        transition: transform 0.2s;
    }

    .doc-card:hover .doc-link-arrow svg {
        transform: translateX(4px);
    }

    /* Help box */
    .docs-help-box {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        text-align: center;
    }

    .docs-help-box h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .docs-help-box p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .docs-help-links {
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    .docs-help-links a {
        color: var(--gold);
        text-decoration: underline;
    }

    .docs-help-links a:hover {
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="docs-wrap">

    <!-- Hero Section -->
    <div class="docs-hero">
        <h1>@lang('docs.documentation')</h1>
        <p class="docs-hero-lead">@lang('docs.everything_need_to_know')</p>
        <a href="/dashboard" class="btn btn-gold">@lang('docs.go_to_dashboard')</a>
    </div>

    <!-- Documentation List -->
    <section class="docs-section">
        <div class="docs-grid">
            <!-- Billing & Plans -->
            <a href="/docs/plans/2026-03-02-billing-admin-enhancements.md" class="doc-card">
                <span class="doc-date">{{ __('docs.last_updated') }}</span>
                <h3 class="doc-title">@lang('docs.billing_subscription_plans')</h3>
                <p class="doc-description">@lang('docs.complete_guide_subscription')</p>
                <span class="doc-tag">@lang('billing.plans')</span>
                <div class="doc-link-arrow">
                    @lang('docs.read_more')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Model Catalog -->
            <a href="/docs/plans/2026-03-02-model-catalog-admin-panel.md" class="doc-card">
                <span class="doc-date">{{ __('docs.last_updated') }}</span>
                <h3 class="doc-title">@lang('docs.model_catalog_admin_panel')</h3>
                <p class="doc-description">@lang('docs.access_llm_models')</p>
                <span class="doc-tag">@lang('docs.api')</span>
                <div class="doc-link-arrow">
                    @lang('docs.read_more')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Recurring Payments & WhatsApp -->
            <a href="/docs/plans/2026-03-02-billing-recurring-whatsapp.md" class="doc-card">
                <span class="doc-date">{{ __('docs.last_updated') }}</span>
                <h3 class="doc-title">@lang('docs.recurring_payments_whatsapp')</h3>
                <p class="doc-description">@lang('docs.myfatoorah_payment_gateway')</p>
                <span class="doc-tag">@lang('billing.plans')</span>
                <div class="doc-link-arrow">
                    @lang('docs.read_more')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Help / More resources box -->
        <div class="docs-help-box">
            <h2>@lang('docs.need_more_help')</h2>
            <p>@lang('docs.check_api_reference')</p>
            <a href="/credits" class="btn btn-gold">@lang('credits.how_credits_work')</a>
            <p class="docs-help-links">
                Ready to start? <a href="/register">Create a free account</a>,
                see our <a href="/features">available models</a>,
                or check <a href="/billing/plans">pricing plans</a>.
            </p>
        </div>
    </section>

</div>
@endsection
