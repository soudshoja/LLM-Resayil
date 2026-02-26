---
phase: 03-api-access
plan: 01
type: execute
wave: 1
depends_on:
  - 01-foundation-auth-01
files_modified: []
autonomous: true
requirements:
  - API-01
  - API-02
  - API-03
  - API-04
  - API-05
  - RATE-01
  - RATE-02
  - RATE-03
  - QUEUE-01
  - QUEUE-02
  - CLOUD-01
  - CLOUD-02
  - MODEL-01
  - MODEL-02
  - MODEL-03
  - MODEL-04
user_setup:
  - service: Ollama GPU Server
    why: "Proxy server for LLM inference"
    env_vars:
      - name: OLLAMA_GPU_URL
        source: "GPU server IP: 208.110.93.90:11434"
  - service: Ollama Cloud Models
    why: "Cloud failover models"
    env_vars:
      - name: OLLAMA_CLOUD_URL
        source: "Cloud Ollama instance URL"
      - name: CLOUD_API_KEY
        source: "Cloud Ollama API key from cloud provider"
  - service: Redis
    why: "Rate limiting storage"
    env_vars:
      - name: REDIS_HOST
        source: "Redis server host"
      - name: REDIS_PORT
        source: "Redis server port (default 6379)"
      - name: REDIS_PASSWORD
        source: "Redis server password"
must_haves:
  truths:
    - "API endpoint /v1/chat/completions accepts Bearer token and proxies to Ollama"
    - "Credits are deducted per request (1x for local, 2x for cloud models)"
    - "Service returns 402 when credits exhausted with top-up link"
    - "Streaming responses work for real-time token generation"
    - "Rate limits enforced per tier (Basic:10/min, Pro:30/min, Enterprise:60/min)"
    - "Queue depth checked before each request, auto-failover to cloud when local queue > 3"
    - "Daily cloud budget tracked with 500 request cap"
    - "Model access control: Basic sees only small/medium models, Pro gets cloud models, Enterprise gets priority queue"
    - "Restricted models (glm-4.7-flash, bge-m3, nomic-embed-text) never exposed via API"
  artifacts:
    - path: "app/Models/OllamaProxy.php"
      provides: "Ollama proxy service with local/cloud routing"
      exports: ["proxyChatCompletions", "proxyModels", "getModelList"]
    - path: "app/Services/RateLimiter.php"
      provides: "Rate limiting middleware per tier"
      exports: ["checkRateLimit", "incrementRateLimit"]
    - path: "app/Services/CloudFailover.php"
      provides: "Cloud failover logic with queue depth checking"
      exports: ["shouldUseCloud", "getCloudModelName", "checkDailyLimit"]
    - path: "app/Services/ModelAccessControl.php"
      provides: "Model access control per subscription tier"
      exports: ["getAllowedModels", "isModelAllowed", "getTierPriority"]
    - path: "app/Services/CreditService.php"
      provides: "Credit deduction on response completion"
      exports: ["deductCredits", "checkCredits", "logUsage"]
    - path: "database/migrations/xxxx_create_usage_logs_table.php"
      provides: "Usage logs table for analytics"
      contains: "create_usage_logs_table"
    - path: "database/migrations/xxxx_create_cloud_budgets_table.php"
      provides: "Cloud budgets tracking table"
      contains: "create_cloud_budgets_table"
    - path: "app/Http/Controllers/Api/ChatCompletionsController.php"
      provides: "OpenAI-compatible chat completions endpoint"
      exports: ["store", "stream"]
    - path: "app/Http/Controllers/Api/ModelsController.php"
      provides: "List accessible models endpoint"
      exports: ["index"]
    - path: "app/Http/Middleware/RateLimit.php"
      provides: "Rate limiting middleware"
      exports: ["handle"]
  key_links:
    - from: "app/Http/Controllers/Api/ChatCompletionsController.php"
      to: "app/Models/OllamaProxy.php"
      via: "proxyChatCompletions()"
      pattern: "OllamaProxy::proxyChatCompletions"
    - from: "app/Services/RateLimiter.php"
      to: "Redis"
      via: "Rate limit storage"
      pattern: "Redis::(get|set|incr)"
    - from: "app/Http/Controllers/Api/ChatCompletionsController.php"
      to: "app/Services/CreditService.php"
      via: "deductCredits()"
      pattern: "CreditService::deductCredits"
    - from: "app/Services/CloudFailover.php"
      to: "app/Models/OllamaProxy.php"
      via: "shouldUseCloud() triggers proxy to cloud"
      pattern: "CloudFailover::shouldUseCloud"
    - from: "app/Http/Controllers/Api/ModelsController.php"
      to: "app/Services/ModelAccessControl.php"
      via: "getAllowedModels()"
      pattern: "ModelAccessControl::getAllowedModels"
