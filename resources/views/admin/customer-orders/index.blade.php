@extends('admin.layouts.master')

@section('title', 'Customer Orders')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Customer Orders</h2>
            <p class="text-sm text-gray-500">Track apparel styles, factory allocations, and delivery schedules</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.order-import-export.export') }}" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('admin.order-import-export.import-view') }}" class="btn btn-outline-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Import Orders
            </a>
            <a href="{{ route('admin.customer-orders.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Order
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <!-- Filter Form -->
        <form action="{{ route('admin.customer-orders.index') }}" method="GET" class="mb-5 space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Order #, style #, style name, color..." class="form-input" />
                </div>

                <!-- Customer Filter -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Customer / Brand</label>
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} {{ $c->brand ? "({$c->brand})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier Filter -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Supplier / Factory</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ETD From -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">ETD Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" />
                </div>

                <!-- ETD To -->
                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">ETD Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" />
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="overdue_only" name="overdue_only" value="1" {{ request('overdue_only') ? 'checked' : '' }}
                        class="form-checkbox text-danger rounded" />
                    <label for="overdue_only" class="text-sm font-semibold text-danger cursor-pointer select-none">
                        Show Overdue ETD Orders Only
                    </label>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter Orders</button>
                    <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto min-h-[260px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Style Image</th>
                        <th>Order No</th>
                        <th>Style No & Name</th>
                        <th>Customer / Brand</th>
                        <th>Supplier / Factory</th>
                        <th>Color & Qty</th>
                        <th>Price</th>
                        <th>Total Value</th>
                        <th>ETD Date</th>
                        <th>Follow-up Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        @php
                            $followup = $order->factoryOrder?->followup;
                        @endphp
                        <tr class="{{ $order->is_overdue ? 'bg-danger/5' : '' }}">
                            <td class="w-16">
                                @if($order->style_image)
                                    <img src="{{ $order->image_url }}" alt="Style"
                                        class="h-12 w-12 rounded object-cover shadow-sm border border-gray-200" />
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded bg-gray-100 dark:bg-gray-800 text-gray-400 text-xs">
                                        No img
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.customer-orders.show', $order) }}"
                                    class="font-bold text-primary hover:underline">
                                    {{ $order->order_no }}
                                </a>
                                @if($order->is_overdue)
                                    <span class="block text-[10px] uppercase font-bold text-danger">Overdue</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold">{{ $order->style_no }}</div>
                                <div class="text-xs text-gray-500">{{ $order->style_name ?? '—' }}</div>
                                @if($order->composition)
                                    <div class="text-[11px] text-gray-400">{{ $order->composition }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold">{{ $order->customer?->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">
                                    @php
                                        $displayBrand = $order->brand ?: $order->customer?->brand;
                                    @endphp
                                    {{ $displayBrand ? "Brand: {$displayBrand}" : '' }}
                                    {{ $order->customer?->session ? "• {$order->customer->session}" : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold">{{ $order->supplier?->name ?? '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->supplier?->location ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $order->color_name ?? 'Standard' }}</div>
                                <span class="badge bg-dark/10 text-dark font-bold">{{ number_format($order->color_qty) }} pcs</span>
                            </td>
                            <td>{{ number_format($order->price, 2) }}</td>
                            <td class="font-bold text-success">{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @if($order->etd_date)
                                    <span class="text-xs font-semibold {{ $order->is_overdue ? 'text-danger font-bold' : '' }}">
                                        {{ $order->etd_date->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($followup)
                                    <div class="flex flex-col gap-1 text-[11px]">
                                        <div class="flex items-center gap-1">
                                            <span class="text-gray-400">PPS:</span>
                                            <span class="font-semibold {{ $followup->pps_comments_status === 'Approved' ? 'text-success' : ($followup->pps_comments_status === 'Rejected' ? 'text-danger' : 'text-warning') }}">
                                                {{ $followup->pps_comments_status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-gray-400">Cut:</span>
                                            <span class="font-semibold {{ $followup->cutting_status === 'Completed' ? 'text-success' : ($followup->cutting_status === 'In Progress' ? 'text-primary' : 'text-gray-500') }}">
                                                {{ $followup->cutting_status }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
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
                                        <a href="{{ route('admin.customer-orders.show', $order) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>View Details</span>
                                        </a>
                                        <a href="{{ route('admin.customer-orders.edit', $order) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit Order</span>
                                        </a>
                                        @if($order->factoryOrder)
                                            <a href="{{ route('admin.factory-orders.edit', $order->factoryOrder) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                                <span>Pricing & AETD</span>
                                            </a>
                                        @endif
                                        @if($followup)
                                            <a href="{{ route('admin.factory-followups.edit', $followup) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                <span>Production Follow-up</span>
                                            </a>
                                        @endif
                                        <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                        <form action="{{ route('admin.customer-orders.destroy', $order) }}" method="POST"
                                            onsubmit="return confirm('Delete order #{{ $order->order_no }}? This will remove paired factory order and followup records.');" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-1.5 text-xs font-medium text-danger hover:bg-danger/10 dark:hover:bg-danger/20 rounded transition text-left">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                <span>Delete Order</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-8 text-gray-500">
                                No customer orders found. Click "New Order" or "Import Orders" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
