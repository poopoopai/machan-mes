<?php

namespace App\Http\Controllers\Web;

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
