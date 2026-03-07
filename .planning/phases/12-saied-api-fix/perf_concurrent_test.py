"""
Concurrent performance test: 4 simultaneous users hitting qwen3-vl:235b-instruct-cloud
Wave 1: non-streaming (measure total response time)
Wave 2: streaming (measure TTFB per request)
"""

import requests
import time
import threading
import json
import sys

API_KEY = "dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50"
BASE_URL = "https://llm.resayil.io/api"
MODEL = "qwen3-vl:235b-instruct-cloud"

# ~20k tokens: "The quick brown fox..." repeated 1500 times is ~9000 words ≈ 12-15k tokens
# Use 2000 repetitions to safely hit ~20k tokens
CONTEXT_REPEAT = 2000
CONTEXT = "The quick brown fox jumps over the lazy dog. " * CONTEXT_REPEAT

print(f"Context size: ~{len(CONTEXT)} chars, ~{len(CONTEXT.split())} words", flush=True)
print("=" * 70, flush=True)

# ─────────────────────────────────────────────────────────────────────────────
# WAVE 1: Non-streaming, 4 concurrent users
# ─────────────────────────────────────────────────────────────────────────────

wave1_results = {}

def make_request_non_stream(user_id):
    messages = [
        {"role": "system", "content": CONTEXT},
        {"role": "user", "content": f"User {user_id}: What is 2+2? Answer briefly."}
    ]
    start = time.time()
    try:
        resp = requests.post(
            f"{BASE_URL}/v1/chat/completions",
            headers={
                "Authorization": f"Bearer {API_KEY}",
                "Content-Type": "application/json"
            },
            json={
                "model": MODEL,
                "messages": messages,
                "stream": False,
                "max_tokens": 50
            },
            timeout=300
        )
        elapsed = time.time() - start
        wave1_results[user_id] = {
            "status": resp.status_code,
            "time": round(elapsed, 2),
            "response_snippet": resp.text[:300],
            "error": None
        }
        # Try to extract answer text
        try:
            body = resp.json()
            content = body["choices"][0]["message"]["content"]
            wave1_results[user_id]["answer"] = content.strip()
        except Exception:
            wave1_results[user_id]["answer"] = "(could not parse)"
    except requests.exceptions.Timeout:
        elapsed = time.time() - start
        wave1_results[user_id] = {
            "status": "TIMEOUT",
            "time": round(elapsed, 2),
            "response_snippet": "",
            "error": "Request timed out after 300s",
            "answer": None
        }
    except Exception as e:
        elapsed = time.time() - start
        wave1_results[user_id] = {
            "status": "ERROR",
            "time": round(elapsed, 2),
            "response_snippet": "",
            "error": str(e),
            "answer": None
        }

print("\nWAVE 1: 4 concurrent non-streaming requests", flush=True)
print("Firing all 4 threads simultaneously...", flush=True)

threads_w1 = [threading.Thread(target=make_request_non_stream, args=(i,)) for i in range(1, 5)]
wall_start_w1 = time.time()
for t in threads_w1:
    t.start()
for t in threads_w1:
    t.join()
wall_total_w1 = round(time.time() - wall_start_w1, 2)

print(f"Wave 1 complete. Wall time: {wall_total_w1}s", flush=True)
for uid in sorted(wave1_results):
    r = wave1_results[uid]
    print(f"  User {uid}: status={r['status']} time={r['time']}s answer={r.get('answer', '?')}", flush=True)

# ─────────────────────────────────────────────────────────────────────────────
# WAVE 2: Streaming, 4 concurrent users — measure TTFB
# ─────────────────────────────────────────────────────────────────────────────

wave2_results = {}

