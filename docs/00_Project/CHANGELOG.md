# BUSINESSOS NEPAL - CHANGELOG

**Version:** 1.0  
**Date:** July 25, 2026  
**Status:** Active Template  
**Document Type:** Change Log  
**Related Documents:** [Master Blueprint v1.0](./../01_Blueprint/BUSINESSOS%20NEPAL%20-%20MASTER%20BLUEPRINT%20(Version%201.0).md), [Project Roadmap v1.0](./PROJECT_ROADMAP.md)

---

## 1. PURPOSE

This document serves as the **single source of truth** for all significant changes made to the BusinessOS Nepal codebase, database schema, configuration, and documentation. 

It enables the solo developer/team to track:
- What was changed?
- Why was it changed?
- Who changed it?
- When was it released?

This log is critical for debugging regressions, onboarding new contributors, and maintaining transparency with early adopters.

---

## 2. SCOPE

### 2.1 In Scope
- New features added.
- Bug fixes (with issue reference, if applicable).
- Database migrations (schema changes).
- Security patches.
- Performance optimizations.
- Dependency updates (major/minor).
- Documentation updates (if they affect usage).

### 2.2 Out of Scope
- Minor typo fixes in comments (too granular).
- Developer environment changes (not affecting production).
- Personal notes or brainstorming.

---

## 3. OBJECTIVES

| # | Objective | Alignment |
|---|-----------|-----------|
| C1 | **Maintain audit trail** for every release. | Transparency |
| C2 | **Enable rollback** decisions—if a bug appears, we know exactly which change introduced it. | Stability |
| C3 | **Inform users** (via release notes) about what's new or fixed. | Customer Communication |
| C4 | **Celebrate progress**—tangible record of achievements for the solo founder. | Motivation |

---

## 4. DETAILED SECTIONS

### 4.1 Versioning Scheme

All releases follow **Semantic Versioning (SemVer)** :

| Component | Format | Example | Meaning |
|-----------|--------|---------|---------|
| **Major** | X.0.0 | 1.0.0 → 2.0.0 | Breaking changes (database schema, API contract, UI overhaul) |
| **Minor** | X.Y.0 | 1.0.0 → 1.1.0 | New backward-compatible features |
| **Patch** | X.Y.Z | 1.0.0 → 1.0.1 | Backward-compatible bug fixes, security patches |

**Release Dates**: YYYY-MM-DD

---

### 4.2 Change Categories

| Icon | Category | Description |
|------|----------|-------------|
| ✨ | **Added** | New features or modules. |
| 🛠️ | **Fixed** | Bug fixes and patches. |
| 🔄 | **Changed** | Modifications to existing functionality. |
| ⚠️ | **Deprecated** | Features marked for removal in future versions. |
| 🗑️ | **Removed** | Features that were previously deprecated and now removed. |
| 🔒 | **Security** | Security-related fixes or improvements. |
| ⚡ | **Performance** | Performance optimizations. |
| 📝 | **Documentation** | Significant documentation updates. |
| 🏗️ | **Database** | Schema changes or migrations. |

---

### 4.3 Change Log Entries

---

#### [Unreleased]

> *This section tracks changes that are merged into the `main` branch but not yet released to production. Useful for staging testing.*

- ✨ *[Phase 0]*: Foundation scaffolding started. 
- 📝 *[Docs]*: Created `PROJECT_VISION.md`, `PROJECT_GOALS.md`, `PROJECT_ROADMAP.md`, `CHANGELOG.md`, and `RELEASE_PLAN.md`.

---

#### [1.0.0] - 2027-03-31 (Target)

> *🚀 V1.0 Public Launch — "Foundation Release"*

**Core Modules (V1) Complete:**
- ✨ Authentication (Login, Register, Forgot Password, Email Verification)
- ✨ Multi-Tenancy (Organizations, Branches)
- ✨ User Management with Roles (Owner, Manager, Cashier, Sales, Inventory, HR)
- ✨ Granular Permissions (Spatie/laravel-permission)
- ✨ Dashboard (KPIs, Charts, Notifications)
- ✨ Product Management (with Variants, SKU, Barcode, Images)
- ✨ Categories (Unlimited Hierarchy) & Brands
- ✨ Units (PCS, KG, BOX, Liter)
- ✨ Inventory Management (Stock, Transfers, Adjustments, Low Stock Alerts, Opening Stock)
- ✨ Multi-Warehouse Support
- ✨ Purchase Management (Suppliers, Purchase Orders, Purchases, Returns)
- ✨ Supplier CRM (Basic)
- ✨ Customer Management
- ✨ Sales & POS (Invoices, Discounts, Taxes, Receipts, Returns)
- ✨ Expense Tracking
- ✨ Cashbook (Daily Cash Management)
- ✨ Basic Accounting (Income/Expense, Ledger-Ready)
- ✨ 20+ Operational Reports (Sales, Inventory, Purchase, Customers, Suppliers, Profit, Expenses)
- ✨ Notifications (Email, In-app)
- ✨ Subscription Engine (Plans, Billing, Usage Limits)
- ✨ Audit Logs (Track every critical action)
- ✨ Settings (Language, Currency, Timezone, Business Rules)

