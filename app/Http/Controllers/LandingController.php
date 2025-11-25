<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.homepage');
    }

    public function about()
    {
        return view('landing.about');
    }

    public function pricing()
    {
        return view('landing.pricing');
    }
    public function contact()
    {
        return view('landing.contact');
    }
}
