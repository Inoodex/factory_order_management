<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expense - #{{ $expense->id }}</title>
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

        .expense-heading {
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

        .expense-meta {
            text-align: right;
            vertical-align: top;
        }

        .expense-meta p {
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .expense-badge {
            display: inline-block;
            background-color: #263a79;
            color: white;
            padding: 10px 12px;
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

        .summary-label {
            text-align: right;
            font-weight: bold;
        }

        .table-shade {
            background-color: #eaecf2;
        }

        .notes {
            margin-top: 24px;
            border: 1px solid #263a79;
        }

        .notes th {
            width: 20%;
            background-color: #263a79;
            color: #fff;
            padding: 10px;
            font-weight: normal;
            text-align: left;
        }

        .notes td {
            padding: 10px;
            color: #322014;
        }
    </style>
</head>
@php
    $bgPath = public_path('assets/images/Invoice_Insaf_02.jpg');
    $bgSrc = file_exists($bgPath) ? 'file:///' . str_replace('\\', '/', $bgPath) : null;
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        <table class="expense-heading">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="info-box">
                        <tr>
                            <th>Expense Details</th>
                        </tr>
                        <tr>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        <tr>
                            <td>{{ $expense->chartOfAccount->name ?? 'General' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;" class="expense-meta">
                    <p><span class="expense-badge">Expense No: #{{ $expense->id }}</span></p>
                    <p><strong>Date:</strong> {{ $expense->expense_date->format('Y-m-d') }}</p>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">SL NO.</th>
                    <th style="width: 42%;" class="text-left">PURPOSE</th>
                    <th style="width: 18%;">CATEGORY</th>
                    <th style="width: 14%;">METHOD</th>
                    <th style="width: 18%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="table-shade">1</td>
                    <td class="text-left">{{ $expense->description }}</td>
                    <td class="table-shade">{{ $expense->chartOfAccount->name ?? 'General' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $expense->payment_method)) ?: '-' }}</td>
                    <td class="table-shade text-right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label">SUB TOTAL:</td>
                    <td class="table-shade text-right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label">TOTAL PAID:</td>
                    <td class="table-shade text-right">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label">TOTAL DUE:</td>
                    <td class="table-shade text-right">{{ number_format(0, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="notes">
            <tr>
                <th>ACCOUNT</th>
                <td>{{ $expense->account->account_name ?? 'Cash' }}</td>
            </tr>
            <tr>
                <th>RECORDED BY</th>
                <td>{{ $expense->creator->name ?? 'System' }}</td>
            </tr>
            <tr>
                <th>CREATED</th>
                <td>{{ $expense->created_at->format('Y-m-d') }}</td>
            </tr>
            @if ($expense->notes)
                <tr>
                    <th>NOTES</th>
                    <td>{{ $expense->notes }}</td>
                </tr>
            @endif
        </table>
    </div>
</body>

</html>
