<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expenses Report</title>
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

        .filters {
            margin-top: 8px;
            border: 1px solid #263a79;
        }

        .filters th {
            width: 20%;
            background-color: #263a79;
            color: #fff;
            padding: 8px 10px;
            font-weight: normal;
            text-align: left;
            font-size: 12px;
        }

        .filters td {
            padding: 8px 10px;
            font-size: 12px;
            color: #322014;
        }

        .items-table {
            margin-top: 24px;
            border: 1px solid #263a79;
        }

        .items-table th {
            color: #fff;
            padding: 10px 8px;
            font-weight: normal;
            text-align: center;
            font-size: 11px;
        }

        .items-table th:nth-child(odd) {
            background-color: #263a79;
        }

        .items-table th:nth-child(even) {
            background-color: #c09f5a;
        }

        .items-table td {
            padding: 8px;
            border: 1px solid #263a79;
            text-align: center;
            font-size: 10.5px;
            vertical-align: top;
        }

        .items-table .text-left {
            text-align: left;
        }

        .items-table .text-right {
            text-align: right;
        }

        .table-shade {
            background-color: #eaecf2;
        }

        .summary-row td {
            padding: 8px 10px;
            border: 1px solid #263a79;
            font-size: 11px;
        }

        .summary-label {
            text-align: right;
            font-weight: bold;
        }

        .no-data {
            margin-top: 24px;
            border: 1px solid #263a79;
            padding: 28px;
            text-align: center;
            color: #322014;
            background-color: #eaecf2;
        }
    </style>
</head>
@php
    $bgPath = public_path('assets/images/Invoice_Insaf_02.jpg');
    $bgSrc = file_exists($bgPath) ? 'file:///' . str_replace('\\', '/', $bgPath) : null;
    $filters = [];

    if ($request->get('search')) {
        $filters[] = 'Search: ' . $request->get('search');
    }

    if ($request->get('category')) {
        $filters[] = 'Category: ' . $request->get('category');
    }

    if ($request->get('start_date')) {
        $filters[] = 'From: ' . $request->get('start_date');
    }

    if ($request->get('end_date')) {
        $filters[] = 'To: ' . $request->get('end_date');
    }
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
                            <th>Expense List</th>
                        </tr>
                        <tr>
                            <td>Total Records: {{ $expenses->count() }}</td>
                        </tr>
                        <tr>
                            <td>Total Amount: {{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;" class="report-meta">
                    <p><span class="report-badge">Expense Report</span></p>
                    <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>
                </td>
            </tr>
        </table>

        <table class="filters">
            <tr>
                <th>FILTERS</th>
                <td>{{ count($filters) ? implode(' | ', $filters) : 'All Records' }}</td>
            </tr>
        </table>

        @if ($expenses->count() > 0)
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 7%;">SL NO.</th>
                        <th style="width: 13%;">DATE</th>
                        <th style="width: 28%;" class="text-left">DESCRIPTION</th>
                        <th style="width: 16%;">CATEGORY</th>
                        <th style="width: 13%;">AMOUNT</th>
                        <th style="width: 11%;">METHOD</th>
                        <th style="width: 12%;">RECORDED BY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                        <tr>
                            <td class="table-shade">{{ $loop->index + 1 }}</td>
                            <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td class="text-left">{{ $expense->description }}</td>
                            <td class="table-shade">{{ $expense->chartOfAccount->name ?? 'General' }}</td>
                            <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $expense->payment_method)) ?: '-' }}</td>
                            <td class="table-shade">{{ $expense->creator->name ?? 'System' }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">TOTAL EXPENSES:</td>
                        <td class="table-shade text-right">{{ number_format($totalAmount, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">TOTAL RECORDS:</td>
                        <td class="table-shade text-right">{{ $expenses->count() }}</td>
                        <td colspan="2">ITEMS</td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="no-data">
                No expenses found matching the selected filters.
            </div>
        @endif
    </div>
</body>

</html>
