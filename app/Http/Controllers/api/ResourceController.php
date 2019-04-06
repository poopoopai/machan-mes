<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\Resource;
use App\Http\Repositories\ResourceRepository;

class ResourceController extends Controller
{
    protected $ResourceRepo;

    public function __construct(ResourceRepository $ResourceRepo)
    {
        $this->ResourceRepo = $ResourceRepo;
    }

    public function show()
    {
          $parmas = request()->only('orderno','status','code');
       
        $data = $this->ResourceRepo->store($parmas);
          
        //  $parmas = Resource::with('status')->first();

    //    dd($data);

        return response()->json(['data'=>$data]);
    }
}
