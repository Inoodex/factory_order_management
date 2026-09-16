@extends('admin.layouts.master')

@section('title', 'Edit Office Account')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Office Account</h2>
        <a href="{{ route('admin.office-accounts.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.office-accounts.update', $officeAccount->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="account_name">Account Name <span class="text-danger">*</span></label>
                    <input type="text" name="account_name" id="account_name" class="form-input"
                        value="{{ old('account_name', $officeAccount->account_name) }}" required>
                    @error('account_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account_type">Account Type <span class="text-danger">*</span></label>
                    <select name="account_type" id="account_type" class="form-select" required>
                        <option value="bank"
                            {{ old('account_type', $officeAccount->account_type) == 'bank' ? 'selected' : '' }}>Bank
                        </option>
                        <option value="mfs"
                            {{ old('account_type', $officeAccount->account_type) == 'mfs' ? 'selected' : '' }}>MFS
                            (bKash/Nagad/Rocket)</option>
                        <option value="cash"
                            {{ old('account_type', $officeAccount->account_type) == 'cash' ? 'selected' : '' }}>Cash
                        </option>
                    </select>
                    @error('account_type')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="provider_name">Provider Name</label>
                    <input type="text" name="provider_name" id="provider_name" class="form-input"
                        value="{{ old('provider_name', $officeAccount->provider_name) }}">
                    @error('provider_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account_number">Account/Wallet Number <span class="text-danger">*</span></label>
                    <input type="text" name="account_number" id="account_number" class="form-input"
                        value="{{ old('account_number', $officeAccount->account_number) }}" required>
                    @error('account_number')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="opening_balance">Opening Balance</label>
                    <input type="number" name="opening_balance" id="opening_balance" step="0.01" class="form-input"
                        value="{{ old('opening_balance', $officeAccount->opening_balance) }}">
                    @error('opening_balance')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="branch_name">Branch Name (For Banks)</label>
                    <input type="text" name="branch_name" id="branch_name" class="form-input"
                        value="{{ old('branch_name', $officeAccount->branch_name) }}">
                    @error('branch_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ old('status', $officeAccount->status) == 'active' ? 'selected' : '' }}>
                            Active</option>
                        <option value="inactive"
                            {{ old('status', $officeAccount->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes', $officeAccount->notes) }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('admin.office-accounts.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update Account</button>
            </div>
        </form>
    </div>
@endsection
