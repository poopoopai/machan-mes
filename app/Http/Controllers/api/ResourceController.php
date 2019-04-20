<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\SummaryRepository;
use App\Http\Repositories\ResourceRepository;
use App\Http\Repositories\MainProgramRepository;

class ResourceController extends Controller
{
    protected $ResourceRepo;
    protected $MainProgramRepo;
    protected $SummaryRepo;

    public function __construct(ResourceRepository $ResourceRepo,MainProgramRepository $MainProgramRepo,SummaryRepository $SummaryRepo)
    {
        $this->ResRepo = $ResourceRepo;
        $this->MainRepo = $MainProgramRepo;
        $this->SumRepo = $SummaryRepo;
    }

    public function show()
    {
        $parmas = request()->only('id','orderno','status','code');

        $description = $this->MainRepo->description($parmas);
        //  dd($description); collection
        $status = $this->ResRepo->abnormal($parmas,$description);
        //  dd($status); string
        $count = $this->SumRepo->counts($parmas);
        // dd($count); //arrary

        $message = $this->ResRepo->message($parmas,$status);
        

        $description->abnormal = $status;
        
        $sum = $description->toArray();//把collection 轉陣列
    
        //  $parmas = Resource::with('status')->first();
        $status2 = array_merge($sum,$count);
        
        $show = $this->SumRepo->create($status2);

        

        return response()->json(['status' => $show]);
          
    }
}
