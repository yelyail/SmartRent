<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        return view('staff.dashboard');
    }
    public function bill()
    {
        return view('staff.bill');
    }
    public function analytics()
    {
        return view('staff.analytics');
    }
    public function maintenance()
    {
        return view('staff.maintenance');
    }
    public function propAssets()
    {
        return view('staff.propAssets');
    }
    public function userManagement()
    {
        return view('staff.userManagement');
    }
    public function properties()
    {
        return view('staff.properties');
    }
    public function payment()
    {
        return view('staff.payment');
    }
}
