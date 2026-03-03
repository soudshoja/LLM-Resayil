# API Reference

LLM Resayil provides an OpenAI-compatible API for chat completions and model listing. All requests must include an `Authorization: Bearer YOUR_API_KEY` header.

## Base URL

```
https://llm.resayil.io/api/v1
```

All endpoints are relative to this base URL.

---

## POST /chat/completions

Creates a chat completion response based on the provided messages.

### Request

**Method**: `POST`

**URL**: `https://llm.resayil.io/api/v1/chat/completions`

**Headers**:
```
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json
```

**Request Body** (JSON):

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `model` | string | Yes | The model ID to use (e.g., `llama3.2:3b`). See [Models](models.md) for available options. |
| `messages` | array | Yes | Array of message objects with `role` ("user", "assistant", or "system") and `content` (string). |
| `stream` | boolean | No | If `true`, response will be a server-sent events (SSE) stream. Default: `false`. |
| `temperature` | number | No | Controls randomness: 0 = deterministic, 2 = maximum randomness. Default: 0.7. |
| `max_tokens` | integer | No | Maximum tokens to generate. If not specified, model generates until completion or limit reached. |

### Example Request

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {
        "role": "system",
        "content": "You are a helpful assistant."
      },
      {
        "role": "user",
        "content": "What is the capital of France?"
      }
    ],
    "temperature": 0.7,
    "max_tokens": 100
  }'
```

### Response

**HTTP 200 OK** (non-streaming):

```json
{
  "id": "chatcmpl-8p4h9k2m1n5x",
  "object": "chat.completion",
  "created": 1709500000,
  "model": "llama3.2:3b",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "The capital of France is Paris."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 32,
    "completion_tokens": 9,
    "total_tokens": 41
  }
}
```

**Response Fields**:

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique identifier for this completion. |
| `object` | string | Always `"chat.completion"`. |
| `created` | integer | Unix timestamp (seconds) when the completion was created. |
| `model` | string | The model used to generate the response. |
| `choices` | array | Array of completion choices. |
| `choices[].index` | integer | Index of this choice in the choices array. |
| `choices[].message` | object | The assistant's response message with `role` and `content`. |
| `choices[].finish_reason` | string | Reason completion stopped: `"stop"` (natural end), `"length"` (max_tokens reached), or `"error"`. |
| `usage.prompt_tokens` | integer | Number of tokens in the prompt. |
| `usage.completion_tokens` | integer | Number of tokens in the generated response. |
| `usage.total_tokens` | integer | Total tokens (prompt + completion). |

### Streaming Response

If `stream: true`, the response is a Server-Sent Events (SSE) stream:

```
data: {"id":"chatcmpl-...","object":"chat.completion.chunk","created":1709500000,"model":"llama3.2:3b","choices":[{"index":0,"delta":{"content":"Hello"},"finish_reason":null}]}
data: {"id":"chatcmpl-...","object":"chat.completion.chunk","created":1709500000,"model":"llama3.2:3b","choices":[{"index":0,"delta":{"content":" there"},"finish_reason":null}]}
data: [DONE]
```

Each event is a JSON object on a single line. The stream ends with `data: [DONE]`.

### Error Responses

#### 401 Unauthorized

```json
{
  "error": {
    "code": "unauthorized",
    "message": "Invalid or missing API key"
  }
}
```

**Resolution**: Check your API key and ensure the `Authorization` header is correct.

#### 402 Payment Required

```json
{
  "error": {
    "code": "insufficient_credits",
    "message": "Insufficient credits. Please purchase a top-up at /billing/plans"
  }
}
```

**Resolution**: Purchase additional credits at https://llm.resayil.io/billing/plans.

#### 429 Too Many Requests

```json
{
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Rate limit exceeded. Current limit: 10 requests per minute. Retry after 60 seconds."
  }
}
```

**Resolution**: Slow down your requests or upgrade your plan at https://llm.resayil.io/billing/plans.

#### 503 Service Unavailable

```json
{
  "error": {
    "code": "service_unavailable",
    "message": "The requested model is currently unavailable. Please try again later or use a different model."
  }
}
```

**Resolution**: Try again in a few moments or use a different model from the [Models](models.md) list.

---

## GET /models

Lists all available models.

### Request

**Method**: `GET`

**URL**: `https://llm.resayil.io/api/v1/models`

