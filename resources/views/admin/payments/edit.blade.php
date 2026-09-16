@extends('admin.layouts.master')

@section('title', 'Edit Payment')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Payment</h2>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="customer_order_id">Customer Order</label>
                    <input type="hidden" name="customer_order_id" value="{{ $payment->customer_order_id }}">
                    <select id="customer_order_id_display" class="form-select bg-gray-100 dark:bg-black/20" disabled>
                        <option value="{{ $payment->customer_order_id }}" selected>
                            {{ $payment->customerOrder ? ($payment->customerOrder->order_no . ' (' . $payment->customerOrder->style_no . ') — ' . ($payment->customerOrder->customer?->name ?? 'N/A')) : 'General Payment / No Order' }}
                        </option>
                    </select>
                    <span class="text-xs text-white-dark mt-1">Customer order cannot be changed once payment is created.</span>
                    @error('customer_order_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="receipt_number">Receipt Number</label>
                    <input type="text" name="receipt_number" id="receipt_number"
                        class="form-input bg-gray-100 dark:bg-black/20" disabled
                        value="{{ old('receipt_number', $payment->receipt_number) }}" />
                    @error('receipt_number')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-input" step="0.01" min="0"
                        value="{{ old('amount', $payment->amount) }}" />
                    @error('amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="advance" {{ old('payment_type', $payment->payment_type) == 'advance' ? 'selected' : '' }}>
                            Advance</option>
                        <option value="partial" {{ old('payment_type', $payment->payment_type) == 'partial' ? 'selected' : '' }}>
                            Partial</option>
                        <option value="final" {{ old('payment_type', $payment->payment_type) == 'final' ? 'selected' : '' }}>
                            Final</option>
                    </select>
                    @error('payment_type')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending" {{ old('payment_status', $payment->payment_status) == 'pending' ? 'selected' : '' }}>
                            Pending</option>
                        <option value="completed" {{ old('payment_status', $payment->payment_status) == 'completed' ? 'selected' : '' }}>
                            Completed</option>
                    </select>
                    @error('payment_status')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="office_account_id">Collect To Account</label>
                    <select name="office_account_id" id="office_account_id" class="form-select">
                        <option value="">-- Select Account --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('office_account_id', $payment->office_account_id) == $account->id ? 'selected' : '' }}>
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

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input rows=" 3"
                        placeholder="Additional information...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('admin.payments.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update</button>
            </div>
        </form>
    </div>
@endsection