@extends('admin.layouts.master')

@section('title', 'Edit Chart of Account')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Account: {{ $account->code }} - {{ $account->name }}</h2>
        <a href="{{ route('admin.chart-of-accounts.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.chart-of-accounts.update', $account) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="type">Account Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="asset" {{ old('type', $account->type) == 'asset' ? 'selected' : '' }}>Asset</option>
                        <option value="liability" {{ old('type', $account->type) == 'liability' ? 'selected' : '' }}>Liability</option>
                        <option value="equity" {{ old('type', $account->type) == 'equity' ? 'selected' : '' }}>Equity</option>
                        <option value="revenue" {{ old('type', $account->type) == 'revenue' ? 'selected' : '' }}>Revenue</option>
                        <option value="expense" {{ old('type', $account->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Head (Optional)</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="">No Parent (Top Level)</option>
                        @foreach ($parentAccounts as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }} ({{ ucfirst($parent->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code">Account Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code" class="form-input" value="{{ old('code', $account->code) }}" required>
                    @error('code')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Account Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $account->name) }}" required>
                    @error('name')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.chart-of-accounts.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary px-10">Update Account</button>
            </div>
        </form>
    </div>
@endsection
