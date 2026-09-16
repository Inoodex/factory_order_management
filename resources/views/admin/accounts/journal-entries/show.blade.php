@extends('admin.layouts.master')

@section('title', 'Journal Voucher: ' . $journalEntry->reference_number)

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
        <h2 class="text-xl font-semibold uppercase">Journal Voucher Details</h2>
        <div class="flex gap-2 text-xs">
            {{-- <button onclick="window.print()" class="btn btn-outline-primary gap-2 font-bold uppercase">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2zM17 9V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4" />
                </svg>
                Print
            </button> --}}
            <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-secondary font-bold uppercase">Back to
                List</a>
        </div>
    </div>

    <!-- Voucher Template -->
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

        <!-- Voucher Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-6 border-b border-white-light dark:border-white-light/10">
            <!-- Prepared By -->
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-white-dark font-bold uppercase tracking-[4px] mb-2">Voucher Prepared By:</p>
                    <h4 class="text-xl font-black text-dark dark:text-white-light">{{ $journalEntry->creator->name }}</h4>
                    <p class="text-xs font-mono font-bold text-primary bg-primary/5 inline-block px-2 py-0.5 rounded mt-1">
                        Accounts Dept.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-white-dark font-bold uppercase tracking-widest mb-0.5">Accounting Period
                        </p>
                        <p class="text-sm font-bold text-primary">{{ $journalEntry->period->name }}</p>
                    </div>
                    @if ($journalEntry->application)
                        <div>
                            <p class="text-[10px] text-white-dark font-bold uppercase tracking-widest mb-0.5">Related
                                Application</p>
                            <a href="{{ route('admin.applications.edit', $journalEntry->application) }}"
                                class="text-sm font-bold text-primary hover:underline">
                                {{ $journalEntry->application->student->first_name }}
                                {{ $journalEntry->application->student->last_name }}
                                <span
                                    class="block text-[10px] text-white-dark">{{ $journalEntry->application->application_id }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Voucher Meta -->
            <div class="bg-white-light/30 dark:bg-white-dark/5 p-6 rounded-2xl">
                <div class="text-right mb-4">
                    <p class="text-[10px] font-bold text-white-dark uppercase tracking-[5px] mb-1">Voucher No.</p>
                    <h3 class="text-2xl font-black text-primary tracking-tight">{{ $journalEntry->reference_number }}</h3>
                </div>
                <div class="grid grid-cols-2 gap-4 text-right">
                    <div>
                        <p class="text-[10px] font-bold text-white-dark uppercase tracking-widest mb-0.5">Voucher Date</p>
                        <p class="text-xs font-bold">{{ $journalEntry->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-white-dark uppercase tracking-widest mb-0.5">Status</p>
                        <span
                            class="badge badge-outline-success text-[10px] font-black uppercase">{{ $journalEntry->status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary text-black font-bold uppercase text-[10px] tracking-widest">
                        <th class="p-4 text-left rounded-tl-lg">Account Head & Description</th>
                        <th class="p-4 text-right w-40">Debit</th>
                        <th class="p-4 text-right w-40 rounded-tr-lg">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journalEntry->items as $item)
                        <tr
                            class="border-b border-white-light/50 dark:border-white-light/10 hover:bg-primary/5 transition-all">
                            <td class="p-4">
                                <span class="font-bold text-sm text-primary block leading-none mb-1">
                                    <span
                                        class="text-[10px] text-white-dark font-mono bg-white-dark/5 px-1 mr-1 rounded">{{ $item->chartOfAccount->code }}</span>
                                    {{ $item->chartOfAccount->name }}
                                </span>
                                <span class="text-xs text-white-dark italic">{{ $item->description }}</span>
                            </td>
                            <td class="p-4 text-right font-black font-mono text-success text-base">
                                {{ $item->debit > 0 ? number_format($item->debit, 2) : '-' }}
                            </td>
                            <td class="p-4 text-right font-black font-mono text-danger text-base">
                                {{ $item->credit > 0 ? number_format($item->credit, 2) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-primary">
                        <td
                            class="p-5 text-right uppercase text-xs font-bold tracking-[6px] text-black border-r border-black/5 rounded-bl-lg">
                            Voucher Totals:</td>
                        <td class="p-5 text-right text-black font-black text-2xl font-mono border-r border-black/5">
                            {{ number_format($journalEntry->items->sum('debit'), 2) }}
                        </td>
                        <td class="p-5 text-right text-black font-black text-2xl font-mono rounded-br-lg">
                            {{ number_format($journalEntry->items->sum('credit'), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footnote & Signatures -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12 pb-8">
            <div>
                @if ($journalEntry->note)
                    <div class="p-5 bg-primary/5 border-l-4 border-primary rounded-r-xl">
                        <p class="text-[10px] font-bold uppercase tracking-[3px] text-primary mb-2 opacity-60">Narration /
                            Remarks:</p>
                        <p class="text-sm text-white-dark leading-relaxed italic">"{{ $journalEntry->note }}"</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-col justify-end pt-8">
                <div class="grid grid-cols-2 gap-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-white-dark/30 mb-2"></div>
                        <p class="font-bold uppercase text-[9px] tracking-widest text-white-dark">Prepared By</p>
                        <p class="text-[7px] text-white-dark mt-0.5">{{ $journalEntry->creator->name }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-primary mb-2"></div>
                        <p class="font-bold uppercase text-[9px] tracking-widest text-primary">Accounts Manager</p>
                        <p class="text-[7px] text-white-dark mt-0.5">Authorized Approval</p>
                    </div>
                </div>

                <div class="mt-8 text-right">
                    <p class="text-[8px] text-white-dark tracking-[5px] uppercase opacity-40">System generated voucher</p>
                </div>
            </div>
        </div>

        <!-- Decorative Footer -->
        <div class="w-full h-1.5 bg-gradient-to-r from-primary via-info to-primary rounded-full"></div>
    </div>
@endsection
