# Phase 13: Usage Visibility & API Key Management - Research

**Researched:** 2026-03-07
**Domain:** Laravel SaaS — usage analytics, CSV export, API key management, admin views
**Confidence:** HIGH

---

## Summary

Phase 13 adds usage visibility (call history, summary cards, CSV export) for users and drill-down usage analytics + full API key management for admins. A critical prerequisite bug fix is required: `AdminController::setUserCredits` uses assignment (`$user->credits = X`) instead of increment, which will silently replace credits on top-up rather than adding to them.

The existing schema is largely complete — `usage_logs` already has `prompt_tokens`, `completion_tokens`, `api_key_id`, `response_time_ms`, and `status_code` in migrations (original table + `2026_03_04_000001_add_token_split_to_usage_logs`). The `api_keys` table has `status` via `2026_03_02_000001_add_status_to_api_keys_table`. What is **missing from migrations** is a `created_by` column on `api_keys` (required for "Created by Admin" badge feature).

The real population problem is that `CreditService::deductCredits` (called by `ChatCompletionsController`) does NOT pass `api_key_id`, `response_time_ms`, or `status_code` to `UsageLog::create`. All three columns exist in the DB and in `UsageLog::$fillable`, but the call site omits them. The fix is in `CreditService::deductCredits` — it must accept and persist these values.

**Primary recommendation:** Fix the credit top-up bug and usage log population before building any UI. All schema columns exist; only the call sites need updating.

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| FIX-1 | Admin credit top-up replaces instead of adds — line 67 `$user->credits = X` must become `$user->increment('credits', X)` | Confirmed: `AdminController.php` line 67 uses assignment. `CreditService::addCredits` already uses `increment` — follow that pattern. |
| FIX-2 | usage_logs never populated — missing columns need migration | Confirmed: columns exist in schema (original migration + 2026_03_04 patch). Population gap is in `CreditService::deductCredits` which omits `api_key_id`, `response_time_ms`, `status_code`. No new migration needed for this fix. |
| FIX-3 | api_keys table missing `created_by` column in migration | Confirmed: `created_by` is NOT in any migration. The `status` column IS formally migrated (2026_03_02). Need one new migration: `add_created_by_to_api_keys_table`. |
| FEAT-1 | User dashboard: call history table + summary cards + CSV export | Dashboard.blade.php already has 4 stat cards (inline queries). Must extract to DashboardController, add paginated history table, and CSV export route. |
| FEAT-2 | Admin: usage by user (drill-down) + usage by API key | New admin views + new admin controller methods. Follow pattern in `admin/dashboard.blade.php`. |
| FEAT-3 | Admin: full API key management (list all, revoke/reactivate, create for user, show created_by) | Extends existing `AdminController`. API key creation already exists as JSON endpoint; needs a dedicated admin view. |
| FEAT-4 | User API keys page: show "Created by" badge (Self / Admin), admin-created keys are read-only | Requires `created_by` column (FIX-3) and view changes to `api-keys.blade.php`. |
| FEAT-5 | Rename admin "API Settings" nav to "Integration Settings" | Text change in `admin/api-settings.blade.php` sidebar nav and lang files. |
</phase_requirements>

---

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| Laravel 11 | ^11.0 | Framework | Already installed |
| ramsey/uuid | ^4.7 | UUID PKs | Already installed, all models use it |
| Carbon | (bundled) | Date math for "today", "30 days" | Already used in views |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| Laravel Response streaming | built-in | CSV export | No extra package needed — use `response()->streamDownload()` |
| Laravel `paginate()` | built-in | Paginated history table | Already used in admin/dashboard.blade.php line 98 |
| Chart.js | CDN | Usage-over-time bar/line chart | If chart added; already CDN pattern in project |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| `response()->streamDownload()` | `maatwebsite/excel` | Excel package adds a Composer dependency for a one-file CSV; not worth it |
| Inline `@php` queries in blade | Dedicated controller + view data | Controller approach is cleaner and testable — use it |

**No new Composer packages needed** for this phase.

---

## Architecture Patterns

