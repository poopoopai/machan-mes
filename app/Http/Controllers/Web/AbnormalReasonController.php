<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class AbnormalReasonController extends Controller
{
    public function index() 
    {
        return view('system/abnormalreason');
    }

    public function edit()
    {
        return view('system/edit/editabnormalreason');
    }
}
