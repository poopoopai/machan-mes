<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QualityController extends Controller
{
    public function index() 
    {
        return view('Uptime/quality');
    }

    public function edit()
    {
        return view('Uptime/edit/editquality');
    }
}