### Existing Admin View Pattern
The admin views have TWO layout styles:
- `admin/dashboard.blade.php` and `admin/models.blade.php` — use `@extends('layouts.app')` with inline `<style>` blocks matching the dark luxury design system
- `admin/api-settings.blade.php` — uses a standalone Tailwind CDN layout with a sidebar

**Decision:** New admin pages (usage analytics, API key management) must use `@extends('layouts.app')` (the dark luxury pattern), NOT the Tailwind sidebar pattern. This keeps visual consistency with the main admin dashboard.

### User Dashboard Pattern
`dashboard.blade.php` already has:
- 4-column stat cards grid (`stats-grid`)
- Inline blade `@php` queries (anti-pattern — must move to controller for the new history table)
- Existing CSS vars: `--bg-card`, `--border`, `--gold`, `--text-muted`

The existing dashboard route in `web.php` is a closure (no controller). It must become a controller for the history + CSV.

### Recommended Route Structure (additions to web.php)

```php
// User routes (auth middleware)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/usage/export', [DashboardController::class, 'exportCsv'])->middleware('auth');

// Admin routes (admin middleware)
Route::get('/admin/usage', [AdminUsageController::class, 'index'])->name('admin.usage');
Route::get('/admin/usage/user/{user}', [AdminUsageController::class, 'userDrilldown'])->name('admin.usage.user');
Route::get('/admin/usage/key/{key}', [AdminUsageController::class, 'keyDrilldown'])->name('admin.usage.key');
Route::get('/admin/api-keys', [AdminApiKeysController::class, 'index'])->name('admin.api-keys');
Route::post('/admin/api-keys/{key}/revoke', [AdminApiKeysController::class, 'revoke'])->name('admin.api-keys.revoke');
Route::post('/admin/api-keys/{key}/reactivate', [AdminApiKeysController::class, 'reactivate'])->name('admin.api-keys.reactivate');
// createApiKeyForUser already exists at POST /admin/users/{user}/keys
```

### Recommended Project Structure (new files)
```
app/Http/Controllers/
├── DashboardController.php          # NEW — extracts dashboard logic, adds history + CSV
├── Admin/
│   ├── AdminController.php          # MODIFY — fix setUserCredits bug (line 67)
│   ├── AdminUsageController.php     # NEW — usage analytics views
│   └── AdminApiKeysController.php   # NEW — API key management views + revoke/reactivate

app/Services/
└── CreditService.php                # MODIFY — pass api_key_id, response_time_ms, status_code

database/migrations/
└── 2026_03_07_XXXXXX_add_created_by_to_api_keys_table.php  # NEW

resources/views/
├── dashboard.blade.php              # MODIFY — add history table section
├── api-keys.blade.php               # MODIFY — add "Created by" badge, read-only admin keys
└── admin/
    ├── usage.blade.php              # NEW — usage overview by user
    ├── usage-user.blade.php         # NEW — per-user drill-down
    ├── usage-key.blade.php          # NEW — per-key drill-down
    ├── api-keys.blade.php           # NEW — admin API key management
    └── api-settings.blade.php       # MODIFY — rename nav label

routes/web.php                       # MODIFY — add new routes, replace dashboard closure
lang/en/admin.php                    # MODIFY — add new translation keys
lang/ar/admin.php                    # MODIFY — add Arabic translations
```

### Anti-Patterns to Avoid
- **Inline `@php` DB queries in blade:** Move all queries to controller. The existing dashboard.blade.php does this (lines 138–146). New history table MUST NOT follow this pattern.
- **Raw `$user->credits = X`:** Always use `increment`/`decrement` for credit mutations to avoid race conditions.
- **Using Tailwind CDN layout for new admin pages:** The `api-settings.blade.php` Tailwind layout is isolated. Don't extend it. Use `@extends('layouts.app')`.
- **Hard-coding admin emails:** The codebase has `isAdmin()` — always call `$user->isAdmin()` never compare email strings.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| CSV export | Custom file builder | `response()->streamDownload(fn() => ..., 'usage.csv')` with `fputcsv` | Laravel built-in, no extra RAM for large exports |
| Pagination | Manual OFFSET/LIMIT | `->paginate(25)` + `{{ $logs->links() }}` | Already used in admin dashboard |
| Date range filtering | Raw SQL | `whereBetween('created_at', [$from, $to])` | Carbon + Eloquent scopes |
| UUID generation | Custom | Existing `booted()` pattern on all models | Already established pattern |

