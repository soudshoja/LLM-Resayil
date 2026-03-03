# Error Codes

This guide documents all API error codes, when they occur, example responses, and how to resolve them.

## Error Response Format

All API errors follow a consistent JSON format:

```json
{
  "error": {
    "code": "error_code",
    "message": "Human-readable description of the error"
  }
}
```

The `code` field is a machine-readable identifier you can use for error handling. The `message` field is human-readable and may include actionable hints.

---

## 401 Unauthorized

**HTTP Status**: 401

**When it occurs**:
- No `Authorization` header is provided
- The API key is invalid or malformed
- The API key has been revoked
- The API key is expired
- The `Authorization` header format is incorrect (must be `Bearer YOUR_KEY`, not other formats)

**Example Response**:

```json
{
  "error": {
    "code": "unauthorized",
    "message": "Invalid or missing API key"
  }
}
```

**Example Requests That Fail**:

```bash
# Missing Authorization header
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Content-Type: application/json" \
  -d '{...}'
# Returns 401

# Invalid API key
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer invalid_key_12345" \
  -d '{...}'
# Returns 401

# Revoked key
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer sk_llmr_revoked_key" \
  -d '{...}'
# Returns 401
```

**How to Resolve**:

1. **Verify API key is present**: Ensure your code includes the `Authorization: Bearer YOUR_API_KEY` header on every request
2. **Check key format**: The header must be exactly `Authorization: Bearer ` followed by your key (with space between Bearer and key)
3. **Create a new key**: Go to https://llm.resayil.io/api-keys and create a new API key
4. **Verify key not revoked**: In the dashboard, check if the key shows as "Active" or "Revoked"
5. **Test with valid key**: Replace the invalid key and retry:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer sk_llmr_your_valid_key" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [{"role": "user", "content": "test"}]
  }'
```

---

## 402 Payment Required

**HTTP Status**: 402

**When it occurs**:
- Your account has insufficient credits to process the request
- Your subscription has expired or been cancelled
- Your payment method failed to process
- You've hit a usage quota

**Example Response**:

```json
{
  "error": {
    "code": "insufficient_credits",
    "message": "Insufficient credits. Please purchase a top-up at https://llm.resayil.io/billing/plans"
  }
}
```

**How to Resolve**:

1. **Check credit balance**: Visit https://llm.resayil.io/dashboard and check "Credits Remaining"
2. **Purchase a top-up**: Go to https://llm.resayil.io/billing/plans and select a top-up package:
   - 5,000 credits = 2 KWD
   - 15,000 credits = 5 KWD
   - 50,000 credits = 15 KWD
3. **Complete payment**: Choose KNET or credit card and finish checkout
4. **Retry request**: Credits are added within seconds; your next API call will succeed

**Preventing 402 Errors**:

- Monitor your dashboard weekly to avoid running out unexpectedly
- Set spending alerts if available
- Upgrade to a higher subscription plan if you consistently run out of monthly credits
- Estimate your monthly usage: # requests × avg tokens per request × credit cost

**Example Calculation**:

```
Starter plan: 15,000 credits/month
Avg request: 100 tokens (local model)
Cost per request: 100 credits
Requests per month: 15,000 ÷ 100 = 150 requests

If you need more, purchase top-ups or upgrade to Pro (50,000 credits/month)
```

---

## 429 Too Many Requests

**HTTP Status**: 429

**When it occurs**:
- You've exceeded your plan's rate limit (requests per minute)
- You're making requests faster than your tier allows
- Your API key has reached its per-key limit

**Example Response**:

```json
{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Rate limit exceeded. Current limit: 10 requests per minute. Retry after 60 seconds."
  }
}
```

**Rate Limits by Plan**:

| Plan | Limit |
|------|-------|
| Starter | 10 requests/minute |
| Basic | 10 requests/minute |
| Pro | 30 requests/minute |
| Enterprise | 60 requests/minute |

**How to Resolve**:

**Option 1: Slow down your requests** (immediate)

Use exponential backoff when retrying:

```python
import time

for attempt in range(5):
    response = make_request(api_key, payload)

    if response.status_code == 429:
        wait_time = 2 ** attempt  # 1s, 2s, 4s, 8s, 16s
        time.sleep(wait_time)
        continue

    return response
```

**Option 2: Distribute load across multiple API keys** (medium effort)

If you have multiple keys on your plan, use different keys for different requests:

```python
api_keys = ["key1", "key2", "key3"]

for i, payload in enumerate(payloads):
    api_key = api_keys[i % len(api_keys)]  # Round-robin
    make_request(api_key, payload)
```

**Option 3: Upgrade your plan** (permanent)

1. Go to https://llm.resayil.io/billing/subscription
2. Select a higher plan:
   - Pro: 30 requests/minute (3 API keys)
   - Enterprise: 60 requests/minute (unlimited keys)
3. Pay prorated difference; rate limits apply immediately

**Option 4: Queue your requests** (architectural change)

Use a background job queue to smooth out traffic:

```python
from queue import Queue
import threading

