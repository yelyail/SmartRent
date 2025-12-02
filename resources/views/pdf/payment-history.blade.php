<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment History Report - {{ $companyName }}</title>
    <style>
        @page {
            margin: 15px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
        }
        
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            text-align: center;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin: 5px 0;
            text-align: center;
        }
        
        .report-period {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        
        .tenant-info {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9fafb;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            background: #ffffff;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 5px 0;
        }
        
        .summary-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10px;
        }
        
        .table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            vertical-align: top;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f9ff !important;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }
        
        .currency {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: 600;
        }
        
        .badge-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-rent {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-deposit {
            background-color: #f3e8ff;
            color: #7c3aed;
        }
        
        .badge-utility {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-maintenance {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-other {
            background-color: #f1f5f9;
            color: #475569;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .tenant-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .tenant-detail {
            margin-bottom: 3px;
        }
        
        .tenant-label {
            font-weight: 600;
            color: #6b7280;
        }
        
        .tenant-value {
            color: #1f2937;
        }
        
        .property-list {
            margin-top: 10px;
            padding-left: 15px;
        }
        
        .property-item {
            margin-bottom: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="report-title">COMPLETE PAYMENT HISTORY REPORT</div>
        <div class="report-period">
            Period: {{ $dateFrom }} to {{ $dateTo }} | Generated: {{ $generatedAt }}
        </div>
    </div>
    
    <!-- Tenant Information -->
    <div class="tenant-info">
        <h3 style="font-size: 12px; font-weight: bold; color: #374151; margin-bottom: 8px;">Tenant Information</h3>
        <div class="tenant-details">
            <div class="tenant-detail">
                <span class="tenant-label">Tenant Name:</span>
                <span class="tenant-value"> {{ $user->first_name }} {{ $user->last_name }}</span>
            </div>
            <div class="tenant-detail">
                <span class="tenant-label">Email:</span>
                <span class="tenant-value"> {{ $user->email }}</span>
            </div>
            <div class="tenant-detail">
                <span class="tenant-label">Phone:</span>
                <span class="tenant-value"> {{ $user->contact_num ?? 'N/A' }}</span>
            </div>
            <div class="tenant-detail">
                <span class="tenant-label">User ID:</span>
                <span class="tenant-value"> {{ $user->user_id }}</span>
            </div>
        </div>
        
        <!-- Properties Leased -->
        @if($userProperties->count() > 0)
        <div style="margin-top: 10px;">
            <h4 style="font-size: 11px; font-weight: 600; color: #374151; margin-bottom: 5px;">Properties Leased:</h4>
            <div class="property-list">
                @foreach($userProperties as $property)
                <div class="property-item">
                    • {{ $property->property_name }} 
                    @php
                        // Get leases for this property
                        $propertyLeases = $userLeases->filter(function($lease) use ($property) {
                            return $lease->unit && $lease->unit->property_id == $property->property_id;
                        });
                    @endphp
                    @if($propertyLeases->count() > 0)
                        (Leases: 
                        @foreach($propertyLeases as $lease)
                            @if($lease->unit)
                                Unit {{ $lease->unit->unit_num }}{{ !$loop->last ? ', ' : '' }}
                            @endif
                        @endforeach
                        )
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <!-- Summary Statistics -->
    <div class="section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Payments</div>
                <div class="summary-value">{{ $totalPayments }}</div>
                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">All Properties</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Total Amount</div>
                <div class="summary-value">₱{{ number_format($totalAmount, 2) }}</div>
                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">All Time</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">This Month</div>
                <div class="summary-value">₱{{ number_format($thisMonthAmount, 2) }}</div>
                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">{{ now()->format('M Y') }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Pending Bills</div>
                <div class="summary-value">{{ $pendingBillings }}</div>
                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">All Properties</div>
            </div>
        </div>
    </div>
    
    <!-- Payment History Table -->
    <div style="margin-top: 20px;">
        <h3 style="font-size: 12px; font-weight: bold; color: #374151; margin-bottom: 10px;">Complete Payment History</h3>
        @if($payments->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice No.</th>
                    <th>Payment Date</th>
                    <th>Property/Unit</th>
                    <th>Description</th>
                    <th>Transaction Type</th>
                    <th>Payment Method</th>
                    <th>Reference No.</th>
                    <th class="currency">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningTotal = 0;
                @endphp
                
                @foreach($payments as $payment)
                    @php
                        $invoiceNo = 'INV-' . str_pad($payment->payment_id, 4, '0', STR_PAD_LEFT);
                        $description = $payment->billing ? $payment->billing->bill_name : 'Payment - ' . ($payment->transaction_type ?? 'deposit');
                        $transactionType = $payment->transaction_type ?? 'deposit';
                        $status = $payment->billing ? strtolower($payment->billing->status) : 'paid';
                        $amount = $payment->amount_paid;
                        $runningTotal += $amount;
                        
                        // Get property/unit info from lease if available
                        $propertyInfo = 'N/A';
                        if ($payment->billing && $payment->billing->lease && $payment->billing->lease->unit) {
                            $propertyInfo = $payment->billing->lease->unit->property->property_name . 
                                          ' (Unit ' . $payment->billing->lease->unit->unit_num . ')';
                        }
                        
                        // Determine badge classes
                        $statusBadgeClass = 'badge-paid';
                        if ($status === 'pending') $statusBadgeClass = 'badge-pending';
                        if ($status === 'overdue') $statusBadgeClass = 'badge-overdue';
                        
                        $typeBadgeClass = 'badge-other';
                        if (strpos(strtolower($description), 'rent') !== false) $typeBadgeClass = 'badge-rent';
                        elseif (strpos(strtolower($description), 'deposit') !== false) $typeBadgeClass = 'badge-deposit';
                        elseif (strpos(strtolower($description), 'utility') !== false || 
                                strpos(strtolower($description), 'water') !== false || 
                                strpos(strtolower($description), 'electric') !== false) $typeBadgeClass = 'badge-utility';
                        elseif (strpos(strtolower($description), 'maintenance') !== false || 
                                strpos(strtolower($description), 'repair') !== false) $typeBadgeClass = 'badge-maintenance';
                    @endphp
                    <tr>
                        <td>{{ $invoiceNo }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                        <td>{{ $propertyInfo }}</td>
                        <td>{{ $description }}
                            @if($payment->billing && $payment->billing->bill_period)
                                <br><small style="color: #6b7280; font-size: 8px;">Period: {{ $payment->billing->bill_period }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $typeBadgeClass }}">
                                {{ ucfirst($transactionType) }}
                            </span>
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td>{{ $payment->reference_no ?? 'N/A' }}</td>
                        <td class="currency">₱{{ number_format($amount, 2) }}</td>
                        <td>
                            <span class="status-badge {{ $statusBadgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                
                <tr class="total-row">
                    <td colspan="7" style="text-align: right; font-weight: bold;">Total Amount Paid ({{ $dateFrom }} to {{ $dateTo }}):</td>
                    <td class="currency" style="font-weight: bold;">₱{{ number_format($runningTotal, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 40px; color: #6b7280; border: 1px dashed #d1d5db; border-radius: 5px;">
            <i class="fas fa-receipt" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
            <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 5px;">No Payment Records Found</h4>
            <p>No payment history found for the selected period ({{ $dateFrom }} to {{ $dateTo }})</p>
        </div>
        @endif
    </div>
    
    <!-- Monthly Summary -->
    @if(count($monthlyLabels) > 0)
    <div class="page-break" style="margin-top: 30px;">
        <h3 style="font-size: 12px; font-weight: bold; color: #374151; margin-bottom: 15px;">Monthly Payment Summary</h3>
        <table class="table" style="width: 60%; margin: 0 auto;">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="currency">Total Amount</th>
                    <th>Number of Payments</th>
                    <th>Avg. Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments->groupBy(function($payment) {
                    return \Carbon\Carbon::parse($payment->payment_date)->format('M Y');
                }) as $month => $monthPayments)
                @php
                    $monthTotal = $monthPayments->sum('amount_paid');
                    $monthCount = $monthPayments->count();
                    $monthAverage = $monthCount > 0 ? $monthTotal / $monthCount : 0;
                @endphp
                <tr>
                    <td>{{ $month }}</td>
                    <td class="currency">₱{{ number_format($monthTotal, 2) }}</td>
                    <td class="text-center">{{ $monthCount }}</td>
                    <td class="currency">₱{{ number_format($monthAverage, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td><strong>Overall Average</strong></td>
                    <td class="currency"><strong>₱{{ number_format($totalAmount, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $totalPayments }}</strong></td>
                    <td class="currency">
                        <strong>
                            ₱{{ number_format($totalPayments > 0 ? $totalAmount / $totalPayments : 0, 2) }}
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <!-- Payment Distribution -->
        <div style="margin-top: 30px;">
            <h3 style="font-size: 12px; font-weight: bold; color: #374151; margin-bottom: 15px;">Payment Distribution by Type</h3>
            @php
                $paymentTypes = $payments->groupBy(function($payment) {
                    $description = strtolower($payment->billing ? $payment->billing->bill_name : $payment->transaction_type ?? 'deposit');
                    if (strpos($description, 'rent') !== false) return 'rent';
                    if (strpos($description, 'deposit') !== false) return 'deposit';
                    if (strpos($description, 'utility') !== false || strpos($description, 'water') !== false || strpos($description, 'electric') !== false) return 'utility';
                    if (strpos($description, 'maintenance') !== false || strpos($description, 'repair') !== false) return 'maintenance';
                    return 'other';
                });
            @endphp
            
            <table class="table" style="width: 60%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>Payment Type</th>
                        <th>Count</th>
                        <th class="currency">Total Amount</th>
                        <th>Percentage</th>
                        <th>Avg. Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentTypes as $type => $typePayments)
                    @php
                        $typeCount = $typePayments->count();
                        $typeTotal = $typePayments->sum('amount_paid');
                        $percentage = $totalAmount > 0 ? ($typeTotal / $totalAmount) * 100 : 0;
                        $typeAverage = $typeCount > 0 ? $typeTotal / $typeCount : 0;
                    @endphp
                    <tr>
                        <td>{{ ucfirst($type) }}</td>
                        <td class="text-center">{{ $typeCount }}</td>
                        <td class="currency">₱{{ number_format($typeTotal, 2) }}</td>
                        <td class="text-center">{{ number_format($percentage, 1) }}%</td>
                        <td class="currency">₱{{ number_format($typeAverage, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Property-wise Summary -->
        <div style="margin-top: 30px;">
            <h3 style="font-size: 12px; font-weight: bold; color: #374151; margin-bottom: 15px;">Property-wise Payment Summary</h3>
            @php
                $propertyPayments = [];
                foreach ($payments as $payment) {
                    if ($payment->billing && $payment->billing->lease && $payment->billing->lease->unit) {
                        $propertyName = $payment->billing->lease->unit->property->property_name;
                        $unitNum = $payment->billing->lease->unit->unit_num;
                        $key = $propertyName . '|' . $unitNum;
                        
                        if (!isset($propertyPayments[$key])) {
                            $propertyPayments[$key] = [
                                'property' => $propertyName,
                                'unit' => $unitNum,
                                'count' => 0,
                                'total' => 0
                            ];
                        }
                        
                        $propertyPayments[$key]['count']++;
                        $propertyPayments[$key]['total'] += $payment->amount_paid;
                    }
                }
            @endphp
            
            @if(count($propertyPayments) > 0)
            <table class="table" style="width: 80%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Unit</th>
                        <th>Count</th>
                        <th class="currency">Total Amount</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propertyPayments as $key => $propertyData)
                    @php
                        $propertyPercentage = $totalAmount > 0 ? ($propertyData['total'] / $totalAmount) * 100 : 0;
                    @endphp
                    <tr>
                        <td>{{ $propertyData['property'] }}</td>
                        <td>{{ $propertyData['unit'] }}</td>
                        <td class="text-center">{{ $propertyData['count'] }}</td>
                        <td class="currency">₱{{ number_format($propertyData['total'], 2) }}</td>
                        <td class="text-center">{{ number_format($propertyPercentage, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    @endif
    
   <div class="footer">
        <div>This is a computer-generated report. No signature is required.</div>
        <div>Page <span class="page-number"></span> of <span class="page-count"></span></div>
        <div>Report generated by {{ $companyName }}</div>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $size = 9;
            $y = $pdf->get_height() - 24;
            $x = $pdf->get_width() - $fontMetrics->get_text_width($text, $font, $size) - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
            
            // Add footer text
            $footerText = "Report generated by {{ $companyName }} | {{ $generatedAt }}";
            $footerY = $pdf->get_height() - 10;
            $pdf->page_text(20, $footerY, $footerText, $font, $size);
        }
    </script>
</body>
</html>