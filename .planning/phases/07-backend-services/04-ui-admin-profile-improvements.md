# Phase 7 Plan 04: UI/Admin/Profile Improvements

**Goal:** Polish the model catalog UI, fix admin routing, add user profile management, and remove third-party branding.

**Status:** COMPLETE

---

## Changes Made

### Dynamic Model Loading

`ModelsController` was fully rewritten to query `GET {OLLAMA_GPU_URL}/api/tags` live at request time instead of reading from the static `config/models.php` registry.

- On success: returns all models currently loaded in Ollama with inferred metadata
- On failure (Ollama unreachable): falls back gracefully to `config/models.php`
- `resolveModelDynamically()` method added to replace static registry lookup
- `ChatCompletionsController` updated to call `resolveModelDynamically()` instead of the old static registry approach
- `inferFamily()`, `inferCategory()`, `inferSize()`, and `inferType()` helper methods parse model names to produce metadata without requiring manual config entries
- HuggingFace models (names containing `/`) handled: `inferFamily()` extracts the last path segment (e.g. `hf.co/Qwen/Qwen3-VL-32B` → family "Qwen")

---

### Category Tags + Admin Filters

**Dashboard model catalog:**
- Six category tags added with emoji badges:
  - Chat (emoji badge)
  - Code (emoji badge)
  - Embedding (emoji badge)
  - Vision (emoji badge)
  - Thinking (emoji badge)
  - Tools (emoji badge)
- Categories inferred from model name patterns (e.g. "coder" → code, "vl"/"vision" → vision, "embed" → embedding)
- Type filter (Local / Cloud) hidden from regular users — visible only when `is_admin` is true in the session

**Admin models page (`/admin/models`):**
- Page now loads live from Ollama via the same `/api/v1/models` backend
- Filters added: Family dropdown, Category dropdown, Type (local/cloud), Size (small/medium/large), text search
- `applyFilters()` JS function replaces previous search-only logic — all filter dimensions applied client-side simultaneously
- Category column added to the model table with emoji badge display

---

### Admin Models Routing Fix

Changed `PUT /admin/models/{id}` to `POST /admin/models/update` with `model_id` passed in the request body.

**Problem:** Model IDs can contain forward slashes (e.g. `hf.co/Qwen/Qwen3-VL-32B`). When used as a URL segment, Laravel's router misinterprets slashes as additional path segments, producing 404 or route-mismatch errors even with `->where('id', '.+')`.

**Fix:** Route changed to `POST /admin/models/update` with no URL parameter. The `model_id` is submitted as a form field in the request body, bypassing all URL encoding issues entirely.

---

### Profile Page

New profile management page added for authenticated (session) users.

- Route: `GET /profile` — renders profile edit form
- Route: `POST /profile` — handles name/email update
- Route: `POST /profile/password` — handles password change (requires current password confirmation)
- Controller: `app/Http/Controllers/ProfileController.php`
  - `show()` renders the view
  - `update()` validates + saves name/email
  - `updatePassword()` validates current password with `Hash::check()`, then saves new bcrypt hash
- View: `resources/views/profile.blade.php` — dark luxury theme, two sections (account info / change password), inline validation error display
- "Profile" link added to the main navigation bar for all logged-in users

---

### MyFatoorah Branding Removal

All visible references to "MyFatoorah" removed from user-facing pages. Replaced with "KNET / credit card" as the payment method label.

**Files updated:**
- `resources/views/welcome.blade.php` — payment method description
- `resources/views/billing/plans.blade.php` — checkout button labels and payment info text
- `resources/views/dashboard.blade.php` — top-up section payment method copy

The underlying payment gateway integration code was not changed — only the display strings shown to end users.

---

### HuggingFace Family Inference Fix

`inferFamily()` in `ModelsController` previously extracted family from the first token of the model name (split on `:` and `-`). This failed for HuggingFace-style names that include a full repository path (e.g. `hf.co/Qwen/Qwen3-VL-32B:Q4_K_M`).

**Fix:** Before applying the token-split logic, the method now checks whether the model name contains `/`. If so, it takes the last path segment (the portion after the final `/`) and uses that as the base for family extraction. This correctly maps `hf.co/Qwen/Qwen3-VL-32B` to family "Qwen" instead of "hf.co".
