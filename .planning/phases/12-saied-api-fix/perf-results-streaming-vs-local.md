# Performance Test Results: Streaming vs Non-Streaming

**Date:** 2026-03-07
**Endpoint:** https://llm.resayil.io/api/v1/chat/completions
**Prompt:** "Explain quantum computing in 3 sentences."
**Runs per test:** 3

---

## Summary Table

| Test | Model | Mode | TTFB (avg) | Total Time (avg) | Response Length | Notes |
|------|-------|------|-----------|-----------------|----------------|-------|
| 1 | smollm2:135m (local) | Non-streaming | N/A | **1.242s** | ~332 chars | Works correctly |
| 2 | smollm2:135m (local) | Streaming | ~1.354s | ~1.354s | 0 chars | BROKEN — empty body |
| 3 | qwen3-vl:235b-instruct-cloud | Non-streaming | N/A | **5.950s** | ~548 chars | Works correctly |
| 4 | qwen3-vl:235b-instruct-cloud | Streaming | ~2.281s | ~2.281s | 0 chars | BROKEN — empty body |

---

## Detailed Results

### TEST 1 — Local model (smollm2:135m), Non-Streaming

| Run | Total Time | Response Length |
|-----|-----------|----------------|
| 1   | 1.221s    | 181 chars       |
| 2   | 1.231s    | 433 chars       |
| 3   | 1.279s    | 382 chars       |
| **Min** | **1.221s** | — |
| **Avg** | **1.242s** | ~332 chars |
| **Max** | **1.279s** | — |

Sample response: "Quantum computing is based on the principles of superposition and entanglement, allowing it to process data much faster than classical computers using the laws of quantum mechanics."

---

### TEST 2 — Local model (smollm2:135m), Streaming

| Run | TTFB (time to headers) | Total Time | Content Received |
|-----|----------------------|-----------|-----------------|
| 1   | 1.390s               | 1.390s    | 0 chars (BROKEN) |
| 2   | 1.283s               | 1.283s    | 0 chars (BROKEN) |
| 3   | 1.390s               | 1.390s    | 0 chars (BROKEN) |
| **Min** | **1.283s** | 1.283s | — |
| **Avg** | **1.354s** | 1.354s | — |
| **Max** | **1.390s** | 1.390s | — |

**Status:** BROKEN. Server responds HTTP 200 with `Content-Type: text/event-stream` but `Content-Length: 0` and empty body. No SSE chunks are ever delivered.

---

### TEST 3 — Cloud model (qwen3-vl:235b-instruct-cloud), Non-Streaming

| Run | Total Time | Response Length |
|-----|-----------|----------------|
| 1   | 5.291s    | 531 chars       |
| 2   | 7.557s    | 557 chars       |
| 3   | 5.001s    | 557 chars       |
| **Min** | **5.001s** | — |
| **Avg** | **5.950s** | ~548 chars |
| **Max** | **7.557s** | — |

Sample response: "Quantum computing uses quantum bits, or qubits, which can exist in superpositions of 0 and 1 simultaneously, unlike classical bits that are strictly 0 or 1. These qubits leverage quantum phenomena like entanglement and interference to perform complex calculations exponentially faster for certain problems, such as factoring large numbers or simulating molecular structures. While still in early development, quantum computers hold transformative potential for fields like cryptography, drug discovery, and artificial intelligence."

---

### TEST 4 — Cloud model (qwen3-vl:235b-instruct-cloud), Streaming

| Run | TTFB (time to headers) | Total Time | Content Received |
|-----|----------------------|-----------|-----------------|
| 1   | 2.671s               | 2.671s    | 0 chars (BROKEN) |
| 2   | 1.951s               | 1.951s    | 0 chars (BROKEN) |
| 3   | 2.220s               | 2.220s    | 0 chars (BROKEN) |
| **Min** | **1.951s** | 1.951s | — |
| **Avg** | **2.281s** | 2.281s | — |
| **Max** | **2.671s** | 2.671s | — |

**Status:** BROKEN. Same as Test 2 — empty SSE body.

---

## Critical Finding: Streaming is Broken

All streaming tests returned HTTP 200 with these headers:
```
Content-Type: text/event-stream; charset=UTF-8
Content-Length: 0
```

The body is completely empty (0 bytes). No SSE `data:` chunks are delivered.

**Root cause (infrastructure):** The LiteSpeed web server and/or Cloudflare proxy layer in front of the Laravel application is buffering or discarding the streaming response body before it reaches the client. LiteSpeed is known to buffer responses and can interfere with SSE/chunked-transfer streaming unless specifically configured to allow it.

**Evidence:** The `x-turbo-charged-by: LiteSpeed` response header appears on all requests. LiteSpeed requires explicit configuration to pass through streaming responses without buffering.

---

## Local vs Cloud Comparison (Non-Streaming Only)

| Metric | Local (smollm2:135m) | Cloud (qwen3-vl:235b) | Difference |
|--------|---------------------|----------------------|------------|
| Min total time | 1.221s | 5.001s | Cloud is 4.1x slower (min) |
| Avg total time | 1.242s | 5.950s | Cloud is 4.8x slower (avg) |
| Max total time | 1.279s | 7.557s | Cloud is 5.9x slower (max) |
| Response length | ~332 chars | ~548 chars | Cloud gives 65% more content |
| Response quality | Simple/brief | Detailed/structured | Cloud significantly better |
| Latency variance | Low (58ms range) | High (2.556s range) | Cloud is less consistent |

**Interpretation:** The local model is ~5x faster for time-to-complete, but the cloud model produces substantially better-quality, more complete responses. For use cases where response quality matters, the cloud latency is the cost of that quality.

---

## Recommendations

1. **Fix streaming (high priority):** Add LiteSpeed `X-LiteSpeed-Cache-Control: no-store` header and configure the web server to pass through chunked/SSE responses without buffering. This is likely a one-line change in the `.htaccess` or LiteSpeed config. Until fixed, users receive no benefit from `stream: true`.

2. **Local model is viable for latency-sensitive tasks:** At ~1.2s average, smollm2:135m is fast enough for interactive use. Trade-off is shorter, simpler responses.

3. **Cloud model latency spikes:** The 7.5s max vs 5.0s min for the cloud model suggests occasional queue/cold-start delays. Consider monitoring p95 latency and surfacing loading indicators in the UI.