def make_request_stream(user_id):
    messages = [
        {"role": "system", "content": CONTEXT},
        {"role": "user", "content": f"User {user_id}: What is 3+3? Answer briefly."}
    ]
    start = time.time()
    ttfb = None
    full_content = []
    try:
        with requests.post(
            f"{BASE_URL}/v1/chat/completions",
            headers={
                "Authorization": f"Bearer {API_KEY}",
                "Content-Type": "application/json"
            },
            json={
                "model": MODEL,
                "messages": messages,
                "stream": True,
                "max_tokens": 50
            },
            timeout=300,
            stream=True
        ) as resp:
            http_status = resp.status_code
            for line in resp.iter_lines():
                if line:
                    if ttfb is None:
                        ttfb = round(time.time() - start, 2)
                    line_str = line.decode("utf-8") if isinstance(line, bytes) else line
                    if line_str.startswith("data: "):
                        data_str = line_str[6:]
                        if data_str.strip() == "[DONE]":
                            break
                        try:
                            chunk = json.loads(data_str)
                            delta = chunk["choices"][0]["delta"].get("content", "")
                            if delta:
                                full_content.append(delta)
                        except Exception:
                            pass

        elapsed = round(time.time() - start, 2)
        wave2_results[user_id] = {
            "status": http_status,
            "ttfb": ttfb,
            "total_time": elapsed,
            "answer": "".join(full_content).strip(),
            "error": None
        }
    except requests.exceptions.Timeout:
        elapsed = round(time.time() - start, 2)
        wave2_results[user_id] = {
            "status": "TIMEOUT",
            "ttfb": ttfb,
            "total_time": elapsed,
            "answer": None,
            "error": "Request timed out after 300s"
        }
    except Exception as e:
        elapsed = round(time.time() - start, 2)
        wave2_results[user_id] = {
            "status": "ERROR",
            "ttfb": ttfb,
            "total_time": elapsed,
            "answer": None,
            "error": str(e)
        }

# Brief pause between waves
print("\nPausing 5s between waves...", flush=True)
time.sleep(5)

print("\nWAVE 2: 4 concurrent streaming requests (measuring TTFB)", flush=True)
print("Firing all 4 threads simultaneously...", flush=True)

threads_w2 = [threading.Thread(target=make_request_stream, args=(i,)) for i in range(1, 5)]
wall_start_w2 = time.time()
for t in threads_w2:
    t.start()
for t in threads_w2:
    t.join()
wall_total_w2 = round(time.time() - wall_start_w2, 2)

print(f"Wave 2 complete. Wall time: {wall_total_w2}s", flush=True)
for uid in sorted(wave2_results):
    r = wave2_results[uid]
    print(f"  User {uid}: status={r['status']} ttfb={r['ttfb']}s total={r['total_time']}s answer={r.get('answer', '?')}", flush=True)

# ─────────────────────────────────────────────────────────────────────────────
# Parallelism analysis
# ─────────────────────────────────────────────────────────────────────────────

def analyze_parallelism(results, wall_time, label):
    times = [r["time"] if "time" in r else r.get("total_time", 0) for r in results.values()]
    times_valid = [t for t in times if isinstance(t, (int, float))]
    if not times_valid:
        return f"{label}: no valid times"
    sum_times = sum(times_valid)
    avg_time = sum_times / len(times_valid)
    max_time = max(times_valid)
    # If truly parallel: wall_time ≈ max_time
    # If serialized: wall_time ≈ sum_times
    if wall_time < 1:
        ratio = "N/A"
        verdict = "Too fast to measure"
    else:
        ratio = round(sum_times / wall_time, 2)
        if ratio > 3.0:
            verdict = "HIGHLY PARALLEL (ratio >> 1 means requests ran concurrently)"
        elif ratio > 1.5:
            verdict = "PARTIALLY PARALLEL"
        else:
            verdict = "MOSTLY SERIALIZED (ratio ≈ 1 means requests ran one-by-one)"
    return {
        "label": label,
        "individual_times": times_valid,
        "sum_individual": round(sum_times, 2),
        "wall_time": wall_time,
        "avg_time": round(avg_time, 2),
        "max_time": round(max_time, 2),
        "parallelism_ratio": ratio,
        "verdict": verdict
    }

w1_analysis = analyze_parallelism(wave1_results, wall_total_w1, "Wave 1 (non-streaming)")
w2_analysis = analyze_parallelism(wave2_results, wall_total_w2, "Wave 2 (streaming)")

# ─────────────────────────────────────────────────────────────────────────────
# Collect final data package
# ─────────────────────────────────────────────────────────────────────────────

output = {
    "test_config": {
        "model": MODEL,
        "base_url": BASE_URL,
        "concurrent_users": 4,
        "context_chars": len(CONTEXT),
        "context_approx_words": len(CONTEXT.split()),
        "max_tokens": 50
    },
    "wave1_non_streaming": {
        "wall_time_s": wall_total_w1,
        "results": wave1_results,
        "analysis": w1_analysis
    },
    "wave2_streaming": {
        "wall_time_s": wall_total_w2,
        "results": wave2_results,
        "analysis": w2_analysis
    }
}

print("\n\nFULL RESULTS JSON:", flush=True)
print(json.dumps(output, indent=2), flush=True)
