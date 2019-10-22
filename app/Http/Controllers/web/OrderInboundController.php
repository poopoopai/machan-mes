<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
