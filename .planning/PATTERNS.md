# LLM-Resayil Architectural Patterns & Solutions

**Analysis Date:** 2026-03-04

Extraction of reusable patterns, security mechanisms, and architectural decisions from a Laravel SaaS LLM API gateway serving the Gulf region. Focus on patterns applicable to TypeScript/Node projects.

---

## Reusable Patterns

### 1. Rate Limiting with Redis (Per-Minute Bucketing)

**File:** `app/Services/RateLimiter.php`

**Pattern: Time-Window Rate Limiter**

```php
protected function checkRateLimit(string $userId, string $tier): array {
    $limit = $this->getRateLimit($tier);
    $key = "rate_limit:{$userId}:" . now()->format('Y-m-d-H-i');

    $current = Redis::get($key);
    if ($current === false) {
        return ['allowed' => true, 'remaining' => $limit, 'limit' => $limit];
    }

    $current = (int) $current;
    if ($current >= $limit) {
        return ['allowed' => false, 'remaining' => 0, 'limit' => $limit];
    }

    return ['allowed' => true, 'remaining' => $limit - $current, 'limit' => $limit];
}
```

**Key Design Choices:**
- **Time-window granularity:** Per-minute buckets using `Y-m-d-H-i` format (not sliding window)
- **Fail-open gracefully:** If Redis fails, allows request (prioritizes availability over strict limiting)
- **Tier-based limits:** Different limits per subscription tier (basic=10, pro=30, enterprise=60 req/min)
- **TTL management:** Uses `setex()` with remaining seconds in minute to auto-expire old buckets
- **Status headers:** Returns `X-RateLimit-Limit`, `X-RateLimit-Remaining`, `X-RateLimit-Reset`

**Why it works:**
- Simple to understand and operate
- No distributed state needed (each minute is independent)
- Prevents the need for message queues or complex algorithms
- Graceful degradation when Redis is unavailable

**Node.js TypeScript Adaptation:**
```typescript
// Use Redis with key format: `rate_limit:${userId}:${now.toISOString().slice(0,16).replace(/T/, '-').replace(/:/g, '-')}`
// Or simpler: `rate_limit:${userId}:${Date.now() / 60000 | 0}` (integer division for 1-min buckets)
```

---

### 2. Credit System with Model-Based Cost Multipliers

**Files:** `app/Services/CreditService.php`, `config/models.php`

**Pattern: Tiered Credit Cost Model**

```php
// Service handles cost calculation
public function calculateCost(int $tokensUsed, string $provider, string $model = ''): int {
    $creditMultiplier = $this->resolveMultiplier($provider, $model);
    return (int) ceil($tokensUsed * $creditMultiplier / 1000);
}

// Falls back to provider-based defaults when model not in registry
protected function resolveMultiplier(string $provider, string $model): float {
    $registryMultiplier = config('models.models.' . $model . '.credit_multiplier');
    if ($registryMultiplier !== null) {
        return (float) $registryMultiplier;
    }
    return $this->fallbackMultipliers[$provider] ?? 1.0; // local=1.0, cloud=2.0
}
```

**Cost Structure:**
- **Per 1000 tokens pricing:** `ceil(tokens * multiplier / 1000)` (always rounds up)
- **Model-specific overrides:** Look up in config first, fallback to provider defaults
- **Cloud penalty:** Cloud models cost 2x vs. local (baked into multiplier)
- **Size-based multipliers:** Models have different rates based on parameter count
  - Small (3-14B): local=0.5, cloud=1.0
  - Medium (20-30B): local=1.5, cloud=2.5
  - Large (70B+): local=3.0, cloud=3.5

**Transactional Deduction:**
```php
public function deductCredits($user, int $tokensUsed, string $provider, string $model): array {
    DB::beginTransaction();
    try {
        $user->decrement('credits', $creditsDeducted);
        UsageLog::create([...]);
        DB::commit();
        return ['success' => true, ...];
    } catch (\Exception $e) {
        DB::rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
```

**Key Wins:**
- Atomicity with database transactions
- Immutable audit trail via `UsageLog` (every deduction logged)
- Registry-driven pricing (change models.php, no code changes)
- Graceful fallbacks for unknown models

---

### 3. Middleware Chain for API Authentication & Authorization

**Files:** `app/Http/Middleware/ApiKeyAuth.php`, `app/Http/Middleware/RateLimit.php`

**Pattern: Stateful Request Context**

