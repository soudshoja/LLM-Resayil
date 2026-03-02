# Phase 7 Plan 02: Model Selection + Clean UI + Admin Monitoring

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** (1) Clients can discover and select models from their dashboard. (2) All local/cloud mentions removed from client-facing UI — that's internal routing. (3) Admin gets a real monitoring UI with per-user API call counts, token usage, and model breakdown.

**Architecture:**
- Model catalog: `ModelsController` filters Ollama's list, removes `:cloud` suffix models, returns only clean model names. User dashboard shows available models per tier.
- Provider hiding: Remove `provider` badge from client-facing usage table. Keep in DB and admin views only.
- Monitoring: New `/admin/monitoring` page with usage breakdown by user, model, and time period. Reads from `usage_logs` table.

**Tech Stack:** Laravel 11, Blade, Ollama API at 208.110.93.90:11434, `usage_logs` table

---

## Task 1: Clean up ModelsController — hide :cloud models, return clean names

**Files:**
- Modify: `app/Http/Controllers/Api/ModelsController.php`

**Context:** Currently exposes ALL Ollama models including internal `:cloud` routing models (e.g. `qwen3.5:cloud`, `deepseek-v3.2:cloud`). Clients should never see these.

**Step 1: Read the current ModelsController**
```bash
cat app/Http/Controllers/Api/ModelsController.php
```

**Step 2: Update to filter cloud-routing models**

In the `index()` method, after fetching models from Ollama, filter out any model whose name ends in `:cloud` or contains `remote_model`. Also filter by tier — Basic gets small models, Pro gets medium, Enterprise gets all.

