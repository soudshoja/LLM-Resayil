---
phase: 13-usage-visibility
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - app/Http/Controllers/Admin/AdminController.php
  - database/migrations/2026_03_08_000001_add_created_by_to_api_keys_table.php
  - app/Models/ApiKeys.php
  - app/Http/Middleware/ApiKeyAuth.php
autonomous: true
requirements: [USAGE-01, USAGE-02, USAGE-03]

must_haves:
  truths:
    - "Admin credit top-up ADDS credits to existing balance (not replaces it)"
    - "api_keys table has created_by column (uuid nullable FK to users.id)"
    - "ApiKeyAuth middleware rejects revoked keys with 401"
  artifacts:
    - path: "database/migrations/2026_03_08_000001_add_created_by_to_api_keys_table.php"
      provides: "created_by column on api_keys"
      contains: "created_by"
    - path: "app/Http/Controllers/Admin/AdminController.php"
      provides: "Fixed setUserCredits and updated createApiKeyForUser"
      contains: "increment"
  key_links:
    - from: "app/Http/Controllers/Admin/AdminController.php"
      to: "users.credits"
      via: "increment() method"
      pattern: "increment\\('credits'"
    - from: "app/Http/Middleware/ApiKeyAuth.php"
      to: "api_keys.status"
      via: "status check"
      pattern: "status.*active"
---

<objective>
Wave 1 — Unblocking Fixes (parallel-safe, no UI dependencies).

Three targeted fixes that unblock everything downstream:
1. Admin credit top-up bug: replace SET with INCREMENT so adding credits accumulates instead of overwriting.
2. api_keys created_by migration: formalise the schema column needed by Feature 4/5 (admin key management and user badges).
3. ApiKeyAuth status check: enforce that revoked keys are rejected at the middleware level.

Purpose: These are prod-critical fixes. The credit bug means every admin top-up since launch has wiped existing credits. The status check means revoking a key currently has no effect.
Output: One migration file, two modified PHP files.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/PROJECT.md
@.planning/ROADMAP.md
@.planning/STATE.md
@app/Http/Controllers/Admin/AdminController.php
@app/Http/Middleware/ApiKeyAuth.php
@app/Models/ApiKeys.php
</context>

<tasks>

<task type="auto">
  <name>Task 1: Fix admin credit top-up (SET → INCREMENT) and wire created_by on key creation</name>
  <files>app/Http/Controllers/Admin/AdminController.php</files>
  <action>
Two changes in this file:

**Change A — setUserCredits() (line ~67):**
Replace:
```php
$user->credits = (int) $credits;
$user->save();
return response()->json(['success' => true, 'message' => "Credits updated to {$credits}."]);
```
With:
```php
$user->increment('credits', (int) $credits);
return response()->json(['success' => true, 'message' => "Added {$credits} credits. New balance: " . ($user->fresh()->credits) . "."]);
```
Do NOT use `$user->save()` — `increment()` fires the UPDATE directly and is atomic.

**Change B — createApiKeyForUser() (line ~34):**
Add `'created_by' => auth()->id()` to the ApiKeys::create() array, so admin-created keys are flagged:
```php
ApiKeys::create([
    'user_id'     => $user->id,
    'name'        => $keyName,
    'key'         => $apiKey,
    'prefix'      => $prefix,
    'permissions' => ['read', 'write'],
    'status'      => 'active',
    'created_by'  => auth()->id(),
]);
```
No other changes to this file.
  </action>
  <verify>
Read the file and confirm `increment('credits'` appears in setUserCredits() and `created_by` appears in createApiKeyForUser(). No `$user->credits =` assignment remains in setUserCredits.
  </verify>
  <done>setUserCredits uses increment(); createApiKeyForUser sets created_by = auth()->id()</done>
</task>

<task type="auto">
  <name>Task 2: Migration — add created_by to api_keys + revoke check in ApiKeyAuth</name>
  <files>
    database/migrations/2026_03_08_000001_add_created_by_to_api_keys_table.php
    app/Models/ApiKeys.php
    app/Http/Middleware/ApiKeyAuth.php
  </files>
  <action>
**File 1 — Create migration:**
`database/migrations/2026_03_08_000001_add_created_by_to_api_keys_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            if (!Schema::hasColumn('api_keys', 'created_by')) {
                $table->uuid('created_by')->nullable()->after('status');
                $table->foreign('created_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
```

Note: The `status` column already exists (migration `2026_03_02_000001_add_status_to_api_keys_table.php`). Only `created_by` is new here.

**File 2 — Add created_by to ApiKeys fillable:**
`app/Models/ApiKeys.php`

Add `'created_by'` to the `$fillable` array (after `'status'`):
```php
protected $fillable = [
    'user_id',
    'name',
    'key',
    'prefix',
    'status',
    'created_by',
    'permissions',
    'last_used_at',
];
```

Also add a `createdBy` relationship method:
```php
public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}
```

**File 3 — ApiKeyAuth middleware: enforce status='active':**
`app/Http/Middleware/ApiKeyAuth.php`

After the `if (!$apiKey)` block (line ~42), add a status check immediately after:
```php
if ($apiKey->status !== 'active') {
    return response()->json([
        'message' => 'Unauthenticated.',
        'error'   => 'API key has been revoked.',
    ], 401);
}
```
Insert this block right after the existing `if (!$apiKey)` return block, before the permissions check.
  </action>
  <verify>
Confirm migration file exists at `database/migrations/2026_03_08_000001_add_created_by_to_api_keys_table.php`. Confirm `created_by` in ApiKeys `$fillable`. Confirm `status !== 'active'` check in ApiKeyAuth before the permissions check.
  </verify>
  <done>Migration created; ApiKeys model updated; ApiKeyAuth rejects status != active</done>
</task>

<task type="auto">
  <name>Task 3: Run migration on dev server</name>
  <files>none (server operation)</files>
  <action>
Run the migration on the dev server to confirm it applies cleanly:

```bash
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan migrate"
```

If the command succeeds, the `created_by` column is live on dev. Do NOT run on prod yet — prod migration runs after the full feature branch is merged.

If migration fails with "Column already exists", the `Schema::hasColumn()` guard handles it gracefully — check the output carefully. A "Nothing to migrate" result means the migration already ran (check the filename timestamp).
  </action>
  <verify>
Migration command exits 0. Output contains "Migrating: 2026_03_08_000001_add_created_by_to_api_keys_table" and "Migrated".
  </verify>
  <done>created_by column exists on dev api_keys table</done>
</task>

</tasks>

<verification>
After Wave 1:
- `AdminController::setUserCredits` contains `increment('credits'` — no bare assignment
- `ApiKeys::$fillable` contains `'created_by'`
- `ApiKeyAuth` returns 401 for `status !== 'active'` before any permissions check
- Dev migration ran without errors
</verification>

<success_criteria>
- Admin adding 500 credits to a user with 100 credits results in 600 credits (verified via tinker on dev)
- A key with status='revoked' in the DB gets a 401 response from the API
- `php artisan migrate` on dev reports the created_by migration as Migrated
</success_criteria>

