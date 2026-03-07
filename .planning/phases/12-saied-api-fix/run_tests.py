#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Phase 12 API and Route Tests for llmdev.resayil.io
"""

import sys
import io
import requests
import json
import time
from datetime import datetime

# Force UTF-8 output on Windows
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

BASE_URL = "https://llmdev.resayil.io"
API_KEY = "9c314b287672145bd66dfe35323b5453d4dab477da39d608997cf89eb8782bb1"
TIMEOUT = 120

results = []

def record(name, passed, status_code=None, notes="", response_preview=""):
    status = "PASS" if passed else "FAIL"
    results.append({
        "name": name,
        "status": status,
        "status_code": status_code,
        "notes": notes,
        "response_preview": response_preview[:300] if response_preview else ""
    })
    code_str = f" [{status_code}]" if status_code is not None else ""
    print(f"  [{status}]{code_str} {name}")
    if notes:
        print(f"         {notes}")
    if response_preview:
        preview = response_preview[:200]
        print(f"         Preview: {preview}")
    print()

def safe_get(url, headers=None, timeout=30):
    try:
        r = requests.get(url, headers=headers, timeout=timeout, allow_redirects=True)
        return r, None
    except Exception as e:
        return None, str(e)

def safe_post(url, headers=None, json_body=None, stream=False, timeout=120):
    try:
        r = requests.post(url, headers=headers, json=json_body, stream=stream, timeout=timeout)
        return r, None
    except Exception as e:
        return None, str(e)

auth_headers = {"Authorization": f"Bearer {API_KEY}"}
bad_auth_headers = {"Authorization": "Bearer invalid_key_12345"}

print("=" * 60)
print("Phase 12 Dev API Tests")
print(f"Target: {BASE_URL}")
print(f"Started: {datetime.now().isoformat()}")
print("=" * 60)
print()

# ─────────────────────────────────────────────────────────────
print("## 1. ROUTING TESTS (no auth)")
print("-" * 40)

# GET /v1/models → expect 401
r, err = safe_get(f"{BASE_URL}/v1/models")
if err:
    record("GET /v1/models (no auth) → route exists", False, notes=f"Error: {err}")
else:
    record("GET /v1/models (no auth) → expect 401", r.status_code == 401, r.status_code, response_preview=r.text)

# GET /api/v1/models → expect 401
r, err = safe_get(f"{BASE_URL}/api/v1/models")
if err:
    record("GET /api/v1/models (no auth) → route exists", False, notes=f"Error: {err}")
else:
    record("GET /api/v1/models (no auth) → expect 401", r.status_code == 401, r.status_code, response_preview=r.text)

# GET /faq → expect 200
r, err = safe_get(f"{BASE_URL}/faq")
if err:
    record("GET /faq → expect 200", False, notes=f"Error: {err}")
else:
    record("GET /faq → expect 200", r.status_code == 200, r.status_code, response_preview=r.text[:200])

# GET /features → expect 200
r, err = safe_get(f"{BASE_URL}/features")
if err:
    record("GET /features → expect 200", False, notes=f"Error: {err}")
else:
    record("GET /features → expect 200", r.status_code == 200, r.status_code, response_preview=r.text[:200])

# GET /docs → expect 200
r, err = safe_get(f"{BASE_URL}/docs")
if err:
    record("GET /docs → expect 200", False, notes=f"Error: {err}")
else:
    record("GET /docs → expect 200", r.status_code == 200, r.status_code, response_preview=r.text[:200])

# GET /docs/authentication → expect 200
r, err = safe_get(f"{BASE_URL}/docs/authentication")
if err:
    record("GET /docs/authentication → expect 200", False, notes=f"Error: {err}")
else:
    record("GET /docs/authentication → expect 200", r.status_code == 200, r.status_code, response_preview=r.text[:200])

# ─────────────────────────────────────────────────────────────
print("## 2. API AUTHENTICATION TESTS")
print("-" * 40)

# Valid key
r, err = safe_get(f"{BASE_URL}/v1/models", headers=auth_headers)
if err:
    record("GET /v1/models with valid key → expect 200", False, notes=f"Error: {err}")
else:
    passed = r.status_code == 200
    preview = r.text[:300]
    has_models = "data" in r.text or "model" in r.text.lower()
    record("GET /v1/models with valid key → expect 200 + model list", passed and has_models, r.status_code,
           notes="Has model data: " + str(has_models), response_preview=preview)

# Invalid key
r, err = safe_get(f"{BASE_URL}/v1/models", headers=bad_auth_headers)
if err:
    record("GET /v1/models with invalid key → expect 401", False, notes=f"Error: {err}")
else:
    record("GET /v1/models with invalid key → expect 401", r.status_code == 401, r.status_code, response_preview=r.text)

# /api/v1/models with valid key
r, err = safe_get(f"{BASE_URL}/api/v1/models", headers=auth_headers)
if err:
    record("GET /api/v1/models with valid key → expect 200", False, notes=f"Error: {err}")
else:
    passed = r.status_code == 200
    record("GET /api/v1/models with valid key → expect 200", passed, r.status_code, response_preview=r.text[:300])

# ─────────────────────────────────────────────────────────────
print("## 3. STREAMING TEST (main Phase 12 fix)")
print("-" * 40)

stream_body = {
    "model": "smollm2:135m",
    "messages": [{"role": "user", "content": "Say hello in one word"}],
    "stream": True,
    "max_tokens": 50
}

stream_headers = {**auth_headers, "Content-Type": "application/json", "Accept": "text/event-stream"}

print("  Initiating streaming request (timeout=120s)...")
start_time = time.time()
r, err = safe_post(f"{BASE_URL}/api/v1/chat/completions", headers=stream_headers, json_body=stream_body, stream=True, timeout=120)

if err:
    record("POST /api/v1/chat/completions stream:true → event-stream", False, notes=f"Error: {err}")
else:
    status_code = r.status_code
    content_type = r.headers.get("Content-Type", "")
    is_event_stream = "event-stream" in content_type or "text/event-stream" in content_type

    print(f"  HTTP {status_code} | Content-Type: {content_type}")

    if status_code != 200:
        body = r.text[:500]
        record("POST /api/v1/chat/completions stream:true → 200", False, status_code,
               notes=f"Content-Type: {content_type}", response_preview=body)
        record("stream:true → text/event-stream content-type", False, status_code, notes="Request failed")
        record("stream:true → first chunk within 30s", False, status_code, notes="Request failed")
        record("stream:true → actual data chunks (not empty)", False, status_code, notes="Request failed")
    else:
        record("POST /api/v1/chat/completions stream:true → 200", True, status_code,
               notes=f"Content-Type: {content_type}")
        record("stream:true → text/event-stream content-type", is_event_stream, status_code,
               notes=f"Content-Type: {content_type}")

        # Read chunks
        chunks = []
        first_chunk_time = None
        chunk_text_accumulated = ""

        try:
            for chunk in r.iter_content(chunk_size=None):
                if chunk:
                    if first_chunk_time is None:
                        first_chunk_time = time.time() - start_time
                    chunk_text_accumulated += chunk.decode("utf-8", errors="replace")
                    chunks.append(chunk)
                    if len(chunks) >= 5:
                        # Got enough to verify
                        break
        except Exception as e:
            print(f"  Chunk read error: {e}")

        elapsed = time.time() - start_time
        has_chunks = len(chunks) > 0
        first_chunk_ok = first_chunk_time is not None and first_chunk_time < 30

        record("stream:true → first chunk within 30s", first_chunk_ok, status_code,
               notes=f"First chunk at {first_chunk_time:.1f}s" if first_chunk_time else "No chunks received")
        record("stream:true → actual data chunks (not empty body)", has_chunks, status_code,
               notes=f"Received {len(chunks)} chunks in {elapsed:.1f}s",
               response_preview=chunk_text_accumulated[:300])

# ─────────────────────────────────────────────────────────────
print("## 4. NON-STREAMING TEST")
print("-" * 40)

nonstream_body = {
    "model": "smollm2:135m",
    "messages": [{"role": "user", "content": "Say hello in one word"}],
    "stream": False,
    "max_tokens": 50
}

nonstream_headers = {**auth_headers, "Content-Type": "application/json"}
print("  Initiating non-streaming request (timeout=120s)...")
r, err = safe_post(f"{BASE_URL}/api/v1/chat/completions", headers=nonstream_headers, json_body=nonstream_body, stream=False, timeout=120)

if err:
    record("POST /api/v1/chat/completions stream:false → 200 + JSON", False, notes=f"Error: {err}")
else:
    status_code = r.status_code
    if status_code == 200:
        try:
            data = r.json()
            has_choices = "choices" in data
            has_content = has_choices and len(data["choices"]) > 0
            valid = has_choices and has_content
            preview = json.dumps(data)[:300]
            record("POST /api/v1/chat/completions stream:false → 200 + valid JSON", valid, status_code,
                   notes=f"has choices: {has_choices}, has content: {has_content}", response_preview=preview)
        except Exception as e:
            record("POST /api/v1/chat/completions stream:false → 200 + valid JSON", False, status_code,
                   notes=f"JSON parse error: {e}", response_preview=r.text[:300])
    else:
        record("POST /api/v1/chat/completions stream:false → 200 + valid JSON", False, status_code,
               response_preview=r.text[:300])

# ─────────────────────────────────────────────────────────────
print("## 5. CREDIT / MODEL LIST CHECK")
print("-" * 40)

r, err = safe_get(f"{BASE_URL}/v1/models", headers=auth_headers)
if err:
    record("GET /v1/models includes qwen3-vl:235b-instruct", False, notes=f"Error: {err}")
else:
    if r.status_code == 200:
        text = r.text
        has_qwen = "qwen3-vl" in text.lower() or "qwen" in text.lower()
        has_235b = "235b" in text.lower()
        has_target = "qwen3-vl:235b" in text.lower() or (has_qwen and has_235b)
        try:
            data = r.json()
            model_ids = [m.get("id", "") for m in data.get("data", [])]
            model_list_preview = ", ".join(model_ids[:10])
        except:
            model_list_preview = text[:200]
        record("GET /v1/models includes qwen3-vl:235b-instruct", has_target, r.status_code,
               notes=f"qwen present: {has_qwen}, 235b present: {has_235b}",
               response_preview=f"Models: {model_list_preview}")
    else:
        record("GET /v1/models includes qwen3-vl:235b-instruct", False, r.status_code, response_preview=r.text[:200])

# ─────────────────────────────────────────────────────────────
print()
print("=" * 60)
print("SUMMARY")
print("=" * 60)

passed_count = sum(1 for r in results if r["status"] == "PASS")
failed_count = sum(1 for r in results if r["status"] == "FAIL")
total = len(results)

print(f"Total:  {total}")
print(f"PASS:   {passed_count}")
print(f"FAIL:   {failed_count}")
print()

if failed_count > 0:
    print("FAILURES:")
    for r in results:
        if r["status"] == "FAIL":
            code_str = f" [{r['status_code']}]" if r['status_code'] is not None else ""
            print(f"  - {r['name']}{code_str}")
            if r['notes']:
                print(f"    {r['notes']}")

# ─────────────────────────────────────────────────────────────
# Write markdown report
md_lines = [
    "# Phase 12 Dev Test Results",
    "",
    f"**Date:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}",
    f"**Target:** {BASE_URL}",
    f"**Total:** {total} | **PASS:** {passed_count} | **FAIL:** {failed_count}",
    "",
]

sections = [
    ("1. ROUTING TESTS", ["GET /v1/models (no auth)", "GET /api/v1/models (no auth)", "GET /faq", "GET /features", "GET /docs", "GET /docs/authentication"]),
    ("2. API AUTHENTICATION TESTS", ["GET /v1/models with valid key", "GET /v1/models with invalid key", "GET /api/v1/models with valid key"]),
    ("3. STREAMING TEST", ["stream:true → 200", "text/event-stream", "first chunk within 30s", "actual data chunks"]),
    ("4. NON-STREAMING TEST", ["stream:false"]),
    ("5. CREDIT / MODEL LIST CHECK", ["qwen3-vl"]),
]

for section_title, keywords in sections:
    md_lines.append(f"## {section_title}")
    md_lines.append("")
    md_lines.append("| Test | Result | Status Code | Notes |")
    md_lines.append("|------|--------|-------------|-------|")
    for result in results:
        match = any(kw.lower() in result["name"].lower() for kw in keywords)
        if match:
            icon = "PASS" if result["status"] == "PASS" else "FAIL"
            code = str(result["status_code"]) if result["status_code"] is not None else "-"
            notes = result["notes"].replace("|", "/") if result["notes"] else ""
            md_lines.append(f"| {result['name']} | {icon} | {code} | {notes} |")
    md_lines.append("")

md_lines.append("## Raw Results")
md_lines.append("")
md_lines.append("```")
for result in results:
    icon = "PASS" if result["status"] == "PASS" else "FAIL"
    code_str = f" [{result['status_code']}]" if result["status_code"] is not None else ""
    md_lines.append(f"[{icon}]{code_str} {result['name']}")
    if result["notes"]:
        md_lines.append(f"  Notes: {result['notes']}")
    if result["response_preview"]:
        md_lines.append(f"  Preview: {result['response_preview'][:200]}")
md_lines.append("```")

report_path = r"C:\Users\User\OneDrive - City Travelers\LLM-Resayil\.planning\phases\12-saied-api-fix\dev-test-results.md"
with open(report_path, "w", encoding="utf-8") as f:
    f.write("\n".join(md_lines))

print(f"\nReport written to: {report_path}")
