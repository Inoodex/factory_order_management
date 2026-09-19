@extends('admin.layouts.master')

@section('title', 'Order Invoices')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Billing & Invoices</h2>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Invoice
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.invoices.index') }}" method="GET" class="flex flex-col gap-3 w-full">
                <div style="display: flex; align-items: center; gap: 8px; width: 100%; flex-wrap: wrap;">
                    <div class="relative" style="flex: 2; min-width: 200px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search invoice #, customer / buyer, order #, style #..." class="form-input ltr:pr-11 rtl:pl-11" style="width: 100%;" />
                        <button type="submit"
                            class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                                <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    <select name="status" class="form-select" style="flex: 1; min-width: 150px;">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; width: 100%; flex-wrap: wrap;">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        placeholder="From Date" class="form-input" style="flex: 1; min-width: 150px;" />
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        placeholder="To Date" class="form-input" style="flex: 1; min-width: 150px;" />

                    <div style="flex: 2; min-width: 100px;"></div>

                    <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Filter</button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-danger" style="white-space: nowrap;">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive min-h-[220px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Issue Date</th>
                        <th>Invoice Number</th>
                        <th>Buyer / Customer</th>
                        <th>Order / Style</th>
                        <th>Total Amount</th>
                        <!-- <th>Billing Status</th> -->
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="whitespace-nowrap text-xs text-white-dark">{{ $invoice->date->format('M d, Y') }}</td>
                            <td class="font-bold text-primary">
                                <a href="{{ route('admin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="font-semibold text-sm">
                                {{ $invoice->customerOrder?->customer?->name ?? 'Direct Invoice' }}
                                @if($invoice->customerOrder?->customer?->company_name)
                                    <span class="block text-[10px] text-white-dark">{{ $invoice->customerOrder->customer->company_name }}</span>
                                @endif
                            </td>
                            <td class="text-xs">
                                @if($invoice->customerOrder)
                                    <a href="{{ route('admin.customer-orders.show', $invoice->customerOrder) }}" class="font-bold text-primary hover:underline">
                                        {{ $invoice->customerOrder->order_no }}
                                    </a>
                                    <span class="block text-[10px] text-white-dark">Style: {{ $invoice->customerOrder->style_no }}</span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="font-black text-dark dark:text-white-light font-mono">
                                {{ number_format($invoice->total_amount, 2) }}</td>
                            <!-- <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge badge-outline-success uppercase text-[10px] font-black">Fully Paid</span>
                                @elseif($invoice->status == 'partial')
                                    <span class="badge badge-outline-warning uppercase text-[10px] font-black">Partial Paid</span>
                                @else
                                    <span class="badge badge-outline-danger uppercase text-[10px] font-black">Unpaid</span>
                                @endif
                            </td> -->
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
                                        <a href="{{ route('admin.invoices.show', $invoice) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>View Invoice</span>
                                        </a>
                                        <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            <span>Download PDF</span>
                                        </a>
                                        <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit Invoice</span>
                                        </a>
                                        <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this invoice?');" class="w-full">
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
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-primary/5 rounded-full mb-3">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1" opacity="0.3">
                                            <path
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold tracking-widest uppercase">No Invoices Found</p>
                                    <p class="text-xs text-white-dark mt-1">Start by creating an invoice for a customer order.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection