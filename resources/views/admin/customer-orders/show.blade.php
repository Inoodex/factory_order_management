@extends('admin.layouts.master')

@section('title', 'Order #' . $customerOrder->order_no)

@section('content')
    @php
        $factoryOrder = $customerOrder->factoryOrder;
        $followup = $factoryOrder?->followup;
    @endphp

    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Back to Orders
            </a>
            <h2 class="text-xl font-bold uppercase text-primary">
                Order #{{ $customerOrder->order_no }}
            </h2>
            @if($customerOrder->is_overdue)
                <span class="badge bg-danger text-white uppercase text-xs">ETD Overdue</span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.customer-orders.edit', $customerOrder) }}" class="btn btn-primary btn-sm gap-1">
                Edit Order
            </a>
            @if($factoryOrder)
                <a href="{{ route('admin.factory-orders.edit', $factoryOrder) }}" class="btn btn-outline-info btn-sm gap-1">
                    Edit Factory Pricing
                </a>
            @endif
            @if($followup)
                <a href="{{ route('admin.factory-followups.edit', $followup) }}" class="btn btn-outline-success btn-sm gap-1">
                    Update Production Follow-up
                </a>
            @endif
        </div>
    </div>

    <!-- Main Grid -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left 2 Cols: Order & Style Info -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Order & Style Card -->
            <div class="panel">
                <div class="flex items-start justify-between border-b pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-2xl font-bold text-primary">{{ $customerOrder->style_no }}</h3>
                            @if($customerOrder->brand || $customerOrder->customer?->brand)
                                <span class="badge bg-primary/10 text-primary text-xs font-bold uppercase">{{ $customerOrder->brand ?: $customerOrder->customer?->brand }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $customerOrder->style_name ?? 'No style description' }}</p>
                    </div>
                    @if($customerOrder->style_image)
                        <a href="{{ $customerOrder->image_url }}" target="_blank" title="View Full Image">
                            <img src="{{ $customerOrder->image_url }}" alt="Style"
                                class="h-24 w-24 rounded-lg object-cover shadow-md border hover:scale-105 transition-transform" />
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 py-4 border-b text-sm">
                    <div>
                        <span class="text-gray-400 block text-xs">Fabric Composition</span>
                        <span class="font-semibold">{{ $customerOrder->composition ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Color Name</span>
                        <span class="font-semibold">{{ $customerOrder->color_name ?? 'Standard' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Order Quantity</span>
                        <span class="font-bold text-base text-primary">{{ number_format($customerOrder->color_qty) }} pcs</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Customer Price</span>
                        <span class="font-bold text-base text-success">{{ number_format($customerOrder->price, 2) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 pt-4 text-sm">
                    <div>
                        <span class="text-gray-400 block text-xs">Total Order Value</span>
                        <span class="text-xl font-extrabold text-success">{{ number_format($customerOrder->total_price, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Order Placement Date</span>
                        <span class="font-semibold">{{ $customerOrder->order_date?->format('d M Y') ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Delivery ETD Date</span>
                        <span class="font-semibold {{ $customerOrder->is_overdue ? 'text-danger font-bold' : '' }}">
                            {{ $customerOrder->etd_date?->format('d M Y') ?? '—' }}
                        </span>
                    </div>
                </div>

                @if($customerOrder->notes)
                    <div class="mt-4 rounded bg-gray-50 dark:bg-[#1b2e4b] p-3 text-xs text-gray-600 dark:text-gray-300">
                        <strong class="font-bold text-gray-800 dark:text-white">Remarks:</strong> {{ $customerOrder->notes }}
                    </div>
                @endif
            </div>

            <!-- Production Follow-up Stage Board -->
            @if($followup)
            <div class="panel">
                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="text-md font-bold uppercase text-primary">Production & Sampling Follow-up</h3>
                    <a href="{{ route('admin.factory-followups.edit', $followup) }}" class="btn btn-xs btn-outline-primary">
                        Update Statuses
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <!-- PPS Status -->
                    <div class="rounded-lg border p-4">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">1. Pre-Production Sample (PPS)</span>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="badge {{ $followup->pps_comments_status === 'Approved' ? 'bg-success' : ($followup->pps_comments_status === 'Rejected' ? 'bg-danger' : 'bg-warning') }} text-white text-xs">
                                {{ $followup->pps_comments_status }}
                            </span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-2">Date: {{ $followup->pps_date?->format('d M Y') ?? 'Not recorded' }}</span>
                    </div>

                    <!-- SHS Status -->
                    <div class="rounded-lg border p-4">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">2. Shipment Sample (SHS)</span>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="badge {{ $followup->shs_comments_status === 'Approved' ? 'bg-success' : ($followup->shs_comments_status === 'Rejected' ? 'bg-danger' : 'bg-info') }} text-white text-xs">
                                {{ $followup->shs_comments_status }}
                            </span>
                        </div>
                        <span class="text-[11px] text-gray-400 block mt-2">Sent: {{ $followup->shs_sending_date?->format('d M Y') ?? 'Not recorded' }}</span>
                    </div>

                    <!-- Knitting -->
                    <div class="rounded-lg border p-4">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">3. Knitting Stage</span>
                        <div class="mt-2">
                            <span class="badge {{ $followup->knitting_status === 'Completed' ? 'bg-success' : ($followup->knitting_status === 'In Progress' ? 'bg-primary' : 'bg-gray-500') }} text-white text-xs">
                                {{ $followup->knitting_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Dyeing -->
                    <div class="rounded-lg border p-4">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">4. Dyeing Stage</span>
                        <div class="mt-2">
                            <span class="badge {{ $followup->dyeing_status === 'Completed' ? 'bg-success' : ($followup->dyeing_status === 'In Progress' ? 'bg-primary' : 'bg-gray-500') }} text-white text-xs">
                                {{ $followup->dyeing_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Cutting -->
                    <div class="rounded-lg border p-4">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">5. Cutting Stage</span>
                        <div class="mt-2">
                            <span class="badge {{ $followup->cutting_status === 'Completed' ? 'bg-success' : ($followup->cutting_status === 'In Progress' ? 'bg-primary' : 'bg-gray-500') }} text-white text-xs">
                                {{ $followup->cutting_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Factory FOB / Sub Price -->
                    <div class="rounded-lg border p-4 bg-gray-50 dark:bg-[#1b2e4b]">
                        <span class="text-xs font-semibold text-gray-500 block uppercase">Followup Commercials</span>
                        <div class="mt-2 text-xs">
                            <div>FOB Price: <strong class="text-primary">{{ number_format($followup->fob_price ?? 0, 2) }}</strong></div>
                            <div class="mt-1">Sub Price: <strong class="text-secondary">{{ number_format($followup->sub_price ?? 0, 2) }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right 1 Col: Parties & Factory Commercials -->
        <div class="space-y-6">
            <!-- Customer / Buyer Card -->
            <div class="panel">
                <h3 class="text-md font-bold uppercase text-primary border-b pb-2 mb-3">Customer / Buyer</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block">Name:</span>
                        <strong class="text-base text-primary">{{ $customerOrder->customer?->name ?? '—' }}</strong>
                    </div>
                    @php
                        $displayBrand = $customerOrder->brand ?: $customerOrder->customer?->brand;
                    @endphp
                    @if($displayBrand)
                        <div>
                            <span class="text-xs text-gray-400 block">Brand:</span>
                            <span class="badge bg-info/10 text-info">{{ $displayBrand }}</span>
                        </div>
                    @endif
                    @if($customerOrder->customer?->session)
                        <div>
                            <span class="text-xs text-gray-400 block">Buying Season:</span>
                            <span class="badge bg-secondary/10 text-secondary">{{ $customerOrder->customer->session }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-xs text-gray-400 block">Email:</span>
                        <span>{{ $customerOrder->customer?->email ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Phone:</span>
                        <span>{{ $customerOrder->customer?->phone ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- Supplier / Factory Card -->
            <div class="panel">
                <h3 class="text-md font-bold uppercase text-primary border-b pb-2 mb-3">Supplier / Manufacturing Factory</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block">Factory Name:</span>
                        <strong class="text-base">{{ $customerOrder->supplier?->name ?? '—' }}</strong>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Factory Location:</span>
                        <span>{{ $customerOrder->supplier?->location ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Contact Person:</span>
                        <span>{{ $customerOrder->supplier?->contact_person ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Phone:</span>
                        <span>{{ $customerOrder->supplier?->phone ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- Factory Order Pricing & AETD -->
            @if($factoryOrder)
            <div class="panel">
                <div class="flex items-center justify-between border-b pb-2 mb-3">
                    <h3 class="text-md font-bold uppercase text-primary">Factory Pricing</h3>
                    <a href="{{ route('admin.factory-orders.edit', $factoryOrder) }}" class="btn btn-xs btn-outline-primary">
                        Edit
                    </a>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ETD Price:</span>
                        <span class="font-semibold">{{ number_format($factoryOrder->etd_price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sub Price:</span>
                        <span class="font-semibold">{{ number_format($factoryOrder->sub_price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">FOB Price:</span>
                        <span class="font-bold text-success">{{ number_format($factoryOrder->fob_price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-gray-500">Actual ETD (AETD):</span>
                        <span class="font-bold {{ $factoryOrder->is_overdue ? 'text-danger' : 'text-primary' }}">
                            {{ $factoryOrder->aetd_date?->format('d M Y') ?? 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