**Key insight:** This project has no heavy front-end build pipeline (no Vite/webpack assets). All JS is inline in blade files. CSV export must be pure PHP streaming.

---

## Common Pitfalls

### Pitfall 1: CreditService Does Not Write api_key_id
**What goes wrong:** `usage_logs.api_key_id` is always NULL even though the column exists, because `CreditService::deductCredits` doesn't receive or store it.
**Why it happens:** The ChatCompletionsController calls `deductCredits($user, $tokensUsed, $provider, $modelId, $promptTokens, $completionTokens)` — the API key is available in `$request` but is not extracted and passed.
**How to avoid:** The middleware `ApiKeyAuth` sets `$request->user()` AND should tag the resolved API key on the request (check if it does). If not, retrieve the key from `$request->bearerToken()` lookup before calling deductCredits.
**Warning signs:** All `usage_logs` rows have `api_key_id = NULL` after the fix.

### Pitfall 2: Credits Top-Up Replaces Instead of Adds
**What goes wrong:** Admin sets "add 500 credits" — user's balance becomes 500 instead of currentBalance + 500.
**Root cause:** `AdminController.php` line 67: `$user->credits = (int) $credits; $user->save();`
**Fix:** Replace with `$user->increment('credits', (int) $credits);` — also remove the `$user->save()` call.
**Warning signs:** User reports credits disappearing after admin top-up.

### Pitfall 3: Missing Migration for created_by
**What goes wrong:** The phase requirement states `created_by` "exists in prod DB manually" — this means it was added outside migrations. If the column doesn't exist on dev, the badge feature breaks. The migration will be a no-op on prod but is required for dev/staging consistency.
**How to avoid:** Create migration with `Schema::hasColumn()` check or use `->nullable()` and guard against column-not-exists errors.
**Safe migration pattern:**
```php
if (!Schema::hasColumn('api_keys', 'created_by')) {
    $table->uuid('created_by')->nullable()->after('status');
}
```

### Pitfall 4: Admin Dashboard Pagination Is Broken
**What goes wrong:** `admin/dashboard.blade.php` line 98 calls `->paginate(20)` inside `@foreach` directly — the paginator links will be missing because there is no `{{ $users->links() }}`.
**Scope:** This is a pre-existing bug. Phase 13 should not worsen it but doesn't need to fix it (out of scope).

### Pitfall 5: Two-Layout Problem in Admin
**What goes wrong:** `api-settings.blade.php` uses a completely different layout (Tailwind CDN, sidebar) vs `dashboard.blade.php` (extends layouts.app, dark luxury). Adding new admin pages to the wrong layout creates a fragmented UX.
**How to avoid:** All Phase 13 admin pages use `@extends('layouts.app')`.

### Pitfall 6: Admin "API Settings" Nav Rename
**What goes wrong:** The nav text "API Settings" appears in THREE places: the sidebar inside `api-settings.blade.php`, the translation keys (`admin.api_settings` in lang files), and potentially the route name `admin.api-settings`.
**Safe approach:** Rename only the display text via lang keys. Do NOT rename the route name or URL path — that would break existing bookmarks.

---

## Code Examples

### CSV Export (no external packages)
```php
// DashboardController.php
public function exportCsv(Request $request)
{
    $user = $request->user();

    return response()->streamDownload(function () use ($user) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Date', 'Model', 'Tokens Used', 'Prompt Tokens', 'Completion Tokens', 'Credits Deducted', 'Provider', 'Status']);

        UsageLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->model,
                        $log->tokens_used,
                        $log->prompt_tokens,
                        $log->completion_tokens,
                        $log->credits_deducted,
                        $log->provider,
                        $log->status_code,
                    ]);
                }
            });

        fclose($handle);
    }, 'usage-export-' . now()->format('Y-m-d') . '.csv', [
        'Content-Type' => 'text/csv',
    ]);
}
```

