# Factory Order Management — Step-by-Step Testing & Execution Guide

This hands-on guide walks you through the entire Buying House workflow from beginning to end. Follow these numbered steps using the provided sample test data to verify every feature in your system.

---

## Quick Reference & Credentials

- **Application URL**: `http://127.0.0.1:8000` (or `http://factory_order_management.test`)
- **Login Email / Username**: `hello@inoodex.com` or `admin`
- **Password**: `hello@inoodex.com`

---

## Step 1: Log In to the System
1. Open your browser and go to:
   ```
   http://127.0.0.1:8000
   ```
2. Enter the credentials:
   - **Email / Username**: `hello@inoodex.com`
   - **Password**: `hello@inoodex.com`
3. Click **Sign In**.
4. **Expected Result**: You arrive at the **Dashboard** (`/dashboard`).

---

## Step 2: Verify Master Financial Foundation
Before placing an order, confirm that the accounting period and bank accounts are active.

1. **Check Office Accounts (Cash & Bank)**:
   - Click **Office Accounts** in the sidebar, or go to:
     ```
     http://127.0.0.1:8000/dashboard/office-accounts
     ```
   - **Verify**: You should see default accounts like:
     - `Cash in Hand` (Opening Balance: e.g., $10,000)
     - `Primary Bank Account` (Opening Balance: e.g., $50,000)

2. **Check Accounting Period**:
   - Go to:
     ```
     http://127.0.0.1:8000/dashboard/accounting-periods
     ```
   - **Verify**: An active accounting period exists (e.g., `FY 2025-2026`) marked with status **Active**.

---

## Step 3: Create a Buyer / Customer
A foreign buyer who places garment orders with your buying house.

1. In the sidebar, click **Customers** ➔ **Add Customer**, or navigate to:
   ```
   http://127.0.0.1:8000/dashboard/customers/create
   ```
2. Enter the following test details:
   - **Company / Buyer Name**: `Nordic Wear Apparel AB`
   - **Contact Person**: `Erik Lindqvist`
   - **Email**: `erik@nordicwear.se`
   - **Phone**: `+46 8 123 4567`
   - **Country**: `Sweden`
   - **Address**: `Kungsgatan 14, 111 35 Stockholm, Sweden`
3. Click **Save Customer**.
4. **Expected Result**: Success alert appears, and `Nordic Wear Apparel AB` is listed in the Customers table.

---

## Step 4: Create a Manufacturing Factory / Supplier
The garment factory where your buying house will contract the production.

1. In the sidebar, click **Suppliers** ➔ **Add Supplier**, or navigate to:
   ```
   http://127.0.0.1:8000/dashboard/suppliers/create
   ```
2. Enter the following test details:
   - **Factory / Supplier Name**: `Apex Knit Composite Ltd`
   - **Contact Person**: `Rafiqul Islam`
   - **Email**: `rafiq@apexknit.com`
   - **Phone**: `+880 1711 002233`
   - **Address**: `Plot 45, Gazipur Industrial Area, Dhaka, Bangladesh`
3. Click **Save Supplier**.
4. **Expected Result**: `Apex Knit Composite Ltd` appears in the Suppliers list.

---

## Step 5: Place a Customer Order (PO Booking)
The buyer orders 5,000 pcs of hoodies at $12.00/pc.

1. In the sidebar, click **Customer Orders** ➔ **Create Order**, or go to:
   ```
   http://127.0.0.1:8000/dashboard/customer-orders/create
   ```
2. Enter the order specifications:
   - **Customer**: Select `Nordic Wear Apparel AB`
   - **Supplier**: Select `Apex Knit Composite Ltd`
   - **Order No**: `PO-2026-SE901`
   - **Style No**: `HD-8840`
   - **Style Name**: `Men's Organic Fleece Hoodie`
   - **Composition**: `80% Organic Cotton, 20% Polyester, 320 GSM`
   - **Color Name**: `Heather Charcoal`
   - **Color Quantity**: `5000`
   - **Unit Price ($)**: `12.00`
   - **Order Date**: Select today's date
   - **ETD Date (Expected Delivery)**: Select a date 45 days from today
   - **Notes**: `Buyer requires GOTS certificate and nickel-free eyelets.`
3. Click **Save Order**.
4. **Expected Result**: 
   - Order `PO-2026-SE901` is saved with **Total Value: $60,000.00** ($12.00 × 5,000).

---

## Step 6: Verify Automatic Factory Order & Followup Creation
*Notice that you did NOT have to manually create the factory order! The system automatically initialized it.*

1. Go to **Factory Orders**:
   ```
   http://127.0.0.1:8000/dashboard/factory-orders
   ```
   - **Verify**: You see an entry for `PO-2026-SE901` assigned to `Apex Knit Composite Ltd`.
   - Click the **3-dot action** ➔ **Edit**:
     - Set **Sub Price ($)** (what you pay the factory): `8.50`
     - Set **FOB Price ($)**: `9.00`
     - Click **Update Factory Order**.
   - **Buying House Margin**:
     - Buyer Price: $12.00
     - Factory Price: $8.50
     - Your Gross Profit Margin: **$3.50/pc** ($17,500 total profit on this PO).

---

## Step 7: Update Factory Production & Sample Follow-ups
Merchandisers track sampling and manufacturing stages.

1. Go to **Factory Followups**:
   ```
   http://127.0.0.1:8000/dashboard/factory-followups
   ```
