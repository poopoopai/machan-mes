<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class ApsProcessCodeController extends Controller
{
    public function index() 
    {
        return view('system/apsprocesscode');
    }

    public function edit()
    {
        return view('system/edit/editapsprocesscode');
    }
}
