<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\MainProgramRepository;

set_time_limit(0);
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
        #$parmas = request()->only('id','orderno','status','code','date','time');
        $parme = $this->ResRepo->data();
        foreach($parme as $parmas) {
            // dd($parmas->id);
            $machine = $this->ResRepo->machine($parmas);
           
             //   dd($machine); //string
            $count = $this->SumRepo->counts($parmas,$machine);
            //    dd($count); //collection
            // dd($parmas["status_id"]);
            $description = $this->MainRepo->description($parmas);
            
            // dd($description);
                // dd($description); //collection
            $status = $this->ResRepo->abnormal($parmas,$description);
            //  dd($status);
            $description->abnormal = $status;
            //   dd($status); //string
            
            $restart = $this->SumRepo->restart($parmas,$count);
            //  dd($restart);//collection

            $machineT = $this->SumRepo->machineT($parmas,$count,$machine);
            //    dd($machineT);//collection
            $actual = $this->SumRepo->actual($parmas,$count,$machine);
            //
            $calculate = $this->SumRepo->calculate($parmas,$machineT);
            // dd($calculate);//collection
            $standard = $this->SumRepo->standard($parmas,$calculate);
            // dd($standard);//collection
            $message = $this->ResRepo->message($parmas,$description);
            //   dd($message); //string
            $completion = $this->ResRepo->completion($parmas,$message,$machine);
            //   dd($completion); //string
        
            $description->machine = $machine;
            $description->message_status = $message;
            $description->completion_status = $completion;
            
            
            $break = $this->SumRepo->break($parmas,$standard,$description);
            // dd($break);
            $worktime = $this->SumRepo->worktime($parmas,$break);
            // dd($worktime);
            $manufacturing = $this->SumRepo->manufacturing($parmas,$worktime,$description);
             // dd($manufacturing);
            $downtime = $this->SumRepo->downtime($parmas,$worktime);
             // dd($downtime);
            $breaktime = $this->SumRepo->breaktime($parmas,$downtime);
             
            $refue_time = $this->SumRepo->refue_time($parmas,$breaktime);
            // dd($refue_time);
           
            // dd($total);
            $refueling = $this->SumRepo->refueling($refue_time);

            // dd($total);
            $sum = $description->toArray();//把collection 轉陣列
            
            $refue_time->resources_id = $parmas->id;
           
            $sum1= $refueling->toArray();
            
            //    dd($sum,$sum1);
           
            $status2 = array_merge($sum1,$sum);
            

            $show = $this->SumRepo->create($status2);

            $check = $this->SumRepo->check($parmas);
            if($check){
                $flag = $this->ResRepo->updateflag($parmas);
            }else{
                dd($check);
            }
            
      
            $total = $this->SumRepo->total($show);
       
            $updat = $this->SumRepo->update($total->toArray());

            
        }
       return response()->json(['status' => $updat]);
        
        
          
    }
   
}
