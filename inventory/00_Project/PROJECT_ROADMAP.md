BUSINESSOS NEPAL - PROJECT ROADMAP DOCUMENT
Version: 1.0
Date: July 25, 2026
Status: Approved
Document Type: Project Roadmap
Related Documents: Master Blueprint v1.0, Project Vision v1.1, Project Goals v1.0

1. PURPOSE
This document provides a detailed, time-phased roadmap for the development, testing, and deployment of BusinessOS Nepal Version 1.0.

It translates the abstract phases defined in the Master Blueprint into a concrete, week-by-week execution plan. This serves as the single source of truth for the solo developer/lead architect to track progress against milestones, manage dependencies, and ensure the project remains on schedule despite the "free of cost / bootstrapped" constraints.

2. SCOPE
2.1 In Scope
Detailed breakdown of Phases 0 through 7 (Foundation to Production Hardening).

Timeline estimates (Start/End dates) based on a realistic solo-development pace.

Dependencies mapping (which phase blocks which).

Milestone definitions and go/no-go decision points.

A 12-month post-launch "Horizon 2" glance for V2 planning.

2.2 Out of Scope
Exact task-level daily checklists (those belong in DEVELOPMENT_ROADMAP.md inside the 07_Development folder).

Bug-fix schedules after V1 launch (handled via CHANGELOG.md).

Marketing or sales funnel activities.

3. OBJECTIVES
#	Objective	Alignment
R1	Provide a single visual timeline for all stakeholders to understand when features will be available.	Transparency
R2	Establish hard internal deadlines to prevent perfectionism and scope creep (especially critical for a solo founder).	Focus
R3	Identify critical path dependencies so that delays in Phase 1 automatically trigger notifications to postpone Phase 2.	Risk Management
R4	Ensure V1.0 is ready for Public Launch by April 1, 2027 (8 months from start).	Predictability
4. DETAILED SECTIONS
4.1 Assumptions for Timeline Estimation
#	Assumption
A1	Development Hours: The solo founder dedicates ~25–30 focused hours per week to coding. (Weekends included for debugging).
A2	Architecture Clarity: Since the Blueprint and Vision are fully documented, decision-making time is minimized.
A3	Testing Overhead: Test writing is included in the phase timeline (not a separate phase).
A4	Documentation: Writing inline PHPDoc and updating CHANGELOG.md happens concurrently with development.
A5	Infrastructure: Development environment (Laragon) is already set up. Production deployment will happen in Phase 7.
4.2 Master Schedule Overview (Gantt-Style)
Project Start Date: August 1, 2026
Project End Date (V1 Launch): March 31, 2027
Total Duration: ~8 Months (34 Weeks)

Phase	Name	Start Date	End Date	Duration	Key Deliverable
Phase 0	Foundation	Aug 1, 2026	Aug 31, 2026	4 Weeks	Multi-tenant auth + User/Role system working
Phase 1	Inventory Core	Sep 1, 2026	Sep 30, 2026	4 Weeks	Product CRUD + Stock management with Warehouse support
Phase 2	Sales & POS	Oct 1, 2026	Oct 31, 2026	4 Weeks	Full invoice generation with stock deduction
Phase 3	Purchase	Nov 1, 2026	Nov 30, 2026	4 Weeks	Supplier + Purchase Order to Stock inflow workflow
Phase 4	Finance	Dec 1, 2026	Dec 31, 2026	4 Weeks	Expense tracking + Cashbook ledger ready
Phase 5	Reports	Jan 1, 2027	Jan 31, 2027	4 Weeks	5 core operational dashboards (Sales, Stock, Profit)
Phase 6	SaaS Engine	Feb 1, 2027	Feb 28, 2027	4 Weeks	Subscription plans, billing logic, usage limits
Phase 7	Production Hardening	Mar 1, 2027	Mar 31, 2027	4 Weeks	Penetration testing, performance tuning, deployment scripts
4.3 Detailed Phase Breakdown
🏗️ Phase 0: Foundation (Aug 1 – Aug 31, 2026)
Goal: Set up the bedrock. If this is shaky, everything falls apart.

Tasks:

