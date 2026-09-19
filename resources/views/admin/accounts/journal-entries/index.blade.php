@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Journal Ledger</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.journal-entries.report', array_merge(request()->all(), ['output' => 'preview'])) }}"
                target="_blank" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Preview
            </a>
            <a href="{{ route('admin.journal-entries.report', array_merge(request()->all(), ['output' => 'download'])) }}"
                class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download
            </a>
            <a href="{{ route('admin.journal-entries.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create Voucher
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mt-4 mb-5">
        <form method="GET" action="{{ route('admin.journal-entries.index') }}"
            class="flex flex-col md:flex-row flex-wrap gap-4 mb-4">
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="form-input flex-1 min-w-[150px]" title="Start Date">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input flex-1 min-w-[150px]"
                title="End Date">

            <input type="text" name="reference_number" value="{{ request('reference_number') }}"
                placeholder="Reference No..." class="form-input flex-1 min-w-[150px]">

            <select name="period_id" class="form-select flex-1 min-w-[150px]">
                <option value="">All Periods</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}</option>
                @endforeach
            </select>

            <select name="status" class="form-select flex-1 min-w-[150px]">
                <option value="">All Status</option>
                <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>Reversed</option>
            </select>

            <div class="flex gap-2 whitespace-nowrap md:ml-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel mt-4">
        <div class="table-responsive min-h-[220px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Reference</th>
                        <th>Note / Description</th>
                        <th>Period</th>
                        <th>Voucher Amount</th>
                        <th>Posted By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td class="whitespace-nowrap text-xs font-semibold">{{ $entry->date->format('M d, Y') }}</td>
                            <td class="font-bold underline text-primary">
                                <a
                                    href="{{ route('admin.journal-entries.show', $entry) }}">{{ $entry->reference_number }}</a>
                            </td>
                            <td class="font-xs max-w-xs truncate">
                                <span class="text-xs text-gray-700 dark:text-gray-300">{{ $entry->note ?: 'General Entry' }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge badge-outline-secondary text-[10px] uppercase">{{ $entry->period->name }}</span>
                            </td>
                            <td class="font-bold">{{ number_format($entry->total_amount, 2) }}</td>
                            <td class="text-xs">{{ $entry->creator->name }}</td>
                            <td class="text-center">
                                <div class="relative inline-block text-left" x-data="tableDropdown" @click.outside="close">
                                    <button type="button" @click="toggle" x-ref="btn" class="flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:text-primary hover:bg-gray-100 dark:hover:bg-[#1b2e4b] dark:text-gray-400 focus:outline-none transition" title="Actions">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="5" r="2"/>
                                            <circle cx="12" cy="12" r="2"/>
                                            <circle cx="12" cy="19" r="2"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak x-ref="menu"
                                        x-transition:enter="transition ease-out duration-100" 
                                        x-transition:enter-start="transform opacity-0 scale-95" 
                                        x-transition:enter-end="transform opacity-100 scale-100" 
                                        x-transition:leave="transition ease-in duration-75" 
                                        x-transition:leave-start="transform opacity-100 scale-100" 
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="table-dropdown-menu z-50 w-36 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left">
                                        <a href="{{ route('admin.journal-entries.show', $entry) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-[#121e32]">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View Voucher
                                        </a>
                                        <form action="{{ route('admin.journal-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-danger hover:bg-red-50 dark:hover:bg-[#121e32]">
                                                <svg class="h-3.5 w-3.5 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" opacity="0.2">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2">No journal vouchers recorded yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $entries->links() }}
        </div>
    </div>
@endsection
