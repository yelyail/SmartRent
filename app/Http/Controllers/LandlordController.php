<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyUnits;
use Illuminate\Http\Request;
use App\Models\SmartDevice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Leases;
use App\Models\KycDocument;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Billing;
use App\Models\Payment;
use App\Enums\UserRole;

class LandlordController extends Controller
{
    public function index()
    {
        return view('landlords.dashboard');
    }

    public function analytics()
{
    $user = Auth::user();
    $properties = Property::where('user_id', $user->user_id)->get();
    
    if ($properties->isEmpty()) {
        return $this->emptyLandlordAnalytics($user);
    }
    
    return $this->landlordAnalytics($user);
    }

    private function landlordAnalytics($user)
    {
        $properties = Property::where('user_id', $user->user_id)->get();
        
        // Calculate occupancy rates for each property
        $properties->each(function ($property) use ($user) {
            $totalUnits = $property->units()->count();
            $occupiedUnits = $property->units()->whereHas('leases', function ($query) {
                $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->count();
            
            $property->occupancy_rate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
            $property->occupancy_trend = $this->calculateOccupancyTrend($property);
        });

        // Get active leases with related data
        $activeLeases = Leases::with(['user', 'unit.property', 'billings'])
            ->where('status', 'active')
            ->whereHas('unit.property', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Get total tenants count
        $totalTenants = User::whereHas('leases', function ($query) use ($user) {
            $query->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->whereHas('unit.property', function ($q) use ($user) {
                    $q->where('user_id', $user->user_id);
                });
        })->count();

        // Get recent maintenance requests for landlord's properties
        $maintenanceRequests = MaintenanceRequest::with(['unit.property', 'assignedStaff', 'user'])
            ->whereHas('unit.property', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent payments for landlord's properties
        $recentPayments = Payment::with(['billing', 'lease.unit.property', 'lease.user'])
            ->whereHas('lease.unit.property', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Get payment statistics for landlord
        $paymentStats = $this->getLandlordPaymentStats($user);

        // Maintenance statistics
        $maintenanceStats = $this->getLandlordMaintenanceStats($user);

        return view('landlords.analytics', compact(
            'properties',
            'activeLeases',
            'totalTenants',
            'maintenanceRequests',
            'recentPayments',
            'paymentStats',
            'maintenanceStats'
        ));
    }

    private function getLandlordPaymentStats($user)
    {
        // Debug: Check if we can find properties
        $propertiesCount = Property::where('user_id', $user->user_id)->count();
        logger("Properties count for user {$user->user_id}: {$propertiesCount}");

        // Get pending payments (overdue or due soon)
        $pendingPayments = Billing::whereHas('lease.unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('status', 'pending')->count();

        logger("Pending payments count: {$pendingPayments}");

        // Get total collected revenue
        $totalCollected = Payment::whereHas('lease.unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->sum('amount_paid');

        logger("Total collected: {$totalCollected}");

        // Get this month's revenue
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthRevenue = Payment::whereHas('lease.unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('payment_date', '>=', $currentMonthStart)->sum('amount_paid');

        logger("Current month revenue: {$currentMonthRevenue}");

        return [
            'pending_payments' => $pendingPayments,
            'total_collected' => $totalCollected,
            'monthly_revenue' => $currentMonthRevenue,
        ];
    }

    private function getLandlordMaintenanceStats($user)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Debug maintenance requests
        $currentMonthRequests = MaintenanceRequest::whereHas('unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('created_at', '>=', $currentMonthStart)->count();

        logger("Current month maintenance requests: {$currentMonthRequests}");

        $lastMonthRequests = MaintenanceRequest::whereHas('unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        logger("Last month maintenance requests: {$lastMonthRequests}");

        // Check if we have any maintenance requests at all
        $totalRequests = MaintenanceRequest::whereHas('unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->count();

        logger("Total maintenance requests: {$totalRequests}");

        // Simplified cost calculations
        $averageCost = 185;
        $totalCost = $currentMonthRequests * $averageCost;
        $lastMonthTotalCost = $lastMonthRequests * $averageCost;

        return [
            'monthly_requests' => $currentMonthRequests,
            'request_trend' => $currentMonthRequests - $lastMonthRequests,
            'average_cost' => $averageCost,
            'cost_trend' => 0,
            'total_cost' => $totalCost,
            'total_trend' => $totalCost - $lastMonthTotalCost,
            'avg_resolution_days' => 2.1,
            'resolution_trend' => -0.3,
        ];
    }

    private function emptyLandlordAnalytics($user)
    {
        // Return analytics with empty data for landlords with no properties
        return view('landlords.analytics', [
            'properties' => collect(),
            'activeLeases' => collect(),
            'totalTenants' => 0,
            'maintenanceRequests' => collect(),
            'recentPayments' => collect(),
            'paymentStats' => [
                'pending_payments' => 0,
                'total_collected' => 0,
                'monthly_revenue' => 0,
            ],
            'maintenanceStats' => [
                'monthly_requests' => 0,
                'request_trend' => 0,
                'average_cost' => 0,
                'cost_trend' => 0,
                'total_cost' => 0,
                'total_trend' => 0,
                'avg_resolution_days' => 0,
                'resolution_trend' => 0,
            ]
        ]);
    }

    private function calculateOccupancyTrend($property)
    {
        $trends = ['up', 'down', 'stable'];
        return $trends[array_rand($trends)];
    }
    //for the Maintenance Request

    public function maintenance()
{
    // Get maintenance requests for landlord's properties
    $maintenanceRequests = MaintenanceRequest::with(['user', 'unit.property', 'assignedStaff'])
        ->whereHas('unit.property', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // Calculate stats
    $stats = [
        'total' => $maintenanceRequests->count(),
        'pending' => $maintenanceRequests->where('status', 'pending')->count(),
        'in_progress' => $maintenanceRequests->where('status', 'in_progress')->count(),
        'high_priority' => $maintenanceRequests->where('priority', 'high')->count() + 
                          $maintenanceRequests->where('priority', 'urgent')->count(),
    ];

    return view('landlords.maintenance', compact('maintenanceRequests', 'stats'));
}
    public function storeMaintenanceRequest(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:HVAC,Plumbing,Electrical,Security,Appliances,General,Emergency',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,EMERGENCY',
            'estimated_cost' => 'nullable|numeric|min:0',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,unit_id',
            'tenant_name' => 'required|string|max:255',
            'tenant_phone' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'preferred_date' => 'nullable|date',
            'special_instructions' => 'nullable|string',
        ]);

        // Find or create tenant
        $tenant = User::firstOrCreate(
            ['email' => $request->tenant_email ?? 'temp-' . time() . '@example.com'],
            [
                'name' => $validated['tenant_name'],
                'phone' => $validated['tenant_phone'],
                'password' => bcrypt('temp123'), // Temporary password
                'role' => 'tenant'
            ]
        );

        // Create maintenance request
        $maintenanceRequest = MaintenanceRequest::create([
            'property_id' => $validated['property_id'],
            'unit_id' => $validated['unit_id'],
            'tenant_id' => $tenant->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'estimated_cost' => $validated['estimated_cost'],
            'assigned_to' => $validated['assigned_to'],
            'preferred_date' => $validated['preferred_date'],
            'special_instructions' => $validated['special_instructions'],
            'status' => 'PENDING',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request created successfully',
            'data' => $maintenanceRequest
        ]);
    }

    public function updateMaintenanceStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'notes' => 'nullable|string',
            'actual_cost' => 'nullable|numeric|min:0',
        ]);

        $maintenanceRequest = MaintenanceRequest::forLandlord(Auth::id())->findOrFail($id);

        $maintenanceRequest->updateStatus(
            $validated['status'],
            $validated['notes'],
            Auth::id()
        );

        if ($validated['actual_cost']) {
            $maintenanceRequest->update(['actual_cost' => $validated['actual_cost']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request status updated successfully'
        ]);
    }

    public function getMaintenanceDetails($id)
    {
        $maintenanceRequest = MaintenanceRequest::with([
            'property',
            'unit',
            'tenant',
            'assignedTechnician',
            'photos',
            'statusHistory.changedBy'
        ])->forLandlord(Auth::id())->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequest
        ]);
    }

    public function propAssets()
    {
        // Get all smart devices belonging to the landlord's properties
        $smartDevices = SmartDevice::with(['property'])
            ->whereHas('property', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        // Calculate stats based on your model fields
        $totalDevices = $smartDevices->count();
        $onlineDevices = $smartDevices->where('connection_status', 'online')->count();
        $activeDevices = $smartDevices->where('power_status', 'on')->count(); // This counts devices with power_status = 'on'
        $lowBatteryDevices = $smartDevices->where('battery_level', '<', 10)->count();

        $stats = [
            'total' => $totalDevices,
            'online' => $onlineDevices,
            'active' => $activeDevices, // This now represents devices with power_status = 'on'
            'low_battery' => $lowBatteryDevices
        ];

        return view('landlords.propAssets', compact('smartDevices', 'stats'));
    }
   // Add this to LandlordController
    public function updateDeviceStatus($id, Request $request)
    {
        try {
            $device = SmartDevice::findOrFail($id);
            
            // Verify the device belongs to the landlord
            if ($device->property->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized access to device'
                ], 403);
            }

            $request->validate([
                'power_status' => 'required|in:on,off'
            ]);

            $device->update([
                'power_status' => $request->power_status
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Device status updated successfully',
                'power_status' => $device->power_status
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating device status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating device status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function properties()
    {
        $properties = Property::with([
            'landlord',
            'smartDevices'
        ])->withCount([
            'units as units_count',
            'smartDevices as devices_count',
            'smartDevices as online_devices' => function($query) {
                $query->where('connection_status', 'online');
            }
        ])
        ->addSelect([
            'occupied_units' => function ($query) {
                $query->selectRaw('COUNT(DISTINCT property_units.unit_id)')
                    ->from('property_units')
                    ->leftJoin('leases', function ($join) {
                        $join->on('property_units.unit_id', '=', 'leases.unit_id')
                            ->where('leases.status', 'active');
                    })
                    ->whereColumn('property_units.prop_id', 'properties.prop_id')
                    ->whereNotNull('leases.lease_id');
            }
        ])
        ->where('user_id', Auth::id())
        ->get()
        ->map(function ($property) {
            // Calculate available units (total units - occupied units)
            $property->available_units = $property->units_count - $property->occupied_units;
            return $property;
        });

        return view('landlords.properties', compact('properties'));
    }

    public function userManagement()
    {
        // Get all leases for properties owned by the current landlord
        $leases = Leases::with(['user', 'property', 'unit'])
            ->whereHas('property', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate stats
        $totalTenants = $leases->count();
        $activeTenants = $leases->where('status', 'active')->count();
        $latePayments = $leases->where('status', 'active')->count(); // You can add payment tracking logic
        $expiringSoon = $leases->where('end_date', '<=', now()->addDays(30))->count();

        // Get landlord's properties for the add tenant modal
        $properties = Property::where('user_id', Auth::id())->get();

        return view('landlords.userManagement', compact(
            'leases', 
            'totalTenants', 
            'activeTenants', 
            'latePayments', 
            'expiringSoon',
            'properties'
        ));
    }
    // Add these methods to LandlordController
    public function approveLease($id)
    {
        try {
            $lease = Leases::with('property')->findOrFail($id);
            
            // Check if the lease belongs to the landlord's property
            if ($lease->property->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
            
            $lease->update(['status' => 'approved']);
            
            // Assign the unit to the tenant
            if ($lease->unit_id) {
                PropertyUnits::where('unit_id', $lease->unit_id)->update(['status' => 'occupied']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Lease approved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error approving lease: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve lease: ' . $e->getMessage()
            ], 500);
        }
    }

    public function terminateLease($id)
    {
        try {
            $lease = Leases::with('property')->findOrFail($id);
            
            // Check if the lease belongs to the landlord's property
            if ($lease->property->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
            
            $lease->update(['status' => 'terminated']);
            
            // Free up the unit if it was assigned
            if ($lease->unit_id) {
                PropertyUnits::where('unit_id', $lease->unit_id)->update(['status' => 'available']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Lease terminated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error terminating lease: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to terminate lease: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getTenantDetails($userId)
    {
        try {
            $tenant = Leases::with(['user', 'property', 'unit'])
                ->where('user_id', $userId)
                ->firstOrFail();

            // Fetch KYC data - adjust the model name if different
            $kyc = KycDocument::where('user_id', $userId)->first();

            return response()->json([
                'success' => true,
                'tenant' => $tenant,
                'kyc' => $kyc ? [
                    'kyc_id' => $kyc->id,
                    'status' => $kyc->status,
                    'doc_path' => $kyc->doc_path,
                    'doc_name' => $kyc->doc_name,
                    'proof_of_income' => $kyc->proof_of_income,
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found: ' . $e->getMessage()
            ], 404);
        }
    }
    // Add this method to your LandlordController
    public function updateKycStatus($kycId, Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'status' => 'required|in:approved,rejected,pending'
            ]);

            // Find the KYC record
            $kyc = KycDocument::findOrFail($kycId); // Adjust the model name if different

            // Update the status
            $kyc->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'KYC status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update KYC status: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getRequestDetails($id)
    {
        try {
            $request = MaintenanceRequest::with([
                'unit.property',
                'user',
                'assignedStaff'
            ])->findOrFail($id);

            // Get the raw date values and parse them manually
            $requestedAt = $request->getRawOriginal('requested_at') ?? $request->getRawOriginal('created_at');
            $updatedAt = $request->getRawOriginal('updated_at');
            $assignedAt = $request->getRawOriginal('created_at');
            $completedAt = $request->getRawOriginal('completed_at');


            return response()->json([
                'request_id' => $request->request_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'priority' => $request->priority,
                'property_name' => $request->unit->property->property_name ?? 'N/A',
                'unit_name' => $request->unit->unit_name ?? 'N/A',
                'unit_num' => $request->unit->unit_num ?? 'N/A',
                'tenant_name' => $request->user->first_name . ' ' . $request->user->last_name,
                'tenant_phone' => $request->user->phone_num ?? 'No contact number',
                'requested_at' => $requestedAt ? \Carbon\Carbon::parse($requestedAt)->toISOString() : null,
                'updated_at' => $updatedAt ? \Carbon\Carbon::parse($updatedAt)->toISOString() : null,
                'assigned_staff' => $request->assignedStaff ? $request->assignedStaff->first_name . ' ' . $request->assignedStaff->last_name : null,
                'staff_position' => $request->assignedStaff->position ?? null,
                'assigned_date' => $assignedAt ? \Carbon\Carbon::parse($assignedAt)->toISOString() : null,
                'completed_date' => $completedAt ? \Carbon\Carbon::parse($completedAt)->toISOString() : null,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching maintenance request details: ' . $e->getMessage());
            return response()->json(['error' => 'Request not found'], 404);
        }
    }
}