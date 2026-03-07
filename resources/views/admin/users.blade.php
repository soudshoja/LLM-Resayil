@extends('layouts.app')

@section('title', 'User Monitoring — Admin')

@push('styles')
<style>
    .page-header { margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.5rem; font-weight: 700; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .search-bar { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; }
    .search-input { flex: 1; padding: 0.5rem 0.875rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 0.875rem; }
    .search-input:focus { outline: 2px solid var(--gold-muted, rgba(212,175,55,0.4)); border-color: transparent; }
    .search-input::placeholder { color: var(--text-muted); }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 0.75rem; border-bottom: 1px solid var(--border); text-align: left; white-space: nowrap; }
    .users-table th.text-right { text-align: right; }
    .users-table td { padding: 0.7rem 0.75rem; border-bottom: 1px solid rgba(30,34,48,0.5); font-size: 0.875rem; vertical-align: middle; }
    .users-table td.text-right { text-align: right; }
    .tier-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.55rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .tier-admin    { background: rgba(212,175,55,0.2);  color: #d4af37; }
    .tier-pro      { background: rgba(138,43,226,0.15); color: #a855f7; }
    .tier-basic    { background: rgba(30,144,255,0.15); color: #60a5fa; }
    .tier-starter  { background: rgba(100,100,100,0.15); color: var(--text-muted); }
    .tier-enterprise { background: rgba(16,185,129,0.15); color: #34d399; }
    .btn-view { padding: 0.3rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 6px; background: rgba(212,175,55,0.12); color: var(--gold); border: 1px solid rgba(212,175,55,0.25); text-decoration: none; display: inline-block; transition: all 0.2s; white-space: nowrap; }
    .btn-view:hover { background: rgba(212,175,55,0.22); border-color: rgba(212,175,55,0.5); }
    .back-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1.5rem; transition: color 0.2s; }
    .back-link:hover { color: var(--gold); }
    .gold { color: var(--gold); }
    .pagination-wrap { margin-top: 1.25rem; display: flex; justify-content: center; }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(max-width: 640px) { .users-table th:nth-child(n+5), .users-table td:nth-child(n+5) { display: none; } }
</style>
@endpush

@section('content')
<main>
    <a href="{{ route('admin.dashboard') }}" class="back-link">← Admin Dashboard</a>

    <div class="page-header">
        <h1>User Monitoring</h1>
        <p class="text-secondary text-sm">API call history, token usage, and credits spent per user</p>
    </div>

    @php
        $totalUsers  = \App\Models\User::count();
        $totalCalls  = \App\Models\UsageLog::count();
        $totalTokens = \App\Models\UsageLog::sum('tokens_used');
        $totalSpent  = \App\Models\UsageLog::sum('credits_deducted');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
        </div>
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
            <div class="stat-value">{{ number_format($totalSpent) }}</div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-bar">
        <input
            type="text"
            class="search-input"
            id="userSearch"
            placeholder="Filter by name or email..."
            oninput="filterUsers(this.value)"
        >
    </div>

    <div class="card">
        <table class="users-table" id="usersTable">
            <thead>
                <tr>
                    <th>Name / Email</th>
                    <th>Phone</th>
                    <th>Tier</th>
                    <th class="text-right">Credits</th>
                    <th class="text-right">API Keys</th>
                    <th class="text-right">Total Calls</th>
                    <th class="text-right">Total Tokens</th>
                    <th class="text-right">Credits Spent</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td>
                        <div style="font-weight:500">{{ $user->name ?: '—' }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted)">{{ $user->email }}</div>
                    </td>
                    <td style="color:var(--text-secondary)">{{ $user->phone ?: '—' }}</td>
                    <td>
                        @php
                            $tier = $user->subscription_tier ?? 'starter';
                            $tierClass = 'tier-' . $tier;
                            $tierIcons = ['admin' => '★', 'pro' => '◆', 'enterprise' => '⬡', 'basic' => '●', 'starter' => '○'];
                            $tierIcon = $tierIcons[$tier] ?? '○';
                        @endphp
                        <span class="tier-badge {{ $tierClass }}">{{ $tierIcon }} {{ ucfirst($tier) }}</span>
                    </td>
                    <td class="text-right gold">{{ number_format($user->credits) }}</td>
                    <td class="text-right" style="color:var(--text-secondary)">{{ $user->api_keys_count }}</td>
                    <td class="text-right" style="color:var(--text-secondary)">
                        @php $callCount = \App\Models\UsageLog::where('user_id', $user->id)->count(); @endphp
                        {{ number_format($callCount) }}
                    </td>
                    <td class="text-right" style="color:var(--text-secondary)">{{ number_format($user->usage_logs_sum_tokens_used ?? 0) }}</td>
                    <td class="text-right gold">{{ number_format($user->usage_logs_sum_credits_deducted ?? 0) }}</td>
                    <td style="color:var(--text-muted);font-size:0.8rem;white-space:nowrap">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.detail', $user->id) }}" class="btn-view">View Usage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:2.5rem;color:var(--text-muted)">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</main>

<script>
function filterUsers(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr[data-search]').forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
