---
phase: 05-dashboards
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: []
autonomous: true
requirements:
  - LP-01
  - LP-02
  - LP-03
  - LP-04
  - LP-05
  - LP-06
  - DASH-01
  - DASH-02
  - DASH-03
  - DASH-04
  - DASH-05
user_setup: []
must_haves:
  truths:
    - "Visitor sees hero section with value proposition and sign-up CTA"
    - "Landing page shows how it works in 4 steps"
    - "Pricing section displays 3 tiers (Basic/Pro/Enterprise) with KWD pricing"
    - "Model list section shows accessible models per tier"
    - "API code example section demonstrates usage"
    - "Sign-up CTA redirects to registration page"
    - "User dashboard displays credits remaining and total in clear display"
    - "Usage chart shows requests over time"
    - "API keys list shows all keys with quick delete actions"
    - "Quick top-up button visible on user dashboard"
    - "Usage table shows model breakdown per request"
  artifacts:
    - path: "resources/views/landing/hero.blade.php"
      provides: "Hero section with bilingual text"
      min_lines: 40
    - path: "resources/views/landing/how-it-works.blade.php"
      provides: "4-step process explanation"
      min_lines: 30
    - path: "resources/views/landing/pricing.blade.php"
      provides: "3-tier pricing cards"
      min_lines: 50
    - path: "resources/views/landing/models.blade.php"
      provides: "Model list per tier"
      min_lines: 40
    - path: "resources/views/landing/code-example.blade.php"
      provides: "API usage code snippet"
      min_lines: 35
    - path: "resources/views/dashboard/user.blade.php"
      provides: "User dashboard main view"
      min_lines: 80
    - path: "resources/views/dashboard/components/credit-card.blade.php"
      provides: "Credit display component"
      min_lines: 25
    - path: "resources/views/dashboard/components/usage-chart.blade.php"
      provides: "Chart.js integration for usage visualization"
      min_lines: 30
    - path: "resources/views/dashboard/components/api-keys-table.blade.php"
      provides: "API keys list with delete actions"
      min_lines: 40
    - path: "resources/views/dashboard/components/top-up-section.blade.php"
      provides: "Quick top-up button and options"
      min_lines: 30
    - path: "resources/views/dashboard/components/usage-table.blade.php"
      provides: "Model breakdown usage table"
      min_lines: 45
    - path: "resources/js/charts.js"
      provides: "Chart.js configuration"
      min_lines: 30
  key_links:
    - from: "resources/views/landing/hero.blade.php"
      to: "/register"
      via: "Link to registration"
      pattern: "href=.*register"
    - from: "resources/views/dashboard/user.blade.php"
      to: "resources/views/dashboard/components/credit-card.blade.php"
      via: "Component inclusion"
      pattern: "@include.*credit-card"
    - from: "resources/views/dashboard/components/usage-chart.blade.php"
      to: "resources/js/charts.js"
      via: "Chart.js initialization"
      pattern: "new Chart.*usageChart"
    - from: "resources/views/dashboard/components/api-keys-table.blade.php"
      to: "routes/web.php"
      via: "Delete route"
      pattern: "api-keys.*DELETE"
---

# Phase 5 Plan 01: Landing Page and User Dashboard

## Objective

Build the landing page and user dashboard for the LLM Resayil Portal, implementing all marketing and user-facing dashboard components.

**Purpose:** This plan delivers the public-facing landing page that converts visitors and the user dashboard where customers view their credits, usage, and manage API keys.

**Output:**
- Complete landing page with Hero, How It Works, Pricing, Models, Code Examples sections
- User dashboard with credits display, usage charts, API keys list, and quick top-up
- Dark Luxury design system implementation with RTL support

---

## Files to Create/Modify

