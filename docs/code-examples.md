# Code Examples

This page provides complete, copy-paste-ready examples for integrating LLM Resayil into your applications. All examples use the `llama3.2:3b` model and the base URL `https://llm.resayil.io/api/v1`.

Replace `YOUR_API_KEY` with your actual API key from https://llm.resayil.io/api-keys.

---

## cURL

The simplest way to test the API from the command line.

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

### With Environment Variable

For better security, store your API key in an environment variable:

```bash
export RESAYIL_API_KEY="your_api_key_here"

curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer $RESAYIL_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {"role": "user", "content": "What is 2 + 2?"}
    ]
  }'
```

### Streaming Response

To stream the response in real-time:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {"role": "user", "content": "Tell me a story."}
    ],
    "stream": true
  }'
```

---

## Python

### Using the Official OpenAI SDK

The easiest way to use LLM Resayil in Python is with the OpenAI SDK, which is fully compatible.

**Install the SDK**:

```bash
pip install openai
```

**Basic Example**:

```python
from openai import OpenAI

# Initialize the client with your API key and custom base URL
client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

# Make a chat completion request
response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[
        {"role": "system", "content": "You are a helpful assistant."},
        {"role": "user", "content": "What is the capital of France?"}
    ],
    temperature=0.7,
    max_tokens=100
)

# Print the response
print(response.choices[0].message.content)
```

**With Environment Variables** (recommended):

```python
import os
from openai import OpenAI

# Use environment variable for API key
api_key = os.getenv("RESAYIL_API_KEY")

client = OpenAI(
    api_key=api_key,
    base_url="https://llm.resayil.io/api/v1"
)

response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=[{"role": "user", "content": "Hello, how are you?"}]
)

print(response.choices[0].message.content)
```

**Streaming Response**:

```python
from openai import OpenAI

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

# Stream the response
with client.chat.completions.create(
    model="llama3.2:3b",
    messages=[{"role": "user", "content": "Tell me a story."}],
    stream=True
) as stream:
    for text in stream.text_stream:
        print(text, end="", flush=True)
```

**Error Handling**:

```python
from openai import OpenAI, APIError, AuthenticationError, RateLimitError

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

try:
    response = client.chat.completions.create(
        model="llama3.2:3b",
        messages=[{"role": "user", "content": "Hello"}]
    )
    print(response.choices[0].message.content)

except AuthenticationError:
    print("Invalid API key. Check your credentials.")

except RateLimitError:
    print("Rate limit exceeded. Slow down your requests.")

except APIError as e:
    if e.status_code == 402:
        print("Insufficient credits. Purchase more at /billing/plans")
    elif e.status_code == 503:
        print("Service unavailable. Try again later.")
    else:
        print(f"API error: {e}")

except Exception as e:
    print(f"Unexpected error: {e}")
```

**Function Calling Example**:

```python
from openai import OpenAI
import json

client = OpenAI(
    api_key="YOUR_API_KEY",
    base_url="https://llm.resayil.io/api/v1"
)

# Define tools (functions) the model can call
tools = [
    {
        "type": "function",
        "function": {
            "name": "get_weather",
            "description": "Get the weather for a location",
            "parameters": {
                "type": "object",
                "properties": {
                    "location": {
                        "type": "string",
                        "description": "The location to get weather for"
                    }
                },
                "required": ["location"]
            }
        }
    }
]

messages = [
    {"role": "user", "content": "What's the weather in London?"}
]

# Note: Function calling support depends on the model
# Some models may not support this feature
response = client.chat.completions.create(
    model="llama3.2:3b",
    messages=messages,
    tools=tools,
    tool_choice="auto"
)

print(response.choices[0].message.content)
```

---

## JavaScript / TypeScript

### Using the Official OpenAI SDK

**Install the SDK**:

```bash
npm install openai
```

**Basic Example (CommonJS)**:

```javascript
const OpenAI = require("openai");

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1"
});

async function main() {
    const response = await client.chat.completions.create({
        model: "llama3.2:3b",
        messages: [
            { role: "system", content: "You are a helpful assistant." },
            { role: "user", content: "What is the capital of France?" }
        ],
        temperature: 0.7,
        max_tokens: 100
    });

    console.log(response.choices[0].message.content);
}

main().catch(console.error);
```

**Basic Example (ES Modules)**:

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1"
});

const response = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [
        { role: "user", content: "What is the capital of France?" }
    ]
});

console.log(response.choices[0].message.content);
```

**With Environment Variables**:

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: process.env.RESAYIL_API_KEY,
    baseURL: "https://llm.resayil.io/api/v1"
});

const response = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [{ role: "user", content: "Hello!" }]
});

console.log(response.choices[0].message.content);
```

**Streaming Response**:

```javascript
import OpenAI from "openai";

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1"
});

