# Phase 07: Fix Saied API Infrastructure Bugs

## Goal
Fix 3 critical bugs causing the API to be down for all users. Do NOT touch hosting-level configs (LiteSpeed server settings, cPanel account settings). Only change app-level files (.htaccess, .env, migrations, seeders).

## Bugs to Fix

### Bug A — LiteSpeed not proxying /v1/ routes (HTTP 404)
- Investigate the current .htaccess in public_html
- Check how the Laravel app is deployed (PHP-FPM vs artisan serve)
- Fix .htaccess rewrite rules if needed (app-level only, not server config)
- DO NOT touch LiteSpeed server-level config or cPanel settings

### Bug B — Backend returns HTTP 500 (models table empty)
- Enable APP_DEBUG=true on server to expose real exception
- Identify and fix the actual 500 cause
- Seed or create the models table data
- Re-disable APP_DEBUG=false after diagnosis

### Bug C — Cache table missing (queue workers crash)
- Run: php artisan cache:table && php artisan migrate --force
- Verify queue workers start cleanly after

## Constraints
- SSH into cPanel server: resayili@llm.resayil.io (password: Resayil00+)
- Python paramiko available for SSH: C:\Users\User\AppData\Local\Programs\Python\Python314\python.exe
- Do NOT change LiteSpeed server configs, cPanel account settings, or anything hosting-level
- Only change: .htaccess, .env values, run artisan commands, create/seed DB tables
- Test each fix after applying it

## Success Criteria
- [ ] GET https://llm.resayil.io/v1/models returns HTTP 200 with model list
- [ ] POST https://llm.resayil.io/v1/chat/completions returns HTTP 200 with completion
- [ ] php artisan queue:work starts without crashing
- [ ] Saied's API key works end-to-end
