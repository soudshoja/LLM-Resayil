---
phase: 05-dashboards
plan: 03
type: execute
wave: 2
depends_on:
  - "05-dashboards-01"
  - "05-dashboards-02"
files_modified: []
autonomous: true
requirements:
  - TEAM-01
  - TEAM-02
  - TEAM-03
  - TEAM-04
user_setup: []
must_haves:
  truths:
    - "Enterprise owner can add team members via web interface"
    - "Team members have either admin or member role"
    - "Admins can remove team members from their team"
    - "Team dashboard is accessible only to Enterprise users with team access"
  artifacts:
    - path: "database/migrations/xxxx_create_team_members_table.php"
      provides: "Team members table with user relationships"
      contains: "create_team_members_table"
    - path: "app/Models/TeamMember.php"
      provides: "TeamMember model with user relationships"
      exports: ["user", "teamOwner", "scopeAdmin", "scopeMember"]
    - path: "app/Http/Controllers/TeamMemberController.php"
      provides: "Team member CRUD operations"
      exports: ["index", "store", "destroy"]
    - path: "resources/views/teams/dashboard.blade.php"
      provides: "Team management dashboard for Enterprise users"
      min_lines: 60
    - path: "resources/views/teams/components/add-member-form.blade.php"
      provides: "Form to add team members with role selection"
      min_lines: 40
    - path: "resources/views/teams/components/team-table.blade.php"
      provides: "Table displaying team members with actions"
      min_lines: 45
    - path: "resources/views/teams/components/role-badge.blade.php"
      provides: "Visual role indicator (admin/member)"
      min_lines: 25
  key_links:
    - from: "database/migrations/xxxx_create_team_members_table.php"
      to: "app/Models/TeamMember.php"
      via: "Model uses migration schema"
      pattern: "team_members"
    - from: "app/Http/Controllers/TeamMemberController.php"
      to: "app/Models/TeamMember.php"
      via: "TeamMember::where()->get()"
      pattern: "TeamMember::where\\('user_id"
    - from: "resources/views/teams/dashboard.blade.php"
      to: "app/Http/Middleware/EnterpriseMiddleware.php"
      via: "Route protection"
      pattern: "middleware.*enterprise"
---

# Phase 5 Plan 03: Enterprise Team Management

## Objective

Build the Enterprise team management functionality, allowing Enterprise users to add, manage, and remove team members with role-based access control (admin vs member).

**Purpose:** Enterprise customers need the ability to manage multiple users under their subscription, delegating administrative tasks to trusted team members while maintaining security through role-based permissions.

**Output:**
- Database migration for team_members table with UUID primary keys
- TeamMember Eloquent model with admin/member role scopes
- TeamMemberController with CRUD operations (index, store, destroy)
- Team dashboard view accessible to Enterprise users
- Team member management UI with role badges
- Middleware to protect Enterprise-only features

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `database/migrations/xxxx_create_team_members_table.php` | Team members table schema |
| `app/Models/TeamMember.php` | TeamMember model with relationships |
| `app/Http/Controllers/TeamMemberController.php` | Team member CRUD controller |
| `app/Http/Middleware/EnterpriseMiddleware.php` | Enterprise access control |
| `routes/web.php` | Team routes |
| `resources/views/teams/dashboard.blade.php` | Team management dashboard |
| `resources/views/teams/components/add-member-form.blade.php` | Add member form component |
| `resources/views/teams/components/team-table.blade.php` | Team members table component |
| `resources/views/teams/components/role-badge.blade.php` | Role badge component |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create team_members database migration</name>
  <files>
    - database/migrations/xxxx_create_team_members_table.php
  </files>
  <action>
Create the team_members migration file:

1. **Migration name**: `xxxx_create_team_members_table.php`

2. **Schema**:
   - `id` - UUID primary key
   - `team_owner_id` - Foreign to users (the Enterprise account that owns the team)
   - `member_user_id` - Foreign to users (the user added to the team)
   - `role` - Enum: 'admin', 'member' (default 'member')
   - `added_by_id` - Foreign to users (who added this member)
   - `joined_at` - Timestamp
   - `created_at` / `updated_at`

3. **Constraints**:
   - Unique constraint on (team_owner_id, member_user_id) - prevent duplicate team members
   - Foreign keys with cascade delete

