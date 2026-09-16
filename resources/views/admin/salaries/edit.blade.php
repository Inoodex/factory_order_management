@extends('admin.layouts.master')

@section('title', 'Edit Salary')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Salary</h2>
        <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.salaries.update', $salary->id) }}" method="POST" id="salaryForm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="user_id">Employee (Optional)</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Select Employee</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $salary->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="employee_name">Employee Name <span class="text-danger">*</span></label>
                    <input type="text" name="employee_name" id="employee_name" class="form-input"
                        value="{{ old('employee_name', $salary->employee_name) }}" required>
                    @error('employee_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="month">Month <span class="text-danger">*</span></label>
                    <input type="month" name="month" id="month" class="form-input"
                        value="{{ old('month', $salary->month) }}" required>
                    @error('month') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending" {{ old('payment_status', $salary->payment_status) == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="partial" {{ old('payment_status', $salary->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ old('payment_status', $salary->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('payment_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="basic_salary">Basic Salary <span class="text-danger">*</span></label>
                    <input type="number" name="basic_salary" id="basic_salary" class="form-input amount-input" step="0.01"
                        min="0" value="{{ old('basic_salary', $salary->basic_salary) }}" required>
                    @error('basic_salary') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="overtime_amount">Overtime Amount</label>
                    <input type="number" name="overtime_amount" id="overtime_amount" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('overtime_amount', $salary->overtime_amount) }}">
                    @error('overtime_amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="bonus">Bonus</label>
                    <input type="number" name="bonus" id="bonus" class="form-input amount-input" step="0.01" min="0"
                        value="{{ old('bonus', $salary->bonus) }}">
                    @error('bonus') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="allowances">Allowances</label>
                    <input type="number" name="allowances" id="allowances" class="form-input amount-input" step="0.01"
                        min="0" value="{{ old('allowances', $salary->allowances) }}">
                    @error('allowances') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="tax_deduction">Tax Deduction</label>
                    <input type="number" name="tax_deduction" id="tax_deduction" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('tax_deduction', $salary->tax_deduction) }}">
                    @error('tax_deduction') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="insurance_deduction">Insurance Deduction</label>
                    <input type="number" name="insurance_deduction" id="insurance_deduction" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('insurance_deduction', $salary->insurance_deduction) }}">
                    @error('insurance_deduction') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="other_deductions">Other Deductions</label>
                    <input type="number" name="other_deductions" id="other_deductions" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('other_deductions', $salary->other_deductions) }}">
                    @error('other_deductions') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="paid_amount">Paid Amount</label>
                    <input type="number" name="paid_amount" id="paid_amount" class="form-input" step="0.01" min="0"
                        value="{{ old('paid_amount', $salary->paid_amount) }}" readonly>
                    @error('paid_amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-input"
                        value="{{ old('payment_date', optional($salary->payment_date)->format('Y-m-d') ?: date('Y-m-d')) }}">
                    @error('payment_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">Select Method</option>
                        <option value="cash" {{ old('payment_method', $salary->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method', $salary->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank
                            Transfer</option>
                        <option value="mobile_banking" {{ old('payment_method', $salary->payment_method) == 'mobile_banking' ? 'selected' : '' }}>
                            Mobile Banking</option>
                        <option value="cheque" {{ old('payment_method', $salary->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                    @error('payment_method') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="bank_name">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-input"
                        value="{{ old('bank_name', $salary->bank_name) }}">
                    @error('bank_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="transaction_id">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-input"
                        value="{{ old('transaction_id', $salary->transaction_id) }}">
                    @error('transaction_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 rounded-md border border-white-light p-4 dark:border-[#1b2e4b]">
                    <div class="mb-3 text-sm font-semibold uppercase text-white-dark">Calculated Summary</div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <div class="text-xs text-white-dark">Gross Salary</div>
                            <div id="gross_salary_preview" class="text-lg font-bold text-primary">0.00</div>
                        </div>
                        <div>
                            <div class="text-xs text-white-dark">Total Deductions</div>
                            <div id="deduction_preview" class="text-lg font-bold text-danger">0.00</div>
                        </div>
                        <div>
                            <div class="text-xs text-white-dark">Net Salary</div>
                            <div id="net_salary_preview" class="text-lg font-bold text-success">0.00</div>
                        </div>
                    </div>
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea"
                        rows="3">{{ old('notes', $salary->notes) }}</textarea>
                    @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('admin.salaries.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update Salary</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentStatusInput = document.getElementById('payment_status');
            const paidAmountInput = document.getElementById('paid_amount');

            const basicSalaryInput = document.getElementById('basic_salary');
            const overtimeAmountInput = document.getElementById('overtime_amount');
            const bonusInput = document.getElementById('bonus');
            const allowancesInput = document.getElementById('allowances');
            const taxDeductionInput = document.getElementById('tax_deduction');
            const insuranceDeductionInput = document.getElementById('insurance_deduction');
            const otherDeductionsInput = document.getElementById('other_deductions');

            const grossPreview = document.getElementById('gross_salary_preview');
            const deductionPreview = document.getElementById('deduction_preview');
            const netPreview = document.getElementById('net_salary_preview');

            const toNumber = (value) => parseFloat(value || 0) || 0;
            const toAmount = (value) => value.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            function calculateNet() {
                const gross = toNumber(basicSalaryInput.value) +
                    toNumber(overtimeAmountInput.value) +
                    toNumber(bonusInput.value) +
                    toNumber(allowancesInput.value);

                const deductions = toNumber(taxDeductionInput.value) +
                    toNumber(insuranceDeductionInput.value) +
                    toNumber(otherDeductionsInput.value);

                return {
                    gross,
                    deductions,
                    net: gross - deductions
                };
            }

            function updateSummary() {
                const values = calculateNet();
                grossPreview.textContent = toAmount(values.gross);
                deductionPreview.textContent = toAmount(values.deductions);
                netPreview.textContent = toAmount(values.net);

                paidAmountInput.value = values.net.toFixed(2);
            }

            document.querySelectorAll('.amount-input').forEach((el) => {
                el.addEventListener('input', updateSummary);
            });

            paymentStatusInput.addEventListener('change', function () {
                updateSummary();
            });

            updateSummary();
        });
    </script>
@endpush
