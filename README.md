# LLM Resayil

OpenAI-compatible LLM API gateway with pay-per-use credits.

- **Production:** https://llm.resayil.io
- **Dev / Staging:** https://llmdev.resayil.io

---

## Development Workflow

### Branches

| Branch | Environment | URL |
|--------|-------------|-----|
| `dev`  | Staging     | https://llmdev.resayil.io |
| `main` | Production  | https://llm.resayil.io |

### Day-to-day flow

```bash
# 1. Work locally on the dev branch
git checkout dev

# 2. Make changes, commit
git add <files>
git commit -m "feat: my change"
git push origin dev

# 3. Deploy to staging and test
ssh whm-server "cd ~/llmdev.resayil.io && bash ~/llm.resayil.io/deploy.sh dev"

# 4. Happy with it? Merge to main and deploy to production
git checkout main
git merge dev
git push origin main
ssh whm-server "bash ~/llm.resayil.io/deploy.sh prod"
```

### Deploy scripts

```bash
./deploy.sh dev    # → llmdev.resayil.io  (pulls dev branch)
./deploy.sh prod   # → llm.resayil.io     (pulls main branch)
```

---

## First-Time Dev Server Setup

1. In cPanel, create subdomain `llmdev.resayil.io` → document root `~/llmdev.resayil.io/public`
2. Create database `resayili_llmdev` in cPanel MySQL
3. SSH into the server and run:

```bash
bash ~/llm.resayil.io/setup-dev-server.sh
```

---

## Server Details

- **PHP:** `/opt/cpanel/ea-php82/root/usr/bin/php`
- **Server SSH alias:** `whm-server`
- **Prod path:** `~/llm.resayil.io`
- **Dev path:** `~/llmdev.resayil.io`
