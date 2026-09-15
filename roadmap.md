# Aegis UI — Component Roadmap

> Last updated: 2026-09-13

## Tech Stack

| Layer | Technology |
|-------|------------|
| Language | PHP 8.3+ |
| Framework | Laravel 13 |
| Interactivity | Livewire 4 + Alpine.js 3 |
| CSS | Tailwind CSS 4 (CSS-first) |
| Icons | Tabler Icons via Blade Icons |
| Testing | Pest PHP 3 |
| Build | Vite 6 |

---

## Completed Components

### Actions

| Component | Tag | Type | Features | Tests | Docs |
|-----------|-----|------|----------|-------|------|
| **Button** | `<x-button />` | Blade | 4 variants, 5 sizes, 5 colors, loading state, leading/trailing icons, polymorphic root (`as` prop), link mode (`href`/`target`/`external`), block mode, unstyled mode | Unit + Feature | Yes |
| **Icon** | `<x-icon />` | Blade | Wraps Tabler Icons, name normalization, 5 sizes, decorative vs labeled (aria) | Unit + Feature | Yes |
| **Spinner** | `<x-spinner />` | Blade | Tabler icon or SVG fallback, 5 sizes, prefers-reduced-motion support, configurable icon via config | Unit + Feature | Yes |

### Form Inputs

| Component | Tag | Type | Features | Tests | Docs |
|-----------|-----|------|----------|-------|------|
| **Input** | `<x-input />` | Blade | 5 variants, 5 sizes, leading/trailing icons, password toggle (Alpine), label/hint/error/validation, wire:model binding, block mode | Unit + Feature | Yes |
| **Checkbox** | `<x-checkbox />` | Blade | Custom styled box with SVG checkmark, indeterminate state (Alpine), 5 sizes, 5 colors, 6 radius levels, block/card mode, label, validation | Unit + Feature | Yes |
| **DatePicker** | `<x-datepicker />` | Blade | Alpine.js calendar, 7 modes (single/range/multiple/month/month-range/year/year-range), 6 date formats, presets, week numbers, multi-month, min/max/disabled dates, clearable, wire:model | Unit + Feature | Yes |

### Form Layout

| Component | Tag | Type | Features | Tests | Docs |
|-----------|-----|------|----------|-------|------|
| **FormField** | `<x-form-field />` | Blade | Wrapper for label + slot + feedback, auto-generates id from label, auto-pulls errors from `$errors` bag, aria-describedby | — | Yes |

### Feedback

| Component | Tag | Type | Features | Tests | Docs |
|-----------|-----|------|----------|-------|------|
| **Alert** | `<x-alert />` | Blade | 4 variants, 3 sizes, 5 colors, auto-resolved icon per color, dismissible (Alpine), title slot, role="alert" for danger/warning | Unit + Feature | Yes |

### Disclosure

| Component | Tag | Type | Features | Tests | Docs |
|-----------|-----|------|----------|-------|------|
| **Accordion** | `<x-accordion />` | Blade | Alpine.js state, single/multi-open, 4 variants, 3 sizes, default open | Unit + Feature | Yes |
| **AccordionItem** | `<x-acccordion-item />` | Blade | Collapsible (Alpine x-collapse), leading icon, auto-generated id, chevron rotation, disabled state | — | Yes |

### Shared Concerns (PHP Traits)

| Trait | Used By |
|-------|---------|
| `HasSize` | Button, Input, Checkbox, Accordion, DatePicker, etc. |
| `HasVariant` | Button, Alert, Accordion, etc. |
| `HasColor` | Button, Input, Checkbox, Alert, DatePicker, etc. |
| `HasIcon` | Button, Input, AccordionItem |
| `HasValidation` | Input, Checkbox, DatePicker, FormField |
| `InteractsWithWire` | Input, DatePicker |

---

## Not Yet Built

Planned in `AGENT.md` Section 24 build order but no PHP class, Blade view, or CSS exists.

### High Priority (next in build order)

