<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; color: #2c5282; }
        .invoice-title { font-size: 20px; margin: 10px 0; }
        .details-section { margin: 20px 0; }
        .detail-row { display: flex; margin-bottom: 8px; }
        .detail-label { font-weight: bold; width: 150px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
        .footer { margin-top: 50px; text-align: center; color: #666; font-size: 10px; }
        .section-title { background-color: #2c5282; color: white; padding: 8px; margin: 15px 0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">SmartRent</div>
        <div>123 Business Avenue, Manila, Philippines</div>
        <div>Phone: (02) 1234-5678 | Email: support@smartrent.com</div>
        <div class="invoice-title">INVOICE</div>
        <div><strong>Invoice No:</strong> {{ $invoice_no }}</div>
        <div><strong>Date:</strong> {{ $invoice_date }}</div>
    </div>

    <div class="details-section">
        <div class="section-title">BILL TO</div>
        <div class="detail-row">
            <div class="detail-label">Tenant Name:</div>
            <div>{{ $user->name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Email:</div>
            <div>{{ $user->email }}</div>
        </div>
        @if($payment->lease && $payment->lease->property)
        <div class="detail-row">
            <div class="detail-label">Property:</div>
            <div>{{ $payment->lease->property->property_name }}</div>
        </div>
        @endif
    </div>

    <div class="details-section">
        <div class="section-title">PAYMENT DETAILS</div>
        <div class="detail-row">
            <div class="detail-label">Payment Date:</div>
            <div>{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Payment Method:</div>
            <div>{{ $payment->payment_method }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Reference No:</div>
            <div>{{ $payment->reference_no }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Transaction Type:</div>
            <div>{{ ucfirst($payment->transaction_type) }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($payment->billing)
                        {{ $payment->billing->bill_name }}
                        @if($payment->billing->bill_period)
                            <br><small>Period: {{ $payment->billing->bill_period }}</small>
                        @endif
                    @else
                        Payment - {{ ucfirst($payment->transaction_type) }}
                    @endif
                </td>
                <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total Amount</strong></td>
                <td><strong>₱{{ number_format($payment->amount_paid, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="details-section">
        <div class="section-title">PAYMENT STATUS</div>
        <div style="text-align: center; padding: 10px; background-color: #c6f6d5; color: #22543d; font-weight: bold;">
            PAYMENT RECEIVED - THANK YOU
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated invoice. No signature is required.</p>
        <p>For any questions regarding this invoice, please contact support@smartrent.com</p>
        <p>Generated on: {{ $generated_date ?? now()->format('F d, Y H:i:s') }}</p>
    </div>
</body>
</html>