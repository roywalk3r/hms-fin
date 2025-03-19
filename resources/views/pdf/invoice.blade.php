<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Invoice</title>
    <style>
        :root {
            --primary: #1e88e5;
            --primary-light: #e3f2fd;
            --secondary: #4caf50;
            --text: #333333;
            --text-light: #757575;
            --background: #ffffff;
            --border: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background-color: #f9f9f9;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: var(--background);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-header {
            background-color: var(--primary);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hospital-logo {
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-title {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 16px;
            opacity: 0.9;
        }

        .invoice-body {
            padding: 20px;
        }

        .patient-info {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .patient-info h2 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            font-size: 16px;
        }

        .invoice-details h2 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: var(--text);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .payment-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .payment-info h2 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-pending {
            background-color: #fff8e1;
            color: #f57f17;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #c62828;
        }

        .invoice-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #f5f5f5;
            color: var(--text-light);
            font-size: 14px;
            border-top: 1px solid var(--border);
        }

        @media print {
            body {
                background-color: white;
            }

            .invoice-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .invoice-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .hospital-logo {
                margin-bottom: 15px;
            }

            .invoice-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="invoice-header flex flex-1 justify-between">
        <div class="hospital-logo">
            Memorial Hospital
        </div>
        <div>
            <div class="invoice-title">Invoice</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
        </div>
    </div>

    <div class="invoice-body">
        <div class="patient-info">
            <h2>Patient Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Patient Name</span>
                    <span class="info-value">{{ $invoice->patient->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Appointment ID</span>
                    <span class="info-value">{{ $invoice->appointment->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date of Service</span>
                    <span class="info-value">{{ $invoice->appointment->date ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="invoice-details">
            <h2>Invoice Details</h2>
            <table>
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Medical Services</td>
                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total Amount</strong></td>
                    <td><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="payment-info">
            <h2>Payment Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                            <span class="status-badge status-{{ strtolower($invoice->status) }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Due Date</span>
                    <span class="info-value">{{ $invoice->due_date }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">{{ $invoice->payment_method }}</span>
                </div>
            </div>

            <div class="info-item" style="margin-top: 15px;">
                <span class="info-label">Notes</span>
                <p class="info-value">{{ $invoice->notes }}</p>
            </div>
        </div>
    </div>

    <div class="invoice-footer">
        <p>Thank you for choosing Memorial Hospital for your healthcare needs.</p>
        <p>If you have any questions regarding this invoice, please contact our billing department at (555) 123-4567.</p>
    </div>
</div>
</body>
</html>

