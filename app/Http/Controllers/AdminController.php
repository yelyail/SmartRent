<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admins.dashboard');
    }
    public function bill()
    {
        return view('admins.bill');
    }
    public function analytics()
    {
        return view('admins.analytics');
    }
    public function maintenance()
    {
        return view('admins.maintenance');
    }
    public function propAssets()
    {
        return view('admins.propAssets');
    }
    public function userManagement()
    {
        return view('admins.userManagement');
    }
    public function properties()
    {
        return view('admins.properties');
    }
    public function payment()
    {
        return view('admins.payment');
    }
}