2. Find `PO-2026-SE901` and click **Edit Status** (or 3-dot menu):
   - **PPS Date**: Select today's date
   - **PPS Comments Status**: Change from `Pending` ➔ `Approved with Comments`
   - **Knitting Status**: Change from `Not Started` ➔ `In Progress`
   - **Dyeing Status**: Change from `Not Started` ➔ `In Progress`
   - **Cutting Status**: Change from `Not Started` ➔ `Not Started`
3. Click **Update Followup**.
4. **Visual Verification**: The badges in the table update to reflect the live status.

---

## Step 8: View the Factory Production Pipeline
1. In the sidebar under **Factory Orders**, click **Pipeline** or navigate to:
   ```
   http://127.0.0.1:8000/dashboard/factory-followups/pipeline
   ```
2. **Verify**: You see the production board displaying orders categorized by stage (`Knitting`, `Dyeing`, `Cutting`, `Completed`).

---

## Step 9: Issue Commercial Invoice to Buyer
Issue an invoice for 50% advance payment ($30,000.00).

1. In the sidebar, click **Invoices** ➔ **Create Invoice**:
   ```
   http://127.0.0.1:8000/dashboard/invoices/create
   ```
2. Fill in the invoice details:
   - **Customer Order**: Select `PO-2026-SE901 (Nordic Wear Apparel AB)`
   - **Invoice Number**: `INV-2026-001`
   - **Invoice Date**: Today's date
   - **Due Date**: 14 days from today
   - **Total Amount**: `30000.00`
   - **Status**: `Sent`
   - **Notes**: `50% Advance deposit as per sales contract.`
3. Click **Save Invoice**.
4. **Verify**: Invoice appears with status **Sent / Unpaid**.

---

## Step 10: Record Customer Payment
The buyer wires the $30,000.00 advance to your bank.

1. In the sidebar, click **Payments** ➔ **Record Payment**:
   ```
   http://127.0.0.1:8000/dashboard/payments/create
   ```
2. Enter the payment details:
   - **Customer Order**: Select `PO-2026-SE901`
   - **Invoice**: Select `INV-2026-001`
   - **Amount**: `30000.00`
   - **Payment Type**: `Bank Transfer`
   - **Deposit To Account**: Select `Primary Bank Account`
   - **Notes**: `Swift transfer ref: SW-982103`
3. Click **Save Payment**.
4. **Expected Result**:
   - A unique receipt number is generated (e.g. `REC-20260916-0001`).
   - `Primary Bank Account` balance **increases by +$30,000.00**.
   - An automatic balanced **Journal Entry** is posted to the General Ledger!

---

## Step 11: Record a Factory Expense / Trim Procurement
Your buying house purchases special YKK zippers and polybags for this order.

1. In the sidebar, click **Expenses** ➔ **Add Expense**:
   ```
   http://127.0.0.1:8000/dashboard/expenses/create
   ```
2. Enter the expense:
   - **Category (Chart of Accounts)**: Select `Direct Material / Production Expenses` (or `5100 - Raw Materials & Fabric`)
   - **Paid From (Office Account)**: Select `Primary Bank Account`
   - **Amount**: `2500.00`
   - **Payment Method**: `Bank Transfer`
   - **Description**: `YKK metal zippers and branded polybags for PO-2026-SE901`
   - **Date**: Today's date
3. Click **Save Expense**.
4. **Expected Result**:
   - `Primary Bank Account` balance decreases by **-$2,500.00**.
   - Auto-posted to double-entry general ledger.

---

## Step 12: Inspect General Ledger & Journal Entries
Verify that the accounting engine tracked every transaction automatically.

1. Go to **Journal Entries**:
   ```
   http://127.0.0.1:8000/dashboard/journal-entries
   ```
2. **Verify**:
   - You see the automatic journal entry created from the **Customer Payment** (Debit: Bank / Credit: Receivables).
   - You see the automatic journal entry created from the **Expense** (Debit: Production Cost / Credit: Bank).
3. Click on any entry to see balanced **Debits = Credits**.

---

## Step 13: View Executive Dashboard Analytics
See the real-time business metrics.

1. Click **Dashboard** in the top left, or navigate to:
   ```
   http://127.0.0.1:8000/dashboard
   ```
2. **Verify**:
   - **Total Orders**: Count increased by 1.
   - **Order Volume & Value Trends Chart**: Reflects the new order value ($60,000).
   - **Factory Production Status**: Shows the live counts for Knitting and Dyeing.
   - **Cash & Bank Balances**: Accurately reflects +$30,000 customer payment minus -$2,500 expense.

---

## Testing Checklist

| Step | Action | Status |
| :---: | :--- | :---: |
| 1 | Log in with default admin credentials | [ ] |
| 2 | Verify Office Accounts & Accounting Period | [ ] |
| 3 | Create Buyer (`Nordic Wear Apparel AB`) | [ ] |
| 4 | Create Factory (`Apex Knit Composite Ltd`) | [ ] |
| 5 | Book Customer Order `PO-2026-SE901` (5,000 pcs @ $12) | [ ] |
| 6 | Verify auto-created Factory Order & set sub-price ($8.50) | [ ] |
| 7 | Update Sampling (PPS) & Floor Stages (Knitting, Dyeing) | [ ] |
| 8 | Check Production Pipeline Board | [ ] |
| 9 | Issue Commercial Invoice ($30,000) | [ ] |
| 10 | Receive Buyer Payment into Primary Bank Account | [ ] |
| 11 | Log Factory Material Expense ($2,500) | [ ] |
| 12 | Verify balanced Journal Entries in General Ledger | [ ] |
| 13 | Verify live metrics on the Executive Dashboard | [ ] |

---
*Happy Testing! You have successfully verified the full Buying House lifecycle.*
