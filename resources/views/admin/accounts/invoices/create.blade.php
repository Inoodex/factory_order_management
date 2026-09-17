@extends('admin.layouts.master')

@section('title', 'Create Invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}">
    <style>
        .nice-select {
            width: 100%;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            background-image: none !important;
        }

        .nice-select .current {
            line-height: normal !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .nice-select .list {
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
        }

        .nice-select .nice-select-dropdown {
            width: 100% !important;
            z-index: 50 !important;
        }

        .form-select {
            background-image: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create New Invoice</h2>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <form id="invoice-form" action="{{ route('admin.invoices.store') }}" method="POST" x-data="invoiceForm()"
        @submit.prevent="submitInvoice">
        @csrf

        <div class="panel mt-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="customer_order_id" class="font-semibold text-sm">Customer Order (Apparel / Factory)</label>
                    <select name="customer_order_id" id="customer_order_id" class="form-select font-medium text-sm">
                        <option value="">-- General Invoice / No Specific Order --</option>
                        @foreach ($customerOrders as $ord)
                            <option value="{{ $ord->id }}"
                                {{ (old('customer_order_id', $selectedOrder?->id) == $ord->id) ? 'selected' : '' }}
                                data-order-no="{{ $ord->order_no }}"
                                data-style="{{ $ord->style_no }}"
                                data-customer="{{ $ord->customer?->name ?? 'N/A' }}"
                                data-qty="{{ $ord->color_qty }}"
                                data-price="{{ $ord->price }}"
                                data-total="{{ $ord->total_price ?: ($ord->price * $ord->color_qty) }}">
                                {{ $ord->order_no }} (Style: {{ $ord->style_no }}) — {{ $ord->customer?->name }} [Total: {{ number_format($ord->total_price ?: ($ord->price * $ord->color_qty), 2) }}]
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-500 mt-1 block">Select an order to link this invoice and auto-populate line items</span>
                </div>

                <div class="form-group">
                    <label for="invoice_number">Invoice # (Auto-generated if empty)</label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-input"
                        placeholder="INV-{{ date('Y') }}-XXXX" />
                </div>
            </div>

            {{-- Auto-populated Order Details --}}
            <div x-show="orderDetails" class="mt-5 p-4 bg-primary/5 rounded-lg border border-primary/20" x-cloak>
                <h6 class="text-sm font-bold text-primary uppercase mb-3">Order Details</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-white-dark">Buyer / Customer:</span> <span class="font-semibold"
                            x-text="orderDetails?.customer || '-'"></span></div>
                    <div><span class="text-white-dark">Style No:</span> <span class="font-semibold"
                            x-text="orderDetails?.style || '-'"></span></div>
                    <div><span class="text-white-dark">Quantity:</span> <span class="font-semibold"
                            x-text="orderDetails?.qty || '-'"></span></div>
                    <div><span class="text-white-dark">Total Order Value:</span> <span class="font-semibold text-primary"
                            x-text="formatCurrency(orderDetails?.total || 0)"></span></div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mt-5">
                <div class="form-group">
                    <label for="date">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required value="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-input" required
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}" />
                </div>
            </div>

            <div class="mt-5">
                <label>Notes <span class="text-xs text-white-dark">(appears on invoice PDF)</span></label>
                <input type="hidden" name="notes" id="notes" value="" />
                <div class="flex flex-col gap-2 mt-2" id="notes-container">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="note-checkbox" checked />
                        <input type="text" class="form-input text-sm note-text flex-1" value="Payment terms: As per agreed purchase order contract." />
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="note-checkbox" checked />
                        <input type="text" class="form-input text-sm note-text flex-1" value="Goods once inspected and accepted are subject to standard factory warranty." />
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($errors->has('msg'))
            <div class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded flex items-center gap-3 animate-shake">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-bold">{{ $errors->first('msg') }}</span>
            </div>
        @endif

        <!-- Dynamic Invoice Items -->
        <div class="panel mt-6">
            <h5 class="mb-5 text-lg font-black uppercase text-primary tracking-widest border-b pb-3">Line Item Details</h5>

            <div class="table-responsive">
                <table class="w-full">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="p-3 text-left w-1/4 uppercase text-[10px] tracking-widest">Revenue Head</th>
                            <th class="p-3 text-left uppercase text-[10px] tracking-widest"> Description</th>
                            <th class="p-3 text-right w-24 uppercase text-[10px] tracking-widest">Qty</th>
                            <th class="p-3 text-right w-32 uppercase text-[10px] tracking-widest">Amount</th>
                            <th class="p-3 text-right w-40 uppercase text-[10px] tracking-widest text-primary">Subtotal</th>
                            <th class="p-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b transition-all hover:bg-white-light/10">
                                <td class="p-2">
                                    <select :name="`items[${index}][chart_of_account_id]`"
                                        class="form-select text-xs font-bold" x-model="item.chart_of_account_id" required>
                                        <option value="">Select Ledger</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} -
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="`items[${index}][description]`" class="form-input text-xs"
                                        x-model="item.description" placeholder="Brief description of product / service..." required />
                                </td>
                                <td class="p-2">
                                    <input type="number" :name="`items[${index}][quantity]`" step="1"
                                        class="form-input text-right text-xs" x-model.number="item.quantity"
                                        @input="calculateTotals" required min="1" />
                                </td>
                                <td class="p-2">
                                    <input type="number" :name="`items[${index}][unit_price]`" step="0.01"
                                        class="form-input text-right text-xs font-mono font-bold"
                                        x-model.number="item.unit_price" @input="calculateTotals" required />
                                </td>
                                <td class="p-2 text-right font-black font-mono text-primary"
                                    x-text="formatCurrency(item.unit_price * item.quantity)"></td>
                                <td class="p-2 text-center text-danger">
                                    <button type="button" @click="removeItem(index)"
                                        class="hover:text-danger/70 transition-all" x-show="items.length > 1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <circle cx="12" cy="12" r="10" opacity="0.2" />
                                            <path d="M15 9l-6 6M9 9l6 6" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-primary/5">
                        <tr class="font-black text-2xl border-t-4 border-primary">
                            <td colspan="4" class="p-5 text-right uppercase text-[10px] tracking-[4px] text-white-dark">
                                Grand Invoice Total:</td>
                            <td class="p-5 text-right text-primary font-mono tracking-tighter"
                                x-text="formatCurrency(grandTotal)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-8 flex flex-wrap justify-between items-center gap-4">
                <button type="button" @click="addItem"
                    class="btn btn-outline-primary flex items-center gap-2 text-xs font-bold uppercase transition-transform hover:scale-105 active:scale-95">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    New Item
                </button>

                <div class="flex gap-4">
                    <button type="reset" @click="window.location.reload()"
                        class="btn btn-outline-danger uppercase text-[10px] font-bold">Reset</button>
                    <button type="submit"
                        class="btn btn-primary px-16 uppercase text-xs font-black shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                        Save Invoice
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@php
    $defaultRevenueAccount = $accounts->firstWhere('type', 'revenue') ?? $accounts->first();
@endphp

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        function invoiceForm() {
            const defaultAccountId = '{{ $defaultRevenueAccount?->id ?? '' }}';
            return {
                defaultAccountId: defaultAccountId,
                items: [{
                    chart_of_account_id: defaultAccountId,
                    description: '',
                    quantity: 1,
                    unit_price: 0
                }],
                grandTotal: 0,
                selectedOrderId: '{{ old('customer_order_id', $selectedOrder ? $selectedOrder->id : '') }}',
                orderDetails: null,

                init() {
                    this.syncNotes();
                    const self = this;
                    const el = document.getElementById('customer_order_id');
                    if (el && typeof NiceSelect !== 'undefined') {
                        NiceSelect.bind(el, {
                            searchable: true,
                            placeholder: 'Select Factory Customer Order'
                        });

                        el.addEventListener('change', function() {
                            self.onOrderSelect(this.value);
                        });
                    }

                    if (this.selectedOrderId) {
                        this.$nextTick(() => {
                            this.onOrderSelect(this.selectedOrderId);
                        });
                    }
                },

                onOrderSelect(orderId) {
                    this.selectedOrderId = orderId || '';

                    if (!orderId) {
                        this.orderDetails = null;
                        return;
                    }

                    const el = document.getElementById('customer_order_id');
                    const opt = el ? el.querySelector(`option[value="${orderId}"]`) : null;
                    if (opt && opt.value) {
                        this.orderDetails = {
                            order_no: opt.dataset.orderNo,
                            style: opt.dataset.style,
                            customer: opt.dataset.customer,
                            qty: opt.dataset.qty,
                            total: parseFloat(opt.dataset.total) || 0
                        };

                        // Auto-fill or update first line item with order details
                        if (this.items.length >= 1) {
                            if (!this.items[0].chart_of_account_id) {
                                this.items[0].chart_of_account_id = this.defaultAccountId;
                            }
                            this.items[0].description = `Apparel Order: ${opt.dataset.orderNo} (Style: ${opt.dataset.style})`;
                            this.items[0].quantity = parseInt(opt.dataset.qty) || 1;
                            this.items[0].unit_price = parseFloat(opt.dataset.price) || 0;
                            this.calculateTotals();
                        }
                    } else {
                        this.orderDetails = null;
                    }
                },

                addItem() {
                    this.items.push({
                        chart_of_account_id: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.grandTotal = this.items.reduce((sum, item) => sum + (parseFloat(item.quantity) * parseFloat(item.unit_price) || 0), 0);
                },

                formatCurrency(val) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(val || 0);
                },

                syncNotes() {
                    const rows = document.querySelectorAll('#notes-container > div');
                    const notes = [];
                    rows.forEach(row => {
                        const checkbox = row.querySelector('.note-checkbox');
                        const text = row.querySelector('.note-text');
                        if (checkbox && checkbox.checked && text && text.value.trim()) {
                            notes.push(text.value.trim());
                        }
                    });
                    document.getElementById('notes').value = notes.join('\n');
                },

                submitInvoice() {
                    this.calculateTotals();
                    if (this.grandTotal <= 0) {
                        alert('Invoice total must be greater than zero.');
                        return;
                    }
                    this.syncNotes();
                    document.getElementById('invoice-form').submit();
                }
            }
        }
    </script>
@endpush