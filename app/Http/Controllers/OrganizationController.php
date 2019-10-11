<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Organization;

class OrganizationController extends Controller
{
    public function getOrganization()
    {
        return Organization::get();
    }
}
