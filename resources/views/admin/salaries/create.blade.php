@extends('admin.layouts.master')

@section('title', 'Create Salary')

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
        <h2 class="text-xl font-semibold uppercase">Create Salary</h2>
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
        <form action="{{ route('admin.salaries.store') }}" method="POST" id="salaryForm">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="user_id">Employee</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Select Employee</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="employee_name">Employee Name <span class="text-danger">*</span></label>
                    <input type="text" name="employee_name" id="employee_name" class="form-input"
                        value="{{ old('employee_name') }}" required>
                    @error('employee_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="month">Month <span class="text-danger">*</span></label>
                    <input type="month" name="month" id="month" class="form-input"
                        value="{{ old('month', $defaultMonth) }}" required>
                    @error('month')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="basic_salary">Basic Salary <span class="text-danger">*</span></label>
                    <input type="number" name="basic_salary" id="basic_salary" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('basic_salary') }}" required>
                    @error('basic_salary')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="overtime_amount">Overtime Amount</label>
                    <input type="number" name="overtime_amount" id="overtime_amount" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('overtime_amount', 0) }}">
                    @error('overtime_amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bonus">Bonus</label>
                    <input type="number" name="bonus" id="bonus" class="form-input amount-input" step="0.01"
                        min="0" value="{{ old('bonus', 0) }}">
                    @error('bonus')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="allowances">Allowances</label>
                    <input type="number" name="allowances" id="allowances" class="form-input amount-input" step="0.01"
                        min="0" value="{{ old('allowances', 0) }}">
                    @error('allowances')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tax_deduction">Tax Deduction</label>
                    <input type="number" name="tax_deduction" id="tax_deduction" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('tax_deduction', 0) }}">
                    @error('tax_deduction')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="insurance_deduction">Insurance Deduction</label>
                    <input type="number" name="insurance_deduction" id="insurance_deduction"
                        class="form-input amount-input" step="0.01" min="0"
                        value="{{ old('insurance_deduction', 0) }}">
                    @error('insurance_deduction')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="other_deductions">Other Deductions</label>
                    <input type="number" name="other_deductions" id="other_deductions" class="form-input amount-input"
                        step="0.01" min="0" value="{{ old('other_deductions', 0) }}">
                    @error('other_deductions')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="paid_amount">Paid Amount</label>
                    <input type="number" name="paid_amount" id="paid_amount" class="form-input" step="0.01"
                        min="0" value="{{ old('paid_amount', 0) }}" readonly>
                    @error('paid_amount')
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
                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial
                        </option>
                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('payment_status')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">Select Method</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                            Bank
                            Transfer</option>
                        <option value="mobile_banking" {{ old('payment_method') == 'mobile_banking' ? 'selected' : '' }}>
                            Mobile Banking</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                    @error('payment_method')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bank_name">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-input"
                        value="{{ old('bank_name') }}">
                    @error('bank_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="transaction_id">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-input"
                        value="{{ old('transaction_id') }}">
                    @error('transaction_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
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
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
                <button type="submit" class="btn btn-primary px-10">Save</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_id');
            const employeeNameInput = document.getElementById('employee_name');
            const monthInput = document.getElementById('month');
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

            const userNiceSelect = NiceSelect.bind(userSelect, {
                searchable: true,
                placeholder: 'Select Employee'
            });

            function updateSummary() {
                const gross = toNumber(basicSalaryInput.value) +
                    toNumber(overtimeAmountInput.value) +
                    toNumber(bonusInput.value) +
                    toNumber(allowancesInput.value);

                const deductions = toNumber(taxDeductionInput.value) +
                    toNumber(insuranceDeductionInput.value) +
                    toNumber(otherDeductionsInput.value);

                const net = gross - deductions;

                grossPreview.textContent = toAmount(gross);
                deductionPreview.textContent = toAmount(deductions);
                netPreview.textContent = toAmount(net);

                paidAmountInput.value = net.toFixed(2);
            }

            document.querySelectorAll('.amount-input').forEach((el) => {
                el.addEventListener('input', updateSummary);
            });

            paymentStatusInput.addEventListener('change', function() {
                updateSummary();
            });

            userSelect.addEventListener('change', function() {
                if (!this.value) {
                    return;
                }

                fetch(`{{ route('admin.salaries.get-employee-details') }}?user_id=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.name) {
                            employeeNameInput.value = data.name;
                        }
                    })
                    .catch(() => {});

                if (monthInput.value) {
                    fetch(
                            `{{ route('admin.salaries.check-existing') }}?user_id=${this.value}&month=${monthInput.value}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                alert(data.message);
                            }
                        })
                        .catch(() => {});
                }
            });

            monthInput.addEventListener('change', function() {
                if (!userSelect.value || !this.value) {
                    return;
                }
                fetch(
                        `{{ route('admin.salaries.check-existing') }}?user_id=${userSelect.value}&month=${this.value}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            alert(data.message);
                        }
                    })
                    .catch(() => {});
            });

            document.getElementById('salaryForm').addEventListener('reset', function() {
                setTimeout(() => {
                    userNiceSelect.update();
                }, 10);
            });

            updateSummary();
        });
    </script>
@endpush
