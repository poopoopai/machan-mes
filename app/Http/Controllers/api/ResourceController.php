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
            $count = $this->SumRepo->counts($parmas);
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

            $machineT = $this->SumRepo->machineT($parmas,$count);
            //    dd($machineT);//collection
        
            $calculate = $this->SumRepo->calculate($parmas,$machineT);
            // dd($calculate);//collection
            $standard = $this->SumRepo->standard($parmas,$calculate);
            // dd($standard);//collection
            $message = $this->ResRepo->message($parmas,$description);
            //   dd($message); //string
            $completion = $this->ResRepo->completion($parmas,$message);
            //   dd($completion); //string
        
            
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
            $total = $this->SumRepo->total($parmas,$refue_time);
            // dd($total);


            // dd($total);
            $sum = $description->toArray();//把collection 轉陣列
            $total->resources_id = $parmas->id;
            $sum1= $total->toArray();
            
            //    dd($sum,$sum1);
           
            $status2 = array_merge($sum1,$sum);
            //   dd($status2);
            // dd($parmas);
            // dd($status2);
            echo $status2['resources_id'];
            // dd($status2);
            $show = $this->SumRepo->create($status2);

            $check = $this->SumRepo->check($parmas);
            if($check){
                $flag = $this->ResRepo->updateflag($parmas);
            }else{
                dd($check);
            }
            $refueling = $this->SumRepo->refueling($status2);
            // dd($refueling);
            
            // dd($changerefueling);//collection
            
            $show2 = $this->SumRepo->update($refueling);
            // dd($parmas,$changerefueling);
        }

        return response()->json(['status' => $show]);
          
    }
   
}
