<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Property;
use App\Models\Leases;
use App\Models\MaintenanceRequest;
use App\Models\Billing;
use App\Models\User;
use App\Models\PropertyUnits;
use App\Models\Payment;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $reportType = $request->get('report_type', 'rental');
        $dateRange = $request->get('date_range', 'this_month');
        $property = $request->get('property', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Set date range based on selection
        $dateRangeData = $this->getDateRange($dateRange, $startDate, $endDate);
        $startDate = $dateRangeData['start'];
        $endDate = $dateRangeData['end'];
        
        // Get all properties for dropdown
        $properties = Property::all();
        
        // Generate reports based on type
        $reports = $this->generateReports($reportType, $startDate, $endDate, $property, $properties);
        
        return view('admins.reports', array_merge($reports, [
            'reportType' => $reportType,
            'dateRange' => $dateRange,
            'property' => $property,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRangeData' => $dateRangeData,
            'properties' => $properties,
        ]));
    }
    
    private function getDateRange($range, $customStart = null, $customEnd = null)
    {
        $now = Carbon::now();
        
        switch ($range) {
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'display' => $now->format('F Y')
                ];
                
            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                return [
                    'start' => $lastMonth->copy()->startOfMonth(),
                    'end' => $lastMonth->copy()->endOfMonth(),
                    'display' => $lastMonth->format('F Y')
                ];
                
            case 'this_quarter':
                $quarter = ceil($now->month / 3);
                $startMonth = ($quarter - 1) * 3 + 1;
                return [
                    'start' => Carbon::create($now->year, $startMonth, 1)->startOfMonth(),
                    'end' => Carbon::create($now->year, $startMonth + 2, 1)->endOfMonth(),
                    'display' => 'Q' . $quarter . ' ' . $now->year
                ];
                
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear(),
                    'display' => $now->year
                ];
                
            case 'last_year':
                $lastYear = $now->copy()->subYear();
                return [
                    'start' => $lastYear->copy()->startOfYear(),
                    'end' => $lastYear->copy()->endOfYear(),
                    'display' => $lastYear->year
                ];
                
            case 'custom':
                if ($customStart && $customEnd) {
                    return [
                        'start' => Carbon::parse($customStart)->startOfDay(),
                        'end' => Carbon::parse($customEnd)->endOfDay(),
                        'display' => Carbon::parse($customStart)->format('M j, Y') . ' - ' . Carbon::parse($customEnd)->format('M j, Y')
                    ];
                }
                // Fallback to current month if custom dates not provided
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'display' => $now->format('F Y')
                ];
                
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'display' => $now->format('F Y')
                ];
        }
    }
    
    private function generateReports($reportType, $startDate, $endDate, $property, $allProperties)
    {
        // Only generate rental and maintenance reports
        $reports = [
            'rentalReport' => $this->getRentalReport($startDate, $endDate, $property, $allProperties),
            'maintenanceReport' => $this->getMaintenanceReport($startDate, $endDate, $property, $allProperties),
        ];
        
        return $reports;
    }
    
    private function getRentalReport($startDate, $endDate, $propertyFilter, $allProperties)
    {
        $totalRevenue = 0;
        $totalUnits = 0;
        $totalOccupied = 0;
        $propertyIncome = [];
        
        // Filter properties if needed
        $properties = $allProperties;
        if ($propertyFilter !== 'all') {
            $properties = $properties->where('slug', $propertyFilter);
        }
        
        foreach ($properties as $property) {
            // Get units for this property
            $units = PropertyUnits::where('property_id', $property->property_id)->get();
            $unitsCount = $units->count();
            $totalUnits += $unitsCount;
            
            // Count occupied units (units with active leases)
            $occupiedUnits = 0;
            $propertyRevenue = 0;
            
            foreach ($units as $unit) {
                // Check for active lease
                $activeLease = Leases::where('unit_id', $unit->unit_id)
                    ->where('status', 'active')
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate)
                    ->first();
                
                if ($activeLease) {
                    $occupiedUnits++;
                    
                    // Get rental payments for the date range
                    $rentalPayments = Billing::where('lease_id', $activeLease->lease_id)
                        ->where('status', 'paid')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    
                    foreach ($rentalPayments as $payment) {
                        $propertyRevenue += $payment->amount;
                    }
                }
            }
            
            $totalOccupied += $occupiedUnits;
            $totalRevenue += $propertyRevenue;
            
            $occupancyRate = $unitsCount > 0 ? ($occupiedUnits / $unitsCount) * 100 : 0;
            $avgRent = $occupiedUnits > 0 ? $propertyRevenue / $occupiedUnits : 0;
            
            $propertyIncome[] = [
                'property_id' => $property->property_id,
                'name' => $property->property_name,
                'units' => $unitsCount,
                'occupied' => $occupiedUnits,
                'rental_income' => $propertyRevenue,
                'occupancy_rate' => $occupancyRate,
                'avg_rent' => $avgRent,
                'status' => $occupancyRate >= 90 ? 'excellent' : ($occupancyRate >= 80 ? 'good' : 'needs_attention')
            ];
        }
        
        $overallOccupancyRate = $totalUnits > 0 ? ($totalOccupied / $totalUnits) * 100 : 0;
        $overallAvgRent = $totalOccupied > 0 ? $totalRevenue / $totalOccupied : 0;
        
        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'display' => $startDate->format('M j, Y') . ' to ' . $endDate->format('M j, Y'),
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_units' => $totalUnits,
                'occupied_units' => $totalOccupied,
                'vacant_units' => $totalUnits - $totalOccupied,
                'overall_occupancy_rate' => $overallOccupancyRate,
                'overall_avg_rent' => $overallAvgRent,
            ],
            'property_income' => $propertyIncome,
            'filters' => [
                'property' => $propertyFilter,
                'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            ]
        ];
    }
    
    private function getMaintenanceReport($startDate, $endDate, $propertyFilter, $allProperties)
    {
        $totalRequests = 0;
        $openRequests = 0;
        $completedRequests = 0;
        $maintenanceCost = 0;
        $priorityCounts = [
            'urgent' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
        ];
        
        $recentRequests = [];
        
        // Base query for maintenance requests
        $maintenanceQuery = MaintenanceRequest::with(['unit.property', 'assignedStaff'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        // Filter by property if needed
        if ($propertyFilter !== 'all') {
            $property = $allProperties->where('slug', $propertyFilter)->first();
            if ($property) {
                $unitIds = PropertyUnits::where('property_id', $property->property_id)
                    ->pluck('unit_id')
                    ->toArray();
                
                $maintenanceQuery->whereIn('unit_id', $unitIds);
            }
        }
        
        $maintenanceRequests = $maintenanceQuery->get();
        $totalRequests = $maintenanceRequests->count();
        
        foreach ($maintenanceRequests as $request) {
            // Count by status
            if ($request->status === 'pending' || $request->status === 'in_progress') {
                $openRequests++;
            } elseif ($request->status === 'completed') {
                $completedRequests++;
            }
            
            // Count by priority
            if (isset($priorityCounts[$request->priority])) {
                $priorityCounts[$request->priority]++;
            }
            
            // Calculate cost if billing exists
            if ($request->billing) {
                $maintenanceCost += $request->billing->amount;
            }
            
            // Add to recent requests (limited to 10)
            if (count($recentRequests) < 10) {
                $recentRequests[] = [
                    'id' => $request->request_id,
                    'property' => $request->unit->property->property_name ?? 'N/A',
                    'unit' => 'Unit ' . ($request->unit->unit_num ?? 'N/A'),
                    'type' => $request->title,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'cost' => $request->billing->amount ?? 0,
                    'created_at' => $request->created_at->format('M j, Y'),
                ];
            }
        }
        
        // Calculate average resolution time (in days)
        $avgResolutionTime = 0;
        $completedRequestsWithDates = MaintenanceRequest::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        if ($completedRequestsWithDates->count() > 0) {
            $totalDays = 0;
            foreach ($completedRequestsWithDates as $request) {
                $days = $request->created_at->diffInDays($request->completed_at);
                $totalDays += $days;
            }
            $avgResolutionTime = round($totalDays / $completedRequestsWithDates->count(), 1);
        }
        
        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'display' => $startDate->format('M j, Y') . ' to ' . $endDate->format('M j, Y'),
            ],
            'summary' => [
                'total_requests' => $totalRequests,
                'open_requests' => $openRequests,
                'completed_requests' => $completedRequests,
                'avg_resolution_time' => $avgResolutionTime . ' days',
                'total_cost' => $maintenanceCost,
            ],
            'requests_by_priority' => [
                ['priority' => 'urgent', 'count' => $priorityCounts['urgent'], 'color' => 'bg-red-100 text-red-800'],
                ['priority' => 'high', 'count' => $priorityCounts['high'], 'color' => 'bg-orange-100 text-orange-800'],
                ['priority' => 'medium', 'count' => $priorityCounts['medium'], 'color' => 'bg-yellow-100 text-yellow-800'],
                ['priority' => 'low', 'count' => $priorityCounts['low'], 'color' => 'bg-green-100 text-green-800'],
            ],
            'recent_requests' => $recentRequests,
            'filters' => [
                'property' => $propertyFilter,
                'date_range' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            ]
        ];
    }
    
    public function exportPDF(Request $request)
    {
        $reportType = $request->get('report_type', 'rental');
        $dateRange = $request->get('date_range', 'this_month');
        $property = $request->get('property', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Set date range based on selection
        $dateRangeData = $this->getDateRange($dateRange, $startDate, $endDate);
        $startDate = $dateRangeData['start'];
        $endDate = $dateRangeData['end'];
        
        // Get all properties
        $properties = Property::all();
        
        // Generate report data
        $reportData = [];
        switch ($reportType) {
            case 'rental':
                $reportData = $this->getRentalReport($startDate, $endDate, $property, $properties);
                break;
            case 'maintenance':
                $reportData = $this->getMaintenanceReport($startDate, $endDate, $property, $properties);
                break;
        }
        
        // Prepare data for PDF
        $data = [
            'reportType' => $reportType,
            'reportData' => $reportData,
            'startDate' => $startDate->format('M j, Y'),
            'endDate' => $endDate->format('M j, Y'),
            'property' => $property === 'all' ? 'All Properties' : ucfirst(str_replace('-', ' ', $property)),
            'generatedAt' => now()->format('M j, Y H:i:s'),
            'companyName' => 'SmartRent',
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.report', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Download PDF
        $filename = $reportType . '_report_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }
    public function exportPaymentHistoryPDF(Request $request)
    {
        $user = Auth::user();
        
        // Get date filters
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Get payments through the user's leases
        $paymentsQuery = Payment::whereHas('lease', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->with([
                'billing', 
                'lease.unit.property',
                'lease.user'
            ]);
        
        // Apply date filters
        if ($dateFrom) {
            $paymentsQuery->whereDate('payment_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $paymentsQuery->whereDate('payment_date', '<=', $dateTo);
        }
        
        // Order by payment date
        $paymentsQuery->orderBy('payment_date', 'desc');
        
        $payments = $paymentsQuery->get();
        
        // Get all user leases for property listing
        $userLeases = Leases::where('user_id', $user->user_id)
            ->with('unit.property')
            ->get();
        
        // Get all properties from user's leases
        $userProperties = collect();
        foreach ($userLeases as $lease) {
            if ($lease->unit && $lease->unit->property) {
                $userProperties->push($lease->unit->property);
            }
        }
        $userProperties = $userProperties->unique('property_id');
        
        // Get total statistics
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount_paid');
        
        // Calculate this month's payments
        $now = Carbon::now();
        $thisMonthPayments = $payments->filter(function ($payment) use ($now) {
            $paymentDate = Carbon::parse($payment->payment_date);
            return $paymentDate->month == $now->month && 
                $paymentDate->year == $now->year;
        });
        $thisMonthAmount = $thisMonthPayments->sum('amount_paid');
        
        // Get pending billings from all user leases
        $leaseIds = $userLeases->pluck('lease_id')->toArray();
        $pendingBillings = Billing::whereIn('lease_id', $leaseIds)
            ->where('status', 'pending')
            ->count();
        
        // Group by month for chart data
        $groupedByMonth = $payments->groupBy(function ($payment) {
            return Carbon::parse($payment->payment_date)->format('M Y');
        });
        
        $monthlyLabels = [];
        $monthlyAmounts = [];
        
        foreach ($groupedByMonth as $month => $monthPayments) {
            $monthlyLabels[] = $month;
            $monthlyAmounts[] = $monthPayments->sum('amount_paid');
        }
        
        // Prepare data for PDF
        $data = [
            'user' => $user,
            'payments' => $payments,
            'userLeases' => $userLeases,
            'userProperties' => $userProperties,
            'dateFrom' => $dateFrom ? Carbon::parse($dateFrom)->format('M d, Y') : 'All Time',
            'dateTo' => $dateTo ? Carbon::parse($dateTo)->format('M d, Y') : 'Present',
            'totalPayments' => $totalPayments,
            'totalAmount' => $totalAmount,
            'thisMonthAmount' => $thisMonthAmount,
            'pendingBillings' => $pendingBillings,
            'monthlyLabels' => $monthlyLabels,
            'monthlyAmounts' => $monthlyAmounts,
            'generatedAt' => now()->format('M d, Y H:i:s'),
            'companyName' => 'SmartRent',
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.payment-history', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Stream the PDF to browser
        $filename = 'payment-history-' . ($dateFrom ?: 'all') . '-to-' . ($dateTo ?: 'present') . '.pdf';
        return $pdf->stream($filename);
    }
}