class RequestQueue:
    def __init__(self, rate_limit=10):
        self.queue = Queue()
        self.rate_limit = rate_limit
        self.request_times = []

    def enqueue(self, request):
        self.queue.put(request)

    def process(self):
        while True:
            request = self.queue.get()

            # Remove requests older than 1 minute
            now = time.time()
            self.request_times = [t for t in self.request_times if now - t < 60]

            # Wait if at limit
            while len(self.request_times) >= self.rate_limit:
                time.sleep(1)
                now = time.time()
                self.request_times = [t for t in self.request_times if now - t < 60]

            # Make request
            make_api_call(request)
            self.request_times.append(time.time())
```

**See Also**: [Rate Limits](rate-limits.md) for detailed guidance.

---

## 503 Service Unavailable

**HTTP Status**: 503

**When it occurs**:
- The requested model is temporarily offline or being updated
- The LLM Resayil platform is under maintenance
- A cloud model provider (ollama.com) is experiencing outage
- The GPU server is overwhelmed or offline
- The specific model you requested has been disabled

**Example Response**:

```json
{
  "error": {
    "code": "service_unavailable",
    "message": "The requested model 'model_id' is currently unavailable. Please try again later or use a different model."
  }
}
```

**Example Request**:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -d '{
    "model": "some-offline-model",
    "messages": [...]
  }'
# Returns 503
```

**How to Resolve**:

**Option 1: Retry with exponential backoff** (temporary outages)

```python
import time

for attempt in range(5):
    response = make_request(api_key, payload)

    if response.status_code == 503:
        wait_time = 2 ** attempt  # 1s, 2s, 4s, 8s, 16s
        print(f"Service unavailable. Retrying in {wait_time}s...")
        time.sleep(wait_time)
        continue

    return response

raise Exception("Service unavailable after retries")
```

**Option 2: Switch to a different model** (model permanently offline)

1. Fetch the current models list:

```bash
curl -X GET https://llm.resayil.io/api/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"
```

2. Pick an available model from the response
3. Retry your request with the new model ID:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [...]
  }'
```

**Option 3: Check platform status** (extended outages)

Visit https://llm.resayil.io (or contact support at info@resayil.io) to confirm if there's a known issue.

**Best Practices**:

- Always fetch the models list dynamically before requesting a model
- Implement fallback models in your application:

```python
preferred_models = ["qwen2.5:7b", "llama3.2:3b", "gemma2:9b"]

for model_id in preferred_models:
    try:
        response = make_request(api_key, model_id, payload)
        return response
    except ServiceUnavailable:
        continue

raise Exception("All preferred models unavailable")
```

---

## Other HTTP Status Codes

### 400 Bad Request

**When it occurs**:
- Missing required fields in request body (e.g., `messages` or `model`)
- Invalid JSON in request body
- Invalid parameter values (e.g., `temperature > 2`)

**Example Response**:

```json
{
  "error": {
    "code": "invalid_request",
    "message": "Missing required field: 'messages'"
  }
}
```

**How to Resolve**:

- Verify all required fields are present: `model`, `messages`
- Ensure JSON is properly formatted
- Check parameter ranges: `temperature` 0-2, `max_tokens` > 0

### 500 Internal Server Error

**When it occurs**:
- Unexpected error on the server side
- Database connection issue
- Bug in API code

**Example Response**:

```json
{
  "error": {
    "code": "internal_server_error",
    "message": "An unexpected error occurred. Please try again later."
  }
}
```

**How to Resolve**:

1. Retry the request (may be transient)
2. If persists, contact support at info@resayil.io with:
   - Timestamp
   - API key (last 4 chars only)
   - Request that failed
   - Error message

---

## Error Handling Best Practices

### Implement Retry Logic

```python
import requests
import time

def make_request_with_retry(api_key, payload, max_retries=3):
    for attempt in range(max_retries):
        try:
            response = requests.post(
                'https://llm.resayil.io/api/v1/chat/completions',
                headers={'Authorization': f'Bearer {api_key}'},
                json=payload
            )

            # Handle specific error codes
            if response.status_code == 401:
                raise ValueError("Invalid API key")
            elif response.status_code == 402:
                raise ValueError("Insufficient credits")
            elif response.status_code == 429:
                wait_time = 2 ** attempt
                time.sleep(wait_time)
                continue
            elif response.status_code == 503:
                wait_time = 2 ** attempt
                time.sleep(wait_time)
                continue

            response.raise_for_status()
            return response.json()

        except requests.exceptions.RequestException as e:
            if attempt == max_retries - 1:
                raise
            time.sleep(2 ** attempt)

    raise Exception("Max retries exceeded")
```

### Log Errors for Debugging

```python
import logging

logger = logging.getLogger(__name__)

try:
    response = make_request(api_key, payload)
except ValueError as e:
    logger.error(f"API Error: {e}")
except Exception as e:
    logger.exception(f"Unexpected error: {e}")
```

### Provide User-Friendly Messages

```python
ERROR_MESSAGES = {
    "unauthorized": "Invalid API key. Please check your credentials.",
    "insufficient_credits": "Your account has run out of credits. Please top up at the billing page.",
    "rate_limit_exceeded": "You're sending requests too fast. Please slow down.",
    "service_unavailable": "The service is currently unavailable. Please try again later."
}

def handle_error(error_code):
    return ERROR_MESSAGES.get(error_code, "An unexpected error occurred.")
```

---

## Support

If you encounter an error not listed here:

- **Email**: info@resayil.io
- **Dashboard**: https://llm.resayil.io/dashboard
- **Status Page**: https://llm.resayil.io (if maintenance in progress)