### Paginated Usage History Query
```php
// DashboardController.php
public function index(Request $request)
{
    $user = $request->user();

    $callsToday = UsageLog::where('user_id', $user->id)->whereDate('created_at', today())->count();
    $callsThisMonth = UsageLog::where('user_id', $user->id)->whereMonth('created_at', now()->month)->count();
    $creditsUsed30d = UsageLog::where('user_id', $user->id)->where('created_at', '>=', now()->subDays(30))->sum('credits_deducted');

    $usageHistory = UsageLog::where('user_id', $user->id)
        ->with('apiKey')
        ->orderByDesc('created_at')
        ->paginate(25);

    return view('dashboard', compact('callsToday', 'callsThisMonth', 'creditsUsed30d', 'usageHistory'));
}
```

### Fix: CreditService deductCredits Signature
```php
// Add api_key_id, response_time_ms, status_code to deductCredits
public function deductCredits(
    $user,
    int $tokensUsed,
    string $provider,
    string $model,
    ?int $promptTokens = null,
    ?int $completionTokens = null,
    ?string $apiKeyId = null,
    int $responseTimeMs = 0,
    int $statusCode = 200
): array {
    // ... existing logic ...
    UsageLog::create([
        'user_id'           => $user->id,
        'api_key_id'        => $apiKeyId,
        'model'             => $model,
        'tokens_used'       => $tokensUsed,
        'credits_deducted'  => $creditsDeducted,
        'provider'          => $provider,
        'prompt_tokens'     => $promptTokens,
        'completion_tokens' => $completionTokens,
        'response_time_ms'  => $responseTimeMs,
        'status_code'       => $statusCode,
    ]);
}
```

### Fix: AdminController setUserCredits (line 67)
```php
// BEFORE (line 67):
$user->credits = (int) $credits;
$user->save();

// AFTER:
$user->increment('credits', (int) $credits);
// Remove the $user->save() line entirely
```

### Fix: Migration for created_by Column
```php
// database/migrations/YYYY_MM_DD_HHMMSS_add_created_by_to_api_keys_table.php
public function up(): void
{
    Schema::table('api_keys', function (Blueprint $table) {
        if (!Schema::hasColumn('api_keys', 'created_by')) {
            $table->uuid('created_by')->nullable()->after('status')
                  ->comment('NULL = created by user, non-null = admin user ID');
        }
    });
}
```

### "Created by" Badge in api-keys.blade.php
```blade
{{-- In the keys table row: --}}
<td>
    @if($key->created_by)
        <span class="badge" style="background:rgba(212,175,55,0.15);color:var(--gold);border:1px solid rgba(212,175,55,0.3);">Admin</span>
    @else
        <span class="badge badge-green">Self</span>
    @endif
</td>
{{-- Delete button: hide for admin-created keys --}}
@if(!$key->created_by)
    <button class="btn-delete" ...>Delete</button>
@else
    <span class="text-xs text-muted">Read-only</span>
@endif
```

### Admin Usage by User Query
```php
// AdminUsageController.php
public function index()
{
    $usageByUser = UsageLog::selectRaw('
            user_id,
            COUNT(*) as total_calls,
            SUM(tokens_used) as total_tokens,
            SUM(credits_deducted) as total_credits,
            MAX(created_at) as last_call_at
        ')
        ->with('user:id,name,email')
        ->groupBy('user_id')
        ->orderByDesc('total_calls')
        ->paginate(25);

    return view('admin.usage', compact('usageByUser'));
}
```

---

## Schema State — Complete Mapping

### usage_logs table (current state)
All columns present in migrations. Nothing missing.

