@extends('admin.layouts.master')

@section('title', 'Reconciliation: ' . $reconciliation->account->account_name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
        <div>
            <h2 class="text-xl font-semibold uppercase">Bank Reconciliation Workspace</h2>
            <p class="text-xs text-white-dark mt-1">{{ $reconciliation->account->account_name }} | Statement Date: {{ $reconciliation->statement_date->format('M d, Y') }}</p>
        </div>
        <div class="flex gap-2 text-xs">
            <a href="{{ route('admin.bank-reconciliations.index') }}" class="btn btn-secondary font-bold uppercase">Exit to List</a>
        </div>
    </div>

    @if (session('error'))
        <div class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6" x-data="reconciliationWorkspace()">
        <!-- Summary Sidebar -->
        <div class="space-y-6">
            <div class="panel bg-primary text-white">
                <h5 class="font-black uppercase text-xs tracking-[4px] mb-5 opacity-70">Reconciliation Summary</h5>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-[10px] font-bold uppercase opacity-80">Statement Balance:</span>
                        <span class="font-mono text-lg font-black">{{ number_format($reconciliation->statement_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-[10px] font-bold uppercase opacity-80">Cleared System Balance:</span>
                        <span class="font-mono text-lg font-black" x-text="formatCurrency(systemBalance)"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-[10px] font-bold uppercase {{ $reconciliation->difference == 0 ? 'text-white' : 'text-danger-light' }}">Difference:</span>
                        <span class="font-mono text-3xl font-black" :class="difference === 0 ? 'text-white' : 'text-danger-light'" x-text="formatCurrency(difference)"></span>
                    </div>
                </div>

                <div class="mt-8">
                    <template x-if="difference === 0">
                        <div class="bg-white/10 p-4 rounded-xl text-center">
                            <p class="text-[9px] uppercase font-black tracking-widest mb-3">Reconciliation is balanced!</p>
                            @if($reconciliation->status != 'closed')
                                <form action="{{ route('admin.bank-reconciliations.close', $reconciliation) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn bg-white text-primary w-full shadow-lg font-black uppercase text-xs">Close & Finalize</button>
                                </form>
                            @else
                                <div class="btn bg-white/20 text-white w-full cursor-not-allowed font-black uppercase text-xs">Already Closed</div>
                            @endif
                        </div>
                    </template>
                    <template x-if="difference !== 0">
                        <div class="p-4 border-2 border-white/20 border-dashed rounded-xl text-center">
                            <p class="text-[9px] uppercase font-bold opacity-60">Match items to reach zero difference</p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="panel">
                <h6 class="text-xs font-bold uppercase mb-4 text-white-dark">Matched Transactions</h6>
                <div class="space-y-3">
                    @forelse($reconciliation->items as $recItem)
                        <div class="flex justify-between items-start p-3 bg-success/5 border border-success/10 rounded-lg group">
                            <div>
                                <p class="text-[10px] font-bold uppercase text-success">{{ $recItem->journalEntryItem->journalEntry->reference_number }}</p>
                                <p class="text-[9px] text-white-dark mt-0.5">{{ $recItem->journalEntryItem->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-mono font-bold">{{ number_format($recItem->amount, 2) }}</p>
                                @if($reconciliation->status != 'closed')
                                    <button @click="unmatchItem({{ $recItem->journal_entry_item_id }})" class="text-[9px] text-danger opacity-0 group-hover:opacity-100 hover:underline">Unmatch</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-[10px] text-white-dark py-4 italic">No items matched yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Transaction List -->
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="text-lg font-semibold uppercase">Pending System Transactions</h5>
                    <span class="text-[10px] font-bold text-white-dark uppercase tracking-widest">Unreconciled only</span>
                </div>

                <div class="table-responsive">
                    <table class="table-hover w-full">
                        <thead>
                            <tr class="bg-primary/5 text-[10px] uppercase font-bold tracking-widest">
                                <th class="p-3 text-left">Date</th>
                                <th class="p-3 text-left">Ref / Description</th>
                                <th class="p-3 text-right">Debit</th>
                                <th class="p-3 text-right">Credit</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unreconciledItems as $item)
                                <tr class="border-b transition-all hover:bg-primary/5">
                                    <td class="p-3 text-xs whitespace-nowrap">{{ $item->journalEntry->date->format('M d') }}</td>
                                    <td class="p-3">
                                        <p class="text-xs font-bold leading-none mb-1">{{ $item->journalEntry->reference_number }}</p>
                                        <p class="text-[10px] text-white-dark truncate max-w-[200px]">{{ $item->description }}</p>
                                    </td>
                                    <td class="p-3 text-right text-xs font-mono text-danger">{{ $item->debit > 0 ? number_format($item->debit, 2) : '-' }}</td>
                                    <td class="p-3 text-right text-xs font-mono text-success">{{ $item->credit > 0 ? number_format($item->credit, 2) : '-' }}</td>
                                    <td class="p-3 text-center">
                                        @if($reconciliation->status != 'closed')
                                            <button @click="matchItem({{ $item->id }})" class="btn btn-sm btn-outline-primary whitespace-nowrap text-[10px]">Match</button>
                                        @else
                                            <span class="text-[10px] text-white-dark">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-white-dark italic text-sm">
                                        No pending transactions found for this account.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function reconciliationWorkspace() {
            return {
                systemBalance: {{ $reconciliation->system_balance }},
                statementBalance: {{ $reconciliation->statement_balance }},
                difference: {{ $reconciliation->difference }},
                reconciliationId: {{ $reconciliation->id }},

                matchItem(itemId) {
                    fetch(`{{ route('admin.bank-reconciliations.match', $reconciliation) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ journal_entry_item_id: itemId })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error matching item');
                        }
                    });
                },

                unmatchItem(itemId) {
                    fetch(`{{ route('admin.bank-reconciliations.unmatch', $reconciliation) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ journal_entry_item_id: itemId })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                },

                formatCurrency(val) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(val);
                }
            }
        }
    </script>
@endpush
