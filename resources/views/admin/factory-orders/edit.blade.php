@extends('admin.layouts.master')

@section('title', 'Edit Factory Pricing #' . $factoryOrder->customerOrder?->order_no)

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Edit Factory Commercials</h2>
            <p class="text-sm text-gray-500">Order #{{ $factoryOrder->customerOrder?->order_no }} (Style: {{ $factoryOrder->customerOrder?->style_no }})</p>
        </div>
        <a href="{{ route('admin.factory-orders.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <div class="panel mt-6 max-w-2xl">
        <form action="{{ route('admin.factory-orders.update', $factoryOrder) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="rounded bg-gray-50 dark:bg-[#1b2e4b] p-4 text-sm mb-4">
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Buyer Name:</span>
                    <strong class="text-primary">{{ $factoryOrder->customerOrder?->customer?->name ?? 'N/A' }}</strong>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Factory / Supplier:</span>
                    <strong class="text-secondary">{{ $factoryOrder->customerOrder?->supplier?->name ?? 'N/A' }}</strong>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Customer Unit Price:</span>
                    <strong class="text-success">{{ number_format($factoryOrder->customerOrder?->price ?? 0, 2) }}</strong>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="etd_price" class="font-semibold">ETD Price</label>
                    <input type="number" step="0.01" id="etd_price" name="etd_price"
                        value="{{ old('etd_price', $factoryOrder->etd_price) }}" class="form-input" />
                    @error('etd_price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="sub_price" class="font-semibold">Sub Price</label>
                    <input type="number" step="0.01" id="sub_price" name="sub_price"
                        value="{{ old('sub_price', $factoryOrder->sub_price) }}" class="form-input" />
                    @error('sub_price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="fob_price" class="font-semibold">FOB Price</label>
                    <input type="number" step="0.01" id="fob_price" name="fob_price"
                        value="{{ old('fob_price', $factoryOrder->fob_price) }}" class="form-input font-bold text-success" />
                    @error('fob_price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="aetd_date" class="font-semibold">Actual ETD Date (AETD)</label>
                    <input type="date" id="aetd_date" name="aetd_date"
                        value="{{ old('aetd_date', $factoryOrder->aetd_date?->format('Y-m-d')) }}" class="form-input font-semibold" />
                    @error('aetd_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.factory-orders.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Pricing & AETD</button>
            </div>
        </form>
    </div>
@endsection
