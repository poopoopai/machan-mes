<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
