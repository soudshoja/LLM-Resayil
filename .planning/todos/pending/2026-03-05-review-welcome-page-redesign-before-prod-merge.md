---
created: 2026-03-05T01:23:47.480Z
title: Review welcome page redesign before prod merge
area: ui
files:
  - resources/views/welcome.blade.php
---

## Problem

The welcome page (`/`) was redesigned (commit `a5adcf2`) to match template-3's dark luxury style. It is deployed to dev at https://llmdev.resayil.io but has NOT been reviewed or merged to prod yet.

This needs a sign-off before being included in the full dev→main merge.

Also: the dev→main merge has a known conflict on `resources/views/landing/template-3.blade.php` (main has old 1895-line version, dev has redesigned 893-line version — always take dev's version).

## Solution

1. Review https://llmdev.resayil.io visually — all sections: hero, features, how-it-works, testimonials, pricing, CTA, footer
2. Confirm no Kuwait/region-specific copy leaked in
3. Resolve template-3 merge conflict by taking dev's version: `git checkout --theirs resources/views/landing/template-3.blade.php`
4. Merge dev → main and deploy to prod
