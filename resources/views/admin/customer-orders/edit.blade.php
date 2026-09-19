@extends('admin.layouts.master')

@section('title', 'Edit Customer Order #' . $customerOrder->order_no)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}">
    <style>
        .nice-select {
            width: 100%;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            background-image: none !important;
        }

        .nice-select .current {
            line-height: normal !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .nice-select .list {
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
        }

        .nice-select .nice-select-dropdown {
            width: 100% !important;
            z-index: 50 !important;
        }

        .form-select {
            background-image: none !important;
        }

        /* Dark mode support */
        .dark .nice-select {
            background-color: #1b2e4b;
            border-color: #253b5c;
            color: #888ea8;
        }

        .dark .nice-select .current {
            color: #bfc9d4;
        }

        .dark .nice-select .nice-select-dropdown {
            background-color: #1b2e4b;
            border-color: #253b5c;
            box-shadow: 0 0 0 1px #253b5c;
        }

        .dark .nice-select .nice-select-search {
            background-color: #0e1726;
            border-color: #253b5c;
            color: #e0e6ed;
        }

        .dark .nice-select .option {
            color: #bfc9d4;
        }

        .dark .nice-select .option:hover,
        .dark .nice-select .option.focus,
        .dark .nice-select .option.selected.focus {
            background-color: #0e1726 !important;
            color: #fff;
        }

        .dark .nice-select:after {
            border-color: #888ea8;
        }
    </style>
@endpush

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Edit Customer Order</h2>
            <p class="text-sm text-gray-500">Order #{{ $customerOrder->order_no }} (Style: {{ $customerOrder->style_no }})</p>
        </div>
        <a href="{{ route('admin.customer-orders.show', $customerOrder) }}" class="btn btn-outline-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Order
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.customer-orders.update', $customerOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Parties -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">1. Buyer & Manufacturing Unit</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="customer_id" class="font-semibold">Customer / Buyer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Buyer</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}" data-brand="{{ $c->brand }}" {{ old('customer_id', $customerOrder->customer_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} {{ $c->brand ? "({$c->brand})" : '' }} {{ $c->session ? "- {$c->session}" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="supplier_id" class="font-semibold">Supplier / Factory Unit <span class="text-danger">*</span></label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">Select Factory / Supplier</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $customerOrder->supplier_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} {{ $s->location ? "({$s->location})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Order & Style Details -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">2. Order & Style Specifications</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                    <div>
                        <label for="order_no" class="font-semibold">Order Number <span class="text-danger">*</span></label>
                        <input type="text" id="order_no" name="order_no" value="{{ old('order_no', $customerOrder->order_no) }}" required
                            class="form-input font-bold text-primary" />
                        @error('order_no') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="brand" class="font-semibold">Brand</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $customerOrder->brand ?? $customerOrder->customer?->brand) }}"
                            class="form-input" />
                        @error('brand') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="style_no" class="font-semibold">Style Number <span class="text-danger">*</span></label>
                        <input type="text" id="style_no" name="style_no" value="{{ old('style_no', $customerOrder->style_no) }}" required
                            class="form-input" />
                        @error('style_no') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="style_name" class="font-semibold">Style Name / Description</label>
                        <input type="text" id="style_name" name="style_name" value="{{ old('style_name', $customerOrder->style_name) }}"
                            class="form-input" />
                        @error('style_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3 mt-4">
                    <div>
                        <label for="composition" class="font-semibold">Fabric Composition</label>
                        <input type="text" id="composition" name="composition" value="{{ old('composition', $customerOrder->composition) }}"
                            class="form-input" />
                        @error('composition') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="color_name" class="font-semibold">Color Name</label>
                        <input type="text" id="color_name" name="color_name" value="{{ old('color_name', $customerOrder->color_name) }}"
                            class="form-input" />
                        @error('color_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="color_qty" class="font-semibold">Order Quantity (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" id="color_qty" name="color_qty" value="{{ old('color_qty', $customerOrder->color_qty) }}" min="1" required
                            class="form-input font-bold" />
                        @error('color_qty') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Style Image Upload & Existing Thumbnail -->
                <div class="mt-4 flex items-center gap-6">
                    @if($customerOrder->style_image)
                        <div>
                            <span class="text-xs font-semibold text-gray-500 block mb-1">Current Image:</span>
                            <img src="{{ $customerOrder->image_url }}" alt="Style Thumbnail"
                                class="h-16 w-16 object-cover rounded border shadow-sm" />
                        </div>
                    @endif
                    <div class="flex-1">
                        <label for="style_image" class="font-semibold">Change Style Sketch / Photo</label>
                        <input type="file" id="style_image" name="style_image" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                        <span class="text-xs text-gray-500">Leave empty to keep existing image.</span>
                        @error('style_image') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Pricing & Timeline -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">3. Commercials & Delivery Schedule</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="price" class="font-semibold">Customer Unit Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $customerOrder->price) }}" required
                            class="form-input font-bold text-success" />
                        @error('price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="order_date" class="font-semibold">Order Placement Date</label>
                        <input type="date" id="order_date" name="order_date" value="{{ old('order_date', $customerOrder->order_date?->format('Y-m-d')) }}" class="form-input" />
                        @error('order_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="etd_date" class="font-semibold">Estimated Time of Departure (ETD)</label>
                        <input type="date" id="etd_date" name="etd_date" value="{{ old('etd_date', $customerOrder->etd_date?->format('Y-m-d')) }}" class="form-input" />
                        @error('etd_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="notes" class="font-semibold">Special Instructions / Remarks</label>
                    <textarea id="notes" name="notes" rows="2" class="form-textarea">{{ old('notes', $customerOrder->notes) }}</textarea>
                    @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.customer-orders.show', $customerOrder) }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const customerSelect = document.getElementById('customer_id');
                const supplierSelect = document.getElementById('supplier_id');

                if (customerSelect && typeof NiceSelect !== 'undefined') {
                    NiceSelect.bind(customerSelect, {
                        searchable: true,
                        placeholder: 'Select Buyer / Customer'
                    });
                }

                if (supplierSelect && typeof NiceSelect !== 'undefined') {
                    NiceSelect.bind(supplierSelect, {
                        searchable: true,
                        placeholder: 'Select Factory / Supplier'
                    });
                }

                customerSelect?.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const brandInput = document.getElementById('brand');
                    if (brandInput && !brandInput.value && selected && selected.dataset.brand) {
                        brandInput.value = selected.dataset.brand;
                    }
                });
            });
        </script>
    @endpush
@endsection

