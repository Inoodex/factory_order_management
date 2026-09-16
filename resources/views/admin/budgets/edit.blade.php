@extends('admin.layouts.master')

@section('title', 'Edit Budget Allocation')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Budget Allocation</h2>
        <a href="{{ route('admin.budgets.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.budgets.update', $budget->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="chart_of_account_id">Budget Category <span class="text-danger">*</span></label>
                    <select name="chart_of_account_id" id="chart_of_account_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}"
                                {{ old('chart_of_account_id', $budget->chart_of_account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('chart_of_account_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Allocated Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-input" step="0.01"
                        value="{{ old('amount', $budget->amount) }}" required>
                    @error('amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="period">Budget Period <span class="text-danger">*</span></label>
                    <select name="period" id="period" class="form-select" required>
                        <option value="monthly" {{ old('period', $budget->period) == 'monthly' ? 'selected' : '' }}>Monthly
                        </option>
                        <option value="yearly" {{ old('period', $budget->period) == 'yearly' ? 'selected' : '' }}>Yearly
                        </option>
                    </select>
                    @error('period')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-input"
                            value="{{ old('start_date', $budget->start_date) }}" required>
                        @error('start_date')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-input"
                            value="{{ old('end_date', $budget->end_date) }}" required>
                        @error('end_date')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes', $budget->notes) }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Budget</button>
            </div>
        </form>
    </div>
@endsection
