---
status: resolved
trigger: "unauthenticated-json-error-page"
created: 2026-03-05T00:00:00Z
updated: 2026-03-05T07:30:00Z
---

## Current Focus

hypothesis: CONFIRMED — Handler.php on prod was missing the web-vs-API check
test: Verified via curl: prod /dashboard now returns HTTP 302 redirect to /login
expecting: DONE
next_action: COMPLETE

## Symptoms

expected: Unauthenticated web user visiting /dashboard gets redirected to /login OR sees a beautiful styled error page
actual: Raw JSON response: {"message":"Unauthenticated.","error":"You must be logged in to access this resource."}
errors: {"message":"Unauthenticated.","error":"You must be logged in to access this resource."}
reproduction: Visit https://llm.resayil.io/dashboard without being logged in
started: Ongoing — commit 255b881 was on dev but prod was at 351857a

## Eliminated

- hypothesis: Handler.php on dev is missing the web check
  evidence: Read Handler.php — dev already had lines 39-46 with correct check: expectsJson OR api/* → JSON, else redirect to login
  timestamp: 2026-03-05T07:00:00Z

## Evidence

- timestamp: 2026-03-05T07:00:00Z
  checked: app/Exceptions/Handler.php on dev (local)
  found: Lines 39-46 have correct AuthenticationException handling — web requests redirect to login, API/JSON requests get 401 JSON
  implication: The fix IS on dev already. Prod is at 351857a which is behind dev.

- timestamp: 2026-03-05T07:05:00Z
  checked: app/Exceptions/Handler.php on prod server via SSH
  found: OLD code — no web/API distinction, always returns JSON for AuthenticationException, ValidationException, and HttpException
  implication: Root cause confirmed. Commit 255b881 fix never reached prod.

- timestamp: 2026-03-05T07:15:00Z
  checked: git log on dev and prod
  found: dev HEAD is f4f27fe (many commits ahead), prod HEAD was 351857a. Commit 255b881 "fix(auth): redirect web users to login instead of returning JSON 401" was on dev only, changing only app/Exceptions/Handler.php
  implication: Safe to cherry-pick just that one commit to main without pulling in unfinished features

- timestamp: 2026-03-05T07:20:00Z
  checked: Cherry-pick 255b881 onto main, push to GitHub, deploy prod
  found: Deployment succeeded — "Nothing to migrate", all caches cleared
  implication: Fix is live on prod

- timestamp: 2026-03-05T07:29:00Z
  checked: curl -v https://llm.resayil.io/dashboard (no auth)
  found: HTTP 302 Found, Location: https://llm.resayil.io/login
  implication: Browser users now get properly redirected to login page

- timestamp: 2026-03-05T07:29:30Z
  checked: curl https://llm.resayil.io/api/v1/models with Accept: application/json (no auth)
  found: {"message":"Unauthenticated.","error":"Authorization header required."} — JSON 401 response
  implication: API consumers still get proper JSON responses. No regression.

## Resolution

root_cause: Commit 255b881 added the web-vs-API distinction to Handler.php on dev, but prod (main branch at 351857a) never received this fix. Prod Handler.php was unconditionally returning JSON for ALL AuthenticationException, ValidationException, and HttpException regardless of whether the client was a browser or API consumer.
fix: Cherry-picked commit 255b881 onto main branch, pushed to GitHub, deployed to prod via deploy.sh prod.
verification: curl confirms prod /dashboard returns HTTP 302 → /login for unauthenticated browser requests. API endpoint still returns JSON. No migration needed (only Handler.php changed).
files_changed:
  - app/Exceptions/Handler.php (cherry-pick of commit 255b881 → new commit a9a1a9d on main)
