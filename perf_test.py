import requests
import time
import json
import sys
import io

# Force UTF-8 output to avoid Windows cp1252 issues
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')

api_key = "dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50"
base_url = "https://llm.resayil.io"
model = "qwen3-vl:235b-instruct-cloud"
# Correct endpoint discovered via probing
endpoint = f"{base_url}/api/v1/chat/completions"

def generate_context(repeat_count):
    paragraph = (
        "The quick brown fox jumps over the lazy dog. "
        "This sentence is commonly used to test keyboards and fonts. "
        "Scientists have discovered that repetitive text patterns are "
        "useful for measuring token throughput in language models. "
    )
    return paragraph * repeat_count

def test_context(label, context, repeat_count, approx_tokens):
    print(f"\n{'='*60}", flush=True)
    print(f"Running {label}", flush=True)
    print(f"Context repeats: {repeat_count:,}, approx tokens: ~{approx_tokens:,}", flush=True)
    print(f"Context length (chars): {len(context):,}", flush=True)
    print(f"Sending request at {time.strftime('%H:%M:%S')}...", flush=True)

    messages = [
        {"role": "system", "content": context},
        {"role": "user", "content": "Summarize this in one sentence."}
    ]

    payload = {
        "model": model,
        "messages": messages,
        "stream": False,
        "max_tokens": 200
    }

    send_start = time.time()
    try:
        resp = requests.post(
            endpoint,
            headers={
                "Authorization": f"Bearer {api_key}",
                "Content-Type": "application/json"
            },
            json=payload,
            timeout=300
        )
        total_elapsed = time.time() - send_start

        status = resp.status_code
        response_preview = ""
        usage_info = {}

        try:
            data = resp.json()
            if "choices" in data and data["choices"]:
                content = data["choices"][0].get("message", {}).get("content", "")
                response_preview = content[:200]
            elif "error" in data:
                response_preview = str(data["error"])[:200]
            elif "message" in data:
                response_preview = str(data["message"])[:200]

            if "usage" in data:
                usage_info = data["usage"]
        except Exception:
            response_preview = resp.text[:200]

        credits_header = resp.headers.get("X-Credits-Remaining", "N/A")

        print(f"HTTP Status: {status}", flush=True)
        print(f"Total time: {total_elapsed:.2f}s", flush=True)
        print(f"Credits header: {credits_header}", flush=True)
        print(f"Usage: {usage_info}", flush=True)
        print(f"Response preview: {response_preview[:200]}", flush=True)

        return {
            "label": label,
            "repeat_count": repeat_count,
            "approx_tokens": approx_tokens,
            "context_chars": len(context),
            "status": status,
            "total_time": total_elapsed,
            "credits_header": credits_header,
            "usage": usage_info,
            "response_preview": response_preview[:200],
        }

    except requests.exceptions.Timeout:
        elapsed = time.time() - send_start
        print(f"TIMEOUT after {elapsed:.1f}s", flush=True)
        return {
            "label": label,
            "repeat_count": repeat_count,
            "approx_tokens": approx_tokens,
            "context_chars": len(context),
            "status": "TIMEOUT",
            "total_time": elapsed,
            "credits_header": "N/A",
            "usage": {},
            "response_preview": "TIMEOUT",
        }
    except Exception as e:
        elapsed = time.time() - send_start
        print(f"ERROR: {e}", flush=True)
        return {
            "label": label,
            "repeat_count": repeat_count,
            "approx_tokens": approx_tokens,
            "context_chars": len(context),
            "status": "ERROR",
            "total_time": elapsed,
            "credits_header": "N/A",
            "usage": {},
            "response_preview": str(e)[:200],
        }

# ---- MAIN ----

print("LLM Context Size Performance Test", flush=True)
print(f"Model: {model}", flush=True)
print(f"Endpoint: {endpoint}", flush=True)
print(f"Started: {time.strftime('%Y-%m-%d %H:%M:%S')}", flush=True)

