@extends('admin.layouts.master')

@section('title', 'New Customer Order')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Create Customer Order</h2>
            <p class="text-sm text-gray-500">Initiate a new apparel manufacturing order and auto-generate factory follow-up</p>
        </div>
        <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.customer-orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Parties -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">1. Buyer & Manufacturing Unit</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="customer_id" class="font-semibold">Customer / Buyer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Buyer / Brand</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
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
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
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
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="order_no" class="font-semibold">Order Number <span class="text-danger">*</span></label>
                        <input type="text" id="order_no" name="order_no" value="{{ old('order_no', $suggestedOrderNo) }}" required
                            class="form-input font-bold text-primary" />
                        @error('order_no') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="style_no" class="font-semibold">Style Number <span class="text-danger">*</span></label>
                        <input type="text" id="style_no" name="style_no" value="{{ old('style_no') }}" required
                            placeholder="e.g. STY-2026-901" class="form-input" />
                        @error('style_no') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="style_name" class="font-semibold">Style Name / Description</label>
                        <input type="text" id="style_name" name="style_name" value="{{ old('style_name') }}"
                            placeholder="e.g. Men Slim Crewneck T-Shirt" class="form-input" />
                        @error('style_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3 mt-4">
                    <div>
                        <label for="composition" class="font-semibold">Fabric Composition</label>
                        <input type="text" id="composition" name="composition" value="{{ old('composition') }}"
                            placeholder="e.g. 100% Cotton, 80/20 Cotton/Polyester" class="form-input" />
                        @error('composition') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="color_name" class="font-semibold">Color Name</label>
                        <input type="text" id="color_name" name="color_name" value="{{ old('color_name') }}"
                            placeholder="e.g. Navy Heather, Jet Black" class="form-input" />
                        @error('color_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="color_qty" class="font-semibold">Order Quantity (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" id="color_qty" name="color_qty" value="{{ old('color_qty', 0) }}" min="1" required
                            class="form-input font-bold" />
                        @error('color_qty') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Style Image Upload -->
                <div class="mt-4">
                    <label for="style_image" class="font-semibold">Style Sketch / Photo</label>
                    <input type="file" id="style_image" name="style_image" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    <span class="text-xs text-gray-500">Supported formats: JPG, PNG, WEBP (Max 5MB)</span>
                    @error('style_image') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Section 3: Pricing & Timeline -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">3. Commercials & Delivery Schedule</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="price" class="font-semibold">Customer Unit Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price', '0.00') }}" required
                            class="form-input font-bold text-success" />
                        @error('price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="order_date" class="font-semibold">Order Placement Date</label>
                        <input type="date" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="form-input" />
                        @error('order_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="etd_date" class="font-semibold">Estimated Time of Departure (ETD)</label>
                        <input type="date" id="etd_date" name="etd_date" value="{{ old('etd_date') }}" class="form-input" />
                        @error('etd_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="notes" class="font-semibold">Special Instructions / Remarks</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Packaging requirements, washing instructions, trims specs..." class="form-textarea">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Order & Initialize Production</button>
            </div>
        </form>
    </div>
@endsection
