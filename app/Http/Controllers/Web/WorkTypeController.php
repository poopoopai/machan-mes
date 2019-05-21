<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Entities\SetupShift;
use App\Http\Controllers\Controller;

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
