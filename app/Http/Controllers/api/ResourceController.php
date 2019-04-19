<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Repositories\ResourceRepository;
use App\Http\Repositories\MainProgramRepository;

class ResourceController extends Controller
{
    protected $ResourceRepo;
    protected $MainProgramRepo;

    public function __construct(ResourceRepository $ResourceRepo,MainProgramRepository $MainProgramRepo)
    {
        $this->ResourceRepo = $ResourceRepo;
        $this->MainRepo = $MainProgramRepo;
    }

    public function show()
    {
        $parmas = request()->only('id','orderno','status','code');

        $description = $this->MainRepo->description($parmas);
        $status = $this->ResourceRepo->abnormal($parmas,$description);
        $count = $this->ResourceRepo->counts($parmas);




        
        $description->abnormal = $status;

        
        //  $parmas = Resource::with('status')->first();
    
       
            return response()->json(['status' => $description]);
        
        
    }
}
