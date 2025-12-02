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
        // Get total revenue
        $totalRevenue = Billing::where('status', 'paid')->sum('amount');
        
        // Get total properties
        $totalProperties = Property::count();
        
        // Calculate occupancy rate
        $totalUnits = PropertyUnits::count();
        $occupiedUnits = Leases::where('status', 'active')->count();
        $occupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
        
        // Get open maintenance requests
        $openMaintenanceRequests = MaintenanceRequest::whereIn('status', ['pending', 'in_progress'])->count();
        
        // Get top properties - FIXED: Use query with relationships
        $topProperties = Property::with(['units.leases.billings' => function($query) {
            $query->where('status', 'paid');
        }])->get()
            ->map(function ($property) {
                // Calculate revenue for this property
                $revenue = 0;
                foreach ($property->units as $unit) {
                    foreach ($unit->leases as $lease) {
                        foreach ($lease->billings as $billing) {
                            $revenue += $billing->amount;
                        }
                    }
                }
                
                // Calculate occupancy - FIXED: Use filter() instead of whereHas() on Collection
                $totalUnits = $property->units->count();
                $occupiedUnits = $property->units->filter(function ($unit) {
                    return $unit->leases->where('status', 'active')->count() > 0;
                })->count();
                
                $occupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
                
                // Return as array
                return [
                    'property_id' => $property->property_id,
                    'property_name' => $property->property_name,
                    'property_type' => $property->property_type,
                    'revenue' => $revenue,
                    'occupancy_rate' => $occupancyRate,
                    'total_units' => $totalUnits,
                    'occupied_units' => $occupiedUnits
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values()
            ->toArray();
        
        // Get maintenance stats
        $maintenanceStats = [
            ['status' => 'pending', 'count' => MaintenanceRequest::where('status', 'pending')->count()],
            ['status' => 'in_progress', 'count' => MaintenanceRequest::where('status', 'in_progress')->count()],
            ['status' => 'completed', 'count' => MaintenanceRequest::where('status', 'completed')->count()],
            ['status' => 'cancelled', 'count' => MaintenanceRequest::where('status', 'cancelled')->count()],
        ];
        
        // Get priority stats
        $priorityStats = [
            ['priority' => 'urgent', 'count' => MaintenanceRequest::where('priority', 'urgent')->count()],
            ['priority' => 'high', 'count' => MaintenanceRequest::where('priority', 'high')->count()],
            ['priority' => 'medium', 'count' => MaintenanceRequest::where('priority', 'medium')->count()],
            ['priority' => 'low', 'count' => MaintenanceRequest::where('priority', 'low')->count()],
        ];
        
        // Get recent activities
        $recentActivities = [];
        
        // Add recent payments
        $recentPayments = Billing::with(['lease.unit.property'])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'description' => 'Payment received for ' . ($payment->lease->unit->property->property_name ?? 'Unknown Property'),
                    'amount' => $payment->amount,
                    'time_ago' => $payment->created_at->diffForHumans()
                ];
            });
        
        // Add recent maintenance
        $recentMaintenance = MaintenanceRequest::with(['unit.property', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'maintenance',
                    'description' => 'Maintenance request for ' . ($request->unit->property->property_name ?? 'Unknown Property'),
                    'time_ago' => $request->created_at->diffForHumans()
                ];
            });
        
        // Merge activities
        $recentActivities = $recentPayments->merge($recentMaintenance)
            ->sortByDesc(function ($activity) {
                return $activity['time_ago'];
            })
            ->take(10)
            ->values()
            ->toArray();
        
        // Revenue data for chart (last 6 months)
        $revenueData = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueData['labels'][] = $month->format('M Y');
            
            $monthRevenue = Billing::where('status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $revenueData['data'][] = $monthRevenue;
        }
        
        return view('admins.analytics', compact(
            'totalRevenue',
            'totalProperties',
            'occupancyRate',
            'openMaintenanceRequests',
            'topProperties',
            'maintenanceStats',
            'priorityStats',
            'recentActivities',
            'revenueData'
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
        $staffUsers = User::whereIn('role', ['staff'])->get();

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
        $validated = $request->validate([
            'cost' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'assigned_staff_id' => 'nullable|exists:users,user_id'
        ]);

        $maintenanceRequest = MaintenanceRequest::with(['unit.property', 'user'])->findOrFail($id);
        
        // Check if request is already approved
        if ($maintenanceRequest->status === 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Request already in_progress'
            ]);
        }
        $updateData = [
            'status' => 'in_progress',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'estimated_cost' => $validated['cost']
        ];

        // Add assigned staff if provided
        if (!empty($validated['assigned_staff_id'])) {
            $updateData['assigned_staff_id'] = $validated['assigned_staff_id'];
            $updateData['assigned_at'] = now();
        }
        $maintenanceRequest->update($updateData);

        $activeLease = Leases::where('unit_id', $maintenanceRequest->unit_id)
            ->where('status', 'activate')
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
                'due_date' => $validated['due_date'],
                'late_fee' => 0,
                'overdue_amount_percent' => 0, 
                'amount' => $validated['cost'],
                'status' => 'pending',
                'description' => 'Maintenance request charge: ' . $maintenanceRequest->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request approved and billing created',
                'billing_id' => $billing->bill_id,
                'staff_assigned' => !empty($validated['assigned_staff_id'])
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Request approved but no active lease found for billing',
            'staff_assigned' => !empty($validated['assigned_staff_id'])
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