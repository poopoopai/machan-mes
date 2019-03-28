<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index() 
    {
        return view('Uptime/performance');
    }

    public function edit()
    {
        return view('Uptime/edit/editperformance');
    }
}
