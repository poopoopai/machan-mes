<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerformanceController extends Controller
{
    public function index() 
    {
        return view('Uptime/performance');
    }

    public function edit()
    {
        return view('Uptime/edit/editperformance');
    }
}