const stream = await client.chat.completions.create({
    model: "llama3.2:3b",
    messages: [{ role: "user", content: "Tell me a story." }],
    stream: true
});

for await (const chunk of stream) {
    if (chunk.choices[0]?.delta?.content) {
        process.stdout.write(chunk.choices[0].delta.content);
    }
}
```

**Error Handling**:

```javascript
import OpenAI from "openai";
import { AuthenticationError, RateLimitError, APIError } from "openai";

const client = new OpenAI({
    apiKey: "YOUR_API_KEY",
    baseURL: "https://llm.resayil.io/api/v1"
});

try {
    const response = await client.chat.completions.create({
        model: "llama3.2:3b",
        messages: [{ role: "user", content: "Hello" }]
    });

    console.log(response.choices[0].message.content);

} catch (error) {
    if (error instanceof AuthenticationError) {
        console.error("Invalid API key. Check your credentials.");
    } else if (error instanceof RateLimitError) {
        console.error("Rate limit exceeded. Slow down your requests.");
    } else if (error instanceof APIError) {
        if (error.status === 402) {
            console.error("Insufficient credits. Purchase more at /billing/plans");
        } else if (error.status === 503) {
            console.error("Service unavailable. Try again later.");
        } else {
            console.error(`API error: ${error.message}`);
        }
    } else {
        console.error(`Unexpected error: ${error.message}`);
    }
}
```

**React Example**:

```javascript
import { useState } from "react";
import OpenAI from "openai";

export default function ChatComponent() {
    const [input, setInput] = useState("");
    const [response, setResponse] = useState("");
    const [loading, setLoading] = useState(false);

    const client = new OpenAI({
        apiKey: process.env.REACT_APP_RESAYIL_API_KEY,
        baseURL: "https://llm.resayil.io/api/v1"
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setResponse("");

        try {
            const stream = await client.chat.completions.create({
                model: "llama3.2:3b",
                messages: [{ role: "user", content: input }],
                stream: true
            });

            for await (const chunk of stream) {
                if (chunk.choices[0]?.delta?.content) {
                    setResponse(prev => prev + chunk.choices[0].delta.content);
                }
            }
        } catch (error) {
            setResponse(`Error: ${error.message}`);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Ask me anything..."
                    disabled={loading}
                />
                <button type="submit" disabled={loading}>
                    {loading ? "Loading..." : "Send"}
                </button>
            </form>
            <div>{response}</div>
        </div>
    );
}
```

---

## n8n HTTP Request Node

Integrate LLM Resayil into n8n workflows using the HTTP Request node.

**Node Configuration**:

1. Add an **HTTP Request** node to your workflow
2. Configure as follows:

**Method**: `POST`

**URL**:
```
https://llm.resayil.io/api/v1/chat/completions
```

**Authentication**: None (handled via header)

**Headers**:

| Key | Value |
|-----|-------|
| `Authorization` | `Bearer YOUR_API_KEY` |
| `Content-Type` | `application/json` |

**Body** (JSON format):

```json
{
  "model": "llama3.2:3b",
  "messages": [
    {
      "role": "user",
      "content": "{{ $json.userInput }}"
    }
  ],
  "temperature": 0.7,
  "max_tokens": 100
}
```

**Extract Response**:

Use the Expression Editor to extract the assistant's message:

```
{{ $json.choices[0].message.content }}
```

**Complete Example Workflow**:

```
Input Node
  ↓
HTTP Request (call LLM Resayil)
  ↓
Set node (extract response)
  ↓
Output Node
```

**In Set Node**, define:

```
key: "response"
value: "{{ $json.choices[0].message.content }}"
```

**With Variables Input**:

If you want to pass dynamic input from another node:

```json
{
  "model": "llama3.2:3b",
  "messages": [
    {
      "role": "system",
      "content": "You are a helpful assistant that {{ $json.systemPrompt }}"
    },
    {
      "role": "user",
      "content": "{{ $json.userMessage }}"
    }
  ]
}
```

**Error Handling in n8n**:

Add an **IF** node after the HTTP Request:

```
IF response.status === 200
  THEN: Extract and process response
  ELSE: Handle error (402 = credits, 429 = rate limit, etc.)
```

**Rate Limiting in n8n**:

If you're hitting rate limits, use a **Delay** node between requests:

```
HTTP Request (LLM Resayil)
  ↓
Delay (2 seconds)
  ↓
Next Node
```

---

## Next Steps

- **Read the [API Reference](api-reference.md)** for complete endpoint documentation
- **Check [Models](models.md)** to see all available language models
- **Review [Rate Limits](rate-limits.md)** for production-scale deployments
- **See [Error Codes](error-codes.md)** for comprehensive error handling

---

## Support

For issues with code examples:

- Check the **[API Reference](api-reference.md)** and **[Error Codes](error-codes.md)**
- Contact support at info@resayil.io
- Visit https://llm.resayil.io/dashboard for usage insights