**Headers**:
```
Authorization: Bearer YOUR_API_KEY
```

### Example Request

```bash
curl -X GET https://llm.resayil.io/api/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"
```

### Response

**HTTP 200 OK**:

```json
{
  "object": "list",
  "data": [
    {
      "id": "llama3.2:3b",
      "object": "model",
      "created": 1704067200,
      "owned_by": "meta"
    },
    {
      "id": "qwen2.5:7b",
      "object": "model",
      "created": 1704067200,
      "owned_by": "alibaba"
    },
    {
      "id": "gemma2:9b",
      "object": "model",
      "created": 1704067200,
      "owned_by": "google"
    }
  ]
}
```

**Response Fields**:

| Field | Type | Description |
|-------|------|-------------|
| `object` | string | Always `"list"`. |
| `data` | array | Array of model objects. |
| `data[].id` | string | The model identifier (use this in `POST /chat/completions`). |
| `data[].object` | string | Always `"model"`. |
| `data[].created` | integer | Unix timestamp when the model was added to the platform. |
| `data[].owned_by` | string | Organization that created the model (e.g., "meta", "alibaba", "google"). |

### Error Responses

#### 401 Unauthorized

Same as chat/completions endpoint — ensure your API key is valid.

---

## Rate Limits

All endpoints are subject to rate limiting. The limit depends on your subscription tier:

| Plan | Requests/Minute |
|------|-----------------|
| Starter | 10 |
| Basic | 10 |
| Pro | 30 |
| Enterprise | 60 |

Rate limits are applied per API key on a rolling 1-minute window. If you exceed your limit, the API returns HTTP 429 with an error message.

---

## Token Counting

Tokens are counted at the output (completion). The API returns `usage.total_tokens` in every response:

- **prompt_tokens**: Tokens in your input messages
- **completion_tokens**: Tokens the model generated
- **total_tokens**: Sum of prompt + completion

Credits are deducted based on `completion_tokens`:
- **Local models**: 1 credit per token
- **Cloud models**: 2 credits per token

---

## Timeout & Retry Guidance

- **Timeout**: Set your client timeout to at least 30 seconds for most requests
- **Retry strategy**: Implement exponential backoff (e.g., 1s, 2s, 4s, 8s) for 5xx errors and 429 responses
- **Idempotency**: Retries are safe; each request generates a new completion with a unique `id`

---

## Best Practices

1. **Always include the `Authorization` header** — requests without it will fail with 401
2. **Check the `usage` field** to track your credit consumption
3. **Use streaming** for real-time user feedback in web/mobile applications
4. **Implement error handling** for 401 (auth), 402 (credits), and 429 (rate limits)
5. **Cache model lists** — fetch `/models` once and reuse the list rather than calling it on every request
6. **Set reasonable timeouts** and retry logic in your client code
7. **Monitor your dashboard** at https://llm.resayil.io/dashboard for usage patterns and anomalies

---

## OpenAI SDK Compatibility

Since LLM Resayil is OpenAI-compatible, you can use official OpenAI SDKs with a custom `baseURL`:

### Python

```python
from openai import OpenAI

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[{"role": "user", "content": "Hello"}]
)

print(response.choices[0].message.content)
```

### JavaScript

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1"
});

const response = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [{ role: "user", content: "Hello" }]
});

console.log(response.choices[0].message.content);
```

For more examples and language-specific guides, see [Code Examples](code-examples.md).