| File | Purpose |
|------|---------|
| `resources/views/landing/hero.blade.php` | Hero section with bilingual text and CTA |
| `resources/views/landing/how-it-works.blade.php` | 4-step process explanation |
| `resources/views/landing/pricing.blade.php` | 3-tier pricing cards (Basic/Pro/Enterprise) |
| `resources/views/landing/models.blade.php` | Model list per tier |
| `resources/views/landing/code-example.blade.php` | API usage code snippet |
| `resources/views/dashboard/user.blade.php` | User dashboard main layout |
| `resources/views/dashboard/components/credit-card.blade.php` | Credit display component |
| `resources/views/dashboard/components/usage-chart.blade.php` | Chart.js integration |
| `resources/views/dashboard/components/api-keys-table.blade.php` | API keys list with actions |
| `resources/views/dashboard/components/top-up-section.blade.php` | Quick top-up button |
| `resources/views/dashboard/components/usage-table.blade.php` | Model breakdown table |
| `resources/js/charts.js` | Chart.js configuration |
| `app/Http/Controllers/LandingController.php` | Landing page controller |
| `app/Http/Controllers/DashboardController.php` | Dashboard controller |
| `routes/web.php` | Landing and dashboard routes |

---

## Tasks

<tasks>

<task type="auto">
  <name>Task 1: Create Landing Page Views</name>
  <files>
    - resources/views/landing/hero.blade.php
    - resources/views/landing/how-it-works.blade.php
    - resources/views/landing/pricing.blade.php
    - resources/views/landing/models.blade.php
    - resources/views/landing/code-example.blade.php
  </files>
  <action>
Create landing page blade components following the Dark Luxury design system:

