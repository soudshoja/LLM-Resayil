# Phase 7 Plan 07: UI Fixes + Billing Enhancements + Admin Unlimited Access

**Goal:** Parallel agent team to fix 5 issues discovered during browser QA on 2026-03-03.

**Execution:** Run all 3 agents in parallel. No dependencies between agents.

---

## Agent 1 — Homepage Pricing Redesign + Free Trial Button

**Files:**
- `resources/views/welcome.blade.php`

**Tasks:**

### Task 1A: Match homepage pricing to dashboard card style
The homepage pricing section currently uses a plain `pricing-grid` layout.
Replace it to match the dashboard's dark card style — same look as `/billing/plans`:
- Dark cards with border, tier name badge at top, price large, feature list with ✓ icons
- "Most Popular" badge on Basic, "Best Value" on Pro (matching billing/plans.blade.php)
- Keep existing prices: Starter 15 KWD, Basic 25 KWD, Pro 45 KWD (monthly)

Read `resources/views/billing/plans.blade.php` first to copy the exact card HTML/CSS structure.
Read `resources/views/welcome.blade.php` to locate the `<!-- Pricing -->` section.
Replace the pricing cards HTML only — keep the section wrapper and heading.

### Task 1B: Add Free Trial button next to Starter card
After the Starter card, add a "7-Day Free Trial" promo card (or modify the Starter card) to include:
- A green "FREE TRIAL" badge
- Text: "7-Day Free Trial — then 15 KWD/mo"
- Button: "Start Free Trial" → `/register`
- Style: dashed gold border to stand out from the paid plan cards

### Task 1C: Commit
```
git add resources/views/welcome.blade.php
git commit -m "feat: homepage pricing matches dashboard card style + free trial button"
```

---

## Agent 2 — Dashboard Layout Fix + Admin API Key Purchase

**Files:**
- `resources/views/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php` (or `resources/views/admin.blade.php` — check which exists)
- `app/Http/Controllers/AdminController.php`
- `routes/web.php`

### Task 2A: Fix dashboard layout — API Keys + Top Up Credits side by side
Currently the "API Keys" section and "Top Up Credits" section are stacked.

Read `resources/views/dashboard.blade.php` to find the section containing:
- The `#api-keys-section` or similar div
- The "Top Up Credits" card

Place them in a 2-column grid side by side:
```css
display: grid;
grid-template-columns: 1fr 1fr;
gap: 1.5rem;
```
For mobile (< 768px), stack them (single column).

Make sure the API Keys list doesn't overflow — add `overflow-x: auto` to the key list table if needed.

### Task 2B: Add API Key purchase to Admin panel
Admin at `/admin` should be able to purchase/create API keys for ANY user directly — not go through payment flow.

Since admin has infinite access, add a button "Create API Key" on the admin user list that:
- Calls an existing or new `POST /admin/users/{user}/keys` endpoint
- Creates an `ApiKeys` record directly (no payment, no limit check for admin)
- Returns success JSON

Read `app/Http/Controllers/AdminController.php` to see if `createApiKey()` already exists.
If it does, verify the route is wired and the admin users view has a button for it.
If the button is missing from the view, add it.

### Task 2C: Commit
```
git add resources/views/dashboard.blade.php resources/views/admin.blade.php app/Http/Controllers/AdminController.php routes/web.php
git commit -m "feat: dashboard 2-col layout for API keys + top-up, admin can create keys for any user"
```

---

## Agent 3 — Bug Fixes + Admin Unlimited Access

**Files:**
- `app/Services/BillingService.php`
- `app/Http/Controllers/Billing/PaymentController.php`
- `resources/views/billing/plans.blade.php`
- `resources/views/dashboard.blade.php`
- `config/models.php`
- `app/Http/Middleware/ApiKeyAuth.php` (or wherever admin bypass is checked)

