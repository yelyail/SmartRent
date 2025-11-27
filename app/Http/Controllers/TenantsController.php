<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;    
use App\Models\Leases;
use App\Models\SmartDevice;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyUnits;
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
        return view('tenants.analytics');
    }
    
    public function maintenance()
    {
        return view('tenants.maintenance');
    }
    
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
    public function payDeposit(Request $request, $leaseId)
    {
        $lease = Leases::where('user_id', Auth::id())
            ->where('lease_id', $leaseId)
            ->firstOrFail();

        if ($lease->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Lease must be approved by landlord before paying deposit'
            ], 400);
        }

        if ($lease->deposit_paid) {
            return response()->json([
                'success' => false,
                'message' => 'Deposit already paid for this lease'
            ], 400);
        }

        try {
            DB::beginTransaction();
            
            $paymentMethodMap = [
                'bank_transfer' => 'bank',
                'gcash' => 'e-cash'
            ];

            $paymentMethod = $paymentMethodMap[$request->payment_method] ?? 'cash';

            // Generate reference number based on payment method
            $referenceNo = $this->generateReferenceNumber($request->payment_method, $request->reference_number);

            // Update lease deposit status
            $lease->update([
                'deposit_paid' => true,
                'deposit_paid_at' => now(),
                'deposit_amount' => $lease->deposit_amount,
                'status' => 'active'
            ]);

            // Create billing record for deposit
            $billing = Billing::create([
                'lease_id' => $lease->lease_id,
                'bill_name' => 'Security Deposit - ' . $lease->property->property_name,
                'bill_period' => 'One-time',
                'due_date' => now()->format('Y-m-d'),
                'late_fee' => 0.00,
                'overdue_amount_percent' => 0,
                'amount' => $lease->deposit_amount,
                'status' => 'partial',
            ]);

            // Create payment record for the deposit
            $payment = Payment::create([
                'bill_id' => $billing->bill_id,
                'lease_id' => $lease->lease_id,
                'amount_paid' => $lease->deposit_amount,
                'payment_date' => now(),
                'payment_method' => $paymentMethod,
                'reference_no' => $referenceNo,
            ]);

            // Create invoice for the deposit
            $invoice = Invoices::create([
                'lease_id' => $lease->lease_id,
                'invoice_no' => 'INV-DEP-' . now()->format('YmdHis'),
                'subtotal' => $lease->deposit_amount,
                'late_fees' => 0.00,
                'other_charges' => 0.00,
                'total_amount' => $lease->deposit_amount,
                'status' => 'partial',
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => now()->format('Y-m-d'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit paid successfully! Your lease is now active.',
                'lease' => $lease
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deposit payment failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process deposit payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate reference number based on payment method
     */
    private function generateReferenceNumber($paymentMethod, $userReference = null)
    {
        if ($paymentMethod === 'cash') {
            // For cash payments, generate sequential numbers starting from 1000
            $lastCashPayment = Payment::where('payment_method', 'cash')
                ->where('reference_no', 'like', 'CASH-%')
                ->orderBy('payment_id', 'desc')
                ->first();
            
            $lastNumber = 1000;
            if ($lastCashPayment && preg_match('/CASH-(\d+)/', $lastCashPayment->reference_no, $matches)) {
                $lastNumber = (int)$matches[1] + 1;
            }
            
            return 'CASH-' . $lastNumber;
        }
        
        if ($paymentMethod === 'gcash') {
            return 'GCASH-' . ($userReference ?: now()->format('YmdHis'));
        }
        
        if ($paymentMethod === 'bank_transfer') {
            return 'BANK-' . ($userReference ?: now()->format('YmdHis'));
        }
        
        return 'DEP-' . now()->format('YmdHis');
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