| # | Component | Tag | Type | Why Build Next |
|---|-----------|-----|------|----------------|
| 11 | **Textarea** | `<x-textarea />` | Blade | Mirrors Input. Adds `rows`, `maxlength`, Alpine character count. Completes the text input family. |
| 12 | **Select** | `<x-select />` | Blade | Mirrors Input without icons. Essential for any form. |
| 14 | **Toggle** | `<x-toggle />` | Blade | Extends Checkbox pattern. Alpine interaction. Config already has defaults. |
| 13 | **Radio** | `<x-radio />` | Blade | Mirrors Checkbox. Validates radio group pattern. |

### Medium Priority (UI completeness)

| # | Component | Tag | Type | Why Build |
|---|-----------|-----|------|-----------|
| 5 | **Badge** | `<x-badge />` | Blade | Simple status/label indicator. No interactivity. Config already has defaults. |
| 15 | **Avatar** | `<x-avatar />` | Blade | User/profile image placeholder. No form dependencies. |
| 16 | **Tooltip** | `<x-tooltip />` | Blade | Alpine-only. No server interaction. Common UI pattern. |
| 17 | **Dropdown** | `<x-dropdown />` | Blade | Alpine-only. Trigger/panel slot pattern. Used by many other components. |
| 19 | **Card** | `<x-card />` | Blade | Layout container. Composes other components in demos. |

### Lower Priority (advanced / complex)

| # | Component | Tag | Type | Why Build |
|---|-----------|-----|------|-----------|
| 18 | **Tabs** | `<x-tabs />` | Blade | Alpine-only. Multi-panel slot pattern. Common for settings pages. |
| 20 | **Modal** | `<x-modal />` | Livewire | First Livewire component. Depends on Button, Icon, Spinner. Config already has defaults. |
| 21 | **Combobox** | `<x-combobox />` | Livewire | Most complex component. Built last per build order. |

### Not in Build Plan (suggestions)

These are common UI components that aren't in `AGENT.md` but would round out the library:

| Component | Tag | Type | Rationale |
|-----------|-----|------|-----------|
| **Breadcrumb** | `<x-breadcrumb />` | Blade | Navigation aid. Simple layout component. |
| **Separator** | `<x-separator />` | Blade | Horizontal rule. Used between sections. |
| **Table** | `<x-table />` | Blade | Data display. Often paired with Card. |
| **Pagination** | `<x-pagination />` | Blade | Page navigation. Pairs with Table. |
| **Toast / Notification** | `<x-toast />` | Livewire | ephemeral feedback. More prominent than Alert. |
| **Popover** | `<x-popover />` | Blade | Rich tooltip with content slot. |
| **Stepper** | `<x-stepper />` | Blade | Multi-step form wizard. |
| **Progress** | `<x-progress />` | Blade | Linear/circular progress bar. |
| **Skeleton** | `<x-skeleton />` | Blade | Loading placeholder. Pairs with async content. |
| **Divider** | `<x-divider />` | Blade | Themed separator with optional label. |
| **Chip / Tag** | `<x-chip />` | Blade | Removable badge. Used in combobox selections. |
| **Command Palette** | `<x-command />` | Livewire | Keyboard-driven search. Power-user feature. |

---

## Suggested Build Priority

```
Phase 1 — Form completeness (current focus)
├── Textarea ✓
├── Radio Group ✓
├── Select ✓
└── Toggle ✓

Phase 2 — Display & feedback
├── Badge
├── Avatar
├── Tooltip
└── Separator

Phase 3 — Layout & navigation
├── Dropdown ✓
├── Card ✓
├── Tabs
├── Breadcrumb
└── Table

Phase 4 — Advanced interaction
├── Modal (Livewire)
├── Combobox (Livewire)
└── Popover

Phase 5 — Polish & extras
├── Progress
├── Skeleton
├── Stepper
├── Pagination
└── Toast (Livewire)
```

---

## Current Stats

| Metric | Count |
|--------|-------|
| Completed Blade components | 18 |
| Completed Livewire components | 0 |
| Shared Concerns (traits) | 6 |
| Unit tests | ~200 |
| Feature render tests | ~80 |
| Planned but not built | 8 (from AGENT.md) |
| Suggested additions | 12 |
