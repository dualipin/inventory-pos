# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- **Cajero (Cashier):** Operates the checkout register (`/point-of-sale`) under fast-paced counter conditions. Scans barcodes or searches products, manages cart items and quantities, processes cash payments, and confirms sales.
- **Inventario (Inventory Manager / Stock Clerk):** Tracks stock levels, records inventory movements (entradas, salidas, ajustes), monitors low-stock alerts, and maintains catalog units and packaging.
- **Administrador (Store Admin):** Manages user roles and access, catalog pricing, category structures, promotions, and reviews sales performance and audit histories.

## Product Purpose

Deliver an integrated, fast-paced point-of-sale and inventory control platform that connects counter register operations directly to back-office warehouse inventory with zero synchronization delay. Success means rapid checkout throughput, elimination of manual stock reconciliation, and 100% accurate physical inventory tracking.

## Positioning

An all-in-one counter operations platform that combines immediate, keyboard-friendly register checkout with atomic transactional inventory locking (`lockForUpdate`), preventing overselling and eliminating back-office reconciliation lag.

## Operating Context

- Physical retail/grocery counter environment using desktop, tablet, or terminal displays.
- Hardware interaction: barcode scanners (keyboard emulation / auto-detect), keyboard shortcuts (`/` for product search), receipt printers, and cash drawers.
- High-frequency daily operations where visual feedback, status indicators (e.g., "Caja abierta", "En línea"), and stock badges must be visible at a glance.
- Operating currency and locale: Mexican Pesos (MXN `$`), Spanish-language UI.

## Capabilities and Constraints

- **Capabilities:**
  - Real-time Point of Sale interface (`/point-of-sale`) with search filtering, barcode scanning, live cart calculations, and change/payment handling.
  - Granular role-based access control with predefined roles (`admin`, `cajero`, `inventario`).
  - Atomic stock deductions and inventory movement logs on every completed transaction (`CreateSaleAction`).
  - Inventory movements ledger (`InventoryMovement`) supporting entries, exits, and adjustments.
  - Comprehensive catalog management with units (`kg`, `g`, `l`, `ml`, `pza`), packaging (`pieza`, `caja`, `paquete`), and barcode support.
  - Promotional discount engine (`Promotion`).
- **Constraints & Open Decisions:**
  - Supported payment methods currently restricted to Cash (`Efectivo`). Digital payments (cards, transfers) are currently out of scope.
  - Web platform built on Laravel 13, Filament 5, Livewire, and Tailwind CSS v4.

## Brand Commitments

- **Name:** Inventory POS
- **Brand Assets:** Brand logo located at `public/assets/logo.png`.
- **Tone & Voice:** Operational, direct, high-utility Spanish terminology ("Caja abierta", "Nueva venta", "disp.", "En línea").

## Evidence on Hand

- Verified database seeders and schema covering real retail categories (Bebidas, Lácteos, Abarrotes, Snacks, Limpieza, Higiene) and inventory seed data.
- Active Filament admin and POS interface in `app/Filament/` and `resources/views/filament/pages/point-of-sale.blade.php`.
- Established domain models: `Product`, `Inventory`, `InventoryMovement`, `Sale`, `SaleItem`, `Category`, `Promotion`, `User`.

## Product Principles

1. **Speed at the Register First:** Every checkout interaction must minimize cognitive friction, keystrokes, and clicks. Latency or layout shift directly stalls checkout lines.
2. **Absolute Stock Integrity:** Transactions must lock and verify stock atomically. Overselling or discrepancy between counter sales and physical shelves is unacceptable.
3. **High-Contrast Operational Clarity:** Stock states, totals, active items, and system alerts must remain instantly legible under variable counter lighting.
4. **Role Focus:** Cashiers receive an unobstructed selling surface; inventory and administrative controls remain organized and isolated to appropriate user roles.
