---
name: Inventory POS
description: High-throughput counter terminal design system for retail checkout and inventory control
colors:
  primary: "#0284c7"
  primary-hover: "#0369a1"
  primary-light: "#f0f9ff"
  primary-dark: "#082f49"
  success: "#10b981"
  success-light: "#ecfdf5"
  danger: "#ef4444"
  danger-light: "#fef2f2"
  neutral-bg: "#f9fafb"
  neutral-surface: "#ffffff"
  neutral-surface-dark: "#111827"
  neutral-border: "#e5e7eb"
  neutral-border-dark: "#374151"
  neutral-text: "#111827"
  neutral-text-muted: "#6b7280"
  neutral-text-dark: "#f9fafb"
typography:
  display:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.85rem"
    fontWeight: 800
    lineHeight: 1.1
    letterSpacing: "-0.045em"
  headline:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.6rem"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "-0.035em"
  title:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.05rem"
    fontWeight: 800
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 500
    lineHeight: 1.4
  label:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.6875rem"
    fontWeight: 700
    lineHeight: 1
    letterSpacing: "0.04em"
rounded:
  xs: "4px"
  sm: "8px"
  md: "10px"
  lg: "12px"
  xl: "14px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "14px"
  lg: "16px"
  xl: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.neutral-surface}"
    rounded: "{rounded.sm}"
    padding: "14px 16px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
    textColor: "{colors.neutral-surface}"
  product-tile:
    backgroundColor: "{colors.neutral-surface}"
    rounded: "{rounded.xl}"
    padding: "14px"
  badge:
    rounded: "{rounded.full}"
    padding: "2px 8px"
---

# Design System: Inventory POS

## Overview

**Creative North Star: "The High-Throughput Counter Terminal"**

Inventory POS is designed for tactile, high-contrast register ergonomics engineered for zero hesitation under ambient retail lighting. The aesthetic blends the crisp utility of a physical hardware terminal with the responsiveness and clean hierarchy of modern web software. It avoids decorative fluff, bloated whitespace, and ambiguous states in favor of immediate cognitive recognition, rapid scanner/keyboard input, and atomic state clarity.

The visual tone is restrained, reliable, and functional. Cool neutral slates create a quiet, fatigue-reducing workspace that lets high-priority transactional feedback—stock levels, scanned line items, prices, and checkout totals—stand out with razor-sharp contrast.

**Key Characteristics:**
- **High-Density Legibility:** Interfaces prioritize information density, rapid horizontal scanning, and distinct visual hierarchy over empty white space.
- **Tactile Feedback:** Cards, buttons, and steppers have subtle physical lifts (`-translate-y-0.5`), active depressions (`scale-[0.99]`), and high-visibility focus rings (`focus-visible:outline-info-500/35`).
- **Immediate State Differentiation:** Low stock (≤ 5 units), out-of-stock items, live connection status, and running cart balances are encoded with explicit dual cues (color + badge icon/text).

## Colors

The palette uses a calm, neutral slate architecture with high-visibility signal blue (`info`) reserved for transactional actions and price commitments.

### Primary & Action
- **Signal Sky / Info 600** (`#0284c7` / `rgb(2, 132, 199)`): The focal interactive color. Used exclusively for primary purchase triggers, checkout CTA buttons, active search focus rings, product unit prices, and final ticket totals.
- **Signal Sky Hover / Info 700** (`#0369a1`): Hover/press state for primary buttons.
- **Signal Sky Tint / Info 50** (`#f0f9ff` / dark: `rgba(8, 47, 73, 0.4)`): Subtle background for product icon badges, quick-add badges, and active selections.

### Semantic Status
- **Emerald Success** (`#10b981` / `success-500`): Signifies active connectivity ("En línea"), confirmed transaction success, and healthy inventory levels.
- **Crimson Danger** (`#ef4444` / `danger-500`): Critical alerts, low stock warnings (≤ 5 units), stock depletion errors, and cart cancellation triggers.

### Neutrals
- **Surface Canvas** (`#f9fafb` / dark: `#030712`): Base application canvas for panels and viewports.
- **Card Surface** (`#ffffff` / dark: `#111827`): Elevated surfaces for product tiles, cart sidebars, and input containers.
- **Structural Border** (`#e5e7eb` / dark: `#374151`): Clean 1px borders delineating grid items without heavy shadows.
- **Primary Text** (`#111827` / dark: `#f9fafb`): High-contrast text for prices, titles, and quantities.
- **Muted Text** (`#6b7280` / dark: `#9ca3af`): Secondary metadata, category labels, units, and timestamps.

### Named Rules
**The Transactional Reserve Rule.** The primary blue accent is reserved strictly for transactional flow (action buttons, price display, selected states). It is never used for purely decorative backgrounds or non-actionable headings.

## Typography

**Display & Body Font:** `Instrument Sans`, with standard system-ui fallbacks (`system-ui, -apple-system, sans-serif`).  
**Character:** Clean, humanist geometry with modern grotesque proportions. Highly readable at small badge sizes (10px–11px) while delivering authoritative weight on register monetary displays.

