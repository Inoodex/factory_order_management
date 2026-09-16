<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: left;">Dated: {{ now()->format('d-M-Y') }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: left;">To</th>
        </tr>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: left;">The Branch Manager,</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: left;">Pubali Bank</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: left;">Panthapath Branch, Dhaka.</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: left;">Subject: Transfer of {{ number_format($salaries->sum('net_salary'), 2) }} to respective CD Account holders.</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4" style="text-align: left;">Dear Sir,</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: left;">You are requested to transfer the following listed amount to Insuf Immigration.'s employees respective CD Accounts as Salary for month of {{ $month }} from our A/C.# 3781901011402 maintained with your Branch.</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">SL</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Name</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Amount</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Savings Account</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salaries as $index => $salary)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: left;">{{ $salary->employee_name }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ number_format($salary->net_salary, 2) }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $salary->account_number ?? 'N/A' }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid #000000; text-align: right;">Total</td>
            <td style="font-weight: bold; border: 1px solid #000000; text-align: right;">{{ number_format($salaries->sum('net_salary'), 2) }}</td>
            <td style="border: 1px solid #000000;"></td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="4" style="font-weight: bold; text-align: left;">In Words : {{ $amountInWords }} only</td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="4" style="text-align: left;">Thanking You</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left;">With Best Regards,</td>
        </tr>
    </tbody>
</table>
