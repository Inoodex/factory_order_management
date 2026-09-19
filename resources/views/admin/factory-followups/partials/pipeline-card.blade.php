@php
    $order = $fu->factoryOrder?->customerOrder;
    $now = \Carbon\Carbon::now()->startOfDay();
    $etd = $order?->etd_date ? $order->etd_date->startOfDay() : null;
    $isCompleted = ($stage === 'completed' || $fu->current_stage === 'completed');
    $isOverdue = ($etd && $etd->lt($now) && !$isCompleted);
    $daysLeft = $etd ? $now->diffInDays($etd, false) : null;
    $progress = $fu->progress_percentage;
@endphp

@if($order)
<div class="kanban-card rounded-xl bg-white dark:bg-[#0e1726] p-3.5 shadow-xs border border-gray-100 dark:border-gray-800/80"
    data-search="{{ $order->order_no }} {{ $order->style_no }} {{ $order->style_name }} {{ $order->brand }} {{ $order->customer?->name }} {{ $order->supplier?->name }} {{ $order->color_name }}">
    
    <!-- Card Header: Order No & ETD Urgency Badge -->
    <div class="flex items-center justify-between gap-2 border-b border-gray-100 dark:border-gray-800 pb-2">
        <a href="{{ route('admin.customer-orders.show', $order) }}" 
           class="font-bold text-xs text-primary hover:underline flex items-center gap-1.5">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-primary"></span>
            {{ $order->order_no }}
        </a>

        @if($isCompleted)
            <span class="badge bg-success/20 text-success font-bold text-[10px] px-1.5 py-0.5">
                Ready / Shipped
            </span>
        @elseif($isOverdue)
            <span class="badge bg-danger text-white font-bold text-[10px] px-1.5 py-0.5 animate-pulse">
                Overdue ({{ abs($daysLeft) }}d)
            </span>
        @elseif($daysLeft !== null && $daysLeft <= 7)
            <span class="badge bg-warning/20 text-warning font-bold text-[10px] px-1.5 py-0.5">
                Due in {{ $daysLeft }}d
            </span>
        @elseif($daysLeft !== null)
            <span class="badge bg-gray-100 text-gray-600 dark:bg-[#1b2e4b] dark:text-gray-300 font-medium text-[10px] px-1.5 py-0.5">
                in {{ $daysLeft }}d
            </span>
        @endif
    </div>

    <!-- Card Body: Thumbnail & Details -->
    <div class="mt-2.5 flex items-start gap-2.5">
        @if($order->style_image)
            <img src="{{ $order->image_url }}" alt="{{ $order->style_no }}"
                class="h-12 w-12 rounded-lg object-cover border border-gray-100 dark:border-gray-700 shadow-2xs shrink-0" />
        @else
            <div class="h-12 w-12 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 flex items-center justify-center font-bold text-xs shrink-0 border border-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"/>
                </svg>
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <h4 class="font-bold text-xs text-gray-900 dark:text-white truncate" title="{{ $order->style_no }}">
                {{ $order->style_no }}
            </h4>
            @if($order->style_name)
                <p class="text-[11px] text-gray-500 truncate" title="{{ $order->style_name }}">
                    {{ $order->style_name }}
                </p>
            @endif
            
            <div class="mt-1 flex items-center gap-1 text-[11px] text-gray-600 dark:text-gray-300 font-medium truncate">
                <svg class="h-3 w-3 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="truncate">{{ $order->customer?->name ?? '—' }}</span>
                @if($order->brand || $order->customer?->brand)
                    <span class="text-primary font-semibold">({{ $order->brand ?? $order->customer?->brand }})</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Factory & Location -->
    <div class="mt-2 flex items-center justify-between text-[10px] text-gray-500 border-t border-gray-100 dark:border-gray-800 pt-2">
        <div class="flex items-center gap-1 truncate" title="{{ $order->supplier?->name }}">
            <svg class="h-3 w-3 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="truncate">{{ $order->supplier?->name ?? 'Unassigned' }}</span>
        </div>
        <span class="font-bold text-gray-700 dark:text-gray-300 shrink-0">{{ number_format($order->color_qty) }} pcs</span>
    </div>

    <!-- Mini Milestone Progress Bar -->
    <div class="mt-2.5">
        <div class="flex items-center justify-between text-[10px] text-gray-400 mb-1">
            <span>Milestone Progress</span>
            <span class="font-bold {{ $progress >= 100 ? 'text-success' : 'text-primary' }}">{{ $progress }}%</span>
        </div>
        <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300 {{ $progress >= 100 ? 'bg-success' : ($progress >= 60 ? 'bg-info' : 'bg-primary') }}"
                 style="width: {{ $progress }}%"></div>
        </div>

        <!-- 5 Step Dots -->
        <div class="mt-1.5 flex items-center justify-between text-[9px] text-gray-400 font-semibold px-0.5">
            <span class="{{ str_contains($fu->pps_comments_status ?? '', 'Approved') ? 'text-success font-bold' : 'text-gray-400' }}" title="Pre-Production Sample">PPS</span>
            <span>•</span>
            <span class="{{ $fu->knitting_status === 'Completed' ? 'text-success font-bold' : ($fu->knitting_status === 'In Progress' ? 'text-info font-bold' : 'text-gray-400') }}" title="Knitting">KNIT</span>
            <span>•</span>
            <span class="{{ $fu->dyeing_status === 'Completed' ? 'text-success font-bold' : ($fu->dyeing_status === 'In Progress' ? 'text-warning font-bold' : 'text-gray-400') }}" title="Dyeing">DYE</span>
            <span>•</span>
            <span class="{{ $fu->cutting_status === 'Completed' ? 'text-success font-bold' : ($fu->cutting_status === 'In Progress' ? 'text-secondary font-bold' : 'text-gray-400') }}" title="Cutting">CUT</span>
            <span>•</span>
            <span class="{{ $fu->shs_comments_status === 'Approved' ? 'text-success font-bold' : 'text-gray-400' }}" title="Shipment Sample">SHS</span>
        </div>
    </div>

    <!-- Card Footer Actions -->
    <div class="mt-3 flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-2">
        <a href="{{ route('admin.customer-orders.show', $order) }}" class="text-[11px] font-medium text-gray-500 hover:text-primary hover:underline">
            View Order
        </a>
        <a href="{{ route('admin.factory-followups.edit', $fu) }}" class="btn btn-xs btn-outline-primary px-2.5 py-1 text-[10px] font-bold">
            Update Status
        </a>
    </div>

</div>
@endif