---

# Phase 3 Plan 01: API Access Implementation

## Objective

Create the OpenAI-compatible API proxy service that handles chat completions requests with credit-based billing, rate limiting, model access control, and cloud failover capabilities.

Purpose: Phase 1 (auth) is complete. This phase enables users to make API calls to the platform which proxies to Ollama on the GPU server, with all the necessary business logic for billing, rate limiting, and model routing.

Output: Complete API implementation with Ollama proxy service, rate limiting middleware, cloud failover logic, model access control, and credit deduction system.

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `database/migrations/xxxx_create_usage_logs_table.php` | Usage logs table for analytics |
| `database/migrations/xxxx_create_cloud_budgets_table.php` | Cloud budgets tracking table |
| `app/Models/OllamaProxy.php` | Ollama proxy service with local/cloud routing |
| `app/Services/RateLimiter.php` | Rate limiting middleware per tier |
| `app/Services/CloudFailover.php` | Cloud failover logic with queue depth checking |
| `app/Services/ModelAccessControl.php` | Model access control per subscription tier |
| `app/Services/CreditService.php` | Credit deduction on response completion |
| `app/Http/Controllers/Api/ChatCompletionsController.php` | OpenAI-compatible chat completions endpoint |
| `app/Http/Controllers/Api/ModelsController.php` | List accessible models endpoint |
| `app/Http/Middleware/RateLimit.php` | Rate limiting middleware |
| `routes/api.php` | Add chat completions and models routes |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create database migrations for usage logs and cloud budgets</name>
  <files>
    - database/migrations/xxxx_create_usage_logs_table.php
    - database/migrations/xxxx_create_cloud_budgets_table.php
  </files>
  <action>
Create two migration files:

1. **Usage Logs table** (xxxx_create_usage_logs_table.php):
   - id (UUID primary key)
   - user_id (foreign to users)
   - api_key_id (nullable, foreign to api_keys)
   - model (string)
   - tokens_used (integer)
   - credits_deducted (integer)
   - provider (enum: local, cloud)
   - response_time_ms (integer)
   - status_code (integer)
   - created_at / updated_at
   - Indexes on user_id, created_at

2. **Cloud Budgets table** (xxxx_create_cloud_budgets_table.php):
   - id (UUID primary key)
   - date (date, indexed)
   - requests_today (integer, default 0)
   - daily_limit (integer, default 500)
   - last_reset_at (timestamp)
   - created_at / updated_at
   - Unique constraint on date

Run migrations after creation.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan migrate:fresh --force 2>&1
  </verify>
  <done>
  - Two migration files created
  - Tables usage_logs, cloud_budgets created in database
  - php artisan migrate:fresh --force returns success
  </done>
</task>

<task type="auto">
  <name>Task 2: Create OllamaProxy model with local/cloud routing</name>
  <files>
    - app/Models/OllamaProxy.php
  </files>
  <action>
Create OllamaProxy model that handles routing to local or cloud Ollama instances:

1. **Constructor**: Accepts optional model_name parameter, defaults to 'local'
2. **proxyChatCompletions(Request $request, string $provider, string $model)**:
   - Determines Ollama URL based on provider (local: 208.110.93.90:11434, cloud: env var)
   - Adds Authorization header if cloud provider
   - Forwards request to Ollama /api/chat endpoint
   - Returns streaming response or JSON based on input
   - Handles exceptions and returns appropriate error

3. **proxyModels()**: GET /api/models from Ollama, filters restricted models

4. **getModelList(array $allowedModels)**: Returns filtered model list based on allowed models

5. **checkLocalQueue()**: GET /api/ps to check local queue depth

