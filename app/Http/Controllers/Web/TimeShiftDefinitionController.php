<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeShiftDefinitionController extends Controller
{
    public function index() 
    {
        return view('Uptime/timeshiftdefinition');
    }

    public function edit()
    {
        return view('Uptime/edit/edittimeshiftdefinition');
    }
}
