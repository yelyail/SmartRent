<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('landlords.properties');
    }
    public function userManagement()
    {
        return view('landlords.userManagement');
    }
}