Use GuzzleHttp\Client for HTTP requests.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Models\OllamaProxy::class)));"
  </verify>
  <done>
  - OllamaProxy model with proxyChatCompletions(), proxyModels(), getModelList(), checkLocalQueue()
  - Proper error handling for connection failures
  - Supports both local and cloud Ollama instances
  </done>
</task>

<task type="auto">
  <name>Task 3: Create rate limiter service per tier</name>
  <files>
    - app/Services/RateLimiter.php
  </files>
  <action>
Create RateLimiter service with Redis-based rate limiting:

1. **Rate Limits by Tier**:
   - Basic: 10 requests per minute
   - Pro: 30 requests per minute
   - Enterprise: 60 requests per minute

2. **checkRateLimit(string $userId, string $tier)**:
   - Uses Redis:INCR with EXPIRE for sliding window
   - Key format: rate_limit:{userId}:{minute}
   - Returns {allowed: bool, remaining: int, limit: int}

3. **incrementRateLimit(string $userId, string $tier)**:
   - Increments the rate limit counter
   - Sets expiry to end of current minute

4. **getRateLimitStatus(string $userId, string $tier)**:
   - Returns current rate limit status for debug/metrics

Use Redis facade for storage. Handle Redis connection failures gracefully.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\RateLimiter::class)));"
  </verify>
  <done>
  - RateLimiter with checkRateLimit(), incrementRateLimit(), getRateLimitStatus()
  - Proper Redis integration
  - Tier-based rate limits enforced
  </done>
</task>

<task type="auto">
  <name>Task 4: Create cloud failover service</name>
  <files>
    - app/Services/CloudFailover.php
  </files>
  <action>
Create CloudFailover service with queue depth checking and budget tracking:

1. **shouldUseCloud(User $user)**:
   - Gets current queue depth from local Ollama
   - Returns true if queue > 3
   - Checks daily cloud budget not exceeded

2. **getCloudModelName(string $model)**: Returns cloud equivalent model name

3. **checkDailyLimit(User $user)**:
   - Checks cloud_budgets table for today's usage
   - Returns {allowed: bool, used: int, limit: int}
   - Resets counter if daily reset needed

4. **recordCloudRequest(User $user)**:
   - Increments today's cloud budget counter
   - Returns success/failure

5. **getCloudModels()**: Returns list of cloud model names

Use OllamaProxy for queue depth checking.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\CloudFailover::class)));"
  </verify>
  <done>
  - CloudFailover with shouldUseCloud(), getCloudModelName(), checkDailyLimit()
  - Daily budget tracking in cloud_budgets table
  - Queue depth checking triggers cloud failover
  </done>
</task>

<task type="auto">
  <name>Task 5: Create model access control service</name>
  <files>
    - app/Services/ModelAccessControl.php
  </files>
  <action>
Create ModelAccessControl service for tier-based model access:

1. **Model Definitions**:
   - Basic: llama3.2:3b, smollm2:135m, qwen2.5-coder:14b, mistral-small3.2:24b
   - Pro: Basic + qwen3-30b-40k, Qwen3-VL-32B, qwen3.5:cloud, deepseek-v3.2:cloud, gpt-oss:20b
   - Enterprise: Pro + priority queue

2. **Restricted Models** (never exposed): glm-4.7-flash, bge-m3, nomic-embed-text

3. **getAllowedModels(string $tier)**: Returns array of allowed model names

4. **isModelAllowed(string $model, string $tier)**: Checks if model is accessible

5. **getTierPriority(string $tier)**: Returns priority level (basic=1, pro=2, enterprise=3)

6. **filterModels(array $models, string $tier)**: Filters model list by tier access

Note: tier values are 'basic', 'pro', 'enterprise' from User subscription_tier field.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\ModelAccessControl::class)));"
  </verify>
  <done>
  - ModelAccessControl with getAllowedModels(), isModelAllowed(), filterModels()
  - Proper tier-based access control
  - Restricted models excluded from all lists
  </done>
</task>

<task type="auto">
  <name>Task 6: Create credit service</name>
  <files>
    - app/Services/CreditService.php
  </files>
  <action>
Create CreditService for credit-based billing:

1. **checkCredits(User $user, int $estimatedCost)**:
   - Returns {hasEnough: bool, current: int, required: int}
   - Validates user has sufficient credits

