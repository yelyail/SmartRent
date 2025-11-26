<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;
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
        return view('landlords.propAssets');
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