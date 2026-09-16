@extends('admin.layouts.master')

@section('title', 'Chart of Accounts')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Chart of Accounts</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
        <!-- Add Account Form -->
        <div class="panel">
            <h5 class="mb-5 text-lg font-semibold">Define New Account Head</h5>
            <form action="{{ route('admin.chart-of-accounts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="type">Account Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                        <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                        <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                        <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="parent_id">Parent Head (Optional)</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="">No Parent (Top Level)</option>
                        @foreach ($parentAccounts as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }} ({{ ucfirst($parent->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="code">Account Code</label>
                    <input type="text" name="code" id="code" class="form-input" placeholder="e.g. 1000-01" value="{{ old('code') }}" required>
                </div>
                <div class="mb-4">
                    <label for="name">Account Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. Cash in Hand" value="{{ old('name') }}" required>
                </div>
                <button type="submit" class="btn btn-primary w-full text-base">Register Account</button>
            </form>
        </div>

        <!-- Accounts Hierarchy -->
        <div class="panel lg:col-span-2">
            <h5 class="mb-5 text-lg font-semibold text-primary">Ledger Structure</h5>
            <div class="table-responsive min-h-[220px]">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Head Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            @include('admin.accounts.chart-of-accounts.partials.row', ['account' => $account, 'depth' => 0])
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-400 py-10">Chart of Accounts is empty. Start adding heads.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
