@extends('layouts.app')

@section('title', 'User Detail — ' . ($user->name ?: $user->email) . ' — Admin')

@push('styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1.5rem; transition: color 0.2s; }
    .back-link:hover { color: var(--gold); }
    .user-header { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
    .user-avatar { width: 56px; height: 56px; border-radius: 50%; background: rgba(212,175,55,0.15); border: 2px solid rgba(212,175,55,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: var(--gold); flex-shrink: 0; }
    .user-meta { flex: 1; min-width: 0; }
    .user-name { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; }
    .user-email { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.5rem; word-break: break-all; }
    .user-badges { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .tier-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .tier-admin    { background: rgba(212,175,55,0.2);  color: #d4af37; }
    .tier-pro      { background: rgba(138,43,226,0.15); color: #a855f7; }
    .tier-basic    { background: rgba(30,144,255,0.15); color: #60a5fa; }
    .tier-starter  { background: rgba(100,100,100,0.15); color: var(--text-muted); }
    .tier-enterprise { background: rgba(16,185,129,0.15); color: #34d399; }
    .info-pill { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; background: rgba(255,255,255,0.06); color: var(--text-secondary); }
    .user-header-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }
    .credit-balance { font-size: 1.5rem; font-weight: 700; color: var(--gold); }
    .credit-label { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; text-align: right; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .stat-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .section-title { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; }
    .keys-table, .logs-table { width: 100%; border-collapse: collapse; }
    .keys-table th, .logs-table th { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; white-space: nowrap; }
    .keys-table th.text-right, .logs-table th.text-right { text-align: right; }
    .keys-table td, .logs-table td { padding: 0.65rem 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; vertical-align: middle; }
    .keys-table td.text-right, .logs-table td.text-right { text-align: right; }
    .key-prefix { font-family: monospace; font-size: 0.82rem; color: var(--gold); background: rgba(212,175,55,0.08); padding: 0.15rem 0.4rem; border-radius: 4px; }
    .status-active   { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; background: rgba(16,185,129,0.15); color: #34d399; }
    .status-inactive { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; background: rgba(239,68,68,0.12); color: #f87171; }
    .model-pill { display: inline-block; font-family: monospace; font-size: 0.75rem; color: var(--gold); background: rgba(212,175,55,0.1); padding: 0.15rem 0.5rem; border-radius: 4px; white-space: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis; }
    .status-200 { color: #34d399; }
    .status-4xx { color: #f59e0b; }
    .status-5xx { color: #f87171; }
    .pagination-wrap { margin-top: 1.25rem; display: flex; justify-content: center; }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .user-header { flex-direction: column; } .user-header-right { align-items: flex-start; } }
    @media(max-width: 640px) { .logs-table th:nth-child(n+5), .logs-table td:nth-child(n+5) { display: none; } }
</style>
@endpush

@section('content')
<main>
    <a href="{{ route('admin.users') }}" class="back-link">← All Users</a>

    {{-- User header card --}}
    <div class="user-header">
        <div style="display:flex;align-items:flex-start;gap:1rem;flex:1;min-width:0">
            <div class="user-avatar">{{ strtoupper(substr($user->name ?: $user->email, 0, 1)) }}</div>
            <div class="user-meta">
                <div class="user-name">{{ $user->name ?: '—' }}</div>
                <div class="user-email">{{ $user->email }}</div>
                <div class="user-badges">
                    @php
                        $tier = $user->subscription_tier ?? 'starter';
                        $tierClass = 'tier-' . $tier;
                        $tierIcons = ['admin' => '★', 'pro' => '◆', 'enterprise' => '⬡', 'basic' => '●', 'starter' => '○'];
                        $tierIcon = $tierIcons[$tier] ?? '○';
                    @endphp
                    <span class="tier-badge {{ $tierClass }}">{{ $tierIcon }} {{ ucfirst($tier) }}</span>
                    @if($user->phone)
                        <span class="info-pill">📞 {{ $user->phone }}</span>
                    @endif
                    @if($user->subscription_expiry)
                        <span class="info-pill {{ $user->subscription_expiry->isPast() ? 'status-5xx' : '' }}">
                            Expires {{ $user->subscription_expiry->format('d M Y') }}
                        </span>
                    @endif
                    <span class="info-pill">Joined {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="user-header-right">
            <div class="credit-label">Credit Balance</div>
            <div class="credit-balance">{{ number_format($user->credits) }}</div>
        </div>
    </div>

    {{-- 4 stats cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total API Calls</div>
            <div class="stat-value">{{ number_format($totalCalls) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Tokens</div>
            <div class="stat-value">{{ number_format($totalTokens) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Credits Spent</div>
            <div class="stat-value">{{ number_format($totalCredits) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Avg Tokens / Call</div>
            <div class="stat-value">{{ $totalCalls > 0 ? number_format(intdiv($totalTokens, $totalCalls)) : '—' }}</div>
            <div class="stat-sub">per API call</div>
        </div>
    </div>

    {{-- API Keys table --}}
    <div class="card" style="margin-bottom:1.5rem">
        <div class="section-title">API Keys ({{ $apiKeys->count() }})</div>
        @if($apiKeys->count())
        <table class="keys-table">
            <thead>
                <tr>
                    <th>Key Name</th>
                    <th>Prefix</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Used</th>
                </tr>
            </thead>
            <tbody>
                @foreach($apiKeys as $key)
                <tr>
                    <td style="font-weight:500">{{ $key->name }}</td>
                    <td><span class="key-prefix">{{ $key->prefix }}…</span></td>
                    <td>
                        <span class="status-{{ $key->status === 'active' ? 'active' : 'inactive' }}">
                            {{ ucfirst($key->status) }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8rem">{{ $key->created_at->format('d M Y') }}</td>
                    <td style="color:var(--text-muted);font-size:0.8rem">
                        {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">No API keys created yet.</div>
        @endif
    </div>

    {{-- Usage log table --}}
    <div class="card">
        <div class="section-title">Usage History ({{ number_format($totalCalls) }} total calls)</div>
        @if($usageLogs->count())
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Model</th>
                    <th class="text-right">Prompt Tokens</th>
                    <th class="text-right">Completion Tokens</th>
                    <th class="text-right">Total Tokens</th>
                    <th class="text-right">Credits</th>
                    <th class="text-right">Status</th>
                    <th class="text-right">Response (ms)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usageLogs as $log)
                <tr>
                    <td style="color:var(--text-muted);font-size:0.78rem;white-space:nowrap">
                        {{ $log->created_at->format('d M Y, H:i:s') }}
                    </td>
                    <td><span class="model-pill" title="{{ $log->model }}">{{ $log->model }}</span></td>
                    <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->prompt_tokens ?? 0) }}</td>
                    <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->completion_tokens ?? 0) }}</td>
                    <td class="text-right" style="font-weight:500">{{ number_format($log->tokens_used) }}</td>
                    <td class="text-right" style="color:var(--gold);font-weight:500">{{ $log->credits_deducted }}</td>
                    <td class="text-right">
                        @php $sc = $log->status_code ?? 200; @endphp
                        <span class="{{ $sc >= 500 ? 'status-5xx' : ($sc >= 400 ? 'status-4xx' : 'status-200') }}" style="font-family:monospace;font-size:0.8rem">
                            {{ $sc }}
                        </span>
                    </td>
                    <td class="text-right" style="color:var(--text-muted);font-size:0.8rem">
                        {{ $log->response_time_ms !== null ? number_format($log->response_time_ms) : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($usageLogs->hasPages())
        <div class="pagination-wrap">
            {{ $usageLogs->links() }}
        </div>
        @endif
        @else
        <div style="text-align:center;padding:2.5rem;color:var(--text-muted);font-size:0.875rem">No API calls recorded for this user yet.</div>
        @endif
    </div>
</main>
@endsection
