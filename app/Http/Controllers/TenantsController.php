<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('tenants.propAssets');
    }
    public function payment()
    {
        return view('tenants.payment');
    }
    public function properties()
    {
        return view('tenants.properties');
    }
    public function userManagement()
    {
        return view('tenants.userManagement');
    }
}