| Column | Where Defined | Status |
|--------|--------------|--------|
| id (uuid) | 2024_02_26_100001 | Present |
| user_id (uuid) | 2024_02_26_100001 | Present |
| api_key_id (uuid, nullable) | 2024_02_26_100001 | Present |
| model | 2024_02_26_100001 | Present |
| tokens_used | 2024_02_26_100001 | Present |
| credits_deducted | 2024_02_26_100001 | Present |
| provider (enum local/cloud) | 2024_02_26_100001 | Present |
| response_time_ms | 2024_02_26_100001 | Present |
| status_code | 2024_02_26_100001 | Present |
| prompt_tokens (nullable) | 2026_03_04_000001 | Present |
| completion_tokens (nullable) | 2026_03_04_000001 | Present |
| timestamps | 2024_02_26_100001 | Present |

**Population problem:** `CreditService::deductCredits` only writes `user_id`, `model`, `tokens_used`, `credits_deducted`, `provider`, `prompt_tokens`, `completion_tokens`. Fields `api_key_id`, `response_time_ms`, `status_code` are always NULL/0.

### api_keys table (current state)

| Column | Where Defined | Status |
|--------|--------------|--------|
| id (uuid) | 2024_02_26_000002 | Present |
| user_id (uuid) | 2024_02_26_000002 | Present |
| name | 2024_02_26_000002 | Present |
| key | 2024_02_26_000002 | Present |
| prefix | 2024_02_26_000002 | Present |
| permissions (json) | 2024_02_26_000002 | Present |
| last_used_at | 2024_02_26_000002 | Present |
| timestamps | 2024_02_26_000002 | Present |
| status | 2026_03_02_000001 | Present |
| unique index on key | 2026_03_05_000001 | Present |
| **created_by** | **MISSING** | **NEW MIGRATION NEEDED** |

---

## Files to Create / Modify

### New Files
| File | Type | Purpose |
|------|------|---------|
| `app/Http/Controllers/DashboardController.php` | Controller | Dashboard logic, history, CSV export |
| `app/Http/Controllers/Admin/AdminUsageController.php` | Controller | Usage analytics views |
| `app/Http/Controllers/Admin/AdminApiKeysController.php` | Controller | Admin API key management |
| `resources/views/admin/usage.blade.php` | View | Usage by user overview |
| `resources/views/admin/usage-user.blade.php` | View | Per-user drill-down |
| `resources/views/admin/api-keys.blade.php` | View | Admin API key management |
| `database/migrations/XXXX_add_created_by_to_api_keys_table.php` | Migration | Add created_by column |

### Modified Files
| File | Change |
|------|--------|
| `app/Http/Controllers/Admin/AdminController.php` | Fix line 67: assignment → increment; also set `created_by` on `createApiKeyForUser` |
| `app/Services/CreditService.php` | `deductCredits` — add `api_key_id`, `response_time_ms`, `status_code` params |
| `app/Http/Controllers/Api/ChatCompletionsController.php` | Pass api_key_id to `deductCredits` (extract from request bearer token lookup) |
| `app/Http/Controllers/ApiKeysController.php` | Set `created_by = null` on user-created keys (explicit) |
| `app/Models/ApiKeys.php` | Add `created_by` to `$fillable` |
| `resources/views/dashboard.blade.php` | Add history table section (replace inline queries with controller data) |
| `resources/views/api-keys.blade.php` | Add "Created by" badge column, hide delete for admin-created keys |
| `resources/views/admin/api-settings.blade.php` | Rename "API Settings" → "Integration Settings" in sidebar |
| `resources/views/layouts/app.blade.php` | Add "Integration Settings" nav link for admin (if desired) |
| `routes/web.php` | Replace dashboard closure with controller; add new routes |
| `lang/en/admin.php` (and ar) | Add translation keys for new labels |

---

## Validation Architecture

Note: `workflow.nyquist_validation` is not set in config.json (key absent) — treated as enabled. However, this is a PHP/Laravel project with no configured test suite (PHPUnit is dev-only, no test files exist for these features). All validation is manual/smoke-test.

### Test Framework
| Property | Value |
|----------|-------|
| Framework | PHPUnit ^11.0 (installed, no feature tests written) |
| Config file | None detected for this project |
| Quick run command | N/A — no test suite |
| Full suite command | N/A — no test suite |

