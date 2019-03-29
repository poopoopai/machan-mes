<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderLoadController extends Controller
{
    public function index() 
    {
        return view('management/orderload');
    }

    public function edit()
    {
        return view('management/editorderload');
    }
}