<output>
After completion, create `.planning/phases/13-usage-visibility/13-01-SUMMARY.md`
</output>

---
---

---
phase: 13-usage-visibility
plan: 02
type: execute
wave: 2
depends_on: [13-01]
files_modified:
  - app/Http/Controllers/UsageDashboardController.php
  - resources/views/usage.blade.php
  - routes/web.php
autonomous: true
requirements: [USAGE-04]

must_haves:
  truths:
    - "User visits /usage and sees summary cards: credits remaining, calls today, calls this month, credits used this month"
    - "User sees a paginated table of their API call history with timestamp, model, prompt tokens, completion tokens, credits deducted, response time, status code"
    - "User can filter by date range (from/to) and model via GET params"
    - "User can download their full call log as CSV via /usage/export"
  artifacts:
    - path: "app/Http/Controllers/UsageDashboardController.php"
      provides: "index() and export() methods"
      exports: ["index", "export"]
    - path: "resources/views/usage.blade.php"
      provides: "Usage dashboard Blade view"
      contains: "credits-remaining"
  key_links:
    - from: "resources/views/usage.blade.php"
      to: "UsageDashboardController@index"
      via: "GET /usage route"
      pattern: "route.*usage"
    - from: "UsageDashboardController@export"
      to: "usage_logs"
      via: "streamDownload CSV"
      pattern: "streamDownload"
---

<objective>
Wave 2a — User Usage Dashboard.

Users get a self-service view of their API consumption at /usage. Summary cards show key metrics at a glance. The paginated call log table shows every request with full token/credit detail. Filters let users narrow by date range and model. CSV export lets power users analyse their own data externally.

Purpose: Transparency builds trust. Users who can see exactly where credits go are less likely to churn over "billing confusion".
Output: UsageDashboardController, usage.blade.php view, two new routes.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md
@app/Models/UsageLog.php
@app/Models/User.php
@resources/views/api-keys.blade.php
@resources/views/layouts/app.blade.php
@routes/web.php
</context>

<interfaces>
<!-- Key types the executor needs. No codebase exploration required. -->

From app/Models/UsageLog.php:
```php
// Table: usage_logs
// Columns: id (uuid), user_id, api_key_id (nullable uuid), model (string),
//          tokens_used (int), prompt_tokens (nullable int), completion_tokens (nullable int),
//          credits_deducted (int), provider (enum: local|cloud), response_time_ms (int),
//          status_code (int), created_at, updated_at

// Scopes available:
public function scopeToday($query)         // whereDate('created_at', today)
public function scopeForUser($query, $id)  // where('user_id', $id)
```

From app/Models/User.php (assumed from context):
```php
// Columns include: credits (int), subscription_tier (string)
// Relationship: $user->apiKeys() -> hasMany(ApiKeys)
```
</interfaces>

<tasks>

<task type="auto">
  <name>Task 1: Create UsageDashboardController with index() and export()</name>
  <files>app/Http/Controllers/UsageDashboardController.php</files>
  <action>
Create `app/Http/Controllers/UsageDashboardController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\UsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UsageDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Summary stats
        $creditsRemaining = $user->credits;
        $callsToday       = UsageLog::forUser($user->id)->today()->count();
        $callsThisMonth   = UsageLog::forUser($user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $creditsThisMonth = UsageLog::forUser($user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('credits_deducted');

        // Filters
        $from  = $request->query('from');
        $to    = $request->query('to');
        $model = $request->query('model');

        // Available models for filter dropdown (distinct values from this user's logs)
        $availableModels = UsageLog::forUser($user->id)
            ->select('model')
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        // Query
        $query = UsageLog::forUser($user->id)->orderByDesc('created_at');

        if ($from) {
            $query->whereDate('created_at', '>=', Carbon::parse($from));
        }
        if ($to) {
            $query->whereDate('created_at', '<=', Carbon::parse($to));
        }
        if ($model) {
            $query->where('model', $model);
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('usage', compact(
            'creditsRemaining',
            'callsToday',
            'callsThisMonth',
            'creditsThisMonth',
            'logs',
            'availableModels',
            'from',
            'to',
            'model'
        ));
    }

    public function export(Request $request)
    {
        $user  = $request->user();
        $from  = $request->query('from');
        $to    = $request->query('to');
        $model = $request->query('model');

        $query = UsageLog::forUser($user->id)->orderByDesc('created_at');

        if ($from) {
            $query->whereDate('created_at', '>=', \Carbon\Carbon::parse($from));
        }
        if ($to) {
            $query->whereDate('created_at', '<=', \Carbon\Carbon::parse($to));
        }
        if ($model) {
            $query->where('model', $model);
        }

        $logs = $query->get();

        $filename = 'usage-export-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Timestamp', 'Model', 'Provider',
                'Prompt Tokens', 'Completion Tokens', 'Total Tokens',
                'Credits Deducted', 'Response Time (ms)', 'Status Code',
            ]);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->toDateTimeString(),
                    $log->model,
                    $log->provider,
                    $log->prompt_tokens ?? 0,
                    $log->completion_tokens ?? 0,
                    $log->tokens_used,
                    $log->credits_deducted,
                    $log->response_time_ms,
                    $log->status_code,
                ]);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
```
  </action>
  <verify>
File exists. Contains `public function index(` and `public function export(`. Contains `streamDownload`. Contains `forUser($user->id)`.
  </verify>
  <done>Controller created with both methods</done>
</task>

<task type="auto">
  <name>Task 2: Create usage.blade.php view and register routes</name>
  <files>
    resources/views/usage.blade.php
    routes/web.php
  </files>
  <action>
**File 1 — Create `resources/views/usage.blade.php`:**

Dark luxury design, matching the style of `api-keys.blade.php` (cards, table, monospace prefix styling). Structure:

