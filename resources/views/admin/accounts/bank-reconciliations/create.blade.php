@extends('admin.layouts.master')

@section('title', 'Start Bank Reconciliation')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Start Bank Reconciliation</h2>
        <a href="{{ route('admin.bank-reconciliations.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="panel mt-6 max-w-2xl mx-auto">
        <form action="{{ route('admin.bank-reconciliations.store') }}" method="POST">
            @csrf
            
            <div class="space-y-5">
                <div>
                    <label for="account_id">Bank / Office Account <span class="text-danger">*</span></label>
                    <select name="account_id" id="account_id" class="form-select" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_name }} ({{ $account->account_number }})</option>
                        @endforeach
                    </select>
                    @error('account_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="statement_date">Statement Ending Date <span class="text-danger">*</span></label>
                        <input type="date" name="statement_date" id="statement_date" class="form-input" required value="{{ date('Y-m-d') }}" />
                        @error('statement_date') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="statement_balance">Ending Balance (Bank) <span class="text-danger">*</span></label>
                        <input type="number" name="statement_balance" id="statement_balance" step="0.01" class="form-input" required placeholder="0.00" />
                        @error('statement_balance') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-primary/5 p-4 rounded-lg border border-primary/20">
                    <p class="text-[10px] font-bold uppercase text-primary tracking-widest mb-1">Information:</p>
                    <p class="text-xs text-white-dark leading-relaxed">
                        Initializing this reconciliation will compare your **Bank Statement Ending Balance** with the **System Balance** of the selected account. On the next screen, you will match individual transactions to eliminate the difference.
                    </p>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.bank-reconciliations.index') }}" class="btn btn-outline-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary px-8 uppercase font-bold text-xs">Initialize Reconciliation</button>
                </div>
            </div>
        </form>
    </div>
@endsection
