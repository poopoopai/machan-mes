<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