```blade
@extends('layouts.app')

@section('title', 'Usage Dashboard — LLM Resayil')

@push('styles')
<style>
    .usage-header { margin-bottom: 2rem; }
    .usage-header h1 { font-size: 1.5rem; font-weight: 700; }
    .usage-header p { color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem; }

    /* Summary cards */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gold); }
    @media(max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }

    /* Filters */
    .filters-row { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 0.3rem; }
    .filter-group label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .filter-input { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.75rem; color: var(--text-primary); font-size: 0.875rem; font-family: inherit; }
    .filter-input:focus { outline: none; border-color: var(--gold-muted); }
    .btn-filter { background: var(--gold); color: #0a0d14; border: none; padding: 0.5rem 1.1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; }
    .btn-reset { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
    .btn-export { background: transparent; border: 1px solid rgba(212,175,55,0.4); color: var(--gold); padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .btn-export:hover { background: rgba(212,175,55,0.08); }

    /* Table */
    .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .usage-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .usage-table thead th { padding: 0.85rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .usage-table tbody td { padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .usage-table tbody tr:last-child td { border-bottom: none; }
    .usage-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .model-badge { font-family: 'Courier New', monospace; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 6px; padding: 0.2rem 0.55rem; font-size: 0.78rem; color: var(--gold); }
    .status-ok { color: #6ee7b7; font-size: 0.8rem; font-weight: 600; }
    .status-err { color: #f87171; font-size: 0.8rem; font-weight: 600; }
    .text-right { text-align: right; }
    .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--text-muted); }

    /* Pagination */
    .pagination-wrap { display: flex; justify-content: center; padding: 1.25rem; gap: 0.4rem; flex-wrap: wrap; }
    .pagination-wrap a, .pagination-wrap span { padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.85rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; }
    .pagination-wrap a:hover { border-color: var(--gold-muted); color: var(--gold); }
    .pagination-wrap span.active-page { background: rgba(212,175,55,0.15); border-color: rgba(212,175,55,0.4); color: var(--gold); font-weight: 600; }
</style>
@endpush

@section('content')
<main>
    <div class="usage-header">
        <h1>Usage Dashboard</h1>
        <p>Your API call history and credit consumption</p>
    </div>

    {{-- Summary Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Credits Remaining</div>
            <div class="stat-value">{{ number_format($creditsRemaining) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Calls Today</div>
            <div class="stat-value">{{ number_format($callsToday) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Calls This Month</div>
            <div class="stat-value">{{ number_format($callsThisMonth) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Credits Used This Month</div>
            <div class="stat-value">{{ number_format($creditsThisMonth) }}</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="/usage">
        <div class="filters-row">
            <div class="filter-group">
                <label>From</label>
                <input type="date" name="from" value="{{ $from }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>To</label>
                <input type="date" name="to" value="{{ $to }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>Model</label>
                <select name="model" class="filter-input">
                    <option value="">All models</option>
                    @foreach($availableModels as $m)
                        <option value="{{ $m }}" {{ $model === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group" style="justify-content:flex-end">
                <label>&nbsp;</label>
                <div style="display:flex;gap:0.5rem;align-items:center">
                    <button type="submit" class="btn-filter">Filter</button>
                    <a href="/usage" class="btn-reset">Reset</a>
                    <a href="/usage/export?from={{ $from }}&to={{ $to }}&model={{ $model }}" class="btn-export">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-wrap">
        @if($logs->isEmpty())
            <div class="empty-state">
                <p style="font-size:1.1rem;margin-bottom:0.5rem">No API calls recorded yet</p>
                <p style="font-size:0.875rem">Make your first API call to see usage data here.</p>
            </div>
        @else
            <table class="usage-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Model</th>
                        <th class="text-right">Prompt Tokens</th>
                        <th class="text-right">Completion Tokens</th>
                        <th class="text-right">Credits</th>
                        <th class="text-right">Response (ms)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;white-space:nowrap">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td><span class="model-badge">{{ $log->model }}</span></td>
                        <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->prompt_tokens ?? 0) }}</td>
                        <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->completion_tokens ?? 0) }}</td>
                        <td class="text-right" style="color:var(--gold);font-weight:600">{{ number_format($log->credits_deducted) }}</td>
                        <td class="text-right" style="color:var(--text-muted)">{{ number_format($log->response_time_ms) }}</td>
                        <td>
                            @if($log->status_code >= 200 && $log->status_code < 300)
                                <span class="status-ok">{{ $log->status_code }}</span>
                            @else
                                <span class="status-err">{{ $log->status_code }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($logs->hasPages())
            <div class="pagination-wrap">
                @foreach($logs->links()->elements as $element)
                    @if(is_string($element))
                        <span>{{ $element }}</span>
                    @elseif(is_array($element))
                        @foreach($element as $page => $url)
                            @if($page == $logs->currentPage())
                                <span class="active-page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>
            @endif
        @endif
    </div>
</main>
@endsection
```

**File 2 — Register routes in `routes/web.php`:**

Inside the `@auth` protected area (after the existing `/api-keys` route group, before the billing group), add:

```php
// Usage dashboard (protected)
Route::middleware('auth')->group(function () {
    Route::get('/usage', [\App\Http\Controllers\UsageDashboardController::class, 'index'])->name('usage');
    Route::get('/usage/export', [\App\Http\Controllers\UsageDashboardController::class, 'export'])->name('usage.export');
});
```

Also add the nav link: in `resources/views/layouts/app.blade.php`, inside the `@auth` block, add after the `/api-keys` link:
```html
<a href="/usage">Usage</a>
```
  </action>
  <verify>
Visit `/usage` on dev (after logging in) — returns 200 with four stat cards visible. `/usage/export` triggers a CSV download. `/usage?model=llama3.2` filters correctly.
  </verify>
  <done>
/usage renders with stat cards and paginated log table. /usage/export returns a CSV file. Filters work via GET params.
  </done>
</task>

</tasks>

<verification>
- GET /usage (authenticated) → 200, page contains "Credits Remaining", "Calls Today"
- GET /usage/export → Content-Type: text/csv, filename usage-export-*.csv
- GET /usage?from=2026-01-01&to=2026-12-31&model=llama3.2 → filtered results only
- Pagination appears when > 50 logs exist
</verification>

<success_criteria>
- User sees 4 summary cards with real data from usage_logs
- Full call log table renders with all 7 columns
- CSV export contains headers + one row per matching log entry
- Nav link "Usage" appears for authenticated users
</success_criteria>

<output>
After completion, create `.planning/phases/13-usage-visibility/13-02-SUMMARY.md`
</output>

---
---

---
phase: 13-usage-visibility
plan: 03
type: execute
wave: 2
depends_on: [13-01]
files_modified:
  - app/Http/Controllers/Admin/UsageController.php
  - resources/views/admin/usage-users.blade.php
  - resources/views/admin/usage-user-detail.blade.php
  - resources/views/admin/usage-keys.blade.php
  - routes/web.php
autonomous: true
requirements: [USAGE-05, USAGE-06]

must_haves:
  truths:
    - "Admin visits /admin/usage/users and sees a table: user name/email, total calls, total tokens, credits consumed, last active, subscription tier"
    - "Admin clicks a user row to see /admin/usage/users/{id} with their full call log (same table structure as user dashboard)"
    - "Admin visits /admin/usage/keys and sees: key name, owner email, prefix (masked), total calls, last used, status, created_by label"
  artifacts:
    - path: "app/Http/Controllers/Admin/UsageController.php"
      provides: "users(), userDetail(), keys() methods"
      exports: ["users", "userDetail", "keys"]
    - path: "resources/views/admin/usage-users.blade.php"
      provides: "Admin usage-by-user view"
      contains: "total_calls"
    - path: "resources/views/admin/usage-keys.blade.php"
      provides: "Admin usage-by-key view"
      contains: "prefix"
  key_links:
    - from: "resources/views/admin/usage-users.blade.php"
      to: "/admin/usage/users/{id}"
      via: "clickable row / link"
      pattern: "admin/usage/users"
    - from: "UsageController@keys"
      to: "api_keys JOIN users"
      via: "withCount and with('user')"
      pattern: "withCount.*usageLogs"
