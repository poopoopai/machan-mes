<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
