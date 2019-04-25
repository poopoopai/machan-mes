<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\MainProgramRepository;

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
        $parmas = request()->only('id','orderno','status','code','date','time');
        
        $description = $this->MainRepo->description($parmas);
        
        //  dd($description); collection
        $status = $this->ResRepo->abnormal($parmas,$description);
        $description->abnormal = $status;
        //  dd($status); //string
        $count = $this->SumRepo->counts($parmas);
        //  dd($count); //collection
        $restart = $this->SumRepo->restart($parmas,$count);
        // dd($restart);//collection

        $machineT = $this->SumRepo->machineT($parmas,$count);
        // dd($machineT);//collection
       
        $calculate = $this->SumRepo->calculate($parmas,$machineT);
        // dd($calculate);//collection
        $standard = $this->SumRepo->standard($parmas,$calculate);
        // dd($standard);//collection
        $message = $this->ResRepo->message($parmas,$description);
         //  dd($message); //string
        $completion = $this->ResRepo->completion($parmas,$message);
         //  dd($completion); //string
       
        
        $description->message_status = $message;
        $description->completion_status = $completion;

        
        $breaktime = $this->SumRepo->breaktime($parmas,$standard,$description);
        $worktime = $this->SumRepo->worktime($parmas,$breaktime);
        

        $sum = $description->toArray();//把collection 轉陣列
        $sum1= $worktime->toArray();
        //  $parmas = Resource::with('status')->first();
        $status2 = array_merge($sum,$sum1);
       
        $show = $this->SumRepo->create($status2);






        $refueling = $this->SumRepo->refueling($machineT);
        $changerefueling = $refueling->toArray();
        // dd($refueling);//collection
        $show2 = $this->SumRepo->update($show->id,$changerefueling);

        

        return response()->json(['status' => $show]);
          
    }
   
}
