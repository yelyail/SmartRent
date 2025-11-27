<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .company-name { font-size: 20px; font-weight: bold; color: #2c5282; }
        .report-title { font-size: 16px; margin: 8px 0; }
        .report-period { font-size: 12px; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .table th { background-color: #2c5282; color: white; font-weight: bold; }
        .summary { margin: 15px 0; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #2c5282; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 9px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">SmartRent</div>
        <div class="report-title">PAYMENT HISTORY REPORT</div>
        <div class="report-period">
            @if($date_from && $date_to)
                Period: {{ \Carbon\Carbon::parse($date_from)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($date_to)->format('M d, Y') }}
            @else
                Period: All Payments
            @endif
        </div>
        <div>Generated on: {{ $generated_date }}</div>
    </div>

    <div class="summary">
        <strong>Report Summary:</strong>
        Total Payments: {{ $payments->count() }} | 
        Total Amount: ₱{{ number_format($payments->sum('amount_paid'), 2) }} |
        Generated for: {{ $user->name }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice No</th>
                <th>Description</th>
                <th>Type</th>
                <th class="text-right">Amount</th>
                <th>Method</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                <td>INV-{{ $payment->payment_id }}-{{ date('Y') }}</td>
                <td>
                    @if($payment->billing)
                        {{ $payment->billing->bill_name }}
                    @else
                        Payment - {{ ucfirst($payment->transaction_type) }}
                    @endif
                </td>
                <td>{{ ucfirst($payment->transaction_type) }}</td>
                <td class="text-right">₱{{ number_format($payment->amount_paid, 2) }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ $payment->reference_no }}</td>
            </tr>
            @endforeach
            @if($payments->count() > 0)
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="4" class="text-right">Total:</td>
                <td class="text-right">₱{{ number_format($payments->sum('amount_paid'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($payments->count() === 0)
    <div style="text-align: center; padding: 20px; color: #666;">
        No payments found for the selected period.
    </div>
    @endif

    <div class="footer">
        <p>This is a computer-generated report. For official documents, please contact the administration.</p>
        <p>SmartRent - Property Management System</p>
    </div>
</body>
</html>