### Phase Requirements → Validation Map
| Req ID | Behavior | Test Type | Verification Method |
|--------|----------|-----------|---------------------|
| FIX-1 | Admin top-up adds credits | Manual | Set user to 100 credits, add 50 via admin panel, verify balance = 150 |
| FIX-2 | usage_logs populated after API call | Manual | Make API call, check `usage_logs` table has non-null `api_key_id`, `status_code`, `response_time_ms` |
| FIX-3 | created_by migration runs | Manual | `php artisan migrate` succeeds; `api_keys` table has `created_by` column |
| FEAT-1 | History table visible in dashboard | Smoke | Log in as user, load /dashboard, verify table with paginated rows |
| FEAT-1 | CSV export downloads file | Smoke | Click export, verify CSV downloads with correct columns |
| FEAT-2 | Admin usage by user loads | Smoke | Load /admin/usage as admin, verify table with user rows |
| FEAT-3 | Admin can revoke/reactivate key | Manual | Revoke a key, attempt API call with it — expect 401. Reactivate, retry — expect 200. |
| FEAT-4 | "Admin" badge on admin-created key | Visual | Admin creates key for user, user views /api-keys — "Admin" badge visible, delete button hidden |
| FEAT-5 | Nav renamed | Visual | Load /admin/api-settings — sidebar shows "Integration Settings" |

### Wave 0 Gaps
- No test infrastructure exists for new controllers
- Manual testing is the only available approach
- Recommend creating smoke-test checklist in verification phase

---

## State of the Art

| Old Approach | Current Approach | Notes |
|--------------|------------------|-------|
| Inline `@php` DB queries in blade | Controller-injected view data | New pages MUST use controller pattern |
| `$user->credits = X; $user->save()` | `$user->increment('credits', X)` | The bug to fix — increment is atomic |
| No api_key_id in usage logs | api_key_id tracked from request | After FIX-2 |

---

## Open Questions

1. **Where does ChatCompletionsController get the API key identity?**
   - What we know: `ApiKeyAuth` middleware sets `$request->user()` from the bearer token. The bearer token IS the raw key.
   - What's unclear: Does the middleware tag the resolved `ApiKeys` model instance on the request (e.g., `$request->apiKey`)? If not, a DB lookup is needed.
   - Recommendation: Check `app/Http/Middleware/ApiKeyAuth.php` before implementing FIX-2. If it doesn't attach the key model, add `$request->merge(['resolved_api_key_id' => $apiKey->id])` in the middleware.

2. **Does admin/dashboard use full user list or just paginated?**
   - What we know: Line 98 calls `->paginate(20)` inside `@foreach` — the paginator links HTML is not rendered anywhere in the view.
   - What's unclear: Whether this is intentional (no page links displayed) or a bug.
   - Recommendation: Out of scope for phase 13. Do not change it.

3. **Should usage-by-API-key be a separate admin page or a tab within usage-by-user?**
   - Recommendation: Separate route `/admin/usage/key/{key}` is cleanest. Can be linked from the usage-by-user drill-down.

---

## Sources

### Primary (HIGH confidence)
- Direct codebase inspection — all findings based on reading actual source files
- `database/migrations/` — confirmed column state from migration chain
- `app/Http/Controllers/Admin/AdminController.php` — confirmed bug at line 67
- `app/Services/CreditService.php` — confirmed population gap in `deductCredits`
- `app/Http/Controllers/Api/ChatCompletionsController.php` — confirmed call sites

### Secondary (MEDIUM confidence)
- Laravel 11 documentation (response()->streamDownload, paginate, chunk) — standard Laravel patterns, confirmed by composer.json showing Laravel 11

---

## Metadata

**Confidence breakdown:**
- Bug fixes: HIGH — confirmed by reading actual source lines
- Schema state: HIGH — traced full migration chain
- New controller/view patterns: HIGH — modeled on existing admin/dashboard.blade.php
- CSV export approach: HIGH — standard Laravel, no external packages
- api_key_id flow via middleware: MEDIUM — ApiKeyAuth middleware not read (see Open Questions)

**Research date:** 2026-03-07
**Valid until:** 2026-04-07 (stable codebase, no fast-moving dependencies)
