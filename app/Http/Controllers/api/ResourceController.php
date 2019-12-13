<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\MachinePerformanceRepository;
use App\Repositories\RollerDataRepository;
use App\Services\MachinePerformanceService;
set_time_limit(0);
class ResourceController extends Controller
{
    protected $machinePerformanceRepo;
    protected $rollerDataRepo;
   
    public function __construct(MachinePerformanceRepository $machinePerformanceRepo, MachinePerformanceService $machService,
                                RollerDataRepository $rollerDataRepo)
    {
        $this->machinePerformanceRepo = $machinePerformanceRepo;
        $this->machService = $machService;
        $this->rollerDataRepo = $rollerDataRepo;
    }

    public function show()
    {
        $data = $this->machinePerformanceRepo->index();
        
        return view('machineperformance', ['datas' => $data]);
    }
    public function searchdate()
    {
        $datas = $this->machinePerformanceRepo->searchdate(request()->date);
        
        if($datas[0]){
            return view('searchmachineperformance', ['datas' => $datas]);
        }
        return redirect()->route('show_machineperformance');
    }

    public function fixmachinedatabase()
    { 
        $parme = $this->rollerDataRepo->data();

        foreach ($parme as $parmas) {
            
            $status = $this->machService->refueling($parmas);
            
            $this->machinePerformanceRepo->create($status->toArray());
            
            $this->machService->updateflag($parmas);

            $updat = $this->machService->update($status);   
        }
        return response()->json(['status' => $updat]);
    }
}