```php
// ApiKeyAuth: Extract and validate bearer token
public function handle(Request $request, Closure $next, string $scope = null): Response {
    $key = explode(' ', $request->header('Authorization'))[1];
    $apiKey = ApiKeys::where('key', $key)->first();

    // Check permission scope
    if ($scope && !in_array($scope, $apiKey->permissions)) {
        return response()->json(['error' => "Permission '{$scope}' required"], 403);
    }

    // Update last_used_at for monitoring
    $apiKey->update(['last_used_at' => now()]);

    // Set user context for downstream middleware
    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    return $next($request);
}

// RateLimit: Uses user context from ApiKeyAuth
public function handle(Request $request, Closure $next): Response {
    $user = $request->user(); // Set by ApiKeyAuth
    $tier = $user->subscription_tier ?? 'basic';

    $rateLimit = $this->rateLimiter->checkRateLimit($user->id, $tier);

    if (!$rateLimit['allowed']) {
        return response()->json(['error' => 'Rate limit exceeded'], 429, [
            'X-RateLimit-Limit' => $rateLimit['limit'],
            'X-RateLimit-Remaining' => 0,
        ]);
    }

    $this->rateLimiter->incrementRateLimit($user->id, $tier);

    // Add rate limit headers to response
    $response = $next($request);
    $status = $this->rateLimiter->getRateLimitStatus($user->id, $tier);
    $response->headers->set('X-RateLimit-Limit', $status['limit']);
    $response->headers->set('X-RateLimit-Remaining', $status['remaining']);

    return $response;
}
```

**Middleware Stack Ordering (critical):**
1. `ApiKeyAuth` — extract user from bearer token
2. `RateLimit` — use user tier to enforce limits
3. Controller logic — user already authenticated

**Key Design Choices:**
- **Bearer token format:** `Authorization: Bearer {api_key_string}`
- **Permission scopes:** Optional third parameter for fine-grained access control
- **Activity tracking:** `last_used_at` updated on each use (enables monitoring & alerts)
- **Response headers:** Always include X-RateLimit-* for client visibility

---

### 4. OpenAI-Compatible Proxy with Dynamic Model Resolution

**File:** `app/Http/Controllers/Api/ChatCompletionsController.php`

**Pattern: Dynamic Model Registry with Ollama Fallback**

```php
// Controller resolves model name dynamically from Ollama, falls back to config
protected function resolveModelDynamically(string $clientModel): ?array {
    // Try to fetch from Ollama (live models)
    $models = $this->fetchModelsFromOllama();

    if ($models === null) {
        // Fallback to static config/models.php
        $models = $this->fallbackToConfig();
    }

    // Match by display_id first, then by ollama_name
    if (isset($models[$clientModel])) {
        return [
            'display_id' => $clientModel,
            'ollama_name' => $models[$clientModel]['ollama_name'],
            'type' => $models[$clientModel]['type'],
            'credit_multiplier' => $models[$clientModel]['credit_multiplier'],
        ];
    }

    return null;
}

// Fetch live models from Ollama and infer metadata
protected function fetchModelsFromOllama(): ?array {
    $response = Http::timeout(5)->get($ollamaUrl . '/api/tags');

    $models = [];
    foreach ($response->json()['models'] as $model) {
        $ollamaName = $model['name'];
        $displayId = $this->getDisplayId($ollamaName);

        $metadata = [
            'ollama_name' => $ollamaName,
            'type' => $this->inferType($ollamaName),
            'category' => $this->inferCategory($displayId),
            'family' => $this->inferFamily($displayId),
            'size' => $this->inferSize($displayId, $model['size']),
            'credit_multiplier' => str_ends_with($ollamaName, ':cloud') ? 2.0 : 1.0,
        ];

        // Merge config overrides
        $configOverride = config('models.models.' . $displayId);
        if ($configOverride) {
            $metadata = array_merge($metadata, array_filter([
                'description' => $configOverride['description'] ?? null,
                'context_window' => $configOverride['context_window'] ?? null,
                'credit_multiplier' => $configOverride['credit_multiplier'] ?? $metadata['credit_multiplier'],
            ]));
        }

        $models[$displayId] = $metadata;
    }

    return $models;
}
```

**Request Flow:**
1. Client sends: `POST /v1/chat/completions` with `model: "qwen2-7b"`
2. `ChatCompletionsController::store()` calls `resolveModelDynamically("qwen2-7b")`
3. Returns: `{display_id: "qwen2-7b", ollama_name: "qwen2:7b", type: "local", credit_multiplier: 0.5}`
4. Forwards to Ollama using `ollama_name`
5. Deducts credits using `credit_multiplier` from resolved metadata

**Key Design Wins:**
- **Live model discovery:** No manual registry to maintain, Ollama is source of truth
- **Display name abstraction:** Client uses human-friendly names, internal uses ollama names
- **Automatic categorization:** Infers model capabilities from name (vision, code, thinking, embedding)
- **Graceful degradation:** Falls back to static config if Ollama unavailable
- **Config overrides:** Critical metadata (context window, params) can be set in code, others inferred

---

### 5. Cloud Failover with Queue-Depth Triggering

**File:** `app/Services/CloudFailover.php`

**Pattern: Intelligent Model Routing with Load Feedback**

