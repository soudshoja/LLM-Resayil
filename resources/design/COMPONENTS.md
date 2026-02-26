# Component Specification: LLM Resayil Portal

**Design System:** Dark Luxury - Gulf B2B Professional

## Buttons

### Primary Button
```html
<button class="btn-primary">
  <span class="btn-text">Start Free Trial</span>
</button>
```

```css
.btn-primary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: linear-gradient(135deg, #d4af37 0%, #eac558 100%);
  color: #0f1115;
  font-weight: 600;
  padding: 12px 24px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  min-height: 48px;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.btn-primary:active {
  transform: translateY(0);
}

.btn-primary:disabled {
  background: #6b7280;
  cursor: not-allowed;
  transform: none;
}
```

### Secondary Button
```html
<button class="btn-secondary">
  <span class="btn-text">View Details</span>
</button>
```

```css
.btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #1a1d24;
  color: #e0e5ec;
  font-weight: 500;
  padding: 12px 24px;
  border-radius: 8px;
  border: 1px solid #1a1d24;
  cursor: pointer;
  transition: all 0.2s ease;
  min-height: 48px;
}

.btn-secondary:hover {
  background: #23262e;
  border-color: #d4af37;
  color: #d4af37;
}

.btn-secondary:disabled {
  background: #0f1115;
  border-color: #1a1d24;
  color: #6b7280;
}
```

### Ghost Button
```html
<button class="btn-ghost">
  <span class="btn-text">Cancel</span>
</button>
```

```css
.btn-ghost {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: #a0a8b5;
  font-weight: 500;
  padding: 8px 16px;
  border-radius: 6px;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-ghost:hover {
  color: #e0e5ec;
  background: rgba(255, 255, 255, 0.05);
}
```

---

## Cards

### Standard Card
```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Subscription Status</h3>
  </div>
  <div class="card-body">
    <p>Active plan until March 26, 2026</p>
  </div>
</div>
```

```css
.card {
  background: linear-gradient(135deg, #0f1115 0%, #0a0d14 100%);
  border: 1px solid #1a1d24;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
              0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.card-header {
  margin-bottom: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid #1a1d24;
}

.card-title {
  font-size: 18px;
  font-weight: 600;
  color: #e0e5ec;
  margin: 0;
}

.card-body {
  color: #a0a8b5;
}
```

### Gold Accent Card (Premium Tier)
```html
<div class="card card-premium">
  <div class="card-header">
    <div class="badge-premium">Most Popular</div>
    <h3 class="card-title">Enterprise</h3>
  </div>
  ...
</div>
```

```css
.card-premium {
  border: 1px solid #d4af37;
  box-shadow: 0 0 20px rgba(212, 175, 55, 0.1);
}

.card-premium .card-header {
  border-bottom-color: #d4af37;
}

.badge-premium {
  display: inline-block;
  background: linear-gradient(135deg, #d4af37 0%, #eac558 100%);
  color: #0f1115;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  margin-bottom: 8px;
}
```

---

## Inputs

### Text Input
```html
<div class="input-wrapper">
  <label class="input-label">Email Address</label>
  <input type="email" class="input" placeholder="user@example.com">
  <div class="input-error">Invalid email address</div>
</div>
```

```css
.input-wrapper {
  margin-bottom: 20px;
}

.input-label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #e0e5ec;
}

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

.input-error {
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px;
}
```

### Select Dropdown
```css
.select {
  width: 100%;
  padding: 12px 16px;
  background: #0f1115;
  border: 1px solid #1a1d24;
  border-radius: 8px;
  color: #e0e5ec;
  font-size: 16px;
  cursor: pointer;
}
```

---

## Navigation

### Sidebar Navigation
```html
<nav class="nav-sidebar">
  <a href="/dashboard" class="nav-item active">
    <span class="nav-icon">📊</span>
    <span class="nav-text">Dashboard</span>
  </a>
  <a href="/api-keys" class="nav-item">
    <span class="nav-icon">🔑</span>
    <span class="nav-text">API Keys</span>
  </a>
</nav>
```

```css
.nav-sidebar {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  color: #a0a8b5;
  text-decoration: none;
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

.nav-icon {
  font-size: 20px;
  min-width: 24px;
  text-align: center;
}

.nav-text {
  font-size: 15px;
  font-weight: 500;
}
```

### Top Navigation
```css
.nav-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 24px;
  background: rgba(15, 17, 21, 0.95);
  backdrop-filter: blur(10px);
  position: sticky;
  top: 0;
  z-index: 100;
}
```

---

## Badges

### Status Badge
```html
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Expiring Soon</span>
<span class="badge badge-error">Expired</span>
```

```css
.badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.badge-success {
  background: rgba(5, 150, 105, 0.15);
  color: #059669;
}

.badge-warning {
  background: rgba(245, 158, 11, 0.15);
  color: #f59e0b;
}

.badge-error {
  background: rgba(239, 68, 68, 0.15);
  color: #ef4444;
}

.badge-gold {
  background: rgba(212, 175, 55, 0.15);
  color: #d4af37;
}
```

---

## Charts & Data Viz

### Simple Chart Container
```css
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}
```

### Data Card
```css
.data-card {
  padding: 24px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0f1115 0%, #0a0d14 100%);
  border: 1px solid #1a1d24;
}

.data-card .data-value {
  font-size: 32px;
  font-weight: 700;
  color: #e0e5ec;
  margin-bottom: 8px;
}

.data-card .data-label {
  font-size: 14px;
  color: #a0a8b5;
}

.data-card .data-trend {
  font-size: 14px;
  color: #059669;
}
```

---

## Tables

### Data Table
```css
.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.table th {
  padding: 12px 16px;
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  color: #a0a8b5;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table td {
  padding: 16px;
  border-bottom: 1px solid #1a1d24;
  color: #e0e5ec;
}

.table tr:last-child td {
  border-bottom: none;
}

.table tr:hover td {
  background: rgba(255, 255, 255, 0.02);
}
```

---

## Toast Notifications

```css
.toast {
  position: fixed;
  top: 24px;
  right: 24px;
  padding: 16px 24px;
  border-radius: 8px;
  background: #0f1115;
  border-left: 4px solid;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
  z-index: 1000;
  animation: slideIn 0.3s ease-out;
}

.toast-success { border-color: #059669; }
.toast-error { border-color: #ef4444; }
.toast-warning { border-color: #f59e0b; }
.toast-info { border-color: #3b82f6; }
```

---

## Modal

```css
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: linear-gradient(135deg, #0f1115 0%, #0a0d14 100%);
  border: 1px solid #1a1d24;
  border-radius: 12px;
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  padding: 24px 24px 16px;
  border-bottom: 1px solid #1a1d24;
}

.modal-body {
  padding: 24px;
}
```

---

*Design System: Dark Luxury - Gulf B2B Professional*
*Version: 1.0.0*
