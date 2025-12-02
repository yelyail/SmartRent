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
use Illuminate\Support\Str;

    class TenantsController extends Controller
    {
    public function index()
    {
        $tenantId = Auth::id();
        
        // Get current active lease
        $currentLease = Leases::where('user_id', $tenantId)
            ->where('status', 'activate')
            ->with(['property', 'unit'])
            ->first();

        // Get maintenance statistics
        $maintenanceStats = [
            'total' => MaintenanceRequest::where('user_id', $tenantId)->count(),
            'pending' => MaintenanceRequest::where('user_id', $tenantId)->where('status', 'pending')->count(),
            'in_progress' => MaintenanceRequest::where('user_id', $tenantId)->where('status', 'in_progress')->count(),
        ];

        // Get device statistics (if you have smart devices)
        $deviceStats = [
            'total' => 0,
            'online' => 0,
        ];
        
        if ($currentLease) {
            $devices = SmartDevice::where('prop_id', $currentLease->prop_id)->get();
            $deviceStats['total'] = $devices->count();
            $deviceStats['online'] = $devices->where('connection_status', 'online')->count();
        }

        // Rent status calculation
        $rentStatus = $this->calculateRentStatus($currentLease);

        // Recent activities (mix of payments, maintenance, etc.)
        $recentActivities = $this->getRecentActivities($tenantId);

        return view('tenants.dashboard', compact(
            'currentLease',
            'maintenanceStats',
            'deviceStats',
            'rentStatus',
            'recentActivities'
        ));
    }

    private function calculateRentStatus($currentLease)
    {
        if (!$currentLease) {
            return [
                'status' => 'No Lease',
                'color' => 'bg-gray-500',
                'textColor' => 'text-gray-900',
                'dueDate' => 'N/A'
            ];
        }

        $today = now();
        $dueDate = \Carbon\Carbon::parse($currentLease->start_date)->addMonth()->startOfDay();
        $isPaid = Payment::where('lease_id', $currentLease->lease_id)
            ->where('transaction_type', 'rent')
            ->whereMonth('payment_date', $today->month)
            ->whereYear('payment_date', $today->year)
            ->exists();

        if ($isPaid) {
            return [
                'status' => 'Paid',
                'color' => 'bg-green-500',
                'textColor' => 'text-green-600',
                'dueDate' => $dueDate->format('M j, Y')
            ];
        }

        if ($today->gt($dueDate)) {
            return [
                'status' => 'Overdue',
                'color' => 'bg-red-500',
                'textColor' => 'text-red-600',
                'dueDate' => $dueDate->format('M j, Y')
            ];
        }

        return [
            'status' => 'Due Soon',
            'color' => 'bg-orange-500',
            'textColor' => 'text-orange-600',
            'dueDate' => $dueDate->format('M j, Y')
        ];
    }

    private function getRecentActivities($tenantId)
    {
        $activities = [];
        
        // Get the tenant's lease IDs
        $leaseIds = Leases::where('user_id', $tenantId)->pluck('lease_id');
        
        // Recent payments - query through leases
        $recentPayments = Payment::whereIn('lease_id', $leaseIds)
            ->with('lease.unit') 
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($recentPayments as $payment) {
            $unitInfo = $payment->lease->unit ? $payment->lease->unit->unit_name . ' #' . $payment->lease->unit->unit_num : 'Unit';
            
            $activities[] = [
                'type' => 'payment',
                'title' => ucfirst($payment->payment_type) . ' Payment - '. ucfirst($payment->transaction_type),
                'description' => $unitInfo . ' • ' . \Carbon\Carbon::parse($payment->payment_date)->format('F Y'),
                'amount_paid' => '₱' . number_format($payment->amount_paid, 2),
                'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y'),
                'time' => $payment->created_at->diffForHumans(),
                'icon' => 'fas fa-credit-card',
                'color' => 'bg-green-100 text-green-600',
                'status' => ucfirst($payment->billing->status),
                'statusColor' => $payment->billing->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            ];
        }
        
        // Recent maintenance requests
        $recentMaintenance = MaintenanceRequest::where('user_id', $tenantId)
            ->with('unit') // Load unit relationship
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($recentMaintenance as $request) {
            $unitInfo = $request->unit ? $request->unit->unit_name . ' #' . $request->unit->unit_num : 'Unit';
            
            $activities[] = [
                'type' => 'maintenance',
                'title' => 'Maintenance: ' . $request->title,
                'description' => $unitInfo . ' • ' . Str::limit($request->description, 40),
                'time' => $request->created_at->diffForHumans(),
                'icon' => 'fas fa-wrench',
                'color' => $request->priority === 'urgent' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600',
                'status' => ucfirst($request->status),
                'statusColor' => match($request->status) {
                    'completed' => 'bg-green-100 text-green-800',
                    'in_progress' => 'bg-blue-100 text-blue-800',
                    default => 'bg-orange-100 text-orange-800'
                }
            ];
        }
        
        // Sort by timestamp and limit to 5 activities
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 5);
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
                    ->where('status', 'activate'); // Changed from 'active' to 'activate'
            })
            ->with('property')
            ->get();

        // Get the current tenant's maintenance requests with billing info
        $maintenanceRequests = MaintenanceRequest::where('user_id', $tenantId)
            ->with(['unit.property', 'assignedStaff', 'billing'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all unpaid bills for this tenant
        $unpaidBills = Billing::whereHas('lease', function($query) use ($tenantId) {
                $query->where('user_id', $tenantId);
            })
            ->where('status', 'pending')
            ->with(['lease.unit.property', 'maintenanceRequest'])
            ->get();

        // Get payment methods
        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'gcash' => 'GCash'
        ];
        
        // Get total outstanding amount
        $totalOutstanding = $unpaidBills->sum('amount');

        return view('tenants.maintenance', compact(
            'userUnits',
            'maintenanceRequests',
            'unpaidBills',
            'paymentMethods',
            'totalOutstanding'
        ));
    }

    public function makePayment(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => 'required|exists:billings,bill_id',
            'payment_method' => 'required|string|in:bank_transfer,gcash', // Keep UI validation
            'amount_paid' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string',
            'payment_date' => 'required|date'
        ]);

        // Check if bill belongs to the tenant
        $bill = Billing::with('lease')->findOrFail($validated['bill_id']);
        
        if ($bill->lease->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized payment attempt'
            ]);
        }

        // Map UI values to database values
        $dbPaymentMethod = $validated['payment_method'];
        if ($validated['payment_method'] === 'gcash') {
            $dbPaymentMethod = 'e-cash'; // Map GCash to e-cash
        } elseif ($validated['payment_method'] === 'bank_transfer') {
            $dbPaymentMethod = 'bank'; // Map bank_transfer to bank
        }

        // Create payment record
        $payment = Payment::create([
            'bill_id' => $bill->bill_id,
            'lease_id' => $bill->lease_id,
            'payment_method' => $dbPaymentMethod, // Use database values
            'amount_paid' => $validated['amount_paid'],
            'reference_no' => $validated['reference_number'],
            'payment_date' => $validated['payment_date'],
            'transaction_type' => 'Maintenance'
        ]);

        // Update billing status
        $bill->update([
            'status' => 'paid'
        ]);

        // Update related maintenance request if exists
        if ($bill->maintenanceRequest) {
            $bill->maintenanceRequest->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment successful!',
            'payment' => $payment
        ]);
    }
    // In TenantsController.php
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'unit_id' => 'required|exists:property_units,unit_id',
            'preferred_date' => 'nullable|date|after_or_equal:today'
        ]);

        try {
            $maintenanceRequest = new MaintenanceRequest();
            $autoPriority = $maintenanceRequest->determinePriority(
                $request->title, 
                $request->description
            );
            $maintenanceRequest = MaintenanceRequest::create([
                'user_id' => Auth::id(),
                'unit_id' => $request->unit_id,
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $autoPriority, 
                'status' => 'pending',
                'preferred_date' => $request->preferred_date
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance request submitted successfully!',
                'request' => $maintenanceRequest,
                'auto_priority' => $autoPriority 
            ]);
        } catch (\Exception $e) {
            Log::error('Error submitting maintenance request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit maintenance request. Please try again.'
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
        $user = Auth::user();
        $activeLeaseIds = Leases::where('user_id', $user->user_id)
            ->where('status', 'activate')
            ->pluck('lease_id');
        $propertyIds = PropertyUnits::whereIn('unit_id', function($query) use ($activeLeaseIds) {
            $query->select('unit_id')
                ->from('leases')
                ->whereIn('lease_id', $activeLeaseIds);
        })->pluck('prop_id');
        
        $smartDevices = SmartDevice::whereIn('prop_id', $propertyIds)
            ->where('connection_status', 'online') // Only online devices
            ->with(['property' => function($query) {
                $query->select('prop_id', 'property_name');
            }])
            ->get()
            ->map(function($device) {
                return (object) [
                    'device_id' => $device->device_id,
                    'device_name' => $device->device_name,
                    'device_type' => $device->device_type,
                    'connection_status' => $device->connection_status,
                    'power_status' => $device->power_status,
                    'battery_level' => $device->battery_level,
                    'model' => $device->model,
                    'property_name' => $device->property->property_name ?? 'Unknown Property'
                ];
            });
        
        // Calculate stats based on online devices only
        $totalDevices = $smartDevices->count();
        $onlineDevices = $smartDevices->where('connection_status', 'online')->count();
        $activeDevices = $smartDevices->where('power_status', 'on')->count();
        $lowBatteryDevices = $smartDevices->where('battery_level', '<', 20)->count();
        
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
            ->whereIn('status', ['activate', 'pending', 'approved'])
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
    public function getRequestDetails($id)
    {
        try {
            $request = MaintenanceRequest::with([
                'unit.property',
                'assignedStaff',
                'billing'
            ])->where('user_id', Auth::id())
            ->where('request_id', $id)
            ->firstOrFail();

            return response()->json([
                'success' => true,
                'request' => $request
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching maintenance request details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load request details.'
            ], 404);
        }
    }
}