---
phase: 05-dashboards
plan: 02
type: execute
wave: 1
depends_on: []
files_modified: []
autonomous: true
requirements:
  - ADMIN-01
  - ADMIN-02
  - ADMIN-03
  - ADMIN-04
  - ADMIN-05
user_setup: []
must_haves:
  truths:
    - "Admin dashboard shows platform overview (users, revenue, cloud budget)"
    - "Admin can view all users in management table"
    - "Admin can manually adjust user credits from admin panel"
    - "Admin sees revenue breakdown by subscription tier"
    - "Admin dashboard displays cloud budget status with visual indicators"
  artifacts:
    - path: "resources/views/admin/dashboard.blade.php"
      provides: "Admin dashboard main view"
      min_lines: 80
    - path: "resources/views/admin/components/overview-cards.blade.php"
      provides: "Platform overview metrics"
      min_lines: 40
    - path: "resources/views/admin/components/revenue-breakdown.blade.php"
      provides: "Revenue by tier visualization"
      min_lines: 35
    - path: "resources/views/admin/components/cloud-budget-card.blade.php"
      provides: "Cloud budget status display"
      min_lines: 30
    - path: "resources/views/admin/users.blade.php"
      provides: "User management table"
      min_lines: 50
    - path: "resources/views/admin/components/adjust-credits-modal.blade.php"
      provides: "Manual credit adjustment"
      min_lines: 25
    - path: "app/Http/Controllers/AdminController.php"
      provides: "Admin dashboard controller"
      exports: ["dashboard", "users", "adjustCredits"]
    - path: "app/Http/Middleware/AdminMiddleware.php"
      provides: "Admin access control"
      exports: ["handle"]
  key_links:
    - from: "resources/views/admin/dashboard.blade.php"
      to: "resources/views/admin/components/overview-cards.blade.php"
      via: "Component inclusion"
      pattern: "@include.*overview-cards"
    - from: "resources/views/admin/components/revenue-breakdown.blade.php"
      to: "app/Http/Controllers/AdminController.php"
      via: "Revenue data"
      pattern: "revenueByTier"
    - from: "app/Http/Controllers/AdminController.php"
      to: "app/Http/Middleware/AdminMiddleware.php"
      via: "Middleware registration"
      pattern: "AdminMiddleware::class"
---

# Phase 5 Plan 02: Admin Dashboard

## Objective

Build the admin dashboard for platform monitoring and user management, including overview metrics, user management, and credit adjustment capabilities.

**Purpose:** Admins need visibility into platform health, revenue, and the ability to manage user credits manually. This plan delivers comprehensive admin monitoring tools.

**Output:**
- Admin dashboard with platform overview (users, revenue, cloud budget)
- User management table with search and filtering
- Manual credit adjustment functionality
- Revenue breakdown by subscription tier
- Cloud budget status with visual indicators

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `resources/views/admin/dashboard.blade.php` | Admin dashboard main layout |
| `resources/views/admin/components/overview-cards.blade.php` | Platform overview metrics |
| `resources/views/admin/components/revenue-breakdown.blade.php` | Revenue by tier visualization |
| `resources/views/admin/components/cloud-budget-card.blade.php` | Cloud budget status display |
| `resources/views/admin/users.blade.php` | User management table |
| `resources/views/admin/components/adjust-credits-modal.blade.php` | Manual credit adjustment |
| `app/Http/Controllers/AdminController.php` | Admin dashboard controller |
| `app/Http/Middleware/AdminMiddleware.php` | Admin access control middleware |
| `routes/web.php` | Admin routes |
| `app/Models/CloudBudget.php` | Cloud budget model |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create Admin Dashboard Views</name>
  <files>
    - resources/views/admin/dashboard.blade.php
    - resources/views/admin/components/overview-cards.blade.php
    - resources/views/admin/components/revenue-breakdown.blade.php
    - resources/views/admin/components/cloud-budget-card.blade.php
  </files>
  <action>
Create admin dashboard views:

1. **dashboard.blade.php** - Admin main layout:
   - Sidebar navigation (dashboard, users, revenue, settings)
   - Header with admin profile dropdown
   - Grid layout (4-column) for overview cards
   - Main content area for detailed views
   - Dark Luxury design system with gold accents
   - Responsive admin panel layout

2. **overview-cards.blade.php** - Platform overview:
   - Total users count (with trend indicator)
   - Today's revenue in KWD
   - Active subscriptions count
   - Cloud budget status (requests used/cap)
   - Gold gradient accents on primary metrics
   - Quick stats with color-coded trends

3. **revenue-breakdown.blade.php** - Revenue visualization:
   - Donut chart showing revenue by tier
   - Total revenue display
   - Legend with tier colors
   - Gold accent color for Enterprise
   - Tooltip showing tier and amount

4. **cloud-budget-card.blade.php** - Cloud budget status:
   - Progress bar showing daily cloud usage
   - Visual indicators (green/yellow/red)
   - Requests used / 500 cap display
   - Status badge (Active / Approaching Limit / Exceeded)
   - Gold accent on current usage value

