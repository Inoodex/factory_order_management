@extends('admin.layouts.master')

@section('title', 'Create Payment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}">
    <style>
        .nice-select {
            width: 100%;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }

        .nice-select .current {
            line-height: normal !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .nice-select .list {
            width: 100%;
        }

        /* Fix double arrow issue */
        .nice-select {
            background-image: none !important;
        }

        .form-select {
            background-image: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Payment</h2>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.payments.store') }}" method="POST" id="paymentForm">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="customer_order_id">Customer Order (Apparel / Factory)</label>
                    <select name="customer_order_id" id="customer_order_id" class="form-select">
                        <option value="">-- Select Factory Customer Order --</option>
                        @foreach ($customerOrders as $ord)
                            <option value="{{ $ord->id }}"
                                {{ old('customer_order_id', $selected_order_id) == $ord->id ? 'selected' : '' }}>
                                {{ $ord->order_no }} (Style: {{ $ord->style_no }}) — {{ $ord->customer?->name }} [Total: {{ number_format($ord->total_price, 2) }}]
                            </option>
                        @endforeach
                    </select>
                    @error('customer_order_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group md:col-span-2" id="invoice_section">
                    <label for="invoice_id">Invoice<span class="text-danger">*</span></label>
                    <select name="invoice_id" id="invoice_id" class="form-select" required>
                        <option value="">-- Select Invoice --</option>
                    </select>
                    <span class="text-xs text-white-dark mt-1">Select a specific invoice to record payment against it</span>
                </div>

                <div id="balance_info_container" class="md:col-span-2 hidden">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 bg-primary/5 p-4 rounded-lg border border-primary/20">
                        <div class="flex flex-col">
                            <span class="text-xs text-white-dark uppercase">Total Amount</span>
                            <span id="info_total_fee" class="text-lg font-bold text-primary">0.00</span>
                        </div>
                        <div class="flex flex-col border-l border-r border-white-light/20 px-4">
                            <span class="text-xs text-white-dark uppercase">Total Paid</span>
                            <span id="info_total_paid" class="text-lg font-bold text-success">0.00</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-white-dark uppercase">Due Amount</span>
                            <span id="info_balance" class="text-lg font-bold text-danger">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-input" required step="0.01"
                        min="0" value="{{ old('amount') }}" />
                    @error('amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="advance" {{ old('payment_type') == 'advance' ? 'selected' : '' }}>Advance</option>
                        <option value="partial" {{ old('payment_type') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="final" {{ old('payment_type') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('payment_type')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="completed" {{ old('payment_status', 'completed') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                    </select>
                    @error('payment_status')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-input"
                        value="{{ old('payment_date', date('Y-m-d')) }}">
                    @error('payment_date')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="office_account_id">Collect To Account</label>
                    <select name="office_account_id" id="office_account_id" class="form-select">
                        <option value="">-- Select Account --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}"
                                {{ old('office_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }}
                                ({{ ucfirst($account->account_type) }}{{ $account->provider_name ? ' - ' . $account->provider_name : '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('office_account_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="3" placeholder="Additional information...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger" id="resetBtn">Reset Form</button>
                <button type="submit" class="btn btn-primary px-10" id="submitBtn">Save Payment</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderSelect = document.getElementById('customer_order_id');
            const amountInput = document.getElementById('amount');
            const paymentTypeSelect = document.getElementById('payment_type');
            const paymentStatusSelect = document.getElementById('payment_status');
            const balanceInfoContainer = document.getElementById('balance_info_container');
            const infoTotalFee = document.getElementById('info_total_fee');
            const infoTotalPaid = document.getElementById('info_total_paid');
            const infoBalance = document.getElementById('info_balance');
            const submitBtn = document.getElementById('submitBtn');

            let niceSelect = null;
            if (orderSelect) {
                niceSelect = NiceSelect.bind(orderSelect, {
                    searchable: true,
                    placeholder: 'Select Factory Customer Order'
                });
            }

            function loadInvoices(orderId) {
                const invoiceSection = document.getElementById('invoice_id');
                invoiceSection.innerHTML = '<option value="">-- Loading invoices... --</option>';

                if (!orderId) {
                    invoiceSection.innerHTML =
                        '<option value="">-- Select Invoice (Leave blank for general payment) --</option>';
                    return;
                }

                fetch(`{{ route('admin.payments.get-order-invoices') }}?customer_order_id=${orderId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Invoice endpoint returned ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        let options =
                            '<option value="">-- Select Invoice (Leave blank for general payment) --</option>';

                        if (Array.isArray(data.invoices) && data.invoices.length > 0) {
                            data.invoices.forEach(invoice => {
                                const remaining = invoice.total_amount - invoice.total_paid;
                                options += `<option value="${invoice.id}" data-amount="${remaining}" data-total="${invoice.total_amount}" data-paid="${invoice.total_paid}">
                                                ${invoice.invoice_number} 
                                            </option>`;
                            });
                        } else {
                            options =
                                '<option value="">-- No invoices found for this order --</option>';
                        }

                        invoiceSection.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error loading invoices:', error);
                        invoiceSection.innerHTML = '<option value="">-- Error loading invoices --</option>';
                    });
            }

            function updateBalance() {
                if (!orderSelect) return;
                const orderId = orderSelect.value;
                if (!orderId) {
                    balanceInfoContainer.classList.add('hidden');
                    return;
                }

                fetch(`{{ route('admin.payments.get-order-balance') }}?customer_order_id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        balanceInfoContainer.classList.remove('hidden');
                        infoTotalFee.textContent =
                            `${parseFloat(data.total_fee).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                        infoTotalPaid.textContent =
                            `${parseFloat(data.total_paid).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                        infoBalance.textContent =
                            `${parseFloat(data.balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

                        if (data.balance <= 0) {
                            amountInput.value = '0.00';
                            amountInput.disabled = true;
                            submitBtn.textContent = 'Save Payment';

                            // Visual feedback for completed status
                            infoBalance.classList.remove('text-danger');
                            infoBalance.classList.add('text-success');
                        } else {
                            amountInput.disabled = false;
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Save Payment';
                            amountInput.value = data.balance.toFixed(2);
                            amountInput.max = data.balance;

                            infoBalance.classList.remove('text-success');
                            infoBalance.classList.add('text-danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching balance:', error);
                    });
            }

            if (orderSelect) {
                orderSelect.addEventListener('change', function() {
                    loadInvoices(this.value);
                    updateBalance();
                });
            }

            // Handle invoice selection - auto-fill amount with remaining balance and update balance info
            document.getElementById('invoice_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value && selectedOption.dataset.amount) {
                    const remainingAmount = parseFloat(selectedOption.dataset.amount);
                    const totalAmount = parseFloat(selectedOption.dataset.total);
                    const totalPaid = parseFloat(selectedOption.dataset.paid);

                    // Update balance info for selected invoice
                    balanceInfoContainer.classList.remove('hidden');
                    infoTotalFee.textContent =
                        `${totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                    infoTotalPaid.textContent =
                        `${totalPaid.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                    infoBalance.textContent =
                        `${remainingAmount.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

                    if (remainingAmount > 0) {
                        amountInput.disabled = false;
                        amountInput.max = remainingAmount;
                        amountInput.value = remainingAmount.toFixed(2);
                    } else {
                        amountInput.disabled = true;
                    }
                } else {
                    // No invoice selected, show order balance
                    updateBalance();
                }
            });

            // Trigger once if order is pre-selected
            if (orderSelect && orderSelect.value) {
                loadInvoices(orderSelect.value);
                updateBalance();
            }

            document.getElementById('paymentForm').addEventListener('submit', function() {
                amountInput.disabled = false;
            });

            document.getElementById('resetBtn').addEventListener('click', function() {
                setTimeout(() => {
                    if (niceSelect) niceSelect.update();
                    balanceInfoContainer.classList.add('hidden');
                }, 10);
            });
        });
    </script>
@endpush