### Task 3A: Fix Extra API Key — Enterprise tier + admin bypass
**Problem:** `additionalApiKeyCosts` has no 'enterprise' entry. Admin (enterprise) sees "0.000 KWD" button.

Fix 1 — Add enterprise to `additionalApiKeyCosts` in `BillingService.php`:
```php
'enterprise' => [
    2 => 0,   // 2nd key: free
    3 => 0,   // 3rd key: free
    4 => 2,   // 4th key: 2 KWD
    5 => 5,   // 5th key: 5 KWD
],
```

Fix 2 — In `PaymentController::initiateExtraKeyPayment()`, if the authenticated user is admin (`auth()->user()->email === 'admin@llm.resayil.io'`) OR if `$cost === 0.0`, skip the MyFatoorah invoice entirely and create the `ApiKeys` record directly, then redirect back with success.

Fix 3 — In `billing/plans.blade.php`, in the "Additional API Keys" card:
- If `$cost === 0.0` or user is admin, show button text "Create Free API Key" instead of "Buy Extra API Key — 0.000 KWD"
- Pass a flag from the controller or use inline Blade logic: `@if($nextKeyCost == 0) Create Free API Key @else Buy Extra API Key — {{ $nextKeyCost }} KWD @endif`

### Task 3B: Fix :cloud suffix showing in usage log
**Problem:** `usage_logs` stores internal Ollama name (e.g., `qwen3.5:cloud`) — shown raw on dashboard.

In `resources/views/dashboard.blade.php`, find the Recent API Usage table where model names are rendered.
Add a PHP helper or inline Blade filter to strip the `:cloud` suffix for display:
```blade
{{ str_replace(':cloud', '', $log->model) }}
```
Or if rendered via JS, in the JS table render function add:
```js
modelName.replace(':cloud', '').replace(/-cloud$/, '')
```

Find where model names are rendered (Blade or JS) and apply the strip.

### Task 3C: Fix smollm2:135m size classification
In `config/models.php`, find the entry for `smollm2:135m` (or `smollm2:135m` key).
Change `'size' => 'medium'` to `'size' => 'small'`.
135M parameters is unambiguously small.

### Task 3D: Admin unlimited access — no API key limits
**Problem:** Admin is on 'enterprise' tier but `additionalApiKeyCosts` caps enterprise keys. Admin should have NO cap.

In `PaymentController::initiateExtraKeyPayment()`:
```php
if (auth()->user()->email === 'admin@llm.resayil.io') {
    // Admin: create key directly, no payment, no limit
    ApiKeys::create([...]);
    return redirect()->back()->with('success', 'API key created.');
}
```

Also in `BillingService::getAdditionalApiKeyCost()`, add at top:
```php
// Admin has no key limit
if (auth()->check() && auth()->user()->email === 'admin@llm.resayil.io') {
    return 0.0;
}
```

And in the billing/plans view, hide the "X of N keys used" cap message for admin — show "Unlimited" instead.

### Task 3E: Commit
```
git add app/Services/BillingService.php app/Http/Controllers/Billing/PaymentController.php resources/views/billing/plans.blade.php resources/views/dashboard.blade.php config/models.php
git commit -m "fix: enterprise API key pricing, strip :cloud from usage log, smollm2 size, admin unlimited keys"
```

---

## Final Step (after all 3 agents complete): Deploy

```bash
git push
ssh whm-server "cd ~/llm.resayil.io && git stash && git pull && /opt/cpanel/ea-php82/root/usr/bin/php artisan config:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan route:clear && /opt/cpanel/ea-php82/root/usr/bin/php artisan view:clear"
```

Then verify in Chrome:
1. `/` — pricing section matches billing/plans style, free trial card visible next Starter
2. `/dashboard` — API Keys + Top Up Credits side by side, no :cloud in usage log
3. `/billing/plans` — Admin sees "Unlimited" keys, "Create Free API Key" button works
4. `config/models.php` — smollm2 is small
