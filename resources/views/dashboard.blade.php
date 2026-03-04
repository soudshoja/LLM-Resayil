@extends('layouts.app')

@section('title', __('dashboard.title'))

@push('styles')
<style>
    .dash-header { margin-bottom: 2rem; }
    .dash-header h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .stat-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }
    .savings-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; }
    .savings-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .savings-title { font-size: 1rem; font-weight: 600; }
    .savings-benchmark { display: flex; align-items: baseline; gap: 0.5rem; }
    .savings-amount { font-size: 2rem; font-weight: 700; color: #28a745; }
    .savings-label { font-size: 0.875rem; color: var(--text-muted); }
    .savings-kwd { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.15rem; }
    .savings-estimate-note { font-size: 0.75rem; color: var(--text-muted); font-style: italic; margin-top: 0.25rem; }
    .savings-toggle { font-size: 0.75rem; color: var(--gold); cursor: pointer; text-decoration: none; border: none; background: none; padding: 0; }
    .savings-table-wrapper { display: none; margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 1rem; }
    .savings-table-wrapper.expanded { display: block; }
    .savings-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .savings-table th { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 0; border-bottom: 1px solid var(--border); text-align: left; }
    .savings-table td { padding: 0.5rem 0; border-bottom: 1px solid rgba(30,34,48,0.3); }
    .savings-positive { color: #28a745; font-weight: 600; }
    .savings-no-data { color: var(--text-muted); font-size: 0.875rem; }
    .section-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
    .keys-wrapper { overflow-x: auto; }
    .keys-table { width: 100%; border-collapse: collapse; }
    .keys-table th { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.6rem 0; border-bottom: 1px solid var(--border); text-align: left; }
    .keys-table td { padding: 0.75rem 0; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; }
    .key-prefix { font-family: monospace; background: var(--bg-secondary); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; color: var(--gold); }
    .empty-state { text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.875rem; }
    .topup-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .topup-card { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; }
    .topup-card:hover { border-color: var(--gold-muted); }
    .topup-credits { font-size: 1.25rem; font-weight: 700; color: var(--gold); }
    .topup-price { font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.2rem; }
    .topup-bonus { font-size: 0.75rem; color: #28a745; font-weight: 600; margin-top: 0.25rem; }
    /* Model Catalog Styles */
    .model-catalog { margin-bottom: 2rem; }
    .catalog-header { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 1.5rem; }
    .catalog-title { font-size: 1rem; font-weight: 600; color: var(--text-primary); }
    .filter-group { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
    .filter-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .filter-select { background: var(--bg-primary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; cursor: pointer; min-width: 140px; }
    .filter-select:hover { border-color: var(--gold-muted); }
    .filter-select:focus { outline: none; border-color: var(--gold); }
    .filter-select option { background: var(--bg-card); }
    .search-input { background: var(--bg-primary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; min-width: 200px; }
    .search-input:focus { outline: none; border-color: var(--gold); }
    .model-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.75rem; }
    .model-card { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; cursor: pointer; transition: all 0.2s; position: relative; }
    .model-card:hover { border-color: var(--gold-muted); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
    .model-card.active { border-color: var(--gold); box-shadow: 0 0 0 1px var(--gold); }
    .model-card.selected { border-color: var(--gold); background: rgba(212,175,55,0.05); }
    .model-name { font-family: monospace; font-size: 0.8rem; color: var(--gold); margin-bottom: 0.5rem; word-break: break-all; }
    .model-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center; }
    .model-badge { padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.65rem; text-transform: uppercase; }
    .model-badge-local { background: rgba(5,150,105,0.15); color: #6ee7b7; border: 1px solid rgba(5,150,105,0.3); }
    .model-badge-cloud { background: rgba(212,175,55,0.15); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
    .model-desc { font-size: 0.8rem; color: var(--text-primary); margin-top: 0.5rem; }
    .model-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 0.75rem; padding-top: 0.5rem; border-top: 1px solid rgba(30,34,48,0.5); }
    .model-credits { font-size: 0.7rem; color: var(--text-muted); }
    .model-credits span { color: var(--gold); font-family: monospace; }
    /* Detail Panel */
    .detail-panel { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem; display: none; animation: fadeIn 0.3s ease; }
    .detail-panel.visible { display: block; }
    .detail-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
    .detail-title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }
    .detail-id { font-family: monospace; font-size: 0.8rem; color: var(--gold); word-break: break-all; margin-top: 0.25rem; }
    .detail-close { background: transparent; border: 1px solid var(--border); color: var(--text-muted); padding: 0.4rem 0.8rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem; }
    .detail-close:hover { border-color: var(--text-muted); color: var(--text-primary); }
    .detail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 1.5rem; }
    .detail-section h4 { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .detail-section p { font-size: 0.875rem; color: var(--text-primary); margin-bottom: 0.25rem; word-break: break-all; }
    .detail-section p:last-child { margin-bottom: 0; }
    .detail-label { color: var(--text-muted); font-size: 0.8rem; margin-right: 0.5rem; }
    .code-tabs { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
    .code-tab { background: var(--bg-primary); border: 1px solid var(--border); color: var(--text-muted); padding: 0.4rem 0.9rem; border-radius: 6px 6px 0 0; font-size: 0.8rem; cursor: pointer; }
    .code-tab:hover { color: var(--text-primary); }
    .code-tab.active { background: var(--bg-secondary); border-color: var(--gold); color: var(--gold); }
    .code-block { background: #0a0d14; border: 1px solid var(--border); border-radius: 8px; padding: 1rem; overflow-x: auto; position: relative; }
    .code-block pre { margin: 0; font-family: 'Courier New', monospace; font-size: 0.8rem; color: #e0e5ec; }
    .code-block .curl-kw { color: #c678dd; }
    .code-block .curl-opt { color: #e5c07b; }
    .code-block .curl-str { color: #98c379; }
    .code-block .curl-var { color: #61afef; }
    .code-block .py-key { color: #c678dd; }
    .code-block .py-func { color: #61afef; }
    .code-block .py-str { color: #98c379; }
    .code-block .py-var { color: #e06c75; }
    .code-block .n8n-key { color: #c678dd; }
    .code-block .n8n-str { color: #98c379; }
    .code-block .n8n-num { color: #d19a66; }
    .code-actions { display: flex; gap: 0.5rem; }
    .btn-copy { background: var(--bg-primary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; }
    .btn-copy:hover { border-color: var(--gold); color: var(--gold); }
    .btn-copy.copied { background: var(--success); border-color: var(--success); color: white; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .card-heading { font-size: 1rem; font-weight: 600; }
    .card-heading-mb { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; }
    .card-subheading { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .section-grid { grid-template-columns: 1fr; } .detail-grid { grid-template-columns: 1fr; } }
    @media(max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } .model-grid { grid-template-columns: 1fr; } .catalog-header { flex-direction: column; align-items: stretch; } .filter-group { flex-direction: column; align-items: stretch; } }
</style>
@endpush

@section('content')
<main>
    <div class="dash-header">
        <h1>{{ __('dashboard.welcome_back', ['name' => auth()->user()->name ?: auth()->user()->email]) }}</h1>
        <div class="text-secondary text-sm">
            {{ __('dashboard.plan') }}: <span class="badge badge-gold">{{ ucfirst(auth()->user()->subscription_tier) }}</span>
            @if(auth()->user()->subscription_expiry)
            &nbsp;· {{ __('dashboard.expires') }}: {{ auth()->user()->subscription_expiry->format('d M Y') }}
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">{{ __('dashboard.credits_remaining') }}</div>
            <div class="stat-value">{{ number_format(auth()->user()->credits) }}</div>
            <div class="stat-sub">{{ __('dashboard.available_for_api') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">{{ __('dashboard.api_keys') }}</div>
            <div class="stat-value">{{ auth()->user()->apiKeys()->count() }}</div>
            <div class="stat-sub">{{ __('dashboard.active_keys') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">{{ __('dashboard.requests_30d') }}</div>
            <div class="stat-value">
                {{ \App\Models\UsageLog::where('user_id', auth()->user()->id)->where('created_at', '>=', now()->subDays(30))->count() }}
            </div>
            <div class="stat-sub">{{ __('dashboard.last_30_days') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">{{ __('dashboard.credits_used_30d') }}</div>
            <div class="stat-value">
                {{ number_format(\App\Models\UsageLog::where('user_id', auth()->user()->id)->where('created_at', '>=', now()->subDays(30))->sum('credits_deducted')) }}
            </div>
            <div class="stat-sub">{{ __('dashboard.last_30_days') }}</div>
        </div>
    </div>

    @php
        $costService = app(\App\Services\CostService::class);
        $savings = $costService->getMonthlySavings(auth()->user());
    @endphp
    <div class="savings-card">
        <div class="savings-header">
            <h2 class="savings-title">{{ __('dashboard.savings_this_month') }}</h2>
            @if($savings['total_calls'] > 0)
            <button class="savings-toggle" onclick="toggleSavingsTable(this)">{{ __('dashboard.savings_show_all') }} &darr;</button>
            @endif
        </div>
        @if($savings['total_calls'] > 0 && $savings['benchmark'])
            <div class="savings-benchmark">
                <span class="savings-amount">{{ $savings['benchmark']['is_estimate'] ? '~' : '' }}${{ number_format($savings['benchmark']['savings_usd'], 2) }}</span>
                <span class="savings-label">{{ __('dashboard.savings_vs_gpt4o') }}</span>
            </div>
            <div class="savings-kwd">{{ $savings['benchmark']['is_estimate'] ? '~' : '' }}{{ number_format($savings['benchmark']['savings_kwd'], 3) }} KWD {{ __('dashboard.savings_this_month_label') }}</div>
            @if($savings['benchmark']['is_estimate'])
            <div class="savings-estimate-note">{{ __('dashboard.savings_estimate_note') }}</div>
            @endif
            <div id="savings-table-wrapper" class="savings-table-wrapper">
                <table class="savings-table">
                    <thead>
                        <tr>
                            <th>{{ __('dashboard.savings_col_model') }}</th>
                            <th>{{ __('dashboard.savings_col_public_cost') }}</th>
                            <th>{{ __('dashboard.savings_col_our_cost') }}</th>
                            <th>{{ __('dashboard.savings_col_saved') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($savings['comparisons'] as $cmp)
                        <tr>
                            <td>{{ $cmp['name'] }}</td>
                            <td class="text-muted">{{ $cmp['is_estimate'] ? '~' : '' }}${{ number_format($cmp['public_cost_usd'], 4) }}</td>
                            <td class="text-muted">${{ number_format($savings['our_cost_usd'], 4) }}</td>
                            <td class="savings-positive">{{ $cmp['is_estimate'] ? '~' : '' }}${{ number_format($cmp['savings_usd'], 4) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="savings-no-data">{{ __('dashboard.savings_no_data') }}</div>
        @endif
    </div>

    <!-- Model Catalog -->
    <div class="model-catalog">
        <h2 class="catalog-title">{{ __('dashboard.model_catalog') }}</h2>
        <div class="card" style="margin-top:1rem">
            <div class="catalog-header">
                <div class="filter-group">
                    <span class="filter-label">{{ __('dashboard.family') }}:</span>
                    <select id="filter-family" class="filter-select">
                        <option value="">{{ __('dashboard.all_families_value') }}</option>
                        <option value="Llama 3">Llama 3</option>
                        <option value="Qwen">Qwen</option>
                        <option value="Mistral">Mistral</option>
                        <option value="DeepSeek">DeepSeek</option>
                        <option value="Gemma">Gemma</option>
                        <option value="Phi">Phi</option>
                        <option value="GPT">GPT</option>
                        <option value="BGE">BGE</option>
                        <option value="Nomic">Nomic</option>
                        <option value="Snowflake">Snowflake</option>
                        <option value="Cohere">Cohere</option>
                        <option value="Yi">Yi</option>
                        <option value="Fireworks">Fireworks</option>
                        <option value="E5">E5</option>
                        <option value="GTE">GTE</option>
                        <option value="MiniLM">MiniLM</option>
                        <option value="StarCoder">StarCoder</option>
                        <option value="CodeLlama">CodeLlama</option>
                        <option value="Devstral">Devstral</option>
                        <option value="GLM">GLM</option>
                    </select>
                </div>
                <div class="filter-group">
                    <span class="filter-label">{{ __('dashboard.category') }}:</span>
                    <select id="filter-category" class="filter-select">
                        <option value="">{{ __('dashboard.all_categories_value') }}</option>
                        <option value="chat">{{ __('dashboard.chat') }}</option>
                        <option value="code">{{ __('dashboard.code') }}</option>
                        <option value="embedding">{{ __('dashboard.embedding') }}</option>
                        <option value="vision">{{ __('dashboard.vision') }}</option>
                        <option value="thinking">{{ __('dashboard.thinking') }}</option>
                        <option value="tools">{{ __('dashboard.tools') }}</option>
                    </select>
                </div>
                @if(auth()->user()->email === 'admin@llm.resayil.io')
                <div class="filter-group">
                    <span class="filter-label">{{ __('dashboard.type') }}:</span>
                    <select id="filter-type" class="filter-select">
                        <option value="">{{ __('dashboard.all_types_value') }}</option>
                        <option value="local">{{ __('dashboard.local') }}</option>
                        <option value="cloud">{{ __('dashboard.cloud') }}</option>
                    </select>
                </div>
                @else
                <select id="filter-type" style="display:none"><option value="">{{ __('dashboard.all_types_value') }}</option></select>
                @endif
                <div class="filter-group" style="flex-grow:1">
                    <input type="text" id="search-models" class="search-input" placeholder="{{ __('dashboard.search_models') }}">
                </div>
            </div>

            <div id="models-container" class="model-grid">
                <div class="empty-state">{{ __('dashboard.loading_models') }}</div>
            </div>

            <!-- Model Detail Panel -->
            <div id="model-detail-panel" class="detail-panel">
                <div class="detail-header">
                    <div>
                        <h3 class="detail-title" id="detail-name">{{ __('dashboard.model_info') }}</h3>
                        <code class="detail-id" id="detail-id">model-id</code>
                    </div>
                    <button class="detail-close" onclick="closeDetailPanel()">{{ __('dashboard.close') }}</button>
                </div>
                <div class="detail-grid">
                    <div class="detail-section">
                        <h4>{{ __('dashboard.model_info') }}</h4>
                        <p><span class="detail-label">{{ __('dashboard.family') }}:</span><span id="detail-family">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.category') }}:</span><span id="detail-category">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.size') }}:</span><span id="detail-size">-</span></p>
                    </div>
                    <div class="detail-section">
                        <h4>{{ __('dashboard.technical') }}</h4>
                        <p><span class="detail-label">{{ __('dashboard.context_window') }}:</span><span id="detail-context">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.parameters') }}:</span><span id="detail-params">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.quantization') }}:</span><span id="detail-quant">-</span></p>
                    </div>
                    <div class="detail-section">
                        <h4>{{ __('dashboard.pricing_license') }}</h4>
                        <p><span class="detail-label">{{ __('dashboard.credits_per_token') }}:</span><span id="detail-credits">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.license') }}:</span><span id="detail-license">-</span></p>
                        <p><span class="detail-label">{{ __('dashboard.description') }}:</span><span id="detail-desc">-</span></p>
                    </div>
                </div>
                <div class="detail-section">
                    <h4>{{ __('dashboard.usage_examples') }}</h4>
                    <div class="code-tabs">
                        <button class="code-tab active" onclick="switchCodeTab('curl')">{{ __('dashboard.curl') }}</button>
                        <button class="code-tab" onclick="switchCodeTab('python')">{{ __('dashboard.python') }}</button>
                        <button class="code-tab" onclick="switchCodeTab('n8n')">{{ __('dashboard.n8n') }}</button>
                    </div>
                    <div id="code-curl" class="code-block">
                        <pre><code><span class="curl-kw">curl</span> -X POST http://llm.resayil.io/api/v1/chat/completions \
  -H <span class="curl-str">"Authorization: Bearer YOUR_API_KEY"</span> \
  -H <span class="curl-str">"Content-Type: application/json"</span> \
  -d <span class="curl-str">{
    <span class="curl-kw">"model"</span>: <span class="curl-str">"MODEL_ID"</span>,
    <span class="curl-kw">"messages"</span>: [
      {<span class="curl-kw">"role"</span>: <span class="curl-str">"user"</span>, <span class="curl-kw">"content"</span>: <span class="curl-str">"Your message here"</span>}
    ]
  }</span></code></pre>
                        <div class="code-actions" style="margin-top:0.5rem">
                            <button class="btn-copy" onclick="copyCode('curl')">{{ __('dashboard.copy') }}</button>
                        </div>
                    </div>
                    <div id="code-python" class="code-block" style="display:none">
                        <pre><code><span class="py-key">import</span> requests

response = requests.post(
    <span class="py-str">"http://llm.resayil.io/api/v1/chat/completions"</span>,
    headers={
        <span class="py-str">"Authorization"</span>: <span class="py-str">"Bearer YOUR_API_KEY"</span>,
        <span class="py-str">"Content-Type"</span>: <span class="py-str">"application/json"</span>
    },
    json={
        <span class="py-str">"model"</span>: <span class="py-str">"MODEL_ID"</span>,
        <span class="py-str">"messages"</span>: [
            {<span class="py-str">"role"</span>: <span class="py-str">"user"</span>, <span class="py-str">"content"</span>: <span class="py-str">"Your message here"</span>}
        ]
    }
)
<span class="py-func">print</span>(response.json())</code></pre>
                        <div class="code-actions" style="margin-top:0.5rem">
                            <button class="btn-copy" onclick="copyCode('python')">{{ __('dashboard.copy') }}</button>
                        </div>
                    </div>
                    <div id="code-n8n" class="code-block" style="display:none">
                        <pre><code>{
  <span class="n8n-kw">"parameters"</span>: {
    <span class="n8n-kw">"url"</span>: <span class="n8n-str">"http://llm.resayil.io/api/v1/chat/completions"</span>,
    <span class="n8n-kw">"method"</span>: <span class="n8n-str">"POST"</span>,
    <span class="n8n-kw">"body"</span>: {
      <span class="n8n-kw">"model"</span>: <span class="n8n-str">"MODEL_ID"</span>,
      <span class="n8n-kw">"messages"</span>: [
        {
          <span class="n8n-kw">"role"</span>: <span class="n8n-str">"user"</span>,
          <span class="n8n-kw">"content"</span>: <span class="n8n-str">"Your message here"</span>
        }
      ]
    }
  },
  <span class="n8n-kw">"version"</span>: <span class="n8n-num">1</span>
}</code></pre>
                        <div class="code-actions" style="margin-top:0.5rem">
                            <button class="btn-copy" onclick="copyCode('n8n')">{{ __('dashboard.copy') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-xs text-muted" style="margin-top:0.75rem">{{ __('dashboard.click_to_view') }}</p>
    </div>

    <div class="section-grid">
        <!-- API Keys -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="card-heading">{{ __('dashboard.api_keys_title') }}</h2>
                <button onclick="createKey()" class="btn btn-gold" style="padding:0.4rem 0.9rem;font-size:0.8rem">{{ __('dashboard.new_key') }}</button>
            </div>
            <div id="keys-list">
                @php $keys = auth()->user()->apiKeys()->orderByDesc('created_at')->get(); @endphp
                @if($keys->count())
                <div class="keys-wrapper">
                    <table class="keys-table">
                        <thead><tr><th>{{ __('dashboard.api_keys_table_name') }}</th><th>{{ __('dashboard.api_keys_table_key') }}</th><th>{{ __('dashboard.api_keys_table_created') }}</th><th></th></tr></thead>
                        <tbody>
                        @foreach($keys as $key)
                        <tr>
                            <td>{{ $key->name }}</td>
                            <td><span class="key-prefix">{{ $key->prefix }}...****</span></td>
                            <td class="text-muted">{{ $key->created_at->format('d M Y') }}</td>
                            <td>
                                <form method="POST" action="/api-keys/{{ $key->id }}" style="display:inline" onsubmit="return confirm('{{ __('dashboard.delete_key') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.6rem;font-size:0.75rem">{{ __('dashboard.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">{{ __('dashboard.no_api_keys') }} {{ __('dashboard.create_first_key') }}</div>
                @endif
            </div>

            <!-- Create Key Form (hidden) -->
            <div id="create-key-form" style="display:none;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
                <div class="form-group">
                    <label class="form-label">{{ __('dashboard.key_name') }}</label>
                    <input type="text" id="key-name" class="form-input" placeholder="{{ __('dashboard.my_app_key') }}">
                </div>
                <div style="display:flex;gap:0.5rem">
                    <button onclick="submitKey()" class="btn btn-gold" style="padding:0.5rem 1rem;font-size:0.85rem">{{ __('dashboard.create') }}</button>
                    <button onclick="document.getElementById('create-key-form').style.display='none'" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:0.85rem">{{ __('dashboard.cancel') }}</button>
                </div>
                <div id="new-key-display" style="margin-top:0.75rem;display:none">
                    <div class="alert alert-success" style="font-family:monospace;word-break:break-all" id="new-key-value"></div>
                    <p class="text-xs text-muted mt-2">{{ __('dashboard.key_not_shown_again') }}</p>
                </div>
            </div>
        </div>

        <!-- Top Up Credits -->
        <div class="card">
            <h2 class="card-heading-mb">{{ __('dashboard.top_up_credits') }}</h2>
            <div class="topup-grid">
                <div class="topup-card" onclick="topup(500)">
                    <div class="topup-credits">{{ __('dashboard.topup_500') }}</div>
                    <div class="topup-price">{{ __('dashboard.topup_5k_price') }}</div>
                </div>
                <div class="topup-card" onclick="topup(1100)">
                    <div class="topup-credits">{{ __('dashboard.topup_1100') }}</div>
                    <div class="topup-price">{{ __('dashboard.topup_10k_price') }}</div>
                    <div class="topup-bonus">{{ __('dashboard.topup_bonus') }}</div>
                </div>
                <div class="topup-card" onclick="topup(3000)">
                    <div class="topup-credits">{{ __('dashboard.topup_3000') }}</div>
                    <div class="topup-price">{{ __('dashboard.topup_25k_price') }}</div>
                    <div class="topup-bonus">{{ __('dashboard.topup_bonus_20') }}</div>
                </div>
            </div>
            <p class="text-xs text-muted mt-4">{{ __('dashboard.payments_secure') }} · <a href="/billing/plans" class="text-gold">{{ __('dashboard.view_plans') }} &rarr;</a> · <a href="/credits" class="text-gold">{{ __('dashboard.how_credits_work') }}</a></p>

            <hr style="margin:1.25rem 0;border-color:var(--border)">
            <h3 class="card-subheading">{{ __('dashboard.recent_purchases') }}</h3>
            @php $purchases = \App\Models\TopupPurchase::where('user_id', auth()->user()->id)->orderByDesc('created_at')->take(3)->get(); @endphp
            @if($purchases->count())
                @foreach($purchases as $p)
                <div class="flex justify-between items-center text-sm" style="padding:0.4rem 0;border-bottom:1px solid var(--border)">
                    <span>+{{ number_format($p->credits) }} {{ __('dashboard.credits') }}</span>
                    <span class="badge badge-green">{{ $p->price }} KWD</span>
                </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:1rem">{{ __('dashboard.no_purchases') }}</div>
            @endif
        </div>
    </div>

    <!-- Recent Usage -->
    <div class="card">
        <h2 class="card-heading-mb">{{ __('dashboard.recent_api_usage') }}</h2>
        @php $recentComparisons = $costService->getRecentCallComparisons(auth()->user(), 10); @endphp
        @if(count($recentComparisons) > 0)
        <div style="overflow-x:auto">
        <table class="keys-table">
            <thead>
                <tr>
                    <th>{{ __('dashboard.model') }}</th>
                    <th>{{ __('dashboard.usage_input_tokens') }}</th>
                    <th>{{ __('dashboard.usage_output_tokens') }}</th>
                    <th>{{ __('dashboard.tokens') }}</th>
                    <th>{{ __('dashboard.usage_our_cost') }}</th>
                    <th>{{ __('dashboard.usage_vs_gpt4o') }}</th>
                    <th>{{ __('dashboard.credits_used') }}</th>
                    <th>{{ __('dashboard.time') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recentComparisons as $row)
                @php $log = $row['log']; @endphp
                <tr>
                    <td style="font-family:monospace;font-size:0.8rem">{{ preg_replace('/(-cloud|:cloud)$/', '', $log->model) }}</td>
                    <td class="text-muted">{{ is_null($log->prompt_tokens) ? '&mdash;' : number_format($log->prompt_tokens) }}</td>
                    <td class="text-muted">{{ is_null($log->completion_tokens) ? '&mdash;' : number_format($log->completion_tokens) }}</td>
                    <td>{{ number_format($log->tokens_used) }}</td>
                    <td class="text-gold">${{ number_format($row['our_cost_usd'], 4) }}</td>
                    <td class="text-muted">{{ $row['is_estimate'] ? '~' : '' }}${{ number_format($row['gpt4o_cost_usd'], 4) }}</td>
                    <td class="text-gold">{{ $log->credits_deducted }}</td>
                    <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @else
        <div class="empty-state">{{ __('dashboard.no_api_calls') }} {{ __('dashboard.create_api_key_first') }}</div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
// Model Catalog State
let allModels = [];
let selectedModel = null;

// Translation strings for JavaScript
@php
$dashLang = [
    'loading_models' => __('dashboard.loading_models'),
    'no_models_available' => __('dashboard.no_models_available'),
    'error_loading_models' => __('dashboard.error_loading_models'),
    'no_models_match' => __('dashboard.no_models_match'),
    'model_not_found' => __('dashboard.model_not_found'),
    'copied' => __('dashboard.copied'),
    'copy' => __('dashboard.copy'),
    'please_enter_api_key' => __('dashboard.please_enter_api_key'),
    'tokens' => __('dashboard.tokens'),
    'credits_per_token_unit' => __('dashboard.credits_per_token_unit'),
    'default_key_name' => __('dashboard.default_key_name'),
    'key_created_successfully' => __('dashboard.key_created_successfully'),
];
@endphp
const LANG = @json($dashLang);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadModels();

    // Filter event listeners
    document.getElementById('filter-family').addEventListener('change', renderModels);
    document.getElementById('filter-category').addEventListener('change', renderModels);
    document.getElementById('filter-type').addEventListener('change', renderModels);
    document.getElementById('search-models').addEventListener('input', renderModels);
});

// Load models from API
async function loadModels() {
    const container = document.getElementById('models-container');
    container.textContent = '';
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'empty-state';
    loadingDiv.textContent = LANG.loading_models;
    container.appendChild(loadingDiv);

    try {
        const res = await fetch('/models/catalog', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!res.ok) {
            throw new Error('Failed to fetch models');
        }

        const data = await res.json();
        allModels = data.data || [];

        if (allModels.length === 0) {
            container.textContent = '';
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'empty-state';
            emptyDiv.textContent = LANG.no_models_available;
            container.appendChild(emptyDiv);
            return;
        }

        renderModels();
    } catch (error) {
        container.textContent = '';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'empty-state';
        errorDiv.textContent = LANG.error_loading_models.replace(':message', error.message);
        container.appendChild(errorDiv);
    }
}

// Render models based on current filters
function renderModels() {
    if (allModels.length === 0) return;

    const familyFilter = document.getElementById('filter-family').value;
    const categoryFilter = document.getElementById('filter-category').value;
    const typeFilter = document.getElementById('filter-type').value;
    const searchQuery = document.getElementById('search-models').value.toLowerCase();

    const filtered = allModels.filter(model => {
        if (familyFilter && model.family !== familyFilter) return false;
        if (categoryFilter && model.category !== categoryFilter) return false;
        if (typeFilter && model.type !== typeFilter) return false;
        if (searchQuery && !model.id.toLowerCase().includes(searchQuery) && !(model.name||'').toLowerCase().includes(searchQuery)) return false;
        return true;
    });

    renderModelGrid(filtered);
}

// Render model grid
function renderModelGrid(models) {
    const container = document.getElementById('models-container');

    if (models.length === 0) {
        container.textContent = '';
        const noMatchDiv = document.createElement('div');
        noMatchDiv.className = 'empty-state';
        noMatchDiv.textContent = LANG.no_models_match;
        container.appendChild(noMatchDiv);
        return;
    }

    const categoryIcons = { chat:'💬', code:'💻', embedding:'🔗', vision:'👁', thinking:'🧠', tools:'🔧' };
    const html = models.map(model => {
        const cat = model.category || 'chat';
        const icon = categoryIcons[cat] || '';

        return `
            <div class="model-card" onclick="showModelDetail('${encodeURIComponent(model.id)}')">
                <div class="model-name">${escapeHtml(model.id)}</div>
                <div class="model-meta">
                    <span class="model-badge model-badge-local">${icon} ${escapeHtml(cat)}</span>
                    <span class="model-badge">${escapeHtml(model.family || 'unknown')}</span>
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = html;
}

// Show model detail panel
async function showModelDetail(modelId) {
    // Decode the model ID
    const id = decodeURIComponent(modelId);
    selectedModel = allModels.find(m => m.id === id);

    if (!selectedModel) {
        // Try to get from API if not found
        try {
            const res = await fetch(`/models/catalog`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                const data = await res.json();
                selectedModel = data;
            }
        } catch (e) {
            // Ignore, use partial data
        }
    }

    if (!selectedModel) {
        alert(LANG.model_not_found);
        return;
    }

    // Populate detail panel
    document.getElementById('detail-name').textContent = selectedModel.name || selectedModel.id;
    document.getElementById('detail-id').textContent = selectedModel.id;
    document.getElementById('detail-family').textContent = selectedModel.family || '-';
    document.getElementById('detail-category').textContent = selectedModel.category || '-';
    document.getElementById('detail-size').textContent = selectedModel.size || '-';
    document.getElementById('detail-context').textContent = selectedModel.context_window ? `${selectedModel.context_window.toLocaleString()} ${LANG.tokens}` : '-';
    document.getElementById('detail-params').textContent = selectedModel.params || '-';
    document.getElementById('detail-quant').textContent = selectedModel.quantization || '-';
    document.getElementById('detail-credits').textContent = selectedModel.credit_multiplier ? `${selectedModel.credit_multiplier} ${LANG.credits_per_token_unit}` : '-';
    document.getElementById('detail-license').textContent = selectedModel.license || '-';
    document.getElementById('detail-desc').textContent = selectedModel.description || '-';

    // Update code snippets with the model ID
    updateCodeSnippets(selectedModel.id);

    // Show panel
    document.getElementById('model-detail-panel').classList.add('visible');
}

// Close detail panel
function closeDetailPanel() {
    document.getElementById('model-detail-panel').classList.remove('visible');
    selectedModel = null;
}

// Update code snippets
function updateCodeSnippets(modelId) {
    const modelIdEscaped = escapeHtml(modelId);

    const curlSnippet = `curl -X POST http://llm.resayil.io/api/v1/chat/completions \\\n  -H "Authorization: Bearer YOUR_API_KEY" \\\n  -H "Content-Type: application/json" \\\n  -d '{\n    "model": "${modelIdEscaped}",\n    "messages": [\n      {"role": "user", "content": "Your message here"}\n    ]\n  }'`;

    const pythonSnippet = `import requests

response = requests.post(
    "http://llm.resayil.io/api/v1/chat/completions",
    headers={
        "Authorization": "Bearer YOUR_API_KEY",
        "Content-Type": "application/json"
    },
    json={
        "model": "${modelIdEscaped}",
        "messages": [
            {"role": "user", "content": "Your message here"}
        ]
    }
)
print(response.json())`;

    const n8nSnippet = `{
  "parameters": {
    "url": "http://llm.resayil.io/api/v1/chat/completions",
    "method": "POST",
    "body": {
      "model": "${modelIdEscaped}",
      "messages": [
        {
          "role": "user",
          "content": "Your message here"
        }
      ]
    }
  },
  "version": 1
}`;

    document.querySelector('#code-curl pre code').innerHTML = formatCode(curlSnippet, 'curl');
    document.querySelector('#code-python pre code').innerHTML = formatCode(pythonSnippet, 'python');
    document.querySelector('#code-n8n pre code').innerHTML = formatCode(n8nSnippet, 'n8n');
}

// Switch code tab
function switchCodeTab(tab) {
    document.querySelectorAll('.code-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.code-block').forEach(b => b.style.display = 'none');

    document.querySelector(`.code-tab[onclick="switchCodeTab('${tab}')"]`).classList.add('active');
    document.getElementById(`code-${tab}`).style.display = 'block';
}

// Copy code to clipboard
function copyCode(type) {
    const pre = document.querySelector(`#code-${type} pre`);
    const text = pre.innerText;

    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector(`#code-${type} .btn-copy`);
        const originalText = btn.textContent;
        btn.textContent = LANG.copied;
        btn.classList.add('copied');

        setTimeout(() => {
            btn.textContent = originalText;
            btn.classList.remove('copied');
        }, 2000);
    });
}

// Format code for syntax highlighting
function formatCode(code, type) {
    code = escapeHtml(code);

    if (type === 'curl') {
        code = code.replace(/curl/g, '<span class="curl-kw">curl</span>');
        code = code.replace(/-X \w+/g, '<span class="curl-opt">$&</span>');
        code = code.replace(/-H "[^"]*"/g, '<span class="curl-opt">$&</span>');
        code = code.replace(/-d '[^']*'/g, '<span class="curl-str">$&</span>');
        code = code.replace(/"([a-zA-Z_]+)":/g, '<span class="curl-kw">$1</span>:');
        code = code.replace(/"([^"]+)"/g, '<span class="curl-str">$1</span>');
        code = code.replace(/\$[A-Z_]+/g, '<span class="curl-var">$&</span>');
    } else if (type === 'python') {
        code = code.replace(/\b(import|from|return|if|else|elif|for|while|def|class|print)\b/g, '<span class="py-key">$1</span>');
        code = code.replace(/\b(requests|post|get|put|delete)\b/g, '<span class="py-func">$1</span>');
        code = code.replace(/"([^"]*)"/g, '<span class="py-str">$1</span>');
        code = code.replace(/\b(response|json|headers|json)\b/g, '<span class="py-var">$1</span>');
    } else if (type === 'n8n') {
        code = code.replace(/"([a-zA-Z_]+)":/g, '<span class="n8n-kw">$1</span>:');
        code = code.replace(/"([^"]*)"/g, '<span class="n8n-str">$1</span>');
        code = code.replace(/\b(1|2|3|4|5|true|false|null)\b/g, '<span class="n8n-num">$1</span>');
    }

    return code;
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// API Key prompt (if not available)
document.addEventListener('DOMContentLoaded', function() {
    const apiKey = localStorage.getItem('llm_api_key');
    if (!apiKey && window.location.pathname === '/dashboard') {
        setTimeout(() => {
            alert(LANG.please_enter_api_key);
        }, 1000);
    }
});

// createKey - existing function
function createKey() {
    document.getElementById('create-key-form').style.display = 'block';
    document.getElementById('key-name').focus();
}

async function submitKey() {
    const name = document.getElementById('key-name').value || LANG.default_key_name;
    const res = await fetch('/api-keys', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ name })
    });
    const json = await res.json();
    if (res.ok) {
        // Save API key to localStorage for model catalog
        localStorage.setItem('llm_api_key', json.key);

        document.getElementById('new-key-display').style.display = 'block';
        document.getElementById('new-key-value').textContent = json.key || json.api_key || LANG.key_created_successfully;
        setTimeout(() => location.reload(), 5000);
    }
}

function topup(credits) {
    window.location.href = '/billing/plans';
}

function toggleSavingsTable(btn) {
    var wrapper = document.getElementById('savings-table-wrapper');
    if (wrapper && wrapper.classList.contains('expanded')) {
        wrapper.classList.remove('expanded');
        btn.textContent = '{{ __("dashboard.savings_show_all") }} \u2193';
    } else if (wrapper) {
        wrapper.classList.add('expanded');
        btn.textContent = '{{ __("dashboard.savings_hide") }} \u2191';
    }
}
</script>
@endpush