# Paragraph is ~80 chars, ~18 tokens. Each repeat = ~18 tokens.
# To hit target token counts:
# 10k tokens  -> 10000/18 ~ 556 repeats
# 50k tokens  -> 50000/18 ~ 2778 repeats
# 100k tokens -> 100000/18 ~ 5556 repeats
# 128k tokens -> 128000/18 ~ 7111 repeats

tests = [
    ("Test 1 (~10k tokens)",   556,   10000),
    ("Test 2 (~50k tokens)",  2778,   50000),
    ("Test 3 (~100k tokens)", 5556,  100000),
    ("Test 4 (~128k tokens)", 7111,  128000),
]

results = []
run_start = time.time()

for label, repeats, approx_tokens in tests:
    ctx = generate_context(repeats)
    result = test_context(label, ctx, repeats, approx_tokens)
    results.append(result)
    print(f"Cumulative elapsed: {time.time() - run_start:.1f}s", flush=True)

total_run_time = time.time() - run_start
print(f"\nAll tests complete. Total elapsed: {total_run_time:.1f}s", flush=True)

# ---- Write Results ----

output_path = r"C:\Users\User\OneDrive - City Travelers\LLM-Resayil\.planning\phases\12-saied-api-fix\perf-results-context-size.md"

lines = []
lines.append("# LLM Context Size Performance Test Results")
lines.append("")
lines.append(f"**Model:** `{model}`")
lines.append(f"**Endpoint:** `{endpoint}`")
lines.append(f"**Run Date:** {time.strftime('%Y-%m-%d %H:%M:%S')}")
lines.append(f"**Total Run Time:** {total_run_time:.1f}s")
lines.append("")
lines.append("## Summary Table")
lines.append("")
lines.append("| Context Size | Approx Tokens | Prompt Tokens (actual) | Context Chars | HTTP Status | Total Time (s) | Response Preview |")
lines.append("|---|---|---|---|---|---|---|")

for r in results:
    actual_prompt = r["usage"].get("prompt_tokens", "N/A")
    preview = r["response_preview"].replace("|", "\\|")[:80]
    lines.append(
        f"| {r['label']} | ~{r['approx_tokens']:,} | {actual_prompt} | {r['context_chars']:,} | {r['status']} | {r['total_time']:.2f}s | {preview} |"
    )

lines.append("")
lines.append("## Detailed Results")
lines.append("")

for r in results:
    lines.append(f"### {r['label']}")
    lines.append("")
    lines.append(f"- **Repeats:** {r['repeat_count']:,}")
    lines.append(f"- **Approx Tokens (estimated):** ~{r['approx_tokens']:,}")
    actual_prompt = r['usage'].get('prompt_tokens', 'N/A')
    actual_completion = r['usage'].get('completion_tokens', 'N/A')
    actual_total = r['usage'].get('total_tokens', 'N/A')
    lines.append(f"- **Actual Prompt Tokens:** {actual_prompt}")
    lines.append(f"- **Actual Completion Tokens:** {actual_completion}")
    lines.append(f"- **Actual Total Tokens:** {actual_total}")
    lines.append(f"- **Context Chars:** {r['context_chars']:,}")
    lines.append(f"- **HTTP Status:** {r['status']}")
    lines.append(f"- **Total Time:** {r['total_time']:.2f}s")
    lines.append(f"- **Credits Header:** {r['credits_header']}")
    lines.append(f"- **Response Preview:** `{r['response_preview'][:300]}`")
    lines.append("")

lines.append("## Notes")
lines.append("")
lines.append("- TTFB not separately measured (stream=False, single elapsed covers full round trip)")
lines.append("- Timing includes: request serialization + network transit + model inference + response transfer")
lines.append("- `/v1/credits` endpoint not available; token usage parsed from response `usage` field")
lines.append("- Paragraph unit used for context: ~18 tokens each")

output_text = "\n".join(lines)

with open(output_path, "w", encoding="utf-8") as f:
    f.write(output_text)

print(f"\nResults written to: {output_path}", flush=True)
print("\nFull summary:", flush=True)
for r in results:
    actual_prompt = r["usage"].get("prompt_tokens", "N/A")
    print(f"  {r['label']}: HTTP {r['status']}, {r['total_time']:.2f}s, prompt_tokens={actual_prompt}", flush=True)
