# LLM Context Size Performance Test Results

**Model:** `qwen3-vl:235b-instruct-cloud`
**Endpoint:** `https://llm.resayil.io/api/v1/chat/completions`
**Run Date:** 2026-03-07 18:41:13
**Total Run Time:** 78.6s

## Summary Table

| Context Size | Approx Tokens | Prompt Tokens (actual) | Context Chars | HTTP Status | Total Time (s) | Response Preview |
|---|---|---|---|---|---|---|
| Test 1 (~10k tokens) | ~10,000 | 21149 | 124,544 | 200 | 6.98s | The repetitive sentence “The quick brown fox jumps over the lazy dog” is commonl |
| Test 2 (~50k tokens) | ~50,000 | 105585 | 622,272 | 200 | 50.74s | The repeated sentence "The quick brown fox jumps over the lazy dog" is used to t |
| Test 3 (~100k tokens) | ~100,000 | N/A | 1,244,544 | 503 | 10.51s | {'message': 'Failed to connect to Ollama service', 'code': 503} |
| Test 4 (~128k tokens) | ~128,000 | N/A | 1,592,864 | 503 | 10.36s | {'message': 'Failed to connect to Ollama service', 'code': 503} |

## Detailed Results

### Test 1 (~10k tokens)

- **Repeats:** 556
- **Approx Tokens (estimated):** ~10,000
- **Actual Prompt Tokens:** 21149
- **Actual Completion Tokens:** 41
- **Actual Total Tokens:** 21190
- **Context Chars:** 124,544
- **HTTP Status:** 200
- **Total Time:** 6.98s
- **Credits Header:** N/A
- **Response Preview:** `The repetitive sentence “The quick brown fox jumps over the lazy dog” is commonly used to test keyboards and fonts, and scientists have found such repetitive text patterns useful for measuring token t`

### Test 2 (~50k tokens)

- **Repeats:** 2,778
- **Approx Tokens (estimated):** ~50,000
- **Actual Prompt Tokens:** 105585
- **Actual Completion Tokens:** 42
- **Actual Total Tokens:** 105627
- **Context Chars:** 622,272
- **HTTP Status:** 200
- **Total Time:** 50.74s
- **Credits Header:** N/A
- **Response Preview:** `The repeated sentence "The quick brown fox jumps over the lazy dog" is used to test keyboards and fonts, and scientists have found that such repetitive text patterns are useful for measuring token thr`

### Test 3 (~100k tokens)

- **Repeats:** 5,556
- **Approx Tokens (estimated):** ~100,000
- **Actual Prompt Tokens:** N/A
- **Actual Completion Tokens:** N/A
- **Actual Total Tokens:** N/A
- **Context Chars:** 1,244,544
- **HTTP Status:** 503
- **Total Time:** 10.51s
- **Credits Header:** N/A
- **Response Preview:** `{'message': 'Failed to connect to Ollama service', 'code': 503}`

### Test 4 (~128k tokens)

- **Repeats:** 7,111
- **Approx Tokens (estimated):** ~128,000
- **Actual Prompt Tokens:** N/A
- **Actual Completion Tokens:** N/A
- **Actual Total Tokens:** N/A
- **Context Chars:** 1,592,864
- **HTTP Status:** 503
- **Total Time:** 10.36s
- **Credits Header:** N/A
- **Response Preview:** `{'message': 'Failed to connect to Ollama service', 'code': 503}`

## Notes

- TTFB not separately measured (stream=False, single elapsed covers full round trip)
- Timing includes: request serialization + network transit + model inference + response transfer
- `/v1/credits` endpoint not available; token usage parsed from response `usage` field
- Paragraph unit used for context: ~18 tokens each