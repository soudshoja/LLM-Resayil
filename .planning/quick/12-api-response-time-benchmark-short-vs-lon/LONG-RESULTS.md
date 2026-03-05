# Long Prompt Benchmark (500+ words)

Prompt: I am building a SaaS application that serves as an OpenAI-compat...

Model: llama3.2:latest
Max tokens: 50 (note: model ignored this and returned full responses)
Stream: false

## Call 1

- Total time: 13.543422s
- Time to first byte: 13.543187s
- Connect time: 3.636472s
- HTTP status: 200
- Prompt tokens: 275
- Completion tokens: 1229
- Total tokens: 1504
- Response: Technical Architecture Document\n\nI. Authentication Flow from...

## Call 2

- Total time: 12.839302s
- Time to first byte: 12.839182s
- Connect time: 1.518880s
- HTTP status: 200
- Prompt tokens: 275
- Completion tokens: 1349
- Total tokens: 1624
- Response: Technical Architecture Document: OpenAI-Compatible SaaS Appli...

## Call 3

- Total time: 5.991585s
- Time to first byte: 5.991525s
- Connect time: 0.054220s
- HTTP status: 200
- Prompt tokens: 275
- Completion tokens: 709
- Total tokens: 984
- Response: **Technical Architecture Document**\n\n**1. Authentication Flow**...

## Summary

- Min: 5.991585s
- Max: 13.543422s
- Avg: 10.791436s
- All calls: 200 OK
- Note: max_tokens=50 was not enforced — model returned 709-1349 completion tokens each call
- Call 1 had a notably high connect time (3.636s) vs calls 2-3, suggesting cold connection overhead
- Call 3 was significantly faster (6s vs 12-13s), likely due to KV cache hits on the repeated long prompt
