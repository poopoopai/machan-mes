<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MachinePerformanceController extends Controller
{
    public function index() 
    {
        return view('management/machineperformance');
    }

    public function edit()
    {
        return view('management/editmachineperformance');
    }
}
