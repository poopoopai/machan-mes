<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExclusionReasonController extends Controller
{
    public function index() 
    {
        return view('system/exclusionreason');
    }

    public function edit()
    {
        return view('system/edit/editexclusionreason');
    }
}
