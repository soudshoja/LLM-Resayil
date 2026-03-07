import requests
import time
import json

API_KEY = "dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50"
BASE_URL = "https://llm.resayil.io/api"
PROMPT = "Explain quantum computing in 3 sentences."
RUNS = 3


def test_non_streaming(model, run_num):
    print(f"  Run {run_num}...", end=" ", flush=True)
    start = time.time()
    try:
        resp = requests.post(
            f"{BASE_URL}/v1/chat/completions",
            headers={"Authorization": f"Bearer {API_KEY}", "Content-Type": "application/json"},
            json={
                "model": model,
                "messages": [{"role": "user", "content": PROMPT}],
                "stream": False
            },
            timeout=180
        )
        total = time.time() - start
        resp.raise_for_status()
        data = resp.json()
        content = data["choices"][0]["message"]["content"]
        length = len(content)
        print(f"done in {total:.2f}s, {length} chars")
        return total, length, content
    except Exception as e:
        total = time.time() - start
        print(f"ERROR after {total:.2f}s: {e}")
        return total, 0, f"ERROR: {e}"


def test_streaming(model, run_num):
    print(f"  Run {run_num}...", end=" ", flush=True)
    start = time.time()
    ttfb = None
    total_content = ""
    try:
        with requests.post(
            f"{BASE_URL}/v1/chat/completions",
            headers={"Authorization": f"Bearer {API_KEY}", "Content-Type": "application/json"},
            json={
                "model": model,
                "messages": [{"role": "user", "content": PROMPT}],
                "stream": True
            },
            stream=True,
            timeout=180
        ) as resp:
            resp.raise_for_status()
            for chunk in resp.iter_lines():
                if chunk:
                    if ttfb is None:
                        ttfb = time.time() - start
                    # Try to parse content from SSE
                    line = chunk.decode("utf-8") if isinstance(chunk, bytes) else chunk
                    if line.startswith("data: ") and line != "data: [DONE]":
                        try:
                            data = json.loads(line[6:])
                            delta = data["choices"][0].get("delta", {}).get("content", "")
                            if delta:
                                total_content += delta
                        except Exception:
                            pass
        total = time.time() - start
        print(f"TTFB={ttfb:.2f}s, total={total:.2f}s, {len(total_content)} chars")
        return ttfb, total, len(total_content)
    except Exception as e:
        total = time.time() - start
        ttfb = ttfb or total
        print(f"ERROR after {total:.2f}s: {e}")
        return ttfb, total, 0


def stats(values):
    if not values:
        return None, None, None
    return min(values), sum(values) / len(values), max(values)


def fmt(v):
    if v is None:
        return "N/A"
    return f"{v:.3f}s"


print("=" * 60)
print("LLM Performance Test: Streaming vs Non-Streaming")
print("=" * 60)
print()

results = {}

# TEST 1: Local model, non-streaming
model = "smollm2:135m"
print(f"TEST 1: Local model ({model}), non-streaming")
times = []
lengths = []
sample_response = ""
for i in range(1, RUNS + 1):
    t, l, content = test_non_streaming(model, i)
    times.append(t)
    lengths.append(l)
    if i == 1:
        sample_response = content
mn, avg, mx = stats(times)
avg_len = sum(lengths) / len(lengths) if lengths else 0
results["test1"] = {"min": mn, "avg": avg, "max": mx, "avg_len": avg_len, "sample": sample_response}
print(f"  => min={fmt(mn)}, avg={fmt(avg)}, max={fmt(mx)}, avg_length={avg_len:.0f} chars")
print()

# TEST 2: Local model, streaming
print(f"TEST 2: Local model ({model}), streaming")
ttfbs = []
totals = []
for i in range(1, RUNS + 1):
    ttfb, total, length = test_streaming(model, i)
    ttfbs.append(ttfb)
    totals.append(total)
ttfb_mn, ttfb_avg, ttfb_mx = stats(ttfbs)
tot_mn, tot_avg, tot_mx = stats(totals)
results["test2"] = {"ttfb_min": ttfb_mn, "ttfb_avg": ttfb_avg, "ttfb_max": ttfb_mx,
                    "tot_min": tot_mn, "tot_avg": tot_avg, "tot_max": tot_mx}
print(f"  => TTFB: min={fmt(ttfb_mn)}, avg={fmt(ttfb_avg)}, max={fmt(ttfb_mx)}")
print(f"  => Total: min={fmt(tot_mn)}, avg={fmt(tot_avg)}, max={fmt(tot_mx)}")
print()

# TEST 3: Cloud model, non-streaming
cloud_model = "qwen3-vl:235b-instruct-cloud"
print(f"TEST 3: Cloud model ({cloud_model}), non-streaming")
times = []
lengths = []
sample_response = ""
for i in range(1, RUNS + 1):
    t, l, content = test_non_streaming(cloud_model, i)
    times.append(t)
    lengths.append(l)
    if i == 1:
        sample_response = content
mn, avg, mx = stats(times)
avg_len = sum(lengths) / len(lengths) if lengths else 0
results["test3"] = {"min": mn, "avg": avg, "max": mx, "avg_len": avg_len, "sample": sample_response}
print(f"  => min={fmt(mn)}, avg={fmt(avg)}, max={fmt(mx)}, avg_length={avg_len:.0f} chars")
print()

# TEST 4: Cloud model, streaming
print(f"TEST 4: Cloud model ({cloud_model}), streaming")
ttfbs = []
totals = []
for i in range(1, RUNS + 1):
    ttfb, total, length = test_streaming(cloud_model, i)
    ttfbs.append(ttfb)
    totals.append(total)
ttfb_mn, ttfb_avg, ttfb_mx = stats(ttfbs)
tot_mn, tot_avg, tot_mx = stats(totals)
results["test4"] = {"ttfb_min": ttfb_mn, "ttfb_avg": ttfb_avg, "ttfb_max": ttfb_mx,
                    "tot_min": tot_mn, "tot_avg": tot_avg, "tot_max": tot_mx}
print(f"  => TTFB: min={fmt(ttfb_mn)}, avg={fmt(ttfb_avg)}, max={fmt(ttfb_mx)}")
print(f"  => Total: min={fmt(tot_mn)}, avg={fmt(tot_avg)}, max={fmt(tot_mx)}")
print()

print("=" * 60)
print("RESULTS JSON (for report generation)")
print("=" * 60)
print(json.dumps(results, indent=2))
