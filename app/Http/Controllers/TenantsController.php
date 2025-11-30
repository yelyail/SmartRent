<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;    
use App\Models\Leases;
use App\Models\MaintenanceRequest;
use App\Models\SmartDevice;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyUnits;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TenantsController extends Controller
{
    public function index()
    {
        return view('tenants.dashboard');
    }
    
    public function bill()
    {
        return view('tenants.bill');
    }
    
    public function analytics()
    {
        return view('tenants.reports');
    }
    //for the maintenance
    public function maintenance()
    {
        $tenantId = Auth::id();
        
        $userUnits = PropertyUnits::whereHas('leases', function($query) use ($tenantId) {
                $query->where('user_id', $tenantId)
                    ->where('status', 'active');
            })
            ->with('property')
            ->get();

        // Get the current tenant's maintenance requests
        $maintenanceRequests = MaintenanceRequest::where('user_id', $tenantId)
            ->with(['unit.property', 'assignedStaff'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get staff members with their positions
        $staffMembers = User::where('role', 'staff')
            ->where('status', 'active')
            ->select('user_id', 'first_name', 'last_name', 'position')
            ->orderBy('first_name')
            ->get();

        return view('tenants.maintenance', compact('userUnits', 'maintenanceRequests', 'staffMembers'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'unit_id' => 'required|exists:property_units,unit_id',
            'assigned_staff_id' => 'nullable|exists:users,user_id',
        ]);

        try {
            $maintenanceRequest = new MaintenanceRequest();
            $autoPriority = $maintenanceRequest->determinePriority(
                $validated['title'], 
                $validated['description']
            );

            $maintenanceRequest = MaintenanceRequest::create([
                'user_id' => Auth::id(),
                'unit_id' => $validated['unit_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'priority' => $autoPriority,
                'assigned_staff_id' => $validated['assigned_staff_id'] ?? null,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance request submitted successfully!',
                'request_id' => $maintenanceRequest->request_id,
                'auto_priority' => $autoPriority,
                'assigned_staff' => $validated['assigned_staff_id'] ? 'Manually assigned' : 'Auto-assigned'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit maintenance request: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getStaffMembers()
    {
        $staff = User::where('role', 'staff')
                    ->where('status', 'active')
                    ->select('user_id', 'first_name', 'last_name')
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->user_id,
                            'name' => $user->first_name . ' ' . $user->last_name
                        ];
                    });

        return response()->json($staff);
    }
    // for the smart devices
    public function propAssets()
    {
        $activeLeases = Leases::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with(['property.smartDevices'])
            ->get();

        $smartDevices = collect();
        $totalDevices = 0;
        $onlineDevices = 0;
        $activeDevices = 0;
        $lowBatteryDevices = 0;

        foreach ($activeLeases as $lease) {
            if ($lease->property && $lease->property->smartDevices) {
                $propertyDevices = $lease->property->smartDevices->map(function ($device) use ($lease) {
                    $device->property_name = $lease->property->property_name;
                    $device->property_address = $lease->property->property_address;
                    return $device;
                });
                
                $smartDevices = $smartDevices->merge($propertyDevices);
                
                $totalDevices += $lease->property->smartDevices->count();
                $onlineDevices += $lease->property->smartDevices->where('connection_status', 'online')->count();
                $activeDevices += $lease->property->smartDevices->where('power_status', 'on')->count();
                $lowBatteryDevices += $lease->property->smartDevices->where('battery_level', '<', 20)->count();
            }
        }

        return view('tenants.propAssets', compact(
            'smartDevices', 
            'totalDevices', 
            'onlineDevices', 
            'activeDevices', 
            'lowBatteryDevices'
        ));
    }
    
    public function toggleDevice($id)
    {
        $device = SmartDevice::findOrFail($id);
        
        $hasAccess = Leases::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('prop_id', $device->prop_id)
            ->exists();
            
        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this device'
            ], 403);
        }
        
        $device->power_status = $device->power_status === 'on' ? 'off' : 'on';
        $device->save();
        
        return response()->json([
            'success' => true,
            'power_status' => $device->power_status
        ]);
    }
    
    public function payment()
    {
        return view('tenants.payment');
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

        return view('tenants.properties', compact('properties'));
    }
    
    public function getProperty($id)
    {
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
        ->findOrFail($id);

        return response()->json($property);
    }
    
    public function rentProperty(Request $request, $propertyId)
    {
        $property = Property::withCount([
            'units as units_count',
            'units as occupied_units_count' => function($query) {
                $query->where('status', 'occupied');
            }
        ])->findOrFail($propertyId);
        
        // Check if there are available units
        $availableUnits = $property->units_count - $property->occupied_units_count;
        
        if ($availableUnits <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No available units in this property'
            ], 400);
        }

        // Check if user already has an active or pending lease for this property
        $existingLease = Leases::where('user_id', Auth::id())
            ->where('prop_id', $propertyId)
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->first();

        if ($existingLease) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a rental request for this property'
            ], 400);
        }

        try {
            // Get the first available unit to assign to this lease
            $availableUnit = PropertyUnits::where('prop_id', $propertyId)
                ->where('status', 'available')
                ->first();

            if (!$availableUnit) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available units found for this property'
                ], 400);
            }

            $lease = Leases::create([
                'user_id'        => Auth::id(),
                'prop_id'        => $propertyId,
                'unit_id'        => $availableUnit->unit_id,
                'start_date'     => now(),
                'end_date'       => now()->addYear(),
                'rent_amount'    => $property->property_price,
                'deposit_amount' => 0.00,    // pending → always 0.00
                'status'         => 'pending',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Rental request submitted successfully! Waiting for landlord approval.',
                'lease' => $lease
            ]);

        } catch (\Exception $e) {
            Log::error('Rental request failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit rental request'
            ], 500);
        }
    }
    public function leases()
    {
        $leases = Leases::with('property')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenants.leases', compact('leases'));
    }
    
    public function submitMaintenanceRequest()
    {
        return view('tenants.submitMaintenanceRequest');
    }
    
    public function paymentHistory()
    {
        return view('tenants.paymentHistory');
    }
}