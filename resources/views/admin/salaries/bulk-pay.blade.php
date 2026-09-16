@extends('admin.layouts.master')

@section('title', 'Bulk Salary Payment')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bulk Salary Payment</h2>
        <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
        <!-- Summary Panel -->
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold mb-4">Selected Salaries for Payment</h3>
                    <div class="relative overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Employee</th>
                                    <th class="text-left py-2">Month</th>
                                    <th class="text-right py-2">Net Salary</th>
                                    <th class="text-right py-2">Paid</th>
                                    <th class="text-right py-2">Due Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $salary)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 font-semibold">
                                            {{ $salary->employee_name }}
                                        </td>
                                        <td class="py-3">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $salary->month)->format('M Y') }}
                                        </td>
                                        <td class="py-3 text-right">
                                            {{ number_format($salary->net_salary, 2) }}
                                        </td>
                                        <td class="py-3 text-right">
                                            {{ number_format($salary->paid_amount, 2) }}
                                        </td>
                                        <td class="py-3 text-right font-bold text-danger">
                                            {{ number_format($salary->net_salary - $salary->paid_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-bold bg-gray-100">
                                    <td colspan="4" class="py-3 text-right">Total Due Amount:</td>
                                    <td class="py-3 text-right text-danger">{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div>
            <div class="panel">
                <form action="{{ route('admin.salaries.bulk-pay') }}" method="POST">
                    @csrf

                    <!-- Hidden salary IDs -->
                    @foreach ($salaries as $salary)
                        <input type="hidden" name="salary_ids[]" value="{{ $salary->id }}">
                    @endforeach

                    <h3 class="text-lg font-semibold mb-4">Payment Details</h3>

                    <div class="form-group mb-4">
                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" class="form-input"
                            value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="mobile_banking"
                                {{ old('payment_method') == 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>

                        </select>
                        @error('payment_method')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="office_account_id">Pay From Account</label>
                        <select name="office_account_id" id="office_account_id" class="form-select">
                            <option value="">-- Select Account --</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('office_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->account_name }} ({{ $account->account_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('office_account_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="chart_of_account_id">Category <span class="text-danger">*</span></label>
                        <select name="chart_of_account_id" id="chart_of_account_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('chart_of_account_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('chart_of_account_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-danger flex-1">Cancel</a>
                        <button type="submit" class="btn btn-success flex-1">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
