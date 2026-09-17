@extends('admin.layouts.master')

@section('title', 'Accounting Periods')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Accounting Periods</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
        <!-- Add Period Form -->
        <div class="panel">
            <h5 class="mb-5 text-lg font-semibold">Setup New Period</h5>
            <form action="{{ route('admin.accounting-periods.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name">Period Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. FY 2026-27"
                        value="{{ old('name') }}" required>
                </div>
                <div class="mb-4">
                    <label for="type">Period Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="fiscal_year" {{ old('type') == 'fiscal_year' ? 'selected' : '' }}>Fiscal Year
                        </option>
                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    </select>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-input"
                            value="{{ old('start_date') }}" required>
                    </div>
                    <div>
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-input"
                            value="{{ old('end_date') }}" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="remarks">Remarks (Optional)</label>
                    <textarea name="remarks" id="remarks" rows="3" class="form-textarea" placeholder="Notes about this period...">{{ old('remarks') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Create Period</button>
            </form>
        </div>

        <!-- Periods List -->
        <div class="panel lg:col-span-2">
            <h5 class="mb-5 text-lg font-semibold">Accounting Period Log</h5>
            <div class="table-responsive min-h-[220px]">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Range</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periods as $period)
                            <tr>
                                <td class="font-semibold">{{ $period->name }}</td>
                                <td class="text-xs">
                                    {{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge badge-outline-primary uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $period->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($period->status == 'open')
                                        <span class="badge badge-outline-success">Open</span>
                                    @else
                                        <span class="badge badge-outline-danger">Closed</span>
                                    @endif
                                </td>
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
                                            class="table-dropdown-menu z-50 w-40 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left">
                                            
                                            <!-- Toggle Status Form -->
                                            <form action="{{ route('admin.accounting-periods.update', $period) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $period->status == 'open' ? 'closed' : 'open' }}">
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-medium {{ $period->status == 'open' ? 'text-danger hover:bg-red-50 dark:hover:bg-[#121e32]' : 'text-success hover:bg-emerald-50 dark:hover:bg-[#121e32]' }}">
                                                    @if($period->status == 'open')
                                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                                        Close Period
                                                    @else
                                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                                        Open Period
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Edit Period -->
                                            <a href="{{ route('admin.accounting-periods.edit', $period->id) }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-[#121e32]">
                                                <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit Period
                                            </a>

                                            <!-- Delete Period -->
                                            <form action="{{ route('admin.accounting-periods.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Delete this accounting period?');">
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
                                <td colspan="5" class="text-center text-gray-400 py-8">No accounting periods defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
    </div>
@endsection
