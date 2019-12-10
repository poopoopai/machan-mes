<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\SummaryRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\MainProgramRepository;
use App\Services\MachinePerformanceService;
use App\Services\RollerDataService;

set_time_limit(0);
class ResourceController extends Controller
{

    protected $ResourceRepo;
    protected $MainProgramRepo;
    protected $SummaryRepo;
    protected $MaPerformanceRepo;
    protected $rollerDataService;
    public function __construct(ResourceRepository $ResourceRepo, MainProgramRepository $MainProgramRepo,
                                SummaryRepository $SummaryRepo, MachinePerformanceService $machinePerformanceService
                                , RollerDataService $rollerDataService)
    {
        $this->ResRepo = $ResourceRepo;
        $this->MainRepo = $MainProgramRepo;
        $this->SumRepo = $SummaryRepo;
        $this->MaPerformanceRepo = $machinePerformanceService;
        $this->rollerDataService = $rollerDataService;
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

            $machineT = $this->SumRepo->machineT($parmas, $restart, $machine);//1
 
            $actual = $this->SumRepo->actual($parmas, $count, $machineT);//

            $calculate = $this->SumRepo->calculate($parmas, $actual);
          
            $standard = $this->SumRepo->standard($parmas, $calculate);
           
            $message = $this->ResRepo->message($parmas, $description);//2
        
            $completion = $this->ResRepo->completion($parmas, $message, $machine);

            $description->machine = $machine;// 1
            $description->message_status = $message;//2
            $description->completion_status = $completion;

            $break = $this->SumRepo->break($parmas, $standard, $description);
        
            $worktime = $this->SumRepo->worktime($parmas, $break);
      
            $manufacturing = $this->SumRepo->manufacturing($parmas, $worktime, $description);//
          
            $downtime = $this->SumRepo->downtime($parmas, $manufacturing);
       
            $breaktime = $this->SumRepo->breaktime($downtime);

            $refue_time = $this->SumRepo->refue_time($parmas, $breaktime);
           
            $refueling = $this->SumRepo->refueling($refue_time);

            $sum = $description->toArray(); //把collection 轉陣列

            $refue_time->resources_id = $parmas->id;

            $sum1 = $refueling->toArray();

            $status2 = array_merge($sum1, $sum);

            $show = $this->SumRepo->create($status2);

            $check = $this->SumRepo->check($parmas);
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

            $this->MaPerformanceRepo->machineT($parmas);

            $this->rollerDataService->message($parmas);
        }
    }
}