4. **Run migration**: Execute after creating the file.
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan migrate:fresh --force 2>&1
  </verify>
  <done>
  - Migration file created with correct naming convention
  - team_members table exists in database
  - Unique constraint on (team_owner_id, member_user_id) enforced
  - Foreign keys properly configured
  </done>
</task>

<task type="auto">
  <name>Task 2: Create TeamMember Eloquent model</name>
  <files>
    - app/Models/TeamMember.php
  </files>
  <action>
Create the TeamMember model with proper relationships and scopes:

1. **Fillable attributes**: team_owner_id, member_user_id, role, added_by_id, joined_at

2. **Casts**: joined_at to datetime

3. **Relationships**:
   - `teamOwner()` - belongsTo(User::class, 'team_owner_id')
   - `member()` - belongsTo(User::class, 'member_user_id')
   - `addedBy()` - belongsTo(User::class, 'added_by_id')

4. **Scopes**:
   - `scopeAdmin($query)` - where('role', 'admin')
   - `scopeMember($query)` - where('role', 'member')
   - `scopeForOwner($query, $ownerId)` - where('team_owner_id', $ownerId)

5. **Helper methods**:
   - `is_admin()` - returns boolean based on role
   - `is_member()` - returns boolean based on role

All models use UUID primary keys (already configured via User model booted method).
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan tinker --execute="echo json_encode(array_keys(get_class_methods(\App\Models\TeamMember::class)));"
  </verify>
  <done>
  - TeamMember model created with all relationships
  - Admin and Member scopes implemented
  - Helper methods for role checking
  - UUID primary key support
  </done>
</task>

<task type="auto">
  <name>Task 3: Create TeamMemberController</name>
  <files>
    - app/Http/Controllers/TeamMemberController.php
  </files>
  <action>
Create the TeamMemberController with CRUD operations:

1. **index()** - List team members:
   - Get team_owner_id from authenticated user
   - Filter by team_owner_id = auth()->id()
   - Join with users table for member details
   - Return paginated list with member info and role
   - Return JSON for API requests

2. **store()** - Add team member:
   - Validate: member_email (required, email), role (required, in:admin,member)
   - Find user by email or phone
   - Check user is not already on team (unique constraint)
   - Check user is not trying to add themselves
   - Create TeamMember record
   - Return success with created member

3. **destroy($id)** - Remove team member:
   - Find team member by ID
   - Verify ownership: team_owner_id must be auth()->id()
   - Delete the team member
   - Return success response

All endpoints require authentication via session or API key.
  </action>
  <verify>
  curl -X GET http://localhost:8000/api/team/members -H "Authorization: Bearer {token}"
  curl -X POST http://localhost:8000/api/team/members -H "Authorization: Bearer {token}" -d '{"member_email":"test@example.com","role":"member"}'
  curl -X DELETE http://localhost:8000/api/team/members/{id} -H "Authorization: Bearer {token}"
  </verify>
  <done>
  - Team members list returns authenticated user's team
  - New member can be added with email and role
  - Member can be removed by owner
  - Duplicate prevention via unique constraint
  </done>
</task>

<task type="auto">
  <name>Task 4: Create Enterprise middleware</name>
  <files>
    - app/Http/Middleware/EnterpriseMiddleware.php
  </files>
  <action>
Create middleware to restrict features to Enterprise tier:

1. **handle()** - Check subscription tier:
   - Get authenticated user
   - Check subscription_tier equals 'enterprise'
   - Return 403 Forbidden if not Enterprise
   - Pass request through if Enterprise

2. **Register middleware** in app/Http/Kernel.php:
   - 'enterprise' => EnterpriseMiddleware::class

3. **Usage**: Apply to team routes and any other Enterprise-only features.

4. **Response format**: JSON for API requests, redirect for web requests.
  </action>
  <verify>
  php artisan route:list --path=team | grep middleware
  </verify>
  <done>
  - Middleware registered in Kernel.php
  - Enterprise-only routes protected
  - Non-Enterprise users receive 403 response
  </done>
</task>

<task type="auto">
  <name>Task 5: Create Team Dashboard View</name>
  <files>
    - resources/views/teams/dashboard.blade.php
    - resources/views/teams/components/add-member-form.blade.php
    - resources/views/teams/components/team-table.blade.php
    - resources/views/teams/components/role-badge.blade.php
  </files>
  <action>
