<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Financial Report - {{ $reportDate }}</title>
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

        .items-table {
            margin-top: 22px;
            border: 1px solid #263a79;
        }

        .items-table th {
            color: #fff;
            padding: 9px 8px;
            font-weight: normal;
            text-align: center;
            font-size: 10px;
        }

        .items-table th:nth-child(odd) {
            background-color: #263a79;
        }

        .items-table th:nth-child(even) {
            background-color: #c09f5a;
        }

        .items-table td {
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

        .text-center {
            text-align: center;
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

        .section-title {
            margin-top: 22px;
            color: #263a79;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .totals-table {
            margin-top: 24px;
            border: 1px solid #263a79;
        }

        .totals-table th {
            width: 24%;
            background-color: #263a79;
            color: #fff;
            padding: 10px;
            font-weight: normal;
            text-align: left;
        }

        .totals-table td {
            padding: 10px;
            color: #322014;
        }
    </style>
</head>
@php
    $bgSrc = get_pdf_bg_path('report');
    $companyName = $settings['company_name'] ?? ($settings['site_name'] ?? config('app.name'));
    $totalInc = $payments->sum('amount');
    $totalExp = $expenses->sum('amount');
    $totalTrans = $transfers->sum(function ($transfer) {
        return $transfer->items->sum('debit') ?: $transfer->items->sum('credit');
    });
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        <table class="report-heading">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="info-box">
                        <tr>
                            <th>Financial Summary</th>
                        </tr>
                        <tr>
                            <td>{{ $companyName }}</td>
                        </tr>
                        <tr>
                            <td>Income, expenses, transfers and net movement</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;" class="report-meta">
                    <p><span class="report-badge">Financial Report</span></p>
                    <p><strong>Period:</strong> {{ $reportDate }}</p>
                    <p><strong>Generated:</strong> {{ now()->format('d M, Y h:i A') }}</p>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 34%;" class="text-left">SUMMARY</th>
                    <th style="width: 22%;">INCOME</th>
                    <th style="width: 22%;">EXPENSES</th>
                    <th style="width: 22%;">NET MOVEMENT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left table-shade">{{ $reportDate }}</td>
                    <td class="text-right">{{ number_format($totalInc, 2) }}</td>
                    <td class="text-right table-shade">{{ number_format($totalExp, 2) }}</td>
                    <td class="text-right">{{ number_format($totalInc - $totalExp, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Income Breakdown (Order Payments)</div>
        <table class="items-table" style="margin-top: 8px;">
            <thead>
                <tr>
                    <th style="width: 16%;">DATE</th>
                    <th style="width: 40%;" class="text-left">CUSTOMER / ORDER</th>
                    <th style="width: 24%;">RECEIPT NO.</th>
                    <th style="width: 20%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    @php
                        $order = $payment->customerOrder ?? ($payment->invoice?->customerOrder);
                    @endphp
                    <tr>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
                        </td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                            <strong>{{ $order?->customer?->name ?? 'Direct Payment' }}</strong>
                            @if($order)
                                <br><span style="font-size: 8px; color: #666;">(Order: {{ $order->order_no }} | Style: {{ $order->style_no }})</span>
                            @endif
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $payment->receipt_number ?? '-' }}</td>
                        <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ number_format($payment->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center table-shade">No income recorded.</td>
                    </tr>
                @endforelse
                <tr class="summary-row">
                    <td colspan="3" class="summary-label">TOTAL INCOME:</td>
                    <td class="text-right table-shade">{{ number_format($totalInc, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Expenses Breakdown</div>
        <table class="items-table" style="margin-top: 8px;">
            <thead>
                <tr>
                    <th style="width: 14%;">DATE</th>
                    <th style="width: 34%;" class="text-left">CATEGORY / PURPOSE</th>
                    <th style="width: 20%;">PAYMENT METHOD</th>
                    <th style="width: 18%;">RECORDED BY</th>
                    <th style="width: 14%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}
                        </td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}" style="text-transform: uppercase;">
                            {{ $expense->chartOfAccount->name ?? ($expense->category ?? '-') }}
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}" style="text-transform: uppercase;">
                            {{ $expense->payment_method ?? '-' }}
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $expense->creator->name ?? '-' }}</td>
                        <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ number_format($expense->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center table-shade">No expenses recorded.</td>
                    </tr>
                @endforelse
                <tr class="summary-row">
                    <td colspan="4" class="summary-label">TOTAL EXPENSES:</td>
                    <td class="text-right table-shade">{{ number_format($totalExp, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Office Transfers History</div>
        <table class="items-table" style="margin-top: 8px;">
            <thead>
                <tr>
                    <th style="width: 16%;">DATE</th>
                    <th style="width: 26%;" class="text-left">FROM ACCOUNT</th>
                    <th style="width: 26%;" class="text-left">TO ACCOUNT</th>
                    <th style="width: 16%;">REFERENCE</th>
                    <th style="width: 16%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    @php
                        $debitItem = $transfer->items->firstWhere('debit', '>', 0);
                        $creditItem = $transfer->items->firstWhere('credit', '>', 0);
                        $transferAmount = $transfer->items->sum('debit') ?: $transfer->items->sum('credit');
                    @endphp
                    <tr>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">
                            {{ \Carbon\Carbon::parse($transfer->date)->format('d M, Y') }}
                        </td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ $creditItem->chartOfAccount->name ?? 'N/A' }}
                        </td>
                        <td class="text-left {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ $debitItem->chartOfAccount->name ?? 'N/A' }}
                        </td>
                        <td class="{{ $loop->odd ? 'table-shade' : '' }}">{{ $transfer->reference_number ?? '-' }}
                        </td>
                        <td class="text-right {{ $loop->odd ? 'table-shade' : '' }}">
                            {{ number_format($transferAmount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center table-shade">No transfers recorded.</td>
                    </tr>
                @endforelse
                <tr class="summary-row">
                    <td colspan="4" class="summary-label">TOTAL TRANSFERS:</td>
                    <td class="text-right table-shade">{{ number_format($totalTrans, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <th>NET FINANCIAL MOVEMENT</th>
                <td class="text-right">{{ number_format($totalInc - $totalExp, 2) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
