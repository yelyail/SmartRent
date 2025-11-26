<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;
use App\Models\SmartDevice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        return view('landlords.userManagement');
    }
}