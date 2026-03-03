# Rate Limits

LLM Resayil enforces rate limits to ensure fair use and platform stability. Rate limits are based on your subscription plan and are applied per API key.

## Rate Limits by Plan

Each subscription plan includes a maximum number of API requests per minute:

| Plan | Requests per Minute | Best For |
|------|---------------------|----------|
| Starter | 10 | Hobby projects, testing |
| Basic | 10 | Small applications |
| Pro | 30 | Growing businesses |
| Enterprise | 60 | Large-scale applications |

**Note**: The limits apply per API key, per rolling 1-minute window.

### Admin Override

Users with the admin account (admin@llm.resayil.io) bypass all rate limits and credit checks. This is reserved for testing and administrative purposes only.

## How Rate Limits Work

### Rolling 1-Minute Window

Rate limits use a rolling 1-minute window, not a fixed hourly or daily window:

- The platform counts requests made in the last 60 seconds
- Once 60 seconds have passed, old requests "fall off" the counter
- You can make requests continuously as long as you stay under the limit

### Example

**Starter plan** (10 requests/minute limit):

```
00:00s — Request 1 ✓
00:05s — Request 2 ✓
00:10s — Request 3 ✓
00:15s — Request 4 ✓
00:20s — Request 5 ✓
00:25s — Request 6 ✓
00:30s — Request 7 ✓
00:35s — Request 8 ✓
00:40s — Request 9 ✓
00:45s — Request 10 ✓
00:50s — Request 11 ✗ (limit reached, must wait)
01:00s — Request 1 falls out of window, Request 11 is now allowed ✓
```

## Rate Limit Exceeded Response

When you exceed your rate limit, the API returns **HTTP 429 (Too Many Requests)**:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -d '...'
```

```json
{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Rate limit exceeded. Current limit: 10 requests per minute. Retry after 60 seconds."
  }
}
```

## Response Headers (Planned)

When rate limit responses are implemented, the following headers will be included:

```
X-RateLimit-Limit: 10
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1709500060
```

These headers tell your application:
- `X-RateLimit-Limit`: Your plan's limit (requests/minute)
- `X-RateLimit-Remaining`: Requests remaining in the current window
- `X-RateLimit-Reset`: Unix timestamp when the current window expires

## Retry Strategy

### Exponential Backoff

When you hit a rate limit, implement exponential backoff with jitter:

```python
import time
import requests

def make_request_with_retry(api_key, payload, max_retries=5):
    for attempt in range(max_retries):
        response = requests.post(
            'https://llm.resayil.io/api/v1/chat/completions',
            headers={'Authorization': f'Bearer {api_key}'},
            json=payload
        )

        if response.status_code == 429:
            # Calculate backoff: 2^attempt + random jitter
            wait_time = (2 ** attempt) + (random.random() * 0.1)
            print(f"Rate limited. Waiting {wait_time:.2f} seconds...")
            time.sleep(wait_time)
            continue

        return response

    raise Exception("Max retries exceeded")
```

### JavaScript Example

```javascript
async function makeRequestWithRetry(apiKey, payload, maxRetries = 5) {
    for (let attempt = 0; attempt < maxRetries; attempt++) {
        const response = await fetch(
            'https://llm.resayil.io/api/v1/chat/completions',
            {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiKey}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            }
        );

        if (response.status === 429) {
            const waitTime = Math.pow(2, attempt) + Math.random() * 0.1;
            console.log(`Rate limited. Waiting ${waitTime.toFixed(2)} seconds...`);
            await new Promise(resolve => setTimeout(resolve, waitTime * 1000));
            continue;
        }

        return response;
    }

    throw new Error('Max retries exceeded');
}
```

### Backoff Algorithm Details

- **Attempt 1**: Wait 1-2 seconds
- **Attempt 2**: Wait 2-4 seconds
- **Attempt 3**: Wait 4-8 seconds
- **Attempt 4**: Wait 8-16 seconds
- **Attempt 5**: Wait 16-32 seconds

Always add **jitter** (random delay) to prevent thundering herd (all clients retrying at exact same time).

## Managing Rate Limits

### Estimate Request Volume

Estimate your monthly request volume and choose an appropriate plan:

- **Starter/Basic (10/min)**: Up to 432,000 requests/month
  - Works for: Small apps, background jobs, periodic checks
- **Pro (30/min)**: Up to 1.3M requests/month
  - Works for: Growing businesses, moderate-load apps
- **Enterprise (60/min)**: Up to 2.6M requests/month
  - Works for: High-traffic platforms, real-time systems

### Queue & Batch Requests

If your application has bursts of traffic, use a queue to smooth requests:

```python
from queue import Queue
import threading
import time

