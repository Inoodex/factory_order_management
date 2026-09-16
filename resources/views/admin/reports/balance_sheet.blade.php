@extends('admin.layouts.master')

@section('title', 'Balance Sheet')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Balance Sheet</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.balance-sheet.pdf', ['as_of_date' => $asOfDate, 'from_date' => $fromDate, 'output' => 'preview']) }}" target="_blank" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Preview
            </a>
            <a href="{{ route('admin.reports.balance-sheet.pdf', ['as_of_date' => $asOfDate, 'from_date' => $fromDate, 'output' => 'download']) }}" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.reports.balance-sheet') }}" method="GET"
            class="mb-5 flex flex-wrap items-center gap-4 no-print">
            <div class="flex items-center gap-2">
                <label for="from_date" class="mb-0 font-bold">From Date:</label>
                <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}" class="form-input w-48">
            </div>
            <div class="flex items-center gap-2">
                <label for="as_of_date" class="mb-0 font-bold">As of Date:</label>
                <input type="date" name="as_of_date" id="as_of_date" value="{{ $asOfDate }}" class="form-input w-48">
            </div>
            <button type="submit" class="btn btn-secondary">Generate Report</button>
        </form>

        <div class="text-center mb-8 border-b pb-4">
            <h3 class="text-2xl font-bold uppercase tracking-widest">Balance Sheet</h3>
            <p class="text-gray-500 mt-1 uppercase text-sm">As of {{ \Carbon\Carbon::parse($asOfDate)->format('d F, Y') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            {{-- Left Side: Assets --}}
            <div class="space-y-6">
                <div>
                    <h4
                        class="text-lg font-bold uppercase bg-primary/10 p-3 rounded-lg flex justify-between items-center text-primary">
                        <span>Assets</span>
                        <span class="text-sm font-normal">Amount</span>
                    </h4>

                    <div class="mt-4 space-y-2 px-2">
                        @forelse($data['asset'] as $asset)
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col">
                                    <span
                                        class="font-medium text-gray-700 uppercase text-sm tracking-tight">{{ $asset->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $asset->code }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ number_format($asset->balance, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-gray-400 py-4 text-center italic text-sm">No assets recorded.</p>
                        @endforelse
                    </div>

                    <div
                        class="mt-4 flex justify-between items-center p-3 bg-gray-50 rounded-lg border-2 border-primary/20">
                        <span class="font-bold uppercase text-primary">Total Assets</span>
                        <span class="text-xl font-black text-primary border-b-4 border-double border-primary/40 pb-1">
                            {{ number_format($totalAssets, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right Side: Liabilities & Equity --}}
            <div class="space-y-10">
                {{-- Liabilities --}}
                <div>
                    <h4
                        class="text-lg font-bold uppercase bg-danger/10 p-3 rounded-lg flex justify-between items-center text-danger">
                        <span>Liabilities</span>
                        <span class="text-sm font-normal">Amount</span>
                    </h4>

                    <div class="mt-4 space-y-2 px-2">
                        @forelse($data['liability'] as $liability)
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col">
                                    <span
                                        class="font-medium text-gray-700 uppercase text-sm tracking-tight">{{ $liability->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $liability->code }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ number_format($liability->balance, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-gray-400 py-4 text-center italic text-sm">No liabilities recorded.</p>
                        @endforelse
                    </div>

                    <div
                        class="mt-4 flex justify-between items-center p-3 bg-gray-50 rounded-lg border-b-2 border-danger/20 font-bold">
                        <span class="uppercase text-danger">Total Liabilities</span>
                        <span class="text-lg text-danger">{{ number_format($totalLiabilities, 2) }}</span>
                    </div>
                </div>

                {{-- Equity --}}
                <div>
                    <h4
                        class="text-lg font-bold uppercase bg-success/10 p-3 rounded-lg flex justify-between items-center text-success">
                        <span>Equity</span>
                        <span class="text-sm font-normal">Amount</span>
                    </h4>

                    <div class="mt-4 space-y-2 px-2">
                        @foreach ($data['equity'] as $equity)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div class="flex flex-col">
                                    <span
                                        class="font-medium text-gray-700 uppercase text-sm tracking-tight">{{ $equity->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $equity->code }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ number_format($equity->balance, 2) }}</span>
                            </div>
                        @endforeach

                        {{-- Current Period Profit/Loss --}}
                        <div
                            class="flex justify-between items-center py-3 border-b-2 border-dotted border-success/30 bg-success/5 px-2 rounded mt-2">
                            <span class="font-bold text-success uppercase text-sm">Net Profit / (Loss) <span
                                    class="text-[10px] font-normal lowercase">(Current Period)</span></span>
                            <span class="font-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netProfit, 2) }}
                            </span>
                        </div>
                    </div>

                    <div
                        class="mt-4 flex justify-between items-center p-3 bg-gray-50 rounded-lg border-b-2 border-success/20 font-bold">
                        <span class="uppercase text-success">Total Equity</span>
                        <span class="text-lg text-success">{{ number_format($totalEquity + $netProfit, 2) }}</span>
                    </div>
                </div>

                {{-- Combined Footer --}}
                <div
                    class="mt-6 flex justify-between items-center p-4 bg-gray-800 text-white rounded-lg shadow-xl shadow-gray-200">
                    <span class="font-bold uppercase tracking-wider">Total Liabilities & Equity</span>
                    <span class="text-2xl font-black border-b-4 border-double border-white/40 pb-1">
                        {{ number_format($totalLiabilities + $totalEquity + $netProfit, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Verification Alert --}}
        @php
            $diff = abs($totalAssets - ($totalLiabilities + $totalEquity + $netProfit));
            $isBalanced = $diff < 0.01;
        @endphp

        @if (!$isBalanced)
            <div
                class="mt-8 p-4 bg-danger/10 border border-danger/20 rounded-lg flex items-center gap-3 text-danger no-print">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div class="font-bold">
                    Balance Warning: The sheet is out of balance by {{ number_format($diff, 2) }}.
                    Please ensure all journal entries are balanced.
                </div>
            </div>
        @else
            <div
                class="mt-8 p-4 bg-success/5 border border-success/10 rounded-lg flex items-center justify-center gap-3 text-success/60 text-xs italic no-print">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="h-4 w-4">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Statement accurately balanced
            </div>
        @endif
    </div>

    <style>
        @media print {
            /* Hide only the specific UI elements */
            .no-print,
            .horizontal-menu,
            .vertical-menu,
            .sidebar,
            aside,
            button,
            .btn,
            form,
            .theme-customizer,
            .template-customizer,
            .customizer,
            .settings-panel,
            .preview-panel,
            .demo-panel,
            .theme-panel,
            .config-panel,
            .style-switcher,
            .theme-switcher,
            .preview,
            .toast,
            .notification,
            .alert-dismissible,
            .modal-backdrop,
            .loading-overlay,
            .spinner-overlay,
            .page-loader,
            .preloader,
            #template-customizer,
            #theme-customizer,
            #customizer,
            [id*="customizer"],
            [class*="customizer"],
            .fixed.right-0,
            .fixed.left-0,
            .fixed.top-0,
            .fixed.bottom-0:not(.panel),
            .preview-wrap,
            .preview-container,
            .template-preview,
            .theme-preview,
            .design-preview,
            .layout-preview,
            .style-preview,
            .color-picker,
            .font-picker,
            .layout-picker,
            .theme-options,
            .template-options,
            .settings-btn,
            .customizer-btn,
            .theme-btn,
            .preview-btn,
            .config-btn,
            .toggle-btn,
            .floating-btn,
            .fab,
            .fab-btn,
            .help-btn,
            .support-btn,
            .chat-btn,
            .feedback-btn,
            .guide,
            .tour,
            .walkthrough,
            .onboarding,
            .intro,
            .tooltip,
            .popover,
            .dropdown-menu,
            .context-menu,
            .color-scheme,
            .theme-toggle,
            .dark-mode-toggle,
            .mode-switcher,
            .view-switcher,
            .layout-switcher,
            /* Website logo, header, footer */
            .logo,
            .brand,
            .brand-logo,
            .site-logo,
            .app-logo,
            .company-logo,
            .navbar-brand,
            .navbar-header,
            .page-header,
            .main-header,
            .app-header,
            .site-header,
            header,
            .header,
            .footer,
            .main-footer,
            .app-footer,
            .site-footer,
            .page-footer,
            footer,
            .copyright,
            .powered-by,
            .made-with,
            .breadcrumb,
            .breadcrumbs,
            .page-title,
            .section-title,
            .nav-tabs,
            .tab-nav,
            .pagination,
            .page-navigation,
            .back-to-top,
            .scroll-top,
            .scroll-to-top {
                display: none !important;
            }

            /* Show all content */
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Panel styling */
            .panel {
                border: none !important;
                box-shadow: none !important;
                background: white !important;
            }

            /* Grid layout for print */
            .grid {
                display: grid !important;
            }

            /* Ensure 2 columns on larger paper */
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            /* Ensure backgrounds print */
            .bg-primary\/10,
            .bg-danger\/10,
            .bg-success\/10,
            .bg-gray-50,
            .bg-gray-800,
            .bg-success\/5 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Text colors */
            .text-primary, .text-danger, .text-success, .text-gray-900, .text-gray-700 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
@endsection
