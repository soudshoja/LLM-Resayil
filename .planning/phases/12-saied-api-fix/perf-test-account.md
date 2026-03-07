# Performance Test Account

Created for: qwen3-vl:235b-instruct-cloud context/load testing

| Field | Value |
|-------|-------|
| User ID | cec01804-e478-49c0-a8f7-a162e630512f |
| Email | perftest@llm.resayil.io |
| Password | PerfTest2026! |
| Credits | 50,000 |
| Tier | enterprise |
| API Key | dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50 |

## Usage
```
Authorization: Bearer dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50
```

## Test Plan
- 4 context sizes: 50k / 100k / 150k / 250k tokens — qwen3-vl:235b-instruct-cloud
- 4 concurrent users same prompt — queue depth test
- Streaming vs non-streaming latency comparison
- Local (smollm2:135m) vs cloud latency comparison
