@extends('admin.layouts.master')

@section('title', 'Edit Expense')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Expense</h2>
        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <input type="text" name="description" id="description" class="form-input"
                        value="{{ old('description', $expense->description) }}" required>
                    @error('description')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-input"
                        value="{{ old('amount', $expense->amount) }}" required>
                    @error('amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="expense_date">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" id="expense_date" class="form-input"
                        value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                    @error('expense_date')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="chart_of_account_id">Category <span class="text-danger">*</span></label>
                    <select name="chart_of_account_id" id="chart_of_account_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('chart_of_account_id', $expense->chart_of_account_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('chart_of_account_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">Select Payment Method</option>
                        <option value="cash"
                            {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer"
                            {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank
                            Transfer</option>
                        <option value="mobile_banking"
                            {{ old('payment_method', $expense->payment_method) == 'mobile_banking' ? 'selected' : '' }}>
                            Mobile Banking</option>
                    </select>
                    @error('payment_method')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="office_account_id">Pay From Account</label>
                    <select name="office_account_id" id="office_account_id" class="form-select">
                        <option value="">-- Select Account --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}"
                                {{ old('office_account_id', $expense->office_account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }}
                                ({{ ucfirst($account->account_type) }}{{ $account->provider_name ? ' - ' . $account->provider_name : '' }}
                                - {{ $account->account_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('office_account_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea"
                        rows="3">{{ old('notes', $expense->notes) }}</textarea>
                    @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div> --}}
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('admin.expenses.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update Expense</button>
            </div>
        </form>
    </div>
@endsection
