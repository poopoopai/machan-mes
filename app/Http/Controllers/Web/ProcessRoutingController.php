<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

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
