<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Salary Report - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .bank-details {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Salary Payment Report</h1>
        <p>Month: {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
        <p>Generated on: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Designation</th>
                <th>Basic Salary</th>
                <th>Account Number</th>
                <th>Bank Name</th>
                <th>Bonus</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                {{-- <th>Bank Branch</th>
                <th>Routing Number</th> --}}
                {{-- <th>Status</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $salary)
                <tr>
                    <td>{{ $salary->employee_name }}</td>
                    <td>{{ $salary->user?->roles?->first()?->name ?? 'Custom' }}</td>
                    <td class="text-right">{{ number_format($salary->basic_salary, 2) }}</td>
                    <td>{{ $salary->account_number ?? 'N/A' }}</td>
                    <td>{{ $salary->bank_name ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($salary->bonus, 2) }}</td>
                    <td class="text-right">
                        {{ number_format($salary->tax_deduction + $salary->insurance_deduction + $salary->other_deductions, 2) }}
                    </td>
                    <td class="text-right"><strong>{{ number_format($salary->net_salary, 2) }}</strong></td>

                    {{-- <td>{{ $salary->user->bank_branch ?? 'N/A' }}</td>
                    <td>{{ $salary->user->routing_number ?? 'N/A' }}</td> --}}
                    {{-- <td class="text-center">{{ ucfirst($salary->payment_status) }}</td> --}}
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>{{ number_format($salaries->sum('net_salary'), 2) }}</strong></td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>

    <div class="bank-details">
        <h3>Bank Transfer Instructions</h3>
        <p><strong>Note:</strong> Please use the account details provided above for salary transfers. Ensure all details
            are verified before processing payments.</p>
        <p><strong>Total Employees:</strong> {{ $salaries->count() }}</p>
        <p><strong>Total Amount to Transfer:</strong> {{ number_format($salaries->sum('net_salary'), 2) }}</p>
    </div>
</body>

</html>
