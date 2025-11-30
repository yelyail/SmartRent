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

class LandlordController extends Controller
{
    public function index()
    {
        return view('landlords.dashboard');
    }

    public function analytics()
    {
        return view('landlords.analytics'); 
    }
    //for the Maintenance Request

    public function maintenance()
    {
        // Get maintenance requests for landlord's properties
        $maintenanceRequests = MaintenanceRequest::with(['tenant', 'unit.property', 'assignedTechnician'])
            ->whereHas('unit.property', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('requested_at', 'desc')
            ->get();

        // Calculate stats
        $stats = [
            'total' => $maintenanceRequests->count(),
            'pending' => $maintenanceRequests->where('status', 'PENDING')->count(),
            'in_progress' => $maintenanceRequests->where('status', 'IN_PROGRESS')->count(),
            'high_priority' => $maintenanceRequests->where('priority', 'HIGH')->count(),
        ];

        // Get properties for the modal
        $properties = Property::where('user_id', Auth::id())->get();

        // Get technicians for assignment
        $technicians = User::where('role', 'technician')->get();

        return view('landlords.maintenance', compact('maintenanceRequests', 'stats', 'properties', 'technicians'));
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

    public function payment()
    {
        return view('landlords.payment');
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
}