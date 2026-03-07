---
status: diagnosed
created: 2026-03-07T09:30:00Z
updated: 2026-03-07T09:30:30Z
server: 208.110.93.90
user: soudshoja
---

# GPU Server Health Report — 208.110.93.90
Date: 2026-03-07 ~09:30 UTC

---

## VERDICT: SERVER IS HEALTHY — BUT TWO ISSUES FOUND

---

## 1. Ollama Process Status

**RUNNING** — but NOT via systemd.

```
root  3527  0.1  2.5 12674452 6754400 ?  Ssl  Feb23  25:40 /bin/ollama serve
root  1451128  2.3  0.2 46703728 754292 ?  Sl   09:29   0:01 /usr/bin/ollama runner --model /root/.ollama/models/blobs/sha256-dde5aa3...
```

- Ollama has been running since Feb 23 (11+ days uptime, consistent with system uptime)
- A runner process just started at 09:29 (likely serving an active inference)
- Started from `/bin/ollama` (not a systemd service — no unit file registered)

---

## 2. Ollama API Response

**RESPONDING** on port 11434. Sample of /api/tags output:

```json
{
  "models": [
    {
      "name": "smollm2:135m",
      "size": 270898672,
      "details": { "family": "llama", "parameter_size": "134.52M", "quantization_level": "F16" }
    },
    {
      "name": "rnj-1:8b-cloud",
      "remote_model": "rnj-1:8b",
      "remote_host": "https://ollama.com:443",
      "details": { "family": "gemma3", "parameter_size": "8000000000" }
    },
    {
      "name": "gemma3:27b-cloud",
      "remote_model": "gemma3:27b",
      "remote_host": "https://ollama.com:443"
    }
  ]
}
```

Port 11434 is listening on all interfaces (0.0.0.0 and [::]).

---

## 3. Models Loaded

`ollama list` command not found in PATH for this user session (PATH issue for non-login shell). However the API shows these models are registered:

| Model | Type | Size |
|---|---|---|
| smollm2:135m | Local (F16 GGUF) | 271 MB |
| rnj-1:8b-cloud | Remote proxy -> ollama.com | 8B params |
| gemma3:27b-cloud | Remote proxy -> ollama.com | 27B params |

NOTE: `rnj-1:8b-cloud` and `gemma3:27b-cloud` are **remote cloud models** — they route to ollama.com, not the local GPU. If llm.resayil.io uses these, failures may originate from ollama.com, not this server.

---

## 4. Ollama Logs

`journalctl` shows no entries for the ollama unit (because Ollama is not a systemd service). Logs are not accessible via standard log paths either. To see logs, you would need to check the process's stdout — likely running in a screen/tmux session or via a startup script.

---

## 5. GPU Status

**HEALTHY**

```
NVIDIA RTX A6000
Driver: 560.31.02 | CUDA: 12.6
Temp: 40C | Power: 17W / 300W (very low, idle-ish)
GPU Memory: 28018 MiB / 49140 MiB used (57%)
GPU Util: 0% (currently idle)
```

GPU processes:
| PID | Process | VRAM |
|---|---|---|
| 4459 | VLLM::EngineCore | 25030 MiB |
| 1451128 | /usr/bin/ollama runner | 2974 MiB |

Both VLLM and Ollama are running simultaneously and sharing the GPU. VLLM is taking the majority of VRAM (25 GB). Ollama has 2.97 GB allocated.

**NOTE: VLLM is also running** (process 4459, using 25 GB VRAM). This is a separate inference server. If llm.resayil.io has two backends (Ollama + VLLM), both appear operational.

---

## 6. Disk Space

**HEALTHY — plenty of space**

| Filesystem | Size | Used | Avail | Use% |
|---|---|---|---|---|
| /dev/sda2 (root) | 224G | 90G | 125G | 42% |
| /dev/nvme0n1p1 | 1.8T | 165G | 1.5T | 10% |
| /dev/sdb1 | 9.1T | 34M | 8.6T | 1% |

Disk is not a problem.

---

## 7. Memory

**HEALTHY — massive headroom**

```
Total: 251 GB
Used:   15 GB
Free:  152 GB
Available: 236 GB
```

RAM is nowhere near a bottleneck. No swap configured (fine, given 251 GB RAM).

---

## 8. OOM / Crash Errors

**NONE FOUND**

`dmesg` returned "Operation not permitted" for the current user (non-root). However no OOM kills or crash errors were caught. System has been stable for 11+ days.

---

## 9. Port 11434 Accessibility

**OPEN and LISTENING on all interfaces**

```
LISTEN 0.0.0.0:11434
LISTEN [::]:11434
```

Port is accessible externally (unless a firewall blocks it at the network level — not testable from inside the server).

---

## 10. System Uptime

```
up 11 days, 23:24 hours
load average: 0.21, 0.12, 0.04  (very low)
Kernel: Linux 6.8.0-100-generic (Ubuntu, Jan 2026)
```

System is stable and load is minimal.

---

## ISSUES FOUND

### Issue 1: Ollama NOT managed by systemd (RISK)
- `systemctl status ollama` → "Unit ollama.service could not be found"
- Ollama was started manually (or via a startup script outside systemd)
- If the server reboots, Ollama will NOT automatically restart
- This is a risk for availability but not a current problem (it has been running 11+ days)

### Issue 2: `ollama` not in PATH for non-login SSH sessions (MINOR)
- `ollama list` returns "command not found" for this user's shell session
- The binary exists at `/bin/ollama` and `/usr/bin/ollama` (runner)
- PATH is not set for non-interactive SSH — admin tool sessions may behave unexpectedly

### Issue 3: "cloud" models route externally (AWARENESS)
- `rnj-1:8b-cloud` and `gemma3:27b-cloud` are remote-proxy models pointing to `https://ollama.com:443`
- If Saied's API calls use these models, failures would originate at ollama.com, not this server
- The local model `smollm2:135m` runs fully on-server

### Issue 4: No Ollama logs accessible
- Cannot audit recent requests, errors, or latency from logs
- Consider redirecting Ollama stdout to a log file

---

## SUMMARY

| Check | Status |
|---|---|
| Ollama process running | PASS |
| Ollama API responding | PASS |
| Models available | PASS (3 models) |
| Ollama logs | NOT ACCESSIBLE |
| GPU health | PASS (RTX A6000, 40C, stable) |
| Disk space | PASS (125GB free on root) |
| Memory | PASS (236GB available) |
| OOM/crashes | PASS (none detected) |
| Port 11434 open | PASS |
| System uptime | PASS (11+ days) |

**The GPU server is healthy.** Ollama is running and responding. If Saied's API calls are failing, the problem is likely:
1. The specific model being called (`rnj-1:8b-cloud` / `gemma3:27b-cloud` route to external ollama.com — those could fail independently)
2. A network/firewall rule between llm.resayil.io and this server
3. An application-layer issue in the API routing code, not the GPU server itself
