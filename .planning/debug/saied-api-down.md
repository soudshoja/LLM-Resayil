---
status: investigating
trigger: "saied reports API is down (started today)"
created: 2026-03-07T00:00:00Z
updated: 2026-03-07T09:45:00Z
---

## Current Focus

hypothesis: CONFIRMED BY LIVE TEST — saied's API keys are valid and working. The API is NOT down for saied's account. The 401s in the access log are from a different IP (44.201.145.182, AWS) sending requests with an invalid API key, not from saied's real client. The issue saied is experiencing is likely a CLIENT-SIDE misconfiguration (wrong key in the client app) OR the 44.201.145.182 probe IS saied's client app (e.g. OpenClaw hosted on AWS) using a stale/wrong key.
test: Performed live API test with saied's active keys — both returned HTTP 200
expecting: Admin to ask saied which key his client is using and verify it matches the ones in the DB
next_action: CHECKPOINT — ask saied to confirm which API key string his client app is using

## Symptoms

expected: API should respond normally to saied's requests
actual: saied reports API is down / not working
errors: unknown — investigate server-side logs and user account state
reproduction: unknown
timeline: started today / just now

## Eliminated

- hypothesis: Rate limiting causing permanent block
  evidence: RateLimiter uses per-minute cache keys (format: rate_limit:{userId}:Y-m-d-H-i). Limits reset every minute automatically. A rate limit cannot cause an all-day outage — it would self-heal within 60 seconds. Not the cause of "API is down today".
  timestamp: 2026-03-07

- hypothesis: EnterpriseMiddleware blocking saied
  evidence: EnterpriseMiddleware only applies to /admin/... web routes and enterprise-gated features. The main API routes (/v1/chat/completions, /v1/models) use only 'api.key.auth' middleware — EnterpriseMiddleware is not in that stack.
  timestamp: 2026-03-07

- hypothesis: Cloud budget exceeded blocking all requests
  evidence: CloudFailover.recordCloudRequest() returns false when daily cloud budget (500/day) is hit, but ChatCompletionsController only falls back to local provider — it does NOT return an error to the user. Cloud budget exhaustion cannot cause API-down.
  timestamp: 2026-03-07

## Evidence

- timestamp: 2026-03-07
  checked: ApiKeyAuth middleware (app/Http/Middleware/ApiKeyAuth.php)
  found: CRITICAL BUG — The middleware does NOT check the api_keys.status field. It only does ApiKeys::where('key', $key)->first() with NO status filter. A revoked key (status='revoked') will still authenticate successfully.
  implication: If saied's key was manually revoked via the dashboard but ApiKeyAuth doesn't enforce it, that's not why they're blocked. However, this IS a security bug that needs fixing.

- timestamp: 2026-03-07
  checked: ChatCompletionsController credit check (lines 92-98)
  found: checkCredits uses $estimatedCost = calculateCost(100, 'local') = ceil(100 * 1.0 / 1000) = 1 credit. If user.credits < 1 (i.e., credits = 0), the request is blocked with HTTP 402 and message "Insufficient credits".
  implication: If saied's credits have reached 0, ALL API calls are blocked. This is the most likely cause given "started today" — credits were exhausted today.

- timestamp: 2026-03-07
  checked: users table migration (2024_02_26_000001_create_users_table.php)
  found: subscription_tier is an ENUM with values ['basic', 'pro', 'enterprise']. Default is 'basic'.
  implication: 'starter' and 'admin' are NOT valid MySQL enum values. Any code that tries to set subscription_tier = 'starter' or 'admin' will cause a MySQL strict mode error OR silently store empty string depending on MySQL config.

- timestamp: 2026-03-07
  checked: AdminController.setUserTier() (app/Http/Controllers/Admin/AdminController.php line 90)
  found: CRITICAL BUG — setUserTier() accepts tier values ['starter', 'basic', 'pro', 'enterprise'] but the DB enum only has ['basic', 'pro', 'enterprise']. Setting tier to 'starter' will fail or corrupt.
  implication: If an admin tried to set saied's tier to 'starter' today (downgrade/trial), the DB write would fail silently or with an error, potentially leaving the user in an inconsistent state.

- timestamp: 2026-03-07
  checked: User.isAdmin() (app/Models/User.php line 108-113)
  found: isAdmin() returns true if subscription_tier === 'admin'. But 'admin' is not a valid enum value in the DB migration. The isAdmin() check also accepts email whitelist. The admin tier check would never work via the enum column.
  implication: Admin users rely on the email whitelist to get bypass. If saied is supposed to be admin but isn't in the whitelist, they get credit/rate checks applied.

- timestamp: 2026-03-07
  checked: ProcessTrialCharges command and TrialSubscriptionService
  found: These set subscription_tier = 'starter' in code. If the DB migration has not been updated to include 'starter' as a valid enum value, this will fail on MySQL strict mode (SQLSTATE 1265 Data truncated).
  implication: A scheduled command (ProcessTrialCharges) could be failing today and corrupting user state.

- timestamp: 2026-03-07
  checked: RateLimiter tier lookup (app/Services/RateLimiter.php line 13-17)
  found: Rate limits defined only for ['basic', 'pro', 'enterprise']. If user has tier='starter', getRateLimit('starter') falls back to getRateLimit('basic') = 10 req/min.
  implication: Not a blocking issue, just a tier mismatch.

