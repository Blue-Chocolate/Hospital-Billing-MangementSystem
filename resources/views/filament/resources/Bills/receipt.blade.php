// resources/views/bills/receipt.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>Bill Receipt</title>
</head>
<body>
    <h1>Hospital Bill Receipt</h1>
    <p>Patient: {{ $bill->patient->name }}</p>
    <p>Department: {{ $bill->department->name }}</p>
    <p>Amount: ${{ $bill->amount }}</p>
    <p>Date: {{ $bill->bill_date }}</p>
    <p>Description: {{ $bill->description ?? 'N/A' }}</p>
</body>
</html>