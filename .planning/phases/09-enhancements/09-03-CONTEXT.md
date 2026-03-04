# Phase 9-03: Full Arabic RTL Implementation - Context

**Gathered:** 2026-03-04
**Status:** Ready for planning
**Source:** User request - complete Arabic RTL website implementation

## Phase Boundary

Implement full Arabic RTL (Right-to-Left) support across the entire website. This includes:
1. All pages and views
2. Navigation and menus
3. Forms and buttons
3. Dashboard and API sections
4. Documentation and billing pages

## Current State Assessment

### What's Already Done (ARABIC-01 through ARABIC-06)
According to MEMORY.md:
- ✅ RTL middleware working
- ✅ Locale route configured
- ✅ `dir="rtl"` attribute on HTML
- ✅ Tajawal font loaded
- ✅ Lang files complete for: navigation, dashboard, billing, auth, profile, welcome, credits, validation
- ❌ Remaining: `billing/plans`, `payment-methods`, `credits.blade.php`, `about`, `contact`, `privacy`, `terms`

### What Needs to Be Done
1. ** Translate all remaining Blade files with `__()` wrapping
2. ** Add missing language keys
3. ** Verify RTL rendering in Arabic
4. ** Fix any layout issues
5. ** Ensure consistent language switching

## Requirements

### ARABIC-01: Language Switching
- [ ] Toggle between EN/AR in navbar
- [ ] Route-based locale switching (`/locale/en`, `/locale/ar`)
- [ ] Persistent language preference (session or cookie)
- [ ] Auto-detect user browser language (optional)

### ARABIC-02: Navigation
- [ ] All menu items translated
- [ ] Submenu items translated
- [ ] Mobile menu translated
- [ ] Breadcrumb navigation RTL

### ARABIC-03: Auth Pages
- [ ] Login page
- [ ] Register page
- [ ] Forgot Password page
- [ ] Reset Password page
- [ ] Email verification

### ARABIC-04: Dashboard Pages
- [ ] `/dashboard` - Main dashboard
- [ ] `/api-keys` - API Keys management
- [ ] `/profile` - User profile
- [ ] `/billing/plans` - Subscription plans
- [ ] `/billing/payment-methods` - Payment methods
- [ ] `/credits` - How credits work
- [ ] `/docs` - Documentation

### ARABIC-05: Legal Pages
- [ ] `/about` - About page
- [ ] `/contact` - Contact page
- [ ] `/privacy` - Privacy policy
- [ ] `/terms` - Terms of service

### ARABIC-06: Landing Page
- [ ] Hero section
- [ ] Features section
- [ ] Pricing section
- [ ] Models section
- [ ] FAQ section
- [ ] Footer

### ARABIC-07: RTL Layout Adjustments
- [ ] Card margins/paddings reversed
- [ ] Icon positions adjusted
- [ ] Tables with RTL column order
- [ ] Forms with RTL label positioning
- [ ] Buttons with RTL icon placement
- [ ] Charts/diagrams orientation (if needed)

### ARABIC-08: Content Translation Quality
- [ ] All text translated professionally
- [ ] Technical terms correctly localized
- [ ] Currency shown in KWD format
- [ ] Dates in Arabic format
- [ ] Numbers in Arabic numerals (or Latin? Decide)

## Implementation Plan

### Phase Structure
**Wave 1: Content Translation**
- Translate all Blade views
- Add missing language keys
- Create/update language files

**Wave 2: RTL Layout Fixes**
- Fix card layouts
- Adjust tables
- Verify form elements
- Test on mobile

**Wave 3: Language Switcher**
- Verify EN/AR toggle works
- Test route switching
- Check session persistence

### Files to Create/Modify

#### New Language Keys Needed
`resources/lang/ar/` and `resources/lang/en/`:

