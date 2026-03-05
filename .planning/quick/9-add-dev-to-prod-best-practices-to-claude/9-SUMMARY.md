---
phase: quick-9
plan: 01
subsystem: project-instructions
tags: [claude-md, dev-to-prod, best-practices, documentation]
dependency_graph:
  requires: []
  provides: [project-agent-instructions]
  affects: [every-claude-session]
tech_stack:
  added: []
  patterns: [CLAUDE.md auto-load by Claude Code]
key_files:
  created:
    - CLAUDE.md
  modified: []
decisions:
  - "Encoded 5 mandatory dev-to-prod rules as agent-facing imperative instructions, not human docs"
  - "Each rule grounded in a real past incident (migration divergence, direct DB edit, linter revert)"
metrics:
  duration: "5 minutes"
  completed: "2026-03-05"
  tasks_completed: 1
  tasks_total: 1
  files_created: 1
  files_modified: 0
---

# Quick-9: Add Dev-to-Prod Best Practices to CLAUDE.md Summary

## One-liner

Created CLAUDE.md at project root with 5 mandatory agent-facing rules grounded in real past incidents — migrations, no direct prod DB writes, immediate cherry-pick of critical fixes, pre-merge risk investigation, and release tagging.

## What Was Done

### Task 1: Create CLAUDE.md

Created `CLAUDE.md` at the project root (`D:\Claude\projects\LLM-Resayil\CLAUDE.md`).

The file is written as direct instructions to Claude agents (imperative, actionable), not as human documentation. It contains:

1. **Project Overview** — environment URLs, deploy commands, PHP binary path, UUID PK pattern, admin check method
2. **Design System** — dark luxury palette, fonts, CSS vars, user-facing label restrictions
3. **Google Analytics** — tag ID, isProduction guard, DO NOT REMOVE note
4. **Dev-to-Prod Best Practices** — 5 mandatory rules (see below)

### The 5 Rules

| Rule | Title | Past Incident It Prevents |
|------|-------|--------------------------|
| 1 | Always Use Migrations | `prompt_tokens`/`completion_tokens` added to dev DB directly — would have 500'd prod on push |
| 2 | Never Modify Prod DB Directly | `soud@alphia.net` inserted directly into prod DB, bypassing register flow |
| 3 | Cherry-Pick Critical Fixes Immediately | Email fix + OllamaProxy fixes sat on dev for days while prod was broken |
| 4 | Run Pre-Merge Risk Investigation | No systematic check before merge caused multiple near-miss incidents |
| 5 | Tag Prod Releases After Every Merge | No tags = git log is the only way to identify what's running on prod |

Each rule includes:
- Exact bash commands to follow
- "Why" rationale with specific past incident references
- Any exceptions or edge cases

## Commits

- `ab9a928` (dev) — `chore: add CLAUDE.md with dev-to-prod best practices`
- `632b597` (main) — `chore: add CLAUDE.md with dev-to-prod best practices`

Both `dev` and `main` branches pushed to origin.

## Deviations from Plan

None — plan executed exactly as written.

Note: The commit landed on `main` first (the working branch at execution time), then was cherry-picked to `dev`. Both branches are now in sync on this file. This is the correct outcome per the plan constraints.

## Self-Check: PASSED

- [x] `CLAUDE.md` exists at `D:\Claude\projects\LLM-Resayil\CLAUDE.md`
- [x] Contains "Dev-to-Prod Best Practices" section
- [x] All 5 rules present (verified with grep)
- [x] Commit `ab9a928` exists on dev branch
- [x] Commit `632b597` exists on main branch
- [x] Both pushed to origin
