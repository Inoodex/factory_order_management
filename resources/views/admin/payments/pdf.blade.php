<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payments Report</title>
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
    $bgSrc = get_pdf_bg_path('report');
    $grandTotal = $payments->sum('amount');
    $paymentCount = $payments->count();
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
                            <th style="text-align: center;">Payments Report</th>
                        </tr>
                        <tr>
                            <td>Statement of Order Payments</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 34%;" class="text-left">SUMMARY</th>
                    <th style="width: 22%;">TOTAL PAYMENTS</th>
                    <th style="width: 22%;">GRAND TOTAL</th>
                    <th style="width: 22%;">GENERATED ON</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left table-shade">{{ $periodLabel }}</td>
                    <td class="text-right">{{ number_format($paymentCount) }}</td>
                    <td class="text-right table-shade">{{ number_format($grandTotal, 2) }}</td>
                    <td class="text-right">{{ now()->format('d F Y') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="section-table">
            <thead>
                <tr>
                    <th style="width: 10%;">DATE</th>
                    <th style="width: 14%;">RECEIPT NO</th>
                    <th style="width: 12%;">TYPE</th>
                    <th style="width: 27%;" class="text-left">CUSTOMER / ORDER</th>
                    <th style="width: 12%;">STATUS</th>
                    <th style="width: 10%;">COLLECTED BY</th>
                    <th style="width: 15%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    @php
                        $order = $payment->customerOrder ?? ($payment->invoice?->customerOrder);
                        $customerName = $order?->customer?->name ?? 'N/A';
                        $orderIdentifier = $order?->order_no ?? ($payment->invoice?->invoice_number ?? 'N/A');
                    @endphp
                    <tr>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ optional($payment->payment_date)->format('d M Y') }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $payment->receipt_number }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ ucfirst($payment->payment_type) }}</td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ $customerName }}
                            <br><span style="font-size: 8px; color: #666;">(Order: {{ $orderIdentifier }})</span>
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ ucfirst($payment->payment_status) }}</td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $payment->collector->name ?? '-' }}</td>
                        <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-left table-shade">No payments found matching criteria.</td>
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
                    @if ($paymentCount > 0)
                        Report generated successfully with {{ number_format($paymentCount) }} payment records.
                    @else
                        No data found for selected filters.
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
