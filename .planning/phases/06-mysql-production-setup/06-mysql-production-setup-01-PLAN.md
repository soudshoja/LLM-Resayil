---
phase: 06-mysql-production-setup
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - database/migrations/2026_02_26_XXXXXX_create_jobs_table.php
  - .env (remote production server)
autonomous: false
requirements: []
user_setup:
  - service: production-database
    why: "MySQL database credentials for cPanel-hosted production server"
    env_vars:
      - name: DB_PASSWORD (local .env)
        source: "Production MySQL password from cPanel"
  - service: redis-production
    why: "Redis server for rate limiting on production"
    env_vars:
      - name: REDIS_HOST
        source: "Redis server address (production)"
      - name: REDIS_PASSWORD
        source: "Redis password (production)"
  - service: myfatoorah-webhook
    why: "Payment webhook configuration"
    env_vars:
      - name: MYFATOORAH_API_KEY
        source: "MyFatoorah API key from dashboard"
  - service: whatsapp-api
    why: "WhatsApp notification service credentials"
    env_vars:
      - name: WHATSAPP_API_KEY
        source: "Resayil WhatsApp API key"
      - name: ADMIN_PHONE
        source: "Admin phone number for WhatsApp alerts (96550XXXXXXX format)"
  - service: cloud-ollama
    why: "Cloud Ollama failover service credentials"
    env_vars:
      - name: OLLAMA_CLOUD_URL
        source: "Cloud Ollama API endpoint"
      - name: CLOUD_API_KEY
        source: "Cloud Ollama API authentication key"

must_haves:
  truths:
    - "Queue jobs table exists in production database (php artisan queue:table migration committed locally)"
    - "Production .env file is fully configured with all required credentials"
    - "All 10 migrations run successfully on production database (9 existing + jobs table)"
    - "Both database seeders execute without errors (NotificationTemplateSeeder + UserSeeder)"
    - "APP_KEY is generated and set in production .env"
    - "Production database contains seed data (notification templates, admin user, subscription plans)"
    - "App is fully operational on production server (no migration or seed errors)"
  artifacts:
    - path: "database/migrations/2026_02_26_XXXXXX_create_jobs_table.php"
      provides: "Laravel queue jobs table migration for database queue driver"
      contains: "jobs_table"
    - path: ".env (on production server at /home/resayili/llm.resayil.io/)"
      provides: "Production environment configuration with all service credentials"
      contains: ["DB_PASSWORD", "REDIS_HOST", "REDIS_PASSWORD", "MYFATOORAH_API_KEY", "WHATSAPP_API_KEY", "CLOUD_API_KEY", "APP_KEY"]
  key_links:
    - from: "database/migrations/2026_02_26_XXXXXX_create_jobs_table.php"
      to: "production MySQL database"
      via: "php artisan migrate --force on production"
      pattern: "jobs_table"
    - from: ".env (production)"
      to: "app/Services/MyFatoorahService.php"
      via: "MYFATOORAH_API_KEY environment variable"
      pattern: "env\\('MYFATOORAH_API_KEY'"
    - from: ".env (production)"
      to: "app/Services/WhatsAppService.php"
      via: "WHATSAPP_API_KEY and ADMIN_PHONE"
      pattern: "env\\('WHATSAPP_API_KEY|ADMIN_PHONE'"
    - from: "database/seeders/NotificationTemplateSeeder.php"
      to: "notification_templates table"
      via: "php artisan db:seed --force"
      pattern: "NotificationTemplate::create\\("
    - from: "database/seeders/UserSeeder.php"
      to: "users table"
      via: "php artisan db:seed --force"
      pattern: "User::create\\("

---

# Phase 6 Plan 01: MySQL Production Setup & Deployment

## Objective

Generate the queue jobs table migration locally, configure the production environment with all required credentials, run all database migrations on production, and execute seed data loading. This completes the setup needed for the Laravel app to be fully operational on the production MySQL server.

Purpose: This plan ensures the production database is properly configured with all 10 migrations (9 existing + jobs table) and seeded with initial data (notification templates, admin user, subscription plans), allowing the app to handle queue operations, notifications, payments, and API access immediately after deployment.

Output: Fully configured production database with all migrations applied, seed data loaded, and .env configured for all external services.

---

## Context

@.planning/ROADMAP.md
@.planning/STATE.md

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Generate queue jobs table migration locally</name>
  <files>database/migrations/2026_02_26_XXXXXX_create_jobs_table.php</files>
  <action>
