<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessingTimeController extends Controller
{
    public function index() 
    {
        return view('system/processingtime');
    }
    public function result() 
    {
        return view('system/processingtimeresult');
    }
    public function edit()
    {
        return view('system/edit/editprocessingtime');
    }
}
