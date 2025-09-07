<!DOCTYPE html>
<html>
<head>
    <title>Monthly Bill Report - {{ now()->format('F Y') }}</title>
</head>
<body>
    <h1>Monthly Bill Report - {{ now()->format('F Y') }}</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Department</th>
                <th>Amount</th>
                <th>Insurance</th>
                <th>Coverage</th>
                <th>Status</th>
                <th>Date</th>
                <th>Anomaly</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td>{{ $bill->patient->name }}</td>
                    <td>{{ $bill->department->name }}</td>
                    <td>${{ $bill->amount }}</td>
                    <td>{{ $bill->insurance_provider ?? 'N/A' }}</td>
                    <td>${{ $bill->insurance_coverage }}</td>
                    <td>{{ $bill->payment_status }}</td>
                    <td>{{ $bill->bill_date }}</td>
                    <td>{{ $bill->is_anomaly ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
