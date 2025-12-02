<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KycDocument;
use App\Enums\UserRole;
use App\Models\Property;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\SmartDevice;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\PropertyUnits;
use App\Models\Leases;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    

    public function analytics()
    {
        $currentMonth = now()->format('Y-m');
        $previousMonth = now()->subMonth()->format('Y-m');
        $currentYear = now()->year;
        
        // Get all leases for current month - FIXED LOGIC
        $currentMonthLeases = Leases::where(function($query) use ($currentYear) {
                $query->whereYear('start_date', '=', $currentYear)
                    ->whereMonth('start_date', '=', now()->month);
            })
            ->orWhere(function($query) use ($currentYear) {
                $query->whereYear('end_date', '=', $currentYear)
                    ->whereMonth('end_date', '=', now()->month);
            })
            ->get();

        // Get all leases for previous month - FIXED LOGIC
        $previousMonthLeases = Leases::where(function($query) {
                $query->whereYear('start_date', '=', now()->subMonth()->year)
                    ->whereMonth('start_date', '=', now()->subMonth()->month);
            })
            ->orWhere(function($query) {
                $query->whereYear('end_date', '=', now()->subMonth()->year)
                    ->whereMonth('end_date', '=', now()->subMonth()->month);
            })
            ->get();

        // Calculate Monthly Revenue
        $currentMonthRevenue = Payment::whereYear('payment_date', '=', $currentYear)
            ->whereMonth('payment_date', '=', now()->month)
            ->sum('amount_paid');

        $previousMonthRevenue = Payment::whereYear('payment_date', '=', now()->subMonth()->year)
            ->whereMonth('payment_date', '=', now()->subMonth()->month)
            ->sum('amount_paid');

        $revenueChangePercentage = $previousMonthRevenue > 0 
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : 100;

        // Calculate Occupancy Rate
        $totalUnits = PropertyUnits::count();
        $occupiedUnits = Leases::where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();

        $previousMonthOccupied = Leases::where('status', 'active')
            ->where('end_date', '>=', now()->subMonth())
            ->where('start_date', '<=', now()->subMonth()->endOfMonth())
            ->count();

        $avgOccupancy = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
        $previousMonthOccupancyRate = $totalUnits > 0 ? ($previousMonthOccupied / $totalUnits) * 100 : 0;
        $occupancyChange = $previousMonthOccupancyRate > 0 
            ? (($avgOccupancy - $previousMonthOccupancyRate) / $previousMonthOccupancyRate) * 100 
            : 0;

        // Calculate Tenant Turnover (annual rate)
        $yearStart = now()->startOfYear();
        $yearEnd = now()->endOfYear();
        
        $totalTenants = Leases::where('status', 'active')->count();
        $departedTenants = Leases::where('status', 'terminated')
            ->whereBetween('end_date', [$yearStart, $yearEnd])
            ->count();
        
        $tenantTurnoverRate = $totalTenants > 0 ? ($departedTenants / $totalTenants) * 100 : 0;
        
        $previousYearTenants = Leases::where('status', 'active')
            ->whereYear('start_date', '=', $currentYear - 1)
            ->count();
            
        $previousYearDeparted = Leases::where('status', 'terminated')
            ->whereYear('end_date', '=', $currentYear - 1)
            ->count();
            
        $previousTurnoverRate = $previousYearTenants > 0 
            ? ($previousYearDeparted / $previousYearTenants) * 100 
            : 0;
            
        $turnoverChange = $previousTurnoverRate > 0 
            ? $previousTurnoverRate - $tenantTurnoverRate 
            : 0;

        // Calculate Net Profit (Revenue - Expenses)
        $currentMonthExpenses = Billing::whereHas('maintenanceRequest', function($query) use ($currentYear) {
                $query->whereYear('completed_at', '=', $currentYear)
                    ->whereMonth('completed_at', '=', now()->month);
            })
            ->sum('amount');

        $previousMonthExpenses = Billing::whereHas('maintenanceRequest', function($query) {
                $query->whereYear('completed_at', '=', now()->subMonth()->year)
                    ->whereMonth('completed_at', '=', now()->subMonth()->month);
            })
            ->sum('amount');

        $netProfit = $currentMonthRevenue - $currentMonthExpenses;
        $previousMonthProfit = $previousMonthRevenue - $previousMonthExpenses;
        
        $profitChangePercentage = $previousMonthProfit > 0 
            ? (($netProfit - $previousMonthProfit) / $previousMonthProfit) * 100 
            : 100;

        // Key Metrics Data
        $metrics = [
            'monthly_revenue' => [
                'current' => $currentMonthRevenue,
                'previous' => $previousMonthRevenue,
                'change_percentage' => round($revenueChangePercentage, 1),
                'change_direction' => $revenueChangePercentage >= 0 ? 'up' : 'down'
            ],
            'avg_occupancy' => [
                'current' => round($avgOccupancy, 1),
                'previous' => round($previousMonthOccupancyRate, 1),
                'change_percentage' => round($occupancyChange, 1),
                'change_direction' => $occupancyChange >= 0 ? 'up' : 'down'
            ],
            'tenant_turnover' => [
                'current' => round($tenantTurnoverRate, 1),
                'previous' => round($previousTurnoverRate, 1),
                'change_percentage' => round(abs($turnoverChange), 1),
                'change_direction' => $turnoverChange >= 0 ? 'down' : 'up' // Lower turnover is better
            ],
            'net_profit' => [
                'current' => $netProfit,
                'previous' => $previousMonthProfit,
                'change_percentage' => round($profitChangePercentage, 1),
                'change_direction' => $profitChangePercentage >= 0 ? 'up' : 'down'
            ]
        ];

        // Revenue vs Expenses Chart Data (last 6 months)
        $revenueExpensesData = [
            'labels' => [],
            'revenue' => [],
            'expenses' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('Y-m');
            $monthName = $date->format('M');
            
            $revenueExpensesData['labels'][] = $monthName;
            
            // Get revenue for this month
            $monthRevenue = Payment::whereYear('payment_date', '=', $date->year)
                ->whereMonth('payment_date', '=', $date->month)
                ->sum('amount_paid');
            $revenueExpensesData['revenue'][] = $monthRevenue;
            
            // Get expenses for this month
            $monthExpenses = Billing::whereHas('maintenanceRequest', function($query) use ($date) {
                    $query->whereYear('completed_at', '=', $date->year)
                        ->whereMonth('completed_at', '=', $date->month);
                })
                ->sum('amount');
            $revenueExpensesData['expenses'][] = $monthExpenses;
        }

        // Property Occupancy Data
         $properties = Property::with(['units', 'leases' => function($query) {
            $query->where('status', 'active')
                ->where('end_date', '>=', now());
        }])->get()->map(function($property) {
            $totalUnits = $property->units->count();
            $occupiedUnits = $property->leases->count();
            $occupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
            
            return [
                'name' => $property->property_name,
                'occupancy_rate' => round($occupancyRate, 1),
                'change' => 0,
                'change_direction' => 'neutral',
                'total_units' => $totalUnits,
                'occupied_units' => $occupiedUnits,
                'vacant_units' => $totalUnits - $occupiedUnits
            ];
        }); 

        // Maintenance Analytics Data
        $currentMonthRequests = MaintenanceRequest::whereYear('requested_at', '=', $currentYear)
            ->whereMonth('requested_at', '=', now()->month)
            ->get();

        $previousMonthRequests = MaintenanceRequest::whereYear('requested_at', '=', now()->subMonth()->year)
            ->whereMonth('requested_at', '=', now()->subMonth()->month)
            ->get();

        $currentMonthCompleted = $currentMonthRequests->where('status', 'completed');
        $previousMonthCompleted = $previousMonthRequests->where('status', 'completed');

        $currentMonthCosts = Billing::whereIn('request_id', $currentMonthCompleted->pluck('request_id'))
            ->sum('amount');

        $previousMonthCosts = Billing::whereIn('request_id', $previousMonthCompleted->pluck('request_id'))
            ->sum('amount');

        $avgCostCurrent = $currentMonthCompleted->count() > 0 
            ? $currentMonthCosts / $currentMonthCompleted->count() 
            : 0;

        $avgCostPrevious = $previousMonthCompleted->count() > 0 
            ? $previousMonthCosts / $previousMonthCompleted->count() 
            : 0;

        // Calculate average resolution time
        $completedRequests = MaintenanceRequest::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('requested_at')
            ->whereYear('completed_at', '=', $currentYear)
            ->whereMonth('completed_at', '=', now()->month)
            ->get();

        $totalDays = 0;
        foreach ($completedRequests as $request) {
            if ($request->completed_at && $request->requested_at) {
                $totalDays += $request->completed_at->diffInDays($request->requested_at);
            }
        }
        $avgResolutionDays = $completedRequests->count() > 0 
            ? $totalDays / $completedRequests->count() 
            : 0;

        $maintenance = [
            'requests_this_month' => [
                'current' => $currentMonthRequests->count(),
                'previous' => $previousMonthRequests->count(),
                'change' => $currentMonthRequests->count() - $previousMonthRequests->count()
            ],
            'average_cost' => [
                'current' => round($avgCostCurrent, 2),
                'previous' => round($avgCostPrevious, 2),
                'change' => round($avgCostCurrent - $avgCostPrevious, 2)
            ],
            'total_this_month' => [
                'current' => $currentMonthCosts,
                'previous' => $previousMonthCosts,
                'change' => $currentMonthCosts - $previousMonthCosts
            ],
            'avg_resolution_days' => [
                'current' => round($avgResolutionDays, 1),
                'previous' => 0, // You'd need to calculate this for previous month
                'change' => 0
            ]
        ];

        // Financial Reports Data
        $propertyIncome = [];
        foreach (Property::with(['units.leases.payments' => function($query) use ($currentYear) {
            $query->whereYear('payment_date', '=', $currentYear)
                ->whereMonth('payment_date', '=', now()->month);
        }])->get() as $property) {
            $totalIncome = 0;
            $totalUnits = $property->units->count();
            $occupiedUnitsProp = 0;
            
            foreach ($property->units as $unit) {
                foreach ($unit->leases as $lease) {
                    $totalIncome += $lease->payments->sum('amount_paid');
                    if ($lease->status === 'active') {
                        $occupiedUnitsProp++;
                    }
                }
            }
            
            $avgRent = $occupiedUnitsProp > 0 ? $totalIncome / $occupiedUnitsProp : 0;
            $occupancyRate = $totalUnits > 0 ? ($occupiedUnitsProp / $totalUnits) * 100 : 0;
            
            $propertyIncome[] = [   
                'name' => $property->property_name,
                'units' => $totalUnits,
                'occupied' => $occupiedUnitsProp,
                'rental_income' => $totalIncome,
                'avg_rent' => round($avgRent, 2),
                'occupancy_rate' => round($occupancyRate, 1),
                'status' => $occupancyRate >= 90 ? 'excellent' : ($occupancyRate >= 80 ? 'good' : 'needs_attention')
            ];
        }

        // Expense Breakdown - ADDED MORE CATEGORIES
        $expenseCategories = [
            'Maintenance & Repairs' => Billing::whereHas('maintenanceRequest', function($query) use ($currentYear) {
                $query->whereYear('completed_at', '=', $currentYear)
                    ->whereMonth('completed_at', '=', now()->month);
            })->sum('amount'),
            'Utilities' => 0, // You'll need to add logic for utilities
            'Property Taxes' => 0, // You'll need to add logic for taxes
            'Insurance' => 0, // You'll need to add logic for insurance
            'Management Fees' => 0, // You'll need to add logic for management fees
            'Other Expenses' => 0 // You'll need to add logic for other expenses
        ];

        $totalExpenses = array_sum($expenseCategories);
        
        $expenseBreakdown = [];
        foreach ($expenseCategories as $category => $amount) {
            $percentage = $totalExpenses > 0 ? ($amount / $totalExpenses) * 100 : 0;
            $expenseBreakdown[] = [
                'category' => $category,
                'amount' => $amount,
                'percentage' => round($percentage, 1),
                'change' => 0 // You'd need to calculate change from previous month
            ];
        }

        $financialReport = [
            'period' => [
                'start' => now()->startOfMonth()->format('Y-m-d'),
                'end' => now()->endOfMonth()->format('Y-m-d'),
                'display' => now()->startOfMonth()->format('F j, Y') . ' - ' . now()->endOfMonth()->format('F j, Y')
            ],
            'summary' => [
                'total_revenue' => $currentMonthRevenue,
                'total_expenses' => $currentMonthExpenses,
                'net_profit' => $netProfit,
                'profit_margin' => $currentMonthRevenue > 0 ? ($netProfit / $currentMonthRevenue) * 100 : 0,
                'revenue_change' => round($revenueChangePercentage, 1),
                'expenses_change' => $previousMonthExpenses > 0 
                    ? (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100 
                    : 100,
                'profit_change' => round($profitChangePercentage, 1)
            ],
            'property_income' => $propertyIncome,
            'expense_breakdown' => $expenseBreakdown,
            'chart_data' => [
                'labels' => array_keys($expenseCategories),
                'data' => array_values($expenseCategories),
                'colors' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#6366F1']
            ]
        ];

        // Occupancy Report Data - MOVED OUT OF THE LOOP
         $occupancyReport = [
            'period' => now()->format('F Y'),
            'overall_occupancy' => round($avgOccupancy, 1),
            'vacant_units' => $totalUnits - $occupiedUnits,
            'total_units' => $totalUnits,
            'properties' => $properties, // Already a collection of arrays
            'trend' => [
                'monthly' => [],
                'labels' => []
            ]
        ];

        // Maintenance Report Data
        $maintenanceRequestsByType = MaintenanceRequest::selectRaw('priority, count(*) as count')
            ->whereYear('requested_at', '=', $currentYear)
            ->whereMonth('requested_at', '=', now()->month)
            ->groupBy('priority')
            ->get()
            ->map(function($item) use ($currentMonthRequests) {
                return [
                    'type' => ucfirst($item->priority),
                    'count' => $item->count,
                    'percentage' => $currentMonthRequests->count() > 0 
                        ? ($item->count / $currentMonthRequests->count()) * 100 
                        : 0
                ];
            });

        $maintenanceReport = [
            'period' => now()->format('F Y'),
            'total_requests' => $currentMonthRequests->count(),
            'open_requests' => $currentMonthRequests->where('status', '!=', 'completed')->count(),
            'completed_requests' => $currentMonthCompleted->count(),
            'avg_response_time' => 'N/A', // You'd need to track response time
            'avg_resolution_time' => round($avgResolutionDays, 1) . ' days',
            'total_cost' => $currentMonthCosts,
            'requests_by_type' => $maintenanceRequestsByType,
            'requests_by_priority' => $maintenanceRequestsByType->map(function($item) {
                $colorMap = [
                    'urgent' => 'bg-red-100 text-red-800',
                    'high' => 'bg-orange-100 text-orange-800',
                    'medium' => 'bg-yellow-100 text-yellow-800',
                    'low' => 'bg-green-100 text-green-800'
                ];
                
                return [
                    'priority' => ucfirst($item['type']),
                    'count' => $item['count'],
                    'color' => $colorMap[strtolower($item['type'])] ?? 'bg-gray-100 text-gray-800'
                ];
            }),
            'recent_requests' => MaintenanceRequest::with(['unit.property', 'assignedStaff'])
                ->whereYear('requested_at', '=', $currentYear)
                ->whereMonth('requested_at', '=', now()->month)
                ->orderBy('requested_at', 'desc')
                ->take(5)
                ->get()
                ->map(function($request) {
                    return [
                        'id' => $request->request_id,
                        'property' => $request->unit->property->property_name ?? 'N/A',
                        'unit' => $request->unit->unit_number ?? 'N/A',
                        'type' => $request->priority,
                        'description' => $request->description,
                        'priority' => $request->priority,
                        'status' => $request->status,
                        'created_at' => $request->requested_at->format('Y-m-d'),
                        'completed_at' => $request->completed_at ? $request->completed_at->format('Y-m-d') : null,
                        'cost' => $request->billing ? $request->billing->amount : 0
                    ];
                })
        ];

        // Tenant Report Data
        $paymentStatus = Payment::whereYear('payment_date', '=', $currentYear)
            ->whereMonth('payment_date', '=', now()->month)
            ->get()
            ->groupBy(function($payment) {
                $dueDate = $payment->billing->due_date ?? null;
                if (!$dueDate) return 'unknown';
                
                return $payment->payment_date <= $dueDate ? 'on_time' : 'late';
            });

        $totalPayments = $paymentStatus->flatten()->count();
        
        $tenantReport = [
            'period' => now()->format('F Y'),
            'total_tenants' => $occupiedUnits,
            'new_tenants' => Leases::where('status', 'active')
                ->whereYear('start_date', '=', $currentYear)
                ->whereMonth('start_date', '=', now()->month)
                ->count(),
            'departed_tenants' => Leases::where('status', 'terminated')
                ->whereYear('end_date', '=', $currentYear)
                ->whereMonth('end_date', '=', now()->month)
                ->count(),
            'avg_tenancy_duration' => 'N/A', // You'd need to calculate this
            'payment_status' => [
                'on_time' => $paymentStatus->get('on_time', collect())->count(),
                'late' => $paymentStatus->get('late', collect())->count(),
                'overdue' => 0, // You'd need to define what "overdue" means
                'percentage_on_time' => $totalPayments > 0 
                    ? ($paymentStatus->get('on_time', collect())->count() / $totalPayments) * 100 
                    : 0
            ]
        ];

        // Expense Report Data
        $expensesByProperty = Property::with(['units.maintenanceRequests.billing'])
            ->get()
            ->map(function($property) use ($currentYear) {
                $maintenanceCost = 0;
                $otherCost = 0;
                
                foreach ($property->units as $unit) {
                    foreach ($unit->maintenanceRequests as $request) {
                        if ($request->billing && 
                            $request->completed_at && 
                            $request->completed_at->year == $currentYear && 
                            $request->completed_at->month == now()->month) {
                            $maintenanceCost += $request->billing->amount;
                        }
                    }
                }
                
                // Add other expense calculations as needed
                
                return [
                    'property' => $property->property_name,
                    'maintenance' => $maintenanceCost,
                    'utilities' => 0, // You'd need to track utilities
                    'taxes' => 0,     // You'd need to track taxes
                    'other' => $otherCost,
                    'total' => $maintenanceCost + $otherCost
                ];
            });

        $expenseReport = [
            'period' => now()->format('F Y'),
            'total_expenses' => $currentMonthExpenses,
            'avg_daily_expense' => $currentMonthExpenses > 0 ? $currentMonthExpenses / now()->day : 0,
            'expenses_by_category' => $expenseBreakdown,
            'expenses_by_property' => $expensesByProperty
        ];

         return view('admins.analytics', compact(
            'metrics',
            'revenueExpensesData',
            'properties', 
            'maintenance',
            'financialReport',
            'occupancyReport',
            'maintenanceReport',
            'tenantReport',
            'expenseReport'
        ));
    }
    
     public function maintenance()
    {
        // Get all maintenance requests with related data
        $maintenanceRequests = MaintenanceRequest::with([
            'user',
            'unit.property.landlord', 
            'assignedStaff'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate stats
        $totalRequests = $maintenanceRequests->count();
        $pendingRequests = $maintenanceRequests->where('status', 'pending')->count();
        $inProgressRequests = $maintenanceRequests->where('status', 'in_progress')->count();
        $highPriorityRequests = $maintenanceRequests->whereIn('priority', ['high', 'urgent'])->count();

        $properties = Property::with('units')->get();
        $staffUsers = User::whereIn('role', ['staff', 'admin'])->get();

        return view('admins.maintenance', compact(
            'maintenanceRequests',
            'totalRequests',
            'pendingRequests',
            'inProgressRequests',
            'highPriorityRequests',
            'properties',
            'staffUsers'
        ));
    }

    public function approveRequest(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::with(['unit.property', 'user'])->findOrFail($id);
        
        // Check if request is already approved
        if ($maintenanceRequest->status === 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Request already in_progress'
            ]);
        }

        // Update maintenance request status
        $maintenanceRequest->update([
            'status' => 'in_progress',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Find active lease for the unit
        $activeLease = Leases::where('unit_id', $maintenanceRequest->unit_id)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($activeLease) {
            // Create billing for the maintenance request
            $billing = Billing::create([
                'lease_id' => $activeLease->lease_id,
                'request_id' => $maintenanceRequest->request_id,
                'bill_name' => 'Maintenance: ' . $maintenanceRequest->title,
                'bill_period' => date('M Y'),
                'due_date' => Carbon::now()->addDays(7),
                'late_fee' => $request->input('late_fee', 0),
                'overdue_amount_percent' => $request->input('overdue_amount_percent', 0), 
                'amount' => $request->input('cost', 0),
                'status' => 'pending',
                'description' => 'Maintenance request charge: ' . $maintenanceRequest->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request approved and billing created',
                'billing_id' => $billing->bill_id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Request approved but no active lease found for billing'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
            'assigned_staff_id' => 'nullable|exists:users,user_id',
            'estimated_cost' => 'nullable|numeric|min:0'
        ]);

        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        
        $updateData = [
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $maintenanceRequest->notes
        ];

        // Add assigned staff if provided
        if ($validated['assigned_staff_id'] ?? false) {
            $updateData['assigned_staff_id'] = $validated['assigned_staff_id'];
            $updateData['assigned_at'] = now();
        }

        // Add completion date if status is completed
        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        }

        $maintenanceRequest->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'request' => $maintenanceRequest->load(['user', 'unit.property.landlord', 'assignedStaff'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'unit_id' => 'required|exists:property_units,unit_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_cost' => 'nullable|numeric|min:0'
        ]);

        // Auto-determine priority if not provided
        if (!$validated['priority']) {
            $validated['priority'] = (new MaintenanceRequest())->determinePriority(
                $validated['title'],
                $validated['description']
            );
        }

        $maintenanceRequest = MaintenanceRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request created successfully',
            'request' => $maintenanceRequest->load(['user', 'unit.property'])
        ]);
    }

    public function getRequestDetails($id)
    {
        $request = MaintenanceRequest::with([
            'user',
            'unit.property.landlord',
            'assignedStaff',
            'billing' // This should work now
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'request' => $request
        ]);
    }
    public function getProperty($id)
    {
        try {
            Log::info('Fetching property details for ID: ' . $id);
            
            $property = Property::with([
                'landlord', 
                'units', 
                'smartDevices'
            ])
            ->withCount([
                'units as units_count',
                'units as occupied_units' => function($query) {
                    $query->where('status', 'occupied');
                }
            ])
            ->find($id); // Use find() instead of findOrFail() to handle missing properties gracefully

            if (!$property) {
                Log::warning('Property not found with ID: ' . $id);
                return response()->json([
                    'error' => 'Property not found',
                    'message' => 'The requested property does not exist.'
                ], 404);
            }

            Log::info('Property found:', ['property_id' => $property->prop_id, 'name' => $property->property_name]);
            
            return response()->json($property);
            
        } catch (\Exception $e) {
            Log::error('Error fetching property: ' . $e->getMessage(), [
                'property_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'error' => 'Server error',
                'message' => 'Unable to fetch property details.'
            ], 500);
        }
    }
    public function userManagement(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search', '');
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'all');

        // Build query
        $query = User::with(['kycDocuments' => function($query) {
            $query->latest()->limit(1); // Get only the latest KYC document
        }]);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_num', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get paginated users
        $users = $query->latest()->paginate(10);

        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]);
        }

        // Get stats for the cards
        $stats = [
            'total_tenants' => User::where('role', UserRole::TENANTS)->count(),
            'active_tenants' => User::where('role', UserRole::TENANTS)->where('status', 'active')->count(),
            'total_landlords' => User::where('role', UserRole::LANDLORD)->count(),
            'active_landlords' => User::where('role', UserRole::LANDLORD)->where('status', 'active')->count(),
            'pending_kyc' => KycDocument::where('status', 'pending')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
        ];

        return view('admins.userManagement', compact('users', 'stats'));
    }
    
    public function properties()
    {
        $properties = Property::withCount([
                'units as units_count',
                'units as occupied_units' => function($query) {
                    $query->where('status', 'occupied');
                },
                'smartDevices as devices_count',
                'smartDevices as online_devices' => function($query) {
                    $query->where('connection_status', 'online');
                }
            ])
            ->with('landlord')
            ->get();

        return view('admins.properties', compact('properties'));
    }
    public function getUserStats()
    {
        $stats = [
            'total_tenants' => User::where('role', UserRole::TENANTS)->count(),
            'active_tenants' => User::where('role', UserRole::TENANTS)->where('status', 'active')->count(),
            'active_landlords' => User::where('role', UserRole::LANDLORD)->where('status', 'active')->count(),
            'active_staff' => User::where('role', UserRole::STAFF)->where('status', 'active')->count(),
            'active_admins' => User::where('role', UserRole::ADMIN)->where('status', 'active')->count(),
            'active_users' => User::where('status', 'active')->count(), // Total active across all roles
            'total_landlords' => User::where('role', UserRole::LANDLORD)->count(),
            'pending_kyc' => KycDocument::where('status', 'pending')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
        ];

        return response()->json($stats);
    }
    public function getPendingKyc()
    {
        $kycDocuments = KycDocument::with(['user'])
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(function($doc) {
                return [
                    'kyc_id' => $doc->kyc_id,
                    'user' => [
                        'first_name' => $doc->user->first_name,
                        'last_name' => $doc->user->last_name,
                        'email' => $doc->user->email,
                        'phone_num' => $doc->user->phone_num,
                    ],
                    'doc_type' => $doc->doc_type,
                    'doc_name' => $doc->doc_name,
                    'doc_path' => $doc->doc_path,
                    'proof_of_income' => $doc->proof_of_income,
                    'status' => $doc->status,
                    'created_at' => $doc->created_at,
                    // Add URLs for document access
                    'doc_url' => $doc->doc_path ? Storage::url('images/uploadDocs/' . $doc->doc_path) : null,
                    'income_proof_url' => $doc->proof_of_income ? Storage::url('images/uploadDocs/' . $doc->proof_of_income) : null,
                ];
            });

        return response()->json($kycDocuments);
    }
    
     public function index()
    {
        $totalProperties = Property::count();
        $activeTenants = User::whereHas('leases', function ($query) {
            $query->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        })->count();
        $activeLandlords = User::whereHas('properties')->count();
        $smartDevices = SmartDevice::count();
        $monthlyRevenue = Payment::where('payment_date', '>=', Carbon::now()->subDays(30))
            ->sum('amount_paid');
        $previousRevenue = Payment::whereBetween('payment_date', 
            [Carbon::now()->subDays(60), Carbon::now()->subDays(31)])
            ->sum('amount_paid');
        
        $revenueTrend = $previousRevenue > 0 ? 
            round((($monthlyRevenue - $previousRevenue) / $previousRevenue) * 100, 1) : 
            ($monthlyRevenue > 0 ? 100 : 0);
        
        $totalUnits = PropertyUnits::count();
        $occupiedUnits = PropertyUnits::whereHas('leases', function ($query) {
            $query->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        })->count();
        
        $availableUnits = $totalUnits - $occupiedUnits;
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;
        
        // Active Maintenance Requests
        $activeMaintenance = MaintenanceRequest::whereIn('status', ['pending', 'in_progress'])
            ->count();
        
        // Online Smart Devices
        $onlineDevices = SmartDevice::where('connection_status', 'online')->count();
        $deviceOnlineRate = $smartDevices > 0 ? round(($onlineDevices / $smartDevices) * 100) : 0;
        
        // Recent Activities
        $recentActivities = $this->getRecentActivities();
        
        // User Growth Chart Data
        $userGrowthData = $this->getUserGrowthData();
        
        // Revenue Chart Data
        $revenueChartData = $this->getRevenueChartData();
        
        // Top Performing Properties
        $topProperties = $this->getTopProperties();
        
        // Recent Registrations
        $recentTenants = User::where('role', 'tenant')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentLandlords = User::where('role', 'landlord')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admins.dashboard', compact(
            'totalProperties',
            'activeTenants',
            'activeLandlords',
            'smartDevices',
            'monthlyRevenue',
            'revenueTrend',
            'totalUnits',
            'occupiedUnits',
            'availableUnits',
            'occupancyRate',
            'activeMaintenance',
            'onlineDevices',
            'deviceOnlineRate',
            'recentActivities',
            'userGrowthData',
            'revenueChartData',
            'topProperties',
            'recentTenants',
            'recentLandlords'
        ));
    }
    
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Get recent maintenance requests
        $maintenanceRequests = MaintenanceRequest::with(['unit.property', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'maintenance',
                    'icon' => 'tools',
                    'iconColor' => 'yellow',
                    'title' => $request->title,
                    'description' => $request->unit ? 
                        $request->unit->property->property_name . ' - Unit ' . ($request->unit->unit_number ?? $request->unit->unit_num ?? 'N/A') : 
                        'Unknown Unit',
                    'time' => $request->created_at->diffForHumans(),
                    'priority' => $request->priority
                ];
            });

        // Get recent payments
        $recentPayments = Payment::with(['lease.unit.property', 'lease.user'])
            ->orderBy('payment_date', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'icon' => 'check-circle',
                    'iconColor' => 'green',
                    'title' => 'Rent Payment Received',
                    'description' => ($payment->lease->user->first_name ?? '') . ' ' . 
                                ($payment->lease->user->last_name ?? '') . ' - ' . 
                                ($payment->lease->unit->property->property_name ?? 'Unknown Property'),
                    'time' => $payment->payment_date->diffForHumans(),
                    'amount' => $payment->amount_paid
                ];
            });

        // Get recent user registrations - FIXED
        $recentUsers = User::whereIn('role', ['tenant', 'landlord'])
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($user) {
                // Handle role safely
                $role = $user->role;
                
                // Check if role is an enum
                if ($role instanceof \App\Enums\UserRole) {
                    $roleString = method_exists($role, 'value') ? $role->value : $role->name;
                } elseif (is_string($role)) {
                    $roleString = $role;
                } else {
                    $roleString = 'user';
                }
                
                // Get display name for title
                $roleDisplay = ucfirst($roleString);
                
                return [
                    'type' => 'user',
                    'icon' => 'user-plus',
                    'iconColor' => 'blue',
                    'title' => $roleDisplay . ' Registered',
                    'description' => $user->first_name . ' ' . $user->last_name,
                    'time' => $user->created_at->diffForHumans(),
                    'role' => $roleString
                ];
            });
        $allActivities = collect();
        
        foreach ($maintenanceRequests as $item) {
            $allActivities->push($item);
        }
        
        foreach ($recentPayments as $item) {
            $allActivities->push($item);
        }
        
        foreach ($recentUsers as $item) {
            $allActivities->push($item);
        }
        
        // Sort by time (you'll need to convert time to a sortable format)
        $sortedActivities = $allActivities->sortByDesc(function($item) {
            if (isset($item['time'])) {
                return time(); 
            }
            return 0;
        })->take(5);

        return $sortedActivities;
    }
    
    private function getUserGrowthData()
    {
        $months = [];
        $tenants = [];
        $landlords = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $months[] = $monthName;
            
            $tenantCount = User::where('role', 'tenant')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $landlordCount = User::where('role', 'landlord')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $tenants[] = $tenantCount;
            $landlords[] = $landlordCount;
        }
        
        return [
            'labels' => $months,
            'tenants' => $tenants,
            'landlords' => $landlords
        ];
    }
    
    private function getRevenueChartData()
    {
        // Get revenue for last 6 months
        $months = [];
        $revenues = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $months[] = $monthName;
            
            $revenue = Payment::whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount_paid');
            
            $revenues[] = $revenue;
        }
        
        // Find highest revenue month
        $maxRevenue = max($revenues);
        $maxMonthIndex = array_search($maxRevenue, $revenues);
        $highestMonth = $maxRevenue > 0 ? $months[$maxMonthIndex] : null;
        
        return [
            'labels' => $months,
            'data' => $revenues,
            'highest_month' => $highestMonth,
            'highest_revenue' => $maxRevenue
        ];
    }
    
    private function getTopProperties()
    {
        return Property::withCount([
            'units',
            'activeLease as active_leases_count' 
        ])
        ->withSum('units', 'area_sqm')
        ->orderBy('active_leases_count', 'desc')
        ->take(5)
        ->get()
        ->map(function ($property) {
            // Calculate occupancy rate
            $property->units_count = $property->units_count ?? 0;
            $property->active_leases_count = $property->active_leases_count ?? 0;
            
            $property->occupancy_rate = $property->units_count > 0 ? 
                round(($property->active_leases_count / $property->units_count) * 100) : 0;
            
            return $property;
        });
    }
    public function createInvoice(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::with(['unit.property', 'user'])->findOrFail($id);
        
        // Check if invoice already exists
        $existingBilling = Billing::where('request_id', $id)->first();
        if ($existingBilling) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice already exists for this request',
                'billing_id' => $existingBilling->bill_id
            ]);
        }

        // Find active lease for the unit
        $activeLease = Leases::where('unit_id', $maintenanceRequest->unit_id)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$activeLease) {
            return response()->json([
                'success' => false,
                'message' => 'No active lease found for this unit'
            ]);
        }

        // Create billing invoice
        $billing = Billing::create([
            'lease_id' => $activeLease->lease_id,
            'request_id' => $maintenanceRequest->request_id,
            'bill_name' => 'Maintenance Invoice: ' . $maintenanceRequest->title,
            'bill_period' => date('M Y'),
            'due_date' => Carbon::now()->addDays(7),
            'late_fee' => $request->input('late_fee', 0),
            'overdue_amount_percent' => $request->input('overdue_amount_percent', 0),
            'amount' => $request->input('amount', 0),
            'status' => 'pending',
            'description' => 'Maintenance service: ' . $maintenanceRequest->description,
            'notes' => $request->input('notes', '')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully',
            'invoice' => $billing
        ]);
    }

    public function recordPayment(Request $request, $id)
    {
        // $id is billing_id
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,bank_transfer,credit_card,debit_card,online',
            'amount_paid' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string',
            'payment_date' => 'required|date'
        ]);

        $billing = Billing::with('maintenanceRequest')->findOrFail($id);
        
        // Create payment record
        $payment = Payment::create([
            'bill_id' => $billing->bill_id,
            'lease_id' => $billing->lease_id,
            'payment_method' => $validated['payment_method'],
            'amount_paid' => $validated['amount_paid'],
            'reference_number' => $validated['reference_number'],
            'payment_date' => $validated['payment_date']
        ]);

        // Update billing status
        $billing->update([
            'status' => 'paid'
        ]);

        // Update maintenance request status to "completed"
        if ($billing->maintenanceRequest) {
            $billing->maintenanceRequest->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded and maintenance marked as completed',
            'payment' => $payment
        ]);
    }
}