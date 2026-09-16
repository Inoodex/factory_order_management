@extends('admin.layouts.master')

@section('title', 'Post Journal Voucher')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Post Journal Voucher</h2>
        <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Journal
        </a>
    </div>

    <form id="journal-form" action="{{ route('admin.journal-entries.store') }}" method="POST" x-data="journalForm()"
        @submit.prevent="submitVoucher">
        @csrf

        <div class="panel mt-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div class="form-group">
                    <label for="date">Voucher Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required value="{{ date('Y-m-d') }}" />
                    @error('date')
                        <span class="text-danger text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="period_id">Accounting Period <span class="text-danger">*</span></label>
                    <select name="period_id" id="period_id" class="form-select" required>
                        <option value="">Select Period</option>
                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                        @endforeach
                    </select>
                    @error('period_id')
                        <span class="text-danger text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="reference_number">Reference # (Voucher No)</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-input"
                        placeholder="Auto-generated" />
                </div>
            </div>

            <div class="mt-5">
                <label for="note">Voucher Remarks</label>
                <textarea name="note" id="note" class="form-textarea" rows="2"
                    placeholder="General description of the transaction..."></textarea>
            </div>
        </div>

        @if ($errors->has('msg'))
            <div class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span class="font-bold">{{ $errors->first('msg') }}</span>
            </div>
        @endif

        <!-- Dynamic Ledger Items -->
        <div class="panel mt-6 overflow-x-auto">
            <h5 class="mb-5 text-lg font-semibold">Voucher Details (Double-Entry)</h5>

            <table class="w-full table-auto">
                <thead class="bg-white-light/30 dark:bg-dark/20">
                    <tr>
                        <th class="p-3 text-left w-1/4">Account Head</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-right w-32">Debit</th>
                        <th class="p-3 text-right w-32">Credit</th>
                        <th class="p-3 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b transition-colors hover:bg-white-light/10">
                            <td class="p-2">
                                <select :name="`items[${index}][chart_of_account_id]`" class="form-select text-xs"
                                    x-model="item.chart_of_account_id" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" :name="`items[${index}][description]`" class="form-input text-xs"
                                    x-model="item.description" placeholder="Row description..." required />
                            </td>
                            <td class="p-2">
                                <input type="number" :name="`items[${index}][debit]`" step="0.01"
                                    class="form-input text-right font-mono" x-model.number="item.debit"
                                    @input="calculateTotals" placeholder="0.00" />
                            </td>
                            <td class="p-2">
                                <input type="number" :name="`items[${index}][credit]`" step="0.01"
                                    class="form-input text-right font-mono" x-model.number="item.credit"
                                    @input="calculateTotals" placeholder="0.00" />
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" @click="removeItem(index)" class="text-danger hover:opacity-70"
                                    x-show="items.length > 2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="bg-white-light/10 font-bold border-t-2 border-primary/20">
                        <td colspan="2" class="p-3 text-right uppercase text-xs">Voucher Totals:</td>
                        <td class="p-3 text-right text-primary" x-text="formatCurrency(totalDebit)"></td>
                        <td class="p-3 text-right text-primary" x-text="formatCurrency(totalCredit)"></td>
                        <td></td>
                    </tr>
                    <tr :class="difference === 0 && totalDebit > 0 ? 'bg-success/5' : 'bg-danger/5'">
                        <td colspan="2" class="p-3 text-right font-bold uppercase text-xs">Balance Check:</td>
                        <td colspan="2" class="p-3 text-center text-lg"
                            :class="difference === 0 ? 'text-success' : 'text-danger font-black'">
                            <div class="flex items-center justify-center gap-2">
                                <span x-text="formatCurrency(difference)"></span>
                                <template x-if="difference === 0 && totalDebit > 0">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </template>
                            </div>
                            <span class="text-[10px] block uppercase opacity-70"
                                x-text="difference === 0 ? 'Entry is balanced' : 'Entry is unbalanced'"></span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-6 flex flex-wrap justify-between items-center gap-4">
                <button type="button" @click="addItem" class="btn btn-outline-info flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    New Row
                </button>

                <div class="flex gap-4">
                    <button type="reset" @click="window.location.reload()" class="btn btn-outline-danger">Discard</button>
                    <button type="submit" class="btn btn-primary px-12" :disabled="difference !== 0 || totalDebit === 0">
                        Post Voucher
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function journalForm() {
            return {
                items: [{
                    chart_of_account_id: '',
                    description: '',
                    debit: 0,
                    credit: 0
                },
                {
                    chart_of_account_id: '',
                    description: '',
                    debit: 0,
                    credit: 0
                }
                ],
                totalDebit: 0,
                totalCredit: 0,
                difference: 0,

                addItem() {
                    this.items.push({
                        chart_of_account_id: '',
                        description: '',
                        debit: 0,
                        credit: 0
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.totalDebit = this.items.reduce((sum, item) => sum + (parseFloat(item.debit) || 0), 0);
                    this.totalCredit = this.items.reduce((sum, item) => sum + (parseFloat(item.credit) || 0), 0);
                    this.difference = Math.abs(this.totalDebit - this.totalCredit);

                    if (this.difference < 0.009) this.difference = 0;
                },

                formatCurrency(val) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(val);
                },

                submitVoucher() {
                    this.calculateTotals();
                    if (this.difference !== 0) {
                        return;
                    }
                    if (this.totalDebit === 0) {
                        return;
                    }
                    document.getElementById('journal-form').submit();
                }
            }
        }
    </script>
@endpush