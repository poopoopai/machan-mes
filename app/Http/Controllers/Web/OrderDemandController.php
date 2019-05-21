<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderDemandController extends Controller
{
    public function index() 
    {
        return view('management/orderdemand');
    }

    public function edit()
    {
        return view('management/editorderdemand');
    }
}
