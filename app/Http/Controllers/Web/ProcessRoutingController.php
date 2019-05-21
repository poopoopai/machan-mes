<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessRoutingController extends Controller
{
    public function index() 
    {
        return view('system/processrouting');
    }

    public function edit()
    {
        return view('system/edit/editprocessrouting');
    }

}
