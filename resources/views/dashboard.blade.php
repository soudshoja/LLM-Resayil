@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-header { margin-bottom: 2rem; }
    .dash-header h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    .stat-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; }
    .section-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
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
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .section-grid { grid-template-columns: 1fr; } }
    @media(max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<main>
    <div class="dash-header">
        <h1>Welcome back, {{ auth()->user()->name ?: auth()->user()->email }}</h1>
        <div class="text-secondary text-sm">
            Plan: <span class="badge badge-gold">{{ ucfirst(auth()->user()->subscription_tier) }}</span>
            @if(auth()->user()->subscription_expiry)
            &nbsp;· Expires: {{ auth()->user()->subscription_expiry->format('d M Y') }}
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Credits Remaining</div>
            <div class="stat-value">{{ number_format(auth()->user()->credits) }}</div>
            <div class="stat-sub">Available for API calls</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">API Keys</div>
            <div class="stat-value">{{ auth()->user()->apiKeys()->count() }}</div>
            <div class="stat-sub">Active keys</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Requests (30d)</div>
            <div class="stat-value">
                {{ \App\Models\UsageLog::where('user_id', auth()->user()->id)->where('created_at', '>=', now()->subDays(30))->count() }}
            </div>
            <div class="stat-sub">Last 30 days</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Credits Used (30d)</div>
            <div class="stat-value">
                {{ number_format(\App\Models\UsageLog::where('user_id', auth()->user()->id)->where('created_at', '>=', now()->subDays(30))->sum('credits_deducted')) }}
            </div>
            <div class="stat-sub">Last 30 days</div>
        </div>
    </div>

    <!-- Available Models -->
    <div class="card" style="margin-bottom:1.5rem">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem">Available Models</h2>
        <p class="text-secondary text-sm" style="margin-bottom:1rem">Use these model IDs in your API requests. Your <span class="badge badge-gold">{{ ucfirst(auth()->user()->subscription_tier) }}</span> plan includes:</p>
        @php
        $tierModels = [
            'basic'      => [['id'=>'llama3.2:3b','desc'=>'Fast · 3B · General'],['id'=>'smollm2:135m','desc'=>'Ultra-fast · 135M']],
            'pro'        => [['id'=>'llama3.2:3b','desc'=>'Fast · 3B · General'],['id'=>'smollm2:135m','desc'=>'Ultra-fast · 135M'],['id'=>'qwen2.5-coder:14b','desc'=>'Code · 14B'],['id'=>'mistral-small3.2:24b-instruct-2506-q4_K_M','desc'=>'Balanced · 24B']],
            'enterprise' => [['id'=>'llama3.2:3b','desc'=>'Fast · 3B · General'],['id'=>'smollm2:135m','desc'=>'Ultra-fast · 135M'],['id'=>'qwen2.5-coder:14b','desc'=>'Code · 14B'],['id'=>'mistral-small3.2:24b-instruct-2506-q4_K_M','desc'=>'Balanced · 24B'],['id'=>'glm-4.7-flash:latest','desc'=>'High quality · 30B'],['id'=>'qwen3-30b-40k:latest','desc'=>'Long context 40K · 30B'],['id'=>'gpt-oss:20b','desc'=>'OpenAI OSS · 20B'],['id'=>'hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M','desc'=>'Vision + text · 32B']],
        ];
        $models = $tierModels[auth()->user()->subscription_tier] ?? $tierModels['basic'];
        @endphp
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.75rem">
            @foreach($models as $m)
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.75rem;cursor:pointer" onclick="navigator.clipboard.writeText('{{ $m['id'] }}').then(()=>alert('Copied: {{ $m['id'] }}'))">
                <div style="font-family:monospace;font-size:0.78rem;color:var(--gold);margin-bottom:0.25rem">{{ $m['id'] }}</div>
                <div class="text-xs text-muted">{{ $m['desc'] }}</div>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-muted" style="margin-top:0.75rem">Click any model to copy its ID to clipboard.</p>
    </div>

    <div class="section-grid">
        <!-- API Keys -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 style="font-size:1rem;font-weight:600">API Keys</h2>
                <button onclick="createKey()" class="btn btn-gold" style="padding:0.4rem 0.9rem;font-size:0.8rem">+ New Key</button>
            </div>
            <div id="keys-list">
                @php $keys = auth()->user()->apiKeys()->orderByDesc('created_at')->get(); @endphp
                @if($keys->count())
                <table class="keys-table">
                    <thead><tr><th>Name</th><th>Key</th><th>Created</th><th></th></tr></thead>
                    <tbody>
                    @foreach($keys as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td><span class="key-prefix">{{ $key->prefix }}...****</span></td>
                        <td class="text-muted">{{ $key->created_at->format('d M Y') }}</td>
                        <td>
                            <form method="POST" action="/api-keys/{{ $key->id }}" style="display:inline" onsubmit="return confirm('Delete this key?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.6rem;font-size:0.75rem">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">No API keys yet. Create one to start making requests.</div>
                @endif
            </div>

            <!-- Create Key Form (hidden) -->
            <div id="create-key-form" style="display:none;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
                <div class="form-group">
                    <label class="form-label">Key Name</label>
                    <input type="text" id="key-name" class="form-input" placeholder="My App Key">
                </div>
                <div style="display:flex;gap:0.5rem">
                    <button onclick="submitKey()" class="btn btn-gold" style="padding:0.5rem 1rem;font-size:0.85rem">Create</button>
                    <button onclick="document.getElementById('create-key-form').style.display='none'" class="btn btn-outline" style="padding:0.5rem 1rem;font-size:0.85rem">Cancel</button>
                </div>
                <div id="new-key-display" style="margin-top:0.75rem;display:none">
                    <div class="alert alert-success" style="font-family:monospace;word-break:break-all" id="new-key-value"></div>
                    <p class="text-xs text-muted mt-2">Copy this key now — it won't be shown again.</p>
                </div>
            </div>
        </div>

        <!-- Top Up Credits -->
        <div class="card">
            <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Top Up Credits</h2>
            <div class="topup-grid">
                <div class="topup-card" onclick="topup('5k')">
                    <div class="topup-credits">5,000</div>
                    <div class="topup-price">2 KWD</div>
                </div>
                <div class="topup-card" onclick="topup('15k')">
                    <div class="topup-credits">15,000</div>
                    <div class="topup-price">5 KWD</div>
                </div>
                <div class="topup-card" onclick="topup('50k')">
                    <div class="topup-credits">50,000</div>
                    <div class="topup-price">15 KWD</div>
                </div>
            </div>
            <p class="text-xs text-muted mt-4">Payments processed securely via MyFatoorah (KNET/credit card)</p>

            <hr style="margin:1.25rem 0;border-color:var(--border)">
            <h3 style="font-size:0.875rem;font-weight:600;margin-bottom:0.75rem">Recent Purchases</h3>
            @php $purchases = \App\Models\TopupPurchase::where('user_id', auth()->user()->id)->orderByDesc('created_at')->take(3)->get(); @endphp
            @if($purchases->count())
                @foreach($purchases as $p)
                <div class="flex justify-between items-center text-sm" style="padding:0.4rem 0;border-bottom:1px solid var(--border)">
                    <span>+{{ number_format($p->credits) }} credits</span>
                    <span class="badge badge-green">{{ $p->price }} KWD</span>
                </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:1rem">No purchases yet.</div>
            @endif
        </div>
    </div>

    <!-- Recent Usage -->
    <div class="card">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Recent API Usage</h2>
        @php $logs = \App\Models\UsageLog::where('user_id', auth()->user()->id)->orderByDesc('created_at')->take(10)->get(); @endphp
        @if($logs->count())
        <table class="keys-table">
            <thead><tr><th>Model</th><th>Tokens</th><th>Credits Used</th><th>Time</th></tr></thead>
            <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="font-family:monospace;font-size:0.8rem">{{ $log->model }}</td>
                <td>{{ number_format($log->tokens_used) }}</td>
                <td class="text-gold">{{ $log->credits_deducted }}</td>
                <td class="text-muted">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No API calls yet. Create an API key and make your first request!</div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
function createKey() {
    document.getElementById('create-key-form').style.display = 'block';
    document.getElementById('key-name').focus();
}

async function submitKey() {
    const name = document.getElementById('key-name').value || 'Default';
    const res = await fetch('/api-keys', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ name })
    });
    const json = await res.json();
    if (res.ok) {
        document.getElementById('new-key-display').style.display = 'block';
        document.getElementById('new-key-value').textContent = json.key || json.api_key || 'Key created successfully!';
        setTimeout(() => location.reload(), 5000);
    }
}

async function topup(pack) {
    const res = await fetch('/billing/payment/topup', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ pack })
    });
    const json = await res.json();
    if (json.payment_url) window.location.href = json.payment_url;
}
</script>
@endpush
