@extends('admin.layouts.master')

@section('title', 'Suppliers / Factories')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Suppliers & Factories</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Supplier
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.suppliers.index') }}" method="GET"
                class="flex flex-1 flex-col gap-4 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, location, contact..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto min-h-[220px]">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supplier / Factory Name</th>
                        <th>Location</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Allocated Orders</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                            <td class="font-semibold text-primary">{{ $supplier->name }}</td>
                            <td>
                                @if($supplier->location)
                                    <span class="badge bg-secondary/10 text-secondary">{{ $supplier->location }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td>{{ $supplier->contact_person ?? '—' }}</td>
                            <td>{{ $supplier->phone ?? '—' }}</td>
                            <td>{{ $supplier->email ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.customer-orders.index', ['supplier_id' => $supplier->id]) }}" class="badge bg-primary text-white hover:opacity-80">
                                    {{ $supplier->customer_orders_count }} Orders
                                </a>
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
                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit Supplier</span>
                                        </a>
                                        <a href="{{ route('admin.customer-orders.index', ['supplier_id' => $supplier->id]) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            <span>View Orders</span>
                                        </a>
                                        <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this supplier?');" class="w-full">
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
                            <td colspan="8" class="text-center py-6 text-gray-500">No suppliers found. Click "Add Supplier" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection
