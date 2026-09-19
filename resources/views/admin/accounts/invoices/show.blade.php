@extends('admin.layouts.master')

@section('title', 'Invoice: ' . $invoice->invoice_number)

@push('styles')
    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }

            .panel {
                border: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
        <h2 class="text-xl font-semibold uppercase">Invoice Details</h2>
        <div class="flex gap-2 text-xs">
            {{-- <button onclick="window.print()" class="btn btn-outline-primary gap-2 font-bold uppercase">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2zM17 9V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4" />
                </svg>
                Print
            </button> --}}
            <a href="{{ route('admin.invoices.edit', $invoice) }}"
                class="btn btn-outline-warning font-bold uppercase">Edit</a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary font-bold uppercase gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <!-- Invoice Voucher -->
    <div class="panel mt-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between mb-8 pb-6 border-b-2 border-primary/20">
            <div class="flex items-center gap-4">
                @if (get_setting('app_logo'))
                    <img src="{{ asset('storage/' . get_setting('app_logo')) }}" alt="Logo" class="h-14 w-auto" />
                @else
                    <div
                        class="p-3 bg-primary text-white rounded-xl font-black text-2xl uppercase tracking-tighter shadow-md">
                        INS</div>
                @endif
                <div>
                    <h4 class="text-2xl font-black uppercase text-primary tracking-tight">
                        {{ get_setting('app_name', 'Insaf Education') }}</h4>
                    <p class="text-[10px] text-white-dark font-bold tracking-[3px] uppercase mt-0.5">Consultancy & Agency
                    </p>
                </div>
            </div>
            <div
                class="text-right mt-4 md:mt-0 font-medium text-[10px] tracking-wide text-white-dark flex flex-col justify-end leading-relaxed">
                <p>Haque Tower (Opposite of BRB Hospital), Floor - 6, Panthapath, </p>
                <p>Dhaka-1205, Bangladesh</p>
                <p class="text-primary font-bold mt-1">insafimmigration@gmail.com</p>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-6 border-b border-white-light dark:border-white-light/10">
            <!-- Bill To -->
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-white-dark font-bold uppercase tracking-[4px] mb-2">Invoice To / Customer:</p>
                    <h4 class="text-xl font-black text-dark dark:text-white-light">
                        {{ $invoice->customerOrder?->customer?->name ?? 'Direct Customer' }}
                    </h4>
                    @if($invoice->customerOrder?->customer?->company_name)
                        <p class="text-xs text-white-dark">{{ $invoice->customerOrder->customer->company_name }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-white-dark font-bold uppercase tracking-widest mb-0.5">Order Number</p>
                        <p class="text-sm font-bold text-primary">{{ $invoice->customerOrder?->order_no ?? 'General' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-white-dark font-bold uppercase tracking-widest mb-0.5">Style / Item</p>
                        <p class="text-sm font-bold">{{ $invoice->customerOrder?->style_no ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Meta -->
            <div class="bg-white-light/30 dark:bg-white-dark/5 p-6 rounded-2xl">
                <div class="text-right mb-4">
                    <p class="text-[10px] font-bold text-white-dark uppercase tracking-[5px] mb-1">Invoice No.</p>
                    <h3 class="text-2xl font-black text-primary tracking-tight">{{ $invoice->invoice_number }}</h3>
                </div>
                <div class="grid grid-cols-3 gap-4 text-right">
                    <div>
                        <p class="text-[10px] font-bold text-white-dark uppercase tracking-widest mb-0.5">Issue Date</p>
                        <p class="text-xs font-bold">{{ $invoice->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-white-dark uppercase tracking-widest mb-0.5">Due Date</p>
                        <p class="text-xs font-bold text-danger">{{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-white-dark uppercase tracking-widest mb-0.5">Status</p>
                        @if ($invoice->status == 'paid')
                            <span class="badge badge-outline-success text-[10px] font-black">Paid</span>
                        @elseif($invoice->status == 'partially_paid')
                            <span class="badge badge-outline-warning text-[10px] font-black">Partial</span>
                        @elseif($invoice->status == 'sent')
                            <span class="badge badge-outline-info text-[10px] font-black">Sent</span>
                        @elseif($invoice->status == 'void')
                            <span class="badge badge-outline-dark text-[10px] font-black">Void</span>
                        @else
                            <span class="badge badge-outline-secondary text-[10px] font-black">Draft</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary text-black font-bold uppercase text-[10px] tracking-widest">
                        <th class="p-4 text-left rounded-tl-lg">Service Particulars</th>
                        <th class="p-4 text-right w-20">Qty</th>
                        <th class="p-4 text-right w-36">Unit Rate</th>
                        <th class="p-4 text-right w-40 rounded-tr-lg">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr
                            class="border-b border-white-light/50 dark:border-white-light/10 hover:bg-primary/5 transition-all">
                            <td class="p-4">
                                <span class="font-bold text-sm text-primary block">{{ $item->chartOfAccount->name }}</span>
                                <span class="text-xs text-white-dark">{{ $item->description }}</span>
                            </td>
                            <td class="p-4 text-right font-bold">{{ number_format($item->quantity, 0) }}</td>
                            <td class="p-4 text-right font-mono text-white-dark">{{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="p-4 text-right font-bold font-mono text-dark dark:text-white-light">
                                {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-primary">
                        <td colspan="3"
                            class="p-5 text-right uppercase text-xs font-bold tracking-[6px] text-black rounded-bl-lg">
                            Grand Total:</td>
                        <td class="p-5 text-right text-black font-black text-2xl font-mono rounded-br-lg">
                            {{ number_format($invoice->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer: Notes & Signatures -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12 pb-8">
            <div>
                @if ($invoice->notes)
                    <div class="p-5 bg-primary/5 border-l-4 border-primary rounded-r-xl">
                        <p class="text-[10px] font-bold uppercase tracking-[3px] text-primary mb-2 opacity-60">Notes:</p>
                        <ol class="text-sm text-white-dark leading-relaxed list-decimal list-inside space-y-1">
                            @foreach (array_filter(explode("\n", $invoice->notes)) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>

            <div class="flex flex-col justify-end pt-8">
                <!-- <div class="grid grid-cols-2 gap-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-white-dark/30 mb-2"></div>
                        <p class="font-bold uppercase text-[9px] tracking-widest text-white-dark">Student Signature</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-primary mb-2"></div>
                        <p class="font-bold uppercase text-[9px] tracking-widest text-primary">Accounts Manager</p>
                        <p class="text-[7px] text-white-dark mt-0.5">For {{ get_setting('app_name', 'Insaf Education') }}
                        </p>
                    </div>
                </div> -->

                <div class="mt-8 text-right">
                    <p class="text-[8px] text-white-dark tracking-[5px] uppercase opacity-40">System generated document</p>
                </div>
            </div>
        </div>

        <!-- Decorative Footer -->
        <div class="w-full h-1.5 bg-gradient-to-r from-primary via-info to-primary rounded-full"></div>
    </div>
@endsection
