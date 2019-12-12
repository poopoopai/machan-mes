<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\MachinePerformanceRepository;
use App\Repositories\MainProgramRepository;
use App\Services\MachinePerformanceService;

set_time_limit(0);
class ResourceController extends Controller
{

    protected $ResourceRepo;
    protected $MainProgramRepo;
    protected $machinePerformanceRepo;
    protected $SummaryRepo;
   
    public function __construct(ResourceRepository $ResourceRepo, MainProgramRepository $MainProgramRepo,
                                SummaryRepository $SummaryRepo, MachinePerformanceRepository $machinePerformanceRepo,
                                 MachinePerformanceService $machService)
    {
        $this->ResRepo = $ResourceRepo;
        $this->MainRepo = $MainProgramRepo;
        $this->SumRepo = $SummaryRepo;
        $this->machinePerformanceRepo = $machinePerformanceRepo;
        $this->machService = $machService;
    }

    public function show()
    {
        $data = $this->SumRepo->index();
        
        return view('machineperformance', ['datas' => $data]);
    }


    public function searchdate()
    {
        $datas = $this->SumRepo->searchdate(request()->date);
        
        if($datas[0]){
            return view('searchmachineperformance', ['datas' => $datas]);
        }
        return redirect()->route('show_machineperformance');
    }


    public function getmachinedatabase()
    {
        #$parmas = request()->only('id','orderno','status','code','date','time');
        $parme = $this->ResRepo->data();
        foreach ($parme as $parmas) {
            
            $machine = $this->ResRepo->machine($parmas);//1 machine name

            $count = $this->SumRepo->counts($parmas, $machine);//1

            $description = $this->MainRepo->description($parmas);//2

            $status = $this->ResRepo->abnormal($parmas, $description);//2
         
            $description->abnormal = $status;//2

            $restart = $this->SumRepo->restart($parmas, $count);//1

            $machineT = $this->SumRepo->machineT($parmas, $restart, $machine);//1.1
 
            $actual = $this->SumRepo->actual($parmas, $count, $machineT);//1.1

            $calculate = $this->SumRepo->calculate($parmas, $actual);//1.2
          
            $standard = $this->SumRepo->standard($parmas, $calculate);//1.2
           
            $message = $this->ResRepo->message($parmas, $description);//2
        
            $completion = $this->ResRepo->completion($parmas, $message, $machine);//2

            $description->machine = $machine;// 1
            $description->message_status = $message;//2
            $description->completion_status = $completion;//2

            $break = $this->SumRepo->break($parmas, $standard, $description);//2 1 這裡合併
        
            $worktime = $this->SumRepo->worktime($parmas, $break);//1
      
            $manufacturing = $this->SumRepo->manufacturing($parmas, $worktime, $description);//1
          
            $downtime = $this->SumRepo->downtime($parmas, $manufacturing);//1
       
            $breaktime = $this->SumRepo->breaktime($downtime);//1

            $refue_time = $this->SumRepo->refue_time($parmas, $breaktime);//1
           
            $refueling = $this->SumRepo->refueling($refue_time);//1

            $sum = $description->toArray(); //把collection 轉陣列 //2

            $refue_time->resources_id = $parmas->id;//1

            $sum1 = $refueling->toArray();//1

            $status2 = array_merge($sum1, $sum);//1
            
            $show = $this->SumRepo->create($status2);// 

            $check = $this->SumRepo->check($parmas);//
          
            if ($check) {
                $this->ResRepo->updateflag($parmas);
            } else {
                dd($check);
            }

            $total = $this->SumRepo->total($show);

            $updat = $this->SumRepo->update($total->toArray());
        }
        return response()->json(['status' => $updat]);
    }

    public function fixmachinedatabase()
    { 
        
        $parme = $this->ResRepo->data();
       
        foreach ($parme as $parmas) {
            
            $status = $this->machService->refueling($parmas);
            
            $this->machinePerformanceRepo->create($status->toArray());
            
            // $this->rollerDataService->updateFlag($parmas);

            
        }
    }
}