Create team management views following Dark Luxury design system:

1. **dashboard.blade.php** - Main team management:
   - Header showing team owner name and Enterprise tier badge
   - Add member form (included from add-member-form.blade.php)
   - Team members table (included from team-table.blade.php)
   - Empty state message when no members
   - Navigation sidebar with team, billing, settings links

2. **add-member-form.blade.php** - Add member form:
   - Email input field (user can enter email or phone)
   - Role selector: admin or member (radio or select)
   - Submit button with gold gradient
   - Cancel button
   - Error display for validation

3. **team-table.blade.php** - Team members list:
   - Columns: Name, Email, Role, Added By, Joined Date, Actions
   - Role displayed via role-badge component
   - Admin-only actions (remove member) visible to team owner
   - Pagination support
   - Empty state when no members

4. **role-badge.blade.php** - Role indicator:
   - Admin: Gold gradient badge (#d4af37)
   - Member: Gray/white badge
   - Small, rounded pill shape
   - Bilingual text support

All views use Dark Luxury card styling with gold accents.
  </action>
  <verify>
  ls resources/views/teams/ resources/views/teams/components/
  </verify>
  <done>
  - All 4 team views created
  - Dark Luxury design system applied
  - Role badges implemented
  - Add member form functional
  - Team table with pagination
  </done>
</task>

<task type="auto">
  <name>Task 6: Configure team routes</name>
  <files>
    - routes/web.php
  </files>
  <action>
Configure team management routes in web.php:

1. **Team routes group** - Protected by auth and enterprise middleware:
```php
Route::middleware(['auth', 'enterprise'])->prefix('teams')->group(function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('teams.index');
    Route::post('/members', [TeamMemberController::class, 'store'])->name('teams.members.store');
    Route::delete('/members/{id}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
});
```

2. **Register controller** at top of routes file:
```php
use App\Http\Controllers\TeamMemberController;
```

3. **Add navigation link** to user dashboard sidebar for team management

4. **Enterprise tier check** - Only show team management link if user.subscription_tier == 'enterprise'
  </action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --path=teams
  </verify>
  <done>
  - Team routes registered under /teams prefix
  - Enterprise middleware applied to all routes
  - Navigation link added to user dashboard
  - Routes protected by authentication
  </done>
</task>

</tasks>

---

## Verification

### Phase 5 Plan 03 Complete When:
- [ ] team_members table created with proper schema
- [ ] TeamMember model with relationships and scopes works
- [ ] TeamMemberController index, store, destroy methods work
- [ ] Enterprise middleware protects team routes
- [ ] Team dashboard accessible to Enterprise users only
- [ ] Team members can be added with admin or member role
- [ ] Team members can be removed by team owner
- [ ] Role badges display correctly (gold for admin, gray for member)
- [ ] Duplicate team member prevention works

### Success Criteria:
1. ✓ Team members can be added by owner
2. ✓ Roles: admin vs member
3. ✓ Members can be removed by admin
4. ✓ Team dashboard accessible to Enterprise users

---

## Wave Structure

| Wave | Plan | Dependencies | Notes |
|------|------|--------------|-------|
| 1 | 05-01 | - | Landing page and user dashboard |
| 1 | 05-02 | - | Admin dashboard |
| 2 | 05-03 | 05-01, 05-02 | Enterprise team management |

---

## Output

After completion, create `.planning/phases/05-dashboards/05-dashboards-03-SUMMARY.md` documenting:

```markdown
# Phase 5 Plan 03 Summary

## What Was Built
- Team members database table with UUID primary keys
- TeamMember Eloquent model with admin/member role scopes
- TeamMemberController with full CRUD operations
- Enterprise middleware for tier-based access control
- Team management dashboard views
- Team member role badges
- Team routes with proper middleware

## Files Created
- database/migrations/xxxx_create_team_members_table.php
- app/Models/TeamMember.php
- app/Http/Controllers/TeamMemberController.php
- app/Http/Middleware/EnterpriseMiddleware.php
- resources/views/teams/dashboard.blade.php
- resources/views/teams/components/add-member-form.blade.php
- resources/views/teams/components/team-table.blade.php
- resources/views/teams/components/role-badge.blade.php
- routes/web.php (updated)

## Next Steps
- Phase 5 Plan 04: Final testing and verification
- Deploy to production
- Run user acceptance testing
```