2. **deductCredits(User $user, int $tokensUsed, string $provider, string $model)**:
   - Calculates cost: tokens * 1 (local) or tokens * 2 (cloud)
   - Deducts from user.credits
   - Creates usage log entry
   - Returns {success: bool, deducted: int}

3. **logUsage(User $user, string $apiKeyId, string $model, int $tokensUsed, int $credits, string $provider)**:
   - Creates record in usage_logs table
   - Returns log record

4. **handleCreditExhausted(User $user)**:
   - Returns 402 response structure with top-up link
   - Format: {error: 'Insufficient credits', top_up_url: '/dashboard/topup'}

Tier pricing: local models cost 1x, cloud models cost 2x.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Services\CreditService::class)));"
  </verify>
  <done>
  - CreditService with checkCredits(), deductCredits(), logUsage()
  - Proper 402 response for insufficient credits
  - Usage logging for analytics
  </done>
</task>

<task type="auto">
  <name>Task 7: Create chat completions controller</name>
  <files>
    - app/Http/Controllers/Api/ChatCompletionsController.php
  </files>
  <action>
Create ChatCompletionsController for /v1/chat/completions endpoint:

1. **store(Request $request)** - Non-streaming:
   - Validates: model, messages, stream (optional, default false)
   - Authenticates via ApiKeyAuth middleware
   - Checks rate limit
   - Checks credits
   - Determines provider (local/cloud based on queue depth)
   - Determines model (filtered by user tier)
   - Proxies to OllamaProxy
   - Returns JSON response

2. **stream(Request $request)** - Streaming:
   - Same validation as store()
   - Returns streaming response with SSE format
   - Event: message with JSON payload
   - Event: done when complete

3. **Validation Rules**:
   - model: required, must be in allowed models
   - messages: required, array of {role, content}
   - temperature: optional, 0-1
   - max_tokens: optional

4. **Error Responses**:
   - 401: Invalid API key
   - 402: Insufficient credits
   - 429: Rate limit exceeded
   - 500: Proxy error

Follow OpenAI API format for request/response.
  </action>
  <verify>
  curl -X POST http://localhost:8000/api/v1/chat/completions -H "Authorization: Bearer {key}" -d '{"model":"llama3.2:3b","messages":[{"role":"user","content":"Hello"}]}'
  </verify>
  <done>
  - POST /v1/chat/completions endpoint working
  - Bearer token authentication
  - Credit deduction
  - Ollama proxying
  - Proper error responses
  </done>
</task>

<task type="auto">
  <name>Task 8: Create models controller</name>
  <files>
    - app/Http/Controllers/Api/ModelsController.php
  </files>
  <action>
Create ModelsController for /v1/models endpoint:

1. **index(Request $request)**:
   - Authenticates via ApiKeyAuth middleware
   - Gets user's subscription tier
   - Gets allowed models for tier via ModelAccessControl
   - Fetches models from OllamaProxy
   - Filters to allowed models
   - Returns OpenAI-compatible format:
     ```json
     {
       "object": "list",
       "data": [
         {"id": "model-name", "object": "model", "created": 0}
       ]
     }
     ```

2. **Validation**: Returns 401 for invalid API keys

Format matches OpenAI API structure.
  </action>
  <verify>
  curl -X GET http://localhost:8000/api/v1/models -H "Authorization: Bearer {key}"
  </verify>
  <done>
  - GET /v1/models endpoint working
  - Returns only allowed models per tier
  - Restricted models excluded
  - OpenAI-compatible response format
  </done>
</task>

<task type="auto">
  <name>Task 9: Create rate limit middleware</name>
  <files>
    - app/Http/Middleware/RateLimit.php
  </files>
  <action>
Create RateLimit middleware:

1. **handle(Request $request, Closure $next)**:
   - Extracts user from request (via ApiKeyAuth)
   - Gets user's subscription tier
   - Checks rate limit via RateLimiter
   - Returns 429 if rate limited with retry-after header
   - Increments counter on success
   - Adds rate limit headers to response:
     - X-RateLimit-Limit
     - X-RateLimit-Remaining
     - X-RateLimit-Reset

2. **Register middleware** in app/Http/Kernel.php:
   - 'rate.limit' => RateLimit::class

