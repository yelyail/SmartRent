<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admins.dashboard');
    }
    
    public function landlord()
    {
        return view('landlords.dashboard');
    }
    
    public function tenant()
    {
        return view('tenants.dashboard');
    }
    
    public function staff()
    {
        return view('staff.dashboard');
    }
    
    public function index()
    {
        return view('dashboard');
    }
}
