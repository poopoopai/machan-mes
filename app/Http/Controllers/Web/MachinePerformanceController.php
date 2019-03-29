<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
