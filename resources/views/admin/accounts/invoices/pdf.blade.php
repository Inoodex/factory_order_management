<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #333;
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

        .invoice-title {
            padding: 20px;
            font-weight: bold;
            color: #263a79;
            margin: 0;
        }

        .info-box {
            padding: 15px;
        }

        .info-box th {
            text-align: left;
            font-size: 18px;
            color: #263a79;
        }

        .info-label {
            width: 80px;
            font-weight: bold;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta p {
            font-size: 14px;
        }

        .items-table {
            margin-top: 30px;
            border: 1px solid #263a79;
        }

        .items-table th {
            color: #fff;
            padding: 12px 10px;
            font-weight: normal;
            text-align: center;
        }

        .items-table th:nth-child(odd) {
            background-color: #263a79;
        }

        .items-table th:nth-child(even) {
            background-color: #c09f5a;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #263a79;
            text-align: center;
        }

        .items-table .text-left {
            text-align: left;
        }

        .summary-row td {
            padding: 8px 10px;
            border: 1px solid #263a79;
        }

        .summary-label {
            text-align: right;
            font-weight: bold;
        }

        .status-note {
            font-size: 16px;
            margin-top: 20px;
            color: #322014;
        }

        .table-shade {
            background-color: #eaecf2;
        }
    </style>
</head>
@php
    $bgSrc = get_pdf_bg_path('invoice');
    $currency = currency_symbol('$');
    $totalPaid = $invoice->payments ? $invoice->payments->where('payment_status', 'completed')->sum('amount') : 0;
    $totalDue = max(0, $invoice->total_amount - $totalPaid);
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        {{-- Invoice Header Info --}}
        <table style='margin-top: 80px;'>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="info-box">
                        <tr>
                            <th colspan="2">Invoice To,</th>
                        </tr>
                        <tr>
                            <td><strong>{{ $invoice->customerOrder?->customer?->name ?? 'Direct Customer' }}</strong></td>
                        </tr>
                        @if($invoice->customerOrder?->customer?->company_name)
                        <tr>
                            <td>{{ $invoice->customerOrder->customer->company_name }}</td>
                        </tr>
                        @endif
                        @if($invoice->customerOrder?->customer?->phone)
                        <tr>
                            <td>{{ $invoice->customerOrder->customer->phone }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;" class="invoice-meta">
                    <p style="margin: 0;"><strong
                            style="display: inline-block; background-color: #263a79; color: white; padding: 10px 10px; line-height: 1.2; margin-bottom: 10px;">Invoice
                            No:
                            {{ $invoice->invoice_number }}</strong></p>
                    <p style="margin: 10px 0;"><strong>Date:</strong>
                        {{ optional($invoice->date)->format('Y-m-d') }}</p>
                </td>
            </tr>
        </table>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%; ">SL NO.</th>
                    <th style="width: 47%;" class="text-left">DESCRIPTION</th>
                    <th style="width: 15%;">PRICE ({{ $currency }})</th>
                    <th style="width: 12%;">QTY</th>
                    <th style="width: 18%;">TOTAL ({{ $currency }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td class="table-shade">{{ $loop->index + 1 }}</td>
                        <td class="text-left">{{ $item->description }}</td>
                        <td class="table-shade">{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="table-shade">{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach

                {{-- Summary Rows --}}
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right;">SUB TOTAL:</td>
                    <td class="table-shade">{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right;">TOTAL PAID:</td>
                    <td class="table-shade">{{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right;">TOTAL DUE:</td>
                    <td class="table-shade">{{ number_format($totalDue, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if ($invoice->notes)
            <div class="status-note" style="margin-top: 25px; padding: 10px 15px; border-left: 4px solid #263a79; background-color: #eaecf2;">
                <strong style="color: #263a79;">Note:</strong><br/>
                @foreach (array_filter(explode("\n", $invoice->notes)) as $i => $line)
                    {{ $i + 1 }}. {{ $line }}<br/>
                @endforeach
            </div>
        @endif

    </div>

</body>

</html>