Laravel 11.x installation with Breeze/Jetstream (or custom).

Multi-tenancy setup (Single Database, organization_id scoping).

Spatie/laravel-permission installation.

Organization & Branch models.

User Invitation flow (email-based).

Settings table (key/value pairs).

Dependencies: None (Start here).

Milestone: "Admin User can login, create a Company, invite a 'Manager', and the Manager sees the same company data."

Risk: If Multi-tenancy scope is bypassed in SQL, stop Phase 0 and fix immediately.

📦 Phase 1: Inventory Core (Sep 1 – Sep 30, 2026)
Goal: The heart of the system—managing products and stock.

Tasks:

Categories (nested set/adjacency list) & Brands.

Units (PCS, KG, BOX) and Unit conversions.

Product model with Variants (Size/Color via JSON or related table).

SKU auto-generation and Barcode logic.

Warehouse model.

Stock movement table (Initial Stock, Adjustments, Transfers).

Dependencies: Phase 0 (Needs Organization/Branch).

Milestone: "User can add 5 products with variants, assign them to a Warehouse, and see the 'Available Qty' update."

🧾 Phase 2: Sales & POS (Oct 1 – Oct 31, 2026)
Goal: The money maker—processing customer transactions.

Tasks:

Customer model.

Sales/Invoice table (Header + Lines).

POS UI (Blade + Alpine.js for quick item selection).

Discount/Tax calculation logic.

Receipt generation (Printable HTML/PDF).

Stock deduction (using DB Transactions to prevent negative stock).

Sales Returns (Reverse stock).

Dependencies: Phase 1 (Needs Products & Stock).

Milestone: "User selects a product, applies a 10% discount, clicks 'Pay', stock decreases, and a receipt prints."

📥 Phase 3: Purchase (Nov 1 – Nov 30, 2026)
Goal: Bring stock IN to sell OUT.

Tasks:

Supplier model (CRUD).

Purchase Order (PO) creation.

Purchase Receiving (converts PO to Stock Inflow).

Purchase Returns (send stock back to supplier).

Supplier Ledger (pending payments).

Dependencies: Phase 1 (Needs Products & Warehouses).

Milestone: "User creates a PO, receives the stock, and the Warehouse quantity increases by exactly the PO quantity."

💰 Phase 4: Finance (Dec 1 – Dec 31, 2026)
Goal: See the money flow.

Tasks:

Expense categories and entries.

Cashbook (Daily opening/closing balance).

Basic Accounting Foundation (Journal entries mapped to Sales/Purchases/Expenses).

Bank Account management.

Dependencies: Phase 2 & 3 (Needs Sales & Purchase data for transaction mapping).

Milestone: "The Cashbook opening balance matches yesterday's closing balance, and all sales/purchase are reflected as entries."

📊 Phase 5: Reports (Jan 1 – Jan 31, 2027)
Goal: Actionable insights.

Tasks:

Sales Report (Daily/Monthly).

Top Selling Products.

Stock Valuation Report.

Supplier/Customer Aging summary.

Profit & Loss (Basic) - using Cashbook + Sales + Purchase data.

Dashboard KPI widgets (Total Sales, Low Stock Alerts).

Dependencies: Phase 1, 2, 3, 4 (All data must exist).

Milestone: "The Profit & Loss report matches manual Excel calculation for the test data set."

☁️ Phase 6: SaaS Engine (Feb 1 – Feb 28, 2027)
Goal: Prepare for real tenants.

Tasks:

Plans table (Free, Starter, Pro).

Laravel Cashier (Stripe) or Custom Billing logic (since Stripe might be costly, consider custom manual invoices + Midtrans/eSewa integration later).

Usage Limit Middleware (Max Users, Max Products, Max Branches).

Subscription upgrade/downgrade logic.

Dependencies: Phase 0 (Needs Organization model).

Milestone: "Free tenant with 10 products tries to add the 11th and gets a 'Upgrade Plan' notification."

🛡️ Phase 7: Production Hardening (Mar 1 – Mar 31, 2027)
Goal: Ship something you are proud of and is secure.

Tasks:

Laravel Envoy or GitHub Actions for Deployment.

