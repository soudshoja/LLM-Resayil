---
phase: 01-foundation-auth
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: []
autonomous: true
requirements:
  - AUTH-01
  - AUTH-02
  - AUTH-03
  - KEY-01
  - KEY-02
  - KEY-03
  - KEY-04
user_setup: []
must_haves:
  truths:
    - "User can register with email, phone, and password via web form"
    - "User can log in and session persists across browser restarts"
    - "User can log out from any page and session is terminated"
    - "User can generate API keys with custom labels"
    - "User can view API key prefix (first 12 characters)"
    - "User can delete their own API keys"
    - "Full API key shown exactly once on creation"
  artifacts:
    - path: "app/Models/User.php"
      provides: "User model with relationships"
      exports: ["apiKeys", "subscriptions"]
    - path: "app/Models/ApiKeys.php"
      provides: "API keys model with user relationship"
      exports: ["user"]
    - path: "database/migrations/xxxx_create_users_table.php"
      provides: "Users table with email, phone, password fields"
      contains: "create_users_table"
    - path: "database/migrations/xxxx_create_api_keys_table.php"
      provides: "API keys table with user reference"
      contains: "create_api_keys_table"
    - path: "app/Http/Controllers/Auth/RegisteredUserController.php"
      provides: "User registration endpoint"
      exports: ["store"]
    - path: "app/Http/Controllers/Auth/AuthenticatedSessionController.php"
      provides: "Login/logout endpoints"
      exports: ["store", "destroy"]
    - path: "app/Http/Controllers/ApiKeysController.php"
      provides: "API key management (CRUD)"
      exports: ["index", "store", "destroy"]
    - path: "app/Http/Middleware/ApiKeyAuth.php"
      provides: "API key authentication middleware"
      exports: ["handle"]
  key_links:
    - from: "app/Http/Controllers/Auth/RegisteredUserController.php"
      to: "app/Models/User.php"
      via: "User::create()"
      pattern: "User::create\\(\\$request->validate"
    - from: "app/Http/Controllers/ApiKeysController.php"
      to: "app/Models/ApiKeys.php"
      via: "$request->user()->apiKeys()"
      pattern: "Auth::user\\(\\)->apiKeys\\(\\)"
    - from: "app/Http/Middleware/ApiKeyAuth.php"
      to: "app/Models/ApiKeys.php"
      via: "ApiKey::where('key', $key)->first()"
      pattern: "ApiKey::where\\('key', \\$key\\)"
---

# Phase 1 Plan 01: Foundation & Authentication

## Objective

Create the complete authentication and API key management system for the LLM Resayil Portal, including user registration, login/logout, API key generation, and session management.

Purpose: This is the foundational phase that enables all subsequent platform features. Without working authentication, users cannot access protected resources or use the API.

Output: Complete authentication scaffolding with Laravel Breeze-style conventions, custom API key management, and session persistence.

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `database/migrations/xxxx_create_users_table.php` | Users table schema |
| `database/migrations/xxxx_create_api_keys_table.php` | API keys table schema |
| `database/migrations/xxxx_create_subscriptions_table.php` | Subscriptions table schema |
| `app/Models/User.php` | User model with apiKeys relationship |
| `app/Models/ApiKeys.php` | API keys model with user relationship |
| `app/Models/Subscriptions.php` | Subscriptions model (for future use) |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | User registration endpoint |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Login/logout endpoints |
| `app/Http/Controllers/ApiKeysController.php` | API key CRUD operations |
| `app/Http/Middleware/ApiKeyAuth.php` | API key authentication middleware |
| `routes/web.php` | Web routes for auth and API keys |
| `routes/api.php` | API routes for authenticated endpoints |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create database migrations</name>
  <files>
    - database/migrations/xxxx_create_users_table.php
    - database/migrations/xxxx_create_api_keys_table.php
    - database/migrations/xxxx_create_subscriptions_table.php
  </files>
  <action>
Create three migration files for the authentication system:

1. **Users table** (xxxx_create_users_table.php):
   - id (UUID primary key)
   - email (unique, nullable)
   - phone (unique, nullable)
   - password (hashed)
   - name (nullable)
   - credits (default 0)
   - subscription_tier (enum: basic, pro, enterprise, default basic)
   - subscription_expiry (nullable timestamp)
   - email_verified_at (nullable timestamp)
   - remember_token (nullable)
   - timestamps

2. **API Keys table** (xxxx_create_api_keys_table.php):
   - id (UUID primary key)
   - user_id (foreign to users)
   - name (label for key, e.g., "Production API")
   - key (full 64-char hex key, encrypted)
   - prefix (first 12 chars, visible to user)
   - permissions (json: read, write, admin)
   - last_used_at (nullable timestamp)
   - created_at / updated_at

