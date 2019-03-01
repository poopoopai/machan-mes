<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\SetupShift;

class WorkTypeController extends Controller
{
    public function index() 
    {
        return view('system/worktype');
    }

    public function edit()
    {
        return view('system/edit/editworktype');
    }

}