1. **hero.blade.php** - Hero section:
   - Full-width gradient background (bg-gradient-to-r from-[#0f1115] to-[#0a0d14])
   - Split layout: Text (left) + Visual (right)
   - Gold accent button with gradient background
   - Bilingual text (Arabic RTL, English LTR)
   - Sign-up CTA linking to /register

2. **how-it-works.blade.php** - 4-step process:
   - Horizontal numbered steps on desktop
   - Icons with gold accents (#d4af37)
   - Card-based layout for each step
   - Responsive grid (cols-1 on mobile, cols-4 on desktop)

3. **pricing.blade.php** - 3-tier pricing:
   - Basic, Pro, Enterprise tiers in grid
   - Gold highlighting on Enterprise tier
   - Feature checklist with checkmarks
   - KWD currency display
   - CTA buttons on each card
   - Highlight "Most Popular" badge on Pro tier

4. **models.blade.php** - Model list:
   - Tier-based organization (Basic, Pro, Enterprise)
   - Local vs cloud model indicators
   - Restricted models marked as "Internal Only"
   - Card layout for each model

5. **code-example.blade.php** - API example:
   - Code snippet with syntax highlighting
   - cURL example for API call
   - Change language toggle (JavaScript, Python)
   - Dark code background with gold accents

All views must include:
- Dark Luxury card styling with gradient backgrounds
- Gold accent color (#d4af37) for primary elements
- RTL support via dir="rtl" on Arabic sections
- Responsive design with Tailwind CSS
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan view:clear && ls resources/views/landing/
  </verify>
  <done>
  - All 5 landing page views created
  - Files exist with correct naming convention
  - Views follow Dark Luxury design system
  </done>
</task>

<task type="auto">
  <name>Task 2: Create Dashboard Component Views</name>
  <files>
    - resources/views/dashboard/components/credit-card.blade.php
    - resources/views/dashboard/components/usage-chart.blade.php
    - resources/views/dashboard/components/api-keys-table.blade.php
    - resources/views/dashboard/components/top-up-section.blade.php
    - resources/views/dashboard/components/usage-table.blade.php
  </files>
  <action>
Create dashboard component views for user dashboard:

1. **credit-card.blade.php** - Credit display:
   - Large credit count display (remaining of total)
   - Circular progress bar showing percentage
   - Gold accent color for current tier highlight
   - Quick top-up link button

2. **usage-chart.blade.php** - Usage visualization:
   - Chart.js integration
   - Line/bar chart showing requests over time (last 30 days)
   - Custom Dark Luxury chart colors
   - Responsive container with height: 300px
   - Tooltip with request count and model breakdown

3. **api-keys-table.blade.php** - API keys list:
   - Table with: Name, Prefix (first 12 chars), Permissions, Last Used, Created
   - Quick actions: View (modal), Edit, Delete
   - Gold accent delete button with ghost styling
   - Empty state when no keys exist

4. **top-up-section.blade.php** - Quick top-up:
   - Primary gold gradient button "Quick Top-Up"
   - Display available packs (5K/15K/50K credits)
   - Price in KWD with discount highlighting
   - Links to /billing/topup route

5. **usage-table.blade.php** - Model breakdown:
   - Columns: Date, Model, Requests, Tokens, Cost (credits)
   - Model names with tier badges (local/cloud)
   - Sortable columns
   - Pagination support
   - Gold accent on model type badges

All components use:
- Card styling from design system
- Consistent spacing (16px padding)
- Gold accent color (#d4af37)
- Responsive Tailwind grid classes
</action>
  <verify>
  ls resources/views/dashboard/components/
  </verify>
  <done>
  - All 5 component views created
  - Components follow Dark Luxury card design
  - Chart.js integration prepared
  </done>
</task>

<task type="auto">
  <name>Task 3: Create Dashboard Controller and Routes</name>
  <files>
    - resources/views/dashboard/user.blade.php
    - app/Http/Controllers/DashboardController.php
    - routes/web.php
  </files>
  <action>
Create the dashboard user interface and controller:

1. **user.blade.php** - User dashboard main view:
   - Navigation sidebar (dashboard, API keys, billing, settings)
   - Header with user profile dropdown
   - Grid layout (3-column) for credit card, usage chart, top-up section
   - Usage table below with pagination
   - Dashboard breadcrumb navigation
   - Include all component views

2. **DashboardController.php** - Dashboard controller:
   - index() - Display user dashboard
     - Get user's credits remaining and total
     - Get subscription tier and expiry
     - Get API keys count
     - Get recent usage data (last 30 days)
     - Pass to view with collect() for model breakdown
   - apiKeys() - Display API keys page
   - topUp() - Display top-up options
   - usageHistory() - AJAX endpoint for usage data

3. **routes/web.php** - Add dashboard routes:
```php
// Dashboard (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api-keys', [DashboardController::class, 'apiKeys'])->name('dashboard.api-keys');
    Route::get('/dashboard/top-up', [DashboardController::class, 'topUp'])->name('dashboard.top-up');
    Route::get('/dashboard/usage', [DashboardController::class, 'usageHistory'])->name('dashboard.usage');
});
```

All dashboard views use the Dark Luxury design system with gold accents.
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --name=dashboard 2>&1 | head -20
  </verify>
  <done>
  - DashboardController created with index, apiKeys, topUp methods
  - User dashboard view created with all components
  - Routes registered and accessible
  </done>
</task>

<task type="auto">
  <name>Task 4: Create Chart.js Configuration</name>
  <files>
    - resources/js/charts.js
  </files>
  <action>
Create Chart.js configuration for dashboard charts:

1. **charts.js** - Chart configuration:
   - Chart.js global configuration with Dark Luxury theme
   - Custom color palette matching design system:
     - Primary: #d4af37 (gold)
     - Background: #0f1115 (dark)
     - Text: #e0e5ec (light)
   - Usage chart helper function:
     - Line chart for requests over time
     - X-axis: dates (last 30 days)
     - Y-axis: request count
     - Model breakdown in tooltip
   - Responsive configuration
   - Dark mode chart styling

   ```javascript
   // Global config
   Chart.defaults.font.family = "'Inter', 'Tajawal', sans-serif";
   Chart.defaults.color = '#e0e5ec';
   Chart.defaults.backgroundColor = '#0f1115';

   // Custom plugins for chart enhancements
   ```

2. **Include in layout** - Update app layout to include Chart.js and charts.js
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && cat resources/js/charts.js | head -50
  </verify>
  <done>
  - charts.js created with Dark Luxury theme
  - Chart.js global configuration applied
  - Usage chart helper function ready
  </done>
</task>

<task type="auto">
  <name>Task 5: Create Landing Page Controller</name>
  <files>
    - app/Http/Controllers/LandingController.php
    - routes/web.php
  </files>
  <action>
Create landing page controller and route:

1. **LandingController.php** - Landing page controller:
   - index() - Display landing page
     - Get pricing tiers data (from database or config)
     - Get model access per tier
     - Pass to landing page view

2. **routes/web.php** - Add landing route:
```php
// Public routes
Route::get('/', [LandingController::class, 'index'])->name('landing');
```

3. **Update app layout** - Add navigation links:
   - Home (landing page)
   - Login / Register
   - Dashboard (visible when authenticated)

All landing page views reference this controller for data.
</action>
  <verify>
  cd /home/soudshoja/LLM-Resayil && php artisan route:list --name=landing
  </verify>
  <done>
  - LandingController created with index method
  - Landing page route registered
  - Public navigation links added to layout
  </done>
</task>

</tasks>

---

## Verification

### Phase 5 Plan 01 Complete When:
- [ ] Landing page hero section displays with bilingual text and CTA
- [ ] "How It Works" section shows 4 steps with icons
- [ ] Pricing section shows 3 tiers with KWD pricing
- [ ] Model list section displays accessible models per tier
- [ ] Code example section shows API usage snippet
- [ ] Sign-up CTA on landing page links to /register
- [ ] User dashboard shows credits remaining and total
- [ ] Usage chart displays requests over time (Chart.js)
- [ ] API keys list shows keys with delete actions
- [ ] Quick top-up button visible on dashboard
- [ ] Usage table shows model breakdown

### Success Criteria:
1. ✓ Landing page hero section with value proposition
2. ✓ How it works section with 3-4 steps
3. ✓ Pricing section with 3 tiers
4. ✓ Model list section
5. ✓ API code example section
6. ✓ Sign-up CTA redirects to registration
7. ✓ Credits remaining and total displayed
8. ✓ Usage chart shows requests over time
9. ✓ API keys list with quick delete actions
10. ✓ Quick top-up button visible
11. ✓ Usage table with model breakdown

---

## Wave Structure

| Wave | Plan | Tasks | Notes |
|------|------|-------|-------|
| 1 | 05-01 | 5 | Landing page and user dashboard views, controllers, charts |

---

## Output

After completion, create `.planning/phases/05-dashboards/05-dashboards-01-SUMMARY.md` documenting:

```markdown
# Phase 5 Plan 01 Summary

## What Was Built
- Landing page components (hero, how-it-works, pricing, models, code)
- User dashboard with all required views
- Chart.js configuration for usage visualization
- Dashboard controller with data fetching
- Landing page controller

## Files Created
- resources/views/landing/hero.blade.php
- resources/views/landing/how-it-works.blade.php
- resources/views/landing/pricing.blade.php
- resources/views/landing/models.blade.php
- resources/views/landing/code-example.blade.php
- resources/views/dashboard/user.blade.php
- resources/views/dashboard/components/credit-card.blade.php
- resources/views/dashboard/components/usage-chart.blade.php
- resources/views/dashboard/components/api-keys-table.blade.php
- resources/views/dashboard/components/top-up-section.blade.php
- resources/views/dashboard/components/usage-table.blade.php
- resources/js/charts.js
- app/Http/Controllers/DashboardController.php
- app/Http/Controllers/LandingController.php
- routes/web.php (updated)

## Next Steps
- Phase 5 Plan 02: Admin Dashboard
- Phase 5 Plan 03: Testing and Verification
```
