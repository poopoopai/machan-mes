<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MachineCategoryController extends Controller
{

    public function index() 
    {
        return view('system/machinecategory');
    }
    public function edit()
    {
        return view('system/edit/editmachinecategory');
    }
    public function store(request $request)
    {
        dd($request->all());
    }
}
