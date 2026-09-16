# Factory Order Management — Overall Project Plan

## 1. Overview
Laravel-based system to track orders from customer → supplier/factory → production follow-up. Built from client's data fields doc.

**Stack**: Laravel, MySQL, Breeze (auth) + spatie/laravel-permission (roles), Filament or custom Blade/Livewire (admin panel), maatwebsite/excel (import/export).

## 2. Confirmed decisions
| Item | Decision |
|---|---|
| Session field | Buying/production season |
| Status fields (PPS, SHS, Knitting, Dyeing, Cutting) | Fixed dropdown values |
| Style Image | File upload |
| Roles | Admin, Staff |
| Import/Export | Required |

## 3. Open items
- Exact dropdown value list per status field — pending from client

## 4. Data model (normalized)
- **customers**: name, brand, session
- **suppliers**: name, location
- **customer_orders**: customer_id, supplier_id, style_no, order_no, style_name, style_image, composition, color_name, color_qty, etd_date, order_date, price
- **factory_orders**: customer_order_id, etd_price, sub_price, aetd_date, fob_price
- **factory_followups**: factory_order_id, pps_date, pps_comments_status, shs_sending_date, shs_comments_status, knitting_status, dyeing_status, cutting_status, fob_price, sub_price

Relations: Customer 1—N CustomerOrder; Supplier 1—N CustomerOrder; CustomerOrder 1—1 FactoryOrder; FactoryOrder 1—1 FactoryFollowup.

## 5. Build phases

### Phase 1 — Foundation (mostly done)
- [x] Migrations (5 tables)
- [x] Models + relationships
- [ ] Seeders/factories for dev/test data
- [ ] Lock status dropdown values, convert to enum/const list

### Phase 2 — Auth & Roles
- [ ] Install Breeze
- [ ] Install spatie/laravel-permission
- [ ] Seed roles: admin, staff
- [ ] Middleware/gates per role (staff = limited access, TBD which sections)

### Phase 3 — Admin Panel / CRUD
- [ ] Decide: Filament (fast) vs custom Blade/Livewire (more control)
- [ ] Customers CRUD
- [ ] Suppliers CRUD
- [ ] Customer Orders CRUD + image upload
- [ ] Factory Orders CRUD (linked to Customer Order)
- [ ] Factory Follow-up CRUD (status dropdowns, linked to Factory Order)

### Phase 4 — Order Flow & Dashboard
- [ ] Order listing w/ filters (customer, supplier, status, ETD date range)
- [ ] Follow-up status board (PPS → SHS → Knitting → Dyeing → Cutting)
- [ ] Overdue ETD/AETD flagging

### Phase 5 — Import / Export
- [ ] Excel export: Customer Orders, Factory Orders, Follow-ups
- [ ] Excel import: bulk Customer Orders (template + validation)

### Phase 6 — Polish
- [ ] Search across orders
- [ ] Activity log / audit trail
- [ ] UI pass, responsive check

## 6. Immediate next steps
1. Get exact status dropdown values from client
2. Decide Filament vs custom admin panel
3. Scaffold auth + roles
4. Build Customers CRUD (first module)
