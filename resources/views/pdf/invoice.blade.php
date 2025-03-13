<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h1>Invoice #{{ $invoice->invoice_number }}</h1>
<p><strong>Patient:</strong> {{ $invoice->patient->name }}</p>
<p><strong>Appointment ID:</strong> {{ $invoice->appointment->id }}</p>
<p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
<p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
<p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
<p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
<p><strong>Notes:</strong> {{ $invoice->notes }}</p>
</body>
</html>
