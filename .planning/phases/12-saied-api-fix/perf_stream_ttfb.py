"""
Wave 2 rerun: 4 concurrent streaming requests, corrected TTFB measurement.
Uses iter_content to catch the very first byte, not just first full line.
"""

import requests
import time
import threading
import json

API_KEY = "dc1914298e3b8dc86af3c8660c6e1a76b5262270bccd9f72db3abfaf594fae50"
BASE_URL = "https://llm.resayil.io/api"
MODEL = "qwen3-vl:235b-instruct-cloud"

CONTEXT = "The quick brown fox jumps over the lazy dog. " * 2000

wave2_results = {}

def make_request_stream(user_id):
    messages = [
        {"role": "system", "content": CONTEXT},
        {"role": "user", "content": f"User {user_id}: What is 3+3? Reply with just the number."}
    ]
    start = time.time()
    ttfb = None
    full_content = []
    byte_buffer = b""

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
                "max_tokens": 20
            },
            timeout=300,
            stream=True
        ) as resp:
            http_status = resp.status_code
            # iter_content with small chunk size to catch first byte ASAP
            for chunk in resp.iter_content(chunk_size=1):
                if chunk and ttfb is None:
                    ttfb = round(time.time() - start, 3)
                if chunk:
                    byte_buffer += chunk
                    # Parse complete SSE lines
                    while b"\n" in byte_buffer:
                        line, byte_buffer = byte_buffer.split(b"\n", 1)
                        line_str = line.decode("utf-8").strip()
                        if line_str.startswith("data: "):
                            data_str = line_str[6:]
                            if data_str.strip() == "[DONE]":
                                break
                            try:
                                obj = json.loads(data_str)
                                delta = obj["choices"][0]["delta"].get("content", "")
                                if delta:
                                    full_content.append(delta)
                            except Exception:
                                pass

        elapsed = round(time.time() - start, 3)
        wave2_results[user_id] = {
            "status": http_status,
            "ttfb_s": ttfb,
            "total_s": elapsed,
            "answer": "".join(full_content).strip(),
            "error": None
        }
    except Exception as e:
        elapsed = round(time.time() - start, 3)
        wave2_results[user_id] = {
            "status": "ERROR",
            "ttfb_s": ttfb,
            "total_s": elapsed,
            "answer": None,
            "error": str(e)
        }

print("WAVE 2 RERUN: 4 concurrent streaming requests (corrected TTFB)", flush=True)
print("Firing all 4 threads simultaneously...", flush=True)

threads = [threading.Thread(target=make_request_stream, args=(i,)) for i in range(1, 5)]
wall_start = time.time()
for t in threads:
    t.start()
for t in threads:
    t.join()
wall_total = round(time.time() - wall_start, 3)

print(f"\nWall time: {wall_total}s", flush=True)
for uid in sorted(wave2_results):
    r = wave2_results[uid]
    print(f"  User {uid}: status={r['status']}  TTFB={r['ttfb_s']}s  total={r['total_s']}s  answer={r.get('answer','?')}", flush=True)

print("\nFULL JSON:")
print(json.dumps({
    "wall_time_s": wall_total,
    "results": wave2_results
}, indent=2))
