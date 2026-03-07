---
test_date: 2026-03-07
model: qwen3-vl:235b-instruct-cloud
base_url: https://llm.resayil.io/api
concurrent_users: 4
context_size: ~20k tokens (90,000 chars / ~18,000 words)
---

## Setup Notes

### Endpoint Discovery

The instructions specified `https://llm.resayil.io` as base URL with `/v1/chat/completions`.
That path returned 404 (an Arabic-language custom error page from Cloudflare/LiteSpeed).

Correct endpoint: `https://llm.resayil.io/api/v1/chat/completions`

The `/api` prefix is required. All results below use the corrected URL.

---

## Wave 1: 4 Concurrent Non-Streaming Requests

**Context:** ~20k tokens system prompt ("The quick brown fox..." x 2000) + short user question
**max_tokens:** 50
**All 4 threads started simultaneously**

| User | HTTP Status | Response Time | Answer |
|------|-------------|---------------|--------|
| 1    | 200         | 9.32s         | "4"    |
| 2    | 200         | 7.22s         | "4"    |
| 3    | 200         | 6.87s         | "4"    |
| 4    | 200         | 6.69s         | "4"    |

**Wall time (all 4 complete):** 9.32s
**Sum of individual times:** 30.10s
**Average per request:** 7.53s
**Max individual:** 9.32s (User 1)
**Min individual:** 6.69s (User 4)

### Parallelism Analysis

- Parallelism ratio: 30.10 / 9.32 = **3.23x**
- If fully serialized, wall time would equal sum = ~30s
- Actual wall time 9.32s confirms **requests ran concurrently**
- Verdict: **HIGHLY PARALLEL** — all 4 requests processed simultaneously

### Token Usage (from response JSON)

All users reported `prompt_tokens: ~20,027` confirming the full 20k-token context was received and processed.
Completion tokens: 1-2 (just the digit "4").

### Observations

- No 429 rate-limit errors
- No 500 server errors
- No timeouts
- All 4 completed successfully
- Spread of ~2.6s between fastest (6.69s) and slowest (9.32s) — consistent with normal queue variance under concurrent load

---

## Wave 2: 4 Concurrent Streaming Requests

**Context:** Same ~20k tokens + short user question ("What is 3+3?")
**max_tokens:** 20
**All 4 threads started simultaneously**

### Critical Finding: Streaming Body is Empty

Raw inspection of the streaming endpoint reveals:

```
Content-Type: text/event-stream; charset=utf-8
Content-Length: 0
```

The server responds HTTP 200 with the correct SSE content-type header but sends **zero bytes** in the response body. The connection closes immediately after headers.

This means:
- TTFB cannot be measured (no bytes arrive)
- Streamed content is empty for all users
- The issue is server-side: streaming mode is not functioning on this endpoint

### Wave 2 Timing (connection overhead only, no actual model output)

| User | HTTP Status | TTFB     | Total Time | Answer |
|------|-------------|----------|------------|--------|
| 1    | 200         | N/A (0B) | 3.50s      | (empty)|
| 2    | 200         | N/A (0B) | 3.44s      | (empty)|
| 3    | 200         | N/A (0B) | 6.86s      | (empty)|
| 4    | 200         | N/A (0B) | 6.31s      | (empty)|

**Wall time:** 6.87s
**Sum of individual times:** 20.11s
**Parallelism ratio:** 2.93x — requests fired in parallel but server closed streams rapidly

The "total time" here reflects time until the empty stream closed, not model inference time.
Two users got empty responses in ~3.5s, two in ~6.5s — two-cluster pattern suggests the server may be processing pairs under the hood before returning empty bodies.

### Second Streaming Run (TTFB recheck with iter_content byte-level)

| User | TTFB     | Total  |
|------|----------|--------|
| 1    | N/A (0B) | 2.845s |
| 2    | N/A (0B) | 5.682s |
| 3    | N/A (0B) | 3.050s |
| 4    | N/A (0B) | 4.699s |

Wall time: 5.687s — confirmed streaming body is consistently empty across runs.

---

## Summary

### Did All 4 Complete?

**Non-streaming:** Yes. All 4 returned HTTP 200 with correct answers.
**Streaming:** All 4 returned HTTP 200, but with empty bodies — streaming is broken server-side.

### Rate Limits / Errors?

None. No 429, no 500, no timeouts. The endpoint handled 4 concurrent ~20k-token requests without complaint.

### Request Times (non-streaming)

| Metric     | Value  |
|------------|--------|
| Fastest    | 6.69s  |
| Slowest    | 9.32s  |
| Average    | 7.53s  |
| Spread     | 2.63s  |

### Total Wall Time

- Non-streaming 4x concurrent: **9.32s**
- Streaming 4x concurrent: **6.87s** (but model didn't run — empty body)

### Truly Parallel or Serialized?

**Non-streaming: Truly parallel.** Parallelism ratio 3.23x (vs 4.0x theoretical perfect). The server handles multiple simultaneous ~20k-token inference requests concurrently without queuing them sequentially.

### Queue Errors / Timeouts?

None observed.

### Streaming Bug

`stream: true` requests return `HTTP 200` + `Content-Type: text/event-stream` but `Content-Length: 0` — body is empty. This is a server/proxy bug, not a rate limit or queue issue. Non-streaming mode works correctly and should be used until streaming is repaired.

---

## Endpoint Reference

| Parameter | Value |
|-----------|-------|
| Working base URL | `https://llm.resayil.io/api` |
| Chat completions | `/v1/chat/completions` |
| Non-streaming | Works correctly |
| Streaming | Returns 200 but empty body (broken) |
| Auth header | `Authorization: Bearer <key>` |
| Model ID | `qwen3-vl:235b-instruct-cloud` |
