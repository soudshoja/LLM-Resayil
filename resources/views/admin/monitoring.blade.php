@extends('layouts.app')
@section('title', __('admin.monitoring.title'))

@section('content')
<main>
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">{{ __('admin.monitoring.title') }}</h1>
        <p class="text-secondary text-sm">{{ __('admin.monitoring.subtitle') }}</p>
    </div>

    @php
        $now = now();
        $totalCallsToday   = \App\Models\UsageLog::whereDate('created_at', today())->count();
        $totalCallsWeek    = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(7))->count();
        $totalCallsMonth   = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $totalTokensMonth  = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->sum('tokens_used');
        $totalCreditsMonth = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->sum('credits_deducted');

        $userStats = \App\Models\UsageLog::selectRaw('user_id, count(*) as calls, sum(tokens_used) as tokens, sum(credits_deducted) as credits')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('user_id')
            ->orderByDesc('calls')
            ->get()
            ->map(function($s) { $s->user = \App\Models\User::find($s->user_id); return $s; });

        $modelStats = \App\Models\UsageLog::selectRaw('model, count(*) as calls, sum(tokens_used) as tokens')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('model')
            ->orderByDesc('calls')
            ->get();

        $recentCalls = \App\Models\UsageLog::orderByDesc('created_at')->take(50)->get()
            ->map(function($c) { $c->user = \App\Models\User::find($c->user_id); return $c; });
    @endphp

    {{-- Platform totals --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:2rem">
        @foreach([[__('admin.monitoring.today_calls',$totalCallsToday],[__('admin.monitoring.calls_7d'),number_format($totalCallsWeek)],[__('admin.monitoring.calls_30d'),number_format($totalCallsMonth)],[__('admin.monitoring.tokens_30d'),number_format($totalTokensMonth)],[__('admin.monitoring.credits_30d'),number_format($totalCreditsMonth)]] as [$label,$value])
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ $label }}</div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $value }}</div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem">
        {{-- Per-user stats --}}
        <div class="card">
            <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">{{ __('admin.monitoring.top_users') }}</h2>
            @if($userStats->count())
            <table style="width:100%;border-collapse:collapse">
                <thead><tr>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:left;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.user') }}</th>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.calls') }}</th>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.tokens') }}</th>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.credits') }}</th>
                </tr></thead>
                <tbody>
                @foreach($userStats as $s)
                <tr>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem">
                        <div>{{ $s->user?->name ?: '—' }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted)">{{ $s->user?->email }}</div>
                    </td>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($s->calls) }}</td>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($s->tokens) }}</td>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right;color:var(--gold)">{{ number_format($s->credits) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">{{ __('admin.monitoring.no_api_calls') }}</div>
            @endif
        </div>

        {{-- Top models --}}
        <div class="card">
            <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">{{ __('admin.monitoring.top_models') }}</h2>
            @if($modelStats->count())
            <table style="width:100%;border-collapse:collapse">
                <thead><tr>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:left;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.model') }}</th>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.calls') }}</th>
                    <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.tokens') }}</th>
                </tr></thead>
                <tbody>
                @foreach($modelStats as $s)
                <tr>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-family:monospace;font-size:0.8rem;color:var(--gold)">{{ $s->model }}</td>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($s->calls) }}</td>
                    <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($s->tokens) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">{{ __('admin.monitoring.no_api_calls') }}</div>
            @endif
        </div>
    </div>

    {{-- Recent calls --}}
    <div class="card">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">{{ __('admin.monitoring.recent_calls') }}</h2>
        @if($recentCalls->count())
        <table style="width:100%;border-collapse:collapse">
            <thead><tr>
                <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:left;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.user') }}</th>
                <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:left;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.model') }}</th>
                <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.tokens') }}</th>
                <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.credits') }}</th>
                <th style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">{{ __('admin.monitoring.time') }}</th>
            </tr></thead>
            <tbody>
            @foreach($recentCalls as $c)
            <tr>
                <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem">{{ $c->user?->email ?? '—' }}</td>
                <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-family:monospace;font-size:0.78rem;color:var(--gold)">{{ $c->model }}</td>
                <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($c->tokens_used) }}</td>
                <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right;color:var(--gold)">{{ $c->credits_deducted }}</td>
                <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.75rem;color:var(--text-muted);text-align:right">{{ $c->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">{{ __('admin.monitoring.no_api_calls') }}</div>
        @endif
    </div>
</main>
@endsection
