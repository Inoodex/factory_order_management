@extends('admin.layouts.master')

@section('title', 'Expenses')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Expenses</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.expenses.report', array_merge(request()->query(), ['output' => 'preview'])) }}" class="btn btn-outline-primary gap-2" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Preview PDF
            </a>
            <a href="{{ route('admin.expenses.report', array_merge(request()->query(), ['output' => 'download'])) }}" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Record Expense
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.expenses.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search description, category..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="category" class="form-select w-full md:w-40 pr-10">
                        <option value="">Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="form-input w-full md:w-40" placeholder="Start Date">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="form-input w-full md:w-40" placeholder="End Date">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-danger">Reset</a>

                    {{-- <a href="{{ route('admin.expenses.report', request()->query()) }}" class="btn btn-success gap-2"
                        target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="h-5 w-5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Generate Report
                    </a> --}}

                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Recorded By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                <td class="font-semibold">{{ $expense->description }}</td>
                                <td><span
                                        class="badge badge-outline-info">{{ $expense->chartOfAccount->name ?? 'General' }}</span>
                                </td>
                                <td class="font-bold text-danger">{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->payment_method ?: '-' }}</td>
                                <td>
                                    <div class="text-xs">
                                        {{ $expense->creator->name ?? 'System' }}
                                    </div>
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
                                            <a href="{{ route('admin.expenses.download-pdf', $expense->id) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                                </svg>
                                                <span>Download PDF</span>
                                            </a>
                                            <a href="{{ route('admin.expenses.edit', $expense->id) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                <span>Edit Expense</span>
                                            </a>
                                            <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                            <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this expense?');" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-medium text-danger hover:bg-danger/10 dark:hover:bg-danger/20 rounded transition text-left">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No expenses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
@endsection
