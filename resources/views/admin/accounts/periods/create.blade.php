@extends('admin.layouts.master')

@section('title', 'Create Accounting Period')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Accounting Period</h2>
        <a href="{{ route('admin.accounting-periods.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.accounting-periods.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="name">Period Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. FY 2026-27"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Period Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="fiscal_year" {{ old('type') == 'fiscal_year' ? 'selected' : '' }}>Fiscal Year
                        </option>
                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    </select>
                    @error('type')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input"
                        value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-input" value="{{ old('end_date') }}"
                        required>
                    @error('end_date')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="remarks">Remarks (Optional)</label>
                    <textarea name="remarks" id="remarks" rows="3" class="form-textarea" placeholder="Notes about this period...">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.accounting-periods.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary px-10">Create Period</button>
            </div>
        </form>
    </div>
@endsection
