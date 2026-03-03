# Models

LLM Resayil provides access to 45 state-of-the-art language models across multiple families, including both local and cloud-based options.

## Listing Available Models

To see the current list of available models, make a GET request to the models endpoint:

```bash
curl -X GET https://llm.resayil.io/api/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"
```

Response:

```json
{
  "object": "list",
  "data": [
    {"id": "llama3.2:3b", "object": "model", "created": 1704067200, "owned_by": "meta"},
    {"id": "qwen2.5:7b", "object": "model", "created": 1704067200, "owned_by": "alibaba"},
    {"id": "gemma2:9b", "object": "model", "created": 1704067200, "owned_by": "google"}
  ]
}
```

Always fetch models dynamically — the available list may change as the platform adds, removes, or toggles models.

## Model Families

### Meta Llama

Open-source foundational models optimized for chat and general-purpose tasks.

- **llama3.2:3b** — Lightweight, fast, suitable for real-time applications
- **llama3.2:8b** — Balanced performance and quality
- **llama2:7b** — Previous generation, still reliable
- **llama2:13b** — Higher capacity, better reasoning

### Alibaba Qwen

Advanced Chinese-optimized models with strong multilingual support.

- **qwen2.5:7b** — Latest generation, excellent quality
- **qwen2:7b** — Stable, general-purpose
- **qwen:7b** — Previous version
- **qwen:14b** — High-capacity variant

### Google Gemma

Lightweight, efficient models designed for broad applicability.

- **gemma2:9b** — Latest Gemma 2 model
- **gemma2:2b** — Ultra-lightweight
- **gemma:7b** — Previous generation

### DeepSeek

High-performance reasoning models from Chinese AI lab DeepSeek.

- **deepseek-v3** — Latest flagship model, excellent for complex reasoning
- **deepseek-r1** — Reasoning-focused variant

### Mistral

Fast, efficient models optimized for speed and cost.

- **mistral:7b** — Core Mistral model
- **mixtral:8x7b** — Mixture of Experts model with higher capacity

### Zhipu AI GLM

Advanced models designed for fine-grained language understanding.

- **glm-4:9b** — Latest generation GLM
- **glm-4:7b** — Optimized variant
- **glm:4k** — Previous generation

### Other Models

Additional specialized and community models:

- **neural-chat:7b** — Intel optimized for conversational AI
- **orca:13b** — Microsoft's reasoning-focused model
- **openhermes:7b** — Community fine-tuned for instruction following
- **starling:7b** — Optimized for performance and alignment

## Local vs. Cloud Models

The platform provides both **local models** (running on dedicated GPU servers) and **cloud-proxied models** (via ollama.com integration).

### Local Models

**Count**: 15 models running on dedicated GPU infrastructure

**Characteristics**:
- Faster response times (typically 1-3 seconds per completion)
- Lower cost: **1 credit per token**
- Always available (no external dependency)
- Examples: llama3.2:3b, qwen2.5:7b, gemma2:9b, mistral:7b

**Best for**: Time-sensitive applications, real-time features, budget-conscious use

### Cloud Models

**Count**: 30 models proxied via ollama.com cloud

**Characteristics**:
- Wider model selection (30+ variants)
- Higher cost: **2 credits per token**
- May have slightly longer latency (2-5 seconds)
- Fallback available if local resources saturated

**Best for**: Specialized tasks, niche models, applications where cost isn't the primary concern

### Determining Model Type

There's no explicit flag in the API response. Reference the model documentation or contact support to confirm whether a model is local or cloud-proxied. Generally:
- Models with base names (llama3.2:3b, qwen2.5:7b) tend to be local
- Models with variant suffixes or less common versions tend to be cloud-proxied

## Using a Model

To use a model, pass its exact `id` from the models list to the `model` parameter in a chat completion request:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "llama3.2:3b",
    "messages": [
      {"role": "user", "content": "Who was Albert Einstein?"}
    ]
  }'
```

**Important**: Use the exact model ID as returned by `/models`. IDs are case-sensitive and must match precisely.

## Model Availability & Platform Updates

Models are managed by platform administrators and may be:

- **Toggled on/off**: Models can be disabled temporarily during maintenance or permanently retired
- **Added**: New models are added as they become available and are tested
- **Deprecated**: Older models may be removed if they become obsolete

**Always fetch the models list dynamically** before displaying options to users or making assumptions about availability. Caching the list for short periods (e.g., 5-15 minutes) is reasonable for performance, but be prepared to handle the case where a user's previously-selected model is no longer available.

### Error Handling for Unavailable Models

If you request a model that's been disabled or removed, the API returns HTTP 503:

```json
{
  "error": {
    "code": "service_unavailable",
    "message": "The requested model 'model_id' is currently unavailable. Please try a different model."
  }
}
```

**Resolution**: Fetch the current models list and retry with an available model.

## Recommendations for Different Use Cases

### Real-time Chat Applications

Use lightweight local models for fast responses:
- **llama3.2:3b** — Excellent balance of speed and quality
- **gemma2:2b** — Ultra-fast for latency-critical apps

### Complex Reasoning & Analysis

Use larger models:
- **deepseek-v3** — Best for deep reasoning and analysis
- **mistral:8x7b** — High-capacity mixture of experts

### Multilingual / Chinese-Heavy Workloads

Use Qwen family:
- **qwen2.5:7b** — Superior Chinese understanding and generation
- **qwen:14b** — Larger capacity for complex multilingual tasks

### Cost-Optimized Production

Use local models:
- **llama3.2:3b** — 1 credit/token, still excellent quality
- **mistral:7b** — Fast, low cost, good quality
- **qwen2.5:7b** — Multilingual, cost-effective

### Specialized Tasks

Use cloud models if local options don't meet requirements:
- **deepseek-r1** — Specialized reasoning
- **orca:13b** — Microsoft's reasoning-focused model
- **openhermes:7b** — Instruction-following specialist

## Model Parameters

When making requests, you can adjust model behavior with these parameters:

| Parameter | Range | Default | Effect |
|-----------|-------|---------|--------|
| `temperature` | 0-2 | 0.7 | Controls randomness (0 = deterministic, 2 = creative) |
| `max_tokens` | 1-∞ | Model limit | Maximum tokens to generate |
| `stream` | true/false | false | Stream response in real-time |

Example with parameters:

```bash
curl -X POST https://llm.resayil.io/api/v1/chat/completions \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "deepseek-v3",
    "messages": [{"role": "user", "content": "Explain quantum computing"}],
    "temperature": 0.5,
    "max_tokens": 500
  }'
```

## Support & Documentation

- See [API Reference](api-reference.md) for detailed endpoint documentation
- See [Code Examples](code-examples.md) for language-specific usage patterns
- See [Billing & Credits](billing-credits.md) for cost information per model type
- For model-specific documentation, visit the official project sites:
  - Meta Llama: https://www.llama.com
  - Alibaba Qwen: https://github.com/QwenLM/Qwen
  - Google Gemma: https://ai.google.dev/gemma
  - DeepSeek: https://www.deepseek.com
  - Mistral: https://mistral.ai
