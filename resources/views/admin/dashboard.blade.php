@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; }
    .users-table td { padding: 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')
<main>
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">Admin Dashboard</h1>
        <p class="text-secondary text-sm">Platform overview and management</p>
    </div>

    @php
        $totalUsers = \App\Models\User::count();
        $activeSubscriptions = \App\Models\Subscriptions::where('status', 'active')->count();
        $totalRevenue = \App\Models\TopupPurchase::where('status', 'paid')->sum('amount_kwd') + \App\Models\Subscriptions::where('status', 'active')->count() * 10;
        $totalApiCalls = \App\Models\UsageLog::count();
        $cloudBudget = \App\Models\CloudBudget::orderByDesc('created_at')->first();
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Subscriptions</div>
            <div class="stat-value">{{ $activeSubscriptions }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total API Calls</div>
            <div class="stat-value">{{ number_format($totalApiCalls) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Cloud Budget Used</div>
            <div class="stat-value">{{ $cloudBudget ? $cloudBudget->daily_requests : 0 }}/500</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 style="font-size:1rem;font-weight:600">All Users</h2>
        </div>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Name / Email</th>
                    <th>Phone</th>
                    <th>Plan</th>
                    <th>Credits</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::orderByDesc('created_at')->paginate(20) as $user)
                <tr>
                    <td>
                        <div>{{ $user->name ?: '—' }}</div>
                        <div class="text-muted text-xs">{{ $user->email }}</div>
                    </td>
                    <td class="text-secondary">{{ $user->phone }}</td>
                    <td><span class="badge badge-gold">{{ ucfirst($user->subscription_tier) }}</span></td>
                    <td>{{ number_format($user->credits) }}</td>
                    <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection
