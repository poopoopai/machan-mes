<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class MachineDefinitionController extends Controller
{
    public function index() 
    {
        return view('system/machinedefinition');
    }

    public function edit()
    {
        return view('system/edit/editmachinedefinition');
    }
}