### Hierarchy
- **Display Total** (`font-extrabold`, `1.85rem` / `29.6px`, line-height `1.1`, tracking `[-0.045em]`): Reserved exclusively for the grand checkout total at the base of the cart ticket.
- **Headline / Section** (`font-bold`, `1.6rem` / `25.6px`, line-height `1.2`, tracking `[-0.035em]`): Screen headers ("Nueva venta", "Productos").
- **Card Price Title** (`font-extrabold`, `1.05rem` / `16.8px`, line-height `1.25`, tracking `tight`): Unit prices on product tiles.
- **Body & Product Title** (`font-bold` / `font-medium`, `0.84rem` / `13.44px` to `0.875rem`, line-height `1.4`): Product titles (2-line clamp) and table records.
- **Label & Badge** (`font-bold`, `0.65rem`–`0.68rem` / `10.5px`, uppercase tracking `[0.04em]`): Category tags, stock indicators, hotkey badges (`/`), and status pills.

### Named Rules
**The Numeric Tabular Rule.** All monetary figures and quantity counts must use tabular or tight tracking (`tracking-tight` / `-0.045em`) with two fixed decimals (`number_format($price, 2)`), preventing layout jitter when quantities or cart totals update.

## Layout

- **Viewport Anchoring:** The Point of Sale layout locks to the viewport height (`h-[calc(100dvh-4.25rem)]` on desktop) with internal independent scroll containers for the product catalog and the cart ledger. Cashiers never scroll the main browser window.
- **Spatial Split:**
  - **Catalog Workspace:** Flexible grid (`grid-cols-[minmax(0,1fr)_420px]` on standard displays, `350px` on mid screens). Product tiles flow in responsive auto-fill cards (`repeat(auto-fill, minmax(240px, 1fr))`).
  - **Cart Ledger (Aside):** Fixed width right column containing active ticket items, clear cart action, live item count, payment method selector, and sticky checkout button.
- **Mobile Fallback:** Under `800px`, the layout smoothly converts to a single-column flow with capped scroll heights (`max-h-[50vh]` catalog, `max-h-70` cart) to support tablet or emergency mobile register access.

## Elevation & Depth

- **Tonal Separation First:** Boundaries and hierarchies are established through 1px border lines (`border-gray-200` / `dark:border-gray-700`) and subtle background value shifts (`bg-gray-50` vs `bg-white`) rather than deep drop shadows.
- **Tactile Shadows:**
  - Resting tiles: `shadow-sm`
  - Hover elevation: `-translate-y-0.5 shadow-md` with border transition (`hover:border-info-300`)
  - Active press: `active:scale-[0.99] active:translate-y-0`
- **Focus Rings:** Distinct, non-interfering halo rings (`focus-visible:outline-3 focus-visible:outline-info-500/35 focus-visible:outline-offset-2`) ensuring unambiguous visual confirmation for keyboard and barcode scanner users.

## Shapes

- **Outer Panel Containers:** `rounded-xl` (12px)
- **Product Tiles:** `rounded-[0.85rem]` (13.6px)
- **Product Image Thumbnails:** `rounded-[0.65rem]` (10.4px)
- **Form Controls & CTA Buttons:** `rounded-lg` (8px)
- **Quantity Stepper Buttons:** `rounded` (4px), square `size-6` (24px)
- **Status & Stock Pills:** `rounded-full` (9999px)

## Components

### 1. Global Search & Scanner Input
- Large input (`py-3.5 px-12`) with embedded magnifying glass icon and prominent keyboard shortcut indicator (`kbd` showing `/`).
- Live debounced binding (`wire:model.live.debounce.250ms`) with auto-focus.

### 2. Product Tile
- Left-aligned compact image or fallback category icon in a subtle `bg-info-50` frame.
- Category breadcrumb tag and dynamic stock pill (green when stock > 5, red alert when ≤ 5).
- Two-line clamp product title with zero overflow.
- Prominent price and circular `+` quick-add button that scales on card hover.

### 3. Cart Ledger Item
- 2-column tabular grid displaying item title, unit price, inline quantity stepper buttons (`−` / `+`), and calculated line subtotal.
- Subtle delete cross icon on hover.

### 4. Checkout Action Bar
- Sticky bottom placement in the cart drawer.
- High-contrast button displaying dynamic total: `Cobrar $X,XXX.XX` with loading state `Procesando...` and forward arrow icon.

## Do's and Don'ts

### Do's
- **DO** keep numbers, prices, and quantities consistently formatted to 2 decimal places.
- **DO** maintain the dual-pane split on desktop so cashiers never lose sight of either the product search or the current ticket.
- **DO** provide both mouse and keyboard affordances (e.g. `/` hotkey for search, auto-focus, large click targets).
- **DO** preserve dark mode compatibility across all background and border shades.

### Don'ts
- **DON'T** introduce slow decorative animations or layout-shifting transitions into the checkout path.
- **DON'T** use modal popups for standard register actions (adding items, changing quantities); keep all primary sales loops inline.
- **DON'T** hide stock warnings—if an item has 5 or fewer units, the cashier must see the warning indicator immediately.
- **DON'T** use light gray text on white backgrounds; always satisfy WCAG AA 4.5:1 contrast ratios.
