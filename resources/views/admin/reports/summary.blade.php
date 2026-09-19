@extends('admin.layouts.master')

@section('title', 'Financial Summary')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Financial Summary</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.download-pdf', ['month' => $month, 'year' => $year, 'account_id' => $accountId, 'transaction_type' => $transactionType, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.reports.summary') }}" method="GET" class="mb-5 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="start_date" class="mb-0">From:</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="form-input w-40">
            </div>
            <div class="flex items-center gap-2">
                <label for="end_date" class="mb-0">To:</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="form-input w-40">
            </div>
            <div class="flex items-center gap-2">
                <label for="month" class="mb-0 text-gray-400">Or Month:</label>
                <select name="month" id="month" class="form-select w-32">
                    <option value="">Month</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}"
                            {{ $month == sprintf('%02d', $m) && !request('start_date') ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <select name="year" id="year" class="form-select w-32">
                    @foreach (range(date('Y') - 2, date('Y') + 1) as $y)
                        <option value="{{ $y }}" {{ $year == $y && !request('start_date') ? 'selected' : '' }}>
                            {{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label for="account_id" class="mb-0">Account:</label>
                <select name="account_id" id="account_id" class="form-select w-40">
                    <option value="">Accounts</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}" {{ $accountId == $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label for="transaction_type" class="mb-0">Type:</label>
                <select name="transaction_type" id="transaction_type" class="form-select w-40">
                    <option value="">Types</option>
                    <option value="income" {{ $transactionType == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ $transactionType == 'expense' ? 'selected' : '' }}>Expense</option>
                    <option value="transfer" {{ $transactionType == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('admin.reports.summary') }}" class="btn btn-outline-danger">Reset</a>
        </form>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="panel bg-gradient-to-r from-info/20 to-info/10 border-info/20">
                <div class="flex justify-between">
                    <div class="text-lg font-semibold uppercase">Total Income</div>
                </div>
                <div class="mt-5 flex items-center text-info">
                    <div class="text-3xl font-bold">{{ number_format($summary['total_income'], 2) }}</div>
                </div>
                <div class="mt-5 text-sm">
                    {{ $summary['income_count'] }} payments for selected period
                </div>
            </div>

            <div class="panel bg-gradient-to-r from-danger/20 to-danger/10 border-danger/20">
                <div class="flex justify-between">
                    <div class="text-lg font-semibold uppercase">Total Expenses</div>
                </div>
                <div class="mt-5 flex items-center text-danger">
                    <div class="text-3xl font-bold">{{ number_format($summary['total_expense'], 2) }}</div>
                </div>
                <div class="mt-5 text-sm">
                    {{ $summary['expense_count'] }} transactions for selected period
                </div>
            </div>

            <div class="panel bg-gradient-to-r from-success/20 to-success/10 border-success/20">
                <div class="flex justify-between">
                    <div class="text-lg font-semibold uppercase">Total Transfers</div>
                </div>
                <div class="mt-5 flex items-center text-success">
                    <div class="text-3xl font-bold">{{ number_format($summary['total_transfer'], 2) }}</div>
                </div>
                <div class="mt-5 text-sm">
                    {{ $summary['transfer_count'] }} movements for selected period
                </div>
            </div>

            <div class="panel bg-gradient-to-r from-warning/20 to-warning/10 border-warning/20">
                <div class="flex justify-between">
                    <div class="text-lg font-semibold uppercase">Net Profit</div>
                </div>
                <div class="mt-5 flex items-center text-warning">
                    <div class="text-3xl font-bold">
                        {{ number_format($summary['total_income'] - $summary['total_expense'], 2) }}
                    </div>
                </div>
                <div class="mt-5 text-sm">
                    Net Income (Income - Expenses)
                </div>

            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="panel">
                <h5 class="mb-5 text-lg font-semibold uppercase">Expenses by Category</h5>
                <div class="datatable">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 text-left">Category</th>
                                <th class="py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($summary['by_category'] as $category => $amount)
                                <tr class="border-b">
                                    <td class="py-2 font-medium uppercase">{{ $category }}</td>
                                    <td class="py-2 text-right font-bold text-primary">{{ number_format($amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-center">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <h5 class="mb-5 text-lg font-semibold uppercase">Budgets Status</h5>
                <div class="datatable">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 text-left">Category</th>
                                <th class="py-2 text-right">Budget</th>
                                <th class="py-2 text-right">Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($budgets as $budget)
                                @php
                                    $actual = $summary['by_category'][strtolower($budget->category)] ?? 0;
                                    // Fallback for case sensitivity
                                    if ($actual == 0) {
                                        $actual = $summary['by_category'][$budget->category] ?? 0;
                                    }
                                    $isOver = $actual > $budget->amount;
                                @endphp
                                <tr class="border-b {{ $isOver ? 'bg-danger-light dark:bg-danger/10' : '' }}">
                                    <td class="py-2 font-medium uppercase">{{ $budget->category }}</td>
                                    <td class="py-2 text-right">{{ number_format($budget->amount, 2) }}</td>
                                    <td class="py-2 text-right font-bold {{ $isOver ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($actual, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center">No budgets set for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <div class="panel">
                <h5 class="mb-5 text-lg font-semibold uppercase">Recent Income (Payments)</h5>
                <div class="datatable">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 text-left">Date</th>
                                <th class="py-2 text-left">Customer / Order</th>
                                <th class="py-2 text-left">Receipt</th>
                                <th class="py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments->take(10) as $payment)
                                <tr class="border-b">
                                    <td class="py-2">{{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : '-' }}</td>
                                    <td class="py-2">
                                        @php
                                            $order = $payment->customerOrder ?? ($payment->invoice?->customerOrder);
                                        @endphp
                                        <div class="font-bold text-dark dark:text-white-light">
                                            {{ $order?->customer?->name ?? 'Direct Payment' }}
                                        </div>
                                        @if($order)
                                            <span class="text-[10px] text-white-dark block">Order: {{ $order->order_no }} (Style: {{ $order->style_no }})</span>
                                        @endif
                                    </td>
                                    <td class="py-2 font-mono text-xs">{{ $payment->receipt_number }}</td>
                                    <td class="py-2 text-right font-bold text-success font-mono">
                                        {{ number_format($payment->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center">No income recorded for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
