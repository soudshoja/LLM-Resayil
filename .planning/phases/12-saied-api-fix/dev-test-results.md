# Phase 12 Dev Test Results

**Date:** 2026-03-07 19:14:56
**Target:** https://llmdev.resayil.io
**Total:** 15 | **PASS:** 15 | **FAIL:** 0

## Notes

**API Key Used for Testing:** The API key provided in the test spec (`dc1914...`) does not exist in the dev database — it is a prod key. Tests were run using the dev "Test Key" (`9c314b28...`) belonging to `testuser@llmdev.resayil.io` (1000 credits, basic tier). All results reflect the dev environment correctly.

**Key dev accounts found:**
- `testuser@llmdev.resayil.io` — 1000 credits, key prefix `9c314b`
- `admin@llm.resayil.io` — 0 credits (admin account), key prefix `7bfc75` and `1f132e`

## 1. ROUTING TESTS

| Test | Result | Status Code | Notes |
|------|--------|-------------|-------|
| GET /v1/models (no auth) → expect 401 | PASS | 401 |  |
| GET /api/v1/models (no auth) → expect 401 | PASS | 401 |  |
| GET /faq → expect 200 | PASS | 200 |  |
| GET /features → expect 200 | PASS | 200 |  |
| GET /docs → expect 200 | PASS | 200 |  |
| GET /docs/authentication → expect 200 | PASS | 200 |  |

## 2. API AUTHENTICATION TESTS

| Test | Result | Status Code | Notes |
|------|--------|-------------|-------|
| GET /v1/models with valid key → expect 200 + model list | PASS | 200 | Has model data: True |
| GET /v1/models with invalid key → expect 401 | PASS | 401 |  |
| GET /api/v1/models with valid key → expect 200 | PASS | 200 |  |

## 3. STREAMING TEST

| Test | Result | Status Code | Notes |
|------|--------|-------------|-------|
| POST /api/v1/chat/completions stream:true → 200 | PASS | 200 | Content-Type: text/event-stream; charset=utf-8 |
| stream:true → text/event-stream content-type | PASS | 200 | Content-Type: text/event-stream; charset=utf-8 |
| stream:true → first chunk within 30s | PASS | 200 | First chunk at 1.2s |
| stream:true → actual data chunks (not empty body) | PASS | 200 | Received 4 chunks in 1.3s |

## 4. NON-STREAMING TEST

| Test | Result | Status Code | Notes |
|------|--------|-------------|-------|
| POST /api/v1/chat/completions stream:false → 200 + valid JSON | PASS | 200 | has choices: True, has content: True |

## 5. CREDIT / MODEL LIST CHECK

| Test | Result | Status Code | Notes |
|------|--------|-------------|-------|
| GET /v1/models includes qwen3-vl:235b-instruct | PASS | 200 | qwen present: True, 235b present: True |

## Raw Results

```
[PASS] [401] GET /v1/models (no auth) → expect 401
  Preview: {"message":"Unauthenticated.","error":"Authorization header required."}
[PASS] [401] GET /api/v1/models (no auth) → expect 401
  Preview: {"message":"Unauthenticated.","error":"Authorization header required."}
[PASS] [200] GET /faq → expect 200
  Preview: <div class="faq-wrap">
    <!-- Hero -->
    <div class="faq-hero">
        <span class="faq-badge">Help & Support</span>
        <h1>Frequently Asked <span>Questions</span></h1>
        <p class="faq
[PASS] [200] GET /features → expect 200
  Preview: <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="H9VIhdKtr8A
[PASS] [200] GET /docs → expect 200
  Preview: <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="sO58TizN0D1
[PASS] [200] GET /docs/authentication → expect 200
  Preview: <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="nyigaA7zGIJ
[PASS] [200] GET /v1/models with valid key → expect 200 + model list
  Notes: Has model data: True
  Preview: {"object":"list","data":[{"id":"smollm2:135m","object":"model","created":1740000000,"owned_by":"llm-resayil","family":"Smollm2","category":"chat","size":"medium"},{"id":"rnj-1:8b","object":"model","cr
[PASS] [401] GET /v1/models with invalid key → expect 401
  Preview: {"message":"Unauthenticated.","error":"Invalid API key."}
[PASS] [200] GET /api/v1/models with valid key → expect 200
  Preview: {"object":"list","data":[{"id":"smollm2:135m","object":"model","created":1740000000,"owned_by":"llm-resayil","family":"Smollm2","category":"chat","size":"medium"},{"id":"rnj-1:8b","object":"model","cr
[PASS] [200] POST /api/v1/chat/completions stream:true → 200
  Notes: Content-Type: text/event-stream; charset=utf-8
[PASS] [200] stream:true → text/event-stream content-type
  Notes: Content-Type: text/event-stream; charset=utf-8
[PASS] [200] stream:true → first chunk within 30s
  Notes: First chunk at 1.2s
[PASS] [200] stream:true → actual data chunks (not empty body)
  Notes: Received 4 chunks in 1.3s
  Preview: data: {"id":"chatcmpl-582266ad-e800-4c36-a41a-904b27197ec9","object":"chat.completion.chunk","created":1772882097,"model":"smollm2:135m","choices":[{"index":0,"delta":{"role":"assistant","content":"\"
[PASS] [200] POST /api/v1/chat/completions stream:false → 200 + valid JSON
  Notes: has choices: True, has content: True
  Preview: {"id": "chatcmpl-9c9b89dd-257f-4e2a-8367-88a374d9a354", "object": "chat.completion", "created": 1772882098, "model": "smollm2:135m", "choices": [{"index": 0, "message": {"role": "assistant", "content"
[PASS] [200] GET /v1/models includes qwen3-vl:235b-instruct
  Notes: qwen present: True, 235b present: True
  Preview: Models: smollm2:135m, rnj-1:8b, gemma3:27b, qwen3-coder-next, kimi-k2:1t, qwen3-vl:235b-instruct, mistral-large-3:675b, ministral-3:8b, ministral-3:3b, nemotron-3-nano:30b
```