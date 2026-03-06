@extends('layouts.app')

@section('title', __('alternatives.title'))

@push('styles')
<link href="{{ asset('css/alternatives.css') }}" rel="stylesheet">
@endpush

@section('content')
<main>

    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="hero-section" aria-labelledby="hero-headline">
        <div class="hero-eyebrow" aria-label="{{ __('alternatives.hero_eyebrow') }}">
            <span class="hero-eyebrow-dot" aria-hidden="true"></span>
            {{ __('alternatives.hero_eyebrow') }}
        </div>

        <h1 class="hero-headline" id="hero-headline">
            {{ __('alternatives.hero_headline_pre') }} <span class="highlight">{{ __('alternatives.hero_headline_highlight') }}</span>
        </h1>

        <p class="hero-subheadline">
            {{ __('alternatives.hero_subheadline') }}
        </p>

        <div class="hero-cta">
            <a href="#comparison-matrix" class="cta-btn primary" aria-label="{{ __('alternatives.hero_cta_compare') }}">
                {{ __('alternatives.hero_cta_compare') }}
            </a>
            <a href="{{ route('register') }}" class="cta-btn secondary" aria-label="{{ __('alternatives.hero_cta_start') }}">
                {{ __('alternatives.hero_cta_start') }}
            </a>
        </div>

        <div class="hero-stats" role="list" aria-label="Key statistics">
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">45+</span>
                <span class="hero-stat-label">{{ __('alternatives.hero_stat_models') }}</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">$0.0001</span>
                <span class="hero-stat-label">{{ __('alternatives.hero_stat_per_1k') }}</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">100%</span>
                <span class="hero-stat-label">{{ __('alternatives.hero_stat_openai_compat') }}</span>
            </div>
            <div class="hero-stat" role="listitem">
                <span class="hero-stat-number">&lt;5 min</span>
                <span class="hero-stat-label">{{ __('alternatives.hero_stat_setup') }}</span>
            </div>
        </div>
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         COMPARISON MATRIX
    ═══════════════════════════════════════════════════════════ --}}
    <section class="matrix-section" id="comparison-matrix" aria-labelledby="matrix-title">
        <h2 class="section-title" id="matrix-title">{{ __('alternatives.matrix_title') }}</h2>
        <p class="section-description">
            {{ __('alternatives.matrix_description') }}
        </p>

        {{-- Desktop Table --}}
        <div style="overflow-x: auto;" role="region" aria-label="{{ __('alternatives.matrix_description') }}">
            <table class="comparison-table" aria-describedby="matrix-title">
                <thead>
                    <tr>
                        <th scope="col" style="width: 18%;">{{ __('alternatives.col_feature') }}</th>
                        <th scope="col" class="resayil" style="width: 16.5%;">LLM Resayil</th>
                        <th scope="col" style="width: 16.5%;">OpenRouter</th>
                        <th scope="col" style="width: 16.5%;">Claude API</th>
                        <th scope="col" style="width: 16.5%;">Ollama</th>
                        <th scope="col" style="width: 16%;">Together AI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_pricing') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_pricing') }}</td>
                        <td>{{ __('alternatives.val_openrouter_pricing') }}</td>
                        <td>{{ __('alternatives.val_claude_pricing') }}</td>
                        <td>{{ __('alternatives.val_ollama_pricing') }}</td>
                        <td>{{ __('alternatives.val_together_pricing') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_model_availability') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_models') }}</td>
                        <td>{{ __('alternatives.val_openrouter_models') }}</td>
                        <td>{{ __('alternatives.val_claude_models') }}</td>
                        <td>{{ __('alternatives.val_ollama_models') }}</td>
                        <td>{{ __('alternatives.val_together_models') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_openai_compat') }}</td>
                        <td class="resayil">
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-cross" role="img" aria-label="No">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                    <path d="M2 2L9 9M9 2L2 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                        <td>
                            <span class="icon-check" role="img" aria-label="Yes">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_latency') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_latency') }}</td>
                        <td>{{ __('alternatives.val_openrouter_latency') }}</td>
                        <td>{{ __('alternatives.val_claude_latency') }}</td>
                        <td>{{ __('alternatives.val_ollama_latency') }}</td>
                        <td>{{ __('alternatives.val_together_latency') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_support') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_support') }}</td>
                        <td>{{ __('alternatives.val_openrouter_support') }}</td>
                        <td>{{ __('alternatives.val_claude_support') }}</td>
                        <td>{{ __('alternatives.val_ollama_support') }}</td>
                        <td>{{ __('alternatives.val_together_support') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_best_use') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_best_use') }}</td>
                        <td>{{ __('alternatives.val_openrouter_best_use') }}</td>
                        <td>{{ __('alternatives.val_claude_best_use') }}</td>
                        <td>{{ __('alternatives.val_ollama_best_use') }}</td>
                        <td>{{ __('alternatives.val_together_best_use') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_setup_time') }}</td>
                        <td class="resayil">{{ __('alternatives.val_resayil_setup') }}</td>
                        <td>{{ __('alternatives.val_openrouter_setup') }}</td>
                        <td>{{ __('alternatives.val_claude_setup') }}</td>
                        <td>{{ __('alternatives.val_ollama_setup') }}</td>
                        <td>{{ __('alternatives.val_together_setup') }}</td>
                    </tr>
                    <tr>
                        <td class="header-cell" scope="row">{{ __('alternatives.col_data_privacy') }}</td>
                        <td class="resayil">
                            <span class="icon-partial" role="img" aria-label="{{ __('alternatives.val_resayil_privacy') }}">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                    <circle cx="6" cy="6" r="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="M6 4V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <circle cx="6" cy="8.5" r="0.75" fill="currentColor"/>
                                </svg>
                            </span>
                            <span style="margin-left: 0.4rem; font-size: 0.85rem;">{{ __('alternatives.val_resayil_privacy') }}</span>
                        </td>
                        <td>{{ __('alternatives.val_openrouter_privacy') }}</td>
                        <td>{{ __('alternatives.val_claude_privacy') }}</td>
                        <td>
                            <span class="icon-check" role="img" aria-label="{{ __('alternatives.val_ollama_privacy') }}">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                                    <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span style="margin-left: 0.4rem; font-size: 0.85rem;">{{ __('alternatives.val_ollama_privacy') }}</span>
                        </td>
                        <td>{{ __('alternatives.val_together_privacy') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Mobile Accordion --}}
        <div class="comparison-accordion" role="list" aria-label="Provider comparison — expanded cards">

            {{-- LLM Resayil --}}
            <div class="accordion-item open" role="listitem">
                <button class="accordion-header resayil"
                        aria-expanded="true"
                        aria-controls="accordion-content-resayil"
                        id="accordion-btn-resayil">
                    <span>LLM Resayil</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-resayil" role="region" aria-labelledby="accordion-btn-resayil">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_pricing') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_resayil_pricing') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_models') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_resayil_models') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_openai_compat') }}</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_latency') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_resayil_latency') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_best_for') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_resayil_best_use') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_setup_time') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_resayil_setup') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- OpenRouter --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-openrouter"
                        id="accordion-btn-openrouter">
                    <span>OpenRouter</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-openrouter" role="region" aria-labelledby="accordion-btn-openrouter">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_pricing') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_openrouter_pricing') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_models') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_openrouter_models') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_openai_compat') }}</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_latency') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_openrouter_latency') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_best_for') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_openrouter_best_use') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_setup_time') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_openrouter_setup') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Claude API --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-claude"
                        id="accordion-btn-claude">
                    <span>Claude API</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-claude" role="region" aria-labelledby="accordion-btn-claude">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_pricing') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_claude_pricing') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_models') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_claude_models') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_openai_compat') }}</span>
                            <span class="accordion-value" aria-label="No">
                                <span class="icon-cross" role="img" aria-hidden="true">
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                        <path d="M2 2L9 9M9 2L2 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_latency') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_claude_latency') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_best_for') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_claude_best_use') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_setup_time') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_claude_setup') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ollama --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-ollama"
                        id="accordion-btn-ollama">
                    <span>Ollama</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-ollama" role="region" aria-labelledby="accordion-btn-ollama">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_pricing') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_ollama_pricing') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_models') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_ollama_models_accordion') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_openai_compat') }}</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_latency') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_ollama_latency') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_best_for') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_ollama_best_use') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_setup_time') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_ollama_setup') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Together AI --}}
            <div class="accordion-item" role="listitem">
                <button class="accordion-header"
                        aria-expanded="false"
                        aria-controls="accordion-content-together"
                        id="accordion-btn-together">
                    <span>Together AI</span>
                    <svg class="accordion-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-content-together" role="region" aria-labelledby="accordion-btn-together">
                    <div class="accordion-inner">
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_pricing') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_together_pricing') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_models') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_together_models') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_openai_compat') }}</span>
                            <span class="accordion-value" aria-label="Yes">
                                <span class="icon-check" role="img" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5 9.5L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_latency') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_together_latency') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_best_for') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_together_best_use') }}</span>
                        </div>
                        <div class="accordion-row">
                            <span class="accordion-label">{{ __('alternatives.accordion_setup_time') }}</span>
                            <span class="accordion-value">{{ __('alternatives.val_together_setup') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /.comparison-accordion --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         DEEP DIVE CARDS
    ═══════════════════════════════════════════════════════════ --}}
    <section class="deep-dive-section" aria-labelledby="deep-dive-title">
        <h2 class="section-title" id="deep-dive-title">{{ __('alternatives.deep_dive_title') }}</h2>
        <p class="section-description">
            {{ __('alternatives.deep_dive_description') }}
        </p>

        <div class="deep-dive-grid">

            {{-- LLM Resayil — featured, spans full row --}}
            <article class="deep-dive-card featured fade-up" aria-labelledby="card-title-resayil">
                <div class="card-header-band">
                    <div class="card-avatar avatar-resayil" aria-hidden="true">LR</div>
                    <div class="card-title-group">
                        <h3 id="card-title-resayil">LLM Resayil</h3>
                        <div class="deep-dive-tagline">{{ __('alternatives.resayil_tagline') }}</div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">{{ __('alternatives.resayil_p1') }}</p>
                    <p class="deep-dive-content">{{ __('alternatives.resayil_p2') }}</p>
                    <ul class="deep-dive-list" aria-label="LLM Resayil highlights">
                        @foreach([
                            __('alternatives.resayil_li1'),
                            __('alternatives.resayil_li2'),
                            __('alternatives.resayil_li3'),
                            __('alternatives.resayil_li4'),
                            __('alternatives.resayil_li5'),
                        ] as $li)
                        <li>
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $li }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            {{-- OpenRouter --}}
            <article class="deep-dive-card fade-up delay-1" aria-labelledby="card-title-openrouter">
                <div class="card-header-band">
                    <div class="card-avatar avatar-openrouter" aria-hidden="true">OR</div>
                    <div class="card-title-group">
                        <h3 id="card-title-openrouter">OpenRouter</h3>
                        <div class="deep-dive-tagline">{{ __('alternatives.openrouter_tagline') }}</div>
                        <span class="vs-badge" aria-label="{{ __('alternatives.vs_resayil') }}">{{ __('alternatives.vs_resayil') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">{{ __('alternatives.openrouter_p1') }}</p>
                    <ul class="deep-dive-list" aria-label="OpenRouter highlights">
                        @foreach([
                            ['gold', __('alternatives.openrouter_li1')],
                            ['gold', __('alternatives.openrouter_li2')],
                            ['gold', __('alternatives.openrouter_li3')],
                            ['red',  __('alternatives.openrouter_li4')],
                            ['red',  __('alternatives.openrouter_li5')],
                        ] as [$color, $li])
                        <li>
                            @if($color === 'gold')
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @else
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            @endif
                            {{ $li }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            {{-- Claude API --}}
            <article class="deep-dive-card fade-up delay-2" aria-labelledby="card-title-claude">
                <div class="card-header-band">
                    <div class="card-avatar avatar-claude" aria-hidden="true">CA</div>
                    <div class="card-title-group">
                        <h3 id="card-title-claude">Claude API</h3>
                        <div class="deep-dive-tagline">{{ __('alternatives.claude_tagline') }}</div>
                        <span class="vs-badge" aria-label="{{ __('alternatives.vs_resayil') }}">{{ __('alternatives.vs_resayil') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">{{ __('alternatives.claude_p1') }}</p>
                    <ul class="deep-dive-list" aria-label="Claude API highlights">
                        @foreach([
                            ['gold', __('alternatives.claude_li1')],
                            ['gold', __('alternatives.claude_li2')],
                            ['gold', __('alternatives.claude_li3')],
                            ['red',  __('alternatives.claude_li4')],
                            ['red',  __('alternatives.claude_li5')],
                        ] as [$color, $li])
                        <li>
                            @if($color === 'gold')
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @else
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            @endif
                            {{ $li }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            {{-- Ollama --}}
            <article class="deep-dive-card fade-up delay-3" aria-labelledby="card-title-ollama">
                <div class="card-header-band">
                    <div class="card-avatar avatar-ollama" aria-hidden="true">OL</div>
                    <div class="card-title-group">
                        <h3 id="card-title-ollama">Ollama</h3>
                        <div class="deep-dive-tagline">{{ __('alternatives.ollama_tagline') }}</div>
                        <span class="vs-badge" aria-label="{{ __('alternatives.vs_resayil') }}">{{ __('alternatives.vs_resayil') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">{{ __('alternatives.ollama_p1') }}</p>
                    <ul class="deep-dive-list" aria-label="Ollama highlights">
                        @foreach([
                            ['gold', __('alternatives.ollama_li1')],
                            ['gold', __('alternatives.ollama_li2')],
                            ['gold', __('alternatives.ollama_li3')],
                            ['red',  __('alternatives.ollama_li4')],
                            ['red',  __('alternatives.ollama_li5')],
                        ] as [$color, $li])
                        <li>
                            @if($color === 'gold')
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @else
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            @endif
                            {{ $li }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            {{-- Together AI --}}
            <article class="deep-dive-card fade-up delay-4" aria-labelledby="card-title-together">
                <div class="card-header-band">
                    <div class="card-avatar avatar-together" aria-hidden="true">TA</div>
                    <div class="card-title-group">
                        <h3 id="card-title-together">Together AI</h3>
                        <div class="deep-dive-tagline">{{ __('alternatives.together_tagline') }}</div>
                        <span class="vs-badge" aria-label="{{ __('alternatives.vs_resayil') }}">{{ __('alternatives.vs_resayil') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="deep-dive-content">{{ __('alternatives.together_p1') }}</p>
                    <ul class="deep-dive-list" aria-label="Together AI highlights">
                        @foreach([
                            ['gold', __('alternatives.together_li1')],
                            ['gold', __('alternatives.together_li2')],
                            ['gold', __('alternatives.together_li3')],
                            ['red',  __('alternatives.together_li4')],
                            ['red',  __('alternatives.together_li5')],
                        ] as [$color, $li])
                        <li>
                            @if($color === 'gold')
                            <svg class="list-bullet list-bullet-gold" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3 9L7 13L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @else
                            <svg class="list-bullet" style="color: #f87171;" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4 4L14 14M14 4L4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            @endif
                            {{ $li }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </article>

        </div>{{-- /.deep-dive-grid --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         WHY CHOOSE US — Feature Highlights (SVG icons, no emoji)
    ═══════════════════════════════════════════════════════════ --}}
    <section class="highlights-section" aria-labelledby="highlights-title">
        <h2 class="section-title" id="highlights-title">{{ __('alternatives.highlights_title') }}</h2>
        <p class="section-description">
            {{ __('alternatives.highlights_description') }}
        </p>

        <div class="highlights-grid">

            <div class="highlight-item fade-up">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Coin/cost SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M12 7v1m0 8v1M9.5 10a2.5 2.5 0 0 1 5 0c0 1.5-1 2-2.5 2.5S9.5 13 9.5 14.5a2.5 2.5 0 0 0 5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_1_title') }}</h4>
                <p>{{ __('alternatives.highlight_1_desc') }}</p>
            </div>

            <div class="highlight-item fade-up delay-1">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Plug/compatible SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2v4M8 6h8M7 6v5a5 5 0 0 0 10 0V6M12 16v6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_2_title') }}</h4>
                <p>{{ __('alternatives.highlight_2_desc') }}</p>
            </div>

            <div class="highlight-item fade-up delay-2">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Lightning/hybrid SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M13 2L4.5 13.5H11L11 22L19.5 10.5H13L13 2Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_3_title') }}</h4>
                <p>{{ __('alternatives.highlight_3_desc') }}</p>
            </div>

            <div class="highlight-item fade-up delay-3">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Grid/models SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_4_title') }}</h4>
                <p>{{ __('alternatives.highlight_4_desc') }}</p>
            </div>

            <div class="highlight-item fade-up delay-4">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Rocket/free SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C12 2 7 6 7 12v3l-2 2v1h10v-1l-2-2v-3c0-6 5-10 5-10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 18a3 3 0 0 0 6 0" stroke="currentColor" stroke-width="1.75"/>
                        <circle cx="12" cy="7" r="1.25" fill="currentColor"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_5_title') }}</h4>
                <p>{{ __('alternatives.highlight_5_desc') }}</p>
            </div>

            <div class="highlight-item fade-up delay-5">
                <div class="highlight-icon-wrap" aria-hidden="true">
                    {{-- Lock/security SVG --}}
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <h4>{{ __('alternatives.highlight_6_title') }}</h4>
                <p>{{ __('alternatives.highlight_6_desc') }}</p>
            </div>

        </div>{{-- /.highlights-grid --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         COST CALCULATOR CTA
    ═══════════════════════════════════════════════════════════ --}}
    <section class="calculator-section" aria-labelledby="calculator-title">
        <div class="calculator-container fade-up">
            <h2 class="calculator-title" id="calculator-title">{{ __('alternatives.calculator_title') }}</h2>
            <p class="calculator-description">
                {{ __('alternatives.calculator_description') }}
            </p>
            <a href="{{ route('cost-calculator') }}" class="calculator-cta" aria-label="{{ __('alternatives.calculator_cta') }}">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="14" height="14" rx="2.5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M5.5 6h7M5.5 9h7M5.5 12h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                {{ __('alternatives.calculator_cta') }}
            </a>
        </div>
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         FAQ SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="faq-section" aria-labelledby="faq-title">
        <h2 class="section-title" id="faq-title">{{ __('alternatives.faq_title') }}</h2>
        <p class="section-description">
            {{ __('alternatives.faq_description') }}
        </p>

        <div class="faq-container" role="list" aria-label="FAQ list">

            <div class="faq-item open" role="listitem">
                <button class="faq-question"
                        aria-expanded="true"
                        aria-controls="faq-answer-1"
                        id="faq-btn-1">
                    <span>{{ __('alternatives.faq_q1') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-1" role="region" aria-labelledby="faq-btn-1">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a1') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-2"
                        id="faq-btn-2">
                    <span>{{ __('alternatives.faq_q2') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-2" role="region" aria-labelledby="faq-btn-2">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a2') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-3"
                        id="faq-btn-3">
                    <span>{{ __('alternatives.faq_q3') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-3" role="region" aria-labelledby="faq-btn-3">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a3') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-4"
                        id="faq-btn-4">
                    <span>{{ __('alternatives.faq_q4') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-4" role="region" aria-labelledby="faq-btn-4">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a4') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-5"
                        id="faq-btn-5">
                    <span>{{ __('alternatives.faq_q5') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-5" role="region" aria-labelledby="faq-btn-5">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a5') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-6"
                        id="faq-btn-6">
                    <span>{{ __('alternatives.faq_q6') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-6" role="region" aria-labelledby="faq-btn-6">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a6') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-7"
                        id="faq-btn-7">
                    <span>{{ __('alternatives.faq_q7') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-7" role="region" aria-labelledby="faq-btn-7">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a7') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-8"
                        id="faq-btn-8">
                    <span>{{ __('alternatives.faq_q8') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-8" role="region" aria-labelledby="faq-btn-8">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a8') !!}</div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-question"
                        aria-expanded="false"
                        aria-controls="faq-answer-9"
                        id="faq-btn-9">
                    <span>{{ __('alternatives.faq_q9') }}</span>
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-answer-9" role="region" aria-labelledby="faq-btn-9">
                    <div class="faq-answer-inner">{!! __('alternatives.faq_a9') !!}</div>
                </div>
            </div>

        </div>{{-- /.faq-container --}}
    </section>

    <div class="section-spacer"></div>

    {{-- ═══════════════════════════════════════════════════════════
         FOOTER CTA BANNER — Gold background, dark text
    ═══════════════════════════════════════════════════════════ --}}
    <section class="footer-cta-section" aria-labelledby="footer-cta-headline">
        <h2 class="footer-cta-headline" id="footer-cta-headline">
            {{ __('alternatives.footer_cta_headline_pre') }} <span class="cta-brand">LLM Resayil</span>
        </h2>
        <p class="footer-cta-tagline">
            {{ __('alternatives.footer_cta_tagline') }}
        </p>
        <div class="footer-cta-buttons">
            <a href="{{ route('register') }}" class="cta-btn primary-dark" aria-label="{{ __('alternatives.footer_cta_create_account') }}">
                {{ __('alternatives.footer_cta_create_account') }}
            </a>
            <a href="{{ route('cost-calculator') }}" class="cta-btn secondary-dark" aria-label="{{ __('alternatives.footer_cta_calculate') }}">
                {{ __('alternatives.footer_cta_calculate') }}
            </a>
        </div>
    </section>

    {{-- Internal link box --}}
    <div class="internal-links-box">
        <p>
            {!! __('alternatives.internal_links', [
                'calc_link'       => '<a href="' . route('cost-calculator') . '">' . __('alternatives.internal_calc_link') . '</a>',
                'comparison_link' => '<a href="' . route('comparison') . '">' . __('alternatives.internal_comparison_link') . '</a>',
            ]) !!}
        </p>
    </div>

</main>

{{-- ── FAQ Schema (SEO) ── --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Which API is cheapest overall?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "LLM Resayil is the cheapest at $0.0001 per 1K input tokens. OpenRouter and Together AI are close (around $0.0005–$0.0008), but Resayil edges them out for pure cost efficiency. Ollama is free if you run it locally, but requires your own hardware and setup. OpenAI and Claude API are 10x+ more expensive."
      }
    },
    {
      "@type": "Question",
      "name": "Is LLM Resayil truly OpenAI-compatible?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, 100%. LLM Resayil implements the OpenAI API specification. You can use the OpenAI Python SDK, JavaScript SDK, or any other third-party SDK that supports OpenAI-compatible endpoints. Change one line of code — the base_url parameter — and you're done."
      }
    },
    {
      "@type": "Question",
      "name": "Can I migrate from OpenAI to LLM Resayil easily?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. If you're already using the OpenAI SDK, you just need to change the base_url (or api_base) to https://api.llm.resayil.io. No other code changes needed. Model names stay the same (e.g., gpt-4 maps to a compatible model on Resayil's platform)."
      }
    },
    {
      "@type": "Question",
      "name": "Which API is fastest?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ollama is fastest (sub-500ms latency) because it runs locally on your hardware with zero network overhead. For cloud APIs, Together AI (500ms–2s) and LLM Resayil (1–3s, faster on local models) are the quickest."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need my own GPU for Ollama?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Not strictly — Ollama can run on CPU, but it will be very slow (minutes per request). For practical use, you need a GPU: NVIDIA (CUDA), AMD (ROCm), or Mac Silicon (built-in). Once set up, you can run models like Mistral 7B with less than 1s per token latency."
      }
    }
  ]
}
</script>

{{-- ── Accordion + FAQ Toggle Script ── --}}
<script>
(function () {
    'use strict';

    /* ── Generic toggle helper ── */
    function toggleDisclosure(button, contentId, itemEl) {
        var isOpen = itemEl.classList.contains('open');
        itemEl.classList.toggle('open');
        button.setAttribute('aria-expanded', String(!isOpen));
    }

    /* ── FAQ ── */
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleDisclosure(btn, btn.getAttribute('aria-controls'), btn.closest('.faq-item'));
        });
        /* Enter/Space already fire click on <button>; no extra keydown needed */
    });

    /* ── Comparison Accordion ── */
    document.querySelectorAll('.accordion-header').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleDisclosure(btn, btn.getAttribute('aria-controls'), btn.closest('.accordion-item'));
        });
    });

    /* ── Scroll-reveal via IntersectionObserver ── */
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.fade-up').forEach(function (el) {
            observer.observe(el);
        });
    } else {
        /* Fallback: just show everything immediately */
        document.querySelectorAll('.fade-up').forEach(function (el) {
            el.classList.add('visible');
        });
    }
}());
</script>

@endsection
