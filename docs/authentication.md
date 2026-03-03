# Authentication

LLM Resayil uses API keys for authentication. Every request to the API must include a valid API key in the `Authorization` header.

## Creating API Keys

You can create and manage API keys from the **[API Keys](https://llm.resayil.io/api-keys)** dashboard.

### Steps to Create a Key

1. Go to https://llm.resayil.io/api-keys
2. Click **"Create Key"**
3. Provide a descriptive name (e.g., "Mobile App", "Integration with n8n")
4. Click **"Create"**
5. **Your API key will be displayed only once** — copy it immediately
6. Store it securely (never in version control, client-side code, or public repositories)

### Key Visibility Policy

For security reasons:
- Keys are shown only at creation time
- You cannot view the full key again after leaving the page
- If you lose a key, delete it and create a new one

## API Key Format

API keys are long alphanumeric strings, typically 32+ characters. Example (not real):

```
sk_llmr_abc123def456ghi789jkl012mno345
```

## Authentication Header

Every API request must include the `Authorization` header with your API key:

```
Authorization: Bearer YOUR_API_KEY
```

The format is:
- `Bearer ` (literal, with space)
- Your API key (the full string)

### cURL Example

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer sk_llmr_your_actual_key_here" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [{"role": "user", "content": "Hello"}]
  }'
```

### Python Example

```python
from openai import OpenAI

client = OpenAI(
    api_key="sk_llmr_your_actual_key_here",
    base_url="https://llm.resayil.io/api/v1"
)

# The client automatically adds the Authorization header
response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[{"role": "user", "content": "Hello"}]
)
```

### JavaScript Example

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: "sk_llmr_your_actual_key_here",
    baseURL: "https://llm.resayil.io/api/v1"
});

// The client automatically adds the Authorization header
const response = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [{ role: "user", content: "Hello" }]
});
```

## Multiple API Keys

Depending on your subscription tier, you can create multiple API keys:

| Plan | Max Keys |
|------|----------|
| Starter | 1 |
| Basic | 2 |
| Pro | 3 |
| Enterprise | Unlimited |

**Use case**: Create separate keys for different apps or integrations. If one key is compromised, you can revoke only that key without affecting other integrations.

## Managing API Keys

### Viewing Your Keys

1. Go to https://llm.resayil.io/api-keys
2. You'll see a list of your keys with:
   - **Name**: The name you gave it
   - **Created**: When the key was created
   - **Last Used**: When it was last used (if ever)
   - **Status**: Active or Revoked

### Revoking a Key

1. Find the key in the list
2. Click the **"Revoke"** button
3. Confirm the action
4. The key is immediately disabled — requests using it will return a 401 error
5. You cannot re-enable a revoked key; create a new one instead

## Security Best Practices

### Do's

- Store API keys in environment variables (`.env` files, secrets managers, CI/CD platforms)
- Use different keys for different applications or environments (dev, staging, prod)
- Rotate keys periodically (e.g., every 3–6 months)
- Monitor your usage dashboard for unusual activity
- Include rate limiting and error handling in your code
- Use HTTPS for all API requests (always use https://, never http://)

### Don'ts

- Never hardcode API keys in source code
- Never expose keys in client-side code (frontend JavaScript, mobile apps without a backend)
- Never share keys via email, Slack, or chat applications
- Never commit keys to version control (Git, GitHub, etc.)
- Never use the same key for multiple unrelated projects
- Never log your API key (even in error messages or logs)

### If Your Key is Compromised

1. Go to https://llm.resayil.io/api-keys immediately
2. Click **"Revoke"** on the compromised key
3. Create a new API key
4. Update all applications using the old key to use the new one
5. Monitor your usage dashboard for suspicious activity

## Error Responses

### 401 Unauthorized

Returned when:
- No `Authorization` header is provided
- The API key is invalid or has been revoked
- The API key format is incorrect

#### Example Response

```json
{
  "error": {
    "code": "unauthorized",
    "message": "Invalid or missing API key"
  }
}
```

#### Resolution

- Verify your API key is correct and not revoked
- Check that the `Authorization: Bearer YOUR_API_KEY` header is present
- Create a new key at https://llm.resayil.io/api-keys if needed

## Advanced: API Key Rotation

If you want to rotate keys without downtime:

1. Create a new API key
2. Update your applications to use the new key (you can set both in your config and switch gradually)
3. Monitor the old key's usage to confirm everything has been updated
4. Revoke the old key once you're confident nothing is using it
5. Remove the old key from your configuration

## Support

If you need help with authentication or have lost your API key:
- Reset your password at https://llm.resayil.io/login to regain account access
- Create a new API key at https://llm.resayil.io/api-keys
- Contact support at info@resayil.io for account-related issues
