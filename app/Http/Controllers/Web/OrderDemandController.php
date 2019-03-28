<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

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
