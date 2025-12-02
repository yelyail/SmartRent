<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($reportType) }} Report - {{ $companyName }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            text-align: center;
        }
        
        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #374151;
            margin: 10px 0;
            text-align: center;
        }
        
        .report-period {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        
        .report-filters {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-item {
            text-align: center;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
        }
        
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin: 10px 0;
        }
        
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }
        
        .table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
        }
        
        .table td {
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f9ff !important;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .currency {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .badge-excellent {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-good {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-needs-attention {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-urgent {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-high {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-medium {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-low {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="report-title">{{ $reportType == 'rental' ? 'Rental Report by Property' : 'Maintenance Report' }}</div>
        <div class="report-period">Period: {{ $startDate }} to {{ $endDate }}</div>
        <div class="report-filters">
            Property: {{ $property }} | Generated: {{ $generatedAt }}
        </div>
    </div>
    
    @if($reportType == 'rental')
        <div class="section">
            <div class="section-title">Rental Overview</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Revenue</div>
                    <div class="summary-value">₱{{ number_format($reportData['summary']['total_revenue'] ?? 0, 2) }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Occupied Units</div>
                    <div class="summary-value">{{ $reportData['summary']['occupied_units'] ?? 0 }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Vacant Units</div>
                    <div class="summary-value">{{ $reportData['summary']['vacant_units'] ?? 0 }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Occupancy Rate</div>
                    <div class="summary-value">{{ number_format($reportData['summary']['overall_occupancy_rate'] ?? 0, 1) }}%</div>
                </div>
            </div>
        </div>
        
        @if(isset($reportData['property_income']) && count($reportData['property_income']) > 0)
        <div class="section">
            <div class="section-title">Rental Income by Property</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th class="text-center">Units</th>
                        <th class="text-center">Occupied</th>
                        <th class="text-center">Vacant</th>
                        <th class="text-center">Occupancy Rate</th>
                        <th class="currency">Rental Income</th>
                        <th class="currency">Avg Rent</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalUnits = 0;
                        $totalOccupied = 0;
                        $totalRevenue = 0;
                    @endphp
                    
                    @foreach($reportData['property_income'] as $property)
                        @php
                            $units = $property['units'] ?? 0;
                            $occupied = $property['occupied'] ?? 0;
                            $vacant = $units - $occupied;
                            $revenue = $property['rental_income'] ?? 0;
                            $occupancyRate = $property['occupancy_rate'] ?? 0;
                            $avgRent = $property['avg_rent'] ?? 0;
                            
                            $totalUnits += $units;
                            $totalOccupied += $occupied;
                            $totalRevenue += $revenue;
                            
                            $statusClass = 'badge-needs-attention';
                            if (($property['status'] ?? 'needs_attention') == 'excellent') {
                                $statusClass = 'badge-excellent';
                            } elseif (($property['status'] ?? 'needs_attention') == 'good') {
                                $statusClass = 'badge-good';
                            }
                        @endphp
                        <tr>
                            <td>{{ $property['name'] ?? 'Unknown Property' }}</td>
                            <td class="text-center">{{ $units }}</td>
                            <td class="text-center">{{ $occupied }}</td>
                            <td class="text-center">{{ $vacant }}</td>
                            <td class="text-center">
                                <span class="status-badge {{ $statusClass }}">
                                    {{ number_format($occupancyRate, 1) }}%
                                </span>
                            </td>
                            <td class="currency">₱{{ number_format($revenue, 2) }}</td>
                            <td class="currency">₱{{ number_format($avgRent, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $totalUnits }}</strong></td>
                        <td class="text-center"><strong>{{ $totalOccupied }}</strong></td>
                        <td class="text-center"><strong>{{ $totalUnits - $totalOccupied }}</strong></td>
                        <td class="text-center">
                            <strong>
                                @php
                                    $overallOccupancy = $totalUnits > 0 ? ($totalOccupied / $totalUnits) * 100 : 0;
                                @endphp
                                {{ number_format($overallOccupancy, 1) }}%
                            </strong>
                        </td>
                        <td class="currency"><strong>₱{{ number_format($totalRevenue, 2) }}</strong></td>
                        <td class="currency">
                            <strong>
                                @php
                                    $overallAvgRent = $totalOccupied > 0 ? $totalRevenue / $totalOccupied : 0;
                                @endphp
                                ₱{{ number_format($overallAvgRent, 2) }}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
    @elseif($reportType == 'maintenance')
        <div class="section">
            <div class="section-title">Maintenance Overview</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Requests</div>
                    <div class="summary-value">{{ $reportData['summary']['total_requests'] ?? 0 }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Open Requests</div>
                    <div class="summary-value">{{ $reportData['summary']['open_requests'] ?? 0 }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Completed</div>
                    <div class="summary-value">{{ $reportData['summary']['completed_requests'] ?? 0 }}</div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Total Cost</div>
                    <div class="summary-value">₱{{ number_format($reportData['summary']['total_cost'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        
        @if(isset($reportData['requests_by_priority']) && count($reportData['requests_by_priority']) > 0)
        <div class="section">
            <div class="section-title">Requests by Priority</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalRequests = $reportData['summary']['total_requests'] ?? 0;
                    @endphp
                    
                    @foreach($reportData['requests_by_priority'] as $priority)
                        @php
                            $percentage = $totalRequests > 0 ? ($priority['count'] / $totalRequests) * 100 : 0;
                            $badgeClass = 'badge-' . $priority['priority'];
                        @endphp
                        <tr>
                            <td>
                                <span class="status-badge {{ $badgeClass }}">
                                    {{ ucfirst($priority['priority']) }}
                                </span>
                            </td>
                            <td>{{ $priority['count'] }}</td>
                            <td>{{ number_format($percentage, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if(isset($reportData['recent_requests']) && count($reportData['recent_requests']) > 0)
        <div class="section">
            <div class="section-title">Recent Maintenance Requests</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Property/Unit</th>
                        <th>Issue</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="currency">Cost</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['recent_requests'] as $request)
                        @php
                            $priorityClass = 'badge-' . ($request['priority'] ?? 'medium');
                            $status = $request['status'] ?? 'pending';
                            $statusClass = 'badge-' . str_replace('_', '-', $status);
                        @endphp
                        <tr>
                            <td>#{{ str_pad($request['id'], 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div>{{ $request['property'] }}</div>
                                <div style="font-size: 10px; color: #6b7280;">{{ $request['unit'] }}</div>
                            </td>
                            <td>{{ $request['type'] }}</td>
                            <td>
                                <span class="status-badge {{ $priorityClass }}">
                                    {{ ucfirst($request['priority'] ?? 'medium') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                            <td class="currency">₱{{ number_format($request['cost'], 2) }}</td>
                            <td>{{ $request['created_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endif
    
    <div class="footer">
        <div>Page {{ $pdf->getDomPDF()->get_canvas()->get_page_number() }} of {{ $pdf->getDomPDF()->get_canvas()->get_page_count() }}</div>
        <div>Report generated by {{ $companyName }}</div>
    </div>
</body>
</html>