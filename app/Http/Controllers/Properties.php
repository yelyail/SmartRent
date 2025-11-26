<?php

namespace App\Http\Controllers;

use App\Models\PropertyUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\SmartDevice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Properties extends Controller
{
    public function index()
    {
        $properties = Property::withCount([
            'units as units_count',
            'units as occupied_units' => function($query) {
                $query->where('status', 'occupied');
            }
        ])->where('user_id', Auth::id())->get();

        return view('landlords.properties', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'property_address' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'property_price' => 'required|numeric|min:0',
            'property_description' => 'required|string',
            'property_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'devices' => 'sometimes|array',
            'devices.*.device_name' => 'required|string|max:255',
            'devices.*.device_type' => 'required|string|max:255',
            'devices.*.model' => 'nullable|string|max:255',
            'devices.*.serial_number' => 'nullable|string|max:255',
            'devices.*.connection_status' => 'required|in:online,offline',
            'devices.*.power_status' => 'required|in:on,off',
            'devices.*.battery_level' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            Log::info('Property: ' . json_encode($validated));
            DB::transaction(function () use ($validated, $request) {
                // Handle image upload
                if ($request->hasFile('property_image')) {
                    $imagePath = $request->file('property_image')->store('property_images', 'public');
                    $validated['property_image'] = $imagePath;
                }

                $validated['user_id'] = Auth::id();

                // Create property
                $property = Property::create($validated);

                // Create smart devices if any
                if (isset($validated['devices'])) {
                    foreach ($validated['devices'] as $deviceData) {
                        $deviceData['prop_id'] = $property->prop_id;
                        SmartDevice::create($deviceData);
                    }
                }
            });

            return redirect()->route('landlords.properties')->with('success', 'Property and devices added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding property: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $property = Property::with(['units', 'smartDevices'])
            ->withCount([
                'units as units_count',
                'units as occupied_units' => function($query) {
                    $query->where('status', 'occupied');
                },
                'smartDevices as devices_count',
                'smartDevices as online_devices' => function($query) {
                    $query->where('connection_status', 'online');
                }
            ])
            ->findOrFail($id);

        return response()->json($property);
    }

    /**
     * Store a newly created unit for a property.
     */
    public function storeUnit(Request $request, $propertyId)
    {
        Log::info('Store unit request received', [
            'property_id' => $propertyId,
            'data' => $request->all()
        ]);

        $validated = $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_num' => 'required|integer',
            'unit_type' => 'required|string|max:255',
            'area_sqm' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved,rented,unavailable',
        ]);

        try {
            $validated['prop_id'] = $propertyId;

            $unit = PropertyUnits::create($validated);

            Log::info('Unit created successfully', ['unit_id' => $unit->unit_id]);

            return response()->json([
                'success' => true,
                'message' => 'Unit added successfully!',
                'unit' => $unit
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating unit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding unit: ' . $e->getMessage()
            ], 500);
        }
    }

     public function editUnit($unitId)
    {
        $unit = PropertyUnits::findOrFail($unitId);
        return response()->json($unit);
    }
     public function updateUnit(Request $request, $unitId)
    {
        $unit = PropertyUnits::findOrFail($unitId);

        $validated = $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_num' => 'required|integer',
            'unit_type' => 'required|string|max:255',
            'area_sqm' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved,rented,unavailable,inactive',
        ]);

        $unit->update($validated);

        return response()->json(['success' => 'Unit updated successfully!']);
    }
    public function getUnits($propertyId)
    {
        $units = PropertyUnits::where('prop_id', $propertyId)->get();
        return response()->json($units);
    }
    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return response()->json($property);
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'property_address' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'property_price' => 'required|numeric|min:0',
            'property_description' => 'required|string',
            'property_image' => 'sometimes|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('property_image')) {
            $imagePath = $request->file('property_image')->store('property_images', 'public');
            $validated['property_image'] = $imagePath;
        }

        $property->update($validated);

        // Fix the redirect route
        return redirect()->route('landlords.properties')->with('success', 'Property updated successfully!');
    }
    public function archive($unitId)
    {
        $unit = PropertyUnits::findOrFail($unitId);
            $unit->update(['status' => 'inactive']);

            return response()->json(['success' => 'Unit deactivated successfully!']);
    }

    public function getSmartDevices($propertyId)
    {
        $devices = SmartDevice::where('prop_id', $propertyId)->get();
        return response()->json($devices);
    }
     public function createDevice($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        return response()->json($property);
    }

    /**
     * Store a newly created device for a property.
     */
    public function storeDevice(Request $request, $propertyId)
    {
        Log::info('Store device request received', [
            'property_id' => $propertyId,
            'data' => $request->all()
        ]);

        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'connection_status' => 'required|in:online,offline',
            'power_status' => 'required|in:on,off',
            'battery_level' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $validated['prop_id'] = $propertyId;

            $device = SmartDevice::create($validated);

            Log::info('Device created successfully', ['device_id' => $device->device_id]);

            return response()->json([
                'success' => true,
                'message' => 'Device added successfully!',
                'device' => $device
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating device: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding device: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified device.
     */
    public function editDevice($deviceId)
    {
        $device = SmartDevice::with('property')->findOrFail($deviceId);
        return response()->json($device);
    }

    /**
     * Update the specified device in storage.
     */
    public function updateDevice(Request $request, $deviceId)
    {
        $device = SmartDevice::findOrFail($deviceId);

        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'connection_status' => 'required|in:online,offline',
            'power_status' => 'required|in:on,off',
            'battery_level' => 'nullable|integer|min:0|max:100',
        ]);

        $device->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Device updated successfully!',
            'device' => $device
        ]);
    }

    /**
     * Remove the specified device from storage.
     */
    public function destroyDevice($deviceId)
    {
        try {
            $device = SmartDevice::findOrFail($deviceId);
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Device deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting device: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting device: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive the specified device.
     */
    public function archiveDevice($deviceId)
    {
        try {
            $device = SmartDevice::findOrFail($deviceId);
            $device->update(['status' => 'archived']);

            return response()->json([
                'success' => true,
                'message' => 'Device archived successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error archiving device: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error archiving device: ' . $e->getMessage()
            ], 500);
        }
    }
}