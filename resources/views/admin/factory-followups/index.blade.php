@extends('admin.layouts.master')

@section('title', 'Production Follow-up')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Production & Sampling Follow-up</h2>
            <p class="text-sm text-gray-500">Track PPS, SHS, Knitting, Dyeing, and Cutting production milestones</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.factory-followups.pipeline') }}" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Visual Pipeline Board
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <!-- Filters -->
        <form action="{{ route('admin.factory-followups.index') }}" method="GET" class="mb-5 space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search order #, style #..." class="form-input" />
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Knitting Status</label>
                    <select name="knitting_status" class="form-select">
                        <option value="">All Knitting</option>
                        @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                            <option value="{{ $status }}" {{ request('knitting_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Dyeing Status</label>
                    <select name="dyeing_status" class="form-select">
                        <option value="">All Dyeing</option>
                        @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                            <option value="{{ $status }}" {{ request('dyeing_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Cutting Status</label>
                    <select name="cutting_status" class="form-select">
                        <option value="">All Cutting</option>
                        @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                            <option value="{{ $status }}" {{ request('cutting_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.factory-followups.index') }}" class="btn btn-outline-danger btn-sm">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto min-h-[220px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Style No</th>
                        <th>Buyer / Factory</th>
                        <th>PPS Sample</th>
                        <th>SHS Sample</th>
                        <th>Knitting</th>
                        <th>Dyeing</th>
                        <th>Cutting</th>
                        <th>FOB / Sub Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($followups as $fu)
                        @php
                            $order = $fu->factoryOrder?->customerOrder;
                        @endphp
                        <tr>
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
                                <div class="text-xs font-medium">{{ $order?->customer?->name ?? '—' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $order?->supplier?->name ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $fu->pps_comments_status === 'Approved' ? 'bg-success' : ($fu->pps_comments_status === 'Rejected' ? 'bg-danger' : 'bg-warning') }} text-white text-[11px]">
                                    {{ $fu->pps_comments_status }}
                                </span>
                                @if($fu->pps_date)
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $fu->pps_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $fu->shs_comments_status === 'Approved' ? 'bg-success' : ($fu->shs_comments_status === 'Rejected' ? 'bg-danger' : 'bg-info') }} text-white text-[11px]">
                                    {{ $fu->shs_comments_status }}
                                </span>
                                @if($fu->shs_sending_date)
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $fu->shs_sending_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $fu->knitting_status === 'Completed' ? 'bg-success' : ($fu->knitting_status === 'In Progress' ? 'bg-primary' : ($fu->knitting_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[11px]">
                                    {{ $fu->knitting_status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $fu->dyeing_status === 'Completed' ? 'bg-success' : ($fu->dyeing_status === 'In Progress' ? 'bg-primary' : ($fu->dyeing_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[11px]">
                                    {{ $fu->dyeing_status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $fu->cutting_status === 'Completed' ? 'bg-success' : ($fu->cutting_status === 'In Progress' ? 'bg-primary' : ($fu->cutting_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[11px]">
                                    {{ $fu->cutting_status }}
                                </span>
                            </td>
                            <td>
                                <div class="text-xs">
                                    <span>FOB: <strong>{{ number_format($fu->fob_price ?? 0, 2) }}</strong></span>
                                </div>
                                <div class="text-[11px] text-gray-400">
                                    <span>Sub: <strong>{{ number_format($fu->sub_price ?? 0, 2) }}</strong></span>
                                </div>
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
                                        class="absolute right-0 z-50 mt-1 w-44 origin-top-right rounded-lg bg-white p-1 shadow-lg ring-1 ring-black/5 dark:bg-[#1b2e4b] dark:ring-gray-700 text-left">
                                        <a href="{{ route('admin.factory-followups.edit', $fu) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Update Follow-up</span>
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
                                        @if($fu->factoryOrder)
                                            <a href="{{ route('admin.factory-orders.edit', $fu->factoryOrder) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                                <span>Factory Pricing</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-6 text-gray-500">No factory follow-up records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $followups->links() }}
        </div>
    </div>
@endsection
