@extends('admin.layouts.master')

@section('title', 'Factory Order Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Section with Welcome & Quick Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Factory Orders & Production Analytics</h1>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Executive production overview, factory milestones, buyer performance & delivery health
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.customer-orders.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 shadow-sm hover:shadow transition">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>New Customer Order</span>
            </a>
            <a href="{{ route('admin.factory-followups.pipeline') }}" class="btn btn-outline-primary btn-sm flex items-center gap-1.5">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Production Board</span>
            </a>
        </div>
    </div>

    <!-- Overdue Alert Callout (if any) -->
    @if (($stats['overdue_orders'] ?? 0) > 0)
    <div class="relative overflow-hidden rounded-xl border border-danger/30 bg-danger/5 p-4 text-danger dark:bg-danger/10 dark:border-danger/40">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-danger text-white shadow-sm">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-danger dark:text-danger-light">
                        Urgent Attention: {{ $stats['overdue_orders'] }} Order(s) Have Exceeded Their Target ETD!
                    </h4>
                    <p class="text-xs text-danger/80">
                        Shipment dates need immediate review or factory follow-up confirmation to avoid buyer delivery penalties.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.customer-orders.index', ['overdue_only' => 1]) }}" class="btn btn-sm bg-danger text-white hover:bg-danger/90 border-0 shrink-0 self-start sm:self-auto">
                Review Overdue Orders →
            </a>
        </div>
    </div>
    @endif

    <!-- Top KPI Executive Metric Cards with Mini Sparklines -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 1. Total Orders & Volume -->
        <div class="panel relative overflow-hidden p-5 transition hover:shadow-md border-l-4 border-l-primary flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Booked Orders</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_orders'] ?? 0) }}</h3>
                        <span class="text-xs font-bold text-primary">Orders</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary/20">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
            </div>
            <!-- Mini Sparkline Container -->
            <div class="mt-2 h-10 w-full" id="sparklineOrders"></div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-xs dark:border-gray-800">
                <span class="text-gray-500">Total Pieces:</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_order_qty'] ?? 0) }} pcs</span>
            </div>
        </div>

        <!-- 2. Gross Order Book Value -->
        <div class="panel relative overflow-hidden p-5 transition hover:shadow-md border-l-4 border-l-success flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Gross Order Value</span>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="text-lg font-bold text-success font-mono">{{ currency_symbol() }}</span>
                        <h3 class="text-2xl font-black text-success">{{ number_format($stats['total_order_value'] ?? 0, 2) }}</h3>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success/20">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
            <!-- Mini Sparkline Container -->
            <div class="mt-2 h-10 w-full" id="sparklineValues"></div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-xs dark:border-gray-800">
                <span class="text-gray-500">Avg. Unit Price:</span>
                <span class="font-bold text-success font-mono">
                    {{ currency_symbol() }} {{ ($stats['total_order_qty'] ?? 0) > 0 ? number_format(($stats['total_order_value'] ?? 0) / $stats['total_order_qty'], 2) : '0.00' }}
                </span>
            </div>
        </div>

        <!-- 3. Delivery Performance -->
        <div class="panel relative overflow-hidden p-5 transition hover:shadow-md border-l-4 {{ ($stats['overdue_orders'] ?? 0) > 0 ? 'border-l-danger' : 'border-l-info' }} flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Delivery Status</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-2xl font-black {{ ($stats['overdue_orders'] ?? 0) > 0 ? 'text-danger' : 'text-info' }}">
                            {{ number_format($stats['overdue_orders'] ?? 0) }}
                        </h3>
                        <span class="text-xs font-semibold text-gray-500">Overdue / {{ number_format($stats['on_time_orders'] ?? 0) }} On Track</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ ($stats['overdue_orders'] ?? 0) > 0 ? 'bg-danger/10 text-danger' : 'bg-info/10 text-info' }}">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <!-- Progress Bar Indicator -->
            <div class="my-3">
                <div class="flex justify-between text-[11px] font-semibold mb-1">
                    <span class="text-gray-500">On-Time Compliance</span>
                    <span class="{{ ($stats['on_time_rate'] ?? 100) >= 80 ? 'text-success' : 'text-danger' }}">{{ $stats['on_time_rate'] ?? 100 }}%</span>
                </div>
                <div class="h-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($stats['on_time_rate'] ?? 100) >= 80 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $stats['on_time_rate'] ?? 100 }}%"></div>
                </div>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-xs dark:border-gray-800">
                <span class="text-gray-500">Active ETDs:</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['on_time_orders'] ?? 0 }} shipments on schedule</span>
            </div>
        </div>

        <!-- 4. Global Network -->
        <div class="panel relative overflow-hidden p-5 transition hover:shadow-md border-l-4 border-l-warning flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Network Partners</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                        <span class="text-xs font-semibold text-gray-400">Buyers</span>
                        <span class="text-gray-300">|</span>
                        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300">{{ number_format($stats['total_suppliers'] ?? 0) }}</h3>
                        <span class="text-xs font-semibold text-gray-400">Factories</span>
                    </div>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-warning/10 text-warning dark:bg-warning/20">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="my-3 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 rounded bg-info/10 px-2 py-0.5 text-[11px] font-bold text-info">
                    {{ $stats['total_customers'] }} Buyer Brands
                </span>
                <span class="inline-flex items-center gap-1 rounded bg-warning/10 px-2 py-0.5 text-[11px] font-bold text-warning">
                    {{ $stats['total_suppliers'] }} Mills
                </span>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-xs dark:border-gray-800">
                <span class="text-gray-500">Active Supply Lines:</span>
                <span class="font-bold text-warning">{{ number_format($stats['total_suppliers'] ?? 0) }} Manufacturing Units</span>
            </div>
        </div>
    </div>

    <!-- PRIMARY ECHARTS ROW: Trends (2/3) + Pipeline Donut (1/3) -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Chart 1: Monthly Order Quantity & Value Trends (Apache ECharts Dual Axis) -->
        <div class="panel lg:col-span-2">
            <div class="border-b border-gray-100 pb-4 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Order Volume & Value Trends</h3>
                <p class="text-xs text-gray-500">Monthly booked piece volume vs gross booking value</p>
            </div>
            <div class="pt-4">
                <div id="echartsOrderTrends" class="w-full" style="height: 350px;"></div>
            </div>
        </div>

        <!-- Chart 2: Production Pipeline Stage Breakdown (ECharts Modern Ring Donut) -->
        <div class="panel lg:col-span-1 flex flex-col justify-between">
            <div class="border-b border-gray-100 pb-4 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Production Pipeline</h3>
                        <p class="text-xs text-gray-500">Live milestone stages across all orders</p>
                    </div>
                    <a href="{{ route('admin.factory-followups.index') }}" class="text-xs font-semibold text-primary hover:underline">
                        View All →
                    </a>
                </div>
            </div>
            <div class="pt-2 flex-1 flex flex-col items-center justify-center">
                <div id="echartsPipelineDonut" class="w-full" style="height: 250px;"></div>
            </div>
            <div class="mt-2 border-t border-gray-100 pt-3 text-xs dark:border-gray-800 space-y-1.5">
                @php
                    $colors = ['bg-[#4361ee]', 'bg-[#06b6d4]', 'bg-[#f59e0b]', 'bg-[#8b5cf6]', 'bg-[#10b981]'];
                @endphp
                @foreach ($pipelineStages as $stage => $count)
                    <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full {{ $colors[$loop->index % count($colors)] }}"></span>
                            {{ $stage }}
                        </span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $count }} order(s)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECONDARY ECHARTS ROW: Top Buyers + Factory Allocation + Delivery Health Gauge -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Chart 3: Top Buyers / Brands by Volume (ECharts Horizontal Gradient Bars) -->
        <div class="panel lg:col-span-1">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Top Buyers by Volume</h3>
                    <p class="text-xs text-gray-500">Leading apparel retail brands</p>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="text-xs font-semibold text-primary hover:underline">
                    Buyers →
                </a>
            </div>
            <div class="pt-4">
                <div id="echartsTopBuyers" class="w-full" style="height: 280px;"></div>
            </div>
        </div>

        <!-- Chart 4: Factory Allocation & Capacity (ECharts Rose / Bar) -->
        <div class="panel lg:col-span-1">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Factory Workload</h3>
                    <p class="text-xs text-gray-500">Partner mill capacity share</p>
                </div>
                <a href="{{ route('admin.suppliers.index') }}" class="text-xs font-semibold text-primary hover:underline">
                    Factories →
                </a>
            </div>
            <div class="pt-4">
                <div id="echartsFactoryAllocation" class="w-full" style="height: 280px;"></div>
            </div>
        </div>

        <!-- Chart 5: Delivery Compliance Gauge (ECharts Speedometer) -->
        <div class="panel lg:col-span-1 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Delivery Compliance</h3>
                    <p class="text-xs text-gray-500">On-time shipment compliance gauge</p>
                </div>
                <span class="badge {{ ($stats['on_time_rate'] ?? 100) >= 80 ? 'badge-outline-success' : 'badge-outline-danger' }} text-[11px]">
                    {{ ($stats['on_time_rate'] ?? 100) >= 80 ? 'Healthy' : 'Needs Action' }}
                </span>
            </div>
            <div class="pt-2 flex-1 flex items-center justify-center">
                <div id="echartsDeliveryGauge" class="w-full" style="height: 220px;"></div>
            </div>
            <div class="border-t border-gray-100 pt-3 text-center text-xs dark:border-gray-800">
                <span class="text-gray-500">Target Standard: </span>
                <span class="font-bold text-success">≥ 90% On-Time</span>
                <span class="text-gray-300 mx-1">|</span>
                <span class="text-gray-500">Current: </span>
                <span class="font-bold {{ ($stats['on_time_rate'] ?? 100) >= 80 ? 'text-success' : 'text-danger' }}">
                    {{ $stats['on_time_rate'] ?? 100 }}%
                </span>
            </div>
        </div>
    </div>

    <!-- RECENT CUSTOMER ORDERS TABLE -->
    <div class="panel">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4 dark:border-gray-800 mb-4 gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Recent Customer Orders</h3>
                <p class="text-xs text-gray-500">Real-time order progression, factory allocation and delivery milestones</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-sm btn-outline-primary">
                    View All Orders ({{ $stats['total_orders'] }}) →
                </a>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[220px]">
            <table class="table-hover w-full table-auto text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b dark:border-gray-800">
                        <th class="py-3 px-3">Order Details</th>
                        <th class="py-3 px-3">Buyer / Brand</th>
                        <th class="py-3 px-3">Factory Partner</th>
                        <th class="py-3 px-3 text-right">Quantity</th>
                        <th class="py-3 px-3 text-right">Order Value</th>
                        <th class="py-3 px-3 text-center">ETD Deadline</th>
                        <th class="py-3 px-3 text-center">Production Stage</th>
                        <th class="py-3 px-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($recentOrders as $order)
                        @php
                            $f = $order->factoryOrder?->followup;
                            $currentStage = 'PPS / Sampling';
                            $stageBadge = 'badge-outline-primary';
                            if ($f) {
                                if ($f->cutting_status === 'Completed') {
                                    $currentStage = 'Finishing & Ready';
                                    $stageBadge = 'badge-outline-success';
                                } elseif ($f->cutting_status === 'In Progress') {
                                    $currentStage = 'Cutting & Sewing';
                                    $stageBadge = 'badge-outline-secondary';
                                } elseif ($f->dyeing_status === 'In Progress' || $f->dyeing_status === 'Completed') {
                                    $currentStage = 'Dyeing / Wash';
                                    $stageBadge = 'badge-outline-warning';
                                } elseif ($f->knitting_status === 'In Progress' || $f->knitting_status === 'Completed') {
                                    $currentStage = 'Knitting / Fabric';
                                    $stageBadge = 'badge-outline-info';
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-[#0e1726]/50 transition">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary font-bold text-xs">
                                        {{ strtoupper(substr($order->style_no, 0, 3)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.customer-orders.show', ['customerOrder' => $order->id]) }}" class="font-bold text-primary hover:underline">
                                            {{ $order->order_no }}
                                        </a>
                                        <div class="text-xs text-gray-500">
                                            Style: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $order->style_no }}</span>
                                            @if($order->style_name)
                                                ({{ \Illuminate\Support\Str::limit($order->style_name, 20) }})
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $order->customer?->name ?? '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $order->customer?->brand ?? '' }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-medium text-gray-700 dark:text-gray-300">{{ $order->supplier?->name ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-3 text-right font-bold text-gray-900 dark:text-white">
                                {{ number_format($order->color_qty) }} <span class="text-xs font-normal text-gray-400">pcs</span>
                            </td>
                            <td class="py-3 px-3 text-right font-bold text-success">
                                {{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                @if ($order->is_overdue)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-danger/10 px-2.5 py-0.5 text-xs font-bold text-danger">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $order->etd_date?->format('d M Y') }} (Overdue)
                                    </span>
                                @else
                                    <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                                        {{ $order->etd_date?->format('d M Y') ?? '—' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="badge {{ $stageBadge }} text-[11px] font-semibold">
                                    {{ $currentStage }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
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
                                        <a href="{{ route('admin.customer-orders.show', ['customerOrder' => $order->id]) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>View Order</span>
                                        </a>
                                        <a href="{{ route('admin.customer-orders.edit', $order) }}"
                                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                            <svg class="h-3.5 w-3.5 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>Edit Order</span>
                                        </a>
                                        @if ($order->factoryOrder?->followup)
                                            <a href="{{ route('admin.factory-followups.edit', ['factoryFollowup' => $order->factoryOrder->followup->id]) }}"
                                                class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary dark:text-gray-200 dark:hover:bg-[#121e32] dark:hover:text-primary rounded transition">
                                                <svg class="h-3.5 w-3.5 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                <span>Follow-up</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No customer orders recorded yet.
                                <a href="{{ route('admin.customer-orders.create') }}" class="text-primary font-bold hover:underline ml-1">
                                    Create the first order →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FINANCIAL & HR SUMMARY (Role Protected) -->
    @if (auth()->check() && (auth()->user()->hasRole('accountant') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
    <div class="panel border-t-2 border-t-primary" x-data="{ expanded: true }">
        <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
            <div class="flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <h3 class="font-bold text-sm uppercase text-gray-700 dark:text-gray-300">Financial Ledger & Payroll Summary</h3>
            </div>
            <svg class="h-4 w-4 transition-transform text-gray-400" :class="expanded ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none">
                <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div x-show="expanded" x-collapse class="pt-4 mt-3 border-t border-gray-100 dark:border-gray-800">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-[#1b2e4b]/40">
                    <span class="text-xs font-semibold text-gray-500">Completed Collections</span>
                    <p class="text-xl font-bold text-success mt-1">{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                    <span class="text-[11px] text-gray-400">Total payments collected</span>
                </div>

                <div class="rounded-lg bg-gray-50 p-3 dark:bg-[#1b2e4b]/40">
                    <span class="text-xs font-semibold text-gray-500">Payroll Obligation</span>
                    <p class="text-xl font-bold text-primary mt-1">{{ number_format($stats['total_salary_expense'] ?? 0, 2) }}</p>
                    <span class="text-[11px] text-gray-400">Total gross payroll</span>
                </div>

                <div class="rounded-lg bg-gray-50 p-3 dark:bg-[#1b2e4b]/40">
                    <span class="text-xs font-semibold text-gray-500">Disbursed Salaries</span>
                    <p class="text-xl font-bold text-success mt-1">{{ number_format($stats['total_salary_paid'] ?? 0, 2) }}</p>
                    <span class="text-[11px] text-gray-400">Paid out to personnel</span>
                </div>

                <div class="rounded-lg bg-gray-50 p-3 dark:bg-[#1b2e4b]/40">
                    <span class="text-xs font-semibold text-gray-500">Pending Salaries</span>
                    <p class="text-xl font-bold text-warning mt-1">{{ number_format($stats['total_salary_pending'] ?? 0, 2) }}</p>
                    <span class="text-[11px] text-gray-400">Awaiting disbursement</span>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/echarts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.body.classList.contains('dark');

    const textColor = isDark ? '#94a3b8' : '#64748b';
    const titleColor = isDark ? '#f1f5f9' : '#0f172a';
    const splitLineColor = isDark ? 'rgba(51, 65, 85, 0.4)' : '#f1f5f9';
    const tooltipBg = isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)';
    const tooltipBorder = isDark ? '#334155' : '#e2e8f0';
    const tooltipTextColor = isDark ? '#f8fafc' : '#0f172a';

    const allChartInstances = [];

    // -------------------------------------------------------------
    // Sparkline 1: Total Orders
    // -------------------------------------------------------------
    const sparklineOrdersEl = document.getElementById('sparklineOrders');
    if (sparklineOrdersEl) {
        const c = echarts.init(sparklineOrdersEl);
        c.setOption({
            grid: { left: 0, right: 0, top: 2, bottom: 2 },
            xAxis: { type: 'category', show: false, data: @json($monthlyLabels) },
            yAxis: { type: 'value', show: false },
            series: [{
                data: @json($monthlyQuantities),
                type: 'line',
                smooth: 0.4,
                showSymbol: false,
                lineStyle: { width: 2.5, color: '#4361ee' },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        { offset: 0, color: 'rgba(67, 97, 238, 0.35)' },
                        { offset: 1, color: 'rgba(67, 97, 238, 0.0)' }
                    ])
                }
            }]
        });
        allChartInstances.push(c);
    }

    // -------------------------------------------------------------
    // Sparkline 2: Order Values
    // -------------------------------------------------------------
    const sparklineValuesEl = document.getElementById('sparklineValues');
    if (sparklineValuesEl) {
        const c = echarts.init(sparklineValuesEl);
        c.setOption({
            grid: { left: 0, right: 0, top: 2, bottom: 2 },
            xAxis: { type: 'category', show: false, data: @json($monthlyLabels) },
            yAxis: { type: 'value', show: false },
            series: [{
                data: @json($monthlyValues),
                type: 'line',
                smooth: 0.4,
                showSymbol: false,
                lineStyle: { width: 2.5, color: '#00ab55' },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        { offset: 0, color: 'rgba(0, 171, 85, 0.35)' },
                        { offset: 1, color: 'rgba(0, 171, 85, 0.0)' }
                    ])
                }
            }]
        });
        allChartInstances.push(c);
    }

    // -------------------------------------------------------------
    // Chart 1: Monthly Order Volume & Value Trends (Mixed Chart)
    // -------------------------------------------------------------
    const orderTrendsEl = document.getElementById('echartsOrderTrends');
    if (orderTrendsEl) {
        const chartOrderTrends = echarts.init(orderTrendsEl);
        const monthlyLabels = @json($monthlyLabels);
        const monthlyQuantities = @json($monthlyQuantities);
        const monthlyValues = @json($monthlyValues);

        chartOrderTrends.setOption({
            legend: {
                data: ['Order Volume (Pcs)', 'Order Value'],
                top: 8,
                right: 10,
                icon: 'circle',
                itemGap: 18,
                textStyle: { color: isDark ? '#94a3b8' : '#64748b' }
            },
            grid: { left: '3%', right: '4%', bottom: '3%', top: '18%', containLabel: true },
            tooltip: {
                trigger: 'axis',
                backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                borderColor: isDark ? '#334155' : '#e2e8f0',
                borderWidth: 1,
                padding: 12,
                textStyle: { color: isDark ? '#f8fafc' : '#0f172a' },
                formatter: function (params) {
                    if (!params || !params.length) return '';
                    let html = `<div class="font-bold mb-1 pb-1 border-b ${isDark ? 'border-gray-700' : 'border-gray-200'}">${params[0].name}</div>`;
                    params.forEach(function (item) {
                        let val = item.seriesName.includes('Value')
                            ? Number(item.value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})
                            : Number(item.value).toLocaleString();
                        html += `
                            <div class="flex items-center justify-between gap-4 text-xs py-0.5">
                                <span style="color:${item.color}">${item.marker} ${item.seriesName}:</span>
                                <strong style="color:${tooltipTextColor}">${val}</strong>
                            </div>
                        `;
                    });
                    return html;
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: true,
                data: monthlyLabels,
                axisLine: { lineStyle: { color: isDark ? '#334155' : '#cbd5e1' } },
                axisLabel: { color: isDark ? '#94a3b8' : '#64748b' }
            },
            yAxis: [
                {
                    type: 'value',
                    name: 'Volume (Pcs)',
                    min: 0,
                    axisLabel: {
                        formatter: function (v) { return Number(v).toLocaleString(); },
                        color: textColor,
                        fontSize: 11
                    },
                    splitLine: { lineStyle: { color: splitLineColor, type: 'dashed' } }
                },
                {
                    type: 'value',
                    name: 'Order Value',
                    nameLocation: 'end',
                    nameGap: 14,
                    nameTextStyle: {
                        color: '#00ab55',
                        fontWeight: 600,
                        fontSize: 11,
                        align: 'right',
                        padding: [0, 0, 0, 0]
                    },
                    min: 0,
                    axisLabel: {
                        formatter: function (v) { return Number(v).toLocaleString(); },
                        color: textColor,
                        fontSize: 11
                    },
                    splitLine: { show: false }
                }
            ],
            series: [
                {
                    name: 'Order Volume (Pcs)',
                    type: 'bar',
                    barWidth: '28%',
                    itemStyle: {
                        borderRadius: [5, 5, 0, 0],
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#4361ee' },
                            { offset: 1, color: '#3048cb' }
                        ])
                    },
                    data: monthlyQuantities
                },
                {
                    name: 'Order Value',
                    type: 'line',
                    yAxisIndex: 1,
                    smooth: 0.3,
                    symbol: 'circle',
                    symbolSize: 6,
                    itemStyle: {
                        color: '#00ab55',
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    },
                    lineStyle: {
                        width: 3,
                        color: '#00ab55',
                        shadowColor: 'rgba(0, 171, 85, 0.25)',
                        shadowBlur: 6,
                        shadowOffsetY: 3
                    },
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: 'rgba(0, 171, 85, 0.12)' },
                            { offset: 1, color: 'rgba(0, 171, 85, 0.0)' }
                        ])
                    },
                    data: monthlyValues
                }
            ]
        });
        allChartInstances.push(chartOrderTrends);
    }

    // -------------------------------------------------------------
    // Chart 2: Production Pipeline Stage Breakdown (Ring Donut)
    // -------------------------------------------------------------
    const pipelineDonutEl = document.getElementById('echartsPipelineDonut');
    if (pipelineDonutEl) {
        const chartPipelineDonut = echarts.init(pipelineDonutEl);
        const pipelineData = @json($pipelineStages);
        const pieData = Object.keys(pipelineData).map(key => ({
            name: key,
            value: pipelineData[key]
        }));
        const totalOrders = pieData.reduce((acc, curr) => acc + curr.value, 0);

        chartPipelineDonut.setOption({
            tooltip: {
                trigger: 'item',
                backgroundColor: tooltipBg,
                borderColor: tooltipBorder,
                textStyle: { color: tooltipTextColor },
                formatter: '{b}: <strong>{c} orders</strong> ({d}%)'
            },
            color: ['#4361ee', '#06b6d4', '#f59e0b', '#8b5cf6', '#10b981'],
            series: [{
                name: 'Production Stage',
                type: 'pie',
                radius: ['52%', '78%'],
                center: ['50%', '50%'],
                avoidLabelOverlap: false,
                padAngle: 4,
                itemStyle: {
                    borderRadius: 8,
                    borderColor: isDark ? '#1b2e4b' : '#ffffff',
                    borderWidth: 2
                },
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: 16,
                        fontWeight: 'bold',
                        color: titleColor,
                        formatter: '{b}\n{c} orders'
                    },
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.3)'
                    }
                },
                data: pieData
            }],
            graphic: [{
                type: 'text',
                left: 'center',
                top: '42%',
                style: {
                    text: totalOrders + '\nOrders',
                    textAlign: 'center',
                    fill: titleColor,
                    fontSize: 15,
                    fontWeight: 'bold'
                }
            }]
        });
        allChartInstances.push(chartPipelineDonut);
    }

    // -------------------------------------------------------------
    // Chart 3: Top Buyers (Horizontal Pill Gradient Bars)
    // -------------------------------------------------------------
    const topBuyersEl = document.getElementById('echartsTopBuyers');
    if (topBuyersEl) {
        const chartTopBuyers = echarts.init(topBuyersEl);
        const topBuyersData = @json($topBuyers);
        const buyerNames = topBuyersData.map(b => b.name).reverse();
        const buyerQuantities = topBuyersData.map(b => b.qty).reverse();

        chartTopBuyers.setOption({
            tooltip: {
                trigger: 'axis',
                backgroundColor: tooltipBg,
                borderColor: tooltipBorder,
                textStyle: { color: tooltipTextColor },
                axisPointer: { type: 'shadow' },
                formatter: function(params) {
                    return `<strong>${params[0].name}</strong><br/>Booked: ${Number(params[0].value).toLocaleString()} pcs`;
                }
            },
            grid: {
                left: '2%',
                right: '12%',
                bottom: '3%',
                top: '5%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                axisLabel: {
                    color: textColor,
                    formatter: function(v) { return (v / 1000) + 'k'; }
                },
                splitLine: { lineStyle: { color: splitLineColor, type: 'dashed' } }
            },
            yAxis: {
                type: 'category',
                data: buyerNames,
                axisLine: { lineStyle: { color: splitLineColor } },
                axisLabel: {
                    color: textColor,
                    fontSize: 11,
                    formatter: function(value) {
                        return value.length > 18 ? value.substring(0, 18) + '...' : value;
                    }
                }
            },
            series: [{
                name: 'Pieces Booked',
                type: 'bar',
                barWidth: '45%',
                data: buyerQuantities,
                itemStyle: {
                    borderRadius: [0, 8, 8, 0],
                    color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [
                        { offset: 0, color: '#4361ee' },
                        { offset: 1, color: '#06b6d4' }
                    ]),
                    shadowColor: 'rgba(67, 97, 238, 0.25)',
                    shadowBlur: 5
                },
                label: {
                    show: true,
                    position: 'right',
                    color: textColor,
                    fontSize: 11,
                    fontWeight: 600,
                    formatter: function(params) {
                        return Number(params.value).toLocaleString();
                    }
                }
            }]
        });
        allChartInstances.push(chartTopBuyers);
    }

    // -------------------------------------------------------------
    // Chart 4: Factory Workload Allocation (Rounded Bar / Rose)
    // -------------------------------------------------------------
    const factoryAllocationEl = document.getElementById('echartsFactoryAllocation');
    if (factoryAllocationEl) {
        const chartFactory = echarts.init(factoryAllocationEl);
        const factoryData = @json($factoryAllocation);
        const factoryNames = factoryData.map(f => f.name);
        const factoryQuantities = factoryData.map(f => f.qty);

        chartFactory.setOption({
            tooltip: {
                trigger: 'axis',
                backgroundColor: tooltipBg,
                borderColor: tooltipBorder,
                textStyle: { color: tooltipTextColor },
                axisPointer: { type: 'shadow' },
                formatter: function(params) {
                    return `<strong>${params[0].name}</strong><br/>Capacity: ${Number(params[0].value).toLocaleString()} pcs`;
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '15%',
                top: '8%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: factoryNames,
                axisLabel: {
                    color: textColor,
                    fontSize: 10.5,
                    rotate: 15,
                    formatter: function(v) {
                        return v.length > 15 ? v.substring(0, 15) + '...' : v;
                    }
                },
                axisLine: { lineStyle: { color: splitLineColor } }
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    color: textColor,
                    formatter: function(v) { return (v / 1000) + 'k'; }
                },
                splitLine: { lineStyle: { color: splitLineColor, type: 'dashed' } }
            },
            series: [{
                name: 'Allocated Pieces',
                type: 'bar',
                barWidth: '40%',
                data: factoryQuantities,
                itemStyle: {
                    borderRadius: [6, 6, 0, 0],
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        { offset: 0, color: '#00ab55' },
                        { offset: 1, color: '#057a3e' }
                    ]),
                    shadowColor: 'rgba(0, 171, 85, 0.25)',
                    shadowBlur: 5
                },
                label: {
                    show: true,
                    position: 'top',
                    color: textColor,
                    fontSize: 10.5,
                    fontWeight: 600,
                    formatter: function(params) {
                        return Number(params.value).toLocaleString();
                    }
                }
            }]
        });
        allChartInstances.push(chartFactory);
    }

    // -------------------------------------------------------------
    // Chart 5: Delivery Compliance Speedometer Gauge
    // -------------------------------------------------------------
    const deliveryGaugeEl = document.getElementById('echartsDeliveryGauge');
    if (deliveryGaugeEl) {
        const chartGauge = echarts.init(deliveryGaugeEl);
        const onTimeRate = {{ $stats['on_time_rate'] ?? 100 }};

        chartGauge.setOption({
            series: [{
                type: 'gauge',
                startAngle: 180,
                endAngle: 0,
                center: ['50%', '75%'],
                radius: '100%',
                min: 0,
                max: 100,
                splitNumber: 5,
                axisLine: {
                    lineStyle: {
                        width: 14,
                        color: [
                            [0.5, '#e7515a'],
                            [0.8, '#e2a03f'],
                            [1, '#00ab55']
                        ]
                    }
                },
                pointer: {
                    icon: 'path://M12.8,0.7l12,40.1H0.7L12.8,0.7z',
                    length: '12%',
                    width: 10,
                    offsetCenter: [0, '-60%'],
                    itemStyle: { color: 'auto' }
                },
                axisTick: {
                    length: 8,
                    lineStyle: { color: 'auto', width: 1.5 }
                },
                splitLine: {
                    length: 12,
                    lineStyle: { color: 'auto', width: 2.5 }
                },
                axisLabel: {
                    color: textColor,
                    fontSize: 11,
                    distance: -35,
                    formatter: function (value) {
                        if (value === 0) return '0%';
                        if (value === 50) return '50%';
                        if (value === 100) return '100%';
                        return '';
                    }
                },
                title: {
                    offsetCenter: [0, '-20%'],
                    fontSize: 13,
                    color: textColor
                },
                detail: {
                    fontSize: 24,
                    offsetCenter: [0, '0%'],
                    valueAnimation: true,
                    formatter: function (value) {
                        return Math.round(value) + '%';
                    },
                    color: onTimeRate >= 80 ? '#00ab55' : '#e7515a',
                    fontWeight: 'bold'
                },
                data: [{
                    value: onTimeRate,
                    name: 'On-Time Score'
                }]
            }]
        });
        allChartInstances.push(chartGauge);
    }

    // -------------------------------------------------------------
    // Responsive Resizing for All Charts
    // -------------------------------------------------------------
    window.addEventListener('resize', function () {
        allChartInstances.forEach(chart => {
            if (chart) chart.resize();
        });
    });
});
</script>
@endpush
