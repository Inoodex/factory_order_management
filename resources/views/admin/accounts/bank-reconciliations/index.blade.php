@extends('admin.layouts.master')

@section('title', 'Bank Reconciliations')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bank Reconciliations</h2>
        <a href="{{ route('admin.bank-reconciliations.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Reconciliation
        </a>
    </div>

    <div class="panel mt-6">
        <div class="table-responsive min-h-[220px]">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>Statement Date</th>
                        <th>Account</th>
                        <th class="text-right">Statement Balance</th>
                        <th class="text-right">System Balance</th>
                        <th class="text-right">Difference</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $rec)
                        <tr>
                            <td>{{ $rec->statement_date->format('M d, Y') }}</td>
                            <td class="font-bold text-primary">{{ $rec->account->account_name }}</td>
                            <td class="text-right font-mono">{{ number_format($rec->statement_balance, 2) }}</td>
                            <td class="text-right font-mono">{{ number_format($rec->system_balance, 2) }}</td>
                            <td class="text-right font-mono {{ $rec->difference == 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($rec->difference, 2) }}
                            </td>
                            <td>
                                @if($rec->status == 'closed')
                                    <span class="badge badge-outline-success font-black uppercase text-[10px]">Closed</span>
                                @else
                                    <span class="badge badge-outline-warning font-black uppercase text-[10px]">Open (Draft)</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                    <button type="button" @click="open = !open" class="flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:text-primary hover:bg-gray-100 dark:hover:bg-[#1b2e4b] dark:text-gray-400 focus:outline-none transition" title="Actions">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="5" r="2"/>
                                            <circle cx="12" cy="12" r="2"/>
                                            <circle cx="12" cy="19" r="2"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak 
                                        x-transition:enter="transition ease-out duration-100" 
                                        x-transition:enter-start="transform opacity-0 scale-95" 
                                        x-transition:enter-end="transform opacity-100 scale-100" 
                                        x-transition:leave="transition ease-in duration-75" 
                                        x-transition:leave-start="transform opacity-100 scale-100" 
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-50 mt-1 w-36 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left">
                                        <a href="{{ route('admin.bank-reconciliations.show', $rec) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-[#121e32]">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            {{ $rec->status == 'closed' ? 'View' : 'Continue' }}
                                        </a>
                                        @if($rec->status != 'closed')
                                            <form action="{{ route('admin.bank-reconciliations.destroy', $rec) }}" method="POST" onsubmit="return confirm('Delete this draft?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-danger hover:bg-red-50 dark:hover:bg-[#121e32]">
                                                    <svg class="h-3.5 w-3.5 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-12">
                                No bank reconciliations found. Start by clicking "New Reconciliation".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reconciliations->links() }}
        </div>
    </div>
@endsection
