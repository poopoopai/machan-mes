<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderInboundController extends Controller
{
    public function index() 
    {
        return view('management/orderinbound');
    }

    public function edit()
    {
        return view('management/editorderinbound');
    }
}
