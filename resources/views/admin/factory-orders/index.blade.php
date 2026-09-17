@extends('admin.layouts.master')

@section('title', 'Factory Orders & Commercials')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Factory Orders & Commercials</h2>
            <p class="text-sm text-gray-500">Track factory manufacturing costs, FOB pricing, and Actual ETD schedules</p>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center">
            <form action="{{ route('admin.factory-orders.index') }}" method="GET"
                class="flex flex-1 flex-col gap-4 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search order #, style #..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="overdue_only" name="overdue_only" value="1" {{ request('overdue_only') ? 'checked' : '' }}
                        class="form-checkbox text-danger rounded" />
                    <label for="overdue_only" class="text-sm font-semibold text-danger cursor-pointer select-none">
                        Overdue AETD Only
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.factory-orders.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto min-h-[220px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Style No</th>
                        <th>Customer / Buyer</th>
                        <th>Supplier / Factory</th>
                        <th>Customer Price</th>
                        <th>ETD Price</th>
                        <th>Sub Price</th>
                        <th>FOB Price</th>
                        <th>Actual ETD (AETD)</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($factoryOrders as $fo)
                        @php
                            $order = $fo->customerOrder;
                        @endphp
                        <tr class="{{ $fo->is_overdue ? 'bg-danger/5' : '' }}">
                            <td>
                                @if($order)
                                    <a href="{{ route('admin.customer-orders.show', $order) }}"
                                        class="font-bold text-primary hover:underline">
                                        {{ $order->order_no }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-semibold">{{ $order?->style_no ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="font-medium">{{ $order?->customer?->name ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="font-medium">{{ $order?->supplier?->name ?? '—' }}</span>
                            </td>
                            <td>
                                {{ number_format($order?->price ?? 0, 2) }}
                            </td>
                            <td>
                                <strong class="text-primary">{{ number_format($fo->etd_price ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <strong class="text-secondary">{{ number_format($fo->sub_price ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($fo->fob_price ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                @if($fo->aetd_date)
                                    <span class="text-xs font-semibold {{ $fo->is_overdue ? 'text-danger font-bold' : '' }}">
                                        {{ $fo->aetd_date->format('d M Y') }}
                                    </span>
                                    @if($fo->is_overdue)
                                        <span class="badge bg-danger/10 text-danger text-[10px] ml-1">Overdue</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">Pending</span>
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
                                        class="table-dropdown-menu z-50 w-44 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left">
                                        <a href="{{ route('admin.factory-orders.edit', $fo) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit Pricing & AETD</span>
                                        </a>
                                        @if($order)
                                            <a href="{{ route('admin.customer-orders.show', $order) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>View Order Details</span>
                                            </a>
                                        @endif
                                        @if($fo->followup)
                                            <a href="{{ route('admin.factory-followups.edit', $fo->followup) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                <span>Follow-up Tracking</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-6 text-gray-500">No factory orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $factoryOrders->links() }}
        </div>
    </div>
@endsection
