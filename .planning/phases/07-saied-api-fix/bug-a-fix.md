# Bug A Fix Report: Public URL /v1/models Returns HTTP 404

## Investigation Date
2026-03-07

## Root Cause

**The Laravel RouteServiceProvider was prefixing all `api.php` routes with `/api`**, making the OpenAI-compatible endpoints accessible at `/api/v1/models` instead of `/v1/models`.

### Evidence

- `https://llm.resayil.io/v1/models` → 404 (no route found)
- `https://llm.resayil.io/api/v1/models` → 401 (route found, auth middleware active)

The routes in `routes/api.php` were correctly defined under `Route::prefix('v1')`, but the `RouteServiceProvider` loaded all `api.php` routes with `->prefix('api')`, producing `/api/v1/...` paths.

## Server Architecture Discovered

- **Server IP:** `152.53.86.223` (behind Cloudflare at `104.21.22.141`)
- **SSH:** Port 22 on origin IP (blocked via Cloudflare DNS, connect directly to 152.53.86.223)
- **App location:** `/home/resayili/llm.resayil.io/` (Laravel app root)
- **Web root:** `/home/resayili/llm.resayil.io/public/` (served by LiteSpeed for domain)
- **Note:** `~/public_html/` is a separate WordPress site (resayil.io main site)
- **PHP binary:** `/opt/cpanel/ea-php82/root/usr/bin/php`

## Files Affected

### `app/Providers/RouteServiceProvider.php`

**Before:**
```php
$this->routes(function () {
    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));  // ALL api.php routes got /api prefix

    Route::middleware('web')
        ->group(base_path('routes/web.php'));
});
```

**After:**
```php
$this->routes(function () {
    // OpenAI-compatible /v1/... routes — no /api prefix so external
    // clients can use base_url = "https://llm.resayil.io/v1"
    Route::middleware('api')
        ->group(base_path('routes/v1.php'));

    // Internal API routes — prefixed with /api
    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));

    Route::middleware('web')
        ->group(base_path('routes/web.php'));
});
```

### `routes/v1.php` (new file)

Created to hold the OpenAI-compatible routes without the `/api` prefix:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatCompletionsController;
use App\Http\Controllers\Api\ModelsController;

Route::prefix('v1')->middleware('api.key.auth')->group(function () {
    Route::post('/chat/completions', [ChatCompletionsController::class, 'store']);
    Route::get('/models', [ModelsController::class, 'index']);
});
```

## Fix Applied

1. Backed up `/home/resayili/llm.resayil.io/app/Providers/RouteServiceProvider.php` → `.php.bak`
2. Uploaded updated `RouteServiceProvider.php` (splits v1 routes from api routes)
3. Created `/home/resayili/llm.resayil.io/routes/v1.php` (OpenAI-compatible routes at `/v1/`)
4. Ran `php artisan route:clear` and `php artisan config:clear`

## Verification

### Route List Confirms Fix

```
php artisan route:list --path=v1

  POST       api/v1/chat/completions ..... Api\ChatCompletionsController@store
  GET|HEAD   api/v1/models ........................ Api\ModelsController@index
  POST       v1/chat/completions ......... Api\ChatCompletionsController@store
  GET|HEAD   v1/models ............................ Api\ModelsController@index
```

Both `/v1/models` AND `/api/v1/models` are now registered (backward compatible).

### External HTTP Test

| Endpoint | Before Fix | After Fix |
|---|---|---|
| `GET /v1/models` (no auth) | 404 Not Found | 401 Unauthorized |
| `GET /api/v1/models` (no auth) | 401 Unauthorized | 401 Unauthorized |

A **401** response confirms:
- Laravel found the route
- The `api.key.auth` middleware is active
- The route is functioning correctly — it just needs an API key

## Status

**Bug A: FIXED**

The `/v1/models` route is now accessible. The previous 404 is resolved. Unauthenticated requests correctly receive 401 with JSON: `{"message":"Unauthenticated.","error":"Authorization header required."}`.

## Side Discovery

During investigation, discovered **Bug B** evidence: `llm_models` table does not exist in the database.

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'resayili_llm_resayil.llm_models' doesn't exist
```

This will cause a 500 error when `/v1/models` is called with a valid API key. Bug B must be resolved separately (see plan).

## Files on Server

- Modified: `/home/resayili/llm.resayil.io/app/Providers/RouteServiceProvider.php`
- Created: `/home/resayili/llm.resayil.io/routes/v1.php`
- Backup: `/home/resayili/llm.resayil.io/app/Providers/RouteServiceProvider.php.bak`
