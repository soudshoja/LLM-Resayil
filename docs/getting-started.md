# Getting Started with LLM Resayil

Welcome to LLM Resayil, an OpenAI-compatible LLM API platform with pay-per-use credits. This guide will walk you through registration, subscription, and making your first API call.

## Prerequisites

- A valid email address
- A phone number (for OTP verification during registration)
- A payment method (KNET or international credit/debit card) for subscription or top-ups

## Step 1: Register Your Account

1. Navigate to https://llm.resayil.io/register
2. Fill in the registration form:
   - **Name**: Your full name
   - **Email**: A valid email address
   - **Phone**: Your phone number (including country code, e.g., +965 for Kuwait)
   - **Password**: A strong password (at least 8 characters)
3. Click "Register"
4. You'll be prompted to verify your phone number via OTP (One-Time Password)
5. Check your phone for an SMS containing the 6-digit OTP code
6. Enter the OTP on the screen to complete registration
7. You'll be logged in and redirected to the dashboard

## Step 2: Subscribe to a Plan

1. Once logged in, navigate to https://llm.resayil.io/billing/plans
2. Browse the available subscription plans:
   - **Starter**: 15 KWD/month — 10 requests/minute, 1 API key
   - **Basic**: 25 KWD/month — 10 requests/minute, 2 API keys
   - **Pro**: 45 KWD/month — 30 requests/minute, 3 API keys
   - **Enterprise**: Custom pricing — 60 requests/minute, unlimited API keys
3. To get started risk-free, select the **7-Day Free Trial** (requires payment method on file)
4. Choose your payment method:
   - **KNET** (Kuwait's local payment method)
   - **International Credit Card** (Visa, Mastercard, etc.)
5. Complete the payment process
6. Your subscription is now active, and you have access to all available models

> **Note**: The free trial will automatically convert to the Starter plan after 7 days unless you cancel. You can manage or cancel your subscription anytime at `/billing/plans`.

## Step 3: Create an API Key

1. Go to https://llm.resayil.io/api-keys
2. Click the **"Create Key"** button
3. Give your key a descriptive name (e.g., "Production App", "Testing")
4. Click **"Create"**
5. **Important**: Your API key is displayed only once. Copy it immediately and store it securely (e.g., in your `.env` file or a password manager)
6. If you lose the key, you'll need to create a new one and delete the old one

Your API key will be in the format of a long alphanumeric string. Treat it like a password — never commit it to version control or expose it in client-side code.

## Step 4: Make Your First API Call

Now let's test your setup with a simple chat completion request.

### Using cURL

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {
        "role": "user",
        "content": "Hello! What is 2 + 2?"
      }
    ]
  }'
```

Replace `YOUR_API_KEY` with your actual API key.

### Expected Response

```json
{
  "id": "chatcmpl-abc123",
  "object": "chat.completion",
  "created": 1709500000,
  "model": "llama3.2:3b",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "2 + 2 equals 4."
      },
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": 15,
    "completion_tokens": 8,
    "total_tokens": 23
  }
}
```

The response includes your model's answer, token usage, and completion metadata.

## Step 5: Check Your Usage

1. Go to https://llm.resayil.io/dashboard
2. You'll see:
   - **Credits Remaining**: Your current credit balance
   - **Recent API Calls**: A history of your recent requests with token counts and costs
   - **Usage Charts**: Visual breakdown of your usage patterns
3. Download usage reports for billing records if needed

## Using OpenAI SDKs

Since LLM Resayil is OpenAI-compatible, you can use official OpenAI SDKs by overriding the base URL.

### Python Example

```python
from openai import OpenAI

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[
        {"role": "user", "content": "Hello! What is 2 + 2?"}
    ]
)

print(response.choices[0].message.content)
```

### JavaScript Example

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1",
    defaultHeaders: {
        "User-Agent": "OpenAI/JS"
    }
});

const response = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [
        { role: "user", content: "Hello! What is 2 + 2?" }
    ]
});

console.log(response.choices[0].message.content);
```

## Next Steps

- Read the **[API Reference](api-reference.md)** for detailed endpoint documentation
- Check out **[Models](models.md)** to see all available LLM options
- Review **[Authentication](authentication.md)** for API key management best practices
- Explore **[Code Examples](code-examples.md)** for your preferred language or framework
- Learn about **[Billing & Credits](billing-credits.md)** for pricing and payment details

## Support

If you encounter any issues:
- Check the **[Error Codes](error-codes.md)** reference
- Review **[Rate Limits](rate-limits.md)** if you're hitting limits
- Contact support at info@resayil.io

Happy coding!
