@extends('admin.layouts.master')

@section('title', 'Import Customer Orders')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Bulk Import Customer Orders</h2>
            <p class="text-sm text-gray-500">Upload an Excel/CSV file to batch-import apparel orders</p>
        </div>
        <a href="{{ route('admin.customer-orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Import Form -->
        <div class="panel lg:col-span-2">
            <h3 class="text-md font-bold uppercase text-primary border-b pb-3 mb-4">Upload Spreadsheet</h3>

            @if ($errors->any())
                <div class="mb-4 rounded bg-danger/10 p-4 text-danger">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.order-import-export.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="file" class="font-semibold block mb-2">Select Excel or CSV file <span class="text-danger">*</span></label>
                    <input type="file" id="file" name="file" required accept=".xlsx,.xls,.csv"
                        class="form-input file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    <span class="text-xs text-gray-500 block mt-1">Supported formats: .xlsx, .xls, .csv (Maximum file size: 10MB)</span>
                </div>

                <div class="pt-4 border-t flex items-center justify-between">
                    <a href="{{ route('admin.order-import-export.download-template') }}" class="btn btn-outline-success gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download Sample Template (.csv)
                    </a>

                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Process Import
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions Sidebar -->
        <div class="panel">
            <h3 class="text-md font-bold uppercase text-primary border-b pb-3 mb-4">Required Columns</h3>
            <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                <li><strong class="text-primary font-mono">order_no</strong>: Unique Customer Order # (e.g. ORD-2026-0001)</li>
                <li><strong class="text-primary font-mono">customer_name</strong>: Buyer company name (auto-created if new)</li>
                <li><strong class="text-primary font-mono">brand</strong>: Buyer brand name (optional)</li>
                <li><strong class="text-primary font-mono">season</strong>: Production season (e.g. Summer 2026)</li>
                <li><strong class="text-primary font-mono">supplier_name</strong>: Factory unit name (auto-created if new)</li>
                <li><strong class="text-primary font-mono">location</strong>: Factory location (optional)</li>
                <li><strong class="text-primary font-mono">style_no</strong>: Style number / SKU</li>
                <li><strong class="text-primary font-mono">style_name</strong>: Style description</li>
                <li><strong class="text-primary font-mono">composition</strong>: Fabric breakdown (e.g. 100% Cotton)</li>
                <li><strong class="text-primary font-mono">color_name</strong>: Color description</li>
                <li><strong class="text-primary font-mono">color_qty</strong>: Order quantity in pieces</li>
                <li><strong class="text-primary font-mono">price</strong>: Unit price per piece</li>
                <li><strong class="text-primary font-mono">order_date</strong>: Format YYYY-MM-DD</li>
                <li><strong class="text-primary font-mono">etd_date</strong>: Delivery ETD (YYYY-MM-DD)</li>
            </ul>

            <div class="mt-4 rounded bg-info/10 p-3 text-xs text-info">
                <strong>Tip:</strong> Download the sample template to test with pre-formatted columns.
            </div>
        </div>
    </div>
@endsection