Model tier mapping (based on what's loaded on the GPU server):
```php
protected array $tierModels = [
    'basic'      => ['llama3.2:3b', 'smollm2:135m'],
    'pro'        => ['llama3.2:3b', 'smollm2:135m', 'qwen2.5-coder:14b', 'mistral-small3.2:24b-instruct-2506-q4_K_M'],
    'enterprise' => ['llama3.2:3b', 'smollm2:135m', 'qwen2.5-coder:14b', 'mistral-small3.2:24b-instruct-2506-q4_K_M',
                     'glm-4.7-flash:latest', 'qwen3-30b-40k:latest', 'gpt-oss:20b',
                     'hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M'],
];
```

Response format (OpenAI-compatible):
```json
{
  "object": "list",
  "data": [
    { "id": "llama3.2:3b", "object": "model", "created": 1234567890, "owned_by": "llm-resayil" }
  ]
}
```

**Step 3: Commit**
```bash
git add app/Http/Controllers/Api/ModelsController.php
git commit -m "fix: ModelsController filters cloud-routing models, applies tier access control"
```

---

## Task 2: Remove local/cloud provider badge from user dashboard

**Files:**
- Modify: `resources/views/dashboard.blade.php`

**Step 1: Find the provider badge in the usage table**

The "Recent API Usage" table currently shows:
```blade
<span class="badge {{ $log->provider === 'cloud' ? 'badge-gold' : 'badge-green' }}">{{ ucfirst($log->provider) }}</span>
```

**Step 2: Remove the Type column entirely**

Remove the `<th>Type</th>` header and the `<td>` with the provider badge. Clients don't need to know where their request was routed.

Also remove the "Provider" label from anywhere else on the page.

**Step 3: Commit**
```bash
git add resources/views/dashboard.blade.php
git commit -m "fix: remove local/cloud provider info from client-facing dashboard"
```

---

## Task 3: Add model catalog section to user dashboard

**Files:**
- Modify: `resources/views/dashboard.blade.php`

**Step 1: Add a "Available Models" section**

After the stats grid, add a collapsible or simple card showing the models available for the user's tier. Example:

```blade
<!-- Available Models -->
<div class="card" style="margin-bottom:1.5rem">
    <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Available Models</h2>
    <p class="text-secondary text-sm" style="margin-bottom:1rem">
        Use these model names in your API requests. Your <span class="badge badge-gold">{{ ucfirst(auth()->user()->subscription_tier) }}</span> plan includes:
    </p>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem">
        @php
        $tierModels = [
            'basic'      => [['id'=>'llama3.2:3b','desc'=>'Fast, general purpose, 3B'],['id'=>'smollm2:135m','desc'=>'Ultra-fast, 135M']],
            'pro'        => [['id'=>'llama3.2:3b','desc'=>'Fast, general purpose, 3B'],['id'=>'smollm2:135m','desc'=>'Ultra-fast, 135M'],['id'=>'qwen2.5-coder:14b','desc'=>'Code specialist, 14B'],['id'=>'mistral-small3.2:24b-instruct-2506-q4_K_M','desc'=>'Balanced quality, 24B']],
            'enterprise' => [['id'=>'llama3.2:3b','desc'=>'Fast, general purpose, 3B'],['id'=>'smollm2:135m','desc'=>'Ultra-fast, 135M'],['id'=>'qwen2.5-coder:14b','desc'=>'Code specialist, 14B'],['id'=>'mistral-small3.2:24b-instruct-2506-q4_K_M','desc'=>'Balanced quality, 24B'],['id'=>'glm-4.7-flash:latest','desc'=>'High quality, 30B'],['id'=>'qwen3-30b-40k:latest','desc'=>'Long context 40K, 30B'],['id'=>'gpt-oss:20b','desc'=>'OpenAI OSS, 20B'],['id'=>'hf.co/Qwen/Qwen3-VL-32B-Instruct-GGUF:Q4_K_M','desc'=>'Vision + text, 32B']],
        ];
        $models = $tierModels[auth()->user()->subscription_tier] ?? $tierModels['basic'];
        @endphp
        @foreach($models as $m)
        <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;padding:0.75rem">
            <div style="font-family:monospace;font-size:0.8rem;color:var(--gold);margin-bottom:0.25rem">{{ $m['id'] }}</div>
            <div class="text-xs text-muted">{{ $m['desc'] }}</div>
        </div>
        @endforeach
    </div>
</div>
```

**Step 2: Commit**
```bash
git add resources/views/dashboard.blade.php
git commit -m "feat: add available models catalog to user dashboard"
```

---

## Task 4: Create admin monitoring page

**Files:**
- Create: `resources/views/admin/monitoring.blade.php`
- Modify: `routes/web.php` (add route)
- Modify: `resources/views/layouts/app.blade.php` (add nav link for admin)

**Step 1: Add route in web.php**

Inside the admin middleware group:
```php
Route::get('/monitoring', function () { return view('admin.monitoring'); })->name('admin.monitoring');
```

**Step 2: Add nav link in admin section**

In `layouts/app.blade.php`, in the admin nav section:
```blade
@if(auth()->user()->email === 'admin@llm.resayil.io')
<a href="/admin" style="color:var(--gold)">Admin</a>
<a href="/admin/monitoring" style="color:var(--gold)">Monitor</a>
@endif
```

**Step 3: Create `resources/views/admin/monitoring.blade.php`**

```blade
@extends('layouts.app')
@section('title', 'API Monitoring')

@section('content')
<main>
    <div style="margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:700">API Monitoring</h1>
        <p class="text-secondary text-sm">Real-time usage analytics across all users</p>
    </div>

    @php
        $now = now();
        // Platform totals
        $totalCallsToday   = \App\Models\UsageLog::whereDate('created_at', today())->count();
        $totalCallsWeek    = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(7))->count();
        $totalCallsMonth   = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->count();
        $totalTokensMonth  = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->sum('tokens_used');
        $totalCreditsMonth = \App\Models\UsageLog::where('created_at', '>=', $now->copy()->subDays(30))->sum('credits_deducted');

        // Per-user breakdown (last 30 days)
        $userStats = \App\Models\UsageLog::selectRaw('user_id, count(*) as calls, sum(tokens_used) as tokens, sum(credits_deducted) as credits')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('user_id')
            ->orderByDesc('calls')
            ->with('user')
            ->get();

        // Top models (last 30 days)
        $modelStats = \App\Models\UsageLog::selectRaw('model, count(*) as calls, sum(tokens_used) as tokens')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('model')
            ->orderByDesc('calls')
            ->get();

        // Recent calls (last 50)
        $recentCalls = \App\Models\UsageLog::with('user')
            ->orderByDesc('created_at')
            ->take(50)
            ->get();
    @endphp

    <!-- Platform Totals -->
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:2rem">
        @foreach([
            ['Today\'s Calls', $totalCallsToday],
            ['Calls (7d)', number_format($totalCallsWeek)],
            ['Calls (30d)', number_format($totalCallsMonth)],
            ['Tokens (30d)', number_format($totalTokensMonth)],
            ['Credits Used (30d)', number_format($totalCreditsMonth)],
        ] as [$label, $value])
        <div class="stat-card" style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:1.25rem">
            <div style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">{{ $label }}</div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--gold)">{{ $value }}</div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem">
        <!-- Per-user stats -->
        <div class="card">
            <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Top Users (30d)</h2>
            @if($userStats->count())
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:left;border-bottom:1px solid var(--border)">User</th>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">Calls</th>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">Tokens</th>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">Credits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userStats as $stat)
                    <tr>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem">
                            <div>{{ $stat->user?->name ?: '—' }}</div>
                            <div class="text-xs text-muted">{{ $stat->user?->email }}</div>
                        </td>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($stat->calls) }}</td>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($stat->tokens) }}</td>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right;color:var(--gold)">{{ number_format($stat->credits) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">No API calls yet.</div>
            @endif
        </div>

        <!-- Top models -->
        <div class="card">
            <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Top Models (30d)</h2>
            @if($modelStats->count())
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:left;border-bottom:1px solid var(--border)">Model</th>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">Calls</th>
                        <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0;text-align:right;border-bottom:1px solid var(--border)">Tokens</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modelStats as $stat)
                    <tr>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-family:monospace;font-size:0.8rem;color:var(--gold)">{{ $stat->model }}</td>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($stat->calls) }}</td>
                        <td style="padding:0.6rem 0;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($stat->tokens) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">No API calls yet.</div>
            @endif
        </div>
    </div>

    <!-- Recent API Calls -->
    <div class="card">
        <h2 style="font-size:1rem;font-weight:600;margin-bottom:1rem">Recent API Calls</h2>
        @if($recentCalls->count())
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:left;border-bottom:1px solid var(--border)">User</th>
                    <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:left;border-bottom:1px solid var(--border)">Model</th>
                    <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">Tokens</th>
                    <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">Credits</th>
                    <th style="font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;padding:0.5rem 0.75rem;text-align:right;border-bottom:1px solid var(--border)">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCalls as $call)
                <tr>
                    <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem">
                        {{ $call->user?->email ?? '—' }}
                    </td>
                    <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-family:monospace;font-size:0.8rem;color:var(--gold)">{{ $call->model }}</td>
                    <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right">{{ number_format($call->tokens_used) }}</td>
                    <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.875rem;text-align:right;color:var(--gold)">{{ $call->credits_deducted }}</td>
                    <td style="padding:0.6rem 0.75rem;border-bottom:1px solid rgba(30,34,48,0.5);font-size:0.75rem;color:var(--text-muted);text-align:right">{{ $call->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem">No API calls yet.</div>
        @endif
    </div>
</main>
@endsection
```

**Step 4: Commit**
```bash
git add resources/views/admin/monitoring.blade.php routes/web.php resources/views/layouts/app.blade.php
git commit -m "feat: add admin monitoring page with per-user API usage, token counts, model breakdown"
```

---

## Task 5: Deploy and verify

```bash
git push
ssh whm-server "cd ~/llm.resayil.io && git pull && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear"
```

Verify in browser:
- `/dashboard` — models section visible, no local/cloud badge
- `/admin/monitoring` — loads with stats tables
- `/api/v1/models` — returns only clean model names, no `:cloud` entries
