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

class LandlordController extends Controller
{
    public function index()
    {
        return view('landlords.dashboard');
    }

    public function bill()
    {
        return view('landlords.bill');
    }

    public function analytics()
    {
        return view('landlords.analytics'); 
    }

    public function maintenance()
    {
        return view('landlords.maintenance');
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
        $properties = Property::withCount([
            'units as units_count',
            'units as occupied_units' => function($query) {
                $query->where('status', 'occupied');
            },
            'smartDevices as devices_count',
            'smartDevices as online_devices' => function($query) {
                $query->where('connection_status', 'online');
            }
        ])->where('user_id', Auth::id())->get();

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