SSL setup (LetsEncrypt).

Spatie Backup configuration (Database + Uploads).

OWASP Security Scan (SQL Injection, XSS).

Performance Audit (N+1 queries via Laravel Debugbar).

Final User Acceptance Testing (UAT) with the 10 beta companies.

Migration to Production VPS (DigitalOcean/Render).

Dependencies: ALL previous phases.

Milestone: "Platform passes all security tests and serves the first live public request."

4.4 Critical Path Mapping
In project management, the Critical Path is the sequence of phases that cannot be delayed without delaying the entire project.

Phase 0 (Must finish first)

➡️ Phase 1 (Inventory is needed for Sales and Purchase)

➡️ Phase 2 (Sales is the primary engagement driver—delaying this delays customer feedback)

➡️ Phase 7 (Can't launch without hardening)

Watchdog Alert: If Phase 1 (Inventory) slips by more than 2 weeks, the public launch will shift to May 2027. To compensate, we can shorten Phase 6 (SaaS engine) by using a simple "manual subscription" toggle instead of a full payment gateway in V1.

4.5 Go/No-Go Decision Points
Gate	Decision Point	Criteria to pass to next phase
G1	End of Phase 0	All tests for User/Role permissions pass. No query scoping leaks.
G2	End of Phase 1	Adding 10,000 products does not take > 2 seconds.
G3	End of Phase 2	POS receipt accuracy is verified against manual calculation.
G4	End of Phase 4	Cashbook balance matches sum of all transactions within 1 NPR.
G5	End of Phase 7	Beta users complete their daily operations without a single fatal error.
5. BEST PRACTICES FOR ROADMAP EXECUTION
Weekly Sync (Sunday Night):

Review the previous week's progress.

Update DEVELOPMENT_ROADMAP.md with a checkmark next to completed tasks.

If a phase is behind, reduce scope of that phase (e.g., skip "Bulk Product Import" in Phase 1 and add it as a future patch).

Ignore Perfectionism:

V1 does not need a beautiful UI for every edge case. Focus on "Clean, Functional, and Fast."

Refactoring is cheaper after launch when you have real user feedback.

Automate Early:

Set up automated testing (PHPUnit) during Phase 0 so you don't have to manually test Sales logic 100 times in Phase 2.

Celebrate Milestones:

Every phase completion is a major mental boost. Take 1-2 days off between Phases to avoid burnout.

6. RISKS (ROADMAP SPECIFIC)
Risk	Probability	Impact	Mitigation
Phase 2 (POS) overengineering	High	High	Build a simple HTML POS (no JavaScript frameworks) in V1. Save advanced UI for V2.
Database migration conflicts	Medium	Medium	Use Laravel's down() method strictly. Keep all migrations in a single folder per phase.
Feature request creep during Phase 5	High	Medium	Strictly follow the "V1 Reports List". Ignore requests for "Charts" if they aren't in the Blueprint.
Hosting cost spikes during Phase 7	Low	Medium	Use low-cost VPS ($5-$10/month). Optimize caching to run on 1GB RAM.
7. RECOMMENDATIONS
Use a Physical Kanban Board: Since you're solo, a simple Trello or GitHub Projects board with the phases as columns will keep you sane.

MVP Focus: Technically, the MVP (Minimum Viable Product) is Phases 0, 1, and 2. If you get a job offer or emergency, you can launch a "Inventory + POS" system by October 2026. Everything else is a bonus.

Dry Runs: Before starting Phase 7, set up a staging server (staging.businessos.com.np) and mirror the production environment exactly. Run every workflow 3 times.

Trust the Blueprint: If you feel lost, open the MASTER BLUEPRINT and look at the CORE MODULES (V1) section. If it's not there, don't build it yet.

8. REVISION HISTORY
Version	Date	Author	Changes
1.0	2026-07-25	BusinessOS Team	Initial creation. Detailed Phases 0-7 with timelines, critical path, and go/no-go gates.
9. APPROVALS
Role	Name	Signature	Date
Project Owner			
Lead Developer			
Document Status: ✅ Approved for Execution (Start Phase 0 on Aug 1, 2026)