All views use Dark Luxury design system with gold accents (#d4af37).
</action>
  <verify>
  ls resources/views/admin/ resources/views/admin/components/
  </verify>
  <done>
  - All 4 admin views created
  - Overview cards, revenue breakdown, cloud budget implemented
  - Dark Luxury design system applied
  </done>
</task>

<task type="auto">
  <name>Task 2: Create User Management Views</name>
  <files>
    - resources/views/admin/users.blade.php
    - resources/views/admin/components/adjust-credits-modal.blade.php
  </files>
  <action>
Create user management views:

1. **users.blade.php** - User management table:
   - Search and filter functionality
   - Table columns: User, Email, Phone, Tier, Credits, Status, Actions
   - Pagination support
   - Gold accent delete/edit buttons
   - Filter by tier and status
   - Empty state when no users

2. **adjust-credits-modal.blade.php** - Credit adjustment:
   - Modal popup for credit adjustment
   - Input field for credit amount
   - Positive for add, negative for subtract
   - Reason textarea (optional)
   - Gold gradient confirm button
   - Cancel button (ghost style)
   - Form validation

All views use Dark Luxury modal styling with gold accents.
</action>
  <verify>
  ls resources/views/admin/components/
  </verify>
  <done>
  - Users table view created
  - Adjust credits modal created
  - Search/filter functionality ready
  </done>
</task>

<task type="auto">
  <name>Task 3: Create Admin Controller and Middleware</name>
  <files>
    - app/Http/Controllers/AdminController.php
    - app/Http/Middleware/AdminMiddleware.php
    - routes/web.php
  </files>
  <action>
Create admin functionality:

1. **AdminController.php** - Admin dashboard controller:
   - dashboard() - Main admin dashboard
     - Get total users count
     - Get today's revenue
     - Get revenue by tier
     - Get cloud budget status
     - Get recent activity
   - users() - User management page
     - Get paginated users list
     - Support search by name/email/phone
     - Filter by tier and status
   - adjustCredits() - Manual credit adjustment
     - Validate credit amount
     - Update user credits
     - Log adjustment action
     - Return JSON response

2. **AdminMiddleware.php** - Admin access control:
   - check if user is admin (role = 'admin' or is_admin = true)
   - Redirect non-admins to dashboard
   - Allow access to admin routes

3. **routes/web.php** - Add admin routes:
```php
// Admin routes (admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/adjust-credits', [AdminController::class, 'adjustCredits'])->name('admin.adjust-credits');
});
```

All admin routes are protected by admin middleware.
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --name=admin 2>&1 | head -15
  </verify>
  <done>
  - AdminController created with dashboard, users, adjustCredits methods
  - AdminMiddleware created with role checking
  - Admin routes registered under /admin prefix
  </done>
</task>

<task type="auto">
  <name>Task 4: Create Cloud Budget Model</name>
  <files>
    - app/Models/CloudBudget.php
    - database/migrations/xxxx_create_cloud_budgets_table.php
  </files>
  <action>
Create cloud budget tracking:

1. **migration** (xxxx_create_cloud_budgets_table.php):
   - id (UUID primary)
   - date (date for daily tracking)
   - requests_used (integer, default 0)
   - cap (integer, default 500)
   - is_active (boolean, default true)
   - last_updated_at (timestamp)
   - created_at / updated_at

2. **CloudBudget.php** - Model:
   - Fillable fields
   - Cast date to date type
   - Scopes: scopeToday($query), scopeActive($query)
   - Method: incrementRequests() - increment and save
   - Method: remaining() - return cap - requests_used
   - Method: atLimit() - check if requests_used >= cap
   - Method: isApproachingLimit() - check if > 80% used

Run migration after creation.
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan migrate:fresh --force 2>&1
  </verify>
  <done>
  - CloudBudgets table created
  - CloudBudget model with helper methods
  - Migration ran successfully
  </done>
</task>

</tasks>

---

## Verification

### Phase 5 Plan 02 Complete When:
- [ ] Admin dashboard shows platform overview metrics
- [ ] Users table displays all users with search/filter
- [ ] Adjust credits modal allows manual credit changes
- [ ] Revenue breakdown shows tier distribution
- [ ] Cloud budget card shows usage status
- [ ] Visual indicators change based on budget status
- [ ] Admin middleware protects admin routes

### Success Criteria:
1. ✓ Admin dashboard shows platform overview (users, revenue, cloud budget)
2. ✓ Admin can view all users in management table
3. ✓ Admin can manually adjust user credits from admin panel
4. ✓ Admin sees revenue breakdown by subscription tier
5. ✓ Admin dashboard displays cloud budget status with visual indicators

---

## Wave Structure

| Wave | Plan | Dependencies | Notes |
|------|------|--------------|-------|
| 1 | 05-02 | 05-01 | Admin dashboard (can run parallel with 05-01) |

---

## Output

After completion, create `.planning/phases/05-dashboards/05-dashboards-02-SUMMARY.md` documenting:

```markdown
# Phase 5 Plan 02 Summary

## What Was Built
- Admin dashboard with platform overview
- User management table with search/filter
- Manual credit adjustment functionality
- Cloud budget tracking model
- Admin middleware for access control

## Files Created
- resources/views/admin/dashboard.blade.php
- resources/views/admin/components/overview-cards.blade.php
- resources/views/admin/components/revenue-breakdown.blade.php
- resources/views/admin/components/cloud-budget-card.blade.php
- resources/views/admin/users.blade.php
- resources/views/admin/components/adjust-credits-modal.blade.php
- app/Http/Controllers/AdminController.php
- app/Http/Middleware/AdminMiddleware.php
- app/Models/CloudBudget.php
- database/migrations/xxxx_create_cloud_budgets_table.php
- routes/web.php (updated)

## Next Steps
- Phase 5 Plan 03: Testing and Verification
```
