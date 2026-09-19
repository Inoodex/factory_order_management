@extends('admin.layouts.master')

@section('title', 'Production Pipeline Board')

@push('styles')
    <style>
        .kanban-col {
            min-height: calc(100vh - 340px);
        }
        .kanban-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08);
        }
        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
        }
        .step-dot.active {
            background-color: #00ab55;
            box-shadow: 0 0 0 2px rgba(0, 171, 85, 0.2);
        }
        .step-dot.current {
            background-color: #4361ee;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.3);
        }
        .step-dot.pending {
            background-color: #e5e7eb;
        }
        .dark .step-dot.pending {
            background-color: #374151;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold uppercase tracking-tight text-gray-800 dark:text-white">Production Pipeline Board</h2>
                <span class="badge bg-primary/10 text-primary font-bold text-xs">{{ $totalOrders }} Active Orders</span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Real-time Kanban staging of garment manufacturing, sampling, and floor milestones</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.factory-followups.index') }}" class="btn btn-outline-secondary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
                Tabular View
            </a>
            <a href="{{ route('admin.customer-orders.create') }}" class="btn btn-primary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Order
            </a>
        </div>
    </div>

    <!-- Top Executive KPI Summary Cards -->
    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 1. Active Orders -->
        <div class="panel bg-gradient-to-br from-primary/5 via-white to-white dark:from-primary/10 dark:via-[#0e1726] dark:to-[#0e1726] p-4 border-l-4 border-primary shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total in Pipeline</span>
                    <h3 class="text-2xl font-extrabold text-primary mt-1">{{ number_format($totalOrders) }} <span class="text-xs font-normal text-gray-400">Orders</span></h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-2">
                <span>Active Factories:</span>
                <span class="font-bold text-gray-700 dark:text-gray-300">{{ $suppliers->count() }} Units</span>
            </div>
        </div>

        <!-- 2. Active Volume -->
        <div class="panel bg-gradient-to-br from-info/5 via-white to-white dark:from-info/10 dark:via-[#0e1726] dark:to-[#0e1726] p-4 border-l-4 border-info shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Active Production Pcs</span>
                    <h3 class="text-2xl font-extrabold text-info mt-1">{{ number_format($totalPieces) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-info/10 text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-2">
                <span>Order Value:</span>
                <span class="font-bold text-success">${{ number_format($totalValue, 2) }}</span>
            </div>
        </div>

        <!-- 3. Due This Week -->
        <div class="panel bg-gradient-to-br from-warning/5 via-white to-white dark:from-warning/10 dark:via-[#0e1726] dark:to-[#0e1726] p-4 border-l-4 border-warning shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Due This Week</span>
                    <h3 class="text-2xl font-extrabold text-warning mt-1">{{ number_format($dueThisWeekCount) }} <span class="text-xs font-normal text-gray-400">Orders</span></h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-warning/10 text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-2">
                <span>Next 7 Days:</span>
                <span class="font-bold text-gray-700 dark:text-gray-300">Upcoming ETD</span>
            </div>
        </div>

        <!-- 4. Critical / Overdue -->
        <div class="panel bg-gradient-to-br from-danger/5 via-white to-white dark:from-danger/10 dark:via-[#0e1726] dark:to-[#0e1726] p-4 border-l-4 border-danger shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Overdue / Delayed</span>
                    <h3 class="text-2xl font-extrabold text-danger mt-1">{{ number_format($overdueCount) }} <span class="text-xs font-normal text-gray-400">Critical</span></h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-danger/10 text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-2">
                <span>Attention:</span>
                <span class="font-bold text-danger">Action Required</span>
            </div>
        </div>
    </div>

    <!-- Interactive Filter & Search Bar -->
    <div class="panel mt-5 p-4">
        <form action="{{ route('admin.factory-followups.pipeline') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-1 flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="relative min-w-[220px] flex-1">
                    <input type="text" id="pipelineSearch" name="search" value="{{ request('search') }}"
                        placeholder="Search PO#, Style#, Brand, Customer..."
                        class="form-input ltr:pl-9 rtl:pr-9 text-xs" />
                    <svg class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 text-gray-400 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>

                <!-- Customer Filter -->
                <div class="min-w-[180px]">
                    <select name="customer_id" class="form-select text-xs" onchange="this.form.submit()">
                        <option value="">All Buyers / Customers</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} {{ $c->brand ? "({$c->brand})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier Filter -->
                <div class="min-w-[180px]">
                    <select name="supplier_id" class="form-select text-xs" onchange="this.form.submit()">
                        <option value="">All Factory Units</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                @if(request()->hasAny(['search', 'customer_id', 'supplier_id']))
                    <a href="{{ route('admin.factory-followups.pipeline') }}" class="btn btn-outline-danger btn-sm">
                        Clear Filters
                    </a>
                @endif
            </div>

            <div class="text-xs text-gray-500">
                <span>Displaying <strong>{{ $allFollowups->count() }}</strong> Orders across <strong>5</strong> Stages</span>
            </div>
        </form>
    </div>

    <!-- 5-Column Visual Kanban Board -->
    <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-5">
        
        <!-- Column 1: Sampling (PPS) -->
        <div class="panel bg-gray-50/80 dark:bg-[#1b2e4b]/70 flex flex-col kanban-col p-3 border-t-4 border-t-primary rounded-xl" data-column="sampling">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-primary ring-4 ring-primary/20"></span>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-white">1. PPS Sampling</h3>
                </div>
                <span class="badge bg-primary/20 text-primary font-bold text-xs col-count">{{ $columns['sampling']->count() }}</span>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[72vh] pr-1">
                @forelse ($columns['sampling'] as $fu)
                    @include('admin.factory-followups.partials.pipeline-card', ['fu' => $fu, 'stage' => 'sampling', 'accentColor' => 'primary'])
                @empty
                    <div class="empty-state text-center py-8 text-xs text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        No orders in sampling
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 2: Knitting -->
        <div class="panel bg-gray-50/80 dark:bg-[#1b2e4b]/70 flex flex-col kanban-col p-3 border-t-4 border-t-info rounded-xl" data-column="knitting">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-info ring-4 ring-info/20"></span>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-white">2. Knitting Floor</h3>
                </div>
                <span class="badge bg-info/20 text-info font-bold text-xs col-count">{{ $columns['knitting']->count() }}</span>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[72vh] pr-1">
                @forelse ($columns['knitting'] as $fu)
                    @include('admin.factory-followups.partials.pipeline-card', ['fu' => $fu, 'stage' => 'knitting', 'accentColor' => 'info'])
                @empty
                    <div class="empty-state text-center py-8 text-xs text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        No active knitting
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 3: Dyeing -->
        <div class="panel bg-gray-50/80 dark:bg-[#1b2e4b]/70 flex flex-col kanban-col p-3 border-t-4 border-t-warning rounded-xl" data-column="dyeing">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-warning ring-4 ring-warning/20"></span>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-white">3. Dyeing & Wash</h3>
                </div>
                <span class="badge bg-warning/20 text-warning font-bold text-xs col-count">{{ $columns['dyeing']->count() }}</span>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[72vh] pr-1">
                @forelse ($columns['dyeing'] as $fu)
                    @include('admin.factory-followups.partials.pipeline-card', ['fu' => $fu, 'stage' => 'dyeing', 'accentColor' => 'warning'])
                @empty
                    <div class="empty-state text-center py-8 text-xs text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4 5 5 0 016-4.57V6a3 3 0 116 0v6.43A5 5 0 0121 17a4 4 0 01-4 4H7z" />
                        </svg>
                        No active dyeing
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 4: Cutting & Finishing -->
        <div class="panel bg-gray-50/80 dark:bg-[#1b2e4b]/70 flex flex-col kanban-col p-3 border-t-4 border-t-secondary rounded-xl" data-column="cutting">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-secondary ring-4 ring-secondary/20"></span>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-white">4. Cutting & Sew</h3>
                </div>
                <span class="badge bg-secondary/20 text-secondary font-bold text-xs col-count">{{ $columns['cutting']->count() }}</span>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[72vh] pr-1">
                @forelse ($columns['cutting'] as $fu)
                    @include('admin.factory-followups.partials.pipeline-card', ['fu' => $fu, 'stage' => 'cutting', 'accentColor' => 'secondary'])
                @empty
                    <div class="empty-state text-center py-8 text-xs text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L12 12m0 0l-2.879-2.879a3 3 0 00-4.242 4.242L12 12z" />
                        </svg>
                        No active cutting
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 5: Ready / Completed -->
        <div class="panel bg-gray-50/80 dark:bg-[#1b2e4b]/70 flex flex-col kanban-col p-3 border-t-4 border-t-success rounded-xl" data-column="completed">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-success ring-4 ring-success/20"></span>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-white">5. Ready / Shipped</h3>
                </div>
                <span class="badge bg-success/20 text-success font-bold text-xs col-count">{{ $columns['completed']->count() }}</span>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[72vh] pr-1">
                @forelse ($columns['completed'] as $fu)
                    @include('admin.factory-followups.partials.pipeline-card', ['fu' => $fu, 'stage' => 'completed', 'accentColor' => 'success'])
                @empty
                    <div class="empty-state text-center py-8 text-xs text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600 mb-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        No completed orders
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // Real-time client-side search filtering across cards
            document.getElementById('pipelineSearch')?.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const cards = document.querySelectorAll('.kanban-card');

                cards.forEach(card => {
                    const searchable = (card.getAttribute('data-search') || '').toLowerCase();
                    if (!query || searchable.includes(query)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Update column counts dynamically
                document.querySelectorAll('.kanban-col').forEach(col => {
                    const visibleCards = col.querySelectorAll('.kanban-card:not([style*="display: none"])');
                    const badge = col.querySelector('.col-count');
                    const emptyState = col.querySelector('.empty-state');
                    if (badge) {
                        badge.textContent = visibleCards.length;
                    }
                    if (emptyState) {
                        emptyState.style.display = (visibleCards.length === 0 && query) ? 'block' : (visibleCards.length === 0 ? 'block' : 'none');
                    }
                });
            });
        </script>
    @endpush
@endsection
