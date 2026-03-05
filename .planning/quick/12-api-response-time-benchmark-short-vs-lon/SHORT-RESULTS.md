# Short Prompt Benchmark (5 words)
Prompt: "Say hello in one word."

Model: llama3.2:latest
max_tokens: 10
stream: false

## Call 1
- Total time: 6.386846s
- Time to first byte: 6.386783s
- Connect time: 1.564296s
- HTTP status: 200
- Response: {"id":"chatcmpl-8c3a7686-09ee-4dc8-8290-841034

## Call 2
- Total time: 1.935841s
- Time to first byte: 1.935812s
- Connect time: 0.169519s
- HTTP status: 200
- Response: {"id":"chatcmpl-6d35def4-bda6-4035-bd4b-382682

## Call 3
- Total time: 11.072698s
- Time to first byte: 11.072670s
- Connect time: 1.359091s
- HTTP status: 200
- Response: {"id":"chatcmpl-b0278545-bc34-4fc0-a485-e63e1a

## Summary
- Min: 1.935841s
- Max: 11.072698s
- Avg: 6.465128s

## Notes
- All calls returned HTTP 200 with response body: `{"role":"assistant","content":"Hello."}`
- Token usage: prompt_tokens=31, completion_tokens=3, total_tokens=34 (consistent across all 3)
- Call 1 cold-start (high connect time 1.564s) — likely PHP-FPM process spin-up or queue worker dispatch
- Call 2 fastest (connect 0.169s) — warm connection, likely cached/warm worker
- Call 3 slowest (11.07s) — GPU contention or model context reload
- Time to first byte equals total time in all cases (non-streaming, full response buffered)
- High variance (1.9s–11.1s) suggests GPU scheduling variability on the Ollama backend
