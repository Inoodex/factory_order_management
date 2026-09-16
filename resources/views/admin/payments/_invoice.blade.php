<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Invoice - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .agency-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-info {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .agency-name {
            font-size: 24px;
            font-weight: bold;
            color: #4361ee;
            margin: 0;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            color: #343a40;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #f8f9fa;
            padding: 8px 12px;
            font-weight: bold;
            border-left: 5px solid #4361ee;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #666;
            width: 30%;
        }

        .value {
            color: #333;
        }

        .amount-section {
            background-color: #f1f3f9;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            margin-top: 30px;
        }

        .total-label {
            font-size: 18px;
            font-weight: bold;
            color: #666;
            margin-right: 20px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #4361ee;
        }

        .status-completed {
            color: #00ab55;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            color: #e2a03f;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            color: #4361ee;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="agency-info">
            <h1 class="agency-name">{{ $settings['app_name'] ?? 'Educational Consultancy' }}</h1>
            <p>
                {{ $settings['address'] ?? '' }}<br>
                Phone: {{ $settings['contact_phone'] ?? '' }}<br>
                Email: {{ $settings['contact_email'] ?? '' }}
            </p>
        </div>
        <div class="invoice-info">
            <h2 class="invoice-title">Invoice</h2>
            <p>
                <strong>Receipt No:</strong> {{ $payment->receipt_number }}<br>
                <strong>Date:</strong> {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}<br>
                <strong>Status:</strong> <span
                    class="status-{{ $payment->payment_status }}">{{ $payment->payment_status }}</span>
            </p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Student Details</div>
        <table class="details-table">
            <tr>
                <td class="label">Name:</td>
                <td class="value">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Passport:</td>
                <td class="value">{{ $payment->student->passport_number }}</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td class="value">{{ $payment->student->phone }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Application Info</div>
        <table class="details-table">
            <tr>
                <td class="label">Application ID:</td>
                <td class="value">{{ $payment->application->application_id ?? 'N/A' }}</td>
            </tr>
            {{-- <tr>
                <td class="label">University:</td>
                <td class="value">{{ $payment->application->university->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Course:</td>
                <td class="value">{{ $payment->application->course->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Country:</td>
                <td class="value">{{ $payment->application->university->country->name ?? 'N/A' }}</td>
            </tr> --}}
        </table>
    </div>

    <div class="section">
        <div class="section-title">Payment info</div>
        <table class="details-table">
            <tr>
                <td class="label">Payment Type:</td>
                <td class="value" style="text-transform: capitalize;">{{ $payment->payment_type }} Payment</td>
            </tr>
            <tr>
                <td class="label">Collected By:</td>
                <td class="value">{{ $payment->collector->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="amount-section">
        <span class="total-label">Amount Paid:</span>
        <span class="total-amount">{{ number_format($payment->amount, 2) }}</span>
    </div>

    <div class="footer">
        <p class="thank-you">Thank you for your business!</p>
        <p>This is an electronically generated document. No signature required.</p>
        <p>&copy; {{ date('Y') }} {{ $settings['app_name'] ?? 'Educational Consultancy' }}</p>
    </div>
</body>

</html>
