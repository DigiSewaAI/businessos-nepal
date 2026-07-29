# BUSINESSOS NEPAL - CORE BUSINESS SPECIFICATION

**Version:** 1.0
**Date:** July 25, 2026
**Status:** Approved
**Document Type:** Business Logic & Workflow Specification
**Purpose:** Single source of truth for HOW the system should behave. Merges User Stories, Business Rules, Workflows, and Acceptance Criteria into one actionable document for the developer.

---

## 1. CORE USERS (Personas)

| Role | Description | Key Permissions |
|------|-------------|-----------------|
| **Owner** | Business owner. Sees everything. | Full access to reports, settings, financials. |
| **Manager** | Supervises daily ops. | Can approve purchases, view all sales, manage staff. |
| **Cashier** | Front-desk sales. | Can create sales, view customers, but cannot see purchase cost or modify stock directly. |
| **Inventory Clerk** | Manages stock. | Can add products, adjust stock, transfer between warehouses. Cannot see sales revenue. |

---

## 2. CRITICAL BUSINESS RULES (Non-Negotiable)

| ID | Rule | Details |
|----|------|---------|
| **BR-01** | **Negative Stock is Prohibited** | System MUST prevent a sale if available stock < requested quantity. (Soft-block: Show error message). |
| **BR-02** | **Warehouse Isolation** | Stock is always tied to a specific `warehouse_id`. Sale deduction happens from the selected warehouse only. |
| **BR-03** | **Tax Calculation** | Tax (VAT) is calculated on the *subtotal* (after discount). Formula: `(Total - Discount) * Tax%`. |
| **BR-04** | **Discount Limits** | Cashier cannot apply > 10% discount without Manager/Owner approval (soft warning, but allowed in V1 for simplicity). |
| **BR-05** | **Purchase Return** | Returning a purchase increases the stock back to the warehouse and reduces supplier payable. |
| **BR-06** | **Sales Return** | Returning a sale decreases the stock again and creates a credit note for the customer. |
| **BR-07** | **Cashbook Balance** | Cashbook closing balance MUST equal `Opening Balance + Total Cash Sales + Cash Received - Cash Expenses - Cash Withdrawals`. |
| **BR-08** | **Unique SKU** | SKU must be unique across the organization. System auto-generates if user leaves it blank. |
| **BR-09** | **Audit Trail** | Every `CREATE`, `UPDATE`, `DELETE` on Sales, Purchases, Stock, and Expenses MUST log to `audit_logs` table. |
| **BR-10** | **Multi-Tenant Isolation** | User A from Organization A cannot EVER see data from Organization B. All Eloquent queries MUST have `global scope` or manual `where('organization_id', ...)` checks. |

---

## 3. KEY WORKFLOWS (Happy Path)

### WF-01: Product Creation to Sale
1. **Inventory Clerk** logs in.
2. **Creates** Category (if new) -> Creates Brand (if new) -> Creates Product (with Price, Cost, SKU).
3. **Adds Stock**: Selects Warehouse -> Enters Opening Stock (Quantity) -> Saves.
4. **Cashier** opens POS.
5. **Searches** product (by name/SKU/barcode).
6. **Adds to Cart** (Quantity).
7. **Applies Discount** (optional).
8. **Selects Customer** (or creates new one).
9. **Completes Payment** (Cash/Card/UPI - just a dropdown selection in V1).
10. **Clicks "Complete Sale"**.
    - *System Action*: Deducts stock from warehouse.
    - *System Action*: Creates Invoice.
    - *System Action*: Adds entry to Cashbook (if Cash) or Receivables (if Credit).
11. **Prints/Downloads** Receipt (PDF).

### WF-02: Purchase Order to Stock Inflow
1. **Manager/Inventory Clerk** logs in.
2. **Creates Supplier** (if new).
3. **Creates Purchase Order (PO)**: Selects Supplier -> Adds Products (Expected Qty, Expected Price).
4. **Receives Stock**:
   - User receives partial or full quantity.
   - *System Action*: Increases Warehouse stock.
   - *System Action*: Updates Supplier Ledger (Payable).
5. **Purchase Return** (if defective):
   - Selects purchase -> Selects item -> Enter return qty.
   - *System Action*: Decreases Warehouse stock.
   - *System Action*: Reduces Supplier Payable.

### WF-03: Daily Cashbook Closing
1. **Owner/Manager** opens Cashbook module.
2. Sees today's sales and expenses.
3. **Enters Physical Cash Count**.
4. System compares `Expected Cash` vs `Physical Cash`.
5. Shows variance. User adds a note (e.g., "Short by NPR 100").
6. **Closes Day**: System archives the day's entries and resets Opening Balance for tomorrow.

---

## 4. ACCEPTANCE CRITERIA (For Testing)

| Feature | Scenario | Given | When | Then |
|---------|----------|-------|------|------|
| **Product Stock** | Prevent overselling | Product X has 5 qty in Warehouse A | Cashier tries to sell 6 qty | System shows error: "Insufficient stock. Available: 5" and does NOT create invoice. |
| **Sales Return** | Restore stock | Customer buys 2 qty of Product Y (Stock becomes 3). | Cashier clicks "Return" for 1 qty. | Stock increases back to 4. Customer account shows refund due. |
| **Purchase Return** | Correct supplier balance | Supplier S has payable of NPR 10,000. | Return items worth NPR 2,000. | Warehouse stock decreases. Supplier payable becomes NPR 8,000. |
| **Multi-Tenancy** | Data isolation | User A (Org 1) and User B (Org 2) both have a product named "Milk". | User A searches "Milk". | User A ONLY sees Org 1's "Milk". User B sees Org 2's "Milk". |
| **Cashbook** | Calculation accuracy | Opening balance: 1000. Sales today: 5000 (Cash). Expenses: 2000. | System calculates closing. | Closing Balance MUST be 4000 (1000+5000-2000). |

---

## 5. SIMPLIFIED DATA FLOW MAP

```text
[Inventory Clerk] 
    -> Add Product -> Add Stock (Warehouse)
        -> [Warehouse Table] (qty updates)
            -> [Cashier] creates Sale
                -> Deduct Stock (Transaction)
                -> Create Invoice
                -> Add Cashbook Entry
                    -> [Owner] views Reports -> Profit = (Sales - Cost_of_Goods_Sold - Expenses)