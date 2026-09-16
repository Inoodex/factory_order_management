<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Journal Entries Report</title>
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
    $bgPath = public_path('assets/images/Invoice_Insaf_02.jpg');
    $bgSrc = file_exists($bgPath) ? 'file:///' . str_replace('\\', '/', $bgPath) : null;
    $grandTotal = $entries->sum('total_amount');
    $entryCount = $entries->count();
    $startDate = request('start_date');
    $endDate = request('end_date');
    $periodLabel = ($startDate || $endDate)
        ? (trim(($startDate ?: 'Start') . ' to ' . ($endDate ?: 'End')))
        : 'All periods';
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
                            <th style="text-align: center;">Journal Entries Report</th>
                        </tr>
                        <tr>
                            <td>Statement of Journal Vouchers</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 34%;" class="text-left">SUMMARY</th>
                    <th style="width: 22%;">TOTAL ENTRIES</th>
                    <th style="width: 22%;">GRAND TOTAL</th>
                    <th style="width: 22%;">GENERATED ON</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left table-shade">{{ $periodLabel }}</td>
                    <td class="text-right">{{ number_format($entryCount) }}</td>
                    <td class="text-right table-shade">{{ number_format($grandTotal, 2) }}</td>
                    <td class="text-right">{{ now()->format('d F Y') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 10%;">DATE</th>
                    <th style="width: 14%;">REFERENCE</th>
                    <th style="width: 14%;">PERIOD</th>
                    <th style="width: 25%;" class="text-left">STUDENT / APPLICATION</th>
                    <th style="width: 12%;">POSTED BY</th>
                    <th style="width: 10%;">STATUS</th>
                    <th style="width: 15%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entries as $entry)
                    <tr>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $entry->date->format('d M Y') }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $entry->reference_number }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $entry->period->name ?? '-' }}</td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                            @if ($entry->application)
                                {{ $entry->application->student->first_name }} {{ $entry->application->student->last_name }}
                                <br><span
                                    style="font-size: 8px; color: #666;">({{ $entry->application->application_id }})</span>
                            @else
                                General Entry
                            @endif
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $entry->creator->name ?? '-' }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ ucfirst($entry->status) }}</td>
                        <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ number_format($entry->total_amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-left table-shade">No journal entries found matching the criteria.</td>
                    </tr>
                @endforelse
                <tr class="summary-row">
                    <td colspan="6" class="summary-label">GRAND TOTAL:</td>
                    <td class="text-right table-shade">{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="balance-note">
            <tr>
                <th>STATUS</th>
                <td>
                    @if ($entryCount > 0)
                        Report generated successfully with {{ number_format($entryCount) }} journal entries.
                    @else
                        No data found for the selected filters.
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