```php
public function shouldUseCloud($user): bool {
    if (!$this->isCloudAvailable()) {
        return false;
    }

    if (!$this->checkDailyLimit($user)['allowed']) {
        return false;
    }

    $queueDepth = $this->proxy->checkLocalQueue();

    // Only route to cloud if local queue is backed up
    return $queueDepth > $this->queueThreshold; // threshold = 3
}

public function checkDailyLimit($user): array {
    $today = now()->toDateString();

    $budget = CloudBudget::firstOrCreate(
        ['date' => $today],
        ['requests_today' => 0, 'daily_limit' => $this->dailyLimit] // dailyLimit = 500
    );

    // Auto-reset if past midnight
    if ($budget->last_reset_at && !$budget->last_reset_at->isToday()) {
        $budget->requests_today = 0;
        $budget->last_reset_at = now();
    }

    return [
        'allowed' => $budget->requests_today < $budget->daily_limit,
        'used' => $budget->requests_today,
        'limit' => $budget->daily_limit,
        'remaining' => $budget->daily_limit - $budget->requests_today,
    ];
}
```

**Controller Integration:**
```php
// Determine provider before proxy request
$provider = $isCloudModel ? 'cloud' : 'local';

// Check if we should failover to cloud (if local is local model)
if ($provider === 'local' && $this->cloudFailover->shouldUseCloud($user)) {
    $provider = 'cloud';
    $modelName = $this->cloudFailover->getCloudModelName($modelName); // append :cloud suffix
}

// Record cloud usage for quota tracking
if ($provider === 'cloud' && !$isAdmin) {
    if (!$this->cloudFailover->recordCloudRequest($user)) {
        // Fall back to local if cloud limit exceeded
        $provider = 'local';
    }
}

// Proxy the request
$response = $this->proxy->proxyChatCompletions($request, $provider, $modelName);

// Deduct credits using resolved provider
$cost = $this->creditService->calculateCost($tokensUsed, $provider, $modelId);
```

**Key Design Choices:**
- **Load-based, not quota-based:** Triggers on queue depth (3+ pending), not time-of-day
- **Daily soft limit:** Cloud allowance is per-calendar-day, auto-resets at midnight
- **Cost pass-through:** Cloud requests cost 2x credits (baked into multiplier)
- **Graceful degradation:** If cloud limit hit, falls back to local automatically
- **Model name mapping:** Cloud models use `:cloud` suffix for identification

---

## Security Wins

### 1. Bearer Token Authentication with Scope-Based Authorization

**Location:** `app/Http/Middleware/ApiKeyAuth.php`

**What it does:**
- Requires `Authorization: Bearer {token}` header on all API requests
- Validates token against `ApiKeys` table
- Supports permission scopes for fine-grained access control
- Logs last_used_at for audit trails

**Why it's good:**
- Standard HTTP authentication (easy for clients)
- Database-backed tokens (can revoke immediately)
- Scope system enables API key restrictions ("read-only", "billing-only", etc.)
- Activity tracking for anomaly detection

```php
// Usage in routes
Route::middleware('api.key.auth:read-models')->group(...)
```

---

### 2. Transaction-Based Credit Deduction

**Location:** `app/Services/CreditService.php::deductCredits()`

**What it does:**
- Wraps credit deduction in database transaction
- Creates immutable `UsageLog` entry for every deduction
- Rolls back entire transaction on any error
- Returns success/failure status to caller