3. **Subscriptions table** (xxxx_create_subscriptions_table.php):
   - id (UUID primary key)
   - user_id (foreign to users)
   - tier (enum: basic, pro, enterprise)
   - status (enum: active, cancelled, expired)
   - MyFatoorah_invoice_id (nullable)
   - starts_at (timestamp)
   - ends_at (timestamp)
   - credits_purchased (integer, default 0)
   - credits_used (integer, default 0)
   - auto_renew (boolean, default false)
   - created_at / updated_at

Run migrations after creation.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan migrate:fresh --force 2>&1
  </verify>
  <done>
  - Three migration files created
  - Tables users, api_keys, subscriptions created in database
  - php artisan migrate:fresh --force returns success
  </done>
</task>

<task type="auto">
  <name>Task 2: Create Eloquent models with relationships</name>
  <files>
    - app/Models/User.php
    - app/Models/ApiKeys.php
    - app/Models/Subscriptions.php
  </files>
  <action>
Create three Eloquent models with proper relationships:

1. **User.php** - app/Models/User.php:
   - Fillable: email, phone, password, name, credits, subscription_tier, subscription_expiry
   - Casts: password to hashed, credits to integer
   - HasMany relationship: apiKeys()
   - HasMany relationship: subscriptions()

2. **ApiKeys.php** - app/Models/ApiKeys.php:
   - Fillable: user_id, name, key, prefix, permissions, last_used_at
   - Casts: permissions to array
   - BelongsTo relationship: user()
   - Scopes: scopeActive($query) - where status = 'active'

3. **Subscriptions.php** - app/Models/Subscriptions.php:
   - Fillable: user_id, tier, status, MyFatoorah_invoice_id, starts_at, ends_at, credits_purchased, credits_used, auto_renew
   - BelongsTo relationship: user()

All models use UUID primary keys (use Ramsey\Uuid\Uuid as Uuid).
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Models\User::class)));"
  </verify>
  <done>
  - User model with apiKeys() and subscriptions() methods
  - ApiKeys model with user() method and active scope
  - Subscriptions model with user() method
  - All models use UUID for primary keys
  </done>
</task>

<task type="auto">
  <name>Task 3: Create Auth controllers (registration, login, logout)</name>
  <files>
    - app/Http/Controllers/Auth/RegisteredUserController.php
    - app/Http/Controllers/Auth/AuthenticatedSessionController.php
  </files>
  <action>
Create two Auth controllers following Laravel conventions:

1. **RegisteredUserController.php**:
   - Method: store(Request $request)
   - Validates: email (required, unique:users,email), phone (required, unique:users,phone), password (required, min:8, confirmed)
   - Creates user with password hashed via bcrypt
   - Logs in user immediately after registration
   - Returns redirect to dashboard

2. **AuthenticatedSessionController.php**:
   - Method: store(Request $request) - Login
     - Validates: phone (required) or email (required), password (required)
     - Attempts authentication via Auth::attempt()
     - Returns JSON response with success/error
   - Method: destroy(Request $request) - Logout
     - Auth::logout()
     - Session->flush()
     - Returns JSON success response

Both controllers use JSON responses for API endpoints.
  </action>
  <verify>
  curl -X POST http://localhost:8000/register -H "Content-Type: application/json" -d '{"email":"test@example.com","phone":"96500000000","password":"password123","password_confirmation":"password123"}'
  </verify>
  <done>
  - User can register via POST /register
  - User can log in via POST /login
  - User can log out via DELETE /logout
  - Sessions persist across browser restarts (using remember_token)
  </done>
</task>

<task type="auto">
  <name>Task 4: Create API Keys controller</name>
  <files>
    - app/Http/Controllers/ApiKeysController.php
  </files>
  <action>
Create ApiKeysController with full CRUD operations:

1. **index()** - List user's API keys
   - Returns paginated list of user's apiKeys
   - Shows: name, prefix (first 12 chars), permissions, created_at, last_used_at

2. **store()** - Create new API key
   - Generates 64-character random hex key via bin2hex(random_bytes(32))
   - Computes prefix = substr($key, 0, 12)
   - Validates name (required, max:50)
   - Saves key (encrypted) and prefix to database
   - Returns key ONLY ONCE in response (full 64-char key)
   - Subsequent requests show only prefix

3. **destroy()** - Delete API key
   - Validates key_id
   - Verifies ownership via $request->user()->apiKeys()->where('id', $id)
   - Deletes the key

