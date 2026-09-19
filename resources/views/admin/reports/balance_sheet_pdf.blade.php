<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #333;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .pdf-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
            background-position: top left;
            background-repeat: no-repeat;
            background-size: 210mm 297mm;
        }

        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 70px 35px 0 35px;
            box-sizing: border-box;
        }

        .report-heading {
            margin-top: 100px;
        }

        .info-box {
            padding: 15px;
        }

        .info-box th {
            text-align: left;
            font-size: 18px;
            color: #263a79;
        }

        .info-box td {
            font-size: 13px;
            line-height: 1.6;
        }

        .report-meta {
            text-align: right;
            vertical-align: top;
        }

        .report-meta p {
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .report-badge {
            display: inline-block;
            background-color: #263a79;
            color: white;
            padding: 10px 12px;
            line-height: 1.2;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .section-table {
            margin-top: 22px;
            border: 1px solid #263a79;
        }

        .section-table th {
            color: #fff;
            padding: 9px 8px;
            font-weight: normal;
            text-align: center;
            font-size: 10px;
        }

        .section-table th:nth-child(odd) {
            background-color: #263a79;
        }

        .section-table th:nth-child(even) {
            background-color: #c09f5a;
        }

        .section-table td {
            padding: 7px 8px;
            border: 1px solid #263a79;
            font-size: 9.5px;
            vertical-align: top;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .table-shade {
            background-color: #eaecf2;
        }

        .summary-row td {
            padding: 8px;
            border: 1px solid #263a79;
            font-weight: bold;
        }

        .summary-label {
            text-align: right;
            font-weight: bold;
        }

        .two-col {
            margin-top: 20px;
        }

        .two-col-cell {
            width: 50%;
            vertical-align: top;
        }

        .left-pad {
            padding-right: 8px;
        }

        .right-pad {
            padding-left: 8px;
        }

        .balance-note {
            margin-top: 20px;
            border: 1px solid #263a79;
        }

        .balance-note th {
            width: 24%;
            background-color: #263a79;
            color: #fff;
            padding: 10px;
            font-weight: normal;
            text-align: left;
        }

        .balance-note td {
            padding: 10px;
            color: #322014;
        }
    </style>
</head>
@php
    $bgSrc = get_pdf_bg_path('report');
    $liabilitiesAndEquity = $totalLiabilities + $totalEquity + $netProfit;
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        <table class="report-heading">
            <tr>
                <td style="vertical-align: top; text-align: center;">
                    <table class="info-box">
                        <tr>
                            <th style="text-align: center;">Balance Sheet</th>
                        </tr>
                        {{-- <p><strong>As of:</strong> {{ \Carbon\Carbon::parse($asOfDate)->format('Y-m-d') }}</p> --}}
                        <tr>
                            <td>Statement of Financial Position</td>
                        </tr>
                    </table>
                </td>
                {{-- <td style="width: 50%;" class="report-meta">
                    <p><span class="report-badge">Balance Sheet</span></p>
                    <p><strong>As of:</strong> {{ \Carbon\Carbon::parse($asOfDate)->format('Y-m-d') }}</p>
                </td> --}}
            </tr>
        </table>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 34%;" class="text-left">SUMMARY</th>
                    <th style="width: 22%;">TOTAL ASSETS</th>
                    <th style="width: 22%;">TOTAL LIABILITIES</th>
                    <th style="width: 22%;">TOTAL EQUITY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left table-shade">As of {{ \Carbon\Carbon::parse($asOfDate)->format('d F Y') }}</td>
                    <td class="text-right">{{ number_format($totalAssets, 2) }}</td>
                    <td class="text-right table-shade">{{ number_format($totalLiabilities, 2) }}</td>
                    <td class="text-right">{{ number_format($totalEquity + $netProfit, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="two-col">
            <tr>
                <td class="two-col-cell left-pad">
                    <table class="section-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 66%;" class="text-left">ASSETS</th>
                                <th style="width: 34%;">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['asset'] as $asset)
                                <tr>
                                    <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ $asset->name }}
                                        @if ($asset->code)
                                            <br><span style="font-size: 8px; color: #666;">{{ $asset->code }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ number_format($asset->balance, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-left table-shade">No assets recorded.</td>
                                    <td class="text-right table-shade">-</td>
                                </tr>
                            @endforelse
                            <tr class="summary-row">
                                <td class="summary-label">TOTAL ASSETS:</td>
                                <td class="text-right table-shade">{{ number_format($totalAssets, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="two-col-cell right-pad">
                    <table class="section-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 66%;" class="text-left">LIABILITIES</th>
                                <th style="width: 34%;">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['liability'] as $liability)
                                <tr>
                                    <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ $liability->name }}
                                        @if ($liability->code)
                                            <br><span
                                                style="font-size: 8px; color: #666;">{{ $liability->code }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ number_format($liability->balance, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-left table-shade">No liabilities recorded.</td>
                                    <td class="text-right table-shade">-</td>
                                </tr>
                            @endforelse
                            <tr class="summary-row">
                                <td class="summary-label">TOTAL LIABILITIES:</td>
                                <td class="text-right table-shade">{{ number_format($totalLiabilities, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="section-table" style="margin-top: 16px;">
                        <thead>
                            <tr>
                                <th style="width: 66%;" class="text-left">EQUITY</th>
                                <th style="width: 34%;">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['equity'] as $equity)
                                <tr>
                                    <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ $equity->name }}
                                        @if ($equity->code)
                                            <br><span style="font-size: 8px; color: #666;">{{ $equity->code }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                                        {{ number_format($equity->balance, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-left table-shade">No equity accounts recorded.</td>
                                    <td class="text-right table-shade">-</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td class="text-left">Net Profit / (Loss)</td>
                                <td class="text-right">{{ number_format($netProfit, 2) }}</td>
                            </tr>
                            <tr class="summary-row">
                                <td class="summary-label">TOTAL EQUITY:</td>
                                <td class="text-right table-shade">{{ number_format($totalEquity + $netProfit, 2) }}
                                </td>
                            </tr>
                            <tr class="summary-row">
                                <td class="summary-label">TOTAL LIABILITIES &amp; EQUITY:</td>
                                <td class="text-right table-shade">{{ number_format($liabilitiesAndEquity, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table class="balance-note">
            <tr>
                <th>STATUS</th>
                <td>
                    @if ($isBalanced)
                        Statement is balanced - Total Assets = Total Liabilities &amp; Equity
                    @else
                        Warning: Balance sheet is out of balance by {{ number_format($diff, 2) }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
