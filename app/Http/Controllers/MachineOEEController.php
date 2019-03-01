<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MachineOEEController extends Controller
{
    public function index() 
    {
        return view('Uptime/machineoee');
    }

    public function edit()
    {
        return view('Uptime/edit/editmachineoee');
    }
}