class RateLimitedQueue:
    def __init__(self, max_requests_per_minute=10):
        self.queue = Queue()
        self.max_requests = max_requests_per_minute
        self.window_duration = 60  # seconds

    def add_request(self, request):
        self.queue.put(request)

    def process_queue(self):
        request_times = []

        while True:
            if self.queue.empty():
                time.sleep(1)
                continue

            # Remove requests older than 1 minute
            now = time.time()
            request_times = [t for t in request_times if now - t < self.window_duration]

            # If under limit, process next request
            if len(request_times) < self.max_requests:
                request = self.queue.get()
                # Make API call here
                request_times.append(now)
            else:
                # Wait before retrying
                time.sleep(1)

# Usage
queue = RateLimitedQueue(max_requests_per_minute=10)

# Enqueue requests
for i in range(100):
    queue.add_request({'model': 'llama3.2:3b', 'messages': [...]})

# Process in separate thread
threading.Thread(target=queue.process_queue, daemon=True).start()
```

### Use Multiple API Keys

If you have multiple API keys (allowed on Basic, Pro, and Enterprise plans), distribute load across keys:

```python
api_keys = [
    "sk_llmr_key1_...",
    "sk_llmr_key2_...",
    "sk_llmr_key3_..."
]

# Round-robin: use a different key for each request
for i, request in enumerate(requests):
    api_key = api_keys[i % len(api_keys)]
    make_api_call(api_key, request)
```

This effectively multiplies your throughput by the number of keys.

### Monitor Your Usage

1. Visit https://llm.resayil.io/dashboard
2. Check the **Usage Chart** to see requests/minute trends
3. If you consistently hit limits, upgrade your plan

## Upgrade Your Plan

When you need higher rate limits:

1. Go to https://llm.resayil.io/billing/subscription
2. Select a higher plan (Basic → Pro → Enterprise)
3. Pay the prorated difference for the current month
4. **Rate limits apply immediately**

## Frequently Asked Questions

### Q: Do rate limits reset at midnight?

**A**: No. Rate limits use a rolling 1-minute window, not daily/hourly. Once 60 seconds have passed, old requests are no longer counted.

### Q: If I have 2 API keys, do I get 2× the rate limit?

**A**: No. Rate limits are enforced per key. With 2 keys on a Starter plan, you can make 10 requests/minute per key (20 total across both keys).

### Q: Does a failed request count against my rate limit?

**A**: Yes. All requests (successful or failed) count toward the rate limit.

### Q: What if I consistently need more than 60 requests/minute?

**A**: Contact support at info@resayil.io for Enterprise plan details and custom rate limit negotiation.

### Q: Can I get real-time rate limit status?

**A**: Monitor the `X-RateLimit-Remaining` header (when implemented) or track request timestamps in your application.

### Q: Do streaming requests count differently?

**A**: No. Each streaming request counts as 1 request, regardless of duration or size of the stream.

## Support

For rate limit issues or to discuss higher limits:

- **Email**: info@resayil.io
- **Dashboard**: https://llm.resayil.io/billing/subscription
- **Response time**: Usually within 24 hours