Generate the Laravel queue jobs table migration locally using Artisan command:

1. Run: `cd /home/soudshoja/LLM-Resayil && php artisan queue:table`
2. This creates a new migration file: database/migrations/2026_02_26_XXXXXX_create_jobs_table.php
3. The migration defines the jobs table with columns: id, queue, payload, attempts, reserved_at, available_at, created_at
4. This migration is required because QUEUE_CONNECTION=database in .env uses the jobs table to store queue jobs
5. Verify file is created and contains `Schema::create('jobs', function (Blueprint $table)`
6. Commit the migration file to git with message: "chore: add queue jobs table migration"

The migration file will be committed locally and pushed to production along with other code changes. Production will run this migration with php artisan migrate --force.
  </action>
  <verify>
ls -la /home/soudshoja/LLM-Resayil/database/migrations/ | grep create_jobs_table && grep -q "Schema::create('jobs'" /home/soudshoja/LLM-Resayil/database/migrations/2026_02_26_*_create_jobs_table.php
  </verify>
  <done>
  - Queue jobs migration file exists in database/migrations/
  - Migration contains correct schema for jobs table
  - File is committed to git and ready for production deployment
  </done>
</task>

<task type="checkpoint:decision" gate="blocking">
  <decision>Which Resayil WhatsApp API endpoint URL should be used?</decision>
  <context>
