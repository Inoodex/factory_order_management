@extends('admin.layouts.master')

@section('title', 'Edit Invoice')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Invoice #{{ $invoice->invoice_number }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                View
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <form id="invoice-form" action="{{ route('admin.invoices.update', $invoice) }}" method="POST" x-data="invoiceForm()"
        @submit.prevent="submitInvoice">
        @csrf
        @method('PUT')

        <div class="panel mt-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="customer_order_id">Customer Order (Apparel / Factory)</label>
                    <select name="customer_order_id" id="customer_order_id" class="form-select"
                        x-model="selectedOrderId" @change="onOrderSelect">
                        <option value="">-- General Invoice / No Order --</option>
                        @foreach ($customerOrders as $ord)
                            <option value="{{ $ord->id }}" {{ $invoice->customer_order_id == $ord->id ? 'selected' : '' }}
                                data-order-no="{{ $ord->order_no }}"
                                data-style="{{ $ord->style_no }}"
                                data-customer="{{ $ord->customer?->name ?? 'N/A' }}"
                                data-qty="{{ $ord->color_qty }}"
                                data-price="{{ $ord->price }}"
                                data-total="{{ $ord->total_price ?: ($ord->price * $ord->color_qty) }}">
                                {{ $ord->order_no }} (Style: {{ $ord->style_no }}) — {{ $ord->customer?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="invoice_number">Invoice #</label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-input"
                        value="{{ $invoice->invoice_number }}" />
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partially_paid" {{ $invoice->status == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="void" {{ $invoice->status == 'void' ? 'selected' : '' }}>Void</option>
                    </select>
                </div>
            </div>

            {{-- Order Details --}}
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
                    <input type="date" name="date" id="date" class="form-input" required
                        value="{{ $invoice->date ? $invoice->date->format('Y-m-d') : date('Y-m-d') }}" />
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-input" required
                        value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days')) }}" />
                </div>
            </div>

            <div class="mt-5">
                <label>Notes <span class="text-xs text-white-dark">(appears on invoice PDF)</span></label>
                <input type="hidden" name="notes" id="notes" value="{{ $invoice->notes }}" />
                <div class="flex flex-col gap-2 mt-2" id="notes-container">
                    @php
                        $rawNotes = str_replace("\r\n", "\n", $invoice->notes ?? '');
                        $existingNotes = array_filter(array_map('trim', explode("\n", $rawNotes)));
                        
                        $defaultNotes = [
                            'Payment terms: As per agreed purchase order contract.',
                            'Goods once inspected and accepted are subject to standard factory warranty.',
                        ];

                        $allNotes = array_unique(array_merge($defaultNotes, $existingNotes));
                    @endphp

                    @foreach ($allNotes as $note)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="note-checkbox" {{ in_array($note, $existingNotes) || (empty($existingNotes) && in_array($note, $defaultNotes)) ? 'checked' : '' }} />
                            <input type="text" class="form-input text-sm note-text flex-1" value="{{ $note }}" />
                        </div>
                    @endforeach
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
                                        x-model="item.description" placeholder="Brief details about service..." required />
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
                    <a href="{{ route('admin.invoices.index') }}"
                        class="btn btn-outline-danger uppercase text-[10px] font-bold">Cancel</a>
                    <button type="submit"
                        class="btn btn-primary px-16 uppercase text-xs font-black shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                        Update Invoice
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        function invoiceForm() {
            return {
                items: {!! json_encode($invoice->items->map(fn($item) => [
                    'chart_of_account_id' => (string) $item->chart_of_account_id,
                    'description' => $item->description,
                    'quantity' => (float) $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                ])->values()) !!},
                grandTotal: 0,
                selectedOrderId: '{{ $invoice->customer_order_id }}',
                orderDetails: {!! json_encode($invoice->customerOrder ? [
                    'order_no' => $invoice->customerOrder->order_no,
                    'style' => $invoice->customerOrder->style_no,
                    'customer' => $invoice->customerOrder->customer?->name ?? 'N/A',
                    'qty' => $invoice->customerOrder->color_qty,
                    'total' => (float) ($invoice->customerOrder->total_price ?: ($invoice->customerOrder->price * $invoice->customerOrder->color_qty)),
                ] : null) !!},

                init() {
                    this.initNiceSelect();
                    this.syncNotes();
                    this.calculateTotals();
                },

                initNiceSelect() {
                    setTimeout(() => {
                        const el = document.getElementById('customer_order_id');
                        if (el) {
                            NiceSelect.bind(el, {
                                searchable: true,
                                placeholder: 'Select Factory Customer Order'
                            });
                        }
                    }, 100);
                },

                onOrderSelect() {
                    const el = document.getElementById('customer_order_id');
                    if (!el || !el.value) {
                        this.orderDetails = null;
                        return;
                    }

                    const opt = el.options[el.selectedIndex];
                    if (opt) {
                        this.orderDetails = {
                            order_no: opt.dataset.orderNo,
                            style: opt.dataset.style,
                            customer: opt.dataset.customer,
                            qty: opt.dataset.qty,
                            total: parseFloat(opt.dataset.total) || 0
                        };
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