- timestamp: 2026-03-07
  checked: ApiKeys model (app/Models/ApiKeys.php)
  found: The model has scopeActive() defined but ApiKeyAuth middleware does NOT use this scope. It queries all keys regardless of status.
  implication: API key revocation via UI is not actually enforced at the auth layer (security bug), but this means a "revoked" key would still work — so key revocation is NOT why saied is blocked.

- timestamp: 2026-03-07
  checked: CloudFailover service + cloud budget
  found: Daily limit is 500 requests hardcoded. When exceeded, falls back to local (not an error). Cloud URL and API key are checked for availability — if OLLAMA_CLOUD_URL or CLOUD_API_KEY env vars are empty, cloud is unavailable but local still works.
  implication: Cloud-related issues would not block saied unless Ollama itself (local) is down.

## Live Test Results (Agent 2 — 2026-03-07 ~09:30 UTC+3)

- timestamp: 2026-03-07T09:28
  checked: Prod DB — users table for saied
  found: email=iamshoja@gmail.com, name=Saeid Shoja, credits=1500, subscription_tier=basic. Account is healthy.
  implication: Credits NOT exhausted. Tier is valid. Account is not blocked by credit check.

- timestamp: 2026-03-07T09:29
  checked: Prod DB — api_keys for saied (user_id=a20de43f-e2d7-40ff-92cd-54ee5002c4cb)
  found: 3 keys, ALL status='active': "Admin-created key" (never used), "OpenClaw V1" (last used 01:30 today), "OpenClaw" (last used Mar 5). No revoked keys.
  implication: Key revocation is NOT the issue.

- timestamp: 2026-03-07T09:30
  checked: Prod DB — usage_logs for saied
  found: 0 rows. No usage ever logged. Even after our successful live test that returned HTTP 200, usage_logs still has 0 rows.
  implication: SECONDARY BUG — CreditService.deductCredits() is failing silently after successful API calls. Credits are never deducted and usage is never logged. This is a separate bug from the API-down report.

- timestamp: 2026-03-07T09:30
  checked: Live API test with key "OpenClaw V1" (fb68...f9)
  found: GET /api/v1/models → HTTP 200 with full model list. POST /api/v1/chat/completions with llama3.2:3b → HTTP 200 with valid completion. API is fully functional.
  implication: The API is NOT down from the server side. Saied's key works.

- timestamp: 2026-03-07T09:31
  checked: Access log llm.resayil.io-ssl_log for today
  found: ALL /api/v1 calls today are from 44.201.145.182 (AWS us-east-1), every 15 minutes on the :00/:15/:30/:45 mark, using curl/8.5.0, ALL returning HTTP 401. Response body size = 57 bytes = matches "Invalid API key." error (not "Authorization header required."). So the probe IS sending a Bearer token, but the token is invalid/not in the DB.
  implication: The 401 probe from 44.201.145.182 is either: (a) saied's OpenClaw application hosted on AWS using a wrong/deleted key, or (b) an unrelated monitoring bot. This is the most likely cause of saied's "API is down" report — his CLIENT is sending the wrong key.

- timestamp: 2026-03-07T09:32
  checked: Maintenance mode
  found: storage/framework/down does not exist. App is not in maintenance mode.
  implication: No global maintenance block.

- timestamp: 2026-03-07T09:33
  checked: IP ban mechanism
  found: IPBanned event exists and is dispatched to AdminAlertListener, but there is NO middleware that actually checks a banned IP list. The IPBanned event is purely a notification. No IP ban table, no middleware checking it. IP banning does NOT block requests.
  implication: IP banning is not a blocking mechanism for requests. Cannot explain the 401s.

## Resolution

root_cause: The API server is fully operational. Saied's account is healthy (1500 credits, basic tier, 3 active API keys). The source of saied's "API is down" report is that his CLIENT APPLICATION (likely OpenClaw, hosted at AWS IP 44.201.145.182) is sending an INVALID API key. Every API call from that IP returns HTTP 401 "Invalid API key." — meaning the Bearer token being sent does not match any key in the api_keys table. Most likely cause: the client is configured with an old/deleted key or a typo in the key string.

SECONDARY BUG DISCOVERED: CreditService.deductCredits() appears to be failing silently — 0 usage_logs rows despite successful API calls. Credits are not being deducted. This needs separate investigation (likely a DB transaction issue or the UsageLog model's created_at column has a null constraint problem).

fix: |
  IMMEDIATE ACTION for saied:
  1. Ask saied: "Which API key are you using in OpenClaw? Paste the first 8-10 characters."
  2. Compare against his 3 keys:
     - Admin-created key: starts with 1b5ef32d...
     - OpenClaw V1: starts with fb684309...
     - OpenClaw: starts with 89996a35...
  3. If his app is using a different key, he needs to update it in OpenClaw's settings.
  4. The "OpenClaw V1" key was last used at 01:30 today — this is likely the correct one.
     If his app stopped working after 01:30, something in his client config changed.

  SECONDARY BUG (CreditService):
  Investigate why usage_logs has 0 rows. Check if UsageLog model has a schema mismatch,
  or if deductCredits() DB transaction is silently rolling back. Run:
  SELECT * FROM usage_logs LIMIT 5;
  SHOW CREATE TABLE usage_logs;

verification:
  - Live tested saied's fb68... key: GET /api/v1/models = HTTP 200, POST /api/v1/chat/completions = HTTP 200
  - Live tested saied's 8999... key: GET /api/v1/models = HTTP 200
  - Confirmed credits=1500 (not exhausted)
  - Confirmed no maintenance mode
  - Confirmed no IP ban enforcement mechanism

files_changed: []
