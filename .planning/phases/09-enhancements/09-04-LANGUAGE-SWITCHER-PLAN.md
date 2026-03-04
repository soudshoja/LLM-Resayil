# LLM Resayil - Language Switcher Implementation Plan

## Current State
- Language switcher exists but uses full page reload via POST form
- Route exists at `/locale/{locale}` (GET method)
- Switcher submits to `/language/ar` which doesn't exist (fixed to `/locale/ar`)

## Goal
Implement SPA-style language switching that:
1. Changes language without full page reload
2. Updates all text content dynamically
3. Maintains current page state

## Implementation Approach

### Option A: AJAX Page Update (Recommended)
```
1. Language switcher sends POST to /locale/{lang}
2. Server returns partial page content in requested language
3. Client updates DOM with new content
4. Update document.dir for RTL/LTR
```

### Option B: Client-Side Translation
```
1. Load all translations in JS on page load
2. Language switcher triggers JS function
3. JS replaces all textContent with translated strings
4. Update document.dir for RTL/LTR
```

### Option C: Minimal Reload (Simplest)
```
1. Language switcher triggers reload with locale parameter
2. Laravel returns page in new locale
3. Minimal visual disruption (cache-friendly)
```

## Recommended: Option A (AJAX Partial Update)

### Backend Changes
```php
// routes/web.php
Route::post('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale');

// Add AJAX route
Route::post('/locale/ajax/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return response()->json(['locale' => $locale]);
})->name('locale.ajax');
```

### Frontend Changes
```javascript
async function setLanguage(lang) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    await fetch(`/locale/ajax/${lang}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ locale: lang })
    });

    // Update document direction
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';

    // Update button text
    document.querySelector('.lang-btn .lang-text').textContent = lang === 'ar' ? 'AR' : 'EN';

    // Reload page for content updates
    location.reload();
}
```

## Summary for Next Session
- Use AJAX for locale change (minimal overhead)
- Keep page reload for content updates (simplest reliable approach)
- Fix underline issues by checking nav and footer styles
- Add official SVG logos to hero slider
- Improve slider with animations and counter

## Files to Modify
1. `routes/web.php` - Add AJAX locale route
2. `resources/views/layouts/app.blade.php` - Fix language switcher JS
3. `resources/views/welcome.blade.php` - Add logos, improve slider
