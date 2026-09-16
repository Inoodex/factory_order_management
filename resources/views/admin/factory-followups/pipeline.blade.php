@extends('admin.layouts.master')

@section('title', 'Production Pipeline Board')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Production Pipeline Board</h2>
            <p class="text-sm text-gray-500">Visual staging of all active apparel manufacturing follow-ups</p>
        </div>
        <a href="{{ route('admin.factory-followups.index') }}" class="btn btn-outline-secondary">
            Tabular View
        </a>
    </div>

    <!-- 5-Column Kanban Board -->
    <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-5">
        <!-- Col 1: PPS Sampling -->
        <div class="panel bg-gray-50 dark:bg-[#1b2e4b] flex flex-col h-full">
            <div class="flex items-center justify-between border-b pb-2 mb-3">
                <h3 class="font-bold text-xs uppercase text-primary">1. PPS Sampling</h3>
                <span class="badge bg-primary/20 text-primary text-xs">{{ $followups->count() }}</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[70vh]">
                @foreach ($followups as $fu)
                    @php $order = $fu->factoryOrder?->customerOrder; @endphp
                    @if($order)
                        <div class="rounded-lg bg-white dark:bg-[#0e1726] p-3 shadow-sm border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.customer-orders.show', $order) }}" class="font-bold text-xs text-primary hover:underline">
                                    {{ $order->order_no }}
                                </a>
                                <span class="badge {{ $fu->pps_comments_status === 'Approved' ? 'bg-success' : ($fu->pps_comments_status === 'Rejected' ? 'bg-danger' : 'bg-warning') }} text-white text-[10px]">
                                    {{ $fu->pps_comments_status }}
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold mt-1">{{ $order->style_no }}</div>
                            <div class="text-[10px] text-gray-500">{{ $order->customer?->name ?? '—' }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 flex justify-between">
                                <span>Target: {{ $fu->pps_date?->format('d M') ?? 'N/A' }}</span>
                                <a href="{{ route('admin.factory-followups.edit', $fu) }}" class="text-primary hover:underline">Edit</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Col 2: SHS Sampling -->
        <div class="panel bg-gray-50 dark:bg-[#1b2e4b] flex flex-col h-full">
            <div class="flex items-center justify-between border-b pb-2 mb-3">
                <h3 class="font-bold text-xs uppercase text-primary">2. SHS Sampling</h3>
                <span class="badge bg-info/20 text-info text-xs">{{ $followups->where('shs_comments_status', 'Approved')->count() }} Approved</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[70vh]">
                @foreach ($followups as $fu)
                    @php $order = $fu->factoryOrder?->customerOrder; @endphp
                    @if($order)
                        <div class="rounded-lg bg-white dark:bg-[#0e1726] p-3 shadow-sm border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-primary">{{ $order->order_no }}</span>
                                <span class="badge {{ $fu->shs_comments_status === 'Approved' ? 'bg-success' : ($fu->shs_comments_status === 'Rejected' ? 'bg-danger' : 'bg-info') }} text-white text-[10px]">
                                    {{ $fu->shs_comments_status }}
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold mt-1">{{ $order->style_no }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 flex justify-between">
                                <span>Sent: {{ $fu->shs_sending_date?->format('d M') ?? 'N/A' }}</span>
                                <a href="{{ route('admin.factory-followups.edit', $fu) }}" class="text-primary hover:underline">Edit</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Col 3: Knitting -->
        <div class="panel bg-gray-50 dark:bg-[#1b2e4b] flex flex-col h-full">
            <div class="flex items-center justify-between border-b pb-2 mb-3">
                <h3 class="font-bold text-xs uppercase text-primary">3. Knitting</h3>
                <span class="badge bg-secondary/20 text-secondary text-xs">{{ $followups->where('knitting_status', 'Completed')->count() }} Done</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[70vh]">
                @foreach ($followups as $fu)
                    @php $order = $fu->factoryOrder?->customerOrder; @endphp
                    @if($order)
                        <div class="rounded-lg bg-white dark:bg-[#0e1726] p-3 shadow-sm border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-primary">{{ $order->order_no }}</span>
                                <span class="badge {{ $fu->knitting_status === 'Completed' ? 'bg-success' : ($fu->knitting_status === 'In Progress' ? 'bg-primary' : ($fu->knitting_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[10px]">
                                    {{ $fu->knitting_status }}
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold mt-1">{{ $order->style_no }}</div>
                            <div class="text-[10px] text-gray-500">{{ $order->supplier?->name ?? '—' }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Col 4: Dyeing -->
        <div class="panel bg-gray-50 dark:bg-[#1b2e4b] flex flex-col h-full">
            <div class="flex items-center justify-between border-b pb-2 mb-3">
                <h3 class="font-bold text-xs uppercase text-primary">4. Dyeing</h3>
                <span class="badge bg-warning/20 text-warning text-xs">{{ $followups->where('dyeing_status', 'Completed')->count() }} Done</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[70vh]">
                @foreach ($followups as $fu)
                    @php $order = $fu->factoryOrder?->customerOrder; @endphp
                    @if($order)
                        <div class="rounded-lg bg-white dark:bg-[#0e1726] p-3 shadow-sm border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-primary">{{ $order->order_no }}</span>
                                <span class="badge {{ $fu->dyeing_status === 'Completed' ? 'bg-success' : ($fu->dyeing_status === 'In Progress' ? 'bg-primary' : ($fu->dyeing_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[10px]">
                                    {{ $fu->dyeing_status }}
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold mt-1">{{ $order->style_no }}</div>
                            <div class="text-[10px] text-gray-500">{{ $order->color_name ?? '—' }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Col 5: Cutting -->
        <div class="panel bg-gray-50 dark:bg-[#1b2e4b] flex flex-col h-full">
            <div class="flex items-center justify-between border-b pb-2 mb-3">
                <h3 class="font-bold text-xs uppercase text-primary">5. Cutting</h3>
                <span class="badge bg-success/20 text-success text-xs">{{ $followups->where('cutting_status', 'Completed')->count() }} Done</span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[70vh]">
                @foreach ($followups as $fu)
                    @php $order = $fu->factoryOrder?->customerOrder; @endphp
                    @if($order)
                        <div class="rounded-lg bg-white dark:bg-[#0e1726] p-3 shadow-sm border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-primary">{{ $order->order_no }}</span>
                                <span class="badge {{ $fu->cutting_status === 'Completed' ? 'bg-success' : ($fu->cutting_status === 'In Progress' ? 'bg-primary' : ($fu->cutting_status === 'Delayed' ? 'bg-danger' : 'bg-gray-500')) }} text-white text-[10px]">
                                    {{ $fu->cutting_status }}
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold mt-1">{{ $order->style_no }} ({{ number_format($order->color_qty) }} pcs)</div>
                            <div class="text-[10px] text-gray-400 mt-1">ETD: {{ $order->etd_date?->format('d M') ?? 'N/A' }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
