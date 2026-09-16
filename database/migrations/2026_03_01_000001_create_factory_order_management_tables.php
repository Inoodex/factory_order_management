<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Personal Access Tokens (Sanctum)
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 2. Settings & System Notifications
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });


        // 3. Tyro Roles modification
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'is_active')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('slug');
            });
        }

        // 4. Double-Entry Accounting
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->year('year')->nullable();
            $table->unsignedTinyInteger('month')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('type', ['fiscal_year', 'monthly', 'quarterly'])->default('monthly');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('remarks')->nullable();
            $table->boolean('is_closed')->nullable()->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['year', 'month']);
        });

        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('office_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->enum('account_type', ['bank', 'mfs', 'cash']);
            $table->string('provider_name')->nullable();
            $table->string('account_number');
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->decimal('opening_balance', 15, 2)->default(0.00);
            $table->string('branch_name')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('accounting_periods');
            $table->date('date');
            $table->string('reference_number')->unique();
            $table->text('note')->nullable();
            $table->enum('status', ['draft', 'posted', 'void'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('period', ['monthly', 'yearly'])->default('monthly');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('office_accounts');
            $table->date('statement_date');
            $table->decimal('statement_balance', 15, 2);
            $table->decimal('system_balance', 15, 2);
            $table->decimal('difference', 15, 2)->default(0.00);
            $table->enum('status', ['draft', 'closed'])->default('draft');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('bank_reconciliation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reconciliation_id')->constrained('bank_reconciliations')->cascadeOnDelete();
            $table->foreignId('journal_entry_item_id')->nullable()->constrained('journal_entry_items')->nullOnDelete();
            $table->string('bank_statement_ref')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['matched', 'unmatched', 'adjustment'])->default('unmatched');
            $table->timestamp('matched_at')->nullable();
            $table->foreignId('matched_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 5. HR & Payroll
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_name');
            $table->string('month', 7); // Format: YYYY-MM
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('overtime_amount', 10, 2)->default(0.00);
            $table->decimal('bonus', 10, 2)->default(0.00);
            $table->decimal('allowances', 10, 2)->default(0.00);
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('tax_deduction', 10, 2)->default(0.00);
            $table->decimal('insurance_deduction', 10, 2)->default(0.00);
            $table->decimal('other_deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_banking', 'cheque'])->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'month']);
            $table->index(['payment_status', 'month']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_banking', 'cheque'])->nullable();
            $table->foreignId('office_account_id')->nullable()->constrained('office_accounts')->nullOnDelete();
            $table->foreignId('salary_id')->nullable()->constrained('salaries')->nullOnDelete();
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Factory Order Management Domain
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('session')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('order_no')->unique();
            $table->string('style_no');
            $table->string('style_name')->nullable();
            $table->string('style_image')->nullable();
            $table->string('composition')->nullable();
            $table->string('color_name')->nullable();
            $table->integer('color_qty')->default(0);
            $table->date('order_date')->nullable();
            $table->date('etd_date')->nullable();
            $table->decimal('price', 12, 2)->nullable()->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('factory_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id')->constrained('customer_orders')->cascadeOnDelete();
            $table->decimal('etd_price', 12, 2)->nullable()->default(0.00);
            $table->decimal('sub_price', 12, 2)->nullable()->default(0.00);
            $table->date('aetd_date')->nullable();
            $table->decimal('fob_price', 12, 2)->nullable()->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('factory_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_order_id')->constrained('factory_orders')->cascadeOnDelete();
            $table->date('pps_date')->nullable();
            $table->string('pps_comments_status')->nullable();
            $table->date('shs_sending_date')->nullable();
            $table->string('shs_comments_status')->nullable();
            $table->string('knitting_status')->nullable();
            $table->string('dyeing_status')->nullable();
            $table->string('cutting_status')->nullable();
            $table->decimal('fob_price', 12, 2)->nullable()->default(0.00);
            $table->decimal('sub_price', 12, 2)->nullable()->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Invoices, Payments, Commissions
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id')->nullable()->constrained('customer_orders')->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['draft', 'sent', 'paid', 'partially_paid', 'void'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->string('description');
            $table->decimal('quantity', 15, 2)->default(1.00);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id')->nullable()->constrained('customer_orders')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['advance', 'partial', 'final'])->default('advance');
            $table->dateTime('payment_date');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('receipt_number', 50)->nullable();
            $table->enum('payment_status', ['pending', 'completed'])->default('pending');
            $table->foreignId('office_account_id')->nullable()->constrained('office_accounts')->nullOnDelete();
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('factory_followups');
        Schema::dropIfExists('factory_orders');
        Schema::dropIfExists('customer_orders');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('bank_reconciliation_items');
        Schema::dropIfExists('bank_reconciliations');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('taxes');
        Schema::dropIfExists('journal_entry_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('office_accounts');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('accounting_periods');

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'is_active')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        Schema::dropIfExists('notifications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('personal_access_tokens');
    }
};