Use Redis for rate limit storage.
  </action>
  <verify>
  Test: Send 15 requests quickly as Basic tier user - 11th should return 429
  </verify>
  <done>
  - Rate limiting middleware active
  - 429 returned when limit exceeded
  - Rate limit headers present in response
  - Per-tier limits enforced
  </done>
</task>

<task type="auto">
  <name>Task 10: Configure API routes</name>
  <files>
    - routes/api.php
  </files>
  <action>
Update routes/api.php with new API access routes:

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeysController;
use App\Http\Controllers\Api\ChatCompletionsController;
use App\Http\Controllers\Api\ModelsController;

// Protected API endpoints
Route::middleware(['auth:sanctum', 'api.key.auth'])->group(function () {
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });

    // API Keys routes
    Route::apiResource('api-keys', ApiKeysController::class);

    // Chat Completions - OpenAI-compatible
    Route::post('/chat/completions', [ChatCompletionsController::class, 'store']);
    Route::post('/chat/completions/stream', [ChatCompletionsController::class, 'stream']);

    // Models endpoint
    Route::get('/models', [ModelsController::class, 'index']);
});
```

Ensure middleware order: auth first, then api.key.auth.
  </action>
  <verify>
  php artisan route:list --path=chat
  php artisan route:list --path=models
  </verify>
  <done>
  - All API routes registered
  - Protected routes require authentication
  - Streaming endpoint available
  </done>
</task>

</tasks>

---

## Verification

### Phase 3 Complete When:
- [ ] All migrations created and ran successfully
- [ ] POST /v1/chat/completions accepts Bearer token and proxies to Ollama
- [ ] Credits deducted per request (1x local, 2x cloud)
- [ ] 402 response returned when credits exhausted
- [ ] Streaming responses work for token generation
- [ ] Rate limits enforced per tier (Basic:10, Pro:30, Enterprise:60/min)
- [ ] Auto-failover to cloud when local queue > 3
- [ ] Daily cloud budget tracked (500 request cap)
- [ ] Model access control per tier (Basic small/medium, Pro cloud, Enterprise priority)
- [ ] Restricted models never exposed via API

### Success Criteria from Phase 3:
1. ✓ API endpoint accepts Bearer token authentication and proxies to Ollama
2. ✓ Credits are deducted per request (1x for local, 2x for cloud models)
3. ✓ When credits exhausted, service returns 402 with top-up link
4. ✓ Streaming responses work for real-time token generation
5. ✓ Rate limits enforced per tier (Basic:10/min, Pro:30/min, Enterprise:60/min)
6. ✓ Queue depth checked before each request, auto-failover to cloud when local queue > 3
7. ✓ Daily cloud budget tracked with 500 request cap
8. ✓ Model access control: Basic sees only small/medium models, Pro gets cloud models, Enterprise gets priority queue
9. ✓ Restricted models (glm-4.7-flash, bge-m3, nomic-embed-text) never exposed via API

---

## Wave Structure

| Wave | Plan | Tasks | Notes |
|------|------|-------|-------|
| 1 | 03-api-access-01 | 10 | Foundation - migrations, services, controllers, middleware, routes |

---

## Output

After completion, create `.planning/phases/03-api-access/03-api-access-01-SUMMARY.md` documenting:

```markdown
# Phase 3 Plan 01 Summary

## What Was Built
- Database schema (usage_logs, cloud_budgets tables)
- OllamaProxy model with local/cloud routing
- RateLimiter service with Redis-based rate limiting
- CloudFailover service with queue depth checking
- ModelAccessControl service for tier-based access
- CreditService for credit-based billing
- ChatCompletionsController for OpenAI-compatible API
- ModelsController for model listing
- RateLimit middleware
- API routes configured

## Files Created
- database/migrations/xxxx_create_usage_logs_table.php
- database/migrations/xxxx_create_cloud_budgets_table.php
- app/Models/OllamaProxy.php
- app/Services/RateLimiter.php
- app/Services/CloudFailover.php
- app/Services/ModelAccessControl.php
- app/Services/CreditService.php
- app/Http/Controllers/Api/ChatCompletionsController.php
- app/Http/Controllers/Api/ModelsController.php
- app/Http/Middleware/RateLimit.php
- routes/api.php (updated)

## Next Steps
- Phase 3 Plan 02: Testing and verification
- Phase 4: Notifications (WhatsApp notification system)
```
