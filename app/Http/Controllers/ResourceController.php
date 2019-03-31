<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Resource;

class ResourceController extends Controller
{
    public function index() 
    {
        dd(Resource::find(153455)->first());
    }
    public function destroy($id)
    {
        $this->resRepo->destroy($id);

        return redirect()->route('resource.index');
    }
}