**Security:**
- 🔒 Enforced `organization_id` scoping on all queries.
- 🔒 Laravel Policies for every model.
- 🔒 CSRF, Rate Limiting, Password Hashing.

**Performance:**
- ⚡ Eager loading and pagination across all list views.
- ⚡ Database indexes on `organization_id`, `branch_id`, `created_at`.

**Localization:**
- 📝 Nepali (नेपाली) and English (EN) language support.
- 📝 NPR currency format.

---

##### [1.0.0-beta.1] - 2027-02-15 (Target)

> *🔬 Beta Testing Release — Internal & 10 Pilot Companies*

**Added:**
- ✨ Complete Phase 0 to Phase 6 implementation.
- ✨ Manual subscription toggle for beta testing (bypass payment gateway).

**Fixed:**
- 🛠️ *(Placeholder for beta bugs found)*

**Known Issues:**
- PDF receipt generation may break for very long product names.
- Mobile view on older Android browsers requires testing.

---

##### [0.5.0] - 2026-12-31 (Target)

> *🧪 Internal Alpha — End of Phase 4 (Finance)*

**Added:**
- ✨ Phase 4: Expenses, Cashbook, Basic Accounting.
- ✨ Phase 5: Reports (draft).

**Database:**
- 🏗️ Added `expenses` table.
- 🏗️ Added `cashbook` table.
- 🏗️ Added `journal_entries` table.

---

##### [0.3.0] - 2026-10-31 (Target)

> *🧪 Internal Alpha — End of Phase 2 (Sales)*

**Added:**
- ✨ Phase 1: Inventory Core (Products, Categories, Brands, Units, Warehouses, Stock).
- ✨ Phase 2: Sales & POS (Customers, Invoices, Receipts, Returns).
- ✨ Stock deduction on sale.

**Database:**
- 🏗️ Added `products`, `variants`, `categories`, `brands`, `units`, `warehouses`, `stock_movements`.
- 🏗️ Added `sales`, `sale_lines`, `customers`.
- 🏗️ Added `invoices` (merged with sales).

---

##### [0.1.0] - 2026-08-31 (Target)

> *🧪 Foundation Alpha — End of Phase 0*

**Added:**
- ✨ Laravel 11.x base installation.
- ✨ Multi-tenancy with `organization_id` scoping.
- ✨ Authentication (Login, Register, Forgot Password, Email Verification).
- ✨ Organization and Branch models.
- ✨ User, Role, Permission (Spatie).
- ✨ Settings table.
- ✨ Audit Log.

**Database:**
- 🏗️ Initial migration set: `users`, `organizations`, `branches`, `roles`, `permissions`, `settings`, `audit_logs`.

---

##### [0.0.1] - 2026-07-25

> *📄 Documentation Phase*

**Added:**
- 📝 Master Blueprint v1.0.
- 📝 Project Vision v1.1.
- 📝 Project Goals v1.0.
- 📝 Project Roadmap v1.0.
- 📝 CHANGELOG.md (this file).
- 📝 RELEASE_PLAN.md.

---

## 5. BEST PRACTICES FOR MAINTAINING THIS LOG

1. **Update Immediately**: Whenever you merge a feature or fix a bug, update `CHANGELOG.md` in the same commit. Do not "catch up" later.
2. **Refer to Tickets**: If using GitHub Issues, reference the issue number (e.g., `#12`).
3. **Use the "Unreleased" Section**: During development, keep adding entries under `[Unreleased]`. When cutting a release, move them to a new version header.
4. **Be User-Facing**: Write entries from the user's perspective. Instead of "Refactored ProductService", say "Improved product search speed by 40%".
5. **Security First**: If a security fix is being patched quietly, do not disclose details until the fix is deployed.

---

## 6. RISKS

| Risk | Mitigation |
|------|------------|
| Forgetting to update the log | Automate commit hooks that remind you to check `CHANGELOG.md`. |
| Overly verbose logs | Use the category system (✨, 🛠️) to keep it scannable. |
| Inconsistent formatting | Follow the template strictly for every entry. |

---

## 7. RECOMMENDATIONS

- **Link to Releases**: In the future, integrate this with GitHub Releases or a similar tool so users can see the log beautifully formatted.
- **Keep a "Known Issues" section**: In the `[Unreleased]` or `[Beta]` sections, track known bugs so early adopters are aware.

---

## 8. REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-07-25 | BusinessOS Team | Initial creation. Structure defined, placeholders for Phase 0-7 added. |

---

**Document Status:** ✅ Active Template (Update continuously)