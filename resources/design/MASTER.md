# Design System: LLM Resayil Portal

**Selected Style:** Option 1 - Dark Luxury - Gulf B2B Professional

## Color Palette

### Primary Colors
| Color | Hex | Usage |
|-------|-----|-------|
| Deep Charcoal | `#0f1115` | Main background, cards |
| Dark Navy | `#0a0d14` | Secondary background |
| Gold Accent | `#d4af37` | Primary buttons, highlights |
| Gold Light | `#eac558` | Hover states, gradients |
| Slate | `#1a1d24` | Borders, dividers |

### Secondary Colors
| Color | Hex | Usage |
|-------|-----|-------|
| Muted Gold | `#8a702a` | Disabled states |
| Light Gray | `#e0e5ec` | Text on dark backgrounds |
| Muted Gray | `#a0a8b5` | Secondary text |
| Dark Gray | `#6b7280` | Tertiary text |

### Semantic Colors
| Color | Hex | Usage |
|-------|-----|-------|
| Success | `#059669` | Success messages |
| Warning | `#f59e0b` | Warning alerts |
| Error | `#ef4444` | Error states |
| Info | `#3b82f6` | Informational |

---

## Typography

### Arabic Fonts
| Font | Weight | Usage |
|------|--------|-------|
| Tajawal | 400 | Body text |
| Tajawal | 500 | Headings |
| Tajawal | 700 | emphasized text |
| Tajawal | 900 | Hero titles |

### English Fonts
| Font | Weight | Usage |
|------|--------|-------|
| Inter | 400 | Body text |
| Inter | 500 | Headings |
| Inter | 600 | Subheadings |
| Inter | 700 | Emphasized text |

### Font Sizes

**Body Text:**
- Small: 14px (0.875rem)
- Base: 16px (1rem)
- Large: 18px (1.125rem)

**Headings:**
- H1: 32px (2rem) - 36px (2.25rem)
- H2: 24px (1.5rem) - 28px (1.75rem)
- H3: 20px (1.25rem) - 24px (1.5rem)
- H4: 18px (1.125rem) - 20px (1.25rem)

---

## Components

### Cards
```css
.card {
  background: linear-gradient(135deg, #0f1115 0%, #0a0d14 100%);
  border: 1px solid #1a1d24;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
              0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
              0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
```

### Buttons

**Primary Button (Gold):**
```css
.btn-primary {
  background: linear-gradient(135deg, #d4af37 0%, #eac558 100%);
  color: #0f1115;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}
```

**Secondary Button (Slate):**
```css
.btn-secondary {
  background: #1a1d24;
  color: #e0e5ec;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  border: 1px solid #1a1d24;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-secondary:hover {
  background: #23262e;
  border-color: #d4af37;
}
```

### Inputs
```css
.input {
  width: 100%;
  padding: 12px 16px;
  background: #0f1115;
  border: 1px solid #1a1d24;
  border-radius: 8px;
  color: #e0e5ec;
  font-size: 16px;
  transition: all 0.2s ease;
}

.input:focus {
  outline: none;
  border-color: #d4af37;
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
```

### Navigation
```css
.nav-item {
  padding: 10px 16px;
  border-radius: 8px;
  color: #a0a8b5;
  transition: all 0.2s ease;
}

.nav-item:hover {
  color: #d4af37;
  background: rgba(212, 175, 55, 0.05);
}

.nav-item.active {
  color: #d4af37;
  background: rgba(212, 175, 55, 0.1);
  border-left: 3px solid #d4af37;
}
```

---

## Layout System

### Spacing Scale
| Size | Pixels | REM |
|------|--------|-----|
| xs | 4px | 0.25rem |
| sm | 8px | 0.5rem |
| md | 16px | 1rem |
| lg | 24px | 1.5rem |
| xl | 32px | 2rem |
| 2xl | 48px | 3rem |
| 3xl | 64px | 4rem |

### Grid System
```css
.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
}

.grid {
  display: grid;
  gap: 24px;
}

.grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
.grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
.grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
```

---

## RTL Support

### Arabic Layout Adjustments
```css
[dir="rtl"] .text-left { text-align: right; }
[dir="rtl"] .text-right { text-align: left; }
[dir="rtl"] .float-left { float: right; }
[dir="rtl"] .float-right { float: left; }
[dir="rtl"] .pl-4 { padding-left: 0; padding-right: 16px; }
[dir="rtl"] .pr-4 { padding-right: 0; padding-left: 16px; }
```

---

## Animations

```css
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
  from { transform: translateX(-20px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes pulseGold {
  0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
}

.animate-fade-in { animation: fadeIn 0.5s ease-out; }
.animate-slide-in { animation: slideIn 0.3s ease-out; }
```

---

## Landing Page Sections

### Hero Section
- Full-width gradient background
- Split layout: Text (left) + Visual (right)
- Gold accent color on CTA
- Bilingual text display

### Pricing Section
- 3-column grid (Basic, Pro, Enterprise)
- Gold highlighting on Enterprise tier
- Feature checklist with checkmarks
- CTA buttons on each card

### How It Works
- 4-step numbered process
- Icons with gold accents
- Horizontal layout on desktop, vertical on mobile

---

## Mobile Responsiveness

```css
/* Mobile-first breakpoints */
@mobile: 640px;
@tablet: 768px;
@desktop: 1024px;
@large: 1280px;

/* Touch targets minimum */
.btn, .nav-item, .input {
  min-height: 44px;
}
```

---

## Files

| File | Purpose |
|------|---------|
| MASTER.md | This file - design system reference |
| COLORS.md | Color palette details |
| TYPOGRAPHY.md | Font specifications |
| COMPONENTS.md | Component specifications |
| LAYOUT.md | Grid and spacing system |

---

*Last updated: 2026-02-26*
*Design system version: 1.0.0*
*Selected option: Dark Luxury - Gulf B2B Professional*
