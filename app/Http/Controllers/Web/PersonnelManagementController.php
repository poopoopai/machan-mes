<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class PersonnelManagementController extends Controller
{
    public function index() 
    {
        return view('account/personnelmanagement');
    }

    public function edit()
    {
        return view('account/editpersonnelmanagement');
    }
}
