<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BreakTimeController extends Controller
{
    public function index() 
    {
        return view('system/breaktime');
    }

    public function edit()
    {
        return view('system/edit/editbreaktime');
    }
}
