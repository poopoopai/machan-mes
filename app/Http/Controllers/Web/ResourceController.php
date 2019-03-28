<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Entities\Resource;

class ResourceController extends Controller
{
    public function index() 
    {
        dd(Resource::where('id',153455)->get());
    }
}