The .env.example shows WHATSAPP_API_URL=https://api.resayil.io/whatsapp but this needs to be confirmed for production.
  </context>
  <options>
    <option id="option-resayil-api">
      <name>Use https://api.resayil.io/whatsapp (existing Resayil endpoint)</name>
      <pros>Existing integration pattern, consistent with current infrastructure</pros>
      <cons>Requires that endpoint is publicly accessible and ready</cons>
    </option>
    <option id="option-custom-endpoint">
      <name>Use custom production endpoint</name>
      <pros>Can use dedicated subdomain for WhatsApp API</pros>
      <cons>Requires configuration of DNS and routing</cons>
    </option>
  </options>
  <resume-signal>Confirm which endpoint to use in .env (default: https://api.resayil.io/whatsapp)</resume-signal>
</task>

<task type="checkpoint:human-action" gate="blocking">
  <name>Task 2: Gather and verify all production credentials</name>
  <what-needed>
Collect the following credentials from the respective services/dashboards before applying to production. These will be used in the .env configuration:

**From MyFatoorah Dashboard:**
- MYFATOORAH_API_KEY (from Settings -> API Keys)

**From Resayil WhatsApp Integration:**
- WHATSAPP_API_KEY (from API credentials)
- ADMIN_PHONE (admin phone number in 96550XXXXXXX format)

**From Cloud Ollama Service:**
- OLLAMA_CLOUD_URL (cloud service endpoint URL)
- CLOUD_API_KEY (cloud service authentication key)

**From Production MySQL (cPanel):**
- DB_PASSWORD (MySQL password for resayili_llm user)

**From Production Redis (if configured):**
- REDIS_HOST (Redis server address, default 127.0.0.1)
- REDIS_PASSWORD (Redis password if authentication required)

**APP_KEY Generation:**
- Will be generated on production server with: php artisan key:generate
  </what-needed>
  <how-to-proceed>
1. Collect all credentials from their respective services/dashboards
2. Have these values ready to input into .env configuration in next task
3. Confirm you have all required values and they are correct (test connectivity if possible)
4. Type "credentials_ready" when all values are gathered and verified
  </how-to-proceed>
  <resume-signal>Type "credentials_ready" once all production credentials are gathered and verified</resume-signal>
</task>

<task type="auto">
  <name>Task 3: Configure .env file on production server</name>
  <files>.env (on production server at /home/resayili/llm.resayil.io/)</files>
  <action>
Configure the production .env file with all required credentials using the ssh-whm skill:

1. **Connect to production server** via SSH:
   - Use skill: ssh-whm
   - Navigate to: /home/resayili/llm.resayil.io/

2. **Create/update .env file** with the following key configurations:
   ```
   APP_NAME="LLM Resayil Portal"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://llm.resayil.io

   # Database credentials (use values from cPanel MySQL)
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=resayili_llm_resayil
   DB_USERNAME=resayili_llm
   DB_PASSWORD={DB_PASSWORD_from_credentials}

   # Redis (for rate limiting)
   REDIS_HOST={REDIS_HOST_from_credentials}
   REDIS_PORT=6379
   REDIS_PASSWORD={REDIS_PASSWORD_from_credentials}

   # Ollama API configuration
   OLLAMA_GPU_URL=http://208.110.93.90:11434
   OLLAMA_CLOUD_URL={OLLAMA_CLOUD_URL_from_credentials}
   CLOUD_API_KEY={CLOUD_API_KEY_from_credentials}

   # MyFatoorah Payment Gateway
   MYFATOORAH_API_KEY={MYFATOORAH_API_KEY_from_credentials}
   MYFATOORAH_BASE_URL=https://ap-gateway.myfatoorah.com
   MYFATOORAH_CALLBACK_URL=https://llm.resayil.io/webhooks/payment
   MYFATOORAH_CURRENCY=KWD

   # Resayil WhatsApp API (confirmed from checkpoint decision)
   WHATSAPP_API_URL=https://api.resayil.io/whatsapp
   WHATSAPP_API_KEY={WHATSAPP_API_KEY_from_credentials}
   ADMIN_PHONE={ADMIN_PHONE_from_credentials}

   # Queue Configuration (database queue driver)
   QUEUE_CONNECTION=database

   # Session and Cache
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   CACHE_DRIVER=file
   LOG_CHANNEL=stack
   ```

3. **Save .env file** with secure permissions:
   - Write .env to /home/resayili/llm.resayil.io/.env
   - Ensure file permissions are 600 (readable only by app user): chmod 600 .env

4. **Verify .env is complete**:
   - All required variables are present
   - No placeholder values remain
   - File is readable by application

5. **Generate APP_KEY** (next task will do this, but can also be done here):
   - Command: php artisan key:generate
   - This generates a unique encryption key for the application
   - APP_KEY will be automatically set in .env
  </action>
  <verify>
cat /home/resayili/llm.resayil.io/.env | grep -E "DB_PASSWORD|MYFATOORAH_API_KEY|WHATSAPP_API_KEY|CLOUD_API_KEY|ADMIN_PHONE" | wc -l
This should return 5 (all required credentials present)
  </verify>
  <done>
  - .env file exists on production server
  - All required credentials are set (DB_PASSWORD, REDIS_HOST, REDIS_PASSWORD, MYFATOORAH_API_KEY, WHATSAPP_API_KEY, CLOUD_API_KEY, ADMIN_PHONE)
  - File permissions are secure (chmod 600)
  - File is ready for application startup
  </done>
</task>

<task type="auto">
  <name>Task 4: Generate APP_KEY on production server</name>
  <files>.env (updated with APP_KEY on production server)</files>
  <action>
Generate the application encryption key on the production server:

1. **Connect to production server** via SSH (ssh-whm skill)
2. **Navigate to application directory**: cd /home/resayili/llm.resayil.io/
3. **Generate APP_KEY** using Artisan:
   - Command: php artisan key:generate
   - This generates a unique encryption key and sets it in .env as: APP_KEY=base64:XXXXXXXXXXXXX
   - The key is used for encrypting session data, cookies, and other sensitive information
4. **Verify the key was generated**:
   - Check .env file: grep "^APP_KEY=" .env
   - Should see: APP_KEY=base64:... (with actual base64 encoded key)
5. **Do NOT output the APP_KEY value** - keep it secret and stored in .env only

The APP_KEY is crucial for application security. Do not share or expose this value.
  </action>
  <verify>
grep "^APP_KEY=base64:" /home/resayili/llm.resayil.io/.env | wc -l
This should return 1 (APP_KEY is set)
  </verify>
  <done>
  - APP_KEY is generated and set in .env
  - Key is base64 encoded and starts with "base64:"
  - .env is ready for application startup
  </done>
</task>

<task type="auto">
  <name>Task 5: Run all database migrations on production</name>
  <files>
    - database/migrations/2024_02_26_000001_create_users_table.php (and all other 8 existing migrations)
    - database/migrations/2026_02_26_XXXXXX_create_jobs_table.php
    - production MySQL database (resayili_llm_resayil)
  </files>
  <action>
Execute all database migrations on the production server. This includes the 9 existing migrations plus the newly generated queue jobs table migration:

**Existing migrations (9 total):**
1. 2024_02_26_000001_create_users_table.php
2. 2024_02_26_000002_create_api_keys_table.php
3. 2024_02_26_000003_create_subscriptions_table.php
4. 2024_02_26_000004_create_notifications_table.php
5. 2024_02_26_000005_create_notification_templates_table.php
6. 2024_02_26_100001_create_usage_logs_table.php
7. 2024_02_26_100002_create_cloud_budgets_table.php
8. 2026_02_26_063647_create_topup_purchases_table.php
9. 2026_02_26_072500_create_team_members_table.php

**New migration (1 total):**
10. 2026_02_26_XXXXXX_create_jobs_table.php (generated in Task 1)

**Steps:**

1. **Connect to production server** via SSH (ssh-whm skill)
2. **Navigate to application directory**: cd /home/resayili/llm.resayil.io/
3. **Verify code is deployed** - the queue jobs migration should already be in database/migrations/ from git pull
4. **Run migrations with --force flag**:
   - Command: php artisan migrate --force
   - The --force flag is required for production (bypasses "Are you sure?" prompt)
   - This will create all tables: users, api_keys, subscriptions, notifications, notification_templates, usage_logs, cloud_budgets, topup_purchases, team_members, jobs
5. **Verify all migrations succeeded**:
   - Command: php artisan migrate:status
   - Should show all 10 migrations as "Ran"
   - No migration should show as "Pending"
6. **Verify tables exist in database** (optional verification):
   - Command: php artisan tinker then: Schema::getTables()
   - Should list all 10 tables

If any migration fails:
- Check .env is correctly configured (especially DB credentials)
- Check MySQL server is accessible from the application server
- Check migrations directory contains all 10 migration files
- Review Laravel logs at /home/resayili/llm.resayil.io/storage/logs/ for detailed errors
  </action>
  <verify>
php artisan migrate:status | grep -E "^.+\s+(Ran|Pending)" | wc -l
This should return 10 (all 10 migrations accounted for - either Ran or Pending)

For verification it ran successfully:
php artisan migrate:status | grep "Pending" | wc -l
This should return 0 (no pending migrations)
  </verify>
  <done>
  - All 10 database migrations executed successfully on production
  - All tables created: users, api_keys, subscriptions, notifications, notification_templates, usage_logs, cloud_budgets, topup_purchases, team_members, jobs
  - No migration errors in logs
  - Database schema is ready for application use
  </done>
</task>

<task type="auto">
  <name>Task 6: Run database seeders on production</name>
  <files>
    - database/seeders/DatabaseSeeder.php
    - database/seeders/NotificationTemplateSeeder.php
    - database/seeders/SubscriptionPlanSeeder.php
    - database/seeders/UserSeeder.php
    - production MySQL database (resayili_llm_resayil)
  </files>
  <action>
Execute the database seeders on the production server to populate initial data. The DatabaseSeeder runs all seeders in sequence:

**Seeders executed:**
1. SubscriptionPlanSeeder - Creates 3 subscription tiers: Basic (10 KWD/month), Pro (50 KWD/month), Enterprise (200 KWD/month)
2. NotificationTemplateSeeder - Creates notification templates for all events (welcome, subscription, credits, renewal, cloud budget, IP ban, enterprise alert) in Arabic and English
3. UserSeeder - Creates initial admin user with credentials (email + password) and subscription

**Steps:**

1. **Connect to production server** via SSH (ssh-whm skill)
2. **Navigate to application directory**: cd /home/resayili/llm.resayil.io/
3. **Run all seeders with --force flag**:
   - Command: php artisan db:seed --force
   - The --force flag is required for production (bypasses confirmation prompt)
   - This executes DatabaseSeeder which calls all seeders in order
4. **Verify seeders executed**:
   - Command: php artisan tinker
   - Then: DB::table('subscription_plans')->count() - should return 3
   - Then: DB::table('notification_templates')->count() - should return 20+ (multiple languages)
   - Then: DB::table('users')->count() - should return 1 (admin user)
   - Then: exit
5. **Capture admin credentials** (from UserSeeder):
   - The admin user is created with a specific email and password
   - These credentials should be saved securely for first login to admin dashboard
   - Review database/seeders/UserSeeder.php to see exact credentials used

If any seeder fails:
- Check all migrations ran successfully (Task 5)
- Check database credentials in .env are correct
- Review Laravel logs at /home/resayili/llm.resayil.io/storage/logs/ for errors
- Some seeders may depend on foreign key relationships from migrations
  </action>
  <verify>
php artisan tinker <<< "
echo 'Plans: ' . DB::table('subscription_plans')->count() . \"\n\";
echo 'Templates: ' . DB::table('notification_templates')->count() . \"\n\";
echo 'Users: ' . DB::table('users')->count() . \"\n\";
exit;
"
This should output:
Plans: 3
Templates: (20+)
Users: 1
  </verify>
  <done>
  - SubscriptionPlanSeeder executed - 3 subscription tiers created (Basic, Pro, Enterprise)
  - NotificationTemplateSeeder executed - all notification templates created (Arabic and English)
  - UserSeeder executed - admin user created with subscription
  - Database contains all seed data and is ready for application use
  - Admin credentials are available for first login
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <name>Task 7: Verify production application is operational</name>
  <what-built>
Complete production setup:
- Queue jobs table migration generated and deployed
- .env configured with all credentials (DB, Redis, MyFatoorah, WhatsApp, Cloud Ollama)
- APP_KEY generated for encryption
- All 10 database migrations executed
- All seeders executed with initial data
  </what-built>
  <how-to-verify>
Perform the following verification checks on the production server at https://llm.resayil.io:

1. **Check application loads**:
   - Visit https://llm.resayil.io in browser
   - Should load without database connection errors
   - Should load login/registration page

2. **Check database is accessible**:
   - SSH to production server
   - Run: php artisan tinker
   - Run: DB::connection()->getDatabaseName() (should return "resayili_llm_resayil")
   - Run: DB::table('users')->count() (should return 1)
   - Type: exit

3. **Check queue is configured**:
   - SSH to production server
   - Run: php artisan queue:work --once (should process without error, may show "no jobs available")
   - This verifies jobs table exists and queue driver is working

4. **Check migration status**:
   - SSH to production server
   - Run: php artisan migrate:status
   - All 10 migrations should show as "Ran"

5. **Check logs for errors**:
   - SSH to production server
   - Run: tail -50 /home/resayili/llm.resayil.io/storage/logs/laravel.log
   - Should not show database connection errors or migration errors

6. **Test login with admin credentials**:
   - If seeder output shows admin email/password, test login on https://llm.resayil.io/login
   - Should successfully log in and see dashboard

7. **Check external services are accessible**:
   - SSH to production server
   - Run: php artisan tinker
   - Run: Http::get('https://api.resayil.io/whatsapp') - should not timeout
   - Run: Http::get(env('OLLAMA_CLOUD_URL')) - should not timeout
   - Type: exit
  </how-to-verify>
  <resume-signal>
Confirm all verification checks pass. If any check fails:
- Describe which check failed
- Share the error message or output
- Do NOT proceed until all checks pass

Type "all_checks_pass" once all verification is complete and application is operational
  </resume-signal>
</task>

</tasks>

---

## Verification

**Phase-level verification:**

1. **Queue jobs migration exists and is committed** to git
2. **Production .env is fully configured** with all 12+ required environment variables
3. **APP_KEY is generated** and stored securely in .env
4. **All 10 migrations executed** on production database (0 pending migrations)
5. **All seed data loaded** (3 subscription plans, 20+ notification templates, 1 admin user)
6. **Production application is accessible** at https://llm.resayil.io without errors
7. **Database queue driver is operational** (jobs table exists and php artisan queue:work responds)
8. **Admin can log in** with seeded credentials to dashboard

---

## Success Criteria

1. ✓ Queue jobs table migration generated locally with `php artisan queue:table` and committed
2. ✓ Production .env file configured with all required credentials (DB, Redis, MyFatoorah, WhatsApp, Cloud Ollama)
3. ✓ APP_KEY generated on production with `php artisan key:generate`
4. ✓ All 10 database migrations executed on production without errors
5. ✓ All seeders executed successfully (subscription plans, notification templates, admin user)
6. ✓ Production database contains all required seed data
7. ✓ Application loads at https://llm.resayil.io without errors
8. ✓ Admin can log in with seeded credentials
9. ✓ Queue driver is operational (jobs table ready for background tasks)
10. ✓ All external services (MyFatoorah, WhatsApp, Cloud Ollama) are configured and accessible

---

## Output

After completion, create `.planning/phases/06-mysql-production-setup/06-mysql-production-setup-01-SUMMARY.md` with:

- Queue jobs migration file path and timestamp
- .env configuration summary (services configured, not actual values)
- Migration execution summary (10 migrations, 0 errors)
- Seeder execution summary (3 tiers, 20+ templates, 1 admin user)
- Production URL and access status
- Admin login credentials location (secure note)
- Next steps for running production application