```php
// billing.php
'plans' => 'الخطط',
'plan_details' => 'تفاصيل الخطة',
'subscribe_now' => 'اشترك الآن',
'current_plan' => 'الخطة الحالية',
'expires_on' => 'تنتهي في',
'top_up' => 'إعادة شحن الرصيد',
'credits_pack' => 'حزمة رصيد',
'bonus' => '/+ % مكافأة',
'payment_method' => 'طريقة الدفع',
'add_payment' => 'إضافة طريقة دفع',
'enter_card' => 'أدخل رقم البطاقة',
'expiry_date' => 'تاريخ الانتهاء',
'cvv' => 'CVV',
'pay_now' => 'دفع الآن',
'recent_purchases' => 'المشتريات الأخيرة',

// legal.php
'about' => 'من نحن',
'contact' => 'اتصل بنا',
'privacy' => 'سياسة الخصوصية',
'terms' => 'الشروط والأحكام',
'company_name' => 'LLM Resayil',
'address' => 'العنوان',
'email' => 'البريد الإلكتروني',
'phone' => 'الهاتف',
'copyright' => 'حقوق النشر',

// about.php
'about_title' => 'من نحن',
'about_desc' => 'نبذة عن منصتنا...',
'mission' => 'رسالتنا',
'vision' => 'رؤيتنا',
'team' => 'فريق العمل',
```

#### Files to Modify
```
resources/views/
├── welcome.blade.php          # Add __() to all static text
├── docs.blade.php             # Add __() to documentation
├── billing/
│   ├── plans.blade.php        # PLANS - Needs full RTL
│   └── payment-methods.blade.php  # PAYMENT - Needs full RTL
├── profile.blade.php          # Already in MEMORY as complete
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── ...
├── layouts/
│   └── app.blade.php          # Navbar, footer
└── ...

resources/lang/
├── ar/
│   ├── navigation.php
│   ├── dashboard.php
│   ├── billing.php            # Create
│   ├── auth.php
│   ├── profile.php
│   ├── credits.php
│   ├── welcome.php
│   ├── legal.php              # Create
│   └── about.php              # Create
└── en/
    ├── ... (all existing)
```

### RTL-Specific CSS Adjustments

```css
/* In app.blade.php or global CSS */
[dir="rtl"] .card { padding: 1.5rem; }
[dir="rtl"] .btn { padding: 0.5rem 1.25rem; }
[dir="rtl"] .nav-links { flex-direction: row-reverse; }
[dir="rtl"] table { text-align: right; }
[dir="rtl"] .form-label { text-align: right; }
[dir="rtl"] .form-input { padding-left: 1rem; padding-right: 0.65rem; }
[dir="rtl"] .badge { margin-left: 0.5rem; margin-right: 0; }
[dir="rtl"] .flex-row-reverse { flex-direction: row; }
[dir="rtl"] .mr-2 { margin-left: 0.5rem; margin-right: 0; }
[dir="rtl"] .ml-2 { margin-right: 0.5rem; margin-left: 0; }
[dir="rtl"] .text-right { text-align: left; }
[dir="rtl"] .text-left { text-align: right; }
```

## Technical Considerations

### 1. Arabic Numerals vs Eastern Arabic Numerals
**Decision needed:** Should numbers show as `123` or `١٢٣`?
- Most Arabic websites use Eastern Arabic numerals
- Laravel's `numfmt_format` can handle this
- Suggestion: Use Eastern Arabic for all numbers in Arabic mode

### 2. Date Format
Arabic date format: `اليوم، ٤ مارس ٢٠٢٦`
- Use Laravel's date localization
- Format: `D M Y` → `٤ مارس ٢٠٢٦`

### 3. Currency Format
- Format: `٢.٠٠٠ د.ك` (KWD with Arabic thousands separator)
- Use `number_format()` with Arabic locale

### 4. Text Direction in Mixed Content
Some content should remain LTR in Arabic:
- API code snippets
- URLs
- Email addresses
- Programming terms

### 5. Font Loading
Current: Inter (Latin) + Tajawal (Arabic)
- Tajawal should cover Arabic
- Check for proper Arabic character support

## Success Criteria

1. **Complete Translation:** All user-facing text is in Arabic when language is AR
2. **Proper RTL:** All layouts render correctly with `dir="rtl"`
3. **Consistent Switching:** Language toggle works on all pages
4. **Mobile Friendly:** RTL works on mobile devices
5. **No English Stray:** No English text visible in Arabic mode
6. **Accurate Numbers:** Numbers displayed correctly in Arabic format

## Deferred Items

- Auto-detect browser language on first visit
- RTL for admin panel (optional, admin likely prefers English)
- PDF export with Arabic support
- Arabic voice navigation (future)

---

*Phase: 09-03*
*Context gathered: 2026-03-04 from user session*