---

<objective>
Wave 2b — Admin Usage Views (parallel with Plan 02, no shared files).

Two admin views: usage aggregated by user (with drill-down), and usage aggregated by API key. Admins get a platform-wide lens into consumption patterns — who is using the API, how much, and which keys are active.

Purpose: Support and trust. Admins need to identify heavy users, diagnose billing disputes, and spot rogue keys — without needing direct DB access.
Output: AdminUsageController, three Blade views, four new admin routes.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md
@app/Models/UsageLog.php
@app/Models/ApiKeys.php
@app/Models/User.php
@resources/views/admin/dashboard.blade.php
@routes/web.php
</context>

<interfaces>
<!-- Extracted from codebase for executor reference -->

From app/Models/UsageLog (relevant scopes):
```php
// scopeForUser($query, $userId) — where('user_id', $id)
// scopeToday($query) — whereDate('created_at', today())
```

Admin middleware pattern (from web.php):
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // All admin routes live here
});
```

Existing admin nav links in layouts/app.blade.php:
```html
<a href="/admin" class="nav-link-gold">Admin</a>
<a href="/admin/monitoring" class="nav-link-gold">Monitor</a>
<a href="/admin/models" class="nav-link-gold">Models</a>
```
</interfaces>

<tasks>

<task type="auto">
  <name>Task 1: Create AdminUsageController with users(), userDetail(), and keys()</name>
  <files>app/Http/Controllers/Admin/UsageController.php</files>
  <action>
Create `app/Http/Controllers/Admin/UsageController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKeys;
use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsageController extends Controller
{
    /**
     * Usage summary per user — aggregated.
     */
    public function users(Request $request)
    {
        $users = User::select('users.*')
            ->leftJoin('usage_logs', 'users.id', '=', 'usage_logs.user_id')
            ->selectRaw('
                COUNT(usage_logs.id) as total_calls,
                COALESCE(SUM(usage_logs.tokens_used), 0) as total_tokens,
                COALESCE(SUM(usage_logs.credits_deducted), 0) as total_credits,
                MAX(usage_logs.created_at) as last_active
            ')
            ->groupBy('users.id')
            ->orderByDesc('total_calls')
            ->paginate(50);

        return view('admin.usage-users', compact('users'));
    }

    /**
     * Full call log for a specific user — admin drill-down.
     */
    public function userDetail(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $from  = $request->query('from');
        $to    = $request->query('to');
        $model = $request->query('model');

        $query = UsageLog::forUser($userId)->orderByDesc('created_at');

        if ($from) {
            $query->whereDate('created_at', '>=', \Carbon\Carbon::parse($from));
        }
        if ($to) {
            $query->whereDate('created_at', '<=', \Carbon\Carbon::parse($to));
        }
        if ($model) {
            $query->where('model', $model);
        }

        $logs = $query->paginate(50)->withQueryString();

        $availableModels = UsageLog::forUser($userId)
            ->select('model')->distinct()->orderBy('model')->pluck('model');

        return view('admin.usage-user-detail', compact('user', 'logs', 'availableModels', 'from', 'to', 'model'));
    }

    /**
     * Usage summary per API key — aggregated.
     */
    public function keys(Request $request)
    {
        $keys = ApiKeys::with(['user', 'createdBy'])
            ->withCount('usageLogs')
            ->orderByDesc('usage_logs_count')
            ->paginate(100);

        return view('admin.usage-keys', compact('keys'));
    }
}
```

Also add the `usageLogs` relationship to `app/Models/ApiKeys.php` (needed for `withCount`):

In `ApiKeys.php`, add after the existing `user()` method:
```php
public function usageLogs()
{
    return $this->hasMany(\App\Models\UsageLog::class, 'api_key_id');
}
```
  </action>
  <verify>
File exists. Contains `public function users(`, `public function userDetail(`, `public function keys(`. ApiKeys.php contains `usageLogs()` relationship.
  </verify>
  <done>Controller created with three action methods; ApiKeys model has usageLogs relationship</done>
</task>

<task type="auto">
  <name>Task 2: Create three admin Blade views and register routes</name>
  <files>
    resources/views/admin/usage-users.blade.php
    resources/views/admin/usage-user-detail.blade.php
    resources/views/admin/usage-keys.blade.php
    routes/web.php
  </files>
  <action>
**File 1 — `resources/views/admin/usage-users.blade.php`:**

```blade
@extends('layouts.app')
@section('title', 'Usage by User — Admin')

@push('styles')
<style>
    .admin-header { margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; }
    .admin-nav { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
    .admin-nav a { padding: 0.45rem 1rem; border-radius: 8px; font-size: 0.875rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; }
    .admin-nav a.active, .admin-nav a:hover { border-color: var(--gold-muted); color: var(--gold); background: rgba(212,175,55,0.06); }
    .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .data-table thead th { padding: 0.85rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .data-table tbody td { padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.025); cursor: pointer; }
    .tier-badge { display: inline-flex; padding: 0.18rem 0.55rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
    .tier-basic { background: rgba(100,100,100,0.15); color: #9ca3af; border: 1px solid rgba(100,100,100,0.3); }
    .tier-pro { background: rgba(212,175,55,0.12); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); }
    .tier-enterprise { background: rgba(99,102,241,0.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3); }
    .text-muted { color: var(--text-muted); }
    .text-right { text-align: right; }
    .gold { color: var(--gold); font-weight: 600; }
</style>
@endpush

@section('content')
<main>
    <div class="admin-header">
        <div>
            <h1>Usage Analytics</h1>
            <p style="color:var(--text-muted);font-size:0.875rem;margin-top:0.25rem">Platform-wide API consumption</p>
        </div>
        <a href="/admin" style="color:var(--text-muted);font-size:0.875rem">← Admin</a>
    </div>

    <div class="admin-nav">
        <a href="/admin/usage/users" class="active">By User</a>
        <a href="/admin/usage/keys">By API Key</a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tier</th>
                    <th class="text-right">Total Calls</th>
                    <th class="text-right">Total Tokens</th>
                    <th class="text-right">Credits Consumed</th>
                    <th>Last Active</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr onclick="location.href='/admin/usage/users/{{ $user->id }}'" title="View call log">
                    <td>
                        <div style="font-weight:500">{{ $user->name }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted)">{{ $user->email }}</div>
                    </td>
                    <td>
                        <span class="tier-badge tier-{{ $user->subscription_tier ?? 'basic' }}">
                            {{ ucfirst($user->subscription_tier ?? 'basic') }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($user->total_calls) }}</td>
                    <td class="text-right text-muted">{{ number_format($user->total_tokens) }}</td>
                    <td class="text-right gold">{{ number_format($user->total_credits) }}</td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">
                        {{ $user->last_active ? \Carbon\Carbon::parse($user->last_active)->diffForHumans() : 'Never' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted)">No usage data yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div style="padding:1rem;text-align:center">{{ $users->links() }}</div>
        @endif
    </div>
</main>
@endsection
```

**File 2 — `resources/views/admin/usage-user-detail.blade.php`:**

```blade
@extends('layouts.app')
@section('title', 'Usage: {{ $user->name }} — Admin')

@push('styles')
<style>
    .admin-header { margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.4rem; font-weight: 700; }
    .filters-row { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 0.3rem; }
    .filter-group label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .filter-input { background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 0.45rem 0.75rem; color: var(--text-primary); font-size: 0.875rem; font-family: inherit; }
    .btn-filter { background: var(--gold); color: #0a0d14; border: none; padding: 0.5rem 1.1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; }
    .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .data-table thead th { padding: 0.85rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .data-table tbody td { padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .model-badge { font-family: monospace; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 5px; padding: 0.15rem 0.5rem; font-size: 0.78rem; color: var(--gold); }
    .text-right { text-align: right; }
    .status-ok { color: #6ee7b7; font-weight: 600; font-size: 0.8rem; }
    .status-err { color: #f87171; font-weight: 600; font-size: 0.8rem; }
</style>
@endpush

@section('content')
<main>
    <div class="admin-header">
        <a href="/admin/usage/users" style="color:var(--text-muted);font-size:0.875rem;display:block;margin-bottom:0.75rem">← Back to Usage by User</a>
        <h1>{{ $user->name }}</h1>
        <p style="color:var(--text-muted);font-size:0.875rem;margin-top:0.25rem">{{ $user->email }} &middot; {{ ucfirst($user->subscription_tier ?? 'basic') }}</p>
    </div>

    <form method="GET" action="/admin/usage/users/{{ $user->id }}">
        <div class="filters-row">
            <div class="filter-group">
                <label>From</label>
                <input type="date" name="from" value="{{ $from }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>To</label>
                <input type="date" name="to" value="{{ $to }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>Model</label>
                <select name="model" class="filter-input">
                    <option value="">All</option>
                    @foreach($availableModels as $m)
                        <option value="{{ $m }}" {{ $model === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group" style="justify-content:flex-end">
                <label>&nbsp;</label>
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-wrap">
        @if($logs->isEmpty())
            <div style="text-align:center;padding:3rem;color:var(--text-muted)">No calls recorded for this user.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Model</th>
                        <th class="text-right">Prompt Tokens</th>
                        <th class="text-right">Completion Tokens</th>
                        <th class="text-right">Credits</th>
                        <th class="text-right">Response (ms)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;white-space:nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td><span class="model-badge">{{ $log->model }}</span></td>
                        <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->prompt_tokens ?? 0) }}</td>
                        <td class="text-right" style="color:var(--text-secondary)">{{ number_format($log->completion_tokens ?? 0) }}</td>
                        <td class="text-right" style="color:var(--gold);font-weight:600">{{ number_format($log->credits_deducted) }}</td>
                        <td class="text-right" style="color:var(--text-muted)">{{ number_format($log->response_time_ms) }}</td>
                        <td>
                            @if($log->status_code >= 200 && $log->status_code < 300)
                                <span class="status-ok">{{ $log->status_code }}</span>
                            @else
                                <span class="status-err">{{ $log->status_code }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($logs->hasPages())
            <div style="padding:1rem;text-align:center">{{ $logs->links() }}</div>
            @endif
        @endif
    </div>
</main>
@endsection
```

**File 3 — `resources/views/admin/usage-keys.blade.php`:**

```blade
@extends('layouts.app')
@section('title', 'Usage by API Key — Admin')

@push('styles')
<style>
    .admin-header { margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; }
    .admin-nav { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
    .admin-nav a { padding: 0.45rem 1rem; border-radius: 8px; font-size: 0.875rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; }
    .admin-nav a.active, .admin-nav a:hover { border-color: var(--gold-muted); color: var(--gold); background: rgba(212,175,55,0.06); }
    .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .data-table thead th { padding: 0.85rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .data-table tbody td { padding: 0.85rem 1.1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .prefix-mono { font-family: monospace; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 5px; padding: 0.15rem 0.5rem; font-size: 0.8rem; color: var(--gold); }
    .status-active { color: #6ee7b7; font-size: 0.8rem; font-weight: 600; }
    .status-revoked { color: #f87171; font-size: 0.8rem; font-weight: 600; }
    .badge-self { background: rgba(100,100,100,0.15); color: var(--text-secondary); border: 1px solid rgba(100,100,100,0.3); padding: 0.15rem 0.5rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
    .badge-admin { background: rgba(212,175,55,0.12); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); padding: 0.15rem 0.5rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
    .text-right { text-align: right; }
</style>
@endpush

@section('content')
<main>
    <div class="admin-header">
        <div>
            <h1>Usage Analytics</h1>
            <p style="color:var(--text-muted);font-size:0.875rem;margin-top:0.25rem">Platform-wide API consumption</p>
        </div>
        <a href="/admin" style="color:var(--text-muted);font-size:0.875rem">← Admin</a>
    </div>

    <div class="admin-nav">
        <a href="/admin/usage/users">By User</a>
        <a href="/admin/usage/keys" class="active">By API Key</a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Key Name</th>
                    <th>Owner</th>
                    <th>Prefix</th>
                    <th class="text-right">Total Calls</th>
                    <th>Last Used</th>
                    <th>Status</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keys as $key)
                <tr>
                    <td style="font-weight:500">{{ $key->name }}</td>
                    <td>
                        <div style="font-size:0.85rem">{{ $key->user->name ?? '—' }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted)">{{ $key->user->email ?? '' }}</div>
                    </td>
                    <td><span class="prefix-mono">{{ $key->prefix }}…</span></td>
                    <td class="text-right" style="color:var(--gold);font-weight:600">{{ number_format($key->usage_logs_count) }}</td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">
                        {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never' }}
                    </td>
                    <td>
                        @if($key->status === 'active')
                            <span class="status-active">Active</span>
                        @else
                            <span class="status-revoked">Revoked</span>
                        @endif
                    </td>
                    <td>
                        @if(!$key->created_by || $key->created_by === $key->user_id)
                            <span class="badge-self">Self</span>
                        @else
                            <span class="badge-admin" title="{{ $key->createdBy->name ?? 'Admin' }}">Admin</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted)">No API keys found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($keys->hasPages())
        <div style="padding:1rem;text-align:center">{{ $keys->links() }}</div>
        @endif
    </div>
</main>
@endsection
```

**File 4 — Register admin routes in `routes/web.php`:**

Inside the existing `Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {` block, add:

```php
// Usage analytics
Route::get('/usage/users', [\App\Http\Controllers\Admin\UsageController::class, 'users'])->name('admin.usage.users');
Route::get('/usage/users/{userId}', [\App\Http\Controllers\Admin\UsageController::class, 'userDetail'])->name('admin.usage.user-detail');
Route::get('/usage/keys', [\App\Http\Controllers\Admin\UsageController::class, 'keys'])->name('admin.usage.keys');
```

Also add the nav link in `resources/views/layouts/app.blade.php` inside the `@if(auth()->user()->isAdmin())` block:
```html
<a href="/admin/usage/users" class="nav-link-gold">Usage</a>
```
Add after the existing `<a href="/admin/models" ...>` line.
  </action>
  <verify>
GET /admin/usage/users (as admin) returns 200 with the table. GET /admin/usage/users/{validUserId} returns 200 with that user's call log. GET /admin/usage/keys returns 200 with key table showing prefix and created_by badge.
  </verify>
  <done>
Three admin usage views render. Clicking a user row navigates to their detail view. Keys table shows Self/Admin badge on created_by column.
  </done>
</task>

</tasks>

<verification>
- /admin/usage/users → 200, table with user rows, total_calls column
- /admin/usage/users/{id} → 200, specific user's call log with filters
- /admin/usage/keys → 200, table with prefix column and created_by badge
- Rows with created_by null or = user_id show "Self"; rows with admin's id show "Admin"
</verification>

<success_criteria>
- Admin sees all users ranked by call volume
- Clicking a user opens their full call log (paginated, filterable)
- Key list shows masked prefix, call count, status and created-by attribution
</success_criteria>

<output>
After completion, create `.planning/phases/13-usage-visibility/13-03-SUMMARY.md`
</output>

---
---

---
phase: 13-usage-visibility
plan: 04
type: execute
wave: 3
depends_on: [13-01, 13-02, 13-03]
files_modified:
  - app/Http/Controllers/Admin/AdminApiKeysController.php
  - resources/views/admin/api-keys.blade.php
  - resources/views/admin/api-settings.blade.php
  - resources/views/api-keys.blade.php
  - routes/web.php
  - resources/views/layouts/app.blade.php
autonomous: false
requirements: [USAGE-07, USAGE-08]

must_haves:
  truths:
    - "Admin visits /admin/api-keys and sees ALL system keys with revoke/reactivate toggle and create-key-for-user form"
    - "Admin can revoke a key — it flips status to 'revoked' and is immediately rejected by ApiKeyAuth"
    - "Admin can create a key for any user — modal with user search, sets created_by = admin ID"
    - "Nav label 'API Settings' changed to 'Integration Settings' (admin nav only)"
    - "User's /api-keys page shows 'You' badge on self-created keys and 'Admin' badge (read-only) on admin-created keys"
  artifacts:
    - path: "app/Http/Controllers/Admin/AdminApiKeysController.php"
      provides: "index(), revoke(), reactivate(), store() for admin key management"
      exports: ["index", "revoke", "reactivate", "store"]
    - path: "resources/views/admin/api-keys.blade.php"
      provides: "System-wide key management view"
      contains: "revoke"
    - path: "resources/views/api-keys.blade.php"
      provides: "User key list with created_by badge"
      contains: "badge-admin"
  key_links:
    - from: "resources/views/admin/api-keys.blade.php"
      to: "AdminApiKeysController@revoke"
      via: "POST /admin/api-keys/{id}/revoke"
      pattern: "admin/api-keys.*revoke"
    - from: "resources/views/api-keys.blade.php"
      to: "api_keys.created_by"
      via: "badge display + delete button disabled"
      pattern: "created_by"
---

<objective>
Wave 3 — Admin Key Management + User Key Badges.

Depends on Wave 1 (created_by column exists) and Waves 2a/2b (views established). This plan adds:
1. A full admin system-key-management page at /admin/api-keys — revoke/reactivate any key, create keys for users.
2. Rename "API Settings" nav link to "Integration Settings" to clarify that page manages system credentials, not user keys.
3. User-facing /api-keys page gets "You" / "Admin" badges per key. Admin-created keys show as read-only (delete button hidden).

Purpose: Admins need security controls over all API keys. Users need to understand who created their keys and which ones they can manage.
Output: New controller, new admin view, updated user view, renamed nav link.
</objective>

<execution_context>
@C:/Users/User/.claude/get-shit-done/workflows/execute-plan.md
@C:/Users/User/.claude/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/STATE.md
@app/Models/ApiKeys.php
@app/Http/Controllers/ApiKeysController.php
@resources/views/api-keys.blade.php
@resources/views/admin/dashboard.blade.php
@resources/views/layouts/app.blade.php
@routes/web.php
</context>

<interfaces>
<!-- ApiKeys model after Plan 01 changes -->

From app/Models/ApiKeys.php (after Plan 01):
```php
protected $fillable = [
    'user_id', 'name', 'key', 'prefix', 'status', 'created_by', 'permissions', 'last_used_at'
];

public function user()       { return $this->belongsTo(User::class); }
public function createdBy()  { return $this->belongsTo(User::class, 'created_by'); }
public function usageLogs()  { return $this->hasMany(\App\Models\UsageLog::class, 'api_key_id'); }
public function scopeActive($query) { return $query->where('status', 'active'); }
```
</interfaces>

<tasks>

<task type="auto">
  <name>Task 1: Create AdminApiKeysController + new admin route + nav rename</name>
  <files>
    app/Http/Controllers/Admin/AdminApiKeysController.php
    routes/web.php
    resources/views/layouts/app.blade.php
  </files>
  <action>
**File 1 — Create `app/Http/Controllers/Admin/AdminApiKeysController.php`:**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKeys;
use App\Models\User;
use Illuminate\Http\Request;

class AdminApiKeysController extends Controller
{
    /**
     * List all API keys system-wide.
     */
    public function index(Request $request)
    {
        $keys = ApiKeys::with(['user', 'createdBy'])
            ->withCount('usageLogs')
            ->orderByDesc('created_at')
            ->paginate(100);

        // All users for the "create key for user" modal
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.api-keys', compact('keys', 'users'));
    }

    /**
     * Revoke an API key.
     */
    public function revoke(Request $request, $id)
    {
        $key = ApiKeys::findOrFail($id);
        $key->update(['status' => 'revoked']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'revoked']);
        }
        return back()->with('success', "Key '{$key->name}' has been revoked.");
    }

    /**
     * Reactivate a revoked API key.
     */
    public function reactivate(Request $request, $id)
    {
        $key = ApiKeys::findOrFail($id);
        $key->update(['status' => 'active']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'active']);
        }
        return back()->with('success', "Key '{$key->name}' has been reactivated.");
    }

    /**
     * Rename an API key (admin only).
     */
    public function rename(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:50']);
        $key = ApiKeys::findOrFail($id);
        $key->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }
}
```

**File 2 — Add routes in `routes/web.php`:**

Inside the `Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {` block, add:

```php
// Admin API key management (system-wide)
Route::get('/api-keys', [\App\Http\Controllers\Admin\AdminApiKeysController::class, 'index'])->name('admin.api-keys');
Route::post('/api-keys/{id}/revoke', [\App\Http\Controllers\Admin\AdminApiKeysController::class, 'revoke'])->name('admin.api-keys.revoke');
Route::post('/api-keys/{id}/reactivate', [\App\Http\Controllers\Admin\AdminApiKeysController::class, 'reactivate'])->name('admin.api-keys.reactivate');
Route::post('/api-keys/{id}/rename', [\App\Http\Controllers\Admin\AdminApiKeysController::class, 'rename'])->name('admin.api-keys.rename');
```

**File 3 — Rename nav in `resources/views/layouts/app.blade.php`:**

In the `@if(auth()->user()->isAdmin())` block, find the existing `admin/api-settings` link. It currently reads:

```html
{{-- (there is no direct api-settings link in the nav, it is accessed from admin dashboard) --}}
```

Add a new "API Keys" admin nav link after the existing admin links:
```html
<a href="/admin/api-keys" class="nav-link-gold">API Keys</a>
```

Also rename the route name reference: in `web.php`, the existing `admin.api-settings` route stays as-is but its nav label should say "Integration Settings". Find the existing `admin/api-settings` route comment or view title and update the page `<title>` in `resources/views/admin/api-settings.blade.php` if it exists. Open that file and change any heading that says "API Settings" to "Integration Settings".
  </action>
  <verify>
AdminApiKeysController file exists with index(), revoke(), reactivate(), rename() methods. Routes admin.api-keys, admin.api-keys.revoke, admin.api-keys.reactivate exist in web.php. Nav contains `/admin/api-keys` link.
  </verify>
  <done>Controller created; routes registered; nav updated</done>
</task>

<task type="auto">
  <name>Task 2: Create admin/api-keys.blade.php and update user api-keys view with badges</name>
  <files>
    resources/views/admin/api-keys.blade.php
    resources/views/api-keys.blade.php
  </files>
  <action>
**File 1 — Create `resources/views/admin/api-keys.blade.php`:**

Dark luxury design. Shows all system keys. Each row has: key name (editable inline), owner, prefix, call count, last used, status toggle button, created-by badge.
Includes a "Create Key for User" button that opens a modal with a user dropdown and key name field.

```blade
@extends('layouts.app')
@section('title', 'API Key Management — Admin')

@push('styles')
<style>
    .page-header { margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; }
    .page-header h1 { font-size: 1.5rem; font-weight: 700; }
    .btn-gold { background: linear-gradient(135deg, var(--gold), #e8c84a); color: #0a0d14; border: none; padding: 0.55rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; }
    .table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .data-table thead th { padding: 0.85rem 1.1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
    .data-table tbody td { padding: 0.75rem 1.1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .prefix-mono { font-family: monospace; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 5px; padding: 0.15rem 0.5rem; font-size: 0.8rem; color: var(--gold); }
    .badge-self { background: rgba(100,100,100,0.12); color: var(--text-secondary); border: 1px solid rgba(100,100,100,0.25); padding: 0.15rem 0.55rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
    .badge-admin { background: rgba(212,175,55,0.12); color: var(--gold); border: 1px solid rgba(212,175,55,0.3); padding: 0.15rem 0.55rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
    .status-active { color: #6ee7b7; font-size: 0.8rem; font-weight: 600; }
    .status-revoked { color: #f87171; font-size: 0.8rem; font-weight: 600; }
    .btn-revoke { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 6px; padding: 0.25rem 0.65rem; font-size: 0.775rem; font-weight: 500; cursor: pointer; }
    .btn-revoke:hover { background: rgba(239,68,68,0.2); }
    .btn-reactivate { background: rgba(5,150,105,0.1); border: 1px solid rgba(5,150,105,0.3); color: #6ee7b7; border-radius: 6px; padding: 0.25rem 0.65rem; font-size: 0.775rem; font-weight: 500; cursor: pointer; }
    .btn-reactivate:hover { background: rgba(5,150,105,0.2); }
    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; width: 100%; max-width: 480px; box-shadow: 0 24px 64px rgba(0,0,0,0.5); }
    .modal-box h3 { font-size: 1.1rem; font-weight: 600; margin: 0 0 1.25rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.375rem; }
    .form-input { width: 100%; padding: 0.6rem 0.85rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; font-family: inherit; box-sizing: border-box; }
    .form-input:focus { outline: none; border-color: var(--gold-muted); }
    .modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem; }
    .btn-cancel { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
    .key-reveal-box { background: var(--bg-secondary); border: 1px solid rgba(212,175,55,0.3); border-radius: 10px; padding: 1rem; margin: 1rem 0; display: none; }
    .key-reveal-box.show { display: block; }
    .key-reveal-value { font-family: monospace; font-size: 0.78rem; color: var(--gold); word-break: break-all; }
    .key-warning { font-size: 0.78rem; color: #f87171; margin-top: 0.5rem; }
    .text-right { text-align: right; }
</style>
@endpush

@section('content')
<main>
    <div class="page-header">
        <div>
            <h1>API Key Management</h1>
            <p style="color:var(--text-muted);font-size:0.875rem;margin-top:0.25rem">All user API keys — system-wide</p>
        </div>
        <button class="btn-gold" onclick="document.getElementById('create-key-modal').classList.add('active')">
            + Create Key for User
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Key Name</th>
                    <th>Owner</th>
                    <th>Prefix</th>
                    <th class="text-right">Calls</th>
                    <th>Last Used</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keys as $key)
                <tr>
                    <td style="font-weight:500">{{ $key->name }}</td>
                    <td>
                        <div style="font-size:0.85rem">{{ $key->user->name ?? '—' }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted)">{{ $key->user->email ?? '' }}</div>
                    </td>
                    <td><span class="prefix-mono">{{ $key->prefix }}…</span></td>
                    <td class="text-right" style="color:var(--gold);font-weight:600">{{ number_format($key->usage_logs_count) }}</td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">
                        {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'Never' }}
                    </td>
                    <td>
                        @if($key->status === 'active')
                            <span class="status-active">Active</span>
                        @else
                            <span class="status-revoked">Revoked</span>
                        @endif
                    </td>
                    <td>
                        @if(!$key->created_by || $key->created_by === $key->user_id)
                            <span class="badge-self">Self</span>
                        @else
                            <span class="badge-admin" title="{{ $key->createdBy->name ?? 'Admin' }}">Admin</span>
                        @endif
                    </td>
                    <td>
                        @if($key->status === 'active')
                        <form method="POST" action="/admin/api-keys/{{ $key->id }}/revoke" style="display:inline">
                            @csrf
                            <button type="submit" class="btn-revoke" onclick="return confirm('Revoke this key? It will stop working immediately.')">Revoke</button>
                        </form>
                        @else
                        <form method="POST" action="/admin/api-keys/{{ $key->id }}/reactivate" style="display:inline">
                            @csrf
                            <button type="submit" class="btn-reactivate">Reactivate</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted)">No API keys in the system</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($keys->hasPages())
        <div style="padding:1rem;text-align:center">{{ $keys->links() }}</div>
        @endif
    </div>
</main>

{{-- Create Key Modal --}}
<div id="create-key-modal" class="modal-overlay">
    <div class="modal-box">
        <h3>Create API Key for User</h3>
        <form id="create-key-form">
            @csrf
            <div class="form-group">
                <label class="form-label">User</label>
                <select name="user_id" id="ck-user-id" class="form-input" required>
                    <option value="">Select a user…</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Key Name</label>
                <input type="text" name="key_name" id="ck-key-name" class="form-input" placeholder="e.g. Admin Provisioned Key" maxlength="50" required>
            </div>
            <div id="ck-reveal" class="key-reveal-box">
                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem">New API Key — copy now, not shown again</div>
                <div id="ck-key-value" class="key-reveal-value"></div>
                <div class="key-warning">Store this key immediately. It cannot be recovered.</div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('create-key-modal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn-gold" id="ck-submit">Create Key</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('create-key-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const userId = document.getElementById('ck-user-id').value;
    const keyName = document.getElementById('ck-key-name').value;
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const btn = document.getElementById('ck-submit');
    btn.textContent = 'Creating…';
    btn.disabled = true;

    try {
        const res = await fetch('/admin/users/' + userId + '/keys', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ key_name: keyName })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('ck-key-value').textContent = data.message;
            document.getElementById('ck-reveal').classList.add('show');
            btn.textContent = 'Created';
            setTimeout(() => location.reload(), 4000);
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
            btn.textContent = 'Create Key';
            btn.disabled = false;
        }
    } catch (err) {
        alert('Request failed. Check console.');
        btn.textContent = 'Create Key';
        btn.disabled = false;
    }
});

// Close modal on overlay click
document.getElementById('create-key-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});
</script>
@endsection
```

**File 2 — Update `resources/views/api-keys.blade.php` to show created_by badges:**

The user API keys view currently shows a table with columns: Name, Prefix, Created, Last Used, Actions.

Locate the `@foreach($apiKeys as $apiKey)` loop in the existing view. Within each row:

After the key prefix cell, the existing view shows a delete button. Make these changes:

1. Add a "Created by" column in the `<thead>`:
```html
<th>Created By</th>
```
(Insert before the existing Actions `<th>`)

2. In `<tbody>` rows, add the badge cell before the delete button cell:
```blade
<td>
    @if(!$apiKey->created_by || $apiKey->created_by === $apiKey->user_id)
        <span class="badge badge-green" style="font-size:0.72rem">You</span>
    @else
        <span class="badge badge-gold" style="font-size:0.72rem" title="Created by admin">Admin</span>
    @endif
</td>
```

3. Wrap the delete button in a conditional so admin-created keys cannot be deleted by the user:
```blade
<td>
    @if(!$apiKey->created_by || $apiKey->created_by === auth()->id())
        <button class="btn-delete" data-id="{{ $apiKey->id }}">Delete</button>
    @else
        <span style="font-size:0.78rem;color:var(--text-muted);font-style:italic">Admin managed</span>
    @endif
</td>
```

The `ApiKeys` model already has `created_by` in fillable (from Plan 01 Task 2). The `$apiKeys` variable is already passed to the view from `ApiKeysController::index()` — no controller change needed. However, ensure the query eager-loads `created_by` data by verifying `ApiKeysController::index()` calls `$user->apiKeys()->orderBy('created_at', 'desc')->get()` — this already returns all columns including `created_by`.
  </action>
  <verify>
GET /admin/api-keys returns 200 with full key table. POST /admin/api-keys/{id}/revoke with CSRF flips status to revoked. GET /api-keys (user) shows "You" or "Admin" badge per key. Admin-created key rows show "Admin managed" instead of delete button.
  </verify>
  <done>
Admin can revoke/reactivate keys via the management page. User sees created_by badges. Admin-created keys are read-only for users.
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <what-built>
Complete Phase 13 feature set:
- Wave 1: Admin credit top-up fixed (increment not set), created_by migration run on dev, ApiKeyAuth checks status
- Wave 2a: /usage page for users with stat cards, paginated log table, CSV export
- Wave 2b: /admin/usage/users (by user + drill-down) and /admin/usage/keys (by key)
- Wave 3: /admin/api-keys full key management with revoke/reactivate, "Create Key for User" modal, user badge display
  </what-built>
  <how-to-verify>
Run on dev (llmdev.resayil.io):

1. **Credit top-up fix:**
   - Log in as admin, go to Admin Dashboard
   - Find a user with non-zero credits, note the balance
   - Add 100 credits — confirm the result is PREVIOUS + 100 (not just 100)

2. **User usage dashboard:**
   - Log in as any user who has made API calls
   - Visit /usage — confirm 4 stat cards render with real numbers
   - Verify table shows rows with model, token columns, credits, status
   - Click "Export CSV" — confirm a CSV downloads with correct headers
   - Apply a date filter — confirm results narrow

3. **Admin usage by user:**
   - Log in as admin, visit /admin/usage/users
   - Confirm table shows users with call counts
   - Click a user row — confirm /admin/usage/users/{id} loads their call log

4. **Admin usage by API key:**
   - Visit /admin/usage/keys
   - Confirm prefix column shows masked key prefix
   - Confirm created_by column shows "Self" or "Admin" badge

5. **Admin API key management:**
   - Visit /admin/api-keys
   - Click "Revoke" on an active key — confirm status flips to "Revoked"
   - Make an API call with that key — confirm 401 "API key has been revoked"
   - Click "Reactivate" — confirm key works again
   - Use "Create Key for User" modal — select a user, enter a name, submit
   - Confirm the new key appears in the table with "Admin" created_by badge
   - Log in as that user, visit /api-keys — confirm the key shows "Admin" badge and no delete button

6. **Nav labels:**
   - Confirm admin nav shows "API Keys" link pointing to /admin/api-keys
   - Visit /admin/api-settings — confirm the page title/heading says "Integration Settings"
  </how-to-verify>
  <resume-signal>Type "approved" if all checks pass. Describe any issues to fix.</resume-signal>
</task>

</tasks>

<verification>
Full end-to-end check (see checkpoint task above for detailed steps).

Automated checks to run before human verification:
```bash
ssh whm-server "cd ~/llmdev.resayil.io && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:list | grep -E '(usage|admin/api-keys)'"
```
Should show: GET /usage, GET /usage/export, GET /admin/usage/users, GET /admin/usage/users/{userId}, GET /admin/usage/keys, GET /admin/api-keys, POST /admin/api-keys/{id}/revoke, POST /admin/api-keys/{id}/reactivate.
</verification>

<success_criteria>
- Admin credit top-up adds to existing balance (verified manually on dev)
- /usage page renders for authenticated users with real data
- /admin/usage/users shows all users with call aggregates
- /admin/usage/keys shows all keys with created_by attribution
- /admin/api-keys lets admin revoke/reactivate any key
- Revoked key returns 401 from ApiKeyAuth
- User /api-keys shows "You"/"Admin" badges; admin-created keys have no delete button
- /admin/api-settings page title reads "Integration Settings"
- Human checkpoint: approved
</success_criteria>

<output>
After completion, create `.planning/phases/13-usage-visibility/13-04-SUMMARY.md`
</output>
