<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Admin\{
    AccountingPeriodController,
    BankReconciliationController,
    BudgetController,
    ChartOfAccountController,
    CustomerController,
    CustomerOrderController,
    DashboardController,
    ExpenseController,
    FactoryFollowupController,
    FactoryOrderController,
    InvoiceController,
    JournalEntryController,
    NotificationController,
    OfficeAccountController,
    OrderImportExportController,
    PaymentController,
    ReportController,
    RoleController as LocalRoleController,
    SalaryController,
    SettingController,
    SupplierController
};
use App\Http\Controllers\FileServingController;

// Rate limiter for login routes (5 attempts per minute per IP)
RateLimiter::for('login', function () {
    return Limit::perMinute(5)->by(request()->ip());
});

Route::get('/', function () {
    return redirect()->route('tyro-login.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('tyro-dashboard.index');

// Settings
Route::prefix('dashboard/settings')->name('admin.settings.')->middleware('can:manage-settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/update', [SettingController::class, 'update'])->name('update');
});

// Notifications
Route::prefix('dashboard/notifications')->name('admin.notifications.')->group(function () {
    Route::get('count', [NotificationController::class, 'getUnreadCount'])->name('count');
    Route::get('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::get('read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
});

// ==========================================
// FACTORY ORDER MANAGEMENT MODULES
// ==========================================

// Customers / Buyers
Route::prefix('dashboard/customers')->name('admin.customers.')->middleware('auth')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/', [CustomerController::class, 'store'])->name('store');
    Route::get('{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('{customer}', [CustomerController::class, 'destroy'])->name('destroy');
});

// Suppliers / Factories
Route::prefix('dashboard/suppliers')->name('admin.suppliers.')->middleware('auth')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    Route::get('{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
    Route::put('{supplier}', [SupplierController::class, 'update'])->name('update');
    Route::delete('{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
});

// Customer Orders
Route::prefix('dashboard/customer-orders')->name('admin.customer-orders.')->middleware('auth')->group(function () {
    Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
    Route::get('/create', [CustomerOrderController::class, 'create'])->name('create');
    Route::post('/', [CustomerOrderController::class, 'store'])->name('store');
    Route::get('{customerOrder}', [CustomerOrderController::class, 'show'])->name('show');
    Route::get('{customerOrder}/edit', [CustomerOrderController::class, 'edit'])->name('edit');
    Route::put('{customerOrder}', [CustomerOrderController::class, 'update'])->name('update');
    Route::delete('{customerOrder}', [CustomerOrderController::class, 'destroy'])->name('destroy');
});

// Factory Orders (Pricing & AETD)
Route::prefix('dashboard/factory-orders')->name('admin.factory-orders.')->middleware('auth')->group(function () {
    Route::get('/', [FactoryOrderController::class, 'index'])->name('index');
    Route::get('{factoryOrder}/edit', [FactoryOrderController::class, 'edit'])->name('edit');
    Route::put('{factoryOrder}', [FactoryOrderController::class, 'update'])->name('update');
});

// Production Follow-up (Sampling & Production Stages)
Route::prefix('dashboard/factory-followups')->name('admin.factory-followups.')->middleware('auth')->group(function () {
    Route::get('/', [FactoryFollowupController::class, 'index'])->name('index');
    Route::get('/pipeline', [FactoryFollowupController::class, 'pipeline'])->name('pipeline');
    Route::get('{factoryFollowup}/edit', [FactoryFollowupController::class, 'edit'])->name('edit');
    Route::put('{factoryFollowup}', [FactoryFollowupController::class, 'update'])->name('update');
});

// Bulk Import & Export
Route::prefix('dashboard/order-import-export')->name('admin.order-import-export.')->middleware('auth')->group(function () {
    Route::get('/export', [OrderImportExportController::class, 'export'])->name('export');
    Route::get('/import', [OrderImportExportController::class, 'importView'])->name('import-view');
    Route::get('/download-template', [OrderImportExportController::class, 'downloadTemplate'])->name('download-template');
    Route::post('/import', [OrderImportExportController::class, 'import'])->name('import');
});

// ==========================================
// PRESERVED FINANCIAL & ACCOUNTING MODULES
// ==========================================

// Payment Management
Route::prefix('dashboard/payments')->name('admin.payments.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/report', [PaymentController::class, 'report'])->name('report')->middleware('can:*accountant');
    Route::get('/create', [PaymentController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [PaymentController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('{payment}/edit', [PaymentController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::get('{payment}/download-invoice', [PaymentController::class, 'downloadInvoice'])->name('download-invoice')->middleware('can:*accountant');
    Route::get('/get-application-balance', [PaymentController::class, 'getApplicationBalance'])->name('get-application-balance');
    Route::get('/get-application-invoices', [PaymentController::class, 'getApplicationInvoices'])->name('get-application-invoices');
    Route::put('{payment}', [PaymentController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{payment}', [PaymentController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Role Management
Route::prefix('dashboard/roles')->name('tyro-dashboard.roles.')->group(function () {
    Route::get('/', [LocalRoleController::class, 'index'])->name('index');
    Route::get('/create', [LocalRoleController::class, 'create'])->name('create');
    Route::post('/', [LocalRoleController::class, 'store'])->name('store');
    Route::get('{id}/edit', [LocalRoleController::class, 'edit'])->name('edit');
    Route::put('{id}', [LocalRoleController::class, 'update'])->name('update');
    Route::post('{id}/toggle', [LocalRoleController::class, 'toggleStatus'])->name('toggle');
    Route::delete('{id}', [LocalRoleController::class, 'destroy'])->name('destroy');
});

// Expense Management
Route::prefix('dashboard/expenses')->name('admin.expenses.')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/report', [ExpenseController::class, 'report'])->name('report')->middleware('can:*accountant');
    Route::get('/create', [ExpenseController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [ExpenseController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('{expense}/pdf', [ExpenseController::class, 'downloadPdf'])->name('download-pdf')->middleware('can:*accountant');
    Route::get('{expense}/edit', [ExpenseController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::put('{expense}', [ExpenseController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{expense}', [ExpenseController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
    Route::get('/dashboard/expenses/preview', [ExpenseController::class, 'preview'])->name('preview')->middleware('can:*accountant');
    Route::get('/dashboard/expenses/download', [ExpenseController::class, 'download'])->name('download')->middleware('can:*accountant');
});

// Office Accounts Management
Route::prefix('dashboard/office-accounts')->name('admin.office-accounts.')->group(function () {
    Route::get('/', [OfficeAccountController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/create', [OfficeAccountController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [OfficeAccountController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('{officeAccount}/edit', [OfficeAccountController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::put('{officeAccount}', [OfficeAccountController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{officeAccount}', [OfficeAccountController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Budget Management
Route::prefix('dashboard/budgets')->name('admin.budgets.')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/create', [BudgetController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [BudgetController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('{budget}/edit', [BudgetController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::put('{budget}', [BudgetController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{budget}', [BudgetController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Accounting Periods
Route::prefix('dashboard/accounting-periods')->name('admin.accounting-periods.')->group(function () {
    Route::get('/', [AccountingPeriodController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/create', [AccountingPeriodController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::get('/{period}/edit', [AccountingPeriodController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::post('/', [AccountingPeriodController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::put('{period}', [AccountingPeriodController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{period}', [AccountingPeriodController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Chart of Accounts
Route::prefix('dashboard/chart-of-accounts')->name('admin.chart-of-accounts.')->group(function () {
    Route::get('/', [ChartOfAccountController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/{account}/edit', [ChartOfAccountController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::post('/', [ChartOfAccountController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::post('{account}/status', [ChartOfAccountController::class, 'toggleStatus'])->name('status')->middleware('can:*accountant');
    Route::put('{account}', [ChartOfAccountController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('{account}', [ChartOfAccountController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Journal Entries
Route::prefix('dashboard/journal-entries')->name('admin.journal-entries.')->group(function () {
    Route::get('/report', [JournalEntryController::class, 'report'])->name('report')->middleware('can:*accountant');
    Route::get('/', [JournalEntryController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/create', [JournalEntryController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [JournalEntryController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('{journalEntry}', [JournalEntryController::class, 'show'])->name('show')->middleware('can:*accountant');
    Route::delete('{journalEntry}', [JournalEntryController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Invoices
Route::prefix('dashboard/invoices')->name('admin.invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index')->middleware('can:*consultant|*accountant');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create')->middleware('can:*consultant|*accountant');
    Route::post('/', [InvoiceController::class, 'store'])->name('store')->middleware('can:*consultant|*accountant');
    Route::get('{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::put('{invoice}', [InvoiceController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::get('{invoice}', [InvoiceController::class, 'show'])->name('show')->middleware('can:*consultant|*accountant');
    Route::delete('{invoice}', [InvoiceController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
    Route::get('/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('download-pdf')->middleware('can:*consultant|*accountant');
});

// Bank Reconciliations
Route::prefix('dashboard/bank-reconciliations')->name('admin.bank-reconciliations.')->middleware(['auth'])->group(function () {
    Route::get('/', [BankReconciliationController::class, 'index'])->name('index');
    Route::get('/create', [BankReconciliationController::class, 'create'])->name('create');
    Route::post('/', [BankReconciliationController::class, 'store'])->name('store');
    Route::get('/{reconciliation}', [BankReconciliationController::class, 'show'])->name('show');
    Route::post('/{reconciliation}/match', [BankReconciliationController::class, 'matchItem'])->name('match');
    Route::post('/{reconciliation}/unmatch', [BankReconciliationController::class, 'unmatchItem'])->name('unmatch');
    Route::post('/{reconciliation}/close', [BankReconciliationController::class, 'close'])->name('close');
    Route::delete('/{reconciliation}', [BankReconciliationController::class, 'destroy'])->name('destroy');
});

// Financial Reports
Route::prefix('dashboard/reports')->name('admin.reports.')->group(function () {
    Route::get('/summary', [ReportController::class, 'summary'])->name('summary')->middleware('can:*accountant');
    Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet')->middleware('can:*accountant');
    Route::get('/balance-sheet/pdf', [ReportController::class, 'balanceSheetPdf'])->name('balance-sheet.pdf')->middleware('can:*accountant');
    Route::get('/download-pdf', [ReportController::class, 'downloadPdf'])->name('download-pdf')->middleware('can:*accountant');
});

// ==========================================
// PRESERVED HR & PAYROLL MODULES
// ==========================================

Route::prefix('dashboard/salaries')->name('admin.salaries.')->group(function () {
    Route::get('/get-employee-details', [SalaryController::class, 'getEmployeeDetails'])->name('get-employee-details');
    Route::get('/check-existing', [SalaryController::class, 'checkExistingSalary'])->name('check-existing');
    Route::get('/export-excel', [SalaryController::class, 'exportExcel'])->name('export-excel')->middleware('can:*accountant');
    Route::get('/export-pdf', [SalaryController::class, 'exportPdf'])->name('export-pdf')->middleware('can:*accountant');
    Route::post('/{salary}/mark-paid', [SalaryController::class, 'markAsPaid'])->name('mark-paid')->middleware('can:*accountant');
    Route::post('/bulk-store', [SalaryController::class, 'bulkStore'])->name('bulk-store')->middleware('can:*accountant');
    Route::post('/bulk-update-basic-salary', [SalaryController::class, 'bulkUpdateBasicSalary'])->name('bulk-update-basic-salary')->middleware('can:*accountant');
    Route::post('/bulk-update-account-details', [SalaryController::class, 'bulkUpdateAccountDetails'])->name('bulk-update-account-details')->middleware('can:*accountant');
    Route::get('/generate', [SalaryController::class, 'generate'])->name('generate')->middleware('can:*accountant');
    Route::get('/bulk-pay-form', [SalaryController::class, 'bulkPayForm'])->name('bulk-pay-form')->middleware('can:*accountant');
    Route::post('/bulk-pay', [SalaryController::class, 'bulkPay'])->name('bulk-pay')->middleware('can:*accountant');
    Route::get('/', [SalaryController::class, 'index'])->name('index')->middleware('can:*accountant');
    Route::get('/create', [SalaryController::class, 'create'])->name('create')->middleware('can:*accountant');
    Route::post('/', [SalaryController::class, 'store'])->name('store')->middleware('can:*accountant');
    Route::get('/{salary}', [SalaryController::class, 'show'])->name('show')->middleware('can:*accountant');
    Route::get('/{salary}/edit', [SalaryController::class, 'edit'])->name('edit')->middleware('can:*accountant');
    Route::put('/{salary}', [SalaryController::class, 'update'])->name('update')->middleware('can:*accountant');
    Route::delete('/{salary}', [SalaryController::class, 'destroy'])->name('destroy')->middleware('can:*accountant');
});

// Storage file serving
Route::get('/files/preview/{path}', [FileServingController::class, 'preview'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('preview-file');

Route::get('/files/download/{path}', [FileServingController::class, 'download'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('download-file');

Route::get('/files/{path}', [FileServingController::class, 'serveFile'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('serve-file');
