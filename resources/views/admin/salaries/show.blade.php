@extends('admin.layouts.master')

@section('title', 'Salary Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Salary Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.salaries.edit', $salary->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="text-xs text-white-dark">Employee</p>
                <p class="font-semibold">{{ $salary->employee_name }}</p>
            </div>
            <div>
                <p class="text-xs text-white-dark">Month</p>
                <p class="font-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $salary->month)->format('F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-white-dark">Payment Status</p>
                <span class="badge badge-outline-{{ $salary->status_color }}">{{ $salary->status_label }}</span>
            </div>
            <div>
                <p class="text-xs text-white-dark">Recorded By</p>
                <p class="font-semibold">{{ $salary->creator->name ?? 'System' }}</p>
            </div>
        </div>

        <hr class="my-6 border-white-light dark:border-[#1b2e4b]" />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-md border border-white-light p-4 dark:border-[#1b2e4b]">
                <h6 class="mb-4 text-sm font-semibold uppercase text-white-dark">Earnings</h6>
                <div class="space-y-2">
                    <div class="flex justify-between"><span>Basic
                            Salary</span><span>{{ number_format($salary->basic_salary, 2) }}</span></div>
                    <div class="flex justify-between">
                        <span>Overtime</span><span>{{ number_format($salary->overtime_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between"><span>Bonus</span><span>{{ number_format($salary->bonus, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Allowances</span><span>{{ number_format($salary->allowances, 2) }}</span>
                    </div>
                    <div class="mt-3 flex justify-between border-t border-white-light pt-3 font-bold dark:border-[#1b2e4b]">
                        <span>Gross Salary</span>
                        <span class="text-primary">{{ number_format($salary->gross_salary, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-md border border-white-light p-4 dark:border-[#1b2e4b]">
                <h6 class="mb-4 text-sm font-semibold uppercase text-white-dark">Deductions & Payment</h6>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Tax</span><span>{{ number_format($salary->tax_deduction, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Insurance</span><span>{{ number_format($salary->insurance_deduction, 2) }}</span>
                    </div>
                    <div class="flex justify-between"><span>Other
                            Deductions</span><span>{{ number_format($salary->other_deductions, 2) }}</span></div>
                    <div class="flex justify-between"><span>Paid
                            Amount</span><span>{{ number_format($salary->paid_amount, 2) }}</span></div>
                    <div class="mt-3 flex justify-between border-t border-white-light pt-3 font-bold dark:border-[#1b2e4b]">
                        <span>Net Salary</span>
                        <span class="text-success">{{ number_format($salary->net_salary, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs text-white-dark">Payment Date</p>
                <p class="font-semibold">{{ optional($salary->payment_date)->format('M d, Y') ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-white-dark">Payment Method</p>
                <p class="font-semibold">
                    {{ $salary->payment_method ? ucwords(str_replace('_', ' ', $salary->payment_method)) : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-white-dark">Bank Name</p>
                <p class="font-semibold">{{ $salary->bank_name ?: 'N/A' }}</p>
                <p class="text-xs text-white-dark">Transaction ID</p>
                <p class="font-semibold">{{ $salary->transaction_id ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <p class="text-xs text-white-dark">Notes</p>
            <div class="mt-1 rounded-md bg-[#f1f2f3] p-4 dark:bg-[#0e1726]">
                {{ $salary->notes ?: 'No notes provided.' }}
            </div>
        </div>
    </div>

    @if ($salary->payment_status !== 'paid')
        <div class="panel mt-6">
            <h5 class="mb-4 text-lg font-semibold">Mark As Paid</h5>
            <form action="{{ route('admin.salaries.mark-paid', $salary->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-group">
                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" class="form-input"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_banking">Mobile Banking</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="transaction_id">Transaction ID</label>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-input">
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ $salary->notes }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-success px-10">Confirm Payment</button>
                </div>
            </form>
        </div>
    @endif
@endsection
