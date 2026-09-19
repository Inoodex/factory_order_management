<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
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

        .invoice-heading {
            margin-top: 100px;
        }

        .info-box {
            padding: 7px;
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

        .invoice-meta {
            text-align: right;
            vertical-align: top;
        }

        .invoice-meta p {
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .invoice-badge {
            display: inline-block;
            background-color: #263a79;
            color: white;
            padding: 7px 12px;
            line-height: 1.2;
            margin-bottom: 10px;
            font-weight: bold;
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
            vertical-align: top;
        }

        .items-table .text-left {
            text-align: left;
        }

        .items-table .text-right {
            text-align: right;
        }

         .summary-row td {
            padding: 8px 10px;
            border: 1px solid #263a79;
        }

        /*.summary-row td:first-child {
            text-align: right !important;
            font-weight: bold;
        }

        .summary-row td:nth-child(2),
        .summary-row td:nth-child(3),
        .summary-row td:nth-child(4) {
            text-align: right !important;
        }

        .summary-row td:last-child {
            text-align: right !important;
        } */

        .table-shade {
            background-color: #eaecf2;
        }

        .payment-details {
            margin-top: 5px;
            border: 1px solid #263a79;
        }

        .payment-details th {
            width: 22%;
            background-color: #263a79;
            color: #fff;
            padding: 7px;
            font-weight: normal;
            text-align: left;
        }

        .payment-details td {
            padding: 5px;
            color: #322014;
        }
    </style>
</head>
@php
    $bgSrc = get_pdf_bg_path('invoice');
    $currency = currency_symbol('$');
    $invoice = $payment->invoice;
    $invoiceTotal = $invoice ? $invoice->total_amount : $payment->amount;
    $invoiceNo = $invoice?->invoice_number ?? ($payment->receipt_number ?: '#' . $payment->id);
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        <table class="invoice-heading">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="info-box">
                        <tr>
                            <th>Invoice / Paid By,</th>
                        </tr>
                        @php
                            $order = $payment->customerOrder ?? ($payment->invoice?->customerOrder);
                        @endphp
                        <tr>
                            <td>{{ $order?->customer?->name ?? 'Direct Customer' }}</td>
                        </tr>
                        @if($order?->customer?->phone)
                        <tr>
                            <td>{{ $order->customer->phone }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                <td style="width: 50%;" class="invoice-meta">
                    <p><span class="invoice-badge">Invoice No: {{ $invoiceNo }}</span></p>
                    <p><strong>Receipt No:</strong> {{ $payment->receipt_number }}</p>
                    <p><strong>Date:</strong> {{ optional($payment->payment_date)->format('Y-m-d') ?? $payment->created_at->format('Y-m-d') }}</p>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">SL NO.</th>
                    <th style="width: 47%;" class="text-left">DESCRIPTION</th>
                    <th style="width: 15%;">PRICE ({{ $currency }})</th>
                    <th style="width: 12%;">QTY</th>
                    <th style="width: 18%;">TOTAL ({{ $currency }})</th>
                </tr>
            </thead>
            <tbody>
                @if ($invoice && $invoice->items->count())
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="table-shade">{{ $loop->index + 1 }}</td>
                            <td class="text-left">{{ $item->description }}</td>
                            <td class="table-shade text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity ?? '-' }}</td>
                            <td class="table-shade text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="table-shade">1</td>
                        <td class="text-left">{{ $payment->notes ?: ucwords($payment->payment_type) . ' payment' }}</td>
                        <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                        <td>1</td>
                        <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                @endif

                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">SUB TOTAL:</td>
                    <td class="table-shade text-right">{{ number_format($invoiceTotal, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">PAID THIS RECEIPT:</td>
                    <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">TOTAL PAID:</td>
                    <td class="table-shade text-right">{{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">TOTAL DUE:</td>
                    <td class="table-shade text-right">{{ number_format($remainingBalance, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- <div style="padding-top: 10px; font-size: 12px; color: #4361EE;">
            <div style="font-size: 14px; font-weight: bold;">Payment Method: @if ($payment->account) {{ $payment->account->account_type === 'cash' ? 'Office Cash' : 'Bank Transfer' }}@endif</div>
            @if ($payment->account && $payment->account->account_type !== 'cash')
            <div style="margin-top: 7px;">Account Number: {{ $payment->account->account_number ?? 'N/A' }}</div>
            <div>Account Name: INSAF IMMIGRATION</div>
            <div>Bank Name: {{ $payment->account->account_name ?? 'N/A' }}</div>
            @endif
        </div> --}}
    </div>

</body>

</html>
