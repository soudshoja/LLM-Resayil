---
phase: 06-mysql-production-setup
plan: 01
type: execute
wave: 1
depends_on: []
files_modified:
  - database/migrations/2026_02_26_XXXXXX_create_jobs_table.php
autonomous: true
requirements: []
user_setup: []

must_haves:
  truths:
    - "Queue jobs table migration exists in database/migrations/ directory"
    - "Migration file is committed to git and ready for production deployment"
  artifacts:
    - path: "database/migrations/2026_02_26_XXXXXX_create_jobs_table.php"
      provides: "Laravel queue jobs table migration for database queue driver"
      contains: "Schema::create('jobs'"
  key_links:
    - from: "database/migrations/2026_02_26_XXXXXX_create_jobs_table.php"
      to: "production MySQL database"
      via: "Will be deployed and run via php artisan migrate --force"
      pattern: "jobs_table"

---

# Phase 6 Plan 01: Generate Queue Jobs Migration Locally

## Objective

Generate the Laravel queue jobs table migration locally and commit it to git. This creates the migration file that will be deployed to production and run during the database migration step.

Purpose: The QUEUE_CONNECTION=database in .env requires a jobs table to store queue jobs. This migration must be generated locally, committed, and then deployed to production.

Output: Migration file committed to git and ready for production deployment.

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
ls -la /home/soudshoja/LLM-Resayil/database/migrations/ | grep create_jobs_table && grep -q "Schema::create('jobs'" /home/soudshoja/LLM-Resayil/database/migrations/2026_02_26_*_create_jobs_table.php && cd /home/soudshoja/LLM-Resayil && git log --oneline -1 | grep -q "queue jobs"
  </verify>
  <done>
  - Queue jobs migration file exists in database/migrations/
  - Migration contains correct schema for jobs table
  - File is committed to git and ready for production deployment
  </done>
</task>

</tasks>

---

## Verification

1. **Migration file exists** with correct schema
2. **File is committed** to git with appropriate message
3. **Ready for deployment** - no uncommitted changes

---

## Success Criteria

1. ✓ Queue jobs table migration generated locally with `php artisan queue:table`
2. ✓ Migration file committed to git with message about queue jobs table
3. ✓ File is ready to be deployed to production with next code push

---

## Output

After completion, create `.planning/phases/06-mysql-production-setup/06-mysql-production-setup-01-SUMMARY.md` with:

- Queue jobs migration file path and timestamp
- Git commit hash
- Ready for production deployment note