**Why it's good:**
- Prevents phantom debits (failed transactions don't leave partial state)
- Immutable audit trail (every token cost is logged with timestamp)
- Recovery mechanism (admins can see what failed and why)

**Risk Mitigated:** "User loses credits due to API crash after token count but before deduction"

---

### 3. Admin Bypass with Email Check (Anti-Pattern Alert!)

**Location:** `app/Http/Controllers/Api/ChatCompletionsController.php::store()`

**Code:**
```php
$isAdmin = auth()->user()->email === 'admin@llm.resayil.io';

if (!$isAdmin) {
    $rateLimit = $this->rateLimiter->checkRateLimit($user->id, $tier);
    if (!$rateLimit['allowed']) {
        return response()->json([...], 429);
    }
}
```

**Security Issue:**
- Admin account can bypass rate limits, credit checks, model access controls
- **PROBLEM:** If admin account is compromised, attacker has unlimited API access
- **PROBLEM:** No audit trail for admin requests (might skip credit logging)

**Better Approach:**
- Use role/permission system instead of email string comparison
- Create explicit "admin" user role in database
- Still bypass limits, but log all admin actions
- Use middleware to check user role, not inline email checks

---

### 4. HMAC Signature Validation (Webhook Security)

**Location:** `app/Http/Controllers/Billing/WebhookController.php::validateSignature()`

**Code:**
```php
protected function validateSignature(Request $request): bool {
    $receivedHmac = $request->header('X-Myfatoorah-Hmac');

    if (empty($receivedHmac)) {
        // Allow webhook without HMAC for now (can be enabled later)
        return true;
    }

    $payload = $request->getContent();
    $expectedHmac = hash_hmac('sha256', $payload, env('MYFATOORAH_API_KEY'));

    return hash_equals($expectedHmac, $receivedHmac);
}
```

**Security Wins:**
- Verifies webhook actually came from MyFatoorah (not spoofed)
- Uses `hash_equals()` for constant-time comparison (prevents timing attacks)
- Graceful fallback (webhook still processes if HMAC missing)

**Note:** Currently allows webhooks without HMAC signature. Should enforce in production.

---

### 5. Phone Number Validation with Gulf Region Awareness

**Location:** `app/Services/WhatsAppNotificationService.php::validatePhone()`

**Code:**
```php
public function validatePhone(string $phone): ?string {
    $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);

    // Check for valid E.164 format: + followed by 1-15 digits
    if (!preg_match('/^\+[1-9]\d{1,14}$/', $cleaned)) {
        // Try to add + if missing (common in Gulf region)
        if (preg_match('/^[1-9]\d{8,14}$/', $cleaned)) {
            $commonPrefixes = ['965', '966', '971', '974', '968', '973'];
            foreach ($commonPrefixes as $prefix) {
                if (str_starts_with($cleaned, $prefix)) {
                    return '+' . $cleaned;
                }
            }
            // Default to Kuwait code if no match
            return '+965' . $cleaned;
        }
        return null;
    }

    return $cleaned;
}
```

**Why it's good:**
- Prevents messages sent to invalid numbers (carrier rejects, costs money)
- Recognizes Gulf region code prefixes (965=KW, 966=SA, 971=UAE, etc.)
- Normalizes to E.164 format before sending to WhatsApp API
- Graceful degradation: defaults to Kuwait if prefix unknown

---

## Integration Patterns

### 1. MyFatoorah Payment Gateway Integration

**Files:** `app/Services/MyFatoorahService.php`, `app/Http/Controllers/Billing/WebhookController.php`

**Pattern: Two-Step Payment Flow**

```php
// Step 1: Create invoice and get checkout URL
public function createInvoice(array $data): array {
    $amount = $data['amount'];
    $paymentMethodId = $this->getPaymentMethodId((float) $amount);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])->post($this->baseUrl . '/v2/ExecutePayment', [
        'PaymentMethodId' => $paymentMethodId,
        'InvoiceValue' => $amount,
        'DisplayCurrencyIso' => 'KWD',
        'CallBackUrl' => $data['callback_url'],
        'ErrorUrl' => $data['error_callback_url'],
        'CustomerReference' => $data['user_id'],
        'UserDefinedField' => json_encode([
            'user_id' => $data['user_id'],
            'type' => $data['type'], // 'subscription' or 'topup'
            'tier' => $data['tier'],
        ]),
        'InvoiceItems' => [[...]], // Itemized receipt
    ]);

    return [
        'invoice_id' => $result['Data']['InvoiceId'],
        'invoice_url' => $result['Data']['PaymentURL'],
        'status' => 'pending',
    ];
}

// Step 2: Handle webhook callback
public function handleWebhook(Request $request) {
    // Validate HMAC signature
    if (!$this->validateSignature($request)) {
        return response()->json(['status' => 'error'], 401);
    }

    $invoiceId = $request->get('InvoiceId');

    // Idempotency: check if already processed
    if ($this->isTransactionProcessed($invoiceId)) {
        return response()->json(['status' => 'success'], 200);
    }

    // Verify with MyFatoorah
    $paymentInfo = $this->myfatoorahService->verifyPayment($invoiceId);

    // Route based on payment type (from UserDefinedField)
    switch ($paymentStatus) {
        case 'Paid':
        case 'PaidSuccessfully':
            $this->handlePaidInvoice($invoiceId, $paymentInfo);
            break;
        case 'Cancelled':
        case 'Expired':
            $this->handleFailedInvoice($invoiceId);
            break;
    }
}
```

**Key Design Patterns:**
- **UserDefinedField:** Pass context through payment gateway (type, tier, user_id)
- **Idempotency check:** Webhook might be called multiple times; prevent double-crediting
- **Dual verification:** Verify with gateway (verifyPayment) before updating database
- **Itemized invoicing:** Include item details for user visibility (e.g., "Starter Plan (1000 credits)")

---

### 2. WhatsApp Notification System with Bilingual Templates

**Files:** `app/Services/WhatsAppNotificationService.php`, `app/Jobs/SendWhatsAppNotification.php`, `app/Models/NotificationTemplate.php`

**Pattern: Template-Driven, Queue-Based Notifications**

```php
// 1. Database-driven templates (arabic_content, english_content columns)
// NotificationTemplate model:
public function getTemplate(string $language): ?string {
    $content = match ($language) {
        'ar' => $this->arabic_content,
        'en' => $this->english_content,
        default => null,
    };

    return $content ?? ($language === 'en' ? $this->english_content : $this->arabic_content);
}

// 2. Queue-based job for async sending
class SendWhatsAppNotification implements ShouldQueue {
    public function __construct(
        string $notificationId,
        ?string $userId = null,
        ?string $phone = null,
        string $language = 'ar',
        string $templateCode = '',
        array $metadata = []
    ) {
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->phone = $phone;
        $this->language = $language;
        $this->templateCode = $templateCode;
        $this->metadata = $metadata;
    }

    public function middleware(): array {
        // Prevent concurrent sends of same notification
        return [new WithoutOverlapping($this->notificationId)];
    }

    public function handle(WhatsAppNotificationService $whatsappService): void {
        $notification = Notification::find($this->notificationId);
        $template = NotificationTemplate::where('code', $this->templateCode)
            ->where('is_active', true)
            ->first();

        // Get template content for language
        $content = $template->getTemplate($this->language);

        // Format message with metadata
        $message = $this->formatMessage($content, $this->metadata);
        // Replaces {key} placeholders in template content

        // Send via WhatsApp API
        $result = $whatsappService->send($phone, $message, $this->language);

        if ($result['status'] === 'success') {
            $notification->status = 'sent';
            $notification->sent_at = now();
            $notification->save();
        } else {
            $notification->status = 'failed';
            $notification->retry_count += 1;
            $notification->save();
        }
    }
}

// 3. Event-driven dispatching
// When credits are low:
Event::dispatch(new CreditsLow($user));

// Listener dispatches job:
class CreditWarningListener implements ShouldQueue {
    public function handle(User $user): void {
        SendWhatsAppNotification::dispatch(
            'credit_warn_' . $user->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'credit_warning',
            ['remaining' => 20] // 20% remaining
        );
    }
}
```

**Bilingual Content Example:**
```
NotificationTemplate {
    code: 'credit_warning',
    name: 'Low Credit Warning',
    arabic_content: 'تحذير: أرصدتك في الأسفل. متبقي: {remaining}%',
    english_content: 'Warning: Your credits are running low. Remaining: {remaining}%',
    is_active: true
}
```

**Key Design Wins:**
- **Database templates:** Admins can edit messages without code changes
- **Bilingual support:** Single send() call, language determined by user preference
- **Queue-based:** Async sending doesn't block API responses
- **Retry tracking:** Logs retry_count if send fails
- **Deduplication:** WithoutOverlapping middleware prevents duplicate sends
- **Metadata templating:** Messages parameterized with {key} placeholders

---

### 3. Event-Driven Notifications with Listener Architecture

**Files:** `app/Providers/EventServiceProvider.php`, `app/Events/`, `app/Listeners/`

**Pattern: Observable Domain Events**

```php
// EventServiceProvider registers all event→listener mappings
protected $listen = [
    UserRegistered::class => [WelcomeNotificationListener::class],
    SubscriptionPaid::class => [SubscriptionConfirmationListener::class],
    CreditsLow::class => [CreditWarningListener::class],
    CreditsExhausted::class => [CreditsExhaustedListener::class],
    SubscriptionExpiring::class => [RenewalReminderListener::class],
    CloudBudgetWarning::class => [CloudBudgetAlertListener::class],
    IPBanned::class => [AdminAlertListener::class],
];

// Domain event example:
class CreditsLow {
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }
}

// Business logic triggers event (e.g., in CreditService):
if ($this->areCreditsLow($user)) {
    Event::dispatch(new CreditsLow($user));
}

// Listener responds:
class CreditWarningListener implements ShouldQueue {
    public function handle(User $user): void {
        SendWhatsAppNotification::dispatch(
            'credit_warn_' . $user->id,
            $user->id,
            $user->phone,
            $user->language ?? 'ar',
            'credit_warning',
            ['remaining' => $this->getPercentRemaining($user)]
        );
    }
}
```

**Why it's good:**
- **Decoupling:** CreditService doesn't know about notifications
- **Extensibility:** Add new listeners without touching CreditService
- **Async by default:** Listeners implement ShouldQueue for background processing
- **Multiple handlers:** One event can trigger multiple listeners (notifications + admin alert + analytics)
- **Testability:** Easy to mock/spy on Event::dispatch()

---

## Rate Limiting Approach

### Time-Window Rate Limiter with Tier-Based Limits

**Core Concept:**
- Divide time into one-minute buckets
- Track request count in Redis per bucket
- Increment on each request, return remaining count

**Tier Configuration:**
```php
protected array $rateLimits = [
    'basic' => 10,    // 10 requests per minute
    'pro' => 30,      // 30 requests per minute
    'enterprise' => 60, // 60 requests per minute
];
```

**Implementation Flow:**

```
1. Request arrives → Middleware extracts user tier
2. Calculate Redis key: "rate_limit:{userId}:{now.YYYYMMDDHHmm}"
3. Check Redis:
   - If key exists and value >= limit → REJECT (429)
   - If key exists and value < limit → ALLOW, increment
   - If key doesn't exist → ALLOW, set with TTL=remaining_seconds_in_minute
4. Return response with X-RateLimit-* headers
```

**Middleware Response Headers:**
```
X-RateLimit-Limit: 30
X-RateLimit-Remaining: 12
X-RateLimit-Reset: 1678886460 (Unix timestamp of next minute)
```

**TTL Management:**
```php
$current = Redis::get($key);

if ($current === false) {
    // First request in this minute, set with TTL = seconds until next minute
    Redis::setex($key, 60 - now()->format('s'), 1);
} else {
    // Increment counter
    Redis::incr($key);

    // Extend TTL (ensures cleanup even if requests stop)
    Redis::expire($key, 60 - now()->format('s'));
}
```

**Failover Behavior:**
- If Redis is down, **allow the request** (fail-open)
- Logging records the error for monitoring
- Users experience no disruption, but limits aren't enforced

**Why This Works:**
- Simple bucket model (no complex sliding window math)
- Each minute is independent (no state carried between minutes)
- TTL-based cleanup (no stale keys accumulate)
- Tier-based flexibility (easy to adjust per subscription)

---

## Notification Architecture

### Multi-Channel Event-Driven System

**Components:**

1. **Domain Events** (`app/Events/`)
   - UserRegistered, SubscriptionPaid, CreditsLow, CreditsExhausted, SubscriptionExpiring, CloudBudgetWarning, IPBanned, NewEnterpriseCustomer

2. **Event Listeners** (`app/Listeners/`)
   - Implement ShouldQueue for async processing
   - Listen for specific events
   - Dispatch SendWhatsAppNotification jobs

3. **Queue Jobs** (`app/Jobs/SendWhatsAppNotification.php`)
   - Async queue worker processes notifications
   - Supports retry on failure
   - WithoutOverlapping middleware prevents duplicates

4. **Notification Templates** (`app/Models/NotificationTemplate.php`)
   - Database-driven (arabic_content, english_content)
   - Parameterized with {key} placeholders
   - Can be enabled/disabled without code changes

5. **WhatsApp Service** (`app/Services/WhatsAppNotificationService.php`)
   - Handles HTTP communication to WhatsApp API
   - Phone number validation with Gulf region awareness
   - Message formatting for WhatsApp constraints

**Trigger Points:**
```php
// In CreditService:
if ($this->areCreditsLow($user)) {
    Event::dispatch(new CreditsLow($user));
}

// Event → Listener → Job → WhatsApp API
// Timeline:
// 1. Event dispatched immediately (sync)
// 2. Listener executed (sync or queued)
// 3. SendWhatsAppNotification job queued
// 4. Queue worker picks up job asynchronously
// 5. WhatsApp notification sent
// 6. Notification record updated with status (sent/failed)
```

**Bilingual Resolution:**
```
User has language='ar' preference
→ SendWhatsAppNotification dispatched with language='ar'
→ NotificationTemplate.getTemplate('ar') returns arabic_content
→ Message formatted with metadata: "تحذير: أرصدتك في الأسفل"
→ Sent to WhatsApp API
```

**Failure Handling:**
```php
// In SendWhatsAppNotification::handle():
if ($result['status'] === 'success') {
    $notification->status = 'sent';
    $notification->sent_at = now();
} else {
    $notification->status = 'failed';
    $notification->retry_count += 1;

    // Laravel queue automatically retries based on config
    // Can implement exponential backoff via maxExceptions/maxAttempts
}
```

---

## What OpenClaw Could Borrow

### 1. Redis-Based Rate Limiting (Priority: High)

OpenClaw uses TypeScript/Node. Adapt the Laravel pattern:

```typescript
// rate-limiter.ts
import Redis from 'ioredis';

interface RateLimitConfig {
  basic: number;
  pro: number;
  enterprise: number;
}

class RateLimiter {
  constructor(private redis: Redis) {}

  async checkRateLimit(userId: string, tier: string): Promise<{
    allowed: boolean;
    remaining: number;
    limit: number;
  }> {
    const limit = this.limits[tier] || this.limits.basic;
    const now = new Date();
    const minute = now.toISOString().slice(0, 16).replace('T', '-'); // YYYY-MM-DDHH-mm
    const key = `rate_limit:${userId}:${minute}`;

    try {
      const current = await this.redis.get(key);

      if (current === null) {
        const ttl = 60 - now.getSeconds();
        await this.redis.setex(key, ttl, '1');
        return { allowed: true, remaining: limit - 1, limit };
      }

      const count = parseInt(current, 10);
      if (count >= limit) {
        return { allowed: false, remaining: 0, limit };
      }

      return {
        allowed: true,
        remaining: limit - count,
        limit,
      };
    } catch (error) {
      // Fail open: allow if Redis fails
      console.error('RateLimiter Redis error:', error);
      return { allowed: true, remaining: limit, limit };
    }
  }

  async incrementRateLimit(userId: string, tier: string): Promise<void> {
    const now = new Date();
    const minute = now.toISOString().slice(0, 16).replace('T', '-');
    const key = `rate_limit:${userId}:${minute}`;

    try {
      const ttl = 60 - now.getSeconds();
      await this.redis.incr(key);
      await this.redis.expire(key, ttl);
    } catch (error) {
      console.error('RateLimiter increment error:', error);
    }
  }

  private limits: RateLimitConfig = {
    basic: 10,
    pro: 30,
    enterprise: 60,
  };
}
```

**Middleware Integration:**
```typescript
// Express middleware
app.use(async (req, res, next) => {
  const user = req.user; // From prior auth middleware
  const tier = user.subscription_tier || 'basic';

  const limit = await rateLimiter.checkRateLimit(user.id, tier);

  if (!limit.allowed) {
    return res.status(429).json({ error: 'Rate limit exceeded' });
  }

  await rateLimiter.incrementRateLimit(user.id, tier);

  res.set('X-RateLimit-Limit', String(limit.limit));
  res.set('X-RateLimit-Remaining', String(limit.remaining));
  res.set('X-RateLimit-Reset', String(Math.floor(Date.now() / 1000) + 60));

  next();
});
```

---

### 2. Transaction-Based Credit Deduction (Priority: High)

Adapt to TypeScript with database transactions:

```typescript
// credit-service.ts
import { Database } from 'typeorm';

class CreditService {
  async deductCredits(
    user: User,
    tokensUsed: number,
    provider: 'local' | 'cloud',
    model: string
  ): Promise<{ success: boolean; deducted: number; remaining?: number }> {
    const multiplier = this.resolveMultiplier(provider, model);
    const creditsDeducted = Math.ceil(tokensUsed * multiplier / 1000);

    if (user.credits < creditsDeducted) {
      return {
        success: false,
        deducted: 0,
      };
    }

    // Use database transaction
    const queryRunner = this.db.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      // Deduct from user
      await queryRunner.manager.decrement(
        User,
        { id: user.id },
        'credits',
        creditsDeducted
      );

      // Log usage
      await queryRunner.manager.insert(UsageLog, {
        userId: user.id,
        model,
        tokensUsed,
        creditsDeducted,
        provider,
      });

      await queryRunner.commitTransaction();

      return {
        success: true,
        deducted: creditsDeducted,
        remaining: user.credits - creditsDeducted,
      };
    } catch (error) {
      await queryRunner.rollbackTransaction();
      return {
        success: false,
        deducted: 0,
      };
    } finally {
      await queryRunner.release();
    }
  }

  private resolveMultiplier(provider: string, model: string): number {
    // Check config override
    const modelConfig = config.models[model];
    if (modelConfig?.creditMultiplier !== undefined) {
      return modelConfig.creditMultiplier;
    }

    // Fallback to provider default
    return provider === 'cloud' ? 2.0 : 1.0;
  }
}
```

---

### 3. OpenAI-Compatible Proxy Pattern (Priority: High)

```typescript
// openai-proxy.ts
import OpenAI from 'openai';

class OpenAIProxy {
  async handleChatCompletions(
    req: Request,
    userId: string,
    tier: string
  ): Promise<Response> {
    // 1. Validate request
    const { model, messages, temperature, max_tokens } = req.body;

    // 2. Resolve model
    const modelResolution = await this.resolveModelDynamically(model);
    if (!modelResolution) {
      return res.status(404).json({ error: `Model '${model}' not found` });
    }

    // 3. Check credits
    const estimatedCost = this.creditService.calculateCost(100, 'local');
    if (!this.creditService.checkCredits(user, estimatedCost)) {
      return res.status(402).json({ error: 'Insufficient credits' });
    }

    // 4. Forward to upstream (Ollama or OpenAI)
    const provider = modelResolution.type === 'cloud' ? 'cloud' : 'local';
    const ollamaModel = modelResolution.ollamaName;

    const response = await openai.chat.completions.create({
      model: ollamaModel,
      messages,
      temperature,
      max_tokens,
    });

    // 5. Deduct credits
    const tokensUsed = response.usage.total_tokens;
    await this.creditService.deductCredits(
      user,
      tokensUsed,
      provider,
      model
    );

    // 6. Return response
    return res.json(response);
  }

  private async resolveModelDynamically(
    clientModel: string
  ): Promise<ModelResolution | null> {
    // Try to fetch from Ollama
    const models = await this.fetchFromOllama();

    if (!models) {
      // Fallback to config
      return this.fallbackToConfig(clientModel);
    }

    return models[clientModel] || null;
  }

  private async fetchFromOllama(): Promise<Record<string, any> | null> {
    try {
      const response = await fetch(`${env.OLLAMA_URL}/api/tags`);
      const data = await response.json();

      const models: Record<string, any> = {};
      for (const model of data.models) {
        const displayId = this.getDisplayId(model.name);
        models[displayId] = {
          ollamaName: model.name,
          type: this.inferType(model.name),
          size: this.inferSize(model.name, model.size),
          creditMultiplier: model.name.includes(':cloud') ? 2.0 : 1.0,
        };
      }

      return models;
    } catch (error) {
      return null;
    }
  }

  private getDisplayId(ollamaName: string): string {
    return ollamaName.replace(/:cloud|-cloud/g, '');
  }

  private inferType(ollamaName: string): 'local' | 'cloud' {
    return ollamaName.includes(':cloud') || ollamaName.includes('-cloud')
      ? 'cloud'
      : 'local';
  }

  private inferSize(
    displayId: string,
    bytes: number
  ): 'small' | 'medium' | 'large' {
    // Implement logic from Laravel controller
    // ...
    return 'medium'; // placeholder
  }
}
```

---

### 4. Event-Driven Notification System (Priority: Medium)

For OpenClaw, use EventEmitter instead of Laravel events:

```typescript
// notification-system.ts
import { EventEmitter } from 'events';
import Bull from 'bull';

class NotificationSystem {
  private events = new EventEmitter();
  private notificationQueue = new Bull('notifications');

  constructor() {
    // Register event handlers
    this.events.on('credits-low', (user) =>
      this.handleCreditsLow(user)
    );
    this.events.on('subscription-paid', (user) =>
      this.handleSubscriptionPaid(user)
    );

    // Queue processor
    this.notificationQueue.process(async (job) => {
      const { templateCode, userId, language, metadata } = job.data;

      const template = await NotificationTemplate.findOne({ code: templateCode });
      const content = template.getContent(language);
      const message = this.formatMessage(content, metadata);

      const result = await this.whatsappService.send(
        user.phone,
        message,
        language
      );

      if (!result.success) {
        throw new Error(result.error);
      }
    });
  }

  async handleCreditsLow(user: User) {
    const remaining = this.getPercentRemaining(user);

    await this.notificationQueue.add({
      templateCode: 'credit-warning',
      userId: user.id,
      language: user.language || 'ar',
      metadata: { remaining },
    });
  }

  // Emit event when credits are low
  emitCreditsLow(user: User) {
    this.events.emit('credits-low', user);
  }
}
```

**Usage in CreditService:**
```typescript
if (this.areCreditsLow(user)) {
  notificationSystem.emitCreditsLow(user);
}
```

---

### 5. MyFatoorah Payment Integration (Priority: Medium)

Adapt webhook handling pattern:

```typescript
// payment-controller.ts
class PaymentController {
  async handleWebhook(req: Request, res: Response) {
    // 1. Validate HMAC
    if (!this.validateSignature(req)) {
      return res.status(401).json({ error: 'Invalid signature' });
    }

    // 2. Check idempotency (prevent double-crediting)
    const invoiceId = req.body.InvoiceId;
    if (await this.isProcessed(invoiceId)) {
      return res.json({ status: 'success' });
    }

    // 3. Verify with payment provider
    const payment = await this.paymentService.verifyPayment(invoiceId);

    // 4. Route based on payment type
    const { user_id, type, tier } = JSON.parse(
      req.body.UserDefinedField
    );

    if (payment.status === 'Paid') {
      if (type === 'subscription') {
        await this.billingService.subscribeUser(user_id, tier, invoiceId);
      } else if (type === 'topup') {
        await this.billingService.topupCredits(user_id, payment.amount, invoiceId);
      }
    }

    return res.json({ status: 'success' });
  }

  private validateSignature(req: Request): boolean {
    const receivedHmac = req.headers['x-myfatoorah-hmac'];
    if (!receivedHmac) {
      return true; // Allow for now, enforce later
    }

    const payload = JSON.stringify(req.body);
    const expectedHmac = crypto
      .createHmac('sha256', env.MYFATOORAH_API_KEY)
      .update(payload)
      .digest('hex');

    return crypto.timingSafeEqual(
      Buffer.from(expectedHmac),
      Buffer.from(receivedHmac as string)
    );
  }

  private async isProcessed(invoiceId: string): Promise<boolean> {
    const sub = await Subscription.findOne({ invoiceId });
    if (sub) return true;

    const topup = await TopupPurchase.findOne({ invoiceId });
    return !!topup;
  }
}
```

---

## Summary: Top Takeaways for OpenClaw

| Pattern | Priority | Why | How |
|---------|----------|-----|-----|
| **Redis Rate Limiter** | High | Simple, scalable, per-minute buckets | Implement per the code above |
| **Transactional Credits** | High | Prevents phantom debits, audit trail | Wrap deduction + logging in DB transaction |
| **OpenAI Proxy Pattern** | High | Live model discovery, config overrides | Fetch from Ollama, fallback to config |
| **Event-Driven Notifications** | Medium | Decoupled, extensible, async | EventEmitter + Bull queue |
| **MyFatoorah Integration** | Medium | Two-step flow, idempotency, webhook validation | Copy webhook pattern, validate HMAC |
| **Admin Role System** | Medium | Better than email string checks | Use proper role/permission system, log actions |
| **Phone Validation** | Low | Regional awareness | Use E.164 + prefix detection |

---

*End of patterns analysis*
