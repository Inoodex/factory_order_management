<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Invoice - #{{ $expense->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            font-size: 12px;
            line-height: 1.3;
        }

        .container {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .company-info {
            float: left;
            width: 45%;
        }

        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            margin-right: 5px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            clear: both;
        }

        .section {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .section h3 {
            font-size: 14px;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 5px 10px;
            margin: 0 0 10px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-label {
            font-weight: bold;
            width: 40%;
        }

        .detail-value {
            width: 60%;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .totals {
            width: 100%;
            margin-top: 10px;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 5px 0;
            text-align: right;
        }

        .totals td:first-child {
            text-align: left;
            padding-right: 10px;
        }

        .signatures {
            width: 100%;
            margin-top: 150px;
            overflow: hidden;
        }

        .signature {
            float: left;
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 40px;
            margin-right: 5%;
        }

        .signature:last-child {
            margin-right: 0;
            float: right;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            margin-top: 20px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="header clearfix">
            <div class="company-info">
                <div class="company-name">
                    <span class="logo">i</span> {{ $settings['app_name'] ?? 'Educational Consultancy' }}
                </div>
                Phone: {{ $settings['contact_phone'] ?? '' }}<br>
                Email: {{ $settings['contact_email'] ?? '' }}
            </div>
            <div class="invoice-info">
                Date: {{ $expense->expense_date->format('Y-m-d') }}<br>
                Expense ID: #{{ $expense->id }}<br>
                Address: {{ $settings['address'] ?? '' }}
            </div>
        </div>

        <div class="invoice-title">EXPENSE DETAILS</div>

        <!-- Basic Information -->
        <div class="section">
            <h3>Basic Information</h3>
            <div class="detail-row">
                <div class="detail-label">Description:</div>
                <div class="detail-value">{{ $expense->description }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Category:</div>
                <div class="detail-value">{{ $expense->category ?? 'General' }}</div>
            </div>
        </div>

        <!-- Payment / Account Info -->
        <div class="section">
            <h3>Payment Details</h3>
            <div class="detail-row">
                <div class="detail-label">Amount:</div>
                <div class="detail-value">{{ number_format($expense->amount, 2) }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value">{{ $expense->payment_method ?? 'Cash' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Account:</div>
                <div class="detail-value">{{ optional($expense->office_account)->account_name ?? 'Cash' }}</div>
            </div>
        </div>

        <!-- Notes -->
        @if($expense->notes)
            <div class="section">
                <h3>Notes</h3>
                <div class="detail-row">
                    <div class="detail-value">{{ $expense->notes }}</div>
                </div>
            </div>
        @endif

        <!-- Record Info -->
        <div class="section">
            <h3>Record Information</h3>
            <div class="detail-row">
                <div class="detail-label">Recorded By:</div>
                <div class="detail-value">{{ $expense->creator->name ?? 'System' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Created Date:</div>
                <div class="detail-value">{{ $expense->created_at->format('Y-m-d') }}</div>
            </div>
            @if($expense->updated_at !== $expense->created_at)
                <div class="detail-row">
                    <div class="detail-label">Last Updated:</div>
                    <div class="detail-value">{{ $expense->updated_at->format('Y-m-d') }}</div>
                </div>
            @endif
        </div>

        <!-- Signatures -->
        <div class="signatures clearfix">
            <div class="signature">Prepared By</div>
            <div class="signature">Authorized Signature</div>
        </div>

        <!-- Page number -->
        <div class="page-number">
            Page 1/1
        </div>

    </div>
</body>

</html>