All endpoints require authentication via Bearer token or session.
  </action>
  <verify>
  curl -X POST http://localhost:8000/api-keys -H "Authorization: Bearer {token}" -d '{"name":"Production"}'
  curl -X GET http://localhost:8000/api-keys -H "Authorization: Bearer {token}"
  curl -X DELETE http://localhost:8000/api-keys/{id} -H "Authorization: Bearer {token}"
  </verify>
  <done>
  - User can list all their API keys
  - User can create new key with custom name
  - Full key shown exactly once on creation
  - User can delete keys
  </done>
</task>

<task type="auto">
  <name>Task 5: Create API Key authentication middleware</name>
  <files>
    - app/Http/Middleware/ApiKeyAuth.php
  </files>
  <action>
Create API Key authentication middleware:

1. **handle(Request $request, Closure $next)**:
   - Extracts Bearer token from Authorization header
   - Queries ApiKeys table for matching key
   - Checks key status is 'active'
   - Checks permissions include required scope (read/write/admin)
   - Attaches user to request via $request->user()
   - Updates last_used_at timestamp

2. **Register middleware** in app/Http/Kernel.php:
   - 'api.key.auth' => ApiKeyAuth::class

Return 401 with JSON error if authentication fails.
  </action>
  <verify>
  curl -X GET http://localhost:8000/api/protected -H "Authorization: Bearer {valid_key}"
  curl -X GET http://localhost:8000/api/protected -H "Authorization: Bearer {invalid_key}"
  </verify>
  <done>
  - Valid API key grants access
  - Invalid API key returns 401
  - last_used_at timestamp updates on each request
  </done>
</task>

<task type="auto">
  <name>Task 6: Configure routes for auth and API keys</name>
  <files>
    - routes/web.php
    - routes/api.php
  </files>
  <action>
Configure routes:

**routes/web.php**:
```php
// Auth routes
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth');

// Dashboard (protected)
Route::get('/dashboard', function () { return view('dashboard'); })->middleware('auth');

// API Keys routes (web interface)
Route::get('/api-keys', [ApiKeysController::class, 'index'])->middleware('auth');
Route::post('/api-keys', [ApiKeysController::class, 'store'])->middleware('auth');
Route::delete('/api-keys/{id}', [ApiKeysController::class, 'destroy'])->middleware('auth');
```

**routes/api.php**:
```php
// Protected API endpoints
Route::middleware(['auth:sanctum', 'api.key.auth'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('api-keys', ApiKeysController::class);
});
```
  </action>
  <verify>
  php artisan route:list --path=auth
  php artisan route:list --path=api-keys
  </verify>
  <done>
  - All auth routes registered (register, login, logout)
  - All API keys routes registered (index, store, destroy)
  - Protected routes require authentication
  </done>
</task>

</tasks>

---

## Verification

### Phase 1 Complete When:
- [ ] All migrations created and ran successfully
- [ ] User registration works via web form
- [ ] User login persists across browser sessions
- [ ] User logout terminates session completely
- [ ] API keys can be created with custom labels
- [ ] Full API key shown exactly once on creation
- [ ] API key prefix (first 12 chars) visible on list
- [ ] API keys can be deleted by user
- [ ] API Key middleware authenticates requests correctly

### Success Criteria from Phase 1:
1. ✓ User can register account with email, phone, and password via web form
2. ✓ User can log in and remains authenticated across browser sessions
3. ✓ User can log out from any page and their session is terminated
4. ✓ User can generate API keys with custom labels
5. ✓ User can view API key prefix (first 12 chars)
6. ✓ User can delete their own API keys
7. ✓ Full API key shown exactly once on creation

---

## Wave Structure

| Wave | Plan | Tasks | Notes |
|------|------|-------|-------|
| 1 | 01-01 | 6 | Foundation - migrations, models, controllers, middleware |

---

## Output

After completion, create `.planning/phases/01-foundation-auth/01-foundation-auth-01-SUMMARY.md` documenting:

```markdown
# Phase 1 Plan 01 Summary

## What Was Built
- Database schema (users, api_keys, subscriptions tables)
- Eloquent models with relationships
- Auth controllers (register, login, logout)
- API Keys controller (CRUD)
- API Key authentication middleware
- Routes configured

## Files Created
- database/migrations/xxxx_create_users_table.php
- database/migrations/xxxx_create_api_keys_table.php
- database/migrations/xxxx_create_subscriptions_table.php
- app/Models/User.php
- app/Models/ApiKeys.php
- app/Models/Subscriptions.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Http/Controllers/Auth/AuthenticatedSessionController.php
- app/Http/Controllers/ApiKeysController.php
- app/Http/Middleware/ApiKeyAuth.php
- routes/web.php (updated)
- routes/api.php (updated)

## Next Steps
- Phase 1 Plan 02: Views and UI for authentication
- Phase 1 Plan 03: Testing and verification
```
